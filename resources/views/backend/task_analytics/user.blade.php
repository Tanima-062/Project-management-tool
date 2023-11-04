<div class="table-responsive" style="margin-bottom: 20px;">
    <table class="table supervise-status-table">
        <thead>
            <tr>
                <th>SL No.<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="id"></th>
                <th  style="width:25%;">Full Name<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="name"></th>
                <th  style="width:50%;">Team</th>
                <th  style="width:25%;">Total Task<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="total"></th>
                <th  style="width:25%;">Accepted<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="accepted"></th>
                <th  style="width:25%;">In Progress<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="inProgress"></th>
                <th  style="width:15%;">Completed<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="completed"></th>
                <th  style="width:15%;">Overdue<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="overdue"></th>
                <th  style="width:15%;">Allocated Time<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="allocated_time"></th>
                <th  style="width:15%;">Spend Time<i class='user-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="spend_time"></th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{empty($sort_id) ? ++$serialUser : $serialUser--}}</td>
                <td><a href="{{route('task-analytics.show',$user->id)}}">{{$user->name}}</a></td>
                <td>
                    @php $team = \App\Modals\User::getTeamName($user->id); @endphp
                    {{ $team }}
                </td>
                @php $task_status = \App\Modals\User::taskCountByStatus($user->id); @endphp
                @php $spend_time = \App\Modals\WorkLog::getTotalSpendTime($user->id); @endphp
                <td class="task-quantity-number Tasks ">
                    {{$task_status['total']}}
                </td>
                <td class="task-quantity-number Accepted">
                    {{$task_status['accepted']}}
                </td>
                <td class="task-quantity-number In-Progress">
                    {{$task_status['inProgress']}}
                </td>
                <td class="task-quantity-number Completed">
                    {{$task_status['completed']}}
                </td>
                <td class="task-quantity-number overdue">
                    {{$task_status['overdue']}}
                </td>
                <td class="task-quantity-number">
                    {{$task_status['allocated_time']}}
                </td>
                <td class="task-quantity-number">
                    {{$spend_time}}
                </td>
            </tr>
        @endforeach    
        </tbody>
    </table>
</div>
<div class="user-list-card-pagination" id="pageUser" style="margin-bottom:20px;">
    @if(count($users) > 0)
        @if((($users->currentPage()-1)*$users->perPage())+$users->perPage() < $users->total())
            Showing from {{(($users->currentPage()-1)*$users->perPage())+1}} to {{(($users->currentPage()-1)*$users->perPage())+$users->perPage()}} of {{$users->total()}} 
        @else
            Showing from {{(($users->currentPage()-1)*$users->perPage())+1}} to {{$users->total()}} of {{$users->total()}} 
        @endif
    @else
        Showing from 0 to 0 of 0    
    @endif
        {{ $users->links() }}
</div>

