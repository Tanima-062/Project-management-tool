<?php

namespace App\Exports;

use App\Modals\RequestTask;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MyRequestTasksExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $auth_user_id = Auth::user()['id'];
        $task_query = RequestTask::where('request_from',$auth_user_id);
        if(Session::has('my.request.task.filter')){
            $request = Session::get('my.request.task.filter');
            if(isset($request['priority'])){
                $task_query = $task_query->where(['priority' => $request['priority']]);
            }
            if(isset($request['status'])){
                $task_query = $task_query->where(['status' => $request['status']]);
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

            return $task_query->orderBy('id','desc')->get();
        }else{
            return $task_query->orderBy('id','desc')->get();
        }
    }
    public function headings(): array
    {
        return [
            'Task ID',
            'Task Title',
            'Start Time',
            'Due Time',
            'Priority',
            'Status',
            'Request From',
            'Request To'
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
            $row->requestFrom->name,
            $row->assignByWithTrashed->name
        ];
    }
    public function prepareRows($rows): array
    {
        return array_map(function ($task) {
            if($task->status == '-1'){
                $task->status = 'Pending';
            }
            elseif($task->status == '0'){
                $task->status = 'Rejected';
            }
            elseif($task->status == '1'){
                $task->status = 'Approved';
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
