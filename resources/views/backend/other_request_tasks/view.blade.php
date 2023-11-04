<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Management Platform</title>
    @include('backend.layouts.styles')
    {{-- <style>
        .dot {
            height: 20px;
            width: 20px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
        }
    </style> --}}
</head>
<body>
<div class="container-scroller task-details-main-page">
    <!-- partial:partials/_navbar.html -->
    @include('backend.layouts.header')
    <div class="container-fluid page-body-wrapper">
        @include('backend.layouts.sidebar')
        <div class="main-panel view-task-panel">
            <div class="content-wrapper">
                <div id="alert-show">
                    @include('backend.layouts.messages')
                </div>
                <div class="CreateTaskBox card-body">
                    <div class="row card-head mt-2">
                        <div class="col-md-7 col-sm-7">
                            <input type = "hidden" value="{{$task->id}}" id="task_id">
                            <h6 class="task-id-title">{{empty($task->task_id) ? '' : $task->task_id}}</h6>
                            <div class="Task-detail-view-title" data-toggle="tooltip" data-placement="right" title="{{$task->title}}" style="word-break:break-word;">{{empty($task->title) ? '' : (strlen($task->title) > 100 ? substr($task->title, 0, 100).'...' : $task->title)}}</div>
                        </div>
                        <div class="col-md-5 col-sm-5 text-end view-task-header-buttons">
                            <button class="btn priority-btn">
                                Priority: {{$task->priority == '0' ? 'Low' : ($task->priority == '1' ? 'Medium' : ($task->priority == '2' ? 'High' : ''))}}
                            </button>
                           
                            @if($task->status == -1)
                                <a href="{{route('myRequestTasks.edit', $task->id)}}" class="btn Rectangle-btn edit-task-btn">
                                    <i class="far fa-edit"></i>
                                    <span class="New-TAsk">EDIT TASK</span>
                                </a>
                            @endif
                           
                            @if($task->status == -1)
                            <button class="btn btn-inverse-secondary btn-fw">Pending</button>
                            @elseif($task->status == '1')
                            <button class="btn btn-inverse-warning btn-fw">Accepted</button>
                            @elseif($task->status == 0)
                            <button class="btn btn-inverse-danger btn-fw">Rejected</button>
                            @endif
                        </div>
                    </div>

                    <hr class="task-view-hr"/>

                    <div class="row assign-by-assign-to-block">
                        <div class="col-md-6 pl-0">
                            <label>Assigned By</label>
                            <div class="media" style="align-items: center;">
                                <div class="media-left">
                                @if(isset($task->assignByWithTrashed->image) && file_exists(public_path('backend/uploads/profile_images/'.$task->assignByWithTrashed->id.'/'.$task->assignByWithTrashed->image)))
                                    <img src="{!! URL::to('public/backend/uploads/profile_images/'.$task->assignByWithTrashed->id.'/'.$task->assignByWithTrashed->image) !!}" height="55" width="55" style="border-radius: 50%">
                                @else 
                                    <svg xmlns="http://www.w3.org/2000/svg" height="55" width="55" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                @endif
                                </div>
                                <div class="media-body">&nbsp;&nbsp;{{empty($task->assignByWithTrashed->name) ? '' : $task->assignByWithTrashed->name}}</div>
                            </div>
                            <br>
                            <div class="row date-row">
                                <div class="col-6">
                                    <div class="task-element">
                                        <label>Start Date</label>
                                        <br>
                                        <i class="fa fa-calendar"><span>{{empty($task->start_date) ? '' : date('d M, Y', strtotime($task->start_date))}}</span></i>
                                      </div>
                                </div>
                                <div class="col-6">
                                    <div class="task-element">
                                        <label>Due Date</label>
                                        
                                        @if(!empty($task->end_date) && $task->end_date < date('Y-m-d') && $task->status < 2)
                                            <p class="assign-to-me-badge badge badge-danger">Overdue</p> 
                                        @endif
                                        <br>
                                        <i class="fa fa-calendar">
                                        <span> 
                                            {{empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))}}
                                        </span>
                                        </i>
                                      </div>
                                </div>
                            </div>                            
                            {{-- <div class="task-element" style="float:left;">
                              <div class="Task-view-title" style="font-size:14px;">Priority</div>
                              <p>{{$task->priority == '0' ? 'Low' : ($task->priority == '1' ? 'Medium' : ($task->priority == '2' ? 'High' : ''))}}</p>
                            </div> --}}
                        </div>

                        <div class="col-md-6 assign-to-block">
                            <div class="Task-Assign-To">
                                <div class="row">
                                    <div class="col-9">
                                       <div class="Task-view-title">Assigned To</div>
                                   </div>
                                    <div class="col-3">
                                        <div class="Task-view-title">Status</div>
                                    </div>
                                </div>
                                <div class="task-view-assigned-to-card">
                                    
                                    <div class="row task-view-assigned-to-card-row pt-3">
                                        <div class="col-9">
                                            <div class="media" style="align-items: center;">
                                                <div class="media-left">
                                                    @if(isset($task->requestFrom->image) && file_exists(public_path('backend/uploads/profile_images/'.$task->requestFrom->id.'/'.$task->requestFrom->image)))
                                                        <img src="{!! URL::to('public/backend/uploads/profile_images/'.$task->requestFrom->id.'/'.$task->requestFrom->image)!!}" height="45" width="45" style="border-radius: 50%">
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="45" width="45" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg> 
                                                    @endif
                                                </div>
                                                <div class="media-body">&nbsp;&nbsp;{{empty($task->requestFrom->name) ? '' : $task->requestFrom->name}}{{empty($task->requestFrom->program) ? '' : ' ('.$task->requestFrom->program.')'}}</div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <td>
                                                @if($task->status == 1)
                                                <button class="btn btn-inverse-warning btn-fw task-status">Accepted</button>
                                                @else
                                                <button class="btn btn-inverse-secondary btn-fw task-status">Pending</button>
                                                @endif
                                            </td>
                                        </div>
                                    </div>
                                        
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 description-block" >
                            <label>Description</label>
                            <p>{!! html_entity_decode($task->description) !!}</p>
                        </div>
                    </div>
                    @if(count($task->attachments) > 0)
                        <div class="row" style="margin-top: 20px;">
                            <label>Attachments</label>
                            @foreach($task->attachments as $attachment)
                                <div>
                                    <input type="checkbox" disabled>
                                    <a href="{{route('request.file.download',['taskId' => $task->id])}}">{{empty($attachment->file_name) ? '' : $attachment->file_name}}</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="row p-3" id="tabId">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Comments <span class="dot">{{count($task->comments)}}</span></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">History <span class="dot">{{count($task->histories)}}</span></button>
                            </li>
                        </ul>
                        <div class="tab-content viewTaskTabPanel" id="myTabContent">
                            <div class="tab-pane fade show active taskCommentsSection" id="home" role="tabpanel" aria-labelledby="home-tab">
                                @if(count($task->comments) > 0)
                                    @foreach($task->comments as $comment)
                                        <div class="media comment">
                                            <div class="media-left">
                                                @if(isset($comment->user->image) && file_exists(public_path('backend/uploads/profile_images/'.$comment->user->id.'/'.$comment->user->image)))
                                                    <img src="{!! URL::to('public/backend/uploads/profile_images/'.$comment->user->id.'/'.$comment->user->image)!!}" height="40" width="40" style="border-radius: 50%">
                                                    &nbsp;&nbsp;
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="45" width="45" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                    &nbsp;&nbsp;
                                                @endif
                                            </div>
                                            <div class="media-body">
                                                <div class="comment-detail-{{$comment->id}}">
                                                    <p>
                                                        <span class="comment-by">{{empty($comment->user->name) ? '' : $comment->user->name}}</span>
                                                        <span class="Hour-ago">{{empty($comment->created_at) ? '' : date_format($comment->created_at, 'd M, Y h:i:s A')}}</span>
                                                        @if(\Illuminate\Support\Facades\Auth::user()['id'] == $comment->user_id)
                                                            {{-- <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{route('comment.delete', $comment->id)}}" onclick="deleteData('delete-comment-form-{{$comment->id}}')">
                                                                <i class="fa fa-trash" style="margin-right: 5px;"></i>
                                                                Delete
                                                            </a> --}}
                                                            <button class="Rectangle-Delete delete-btn-for-reply" style="text-decoration: none;" onclick="editData('{{$comment->id}}')">
                                                                <i class="fa fa-edit" style="margin-right: 5px;"></i>
                                                            </button>
                                                            <a class="Rectangle-Delete delete-btn-for-reply" style="text-decoration: none;" href="{{route('comment.delete', $comment->id)}}" onclick="deleteData('delete-comment-form-{{$comment->id}}')">
                                                                <i class="fa fa-trash" style="margin-right: 5px;"></i>
                                                            </a>
                                                        @endif
                                                        <form id="delete-comment-form-{{$comment->id}}" action="{{route('comment.delete', $comment->id)}}" method="POST" class="d-none" style="display: none">
                                                        @csrf
                                                        </form>
                                                    </p>
                                                    <p>{!! $comment->text !!}</p>
                                                    @if(count($comment->attachments) > 0)
                                                        @foreach($comment->attachments as $attachment)
                                                            <div class="hand-icon-added">
                                                                <i class="fas fa-hand-point-right"></i>
                                                                <a href="{{route('download.commentFile',['commentId' => $comment->id])}}">{{empty($attachment->file_name) ? '' : $attachment->file_name}}</a>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <form method="POST" action="{{route('comment.edit', $comment->id)}}" class="comment-form comment-edit-form-{{$comment->id}}" id="comment-edit-form-{{$comment->id}}" enctype="multipart/form-data" style="display:none;">
                                                    @csrf
                                                    <div class="form-group my-3">
                                                        <textarea class="form-control reply-textarea ckeditor"  name="text-comment-edit-form-{{$comment->id}}" rows="3">{!! $comment->text !!}</textarea>
                                                        <p style="color:red;" class="comment-error"></p>
                                                    </div>
                                                    <button type="submit" class="btn comment-btn float-right">Submit</button>
                                                </form>
                                                <a onclick="showReplyField('comment-{{$comment->id}}')" class="comment-reply-link">Reply</a>
                                                <form method="POST" class="comment-form" action="{{route('reply',$comment->id)}}" id="comment-{{$comment->id}}" style="display: none;" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group mt-3">
                                                        <textarea class="form-control reply-textarea ckeditor" name="text-comment-{{$comment->id}}" rows="3"></textarea>
                                                        <p style="color:red;" class="comment-error"></p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Attachments</label>
                                                        <div id="filepond-error-comment-{{$comment->id}}"></div>
                                                        <input type="file" class="file-pond" name="attachments[]" multiple>
                                                    </div>
                                                    <button type="submit" class="btn btn-sm comment-btn float-right">Reply</button>
                                                </form>
                                           </div>
                                        </div>
                                        @if(count($comment->replies) > 0)
                                            @foreach($comment->replies as $reply)
                                                <div class="comment-reply-div">
                                                    <div class="media">
                                                        <div class="media-left">
                                                        @if(isset($reply->user->image) && file_exists(public_path('backend/uploads/profile_images/'.$reply->user->id.'/'.$reply->user->image)))
                                                            <img src="{!! URL::to('public/backend/uploads/profile_images/'.$reply->user->id.'/'.$reply->user->image)!!}" height="40" width="40" style="border-radius: 50%">
                                                            &nbsp;&nbsp;
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="45" viewBox="0 0 24 24" width="45"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                            &nbsp;&nbsp;
                                                        @endif
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="reply-detail-{{$reply->id}}">
                                                                <p>
                                                                    <span class="comment-by">{{empty($reply->user->name) ? '' : $reply->user->name}}</span>
                                                                    <span class="Hour-ago">{{empty($reply->created_at) ? '' : date_format($reply->created_at, 'd M, Y h:i:s A')}}</span>

                                                                    @if(\Illuminate\Support\Facades\Auth::user()['id'] == $reply->user_id)
                                                                        {{-- <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{route('requestTask.reply.delete', $reply->id)}}" onclick="deleteData('delete-reply-form-{{$reply->id}}')">
                                                                            <i class="fa fa-trash" style="margin-right: 5px;"></i>
                                                                            Delete
                                                                        </a> --}}
                                                                        <button class="Rectangle-Delete delete-btn-for-reply" style="text-decoration: none;" onclick="editDataReply('{{$reply->id}}')">
                                                                            <i class="fa fa-edit" style="margin-right: 5px;"></i>
                                                                        </button>
                                                                        <a class="Rectangle-Delete delete-btn-for-reply" style="text-decoration: none;" href="{{route('reply.delete', $reply->id)}}" onclick="deleteData('delete-reply-form-{{$reply->id}}')">
                                                                            <i class="fa fa-trash" style="margin-right: 5px;"></i>
                                                                        </a>
                                                                    @endif
                                                                    <form id="delete-reply-form-{{$reply->id}}" action="{{route('requestTask.reply.delete', $reply->id)}}" method="POST" class="d-none" style="display: none">
                                                                        @csrf
                                                                    </form>
                                                                </p>
                                                                <p>{!! $reply->text !!}</p>
                                                                @if(count($reply->attachments) > 0)
                                                                    @foreach($reply->attachments as $attachment)
                                                                        <div class="hand-icon-added">
                                                                            <!-- <input type="checkbox" disabled> -->
                                                                            <i class="fas fa-hand-point-right"></i>
                                                                            <a href="{{route('requestTask.download.replyFile',['replyId' => $reply->id])}}">{{empty($attachment->file_name) ? '' : $attachment->file_name}}</a>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                                <!-- <a onclick="showReplyField('reply-{{$reply->id}}')" class="comment-reply-link">Reply</a>
                                                                <form method="POST" action="{{route('reply',$comment->id)}}" id="reply-{{$reply->id}}" style="display: none;">
                                                                    @csrf
                                                                    <div class="form-group" style="margin-top: 10px;">
                                                                        <textarea class="form-control" name="text" rows="3" style="box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.16);background-color: #ffffff;"></textarea>
                                                                    </div>
                                                                    <input type="submit" value="Comment" style="border-color: #ec008c; color:#ec008c; background-color: white;">

                                                                </form> -->
                                                            </div>
                                                            <form method="POST" action="{{route('requestTask.reply.edit', $reply->id)}}" class="comment-form reply-edit-form-{{$reply->id}}" id="reply-edit-form-{{$reply->id}}" enctype="multipart/form-data" style="display:none;">
                                                                @csrf
                                                                <div class="form-group my-3">
                                                                    <textarea class="form-control reply-textarea ckeditor" name="text-reply-edit-form-{{$reply->id}}" rows="3">{!! $reply->text !!}</textarea>
                                                                    <p style="color:red;" class="comment-error"></p>
                                                                </div>
                                                                <button type="submit" class="btn comment-btn float-right">Submit</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                                <form method="POST" action="{{route('requestTask.comment', $task->id)}}" class="comment-form" id="comment-empty" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group my-3">
                                        <textarea class="form-control reply-textarea ckeditor" name="text" rows="3"></textarea>
                                        <p style="color:red;" class="comment-error"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Attachments</label>
                                        <div id="filepond-error-empty"></div>
                                        <input type="file" id="file-pond-empty" name="attachments[]" multiple>
                                    </div>
                                    <button type="submit" class="btn comment-btn float-right">Submit</button>
                                </form>
                            </div>
                            <div class="tab-pane fade taskHistorySection" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                @foreach($task->histories as $history)
                                    
                                    <div class="media align-items-center">
                                        <div class="media-left">
                                            @if(isset($history->user->image) && file_exists(public_path('backend/uploads/profile_images/'.$history->user->id.'/'.$history->user->image)))
                                                <img src="{!! URL::to('public/backend/uploads/profile_images/'.$history->user->id.'/'.$history->user->image)!!}" height="40" width="40" style="border-radius: 50%">
                                                &nbsp;&nbsp;
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" height="45" width="45" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                &nbsp;&nbsp;
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            <div>
                                                <span class="comment-by">{{empty($history->user->name) ? '' : $history->user->name}} </span>
                                                {{empty($history->message) ? '' : $history->message}}
                                            </div>
                                            <div class="history-comment">{!! html_entity_decode($history->comment) !!}</div>
                                            <div class="Hour-ago">{{empty($history->created_at) ? '' : date_format($history->created_at, 'd M, Y h:i:s A')}}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
            {{--            footer--}}
            <!-- partial -->
    </div>
            <!-- main-panel ends -->
