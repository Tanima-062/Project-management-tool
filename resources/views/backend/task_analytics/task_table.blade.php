<div class="table-responsive" style="margin-bottom: 20px;">
    <table class="table supervise-status-table">
        <thead>
            <tr>
                <th>SL No.<i class='fas fa-exchange-alt custom-sorting' style="color:#009877;" order="asc" coloumn="id"></i></th>
                <th  style="width:50%;">Task Name<i class='fas fa-exchange-alt custom-sorting' style="color:#009877;" order="asc" coloumn="title"></i></th>
                <th  style="width:25%;">Assigned By<i class='fas fa-exchange-alt custom-sorting' style="color:#009877;" order="asc" coloumn="assign_by"></i></th>
                <th  style="width:25%;">Start Time<i class='fas fa-exchange-alt custom-sorting' style="color:#009877;" order="asc" coloumn="start_date"></i></th>
                <th  style="width:25%;">Due Time<i class='fas fa-exchange-alt custom-sorting' style="color:#009877;" order="asc" coloumn="end_date"></i></th>
                <th  style="width:25%;">Priority<i class='fas fa-exchange-alt custom-sorting' style="color:#009877;" order="asc" coloumn="priority"></i></th>
                <th  style="width:25%;">Status<i class='fas fa-exchange-alt custom-sorting' style="color:#009877;" order="asc" coloumn="status"></i></th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks_for_list as $task)
                <tr>
                    <td>{{empty($sort_id) ? ++$serial : $serial--}}</td>
                    <td class="pl-1" style="white-space: pre-line;line-height: 18px;">
                       <a href="{{route('tasks.show',$task->id)}}" style="color: #188ef9">{{empty($task->title) ? '' : $task->title}}{{empty($task->parent_id) ? '' : ' (Sub-Task)'}}</a>
                    </td>
                    <td>
                    {{ empty($task->assignBy->name) ? '' : $task->assignBy->name }}
                    </td>
                    <td>{{empty($task->start_date) ? '' : date('d M, Y', strtotime($task->start_date))}}</td>
                    @if(!empty($task->end_date) && $task->end_date < date('Y-m-d') && $task->status < 2)
                        <td>
                            <p>{{empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))}}</p>
                            <p class="assign-to-me-badge badge badge-danger">Overdue</p>
                        </td>
                    @else
                        <td>{{empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))}}</td>
                    @endif
                    <td>{{$task->priority == '0' ? 'Low' : ($task->priority == '1' ? 'Medium' : ($task->priority == '2' ? 'High' : ''))}}</td>

                    @if($task->status == '-1')
                        <td>
                            <button class="btn btn-inverse-secondary btn-fw button-pending task-status">Pending</button>
                        </td>
                    @elseif($task->status == '0')
                        <td>
                            <button class="btn btn-inverse-warning btn-fw  task-status">Accepted</button>
                        </td>    
                    @elseif($task->status == '1')
                        <td>
                            <button class="btn btn-inverse-info btn-fw  task-status">In Progress</button>
                        </td>
                    @elseif($task->status == '2')
                        <td>
                            <button class="btn btn-inverse-success btn-fw task-status">Completed</button>
                        </td>
                    @elseif($task->status == '4')
                        <td>
                            <button class="btn btn-inverse-danger btn-fw task-status">Rejected</button>
                        </td>
                    @elseif($task->status == '5')       
                        <td><button class="btn btn-inverse-danger btn-fw task-status" style="background-color:#D4D0EE;color:#483D8B">On Hold</button></td>
                    @elseif($task->status == '6')       
                        <td><button class="btn btn-inverse-danger btn-fw task-status" style="background-color:#E3C9EF;color:#a110e3">On Review</button></td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    
</div>
<div id="pageUserTask" style="margin-top:20px; padding-bottom: 20px;">
    @if(count($tasks_for_list) > 0)
        @if((($tasks_for_list->currentPage()-1)*$tasks_for_list->perPage())+$tasks_for_list->perPage() < $tasks_for_list->total())
            Showing from {{(($tasks_for_list->currentPage()-1)*$tasks_for_list->perPage())+1}} to {{(($tasks_for_list->currentPage()-1)*$tasks_for_list->perPage())+$tasks_for_list->perPage()}} of {{$tasks_for_list->total()}} 
        @else
            Showing from {{(($tasks_for_list->currentPage()-1)*$tasks_for_list->perPage())+1}} to {{$tasks_for_list->total()}} of {{$tasks_for_list->total()}} 
        @endif
    @else
        Showing from 0 to 0 of 0
    @endif        
        {{ $tasks_for_list->links() }}
</div>