<?php

namespace App\Exports;

use App\Modals\WorkLog;
use App\Modals\Member;
use App\Modals\Week;
use App\Modals\Team;
use App\Modals\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Session;

class WorkLogsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $data;

    public function collection()
    {
        $weekId = Week::select('id')->orderBy('id','desc')->first();

        if(Session::has('worklog.filter')){
            $request = Session::get('worklog.filter');
            $weekId = $request['weekId'];
            $userIds = Member::where('team_id',$request['teamId'])->groupBy('user_id')->pluck('user_id')->toArray();
            if(isset($request['search_key'])){
                $ids = User::where('name','like','%'.$request['search_key'].'%')->groupBy('id')->pluck('id')->toArray();
                $resultIds = [];
                foreach($ids as $id){
                    if(in_array($id,$userIds)){
                        array_push($resultIds,$id);
                    }
                }

                $userIds = $resultIds;
            }
        }else{
            $teams = Team::all();
            $userIds = Member::where('team_id',$teams[0]->id)->groupBy('user_id')->pluck('user_id')->toArray();
        }

        $result = [];
        $index = 0;
        foreach($userIds as $userId){
            $worklogs = \App\Modals\WorkLog::getTasksForExport($userId,$weekId);
            for($i=0; $i < count($worklogs); $i++){
                if(count($worklogs[$i]) > 0){
                    // array_push($result, [
                    //     'week_number' => isset($worklogs[$i]['week_number']) ? $worklogs[$i]['week_number'] : '', 
                    //     'user' => isset($worklogs[$i]['user']) ? $worklogs[$i]['user'] : '', 
                    //     'task' => isset($worklogs[$i]['task']) ? $worklogs[$i]['task'] : '', 
                    //     'date' => isset($worklogs[$i]['date']) ? $worklogs[$i]['date'] : '', 
                    //     'time' => isset($worklogs[$i]['time']) ? $worklogs[$i]['time'] : ''
                    // ]);
                    $result[$index] = array(
                    'week_number' => isset($worklogs[$i]['week_number']) ? $worklogs[$i]['week_number'] : '', 
                    'user' => isset($worklogs[$i]['user']) ? $worklogs[$i]['user'] : '', 
                    'task' => isset($worklogs[$i]['task']) ? $worklogs[$i]['task'] : '', 
                    'date' => isset($worklogs[$i]['date']) ? $worklogs[$i]['date'] : '', 
                    'time' => isset($worklogs[$i]['time']) ? $worklogs[$i]['time'] : '');
                    $index++;
                }
            }
        }

        $this->data = $result;

        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Week Number',
            'User',
            'Date',
            'Task',
            'Spend Time',
        ] ;
    }
    
}
