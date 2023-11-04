<?php


namespace App\Enums\Notification;

use App\Enums\Base\BaseEnum;

/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@mail.com]
 * @create date 2021-01-31 13:10:25
 * @modify date 2021-01-31 13:10:25
 * @desc [description]
 */

class NotificationCodeEnum extends BaseEnum {
    private const TaskAssigned = 1001;
    private const TaskAccepted = 1002;
    private const TaskRejected = 1003;
    private const TaskCompleted = 1004;
    private const TaskEdited = 1005;
    private const AssigneeTaskEscalation  = 1006;
    private const TaskRequestAdditionalInfo  = 1007;
    private const TaskRequestTimeExtension  = 1008;
    private const TaskRequestOthers  = 1009;
    private const UserCreate  = 1010;
    private const Nudge  = 1011;
    private const TaskOverdue  = 1012; 
    private const TaskOverdueAfter2Days  = 1013; 
    private const TaskRequest = 1014;
    private const TaskRequestAccepted = 1015;
    private const TaskRequestRejected = 1016;
    private const TaskAssignedInternal = 2001;
    private const TaskAcceptedInternal = 2002;
    private const TaskRejectedInternal = 2003;
    private const TaskCompletedInternal = 2004;
    private const TaskEditedInternal = 2005;
    private const TaskEscalationInternal = 2006;
    private const TaskRequestAdditionalInfoInternal = 2007;
    private const TaskRequestTimeExtensionInternal = 2008;
    private const TaskRequestOthersInternal = 2009;
    private const UserCreateInternal = 2010;
    private const NudgeInternal = 2011;
    private const TaskOverdueInternal  = 2012;
    private const TaskOverdueAfter2DaysInternal  = 2013;
    private const SendEmailForReset = 2014;
    private const TaskRequestInternal = 2015;
    private const TaskRequestAcceptedInternal = 2016;
    private const TaskRequestRejectedInternal = 2017;
}