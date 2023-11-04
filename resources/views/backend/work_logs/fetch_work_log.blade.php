<table class="table">
    <thead>
    <col>
    <colgroup span="3"></colgroup>
    <colgroup span="3"></colgroup>
    <colgroup span="3"></colgroup>
    <colgroup span="3"></colgroup>
    <colgroup span="3"></colgroup>
    <tr>
        <th rowspan="2">Name</th>
        <th colspan="3" scope="colgroup">Sun</th>
        <th colspan="3" scope="colgroup">Mon</th>
        <th colspan="3" scope="colgroup">Tue</th>
        <th colspan="3" scope="colgroup">Wed</th>
        <th colspan="3" scope="colgroup">Thu</th>
    </tr>
    <tr>
        <th colspan="2">Task</th>
        <th colspan="1">Action</th>
        <th colspan="2">Task</th>
        <th colspan="1">Action</th>
        <th colspan="2">Task</th>
        <th colspan="1">Action</th>
        <th colspan="2">Task</th>
        <th colspan="1">Action</th>
        <th colspan="2">Task</th>
        <th colspan="1">Action</th>
    </tr>
    </thead>
    <tbody>
        @foreach($users as $user)   
            @php $worklogs = \App\Modals\WorkLog::getWorkLogs($user->id,$weekId); @endphp
                @if(count($worklogs) > 0)
                    @for($i=0; $i<$worklogs['maxRow']; $i++)
                        <tr>
                            @if($i==0)
                                <td rowspan="{{$worklogs['maxRow']}}"><p>{{$user->name}}</p><p>Total Tasks: {{$worklogs['totalTasks']}}</p><p>Total Spent Time: {{$worklogs['totalTime']}}</p></td>
                            @endif

                            @if(isset($worklogs['sunTasks'][$i]))
                                @php $worklog = $worklogs['sunTasks'][$i]; @endphp
                                <td class="pl-1" style="white-space: pre-line;line-height: 18px;" colspan="2">
                                    <a href="{{route('tasks.show',$worklog['task']->id)}}" style="color: #009877">{{empty($worklog['task']->Task->title) ? '' : $worklog['task']->Task->title}}{{empty($worklog['task']->Task->parent_id) ? '' : ' (Sub-Task)'}} ({{$worklog['time']}})</a>
                                </td>
                            @else
                                <td colspan="2"></td>
                            @endif
                            @if($i==0)
                                <td rowspan="{{$worklogs['maxRow']}}">
                                    @if($i==0 && count($worklogs['sunTasks']) > 0)
                                        @if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id)
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{route('worklogs.edit', $worklog['task']->date)}}"><i class="fa fa-pencil" style="margin-right: 5px;"></i></a>
                                        @endif
                                        @if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id)
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{ route('worklogs.destroy', $worklog['task']->id) }}"
                                                onclick="deleteData({{$worklog['task']->id}})"><i class="fa fa-trash"></i>  
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            @endif

                            @if(isset($worklogs['monTasks'][$i]))
                                @php $worklog = $worklogs['monTasks'][$i]; @endphp
                                <td class="pl-1" style="white-space: pre-line;line-height: 18px;" colspan="2">
                                    <a href="{{route('tasks.show',$worklog['task']->id)}}" style="color: #009877">{{empty($worklog['task']->Task->title) ? '' : $worklog['task']->Task->title}}{{empty($worklog['task']->Task->parent_id) ? '' : ' (Sub-Task)'}} ({{$worklog['time']}})</a>
                                </td>
                            @else
                                <td colspan="2"></td>
                            @endif
                            @if($i==0)
                                <td rowspan="{{$worklogs['maxRow']}}">
                                    @if($i==0 && count($worklogs['monTasks']) > 0)    
                                        @if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id)
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{route('worklogs.edit', $worklog['task']->date)}}"><i class="fa fa-pencil" style="margin-right: 5px;"></i></a>
                                        @endif
                                        @if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id)
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{ route('worklogs.destroy', $worklog['task']->id) }}"
                                            onclick="deleteData({{$worklog['task']->id}})"><i class="fa fa-trash"></i>
                                                
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            @endif

                            @if(isset($worklogs['tueTasks'][$i]))
                                @php $worklog = $worklogs['tueTasks'][$i]; @endphp
                                <td class="pl-1" style="white-space: pre-line;line-height: 18px;" colspan="2">
                                    <a href="{{route('tasks.show',$worklog['task']->id)}}" style="color: #009877">{{empty($worklog['task']->Task->title) ? '' : $worklog['task']->Task->title}}{{empty($worklog['task']->Task->parent_id) ? '' : ' (Sub-Task)'}} ({{$worklog['time']}})</a>
                                </td>
                            @else
                                <td colspan="2"></td>
                            @endif
                            @if($i==0)
                                <td rowspan="{{$worklogs['maxRow']}}">
                                    @if($i==0 && count($worklogs['tueTasks']) > 0)    
                                        @if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id)
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{route('worklogs.edit', $worklog['task']->date)}}"><i class="fa fa-pencil" style="margin-right: 5px;"></i></a>
                                        @endif
                                        @if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id)
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{ route('worklogs.destroy', $worklog['task']->id) }}"
                                            onclick="deleteData({{$worklog['task']->id}})"><i class="fa fa-trash"></i>
                                                
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            @endif

                            @if(isset($worklogs['wedTasks'][$i]))
                                @php $worklog = $worklogs['wedTasks'][$i]; @endphp
                                <td class="pl-1" style="white-space: pre-line;line-height: 18px;" colspan="2">
                                    <a href="{{route('tasks.show',$worklog['task']->id)}}" style="color: #009877">{{empty($worklog['task']->Task->title) ? '' : $worklog['task']->Task->title}}{{empty($worklog['task']->Task->parent_id) ? '' : ' (Sub-Task)'}} ({{$worklog['time']}})</a>
                                </td>
                            @else
                                <td colspan="2"></td>
                            @endif
                            @if($i==0)
                                <td rowspan="{{$worklogs['maxRow']}}">
                                    @if($i==0 && count($worklogs['wedTasks']) > 0)    
                                        @if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id)
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{route('worklogs.edit', $worklog['task']->date)}}"><i class="fa fa-pencil" style="margin-right: 5px;"></i></a>
                                        @endif
                                        @if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id)
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{ route('worklogs.destroy', $worklog['task']->id) }}"
                                            onclick="deleteData({{$worklog['task']->id}})"><i class="fa fa-trash"></i>
                                                
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            @endif

                            @if(isset($worklogs['thuTasks'][$i]))
                                @php $worklog = $worklogs['thuTasks'][$i]; @endphp
                                <td class="pl-1" style="white-space: pre-line;line-height: 18px;" colspan="2">
                                    <a href="{{route('tasks.show',$worklog['task']->id)}}" style="color: #009877">{{empty($worklog['task']->Task->title) ? '' : $worklog['task']->Task->title}}{{empty($worklog['task']->Task->parent_id) ? '' : ' (Sub-Task)'}} ({{$worklog['time']}})</a>
                                </td>
                            @else
                                <td colspan="2"></td>
                            @endif
                            @if($i==0)
                                <td rowspan="{{$worklogs['maxRow']}}">
                                    @if($i==0 && count($worklogs['thuTasks']) > 0)    
                                        @if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id)
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{route('worklogs.edit', $worklog['task']->date)}}"><i class="fa fa-pencil" style="margin-right: 5px;"></i></a>
                                        @endif
                                        @if(\Illuminate\Support\Facades\Auth::user()['id'] == $user->id)
                                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{ route('worklogs.destroy', $worklog['task']->id) }}"
                                            onclick="deleteData({{$worklog['task']->id}})"><i class="fa fa-trash"></i>
                                                
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endfor
                @endif    
        @endforeach
    </tbody>
</table>