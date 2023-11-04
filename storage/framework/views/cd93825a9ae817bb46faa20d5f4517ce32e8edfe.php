<table class="table">
    <thead>
    <col>
    <colgroup span="3"></colgroup>
    <colgroup span="3"></colgroup>
    <colgroup span="3"></colgroup>
    <colgroup span="3"></colgroup>
    <colgroup span="3"></colgroup>
    <tr>
        <th rowspan="2">Name</th>
        <th colspan="3" scope="colgroup">Sun</th>
        <th colspan="3" scope="colgroup">Mon</th>
        <th colspan="3" scope="colgroup">Tue</th>
        <th colspan="3" scope="colgroup">Wed</th>
        <th colspan="3" scope="colgroup">Thu</th>
    </tr>
    <tr>
        <th colspan="2">Task</th>
        <th colspan="1">Action</th>
        <th colspan="2">Task</th>
        <th colspan="1">Action</th>
        <th colspan="2">Task</th>
        <th colspan="1">Action</th>
        <th colspan="2">Task</th>
        <th colspan="1">Action</th>
        <th colspan="2">Task</th>
        <th colspan="1">Action</th>
    </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>   
            <?php $worklogs = \App\Modals\WorkLog::getWorkLogs($user->id,$weekId); ?>
                <?php if(count($worklogs) > 0): ?>
                    <?php for($i=0; $i<$worklogs['maxRow']; $i++): ?>
                        <tr>
                            <?php if($i==0): ?>
                                <td rowspan="<?php echo e($worklogs['maxRow']); ?>"><p><?php echo e($user->name); ?></p><p>Total Tasks: <?php echo e($worklogs['totalTasks']); ?></p><p>Total Spent Time: <?php echo e($worklogs['totalTime']); ?></p></td>
                            <?php endif; ?>

                            <?php if(isset($worklogs['sunTasks'][$i])): ?>
                                <?php $worklog = $worklogs['sunTasks'][$i]; ?>
                                <td class="pl-1" style="white-space: pre-line;line-height: 18px;" colspan="2">
                                    <a href="<?php echo e(route('tasks.show',$worklog['task']->id)); ?>" style="color: #009877"><?php echo e(empty($worklog['task']->Task->title) ? '' : $worklog['task']->Task->title); ?><?php echo e(empty($worklog['task']->Task->parent_id) ? '' : ' (Sub-Task)'); ?> (<?php echo e($worklog['time']); ?>)</a>
                                </td>
                            <?php else: ?>
                                <td colspan="2"></td>
                            <?php endif; ?>
                            <?php if($i==0): ?>
                                <td rowspan="<?php echo e($worklogs['maxRow']); ?>">
                                    <?php if($i==0 && count($worklogs['sunTasks']) > 0): ?>
                                        <?php if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id): ?>
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('worklogs.edit', $worklog['task']->date)); ?>"><i class="fa fa-pencil" style="margin-right: 5px;"></i></a>
                                        <?php endif; ?>
                                        <?php if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id): ?>
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('worklogs.destroy', $worklog['task']->id)); ?>"
                                                onclick="deleteData(<?php echo e($worklog['task']->id); ?>)"><i class="fa fa-trash"></i>  
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>

                            <?php if(isset($worklogs['monTasks'][$i])): ?>
                                <?php $worklog = $worklogs['monTasks'][$i]; ?>
                                <td class="pl-1" style="white-space: pre-line;line-height: 18px;" colspan="2">
                                    <a href="<?php echo e(route('tasks.show',$worklog['task']->id)); ?>" style="color: #009877"><?php echo e(empty($worklog['task']->Task->title) ? '' : $worklog['task']->Task->title); ?><?php echo e(empty($worklog['task']->Task->parent_id) ? '' : ' (Sub-Task)'); ?> (<?php echo e($worklog['time']); ?>)</a>
                                </td>
                            <?php else: ?>
                                <td colspan="2"></td>
                            <?php endif; ?>
                            <?php if($i==0): ?>
                                <td rowspan="<?php echo e($worklogs['maxRow']); ?>">
                                    <?php if($i==0 && count($worklogs['monTasks']) > 0): ?>    
                                        <?php if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id): ?>
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('worklogs.edit', $worklog['task']->date)); ?>"><i class="fa fa-pencil" style="margin-right: 5px;"></i></a>
                                        <?php endif; ?>
                                        <?php if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id): ?>
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('worklogs.destroy', $worklog['task']->id)); ?>"
                                            onclick="deleteData(<?php echo e($worklog['task']->id); ?>)"><i class="fa fa-trash"></i>
                                                
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>

                            <?php if(isset($worklogs['tueTasks'][$i])): ?>
                                <?php $worklog = $worklogs['tueTasks'][$i]; ?>
                                <td class="pl-1" style="white-space: pre-line;line-height: 18px;" colspan="2">
                                    <a href="<?php echo e(route('tasks.show',$worklog['task']->id)); ?>" style="color: #009877"><?php echo e(empty($worklog['task']->Task->title) ? '' : $worklog['task']->Task->title); ?><?php echo e(empty($worklog['task']->Task->parent_id) ? '' : ' (Sub-Task)'); ?> (<?php echo e($worklog['time']); ?>)</a>
                                </td>
                            <?php else: ?>
                                <td colspan="2"></td>
                            <?php endif; ?>
                            <?php if($i==0): ?>
                                <td rowspan="<?php echo e($worklogs['maxRow']); ?>">
                                    <?php if($i==0 && count($worklogs['tueTasks']) > 0): ?>    
                                        <?php if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id): ?>
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('worklogs.edit', $worklog['task']->date)); ?>"><i class="fa fa-pencil" style="margin-right: 5px;"></i></a>
                                        <?php endif; ?>
                                        <?php if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id): ?>
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('worklogs.destroy', $worklog['task']->id)); ?>"
                                            onclick="deleteData(<?php echo e($worklog['task']->id); ?>)"><i class="fa fa-trash"></i>
                                                
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>

                            <?php if(isset($worklogs['wedTasks'][$i])): ?>
                                <?php $worklog = $worklogs['wedTasks'][$i]; ?>
                                <td class="pl-1" style="white-space: pre-line;line-height: 18px;" colspan="2">
                                    <a href="<?php echo e(route('tasks.show',$worklog['task']->id)); ?>" style="color: #009877"><?php echo e(empty($worklog['task']->Task->title) ? '' : $worklog['task']->Task->title); ?><?php echo e(empty($worklog['task']->Task->parent_id) ? '' : ' (Sub-Task)'); ?> (<?php echo e($worklog['time']); ?>)</a>
                                </td>
                            <?php else: ?>
                                <td colspan="2"></td>
                            <?php endif; ?>
                            <?php if($i==0): ?>
                                <td rowspan="<?php echo e($worklogs['maxRow']); ?>">
                                    <?php if($i==0 && count($worklogs['wedTasks']) > 0): ?>    
                                        <?php if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id): ?>
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('worklogs.edit', $worklog['task']->date)); ?>"><i class="fa fa-pencil" style="margin-right: 5px;"></i></a>
                                        <?php endif; ?>
                                        <?php if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id): ?>
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('worklogs.destroy', $worklog['task']->id)); ?>"
                                            onclick="deleteData(<?php echo e($worklog['task']->id); ?>)"><i class="fa fa-trash"></i>
                                                
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>

                            <?php if(isset($worklogs['thuTasks'][$i])): ?>
                                <?php $worklog = $worklogs['thuTasks'][$i]; ?>
                                <td class="pl-1" style="white-space: pre-line;line-height: 18px;" colspan="2">
                                    <a href="<?php echo e(route('tasks.show',$worklog['task']->id)); ?>" style="color: #009877"><?php echo e(empty($worklog['task']->Task->title) ? '' : $worklog['task']->Task->title); ?><?php echo e(empty($worklog['task']->Task->parent_id) ? '' : ' (Sub-Task)'); ?> (<?php echo e($worklog['time']); ?>)</a>
                                </td>
                            <?php else: ?>
                                <td colspan="2"></td>
                            <?php endif; ?>
                            <?php if($i==0): ?>
                                <td rowspan="<?php echo e($worklogs['maxRow']); ?>">
                                    <?php if($i==0 && count($worklogs['thuTasks']) > 0): ?>    
                                        <?php if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id): ?>
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('worklogs.edit', $worklog['task']->date)); ?>"><i class="fa fa-pencil" style="margin-right: 5px;"></i></a>
                                        <?php endif; ?>
                                        <?php if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id): ?>
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('worklogs.destroy', $worklog['task']->id)); ?>"
                                            onclick="deleteData(<?php echo e($worklog['task']->id); ?>)"><i class="fa fa-trash"></i>
                                                
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endfor; ?>
                <?php endif; ?>    
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table><?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/work_logs/fetch_work_log.blade.php ENDPATH**/ ?>