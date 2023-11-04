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
                <h3 class="role-list-title">Notification Module</h3>
                <hr class="Dash-Line">
                <?php echo $__env->make('backend.layouts.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="row DataTableBox">
                    <div class="row role-list-table-subHeader">
                        <div class="col-sm-5 subHeader-col-1">
                            <div class="form-inline">
                                <span>
                                    <b>All Event List</b>
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
                        </div>
                    </div>
                    <div>
                        <hr>
                    </div>
                    <form action="<?php echo e(route('notification.module.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="table-responsive" style="margin-bottom: 20px;">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th  style="width:50%;">Events</th>
                                    <th  style="width:25%;">Email</th>
                                    <th  style="width:25%;">In App</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $notification_modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->index+1); ?></td>
                                        <td><?php echo e($event->displayName); ?></td>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="checkbox" name="emails[]" id="email<?php echo e($event->id); ?>" value="<?php echo e($event->event); ?>" <?php echo e(($event->email == 1) ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="email<?php echo e($event->id); ?>"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="checkbox" name="in_app[]" id="in_app<?php echo e($event->id); ?>" value="<?php echo e($event->event); ?>" <?php echo e(($event->in_app == 1) ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="in_app<?php echo e($event->id); ?>"></label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12 text-center">
                                <button class="btn custom-btn">Update</button>
                            </div>
                        </div>
                    </form>
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
                    "pageLength": 20,
                    "dom": 'lrt',
                    "lengthChange": false,
                    columnDefs: [
                        { orderable: false, targets: [-1] }
                    ]
                });
                $('#search').keyup(function(){
                    tableCustom.search($(this).val()).draw() ;
                })

            });
        </script>

</body>

</html>
<?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/notification_modules/index.blade.php ENDPATH**/ ?>