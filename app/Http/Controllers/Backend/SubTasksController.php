<?php

namespace App\Http\Controllers\Backend;

use App\Modals\Comment;
use App\Http\Controllers\Controller;
use App\Modals\History;
use App\Modals\Member;
use Illuminate\Http\Request;
use App\Modals\Task;
use App\Modals\Team;
use App\Modals\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification\Notifier;
use App\Http\Controllers\Backend\TasksController;
use FileVault;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Modals\Tag;

class SubTasksController extends Controller
{
    public function create($id){
        $dirname = public_path('backend/uploads/tasks/tmp');
        array_map('unlink', glob("$dirname/*.*"));
        if(is_dir($dirname)) {
            rmdir($dirname);
        }
        $task = Task::with('assignees','teamTasks')->where(['id' => $id])->first();
        $teams = Team::orderBy('name')->get();
        $tags = Tag::all();
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        return view('backend.sub_task.create',compact('task','teams', 'notifications','tags'));
    }
    public function store(Request $request, $id){
        $start_date = date('Y-m-d',strtotime($request->start_date));
        $end_date = date('Y-m-d',strtotime($request->end_date));

        $auth_user_id = Auth::user()['id'];

        $taskController = new TasksController();
        
        $task = new Task();
        $task->assign_by_id = Auth::user()['id'];
        $task->assign_to = $request->assign_to;
        $task->title = $request->title;
        if(isset($request->description)){
            $task->description = $request->description;
        }

        if(isset($request->start_date) && isset($request->end_date)){
            $start_date = date('Y-m-d',strtotime($request->start_date));
            $end_date = date('Y-m-d',strtotime($request->end_date));
            $task->start_date = $start_date;
            $task->end_date = $end_date;

            if(!isset($request->allocated_time)){
                $days = date_diff(date_create($start_date),date_create($end_date))->format('%a');
                $st = str_replace(':','.',$request->start_time);
                $et = str_replace(':','.',$request->end_time);
                $allocated_time = (($days-1)*8)+(18-floatval($st))+floatval($et);
            }else{
                $task->allocated_time = $request->allocated_time;
            }
        }
        elseif(isset($request->start_date) && !isset($request->end_date)){
            $start_date = date('Y-m-d',strtotime($request->start_date));
            $task->start_date = $start_date;
        }
        
        $task->priority = $request->priority;
        $task->parent_id = $id;

        $parentTask = Task::find($id);

        $task->main_parent_id = $parentTask->main_parent_id;
        $task->status = -1;

        if(Session::has('wfh')){
            $task->wfh = 1;
        }

        $task->save();

        if($request->comment){
            $comment = new Comment();
            $comment->task_id = $task->id;
            $comment->user_id = Auth::user()['id'];
            $comment->text = $request->comment;
            $comment->save();
        }
        $task = Task::find($task->id);
        $task->task_id = 'T'. str_pad($task->id, 5, '0', STR_PAD_LEFT);
        $task->save();

        $task_tags = [];
        if(isset($request->tags)){
            foreach($request->tags as $tag){
                array_push($task_tags,['task_id' => $task->id, 'tag_id' => $tag]);
            }
        }
        DB::table('task_tags')->insert($task_tags);

        if($request->assign_to == 'team'){
            $members = Member::whereIn('team_id', $request->teams)->get();
            $team_tasks = [];
            $member_tasks = [];
            foreach ($request->teams as $team_id) {
                array_push($team_tasks, ['task_id' => $task->id, 'team_id' => $team_id,'team_task_status' => 0]);
            }
            DB::table('team_tasks')->insert($team_tasks);

            foreach ($members as $member){
                array_push($member_tasks,['task_id' => $task->id, 'team_id' => $member->team_id, 'member_user_id' => $member->user_id, 'task_status' => 0, 'request_status' => $member->user_id == $auth_user_id ? 'accepted' : 'pending']);

                if(!empty($member->user->email)){
                   // $taskController->sendNotification($member->user_id, $member->user->email, $member->user->name, $task, 'taskAssign');
                }
            }
            DB::table('member_tasks')->insert($member_tasks);
        }elseif($request->assign_to == 'individual'){
            $individuals = [];
            $from_teams = $request->individual_from_teams;
            foreach ($request->individuals as $key=>$individual_id) {
                array_push($individuals, ['task_id' => $task->id, 'team_id' => $from_teams[$key],'user_id' => $individual_id, 'task_status' => -1, 'request_status' => $individual_id == $auth_user_id ? 'accepted' : 'pending']);

                $assigneeUser = User::where('id', $individual_id)->first();

                if(!empty($assigneeUser->email)){
                   // $taskController->sendNotification($individual_id, $assigneeUser->email, $assigneeUser->name, $task, 'taskAssign');
                }
            }
            DB::table('assignees')->insert($individuals);
        }

        if (!empty($request->attachments)) {
            $attachments = [];
            $request_files = [];
            foreach($request->attachments as $attachment){
                if(isset($attachment)){
                    array_push($request_files,$attachment);
                }
            }
            foreach ($request_files as $file_name) {
            
                $from = storage_path('app/backend/uploads/tasks/tmp/' . $file_name);
                $to = storage_path('app/backend/uploads/tasks/' . $task->id);
                if (!is_dir($to)) {
                    mkdir($to, 0777, true);
                }
                copy($from, $to . '/' . $file_name);
                FileVault::encrypt('backend/uploads/tasks/' . $task->id . '/' . $file_name);
                array_push($attachments, ['task_id' => $task->id, 'file_name' => $file_name, 'file_path' => 'storage']);
                
            }
            DB::table('attachments')->insert($attachments);
        }

        Storage::deleteDirectory('backend/uploads/tasks/tmp');

        $history = new History();
        $history->task_id = $task->id;
        $history->user_id = Auth::user()['id'];
        $history->request_type = 'subtask.create';
        $history->message = 'created this task.';
        $history->save();

        session()->flash('success','Sub Task has been created!');
        return response()->json( array('success' => true) );

    }

    public function subTaskdestroy($id)
    {
        $task = Task::find($id);
        if(!is_null($task)){
            $task->delete();
        }
        session()->flash('success','Sub Task has been deleted!');
        return 'deleted';
    }
}
