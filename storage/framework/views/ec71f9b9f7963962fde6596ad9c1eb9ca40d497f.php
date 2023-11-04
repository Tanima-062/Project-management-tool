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
<?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/work_logs/index.blade.php ENDPATH**/ ?>