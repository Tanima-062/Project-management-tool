<?php

namespace App\Http\Controllers\Backend;

use App\Modals\Assignee;
use App\Modals\History;
use App\Http\Controllers\Controller;
use App\Modals\RequestType;
use App\Modals\Team;
use Illuminate\Http\Request;
use App\Modals\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Modals\MemberTask;
use App\Modals\Task;
use App\Modals\TeamTask;
use App\Models\Notification\Notifier;
use App\Http\Controllers\Backend\TasksController;

class RequestController extends Controller
{
    public function requestSave(Request $request, $id){

        $taskController = new TasksController();

        
        $auth_user_id = Auth::user()['id'];
        $auth_user_name = Auth::user()['name'];
        $task = Task::where(['id' => $id])->first();
        $message='';

        $flag = false;

        if($request->reason == 'sick.leave' || $request->reason == 'annual.leave' || $request->reason == 'engage.with.other.priority.tasks'|| (($request->reason == 'others') && ($request->other_reason=='reject'))) {
            $request_type = RequestType::where(['name' => $request->reason])->first();
            $message = 'rejected the task due to '.$request_type->displayName;

            $request_model = new Requests();
            $request_model->task_id = $id;
            $request_model->request_user_id = $auth_user_id;
            $request_model->request_type = $request->reason;
            $request_model->comment = $request->comment;
            $request_model->message = $message;
            $request_model->save();

            if(!empty($task->assignBy->email)){
                $taskController->sendNotification($task->assign_by_id, $task->assignBy->email, Auth::user()['name'], $task, 'taskReject');
            }
            
            MemberTask::where(['task_id' => $id])->where(['member_user_id' => $auth_user_id])->update(['request_status' => 'rejected', 'updated_at' => new \DateTime()]);
            Assignee::where(['task_id' => $id])->where(['user_id' => $auth_user_id])->update(['request_status' => 'rejected','updated_at' => new \DateTime()]);
            
            if($task->assign_to == 'team'){
                $auth_user_as_member = MemberTask::where(['task_id' => $id])->where(['member_user_id' => $auth_user_id])->first();
    
                if($auth_user_as_member) {
                    $auth_user_as_member->task_status = $request->task_status;
                    $auth_user_as_member->updated_at = new \DateTime();
                    $auth_user_as_member->save();
    
                    $members = MemberTask::where('team_id', $auth_user_as_member->team_id)
                                        ->where('task_id', $id)
                                        ->where('request_status', '<>', 'rejected')
                                        ->pluck('task_status')
                                        ->toArray();
                    $members_requestStatus = MemberTask::where('team_id', $auth_user_as_member->team_id)
                                ->where('task_id', $id)
                                ->where('request_status', '<>', 'rejected')
                                ->pluck('request_status')
                                ->toArray();                    
    
                    if(count($members) > 0) {
                        if(in_array(1, $members)) {
                            DB::table('team_tasks')->where(['task_id' => $id])->where(['team_id' => $auth_user_as_member->team_id])->update(array('team_task_status' => 1));
                        } elseif(in_array(0, $members)) {
                            DB::table('team_tasks')->where(['task_id' => $id])->where(['team_id' => $auth_user_as_member->team_id])->update(array('team_task_status' => 0));
                        } else {
                            if(in_array('pending', $members_requestStatus)){
                                DB::table('team_tasks')->where(['task_id' => $id])->where(['team_id' => $auth_user_as_member->team_id])->update(array('team_task_status' => 1));
                            }else{
                                DB::table('team_tasks')->where(['task_id' => $id])->where(['team_id' => $auth_user_as_member->team_id])->update(array('team_task_status' => 2));
                            }
                        }
                    }
                }
                $team_tasks = TeamTask::where(['task_id' => $id])->pluck('team_task_status')->toArray();
    
                if(in_array(1,$team_tasks)){
                    $task->status = 1;
                }elseif(in_array(0,$team_tasks)){
                    $task->status = 0;
                }else{
                    $task->status = 2;
                }
                $task->save();
            }else {
    
                $auth_user_as_assignee = Assignee::where(['task_id' => $id])->where(['user_id' => $auth_user_id])->first();
    
                if ($auth_user_as_assignee) {
                    $auth_user_as_assignee->task_status = 4;
                    $auth_user_as_assignee->updated_at = new \DateTime();
                    $auth_user_as_assignee->save();
                }
    
                $assignees = Assignee::where('task_id', $id)
                                ->where('request_status', '<>', 'rejected')
                                ->groupBy('task_status')
                                ->pluck('task_status')
                                ->toArray();

                if(count($assignees) > 0){                
                    if(in_array(1, $assignees)) {
                        DB::table('tasks')->where(['id' => $id])->update(array('status' => 1));
                    } elseif(in_array(2, $assignees)) {
                        if(in_array(-1, $assignees) || in_array(0, $assignees)){
                            DB::table('tasks')->where(['id' => $id])->update(array('status' => 1));
                        }else{
                            DB::table('tasks')->where(['id' => $id])->update(array('status' => 2));
                        }
                    } elseif(in_array(0, $assignees)){
                        DB::table('tasks')->where(['id' => $id])->update(array('status' => 0));
                    }else{
                        DB::table('tasks')->where(['id' => $id])->update(array('status' => -1));
                    }
                }else{
                    DB::table('tasks')->where(['id' => $id])->update(array('status' => 4));
                }
            }

            $flag = true;
        }

        $history = new History();
        $history->task_id = $id;
        $history->user_id = $auth_user_id;
        $history->request_type = $request->reason;
        $history->comment = $request->comment;

        if($request->reason == 'additional.information'){
            $message = 'requested for additional information';

            if(!empty($task->assignBy->email)){
                $taskController->sendNotification($task->assign_by_id, $task->assignBy->email, Auth::user()['name'], $task, 'taskRequestAdditionalInfo');
            }

        } elseif($request->reason == 'time.extension'){
            $history->extended_date = $request->extended_date;
            $message = 'requested for time extension to ' . $request->extended_date;

            if(!empty($task->assignBy->email)){
                $taskController->sendNotification($task->assign_by_id, $task->assignBy->email, Auth::user()['name'], $task, 'taskRequestTimeExtension');
            }

        } elseif(($request->reason == 'others') && ($request->other_reason=='request')){
            $message = 'requested for other type';

            if(!empty($task->assignBy->email)){
                $taskController->sendNotification($task->assign_by_id, $task->assignBy->email, Auth::user()['name'], $task, 'taskRequestOthers');
            }
        }

        $history->message = $message;
        $history->save();

        if($flag){
            session()->flash('error','Task has been rejected!');
        }else{
            session()->flash('success','Request has send successfully!');
        }

        return redirect()->route('tasks.index');
    }
}
