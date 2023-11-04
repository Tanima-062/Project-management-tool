<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Modals\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\Notification\Notifier;
use App\Modals\Assignee;
use App\Modals\MemberTask;
use App\Modals\TeamTask;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index(){
        if(Auth::user()) {
            $auth_user_id = Auth::user()['id'];

            $assign_to_me_tasks = Task::with(['memberTasks'=> function($q) use ($auth_user_id){
                    $q->where(['member_user_id' => $auth_user_id])
                      ->where(['request_status' => 'accepted'])
                      ->where('task_status', '!=', 2);
                }])->with(['assignees'=> function($q) use ($auth_user_id){
                    $q->where(['user_id' => $auth_user_id])
                      ->where(['request_status' => 'accepted'])
                      ->where('task_status', '!=', 2);
                }])
                ->where('status','!=' , 2)
                ->where('assign_by_id','!=' ,$auth_user_id)
                ->orderBy('end_date','asc')
                ->get();

            $assign_to_me_tasks_in_chart = Task::with(['memberTasks'=> function($q) use ($auth_user_id){
                                        $q->where(['member_user_id' => $auth_user_id])
                                        ->where(['request_status' => 'accepted']);

                                    }])->with(['assignees'=> function($q) use ($auth_user_id){
                                        $q->where(['user_id' => $auth_user_id])
                                        ->where(['request_status' => 'accepted']);
                                    }])
                                    ->where('assign_by_id','!=' ,$auth_user_id)
                                    ->get();
                                   

            $assign_by_me_tasks = Task::with('assignBy','teamTasks','memberTasks','assignees')
                                  ->where('status','!=',2)
                                  ->where(['assign_by_id' => $auth_user_id])
                                  ->orderBy('end_date','asc')
                                  ->get();

            $assign_by_me_taskChart = Task::with('assignBy','teamTasks','memberTasks','assignees')
                                      ->where(['assign_by_id' => $auth_user_id])
                                      ->get();

            $pending_tasks = Task::with(['memberTasks' => function($q) use ($auth_user_id){
                                        $q->where(['member_user_id' => $auth_user_id])
                                        ->where(['request_status' => 'pending']);
                                    }])->with(['assignees' => function($q) use ($auth_user_id){
                                        $q->where(['user_id' => $auth_user_id])
                                        ->where(['request_status' => 'pending']);
                                    }])
                            ->where('assign_by_id', '!=', $auth_user_id)
                            ->orderBy('id','desc')
                            ->get();
                            
            $assign_to_me_todo_task_count = 0;

            $assign_to_me_inProgress_task_count = 0;

            $assign_to_me_completed_task_count = 0;

            $assign_to_me_overdue_task_count = 0;


            foreach ($assign_to_me_tasks as $key => $task){
                if((count($task->memberTasks) == 0 && count($task->assignees) == 0)){
                    unset($assign_to_me_tasks[$key]);
                }
            }
          
            foreach ($assign_to_me_tasks_in_chart as $key => $task){ 
                if((count($task->memberTasks) == 0 && count($task->assignees) == 0)){
                    unset($assign_to_me_tasks_in_chart[$key]);
                }else{
                    foreach($task->assignees as $assignee){
                        if($assignee->request_status == 'accepted'){
                            if((isset($assignee->end_date)) &&  ($assignee->end_date < date('Y-m-d')) && ($assignee->task_status < 2) ){
                                $assign_to_me_overdue_task_count++;   
                            }
                            if($assignee->task_status == 0){
                                $assign_to_me_todo_task_count++;
                            }elseif($assignee->task_status == 1){
                                $assign_to_me_inProgress_task_count++;
                            }elseif($assignee->task_status == 2){
                                $assign_to_me_completed_task_count++;
                            }
                        } 
                    } 
                }  
            }

            $assign_by_me_todo_task_count = 0;

            $assign_by_me_inProgress_task_count = 0;

            $assign_by_me_completed_task_count = 0;

            $assign_by_me_overdue_task_count = 0;

            foreach ($assign_by_me_tasks as $key=>$task){
                if(count($task->teamTasks) == 0 && count($task->memberTasks) == 0 && count($task->assignees) == 0){
                    unset($assign_by_me_tasks[$key]);
                }else{
                    if($task->end_date < date('Y-m-d')){
                        $assign_by_me_overdue_task_count++;   
                    }
                }
            }
            foreach ($assign_by_me_taskChart as $task){
                if(count($task->assignees) > 0){
                
                    if($task->status == 0){
                        $assign_by_me_todo_task_count++;
                    }elseif($task->status == 1){
                        $assign_by_me_inProgress_task_count++;
                    }elseif($task->status == 2){
                        $assign_by_me_completed_task_count++;
                    }
                }
                
            }

            foreach($pending_tasks as $key => $pending_task){
                if(count($pending_task->memberTasks) == 0 && count($pending_task->assignees) == 0){
                    unset($pending_tasks[$key]);
                }
            }

            $rejected_tasks = Task::join('assignees','assignees.task_id','=','tasks.id')
                            ->join('users','users.id','=','assignees.user_id')
                            ->where('assignees.user_id', '!=', $auth_user_id)
                            ->where(['assignees.request_status' => 'rejected'])
                            ->where(['tasks.assign_by_id' => $auth_user_id])
                            ->select('assignees.updated_at AS updated_at', 'tasks.title','tasks.id AS taskId','users.id AS user_id','users.name')
                            ->orderBy('assignees.updated_at', 'DESC')
                            ->get();

            $notifications = Notifier::getNotifier(Auth::user()['id']);
            
            return view('backend.dashboard',
                compact('assign_to_me_tasks','assign_by_me_tasks','pending_tasks','rejected_tasks', 'notifications',
                    'assign_to_me_todo_task_count','assign_to_me_inProgress_task_count','assign_to_me_completed_task_count',
                    'assign_to_me_overdue_task_count', 'assign_by_me_overdue_task_count',
                    'assign_by_me_todo_task_count','assign_by_me_inProgress_task_count','assign_by_me_completed_task_count'));
        }else{
            return redirect()->route('login');
        }
    }

    public function kanban_view(Request $request, $id){
        $auth_user_id = Auth::user()['id'];

        $task = Task::where(['id' => $id])->first();

        if($task->assign_to == 'team'){
            $auth_user_as_member = MemberTask::where(['task_id' => $id])->where(['member_user_id' => $auth_user_id])->first();

            if($auth_user_as_member) {
                $auth_user_as_member->task_status = $request->status;
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
                $auth_user_as_assignee->task_status = $request->status;
                $auth_user_as_assignee->updated_at = new \DateTime();
                $auth_user_as_assignee->save();
            }

            $assignees = Assignee::where('task_id', $id)
                                ->where('request_status', '<>', 'rejected')
                                ->groupBy('task_status')
                                ->pluck('task_status')
                                ->toArray();
                                
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
        }
        $task = DB::table('tasks')
            ->where('id', $id)
            ->get();
        return $task;

    }

    public function clearNotification(){
        $auth_user_id = Auth::user()['id'];
        DB::table('notifier')->where(['recipient_id' => $auth_user_id])->update(array('is_read' => 1));
        return;
    }

    public function changeOfficeStatus(Request $request){
        if($request->status == 'wfh'){
            Session::put('wfh', 'wfh');
        }else{
            if(Session::has('wfh')){
                Session::forget('wfh');
            }
        }
        return response()->json( array('success' => true, 'msg' => 'Work status has been updated!') );
    }
}
