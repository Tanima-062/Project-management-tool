<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification\Notifier;
use Illuminate\Support\Facades\Session;
use App\Modals\RequestTask;
use App\Modals\Assignee;
use App\Modals\TemporaryFile;
Use App\Modals\User;
Use App\Modals\Task;
Use App\Modals\History;
Use App\Modals\Attachment;
Use App\Modals\Reply;
Use App\Modals\Comment;
use DateTime;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MyRequestTasksExport;
use App\Services\Notification\EmailNotificationService;
use App\Services\Notification\NotifierService;
use App\Modals\NotificationModule;
use App\Enums\Notification\NotificationCodeEnum;
use Config;
use FileVault;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Modals\Member;
use App\Modals\Tag;
use App\Modals\TaskTag;

class MyRequestTasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Session::has('my.request.task.filter')){
            Session::forget('my.request.task.filter');
        }
        $auth_user_id = Auth::user()['id'];
        $tasks_for_list = RequestTask::where('request_from',$auth_user_id)->orderBy('id','desc')->paginate(10);
        $notifications = Notifier::getNotifier($auth_user_id);
        return view('backend.my_request_tasks.list',compact('tasks_for_list','notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        $tags = Tag::all();
        return view('backend.my_request_tasks.create',compact('notifications','tags'));
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
        $user = User::where(['id' => $auth_user_id])->first();

        if(isset($user->supervisor) && ($user->supervisor != $auth_user_id)){

            $task = new RequestTask();
        
            $task->assign_by_id = $user->supervisor;
            $task->request_to = $user->supervisor;
            $task->request_from = $auth_user_id;
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
            $task->status = -1;
            $task->save();

            if ($request->comment) {
                $comment = new Comment();
                $comment->request_task_id = $task->id;
                $comment->user_id = Auth::user()['id'];
                $comment->text = $request->comment;
                $comment->save();
            }

            $task = RequestTask::find($task->id);
            $task->task_id = 'R' . str_pad($task->id, 5, '0', STR_PAD_LEFT);
            $task->save();

            $task_tags = [];
            if(isset($request->tags)){
                foreach($request->tags as $tag){
                    array_push($task_tags,['request_task_id' => $task->id, 'tag_id' => $tag]);
                }
            }
            DB::table('task_tags')->insert($task_tags);

            if(!empty($task->assignBy->email)){
                //$this->sendNotification($task->assignBy->id, $task->assignBy->email, $task->requestFrom->name, $task, 'taskRequest');
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

            $history = new History();
            $history->task_id = $task->id;
            $history->user_id = $task->request_from;
            $history->request_type = 'task.request.create';
            $history->message = 'created this task.';
            $history->save();

            session()->flash('success', 'Request Task has been created!');
            return response()->json( array('success' => true) );
        }else{
            $task = new Task();
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
            $task->status = 0;
            $task->assign_by_id = $auth_user_id;
            $task->assign_to = 'individual';
            
            $task->save();

            $task->task_id = 'T' . str_pad($task->id, 5, '0', STR_PAD_LEFT);
            $task->main_parent_id = $task->id;
            $task->save();

            $task_tags = [];
            foreach($request->tags as $tag){
                array_push($task_tags,['task_id' => $task->id, 'tag_id' => $tag]);
            }
            DB::table('task_tags')->insert($task_tags);

            $assignee = new Assignee();
            $assignee->user_id = $auth_user_id;
            $assignee->task_id = $task->id;
            $assignee->task_status = 0;
            $assignee->request_status = 'accepted';

            $member = Member::where(['user_id' => $auth_user_id])->first();
            $assignee->team_id = 0;
            if($member){
                $assignee->team_id = $member->team_id;
            }

            $assignee->save();

            $assigneeUser = User::where(['id' => $auth_user_id])->first();
            if(!empty($assigneeUser->email)){
                //$this->sendNotification($assigneeUser->id, $assigneeUser->email, $request_task->requestFrom->name, $request_task, 'taskRequestAccept');
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
        $task = RequestTask::with('assignBy','attachments','comments')
                ->with(['histories' => function($q){
                    $q->orderBy('id','desc');
                }])->find($id);
        if($task == null){
            session()->flash('error', 'This task doesn\'t exists!');
            return redirect()->route('myRequestTasks.index');
        }
        return view('backend.my_request_tasks.view',compact('notifications','task'));
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
        return view('backend.my_request_tasks.edit',compact('notifications','task'));
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
        $flag = false;

        $task->title = $request->title;
        if(isset($request->description)){
            $task->description = $request->description;
        }

        if(isset($request->start_date) && isse($request->end_date)){
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
        if($task->status == 0){
            $flag = true;
            $task->status = -1;
        }
        $task->save();

        if(isset($request->tags)){
            foreach($request->tags as $tag){
                array_push($task_tags,['request_task_id' => $task->id, 'tag_id' => $tag]);
            }
        }
        TaskTag::where(['request_task_id' => $task->id])->delete();
        DB::table('task_tags')->insert($task_tags);

        if(($flag == true) && (!empty($task->assignBy->email))){
            //$this->sendNotification($task->assignBy->id, $task->assignBy->email, $task->requestFrom->name, $task, 'taskRequest');
        }
        
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

        $history = new History();
        $history->task_id = $task->id;
        $history->user_id = $task->request_from;
        $history->request_type = 'task.request.edit';
        $history->message = 'updated this task.';
        $history->save();

        session()->flash('success', 'Request Task has been updated!');
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
        $task = DB::table('request_tasks')->where('id',$id)->delete();
        return response()->json( array('msg' => 'Request Task has been deleted') );
    }

    public function downloadFile($taskId){
        $attachment = Attachment::where(['request_task_id' => $taskId])->first();
        if($attachment->file_path == 'public'){
            $path = public_path('backend'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'request_tasks'.DIRECTORY_SEPARATOR.$attachment->request_task_id.DIRECTORY_SEPARATOR.$fileName);
            return response()->download($path);
        }else{
            $fileName = $attachment->file_name;
            return response()->streamDownload(function () use ($taskId,$fileName) {
                FileVault::streamDecrypt('backend/uploads/request_tasks/' . $taskId. '/'. $fileName.'.enc');
            }, Str::replaceLast('.enc', '', $fileName)); 
        }
    }

    public function search(Request $request){
        Session::put('my.request.task.filter', $request->all());
        $auth_user_id = Auth::user()['id'];
        $task_query = RequestTask::where(['request_from' => $auth_user_id]);
    
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
                    return response()->json( array('success' => true, 'view'=>view('backend.my_request_tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
                }elseif($request->order == 'desc'){
                    $tasks_for_list = $task_query->orderByRaw('FIELD(priority, 1, 0, 2)')->paginate(10);
                    return response()->json( array('success' => true, 'view'=>view('backend.my_request_tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
                }
            }
            if($request->coloumn == 'status'){
                if($request->order == 'asc'){
                    $tasks_for_list = $task_query->orderByRaw('FIELD(status, 1, -1, 0)')->paginate(10);
                    return response()->json( array('success' => true, 'view'=>view('backend.my_request_tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
                }elseif($request->order == 'desc'){
                    $tasks_for_list = $task_query->orderByRaw('FIELD(status, 0, -1, 1)')->paginate(10);
                    return response()->json( array('success' => true, 'view'=>view('backend.my_request_tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
                }
            }
            $tasks_for_list = $task_query->orderBy($request->coloumn, $request->order)->paginate(10);
            return response()->json( array('success' => true, 'view'=>view('backend.my_request_tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
        }
        $tasks_for_list = $task_query->orderBy('id','desc')->paginate(10);
        return response()->json( array('success' => true, 'view'=>view('backend.my_request_tasks.task_table',['tasks_for_list' => $tasks_for_list])->render()) );
    }
    public function export(){
        return Excel::download(new MyRequestTasksExport,'my_request_tasks.xlsx');
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
        $comment->request_task_id = $task_id;
        $comment->user_id = Auth::user()['id'];
        $comment->text = $request->text;
        $comment->save();

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
                FileVault::encrypt('backend/uploads/comments/' . $comment->id . '/' . $file_name);
                array_push($attachments, ['comment_id' => $comment->id, 'file_name' => $file_name, 'file_path' => 'storage']);
                
            }
            DB::table('attachments')->insert($attachments);
        }

        Storage::deleteDirectory('backend/uploads/comments/tmp');

        session()->flash('success','Comment has been saved.');
    }

    public function commentEdit(Request $request,$id){
        $comment = Comment::find($id);
        $comment->text = $request->text;
        $comment->save();

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
    }
    public function replySave(Request $request,$id){

        $comment = Comment::find($id);
        $reply = new Reply();
        $reply->comment_id = $comment->id;
        $reply->request_task_id = $comment->request_task_id;
        $reply->user_id = Auth::user()['id'];
        $reply->text = $request->text;
        $reply->save();


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
                FileVault::encrypt('backend/uploads/replies/' . $reply->id . '/' . $file_name);
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
            Reply::where(['task_id' => $comment->request_task_id])->where(['comment_id' => $comment->id])->delete();
        }
        Attachment::where(['comment_id' => $id])->delete();
        session()->flash('success','Comment has been deleted!');
    }

    public function sendNotification($recipient_id, $recipient_email, $assigneeUserName, $task, $activityType){
        $emailNotificationCode = 0;
        $internalNotificationCode = 0;
        if($activityType == 'taskRequest'){
            $event = NotificationModule::where(['event' => 'taskRequest'])->first();
            if($event->email == 1){
                $emailNotificationCode = NotificationCodeEnum::TaskRequest()->getValue();
            }
            if($event->in_app == 1){
                $internalNotificationCode = NotificationCodeEnum::TaskRequestInternal()->getValue();
            }
        }
        
        if( $internalNotificationCode > 0){
            $task_url = '/otherRequestTasks/' .$task->id;
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
        //         'StatusoftheTask' => $task->status == -1 ? 'Pending' : ($task->status == 0 ? 'Accepted' : ($task->status == 1 ? 'In-Progress' : '')),
        //         'TaskUrl'=> Config::get('app.url'). '/otherRequestTasks/' .$task->id,
        //         ]);
        //     $emailService->sendEmail();
        // }
        return;
    }
}
