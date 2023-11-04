<div class="table-responsive" style="margin-bottom: 20px;">
    <table class="table supervise-status-table">
        <thead>
            <tr>
                <th>SL No.<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderMember="asc" coloumnMember="id"></th>
                <th  style="width:25%;">Full Name<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderMember="asc" coloumnMember="name"></th>
                <th  style="width:50%;">Team</th>
                <th  style="width:25%;">Total Task<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderMember="asc" coloumnMember="total"></th>
                <th  style="width:25%;">Accepted<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderMember="asc" coloumnMember="accepted"></th>
                <th  style="width:25%;">In Progress<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderMember="asc" coloumnMember="inProgress"></th>
                <th  style="width:15%;">Completed<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderMember="asc" coloumnMember="completed"></th>
                <th  style="width:15%;">Overdue<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderMember="asc" coloumnMember="overdue"></th>
                <th  style="width:15%;">Allocated Time<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="allocated_time"></th>
                <th  style="width:15%;">Spend Time<i class='member-coloumn fas fa-exchange-alt custom-sorting' style="color:#009877;" orderUser="asc" coloumnUser="spend_time"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
            <tr>
                <td>{{empty($sort_id) ? ++$serialMember : $serialMember--}}</td>
                <td><a href="{{route('task-analytics.show',$member->id)}}">{{$member->name}}</a></td>
                <td>
                    @php $team = \App\Modals\User::getTeamName($member->id); @endphp
                    {{ $team }}
                </td>
                @php $task_status = \App\Modals\User::taskCountByStatus($member->id); @endphp
                @php $spend_time = \App\Modals\WorkLog::getTotalSpendTime($member->id); @endphp
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
<div class="member-list-card-pagination" id="pageMember" style="margin-bottom:20px;">
    @if(count($members) > 0)
        @if((($members->currentPage()-1)*$members->perPage())+$members->perPage() < $members->total())
            Showing from {{(($members->currentPage()-1)*$members->perPage())+1}} to {{(($members->currentPage()-1)*$members->perPage())+$members->perPage()}} of {{$members->total()}} 
        @else
            Showing from {{(($members->currentPage()-1)*$members->perPage())+1}} to {{$members->total()}} of {{$members->total()}} 
        @endif
    @else
        Showing from 0 to 0 of 0    
    @endif
        {{ $members->links() }}
</div>

