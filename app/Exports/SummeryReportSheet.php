<?php

namespace App\Exports;

use App\Modals\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Session;

class SummeryReportSheet implements FromCollection, WithMapping, WithHeadings, WithTitle
{
  use Exportable;

  public $count = 1;

  public function collection()
  {
      return  User::with(['supervisorInformation'])->get();
  }

  public function headings(): array
  {
      return [
          'SL',
          'Programme',
          'Resource Name',
          'Designation',
          'Supervisor',
          'Task Created',
          'Task accepted',
          'Task rejected'
      ] ;
  }

  public function map($row): array
  {
      return [
          $this->count++,
          $row->programme,
          $row->name,
          $row->designation,
          $row->supervisorInformation ? $row->supervisorInformation->name : '',
          $row->created,
          $row->accepted,
          $row->rejected
      ];
  }

  public function prepareRows($rows): array
    {
        return array_map(function ($user) {
            $program = User::programNameConcate($user->userPrograms);
            $user->programme = $program;

            $task_status = User::summeryReport($user->id);
            $user->created = $task_status['created'] > 0 ? $task_status['created'] : '0' ;
            $user->accepted =  $task_status['accepted'] > 0 ? $task_status['accepted'] : '0';
            $user->rejected = $task_status['rejected'] > 0 ? $task_status['rejected'] : '0';

            return $user;
        }, $rows);
    }

  public function title(): string
  {
      return 'Summary Report';
  }
}
