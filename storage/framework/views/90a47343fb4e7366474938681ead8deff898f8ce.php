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
                <h3 class="role-list-title">Role Management</h3>
                <hr class="Dash-Line">
                <div class="row DataTableBox">
                    <div class="row role-list-table-subHeader">
                        <div class="col-sm-5 subHeader-col-1">
                            <div class="form-inline">
                                <span>
                                    <b>All Role List</b>
                                </span>
                                <input class="form-control role-list-search mr-sm-2 " type="search" id="search" placeholder="Search..." aria-label="Search">
                            </div>
                        </div>
                        <div class="col-sm-7 subHeader-col-2">
                            <a href="<?php echo e(route('roles.export')); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="34.253" viewBox="0 0 116.299 34.253">
                                    <defs>
                                        <style>
                                            .cls-3{fill:#576271}
                                        </style>
                                    </defs>
                                    <g id="Export_Btn_with_Icon" transform="translate(-1712.137 -1010)">
                                        <g id="Rectangle_57" fill="#fbfbfb" stroke="#586372" transform="translate(1712.137 1010)">
                                            <rect width="116.299" height="34.253" stroke="none" rx="7"/>
                                            <rect width="115.299" height="33.253" x=".5" y=".5" fill="none" rx="6.5"/>
                                        </g>
                                        <text id="Export" fill="#576271" font-family="OpenSans-Regular, Open Sans" font-size="17px" transform="translate(1776.711 1033)">
                                            <tspan x="-25.994" y="0">Export</tspan>
                                        </text>
                                        <g id="Export_Icon" transform="translate(1724.555 1018.585)">
                                            <path id="Path_503" d="M29.983 1.2a4.116 4.116 0 0 0-5.814 0l-2.907 2.909a.632.632 0 1 0 .894.891l2.907-2.9a2.846 2.846 0 0 1 4.025 4.025l-3.8 3.8a2.85 2.85 0 0 1-4.025 0 .632.632 0 1 0-.894.894 4.116 4.116 0 0 0 5.814 0l3.8-3.8a4.121 4.121 0 0 0 0-5.814z" class="cls-3" transform="translate(-13.801 -.001)"/>
                                            <path id="Path_504" d="M8.582 24.917l-2.46 2.46A2.846 2.846 0 0 1 2.1 23.352l3.578-3.578a2.857 2.857 0 0 1 4.025 0 .632.632 0 0 0 .894-.894 4.116 4.116 0 0 0-5.814 0L1.2 22.458a4.111 4.111 0 0 0 5.814 5.813l2.46-2.46a.632.632 0 0 0-.894-.894z" class="cls-3" transform="translate(-.003 -12.087)"/>
                                        </g>
                                    </g>
                                </svg>
                            </a>
                            <?php if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'role.create')): ?>
                            <a class="btn-hover" href="<?php echo e(route('roles.create')); ?>">
                                <button class="role-list-New-role-btn">New Role</button>
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
                                <th>SL</th>
                                <th  style="width:25%;">Name</th>
                                <th  style="width:50%;">Permission</th>
                                <th  style="width:25%;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(isset($role)): ?>
                                    <tr>
                                        <td><?php echo e($loop->index+1); ?></td>
                                        <td><?php echo e($role->name); ?></td>
                                        <td>
                                            <?php $__currentLoopData = $role->permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <p><?php echo e($perm->displayName); ?></p>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td>
                                            <?php if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'role.edit')): ?>
                                               <a class="Rectangle-Edit btn-hover2" style="text-decoration: none;" href="<?php echo e(route('roles.edit', $role->id)); ?>"><i class="fa fa-pencil" style="margin-right: 5px;"></i>Edit</a>
                                            <?php endif; ?>
                                            <?php if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'role.delete')): ?>
                                                <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('roles.destroy', $role->id)); ?>"
                                                   onclick="deleteData('delete-form-<?php echo e($role->id); ?>')"><i class="fa fa-trash"></i>
                                                    Delete
                                                </a>

                                                <form id="delete-form-<?php echo e($role->id); ?>" action="<?php echo e(route('roles.destroy', $role->id)); ?>" method="POST" class="d-none" style="display: none">
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
                    "pageLength": 4,
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
<?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/roles/list.blade.php ENDPATH**/ ?>