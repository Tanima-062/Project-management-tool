<?php

namespace App\Exports;

use App\Modals\Task;
use App\Modals\Assignee;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Session;
use Carbon\Carbon;

class DetailReportSheet implements FromCollection, WithMapping, WithHeadings, WithTitle
{
  use Exportable;

  public $count = 1;


  public function __construct($start_date, $end_date)
  {
    $this->start_date = $start_date;
    $this->end_date   = $end_date;
  }

  public function collection()
  {

      $tasks = Task::with(['assignByWithTrashed', 'assignees', 'teamTasks'])
                    ->whereBetween('start_date', [$this->start_date, $this->end_date])
                    ->get();

      $total = [];

      foreach ($tasks as $task) {
        if($task->assign_to == 'individual')
        {
          $assignees = Assignee::with(['task', 'userWithTrashed.supervisorInformation', 'user.firstProgram.program'])
                               ->where('task_id', $task->id)
                               ->get();

         foreach ($assignees as $assignee) {
           array_push($total, strval($assignee->id));
         }
        }
      }
      
        return Assignee::with(['task.assignByWithTrashed', 'userWithTrashed.supervisorInformation', 'userWithTrashed.firstProgram.program'])
                        ->whereIn('id', $total)
                        ->get();
  }

  public function headings(): array
  {
      return [
          'SL',
          'Task ID',
          'Task Title',
          'Task Description',
          'Task Type',
          'Assignee',
          'Assigner',
          'Supervisor',
          'Task Owner (Programme)',
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
          $this->count++,
          $row->task            ? $row->task->task_id : '',
          $row->task            ? $row->task->title : '',
          $row->task            ? strip_tags($row->task->description) : '',
          $row->task            ? $row->task->parent_id ? 'Main Task' : 'Sub-Task' : '',
          $row->userWithTrashed ? $row->userWithTrashed->name : '',
          $row->task            ? $row->task->assignByWithTrashed ? $row->task->assignByWithTrashed->name : '' : '',
          $row->userWithTrashed ? $row->userWithTrashed->supervisorInformation ? $row->userWithTrashed->supervisorInformation->name : '' : '',
          $row->userWithTrashed ? $row->userWithTrashed->firstProgram ? $row->userWithTrashed->firstProgram->program ? $row->userWithTrashed->firstProgram->program->name : '' : '' : '',
          $row->task            ? Carbon::parse($row->task->start_date)->toFormattedDateString() : '',
          $row->task            ? Carbon::parse($row->task->end_date)->toFormattedDateString() : '',
          $row->task            ? $row->task->priority : '',
          $row->task            ? $row->task->status : '',
          $row->task            ? Carbon::parse($row->task->created_at)->toFormattedDateString() : ''

          // $row->title,
          // $row->description,
          // $row->parent_id == 0 ? 'Main Task' : 'Sub-Task',
          // $row->assignees,
          // $row->assignBy->name,
          // $row->supervisor,
          // $row->assignBy->program,
          // Carbon::parse($row->start_date)->toFormattedDateString(),
          // Carbon::parse($row->end_date)->toFormattedDateString(),
          // $row->priority,
          // $row->status,
          // Carbon::parse($row->created_at)->toFormattedDateString()
      ];
  }



  public function prepareRows($rows): array
  {
    return array_map(function ($task) {

        // $assignee   = '';
        // $supervisor = '';
        // $program = '';

        if($task->task)
        {
          if($task->task->status == '-1'){
              $task->task->status = 'Pending';
          }
          elseif($task->task->status == '0'){
              $task->task->status = 'Accepted';
          }
          elseif($task->task->status == '1'){
              $task->task->status = 'In Progress';
          }
          elseif($task->task->status == '2'){
              $task->task->status = 'Completed';
          }
          elseif($task->task->status == '4'){
            $task->task->status = 'Rejected';
          }


          if($task->task->priority == '0'){
              $task->task->priority = 'Low';
          }
          elseif($task->task->priority == '1'){
              $task->task->priority = 'Medium';
          }
          elseif($task->task->priority == '2'){
              $task->task->priority = 'High';
          }
        }

        // foreach ($task->user->firstProgram as $program) {
        //   $program .= $program->program->name .', ';
        // }
        // if($task->assign_to == 'team'){
        //     foreach($task->teamTasks as $team_task){
        //         if(!empty($team_task->Team)){
        //             $assignee .= $team_task->Team->name .', ';
        //             $supervisor .= $team_task->Team->teamLead->name .', ';
        //         }
        //     }
        // }
        // elseif($task->assign_to == 'individual'){
        //     foreach($task->assignees as $singleAssignee){
        //         if(!empty($singleAssignee->user->name)){
        //             $assignee .= $singleAssignee->User->name .', ';
        //             $supervisor .= $singleAssignee->user->supervisorInformation->name .', ';
        //
        //         }
        //     }
        // }
        // $task->assignees  = $assignee;
        // $task->supervisor = $supervisor;
        // $task->program = $program;
        return $task;
      }, $rows);
  }

  public function title(): string
  {
      return 'Detail Report';
  }
}
