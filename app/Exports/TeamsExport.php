<?php

namespace App\Exports;

use App\Modals\Team;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TeamsExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Team::all();
    }

    public function headings(): array
    {
        return [
            'Team Name',
            'Team Lead',
            'Members',
            'Created At',
            'Updated At'
        ] ;
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->teamLead->name,
            $row->members,
            Carbon::parse($row->created_at)->toFormattedDateString(),
            Carbon::parse($row->updated_at)->toFormattedDateString()
        ];
    }
    public function prepareRows($rows): array
    {
        return array_map(function ($team) {
            $member = '';
            if($team->members){
                foreach ($team->members as $mem){
                    if(isset($mem->user->name)){
                        $member .= $mem->user->name . ', ';
                    }
                }
            }else{
                $member = '';
            }
            $team->members = $member;
            return $team;
        }, $rows);
    }
}
