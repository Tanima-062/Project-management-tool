<?php

namespace App\Exports;

use App\Modals\Task;
use App\Modals\Team;
use App\Modals\Member;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class TasksExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $auth_user_id = Auth::user()['id'];

        $member = Member::where('user_id',$auth_user_id)->first();
        $teams = Team::all();

        if(Session::has('task.filter')){
            $request = Session::get('task.filter');
            if(isset($request['team'])){
                $member->team_id = $request['team'];
            }
        }

        $userIds = Member::where('team_id',$member->team_id)->groupBy('user_id')->pluck('user_id')->toArray();

        $task_query= Task::with(['assignees' => function($query) use ($userIds){

                        $query->whereIn('user_id', $userIds);

                    }]);    
        if(Session::has('task.filter')){
            $request = Session::get('task.filter');


            if(isset($request['status'])){
                if($request['status'] == 3){
                    $task_query->where('end_date', '<',  date('Y-m-d'))->where('status', '!=', 2);
                }elseif($request['status'] == 5){
                    $task_query->where(['wfh' => '1']);
                }else{
                    $task_query->where(['status' => $request['status']]);
                }
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
            if(isset($request['search_key_task'])){
                $task_query->where('title','like','%'.$request['search_key_task'].'%');
                $task_query->orWhere('task_id','like','%'.$request['search_key_task'].'%');
            }

    
            $tasks = $task_query->get();
            $taskIds = [];
            foreach ($tasks as $key => $task){
            if((count($task->memberTasks) == 0) && (count($task->assignees)==0) && ($task->assign_by_id != $auth_user_id)){
                unset($tasks[$key]);
            }else{
                array_push($taskIds,$task->id);
            }
        
            return Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy('id', 'desc')->get();
            
            }
        }else{
            $tasks = $task_query->get();
                $taskIds = [];
                foreach ($tasks as $key => $task){
                if((count($task->memberTasks) == 0) && (count($task->assignees)==0) && ($task->assign_by_id != $auth_user_id)){
                    unset($tasks[$key]);
                }else{
                    array_push($taskIds,$task->id);
                }
            }
            return Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy('id', 'desc')->get();

        }
    }

    public function headings(): array
    {
        return [
            'Task ID',
            'Task Title',
            'Assignee',
            'Start Date',
            'Due Date',
            'Allocated Time',
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
            $row->allocated_time,
            $row->priority,
            $row->status,
            Carbon::parse($row->created_at)->toFormattedDateString()
        ];
    }
    public function prepareRows($rows): array
    {
        return array_map(function ($task) {
            $assignee = '';
            if($task->status == '-1'){
                $task->status = 'Pending';
            }
            elseif($task->status == '0'){
                $task->status = 'Accepted';
            }
            elseif($task->status == '1'){
                $task->status = 'In Progress';
            }
            elseif($task->status == '2'){
                $task->status = 'Completed';
            }elseif($task->status == '4'){
                $task->status = 'Rejected';
            }

            if($task->priority == '0'){
                $task->priority = 'Low';
            }
            elseif($task->priority == '1'){
                $task->priority = 'Medium';
            }
            elseif($task->priority == '2'){
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
            $task->allocated_time = sprintf('%02d:%02d', (int) $task->allocated_time, fmod($task->allocated_time, 1) * 60);
            return $task;
        }, $rows);
    }
}
