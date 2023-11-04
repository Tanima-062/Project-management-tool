@foreach($teams as $team)
    <!-- @php $customTeam = \App\Modals\Team::isMyCustomTeam($team); @endphp -->
    <!-- @php $organizationTeam = \App\Modals\Team::isOrganizationTeam($team); @endphp -->
    <!-- @if($team->flag == 'organization')
        @php $hasTeam = \App\Modals\Task::hasTeam($task->teamTasks, $team); @endphp
        <div>
            <input type="checkbox" id="{{$team->id}}" name="teams[]" class="checkbox disable-team team_values" value="{{$team->id}}" class="disable-team" {{$hasTeam}}>
            <label for="{{$team->id}}">{{$team->name}}</label>
        </div>
    @else -->
        @php $customTeam = \App\Modals\Team::isMyCustomTeam($team); @endphp
        @if($customTeam)
            @php $hasTeam = \App\Modals\Task::hasTeam($task->teamTasks, $team); @endphp
            <label class="container" for="{{$team->id}}">
                {{$team->name}}
                <input type="checkbox" id="{{$team->id}}" name="teams[]" class="checkbox disable-team team_values" value="{{$team->id}}" {{$hasTeam}}>
                <span class="checkmark"></span>
            </label>
            {{-- <div>
                <input type="checkbox" id="{{$team->id}}" name="teams[]" class="checkbox disable-team team_values" value="{{$team->id}}" class="disable-team" {{$hasTeam}}>
                <label for="{{$team->id}}">{{$team->name}}</label>
            </div> --}}
        @endif
    <!-- @endif -->
@endforeach
