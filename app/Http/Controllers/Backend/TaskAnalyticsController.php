<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modals\User;
use App\Modals\Program;
use App\Modals\Task;
use App\Modals\Member;
use App\Modals\Assignee;
use App\Modals\UserProgram;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TaskAnalyticsUserExport;
use App\Exports\TaskAnalyticsSingleUserExport;
use App\Exports\TaskAnalyticsMemberExport;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\Paginator;
use App\Models\Notification\Notifier;
use Illuminate\Support\Facades\DB;
use App\Modals\RequestType;
use App\Modals\Week;
use DateTime;

class TaskAnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Session::has('task_analytics.filter')){
            Session::forget('task_analytics.filter');
        }
        if(Session::has('task_analytics.user.filter')){
            Session::forget('task_analytics.user.filter');
        }
        if(Session::has('task_analytics.singleUser.filter')){
            Session::forget('task_analytics.singleUser.filter');
        }

        if(User::hasSpecificPermission(Auth::user(),'task.analytics.show')){
            $auth_user_id = Auth::user()['id'];
            $notifications = Notifier::getNotifier($auth_user_id);
            $users = User::where(['supervisor' => $auth_user_id])->orderBy('id','desc')->paginate(10);
            $programIds = UserProgram::where(['user_id' => $auth_user_id])->groupBy('program_id')->pluck('program_id')->toArray();
            $programs = Program::whereIn('id',$programIds)->get();
            $userIds = UserProgram::where('program_id', $programIds[0])->groupBy('user_id')->pluck('user_id')->toArray();
            $memberIds = User::whereIn('id', $userIds)->pluck('id')->toArray();

            $dates =explode('-', date('N-W-Y-D'));  
        
            if($dates[3] == 'Sun'){
                $dates[1] = $dates[1]+1;
            }
            
            $dto = new DateTime();
            $start_date = $dto->setISODate($dates[2], $dates[1])->modify('-1 days')->format('Y-m-d');
            $end_date = $dto->modify('+4 days')->format('Y-m-d');

            $week = Week::where(['week_number'=>$dates[1],'year'=>$dates[2]])->first();

            if(is_null($week)){
                $week = new Week();
                $week->week_number = $dates[1];
                $week->year = $dates[2];
                $week->start_date = $start_date;
                $week->end_date = $end_date;
                $week->save();
            }
            $tasks = Task::with(['assignees' => function($q) use ($memberIds){
                        $q->whereIn('user_id',$memberIds);
                    }])->whereBetween('start_date',[ date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))])
                    ->whereBetween('end_date', [ date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))])->get();

            $assigneeIds = [];
        
            $members = User::whereIn('id',$memberIds)->orderBy('id','desc')->paginate(10);
            $overdueCount = [];
            $assigneeNames = [];
            $acceptedCount = [];
            $inProgressCount = [];
            $completedCount = [];
            // $totalTaskCount = [];
            $rejectTaskCount = [];
            $pendingTaskCount = [];

            $overall_task_count = ['tasks' => 0, 'accepted' => 0, 'inProgress' => 0, 'completed' => 0, 'pending' => 0, 'overdue' => 0];
            
            foreach($tasks as $key => $task){
                $flag = 0;
                foreach($task->assignees as $assignee){
                    if(in_array($assignee->user_id, $memberIds)){
                        if(!in_array($assignee->user_id, $assigneeIds)){
                            if(isset($assignee->User->name)){
                                array_push($assigneeIds,$assignee->user_id);
                                array_push($assigneeNames, $assignee->User->name);
                            }
                        }
                        if(in_array($assignee->user_id, $assigneeIds)){
                            $index = array_search($assignee->user_id, $assigneeIds);
                            
                            if($assignee->request_status == 'rejected'){
                                if(isset($rejectTaskCount[$index])){
                                    $rejectTaskCount[$index]++;
                                }else{
                                    $rejectTaskCount[$index]=1;
                                }
                            }
                            elseif($assignee->request_status == 'pending'){
    
                                $flag = 1;
    
                                // if(isset($totalTaskCount[$index])){
                                //     $totalTaskCount[$index]++;
                                // }else{
                                //     $totalTaskCount[$index] = 1;
                                // }
    
                                if(($task->end_date < date('Y-m-d')) && ($task->status < 2)){
                                    if(isset($overdueCount[$index])){
                                        $overdueCount[$index]++;
                                    }else{
                                        $overdueCount[$index]=1;
                                    }
                                }
                               
                                if(isset($pendingTaskCount[$index])){
                                    $pendingTaskCount[$index]++;
                                }else{
                                    $pendingTaskCount[$index] = 1;
                                }
                                
                            }
                            elseif($assignee->request_status == 'accepted'){
    
                                $flag = 1;
    
                                // if(isset($totalTaskCount[$index])){
                                //     $totalTaskCount[$index]++;
                                // }else{
                                //     $totalTaskCount[$index] = 1;
                                // }
    
                                if(($task->end_date < date('Y-m-d')) && ($task->status < 2)){
                                    if(isset($overdueCount[$index])){
                                        $overdueCount[$index]++;
                                    }else{
                                        $overdueCount[$index]=1;
                                    }
                                }
                            
                                if($assignee->task_status == 0){
                                    if(isset($acceptedCount[$index])){
                                        $acceptedCount[$index]++;
                                    }else{
                                        $acceptedCount[$index]=1;
                                    }
                                }
                                elseif($assignee->task_status == 1){
                                    if(isset($inProgressCount[$index])){
                                        $inProgressCount[$index]++;
                                    }else{
                                        $inProgressCount[$index]=1;
                                    }
                                }
                                elseif($assignee->task_status == 2){
                                    if(isset($completedCount[$index])){
                                        $completedCount[$index]++;
                                    }else{
                                        $completedCount[$index]=1;
                                    }
                                }
                            }
                        }
                    }
                }
    
                if((count($task->assignees) > 0) && ($flag == 1)){
                    $overall_task_count['tasks']++;
                    if(($task->end_date < date('Y-m-d')) && ($task->status < 2)){
                        $overall_task_count['overdue']++;
                    }
                    if($task->status == 0){
                        $overall_task_count['accepted']++;
                    }elseif($task->status == 1){
                        $overall_task_count['inProgress']++;
                    }elseif($task->status == 2){
                        $overall_task_count['completed']++;
                    }elseif($task->status == -1){
                        $overall_task_count['pending']++;
                    }
                }
                
            } 
    
            for($i=0; $i < count($assigneeIds); $i++){
               
                if(!isset($overdueCount[$i])){
                    $overdueCount[$i] = 0;
                }
                if(!isset($acceptedCount[$i])){
                    $acceptedCount[$i] = 0;
                }
                if(!isset($inProgressCount[$i])){
                    $inProgressCount[$i] = 0;
                }
                if(!isset($completedCount[$i])){
                    $completedCount[$i] = 0;
                }
            }
    
            ksort($overdueCount);
            
            $resultArray = $this->sorting($assigneeNames, $overdueCount);
    
            $overdueNames = array_column($resultArray,'name');
            $overdueCount = array_column($resultArray, 'number');
            
            ksort($acceptedCount);
            ksort($inProgressCount);
            ksort($completedCount);
    
            $taskSum = $this->addition($acceptedCount, $inProgressCount, $completedCount);
    
            $resultArray = $this->sortingStatus($assigneeNames, $taskSum, $acceptedCount, $inProgressCount, $completedCount);
            $statusNames = array_column($resultArray,'name');
            $acceptedCount = array_column($resultArray,'accepted');
            $inProgressCount = array_column($resultArray, 'inProgress');
            $completedCount = array_column($resultArray,'completed');
    
            $totalMembers = User::whereIn('id',$memberIds)->get();
            // $totalAccepted = array_sum($acceptedCount); 
            // $totalInprogress = array_sum($inProgressCount);
            // $totalCompleted = array_sum($completedCount);
    
            // $totalTask = array_sum($totalTaskCount);
            $totalRejected = array_sum($rejectTaskCount);
            $totalOverdue = array_sum($overdueCount); 
            $memberCount = count($memberIds);
    
            $serialUser = $users->perPage() * ($users->currentPage() - 1);
            $serialMember = $members->perPage() * ($members->currentPage() - 1);
            
            return view('backend.task_analytics.index',compact('notifications','users','programs','overdueNames', 'statusNames','overdueCount','acceptedCount','inProgressCount','completedCount', 'overall_task_count',
                'serialUser','serialMember' ,'assigneeIds','memberCount','totalMembers','totalRejected','totalOverdue','members','start_date','end_date'));
        }else{
            session()->flash('error', 'You are not authorized for this page!');
            return back();
        }
    }

    public function addition($acc,$inp,$com){
        $sum = [];
        for($i=0; $i < count($acc); $i++){
            $sum[$i] = $acc[$i] + $inp[$i] + $com[$i];
        }
        return $sum;
    }

    public function sorting ($assigneeNames, $task_count){
        $arr = [];
        for($i = 0; $i < count($task_count); $i++){
            if($task_count[$i] > 0){
                array_push($arr, ['name' => $assigneeNames[$i], 'number' => $task_count[$i]]);
            }
        }
        $flag = array_column($arr, 'number');
        array_multisort($flag, SORT_DESC, $arr);
        return $arr;
    }

    public function sortingStatus ($assigneeNames, $total, $acc, $inp, $com){
        $arr = [];
        for($i = 0; $i < count($total); $i++){
            if($total[$i] > 0){
                array_push($arr, ['name' => $assigneeNames[$i],
                'number' => $total[$i], 
                'accepted'=> $acc[$i], 
                'inProgress' => $inp[$i],
                'completed' => $com[$i]
                ]);
            }
        }
        $flag = array_column($arr, 'number');
        array_multisort($flag, SORT_DESC, $arr);
        return $arr;
    }


    public function fetchData(Request $request){
        Session::put('task_analytics.filter', $request->all());
        $userIds = UserProgram::where(['program_id' => $request->program_id])->groupBy('user_id')->pluck('user_id')->toArray();
        $memberIds = User::whereIn('id',$userIds)->pluck('id')->toArray();
        $tasks = Task::with(['assignees' => function($q) use ($memberIds){
                    $q->whereIn('user_id',$memberIds);
                }])->get();

        $assigneeIds = [];

        $members = User::whereIn('id',$memberIds)->orderBy('id','desc')->paginate(10);
        $overdueCount = [];
        $assigneeNames = [];
        $acceptedCount = [];
        $inProgressCount = [];
        $completedCount = [];
        // $totalTaskCount = [];
        $rejectTaskCount = [];
        $pendingTaskCount = [];

        $overall_task_count = ['tasks' => 0, 'accepted' => 0, 'inProgress' => 0, 'completed' => 0, 'pending' => 0, 'overdue' => 0];

        foreach($tasks as $key => $task){
            $flag = 0;
            foreach($task->assignees as $assignee){
                if(in_array($assignee->user_id, $memberIds)){
                    if(!in_array($assignee->user_id, $assigneeIds)){
                        if(isset($assignee->User->name)){
                            array_push($assigneeIds,$assignee->user_id);
                            array_push($assigneeNames, $assignee->User->name);
                        }
                    }
                    if(in_array($assignee->user_id, $assigneeIds)){
                        $index = array_search($assignee->user_id, $assigneeIds);
                        
                        if($assignee->request_status == 'rejected'){
                            if(isset($rejectTaskCount[$index])){
                                $rejectTaskCount[$index]++;
                            }else{
                                $rejectTaskCount[$index]=1;
                            }
                        }
                        elseif($assignee->request_status == 'pending'){

                            $flag = 1;

                            // if(isset($totalTaskCount[$index])){
                            //     $totalTaskCount[$index]++;
                            // }else{
                            //     $totalTaskCount[$index] = 1;
                            // }

                            if(($task->end_date < date('Y-m-d')) && ($task->status < 2)){
                                if(isset($overdueCount[$index])){
                                    $overdueCount[$index]++;
                                }else{
                                    $overdueCount[$index]=1;
                                }
                            }
                           
                            if(isset($pendingTaskCount[$index])){
                                $pendingTaskCount[$index]++;
                            }else{
                                $pendingTaskCount[$index] = 1;
                            }
                            
                        }
                        elseif($assignee->request_status == 'accepted'){

                            $flag = 1;

                            // if(isset($totalTaskCount[$index])){
                            //     $totalTaskCount[$index]++;
                            // }else{
                            //     $totalTaskCount[$index] = 1;
                            // }

                            if(($task->end_date < date('Y-m-d')) && ($task->status < 2)){
                                if(isset($overdueCount[$index])){
                                    $overdueCount[$index]++;
                                }else{
                                    $overdueCount[$index]=1;
                                }
                            }
                        
                            if($assignee->task_status == 0){
                                if(isset($acceptedCount[$index])){
                                    $acceptedCount[$index]++;
                                }else{
                                    $acceptedCount[$index]=1;
                                }
                            }
                            elseif($assignee->task_status == 1){
                                if(isset($inProgressCount[$index])){
                                    $inProgressCount[$index]++;
                                }else{
                                    $inProgressCount[$index]=1;
                                }
                            }
                            elseif($assignee->task_status == 2){
                                if(isset($completedCount[$index])){
                                    $completedCount[$index]++;
                                }else{
                                    $completedCount[$index]=1;
                                }
                            }
                        }
                    }
                }
            }

            if((count($task->assignees) > 0) && ($flag == 1)){
                $overall_task_count['tasks']++;
                if(($task->end_date < date('Y-m-d')) && ($task->status < 2)){
                    $overall_task_count['overdue']++;
                }
                if($task->status == 0){
                    $overall_task_count['accepted']++;
                }elseif($task->status == 1){
                    $overall_task_count['inProgress']++;
                }elseif($task->status == 2){
                    $overall_task_count['completed']++;
                }elseif($task->status == -1){
                    $overall_task_count['pending']++;
                }
            }
            
        } 

        for($i=0; $i < count($assigneeIds); $i++){
           
            if(!isset($overdueCount[$i])){
                $overdueCount[$i] = 0;
            }
            if(!isset($acceptedCount[$i])){
                $acceptedCount[$i] = 0;
            }
            if(!isset($inProgressCount[$i])){
                $inProgressCount[$i] = 0;
            }
            if(!isset($completedCount[$i])){
                $completedCount[$i] = 0;
            }
        }

        ksort($overdueCount);
        
        $resultArray = $this->sorting($assigneeNames, $overdueCount);

        $overdueNames = array_column($resultArray,'name');
        $overdueCount = array_column($resultArray, 'number');
        
        ksort($acceptedCount);
        ksort($inProgressCount);
        ksort($completedCount);

        $taskSum = $this->addition($acceptedCount, $inProgressCount, $completedCount);

        $resultArray = $this->sortingStatus($assigneeNames, $taskSum, $acceptedCount, $inProgressCount, $completedCount);
        $statusNames = array_column($resultArray,'name');
        $acceptedCount = array_column($resultArray,'accepted');
        $inProgressCount = array_column($resultArray, 'inProgress');
        $completedCount = array_column($resultArray,'completed');

        $totalMembers = User::whereIn('id',$memberIds)->get();
        // $totalAccepted = array_sum($acceptedCount); 
        // $totalInprogress = array_sum($inProgressCount);
        // $totalCompleted = array_sum($completedCount);

        // $totalTask = array_sum($totalTaskCount);
        $totalRejected = array_sum($rejectTaskCount);
        $totalOverdue = array_sum($overdueCount); 
        $memberCount = count($memberIds);

        $serialMember = $members->perPage() * ($members->currentPage() - 1);
        return response()->json( array('success' => true, 'view'=> view('backend.task_analytics.chart')->render(),compact('overdueNames','statusNames','overdueCount','acceptedCount','inProgressCount','completedCount'),
        'viewM'=>view('backend.task_analytics.supervise_status',compact('assigneeIds','totalMembers','memberCount','overall_task_count','totalRejected','totalOverdue', 'serialMember','members','overdueCount','acceptedCount','inProgressCount','completedCount'))->render()));      
    }

    public function fetchMember(Request $request){
        Session::put('task_analytics.filter', $request->all());
        $userIds = UserProgram::where(['program_id' => $request->program_id])->groupBy('user_id')->pluck('user_id')->toArray();
        $memberIds = User::whereIn('id',$userIds)->where(['status' => 1])->pluck('id')->toArray();
        if(isset($request['member_query'])){
            $resultIds = User::where(['status' => 1])->where('name','like','%'.$request['member_query'].'%')->pluck('id')->toArray();
            $memberIds = array_intersect($userIds,$resultIds);
        }

        if(isset($request['coloumn'])){  
            if(($request['coloumn'] == 'id') || ($request['coloumn'] == 'name')){
                $members = User::whereIn('id',$memberIds)->orderBy($request['coloumn'], $request['order'])->paginate(10);
                if(($request['coloumn'] == 'id') && $request['order'] == 'asc'){
                    $serialMember = $members->total();
                    if($members->currentPage() > 1){
                        $serialMember = $members->total() - (($members->currentPage()-1) * $members->perPage());
                    }
                    return response()->json( array('success' => true, 'view'=>view('backend.task_analytics.member',['members' => $members,'serialMember' => $serialMember, 'sort_id' => 'asc'])->render()) );
                }
                $serialMember = $members->perPage() * ($members->currentPage() - 1);
                return response()->json( array('success' => true, 'view'=>view('backend.task_analytics.member',compact('serialMember','members'))->render()));
            }else{
                $ids = User::whereIn('id',$memberIds)->pluck('id')->toArray();
                $userIds = User::orderByColoumn($ids,$request['coloumn'],$request['order']);
                $members = User::whereIn('id',$memberIds)->orderByRaw('FIELD(id, ' .implode(",",$userIds). ')')->paginate(10);  
                $serialMember = $members->perPage() * ($members->currentPage() - 1);
                return response()->json( array('success' => true, 'view'=>view('backend.task_analytics.member',compact('serialMember','members'))->render()));
            }
        }
        
        $members = User::whereIn('id',$memberIds)->oderBy('id','desc')->paginate(10);
        
        return response()->json( array('success' => true, 'view'=>view('backend.task_analytics.member',compact('serialMember','members'))->render()));      
    }

    public function fetchUser(Request $request){
        
        Session::put('task_analytics.user.filter', $request->all());

        $auth_user_id = Auth::user()['id'];
        $user_query = User::where(['supervisor' => $auth_user_id]); 
        if(isset($request->query)){
            $user_query->where('name','like','%'.$request['query'].'%');
        }

        if(isset($request->coloumn)){  
            if(($request->coloumn == 'id') || ($request->coloumn == 'name')){
                $users = $user_query->orderBy($request->coloumn, $request->order)->paginate(10);
                if(($request->coloumn == 'id') && $request->order == 'asc'){
                    $serialUser = $users->total();
                    if($users->currentPage() > 1){
                        $serialUser = $users->total() - (($users->currentPage()-1) * $users->perPage());
                    }
                    return response()->json( array('success' => true, 'view'=>view('backend.task_analytics.user',['users' => $users,'serialUser' => $serialUser, 'sort_id' => 'asc'])->render()) );
                }
                $serialUser = $users->perPage() * ($users->currentPage() - 1);
                return response()->json( array('success' => true, 'view'=>view('backend.task_analytics.user',compact('serialUser','users'))->render()));
            }else{
                $temp_query = $user_query;
                $ids = $temp_query->pluck('id')->toArray();
                $userIds = User::orderByColoumn($ids,$request->coloumn,$request->order);
                $users = $user_query->orderByRaw('FIELD(id, ' .implode(",",$userIds). ')')->paginate(10);  
                $serialUser = $users->perPage() * ($users->currentPage() - 1);
                return response()->json( array('success' => true, 'view'=>view('backend.task_analytics.user',compact('serialUser','users'))->render()));
            }
        }
        
        $users = $user_query->orderBy('id', 'desc')->paginate(10);
        $serialUser = $users->perPage() * ($users->currentPage() - 1);
        return response()->json( array('success' => true, 'view'=>view('backend.task_analytics.user',compact('serialUser','users'))->render()));
    }

    public function export(){
        return Excel::download(new TaskAnalyticsUserExport,'users.xlsx');
    }

    public function singleUserExport($id){
        Session::put('task_analytics.user_id', $id);
        return Excel::download(new TaskAnalyticsSingleUserExport,'user.xlsx');
    }

    public function exportMember(){
        return Excel::download(new TaskAnalyticsMemberExport,'members.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Session::has('task_analytics.singleUser.filter')){
            Session::forget('task_analytics.singleUser.filter');
        }
        if(Session::has('task_analytics.user.filter')){
            Session::forget('task_analytics.user.filter');
        }
        if(User::hasSpecificPermission(Auth::user(),'task.analytics.show')){
            $auth_user_id = Auth::user()['id'];
            $notifications = Notifier::getNotifier($auth_user_id);
            Session::put('task_analytics.user_id', $id);
            $user = User::find($id);
            $tasks = Task::with(['memberTasks' => function($query) use ($id){

                            $query->where(['member_user_id' => $id])
                                ->where('request_status', '<>', 'rejected');

                    }])
                    ->with(['assignees' => function($query) use ($id){

                            $query->where(['user_id' => $id])
                                ->where('request_status','<>', 'rejected');

                    }])->where('end_date','<',date('Y-m-d'))->where('status','!=',2)->where('assign_by_id', '!=', $id)->get();

            $taskIds = [];

            foreach ($tasks as $key => $single_task){
                if(count($single_task->assignees)==0){
                    unset($tasks[$key]);
                }else{
                    array_push($taskIds,$single_task->id);
                }
            }

            $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy('id','desc')->paginate(10);
            $user_name = strlen($user->name) > 17 ? substr($user->name, 0, 17).'...' :  $user->name;
            $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
            return view('backend.task_analytics.show',compact('notifications','user','tasks_for_list', 'user_name','serial'));
        }else{
            session()->flash('error', 'You are not authorized for this page!');
            return back();
        }
    }

    public function showTask($id){
        if(User::hasSpecificPermission(Auth::user(),'task.analytics.show')){

            DB::table('temporary_files')->delete();

            $auth_user_id = Auth::user()['id'];
            $request_status = '';
            $my_task_status = 0;
            $taskIds = [];

            $task = Task::with('assignBy','assignees','attachments','comments','replies','requests')
                        ->with(['histories' => function($q){
                            $q->orderBy('id','desc');
                        }])->find($id);

            if($task->assign_to == 'team') {
                $member_task = MemberTask::where([
                                                'task_id'=>$id,
                                                'member_user_id' => $auth_user_id,
                                        ])->first();
                if($member_task){
                    $request_status = $member_task->request_status;
                    $my_task_status = $member_task->task_status;
                }
            }
            elseif($task->assign_to == 'individual') {
                $assignee_task = Assignee::where([
                                                'task_id' => $id,
                                                'user_id' => $auth_user_id,
                                        ])->first();
                if($assignee_task){
                    $request_status = $assignee_task->request_status;
                    $my_task_status = $assignee_task->task_status;

                }
            }
            $sub_tasks = Task::with('teamTasks','attachments')->where(['parent_id' => $id])->get();
            $request_types = RequestType::all();
            $notifications = Notifier::getNotifier(Auth::user()['id']);

            $tasks= Task::with(['memberTasks' => function($query) use ($auth_user_id){

                            $query->where(['member_user_id' => $auth_user_id])
                                ->where('request_status', '<>', 'rejected');

                    }])
                    ->with(['assignees' => function($query) use ($auth_user_id){

                            $query->where(['user_id' => $auth_user_id])
                                ->where('request_status','<>', 'rejected');

                    }])->get();    
            
            return view('backend.task_analytics.view',compact('task','sub_tasks', 'notifications', 'request_types', 'request_status','my_task_status'));
        }else{
            session()->flash('error', 'You are not authorized for this page!');
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function fetchTasksOfUser(Request $request){
       
        Session::put('task_analytics.singleUser.filter', $request->all());
        $auth_user_id = Session::get('task_analytics.user_id');
        $user = User::where(['id'=> $auth_user_id])->first();
        
        if(isset($request->page)){
            $currentPage = $request->page;
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
        }
        if(isset($request->assignee)){
            if($request->assignee == 'assign_to_me'){
                $task_query = Task::with(['memberTasks' => function($query) use ($auth_user_id){

                                    $query->where(['member_user_id' => $auth_user_id])
                                        ->where('request_status', '<>', 'rejected');

                            }])
                            ->with(['assignees' => function($query) use ($auth_user_id){

                                    $query->where(['user_id' => $auth_user_id])
                                        ->where('request_status','<>', 'rejected');

                            }])
                            ->where('assign_by_id','!=',$auth_user_id);
            }elseif($request->assignee == 'assign_by_me'){

                $task_query = Task::with('assignees','teamTasks','memberTasks')->where('assign_by_id', $auth_user_id);

            }elseif($request->assignee == 'assign_all'){
                $task_query = Task::with(['memberTasks' => function($query) use ($auth_user_id){

                                        $query->where(['member_user_id' => $auth_user_id])
                                            ->where('request_status', '<>', 'rejected');

                                }])
                                ->with(['assignees' => function($query) use ($auth_user_id){

                                        $query->where(['user_id' => $auth_user_id])
                                            ->where('request_status','<>', 'rejected');

                                }]);
            }
        }
        if(isset($request->status)){
            if($request->status == 3){
                $task_query->where('end_date', '<',  date('Y-m-d'))->where('status', '!=', 2);
            }elseif($request->status == 5){
                $task_query->where(['wfh' => '1']);
            }else{
                $task_query->where(['status' => $request->status]);
            }
        }
        if(isset($request->priority)){
            $task_query->where(['priority' => $request->priority]);
        }
        if(isset($request->start_date) && isset($request->end_date)){
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $task_query->where(function($q) use ($start_date, $end_date){
                $q->whereBetween('start_date',[ date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
                $q->orWhereBetween('end_date',[ date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            });
          
        }
        if(isset($request->search_key)){
            $task_query->where('title','like','%'.$request->search_key.'%');
            $task_query->orWhere('task_id','like','%'.$request->search_key.'%');
        }

        if(isset($request->assignee)){
            if($request->assignee == 'assign_to_me'){
                $tasks = $task_query->get();
                $taskIds = [];
                foreach ($tasks as $key => $task){
                    if((count($task->assignees)==0)){
                        unset($tasks[$key]);
                    }else{        
                        array_push($taskIds, $task->id);
                    }
                }
                if((isset($request->coloumn)) && ($request->coloumn == 'assign_by')){
                    $order = $request->order;
                    $tasks_for_list = Task::join('users','users.id','=','tasks.assign_by_id')
                                    ->whereIn('tasks.id',$taskIds)->orderBy('users.name',$order)->paginate(10);
    
                    $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                    return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                }
                if((isset($request->coloumn)) && ($request->coloumn != 'assign_by')){
                    $tasks_for_list = $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy($request->coloumn, $request->order)->paginate(10);
                    $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                    if($request->coloumn == 'priority'){
                        
                        if($request->order == 'asc'){
                            $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(priority, 2, 0, 1)')->paginate(10);
                            $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                            return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                        }elseif($request->order == 'desc'){
                            $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(priority, 1, 0, 2)')->paginate(10);
                            $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                            return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                        }
                    }
                    if($request->coloumn == 'status'){
                        if($request->order == 'asc'){
                            $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(status, 0, 2, 1, -1)')->paginate(10);
                            $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                            return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                        }elseif($request->order == 'desc'){
                            $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(status, -1, 1, 2, 0)')->paginate(10);
                            $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                            return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                        }
                    }
                    if(($request->coloumn == 'id') && $request->order == 'asc'){
                        $serial = $tasks_for_list->total();
                        if($tasks_for_list->currentPage() > 1){
                            $serial = $tasks_for_list->total() - (($tasks_for_list->currentPage()-1) * $tasks_for_list->perPage());
                        }
                        return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial, 'sort_id' => 'asc'])->render()) );
                    }
                    return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                }
                $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy('id', 'desc')->paginate(10);
                $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                    
            }elseif($request->assignee == 'assign_by_me'){

                $tasks =  $task_query->get();    

                $taskIds = $task_query->pluck('id')->toArray();
                if((isset($request->coloumn)) && ($request->coloumn == 'assign_by')){
                    $order = $request->order;
                    $tasks_for_list = Task::join('users','users.id','=','tasks.assign_by_id')
                                    ->where('tasks.assign_by_id', $auth_user_id)
                                    ->orderBy('users.name',$order)->paginate(10);
    
                    $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                    return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                }
                if((isset($request->coloumn)) && ($request->coloumn != 'assign_by')){
                    if($request->coloumn == 'priority'){    
                        if($request->order == 'asc'){
                            $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(priority, 2, 0, 1)')->paginate(10);
                            $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                            return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                        }elseif($request->order == 'desc'){
                            $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(priority, 1, 0, 2)')->paginate(10);
                            $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                            return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                        }
                    }
                    if($request->coloumn == 'status'){
                        if($request->order == 'asc'){
                            $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(status, 0, 2, 1, -1)')->paginate(10);
                            $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                            return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                        }elseif($request->order == 'desc'){
                            $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(status, -1, 1, 2, 0)')->paginate(10);
                            $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                            return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                        }
                    }
                    if(($request->coloumn == 'id') && $request->order == 'asc'){
                        $serial = $tasks_for_list->total();
                        if($tasks_for_list->currentPage() > 1){
                            $serial = $tasks_for_list->total() - (($tasks_for_list->currentPage()-1) * $tasks_for_list->perPage());
                        }
                        return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial, 'sort_id' => 'asc'])->render()) );
                    }
                    $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy($request->coloumn, $request->order)->paginate(10);
                    $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                    return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                }
                    

                $tasks_for_list = $task_query->orderBy('id', 'desc')->paginate(10);
                $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                    
            }elseif($request->assignee == 'assign_all'){
                $tasks = $task_query->get();
                $taskIds = [];
                foreach ($tasks as $key => $task){
                    if((count($task->assignees)==0) && ($task->assign_by_id != $auth_user_id)){
                        unset($tasks[$key]);
                    }else{ 
                        array_push($taskIds, $task->id);
                    }
                }
                if((isset($request->coloumn)) && ($request->coloumn == 'assign_by')){
                    $order = $request->order;
                    $tasks_for_list = Task::join('users','users.id','=','tasks.assign_by_id')
                                    ->whereIn('tasks.id',$taskIds)->orderBy('users.name',$order)->paginate(10);
    
                    $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                    return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                }
                if((isset($request->coloumn)) && ($request->coloumn != 'assign_by')){
                    if($request->coloumn == 'priority'){
                        
                        if($request->order == 'asc'){
                            $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(priority, 2, 0, 1)')->paginate(10);
                            $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                            return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                        }elseif($request->order == 'desc'){
                            $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(priority, 1, 0, 2)')->paginate(10);
                            $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                            return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                        }
                    }
                    if($request->coloumn == 'status'){
                        if($request->order == 'asc'){
                            $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(status, 0, 2, 1, -1)')->paginate(10);
                            $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                            return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                        }elseif($request->order == 'desc'){
                            $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderByRaw('FIELD(status, -1, 1, 2, 0)')->paginate(10);
                            $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                            return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                        }
                    }
                    if(($request->coloumn == 'id') && $request->order == 'asc'){
                        $serial = $tasks_for_list->total();
                        if($tasks_for_list->currentPage() > 1){
                            $serial = $tasks_for_list->total() - (($tasks_for_list->currentPage()-1) * $tasks_for_list->perPage());
                        }
                        return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial, 'sort_id' => 'asc'])->render()) );
                    }
                    $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy($request->coloumn, $request->order)->paginate(10);
                    $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                    return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                }

                $tasks_for_list = Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy('id', 'desc')->paginate(10);
                $serial = $tasks_for_list->perPage() * ($tasks_for_list->currentPage() - 1);
                return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.singleUser_task_status',['user' => $user])->render(),'view'=>view('backend.task_analytics.task_table',['tasks_for_list' => $tasks_for_list,'serial' => $serial])->render()) );
                    
            }
        }
    }

    public function fetchAllData(Request $request){
        Session::put('task_analytics.user.filter', $request->all());

        $auth_user_id = Auth::user()['id'];
        $user_query = User::where(['supervisor' => $auth_user_id]); 
        if(isset($request->query)){
            $user_query->where('name','like','%'.$request['query'].'%');
        }
        $users = $user_query->orderBy('id', 'desc')->paginate(10);
        $serialUser = $users->perPage() * ($users->currentPage() - 1);

        Session::put('task_analytics.filter', $request->all());
        $userIds = UserProgram::where(['program_id' => $request->program_id])->groupBy('user_id')->pluck('user_id')->toArray();
        $memberIds = User::whereIn('id',$userIds)->pluck('id')->toArray();
        if(isset($request['member_query'])){
            $resultIds = User::where(['status' => 1])->where('name','like','%'.$request['member_query'].'%')->pluck('id')->toArray();
            $memberIds = array_intersect($userIds,$resultIds);
        }
        $task_query = Task::with(['assignees' => function($q) use ($memberIds){
                    $q->whereIn('user_id',$memberIds);
                }]);
        if(isset($request->start_date) && isset($request->end_date)){
            $task_query->whereBetween('start_date',[ date('Y-m-d', strtotime($request->start_date)), date('Y-m-d', strtotime($request->end_date))]);
            $task_query->orWhereBetween('end_date', [ date('Y-m-d', strtotime($request->start_date)), date('Y-m-d', strtotime($request->end_date))]);
        }
        elseif(isset($request->start_date)){
            $task_query->where('start_date', '>=',  date('Y-m-d', strtotime($request->start_date)));
        }
        elseif(isset($request->end_date)){
            $task_query->where('end_date', '<=',  date('Y-m-d', strtotime($request->end_date)));
        }
        $tasks = $task_query->get();
        $assigneeIds = [];

        $members = User::whereIn('id',$memberIds)->orderBy('id','desc')->paginate(10);
        $overdueCount = [];
        $assigneeNames = [];
        $acceptedCount = [];
        $inProgressCount = [];
        $completedCount = [];
        // $totalTaskCount = [];
        $rejectTaskCount = [];
        $pendingTaskCount = [];

        $overall_task_count = ['tasks' => 0, 'accepted' => 0, 'inProgress' => 0, 'completed' => 0, 'pending' => 0, 'overdue' => 0];

        foreach($tasks as $key => $task){
            $flag = 0;
            foreach($task->assignees as $assignee){
                if(in_array($assignee->user_id, $memberIds)){
                    if(!in_array($assignee->user_id, $assigneeIds)){
                        if(isset($assignee->User->name)){
                            array_push($assigneeIds,$assignee->user_id);
                            array_push($assigneeNames, $assignee->User->name);
                        }
                    }
                    if(in_array($assignee->user_id, $assigneeIds)){
                        $index = array_search($assignee->user_id, $assigneeIds);
                        
                        if($assignee->request_status == 'rejected'){
                            if(isset($rejectTaskCount[$index])){
                                $rejectTaskCount[$index]++;
                            }else{
                                $rejectTaskCount[$index]=1;
                            }
                        }
                        elseif($assignee->request_status == 'pending'){

                            $flag = 1;

                            // if(isset($totalTaskCount[$index])){
                            //     $totalTaskCount[$index]++;
                            // }else{
                            //     $totalTaskCount[$index] = 1;
                            // }

                            if(($task->end_date < date('Y-m-d')) && ($task->status < 2)){
                                if(isset($overdueCount[$index])){
                                    $overdueCount[$index]++;
                                }else{
                                    $overdueCount[$index]=1;
                                }
                            }
                           
                            if(isset($pendingTaskCount[$index])){
                                $pendingTaskCount[$index]++;
                            }else{
                                $pendingTaskCount[$index] = 1;
                            }
                            
                        }
                        elseif($assignee->request_status == 'accepted'){

                            $flag = 1;

                            // if(isset($totalTaskCount[$index])){
                            //     $totalTaskCount[$index]++;
                            // }else{
                            //     $totalTaskCount[$index] = 1;
                            // }

                            if(($task->end_date < date('Y-m-d')) && ($task->status < 2)){
                                if(isset($overdueCount[$index])){
                                    $overdueCount[$index]++;
                                }else{
                                    $overdueCount[$index]=1;
                                }
                            }
                        
                            if($assignee->task_status == 0){
                                if(isset($acceptedCount[$index])){
                                    $acceptedCount[$index]++;
                                }else{
                                    $acceptedCount[$index]=1;
                                }
                            }
                            elseif($assignee->task_status == 1){
                                if(isset($inProgressCount[$index])){
                                    $inProgressCount[$index]++;
                                }else{
                                    $inProgressCount[$index]=1;
                                }
                            }
                            elseif($assignee->task_status == 2){
                                if(isset($completedCount[$index])){
                                    $completedCount[$index]++;
                                }else{
                                    $completedCount[$index]=1;
                                }
                            }
                        }
                    }
                }
            }

            if((count($task->assignees) > 0) && ($flag == 1)){
                $overall_task_count['tasks']++;
                if(($task->end_date < date('Y-m-d')) && ($task->status < 2)){
                    $overall_task_count['overdue']++;
                }
                if($task->status == 0){
                    $overall_task_count['accepted']++;
                }elseif($task->status == 1){
                    $overall_task_count['inProgress']++;
                }elseif($task->status == 2){
                    $overall_task_count['completed']++;
                }elseif($task->status == -1){
                    $overall_task_count['pending']++;
                }
            }
            
        } 

        for($i=0; $i < count($assigneeIds); $i++){
           
            if(!isset($overdueCount[$i])){
                $overdueCount[$i] = 0;
            }
            if(!isset($acceptedCount[$i])){
                $acceptedCount[$i] = 0;
            }
            if(!isset($inProgressCount[$i])){
                $inProgressCount[$i] = 0;
            }
            if(!isset($completedCount[$i])){
                $completedCount[$i] = 0;
            }
        }

        ksort($overdueCount);
        
        $resultArray = $this->sorting($assigneeNames, $overdueCount);

        $overdueNames = array_column($resultArray,'name');
        $overdueCount = array_column($resultArray, 'number');
        
        ksort($acceptedCount);
        ksort($inProgressCount);
        ksort($completedCount);

        $taskSum = $this->addition($acceptedCount, $inProgressCount, $completedCount);

        $resultArray = $this->sortingStatus($assigneeNames, $taskSum, $acceptedCount, $inProgressCount, $completedCount);
        $statusNames = array_column($resultArray,'name');
        $acceptedCount = array_column($resultArray,'accepted');
        $inProgressCount = array_column($resultArray, 'inProgress');
        $completedCount = array_column($resultArray,'completed');

        $totalMembers = User::whereIn('id',$memberIds)->get();
        // $totalAccepted = array_sum($acceptedCount); 
        // $totalInprogress = array_sum($inProgressCount);
        // $totalCompleted = array_sum($completedCount);

        // $totalTask = array_sum($totalTaskCount);
        $totalRejected = array_sum($rejectTaskCount);
        $totalOverdue = array_sum($overdueCount); 
        $memberCount = count($memberIds);

        $serialMember = $members->perPage() * ($members->currentPage() - 1);

        return response()->json( array('success' => true, 'viewS'=>view('backend.task_analytics.status_card')->render(), 'viewU' =>view('backend.task_analytics.user',compact('serialUser','users'))->render(), 'view'=> view('backend.task_analytics.chart')->render(),compact('overdueNames','statusNames','overdueCount','acceptedCount','inProgressCount','completedCount'),
        'viewM'=>view('backend.task_analytics.supervise_status',compact('assigneeIds','totalMembers','memberCount','overall_task_count','totalRejected','totalOverdue', 'serialMember','members','overdueCount','acceptedCount','inProgressCount','completedCount'))->render()));
    }
}
