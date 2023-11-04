<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Task Management Platform</title>
    <?php echo $__env->make('backend.layouts.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
</head>
<body>
<div class="container-scroller task-details-main-page">
    <!-- partial:partials/_navbar.html -->
    <?php echo $__env->make('backend.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="container-fluid page-body-wrapper">
        <?php echo $__env->make('backend.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="main-panel view-task-panel">
            <div class="content-wrapper">
                <div id="alert-show">
                    <?php echo $__env->make('backend.layouts.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <div class="CreateTaskBox card-body">
                    <div>
                        <?php for($i= count($task_breadcums)-1; $i >= 0; $i--): ?>
                            <?php if($i == 0): ?>
                                <span>
                                  <a class="task-id-title" href="<?php echo e(route('tasks.show',$task_breadcums[$i]['id'])); ?>"><?php echo e($task_breadcums[$i]['task_id']); ?></a>
                                </span>
                            <?php else: ?>
                                <span>
                                    <a class="task-id-title" href="<?php echo e(route('tasks.show',$task_breadcums[$i]['id'])); ?>"><?php echo e($task_breadcums[$i]['task_id']); ?></a>
                                </span><span class="task-id-title"> > </span>    
                            <?php endif; ?>    
                        <?php endfor; ?>
                    </div>
                    <div class="row card-head mt-2">
                        <div class="col-md-5 col-sm-5 pr-0">
                            <input type = "hidden" value="<?php echo e($task->id); ?>" id="task_id">
                            <div class="Task-detail-view-title" data-toggle="tooltip" data-placement="right" title="<?php echo e($task->title); ?>" style="word-break:break-word;"><?php echo e(empty($task->title) ? '' : (strlen($task->title) > 100 ? substr($task->title, 0, 100).'...' : $task->title)); ?></div>
                        </div>
                        <div class="col-md-7 col-sm-7 text-end view-task-header-buttons">
                            <button class="btn priority-btn">
                                Priority: <?php echo e($task->priority == '0' ? 'Low' : ($task->priority == '1' ? 'Medium' : ($task->priority == '2' ? 'High' : ''))); ?>

                            </button>
                            <?php if($task->assign_by_id == \Illuminate\Support\Facades\Auth::user()['id']): ?>
                                <a href="<?php echo e(route('tasks.edit', $task->id)); ?>" class="btn Rectangle-btn edit-task-btn">
                                    <i class="far fa-edit"></i>
                                    <span class="New-TAsk">EDIT TASK</span>
                                </a>
                            <?php endif; ?>
                            <?php if($request_status == 'pending'): ?>
                                <form method="POST" action="<?php echo e(route('task.updateRequestStatus',$task->id)); ?>" class="float-right">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="status" value="accepted">
                                    <button type="submit" class="btn btn-success task-accept-button">Accept</button>
                                    <button type="button" class="btn btn-danger task-reject-button" id="reject-btn">Reject</button>
                                </form>
                            <?php else: ?>
                                <?php if(($task->assign_by_id == \Illuminate\Support\Facades\Auth::user()['id']) && ($task->status != 2)): ?>
                                    <a href="<?php echo e(route('tasks.reassign',$task->id)); ?>">
                                        <button class="Rectangle-btn reassign-btn">
                                            <!-- <img src="<?php echo e(asset('backend/assets/img/Reassign-Icon.png')); ?>" class="New-Task-Plus-Icon"> -->
                                            <i class="fas fa-exchange-alt"></i>
                                            <span class="New-TAsk">REASSIGN</span>
                                        </button>
                                    </a>
                                <?php endif; ?>
                                <?php if(count($task->assignees) > 0): ?>
                                    <?php if($task->status == '-1'): ?>
                                        <td>
                                            <button class="btn btn-inverse-secondary btn-fw button-pending">Pending</button>
                                        </td>
                                    <?php elseif($task->status == '0'): ?>
                                        <td>
                                            <button class="btn btn-inverse-warning btn-fw">Accepted</button>
                                        </td>    
                                    <?php elseif($task->status == '1'): ?>
                                        <td>
                                            <button class="btn btn-inverse-info btn-fw">In Progress</button>
                                        </td>
                                    <?php elseif($task->status == '2'): ?>
                                        <td>
                                            <button class="btn btn-inverse-success btn-fw">Completed</button>
                                        </td>
                                    <?php elseif($task->status == '4'): ?>
                                        <td>
                                            <button class="btn btn-inverse-danger btn-fw">Rejected</button>
                                        </td>
                                    <?php elseif($task->status == '5'): ?>       
                                        <td><button class="btn btn-inverse-danger btn-fw" style="background-color:#D4D0EE;color:#483D8B">On Hold</button></td>
                                    <?php elseif($task->status == '6'): ?>       
                                        <td><button class="btn btn-inverse-danger btn-fw" style="background-color:#E3C9EF;color:#a110e3">On Review</button></td>
                                    <?php endif; ?>  
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr class="task-view-hr"/>

                    <div class="row assign-by-assign-to-block">
                        <div class="col-md-6 pl-0">
                            <label>Assigned By</label>
                            <div class="media" style="align-items: center;">
                                <div class="media-left">
                                <?php if(isset($task->assignByWithTrashed->image) && file_exists(public_path('backend/uploads/profile_images/'.$task->assignByWithTrashed->id.'/'.$task->assignByWithTrashed->image))): ?>
                                    <img src="<?php echo URL::to('public/backend/uploads/profile_images/'.$task->assignByWithTrashed->id.'/'.$task->assignByWithTrashed->image); ?>" height="55" width="55" style="border-radius: 50%">
                                <?php else: ?>
                                     <!-- <img class="profile-picture" src="<?php echo e(asset('backend/uploads/profile_images/user-svgrepo-com.svg')); ?>" alt="profile" height="45px;" width="45px"/>    -->
                                    <svg xmlns="http://www.w3.org/2000/svg" height="55" width="55" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                <?php endif; ?>
                                </div>
                                <div class="media-body">&nbsp;&nbsp;<?php echo e(empty($task->assignByWithTrashed->name) ? '' : $task->assignByWithTrashed->name); ?></div>
                            </div>
                            <br>
                            <?php if(isset($task->start_date)): ?>
                                <div class="row date-row">
                                    <div class="col-6">
                                        <div class="task-element">
                                            <label>Start Date</label>
                                            <br>
                                            <i class="fa fa-calendar"><span><?php echo e(empty($task->start_date) ? '' : date('d M, Y', strtotime($task->start_date))); ?></span></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <?php if(isset($task->end_date)): ?>
                                            <div class="task-element">
                                                <label>Due Date</label>
                                                
                                                <?php if(!empty($task->end_date) && $task->end_date < date('Y-m-d') && $task->status < 2): ?>
                                                    <p class="assign-to-me-badge badge badge-danger">Overdue</p> 
                                                <?php endif; ?>
                                                <br>
                                                <i class="fa fa-calendar">
                                                <span> 
                                                    <?php echo e(empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))); ?>

                                                </span>
                                                </i>
                                            </div>
                                        <?php endif; ?>    
                                    </div>
                                </div> 
                            <?php endif; ?>
                            <?php if(isset($task->allocated_time)): ?>
                                <div class="row date-row">
                                    <div class="col-6">
                                        <div class="task-element">
                                            <label>Allocated Time</label>
                                            <br>
                                            <i class="fa fa-clock"><span><?php echo e($task->allocated_time); ?></span></i>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>                           
                            
                        </div>

                        <div class="col-md-6 assign-to-block">
                            <div class="Task-Assign-To">
                                <div class="row">
                                    <div class="col-6">
                                       <div class="Task-view-title ml-5 mt-4">Assigned To</div>
                                   </div>
                                    <div class="col-3">
                                        <div class="Task-view-title">Status</div>
                                    </div>
                                    <div class="col-3">
                                        <div class="Task-view-title">Spend Time</div>
                                    </div>
                                </div>
                                <div class="task-view-assigned-to-card">
                                    <?php if($task->assign_to == 'individual'): ?>
                                        <?php $__currentLoopData = $task->assignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if((((!empty($assignee->userWithTrashed))&& ($assignee->show_rejected_task == '1'))) || (((!empty($assignee->userWithTrashed)) && ($assignee->task_status != '4') && ($assignee->show_rejected_task == '0')))): ?> 
                                                <div class="row task-view-assigned-to-card-row pt-3">
                                                    <div class="col-6">
                                                        <div class="media" style="align-items: center;">
                                                            <div class="media-left">
                                                                <?php if(isset($assignee->userWithTrashed->image) && file_exists(public_path('backend/uploads/profile_images/'.$assignee->userWithTrashed->id.'/'.$assignee->userWithTrashed->image))): ?>
                                                                    <img src="<?php echo URL::to('public/backend/uploads/profile_images/'.$assignee->userWithTrashed->id.'/'.$assignee->userWithTrashed->image); ?>" height="45" width="45" style="border-radius: 50%">
                                                                <?php else: ?>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" height="45" width="45" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg> 
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="media-body" style="word-break:break-word;">&nbsp;&nbsp;<?php echo e(empty($assignee->userWithTrashed->name) ? '' : $assignee->userWithTrashed->name); ?><?php echo e(empty($assignee->userWithTrashed->program) ? '' : ' ('.$assignee->userWithTrashed->program.')'); ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <?php if($assignee->task_status == '-1'): ?>
                                                           
                                                            <button class="btn btn-inverse-secondary btn-fw task-status">Pending</button>
                                                            
                                                        <?php elseif($assignee->task_status == '0'): ?>
                                                            
                                                            <button class="btn btn-inverse-warning btn-fw task-status">Accepted</button>
                                                            
                                                        <?php elseif($assignee->task_status == '1'): ?>
                                                           
                                                            <button class="btn btn-inverse-info btn-fw task-status">In&nbsp;Progress</button>
                                                            
                                                        <?php elseif($assignee->task_status == '2'): ?>
                                                           
                                                            <button class="btn btn-inverse-success btn-fw task-status">Completed</button>
                                                           
                                                        <?php elseif($assignee->task_status == '4'): ?>
                                                            <button class="btn btn-inverse-danger btn-fw task-status">Rejected</button>
                                                        <?php elseif($assignee->task_status == '5'): ?>       
                                                            <button class="btn btn-inverse-danger btn-fw task-status" style="background-color:#D4D0EE;color:#483D8B">On Hold</button>  
                                                        <?php elseif($assignee->task_status == '6'): ?>       
                                                            <button class="btn btn-inverse-danger btn-fw task-status" style="background-color:#E3C9EF;color:#a110e3">On Review</button>  
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-3">
                                                        <?php $spend_time = \App\Modals\WorkLog::getSpendTime($assignee->userWithTrashed->id,$task->id); ?>
                                                        <div class="ml-4"><?php echo e($spend_time); ?></div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 description-block" >
                            <label>Description</label>
                            <p><?php echo html_entity_decode($task->description); ?></p>
                        </div>
                    </div>

                    <?php if(count($sub_tasks) > 0): ?>
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
                                <?php $__currentLoopData = $sub_tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub_task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($sub_task->task_id); ?></td>
                                        <td class="pl-1">
                                            <a href="<?php echo e(route('tasks.show',$sub_task->id)); ?>" style="color: #009877"><?php echo e(empty($sub_task->title) ? '' : $sub_task->title.' (Sub-Task)'); ?></a>
                                        </td>
                                        <td><?php echo e(empty($sub_task->assignByWithTrashed->name) ? '' : $sub_task->assignByWithTrashed->name); ?></td>
                                        <td>
                                            <?php if($sub_task->assign_to == 'team'): ?>
                                                <?php $__currentLoopData = $sub_task->memberTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member_task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <p><?php echo e(empty($member_task->user->name) ? '' : $member_task->user->name); ?></p>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php elseif($sub_task->assign_to == 'individual'): ?>
                                                <?php $__currentLoopData = $sub_task->assignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if((((!empty($assignee->userWithTrashed))&& ($assignee->show_rejected_task == '1'))) || (((!empty($assignee->userWithTrashed)) && ($assignee->task_status != '4') && ($assignee->show_rejected_task == '0')))): ?> 
                                                        <p><?php echo e(empty($assignee->userWithTrashed->name) ? '' : $assignee->userWithTrashed->name); ?></p>    
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </td>
                                        <?php if($sub_task->end_date < date('Y-m-d') && $sub_task->status < 2): ?>
                                            <td>
                                                <p><?php echo e(empty($sub_task->end_date) ? '' : date('d M, Y', strtotime($sub_task->end_date))); ?></p>
                                                <p class="assign-to-me-badge badge badge-danger">Overdue</p>
                                            </td>
                                        <?php else: ?>
                                            <td><?php echo e(empty($sub_task->end_date) ? '' : date('d M, Y', strtotime($sub_task->end_date))); ?></td>
                                        <?php endif; ?>
                                        <td><?php echo e($sub_task->priority == '0' ? 'Low' : ($sub_task->priority == '1' ? 'Medium' : ($sub_task->priority == '2' ? 'High' : ''))); ?></td>

                                        <?php $show_wfh = \App\Modals\Task::showWFH($sub_task); ?>
                                        <?php if($sub_task->status == '-1'): ?>
                                            <td>
                                                <button class="btn btn-inverse-secondary btn-fw button-pending  task-status">Pending</button>
                                                <?php if($show_wfh): ?>
                                                    <button class="btn btn-fw task-status WFH">WFH</button>
                                                <?php endif; ?>
                                            </td>
                                        <?php elseif($sub_task->status == '0'): ?>
                                            <td>
                                                <button class="btn btn-inverse-warning btn-fw  task-status">Accepted</button>
                                                <?php if($show_wfh): ?>
                                                    <button class="btn btn-fw task-status WFH">WFH</button>
                                                <?php endif; ?>
                                            </td>    
                                        <?php elseif($sub_task->status == '1'): ?>
                                            <td>
                                                <button class="btn btn-inverse-info btn-fw  task-status">In Progress</button>
                                                <?php if($show_wfh): ?>
                                                    <button class="btn btn-fw task-status WFH">WFH</button>
                                                <?php endif; ?>
                                            </td>
                                        <?php elseif($sub_task->status == '2'): ?>
                                            <td>
                                                <button class="btn btn-inverse-success btn-fw task-status">Completed</button>
                                                <?php if($show_wfh): ?>
                                                    <button class="btn btn-fw task-status WFH">WFH</button>
                                                <?php endif; ?>
                                            </td>
                                        <?php elseif($sub_task->status == '4'): ?>
                                            <td>
                                                <button class="btn btn-inverse-danger btn-fw task-status">Rejected</button>
                                                <?php if($show_wfh): ?>
                                                    <button class="btn btn-fw task-status WFH">WFH</button>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>

                                        <td>
                                        <?php if($sub_task->assign_by_id == \Illuminate\Support\Facades\Auth::user()['id'] && $sub_task->status < 2): ?>
                                            <a class="Rectangle-Edit" style="text-decoration: none;" href="<?php echo e(route('tasks.edit', $sub_task->id)); ?>"><i class="fa fa-pencil" style="margin-right: 5px;"></i>Edit</a>

                                            <a class="Rectangle-Delete" style="text-decoration: none;" href="<?php echo e(route('subTask.destroy', $sub_task->id)); ?>"
                                                onclick="deleteData('delete-form-<?php echo e($sub_task->id); ?>');">
                                                <i class="fa fa-trash"></i>
                                                Delete
                                            </a>

                                            <form id="delete-form-<?php echo e($sub_task->id); ?>" action="<?php echo e(route('subTask.destroy', $sub_task->id)); ?>" method="POST" class="d-none" style="display: none">
                                                <?php echo csrf_field(); ?>
                                            </form>
                                        <?php else: ?>
                                            <span class="task-list-action-na">No Action Required</span>
                                        <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if(($request_status == "" || $request_status == "accepted") && $task->status < 2): ?>
                        <div class="mt-3">
                        <a href="<?php echo e(route('subtasks.create',$task->id)); ?>">Create Sub Task</a>
                        </div>
                    <?php endif; ?>
                    <?php if($request_status == 'accepted'): ?>
                        <div class="col-sm-4 pl-0 mt-3">
                            <form method="POST" action="<?php echo e(route('task.updateTaskStatus',$task->id)); ?>">
                                <?php echo csrf_field(); ?>
                                <label>Change Status</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="progress-dropdown" name="task_status">
                                            <option value="1" <?php echo e(($my_task_status == 1) ? 'selected' : ''); ?>>In Progress</option>
                                            <option value="2" <?php echo e(($my_task_status == 2) ? 'selected' : ''); ?>>Completed</option>
                                            <option value="5" <?php echo e(($my_task_status == 5) ? 'selected' : ''); ?>>On Hold</option>
                                            <option value="6" <?php echo e(($my_task_status == 6) ? 'selected' : ''); ?>>On Review</option>
                                        </select>
                                        <button class="Create update">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                    <?php if(count($task->attachments) > 0): ?>
                        <div class="row" style="margin-top: 20px;">
                            <label>Attachments</label>
                            <?php $__currentLoopData = $task->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div>
                                    <input type="checkbox" disabled>
                                    <a href="<?php echo e(route('download',['taskId' => $task->id])); ?>"><?php echo e(empty($attachment->file_name) ? '' : $attachment->file_name); ?></a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <div class="row p-3" id="tabId">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Comments <span class="dot"><?php echo e(count($task->comments)); ?></span></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">History <span class="dot"><?php echo e(count($task->histories)); ?></span></button>
                            </li>
                            <?php if(\App\Modals\Task::meAsAssignToTask($task)): ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="request-tab" data-bs-toggle="tab" data-bs-target="#request" type="button" role="tab" aria-controls="request" aria-selected="false">Request </button>
                            </li>
                            <?php endif; ?>
                        </ul>
                        <div class="tab-content viewTaskTabPanel" id="myTabContent">
                            <div class="tab-pane fade show active taskCommentsSection" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <?php if(count($task->comments) > 0): ?>
                                    <?php $__currentLoopData = $task->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="media comment">
                                            <div class="media-left">
                                                <?php if(isset($comment->user->image) && file_exists(public_path('backend/uploads/profile_images/'.$comment->user->id.'/'.$comment->user->image))): ?>
                                                    <img src="<?php echo URL::to('public/backend/uploads/profile_images/'.$comment->user->id.'/'.$comment->user->image); ?>" height="40" width="40" style="border-radius: 50%">
                                                    &nbsp;&nbsp;
                                                <?php else: ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="45" width="45" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                    &nbsp;&nbsp;
                                                <?php endif; ?>
                                            </div>
                                            <div class="media-body">
                                                <div class="comment-detail-<?php echo e($comment->id); ?>">
                                                    <p>
                                                        <span class="comment-by"><?php echo e(empty($comment->user->name) ? '' : $comment->user->name); ?></span>
                                                        <span class="Hour-ago"><?php echo e(empty($comment->created_at) ? '' : date_format($comment->created_at, 'd M, Y h:i:s A')); ?></span>
                                                        <?php if(\Illuminate\Support\Facades\Auth::user()['id'] == $comment->user_id): ?>
                                                            
                                                            <button class="Rectangle-Delete delete-btn-for-reply" style="text-decoration: none;" onclick="editData('<?php echo e($comment->id); ?>')">
                                                                <i class="fa fa-edit" style="margin-right: 5px;"></i>
                                                            </button>
                                                            <a class="Rectangle-Delete delete-btn-for-reply" style="text-decoration: none;" href="<?php echo e(route('comment.delete', $comment->id)); ?>" onclick="deleteData('delete-comment-form-<?php echo e($comment->id); ?>')">
                                                                <i class="fa fa-trash" style="margin-right: 5px;"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <form id="delete-comment-form-<?php echo e($comment->id); ?>" action="<?php echo e(route('comment.delete', $comment->id)); ?>" method="POST" class="d-none" style="display: none">
                                                        <?php echo csrf_field(); ?>
                                                        </form>
                                                    </p>
                                                    <p><?php echo $comment->text; ?></p>
                                                    <?php if(count($comment->attachments) > 0): ?>
                                                        <?php $__currentLoopData = $comment->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="hand-icon-added">
                                                                <i class="fas fa-hand-point-right"></i>
                                                                <a href="<?php echo e(route('download.commentFile',['commentId' => $comment->id])); ?>"><?php echo e(empty($attachment->file_name) ? '' : $attachment->file_name); ?></a>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                </div>
                                                <form method="POST" action="<?php echo e(route('comment.edit', $comment->id)); ?>" class="comment-form comment-edit-form-<?php echo e($comment->id); ?>" id="comment-edit-form-<?php echo e($comment->id); ?>" enctype="multipart/form-data" style="display:none;">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="form-group my-3">
                                                        <textarea class="form-control reply-textarea ckeditor"  name="text-comment-edit-form-<?php echo e($comment->id); ?>" rows="3"><?php echo $comment->text; ?></textarea>
                                                        <p style="color:red;" class="comment-error"></p>
                                                    </div>
                                                    <button type="submit" class="btn comment-btn float-right">Submit</button>
                                                </form>
                                                <?php if((\App\Modals\Task::meAsAssignToTask($task)) && ($task->assignBy->id == \Illuminate\Support\Facades\Auth::user()['id'])): ?>
                                                    <a onclick="showReplyField('comment-<?php echo e($comment->id); ?>')" class="comment-reply-link">Reply</a>
                                                <?php endif; ?>
                                                <form method="POST" class="comment-form" action="<?php echo e(route('reply',$comment->id)); ?>" id="comment-<?php echo e($comment->id); ?>" style="display: none;" enctype="multipart/form-data">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="form-group mt-3">
                                                        <textarea class="form-control reply-textarea ckeditor" name="text-comment-<?php echo e($comment->id); ?>" rows="3"></textarea>
                                                        <p style="color:red;" class="comment-error"></p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Attachments</label>
                                                        <div id="filepond-error-comment-<?php echo e($comment->id); ?>"></div>
                                                        <input type="file" class="file-pond" name="attachments[]" multiple>
                                                    </div>
                                                    <button type="submit" class="btn btn-sm comment-btn float-right">Reply</button>
                                                </form>
                                           </div>
                                        </div>
                                        <?php if(count($comment->replies) > 0): ?>
                                            <?php $__currentLoopData = $comment->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="comment-reply-div">
                                                    <div class="media">
                                                        <div class="media-left">
                                                        <?php if(isset($reply->user->image) && file_exists(public_path('backend/uploads/profile_images/'.$reply->user->id.'/'.$reply->user->image))): ?>
                                                            <img src="<?php echo URL::to('public/backend/uploads/profile_images/'.$reply->user->id.'/'.$reply->user->image); ?>" height="40" width="40" style="border-radius: 50%">
                                                            &nbsp;&nbsp;
                                                        <?php else: ?>
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="45" viewBox="0 0 24 24" width="45"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                            &nbsp;&nbsp;
                                                        <?php endif; ?>
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="reply-detail-<?php echo e($reply->id); ?>">
                                                                <p>
                                                                    <span class="comment-by"><?php echo e(empty($reply->user->name) ? '' : $reply->user->name); ?></span>
                                                                    <span class="Hour-ago"><?php echo e(empty($reply->created_at) ? '' : date_format($reply->created_at, 'd M, Y h:i:s A')); ?></span>

                                                                    <?php if(\Illuminate\Support\Facades\Auth::user()['id'] == $reply->user_id): ?>
                                                                        
                                                                        <button class="Rectangle-Delete delete-btn-for-reply" style="text-decoration: none;" onclick="editDataReply('<?php echo e($reply->id); ?>')">
                                                                            <i class="fa fa-edit" style="margin-right: 5px;"></i>
                                                                        </button>
                                                                        <a class="Rectangle-Delete delete-btn-for-reply" style="text-decoration: none;" href="<?php echo e(route('reply.delete', $reply->id)); ?>" onclick="deleteData('delete-reply-form-<?php echo e($reply->id); ?>')">
                                                                            <i class="fa fa-trash" style="margin-right: 5px;"></i>
                                                                        </a>
                                                                    <?php endif; ?>
                                                                    <form id="delete-reply-form-<?php echo e($reply->id); ?>" action="<?php echo e(route('reply.delete', $reply->id)); ?>" method="POST" class="d-none" style="display: none">
                                                                        <?php echo csrf_field(); ?>
                                                                    </form>
                                                                </p>
                                                                <p><?php echo $reply->text; ?></p>
                                                                <?php if(count($reply->attachments) > 0): ?>
                                                                    <?php $__currentLoopData = $reply->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="hand-icon-added">
                                                                            <!-- <input type="checkbox" disabled> -->
                                                                            <i class="fas fa-hand-point-right"></i>
                                                                            <a href="<?php echo e(route('download.replyFile',['replyId' => $reply->id])); ?>"><?php echo e(empty($attachment->file_name) ? '' : $attachment->file_name); ?></a>
                                                                        </div>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php endif; ?>
                                                                <!-- <a onclick="showReplyField('reply-<?php echo e($reply->id); ?>')" class="comment-reply-link">Reply</a>
                                                                <form method="POST" action="<?php echo e(route('reply',$comment->id)); ?>" id="reply-<?php echo e($reply->id); ?>" style="display: none;">
                                                                    <?php echo csrf_field(); ?>
                                                                    <div class="form-group" style="margin-top: 10px;">
                                                                        <textarea class="form-control" name="text" rows="3" style="box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.16);background-color: #ffffff;"></textarea>
                                                                    </div>
                                                                    <input type="submit" value="Comment" style="border-color: #ec008c; color:#ec008c; background-color: white;">

                                                                </form> -->
                                                            </div>
                                                            <form method="POST" action="<?php echo e(route('reply.edit', $reply->id)); ?>" class="comment-form reply-edit-form-<?php echo e($reply->id); ?>" id="reply-edit-form-<?php echo e($reply->id); ?>" enctype="multipart/form-data" style="display:none;">
                                                                <?php echo csrf_field(); ?>
                                                                <div class="form-group my-3">
                                                                    <textarea class="form-control reply-textarea ckeditor" name="text-reply-edit-form-<?php echo e($reply->id); ?>" rows="3"><?php echo $reply->text; ?></textarea>
                                                                    <p style="color:red;" class="comment-error"></p>
                                                                </div>
                                                                <button type="submit" class="btn comment-btn float-right">Submit</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <?php if((\App\Modals\Task::meAsAssignToTask($task)) || ($task->assignBy->id == \Illuminate\Support\Facades\Auth::user()['id'])): ?>
                                    <form method="POST" action="<?php echo e(route('comment', $task->id)); ?>" class="comment-form" id="comment-empty" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
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
                                <?php endif; ?>
                            </div>
                            <div class="tab-pane fade taskHistorySection" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <!-- <?php $__currentLoopData = $task->requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="media" style="align-items: center;">
                                        <div class="media-left">
                                            <?php if(isset($request->user->image) && file_exists(public_path('backend/uploads/profile_images/'.$request->user->id.'/'.$request->user->image))): ?>
                                                <img src="<?php echo e(asset('backend/uploads/profile_images/'.$request->user->id.'/'.$request->user->image)); ?>" height="45" width="45" style="border-radius: 50%">
                                                &nbsp;&nbsp;
                                            <?php else: ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" height="45" width="45" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                &nbsp;&nbsp;
                                            <?php endif; ?>
                                        </div>
                                        <div class="media-body">
                                            <div><?php echo e($request->message); ?></div>
                                            <div class="Hour-ago"><?php echo e(date('d M, Y h:m A', strtotime($request->created_at))); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> -->
                                <?php $__currentLoopData = $task->histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <div class="media align-items-center">
                                        <div class="media-left">
                                            <?php if(isset($history->user->image) && file_exists(public_path('backend/uploads/profile_images/'.$history->user->id.'/'.$history->user->image))): ?>
                                                <img src="<?php echo URL::to('public/backend/uploads/profile_images/'.$history->user->id.'/'.$history->user->image); ?>" height="40" width="40" style="border-radius: 50%">
                                                &nbsp;&nbsp;
                                            <?php else: ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" height="45" width="45" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                &nbsp;&nbsp;
                                            <?php endif; ?>
                                        </div>
                                        <div class="media-body">
                                            <div>
                                                <span class="comment-by"><?php echo e(empty($history->user->name) ? '' : $history->user->name); ?> </span>
                                                <?php echo e(empty($history->message) ? '' : $history->message); ?>

                                            </div>
                                            <div class="history-comment"><?php echo html_entity_decode($history->comment); ?></div>
                                            <div class="Hour-ago"><?php echo e(empty($history->created_at) ? '' : date_format($history->created_at, 'd M, Y h:i:s A')); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="tab-pane fade" id="request" role="tabpanel" aria-labelledby="request-tab">
                                <form method="POST" action="<?php echo e(route('request.save',$task->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label for="reason"><b>Request For</b></label>
                                        <select id="reason" name="reason" class="form-control">
                                            <?php $__currentLoopData = $request_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($request_type->name); ?>"><?php echo e(empty($request_type->displayName) ? '' : $request_type->displayName); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                        <a class="btn custom-outline-btn" href="<?php echo e(route('dashboard')); ?>">Close</a>
                                        <button class="btn custom-btn">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
            
            <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
        <!-- container-scroller -->
      
        <?php echo $__env->make('backend.layouts.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                            'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
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
                                'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
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
                }else{
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
                            data: formData,
                            success: function (data) {
                                window.location = '/project_management_tool/tasks/'+$("#task_id").val();
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
                        $("#alert-show").html("<div class='alert alert-success'><div><p>"+data.msg+"</p></div></div>");
                        $("#alert-show").show().delay(5000).fadeOut();
                    }
                });
            }
        </script>
</body>
</html>
<?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/tasks/view.blade.php ENDPATH**/ ?>