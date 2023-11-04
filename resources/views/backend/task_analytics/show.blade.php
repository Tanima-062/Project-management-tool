<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    @include('backend.layouts.styles')
</head>
<body>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    @include('backend.layouts.header')
    <div class="container-fluid page-body-wrapper">
        @include('backend.layouts.sidebar')
        <div class="main-panel users-list-main-panel task-analytics-main-panel">
            <div class="content-wrapper individual-all-task-details-wrapper" >
                @include('backend.layouts.messages')
                <div class="row">
                    <div class="col-md-12 all-task-details-card">
                        <div class="row"> 
                            <div class="col-md-4">
                                <h3 class="users-list-title">Overall Task Status</h3>
                                <hr class="Dash-Line">
                            </div>
                            <div class="col-md-8 date-picker-for-filter text-right task-index-page">
                                {{-- <span class="task-index-page"> --}}
                                <span class="btn-hover"><button class="Rectangle-btn form-submit" type="button" style="float: right;"><i class="fas fa-search" style="margin-right:5px;"></i>Search</button></span>
                                <button type="reset" class="btn-custom-reset" id="reset" style="float:right; margin-top:5px;">Clear</button>
                                
                                <span class='right-inner-addon date datepicker text-left to-date' style="float:right;">
                                    <i class="fa fa-calendar-o date-picker"></i>
                                    <input name='end_date' value="" type="text" class="date-picker task-list-filter" placeholder="To" autocomplete="off" id="end_date"/>
                                    <p class="error end_date-error" style="color:red;"></p>
                                </span> 
                                <span class='right-inner-addon date datepicker text-left from-date' style="float:right;">
                                    <i class="fa fa-calendar-o date-picker"></i>
                                    <input name='start_date' value="" type="text" class="date-picker task-list-filter" placeholder="From" autocomplete="off" id="start_date"/>
                                    <p class="error start_date-error" style="color:red;"></p>
                                </span>
                                  
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4 text-right mb-2 pl-0">
                                <span class="error start_date-error" style="color:red;"></span>
                                <span class="error end_date-error" style="color:red;padding-right: 290px; "></span>
                            </div>
                        </div> --}}
                        {{-- task status card --}}
                        <div class="card analytics-task-status-crd">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="overall-task-img">
                                        <img src="{!! URL::to('public/backend/assets/img/stl_report.png')!!}" class="">
                                    </div>
                                </div>
                                <div class="col-md-2 pl-0">
                                    <div class="pt-2 task-assigned-person-name">
                                        <b>{{$user->name}}</b>
                                        @php $team = \App\Modals\User::getTeamName($user->id); @endphp
                                        <h6 class="mt-1"> {{ $team }}</h6>
                                    </div>
                                </div>
                                @php $task_status = \App\Modals\User::taskCountByStatus($user->id); @endphp
                                <div class="col-md-8 overall-task-quantity p-sm-0">
                                  <div class="row">
                                      <div class="col-md-7">
                                          <div class="row">
                                              <div class="col">
                                                <h4 class="task-quantity-title">Tasks</h4>
                                                <h3 class="task-quantity-number Tasks">{{$task_status['total']}}</h3>
                                              </div>
                                              <div class="col pl-sm-0">
                                                <h4 class="task-quantity-title">Pending</h4>
                                                <h3 class="task-quantity-number Pending">{{$task_status['pending']}}</h3>
                                              </div>
                                              <div class="col pl-sm-0">
                                                <h4 class="task-quantity-title">Accepted</h4>
                                                <h3 class="task-quantity-number Accepted">{{$task_status['accepted']}}</h3>
                                              </div>
                                              <div class="col pl-sm-0">
                                                <h4 class="task-quantity-title">In Progress</h4>
                                                <h3 class="task-quantity-number In-Progress">{{$task_status['inProgress']}}</h3>
                                            </div>
                                          </div>
                                      </div>
                                      <div class="col-md-5">
                                        <div class="row">
                                            
                                            <div class="col pl-sm-0">
                                                <h4 class="task-quantity-title">Completed</h4>
                                                <h3 class="task-quantity-number Completed">{{$task_status['completed']}}</h3>
                                            </div>
                                            <div class="col pl-sm-0">
                                                <h4 class="task-quantity-title">Rejected</h4>
                                                <h3 class="task-quantity-number Rejected">{{$task_status['rejected']}}</h3>
                                            </div>
                                            <div class="col pl-sm-0">
                                                <h4 class="task-quantity-title">Overdue</h4>
                                                <h3 class="task-quantity-number overdue">{{$task_status['overdue']}}</h3>
                                            </div>
                                        </div>
                                      </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        {{-- task status card end--}} 
                    </div>     
                    <div class="col-md-12 individual-all-task-details my-5">
                        <h3 class="users-list-title">All Task Details</h3>
                        <hr class="Dash-Line">
                        <form id="searchForm" method="POST" action="{{route('taskAnalytics.fetchTasksOfUser')}}">
                            @csrf
                            <input type="hidden" name="order" id="order" value="order">
                            <input type="hidden" name="coloumn" id="coloumn">
                            <div class="row mb-3">
                                <div class="col-md-9 col-sm-8">
                                    <img src="{!!URL::to('public/backend/assets/img/info-icon.png') !!}" class="Info-Icon-Common assignee-section-dropdown"><small class="assignee-list assignee-section-dropdown">ASSIGNEE:</small>
                                    <span class="dropdown assignee-section-dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Assign To {{$user_name}}
                                        <span class="caret"></span></button>
                                        <div class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                            <div class="assignee-section">
                                                <p>Assignee</p><hr>
                                                <div>
                                                    <input type="radio" id="all" name="assignee" class="checkbox" value="assign_all">
                                                    <label for="all">All</label>
                                                </div>
                                                <div>
                                                    <input type="radio" id="assign_to_me" name="assignee" class="checkbox" value="assign_to_me" checked>
                                                    <label for="assign_to_me">Assign To {{$user_name}}</label>
                                                </div>
                                                <div>
                                                    <input type="radio" id="assign_by_me" name="assignee" class="checkbox" value="assign_by_me">
                                                    <label for="assign_by_me">Assign By {{$user_name}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                    <img src="{!! URL::to('public/backend/assets/img/info-icon.png') !!}" class="Info-Icon-Common"><small class="task-list-priority">PRIORITY</small>
                                    <select class="All task-list-filter" name="priority" id="priority">
                                        <option value="">Select Priority</option>
                                        <option value="2">High</option>
                                        <option value="1">Medium</option>
                                        <option value="0">Low</option>
                                    </select>

                                    <img src="{!! URL::to('public/backend/assets/img/info-icon.png')!!}" class="Info-Icon-Common"><small class="task-list-status">STATUS</small>
                                    <select class="All task-list-filter" name="status" id="status">
                                        <option value="">All</option>
                                        <option value="-1">Pending</option>
                                        <option value="0">Accepted</option>
                                        <option value="1">In Progress</option>
                                        <option value="2">Completed</option>
                                        <option value="3" selected>Overdue</option>
                                        <option value="4">Rejected</option>
                                        <option value="5">WFH</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                        <div class="card direct-supervise-status">
                            <div class="row DataTableBox">
                                <div class="row role-list-table-subHeader pr-0">
                                    <div class="col-sm-5 subHeader-col-1">
                                        <div class="form-inline">
                                            <span>
                                                <b>Individual Task Details</b>
                                            </span>
                                            <input class="form-control supervise-status-search mr-sm-2 " type="search" id="search_key" placeholder="Search..." aria-label="Search">
                                        </div>
                                    </div>
                                    <div class="col-sm-7 subHeader-col-2 pr-0">
                                        <a href="{{route('single.user.export',$user->id)}}">
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
                                <div class="single-user-task-table">
                                    @include('backend.task_analytics.task_table')
                                </div>
                            </div>
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

@include('backend.layouts.scripts')
<script>
    $(document).ready(function() {
        $("#assign_to_me").prop('checked',true);
        $("#status").val('3');
        pagination();
        sortingColoumn();
    });

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
    
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    })

    $("#search_key").keyup(function(){
        $("#order").val('order');
        $("#coloumn").val('');
        formSubmit();
    });
    
    $("#start_date").change(function (){
        $("#order").val('order');
        $("#coloumn").val('');
        $(".start_date-error").html("");
    });

    $("#end_date").change(function (){
        $("#order").val('order');
        $("#coloumn").val('');
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
            formSubmit();
        }
    });

    $("#priority").change(function (){
        $("#order").val('order');
        $("#coloumn").val('');
        formSubmit();
    });

    $("#status").change(function (){
        $("#order").val('order');
        $("#coloumn").val('');
        formSubmit();
    });

    $("#reset").on("click", function(){
        $("#order").val('order');
        $("#coloumn").val('');
        $("#start_date").val("");
        $("#end_date").val("");
        $("#reset").addClass("clicked");
        $(".end_date-error").html("");
        $(".start_date-error").html("");
        formSubmit();
    });

    $('.checkbox').click(function(){
        $("#order").val('order');
        $("#coloumn").val('');

        var value = $(this).val();
        if(value == 'assign_to_me'){
            document.getElementById("menu1").innerHTML="Assign To "+ "<?php echo $user_name; ?>";
        }
        if(value == 'assign_by_me'){
            document.getElementById("menu1").innerHTML="Assign By "+ "<?php echo $user_name; ?>";
        }
        if(value == 'assign_all'){
            document.getElementById("menu1").innerHTML="All";
        }
        
        formSubmit();
    });

    function sortingColoumn(){
        $('.fas').click(function(){
            $("#order").val($(this).attr('order'));
            $("#coloumn").val($(this).attr('coloumn'));
            formSubmit();
        });
    }

    function formSubmit(){
        var formData = $('#searchForm').serializeArray();
        formData.push({name: "search_key", value: $("#search_key").val()});
        formData.push({name:"start_date", value: $("#start_date").val()});
        formData.push({name:"end_date", value: $("#end_date").val()});
        $.ajax({
            url: $('#searchForm').attr('action'),
            method:$('#searchForm').attr('method'),
            data:formData,
            success:function(data){
                $('.single-user-task-table').html(data.view);
                if($("#start_date").val() != "" || $("#end_date").val() != "" || $("#reset").hasClass("clicked")){
                    $('.Tasks').html(data.task_status['total']);
                    $('.Pending').html(data.task_status['pending']);
                    $('.Accepted').html(data.task_status['accepted']);
                    $('.In-Progress').html(data.task_status['inProgress']);
                    $('.Completed').html(data.task_status['completed']);
                    $('.Rejected').html(data.task_status['rejected']);
                    $('.overdue').html(data.task_status['overdue']);
                    $("#reset").removeClass('clicked');
                }
                if($("#order").val() == 'asc'){
                    var coloumn = $("#coloumn").val();
                    $('[coloumn= '+coloumn+']').attr('order','desc');
                }
                console.log(data);
                sortingColoumn();
                pagination();
            }
        })
    }

    function pagination(){
        $('.page-link').click(function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];

            var formData = $('#searchForm').serializeArray();
            formData.push({name: "search_key", value: $("#search_key").val()});
            formData.push({name: "page", value: page});

            $.ajax({
                url: '/project_management_tool/fetch-tasks-of-user?page=' + page,
                method:$('#searchForm').attr('method'),
                data:formData,
                success:function(data){
                    $('.single-user-task-table').html(data.view);
                    sortingColoumn();
                    pagination();
                }
            })
             
        });
    }
 
</script>

</body>

</html>
