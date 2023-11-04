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
        <div class="main-panel users-list-main-panel">
            <div class="content-wrapper" >
                <div id="alert-show">
                    <?php echo $__env->make('backend.layouts.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <h3 class="users-list-title">User Management</h3>
                <hr class="Dash-Line">
                <div class="row DataTableBox">
                    <div class="row users-list-table-subHeader">
                        <div class="col-sm-5 subHeader-col-1">
                            <div class="form-inline">
                                <span><b>All Users List</b></span>
                                <input class="form-control mr-sm-2 user-list-search" type="search" placeholder="Search..." aria-label="Search" id="search_key">
                                <input type="hidden" name="user-order" id="user_order" value="order">
                                <input type="hidden" name="user-coloumn" id="user_coloumn">
                            </div>
                        </div>
                        <div class="col-sm-7 subHeader-col-2">
                            <a href="<?php echo e(route('users.export')); ?>">
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
                            <div style="display: inline-block;">
                                <a class="btn-hover" href="<?php echo e(route('users.show.bulkUpload')); ?>"><button class="user-list-Bulk-Upload">Bulk Upload</button></a>
                            </div>
                            <?php if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'user.create')): ?>
                                <a class="btn-hover" href="<?php echo e(route('users.create')); ?>"><button class="user-list-New-User">New User</button></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                      <hr>
                    </div>

                    <div class="user-table-section">
                        <?php echo $__env->make('backend.users.fetch_user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                search();
                pagination();
                sortingColoumn();
            });
            function sortingColoumn(){
                $('.fas').click(function(){
                    $("#user_order").val($(this).attr('order'));
                    $("#user_coloumn").val($(this).attr('coloumn'));
                    formSubmit();
                });
            }

            function formSubmit(){
                var query = $("#search_key").val();
                var coloumn = $("#user_coloumn").val();
                var order = $("#user_order").val();
                $.ajax({
                    url:'/project_management_tool/user/search',
                    method:'GET',
                    data:{query:query, order:order, coloumn:coloumn},
                    success:function(data){
                        $(".user-table-section").html(data.view);
                        if($("#user_order").val() == 'asc'){
                            var coloumnUser = $("#user_coloumn").val();
                            $('[coloumn= '+coloumnUser+']').attr('order','desc');
                        }
                        pagination();
                        sortingColoumn();
                    }
                }) 
            }
            function search(){
                $("#search_key").keyup(function(){
                    formSubmit();
                });
            }
            function pagination(){
                $('.page-link').click(function(event){
                    event.preventDefault();
                    var page = $(this).attr('href').split('page=')[1];
                    var query = $("#search_key").val();
                    if (page) {
                        $.ajax({
                            url: '/project_management_tool/user/search?page=' + page,
                            method:'GET',
                            data:{query:query},
                            success:function(data){
                                $('.user-table-section').html(data.view);
                                pagination();
                                sortingColoumn();
                            }
                        })
                    }
                });
            }
        </script>

</body>

</html>
<?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/users/list.blade.php ENDPATH**/ ?>