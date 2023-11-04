<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>Task ID<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="task_id"></i></th>
            <th class="pl-1">Task Title<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="title"></i></th>
            <th>Assignee<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="id"></i></th>
            <th>Allocated Time<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="allocated_time"></i></th>
            <th>Priority<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="priority"></i></th>
            <th>Status<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="status"></i></th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $tasks_for_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e(empty($task->task_id) ? '' : $task->task_id); ?></td>
                <td class="pl-1" style="white-space: pre-line;line-height: 18px;">
                    <a href="<?php echo e(route('tasks.show',$task->id)); ?>" style="color: #009877; font-size:15px;"><b><?php echo e(empty($task->title) ? '' : $task->title); ?><?php echo e(empty($task->parent_id) ? '' : ' (Sub-Task)'); ?></b></a>
                    <div class="row">
                        <?php $__currentLoopData = $task->taskTags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task_tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mh-100 h-25 d-inline-block w-50 mw-100 mr-1" style="word-break:break-word; text-align:center; border-radius:10px; background-color: rgba(0,0,255,.1)"><?php echo e($task_tag->Tag->name); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
                    </div> 
                </td>
                <td>  
                    <?php $__currentLoopData = $task->assignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  
                        <?php if((((!empty($assignee->userWithTrashed))&& ($assignee->show_rejected_task == '1'))) || (((!empty($assignee->userWithTrashed)) && ($assignee->task_status != '4') && ($assignee->show_rejected_task == '0')))): ?> 
                            <p><?php echo e(empty($assignee->userWithTrashed->name) ? '' : $assignee->userWithTrashed->name); ?></p>    
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td><?php echo e($task->allocated_time); ?></td>
                <td><?php echo e($task->priority == '0' ? 'Low' : ($task->priority == '1' ? 'Medium' : ($task->priority == '2' ? 'High' : ''))); ?></td>

                <?php if($task->status == '-1'): ?>
                    <td>
                        <button class="btn btn-inverse-secondary btn-fw button-pending  task-status">Pending</button>
                    </td>
                <?php elseif($task->status == '0'): ?>
                    <td>
                        <button class="btn btn-inverse-warning btn-fw  task-status">Accepted</button>
                    </td>    
                <?php elseif($task->status == '1'): ?>
                    <td>
                        <button class="btn btn-inverse-info btn-fw  task-status">In Progress</button>
                    </td>
                <?php elseif($task->status == '2'): ?>
                    <td>
                        <button class="btn btn-inverse-success btn-fw task-status">Completed</button>
                    </td>
                <?php elseif($task->status == '4'): ?>
                    <td>
                        <button class="btn btn-inverse-danger btn-fw task-status">Rejected</button>
                    </td>
                <?php elseif($task->status == '5'): ?>       
                    <td><button class="btn btn-inverse-danger btn-fw task-status" style="background-color:#D4D0EE;color:#483D8B">On Hold</button></td>
                <?php elseif($task->status == '6'): ?>       
                    <td><button class="btn btn-inverse-danger btn-fw task-status" style="background-color:#E3C9EF;color:#a110e3">On Review</button></td>
                <?php endif; ?>

                <td>
                    <a class="Rectangle-Edit btn-hover2" style="text-decoration: none;" href="<?php echo e(route('tasks.edit', $task->id)); ?>"><i class="fa fa-pencil" style="margin-right: 5px;"></i>Edit</a>
                    <?php if($task->assign_by_id == \Illuminate\Support\Facades\Auth::user()['id']): ?>
                        <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('tasks.destroy', $task->id)); ?>"
                            onclick="deleteData('delete-form-<?php echo e($task->id); ?>');"><i class="fa fa-trash"></i>
                            Delete
                        </a>
                    <?php endif; ?>

                    <form id="delete-form-<?php echo e($task->id); ?>" action="<?php echo e(route('tasks.destroy', $task->id)); ?>" method="POST" class="d-none" style="display: none">
                        <?php echo method_field('DELETE'); ?>
                        <?php echo csrf_field(); ?>
                    </form>
                </td>
            </tr>
            <?php $subTasks = \App\Modals\Task::getSubTasks($task); ?>
            <?php $__currentLoopData = $subTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr style="background-color: #F0F0F0">
                    <td><?php echo e(empty($task->task_id) ? '' : $task->task_id); ?></td>
                    <td class="pl-1" style="white-space: pre-line;line-height: 18px;">
                        <a href="<?php echo e(route('tasks.show',$task->id)); ?>" style="color: #009877; font-size:15px;"><b><?php echo e(empty($task->title) ? '' : $task->title); ?><?php echo e(empty($task->parent_id) ? '' : ' (Sub-Task)'); ?></b></a>
                        <div class="row">
                            <?php $__currentLoopData = $task->taskTags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task_tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="mh-100 h-25 d-inline-block mw-100 mr-1" style="word-break:break-word; width:70px; border-radius:10px; background-color: rgba(0,0,255,.1)"><?php echo e($task_tag->Tag->name); ?></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
                        </div> 
                    </td>
                    <td>  
                        <?php $__currentLoopData = $task->assignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  
                            <?php if((((!empty($assignee->userWithTrashed))&& ($assignee->show_rejected_task == '1'))) || (((!empty($assignee->userWithTrashed)) && ($assignee->task_status != '4') && ($assignee->show_rejected_task == '0')))): ?> 
                                <p><?php echo e(empty($assignee->userWithTrashed->name) ? '' : $assignee->userWithTrashed->name); ?></p>    
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td><?php echo e($task->allocated_time); ?></td>
                    <td><?php echo e($task->priority == '0' ? 'Low' : ($task->priority == '1' ? 'Medium' : ($task->priority == '2' ? 'High' : ''))); ?></td>

                    <?php if($task->status == '-1'): ?>
                        <td>
                            <button class="btn btn-inverse-secondary btn-fw button-pending  task-status">Pending</button>
                        </td>
                    <?php elseif($task->status == '0'): ?>
                        <td>
                            <button class="btn btn-inverse-warning btn-fw  task-status">Accepted</button>
                        </td>    
                    <?php elseif($task->status == '1'): ?>
                        <td>
                            <button class="btn btn-inverse-info btn-fw  task-status">In Progress</button>
                        </td>
                    <?php elseif($task->status == '2'): ?>
                        <td>
                            <button class="btn btn-inverse-success btn-fw task-status">Completed</button>
                        </td>
                    <?php elseif($task->status == '4'): ?>
                        <td>
                            <button class="btn btn-inverse-danger btn-fw task-status">Rejected</button>
                        </td>
                    <?php endif; ?>

                    <td>
                        <a class="Rectangle-Edit btn-hover2" style="text-decoration: none;" href="<?php echo e(route('tasks.edit', $task->id)); ?>"><i class="fa fa-pencil" style="margin-right: 5px;"></i>Edit</a>
                        <?php if($task->assign_by_id == \Illuminate\Support\Facades\Auth::user()['id']): ?>
                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('tasks.destroy', $task->id)); ?>"
                                onclick="deleteData('delete-form-<?php echo e($task->id); ?>');"><i class="fa fa-trash"></i>
                                Delete
                            </a>
                        <?php endif; ?>

                        <form id="delete-form-<?php echo e($task->id); ?>" action="<?php echo e(route('tasks.destroy', $task->id)); ?>" method="POST" class="d-none" style="display: none">
                            <?php echo method_field('DELETE'); ?>
                            <?php echo csrf_field(); ?>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });
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
<?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/tasks/task_table.blade.php ENDPATH**/ ?>