</div>
    <!-- page-body-wrapper ends -->
    <!-- container-scroller -->
    @include('backend.layouts.scripts')
<script>
        CKEDITOR.editorConfig = function( config ) {
            config.fullPage = true;
        };
            const input = document.querySelector("#file-pond-empty");
            const pond = FilePond.create(input);
            var filepond_id = '';
            var filenames = [];
            var filenames_reply = [];

            pond.setOptions({
                server:{
                    process: {
                        url: '/project_management_tool/uploadFilesComment',
                        headers: {
                            'X-CSRF-TOKEN': '{!! csrf_token() !!}'
                        }
                    }
                }
            });
            pond.on('addfile',
                function(error, file){
                    console.log(filenames);
                    if(filenames.includes(file.filename)){
                        error = {
                            main: 'duplicate',
                            sub: 'A file with that name already exists in the pond.'
                        }
                    }
                    if(error) handleFileError(error, file, 'filepond-error-empty');
                    filenames.push(file.filename);
                });

            pond.on('removefile',
                function(error, file){
                    var index = filenames.indexOf(file.filename);
                    filenames.splice(index, 1);
                });

            function handleFileError(error, file, id){
                let err = document.querySelector("#"+id);
                err.innerHTML = file.filename + " cannot be loaded " + error.sub;
                pond.removeFile(file);
            }
            

            $(document).ready(function() {
                commentSubmit();
                dataTableInitialize();
            });
            function dataTableInitialize(){
                $('.table').DataTable({
                    targets: 'no-sort',
                    bSort: false,
                    order: [],
                    searching: false,
                    pageLength: 2,
                    lengthChange: false
                });
            }
            function showReplyField(id){
                $('.comment-form').hide();
                $('#comment-empty').show();
                if($("#"+id).is(":visible")){
                    $("#"+id).hide();
                }else{
                    $("#"+id).show();
                    filenames_reply = [];
                    filepondReply(id);
                }
                
            }

            function filepondReply(id){
                const input1 = document.querySelector("#"+id+" .file-pond");
                const pond1 = FilePond.create(input1);

                pond1.setOptions({
                    server:{
                        process: {
                            url: '/project_management_tool/uploadFilesReply',
                            headers: {
                                'X-CSRF-TOKEN': '{!! csrf_token() !!}'
                            }
                        }
                    }
                });
                pond1.on('addfile',
                    function(error, file){
                        console.log(filenames_reply);
                        if(filenames_reply.includes(file.filename)){
                            error = {
                                main: 'duplicate',
                                sub: 'A file with that name already exists in the pond.'
                            }
                        }
                        var error_id = 'filepond-error-'+id;
                        if(error){
                            let err = document.querySelector("#"+error_id);
                            err.innerHTML = file.filename + " cannot be loaded " + error.sub;
                            pond1.removeFile(file);
                        }
                        filenames_reply.push(file.filename);
                    });

                pond1.on('removefile',
                    function(error, file){
                        var index = filenames_reply.indexOf(file.filename);
                        filenames_reply.splice(index, 1);
                    });

            }
            
            $(".fa-calendar-o").on("click", function(){
                $(this).siblings("input").datepicker({
                    forceParse:false,
                    autoclose: true,
                    immediateUpdates: true,
                    todayBtn: true,
                    todayHighlight: true
                });
                // $(this).siblings("input").datepicker('update', new Date());
                $(this).siblings("input").datepicker('show');
            });

            $('.date-picker').on("change",function(){
                const monthNames = ["January", "February", "March", "April", "May", "June",
                                    "July", "August", "September", "October", "November", "December"];
                const d = $(this).val().split('/');
                const date = d[1] + " " +monthNames[Number(d[0]-1)] + ', '+ d[2]+ ' ';
                $(this).val(date);
            });

        
            function commentSubmit(){
                $('.comment-form').submit(function(e){
                    e.preventDefault();
                
                    var formData = $(this).serializeArray();
                    var flag = '';

                    if($(this).attr('id') == 'comment-empty'){
                        var comment = CKEDITOR.instances.text.getData();
                        formData.push({name: "text", value: comment});
                        if(filenames.length > 0){
                            for (let i = 0; i < filenames.length; i++) {
                                formData.push({name: "attach_files[]", value: filenames[i]});
                            }
                        }else{
                            if(comment == ''){
                                flag='comment';
                                $(this).find('.comment-error').html('Text is required.');
                            }
                        }
                    }else{
                        var id = "text-"+$(this).attr('id');
                        var comment = CKEDITOR.instances[id].getData();
                        formData.push({name: "text", value: comment});
                        if(filenames_reply.length > 0){
                            for (let i = 0; i < filenames_reply.length; i++) {
                                formData.push({name: "attach_files[]", value: filenames_reply[i]});
                            }
                        }else{
                            if(comment == ''){
                                flag='comment';
                                $(this).find('.comment-error').html('Text is required.');
                            }
                        }
                    }
                    if(flag==''){
                        $.ajax({
                            url: $(this).attr('action'),
                            method: $(this).attr('method'),
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: formData,
                            success: function (data) {
                                window.location = '/myRequestTasks/'+$("#task_id").val();
                            },
                            error: function (xhr, status, errorThrown) {
                                console.log(xhr.responseText);
                                //Here the status code can be retrieved like;
                                // xhr.status;

                                //The message added to Response object in Controller can be retrieved as following.
                                alert(xhr.responseText);
                            }
                        });
                    }
                });
            }

            function deleteData(id){
                event.preventDefault();
                swal({
                        title: "Are you sure?",
                        type: "error",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "CONFIRM",
                        cancelButtonText: "CANCEL",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function() {
                        $.ajax({
                            url: $("#" + id).attr('action'),
                            method: 'POST',
                            data: $("#" + id).serializeArray(),
                            success: function () {
                                location.reload();
                            }
                        });
                    }
                );
            }

            function editData(id){
                event.preventDefault();
                $('.comment-detail-'+id).hide();
                $('.comment-edit-form-'+id).show();
                commentSubmit();
            }

            function editDataReply(id){
                event.preventDefault();
                $('.reply-detail-'+id).hide();
                $('.reply-edit-form-'+id).show();
                commentSubmit();
            }
        </script>
</body>
</html>
