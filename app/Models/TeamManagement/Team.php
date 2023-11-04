<?php

namespace App\Models\TeamManagement;

/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@mail.com]
 * @create date 2021-03-20 15:06:17
 * @modify date 2021-03-20 15:06:17
 * @desc [description]
 */

use App\Engine\Interfaces\VisionImplementable;
use App\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modals\TeamTask;
use App\Enums\Task\TaskStatusEnum;
use App\Modals\Member;

class Team extends BaseModel implements VisionImplementable
{
    protected $table = 'teams';
    use SoftDeletes;
    protected $dates=['deleted_at'];

    public function relations()
    {
        return [
            [
                'key' => 'members',
                "model" => Member::class,
                "base_model" => $this,
                "base_reation" => 'id',
                "related" => 'team_id',
            ],
        ];
    }

    public function teamLead()
    {
        return $this->belongsTo('App\Modals\User', 'team_lead_id', 'id');
    }

    public function created_by()
    {
        return $this->belongsTo('App\Modals\User', 'create_by_id', 'id')->select('id', 'name');
    }

    public function members()
    {
        return $this->hasMany('App\Modals\Member', 'team_id','id')
        ->join('users', 'users.id', '=', 'members.user_id')
        ->orderBy('users.name', 'ASC');
    }

    public function teamTasks()
    {
        return $this->hasMany('App\Modals\TeamTask', 'team_id','id');
    }

    public function memberTasks()
    {
        return $this->hasMany('App\Modals\MemberTask', 'team_id','id');
    }

    public function render_task_overview() {
        $total_task = TeamTask::join('tasks', 'tasks.id', '=', 'team_tasks.task_id')
            ->where('team_tasks.team_id', $this->id)->count();
        $completed_task = TeamTask::join('tasks', 'tasks.id', '=', 'team_tasks.task_id')
        ->where('team_tasks.team_id', $this->id)
        ->where('tasks.status', TaskStatusEnum::Complete()->getValue())
        ->count();
        return [
            'total' => $total_task,
            'completed_task' => $completed_task
        ];
    }

    public function render_team_member(){
        $members = Member::join('users', 'users.id', '=', 'members.user_id')
            ->where('members.team_id', $this->id)
            ->select('users.image', 'users.id')
            ->get();
        $numberOFmember = Member::join('users', 'users.id', '=', 'members.user_id')
            ->where('members.team_id', $this->id)
            ->select('users.image')->count();

        return [
            'images' => $members,
            'number' => $numberOFmember
        ];
    }

    // public function render_member_overview(){
    //     $team_member = User::join
    // }

    public function serializerFields()
    {
        return ['id', 'teamLead', 'task_overview', 'member_overview', 'team_member'];
    }

    public function selectFields()
    {
        return ['id', 'team_lead_id', 'team_lead_from', 'name', 'flag', 'create_by_id'];
    }

    static public function postserializerFields()
    {
        return ['name'];
    }
}
