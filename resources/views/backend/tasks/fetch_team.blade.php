@foreach($teams as $team)
    @php $organizationTeam = \App\Modals\Team::isOrganizationTeam($team); @endphp
    <!-- @if($team->flag == 'organization')

        <label class="container" for="{{$team->id}}">
            {{empty($team->name) ? '' : (strlen($team->name) > 20 ? substr($team->name, 0, 20).'...' : $team->name)}} 
            <input type="checkbox" name="teams[]" id="{{$team->id}}" class="checkbox disable-team team_values" value="{{$team->id}}" >
            <span class="checkmark"></span>
        </label>

        {{-- <div>
            <input type="checkbox" id="{{$team->id}}" name="teams[]" class="checkbox disable-team team_values" value="{{$team->id}}" class="disable-team">
            <label for="{{$team->id}}">{{empty($team->name) ? '' : (strlen($team->name) > 20 ? substr($team->name, 0, 20).'...' : $team->name)}}</label>
        </div> --}}
    @else -->
        @php $customTeam = \App\Modals\Team::isMyCustomTeam($team); @endphp
        @if($customTeam)
            <label class="container" for="{{$team->id}}">
                {{empty($team->name) ? '' : (strlen($team->name) > 20 ? substr($team->name, 0, 20).'...' : $team->name)}}
                <input type="checkbox" name="teams[]" id="{{$team->id}}" class="checkbox disable-team team_values" value="{{$team->id}}" >
                <span class="checkmark"></span>
            </label>

            {{-- <div>
                <input type="checkbox" id="{{$team->id}}" name="teams[]" class="checkbox disable-team team_values" value="{{$team->id}}" class="disable-team">
                <label for="{{$team->id}}">{{empty($team->name) ? '' : (strlen($team->name) > 20 ? substr($team->name, 0, 20).'...' : $team->name)}}</label>
            </div> --}}
        @endif
    <!-- @endif -->
@endforeach
