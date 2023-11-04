<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use App\Modals\User;
use App\Modals\Program;
use App\Modals\UserProgram;
use App\Modals\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Session;

class TaskAnalyticsMemberExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $auth_user_id = Auth::user()['id'];
        $users = User::where(['supervisor' => $auth_user_id])->paginate(5);
        $programs = Program::all();
        $userIds = UserProgram::where(['program_id' => $programs[0]->id])->pluck('user_id')->toArray();
        $memberIds = User::whereIn('id',$userIds)->where(['status' => 1])->pluck('id')->toArray();
        if(Session::has('task_analytics.filter')){
            $request = Session::get('task_analytics.filter');
            $userIds = UserProgram::where(['program_id' => $request['program_id']])->pluck('user_id')->toArray();
            if(isset($request['member_query'])){
                $resultIds = User::where(['status' => 1])->where('name','like','%'.$request['member_query'].'%')->pluck('id')->toArray();
                $memberIds = array_intersect($userIds,$resultIds);
            }else{
                $memberIds = User::whereIn('id',$userIds)->where(['status' => 1])->pluck('id')->toArray(); 
            }
        }

        return User::whereIn('id',$memberIds)->get();
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
