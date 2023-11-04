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
                            <form method="POST" action="{{ route('worklogs.update') }}"> 
                                @csrf  
                                <input type="hidden" value="{{$date}}" name="date">
                                <table class="table">
                                    <tr class="row-copy" style="display:none;">
                                        <td>
                                            <select class="form-control col-md-12" name="tasks[]">
                                                @foreach($tasks as $task)
                                                    <option value="{{$task->id}}" {{(collect(old('task'))->contains($task->id)) ? 'selected':''}}>{{$task->title}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="times[]" value="{{old('times')}}" onkeyup="if (/[^0-9\.]/g.test(this.value)) this.value = this.value.replace(/[^0-9\.]/g,'')">
                                        </td>
                                        <td>
                                            <div class='right-inner-addon date datepicker'>
                                                <i class="fa fa-calendar-o date-picker" style="margin-top:7px;"></i>
                                                <input name='dates[]' value="{{date('d M, Y')}}" type="text" class="form-control date-picker date-picker-input" data-date="" data-date-format="d M, yyyy" autocomplete="off" readonly id="date"/>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="summery[]" rows="5" cols="40">{{old('summery')}}</textarea>
                                        </td>
                                        <td>
                                            <div class="w3-container">
                                                <a class="w3-button w3-xlarge w3-circle w3-teal" onclick="rowAdd($(this))">+</a>
                                                <a class="w3-button w3-xlarge w3-circle w3-red w3-card-4" onclick="rowDelete($(this))" style="display:none;">-</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <thead>
                                        <tr>
                                            <th  style="width:40%;">Tasks</th>
                                            <th  style="width:10%;">Time</th>
                                            <th  style="width:20%;">Date</th>
                                            <th style="width:15%;">Summery</th>
                                            <th  style="width:15%;">Add/Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($work_logs as $worklog)
                                            <tr class="existing-tasks">
                                                <td>
                                                    <select class="form-control {{ $errors->has('tasks') ? ' is-invalid' : '' }} col-md-12" name="tasks[]">
                                                        @foreach($tasks as $task)
                                                            <option value="{{$task->id}}" {{((collect(old('task'))->contains($task->id)) ? 'selected': ($task->id == $worklog->task_id)) ? 'selected' : ''}}>{{$task->title}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="times[]" value="{{old('times') ? old('times') : $worklog->time}}" onkeyup="if (/[^0-9\.]/g.test(this.value)) this.value = this.value.replace(/[^0-9\.]/g,'') " required>
                                                </td>
                                                <td>
                                                    <div class='right-inner-addon date datepicker'>
                                                        <i class="fa fa-calendar-o date-picker" style="margin-top:7px;"></i>
                                                        <input name='dates[]' value="{{date('d M, Y',strtotime($worklog->date))}}" type="text" class="form-control date-picker date-picker-input" data-date="" data-date-format="d M, yyyy" autocomplete="off" readonly id="date"/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <textarea name="summery[]" rows="5" cols="40">{{$worklog->summery}}</textarea>
                                                </td>
                                                <td>
                                                    <div class="w3-container">
                                                        <a class="w3-button w3-xlarge w3-circle w3-red w3-card-4" onclick="rowDelete($(this))">-</a>
                                                    </div>
                                                </td>
                                            </tr>   
                                        @endforeach
                                        <tr class="new-task">
                                            <td>
                                                <select class="form-control {{ $errors->has('tasks') ? ' is-invalid' : '' }} col-md-12" name="tasks[]">
                                                    @foreach($tasks as $task)
                                                        <option value="{{$task->id}}" {{(collect(old('task'))->contains($task->id)) ? 'selected':''}}>{{$task->title}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('tasks'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('tasks') }}</strong>
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="times[]" value="{{old('times')}}" onkeyup="if (/[^0-9\.]/g.test(this.value)) this.value = this.value.replace(/[^0-9\.]/g,'') ">
                                            </td>
                                            <td>
                                                <div class='right-inner-addon date datepicker'>
                                                    <i class="fa fa-calendar-o date-picker" style="margin-top:7px;"></i>
                                                    <input name='dates[]' value="{{date('d M, Y')}}" type="text" class="form-control date-picker date-picker-input" data-date="" data-date-format="d M, yyyy" autocomplete="off" readonly id="date"/>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea name="summery[]" rows="5" cols="40">{{old('summery')}}</textarea>
                                            </td>
                                            <td>
                                                <div class="w3-container">
                                                    <a class="w3-button w3-xlarge w3-circle w3-teal" onclick="rowAdd($(this))">+</a>
                                                    <a class="w3-button w3-xlarge w3-circle w3-red w3-card-4" onclick="rowDelete($(this))" style="display:none;">-</a>
                                                </div>
                                            </td>
                                        </tr>   
                                    </tbody>
                                </table>
                                <div class="text-center mt-4">
                                    <a class="btn custom-outline-btn" href="{{route('worklogs.index')}}">Cancel</a>
                                    <button class="btn custom-btn" type="submit">Save</button>
                                </div>
                            </form>
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
    <script>
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

        function rowAdd(This){
            event.preventDefault();
            This.closest('tbody').append($('.row-copy').clone().removeClass('row-copy').show());
            $('tbody tr:last').find("[type='text']").attr('required',true);
            This.next().show();
            This.hide();
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
        }
        function rowDelete(This){
            event.preventDefault();
            This.closest('tr').remove();
            if($('.existing-tasks').length == 0){
                $('.new-task').find("[type='text']").attr('required',true);
            }
        }
        
    </script>

</body>

</html>
