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
                        <div class="col-md-5 col-sm-5 pr-0">
                            <input type = "hidden" value="{{$task->id}}" id="task_id">
                            <h6 class="task-id-title">{{empty($task->task_id) ? '' : $task->task_id}}</h6>
                            <div class="Task-detail-view-title" data-toggle="tooltip" data-placement="right" title="{{$task->title}}">{{empty($task->title) ? '' : (strlen($task->title) > 50 ? substr($task->title, 0, 50).'...' : $task->title)}}</div>
                        </div>
                        <div class="col-md-7 col-sm-7 text-end view-task-header-buttons">
                            <button class="btn priority-btn">
                                Priority: {{$task->priority == '0' ? 'Low' : ($task->priority == '1' ? 'Medium' : ($task->priority == '2' ? 'High' : ''))}}
                            </button>
                            @if($task->assign_by_id == \Illuminate\Support\Facades\Auth::user()['id'])
                                <a href="{{route('tasks.edit', $task->id)}}" class="btn Rectangle-btn edit-task-btn">
                                    <i class="far fa-edit"></i>
                                    <span class="New-TAsk">EDIT TASK</span>
                                </a>
                            @endif
                            @if($request_status == 'pending')
                                <form method="POST" action="{{route('task.updateRequestStatus',$task->id)}}" class="float-right">
                                    @csrf
                                    <input type="hidden" name="status" value="accepted">
                                    <button type="submit" class="btn btn-success task-accept-button">Accept</button>
                                    <button type="button" class="btn btn-danger task-reject-button" id="reject-btn">Reject</button>
                                </form>
                            @else
                                @if(($task->assign_by_id == \Illuminate\Support\Facades\Auth::user()['id']) && ($task->status < 2))
                                    <a href="{{route('tasks.reassign',$task->id)}}">
                                        <button class="Rectangle-btn reassign-btn">
                                            <!-- <img src="{{asset('backend/assets/img/Reassign-Icon.png')}}" class="New-Task-Plus-Icon"> -->
                                            <i class="fas fa-exchange-alt"></i>
                                            <span class="New-TAsk">REASSIGN</span>
                                        </button>
                                    </a>
                                @endif
                                @if(count($task->assignees) > 0)
                                @php $show_wfh = \App\Modals\Task::showWFH($task); @endphp
                                @if($task->status == '-1')
                                        <td>
                                            <button class="btn btn-inverse-secondary btn-fw button-pending">Pending</button>
                                            @if($show_wfh)
                                                <button class="btn btn-fw WFH">WFH</button>
                                            @endif
                                        </td>
                                    @elseif($task->status == '0')
                                        <td>
                                            <button class="btn btn-inverse-warning btn-fw">Accepted</button>
                                            @if($show_wfh)
                                                <button class="btn btn-fw WFH">WFH</button>
                                            @endif
                                        </td>    
                                    @elseif($task->status == '1')
                                        <td>
                                            <button class="btn btn-inverse-info btn-fw">In Progress</button>
                                            @if($show_wfh)
                                                <button class="btn btn-fw WFH">WFH</button>
                                            @endif
                                        </td>
                                    @elseif($task->status == '2')
                                        <td>
                                            <button class="btn btn-inverse-success btn-fw">Completed</button>
                                            @if($show_wfh)
                                                <button class="btn btn-fw WFH">WFH</button>
                                            @endif
                                        </td>
                                    @elseif($task->status == '4')
                                        <td>
                                            <button class="btn btn-inverse-danger btn-fw">Rejected</button>
                                            @if($show_wfh)
                                                <button class="btn btn-fw WFH">WFH</button>
                                            @endif
                                        </td>
                                    @endif  
                                @endif
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
                                    @if($task->assign_to == 'individual')
                                        @foreach($task->assignees as $assignee)
                                            @if($assignee->request_status != 'rejected' && !empty($assignee->userWithTrashed))
                                                <div class="row task-view-assigned-to-card-row pt-3">
                                                    <div class="col-9">
                                                        <div class="media" style="align-items: center;">
                                                            @if($task->assign_by_id == \Illuminate\Support\Facades\Auth::user()['id'])
                                                                <a onclick="nuzz('nuzz-form-{{$assignee->user->id}}-{{$task->id}}');" href="{{route('tasks.nuzz',['user_id'=>$assignee->user->id,'task_id'=>$task->id])}}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 45 45">
                                                                        <g transform="translate(-1662 -269)">
                                                                            <rect width="45" height="45" fill="#f4fcef" rx="2" transform="translate(1662 269)"/>
                                                                            <g>
                                                                                <path fill="#fae249" d="M145.677 19.171h-8.01l4.505-9.512H132.16l-2.5 13.516h6.508l-6.007 15.519z" transform="translate(1669.379 276.481) translate(-121.544 -9.158)"/>
                                                                                <path d="M1.657 233.659h5.006v1H1.657v-1z" transform="translate(1669.379 276.481) translate(-1.553 -219.141)"/>
                                                                                <path d="M401.657 233.659h5.006v1h-5.006z" transform="translate(1669.379 276.481) translate(-376.523 -219.141)"/>
                                                                                <path d="M416.006 7.506l3.5-3.5.708.708-3.5 3.5z" transform="translate(1669.379 276.481) translate(-389.974 -3.855)"/>
                                                                                <path d="M.036 407.507l3.5-3.5.708.708-3.5 3.5z" transform="translate(1669.379 276.481) translate(-.034 -378.826)"/>
                                                                                <path d="M416 412.714l.708-.708 3.5 3.5-.708.708z" transform="translate(1669.379 276.481) translate(-389.971 -386.328)"/>
                                                                                <path d="M0 4.707L.708 4l3.5 3.5-.708.711z" transform="translate(1669.379 276.481) translate(0 -3.853)"/>
                                                                                <path d="M135.095 1.892a.5.5 0 0 0-.423-.233H124.66a.5.5 0 0 0-.492.41l-2.5 13.516a.5.5 0 0 0 .492.592h5.778l-5.748 14.837a.5.5 0 0 0 .859.492l15.519-19.524a.5.5 0 0 0-.392-.812h-7.219l4.167-8.8a.5.5 0 0 0-.029-.478zm-5.381 9.565a.5.5 0 0 0 .452.715h6.972L124.31 28.31l4.821-12.453a.5.5 0 0 0-.467-.681h-5.906l2.318-12.516h8.8z" transform="translate(1669.379 276.481) translate(-114.043 -1.659)"/>
                                                                            </g>
                                                                        </g>
                                                                    </svg>
                                                                </a>
                                                                <form id="nuzz-form-{{$assignee->userWithTrashed->id}}-{{$task->id}}" action="{{route('tasks.nuzz',['user_id'=>$assignee->userWithTrashed->id,'task_id'=>$task->id])}}" method="POST" class="d-none" style="display: none">
                                                                    @csrf
                                                                </form>
                                                                &nbsp;&nbsp;
                                                            @endif
                                                            <div class="media-left">
                                                                @if(isset($assignee->userWithTrashed->image) && file_exists(public_path('backend/uploads/profile_images/'.$assignee->userWithTrashed->id.'/'.$assignee->userWithTrashed->image)))
                                                                    <img src="{!! URL::to('public/backend/uploads/profile_images/'.$assignee->user->id.'/'.$assignee->user->image)!!}" height="45" width="45" style="border-radius: 50%">
                                                                @else
                                                                    <svg xmlns="http://www.w3.org/2000/svg" height="45" width="45" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg> 
                                                                @endif
                                                            </div>
                                                            <div class="media-body">&nbsp;&nbsp;{{empty($assignee->userWithTrashed->name) ? '' : $assignee->userWithTrashed->name}}{{empty($assignee->userWithTrashed->program) ? '' : ' ('.$assignee->userWithTrashed->program.')'}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                    @if($assignee->task_status == '-1')
                                                            <td>
                                                                <button class="btn btn-inverse-secondary btn-fw task-status">Pending</button>
                                                                @if($assignee->wfh == '1')
                                                                    <button class="btn btn-fw task-status WFH" style="padding:2px;">WFH</button>
                                                                @endif
                                                            </td>
                                                        @elseif($assignee->task_status == '0')
                                                            <td>
                                                                <button class="btn btn-inverse-warning btn-fw task-status">Accepted</button>
                                                                @if($assignee->wfh == '1')
                                                                    <button class="btn btn-fw task-status WFH">WFH</button>
                                                                @endif
                                                            </td>
                                                        @elseif($assignee->task_status == '1')
                                                            <td>
                                                                <button class="btn btn-inverse-info btn-fw task-status">In&nbsp;Progress</button>
                                                                @if($assignee->wfh == '1')
                                                                    <button class="btn btn-fw task-status WFH">WFH</button>
                                                                @endif
                                                            </td>
                                                        @elseif($assignee->task_status == '2')
                                                            <td>
                                                                <button class="btn btn-inverse-success btn-fw task-status">Completed</button>
                                                                @if($assignee->wfh == '1')
                                                                    <button class="btn btn-fw task-status WFH">WFH</button>
                                                                @endif
                                                            </td>
                                                        @elseif($assignee->task_status == '4')
                                                            <td>
                                                                <button class="btn btn-inverse-danger btn-fw task-status">Rejected</button>
                                                                @if($assignee->wfh == '1')
                                                                    <button class="btn btn-fw task-status WFH">WFH</button>
                                                                @endif
                                                            </td>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach($task->memberTasks as $member_task)
                                            @if($member_task->request_status != 'rejected' && !empty($member_task->user))
                                                <div class="row task-view-assigned-to-card-row pt-3">
                                                    <div class="col-9">
                                                        <div class="media" style="align-items: center;">
                                                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 45 45">
                                                            <g transform="translate(-1662 -269)">
                                                                <rect width="45" height="45" fill="#f4fcef" rx="2" transform="translate(1662 269)"/>
                                                                <g>
                                                                    <path fill="#fae249" d="M145.677 19.171h-8.01l4.505-9.512H132.16l-2.5 13.516h6.508l-6.007 15.519z" transform="translate(1669.379 276.481) translate(-121.544 -9.158)"/>
                                                                    <path d="M1.657 233.659h5.006v1H1.657v-1z" transform="translate(1669.379 276.481) translate(-1.553 -219.141)"/>
                                                                    <path d="M401.657 233.659h5.006v1h-5.006z" transform="translate(1669.379 276.481) translate(-376.523 -219.141)"/>
                                                                    <path d="M416.006 7.506l3.5-3.5.708.708-3.5 3.5z" transform="translate(1669.379 276.481) translate(-389.974 -3.855)"/>
                                                                    <path d="M.036 407.507l3.5-3.5.708.708-3.5 3.5z" transform="translate(1669.379 276.481) translate(-.034 -378.826)"/>
                                                                    <path d="M416 412.714l.708-.708 3.5 3.5-.708.708z" transform="translate(1669.379 276.481) translate(-389.971 -386.328)"/>
                                                                    <path d="M0 4.707L.708 4l3.5 3.5-.708.711z" transform="translate(1669.379 276.481) translate(0 -3.853)"/>
                                                                    <path d="M135.095 1.892a.5.5 0 0 0-.423-.233H124.66a.5.5 0 0 0-.492.41l-2.5 13.516a.5.5 0 0 0 .492.592h5.778l-5.748 14.837a.5.5 0 0 0 .859.492l15.519-19.524a.5.5 0 0 0-.392-.812h-7.219l4.167-8.8a.5.5 0 0 0-.029-.478zm-5.381 9.565a.5.5 0 0 0 .452.715h6.972L124.31 28.31l4.821-12.453a.5.5 0 0 0-.467-.681h-5.906l2.318-12.516h8.8z" transform="translate(1669.379 276.481) translate(-114.043 -1.659)"/>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                        &nbsp;&nbsp; -->
                                                            <div class="media-left">
                                                            @if(isset($member_task->user->image) && file_exists(public_path('backend/uploads/profile_images/'.$member_task->user->id.'/'.$member_task->user->image)))
                                                                <img src="{!! URL::to('public/backend/uploads/profile_images/'.$member_task->member_user_id.'/'.$member_task->user->image)!!}" height="45" width="45" style="border-radius: 50%">
                                                            @else 
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="45" width="45"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg> 
                                                            @endif
                                                            </div>
                                                            <div class="media-body">&nbsp;&nbsp;{{empty($member_task->user->name) ? '' : $member_task->user->name}}{{empty($member_task->user->program) ? '' : ' ('.$member_task->user->program.')'}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        @if($member_task->request_status == 'pending')
                                                            <td><button class="btn btn-inverse-secondary btn-fw button-pending  task-status">Pending</button></td>
                                                        @else
                                                            @if($member_task->task_status == '0')
                                                                <td><button class="btn btn-inverse-warning btn-fw button-to-do  task-status">Accepted</button></td>
                                                            @elseif($member_task->task_status == '1')
                                                                <td><button class="btn btn-inverse-info btn-fw  task-status">In&nbsp;Progress</button></td>
                                                            @elseif($member_task->task_status=='2')
                                                                <td><button class="btn btn-inverse-success btn-fw task-status">Completed</button></td>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 description-block" >
                            <label>Description</label>
                            <p>{!! html_entity_decode($task->description) !!}</p>
                        </div>
                    </div>

                    {{-- <div class="row description-block" >
                        <div class="Task-view-title" style="font-size:14px;">Description</div>
                        <p>{{empty($task->description) ? '' : $task->description}}</p>
                    </div> --}}
                    @if(count($sub_tasks) > 0)
                    <div class="row" style="margin-left:20px;margin-top: 20px;">
                        <div class="Task-view-title" style="font-size:16px;">Sub-Task</div>
                    </div>
                    <div class="row DataTableBox" style="margin-top: 20px; margin-bottom:20px; margin-left:30px; margin-right:30px; padding-bottom:10px;">
                        <div class="table-responsive">
                            <table class="table" id="task-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th class="pl-1">Title</th>
                                    <th>Assign By</th>
                                    <th>Assignee</th>
                                    <th>Due Time</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sub_tasks as $sub_task)
                                    <tr>
                                        <td>{{$sub_task->task_id}}</td>
                                        <td class="pl-1">
                                            <a href="{{route('tasks.show',$sub_task->id)}}" style="color: #ec008c">{{empty($sub_task->title) ? '' : $sub_task->title.' (Sub-Task)'}}</a>
                                        </td>
                                        <td>{{empty($sub_task->assignByWithTrashed->name) ? '' : $sub_task->assignByWithTrashed->name}}</td>
                                        <td>
                                            @if($sub_task->assign_to == 'team')
                                                @foreach($sub_task->memberTasks as $member_task)
                                                    <p>{{empty($member_task->user->name) ? '' : $member_task->user->name}}</p>
                                                @endforeach
                                            @elseif($sub_task->assign_to == 'individual')
                                                @foreach($sub_task->assignees as $assignee)
                                                    <p>{{empty($assignee->user->name) ? '' : $assignee->user->name}}</p>
                                                @endforeach
                                            @endif
                                        </td>
                                        @if($sub_task->end_date < date('Y-m-d'))
                                            <td>
                                                <p>{{empty($sub_task->end_date) ? '' : date('d M, Y', strtotime($sub_task->end_date))}}</p>
                                                <p class="assign-to-me-badge badge badge-danger">Overdue</p>
                                            </td>
                                        @else
                                            <td>{{empty($sub_task->end_date) ? '' : date('d M, Y', strtotime($sub_task->end_date))}}</td>
                                        @endif
                                        <td>{{$sub_task->priority == '0' ? 'Low' : ($sub_task->priority == '1' ? 'Medium' : ($sub_task->priority == '2' ? 'High' : ''))}}</td>

                                        @php $show_wfh = \App\Modals\Task::showWFH($sub_task); @endphp
                                        @if($sub_task->status == '-1')
                                            <td>
                                                <button class="btn btn-inverse-secondary btn-fw button-pending  task-status">Pending</button>
                                                @if($show_wfh)
                                                    <button class="btn btn-fw task-status WFH">WFH</button>
                                                @endif
                                            </td>
                                        @elseif($sub_task->status == '0')
                                            <td>
                                                <button class="btn btn-inverse-warning btn-fw  task-status">Accepted</button>
                                                @if($show_wfh)
                                                    <button class="btn btn-fw task-status WFH">WFH</button>
                                                @endif
                                            </td>    
                                        @elseif($sub_task->status == '1')
                                            <td>
                                                <button class="btn btn-inverse-info btn-fw  task-status">In Progress</button>
                                                @if($show_wfh)
                                                    <button class="btn btn-fw task-status WFH">WFH</button>
                                                @endif
                                            </td>
                                        @elseif($sub_task->status == '2')
                                            <td>
                                                <button class="btn btn-inverse-success btn-fw task-status">Completed</button>
                                                @if($show_wfh)
                                                    <button class="btn btn-fw task-status WFH">WFH</button>
                                                @endif
                                            </td>
                                        @elseif($sub_task->status == '4')
                                            <td>
                                                <button class="btn btn-inverse-danger btn-fw task-status">Rejected</button>
                                                @if($show_wfh)
                                                    <button class="btn btn-fw task-status WFH">WFH</button>
                                                @endif
                                            </td>
                                        @endif

                                        <td>
                                        @if($sub_task->assign_by_id == \Illuminate\Support\Facades\Auth::user()['id'] && $sub_task->status < 2)
                                            <a class="Rectangle-Edit" style="text-decoration: none;" href="{{route('tasks.edit', $sub_task->id)}}"><i class="fa fa-pencil" style="margin-right: 5px;"></i>Edit</a>

                                            <a class="Rectangle-Delete" style="text-decoration: none;" href="{{ route('subTask.destroy', $sub_task->id) }}"
                                                onclick="deleteData('delete-form-{{$sub_task->id}}');">
                                                <i class="fa fa-trash"></i>
                                                Delete
                                            </a>

                                            <form id="delete-form-{{$sub_task->id}}" action="{{ route('subTask.destroy', $sub_task->id) }}" method="POST" class="d-none" style="display: none">
                                                @csrf
                                            </form>
                                        @else
                                            <span class="task-list-action-na">No Action Required</span>
                                        @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if(($request_status == "" || $request_status == "accepted") && $task->status < 2)
                        <div class="mt-3">
                        <a href="{{route('subtasks.create',$task->id)}}">Create Sub Task</a>
                        </div>
                    @endif
                    @if($request_status == 'accepted')
                        <div class="col-sm-4 pl-0 mt-3">
                            <form method="POST" action="{{route('task.updateTaskStatus',$task->id)}}">
                                @csrf
                                <label>Change Status</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="progress-dropdown" name="task_status">
                                            <!-- <option value="0" {{($my_task_status == 0) ? 'selected' : ''}}>Accepted</option> -->
                                            <option value="1" {{($my_task_status == 1) ? 'selected' : ''}}>In Progress</option>
                                            <option value="2" {{($my_task_status == 2) ? 'selected' : ''}}>Completed</option>
                                        </select>
                                        <button class="Create update">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                    @if(count($task->attachments) > 0)
                        <div class="row" style="margin-top: 20px;">
                            <label>Attachments</label>
                            @foreach($task->attachments as $attachment)
                                <div>
                                    <input type="checkbox" disabled>
                                    <a href="{{route('download',['taskId' => $task->id])}}">{{empty($attachment->file_name) ? '' : $attachment->file_name}}</a>
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
                            @if(\App\Modals\Task::meAsAssignToTask($task))
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="request-tab" data-bs-toggle="tab" data-bs-target="#request" type="button" role="tab" aria-controls="request" aria-selected="false">Request </button>
                            </li>
                            @endif
                        </ul>
                        <div class="tab-content viewTaskTabPanel" id="myTabContent">
                            <div class="tab-pane fade show active taskCommentsSection" id="home" role="tabpanel" aria-labelledby="home-tab">
                                @if(count($task->comments) > 0)
                                    @foreach($task->comments as $comment)
                                        <div class="media comment">
                                            <div class="media-left">
                                                @if(isset($comment->user->image) && file_exists(public_path('backend/uploads/profile_images/'.$comment->user->id.'/'.$comment->user->image)))
                                                    <img src="{!! URL::to('public/backend/uploads/profile_images/'.$comment->user->id.'/'.$comment->user->image) !!}" height="40" width="40" style="border-radius: 50%">
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
                                                @if((\App\Modals\Task::meAsAssignToTask($task)) && ($task->assignBy->id == \Illuminate\Support\Facades\Auth::user()['id']))
                                                    <a onclick="showReplyField('comment-{{$comment->id}}')" class="comment-reply-link">Reply</a>
                                                @endif
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
                                                            <img src="{!! URL::to('public/backend/uploads/profile_images/'.$reply->user->id.'/'.$reply->user->image) !!}" height="40" width="40" style="border-radius: 50%">
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
                                                                        {{-- <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{route('reply.delete', $reply->id)}}" onclick="deleteData('delete-reply-form-{{$reply->id}}')">
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
                                                                    <form id="delete-reply-form-{{$reply->id}}" action="{{route('reply.delete', $reply->id)}}" method="POST" class="d-none" style="display: none">
                                                                        @csrf
                                                                    </form>
                                                                </p>
                                                                <p>{!! $reply->text !!}</p>
                                                                @if(count($reply->attachments) > 0)
                                                                    @foreach($reply->attachments as $attachment)
                                                                        <div class="hand-icon-added">
                                                                            <!-- <input type="checkbox" disabled> -->
                                                                            <i class="fas fa-hand-point-right"></i>
                                                                            <a href="{{route('download.replyFile',['replyId' => $reply->id])}}">{{empty($attachment->file_name) ? '' : $attachment->file_name}}</a>
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
                                                            <form method="POST" action="{{route('reply.edit', $reply->id)}}" class="comment-form reply-edit-form-{{$reply->id}}" id="reply-edit-form-{{$reply->id}}" enctype="multipart/form-data" style="display:none;">
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
                                @if((\App\Modals\Task::meAsAssignToTask($task)) && ($task->assignBy->id == \Illuminate\Support\Facades\Auth::user()['id']))
                                    <form method="POST" action="{{route('comment', $task->id)}}" class="comment-form" id="comment-empty" enctype="multipart/form-data">
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
                                @endif
                            </div>
                            <div class="tab-pane fade taskHistorySection" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <!-- @foreach($task->requests as $request)
                                    <div class="media" style="align-items: center;">
                                        <div class="media-left">
                                            @if(isset($request->user->image) && file_exists(public_path('backend/uploads/profile_images/'.$request->user->id.'/'.$request->user->image)))
                                                <img src="{{asset('backend/uploads/profile_images/'.$request->user->id.'/'.$request->user->image)}}" height="45" width="45" style="border-radius: 50%">
                                                &nbsp;&nbsp;
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" height="45" width="45" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                &nbsp;&nbsp;
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            <div>{{$request->message}}</div>
                                            <div class="Hour-ago">{{date('d M, Y h:m A', strtotime($request->created_at))}}</div>
                                        </div>
                                    </div>
                                @endforeach -->
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
                                            <div class="history-comment">{{empty($history->comment) ? '' : $history->comment}}</div>
                                            <div class="Hour-ago">{{empty($history->created_at) ? '' : date_format($history->created_at, 'd M, Y h:i:s A')}}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="tab-pane fade" id="request" role="tabpanel" aria-labelledby="request-tab">
                                <form method="POST" action="{{route('request.save',$task->id)}}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="reason"><b>Request For</b></label>
                                        <select id="reason" name="reason" class="form-control">
                                            @foreach($request_types as $request_type)
                                                <option value="{{$request_type->name}}">{{empty($request_type->displayName) ? '' : $request_type->displayName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="other_reason" id="other_reason" value="request">
                                    <div class="form-group row date-show d-none" >
                                        <div class="col-sm-4">
                                            <label for="extended_date"><b>Due Time</b></label>
                                            <div class='right-inner-addon date datepicker'>
                                                <i class="fa fa-calendar-o date-picker"></i>
                                                <input name='extended_date' value="" type="text" class="form-control date-picker" autocomplete="off" readonly id="extended_date"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group comment-section">
                                        <label for="comments"><b>Comments</b></label>
                                        <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                                    </div>
                                    <div class="text-center">
                                        <a class="btn custom-outline-btn" href="{{route('dashboard')}}">Close</a>
                                        <button class="btn custom-btn">Save</button>
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
                        url: '/uploadFilesComment',
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
                            url: '/uploadFilesReply',
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
            $("#reject-btn").click(function (){
                $('html, body').animate({
                    scrollTop: $("#myTab").offset().top
                });
                $("#other_reason").val('reject');
                $('#request-tab').trigger('click');
            });
            $("#reason").change(function(){
                if($(this).val() == 'time.extension'){
                    $(".date-show").removeClass("d-none");
                //    $('.date-show').show();
                }else{
                //    $('.date-show').hide();
                    $(".date-show").addClass("d-none");
                }
            });
            
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
                                location.reload();
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

            function nuzz(id){
                event.preventDefault();
                $.ajax({
                    url: $("#"+id).attr('action'),
                    method: 'POST',
                    data: $("#" + id).serializeArray(),
                    success: function (data) {
                        console.log(data.msg);
                        $("#alert-show").html("<div class='alert alert-success'><div><p>"+data.msg+"</p></div></div>");
                        $("#alert-show").show().delay(5000).fadeOut();
                    }
                });
            }
        </script>
</body>
</html>
