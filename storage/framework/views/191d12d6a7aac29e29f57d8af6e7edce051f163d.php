<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    <?php echo $__env->make('backend.layouts.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
</head>

<body id="app">
    <div class="container-scroller dashboard-page">
        <!-- partial:partials/_navbar.html -->
        <?php echo $__env->make('backend.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="container-fluid page-body-wrapper">
            <?php echo $__env->make('backend.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="main-panel dashboard-main-panel">
                <div class="content-wrapper Blue-Color-BG">
                    <div id="alert-show">
                        <?php echo $__env->make('backend.layouts.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="row Blue-Color-BG-row">
                        <div class="col-md-3">
                            <div  class="assign-to-me">Assigned To Me</div>
                            <hr class="Dash-Line">
                            <div class="Pie-Chart-Card-Box">
                                <div class="row">
                                    <h5>Overall Task Status</h5>
                                    <div class="col-md-6">
                                        <div class="task-status-percnetage text-center" style=" font-size:20px;">
                                            <?php echo e(($assign_to_me_todo_task_count + $assign_to_me_inProgress_task_count + $assign_to_me_completed_task_count) == 0 ? 0 : number_format((($assign_to_me_completed_task_count / ($assign_to_me_todo_task_count + $assign_to_me_inProgress_task_count + $assign_to_me_completed_task_count)) * 100), 2, '.', '' )); ?>%
                                        </div>
                                        <div class="Overall-Progress">Overall Progress</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="task-status-percnetage" style="color:#FF4747; text-align:center; font-size:20px;"><?php echo e($assign_to_me_overdue_task_count); ?></div>
                                        <div class="Overall-Progress">Overdue Tasks</div>
                                    </div>
                                </div>
                                <canvas id="myChart" width="80" height="50"></canvas>
                                <div class="row Pie-Chart-Card-Box-row">
                                    <div class="col-4 Pie-Chart-Card-Box-row-item-1 p-0">
                                        <p><?php echo e($assign_to_me_todo_task_count); ?></p>
                                        <p class="Task-status-in-chart">Accepted</p>
                                    </div>
                                    <div class="col-4 Pie-Chart-Card-Box-row-item-2 p-0">
                                        <p><?php echo e($assign_to_me_inProgress_task_count); ?></p>
                                        <p class="Task-status-in-chart">In Progress</p>
                                    </div>
                                    <div class="col-4 Pie-Chart-Card-Box-row-item-3 p-0">
                                        <p><?php echo e($assign_to_me_completed_task_count); ?></p>
                                        <p class="Task-status-in-chart">Completed</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 Task-List-Card-Box">
                            <h5>Task List</h5>
                            <div class="table-responsive">
                                <table class="table custom-table" id="assignToMe" style="position:relative;">
                                    <thead class="table_header_color" style="position: sticky; top: 0px;">
                                    <tr>
                                        <th>Task ID</th>
                                        <th>Task Title</th>
                                        <th>Assigned By</th>
                                        <th>Due Time</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php $__currentLoopData = $assign_to_me_tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td style="padding-top: 19px;">
                                                <?php echo e(empty($task->task_id) ? '' : $task->task_id); ?>

                                            </td>
                                            <td style="white-space: pre-line;line-height: 18px; "><a href="<?php echo e(route('tasks.show',$task->id)); ?>"><?php echo e(empty($task->title) ? '' : $task->title); ?></a></td>
                                            <td><?php echo e(empty($task->assignBy->name) ? '' : $task->assignBy->name); ?></td>
                                            <?php if($task->end_date < date('Y-m-d')): ?>
                                                <td>
                                                    <p><?php echo e(empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))); ?></p>
                                                    <p class="assign-to-me-badge badge badge-danger">Overdue</p>
                                                </td>
                                            <?php else: ?>
                                                <td><?php echo e(empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))); ?></td>
                                            <?php endif; ?>
                                            
                                            <?php if($task->assignees[0]->task_status == '-1'): ?>
                                                <td>
                                                    <button class="btn btn-inverse-secondary btn-fw button-pending task-status">Pending</button>
                                                </td>
                                            <?php elseif($task->assignees[0]->task_status == '0'): ?>
                                                <td>
                                                    <button class="btn btn-inverse-warning btn-fw  task-status">Accepted</button>
                                                </td>
                                            <?php elseif($task->assignees[0]->task_status == '1'): ?>
                                                <td>
                                                    <button class="btn btn-inverse-info btn-fw task-status">In progress</button>
                                                </td>
                                            <?php elseif($task->assignees[0]->task_status == '2'): ?>
                                                <td>
                                                    <button class="btn btn-inverse-success btn-fw task-status">Completed</button>
                                                </td>
                                            <?php elseif($task->assignees[0]->task_status == '5'): ?>       
                                                <td><button class="btn btn-inverse-danger btn-fw task-status" style="background-color:#D4D0EE;color:#483D8B">On Hold</button></td>
                                             <?php elseif($task->assignees[0]->task_status == '6'): ?>       
                                                <td><button class="btn btn-inverse-danger btn-fw task-status" style="background-color:#E3C9EF;color:#a110e3">On Review</button></td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                            <div class="col-md-3">
                                <div  class="assign-to-me">New Task</div>
                                <hr class="Dash-Line">
                                <div class="accepted-item">
                                    <div class="New-Task-Card-Box">
                                        <?php $__currentLoopData = $pending_tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e(route('tasks.show', $task->id)); ?>" class="dashboard-task-card">
                                                <div class="List-Item-BG-White card">
                                                    <div class="media">
                                                        <div class="media-left">
                                                        <?php if(isset($task->assignBy->image) && file_exists(public_path('backend/uploads/profile_images/'.$task->assignBy->id.'/'.$task->assignBy->image))): ?>
                                                            <img src="<?php echo URL::to('public/backend/uploads/profile_images/'.$task->assignBy->id.'/'.$task->assignBy->image); ?>" height="40" width="40" style="border-radius: 50%">
                                                            &nbsp;
                                                        <?php else: ?>
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="40" height="40"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                            &nbsp;
                                                        <?php endif; ?>
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="Task-title">&nbsp;&nbsp;<?php echo e(empty($task->title) ? '' : (strlen($task->title) > 17 ? substr($task->title, 0, 17).'...' : $task->title)); ?></div>
                                                            <div>
                                                                <span class="Assigned-By" style="color:#7a8fa4">&nbsp;&nbsp;Assign By</span>
                                                                <span class="Assigned-By" style="color: #6c63ff;">
                                                                    <?php echo e(empty($task->assignBy->name) ? '' : (strlen($task->assignBy->name) > 17 ? substr($task->assignBy->name, 0, 17).'...' : $task->assignBy->name)); ?>

                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="Hour-ago"><?php echo e(empty($task->created_at) ? '' : $task->created_at->diffForHumans()); ?></div>
                                                </div>
                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <div class="content-wrapper Yellow-Color-BG">
                    <div class="row justify-content-around mt-3">
                        <div class="col-md-3">
                            <div  class="assign-to-me">Assigned By Me</div>
                            <hr class="Dash-Line">
                            <div class="Pie-Chart-Card-Box">
                                <div class="row">
                                    <h5>Overall Task Status</h5>
                                    <div class="col-md-6">
                                        <div class="task-status-percnetage">
                                            <?php echo e(($assign_by_me_todo_task_count + $assign_by_me_inProgress_task_count + $assign_by_me_completed_task_count) == 0 ? 0 : number_format((($assign_by_me_completed_task_count / ($assign_by_me_todo_task_count + $assign_by_me_inProgress_task_count + $assign_by_me_completed_task_count)) * 100), 2, '.', '' )); ?>%
                                        </div>
                                        <div class="Overall-Progress">Overall Progress</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="task-status-percnetage" style="color:#FF4747;"><?php echo e($assign_by_me_overdue_task_count); ?></div>
                                        <div class="Overall-Progress">Overdue Tasks</div>
                                    </div>
                                </div>
                                <canvas id="myChart1" width="80" height="50"></canvas>
                                <div class="row Pie-Chart-Card-Box-row">
                                    <div class="col-4 p-0">
                                        <p style="color: #f2b70a; text-align:center; font-size:20px;"><?php echo e($assign_by_me_todo_task_count); ?></p>
                                        <p class="Task-status-in-chart">Accepted</p>
                                    </div>
                                    <div class="col-4 p-0">
                                        <p style="color: #188ef9; text-align:center; font-size:20px;"><?php echo e($assign_by_me_inProgress_task_count); ?></p>
                                        <p class="Task-status-in-chart">In Progress</p>
                                    </div>
                                    <div class="col-4 p-0">
                                        <p style="color: #4dc400; text-align:center; font-size:20px;"><?php echo e($assign_by_me_completed_task_count); ?></p>
                                        <p class="Task-status-in-chart">Completed</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="col-md-6 Task-List-Card-Box">
                        <h5>Task List</h5>
                    <div class="table-responsive">
                            <table class="table" id="assignByMe" style="position: relative;" >
                                <thead class="table_header_color" style="position: sticky; top: 0px;">
                                    <tr>
                                        <th>Task ID</th>
                                        <th>Task Title</th>
                                        <th>Assigned To</th>
                                        <th>Due Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $assign_by_me_tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e(empty($task->task_id) ? '' : $task->task_id); ?></td>
                                        <td style="white-space: pre-line;line-height: 18px; "><a href="<?php echo e(route('tasks.show',$task->id)); ?>"><?php echo e(empty($task->title) ? '' : $task->title); ?></a></td>
                                        <td style="padding-top: 3px; padding-right: 3px;">
                                            <?php if($task->assign_to == 'team'): ?>
                                                <?php $__currentLoopData = $task->memberTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member_task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <p><?php echo e(empty($member_task->user->name) ? '' : $member_task->user->name); ?></p>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <?php $__currentLoopData = $task->assignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <p><?php echo e(empty($assignee->user->name) ? '' : $assignee->user->name); ?></p>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </td>
                                        <?php if($task->end_date < date('Y-m-d')): ?>
                                        <td>
                                            <p><?php echo e(empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))); ?></p>
                                            <p class="assign-to-me-badge badge badge-danger">Overdue</p>
                                        </td>
                                        <?php else: ?>
                                            <td><?php echo e(empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))); ?></td>
                                        <?php endif; ?>
                                        <?php if($task->status == '-1'): ?>
                                            <td>
                                                <button class="btn btn-inverse-secondary btn-fw button-pending task-status">Pending</button>
                                            </td>
                                        <?php elseif($task->status == '0'): ?>
                                            <td>
                                                <button class="btn btn-inverse-warning btn-fw task-status">Accepted</button>
                                            </td>
                                        <?php elseif($task->status == '1'): ?>
                                            <td>
                                                <button class="btn btn-inverse-info btn-fw task-status">In progress</button>
                                            </td>
                                        <?php elseif($task->status == '2'): ?>
                                            <td>
                                                <button class="btn btn-inverse-success btn-fw task-status">Completed</button>
                                            </td>
                                        <?php elseif($task->status == '5'): ?>       
                                            <td><button class="btn btn-inverse-danger btn-fw task-status" style="background-color:#D4D0EE;color:#483D8B">On Hold</button></td>
                                        <?php elseif($task->status == '6'): ?>       
                                            <td><button class="btn btn-inverse-danger btn-fw task-status" style="background-color:#E3C9EF;color:#a110e3">On Review</button></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="assign-to-me">Rejected Items</div>
                        <hr class="Dash-Line">
                        <div class="rejected-item dashboard-rejected-item">
                            <div class="New-Task-Card-Box">
                                <?php if(!empty($rejected_tasks)): ?>
                                    <?php $__currentLoopData = $rejected_tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(route('tasks.show', $task->taskId)); ?>" class="dashboard-task-card">
                                            <div class="List-Item-BG-White">
                                                <div class="media">
                                                    <div class="media-left reject-item-media-left">
                                                    <?php if(isset($task->image) && file_exists(public_path('backend/uploads/profile_images/'.$task->user_id.'/'.$task->image))): ?>
                                                        <img src="<?php echo URL::to('public/backend/uploads/profile_images/'.$task->user_id.'/'.$task->image); ?>" height="40" width="40" style="border-radius: 50%">
                                                        &nbsp;
                                                    <?php else: ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="40" height="40"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                        &nbsp;
                                                    <?php endif; ?>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="Task-title">
                                                            <?php echo e(empty($task->title) ? '' : (strlen($task->title) > 17 ? substr($task->title, 0, 16).'...' : $task->title)); ?>

                                                        </div>
                                                        <div>
                                                            <span class="Assigned-By" style="color:#7a8fa4">Rejected By</span>
                                                            <span class="Assigned-By" style="color: #6c63ff;">
                                                                <?php echo e(empty($task->name) ? '' : (strlen($task->name) > 17 ? substr($task->name, 0, 16).'...' : $task->name)); ?>

                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="Hour-ago"><?php echo e(empty($task->updated_at) ? '' : $task->updated_at->diffForHumans()); ?></div>
                                            </div>
                                        </a>        
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
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
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        const assign_to_me_to_do = <?php echo $assign_to_me_todo_task_count; ?>;        const assign_to_me_inProgress = <?php
        echo $assign_to_me_inProgress_task_count; ?>;        const assign_to_me_completed = <?php
        echo $assign_to_me_completed_task_count; ?>;        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [assign_to_me_to_do, assign_to_me_inProgress, assign_to_me_completed],
                    backgroundColor: [
                        '#f2b70a',
                        '#188ef9',
                        '#4dc400',

                    ],
                }],
                labels: [
                    'Accepted',
                    'In Progress',
                    'Completed'
                ],
            },
            options: {
                aspectRatio: 1,
                cutoutPercentage: 75,
                legend: {
                    display: false,
                },
            }
        });

        const assign_by_me_to_do = <?php echo $assign_by_me_todo_task_count; ?>;        const assign_by_me_inProgress = <?php
        echo $assign_by_me_inProgress_task_count; ?>;        const assign_by_me_completed = <?php
        echo $assign_by_me_completed_task_count; ?>;
        var ctx1 = document.getElementById('myChart1').getContext('2d');
        var myChart1 = new Chart(ctx1, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [assign_by_me_to_do, assign_by_me_inProgress, assign_by_me_completed],
                    backgroundColor: [
                        '#f2b70a',
                        '#188ef9',
                        '#4dc400',

                    ],
                }],
                labels: [
                    'Accepted',
                    'In Progress',
                    'Completed'
                ],
            },
            options: {
                aspectRatio: 1,
                cutoutPercentage: 75,
                legend: {
                    display: false,
                },
            }
        });

    </script>

    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
    <script>
        $(document).ready(function() {
            const tableCustom =  $('.table').DataTable({
                    "dom": 'lrt',
                    "lengthChange": false,
                    "paging": false,
                    columnDefs: [
                        { orderable: false, targets: [-1] }
                    ]
            });

            var firebaseConfig = {
                apiKey: "AIzaSyDgzSaKXvtEX8_KNEvezWQWlVurXK-snDE",
                authDomain: "hardy-abode-257407.firebaseapp.com",
                databaseURL: "https://hardy-abode-257407.firebaseio.com",
                projectId: "hardy-abode-257407",
                storageBucket: "hardy-abode-257407.appspot.com",
                messagingSenderId: "405457552881",
                appId: "1:405457552881:web:c8d4c53b6cc065cf03f050",
                measurementId: "G-60LZG0BS59"
            };

            firebase.initializeApp(firebaseConfig);
            const messaging = firebase.messaging();

            function initFirebaseMessagingRegistration() {
                messaging
                    .requestPermission()
                    .then(function() {
                        return messaging.getToken()
                    })
                    .then(function(token) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: '<?php echo e(route('save-token')); ?>',
                            type: 'POST',
                            data: {
                                token: token
                            },
                            dataType: 'JSON',
                            success: function(response) {

                            },
                            error: function(err) {
                            },
                        });

                    }).catch(function(err) {
                    });
            }

            messaging.onMessage(function(payload) {
                const noteTitle = payload.notification.title;
                const noteOptions = {
                    body: payload.notification.body,
                    icon: payload.notification.icon,
                };
                new Notification(noteTitle, noteOptions);
            });
            initFirebaseMessagingRegistration();
        });
    </script>

    <?php echo $__env->make('backend.layouts.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script type="modules" src="resources/js/app.js"></script>
</body>

</html>
<?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/dashboard.blade.php ENDPATH**/ ?>