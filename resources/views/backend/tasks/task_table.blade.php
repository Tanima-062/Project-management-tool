<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>Task ID<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="task_id"></i></th>
            <th class="pl-1">Task Title<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="title"></i></th>
            <th>Assignee<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="id"></i></th>
            <th>Allocated Time<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="allocated_time"></i></th>
            <th>Priority<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="priority"></i></th>
            <th>Status<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="status"></i></th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tasks_for_list as $task)
            <tr>
                <td>{{empty($task->task_id) ? '' : $task->task_id}}</td>
                <td class="pl-1" style="white-space: pre-line;line-height: 18px;">
                    <a href="{{route('tasks.show',$task->id)}}" style="color: #009877; font-size:15px;"><b>{{empty($task->title) ? '' : $task->title}}{{empty($task->parent_id) ? '' : ' (Sub-Task)'}}</b></a>
                    <div class="row">
                        @foreach($task->taskTags as $task_tag)
                            <div class="mh-100 h-25 d-inline-block w-50 mw-100 mr-1" style="word-break:break-word; text-align:center; border-radius:10px; background-color: rgba(0,0,255,.1)">{{$task_tag->Tag->name}}</div>
                        @endforeach   
                    </div> 
                </td>
                <td>  
                    @foreach($task->assignees as $assignee)  
                        @if((((!empty($assignee->userWithTrashed))&& ($assignee->show_rejected_task == '1'))) || (((!empty($assignee->userWithTrashed)) && ($assignee->task_status != '4') && ($assignee->show_rejected_task == '0')))) 
                            <p>{{empty($assignee->userWithTrashed->name) ? '' : $assignee->userWithTrashed->name}}</p>    
                        @endif
                    @endforeach
                </td>
                <td>{{$task->allocated_time}}</td>
                <td>{{$task->priority == '0' ? 'Low' : ($task->priority == '1' ? 'Medium' : ($task->priority == '2' ? 'High' : ''))}}</td>

                @if($task->status == '-1')
                    <td>
                        <button class="btn btn-inverse-secondary btn-fw button-pending  task-status">Pending</button>
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

                <td>
                    <a class="Rectangle-Edit btn-hover2" style="text-decoration: none;" href="{{route('tasks.edit', $task->id)}}"><i class="fa fa-pencil" style="margin-right: 5px;"></i>Edit</a>
                    @if($task->assign_by_id == \Illuminate\Support\Facades\Auth::user()['id'])
                        <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{ route('tasks.destroy', $task->id) }}"
                            onclick="deleteData('delete-form-{{$task->id}}');"><i class="fa fa-trash"></i>
                            Delete
                        </a>
                    @endif

                    <form id="delete-form-{{$task->id}}" action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-none" style="display: none">
                        @method('DELETE')
                        @csrf
                    </form>
                </td>
            </tr>
            @php $subTasks = \App\Modals\Task::getSubTasks($task); @endphp
            @foreach($subTasks as $task)
                <tr style="background-color: #F0F0F0">
                    <td>{{empty($task->task_id) ? '' : $task->task_id}}</td>
                    <td class="pl-1" style="white-space: pre-line;line-height: 18px;">
                        <a href="{{route('tasks.show',$task->id)}}" style="color: #009877; font-size:15px;"><b>{{empty($task->title) ? '' : $task->title}}{{empty($task->parent_id) ? '' : ' (Sub-Task)'}}</b></a>
                        <div class="row">
                            @foreach($task->taskTags as $task_tag)
                                <div class="mh-100 h-25 d-inline-block mw-100 mr-1" style="word-break:break-word; width:70px; border-radius:10px; background-color: rgba(0,0,255,.1)">{{$task_tag->Tag->name}}</div>
                            @endforeach   
                        </div> 
                    </td>
                    <td>  
                        @foreach($task->assignees as $assignee)  
                            @if((((!empty($assignee->userWithTrashed))&& ($assignee->show_rejected_task == '1'))) || (((!empty($assignee->userWithTrashed)) && ($assignee->task_status != '4') && ($assignee->show_rejected_task == '0')))) 
                                <p>{{empty($assignee->userWithTrashed->name) ? '' : $assignee->userWithTrashed->name}}</p>    
                            @endif
                        @endforeach
                    </td>
                    <td>{{$task->allocated_time}}</td>
                    <td>{{$task->priority == '0' ? 'Low' : ($task->priority == '1' ? 'Medium' : ($task->priority == '2' ? 'High' : ''))}}</td>

                    @if($task->status == '-1')
                        <td>
                            <button class="btn btn-inverse-secondary btn-fw button-pending  task-status">Pending</button>
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
                    @endif

                    <td>
                        <a class="Rectangle-Edit btn-hover2" style="text-decoration: none;" href="{{route('tasks.edit', $task->id)}}"><i class="fa fa-pencil" style="margin-right: 5px;"></i>Edit</a>
                        @if($task->assign_by_id == \Illuminate\Support\Facades\Auth::user()['id'])
                            <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{ route('tasks.destroy', $task->id) }}"
                                onclick="deleteData('delete-form-{{$task->id}}');"><i class="fa fa-trash"></i>
                                Delete
                            </a>
                        @endif

                        <form id="delete-form-{{$task->id}}" action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-none" style="display: none">
                            @method('DELETE')
                            @csrf
                        </form>
                    </td>
                </tr>
            @endforeach
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
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });
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
