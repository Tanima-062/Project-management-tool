<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification\Notifier;
use Illuminate\Support\Facades\Session;
use App\Modals\RequestTask;
use App\Modals\Task;
use App\Modals\Assignee;
use App\Modals\Attachment;
use App\Modals\Member;
use DateTime;
use DB;
use App\Modals\TemporaryFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OtherRequestTasksExport;
Use App\Modals\Comment;
Use App\Modals\User;
use App\Services\Notification\EmailNotificationService;
use App\Services\Notification\NotifierService;
use App\Modals\NotificationModule;
Use App\Modals\History;
use App\Enums\Notification\NotificationCodeEnum;
use App\Modals\Reason;
use Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use FileVault;
use App\Modals\TaskTag;
use App\Modals\Tag;

class OtherRequestTasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Session::has('other.request.task.filter')){
            Session::forget('other.request.task.filter');
        }
        $auth_user_id = Auth::user()['id'];
        $tasks_for_list = RequestTask::where('request_to',$auth_user_id)->orderBy('id','desc')->paginate(10);
        $notifications = Notifier::getNotifier($auth_user_id);
        return view('backend.other_request_tasks.list',compact('tasks_for_list','notifications'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        $task = RequestTask::with('assignBy','attachments','comments','taskTags')
                ->with(['histories' => function($q){
                    $q->orderBy('id','desc');
                }])->find($id);

        if($task == null){
            session()->flash('error', 'This task doesn\'t exists!');
            return redirect()->route('otherRequestTasks.index');
        }
        $reasons = Reason::all();

        $tags = Tag::all();

        if($task->status == -1){
            return view('backend.other_request_tasks.update_request',compact('notifications','task','reasons','tags'));
        }else{
            return view('backend.other_request_tasks.view',compact('notifications','task'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        $task = RequestTask::with('attachments','comments')->find($id);
        $tags = Tag::all();
        return view('backend.other_request_tasks.edit',compact('notifications','task','tags'));
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
        $task = RequestTask::find($id);

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
        $task->save(); 

        $task_tags = [];
       
        if(isset($request->tags)){
            foreach($request->tags as $tag){
                array_push($task_tags,['request_task_id' => $task->id, 'tag_id' => $tag]);
            }
        }
        TaskTag::where(['request_task_id' => $task->id])->delete();
        DB::table('task_tags')->insert($task_tags);

        if ($request->comment) {
            $comment = new Comment();
            $comment->request_task_id = $task->id;
            $comment->user_id = Auth::user()['id'];
            $comment->text = $request->comment;
            $comment->save();
        } 

        if(empty($request->existing_attachments)){
            Attachment::where(['request_task_id' => $id])->delete();
        }else{
            Attachment::where(['request_task_id' => $id])->whereNotIn('file_name',$request->existing_attachments)->delete();
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
                $to = storage_path('app/backend/uploads/request_tasks/' . $task->id);
                if (!is_dir($to)) {
                    mkdir($to, 0777, true);
                }
                copy($from, $to . '/' . $file_name);
                FileVault::encrypt('backend/uploads/request_tasks/' . $task->id . '/' . $file_name);
                array_push($attachments, ['request_task_id' => $task->id, 'file_name' => $file_name, 'file_path' => 'storage']);
            }
            DB::table('attachments')->insert($attachments);
        }

        Storage::deleteDirectory('backend/uploads/tasks/tmp');

        $this->approveRequest($request, $id);

        session()->flash('success', 'Task has been approved successfully!');
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
        //
    }

    public function rejectRequest(Request $request){
        
        $task = RequestTask::find($request->taskId);

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

        $task->priority = $request->priority;
        $task->save(); 

        $task_tags = [];
       
        if(isset($request->tags)){
            foreach($request->tags as $tag){
                array_push($task_tags,['request_task_id' => $task->id, 'tag_id' => $tag]);
            }
        }
        TaskTag::where(['request_task_id' => $task->id])->delete();
        DB::table('task_tags')->insert($task_tags);


        if(empty($request->existing_attachments)){
            Attachment::where(['request_task_id' => $request->taskId])->delete();
        }else{
            Attachment::where(['request_task_id' => $request->taskId])->whereNotIn('file_name',$request->existing_attachments)->delete();
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
                $to = storage_path('app/backend/uploads/request_tasks/' . $task->id);
                if (!is_dir($to)) {
                    mkdir($to, 0777, true);
                }
                copy($from, $to . '/' . $file_name);
                FileVault::encrypt('backend/uploads/request_tasks/' . $task->id . '/' . $file_name);
                array_push($attachments, ['request_task_id' => $task->id, 'file_name' => $file_name, 'file_path' => 'storage']);
                
            }
            DB::table('attachments')->insert($attachments);
        }

        Storage::deleteDirectory('backend/uploads/tasks/tmp/');

        DB::table('request_tasks')->where('id',$request->taskId)->update(['status' => 0]);
        $request_task = RequestTask::where(['id'=>$request->taskId])->first();
        $assigneeUser = User::where(['id' => $request_task->request_from])->first();
        if(!empty($assigneeUser->email)){
            //$this->sendNotification($assigneeUser->id, $assigneeUser->email, $request_task->requestFrom->name, $request_task, 'taskRequestReject');
        }

        $reason = Reason::where('name',$request->reason)->first();
        $history = new History();
        $history->request_task_id = $request->taskId;
        $history->user_id = Auth::user()['id'];
        $history->request_type = $request->reason;
        $history->comment = $request->comment;
        $history->message = 'rejected task due to '.$reason->displayName;
        $history->save();

        session()->flash('error', 'Task has been rejected!');
        return response()->json( array('success' => true));

    }

    public function approveRequest(Request $request,$id)
    {
        DB::table('request_tasks')->where('id',$id)->update(['status' => 1]);
        $request_task = RequestTask::find($id);

        $task = new Task();
        $task->title = $request_task->title;
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
        $task->priority = $request_task->priority;
        $task->status = 0;
        $task->assign_by_id = $request_task->assign_by_id;
        $task->assign_to = $request_task->assign_to;
        
        $task->save();

        $task_tags = [];
        if(isset($request->tags)){
            foreach($request->tags as $tag){
                array_push($task_tags,['task_id' => $task->id, 'tag_id' => $tag]);
            }
        }
        DB::table('task_tags')->insert($task_tags);

        $task->task_id = 'T' . str_pad($task->id, 5, '0', STR_PAD_LEFT);
        $task->main_parent_id = $task->id;
        $task->save();

        $assignee = new Assignee();
        $assignee->user_id = $request_task->request_from;
        $assignee->task_id = $task->id;
        $assignee->task_status = 0;
        $assignee->request_status = 'accepted';

        $member = Member::where(['user_id' => $request_task->request_from])->first();
        $assignee->team_id = 0;
        if($member){
            $assignee->team_id = $member->team_id;
        }

        $assignee->save();

        DB::table('task_tags')->where('request_task_id',$id)->update(['task_id' => $task->id]);

        $assigneeUser = User::where(['id' => $request_task->request_from])->first();
        if(!empty($assigneeUser->email)){
            //$this->sendNotification($assigneeUser->id, $assigneeUser->email, $request_task->requestFrom->name, $request_task, 'taskRequestAccept');
        }

        $attachments = Attachment::where('request_task_id',$id)->get();
        foreach($attachments as $attachment) {
            $from = storage_path('app/backend/uploads/request_tasks/' .$attachment->request_task_id.'/'.$attachment->file_name.'.enc');
            $to = storage_path('app/backend/uploads/tasks/' . $task->id);
            if (!is_dir($to)) {
                mkdir($to, 0777, true);
            }
            copy($from, $to . '/' . $attachment->file_name.'.enc');   
        }

        DB::table('attachments')->where('request_task_id',$id)->update(['task_id' => $task->id]);
        DB::table('comments')->where('request_task_id',$id)->update(['task_id' => $task->id]); 
        DB::table('replies')->where('request_task_id',$id)->update(['task_id' => $task->id]);

        if(Route::is('otherRequestTasks.approve')){
            session()->flash('success', 'Task has been approved successfully!');
            return redirect()->route('otherRequestTasks.index');   
        }

    }

    public function search(Request $request){
        Session::put('other.request.task.filter', $request->all());
        $auth_user_id = Auth::user()['id'];
        $task_query = RequestTask::where(['request_to' => $auth_user_id]);
        if(isset($request->priority)){
            $task_query = $task_query->where(['priority' => $request->priority]);
        }
        if(isset($request->status)){
            $task_query = $task_query->where(['status' => $request->status]);
        }
        if(isset($request->start_date) && isset($request->end_date)){
            $task_query->whereBetween('start_date',[ date('Y-m-d', strtotime($request->start_date)), date('Y-m-d', strtotime($request->end_date))]);
            $task_query->where('end_date', '<=',  date('Y-m-d', strtotime($request->end_date)));
        }
        elseif(isset($request->start_date)){
            $task_query->where('start_date', '>=',  date('Y-m-d', strtotime($request->start_date)));
        }
        elseif(isset($request->end_date)){
            $task_query->where('end_date', '<=',  date('Y-m-d', strtotime($request->end_date)));
        } 
        if(isset($request->search_key)){
            $task_query->where('title','like','%'.$request->search_key.'%');
            $task_query->orWhere('task_id','like','%'.$request->search_key.'%');
        }

        if(isset($request->coloumn)){
            if($request->coloumn == 'priority'){ 
                if($request->order == 'asc'){
                    $tasks_for_list = $task_query->orderByRaw('FIELD(priority, 2, 0, 1)')->paginate(10);
                    return response()->json( array('success' => true, 'view'=>view('backend.other_request_tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
                }elseif($request->order == 'desc'){
                    $tasks_for_list = $task_query->orderByRaw('FIELD(priority, 1, 0, 2)')->paginate(10);
                    return response()->json( array('success' => true, 'view'=>view('backend.other_request_tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
                }
            }
            if($request->coloumn == 'status'){
                if($request->order == 'asc'){
                    $tasks_for_list = $task_query->orderByRaw('FIELD(status, 1, -1, 0)')->paginate(10);
                    return response()->json( array('success' => true, 'view'=>view('backend.other_request_tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
                }elseif($request->order == 'desc'){
                    $tasks_for_list = $task_query->orderByRaw('FIELD(status, 0, -1, 1)')->paginate(10);
                    return response()->json( array('success' => true, 'view'=>view('backend.other_request_tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
                }
            }
            $tasks_for_list = $task_query->orderBy($request->coloumn, $request->order)->paginate(10);
            return response()->json( array('success' => true, 'view'=>view('backend.other_request_tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
        }
        $tasks_for_list = $task_query->orderBy('id','desc')->paginate(10);
        return response()->json( array('success' => true, 'view'=>view('backend.other_request_tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
    }

    public function export(){
        return Excel::download(new OtherRequestTasksExport,'other_request_tasks.xlsx');
    }

    public function sendNotification($recipient_id, $recipient_email, $assigneeUserName, $task, $activityType){
        $emailNotificationCode = 0;
        $internalNotificationCode = 0;
        if($activityType == 'taskRequestAccept'){
            $event = NotificationModule::where(['event' => 'taskRequestAccept'])->first();
            if($event->email == 1){
                $emailNotificationCode = NotificationCodeEnum::TaskRequestAccepted()->getValue();
            }
            if($event->in_app == 1){
                $internalNotificationCode = NotificationCodeEnum::TaskRequestAcceptedInternal()->getValue();
            }
        }

        elseif($activityType == 'taskRequestReject'){
            $event = NotificationModule::where(['event' => 'taskRequestReject'])->first();
            if($event->email == 1){
                $emailNotificationCode = NotificationCodeEnum::TaskRequestRejected()->getValue();
            }
            if($event->in_app == 1){
                $internalNotificationCode = NotificationCodeEnum::TaskRequestRejectedInternal()->getValue();
            }
        }
        
        if( $internalNotificationCode > 0){
            $task_url =  '/myRequestTasks/' .$task->id;
            NotifierService::sendIndividualPerson($recipient_id, $task->id, $internalNotificationCode, $task_url, [
                'Nameofthetask' => $task->title,
                'TaskId' => $task->task_id,
                'Assignee' => $assigneeUserName,
                'NameoftheAssigner' => $task->assignBy->name
                ]);
        }

        // Email Notification
        // if((!empty($recipient_email)) && ($emailNotificationCode > 0)) {
        //     $emailService = new EmailNotificationService($emailNotificationCode, 'email', $recipient_email);
        //     $emailService->replaceTempateVariable([
        //         'TaskId'=> $task->task_id,
        //         'Assignee'=> $assigneeUserName,
        //         'Nameofthetask' => $task->title,
        //         'NameoftheAssigner' => $task->assignBy->name,
        //         'PriorityoftheTask' => $task->priority == 0 ? 'Low' : ($task->priority == 1 ? 'Medium' : 'High'),
        //         'StartDateofthetask' => empty($task->start_date) ? '' : date('d M, Y', strtotime($task->start_date)),
        //         'EndDateofthetask' => empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date)),
        //         'StatusoftheTask' => $task->status == -1 ? 'Pending' : ($task->status == 0 ? 'Rejected' : ($task->status == 1 ? 'Accepted'  : '')),
        //         'TaskUrl'=> Config::get('app.url'). '/myRequestTasks/' .$task->id,
        //         ]);
        //     $emailService->sendEmail();
        // }
        return;
    }
}
