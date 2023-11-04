<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>Task ID<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="task_id"></i></th>
            <th class="pl-1">Task Title<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="title"></i></th>
            <th>Assignee<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="id"></i></th>
            <th>Start Time<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="start_date"></i></th>
            <th>Due Time<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="end_date"></i></th>
            <th>Priority<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="priority"></i></th>
            <th>Status<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="status"></th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tasks_for_list as $task)
            <tr>
                <td>{{empty($task->task_id) ? '' : $task->task_id}}</td>
                <td class="pl-1" style="white-space: pre-line;line-height: 18px;">
                    <a href="{{route('otherRequestTasks.show',$task->id)}}" style="color: #009877">{{empty($task->title) ? '' : $task->title}}{{empty($task->parent_id) ? '' : ' (Sub-Task)'}}</a>
                </td>
                <td>{{empty($task->requestFrom->name) ? '' : $task->requestFrom->name}}</td>
                <td>{{empty($task->start_date) ? '' : date('d M, Y', strtotime($task->start_date))}}</td>
                <!-- @if(!empty($task->end_date) && $task->end_date < date('Y-m-d') && $task->status < 2)
                    <td>
                        <p>{{empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))}}</p>
                        <p class="assign-to-me-badge badge badge-danger">Overdue</p>
                    </td>
                @else
                    <td>{{empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))}}</td>
                @endif -->
                <td>{{empty($task->end_date) ? '' : date('d M, Y', strtotime($task->end_date))}}</td>
                <td>{{$task->priority == '0' ? 'Low' : ($task->priority == '1' ? 'Medium' : ($task->priority == '2' ? 'High' : ''))}}</td>

                @if($task->status == '-1')
                    <td><button class="btn btn-inverse-secondary btn-fw button-pending  task-status">Pending</button></td>
                @elseif($task->status == '1')
                    <td><button class="btn btn-inverse-warning btn-fw  task-status">Accepted</button></td> 
                @elseif($task->status == '0')
                    <td><button class="btn btn-inverse-danger btn-fw task-status">Rejected</button></td>    
                @endif

                @if($task->status == '-1')
                    <td>
                        <form method="POST" action="{{route('otherRequestTasks.approve',$task->id)}}" class="task-details-main-page">  
                            @csrf
                            <button type="submit" class="btn btn-success task-accept-button">Accept</button>
                            <a href="{{route('otherRequestTasks.show',$task->id)}}" class="btn btn-danger button task-reject-button">Reject</a>
                        </form>
                    </td>
                @else
                    <td><span class="task-list-action-na">No Action Required</span></td>
                @endif

            </tr>
        @endforeach
        </tbody>
    </table>
   
    <div class="task-list-card-pagination" id="pageTask" style="margin-top:20px;">
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
   
</div>
