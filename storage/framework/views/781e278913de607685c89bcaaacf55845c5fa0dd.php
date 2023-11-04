<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>Task ID<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="task_id"></i></th>
            <th class="pl-1">Task Title<i class='fas fa-exchange-alt custom-sorting'  order="asc" coloumn="title"></i></th>
            <th>Assignee<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="id"></i></th>
            <th>Start Time<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="start_date"></i></th>
            <th>Due Time<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="end_date"></i></th>
            <th>Priority<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="priority"></i></th>
            <th>Status<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="status"></th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $tasks_for_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e(empty($task->task_id) ? '' : $task->task_id); ?></td>
                <td class="pl-1" style="white-space: pre-line;line-height: 18px;">
                    <a href="<?php echo e(route('myRequestTasks.show',$task->id)); ?>" style="color: #009877"><?php echo e(empty($task->title) ? '' : $task->title); ?><?php echo e(empty($task->parent_id) ? '' : ' (Sub-Task)'); ?></a>
                </td>
                <td><?php echo e(empty($task->requestFrom->name) ? '' : $task->requestFrom->name); ?></td>
                <td><?php echo e(empty($task->start_date) ? '' : date('d M, Y', strtotime($task->start_date))); ?></td>
                <!-- <?php if(!empty($task->end_date) && $task->end_date < date('Y-m-d') && $task->status < 2): ?>
                    <td>
                        <p><?php echo e(empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))); ?></p>
                        <p class="assign-to-me-badge badge badge-danger">Overdue</p>
                    </td>
                <?php else: ?>
                    <td><?php echo e(empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))); ?></td>
                <?php endif; ?> -->
                <td><?php echo e(empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))); ?></td>
                <td><?php echo e($task->priority == '0' ? 'Low' : ($task->priority == '1' ? 'Medium' : ($task->priority == '2' ? 'High' : ''))); ?></td>

                <?php if($task->status == '-1'): ?>
                    <td><button class="btn btn-inverse-secondary btn-fw button-pending  task-status">Pending</button></td>
                <?php elseif($task->status == '1'): ?>
                        <td><button class="btn btn-inverse-warning btn-fw  task-status">Accepted</button></td> 
                <?php elseif($task->status == '0'): ?>
                    <td><button class="btn btn-inverse-danger btn-fw task-status">Rejected</button></td>    
                <?php endif; ?>

                <td>
                    <?php if($task->request_from == \Illuminate\Support\Facades\Auth::user()['id'] && $task->status != 1): ?>
                        <a class="Rectangle-Edit btn-hover2" style="text-decoration: none;" href="<?php echo e(route('myRequestTasks.edit', $task->id)); ?>"><i class="fa fa-pencil" style="margin-right: 5px;"></i>Edit</a>

                        <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('myRequestTasks.destroy', $task->id)); ?>"
                            onclick="deleteData('delete-form-<?php echo e($task->id); ?>');"><i class="fa fa-trash"></i>
                            Delete
                        </a>

                        <form id="delete-form-<?php echo e($task->id); ?>" action="<?php echo e(route('myRequestTasks.destroy', $task->id)); ?>" method="POST" class="d-none" style="display: none">
                            <?php echo method_field('DELETE'); ?>
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
   
    <div class="task-list-card-pagination" id="pageTask" style="margin-top:20px;">
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

    </div>
   
</div>
<script>
    function deleteData(id){
        event.preventDefault();
        swal({
                title: "Are you sure?",
                type: "error",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "CONFIRM",
                cancelButtonText: "CANCEL",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function() {
                $.ajax({
                    url: $("#" + id).attr('action'),
                    method: 'POST',
                    data: $("#" + id).serializeArray(),
                    success: function (data) {
                        formSubmit();
                        $("#alert-show").html("<div class='alert alert-success'><div><p>"+data.msg+"</p></div></div>");
                        $("#alert-show").show().delay(5000).fadeOut();
                    }
                });
            }
        );
    }
    
</script>
<?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/my_request_tasks/task_table.blade.php ENDPATH**/ ?>