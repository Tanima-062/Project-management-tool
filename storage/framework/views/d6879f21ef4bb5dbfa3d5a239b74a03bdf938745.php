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
                <h3 class="role-list-title">Work Log</h3>
                <hr class="Dash-Line">
                <img src="<?php echo URL::to ('public/backend/assets/img/info-icon.png'); ?>" class="Info-Icon-Common"><small class="task-list-priority">WEEK</small>
                <select class="All task-list-filter" name="week" id="week">
                    <?php $__currentLoopData = $weeks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $week): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($week->id); ?>">Week <?php echo e($week->week_number); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <span class='right-inner-addon date datepicker'>
                    <i class="fa fa-calendar-o date-picker"></i>
                    <input name='start_date' value="<?php echo e(date('d M, Y', strtotime($weeks[0]->start_date))); ?>" type="text" class="date-picker task-list-filter" placeholder="Start Time" autocomplete="off" id="start_date" readonly/>
                </span>
                <span class='right-inner-addon date datepicker'>
                    <i class="fa fa-calendar-o date-picker"></i>
                    <input name='end_date' value="<?php echo e(date('d M, Y', strtotime($weeks[0]->end_date))); ?>" type="text" class="date-picker task-list-filter" placeholder="Due Time" autocomplete="off" id="end_date" readonly/>
                </span>
                <img src="<?php echo URL::to ('public/backend/assets/img/info-icon.png'); ?>" class="Info-Icon-Common"><small class="task-list-priority">TEAM</small>
                <select class="All task-list-filter" name="team" id="team">
                    <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($team->id); ?>"><?php echo e($team->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                
                <div class="row DataTableBox mt-2">
                    <div class="row role-list-table-subHeader mb-1">
                        <div class="col-sm-5 subHeader-col-1">
                            <input class="form-control user-list-search col-9" style="height: 10px;" type="search" placeholder="Search by user name ..." aria-label="Search" id="search_key">
                        </div>
                        <div class="col-sm-7 subHeader-col-2">  
                        <a href="<?php echo e(route('worklogs.export')); ?>">
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
                            <a class="btn-hover" href="<?php echo e(route('worklogs.create')); ?>">
                                <button class="role-list-New-role-btn">New WorkLog</button>
                            </a>   
                        </div>
                    </div>
                    <div class="table-responsive worklog-div" style="margin-bottom: 20px;">
                       <?php echo $__env->make('backend.work_logs.fetch_work_log', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                $('#week').change(function(){
                   formSubmit();
                });
                $('#team').change(function(){
                   formSubmit();
                }); 
                $('#search_key').keyup(function(){
                   formSubmit();
                });  

            });

            function formSubmit(){
                var weekId = $("#week").val();
                var teamId = $("#team").val();
                var search_key = $("#search_key").val();
                $.ajax({
                    type:'GET',
                    url:'/project_management_tool/worklog-search',
                    data:{weekId:weekId,teamId:teamId,search_key:search_key},
                    success:function(data){
                        $("#start_date").val(data.start_date);
                        $("#end_date").val(data.end_date);
                        $('.worklog-div').html(data.view);
                    }
                })
            }
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
                            url: 'worklogs/destroy',
                            method: 'GET',
                            data: {id:id},
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
<?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/work_logs/index.blade.php ENDPATH**/ ?>