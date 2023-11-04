<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use App\Modals\User;
use App\Modals\Task;
use App\Modals\Assignee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Session;

class TaskAnalyticsUserExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $auth_user_id = Auth::user()['id'];
        if(Session::has('task_analytics.user.filter')){
            $request = Session::get('task_analytics.user.filter');
            if(isset($request['query'])){
                return User::where(['supervisor' => $auth_user_id])->where('name','like','%'.$request['query'].'%')->get();
            }
            if(isset($request['start_date']) && isset($request['end_date'])){
                $taskIds = Task::whereBetween('start_date',[ date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))])
                            ->orWhereBetween('end_date', [ date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))])->pluck('id')->toArray();
                $assigneeIds = Assignee::whereIn('task_id',$taskIds)->pluck('user_id')->toArray();
                return User::where(['supervisor' => $auth_user_id])->whereIn('id',$assigneeIds)->get();
            }
            elseif(isset($request->start_date)){
                $taskIds =Task::where('start_date', '>=',  date('Y-m-d', strtotime($request['start_date'])))->pluck('id')->toArray();
                $assigneeIds = Assignee::whereIn('task_id',$taskIds)->pluck('user_id')->toArray();
                return User::where(['supervisor' => $auth_user_id])->whereIn('id',$assigneeIds)->get();
            }
            elseif(isset($request->end_date)){
                $taskIds = Task::where('end_date', '<=',  date('Y-m-d', strtotime($request['end_date'])))->pluck('id')->toArray();
                $assigneeIds = Assignee::whereIn('task_id',$taskIds)->pluck('user_id')->toArray();
                return User::where(['supervisor' => $auth_user_id])->whereIn('id',$assigneeIds)->get();
            } 
        }
        return User::where(['supervisor' => $auth_user_id])->get();
    }

    public function headings(): array
    {
        return [
            'Full Name',
            'Programme',
            'Total Task',
            'Accepted',
            'In Progress',
            'Completed',
            'Overdue'
        ];
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->programme,
            $row->totalTask,
            $row->accepted,
            $row->inProgress,
            $row->completed,
            $row->overdue
        ];
    }

    public function prepareRows($rows): array
    {
        return array_map(function ($user) {
            $program = User::programNameConcate($user->userPrograms);
            $user->programme = $program;

            $task_status = User::taskCountByStatus($user->id);
            $user->totalTask = $task_status['total'] > 0 ? $task_status['total'] : '0' ;
            $user->accepted = $task_status['accepted'] > 0 ? $task_status['accepted'] : '0';
            $user->inProgress = $task_status['inProgress'] > 0 ? $task_status['inProgress'] : '0';
            $user->completed = $task_status['completed'] > 0 ? $task_status['completed'] : '0';
            $user->overdue = $task_status['overdue'] > 0 ? $task_status['overdue'] : '0';

            return $user;
        }, $rows);
    }
}
