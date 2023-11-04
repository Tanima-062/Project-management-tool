@foreach($teams as $team)
    <div class="accordion accordion-flush" id="accordionFlushExample-{{$team->id}}">
        <div class="accordion-item">
            <h2 class="accordion-header individual-teams" id="flush-headingOne-{{$team->id}}" team_id="{{$team->id}}">
                <button class="accordion-button collapsed disable-individual" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne-{{$team->id}}" aria-expanded="false" aria-controls="flush-collapseOne-{{$team->id}}">
                    {{$team->name}}
                </button>
            </h2>
            <div id="flush-collapseOne-{{$team->id}}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne-{{$team->id}}" data-bs-parent="#accordionFlushExample-{{$team->id}}">
                <div class="accordion-body">
                    <div style="overflow-y: auto; height: 100px;">
                        <div class="all-div">
                            <label class="container" for="{{$team->id}}-all">
                                All
                                <input type="checkbox" id="{{$team->id}}-all" class="checkbox checkbox-all individual_values">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        @foreach($team->members as $member)
                            @if(isset($member->user->name) && ($member->user->status > 0) && ($member->user->id != \Illuminate\Support\Facades\Auth::user()['id']))
                                @php $hasAssignee = \App\Modals\Task::hasAssignee($task->assignees, $member); @endphp
                                <div class="{{$team->id}}-all">
                                    <label class="container" for="{{$team->id}}{{$member->user_id}}">
                                        {{empty($member->user->name) ? '' : $member->user->name}}{{empty($member->user->email) ? '' : (' (' . $member->user->email . ')')}}
                                        <input type="checkbox" value="{{$member->team_id}}" style="display: none" class="checkbox" {{$hasAssignee}}>
                                        <input type="checkbox" id="{{$team->id}}{{$member->user_id}}" class="checkbox checkbox-member individual-{{$team->id}}" value="{{$member->user_id}}" {{$hasAssignee}}>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach