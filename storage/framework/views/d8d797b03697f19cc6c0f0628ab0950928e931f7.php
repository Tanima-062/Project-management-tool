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
                    <div class="row DataTableBox">
                        <div class="table-responsive" style="margin-bottom: 20px;">
                            <form method="POST" action="<?php echo e(route('worklogs.store')); ?>"> 
                                <?php echo csrf_field(); ?>  
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
                                            <div class='right-inner-addon date datepicker'>
                                                <i class="fa fa-calendar-o date-picker" style="margin-top:7px;"></i>
                                                <input name='dates[]' value="<?php echo e(date('d M, Y')); ?>" type="text" class="form-control date-picker date-picker-input" data-date="" data-date-format="d M, yyyy" autocomplete="off" readonly id="date"/>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="summery[]" rows="5" cols="40"></textarea>
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
                                            <th  style="width:40%%;">Tasks</th>
                                            <th  style="width:10%;">Time</th>
                                            <th style="width:20%">Date</th>
                                            <th style="width: 15%">Summery</th>
                                            <th  style="width:15%;">Add/Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
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
                                                <input type="text" class="form-control" name="times[]" value="<?php echo e(old('times')); ?>" onkeyup="if (/[^0-9\.]/g.test(this.value)) this.value = this.value.replace(/[^0-9\.]/g,'') " required>
                                            </td>
                                            <td>
                                                <div class='right-inner-addon date datepicker'>
                                                    <i class="fa fa-calendar-o date-picker" style="margin-top:7px;"></i>
                                                    <input name='dates[]' value="<?php echo e(date('d M, Y')); ?>" type="text" class="form-control date-picker date-picker-input" data-date="" data-date-format="d M, yyyy" autocomplete="off" readonly id="date"/>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea name="summery[]" rows="5" cols="40"></textarea>
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
        $(".fa-calendar-o").on("click", function(){
            $(this).siblings("input").datepicker({
                forceParse:false,
                autoclose: true,
                immediateUpdates: true,
                todayBtn: true,
                todayHighlight: true
            });
            $(this).siblings("input").datepicker('show');
        });

        function rowAdd(This){
            event.preventDefault();
            This.closest('tbody').append($('.row-copy').clone().removeClass('row-copy').show());
            $('tbody tr:last').find("[type='text']").attr('required',true);
            This.next().show();
            This.hide();
            $(".fa-calendar-o").on("click", function(){
                $(this).siblings("input").datepicker({
                    forceParse:false,
                    autoclose: true,
                    immediateUpdates: true,
                    todayBtn: true,
                    todayHighlight: true
                });
                $(this).siblings("input").datepicker('show');
            });
        }
        function rowDelete(This){
            event.preventDefault();
            This.closest('tr').remove();
        }
        
    </script>

</body>

</html>
<?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/work_logs/create.blade.php ENDPATH**/ ?>