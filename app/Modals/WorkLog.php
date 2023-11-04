<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use App\Modals\Task;
use App\Modals\Week;
use Illuminate\Support\Facades\Session;

class WorkLog extends Model
{
    public function task()
    {
        return $this->belongsTo('App\Modals\Task', 'task_id', 'id');
    }
    public function taskWithTrashed()
    {
        return $this->belongsTo('App\Modals\Task', 'task_id','id')->withTrashed();
    }
    public function week()
    {
        return $this->belongsTo('App\Modals\Week', 'week_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('App\Modals\User', 'user_id', 'id');
    }

    public function userWithTrashed()
    {
        return $this->belongsTo('App\Modals\User', 'user_id','id')->withTrashed();
    }

    public static function getWorkLogs($userId,$weekId){
        $dates = WorkLog::distinct('date')->where(['user_id'=>$userId, 'week_id' => $weekId])->pluck('date')->toArray();
        $totalTasks = WorkLog::distinct('task_id')->where(['user_id'=>$userId, 'week_id' => $weekId])->pluck('task_id')->toArray();
        $sunTasks = [];
        $monTasks = [];
        $tueTasks = [];
        $wedTasks = [];
        $thuTasks = [];
        $totalTime = 0;
        $day = null;
        foreach($dates as $date){
            $day = date("D", strtotime($date));
            if($day == 'Sun'){
                $suns = WorkLog::where(['user_id'=>$userId, 'week_id' => $weekId, 'date' => $date])->get();
                foreach($suns as $sunTask){
                    if(isset($sunTask->Task->id)){
                        array_push($sunTasks,['task' => $sunTask, 'time' => $sunTask->time]);
                        $totalTime = $totalTime + $sunTask->time;
                    }
                }
            }
            elseif($day == 'Mon'){
                $mons = WorkLog::where(['user_id'=>$userId, 'week_id' => $weekId, 'date' => $date])->get();
                foreach($mons as $monTask){
                    if(isset($monTask->Task->id)){
                        array_push($monTasks,['task' => $monTask, 'time' => $monTask->time]);
                        $totalTime = $totalTime + $monTask->time;
                    }
                }
            }
            elseif($day == 'Tue'){
                $tues = WorkLog::where(['user_id'=>$userId, 'week_id' => $weekId, 'date' => $date])->get();
                foreach($tues as $tueTask){
                    if(isset($tueTask->Task->id)){
                        array_push($tueTasks,['task' => $tueTask, 'time' => $tueTask->time]);
                        $totalTime = $totalTime + $tueTask->time;
                    }
                }
            }
            elseif($day == 'Wed'){
                $weds = WorkLog::where(['user_id'=>$userId, 'week_id' => $weekId, 'date' => $date])->get();
                foreach($weds as $wedTask){
                    if(isset($wedTask->Task->id)){
                        array_push($wedTasks,['task' => $wedTask, 'time' => $wedTask->time]);
                        $totalTime = $totalTime + $wedTask->time;
                    }
                }
            }
            elseif($day == 'Thu'){
                $thus = WorkLog::where(['user_id'=>$userId, 'week_id' => $weekId, 'date' => $date])->get();
                foreach($thus as $thuTask){
                    if(isset($thuTask->Task->id)){
                        array_push($thuTasks,['task' => $thuTask, 'time' => $thuTask->time]);
                        $totalTime = $totalTime + $thuTask->time;
                    }
                }
            }
        
        }

        $maxRow = max(count($sunTasks),count($monTasks),count($tueTasks),count($wedTasks),count($thuTasks));
        $worklogs = ['maxRow'=>$maxRow,'sunTasks'=>$sunTasks,'monTasks'=>$monTasks,'tueTasks'=>$tueTasks,'wedTasks'=>$wedTasks,'thuTasks'=>$thuTasks,'totalTasks' => count($totalTasks), 'totalTime' => $totalTime];
        return $worklogs;
    }

    public static function getSpendTime($user_id, $task_id){
        $worklogs = WorkLog::where('user_id',$user_id)->where('task_id',$task_id)->get();
        $spend_time = 0;
        foreach($worklogs as $worklog){
            $spend_time = $spend_time + $worklog->time;
        }

        return '0';
    }

    public static function getTotalSpendTimeFormat($user_id){
        $spend_time = 0;

        $dates =explode('-', date('N-W-Y-D'));  
        
        if($dates[3] == 'Sun'){
            $dates[1] = $dates[1]+1;
        }

        $dto = new DateTime();
        $start_date = $dto->setISODate($dates[2], $dates[1])->modify('-1 days')->format('Y-m-d');
        $end_date = $dto->modify('+4 days')->format('Y-m-d');

        $task_query = Task::with(['assignees' => function($query) use ($user_id){
                        $query->where('user_id', $user_id);
                    }]);
        $taskIds = [];

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
        }

        $taskIds = $task_query->whereBetween('start_date',[ date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))])
                ->whereBetween('end_date', [date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))])->groupBy('id')->pluck('id')->toArray();
        

        $worklogs = WorkLog::where('user_id',$user_id)->whereIn('task_id',$taskIds)->get();

        foreach($worklogs as $worklog){
            $spend_time = $spend_time + $worklog->time;
        }

        if($spend_time > 0){
            $time = sprintf('%02d:%02d', (int) $spend_time, fmod($spend_time, 1) * 60);
            return $time;
        }

        return '00:00';
    }

    public static function getTotalSpendTime($user_id){
        $dates =explode('-', date('N-W-Y-D'));  
        
        if($dates[3] == 'Sun'){
            $dates[1] = $dates[1]+1;
        }
        
        $dto = new DateTime();
        $start_date = $dto->setISODate($dates[2], $dates[1])->modify('-1 days')->format('Y-m-d');
        $end_date = $dto->modify('+4 days')->format('Y-m-d');

        $task_query = Task::with(['assignees' => function($query) use ($user_id){
            $query->where('user_id', $user_id);
        }]);
        $taskIds = [];

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
        }

        $taskIds = $task_query->groupBy('id')->pluck('id')->toArray();

        $worklogs = WorkLog::where('user_id',$user_id)->whereBetween('date',[ date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))])->groupBy('task_id')->get();
        
        $spend_time = 0;
        foreach($worklogs as $worklog){    
            $spend_time = $spend_time + $worklog->time;
        }
        return $spend_time;
    }

    public static function getTasksForExport($user_id, $week_id){
        $week = Week::find($week_id);
        $worklogs = WorkLog::where(['user_id'=>$user_id, 'week_id' => $week_id])->get();
        $result = [];

        foreach($worklogs as $worklog){
           array_push($result, ['week_number' => $week->week_number, 'user' => $worklog->userWithTrashed->name, 'task' => $worklog->taskWithTrashed->title, 'date' => $worklog->date, 'time' => $worklog->time]);
        }
        
        return $result;
    }

}
