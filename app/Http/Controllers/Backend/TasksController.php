<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Notification\NotificationCodeEnum;
use App\Modals\Assignee;
use App\Modals\Comment;
use App\Modals\History;
use App\Http\Controllers\Controller;
use App\Modals\Member;
use App\Modals\RequestType;
use App\Modals\Team;
use App\Modals\TeamTask;
use Illuminate\Http\Request;
use App\Modals\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Modals\Reply;
use App\Modals\MemberTask;
use App\Modals\Attachment;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TasksExport;
use App\Exports\TaskByTeamExport;
use App\Modals\User;
use App\Services\Notification\EmailNotificationService;
use App\Services\Notification\NotifierService;
use App\Models\Notification\Notifier;
use Illuminate\Support\Facades\Session;
use Config;
use DateTime;
use App\Modals\NotificationModule;
use FileVault;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Modals\Tag;
use App\Modals\TaskTag;
use App\Modals\Week;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Session::has('task.filter')){
            Session::forget('task.filter');
        }

        $dates =explode('-', date('N-W-Y-D'));  
        
        if($dates[3] == 'Sun'){
            $dates[1] = $dates[1]+1;
        }
           
        $dto = new DateTime();
        $start_date = $dto->setISODate($dates[2], $dates[1])->modify('-1 days')->format('Y-m-d');
        $end_date = $dto->modify('+4 days')->format('Y-m-d');

        $week = Week::where(['week_number'=>$dates[1],'year'=>$dates[2]])->first();

        if(is_null($week)){
            $week = new Week();
            $week->week_number = $dates[1];
            $week->year = $dates[2];
            $week->start_date = $start_date;
            $week->end_date = $end_date;
            $week->save();
        }

        $auth_user_id = Auth::user()['id'];
        $member = Member::where('user_id',$auth_user_id)->first();
        $teams = Team::all();
        $notifications = Notifier::getNotifier($auth_user_id);

        $userIds = Member::where('team_id',$member->team_id)->groupBy('user_id')->pluck('user_id')->toArray();
        
        $tasks= Task::with(['assignees' => function($query) use ($userIds){

                        $query->whereIn('user_id', $userIds);

                }])->where('start_date','>=', $start_date)
                ->where('parent_id',0)->get();        
                
        $taskIds = [];
       
        foreach ($tasks as $key => $task){
            if((count($task->assignees)==0)){
                unset($tasks[$key]);
            }else{

                array_push($taskIds,$task->id);
            }
        }

        
        $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy('id','desc')->paginate(10);
      
        return view('backend.tasks.list',compact('notifications','member','teams','start_date','tasks_for_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dirname = public_path('backend/uploads/tasks/tmp');
        array_map('unlink', glob("$dirname/*.*"));
        if(is_dir($dirname)) {
            rmdir($dirname);
        }

        $tags = Tag::all();
  
        $teams = Team::with(['members.user' => function ($q){
                              $q->orderBy('name','asc');
                           }])->orderBy('name', 'asc')->get();
        
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        return view('backend.tasks.create',compact('teams', 'notifications','tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $auth_user_id = Auth::user()['id'];

        $task = new Task();
        $task->assign_by_id = $auth_user_id;
        $task->assign_to = $request->assign_to;
        $task->title = $request->title;
       
        $task->description = $request->description;

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
        $task->status = -1;



        if(Session::has('wfh')){
            $task->wfh = 1;
        }

        $task->save();

        if ($request->comment) {
            $comment = new Comment();
            $comment->task_id = $task->id;
            $comment->user_id = Auth::user()['id'];
            $comment->text = $request->comment;
            $comment->save();
        }
        $task = Task::find($task->id);
        $task->task_id = 'T' . str_pad($task->id, 5, '0', STR_PAD_LEFT);
        $task->main_parent_id = $task->id;
        $task->save();
        
        $task_tags = [];

        if(isset($request->tags)){
            foreach($request->tags as $tag){
                array_push($task_tags,['task_id' => $task->id, 'tag_id' => $tag]);
            }
        }
        DB::table('task_tags')->insert($task_tags);

        if ($request->assign_to == 'team') {
            $members = Member::whereIn('team_id', $request->teams)->get();
            $team_tasks = [];
            $member_tasks = [];
            foreach ($request->teams as $team_id) {
                array_push($team_tasks, [
                    'task_id' => $task->id,
                    'team_id' => $team_id,
                    'team_task_status' => 0,
                    'created_at' => new \DateTime(),
                    'updated_at' => new \DateTime()
                    ]);
            }
            DB::table('team_tasks')->insert($team_tasks);

            foreach ($members as $member){

                if(!empty($member->user->email)){
                    //$this->sendNotification($member->user_id, $member->user->email, $member->user->name, $task, 'taskAssign');
                }

                array_push($member_tasks,
                [
                    'task_id' => $task->id,
                    'team_id' => $member->team_id,
                    'member_user_id' => $member->user_id,
                    'task_status' => 0,
                    'request_status' => $member->user_id == $auth_user_id ? 'accepted' : 'pending',
                    'created_at' => new \DateTime(),
                    'updated_at' => new \DateTime()
                ]);
            }
            DB::table('member_tasks')->insert($member_tasks);

        }
        elseif ($request->assign_to == 'individual') {
            $from_teams = $request->individual_from_teams;
            $individuals = [];

            foreach ($request->individuals as $key=>$individual_id) {
                                
                $assigneeUser = User::where('id', $individual_id)->first();

                if(!empty($assigneeUser->email)){
                    //$this->sendNotification($individual_id, $assigneeUser->email, $assigneeUser->name, $task, 'taskAssign');
                }

                array_push($individuals,
                [
                    'task_id' => $task->id,
                    'team_id' => $from_teams[$key],
                    'user_id' => $individual_id,
                    'task_status' => -1,
                    'request_status' => $individual_id == $auth_user_id ? 'accepted' : 'pending',
                    'created_at' => new \DateTime(),
                    'updated_at' => new \DateTime()
                ]);
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
                FileVault::encrypt('backend/uploads/tasks/' . $task->id .'/'.$file_name);
                array_push($attachments, ['task_id' => $task->id, 'file_name' => $file_name, 'file_path' => 'storage']);
                
            }
            DB::table('attachments')->insert($attachments);
        }

        Storage::deleteDirectory('backend/uploads/tasks/tmp');
       
        $history = new History();
        $history->task_id = $task->id;
        $history->user_id = Auth::user()['id'];
        $history->request_type = 'task.create';
        $history->message = 'created this task.';
        $history->save();

        session()->flash('success', 'Task has been created!');
        return response()->json( array('success' => true) );

    }

    public function fileUpload(Request $request){
        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            for($i = 0; $i < count($attachments); $i++) {
                $file_name = $attachments[$i]->getClientOriginalName();
                $destination_path = storage_path('app/backend'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'tasks'.DIRECTORY_SEPARATOR.'tmp');
                $attachments[$i]->move($destination_path,$file_name);
            }
        }
    }

    public function fileUploadComment(Request $request){
        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            for($i = 0; $i < count($attachments); $i++) {
                $file_name = $attachments[$i]->getClientOriginalName();
                $destination_path = storage_path('app/backend'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'comments'.DIRECTORY_SEPARATOR.'tmp');
                $attachments[$i]->move($destination_path,$file_name);
            }
        }
    }

    public function fileUploadReply(Request $request){
        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            for($i = 0; $i < count($attachments); $i++) {
                $file_name = $attachments[$i]->getClientOriginalName();
                $destination_path = storage_path('app/backend'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'replies'.DIRECTORY_SEPARATOR.'tmp');
                $attachments[$i]->move($destination_path,$file_name);
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $auth_user_id = Auth::user()['id'];
        $request_status = '';
        $my_task_status = 0;
        $taskIds = [];
        $breadcums = [];

        $task = Task::with('assignBy','assignees','attachments','comments','replies','requests')
                    ->with(['histories' => function($q){
                        $q->orderBy('id','desc');
                    }])->find($id);

        $task_breadcums = Task::taskBreadcums($task, []);               

        if(!isset($task)){
            session()->flash('error','This task doesn\'t exist anymore!');
            return redirect()->route('tasks.index');
        }              

        if((isset($task->assign_to)) && ($task->assign_to == 'team')) {
            $member_task = MemberTask::where([
                                            'task_id'=>$id,
                                            'member_user_id' => $auth_user_id,
                                    ])->first();
            if($member_task){
                $request_status = $member_task->request_status;
                $my_task_status = $member_task->task_status;
            }
        }
        elseif((isset($task->assign_to)) && ($task->assign_to == 'individual')) {
            $assignee_task = Assignee::where([
                                            'task_id' => $id,
                                            'user_id' => $auth_user_id,
                                    ])->first();
            if($assignee_task){
                $request_status = $assignee_task->request_status;
                $my_task_status = $assignee_task->task_status;

            }
        }
        $sub_tasks = Task::with('teamTasks','attachments')->where(['parent_id' => $id])->get();
        $request_types = RequestType::all();
        $notifications = Notifier::getNotifier(Auth::user()['id']);

        // $tasks= Task::with(['memberTasks' => function($query) use ($auth_user_id){

        //                 $query->where(['member_user_id' => $auth_user_id]);

        //         }])
        //         ->with(['assignees' => function($query) use ($auth_user_id){

        //                 $query->where(['user_id' => $auth_user_id]);

        //         }])->get();    
        
             
        // foreach ($tasks as $key => $single_task){
        //     if((count($single_task->memberTasks) == 0) && (count($single_task->assignees)==0) && ($single_task->assign_by_id != $auth_user_id)){
        //         unset($tasks[$key]);
        //     }
        //     else{   
        //         if(!in_array($id,$taskIds) &&($single_task->main_parent_id == $task->main_parent_id) && ($id > $single_task->id)){
        //             array_push($taskIds, $id);
        //         }
        //         array_push($taskIds,$single_task->id);     
        //     }
        // }
        
        // if(!in_array($id,$taskIds)){
        //     session()->flash('error','You are not authorized to this page!');
        //     return back();
        // }  

        return view('backend.tasks.view',compact('task_breadcums','task','sub_tasks', 'notifications', 'request_types', 'request_status','my_task_status'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $disable_team = '';
        $auth_user_id = Auth::user()['id'];

        // $task_filter = Task::with(['memberTasks' => function($query) use ($auth_user_id){

        //                     $query->where(['member_user_id' => $auth_user_id])
        //                         ->where('request_status', '<>', 'rejected');

        //             }])
        //             ->with(['assignees' => function($query) use ($auth_user_id){

        //                     $query->where(['user_id' => $auth_user_id])
        //                         ->where('request_status','<>', 'rejected');

        //             }])->find($id);     

        // if(isset($task_filter)){        
        //     if((count($task_filter->memberTasks) == 0) && (count($task_filter->assignees)==0) && ($task_filter->assign_by_id != $auth_user_id)){
        //         session()->flash('error','You are not authorized to this page!');
        //         return redirect()->route('tasks.index');
        //     }
        // }else{
        //     session()->flash('error','You are not authorized to this page!');
        //     return redirect()->route('tasks.index');
        // }

        $task = Task::with('assignees','teamTasks','comments','taskTags')->where(['id' => $id])->first();
        $tags = Tag::all();
        $teams = Team::orderBy('name', 'ASC')->get();
        if($task->assign_to == 'individual') {
            $assignee_task = Assignee::where([
                                            'task_id' => $id,
                                            'user_id' => $auth_user_id,
                                    ])->first();
            if($assignee_task){
                $request_status = $assignee_task->request_status;
                $disable_team = 'disabled';
            }
        }
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        return view('backend.tasks.edit',compact('task','teams','disable_team', 'notifications','tags'));
    }

    public function reassign($id)
    {
        $disable_team = '';
        $auth_user_id = Auth::user()['id'];

        // $task_filter = Task::with(['memberTasks' => function($query) use ($auth_user_id){

        //                     $query->where(['member_user_id' => $auth_user_id])
        //                         ->where('request_status', '<>', 'rejected');

        //             }])
        //             ->with(['assignees' => function($query) use ($auth_user_id){

        //                     $query->where(['user_id' => $auth_user_id])
        //                         ->where('request_status','<>', 'rejected');

        //             }])->find($id);     

        // if(isset($task_filter)){        
        //     if((count($task_filter->memberTasks) == 0) && (count($task_filter->assignees)==0) && ($task_filter->assign_by_id != $auth_user_id)){
        //         session()->flash('error','You are not authorized to this page!');
        //         return redirect()->route('tasks.index');
        //     }
        // }else{
        //     session()->flash('error','You are not authorized to this page!');
        //     return redirect()->route('tasks.index');
        // }

        $task = Task::with('assignees','teamTasks')->where(['id' => $id])->first();
        $teams = Team::orderBy('name', 'ASC')->get();
        if($task->assign_to == 'individual') {
            $assignee_task = Assignee::where([
                                            'task_id' => $id,
                                            'user_id' => $auth_user_id,
                                    ])->first();
            if($assignee_task){
                $request_status = $assignee_task->request_status;
                $disable_team = 'disabled';
            }
        }
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        return view('backend.tasks.reassign',compact('task','teams','disable_team', 'notifications'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
        $auth_user_id = Auth::user()['id'];
        
        $task = Task::find($id);
        $task->title = $request->title;
        $task->description = $request->description;

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
        $task->assign_to = $request->assign_to;
        
        $task->save();

        $task_tags = [];
       
        if(isset($request->tags)){
            foreach($request->tags as $tag){
                array_push($task_tags,['task_id' => $task->id, 'tag_id' => $tag]);
            }
        }
        TaskTag::where(['task_id' => $task->id])->delete();
        DB::table('task_tags')->insert($task_tags);

        if($request->comment){
            $comment = new Comment();
            $comment->task_id = $task->id;
            $comment->user_id = Auth::user()['id'];
            $comment->text = $request->comment;
            $comment->save();
        }


        if($request->assign_to == 'team'){

            $task->status = -1;
            $task->save();
            
            DB::table('assignees')->where('task_id', $id)->delete();

            $team_tasks = [];
            $member_tasks = [];

            foreach ($request->teams as $team_id) {

                $existing_team = TeamTask::where(['task_id' => $task->id])->where(['team_id' => $team_id])->first();

                if(is_null($existing_team)){
                    array_push($team_tasks, [
                        'task_id' => $task->id,
                        'team_id' => $team_id,
                        'team_task_status' => 0,
                        'created_at' => new \DateTime(),
                        'updated_at' => new \DateTime()
                        ]);
                }

                $members = Member::where('team_id', $team_id)->get();
                $memberId_array = [];

                foreach ($members as $member){

                    array_push($memberId_array, $member->user_id);                    

                    $existing_member = MemberTask::where('task_id', $task->id)
                                            ->where('team_id', $member->team_id)
                                            ->where('member_user_id', $member->user_id)
                                            ->first();

                    if(is_null($existing_member)){
                        array_push($member_tasks,[
                            'task_id' => $task->id,
                            'team_id' => $member->team_id,
                            'member_user_id' => $member->user_id,
                            'task_status' => 0,
                            'request_status' => $member->user_id == $auth_user_id ? 'accepted' : 'pending',
                            'created_at' => new \DateTime(),
                            'updated_at' => new \DateTime()
                        ]);
                        
                        if(!empty($member->user->email)){
                           // $this->sendNotification($member->user_id, $member->user->email, $member->user->name, $task, 'taskAssign');
                        }
                    }
                    else{
                        if(!empty($member->user->email)){
                           // $this->sendNotification($member->user_id, $member->user->email, $member->user->name, $task, 'taskEdit');
                        }
                    }
                }

            }
            if(count($member_tasks) > 0){
                DB::table('member_tasks')->insert($member_tasks);
            }
            
            TeamTask::where('task_id', $id)
                ->whereNotIn('team_id', $request->teams)
                ->delete();
            
            MemberTask::where('task_id', $id)
            ->whereNotIn('team_id', $request->teams)
            ->delete();

            DB::table('team_tasks')->insert($team_tasks);           
            
        }elseif($request->assign_to == 'individual'){

            DB::table('team_tasks')->where('task_id', $id)->delete();
            DB::table('member_tasks')->where('task_id', $id)->delete();

            $individuals = [];
            $from_teams = $request->individual_from_teams;
            foreach ($request->individuals as $key=>$individual_id){
                if(isset($from_teams[$key])){

                    $existing_assignee = Assignee::where('task_id' , $task->id)
                                            ->where('team_id', $from_teams[$key])
                                            ->where('user_id', $individual_id)
                                            ->where('request_status', '!=', 'rejected')
                                            ->first();
                    $reject_assignee =  Assignee::where('task_id' , $task->id)
                                            ->where('team_id', $from_teams[$key])
                                            ->where('user_id', $individual_id)
                                            ->where(['request_status' => 'rejected'])
                                            ->first(); 
                    if($reject_assignee){

                       $reject_assignee->task_status = -1;
                       $reject_assignee->request_status = 'pending';
                       $reject_assignee->created_at = new \DateTime();
                       $reject_assignee->updated_at = new \DateTime();
                       $reject_assignee->save();

                        $assigneeUser = User::where('id', $individual_id)->first();

                        if(!empty($assigneeUser->email)){
                          //  $this->sendNotification($individual_id, $assigneeUser->email, $assigneeUser->name, $task, 'taskAssign');
                        }
                    }                                               

                    
                    if($existing_assignee){
                        array_push($individuals,[
                            'task_id' => $existing_assignee->task_id,
                            'team_id' => $existing_assignee->team_id,
                            'user_id' => $existing_assignee->user_id,
                            'task_status' => $existing_assignee->task_status,
                            'request_status' => $existing_assignee->request_status,
                            'created_at' => $existing_assignee->created_at,
                            'updated_at' => $existing_assignee->updated_at
                        ]);

                        if(!empty($existing_assignee->user->email)){
                          //  $this->sendNotification($existing_assignee->user_id, $existing_assignee->user->email, $existing_assignee->user->name, $task, 'taskEdit');
                        }
                    }
                    else{

                        array_push($individuals, [
                            'task_id' => $task->id,
                            'team_id'=>$from_teams[$key],
                            'user_id' => $individual_id,
                            'task_status' => -1,
                            'request_status' => $individual_id == $auth_user_id ? 'accepted' : 'pending',
                            'created_at' => new \DateTime(),
                            'updated_at' => new \DateTime()
                            ]);
                            
                        $assigneeUser = User::where('id', $individual_id)->first();

                        if(!empty($assigneeUser->email)){
                           // $this->sendNotification($individual_id, $assigneeUser->email, $assigneeUser->name, $task, 'taskAssign');
                        }
                    }
                }
            }
           
            DB::table('assignees')->where('task_id', $id)->where('request_status','!=','rejected')->delete();
            DB::table('assignees')->where('task_id', $id)->where('request_status','rejected')->update(['show_rejected_task' => 0]);
            DB::table('assignees')->insert($individuals);

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

        if(empty($request->existing_attachments)){
            Attachment::where(['task_id' => $id])->delete();
        }else{
            Attachment::where(['task_id' => $id])->whereNotIn('file_name',$request->existing_attachments)->delete();
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
        $history->request_type = 'task.edit';
        $history->message = 'updated this task.';
        $history->save();

        if($task->parent_id > 0){
            session()->flash('success','Sub Task has been updated successfully!');
        }else{
            session()->flash('success','Task has been updated successfully!');
        }
        return response()->json( array('success' => true) );
    }

    public function reassignUpdate(Request $request, $id)
    {
        $auth_user_id = Auth::user()['id'];

        $task = Task::find($id);
        $task->assign_to = $request->assign_to;
        $task->status = -1;
        if(Session::has('wfh')){
            $task->wfh = 1;
        }
        $task->save();

        if($request->assign_to == 'team'){
            DB::table('assignees')->where('task_id', $id)->delete();

            $team_tasks = [];
            $member_tasks = [];
            foreach ($request->teams as $team_id) {
                $existing_team = TeamTask::where(['task_id' => $task->id])->where(['team_id' => $team_id])->first();
                if(is_null($existing_team)){
                    array_push($team_tasks, [
                        'task_id' => $task->id,
                        'team_id' => $team_id,
                        'team_task_status' => 0,
                        'created_at' => new \DateTime(),
                        'updated_at' => new \DateTime()
                        ]);
                }

                $members = Member::where('team_id', $team_id)->get();
                $memberId_array = [];

                foreach ($members as $member){

                    array_push($memberId_array, $member->user_id);                    

                    $existing_member = MemberTask::where('task_id', $task->id)
                                            ->where('team_id', $member->team_id)
                                            ->where('member_user_id', $member->user_id)
                                            ->first();

                    if(is_null($existing_member)){
                        array_push($member_tasks,[
                            'task_id' => $task->id,
                            'team_id' => $member->team_id,
                            'member_user_id' => $member->user_id,
                            'task_status' => 0,
                            'request_status' => $member->user_id == $auth_user_id ? 'accepted' : 'pending',
                            'created_at' => new \DateTime(),
                            'updated_at' => new \DateTime()
                        ]);

                        if(!empty($member->user->email)){
                            // $this->sendNotification($member->user_id, $member->user->email, $member->user->name, $task, 'taskAssign');
                        }
                    }
                }

            }

            if(count($member_tasks) > 0){
                DB::table('member_tasks')->insert($member_tasks);
            }
            
            TeamTask::where('task_id', $id)
                ->whereNotIn('team_id', $request->teams)
                ->delete();
            MemberTask::where('task_id', $id)
                ->whereNotIn('team_id', $request->teams)
                ->delete();
            
            DB::table('team_tasks')->insert($team_tasks);           
            
        }elseif($request->assign_to == 'individual'){
            DB::table('team_tasks')->where('task_id', $id)->delete();
            DB::table('member_tasks')->where('task_id', $id)->delete();

            $individuals = [];
            $from_teams = $request->individual_from_teams;
            foreach ($request->individuals as $key=>$individual_id){
                if(isset($from_teams[$key])){

                    $existing_assignee = Assignee::where('task_id' , $task->id)
                                            ->where('team_id', $from_teams[$key])
                                            ->where('user_id', $individual_id)
                                            ->where('request_status', '!=', 'rejected')
                                            ->first();
                    $reject_assignee =  Assignee::where('task_id' , $task->id)
                                            ->where('team_id', $from_teams[$key])
                                            ->where('user_id', $individual_id)
                                            ->where(['request_status' => 'rejected'])
                                            ->first(); 
                    if($reject_assignee){

                       $reject_assignee->task_status = -1;
                       $reject_assignee->request_status = 'pending';
                       $reject_assignee->created_at = new \DateTime();
                       $reject_assignee->updated_at = new \DateTime();
                       $reject_assignee->save();
                            
                        $assigneeUser = User::where('id', $individual_id)->first();

                        if(!empty($assigneeUser->email)){
                            // $this->sendNotification($individual_id, $assigneeUser->email, $assigneeUser->name, $task, 'taskAssign');
                        }
                    }                        
                    
                    if(is_null($existing_assignee)){
                        array_push($individuals, [
                            'task_id' => $task->id,
                            'team_id'=>$from_teams[$key],
                            'user_id' => $individual_id,
                            'task_status' => -1,
                            'request_status' => $individual_id == $auth_user_id ? 'accepted' : 'pending',
                            'created_at' => new \DateTime(),
                            'updated_at' => new \DateTime()
                            ]);
                            
                        $assigneeUser = User::where('id', $individual_id)->first();
                        if(!empty($assigneeUser->email)){
                            // $this->sendNotification($individual_id, $assigneeUser->email, $assigneeUser->name, $task, 'taskAssign');
                        }

                    }
                    else{
                        array_push($individuals,[
                            'task_id' => $existing_assignee->task_id,
                            'team_id' => $existing_assignee->team_id,
                            'user_id' => $existing_assignee->user_id,
                            'task_status' => $existing_assignee->task_status,
                            'request_status' => $existing_assignee->request_status,
                            'created_at' => $existing_assignee->created_at,
                            'updated_at' => $existing_assignee->updated_at
                        ]);

                            // NO NOTIFICATION FOR EXISTING USERS
                            // if(!empty($existing_assignee->user->email)){
                            //     $this->sendNotification($existing_assignee->user_id, $existing_assignee->user->email, $existing_assignee->user->name, $task, 'taskAssign');
                            // }
                    }
                }
            }
            DB::table('assignees')->where('task_id', $id)->where('request_status','!=','rejected')->delete();
            DB::table('assignees')->where('task_id', $id)->where('request_status','rejected')->update(['show_rejected_task' => 0]);
            DB::table('assignees')->insert($individuals);
            
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

        $history = new History();
        $history->task_id = $task->id;
        $history->user_id = Auth::user()['id'];
        $history->request_type = 'task.reassign';
        $history->message = 'reassigned this task.';
        $history->save();

        session()->flash('success','Reassign has been successfully!');
        return response()->json( array('success' => true) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if(!is_null($task)){
            $task->delete();
            Assignee::where('task_id',$id)->delete();
        }
        return response()->json( array('msg' => 'Task has been deleted') );
    }

    public function downloadFile($taskId){
        $attachment = Attachment::where(['task_id' => $taskId])->first();
        if($attachment->file_path == 'public'){
            $task = Task::where(['id' => $taskId])->first();
            $path = public_path('backend'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'tasks'.DIRECTORY_SEPARATOR.$task->task_id.DIRECTORY_SEPARATOR.$attachment->file_name);
            return response()->download($path);
        }else{
            $fileName = $attachment->file_name;
            return response()->streamDownload(function () use ($taskId,$fileName) {
                FileVault::streamDecrypt('backend/uploads/tasks/' . $taskId. '/'. $fileName.'.enc');
            }, Str::replaceLast('.enc', '', $fileName)); 
        }
    }

    public function downloadCommentFile($commentId){
        $attachment = Attachment::where(['comment_id' => $commentId])->first();
        if($attachment->file_path == 'public'){
            $path = public_path('backend'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'comments'.DIRECTORY_SEPARATOR.$commentId.DIRECTORY_SEPARATOR.$attachment->file_name);
            return response()->download($path);
        }else{
            $fileName = $attachment->file_name;
            return response()->streamDownload(function () use ($commentId,$fileName) {
                FileVault::streamDecrypt('backend/uploads/comments/' . $commentId. '/'. $fileName.'.enc');
            }, Str::replaceLast('.enc', '', $fileName));
        }
    }

    public function downloadReplyFile($replyId){
        $attachment = Attachment::where(['reply_id' => $replyId])->first();
        if($attachment->file_path == 'public'){
            $path = public_path('backend'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'replies'.DIRECTORY_SEPARATOR.$replyId.DIRECTORY_SEPARATOR.$attachment->file_name);
            return response()->download($path);
        }else{
            $fileName = $attachment->file_name;
            return response()->streamDownload(function () use ($replyId,$fileName) {
                FileVault::streamDecrypt('backend/uploads/replies/' . $replyId. '/'. $fileName.'.enc');
            }, Str::replaceLast('.enc', '', $fileName));
        }
    }

    public function commentSave(Request $request,$task_id){
        $comment = new Comment();
        $comment->task_id = $task_id;
        $comment->user_id = Auth::user()['id'];
        $comment->text = $request->text;
        $comment->save();

        if(Session::has('wfh')){

            $assignee = Assignee::where('task_id', $task_id)->where('user_id', $comment->user_id)->first();

            if((isset($assignee)) && ($assignee->wfh != 1)){
                $assignee->wfh = 1;
                $assignee->save();
            }

            DB::table('tasks')->where('id',$task_id)->update(['wfh' => 1]);
        }

        if (!empty($request->attach_files)) {
            $attachments = [];
            $request_files = [];
            foreach($request->attach_files as $attachment){
                if(isset($attachment)){
                    array_push($request_files,$attachment);
                }
            }
            foreach ($request_files as $file_name) {
                $from = storage_path('app/backend/uploads/comments/tmp/' . $file_name);
                $to = storage_path('app/backend/uploads/comments/' . $comment->id);
                if (!is_dir($to)) {
                    mkdir($to, 0777, true);
                }
                copy($from, $to . '/' . $file_name);

                FileVault::encrypt('backend/uploads/comments/' . $comment->id . '/'. $file_name);
                array_push($attachments, ['comment_id' => $comment->id, 'file_name' => $file_name, 'file_path' => 'storage']);
                
            }
            DB::table('attachments')->insert($attachments);
        }

        Storage::deleteDirectory('backend/uploads/comments/tmp');

        session()->flash('success','Comment has been saved.');
        return;
    }

    public function commentEdit(Request $request,$id){
        $comment = Comment::find($id);
        $comment->text = $request->text;
        $comment->save();

        if(Session::has('wfh')){

            $assignee = Assignee::where('task_id', $comment->task_id)->where('user_id', $comment->user_id)->first();

            if((isset($assignee)) && ($assignee->wfh != 1)){
                $assignee->wfh = 1;
                $assignee->save();
            }
            
            DB::table('tasks')->where('id',$comment->task_id)->update(['wfh' => 1]);
        }

        // if (!empty($request->attach_files)) {
        //     $attachments = [];
        //     $request_files = [];
        //     foreach($request->attach_files as $attachment){
        //         if(isset($attachment)){
        //             array_push($request_files,$attachment);
        //         }
        //     }
        //     foreach ($request_files as $file_name) {
        //         $from = public_path('backend/uploads/comments/tmp/' . $file_name);
        //         $to = public_path('backend/uploads/comments/' . $comment->id);
        //         if (!is_dir($to)) {
        //             mkdir($to, 0777, true);
        //         }
        //         copy($from, $to . '/' . $file_name);
        //         array_push($attachments, ['comment_id' => $comment->id, 'file_name' => $file_name]);
                
        //     }
        //     DB::table('attachments')->insert($attachments);
        // }

        // $this->deleteDir('backend/uploads/comments/tmp');

        session()->flash('success','Comment has been updated.');
        return;
    }
    public function replySave(Request $request,$id){

        $comment = Comment::find($id);
        $reply = new Reply();
        $reply->comment_id = $comment->id;
        $reply->task_id = $comment->task_id;
        $reply->user_id = Auth::user()['id'];
        $reply->text = $request->text;
        $reply->save();

        if(Session::has('wfh')){

            $assignee = Assignee::where('task_id', $comment->task_id)->where('user_id', $reply->user_id)->first();

            if((isset($assignee)) && ($assignee->wfh != 1)){
                $assignee->wfh = 1;
                $assignee->save();
            }
            
            DB::table('tasks')->where('id',$comment->task_id)->update(['wfh' => 1]);
        }


        if (!empty($request->attach_files)) {
            $attachments = [];
            $request_files = [];
            foreach($request->attach_files as $attachment){
                if(isset($attachment)){
                    array_push($request_files,$attachment);
                }
            }
            foreach ($request_files as $file_name) {
                $from = storage_path('app/backend/uploads/replies/tmp/' . $file_name);
                $to = storage_path('app/backend/uploads/replies/' . $reply->id);
                if (!is_dir($to)) {
                    mkdir($to, 0777, true);
                }
                copy($from, $to . '/' . $file_name);
                FileVault::encrypt('backend/uploads/replies/' . $reply->id .'/' . $file->file_name);
                array_push($attachments, ['reply_id' => $reply->id, 'file_name' => $file_name, 'file_path' => 'storage']);
            }
            DB::table('attachments')->insert($attachments);
        }

        Storage::deleteDirectory('backend/uploads/replies/tmp');

        session()->flash('success','Reply has been saved.');
    }

    public function replyEdit(Request $request,$id){
        $reply = Reply::find($id);
        $reply->text = $request->text;
        $reply->save();

        if(Session::has('wfh')){

            $assignee = Assignee::where('task_id', $reply->task_id)->where('user_id', $reply->user_id)->first();

            if((isset($assignee)) && ($assignee->wfh != 1)){
                $assignee->wfh = 1;
                $assignee->save();
            }
            
            DB::table('tasks')->where('id',$reply->task_id)->update(['wfh' => 1]);
        }

        // if (!empty($request->attach_files)) {
        //     $attachments = [];
        //     $request_files = [];
        //     foreach($request->attach_files as $attachment){
        //         if(isset($attachment)){
        //             array_push($request_files,$attachment);
        //         }
        //     }
        //     foreach ($request_files as $file_name) {
        //         $from = public_path('backend/uploads/comments/tmp/' . $file_name);
        //         $to = public_path('backend/uploads/comments/' . $comment->id);
        //         if (!is_dir($to)) {
        //             mkdir($to, 0777, true);
        //         }
        //         copy($from, $to . '/' . $file_name);
        //         array_push($attachments, ['comment_id' => $comment->id, 'file_name' => $file_name]);
                
        //     }
        //     DB::table('attachments')->insert($attachments);
        // }

        // $this->deleteDir('backend/uploads/comments/tmp');

        session()->flash('success','Reply has been updated.');
        return;
    }

    public function updateRequestStatus(Request $request,$id){

        $auth_user_id = Auth::user()['id'];

        $task = Task::find($id);

        if($task->assign_to == 'team'){
            $member = MemberTask::where(['task_id' => $id])->where(['member_user_id' => $auth_user_id])->first();
            if($member) {
                $member->request_status = $request->status;
                $member->updated_at = new \DateTime();
                $member->save();
            }
        }
        else {
            $assignee = Assignee::where(['task_id' => $id])->where(['user_id' => $auth_user_id])->first();
            if($assignee) {
                $assignee->request_status = $request->status;
                $assignee->task_status = 0;
                $assignee->updated_at = new \DateTime();
                if(Session::has('wfh')){
                    $assignee->wfh=1;
                    DB::table('tasks')->where('id',$id)->update(['wfh' => 1]);
                }
                $assignee->save();
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
        

        if(!empty($task->assignBy->email)){
           // $this->sendNotification($task->assign_by_id, $task->assignBy->email, Auth::user()['name'], $task, 'taskAccept');
        }

        $history = new History();
        $history->task_id = $id;
        $history->user_id = $auth_user_id;
        $history->request_type = 'request.status.update';
        $history->message = 'accepted the task';
        $history->save();

        return redirect()->route('tasks.index');
    }

    public function updateTaskStatus(Request $request,$id){
        $auth_user_id = Auth::user()['id'];

        $task = Task::where(['id' => $id])->first();


        $auth_user_as_assignee = Assignee::where(['task_id' => $id])->where(['user_id' => $auth_user_id])->first();

        if ($auth_user_as_assignee) {
            $auth_user_as_assignee->task_status = $request->task_status;
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
                DB::table('tasks')->where(['id' => $id])->update(array('status' => $request->task_status));
            }
        

        if($request->task_status == 2 && !empty($task->assignBy->email)){
           // $this->sendNotification($task->assign_by_id, $task->assignBy->email, Auth::user()['name'], $task, 'taskComplete');
        }

        $history = new History();
        $history->task_id = $id;
        $history->user_id = $auth_user_id;
        $history->request_type = 'task.status.update';
        $history->message = 'updated his/her task status';
        $history->save();

        session()->flash('success','Task status has been updated successfully!');
        return redirect()->route('tasks.index');
    }

    public function search(Request $request){
        
        Session::put('task.filter', $request->all());

        $auth_user_id = Auth::user()['id'];
        
        $member = Member::where('user_id',$auth_user_id)->first();
        
        $notifications = Notifier::getNotifier($auth_user_id);

        if(isset($request->team)){
            $member->team_id = $request->team;
        }

        $userIds = Member::where('team_id',$member->team_id)->groupBy('user_id')->pluck('user_id')->toArray();

        if(isset($request->search_key)){
            $ids = User::where('name','like','%'.$request->search_key.'%')->groupBy('id')->pluck('id')->toArray();
            $resultIds = [];
            foreach($ids as $id){
                if(in_array($id,$userIds)){
                    array_push($resultIds,$id);
                }
            }

            $userIds = $resultIds;
        }

        $task_query= Task::with(['assignees' => function($query) use ($userIds){

                        $query->whereIn('user_id', $userIds);

                    }])->where('parent_id',0);  

        if(isset($request->status)){
            if($request->status == 3){
                $task_query->where('end_date', '<',  date('Y-m-d'))->where('status', '!=', 2);
            }else{
                $task_query->where(['status' => $request->status]);
            }
        }
        if(isset($request->priority)){
            $task_query->where(['priority' => $request->priority]);
        }
        if(isset($request->start_date) && isset($request->end_date)){
            $task_query->whereBetween('start_date',[ date('Y-m-d', strtotime($request->start_date)), date('Y-m-d', strtotime($request->end_date))]);
            $task_query->where('end_date', '<=',  date('Y-m-d', strtotime($request->end_date)));
        }
        elseif(isset($request->start_date)){
            $task_query->where('start_date', '>=', date('Y-m-d', strtotime($request->start_date)));
        }
        elseif(isset($request->end_date)){
            $task_query->where('end_date', '<=',  date('Y-m-d', strtotime($request->end_date)));
        }

        if(isset($request->search_key_task)){
            $task_query->where('title','like','%'.$request->search_key_task.'%');
            $task_query->orWhere('task_id','like','%'.$request->search_key_task.'%');
        }
        
        $tasks = $task_query->get();
        $taskIds = [];
        $total_allocated_time = 0;
        foreach ($tasks as $key => $task){
            if((count($task->assignees)==0)){
                unset($tasks[$key]);
            }else{
                $total_allocated_time = $total_allocated_time + $task->allocated_time;
                array_push($taskIds,$task->id);
            }
        }
        $total_tasks = count($taskIds);
        if(isset($request->coloumn)){
            if($request->coloumn == 'priority'){       
                if($request->order == 'asc'){
                    $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(priority, 2, 0, 1)')->paginate(10);
                    return response()->json( array('success' => true, 'total_allocated_time' => $total_allocated_time, 'total_tasks' => $total_tasks, 'view'=>view('backend.tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
                }elseif($request->order == 'desc'){
                    $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(priority, 1, 0, 2)')->paginate(10);
                    return response()->json( array('success' => true, 'total_allocated_time' => $total_allocated_time, 'total_tasks' => $total_tasks, 'view'=>view('backend.tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
                }
            }
            if($request->coloumn == 'status'){
                if($request->order == 'asc'){
                    $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(status, 0, 2, 1, -1, 4)')->paginate(10);
                    return response()->json( array('success' => true, 'total_allocated_time' => $total_allocated_time, 'total_tasks' => $total_tasks, 'view'=>view('backend.tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
                }elseif($request->order == 'desc'){
                    $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(status, 4, -1, 1, 2, 0)')->paginate(10);
                    return response()->json( array('success' => true, 'total_allocated_time' => $total_allocated_time, 'total_tasks' => $total_tasks, 'view'=>view('backend.tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
                }
            }
            $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy($request->coloumn, $request->order)->paginate(10);
            return response()->json( array('success' => true, 'total_allocated_time' => $total_allocated_time, 'total_tasks' => $total_tasks, 'view'=>view('backend.tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
        }
        $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy('id', 'desc')->paginate(10);
        return response()->json( array('success' => true, 'total_allocated_time' => $total_allocated_time, 'total_tasks' => $total_tasks, 'view'=>view('backend.tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
        
    }

    public function export(){
        return Excel::download(new TasksExport,'tasks.xlsx');
    }

    public function exportByTeam($id){
        Session::put('taskByTeam.team_id', $id);
        return Excel::download(new TaskByTeamExport,'tasks_by_team.xlsx');
    }

    public function TaskUpdateOrCreate(Request $request, $id){
        return Task::taskUpdateOrCreate($request, $id);
    }

    public function taskByTeam($id){
        $auth_user_id = Auth::user()['id'];
        $team_id = $id;
    
        $tasks = Task::with(['teamTasks' => function($query) use ($id){
                           $query->where(['team_id' => $id]);
                        }])->orderBy('id','desc')->get();

        $taskIds = [];                
        foreach($tasks as $key=>$task){
            if(count($task->teamTasks) == 0){
                unset($tasks[$key]);
            }else{
                array_push($taskIds, $task->id);
            }
        }

        $tasks_for_list = Task::with('teamTasks')->whereIn('id',$taskIds)->paginate(10);

        $assigned_tasks = Task::where(['assign_by_id' => $auth_user_id])->orderBy('id','desc')->get();

        $teams = [];
        $team_names = [];
        foreach($assigned_tasks as $task){
            if($task->assign_to == 'team'){
                foreach($task->teamTasks as $team_task){
                    if(isset($task->Team->name)){
                        if(!in_array($team_task->Team->name,$team_names)){
                            array_push($team_names,$team_task->Team->name);
                            array_push($teams,['name' =>$team_Task->Team->name, 'id' => $team_task->team_id]);
                        }
                    }
                }
            }
        }
        $notifications = Notifier::getNotifier(Auth::user()['id']);

        return view('backend.tasks.list_by_team',compact('tasks_for_list','teams', 'team_id', 'notifications'));
    }

    public function nudge($user_id, $task_id) {
        $user = User::where(['id' => $user_id])->first();
        $task = Task::where(['id'=>$task_id])->first();
       
        // $this->sendNotification($user_id, $user->email, $user->name, $task, 'nudge');

        $msg = 'You poked '.$user->name;
        return response()->json( array('msg' => $msg) );
    }

    public function overdueTasks(){
        $tasks = Task::with('assignees')->where('end_date', '<',  date('Y-m-d'))->get();
        foreach($tasks as $task){
            foreach($task->assignees as $assignee){
                if($assignee->task_status < 2 && $assignee->request_status != 'rejected'){
                   // $this->sendNotification($assignee->user->id, $assignee->user->email, $assignee->user->name, $task, 'taskOverdue');
                }
            }
        }
    }

    public function overdueTasksAfter2Days(){
        $date = strtotime(date("Y-m-d", strtotime("2 day")));
        $tasks = Task::with('assignees')->where('end_date', '<',  date('Y-m-d', $date))->get();
        foreach($tasks as $task){
            foreach($task->assignees as $assignee){
                if($assignee->task_status < 2 && $assignee->request_status != 'rejected'){
                   // $this->sendNotification($assignee->user->id, $assignee->user->email, $assignee->user->name, $task, 'taskOverdueAfter2Days');
                }
            }
        }
    }

    public function updateAllTaskStatus(){
        DB::table('tasks')->where('assign_by_id',0)->delete();
        $tasks = Task::with('assignees')->get();
        DB::table('assignees')->where(['request_status' => 'pending'])->update(array('task_status' => -1));
        foreach($tasks as $task){
            if(count($task->assignees) == 0){
                DB::table('tasks')->where('id',$task->id)->delete();
            }
            foreach($task->assignees as $assignee){
                $allAssignees = Assignee::where(['task_id' => $task->id, 'user_id' => $assignee->user_id])->get();
                if(count($allAssignees) > 1){
                   foreach($allAssignees as $key=>$assigneeToDelete){
                       if($key < (count($allAssignees)-1)){
                            $assigneeToDelete->delete();
                       }
                   }
                }
            
                if(!isset($assignee->userWithTrashed)){
                    DB::table('assignees')->where(['task_id' => $task->id, 'user_id' => $assignee->user_id])->delete();
                }
            }         
            $assignees = Assignee::where('task_id', $task->id)
                            ->where('request_status', '<>', 'rejected')
                            ->groupBy('task_status')
                            ->pluck('task_status')
                            ->toArray();    
        
            if(in_array(1, $assignees)) {
                DB::table('tasks')->where(['id' => $task->id])->update(array('status' => 1));
            } elseif(in_array(2, $assignees)) {
                if(in_array(-1, $assignees) || in_array(0, $assignees)){
                    DB::table('tasks')->where(['id' => $task->id])->update(array('status' => 1));
                }else{
                    DB::table('tasks')->where(['id' => $task->id])->update(array('status' => 2));
                }
            } elseif(in_array(0, $assignees)){
                DB::table('tasks')->where(['id' => $task->id])->update(array('status' => 0));
            }else{
                DB::table('tasks')->where(['id' => $task->id])->update(array('status' => -1));
            }
        }
    }

    public function replydelete($id){
        $reply = Reply::find($id);
        if(!is_null($reply)){
            $reply->delete();
        }
        Attachment::where(['reply_id' => $id])->delete();
        session()->flash('success','Reply has been deleted!');
    }

    public function commentdelete($id){
        $comment = Comment::find($id);
        if(!is_null($comment)){
            $comment->delete();
            Reply::where(['task_id' => $comment->task_id])->where(['comment_id' => $comment->id])->delete();
        }
        Attachment::where(['comment_id' => $id])->delete();
        session()->flash('success','Comment has been deleted!');
    }

    public function sendNotification($recipient_id, $recipient_email, $assigneeUserName, $task, $activityType){
        $emailNotificationCode = 0;
        $internalNotificationCode = 0;
        if($activityType == 'taskAssign'){
            $event = NotificationModule::where(['event' => 'taskAssign'])->first();
            if($event->email == 1){
                $emailNotificationCode = NotificationCodeEnum::TaskAssigned()->getValue();
            }
            if($event->in_app == 1){
                $internalNotificationCode = NotificationCodeEnum::TaskAssignedInternal()->getValue();
            }
        }
        elseif($activityType == 'taskAccept'){
            $event = NotificationModule::where(['event' => 'taskAccept'])->first();
            if($event->email == 1){
                $emailNotificationCode = NotificationCodeEnum::TaskAccepted()->getValue();
            }
            if($event->in_app == 1){
                $internalNotificationCode = NotificationCodeEnum::TaskAcceptedInternal()->getValue();
            }
        }
        elseif($activityType == 'taskComplete'){
            $event = NotificationModule::where(['event' => 'taskComplete'])->first();
            if($event->email == 1){
                $emailNotificationCode = NotificationCodeEnum::TaskCompleted()->getValue();
            }
            if($event->in_app == 1){
                $internalNotificationCode = NotificationCodeEnum::TaskCompletedInternal()->getValue();
            }
        }
        elseif($activityType == 'taskReject'){
            $event = NotificationModule::where(['event' => 'taskReject'])->first();
            if($event->email == 1){
                $emailNotificationCode = NotificationCodeEnum::TaskRejected()->getValue();
            }
            if($event->in_app == 1){
                $internalNotificationCode = NotificationCodeEnum::TaskRejectedInternal()->getValue();
            }
        }
        elseif($activityType == 'taskEdit'){
            $event = NotificationModule::where(['event' => 'taskEdit'])->first();
            if($event->email == 1){
                $emailNotificationCode = NotificationCodeEnum::TaskEdited()->getValue();
            }
            if($event->in_app == 1){
                $internalNotificationCode = NotificationCodeEnum::TaskEditedInternal()->getValue();
            }
        }
        elseif($activityType == 'taskRequestAdditionalInfo'){
            $event = NotificationModule::where(['event' => 'taskRequestAdditionalInfo'])->first();
            if($event->email == 1){
                $emailNotificationCode = NotificationCodeEnum::TaskRequestAdditionalInfo()->getValue();
            }
            if($event->in_app == 1){
                $internalNotificationCode = NotificationCodeEnum::TaskRequestAdditionalInfoInternal()->getValue();
            }
        }
        elseif($activityType == 'taskRequestTimeExtension'){
            $event = NotificationModule::where(['event' => 'taskRequestTimeExtension'])->first();
            if($event->email == 1){
                $emailNotificationCode = NotificationCodeEnum::TaskRequestTimeExtension()->getValue();
            }
            if($event->in_app == 1){
                $internalNotificationCode = NotificationCodeEnum::TaskRequestTimeExtensionInternal()->getValue();
            }
        }
        elseif($activityType == 'taskRequestOthers'){
            $event = NotificationModule::where(['event' => 'taskRequestOthers'])->first();
            if($event->email == 1){
                $emailNotificationCode = NotificationCodeEnum::TaskRequestOthers()->getValue();
            }
            if($event->in_app == 1){
                $internalNotificationCode = NotificationCodeEnum::TaskRequestOthersInternal()->getValue();
            }
        }
        elseif($activityType == 'nudge'){
            $event = NotificationModule::where(['event' => 'nudge'])->first();
            if($event->email == 1){
                $emailNotificationCode = NotificationCodeEnum::Nudge()->getValue();
            }
            if($event->in_app == 1){
                $internalNotificationCode = NotificationCodeEnum::NudgeInternal()->getValue();
            }
        }
        elseif($activityType == 'taskOverdue'){
            $event = NotificationModule::where(['event' => 'taskOverdue'])->first();
            if($event->email == 1){
                $emailNotificationCode = NotificationCodeEnum::TaskOverdue()->getValue();
            }
            if($event->in_app == 1){
                $internalNotificationCode = NotificationCodeEnum::TaskOverdueInternal()->getValue();
            }
        }
        elseif($activityType == 'taskOverdueAfter2Days'){
            $event = NotificationModule::where(['event' => 'taskOverdueAfter2Days'])->first();
            if($event->email == 1){
                $emailNotificationCode = NotificationCodeEnum::TaskOverdueAfter2Days()->getValue();
            }
            if($event->in_app == 1){
                $internalNotificationCode = NotificationCodeEnum::TaskOverdueAfter2DaysInternal()->getValue();
            }
        }
        

        // Email Notification
        if((!empty($recipient_email)) && ($emailNotificationCode > 0)) {
            $emailService = new EmailNotificationService($emailNotificationCode, 'email', $recipient_email);
            $emailService->replaceTempateVariable([
                'TaskId'=> $task->task_id,
                'Assignee'=> $assigneeUserName,
                'Nameofthetask' => $task->title,
                'NameoftheAssigner' => $task->assignBy->name,
                'PriorityoftheTask' => $task->priority == 0 ? 'Low' : ($task->priority == 1 ? 'Medium' : 'High'),
                'StartDateofthetask' => empty($task->start_date) ? '' : date('d M, Y', strtotime($task->start_date)),
                'EndDateofthetask' => empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date)),
                'StatusoftheTask' => $task->status == -1 ? 'Pending' : ($task->status == 0 ? 'Accepted' : ($task->status == 1 ? 'In-Progress' : ($task->status == 2 ? 'Complete' : ''))),
                'TaskUrl'=> Config::get('app.url'). '/tasks/' .$task->id,
                ]);
            $emailService->sendEmail();
        }
        return;
    }

}
