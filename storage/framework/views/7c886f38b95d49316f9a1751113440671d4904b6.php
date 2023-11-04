<div class="table-responsive" style="margin-bottom: 20px;">
    <table class="table supervise-status-table">
        <thead>
            <tr>
                <th>SL No.<i class='fas fa-exchange-alt custom-sorting' style="color:#00ad4d;" order="asc" coloumn="id"></i></th>
                <th  style="width:50%;">Task Name<i class='fas fa-exchange-alt custom-sorting' style="color:#00ad4d;" order="asc" coloumn="title"></i></th>
                <th  style="width:25%;">Assigned By<i class='fas fa-exchange-alt custom-sorting' style="color:#00ad4d;" order="asc" coloumn="assign_by"></i></th>
                <th  style="width:25%;">Start Time<i class='fas fa-exchange-alt custom-sorting' style="color:#00ad4d;" order="asc" coloumn="start_date"></i></th>
                <th  style="width:25%;">Due Time<i class='fas fa-exchange-alt custom-sorting' style="color:#00ad4d;" order="asc" coloumn="end_date"></i></th>
                <th  style="width:25%;">Priority<i class='fas fa-exchange-alt custom-sorting' style="color:#00ad4d;" order="asc" coloumn="priority"></i></th>
                <th  style="width:25%;">Status<i class='fas fa-exchange-alt custom-sorting' style="color:#00ad4d;" order="asc" coloumn="status"></i></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $tasks_for_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e(empty($sort_id) ? ++$serial : $serial--); ?></td>
                    <td class="pl-1" style="white-space: pre-line;line-height: 18px;">
                       <a href="<?php echo e(route('task_analytics.task.show',$task->id)); ?>" style="color: #188ef9"><?php echo e(empty($task->title) ? '' : $task->title); ?><?php echo e(empty($task->parent_id) ? '' : ' (Sub-Task)'); ?></a>
                    </td>
                    <td>
                    <?php echo e(empty($task->assignBy->name) ? '' : $task->assignBy->name); ?>

                    </td>
                    <td><?php echo e(empty($task->start_date) ? '' : date('d M, Y', strtotime($task->start_date))); ?></td>
                    <?php if(!empty($task->end_date) && $task->end_date < date('Y-m-d') && $task->status < 2): ?>
                        <td>
                            <p><?php echo e(empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))); ?></p>
                            <p class="assign-to-me-badge badge badge-danger">Overdue</p>
                        </td>
                    <?php else: ?>
                        <td><?php echo e(empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))); ?></td>
                    <?php endif; ?>
                    <td><?php echo e($task->priority == '0' ? 'Low' : ($task->priority == '1' ? 'Medium' : ($task->priority == '2' ? 'High' : ''))); ?></td>

                    <?php $show_wfh = \App\Modals\Task::showWFH($task); ?>
                    <?php if($task->status == '-1'): ?>
                        <td>
                            <button class="btn btn-inverse-secondary btn-fw button-pending task-status">Pending</button>
                            <?php if($show_wfh): ?>
                                <button class="btn btn-fw task-status WFH">WFH</button>
                            <?php endif; ?>
                        </td>
                    <?php elseif($task->status == '0'): ?>
                        <td>
                            <button class="btn btn-inverse-warning btn-fw  task-status">Accepted</button>
                            <?php if($show_wfh): ?>
                                <button class="btn btn-fw task-status WFH">WFH</button>
                            <?php endif; ?>
                        </td>    
                    <?php elseif($task->status == '1'): ?>
                        <td>
                            <button class="btn btn-inverse-info btn-fw  task-status">In Progress</button>
                            <?php if($show_wfh): ?>
                                <button class="btn btn-fw task-status WFH">WFH</button>
                            <?php endif; ?>
                        </td>
                    <?php elseif($task->status == '2'): ?>
                        <td>
                            <button class="btn btn-inverse-success btn-fw task-status">Completed</button>
                            <?php if($show_wfh): ?>
                                <button class="btn btn-fw task-status WFH">WFH</button>
                            <?php endif; ?>
                        </td>
                    <?php elseif($task->status == '4'): ?>
                        <td>
                            <button class="btn btn-inverse-danger btn-fw task-status">Rejected</button>
                            <?php if($show_wfh): ?>
                                <button class="btn btn-fw task-status WFH">WFH</button>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    
</div>
<div id="pageUserTask" style="margin-top:20px; padding-bottom: 20px;">
    <?php if(count($tasks_for_list) > 0): ?>
        <?php if((($tasks_for_list->currentPage()-1)*$tasks_for_list->perPage())+$tasks_for_list->perPage() < $tasks_for_list->total()): ?>
            Showing from <?php echo e((($tasks_for_list->currentPage()-1)*$tasks_for_list->perPage())+1); ?> to <?php echo e((($tasks_for_list->currentPage()-1)*$tasks_for_list->perPage())+$tasks_for_list->perPage()); ?> of <?php echo e($tasks_for_list->total()); ?> 
        <?php else: ?>
            Showing from <?php echo e((($tasks_for_list->currentPage()-1)*$tasks_for_list->perPage())+1); ?> to <?php echo e($tasks_for_list->total()); ?> of <?php echo e($tasks_for_list->total()); ?> 
        <?php endif; ?>
    <?php else: ?>
        Showing from 0 to 0 of 0
    <?php endif; ?>        
        <?php echo e($tasks_for_list->links()); ?>

</div><?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/task_analytics/task_table.blade.php ENDPATH**/ ?>