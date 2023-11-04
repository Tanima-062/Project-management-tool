<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    <?php echo $__env->make('backend.layouts.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        a:hover{
            text-decoration:none;
        }
    </style>
</head>
<body>
    <div class="container-scroller role-list-page">
        <!-- partial:partials/_navbar.html -->
        <?php echo $__env->make('backend.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="container-fluid page-body-wrapper">
            <?php echo $__env->make('backend.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="main-panel role-list-main-panel">
                <div class="content-wrapper">
                    <div id="alert-show">
                        <?php echo $__env->make('backend.layouts.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <h3 class="role-list-title">Work Log</h3>
                    <hr class="Dash-Line">
                    <div class="row DataTableBox">
                        <div class="row role-list-table-subHeader">
                            <div class="col-sm-5 subHeader-col-1">
                                <div class="form-inline">
                                
                                </div>
                            </div>
                            <div class="col-sm-7 subHeader-col-2">
                            </div>
                        </div>
                        <div class="table-responsive" style="margin-bottom: 20px;">
                            <form method="POST" action="<?php echo e(route('worklogs.update')); ?>"> 
                                <?php echo csrf_field(); ?>  
                                <input type="hidden" value="<?php echo e($date); ?>" name="date">
                                <table class="table">
                                    <tr class="row-copy" style="display:none;">
                                        <td>
                                            <select class="form-control col-md-12" name="tasks[]">
                                                <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($task->id); ?>" <?php echo e((collect(old('task'))->contains($task->id)) ? 'selected':''); ?>><?php echo e($task->title); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="times[]" value="<?php echo e(old('times')); ?>" onkeyup="if (/[^0-9\.]/g.test(this.value)) this.value = this.value.replace(/[^0-9\.]/g,'')">
                                        </td>
                                        <td>
                                            <textarea name="summery[]" rows="5" cols="80"><?php echo e(old('summery')); ?></textarea>
                                        </td>
                                        <td>
                                            <div class="w3-container">
                                                <a class="w3-button w3-xlarge w3-circle w3-teal" onclick="rowAdd($(this))">+</a>
                                                <a class="w3-button w3-xlarge w3-circle w3-red w3-card-4" onclick="rowDelete($(this))" style="display:none;">-</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <thead>
                                        <tr>
                                            <th  style="width:35%;">Tasks</th>
                                            <th  style="width:15%;">Time</th>
                                            <th style="width:35%;">Summery</th>
                                            <th  style="width:15%;">Add/Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $work_logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $worklog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="existing-tasks">
                                                <td>
                                                    <select class="form-control <?php echo e($errors->has('tasks') ? ' is-invalid' : ''); ?> col-md-12" name="tasks[]">
                                                        <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($task->id); ?>" <?php echo e(((collect(old('task'))->contains($task->id)) ? 'selected': ($task->id == $worklog->task_id)) ? 'selected' : ''); ?>><?php echo e($task->title); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="times[]" value="<?php echo e(old('times') ? old('times') : $worklog->time); ?>" onkeyup="if (/[^0-9\.]/g.test(this.value)) this.value = this.value.replace(/[^0-9\.]/g,'') " required>
                                                </td>
                                                <td>
                                                    <textarea name="summery[]" rows="5" cols="80"><?php echo e($worklog->summery); ?></textarea>
                                                </td>
                                                <td>
                                                    <div class="w3-container">
                                                        <a class="w3-button w3-xlarge w3-circle w3-red w3-card-4" onclick="rowDelete($(this))">-</a>
                                                    </div>
                                                </td>
                                            </tr>   
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="new-task">
                                            <td>
                                                <select class="form-control <?php echo e($errors->has('tasks') ? ' is-invalid' : ''); ?> col-md-12" name="tasks[]">
                                                    <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($task->id); ?>" <?php echo e((collect(old('task'))->contains($task->id)) ? 'selected':''); ?>><?php echo e($task->title); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <?php if($errors->has('tasks')): ?>
                                                <span class="invalid-feedback" role="alert">
                                                    <strong><?php echo e($errors->first('tasks')); ?></strong>
                                                </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="times[]" value="<?php echo e(old('times')); ?>" onkeyup="if (/[^0-9\.]/g.test(this.value)) this.value = this.value.replace(/[^0-9\.]/g,'') ">
                                            </td>
                                            <td>
                                                <textarea name="summery[]" rows="5" cols="80"><?php echo e(old('summery')); ?></textarea>
                                            </td>
                                            <td>
                                                <div class="w3-container">
                                                    <a class="w3-button w3-xlarge w3-circle w3-teal" onclick="rowAdd($(this))">+</a>
                                                    <a class="w3-button w3-xlarge w3-circle w3-red w3-card-4" onclick="rowDelete($(this))" style="display:none;">-</a>
                                                </div>
                                            </td>
                                        </tr>   
                                    </tbody>
                                </table>
                                <div class="text-center mt-4">
                                    <a class="btn custom-outline-btn">Cancel</a>
                                    <button class="btn custom-btn" type="submit">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                
                <!-- partial -->
                </div>
                <!-- main-panel ends -->
            </div>
        </div>
    </div>
        <!-- page-body-wrapper ends -->
        <!-- container-scroller -->

    <?php echo $__env->make('backend.layouts.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script>
        function rowAdd(This){
            event.preventDefault();
            This.closest('tbody').append($('.row-copy').clone().removeClass('row-copy').show());
            $('tbody tr:last').find("[type='text']").attr('required',true);
            This.next().show();
            This.hide();
        }
        function rowDelete(This){
            event.preventDefault();
            This.closest('tr').remove();
            if($('.existing-tasks').length == 0){
                $('.new-task').find("[type='text']").attr('required',true);
            }
        }
        
    </script>

</body>

</html>
<?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/work_logs/edit.blade.php ENDPATH**/ ?>