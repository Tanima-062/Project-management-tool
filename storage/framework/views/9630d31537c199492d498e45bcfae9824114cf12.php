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
        <div class="main-panel role-edit-main-panel">
            <div class="content-wrapper">
                <div id="alert-show">
                    <?php echo $__env->make('backend.layouts.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <h3 class="role-edit-title">Team</h3>
                <hr class="Dash-Line">
                <div class="CreateRolekBox card-body">
                    <h5 class="mb-4 mt-2">Edit Team - <?php echo e($team->name); ?></h5>
                    <hr>
                    <div class="add-role-form">
                        <form action="<?php echo e(route('teams.update',$team->id)); ?>" method="POST">
                            <?php echo method_field('PUT'); ?>
                            <?php echo csrf_field(); ?>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="name">Team Name <span class="mandatory">*</span></label>
                                </div>
                                <div class="col-md-10" >
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo e($team->name); ?>" placeholder="Enter a Role Name">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <div class="col-md-12 text-center">
                                    <a href="<?php echo e(route('teams.index')); ?>" class="btn custom-outline-btn">Cancel</a>
                                    <button class="btn custom-btn" type="submit">Update</button>
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

</body>

</html>
<?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/teams/edit.blade.php ENDPATH**/ ?>