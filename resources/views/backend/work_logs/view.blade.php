<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    @include('backend.layouts.styles')
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        a:hover{
            text-decoration:none;
        }
    </style>
</head>
<body>
    <div class="container-scroller role-list-page">
        <!-- partial:partials/_navbar.html -->
        @include('backend.layouts.header')
        <div class="container-fluid page-body-wrapper">
            @include('backend.layouts.sidebar')
            <div class="main-panel role-list-main-panel">
                <div class="content-wrapper">
                    <div id="alert-show">
                        @include('backend.layouts.messages')
                    </div>
                    <h3 class="role-list-title">Work Log</h3>
                    <hr class="Dash-Line">
                    <div class="row DataTableBox">
                        <div class="row role-list-table-subHeader">
                            <div class="col-sm-5 subHeader-col-1">
                                <div class="form-inline">
                                
                                </div>
                            </div>
                            <div class="col-sm-7 subHeader-col-2">
                            </div>
                        </div>
                        <div class="table-responsive" style="margin-bottom: 20px;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th  style="width:35%;">Task</th>
                                        <th  style="width:15%;">Time</th>
                                        <th style="width:50%;">Summery</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($worklogs as $worklog)
                                        <tr class="existing-tasks">
                                            <td>{{$worklog->Task->title}}</td>
                                            <td>{{sprintf('%02d:%02d', (int) $worklog->time, fmod($worklog->time, 1) * 60)}}</td>
                                            <td>{{$worklog->summery}}</td>
                                        </tr>   
                                    @endforeach  
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                {{--            footer--}}
                <!-- partial -->
                </div>
                <!-- main-panel ends -->
            </div>
        </div>
    </div>
        <!-- page-body-wrapper ends -->
        <!-- container-scroller -->

    @include('backend.layouts.scripts')

</body>

</html>
