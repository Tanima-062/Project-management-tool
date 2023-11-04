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
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php echo $__env->make('backend.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="container-fluid page-body-wrapper">
        <?php echo $__env->make('backend.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="main-panel users-list-main-panel task-analytics-main-panel task-index-panel">
            <div class="content-wrapper" >
                <div id="alert-show">
                    <?php echo $__env->make('backend.layouts.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-5">
                                <h3 class="users-list-title">Overall Task Status</h3>
                                <hr class="Dash-Line">
                            </div>
                            <div class="col-md-7 task_Status_date_filter">
                                <span class="btn-hover"><button class="Rectangle-btn form-submit" type="button" style="float: right; margin-top:5px;"><i class="fas fa-search" style="margin-right:5px;"></i>Search</button></span>
                                <button type="reset" class="btn-custom-reset" id="reset" style="float:right; margin-top:10px;">Clear</button>
                                
                                <span class='right-inner-addon date datepicker end-date-box' style="float:right;">
                                    <i class="fa fa-calendar-o"></i>
                                    <input name='end_date' value="<?php echo e(date('d M, Y',strtotime($end_date))); ?>" type="text" data-date="" data-date-format="d M, yyyy" class="date-picker task-list-filter" placeholder="To" autocomplete="off" id="end_date"/>
                                    <p class="error end_date-error" style="color:red;"></p>
                                </span>
                                
                                <span class='right-inner-addon date datepicker start-date-box' style="float:right;">
                                    <i class="fa fa-calendar-o"></i>
                                    <input name='start_date' value="<?php echo e(date('d M, Y',strtotime($start_date))); ?>" type="text" data-date="" data-date-format="d M, yyyy" class="date-picker task-list-filter" placeholder="From" autocomplete="off" id="start_date"/>
                                    <p class="error start_date-error" style="color:red;"></p>
                                </span>
                                
                            </div>
                        </div>
                        
                        
                        <div class="card analytics-task-status-crd top-section">
                            <?php echo $__env->make('backend.task_analytics.status_card', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                            
                    </div>

                    <div class="col-md-12 my-5">
                        <h3 class="users-list-title">Direct Supervisee Status</h3>
                        <hr class="Dash-Line">
                        <div style="margin-bottom:20px;">
                            <img src="<?php echo URL::to('public/backend/assets/img/info-icon.png'); ?>" class="Info-Icon-Common"><small class="task-list-priority">PRIORITY</small>
                            <select class="All task-list-filter" name="priority" id="priority">
                                <option value="">Select Priority</option>
                                <option value="2">High</option>
                                <option value="1">Medium</option>
                                <option value="0">Low</option>
                            </select>
                        </div>
                        <div class="card direct-supervise-status">
                            <div class="row DataTableBox">
                                <div class="row role-list-table-subHeader pr-0">
                                    <div class="col-sm-5 subHeader-col-1">
                                        <div class="form-inline">
                                            <span>
                                                <b>Individual Task Details</b>
                                            </span>
                                            <input class="form-control supervise-status-search mr-sm-2 " type="search" id="user_search" placeholder="Search..." aria-label="Search">
                                        </div>
                                    </div>
                                    <input type="hidden" name="user-order" id="user_order" value="order">
                                    <input type="hidden" name="user-coloumn" id="user_coloumn">
                                    <div class="col-sm-7 subHeader-col-2 pr-0">
                                        <a href="<?php echo e(route('users.under.me.export')); ?>">
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
                                <div class="user-table">
                                    <?php echo $__env->make('backend.task_analytics.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </div>
                        </div>
                            
                    </div>
                    <div class="col-md-12 program-details text-center">
                        <div class="form-group m-0">
                            <h3>Department Wise Details</h3>
                            <select class="form-control select2" style="width: 15%;" id="program">
                               <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>{
                                    <?php if($key==0): ?>
                                        <option selected="selected" value="<?php echo e($program->id); ?>" class="selected_program"><?php echo e($program->name); ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo e($program->id); ?>"><?php echo e($program->name); ?></option>
                                    <?php endif; ?>
                               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 program-wise-task-details">
                        <div class="Overdue-task-status-graph">
                            <div class="row show-chart">
                                <?php echo $__env->make('backend.task_analytics.chart', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                        </div>
                        <div class="mt-5 supervise-status">
                            <?php echo $__env->make('backend.task_analytics.supervise_status', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    </div>
                </div>
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
        $("#priority").val('');
        $('#user_search').val('');
        $('#member_search').val('');
        chartReady();
        memberPagination();
        userPagination();
        memberSearch();
        sortingUserColoumn();
        sortingMemberColoumn();
        searchMemberByQuery();
    });

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

    $(".date-picker").on("click", function(){
        $(this).datepicker({
            forceParse:false,
            autoclose: true,
            immediateUpdates: true,
            todayBtn: true,
            todayHighlight: true
        });

        // $(this).siblings("input").datepicker('update', new Date());
        $(this).datepicker('show');
    });


    function memberPagination(){
        $('#pageMember .page-link').click(function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var program_id = $("#program").val();
            var query = $("#member_search").val();
            var order =  $("#member_order").val();
            var coloumn = $("#member_coloumn").val();
             if (page) {
                $.ajax({
                    url: '/project_management_tool/fetch-member?page=' + page,
                    method:'GET',
                    data:{query:query,program_id:program_id, order:order, coloumn:coloumn},
                    success:function(data){
                        $('.member-table').html(data.view);
                        if($("#member_order").val() == 'asc'){
                            var coloumnMember = $("#member_coloumn").val();
                            $('[coloumnMember= '+coloumnMember+']').attr('orderMember','desc');
                        }
                        if($("#member_order").val() == 'desc'){
                            var coloumnUser = $("#member_coloumn").val();
                            $('[coloumnMember= '+coloumnMember+']').attr('orderMember','asc');
                        }
                        sortingMemberColoumn();
                        memberPagination();
                    }
                })
             }
        });
    }

    function userPagination(){
        $('#pageUser .page-link').click(function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var priority = $("#priority").val();
            var query = $("#user_search").val();
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();
            var order =  $("#user_order").val();
            var coloumn = $("#user_coloumn").val();
             if (page) {
                $.ajax({
                    url: '/fetch-user-under-me?page=' + page,
                    method:'GET',
                    data:{priority:priority, query:query, start_date:start_date, end_date:end_date, order:order, coloumn:coloumn},
                    success:function(data){
                        $('.user-table').html(data.view);
                        if($("#user_order").val() == 'asc'){
                            var coloumnUser = $("#user_coloumn").val();
                            $('[coloumnUser= '+coloumnUser+']').attr('orderUser','desc');
                        }
                        if($("#user_order").val() == 'desc'){
                            var coloumnUser = $("#user_coloumn").val();
                            $('[coloumnUser= '+coloumnUser+']').attr('orderUser','asc');
                        }
                        sortingUserColoumn();
                        userPagination();
                    }
                })
             }
        });
    }

    
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    })
    var overdueNames = [<?php echo '"'.implode('","', $overdueNames).'"' ?>];
    var statusNames = [<?php echo '"'.implode('","', $statusNames).'"' ?>];
    var overdues = [<?php echo '"'.implode('","', $overdueCount).'"' ?>];
    var accepted = [<?php echo '"'.implode('","', $acceptedCount).'"' ?>];
    var inProgress = [<?php echo '"'.implode('","', $inProgressCount).'"' ?>];
    var completed = [<?php echo '"'.implode('","', $completedCount).'"' ?>];

function chartReady(){
    // ////////// 1st chart
    var ctx = document.getElementById("myChart01").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: overdueNames,
            datasets: [{
                label: 'Overdue',
                backgroundColor: "#ff0404",
                data: overdues,
            }],
        },
        options: {
            tooltips: {
                displayColors: true,
                callbacks:{
                    mode: 'x',
                },
            },
            scales: {
                xAxes: [{
                    stacked: true,
                    barPercentage: 0.2,
                    gridLines: {
                    display: false,
                    }
                }],
                yAxes: [{
                    stacked: true,
                    ticks: {
                    beginAtZero: true,
                    },
                    type: 'linear',
                }]
            },
                responsive: true,
                maintainAspectRatio: false,
                legend: { position: 'top' },
        }
    });

    
    // /////////// 2nd chart 
    var ctx = document.getElementById("myChart02").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: statusNames,
            datasets: [{
                label: 'Accepted',
                backgroundColor: "#ffab2b",
                data: accepted,
            }, {
                label: 'In Progress',
                backgroundColor: "#188ef9",
                data: inProgress,
            }, {
                label: 'Completed',
                backgroundColor: "#56b91a",
                data: completed,
            }],
        },
        options: {
            tooltips: {
                displayColors: true,
                callbacks:{
                    mode: 'x',
                },
            },
            scales: {
                xAxes: [{
                    stacked: true,
                    barPercentage: 0.5,
                    gridLines: {
                    display: false,
                    }
                }],
                yAxes: [{
                    stacked: true,
                    ticks: {
                    beginAtZero: true,
                    },
                    type: 'linear',
                }]
            },
            responsive: true,
            maintainAspectRatio: false,
            legend: { position: 'top' },
        }
    });
}

