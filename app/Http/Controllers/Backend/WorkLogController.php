<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modals\Task;
use App\Modals\WorkLog;
use App\Modals\Week;
use App\Modals\User;
use App\Modals\Team;
use App\Modals\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DB;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WorkLogsExport;

class WorkLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Session::has('worklog.filter')){
            Session::forget('worklog.filter');
        }

        $weeks = Week::orderBy('id','desc')->get();
        $teams = Team::all();
        $userIds = Member::where('team_id',$teams[0]->id)->groupBy('user_id')->pluck('user_id')->toArray();
        $users = User::whereIn('id',$userIds)->where('status',1)->get();
        $weekId = $weeks[0]->id;
        return view('backend.work_logs.index',compact('users','weeks','teams','weekId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auth_user_id = Auth::user()['id'];
        $tasks = Task::with(['assignees'=> function($q) use ($auth_user_id){
                    $q->where(['user_id' => $auth_user_id, 'request_status' => 'accepted'])
                    ->where('task_status','!=', 2);
                }])
                ->get();
        foreach ($tasks as $key => $task){
            if(count($task->assignees) == 0){
                unset($tasks[$key]);
            }
        }
        return view('backend.work_logs.create',compact('tasks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auth_user_id = Auth::user()['id'];
        $work_logs = [];
        $times = array_filter($request->times);


        $total_time = array_sum($times); 
       
        for($i=1; $i<=count($times); $i++){
            if(isset($times[$i])){
                $dates = explode('-', date('N-W-Y-D',strtotime($request->dates[$i])));  
        
                if($dates[3] == 'Sun'){
                    $dates[1] = $dates[1]+1;
                }
                
                $week = Week::where(['week_number'=>$dates[1],'year'=>$dates[2]])->first();

                if(is_null($week)){
                    $dto = new DateTime();
                    $start_date = $dto->setISODate($dates[2], $dates[1])->modify('-1 days')->format('Y-m-d');
                    $end_date = $dto->modify('+4 days')->format('Y-m-d');

                    $week = new Week();
                    $week->week_number = $dates[1];
                    $week->year = $dates[2];
                    $week->start_date = $start_date;
                    $week->end_date = $end_date;
                    $week->save();
                }
                array_push($work_logs,['task_id'=>$request->tasks[$i],'user_id'=>$auth_user_id,'date'=>date('Y-m-d',strtotime($request->dates[$i])),'week_id' => $week->id,'time'=>$times[$i],'total_time' => $total_time, 'summery' => $request->summery[$i]]);

            }
        }
        DB::table('work_logs')->insert($work_logs);
        session()->flash('success', 'Work Log has been saved!');
        return redirect()->route('worklogs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($date)
    {
        $worklogs = WorkLog::where('date',$date)->get();
        return view('backend.work_logs.view',compact('worklogs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($date)
    {
        $auth_user_id = Auth::user()['id'];
        $work_logs = WorkLog::where(['date' => $date, 'user_id' => $auth_user_id])->get();
        $tasks = Task::with(['assignees'=> function($q) use ($auth_user_id){
                    $q->where(['user_id' => $auth_user_id, 'request_status' => 'accepted'])
                    ->where('task_status','!=', 2);
                }])
                ->get();
        foreach ($tasks as $key => $task){
            if(count($task->assignees) == 0){
                unset($tasks[$key]);
            }
        }
        return view('backend.work_logs.edit',compact('work_logs','tasks','date'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $auth_user_id = Auth::user()['id'];
        $work_logs = [];
        $times = array_filter($request->times);
    
        $total_time = array_sum($times); 
       
        for($i=1; $i<=count($times); $i++){
            if(isset($times[$i])){
                $dates = explode('-', date('N-W-Y-D',strtotime($request->dates[$i])));  
        
                if($dates[3] == 'Sun'){
                    $dates[1] = $dates[1]+1;
                }
                
                $week = Week::where(['week_number'=>$dates[1],'year'=>$dates[2]])->first();

                if(is_null($week)){
                    $dto = new DateTime();
                    $start_date = $dto->setISODate($dates[2], $dates[1])->modify('-1 days')->format('Y-m-d');
                    $end_date = $dto->modify('+4 days')->format('Y-m-d');

                    $week = new Week();
                    $week->week_number = $dates[1];
                    $week->year = $dates[2];
                    $week->start_date = $start_date;
                    $week->end_date = $end_date;
                    $week->save();
                }
                array_push($work_logs,['task_id'=>$request->tasks[$i],'user_id'=>$auth_user_id,'date'=>date('Y-m-d',strtotime($request->dates[$i])),'week_id' => $week->id,'time'=>$times[$i],'total_time' => $total_time,'summery' => $request->summery[$i]]);
            }
        }
        DB::table('work_logs')->where('date',$request->date)->delete();
        DB::table('work_logs')->insert($work_logs);
        session()->flash('success', 'Work Log has been updated!');
        return redirect()->route('worklogs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $worklog = WorkLog::find($request->id);
        if(!is_null($worklog)){
            DB::table('work_logs')->where('date',$worklog->date)->where('user_id',$worklog->user_id)->delete();
        }
        session()->flash('success', 'Work Log has been deleted!');
        return response()->json( array('msg' => 'WorkLog has been deleted') );
    }

    public function search(Request $request){
        Session::put('worklog.filter', $request->all());

        $weekId = $request->weekId;
        $week = Week::find($request->weekId);
        $start_date = date('d M, Y',strtotime($week->start_date));
        $end_date = date('d M, Y',strtotime($week->end_date));
        $userIds = Member::where('team_id',$request->teamId)->groupBy('user_id')->pluck('user_id')->toArray();
        if(isset($request->search_key)){
            $ids = User::where('name','like','%'.$request->search_key.'%')->groupBy('id')->pluck('id')->toArray();
            $resultIds = [];
            foreach($ids as $id){
                if(in_array($id,$userIds)){
                    array_push($resultIds,$id);
                }
            }

            $userIds = $resultIds;
        }
        $users = User::whereIn('id',$userIds)->where('status',1)->get();
        return response()->json( array('success' => true, 'start_date' => $start_date, 'end_date' => $end_date, 'view'=>view('backend.work_logs.fetch_work_log',compact('users','weekId'))->render()) );
    } 

    public function export(){
        return Excel::download(new WorkLogsExport,'worklogs.xlsx');
    }
}
