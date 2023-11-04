<?php

namespace App\Exports;

use App\Modals\Task;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class TaskByTeamExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $auth_user_id = Auth::user()['id'];
        $team_id = Session::get('taskByTeam.team_id');
        $task_query = Task::with(['teamTasks' => function($query) use ($team_id){
                            $query->where(['team_id' => $team_id]);
                      }]);

        if(Session::has('taskByTeam.filter')){
            $request = Session::get('taskByTeam.filter');
            if(isset($request['assignee'])){
                if($request['assignee'] == 'assign_to_me'){
                    $task_query = Task::with(['memberTasks' => function($query) use ($auth_user_id,$team_id){

                                        $query->where(['member_user_id' => $auth_user_id])
                                            ->where('request_status', '<>', 'rejected')
                                            ->where(['team_id' => $team_id]);

                                }])
                                ->where('assign_by_id','!=',$auth_user_id);
                }elseif($request['assignee'] == 'assign_by_me'){

                        $task_query = Task::with(['teamTasks' => function($query) use ($team_id){
                                        $query->where(['team_id' => $team_id]);
                                    }])->where(['assign_by_id' => $auth_user_id]);

                }elseif($request['assignee'] == 'assign_all'){
                    $task_query = Task::with(['teamTasks' => function($query) use ($team_id){
                                        $query->where(['team_id' => $team_id]);
                                }]);
                }
            }


            if(isset($request['status'])){
                $task_query->where(['status' => $request['status']]);
            }
            if(isset($request['priority'])){
                $task_query->where(['priority' => $request['priority']]);
            }
            if(isset($request['start_date']) && isset($request['end_date'])){
                $task_query->whereBetween('start_date',[ date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))]);
                $task_query->where('end_date', '<=',  date('Y-m-d', strtotime($request['end_date'])));
            }
            elseif(isset($request['start_date'])){
                $task_query->where('start_date', '>=',  date('Y-m-d', strtotime($request['start_date'])));
            }
            elseif(isset($request['end_date'])){
                $task_query->where('end_date', '<=',  date('Y-m-d', strtotime($request['end_date'])));
            }
            if(isset($request['search_key'])){
                $task_query->where('title','like','%'.$request['search_key'].'%');
                $task_query->orWhere('task_id','like','%'.$request['search_key'].'%');
            }

            if(isset($request['assignee'])){
                if($request['assignee'] == 'assign_to_me'){
                    $tasks = $task_query->get();
                    $taskIds = [];
                     foreach ($tasks as $key => $task){
                        if(count($task->memberTasks) == 0){
                            unset($tasks[$key]);
                        }else{
                            array_push($taskIds, $task->id);
                        }
                    }
                    return Task::with('memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy('id', 'desc')->get();
                }elseif($request['assignee'] == 'assign_by_me'){
                    $tasks = $task_query->get();
                    $taskIds = [];
                    foreach ($tasks as $key => $task){
                        if(count($task->teamTasks) == 0){
                            unset($tasks[$key]);
                        }else{
                            array_push($taskIds, $task->id);
                        }
                    }
                    return Task::with('memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy('id', 'desc')->get();
                    
                }elseif($request['assignee'] == 'assign_all'){
                    $tasks = $task_query->get();
                    $taskIds = [];
                     foreach ($tasks as $key => $task){
                        if(count($task->teamTasks) == 0){
                            unset($tasks[$key]);
                        }else{
                            array_push($taskIds, $task->id);
                        }
                    }
                    return Task::with('teamTasks')->whereIn('id',$taskIds)->orderBy('id', 'desc')->get();
                }
            }else{
                $tasks = $task_query->get();
                $taskIds = [];
                 foreach ($tasks as $key => $task){
                    if(count($task->teamTasks) == 0){
                        unset($tasks[$key]);
                    }else{
                        array_push($taskIds, $task->id);
                    }
                }
                return Task::with('teamTasks')->whereIn('id',$taskIds)->orderBy('id', 'desc')->get();
            }

        }else{
            $tasks = $task_query->get();
            $taskIds = [];
                foreach ($tasks as $key => $task){
                if(count($task->teamTasks) == 0){
                    unset($tasks[$key]);
                }else{
                    array_push($taskIds, $task->id);
                }
            }
            return Task::with('teamTasks')->whereIn('id',$taskIds)->orderBy('id', 'desc')->get();
        }
    }

    public function headings(): array
    {
        return [
            'Task ID',
            'Task Title',
            'Assignee',
            'Start Time',
            'Due Time',
            'Priority',
            'Status',
            'Created At'
        ] ;
    }
    public function map($row): array
    {
        return [
            $row->task_id,
            $row->title,
            $row->assignees,
            Carbon::parse($row->start_date)->toFormattedDateString(),
            Carbon::parse($row->end_date)->toFormattedDateString(),
            $row->priority,
            $row->status,
            Carbon::parse($row->created_at)->toFormattedDateString()
        ];
    }
    public function prepareRows($rows): array
    {
        return array_map(function ($task) {
            $assignee = '';
            if($task->status == '0'){
                $task->status = 'Accepted';
            }
            if($task->status == '1'){
                $task->status = 'In Progress';
            }
            if($task->status == '2'){
                $task->status = 'Completed';
            }

            if($task->priority == '0'){
                $task->priority = 'Low';
            }
            if($task->priority == '1'){
                $task->priority = 'Medium';
            }
            if($task->priority == '2'){
                $task->priority = 'High';
            }
            if($task->assign_to == 'team'){
                foreach($task->teamTasks as $team_task){
                    if(!empty($team_task->Team)){
                        $assignee .= $team_task->Team->name .', ';
                    }
                }
            }
            elseif($task->assign_to == 'individual'){
                foreach($task->assignees as $singleAssignee){
                    if(!empty($singleAssignee->user->name)){
                    $assignee .= $singleAssignee->User->name .', ';
                    }
                }
            }
            $task->assignees = $assignee;
            return $task;
        }, $rows);
    }
}