$("#user_search").keyup(function(){
    $("#user_order").val('order');
    $("#user_coloumn").val('');
    userSearch();
});

$("#priority").change(function(){
    $("#user_order").val('order');
    $("#user_coloumn").val('');
    userSearch();  
});

$("#start_date").change(function (){
    $("#user_order").val('order');
    $("#user_coloumn").val('');
    $("#member_order").val("");
    $("#member_coloumn").val("");
    $(".start_date-error").html("");
});

$("#end_date").change(function (){
    $("#user_order").val('order');
    $("#user_coloumn").val('');
    $("#member_order").val("");
    $("#member_coloumn").val("");
    $(".end_date-error").html("");
});

$('.form-submit').click(function(){
    var flag = '';
    if($('#start_date').val() != ''){
        if($('#end_date').val() == ''){
            $(".end_date-error").html("To field is required");
            flag = 'end_date';
        }
        if(($('#start_date').val() != '') && ($('#end_date').val() != '')){
            var startDateTimeInMillis = Date.parse($('#start_date').val());
            var endDateTimeInMillis = Date.parse($('#end_date').val());
            if(startDateTimeInMillis > endDateTimeInMillis){
                $(".start_date-error").html("From should smaller than To");
                flag = 'start_date_not_equal';
            }
        }
    }
    else if($('#end_date').val() != ''){
        if($('#start_date').val() == ''){
            $(".start_date-error").html("From field is required");
            flag = 'start_date';
        }
    }
    if(flag == ''){
        fetchAllData();
    }
});

