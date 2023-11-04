<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    <?php echo $__env->make('backend.layouts.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                <h3 class="role-list-title">Team</h3>
                <hr class="Dash-Line">
                <div class="row DataTableBox">
                    <div class="row role-list-table-subHeader">
                        <div class="col-sm-5 subHeader-col-1">
                            <div class="form-inline">
                                <span>
                                    <b>All Team List</b>
                                </span>
                                <input class="form-control role-list-search mr-sm-2 " type="search" id="search" placeholder="Search..." aria-label="Search">
                            </div>
                        </div>
                        <div class="col-sm-7 subHeader-col-2">
                            <?php if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'team.create')): ?>
                            <a class="btn-hover" href="<?php echo e(route('teams.create')); ?>">
                                <button class="role-list-New-role-btn">New Team</button>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <hr>
                    </div>
                    <div class="table-responsive" style="margin-bottom: 20px;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width:10%">SL</th>
                                <th  style="width:65%;">Name</th>
                                <th  style="width:25%;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(isset($team)): ?>
                                    <tr>
                                        <td><?php echo e($loop->index+1); ?></td>
                                        <td><?php echo e($team->name); ?></td>
                                        <td>
                                            <?php if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'team.edit')): ?>
                                               <a class="Rectangle-Edit btn-hover2" style="text-decoration: none;" href="<?php echo e(route('teams.edit', $team->id)); ?>"><i class="fa fa-pencil" style="margin-right: 5px;"></i>Edit</a>
                                            <?php endif; ?>
                                            <?php if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'team.delete')): ?>
                                                <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('teams.destroy', $team->id)); ?>"
                                                   onclick="deleteData('delete-form-<?php echo e($team->id); ?>')"><i class="fa fa-trash"></i>
                                                    Delete
                                                </a>

                                                <form id="delete-form-<?php echo e($team->id); ?>" action="<?php echo e(route('teams.destroy', $team->id)); ?>" method="POST" class="d-none" style="display: none">
                                                    <?php echo method_field('DELETE'); ?>
                                                    <?php echo csrf_field(); ?>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
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
            $(document).ready(function() {
                const tableCustom =  $('.table').DataTable({
                    "pageLength": 10,
                    "dom": 'lrtip',
                    "lengthChange": false,
                    columnDefs: [
                        { orderable: false, targets: [-1] }
                    ]
                });
                $('#search').keyup(function(){
                    tableCustom.search($(this).val()).draw() ;
                })

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
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function() {
                        $.ajax({
                            url: $("#" + id).attr('action'),
                            method: 'POST',
                            data: $("#" + id).serializeArray(),
                            success: function () {
                                location.reload();
                            }
                        });
                    }
                );
            }
        </script>

</body>

</html>
<?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/teams/list.blade.php ENDPATH**/ ?>