<div class="table-responsive" style="margin-bottom: 20px;">
    <table class="table supervise-status-table">
        <thead>
            <tr>
                <th>SL No.<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="id"></th>
                <th  style="width:25%;">Full Name<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="name"></th>
                <th  style="width:50%;">Team</th>
                <th  style="width:25%;">Total Task<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="total"></th>
                <th  style="width:25%;">Accepted<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="accepted"></th>
                <th  style="width:25%;">In Progress<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="inProgress"></th>
                <th  style="width:15%;">Completed<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="completed"></th>
                <th  style="width:15%;">Overdue<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="overdue"></th>
                <th  style="width:15%;">Allocated Time<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="allocated_time"></th>
                <th  style="width:15%;">Spend Time<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="spend_time"></th>
            </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e(empty($sort_id) ? ++$serialUser : $serialUser--); ?></td>
                <td><a href="<?php echo e(route('task-analytics.show',$user->id)); ?>"><?php echo e($user->name); ?></a></td>
                <td>
                    <?php $team = \App\Modals\User::getTeamName($user->id); ?>
                    <?php echo e($team); ?>

                </td>
                <?php $task_status = \App\Modals\User::taskCountByStatus($user->id); ?>
                <?php $spend_time = \App\Modals\WorkLog::getTotalSpendTime($user->id); ?>
                <td class="task-quantity-number Tasks ">
                    <?php echo e($task_status['total']); ?>

                </td>
                <td class="task-quantity-number Accepted">
                    <?php echo e($task_status['accepted']); ?>

                </td>
                <td class="task-quantity-number In-Progress">
                    <?php echo e($task_status['inProgress']); ?>

                </td>
                <td class="task-quantity-number Completed">
                    <?php echo e($task_status['completed']); ?>

                </td>
                <td class="task-quantity-number overdue">
                    <?php echo e($task_status['overdue']); ?>

                </td>
                <td class="task-quantity-number">
                    <?php echo e($task_status['allocated_time']); ?>

                </td>
                <td class="task-quantity-number">
                    <?php echo e($spend_time); ?>

                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>    
        </tbody>
    </table>
</div>
<div class="user-list-card-pagination" id="pageUser" style="margin-bottom:20px;">
    <?php if(count($users) > 0): ?>
        <?php if((($users->currentPage()-1)*$users->perPage())+$users->perPage() < $users->total()): ?>
            Showing from <?php echo e((($users->currentPage()-1)*$users->perPage())+1); ?> to <?php echo e((($users->currentPage()-1)*$users->perPage())+$users->perPage()); ?> of <?php echo e($users->total()); ?> 
        <?php else: ?>
            Showing from <?php echo e((($users->currentPage()-1)*$users->perPage())+1); ?> to <?php echo e($users->total()); ?> of <?php echo e($users->total()); ?> 
        <?php endif; ?>
    <?php else: ?>
        Showing from 0 to 0 of 0    
    <?php endif; ?>
        <?php echo e($users->links()); ?>

</div>

<?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/task_analytics/user.blade.php ENDPATH**/ ?>