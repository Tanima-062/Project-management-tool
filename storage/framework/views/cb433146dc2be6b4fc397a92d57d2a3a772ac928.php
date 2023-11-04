<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    <?php echo $__env->make('backend.layouts.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="stylesheet" href="<?php echo e(asset('backend/assets/templates/vendors/font-awesome/css/font-awesome.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('backend/assets/templates/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')); ?>" type="text/css" />
</head>
<body>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php echo $__env->make('backend.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="container-fluid page-body-wrapper">
        <?php echo $__env->make('backend.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="main-panel task-index-panel" >
            <div class="content-wrapper py-4">
                <div id="alert-show">
                    <?php echo $__env->make('backend.layouts.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <form id="searchForm" method="POST" action="<?php echo e(route('otherRequestTask.search')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="order" id="order" value="order">
                    <input type="hidden" name="coloumn" id="coloumn">
                    <div class="row">
                        <div class="col-md-12">
                            <span class='right-inner-addon date datepicker'>
                                <i class="fa fa-calendar-o date-picker"></i>
                                <input name='start_date' value="" type="text" class="date-picker task-list-filter" placeholder="Start Time" autocomplete="off" id="start_date"/>
                            </span>
                            <span class="error start_date-error" style="color:red;"></span>
                            <span class='right-inner-addon date datepicker'>
                                <i class="fa fa-calendar-o date-picker"></i>
                                <input name='end_date' value="" type="text" class="date-picker task-list-filter" placeholder="Due Time" autocomplete="off" id="end_date"/>
                            </span>
                            <span class="error end_date-error" style="color:red;"></span>
                            <img src="<?php echo URL::to('public/backend/assets/img/info-icon.png'); ?>" class="Info-Icon-Common"><small class="task-list-priority">PRIORITY</small>
                            <select class="All task-list-filter" name="priority" id="priority">
                                <option value="">Select Priority</option>
                                <option value="2">High</option>
                                <option value="1">Medium</option>
                                <option value="0">Low</option>
                            </select>
                            <img src="<?php echo URL::to ('public/backend/assets/img/info-icon.png'); ?>" class="Info-Icon-Common"><small class="task-list-status">STATUS</small>
                            <select class="All task-list-filter" name="status" id="status">
                                <option value="">Select Status</option>
                                <option value="-1">Pending</option>
                                <option value="1">Accepted</option>
                                <option value="0">Rejected</option>
                            </select>
                            <button type="reset" class="btn-custom-reset" id="reset">Clear</button>
                        </div>
                        <!-- <div class="col-md-8">

                        </div> -->
                    </div>

                </form>
                <div class="row DataTableBox" style="margin-top: 20px; padding-bottom:20px;" id="taskSearchBox">
                    <div>
                        <div class="form-inline row task-search-box m-0 py-3" style="border-bottom: 2px solid rgba(0, 0, 0, 0.1);">
                            <div class="col-6 px-0">
                                <span style="margin-right:10px;"><b>All Tasks List</b></span>
                                <input class="form-control user-list-search" style="height: 20px;" type="search" placeholder="Search..." aria-label="Search" id="search_key">
                            </div>
                            <div class="col-6 px-0" style="text-align: right;">
                                <a href="<?php echo e(route('otherRequestTasks.export')); ?>" class="task-export">
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
                                            <text id="Export" fill="#576271" font-family="OpenSans-Regular, Open Sans" font-size="14px" transform="translate(1776.711 1033)">
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
                    </div>
                    <div id="task_table" class="task-table">
                        <?php echo $__env->make('backend.other_request_tasks.task_table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->make('backend.layouts.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script src="<?php echo e(asset('backend/assets/templates/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')); ?>" type="text/javascript"></script>
<script>
var individuals = [];
    $(document).ready(function() {
        sortingColoumn();
        pagination();
    });

    function pagination(){
        $('.page-link').click(function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
             if (page) {
                $.ajax({
                    url: '/project_management_tool/other-request-task/search?page=' + page,
                    method:$('#searchForm').attr('method'),
                    data:$('#searchForm').serializeArray(),
                    success:function(data){
                        $('#task_table').html(data.view);
                        pagination();
                        sortingColoumn();
                    }
                })
             }
        });
    }
    $(".fa-calendar-o").on("click", function(){
        $(this).siblings("input").datepicker({
            forceParse:false,
            autoclose: true,
            immediateUpdates: true,
            todayBtn: true,
            todayHighlight: true
        });

        // $(this).siblings("input").datepicker('update', new Date());
        $(this).siblings("input").datepicker('show');
    });
    $('.date-picker').on("change",function(){
        // alert($(this).val());
        // const monthNames = ["January", "February", "March", "April", "May", "June",
        //                     "July", "August", "September", "October", "November", "December"];
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const d = $(this).val().split('/');
        const date = d[1] + " " +monthNames[Number(d[0]-1)] + ', '+ d[2]+ ' ';
        $(this).val(date);
    });
    
    $("#start_date").change(function (){
        $(".start_date-error").html("");
        formSubmit();
    });

    $("#end_date").change(function (){
        $(".start_date-error").html("");
        formSubmit();
    });

    $("#priority").change(function(){
        formSubmit();
    });

    $("#search_key").keyup(function(){
        formSubmit();
    });

    $("#status").change(function(){
        formSubmit();
    });

    $("#reset").on("click", function(ev){
        $("#search_key").val("");
        $("#end_date").val("");
        $("#start_date").val("");
        $("#status").val("");
        $("#priority").val("");
        $(".end_date-error").html("");
        $(".start_date-error").html("");
        formSubmit();
        setTimeout(function() {
            // executes after the form has been reset
           
        }, 1);
    });

    function formSubmit(){
        var formData = $('#searchForm').serializeArray();
        
        formData.push({name: "search_key", value: $("#search_key").val()});
        formData.push({name: "coloumn", value: $("#coloumn").val()});
        formData.push({name: "order", value: $("#order").val()});

        var flag = '';
       
        if(($('#start_date').val() != '') && ($('#end_date').val() != '')){
            var startDateTimeInMillis = Date.parse($('#start_date').val());
            var endDateTimeInMillis = Date.parse($('#end_date').val());
            if(startDateTimeInMillis > endDateTimeInMillis){
                $(".start_date-error").html("Start Date should smaller than end date");
                flag = 'start_date_not_equal';
            }
        }
        
        if(flag == ''){
            $.ajax({
                url: $('#searchForm').attr('action'),
                method:$('#searchForm').attr('method'),
                data:formData,
                success:function(data){
                    
                    $('#task_table').html(data.view);
                    if($("#order").val() == 'asc'){
                        var coloumn = $("#coloumn").val();
                        $('[coloumn= '+coloumn+']').attr('order','desc');
                    }
                
                    pagination();
                    sortingColoumn();

                }

            })
        }
    }
    function sortingColoumn(){
        $('.fas').click(function(){
            $("#order").val($(this).attr('order'));
            $("#coloumn").val($(this).attr('coloumn'));
            formSubmit();
        });
    }
</script>

</body>

</html>
<?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/other_request_tasks/list.blade.php ENDPATH**/ ?>