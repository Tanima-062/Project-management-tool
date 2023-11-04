<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Drag n Drop</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="<?php echo e(asset('backend/assets/templates/vendors/font-awesome/css/font-awesome.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('backend/assets/templates/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')); ?>" type="text/css" />

	<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            /* background-color: #ffffff; */
        }

        .app {
            display: flex;
            flex-flow: column;
            align-items: center;
            justify-content: center;

            width: 100vw;
            /* height: 100vh; */
            height: 80vh;
        }

        header {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 60px;
        }

        /* .lists {
            display: flex;
            align-items: center;

            flex: 1;
            width: 100%;
            overflow-x: scroll;
        }

        .lists .list {
            display: flex;
            flex-flow: column;
            flex: 1;

            width: 100%;
            min-width: 250px;
            max-width: 350px;
            height: 100%;
            min-height: 150px;

            margin: 0 15px;
            padding: 25px;
            transition: all 0.2s linear;
        }

        .lists .list .list-item {
            width: 90%;
            background-color: #F3F3F3;
            border-radius: 5px;
            padding: 15px 20px;
            margin: 4px 0px;
        }
        .list-item-item{
            font-size: 15px;
            display: flex;
            align-items:
            center
        } */
    </style>
</head>
<body class="task-card-body">
	<div class="app">
		<div class="lists">
            <div class="list">
                <p class="kanban-todo-col sticky">Accepted (<span class="total-accepted"><?php echo e($total_accepted); ?></span>)</p>
                <?php $__currentLoopData = $tasks_for_kanban; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $status = \App\Modals\Task::getKanbanTaskStatus($task); ?>
                        <?php if($status == 0): ?>
                            <div class="list-item card py-3" draggable="true">
                                <input type="hidden" value="<?php echo e($task->id); ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        
                                        <div class="row p-0">
                                            <div class="col-md-12 kanban-card-header-left pr-0">
                                                <div class="status-dot kanban-card-header-todo-dot"></div>
                                                <a href="<?php echo e(route('tasks.show',$task->id)); ?>" style="color: #ec008c;text-decoration:none;" class="kanban-hover">
                                                    <p class="kanban-card-header-para" style="color:#282828;">
                                                        
                                                        &nbsp;&nbsp;<?php echo e(empty($task->title) ? '' : (strlen($task->title) > 30 ? substr($task->title, 0, 30).'...' : $task->title)); ?>

                                                    </p>
                                                </a>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="list-item-item todo-description">
                                            
                                            <?php echo empty($task->description) ? '' : (strlen($task->description) > 115 ? substr($task->description, 0, 115).'...' : $task->description); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="kanban-card-footer" >
                                                    <div class="d-flex">
                                                    <?php if($task->assign_to == 'team'): ?>
                                                        <?php $__currentLoopData = $task->memberTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$member_task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if(!empty($member_task->user->image) && (file_exists(public_path('backend/uploads/profile_images/'.$member_task->user->id.'/'.$member_task->user->image)))): ?>
                                                                <img src="<?php echo e(asset('backend/uploads/profile_images/'.$member_task->user->id.'/'.$member_task->user->image)); ?>" alt="profile"  class="team-card-member-image"/>
                                                            <?php else: ?>
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="kanban-card-member-image"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                            <?php endif; ?>  
                                                            <?php if($key == 3): ?>  <?php break; ?>;  <?php endif; ?>    
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(count($task->memberTasks)>4): ?>
                                                            <div  class="team-card-member-image team-card-member-image-addition-count">+<?php echo e(count($task->memberTasks) - 4); ?></div>
                                                        <?php endif; ?>
                                                    <?php elseif($task->assign_to == 'individual'): ?>
                                                        <?php $__currentLoopData = $task->assignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$assignee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if(!empty($assignee->user->image) && (file_exists(public_path('backend/uploads/profile_images/'.$assignee->user->id.'/'.$assignee->user->image)))): ?>
                                                                <img src="<?php echo e(asset('backend/uploads/profile_images/'.$assignee->user->id.'/'.$assignee->user->image)); ?>" alt="profile"  class="team-card-member-image"/>
                                                            <?php else: ?>
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="kanban-card-member-image"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                            <?php endif; ?> 
                                                            <?php if($key == 3): ?>  <?php break; ?>;  <?php endif; ?>  
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(count($task->assignees)>4): ?>
                                                            <div  class="team-card-member-image team-card-member-image-addition-count">+<?php echo e(count($task->assignees) - 4); ?></div>
                                                        <?php endif; ?> 
                                                    <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if($task->end_date < date('Y-m-d')): ?>
                                                <div class="col-md-4 Overdue-Task" style="margin-top:10px;">
                                                    <div style="font-size:10px;">
                                                        <?php echo e(empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))); ?>

                                                    </div>
                                                    <div class="assign-to-me-badge badge badge-danger my-1">
                                                        Overdue
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

			<div class="list">
                <b class="kanban-inprogress-col sticky">In Progress (<span class="total-inProgress"><?php echo e($total_inProgress); ?></span>)</b>
                <?php $__currentLoopData = $tasks_for_kanban; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $status = \App\Modals\Task::getKanbanTaskStatus($task); ?>
                        <?php if($status == 1): ?>
                            <div class="list-item card py-3" draggable="true">
                                <input type="hidden" value="<?php echo e($task->id); ?>">
                                <div class="row">

                                    <div class="col-md-12">
                                        
                                        <div class="row p-0">
                                            <div class="col-md-12 kanban-card-header-left pr-0">
                                                <div class="status-dot kanban-card-header-progress-dot"></div>
                                                <a href="<?php echo e(route('tasks.show',$task->id)); ?>" style="color: #ec008c; text-decoration:none;" class="kanban-hover">
                                                    <p class="kanban-card-header-para" style="color:#282828">
                                                        &nbsp;&nbsp;<?php echo e(empty($task->title) ? '' : (strlen($task->title) > 30 ? substr($task->title, 0, 30).'...' : $task->title)); ?>

                                                    </p>
                                                </a>
                                            </div>
                                            
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="list-item-item in-progress-description">
                                            <?php echo empty($task->description) ? '' : (strlen($task->description) > 115 ? substr($task->description, 0, 115).'...' : $task->description); ?>

                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="kanban-card-footer">
                                                    <div class="d-flex">
                                                    <?php if($task->assign_to == 'team'): ?>
                                                        <?php $__currentLoopData = $task->memberTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$member_task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if(!empty($member_task->user->image) && (file_exists(public_path('backend/uploads/profile_images/'.$member_task->user->id.'/'.$member_task->user->image)))): ?>
                                                                <img src="<?php echo e(asset('backend/uploads/profile_images/'.$member_task->user->id.'/'.$member_task->user->image)); ?>" alt="profile"  class="team-card-member-image"/>
                                                            <?php else: ?>
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="kanban-card-member-image"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                            <?php endif; ?>   
                                                            <?php if($key == 3): ?>  <?php break; ?>;  <?php endif; ?>   
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(count($task->memberTasks)>4): ?>
                                                            <div  class="team-card-member-image team-card-member-image-addition-count">+<?php echo e(count($task->memberTasks) - 4); ?></div>
                                                        <?php endif; ?>
                                                    <?php elseif($task->assign_to == 'individual'): ?>
                                                        <?php $__currentLoopData = $task->assignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$assignee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if(!empty($assignee->user->image) && (file_exists(public_path('backend/uploads/profile_images/'.$assignee->user->id.'/'.$assignee->user->image)))): ?>
                                                                <img src="<?php echo e(asset('backend/uploads/profile_images/'.$assignee->user->id.'/'.$assignee->user->image)); ?>" alt="profile"  class="team-card-member-image"/>
                                                            <?php else: ?>
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="kanban-card-member-image"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                            <?php endif; ?>
                                                            <?php if($key == 3): ?>  <?php break; ?>;  <?php endif; ?>   
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(count($task->assignees)>4): ?>
                                                            <div  class="team-card-member-image team-card-member-image-addition-count">+<?php echo e(count($task->assignees) - 4); ?></div>
                                                        <?php endif; ?>
                                            
                                                    <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <?php if($task->end_date < date('Y-m-d')): ?>
                                                <div class="col-md-4 text-right Overdue-Task" style="margin-top:10px;">
                                                    <div style="font-size:10px;">
                                                        <?php echo e(empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))); ?>

                                                    </div>
                                                    <div class="assign-to-me-badge badge badge-danger my-1">
                                                        Overdue
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

			<div class="list">
                <p class="kanban-completed-col sticky">Completed (<span class="total-completed"><?php echo e($total_completed); ?></span>)</p>
                <?php $__currentLoopData = $tasks_for_kanban; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $status = \App\Modals\Task::getKanbanTaskStatus($task); ?>
                        <?php if($status == 2): ?>
                            <div class="list-item card py-3" draggable="true">
                                <input type="hidden" value="<?php echo e($task->id); ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        
                                        <div class="row p-0">
                                            <div class="col-md-12 kanban-card-header-left pr-0">
                                                <div class="status-dot kanban-card-header-completed-dot"></div>
                                                <a href="<?php echo e(route('tasks.show',$task->id)); ?>" style="color: #ec008c; text-decoration:none;" class="kanban-hover">
                                                    <p class="kanban-card-header-para" style="color:#282828">
                                                        &nbsp;&nbsp;<?php echo e(empty($task->title) ? '' : (strlen($task->title) > 30 ? substr($task->title, 0, 30).'...' : $task->title)); ?>

                                                    </p>
                                                </a>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="list-item-item completed-description">
                                            <?php echo empty($task->description) ? '' : (strlen($task->description) > 115 ? substr($task->description, 0, 115).'...' : $task->description); ?>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="kanban-card-footer">
                                                <div class="d-flex">
                                                <?php if($task->assign_to == 'team'): ?>
                                                    <?php $__currentLoopData = $task->memberTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$member_task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(!empty($member_task->user->image) && (file_exists(public_path('backend/uploads/profile_images/'.$member_task->user->id.'/'.$member_task->user->image)))): ?>
                                                            <img src="<?php echo e(asset('backend/uploads/profile_images/'.$member_task->user->id.'/'.$member_task->user->image)); ?>" alt="profile"  class="team-card-member-image"/>
                                                        <?php else: ?>
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="kanban-card-member-image"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                        <?php endif; ?> 
                                                        <?php if($key == 3): ?>  <?php break; ?>;  <?php endif; ?>   
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(count($task->memberTasks)>4): ?>
                                                        <div  class="team-card-member-image team-card-member-image-addition-count">+<?php echo e(count($task->memberTasks) - 4); ?></div>
                                                    <?php endif; ?>
                                                <?php elseif($task->assign_to == 'individual'): ?>
                                                    <?php $__currentLoopData = $task->assignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$assignee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(!empty($assignee->user->image) && (file_exists(public_path('backend/uploads/profile_images/'.$assignee->user->id.'/'.$assignee->user->image)))): ?>
                                                            <img src="<?php echo e(asset('backend/uploads/profile_images/'.$assignee->user->id.'/'.$assignee->user->image)); ?>" alt="profile"  class="team-card-member-image"/>
                                                        <?php else: ?>
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="kanban-card-member-image"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                        <?php endif; ?> 
                                                        <?php if($key == 3): ?>  <?php break; ?>;  <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(count($task->assignees)>4): ?>
                                                        <div  class="team-card-member-image team-card-member-image-addition-count">+<?php echo e(count($task->assignees) - 4); ?></div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
		</div>
	</div>

    <script>
        list_items = document.querySelectorAll('.list-item');
        lists = document.querySelectorAll('.list');

        draggedItem = null;
        taskName = null;

        for (let indexOFitem = 0; indexOFitem < list_items.length; indexOFitem++) {
            const item = list_items[indexOFitem];

            item.addEventListener('dragstart', function () {

                // $(this).addClass('test');
                draggedItem = item;
                taskName = item.getElementsByTagName('input');
                setTimeout(function () {
                    // item.style.display = 'none';
                    item.style.boxShadow = "-18px 20px 12px 0px #ddd";
                }, 0)
            });

            item.addEventListener('dragend', function () {
				// $(this).removeClass('test');
                setTimeout(function () {
                    draggedItem.style.display = 'block';
                    draggedItem.style.boxShadow = "0 0px 10px 1px #ddd";
                    draggedItem = null;
                }, 0);
            })

            if(indexOFitem == list_items.length-1){
                for (let indexOFStatus = 0; indexOFStatus < lists.length; indexOFStatus ++) {
                    const list = lists[indexOFStatus];

                    list.addEventListener('dragover', function (e) {
                        e.preventDefault();
                    });

                    list.addEventListener('dragenter', function (e) {
                        e.preventDefault();
                    });

                    list.addEventListener('dragleave', function (e) {
                    });

                    list.addEventListener('drop', function (e) {
                        var taskID = taskName[0].value;
                        var colArea = list.firstElementChild.className.split(" ")[0];
                        var statusDot = draggedItem.querySelector('.status-dot');
                        var overDue = draggedItem.querySelector('.Overdue-Task');

                        var status = draggedItem.querySelector('.status-dot').classList[1];
                        if(status == 'kanban-card-header-todo-dot'){
                            var c = $('.total-accepted').text();
                            c--;
                            $('.total-accepted').html(c);
                        }
                        if(status == 'kanban-card-header-progress-dot'){
                            var a = $('.total-inProgress').text();
                            a--;
                            $('.total-inProgress').html(a);
                        }
                        if(status == 'kanban-card-header-completed-dot'){
                            var b = $('.total-completed').text();
                            b--;
                            $('.total-completed').html(b);
                        }

                        if(colArea == 'kanban-todo-col'){
                            if(overDue){
                                overDue.style.display = "block";
                            }
                            statusDot.style.backgroundColor = "#FFCE00";
                            var count = $('.total-accepted').text();
                            count++;
                            $('.total-accepted').html(count);
                            statusDot.classList.remove(status);
                            statusDot.classList.add('kanban-card-header-todo-dot');
                        }
                        if(colArea == 'kanban-inprogress-col'){
                            if(overDue){
                                overDue.style.display = "block";
                            }
                            statusDot.style.backgroundColor = "#095CF8";
                            var count = $('.total-inProgress').text();
                            count++;
                            $('.total-inProgress').html(count);
                            statusDot.classList.remove(status);
                            statusDot.classList.add('kanban-card-header-progress-dot');
                        }
                        if(colArea == 'kanban-completed-col'){
                            if(overDue){
                                overDue.style.display = "none";
                            }
                            statusDot.style.backgroundColor = "#12B002";
                            var count = $('.total-completed').text();
                            count++;
                            $('.total-completed').html(count);
                            statusDot.classList.remove(status);
                            statusDot.classList.add('kanban-card-header-completed-dot');
                        }
                        // console.log(taskID);

                        // if(indexOFStatus === 0){
                        //     console.log('Pending' + ' saad');
                        // }
                        // else if(indexOFStatus === 1){
                        //     console.log('In progress');
                        // }
                        // else if(indexOFStatus === 2){
                        //     console.log('Completed');
                        // }

                        this.append(draggedItem);
                        //dragSubmit('/get/'+taskID+"?status="indexOFStatus);

                        data = {
                            "task_id": taskID,
                            "status": indexOFStatus
                        }
                        dragSubmit('/get/'+taskID, data);
                    });
                }
            }
        }
        function dragSubmit(path, data){
            $.ajax({
                type:'GET',
                url:path,
                data:data,
                success:function(data) {
                    console.log(data);
                }
            });
        }
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/tasks/task_card.blade.php ENDPATH**/ ?>