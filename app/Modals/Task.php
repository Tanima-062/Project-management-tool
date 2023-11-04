<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use SoftDeletes;

    // shovon modification starts

    public function assignByWithTrashed()
    {
        return $this->belongsTo('App\Modals\User', 'assign_by_id','id')->withTrashed();
    }

    // shovon modification ends

    public function assignBy()
    {
        return $this->belongsTo('App\Modals\User', 'assign_by_id','id');
    }

    public function teamTasks()
    {
        return $this->hasMany('App\Modals\TeamTask', 'task_id','id');
    }

    public function memberTasks()
    {
        return $this->hasMany('App\Modals\MemberTask', 'task_id','id');
    }

    public function assignees()
    {
        return $this->hasMany('App\Modals\Assignee', 'task_id','id');
    }

    public function attachments()
    {
        return $this->hasMany('App\Modals\Attachment', 'task_id','id');
    }
    public function comments()
    {
        return $this->hasMany('App\Modals\Comment', 'task_id','id');
    }
    public function replies()
    {
        return $this->hasMany('App\Modals\Reply', 'task_id','id');
    }

    public function histories()
    {
        return $this->hasMany('App\Modals\History', 'task_id','id');
    }

    public function requests()
    {
        return $this->hasMany('App\Modals\Requests', 'task_id','id');
    }

    public function workLogs()
    {
        return $this->hasMany('App\Modals\WorkLog', 'task_id','id');
    }

    public function taskTags()
    {
        return $this->hasMany('App\Modals\TaskTag', 'task_id','id');
    }


    public static function hasAssignee($assignees,$member){
        $hasAssignee = '';

            foreach ($assignees as $assignee){
                if(($assignee->team_id == $member->team_id) && ($assignee->user_id == $member->user_id) && ($assignee->request_status != 'rejected')){
                    $hasAssignee = 'checked';
                }
            }

        return $hasAssignee;
    }

    public static function hasTeam($team_tasks,$team){
        $hasTeam = '';
        foreach ($team_tasks as $team_task){
            if($team_task->team_id == $team->id){
                $hasTeam = 'checked';
            }
        }
        return $hasTeam;
    }

    public static function taskUpdateOrCreate($request, $id){
        $teamTask = Task::find($id);
        if ($teamTask) {
            $teamTask->status = $request->status;
            $teamTask->updated_at = new \DateTime();
        } else {
            $teamTask = new Task();
            $teamTask->name = $request->name;
            $teamTask->description = $request->description;
            $teamTask->status = $request->status;
            $teamTask->created_at = new \DateTime();
            $teamTask->updated_at = new \DateTime();
        }
        $teamTask->save();
        return $teamTask;
    }

    public static function getKanbanTaskStatus($task){
        $auth_user_id = Auth::user()['id'];
        if($task->assign_to == 'team'){
            $auth_user_as_member = MemberTask::where(['task_id' => $task->id])->where(['member_user_id' => $auth_user_id])->first();

            if($auth_user_as_member) {
                if($auth_user_as_member->request_status == 'pending'){
                    return -1;
                }
                return $auth_user_as_member->task_status;
            }
        }else{
            $auth_user_as_assignee = Assignee::where(['task_id' => $task->id])->where(['user_id' => $auth_user_id])->first();
            if ($auth_user_as_assignee) {
                if($auth_user_as_assignee->request_status == 'pending'){
                    return -1;
                }
                return $auth_user_as_assignee->task_status;
            }
        }
    }

    public static function meAsAssignToTask($task){
        $auth_user_id = Auth::user()['id'];
        if($task->assign_to == 'team'){
            $auth_user_as_member = MemberTask::where(['task_id' => $task->id])->where(['member_user_id' => $auth_user_id])->first();

            if($auth_user_as_member) {
                return true;
            }
        }else{
            $auth_user_as_assignee = Assignee::where(['task_id' => $task->id, 'user_id' => $auth_user_id])->where('task_status', '!=', '4')->first();
            if ($auth_user_as_assignee) {
                return true;
            }
        }
        return false;
    }

    public static function taskBreadcums($task, $task_breadcums){
        $parentTask = Task::where('id',$task->parent_id)->first();
        if($task->parent_id == 0){
            array_push($task_breadcums,['task_id' => $task->task_id, 'id' => $task->id]);
            return  $task_breadcums;
        }
        array_push($task_breadcums,['task_id' => $task->task_id, 'id' => $task->id]);
        if($parentTask){
            return Task::taskBreadcums($parentTask, $task_breadcums);
        }
        return $task_breadcums;
    }

    public static function showWFH($task){

        $auth_user_id = Auth::user()['id'];
        if(($task->assignBy->id == $auth_user_id) && ($task->wfh == 1)){
            return true;
        }else{
            foreach($task->assignees as $assignee){
                if(($assignee->user_id == $auth_user_id) && ($assignee->wfh == 1)){
                    return true;
                }
            }
        }
        return false;
    }

    public static function hasTag($tag_id, $task_tags){
        $hasTag = '';
        foreach($task_tags as $task_tag){
            if($task_tag->tag->id == $tag_id){
                $hasTag = 'selected';
                return $hasTag;
            }
        }
        return $hasTag;
    }

    public static function getSubTasks($task){
        $subTasks = Task::where('parent_id',$task->id)->get();
        return $subTasks;
    }
}
