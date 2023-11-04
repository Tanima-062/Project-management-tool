<div class="table-responsive" style="margin-bottom: 20px;">
    <table class="table supervise-status-table">
        <thead>
            <tr>
                <th>SL No.<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderMember="asc" coloumnMember="id"></th>
                <th  style="width:25%;">Full Name<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderMember="asc" coloumnMember="name"></th>
                <th  style="width:50%;">Team</th>
                <th  style="width:25%;">Total Task<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderMember="asc" coloumnMember="total"></th>
                <th  style="width:25%;">Accepted<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderMember="asc" coloumnMember="accepted"></th>
                <th  style="width:25%;">In Progress<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderMember="asc" coloumnMember="inProgress"></th>
                <th  style="width:15%;">Completed<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderMember="asc" coloumnMember="completed"></th>
                <th  style="width:15%;">Overdue<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderMember="asc" coloumnMember="overdue"></th>
                <th  style="width:15%;">Allocated Time<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="allocated_time"></th>
                <th  style="width:15%;">Spend Time<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="spend_time"></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e(empty($sort_id) ? ++$serialMember : $serialMember--); ?></td>
                <td><a href="<?php echo e(route('task-analytics.show',$member->id)); ?>"><?php echo e($member->name); ?></a></td>
                <td>
                    <?php $team = \App\Modals\User::getTeamName($member->id); ?>
                    <?php echo e($team); ?>

                </td>
                <?php $task_status = \App\Modals\User::taskCountByStatus($member->id); ?>
                <?php $spend_time = \App\Modals\WorkLog::getTotalSpendTime($member->id); ?>
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
<div class="member-list-card-pagination" id="pageMember" style="margin-bottom:20px;">
    <?php if(count($members) > 0): ?>
        <?php if((($members->currentPage()-1)*$members->perPage())+$members->perPage() < $members->total()): ?>
            Showing from <?php echo e((($members->currentPage()-1)*$members->perPage())+1); ?> to <?php echo e((($members->currentPage()-1)*$members->perPage())+$members->perPage()); ?> of <?php echo e($members->total()); ?> 
        <?php else: ?>
            Showing from <?php echo e((($members->currentPage()-1)*$members->perPage())+1); ?> to <?php echo e($members->total()); ?> of <?php echo e($members->total()); ?> 
        <?php endif; ?>
    <?php else: ?>
        Showing from 0 to 0 of 0    
    <?php endif; ?>
        <?php echo e($members->links()); ?>

</div>

<?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/task_analytics/member.blade.php ENDPATH**/ ?>