function fetchAllData(){
    var priority = $("#priority").val();
    var query = $("#user_search").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var member_query = $("#member_search").val();
    var program_id = $("#program").val();
    $.ajax({
        url: '/project_management_tool/fetch-all-data',
        method:'GET',
        data:{priority:priority, query:query,start_date:start_date, end_date:end_date, program_id:program_id, member_query:member_query},
        success:function(data){
            $('.analytics-task-status-crd').html(data.viewS);
            $('.user-table').html(data.viewU);
            $('.supervise-status').html(data.viewM);
            $('.show-chart').html(data.view);
            overdueNames = data[0].overdueNames;
            statusNames = data[0].statusNames;
            overdues = data[0].overdueCount;
            accepted = data[0].acceptedCount;
            inProgress = data[0].inProgressCount;
            completed = data[0].completedCount;
            chartReady();
            sortingUserColoumn();
            userPagination();
            sortingMemberColoumn();
            searchMemberByQuery();
            memberPagination();
        }
    })
}

$("#reset").on("click", function(ev){
    $("#user_order").val('order');
    $("#user_coloumn").val('');
    $("#user_search").val("");
    $("#priority").val("");
    var start_date = ' <?php echo date('d M, Y', strtotime($start_date)); ?> ';
    var end_date = ' <?php echo date('d M, Y', strtotime($end_date)); ?> ';
    $("#start_date").val(start_date);
    $("#end_date").val(end_date);
    $(".end_date-error").html("");
    $(".start_date-error").html("");
    fetchAllData();
});

function sortingUserColoumn(){
    $('.user-coloumn').click(function(){
        $("#user_order").val($(this).attr('orderUser'));
        $("#user_coloumn").val($(this).attr('coloumnUser'));
        userSearch();
    });
}

function sortingMemberColoumn(){
    $('.member-coloumn').click(function(){
        $("#member_order").val($(this).attr('orderMember'));
        $("#member_coloumn").val($(this).attr('coloumnMember'));
        memberSearch();
    });
}

function userSearch(){
    var priority = $("#priority").val();
    var query = $("#user_search").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var order =  $("#user_order").val();
    var coloumn = $("#user_coloumn").val();
    $.ajax({
        url: '/project_management_tool/fetch-user-under-me',
        method:'GET',
        data:{priority:priority, query:query, start_date:start_date, end_date:end_date, order:order, coloumn:coloumn},
        success:function(data){
            $('.user-table').html(data.view);
            if($("#user_order").val() == 'asc'){
                var coloumnUser = $("#user_coloumn").val();
                $('[coloumnUser= '+coloumnUser+']').attr('orderUser','desc');
            }
            sortingUserColoumn();
            userPagination();
        }
    })
}

function searchMemberByQuery(){
    $("#member_search").keyup(function(){
        var order =  $("#member_order").val("");
        var coloumn = $("#member_coloumn").val("");
        memberSearch();
    });
}

function memberSearch(){
    var member_query = $("#member_search").val();
    var program_id = $("#program").val();
    var order =  $("#member_order").val();
    var coloumn = $("#member_coloumn").val();
    $.ajax({
        url: '/project_management_tool/fetch-member',
        method:'GET',
        data:{member_query:member_query, program_id:program_id, order:order, coloumn:coloumn},
        success:function(data){
            $('.member-table').html(data.view);
            if($("#member_order").val() == 'asc'){
                var coloumnMember = $("#member_coloumn").val();
                $('[coloumnMember= '+coloumnMember+']').attr('orderMember','desc');
            }
            if($("#member_order").val() == 'desc'){
                var coloumnUser = $("#member_coloumn").val();
                $('[coloumnMember= '+coloumnMember+']').attr('orderMember','asc');
            }
            sortingMemberColoumn();
            memberPagination();
        }
    })

}

$("#program").change(function(){
    var program_id = $(this).val();
    $.ajax({
        url: '/project_management_tool/fetch-data',
        method: 'GET',
        data: {program_id:program_id},
        success: function (data) {
            $('.supervise-status').html(data.viewM);
            $('.show-chart').html(data.view);
            overdueNames = data[0].overdueNames;
            statusNames = data[0].statusNames;
            overdues = data[0].overdueCount;
            accepted = data[0].acceptedCount;
            inProgress = data[0].inProgressCount;
            completed = data[0].completedCount;
            chartReady();
            sortingMemberColoumn();
            searchMemberByQuery();
            memberPagination();
        }
    });
});
// //////////
</script>

</body>

</html>
<?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/task_analytics/index.blade.php ENDPATH**/ ?>