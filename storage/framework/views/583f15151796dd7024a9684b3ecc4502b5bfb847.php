<div class="row">
    <div class="col-md-2 ">
        <div class="overall-task-img">
            <img src="<?php echo URL::to('public/backend/assets/img/stl_report.png'); ?>" class="">
        </div>
    </div>
    <div class="col-md-10 overall-task-quantity p-sm-0">
    <?php $overall_task_status = \App\Modals\User::overallTaskStatus(); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Departments</h4>
                        <h3 class="task-quantity-number Programs"><?php echo e($overall_task_status['programs']); ?></h3>
                    </div>
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Members</h4>
                        <h3 class="task-quantity-number Members"><?php echo e($overall_task_status['members']); ?></h3>
                    </div>
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Tasks</h4>
                        <h3 class="task-quantity-number Tasks"><?php echo e($overall_task_status['tasks']); ?></h3>
                    </div>
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Pending</h4>
                        <h3 class="task-quantity-number Pending"><?php echo e($overall_task_status['pending']); ?></h3>
                    </div>
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Accepted</h4>
                        <h3 class="task-quantity-number Accepted"><?php echo e($overall_task_status['accepted']); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">In Progress</h4>
                        <h3 class="task-quantity-number In-Progress"><?php echo e($overall_task_status['inProgress']); ?></h3>
                    </div>
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Completed</h4>
                        <h3 class="task-quantity-number Completed"><?php echo e($overall_task_status['completed']); ?></h3>
                    </div>
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Rejected</h4>
                        <h3 class="task-quantity-number Rejected"><?php echo e($overall_task_status['rejected']); ?></h3>
                    </div>
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Overdue</h4>
                        <h3 class="task-quantity-number overdue"><?php echo e($overall_task_status['overdue']); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/task_analytics/status_card.blade.php ENDPATH**/ ?>