<?php

namespace App\Exports;

use App\Modals\Task;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Session;

class TaskAnalyticsSingleUserExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection, WithMapping, WithHeadings
    */
    public function collection()
    {
        $auth_user_id = Session::get('task_analytics.user_id');
        $task_query = Task::with(['memberTasks' => function($query) use ($auth_user_id){

                                $query->where(['member_user_id' => $auth_user_id])
                                    ->where('request_status', '<>', 'rejected');
    
                        }])
                        ->with(['assignees' => function($query) use ($auth_user_id){
    
                                $query->where(['user_id' => $auth_user_id])
                                      ->where('request_status','<>', 'rejected');
    
                        }]);
        if(Session::has('task_analytics.singleUser.filter')){
            $request = Session::get('task_analytics.singleUser.filter');
            if(isset($request['assignee'])){
                if($request['assignee'] == 'assign_to_me'){
                    $task_query = Task::with(['memberTasks' => function($query) use ($auth_user_id){

                                        $query->where(['member_user_id' => $auth_user_id])
                                            ->where('request_status', '<>', 'rejected');

                                }])
                                ->with(['assignees' => function($query) use ($auth_user_id){

                                        $query->where(['user_id' => $auth_user_id])
                                            ->where('request_status','<>', 'rejected');

                                }])
                                ->where('assign_by_id','!=',$auth_user_id);
                }elseif($request['assignee'] == 'assign_by_me'){

                        $task_query = Task::with('assignees','teamTasks','memberTasks')->where('assign_by_id', $auth_user_id);

                }elseif($request['assignee'] == 'assign_all'){
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


            if(isset($request['status'])){
                if($request['status'] == 3){
                    $task_query->where('end_date', '<',  date('Y-m-d'));
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
                $task_query->orWhereBetween('end_date',[ date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))]);
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
                        if((count($task->assignees)==0)){
                            unset($tasks[$key]);
                        }else{
                            array_push($taskIds, $task->id);
                        }
                    }
                    return Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy('id', 'desc')->get();
                }elseif($request['assignee'] == 'assign_by_me'){
                    return $task_query->orderBy('id', 'desc')->get();
                    
                }elseif($request['assignee'] == 'assign_all'){
                    $tasks = $task_query->get();
                    $taskIds = [];
                    foreach ($tasks as $key => $task){
                        if((count($task->assignees)==0) && ($task->assign_by_id != $auth_user_id)){
                            unset($tasks[$key]);
                        }else{
                            array_push($taskIds, $task->id);
                        }
                    }
                    return Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy('id', 'desc')->get();
                }
            }else{
                $tasks = $task_query->get();
                $taskIds = [];
                foreach ($tasks as $key => $task){
                if((count($task->assignees)==0) && ($task->assign_by_id != $auth_user_id)){
                    unset($tasks[$key]);
                }else{
                    array_push($taskIds,$task->id);
                }
            }
            return Task::with('assignees','memberTasks','teamTasks')->whereIn('id',$taskIds)->orderBy('id', 'desc')->get();
            
            }
        }else{
            $tasks = $task_query->get();
                $taskIds = [];
                foreach ($tasks as $key => $task){
                if((count($task->assignees)==0) && ($task->assign_by_id != $auth_user_id)){
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
            'Task Title',
            'Assign By',
            'Start Time',
            'Due Time',
            'Priority',
            'Status'
        ] ;
    }
    public function map($row): array
    {
        return [
            $row->title,
            $row->assignByWithTrashed->name,
            Carbon::parse($row->start_date)->toFormattedDateString(),
            Carbon::parse($row->end_date)->toFormattedDateString(),
            $row->priority,
            $row->status,
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
            return $task;
        }, $rows);
    }
}
