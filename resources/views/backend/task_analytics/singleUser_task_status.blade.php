<div class="row">
    <div class="col-md-2">
        <div class="overall-task-img">
            <img src="{!! URL::to ('public/backend/assets/img/overall-task-icon.png') !!}" class="">
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