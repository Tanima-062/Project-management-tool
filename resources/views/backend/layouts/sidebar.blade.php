<!-- partial:partials/_sidebar.html -->

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="row nav position-fixed">
        @if(Route::is('dashboard'))
            <li class="nav-item nav-active">
                <a class="nav-link" href="{{route('dashboard')}}">
                    <img src="{!!URL::to('public/backend/assets/img/stl_dashboard-icon.png')!!}" class="sidebar-icon">
                    <span class="menu-title"><b>DASHBOARD</b></span>
                </a>
            </li>
        @else
        <li class="nav-item">
            <a class="nav-link" href="{{route('dashboard')}}">
                <img src="{!!URL::to('public/backend/assets/img/dashboard-icon-default.png')!!}" class="sidebar-icon menu-non-active">
                <img src="{!!URL::to('public/backend/assets/img/stl_dashboard-icon.png')!!}" class="sidebar-icon menu-active">
                <span class="menu-title">DASHBOARD</span>
            </a>
        </li>
        @endif
        @if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'task.analytics.show'))
            @if(Route::is('task-analytics.show') ||  Route::is('task-analytics.index') || Route::is('task_analytics.task.show'))
                <li class="nav-item nav-active">
                    <a class="nav-link active" href="{{route('task-analytics.index')}}">
                        <img src="{!!URL::to('public/backend/assets/img/stl_task-analytics-active.png')!!}" class="sidebar-icon">
                        <span class="menu-title"><b>TASK ANALYTICS</b></span>
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{route('task-analytics.index')}}">
                        <img src="{!!URL::to('public/backend/assets/img/task-analytics.png')!!}" class="sidebar-icon menu-non-active">
                        <img src="{!!URL::to('public/backend/assets/img/stl_task-analytics-active.png')!!}" class="sidebar-icon menu-active">
                        <span class="menu-title">TASK ANALYTICS</span>
                    </a>
                </li>
            @endif
        @endif   
        @if(Route::is('tasks.create') || Route::is('tasks.show') || Route::is('tasks.edit') || Route::is('tasks.index') ||
               Route::is('subtasks.create') || Route::is('taskByTeam') )
            <li class="nav-item nav-active">
                <a class="nav-link" href="{{route('tasks.index')}}">
                    <img src="{!!URL::to('public/backend/assets/img/stl_task-icon-active.png')!!}" class="sidebar-icon">
                    <span class="menu-title"><b>TASKS</b></span>
                </a>
            </li>
        @else
        <li class="nav-item">
            <a class="nav-link" href="{{route('tasks.index')}}">
                <img src="{!!URL::to('public/backend/assets/img/task-icon.png')!!}" class="sidebar-icon menu-non-active">
                <img src="{!!URL::to('public/backend/assets/img/stl_task-icon-active.png')!!}" class="sidebar-icon menu-active">
                <span class="menu-title">TASKS</span>
            </a>
        </li>
        @endif
        @if(Route::is('otherRequestTasks.show') || Route::is('otherRequestTasks.updateRequest') || Route::is('otherRequestTasks.index') )
            <li class="nav-item nav-active">
                <a class="nav-link" href="{{route('otherRequestTasks.index')}}">
                    <img src="{!! URL::to('public/backend/assets/img/icon_Task_Approvals.png')!!}" class="sidebar-icon">
                    <span class="menu-title"><b>TASK APPROVALS</b></span>
                </a>
            </li>
        @else
        <li class="nav-item">
            <a class="nav-link" href="{{route('otherRequestTasks.index')}}">
                <img src="{!! URL::to('public/backend/assets/img/icon_Task_Approvals.png')!!}" class="sidebar-icon menu-non-active">
                <img src="{!!URL::to('public/backend/assets/img/icon_Task_Approvals.png')!!}" class="sidebar-icon menu-active">
                <span class="menu-title">TASK APPROVALS</span>
            </a>
        </li>
        @endif
        @if(Route::is('myRequestTasks.create') || Route::is('myRequestTasks.show') || Route::is('myRequestTasks.edit') || Route::is('myRequestTasks.index') )
            <li class="nav-item nav-active">
                <a class="nav-link" href="{{route('myRequestTasks.index')}}">
                    <img src="{!! URL::to('public/backend/assets/img/icon_PROPOSED_TASKS.png')!!}" class="sidebar-icon">
                    <span class="menu-title"><b>PROPOSED TASKS</b></span>
                </a>
            </li>
        @else
        <li class="nav-item">
            <a class="nav-link" href="{{route('myRequestTasks.index')}}">
                <img src="{!! URL::to('public/backend/assets/img/icon_PROPOSED_TASKS.png')!!}" class="sidebar-icon menu-non-active">
                <img src="{!! URL::to('public/backend/assets/img/icon_PROPOSED_TASKS.png')!!}" class="sidebar-icon menu-active">
                <span class="menu-title">PROPOSED TASKS</span>
            </a>
        </li>
        @endif
        @if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'tag.view'))
            @if(Route::is('tag.create') || Route::is('tag.show') || Route::is('tag.edit') || Route::is('tag.index'))
                <li class="nav-item nav-active">
                        <a class="nav-link" href="{{route('tags.index')}}">
                            <i class="fas fa-tags sidebar-icon"></i>
                            <span class="menu-title"><b>TAGS</b></span>
                        </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{route('tags.index')}}">
                        <i class="fas fa-tags sidebar-icon menu-non-active"></i>
                        <i class="fas fa-tags sidebar-icon menu-active" style="color:#009877"></i>
                        <span class="menu-title">TAGS</span>
                    </a>
                </li>
            @endif
        @endif
        @if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'team.view'))
            @if(Route::is('team.create') || Route::is('team.show') || Route::is('team.edit') || Route::is('team.index'))
                <li class="nav-item nav-active">
                    <a class="nav-link" href="{{route('teams.index')}}">
                        <i class="fa fa-users sidebar-icon"></i>
                        <span class="menu-title"><b>TEAMS</b></span>
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{route('teams.index')}}">
                        <i class="fas fa-users sidebar-icon menu-non-active"></i>
                        <i class="fas fa-users sidebar-icon menu-active" style="color:#009877"></i>
                        <span class="menu-title">TEAMS</span>
                    </a>
                </li>
            @endif
        @endif
        @if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'department.view'))
            @if(Route::is('department.create') || Route::is('department.show') || Route::is('department.edit') || Route::is('department.index'))
                <li class="nav-item nav-active">
                    <a class="nav-link" href="{{route('departments.index')}}">
                        <i class="fa fa-industry sidebar-icon"></i>
                        <span class="menu-title"><b>DEPARTMENTS</b></span>
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{route('departments.index')}}">
                        <i class="fas fa-industry sidebar-icon menu-non-active"></i>
                        <i class="fas fa-industry sidebar-icon menu-active" style="color:#009877"></i>
                        <span class="menu-title">DEPARTMENTS</span>
                    </a>
                </li>
            @endif
        @endif
        @if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'company.view'))
            @if(Route::is('companys.create') || Route::is('companys.show') || Route::is('companys.edit') || Route::is('companys.index'))
                <li class="nav-item nav-active">
                    <a class="nav-link" href="{{route('companys.index')}}">
                        <i class="fa fa-building sidebar-icon"></i>
                        <span class="menu-title"><b>COMPANY</b></span>
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{route('companys.index')}}">
                        <i class="fas fa-building sidebar-icon menu-non-active"></i>
                        <i class="fas fa-building sidebar-icon menu-active" style="color:#009877"></i>
                        <span class="menu-title">COMPANY</span>
                    </a>
                </li>
            @endif
        @endif
        @if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'user.view'))
            @if(Route::is('users.create') || Route::is('users.show') || Route::is('users.edit') || Route::is('users.index') ||
                Route::is('users.changePassword') || Route::is('users.show.bulkUpload'))
                <li class="nav-item nav-active">
                        <a class="nav-link" href="{{route('users.index')}}">
                            <img src="{!!URL::to('public/backend/assets/img/stl_user-icon-active.png')!!}" class="sidebar-icon">
                            <span class="menu-title"><b>USER MANAGEMENT</b></span>
                        </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{route('users.index')}}">
                        <img src="{!!URL::to('public/backend/assets/img/user-management-icon.png')!!}" class="sidebar-icon menu-non-active">
                        <img src="{!!URL::to('public/backend/assets/img/stl_user-icon-active.png')!!}" class="sidebar-icon menu-active">
                        <span class="menu-title">USER MANAGEMENT</span>
                    </a>
                </li>
            @endif
        @endif
        @if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'role.view'))
            @if(Route::is('roles.create') || Route::is('roles.edit') || Route::is('roles.index'))
                <li class="nav-item nav-active">
                    <a class="nav-link active" href="{{route('roles.index')}}">
                        <img src="{!!URL::to('public/backend/assets/img/stl_role-icon-active.png')!!}" class="sidebar-icon">

                        <span class="menu-title"><b>ROLE MANAGEMENT</b></span>
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{route('roles.index')}}">
                        <img src="{!!URL::to('public/backend/assets/img/role-management-icon.png')!!}" class="sidebar-icon menu-non-active">
                        <img src="{!!URL::to('public/backend/assets/img/stl_role-icon-active.png')!!}" class="sidebar-icon menu-active">
                        <span class="menu-title">ROLE MANAGEMENT</span>
                    </a>
                </li>
            @endif
        @endif
        @if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'report.show'))
            @if(Route::is('report.index'))
                <li class="nav-item nav-active">
                    <a class="nav-link active" href="{{route('report.index')}}">
                        <img src="{!!URL::to('public/backend/assets/img/stl_icon_Report_active.png')!!}" class="sidebar-icon">
                        <span class="menu-title"><b>REPORT</b></span>
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{route('report.index')}}">
                        <img src="{!!URL::to('public/backend/assets/img/icon_Report.png')!!}" class="sidebar-icon menu-non-active">
                        <img src="{!!URL::to('public/backend/assets/img/stl_icon_Report_active.png')!!}" class="sidebar-icon menu-active">
                        <span class="menu-title">REPORT</span>
                    </a>
                </li>    
            @endif    
        @endif
        @if(Route::is('worklogs.index') || Route::is('worklogs.create') || Route::is('worklogs.edit'))
            <li class="nav-item nav-active">
                <a class="nav-link active" href="{{route('worklogs.index')}}">
                    <img src="{!!URL::to('public/backend/assets/img/stl_task-icon-active.png')!!}" class="sidebar-icon menu-non-active">
                    <span class="menu-title">WORK LOG</span>
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link" href="{{route('worklogs.index')}}">
                <img src="{!!URL::to('public/backend/assets/img/stl_task-icon-active.png')!!}" class="sidebar-icon menu-active">
                    <img src="{!!URL::to('public/backend/assets/img/task-icon.png')!!}" class="sidebar-icon menu-non-active">
                    <span class="menu-title">WORK LOG</span>
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link" href="{{route('logout')}}">
                <img src="{!!URL::to('public/backend/assets/img/logout-icon-default.svg')!!}" class="sidebar-icon menu-non-active">
                <img src="{!!URL::to('public/backend/assets/img/stl_logout.png')!!}" class="sidebar-icon menu-active">
                <span class="menu-title">LOGOUT</span>
            </a>
        </li>
    </ul>
</nav>
<!-- partial -->

<script>
    $('.navbar-toggler').click(function (){
        if($('.Support-Contact').is(':visible')){
            $('.Support-Contact').hide();
        }else{
            $('.Support-Contact').show();
        }
    });
</script>
