<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>Task ID<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="task_id"></i></th>
            <th class="pl-1">Task Title<i class='fas fa-exchange-alt custom-sorting'  order="asc" coloumn="title"></i></th>
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
                    <a href="{{route('myRequestTasks.show',$task->id)}}" style="color: #009877">{{empty($task->title) ? '' : $task->title}}{{empty($task->parent_id) ? '' : ' (Sub-Task)'}}</a>
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

                <td>
                    @if($task->request_from == \Illuminate\Support\Facades\Auth::user()['id'] && $task->status != 1)
                        <a class="Rectangle-Edit btn-hover2" style="text-decoration: none;" href="{{route('myRequestTasks.edit', $task->id)}}"><i class="fa fa-pencil" style="margin-right: 5px;"></i>Edit</a>

                        <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{ route('myRequestTasks.destroy', $task->id) }}"
                            onclick="deleteData('delete-form-{{$task->id}}');"><i class="fa fa-trash"></i>
                            Delete
                        </a>

                        <form id="delete-form-{{$task->id}}" action="{{ route('myRequestTasks.destroy', $task->id) }}" method="POST" class="d-none" style="display: none">
                            @method('DELETE')
                            @csrf
                        </form>
                    @else
                        <span class="task-list-action-na">No Action Required</span>
                    @endif
                </td>
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
<script>
    function deleteData(id){
        event.preventDefault();
        swal({
                title: "Are you sure?",
                type: "error",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "CONFIRM",
                cancelButtonText: "CANCEL",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function() {
                $.ajax({
                    url: $("#" + id).attr('action'),
                    method: 'POST',
                    data: $("#" + id).serializeArray(),
                    success: function (data) {
                        formSubmit();
                        $("#alert-show").html("<div class='alert alert-success'><div><p>"+data.msg+"</p></div></div>");
                        $("#alert-show").show().delay(5000).fadeOut();
                    }
                });
            }
        );
    }
    
</script>
