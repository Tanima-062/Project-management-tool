<?php

namespace App\Modals;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use DB;
use Spatie\Permission\Models\Role;
use App\Modals\Task;
use App\Modals\User;
use App\Modals\UserProgram;
use App\Modals\Assignee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Modals\WorkLog;
use DateTime;

class User extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function userPrograms()
    {
        return $this->hasMany('App\Modals\UserProgram', 'user_id','id');
    }

    // shovon modification starts

    public function firstProgram()
    {
        return $this->hasOne('App\Modals\UserProgram', 'user_id','id');
    }

    public function supervisorInformation()
    {
        return $this->belongsTo('App\Modals\User', 'supervisor','id')->withTrashed();
    }

    // shovon modification ends

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getPermissionGroups(){
        $permission_groups = DB::table('permissions')->select('group_name as name')->groupBy('group_name')->get();
        return $permission_groups;
    }
    public static function getPermissionsByGroupName($group_name){
        $permissions = DB::table('permissions')->select('name','displayName','id')->where(['group_name' => $group_name])->get();
        return $permissions;
    }
    public static function roleHasPermissions($role, $permissions){
        $hasPermission = true;
        foreach ($permissions as $permission){
            if(!$role->hasPermissionTo($permission->name)){
                $hasPermission=false;
                return $hasPermission;
            }
        }
        return $hasPermission;
    }

    public static function hasProgram($program_id, $user_programs){
        $hasProgram = '';
        foreach($user_programs as $user_program){
            if($user_program->program->id == $program_id){
                $hasProgram = 'selected';
                return $hasProgram;
            }
        }
        return $hasProgram;
    }

    public static function programNameConcate($user_programs){
        $program = '';
        foreach($user_programs as $key=>$user_program){
            if($key == count($user_programs)-1 ){
                $program .= $user_program->program->name;
            }else{
                $program .= $user_program->program->name . ', ';
            }
        }
        return $program;
    }

    public static function getTeamName($user_id){
        $team_name = '';
        $userAsMember = Member::where(['user_id' => $user_id])->first();
        if(isset($userAsMember)){
            $team_name = $userAsMember->Team->name;
        }
        return $team_name;
    }

    public static function getUnits(){
        $units = [
            'Advocacy',
            'ERP',
            'ERP-Procurement',
            'Goods Procurement',
            'L&C Department',
            'MF Audit',
            'Migration Team 1',
            'Migration Team 2',
            'Migration Team 3',
            'Migration Team 4',
            'Migration Team 5',
            'Migration Team 6',
            'Partnership',
            'Partnership and Capacity',
            'Platform',
            'PMU',
            'Policy, Research and Evidence',
            'PSU 1',
            'PSU 2',
            'PSU 3',
            'Recruitment 1',
            'Recruitment 2',
            'Research Management Unit',
            'SIL Team 1',
            'SIL Team 2',
            'SIL Team 3',
            'SIL Team 4',
            'Special Projects',
            'Support Function Audit',
            'Support, Technology'
        ];
        return $units;
    }
    public static function getSuperVisors(){
        $supervisors = ['supervisor1','supervisor2','supervisor3'];
        return $supervisors;
    }
    public static function getPrograms(){
        $programs = [
            'Advocacy for Social Change (ASC)',
            'BRAC Pocurement Department (BPD)',
            'Human Resource Department',
            'Internal Audit Department (IAD)',
            'Legal & Compliance',
            'Migration Programme',
            'Partnership Strengthening Unit (PSU)',
            'Social Innovation Lab (SIL)',
            'Technology Division'
        ];
        return $programs;
    }

    public static function hasSpecificPermission($user, $permission_name){
        $user = User::find($user->id);
        $role = Role::where(['name' => $user->role])->where(['status' => 1])->first();
        $hasPermission = false;
        if(isset($role) && $role->hasPermissionTo($permission_name)) {
            $hasPermission = true;
            return $hasPermission;
        }
        return $hasPermission;
    }

    public static function taskCountByStatus($user_id){
        $task_status = ['total' => 0, 'accepted' => 0, 'inProgress' => 0, 'completed' => 0, 'overdue' => 0,'rejected' => 0, 'pending' => 0, 'allocated_time' => 0];
       
        $dates =explode('-', date('N-W-Y-D'));  
        
        if($dates[3] == 'Sun'){
            $dates[1] = $dates[1]+1;
        }
        
        $dto = new DateTime();
        $start_date = $dto->setISODate($dates[2], $dates[1])->modify('-1 days')->format('Y-m-d');
        $end_date = $dto->modify('+4 days')->format('Y-m-d');

        $task_query = Task::with(['assignees' => function($query) use ($user_id){

                            $query->where(['user_id' => $user_id]);
                    }]);
        
        if(Session::has('task_analytics.user.filter')){
            $request = Session::get('task_analytics.user.filter'); 
            if(isset($request['priority'])){
                $task_query = $task_query->where(['priority' => $request['priority']]);
            }
            if(isset($request['start_date']) && isset($request['end_date'])){
                $start_date = $request['start_date'];
                $end_date = $request['end_date'];
            }
        }            
        elseif(Session::has('task_analytics.singleUser.filter')){
            $request = Session::get('task_analytics.singleUser.filter');
            if(isset($request['start_date']) && isset($request['end_date'])){
                $start_date = $request['start_date'];
                $end_date = $request['end_date'];
            }
            // if(isset($request['search_key'])){
            //     $task_query->where('title','like','%'.$request['search_key'].'%');
            //     $task_query->orWhere('task_id','like','%'.$request['search_key'].'%');
            // }
        }
        $task_query->whereBetween('start_date',[ date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))])
            ->whereBetween('end_date', [ date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
        
        $tasks = $task_query->get();
        
        foreach ($tasks as $key => $task){
            foreach($task->assignees as $assignee){
                if($assignee->request_status == 'rejected'){
                    $task_status['rejected']++;
                }
                elseif($assignee->request_status == 'pending'){
                    $task_status['total']++;
                    $task_status['pending']++;
                    if(isset($task->allocated_time)){
                        $task_status['allocated_time'] = $task_status['allocated_time'] + $task->allocated_time;
                    }
                    if(($task->end_date < date('Y-m-d')) && ($assignee->task_status < 2) ){
                        $task_status['overdue']++;
                    }
                    
                }
                elseif($assignee->request_status == 'accepted'){
                    $task_status['total']++;
                    if(isset($task->allocated_time)){
                        $task_status['allocated_time'] = $task_status['allocated_time'] + $task->allocated_time;
                    }
                    if(($task->end_date < date('Y-m-d')) && ($assignee->task_status < 2) ){
                        $task_status['overdue']++;
                    }
                    if($assignee->task_status == 0){
                        $task_status['accepted']++;
                    }
                    elseif($assignee->task_status == 1){
                        $task_status['inProgress']++;
                    }
                    elseif($assignee->task_status == 2){
                        $task_status['completed']++;
                    } 
                }        
            }
        }
        return $task_status;
    }

    public static function overallTaskStatus(){
        $auth_user_id = Auth::user()['id'];
    
        $programIds = UserProgram::where(['user_id' => $auth_user_id])->groupBy('program_id')->pluck('program_id')->toArray();
        $userIds = UserProgram::whereIn('program_id', $programIds)->groupBy('user_id')->pluck('user_id')->toArray();
        $memberIds = User::whereIn('id',$userIds)->where(['status' => 1])->pluck('id')->toArray();
        
        $dates =explode('-', date('N-W-Y-D'));  
        
        if($dates[3] == 'Sun'){
            $dates[1] = $dates[1]+1;
        }
        
        $dto = new DateTime();
        $start_date = $dto->setISODate($dates[2], $dates[1])->modify('-1 days')->format('Y-m-d');
        $end_date = $dto->modify('+4 days')->format('Y-m-d');

        $task_query = Task::with(['assignees' => function($q) use ($memberIds){
                            $q->whereIn('user_id',$memberIds);
                        }])->whereBetween('start_date',[ date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))])
                        ->whereBetween('end_date', [ date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);                
        if(Session::has('task_analytics.user.filter')){
            $request = Session::get('task_analytics.user.filter');
            if(isset($request['start_date']) && isset($request['end_date'])){
                 $task_query->whereBetween('start_date',[ date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))]);
                 $task_query->whereBetween('end_date',[ date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))]);
                // $task_query->where('end_date', '<=',  date('Y-m-d', strtotime($request['end_date'])));
                //$task_query->where('start_date', '>=',  date('Y-m-d', strtotime($request['start_date'])));
            }
            elseif(isset($request['start_date'])){
               $task_query->where('start_date', '>=',  date('Y-m-d', strtotime($request['start_date'])));
            }
            elseif(isset($request['end_date'])){
                $task_query->where('end_date', '<=',  date('Y-m-d', strtotime($request['end_date'])));
            }
        }
       
        $tasks = $task_query->get();

        $overall_task_status =
        [
            'programs' => 0,
            'members' => 0,
            'tasks' => 0,
            'pending' => 0,
            'accepted' => 0,
            'inProgress' => 0,
            'completed' => 0,
            'rejected' => 0,
            'overdue' => 0
        ];

        foreach($tasks as $task){
            $flag = 0;
            foreach($task->assignees as $assignee){
                if(in_array($assignee->user_id, $memberIds)){
                    if($assignee->request_status == 'rejected'){
                        $overall_task_status['rejected']++;
                    }
                    elseif($assignee->request_status == 'pending'){
                        $flag = 1;
                        // if(($task->end_date < date('Y-m-d')) && ($task->status < 2) ){
                        //     $overall_task_status['overdue']++;
                        // }
                    }
                    elseif($assignee->request_status == 'accepted'){
                        $flag = 1;
                        // if(($task->end_date < date('Y-m-d')) && ($task->status < 2) ){
                        //     $overall_task_status['overdue']++;
                        // }
                    }
                }
            }
            if((count($task->assignees) > 0) && ($flag == 1)){
                $overall_task_status['tasks']++;
                if(($task->end_date < date('Y-m-d')) && ($task->status < 2) ){
                    $overall_task_status['overdue']++;
                }
                if($task->status == 0){
                    $overall_task_status['accepted']++;
                }elseif($task->status == 1){
                    $overall_task_status['inProgress']++;
                }elseif($task->status == 2){
                    $overall_task_status['completed']++;
                }elseif($task->status == -1){
                    $overall_task_status['pending']++;
                }
            }
        }
        $overall_task_status['programs'] = count($programIds);
        $overall_task_status['members'] = count($memberIds);

        return $overall_task_status;

    }

    public static function orderByColoumn($user_ids,$coloumn,$order){
        $rows = [];
        $users = User::whereIn('id',$user_ids)->get();
        foreach($users as $user){
            $program = User::programNameConcate($user->userPrograms);
            $task_status = User::taskCountByStatus($user->id);
            $spend_time = WorkLog::getTotalSpendTime($user->id);
            array_push($rows,['id' => $user->id,
                      'program' => $program,
                      'total' => $task_status['total'],
                      'accepted' => $task_status['overdue'],
                      'inProgress' => $task_status['inProgress'],
                      'completed' => $task_status['completed'],
                      'overdue' => $task_status['overdue'],
                      'allocated_time' => $task_status['allocated_time'],
                      'spend_time' => $spend_time
                    ]);
        }

        $flag = array_column($rows, $coloumn);
        if($order == 'asc'){
            array_multisort($flag, SORT_ASC, $rows);
        }else{
            array_multisort($flag, SORT_DESC, $rows);
        }
        $sortedIds = array_column($rows, 'id');
        return $sortedIds;
    }

    public static function summeryReport($user_id){
        $start_date = Session::get('start_date');
        $end_date   = Session::get('end_date');

        $task_status = ['created'=>0, 'accepted' => 0, 'rejected' => 0];
        $taskIds = Task::whereBetween('start_date',[ date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))])->where('end_date', '<=',  date('Y-m-d', strtotime($end_date)))->pluck('id')->toArray();
        $task_status['accepted'] = Assignee::whereIn('task_id',$taskIds)->where(['user_id' => $user_id])->where(['request_status' => 'accepted'])->count();
        $task_status['rejected'] = Assignee::whereIn('task_id',$taskIds)->where(['user_id' => $user_id])->where(['request_status' => 'rejected'])->count();
        $task_status['created'] = Task::whereIn('id',$taskIds)->where(['assign_by_id' => $user_id])->count();
        return $task_status;
    }

}
