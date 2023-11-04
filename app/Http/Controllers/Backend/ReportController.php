<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Modals\Task;
use App\Modals\User;
use App\Modals\Assignee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\Notification\Notifier;
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class ReportController extends Controller
{
  public function getReport()
  {
    if(User::hasSpecificPermission(Auth::user(),'report.show')){
      if(Auth::user()) {
        $auth_user_id = Auth::user()['id'];
        $notifications = Notifier::getNotifier($auth_user_id);

        return view('backend.report.index', compact('notifications'));

      }
      else {
        return redirect()->route('ssoLogin');
      }
    }else{
      session()->flash('error', 'You are not authorized for this page!');
      return back();
    }
  }

  public function getExportedReport(Request $request)
  {
    $request->validate([
        'start_date'       => 'required',
        'end_date'         => 'required',
    ]);

    if($request->start_date > $request->end_date)
    {

      session()->flash('error','Start Date cannot bigger than End Date!');

      return redirect()->route('report.index');
    }
    else {

      $start_date = $request->start_date;
      $end_date   = $request->end_date;

      Session::put('start_date', $start_date);
      Session::put('end_date', $end_date);

      // $tasks = Task::with(['assignBy', 'assignees', 'teamTasks'])
      //               ->whereBetween('start_date', [$start_date, $end_date])
      //               ->get();
      // $total = [];
      // foreach ($tasks as $task) {
      //   if($task->assign_to == 'individual')
      //   {
      //
      //     $assignees = Assignee::with(['task', 'user.supervisorInformation', 'user.userPrograms'])
      //                          ->where('task_id', $task->id)
      //                          ->get();
      //     foreach ($assignees as $assignee) {
      //       array_push($total, strval($assignee->id));
      //     }
      //
      //   }
      // }
      //
      // return Assignee::with(['task', 'user.supervisorInformation', 'user.userPrograms'])
      //                ->whereIn('id', $total)
      //                ->count();

      return (new ReportsExport($start_date, $end_date))->download('report.xlsx');
    }

    // return Excel::download(new ReportsExport, 'users.xlsx');
  }
}
