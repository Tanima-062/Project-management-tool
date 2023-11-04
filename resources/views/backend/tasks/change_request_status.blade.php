<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    @include('backend.layouts.styles')
    <style>
        .dot {
            height: 25px;
            width: 25px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    @include('backend.layouts.header')
    <div class="container-fluid page-body-wrapper">
        @include('backend.layouts.sidebar')
        <div class="main-panel">
            <div class="content-wrapper" style="background-color: #eef9ff;">
                <div class="CreateTaskBox" style="margin-bottom: 20px;">
                    <div class="row justify-content-sm-end">
                        <div class="col-sm-9" style="margin-top: 20px;">
                            <h6 style="color: #0c63e4; margin-left: 30px;">{{$task->task_id}}</h6>
                            <h5 style="margin-left: 30px;">{{$task->title}}</h5>
                        </div>
                        <diV class="col-sm-3" style="margin-top: 20px;">
                            <form method="POST" action="{{route('task.updateRequestStatus',$task->id)}}">
                                @csrf
                                <input type="hidden" name="status" value="accepted">
                                <button style="border-color: #ec008c; color:#ec008c; margin-left: 40px;">Accept</button>
                            </form>
                            <button style="border-color: #ec008c; color:#ec008c; margin-left: 40px;" id="reject-btn">Rejected</button>
                        </diV>
                    </div>
                    <div class="row" style="margin-left: 16px; margin-top: 20px; max-width: 100%; height: auto;">
                        <div class="col-sm-6">
                            <h5>Assigned By</h5>
                            <div class="media">
                                <div class="media-left"><img src="{{asset('backend/assets/img/man.png')}}" height="50px;"></div>
                                <div class="media-body">{{$task->assignBy->name}}</div>
                            </div><br>
                            <h5>Due Date</h5>
                            <i class="fa fa-calendar"><span style="margin-left: 10px;">20 Feb 2021, 05.30 PM</span></i>
                        </div>
                        <div class="col-sm-5">
                            <div style="height:200px; width: 500px; box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.16);background-color: #ffffff; overflow-y: auto; overflow-x: hidden; height: 300px;">
                                <div class="row">
                                    <div class="col-9">
                                        <h5 style="margin-top: 20px; margin-left: 20px;">Assigned To</h5>
                                    </div>
                                    <div class="col">
                                        <h5 style="margin-top: 20px;">Status</h5>
                                    </div>
                                </div>
                                <div style="margin-top: 20px; margin-left: 20px;">
                                    @if($task->assign_to == 'individual')
                                        @foreach($task->assignees as $assignee)
                                            <div class="row">
                                                <div class="col-9">
                                                    <div class="media">
                                                        <div class="media-left"><img src="{{asset('backend/assets/img/man.png')}}" height="50px;"></div>
                                                        <div class="media-body">{{$assignee->user->name}}</div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    @if($assignee->status == '0')
                                                        <td><button class="btn btn-inverse-warning btn-fw" style="border-radius: 0; padding: 5px;">To Do</button></td>
                                                    @elseif($assignee->status == '1')
                                                        <td><button class="btn btn-inverse-info btn-fw" style="border-radius: 0; padding: 5px;">In progress</button></td>
                                                    @elseif($assignee->status)
                                                        <td><button class="btn btn-inverse-success btn-fw" style="border-radius: 0; padding: 5px;">Completed</button></td>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        @foreach($task->teamTasks as $team_task)
                                            <div class="row">
                                                <div class="col-9">
                                                    <div class="media">
                                                        <div class="media-left"><img src="{{asset('backend/assets/img/man.png')}}" height="50px;"></div>
                                                        <div class="media-body">{{$team_task->team->name}}</div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    @if($team_task->status == '0')
                                                        <td><button class="btn btn-inverse-warning btn-fw" style="border-radius: 0; padding: 5px;">To Do</button></td>
                                                    @elseif($team_task->status == '1')
                                                        <td><button class="btn btn-inverse-info btn-fw" style="border-radius: 0; padding: 5px;">In progress</button></td>
                                                    @elseif($team_task->status)
                                                        <td><button class="btn btn-inverse-success btn-fw" style="border-radius: 0; padding: 5px;">Completed</button></td>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-left:20px;margin-top: 20px;">
                        <h5>Description</h5>
                        <p>{{$task->description}}</p>
                    </div>
                    <div class="row justify-content-sm-between">
                        <div class="col-sm-2">
                            <div style="margin-left: 15px; margin-top: 20px;">
                                <h5 style="margin-left: 15px;">Sub Tasks</h5>
                                @foreach($sub_tasks as $sub_task)
                                    <div>
                                        <p>{{$sub_task->title}}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div style="margin-top: 20px;">
                                <h5 style="margin-left: 10px;">Assigned By</h5>
                                @foreach($sub_tasks as $sub_task)
                                    <div>
                                        <div class="media">
                                            <div class="media-left"><img src="{{asset('backend/assets/img/man.png')}}" height="50px;"></div>
                                            <div class="media-body">{{$sub_task->assignBy->name}}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div style="margin-left: 15px; margin-top: 20px;">
                                <h5 style="margin-left: 10px;">Assigned To</h5>
                                @foreach($sub_tasks as $sub_task)
                                    @if($sub_task->assign_to == 'individual')
                                        @foreach($sub_task->assignees as $assignee)
                                            <div class="media">
                                                <div class="media-left"><img src="{{asset('backend/assets/img/man.png')}}" height="50px;"></div>
                                                <div class="media-body">{{$assignee->user->name}}</div>
                                            </div>
                                        @endforeach
                                    @else
                                        @foreach($sub_task->teamTasks as $team_task)
                                            <div class="media">
                                                <div class="media-left"><img src="{{asset('backend/assets/img/man.png')}}" height="50px;"></div>
                                                <div class="media-body">{{$team_task->team->name}}</div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div style="margin-top: 20px;">
                                <h5>Status</h5>
                                <div><button class="btn btn-inverse-warning btn-fw" style="border-radius: 0; padding: 5px;">To Do</button></div><br>
                                <div><button class="btn btn-inverse-warning btn-fw" style="border-radius: 0; padding: 5px;">To Do</button></div>
                            </div>
                        </div>
                        <a href="{{route('subtasks.create',$task->id)}}">Create Sub Task</a>
                        <div class="col-sm-2">
                            <div style="margin-top: 20px;">
                                <h5>Due Time</h5>
                                <h6>{{$task->start_date}}</h6>
                            </div>
                        </div>
                    </div>
                    @if(!empty($task->attachments))
                        <div style="margin-left: 20px;margin-top: 20px;">
                            <h5>Attachments</h5>
                            @foreach($task->attachments as $attachment)
                                <div>
                                    <input type="checkbox" disabled>
                                    <a href="{{route('download',['taskId' => $task->task_id,'fileName' => $attachment->file_name])}}">{{$attachment->file_name}}</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="row" style="margin-left: 20px;margin-top: 20px; margin-right: 30px;">
                        <ul class="nav nav-tabs" id="requestTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="request-tab" data-bs-toggle="tab" data-bs-target="#request" type="button" role="tab" aria-controls="request" aria-selected="true">Requests</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="requestTabContent">
                            <form method="POST" action="{{route('request.save',$task->id)}}">
                                @csrf
                                <div class="form-group">
                                    <select id="reason" name="reason" class="form-control">
                                        <option selected>Select Reason</option>
                                        @foreach($request_types as $request_type)
                                            <option value="{{$request_type->name}}">{{$request_type->displayName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 date-show" style="display: none;">
                                    <label for="start_date">Due Time</label>
                                    <div class='right-inner-addon date datepicker' data-date-format="yyyy-mm-dd" style="box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.16);background-color: #ffffff;">
                                        <i class="fa fa-calendar-o"></i>
                                        <input name='extended_date' type="text" class="form-control date-picker" data-date-format="yyyy-mm-dd" autocomplete="off" readonly/>
                                    </div>
                                </div>
                                <div class="form-group comment-section" style="margin-top:20px;">
                                    <label for="comments">Comments</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="3" style="box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.16);background-color: #ffffff;"></textarea>
                                </div>
                                <div style="text-align: center">
                                    <a class="Cancel" href="{{route('dashboard')}}">Close</a>
                                    <button class="Create">Save</button>
                                </div>
                            </form>
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
        <script>
            $("#reject-btn").click(function (){
                $('html, body').animate({
                    scrollTop: $("#requestTab").offset().top
                });
            });
            $("#reason").change(function(){
                if($(this).val() == 'time.extension'){
                   $('.date-show').show();
                }else{
                    $('.date-show').hide();
                }
            });
        </script>
@include('backend.layouts.scripts')
    <script src="{{asset('backend/assets/templates/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
    <script>
        $('.date-picker').datepicker();
    </script>

</body>

</html>






