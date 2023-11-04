<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    @include('backend.layouts.styles')
    <link rel="stylesheet" href="{{asset('backend/assets/templates/vendors/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/templates/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" type="text/css" />
</head>
<body>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    @include('backend.layouts.header')
    <div class="container-fluid page-body-wrapper">
        @include('backend.layouts.sidebar')
        <div class="main-panel task-index-page" >
            <div class="content-wrapper py-4">
                @include('backend.layouts.messages')
                <form id="searchForm" method="POST" action="{{route('task.searchByTeam')}}">
                    @csrf
                    <input type="hidden" name="team_id" value="{{$team_id}}">
                    <div class="row">
                        <div class="col-md-12">
                            <img src="{{asset('backend/assets/img/info-icon.png')}}" class="Info-Icon-Common"><small class="assignee-list">ASSIGNEE:</small>
                            <span class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">All
                                <span class="caret"></span></button>
                                <div class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                     <div class="assignee-section">
                                        <p>Assignee</p><hr>
                                        <div>
                                            <input type="radio" id="all" name="assignee" class="checkbox" value="assign_all">
                                            <label for="all">All</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="assign_to_me" name="assignee" class="checkbox" value="assign_to_me">
                                            <label for="assign_to_me">Assign To Me</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="assign_by_me" name="assignee" class="checkbox" value="assign_by_me">
                                            <label for="assign_by_me">Assign By Me</label>
                                        </div>
                                     </div>
                                </div>
                            </span>
                            <span class='right-inner-addon date datepicker'>
                                <i class="fa fa-calendar-o date-picker"></i>
                                <input name='start_date' value="" type="text" class="date-picker task-list-filter" placeholder="Start Time" autocomplete="off" id="start_date"/>
                            </span>
                            <span class='right-inner-addon date datepicker'>
                                <i class="fa fa-calendar-o date-picker"></i>
                                <input name='end_date' value="" type="text" class="date-picker task-list-filter" placeholder="Due Time" autocomplete="off" id="end_date"/>
                            </span>
                            <img src="{{asset('backend/assets/img/info-icon.png')}}" class="Info-Icon-Common"><small class="task-list-priority">PRIORITY</small>
                            <select class="All task-list-filter" name="priority" id="priority">
                                <option value="">Select Priority</option>
                                <option value="2">High</option>
                                <option value="1">Medium</option>
                                <option value="0">Low</option>
                            </select>
        
                            <img src="{{asset('backend/assets/img/info-icon.png')}}" class="Info-Icon-Common"><small class="task-list-status">STATUS</small>
                            <select class="All task-list-filter" name="status" id="status">
                                <option value="">Select Status</option>
                                <option value="0">To Do</option>
                                <option value="1">In Progress</option>
                                <option value="2">Completed</option>
                            </select>
                            <button type="reset" class="btn-custom-reset" id="reset">Clear</button>
                            <span onClick="toList()"><img src="{{asset('backend/assets/img/view-icon.png')}}" class="View-Icon list-view" style="display:inline-block;margin-left: 5px;"><small class="list-view-text">LIST VIEW</small></span>
                            <!-- <span onClick="toKanban()"><img src="{{asset('backend/assets/img/view-icon-default.png')}}" class="View-Icon kanban-view"><small class="kanban-view-text">KANBAN VIEW</small></span> -->
                        </div>
                        <!-- <div class="col-md-8">
                            
         
                        </div> -->
                    </div>
                    
                </form>

                <!-- <a href="{{route('tasks.export')}}" class="task-export"><svg xmlns="http://www.w3.org/2000/svg" width="100" height="34.253" viewBox="0 0 116.299 34.253">
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
                </svg></a> -->

                <div class="row DataTableBox" style="margin-top: 20px; padding-bottom:20px;">
                    <div style="padding-left: 20px;">
                        <div class="form-inline row" style="border-bottom: 2px solid rgba(0, 0, 0, 0.1);">
                            <div class="col-6">
                            <span style="margin-right:10px;"><b>All Tasks List</b></span>
                            <input class="form-control user-list-search" style="height: 20px; margin-top:20px;margin-bottom: 20px;" type="search" placeholder="Search..." aria-label="Search" id="search_key">
                            </div>
                            <div class="col-6" style="text-align: right;">
                            <a href="{{route('tasksByTeam.export',$team_id)}}" class="task-export"><svg xmlns="http://www.w3.org/2000/svg" width="100" height="34.253" viewBox="0 0 116.299 34.253">
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
                            </svg></a>
                            </div>

                        </div>
                    </div>
                    <div id="task_table" class="task-table">
                    @include('backend.tasks.task_table')
                    </div>
                </div>
                <div id="task_kanban" class="task-table" style="display: none;">
                   @include('backend.tasks.task_card')
                </div>
            </div>
        </div>
    </div>
</div>

@include('backend.layouts.scripts')

<script src="{{asset('backend/assets/templates/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<script>
var individuals = [];
    $(document).ready(function() {
        pagination();
    });

    function pagination(){
        $('.page-link').click(function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
             if (page) {
                $.ajax({
                    url: '/task/searchByTeam?page=' + page,
                    method:$('#searchForm').attr('method'),
                    data:$('#searchForm').serializeArray(),
                    success:function(data){
                        $('.task-table').html(data.view);
                        pagination();
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

    $("#search_key").keyup(function(){
        formSubmit();
    });
    
    $("#start_date").change(function (){
       formSubmit();
    });

    $("#end_date").change(function (){
        formSubmit();
    });

    $("#priority").change(function (){
        formSubmit();
    });

    $("#status").change(function (){
        formSubmit();
    });

    $("#reset").on("click", function(ev){
        $("#search_key").val("");
        setTimeout(function() {
            // executes after the form has been reset
            formSubmit();
        }, 1);
    });

    $('.checkbox').click(function(){
        
        formSubmit();
    });

    function formSubmit(){
        var formData = $('#searchForm').serializeArray();
        formData.push({name: "search_key", value: $("#search_key").val()});
        $.ajax({
            url: $('#searchForm').attr('action'),
            method:$('#searchForm').attr('method'),
            data:formData,
            success:function(data){
                $('.task-table').html(data.view);
                pagination();
            }

        })
    }

    function toKanban(){
        document.getElementById('task_table').style.display="none";
        document.getElementById('task_kanban').style.display="flex";
    }
    function toList(){
        document.getElementById('task_table').style.display="flex";
        document.getElementById('task_kanban').style.display="none";
    }
</script>

</body>

</html>
