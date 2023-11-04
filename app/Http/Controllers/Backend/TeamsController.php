<?php

namespace App\Http\Controllers\Backend;

use App\Exports\TeamsExport;
use App\Http\Controllers\Base\BaseController;
use App\Modals\Member;
use App\Modals\MemberTask;
use App\Modals\Team;
use App\Modals\Task;
use App\Modals\TeamTask;
use App\Modals\User;
use App\Models\TeamManagement\Team as TeamManagementTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Enums\Notification\NotificationCodeEnum;
use App\Models\Notification\Notifier;
use App\Services\Notification\NotifierService;
class TeamsController extends BaseController
{

    function __construct()
    {
        $team = new TeamManagementTeam();
        $this->entityInstance = $team;
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $teams = Team::all();
        return view('backend.teams.list', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.teams.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // $request->validate([
        //     'name' => 'required|max:100||unique:teams,name,NULL, deleted_at,deleted_at,NULL',
        // ]);

        $teams = array_map('trim', explode(',', $request->teams[1]));
        $team_array = [];
        foreach($teams as $team){
            array_push($team_array,['name'=>$team]);
        }
        if(DB::table('teams')->insert($team_array)){
            session()->flash('success','Team has been created!');
        }else{
            session()->flash('error','Something went wrong!');
        }
        return redirect()->route('teams.index');
    }


    public function show(Request $request, $id){
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        $accepted_tasks = Team::join('team_tasks', 'team_tasks.team_id', '=' ,'teams.id')
            ->join('tasks', 'tasks.id', '=', 'team_tasks.task_id')
            ->join('users', 'users.id', '=', 'tasks.assign_by_id')
            ->where('teams.id', $id)
            ->select('tasks.id', 'tasks.id as task_id', 'title', 'users.name as assigned_name', 'end_date', 'tasks.status as task_status')->paginate(12);

        return view('backend.tasks.team_task_table_list_view', compact('accepted_tasks'));
    }

    public function getTeamTask(Request $request, $id){
        $accepted_tasks = Team::join('team_tasks', 'team_tasks.team_id', '=' ,'teams.id')
            ->join('tasks', 'tasks.id', '=', 'team_tasks.task_id')
            ->join('users', 'users.id', '=', 'tasks.assign_by_id')
            ->where('teams.id', $id)
            ->select('tasks.id', 'tasks.id as task_id', 'title', 'users.name as assigned_name', 'end_date', 'tasks.status as task_status', 'description')->paginate(12);

        return view('backend.tasks.team_task_table_list_view', compact('accepted_tasks', 'notifications'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $team = Team::find($id);
        return view('backend.teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'name' => 'required|max:100||unique:teams,name,'.$id.',id,deleted_at,NULL',
        // ]);

        $team = Team::find($id);
        $team->name = $request->name;
        $team->save();
        session()->flash('success', 'Team has been updated!');
        return redirect()->route('teams.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $team = Team::find($id);
        if (!is_null($team)) {
            DB::table('members')->where('team_id', $id)->delete();
            $team->delete();
        }
        TeamTask::where(['team_id' => $id])->delete();
        MemberTask::where(['team_id' => $id])->delete();
        
        $tasks = Task::with('teamTasks','assignees')->get();

        foreach($tasks as $task){
            if(($task->assign_to == 'team') && (count($task->teamTasks) == 0)){
                Task::where(['id' => $task->id])->delete();
            }
        }
        session()->flash('success','Team has been deleted!');
        return 'deleted';
    }
    public function export(Request $request)
    {
        return Excel::download(new TeamsExport, 'teams.xlsx');
    }
    public function cardView()
    {
        $teams = Team::all();
        $notifications = Notifier::getNotifier(Auth::user()['id']);
        return view('backend.teams.card_view', compact('teams', 'notifications'));
    }

    public function search(Request $request)
    {
        if ($request['query']) {
            $teams = Team::with('teamLead', 'members')->where('name','like','%'.$request['query'].'%')->get();
        } else {
            $teams = Team::with('teamLead', 'members')->get();
        }
        if(isset($request['page']) && ($request['page'] == 'reassign_team')){
            $task = Task::with('assignees','teamTasks')->where(['id' => $request['taskId']])->first();
            return response()->json(array('success' => true, 'view' => view('backend.tasks.fetch_team_reassign', ['task' => $task,'teams' => $teams])->render()));
        }
        elseif(isset($request['page']) && ($request['page'] == 'page_teamLead')){
            return response()->json(array('success' => true, 'view' => view('backend.tasks.fetch_teamLead', ['teams' => $teams])->render()));
        }
        elseif(isset($request['page']) && ($request['page'] == 'page_teamMember')){
            return response()->json(array('success' => true, 'view' => view('backend.tasks.fetch_teamMember', ['teams' => $teams])->render()));
        }
        elseif((isset($request['order'])) && (isset($request['page'])) && ($request['page'] == 'team-list')){
            if ($request['query']) {
                $request->order_by = $request['order'];
                $request->order_by_field = 'name';
                $query = '&|:members__user_id='.Auth::user()->id.'|create_by_id='.Auth::user()->id;
                $request->special_query = 'unique:id';
                $request->filters = $query.','.$request['query'];
                $teams = parent::index($request);
                $request->per_page = 8;
                $teamsForList = parent::index($request);
            } else {
                $request->per_page = $request->per_page ? $request->per_page : 8;
                $request->order_by = $request['order'];
                $request->order_by_field = 'name';
                $query = '&|:members__user_id='.Auth::user()->id.'|create_by_id='.Auth::user()->id;
                $request->special_query = 'unique:id';
                $request->filters = $query;
                $teams = parent::index($request);
                $request->per_page = 8;
                $teamsForList = parent::index($request);
            }
            return response()->json(array('success' => true, 'view' => view('backend.teams.team_table', ['teams' => $teams,'teamsForList' => $teamsForList])->render()));
        }
        elseif((isset($request['order'])) && (isset($request['page'])) && ($request['page'] == 'team-card')){
            if ($request['query']) {
                $request->per_page = 8;
                $request->order_by = $request['order'];
                $request->order_by_field = 'name';
                $query = '&|:members__user_id='.Auth::user()->id.'|create_by_id='.Auth::user()->id;
                $request->special_query = 'unique:id';
                $request->filters = $query.','.$request['query'];
                $teams = parent::index($request);
                $teamsForList = parent::index($request);
            } else {
                $request->per_page = 8;
                $request->order_by = $request['order'];
                $query = '&|:members__user_id='.Auth::user()->id.'|create_by_id='.Auth::user()->id;
                $request->filters = $query;
                $request->special_query = 'unique:id';
                $request->filters = $query;
                $teams = parent::index($request);
                $teamsForList = parent::index($request);
            }
            return response()->json(array('success' => true, 'view' => view('backend.teams.team_card_view', ['teams' => $teams])->render()));
        }
        return response()->json(array('success' => true, 'view' => view('backend.tasks.fetch_team', ['teams' => $teams])->render()));
    }
    public function searchIndividual(Request $request){
        if($request['query']) {
            $query = $request['query'];
            if($request['page'] == 'team.create.teamLead' || $request['page'] == 'team.create.teamMember' || $request['page'] == 'team.edit.teamLead' || $request['page'] == 'team.edit.teamMember'){
                $auth_user_id = Auth::user()['id'];
                $team_lists = Team::with(['members.user' => function ($q) use ($auth_user_id){
                                $q->where(['id' => $auth_user_id]);
                                }])->get();
        
                $teamIds = [];         
                foreach($team_lists as $key=>$team){
                    if((count($team->members) == 0) && ($team->flag == 'custom')){
                        unset($team_lists[$key]);
                    }else{
                        array_push($teamIds,$team->id);
                    }
                }

                $members = Member::whereIn('team_id', $teamIds)->with(['user' => function ($q) use ($query){
                                $q->where('name', 'like', '%'.$query.'%');
                            }])->get();
                $memberIds = [];
                foreach($members as $key=>$member){
                    if(is_null($member->user)){
                        unset($members[$key]);
                    }else{
                        array_push($memberIds, $member->id);
                    }
                }
                
                $teams = Team::with(['members' => function ($q) use ($memberIds){
                           $q->whereIn('id',$memberIds);
                        }])->get();

                       
        
                foreach($teams as $key=>$team){
                    if(count($team->members) == 0){
                        unset($teams[$key]);
                    }
                }
                
            }else{
                $teams = Team::with(['members.user' => function($q) use ($query){
                            $q->where('name', 'like', '%'.$query.'%');
                        }])->orderBy('name', 'ASC')->get();

                foreach($teams as $keyTeam=>$team){
                    if(count($team->members)==0){
                        unset($teams[$keyTeam]);
                    }else{
                        foreach($team->members as $keyMember=>$member){
                            if(is_null($member->user)){
                                unset($team->members[$keyMember]);
                            }
                        }
                        if(count($team->members) == 0){
                            unset($teams[$keyTeam]);
                        }
                    }
                }
            }
        }else {
            if($request['page'] == 'team.create.teamLead' || $request['page'] == 'team.create.teamMember' || $request['page'] == 'team.edit.teamLead' || $request['page'] == 'team.edit.teamMember'){
                $auth_user_id = Auth::user()['id'];
                $team_lists = Team::with(['members' => function ($q) use ($auth_user_id){
                                $q->where(['user_id' => $auth_user_id]);
                             }])->get();
        
                $teamIds = [];         
                foreach($team_lists as $key=>$team){
                    if((count($team->members) == 0) && ($team->flag == 'custom')){
                        unset($team_lists[$key]);
                    }else{
                        array_push($teamIds,$team->id);
                    }
                }
                
                $teams = Team::with(['members' => function ($q) use ($teamIds){
                           $q->whereIn('team_id',$teamIds);
                        }])->get();
        
                foreach($teams as $key=>$team){
                    if(count($team->members) == 0){
                        unset($teams[$key]);
                    }
                }
                
            }else{
                $teams = Team::with('members')->orderBy('name', 'ASC')->get();
            }
        }

        if(isset($request['page']) && ($request['page'] == 'team.create.teamLead')){
            $auth_user_id = Auth::user()['id'];
            return response()->json(array('success' => true, 'view' => view('backend.teams.fetch_teamLead', ['teams' => $teams])->render()));
        }
        if(isset($request['page']) && ($request['page'] == 'team.create.teamMember')){
            return response()->json(array('success' => true, 'view' => view('backend.teams.fetch_member', ['teams' => $teams])->render()));
        }
        if(isset($request['page']) && ($request['page'] == 'team.edit.teamLead')){
            return response()->json(array('success' => true, 'view' => view('backend.teams.fetch_teamLead', ['teams' => $teams])->render()));
        }
        if(isset($request['page']) && ($request['page'] == 'team.edit.teamMember')){
            return response()->json(array('success' => true, 'view' => view('backend.teams.fetch_member', ['teams' => $teams])->render()));
        }
        else{
            return response()->json(array('success' => true, 'view' => view('backend.tasks.fetch_individual', ['teams' => $teams])->render()));
        }
    }
}
