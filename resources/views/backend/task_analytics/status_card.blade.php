<div class="row">
    <div class="col-md-2 ">
        <div class="overall-task-img">
            <img src="{!! URL::to('public/backend/assets/img/stl_report.png')!!}" class="">
        </div>
    </div>
    <div class="col-md-10 overall-task-quantity p-sm-0">
    @php $overall_task_status = \App\Modals\User::overallTaskStatus(); @endphp
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Departments</h4>
                        <h3 class="task-quantity-number Programs">{{$overall_task_status['programs']}}</h3>
                    </div>
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Members</h4>
                        <h3 class="task-quantity-number Members">{{$overall_task_status['members']}}</h3>
                    </div>
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Tasks</h4>
                        <h3 class="task-quantity-number Tasks">{{$overall_task_status['tasks']}}</h3>
                    </div>
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Pending</h4>
                        <h3 class="task-quantity-number Pending">{{$overall_task_status['pending']}}</h3>
                    </div>
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Accepted</h4>
                        <h3 class="task-quantity-number Accepted">{{$overall_task_status['accepted']}}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">In Progress</h4>
                        <h3 class="task-quantity-number In-Progress">{{$overall_task_status['inProgress']}}</h3>
                    </div>
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Completed</h4>
                        <h3 class="task-quantity-number Completed">{{$overall_task_status['completed']}}</h3>
                    </div>
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Rejected</h4>
                        <h3 class="task-quantity-number Rejected">{{$overall_task_status['rejected']}}</h3>
                    </div>
                    <div class="col pl-sm-0">
                        <h4 class="task-quantity-title">Overdue</h4>
                        <h3 class="task-quantity-number overdue">{{$overall_task_status['overdue']}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>