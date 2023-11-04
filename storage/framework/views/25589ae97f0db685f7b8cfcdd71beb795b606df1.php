<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    <?php echo $__env->make('backend.layouts.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="stylesheet" href="<?php echo e(asset('backend/assets/templates/vendors/font-awesome/css/font-awesome.min.css')); ?>">
</head>
<body>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php echo $__env->make('backend.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="container-fluid page-body-wrapper">
        <?php echo $__env->make('backend.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="main-panel role-create-main-panel">
            <div class="content-wrapper">
                <div id="alert-show">
                    <?php echo $__env->make('backend.layouts.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <h3 class="role-create-title">Role Management</h3>
                <hr class="Dash-Line">
                <div class="CreateRolekBox card-body">
                    <h5 class="mb-4 mt-2">Add New Role</h5>
                    <hr>
                    <div class="add-role-form">
                        <form action="<?php echo e(route('roles.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="name">Role Name <span class="mandatory">*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter a Role Name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="status">Status <span class="mandatory">*</span></label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="radio-item">
                                        <input type="radio" id="status" name="status" value="1" checked>
                                        <label for="status">Active</label>
                                    </div>
    
                                    <div class="radio-item">
                                        <input type="radio" id="inactive" name="status" value="0">
                                        <label for="inactive">Inactive</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 pt-3">
                                    <label>Permissions</label>
                                </div>
                                <div class="col-md-10 form-check">
                                    <input type="checkbox" class="checkbox" id="checkPermissionAll" value="1">
                                    <label class="form-check-label" for="checkPermissionAll">All</label>
                                </div>
                            </div>   
                            <hr> 
                            <div class="form-group row">
                                <div class="col-md-2"></div>
                                <div class="col-md-10 form-check">                               
                                    <?php $i=1; ?>
                                    <?php $__currentLoopData = $permission_groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="row">
                                            <div class="col-3 group-check">
                                                <div class="form-check">
                                                    <input type="checkbox" class="checkbox" id="<?php echo e($i); ?>Management" value="<?php echo e($group->name); ?>" onclick="checkPermissionByGroup('role-<?php echo e($i); ?>-management-checkbox',this)">
                                                    <label class="form-check-label" for="<?php echo e($i); ?>Management"><?php echo e($group->name); ?></label>
                                                </div>
                                            </div>
                                            <div class="col-9 role-group role-<?php echo e($i); ?>-management-checkbox">
                                                <?php
                                                    $permissions = App\Modals\User::getPermissionsByGroupName($group->name);
                                                    $j=1;
                                                ?>
                                                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="checkbox" name="permissions[]" id="checkPermission<?php echo e($permission->id); ?>" value="<?php echo e($permission->name); ?>" onclick="selectDeselectGroup('<?php echo e($i); ?>Management','role-<?php echo e($i); ?>-management-checkbox')">
                                                        <label class="form-check-label" for="checkPermission<?php echo e($permission->id); ?>"><?php echo e($permission->displayName); ?></label>
                                                    </div>
                                                    <?php $j++; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div><br>
                                        <?php $i++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>                            
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 text-center">
                                    <a href="<?php echo e(route('roles.index')); ?>" class="btn custom-outline-btn">Cancel</a>
                                    <button class="btn custom-btn">Create</button>
                                </div>
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
            $("#checkPermissionAll").click(function () {
                if($(this).is(':checked')){
                    $("input[type=checkbox]").prop('checked',true);
                }else{
                    $("input[type=checkbox]").prop('checked',false);
                }
            });

            function checkPermissionByGroup(className, checkThis) {
                const groupIdName = $("#"+checkThis.id);
                const classCheckBox = $("."+className+" input");
                if(groupIdName.is(':checked')){
                    classCheckBox.prop('checked',true);
                }else{
                    classCheckBox.prop('checked',false);
                }
                checkAllPermissions();
            }
            function selectDeselectGroup(groupIdName,className){
                const classCheckBox = $("."+className+" input");
                let flag = true;
                classCheckBox.each(function () {
                    if($(this).is(':not(:checked)')){
                        $("#"+groupIdName).prop('checked', false);
                        $("#"+groupIdName).removeAttr('checked');
                        flag = false;
                        return false;
                    }
                });
                if(flag){
                    $("#"+groupIdName).prop('checked', true);
                }
                checkAllPermissions();
            }
            function checkAllPermissions() {
                let flagAll = true;
                $('.group-check input').each(function () {
                    if($(this).is(':not(:checked)')){
                        $("#checkPermissionAll").prop('checked', false);
                        flagAll = false;
                        return false;
                    }
                });
                if(flagAll){
                    $("#checkPermissionAll").prop('checked', true);
                }
            }
        </script>


</body>

</html>
<?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/roles/create.blade.php ENDPATH**/ ?>