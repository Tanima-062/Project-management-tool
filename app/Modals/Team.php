<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Team extends Model
{
    use SoftDeletes;
    protected $dates=['deleted_at'];

    public function teamLead()
    {
        return $this->belongsTo('App\Modals\User', 'team_lead_id', 'id');
    }

    public function members()
    {
        return $this->hasMany('App\Modals\Member', 'team_id','id');
    }

    public function teamTasks()
    {
        return $this->hasMany('App\Modals\TeamTask', 'team_id','id');
    }

    public function memberTasks()
    {
        return $this->hasMany('App\Modals\MemberTask', 'team_id','id');
    }

    public function createBy()
    {
        return $this->belongsTo('App\Modals\User', 'create_by_id','id');
    }


    public static function hasMember($team, $singleTeam, $team_member=null){
        $hasMember = false;
        if($singleTeam->members) {
            foreach ($singleTeam->members as $member){
                if(isset($member->team_member_from) && ($team->id == $member->team_member_from)){
                    if (isset($team_member->user_id) && ($team_member->user_id == $member->user_id)) {
                        $hasMember = true;
                        return $hasMember;
                    }    
                }
            }
        }
        return $hasMember;
    }
    
    public static function hasTeamLead($team, $singleTeam, $member=null){
        $hasTeamLead = false;
        if(isset($singleTeam->team_lead_from) && ($team->id == $singleTeam->team_lead_from)){
            if(isset($member->user_id) && ($member->user_id == $singleTeam->team_lead_id)){
                $hasTeamLead = true;
                return $hasTeamLead;
            }
        }
        return $hasTeamLead;
    }

    public static function isMyCustomTeam($team){
        $auth_user_id = Auth::user()['id'];
        $flag = false;
        if($team->create_by_id == $auth_user_id){
            $flag = true;
            return $flag;
        }
        elseif($team->flag == 'custom'){
            foreach($team->members as $member){
                if((isset($member->user_id) && ($member->user_id == $auth_user_id))){
                    $flag = true;
                    return $flag;
                }
            }
        }
        return $flag;
    }

    public static function isOrganizationTeam($team){
        $auth_user_id = Auth::user()['id'];
        $flag = false;
        if((isset($team->flag)) && ($team->flag == 'organization')){
            if((isset($team->create_by_id)) && ($team->create_by_id == $auth_user_id)){
                $flag = true;
                return $flag;
            }else{
                foreach($team->members as $member){
                    if((isset($member->user_id) && ($member->user_id == $auth_user_id))){
                        $flag = true;
                        return $flag;
                    }
                }
            }
        }
        return $flag;
    }
}
