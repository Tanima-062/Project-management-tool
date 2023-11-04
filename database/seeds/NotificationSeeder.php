<?php

use App\Enums\Notification\NotificationCodeEnum;
use App\Enums\Notification\NotificationTypeEnum;
use Illuminate\Database\Seeder;
use App\Models\Notification\NotificationTemplate;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emailType = NotificationTypeEnum::Email()->getValue();
        $smsType = NotificationTypeEnum::Sms()->getValue();
        $internalNotifierType = NotificationTypeEnum::Internal()->getValue();

        $taskRequestSubject = 'A Task has been requested to you for approval – Task No. {{TaskId}}';

        $taskRequestBody = "<p style='margin-top: 10px;'>Dear {{NameoftheAssigner}}</p>
        <p> A task has been requested to you for approval. Following are the details: </p>
        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
        <tbody> <tr> <td align='left'> <table role='presentation' border='0' cellpadding='0' cellspacing='0'> <tbody>
        <tr> <td>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody> </table>
        <p>Task Name: {{Nameofthetask}} </p>
        <p>Assigned By: {{NameoftheAssigner}}</p>
        <p>Priority: {{PriorityoftheTask}}</p>
        <p>Start date: {{StartDateofthetask}} </p>
        <p>End date: {{EndDateofthetask}} </p>
        <p>Status: {{StatusoftheTask}} </p>
        </br></br>
        <p>Go to Task: {{TaskUrl}} </p>";
        

        $taskAssignedSubject = 'A Task has been assigned to you – Task No. {{TaskId}}';

        $taskAssignedBody = "<p style='margin-top: 10px;'>Dear {{Assignee}}</p>
        <p> A task has been assigned to you. Following are the details: </p>
        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
        <tbody> <tr> <td align='left'> <table role='presentation' border='0' cellpadding='0' cellspacing='0'> <tbody>
        <tr> <td>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody> </table>
        <p>Task Name: {{Nameofthetask}} </p>
        <p>Assigned By: {{NameoftheAssigner}}</p>
        <p>Priority: {{PriorityoftheTask}}</p>
        <p>Start date: {{StartDateofthetask}} </p>
        <p>End date: {{EndDateofthetask}} </p>
        <p>Status: {{StatusoftheTask}} </p>
        </br></br>
        <p>Go to Task: {{TaskUrl}} </p>";







        $taskAcceptedSubject = 'Task No. {{TaskId}} has been accepted by {{Assignee}}';

        $taskAcceptedBody = "<p style='margin-top: 10px;'>Dear {{NameoftheAssigner}}</p>
        <p> A task assigned by you has been accepted. Following are the details: </p>
        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
        <tbody> <tr> <td align='left'> <table role='presentation' border='0' cellpadding='0' cellspacing='0'> <tbody>
        <tr> <td>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody> </table>
        <p>Task Name: {{Nameofthetask}} </p>
        <p>Assigned By: {{NameoftheAssigner}}</p>
        <p>Priority: {{PriorityoftheTask}}</p>
        <p>Start date: {{StartDateofthetask}} </p>
        <p>End date: {{EndDateofthetask}} </p>
        <p>Status: {{StatusoftheTask}} </p>
        </br></br>
        <p>Go to Task: {{TaskUrl}} </p>";

        $taskRequestAcceptedSubject = 'Task No. {{TaskId}} has been accepted by {{NameoftheAssigner}}';

        $taskRequestAcceptedBody = "<p style='margin-top: 10px;'>Dear {{Assignee}}</p>
        <p> A task request sent by you has been accepted. Following are the details: </p>
        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
        <tbody> <tr> <td align='left'> <table role='presentation' border='0' cellpadding='0' cellspacing='0'> <tbody>
        <tr> <td>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody> </table>
        <p>Task Name: {{Nameofthetask}} </p>
        <p>Assigned By: {{NameoftheAssigner}}</p>
        <p>Priority: {{PriorityoftheTask}}</p>
        <p>Start date: {{StartDateofthetask}} </p>
        <p>End date: {{EndDateofthetask}} </p>
        <p>Status: {{StatusoftheTask}} </p>
        </br></br>
        <p>Go to Task: {{TaskUrl}} </p>";









        $taskRejectedSubject = 'Task No. {{TaskId}} has been rejected by {{Assignee}}';

        $taskRejectedBody = "<p style='margin-top: 10px;'>Dear {{NameoftheAssigner}}</p>
        <p> A task assigned by you has been rejected. Following are the details: </p>
        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
        <tbody> <tr> <td align='left'> <table role='presentation' border='0' cellpadding='0' cellspacing='0'> <tbody>
        <tr> <td>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody> </table>
        <p>Task Name: {{Nameofthetask}} </p>
        <p>Assigned By: {{NameoftheAssigner}}</p>
        <p>Priority: {{PriorityoftheTask}}</p>
        <p>Start date: {{StartDateofthetask}} </p>
        <p>End date: {{EndDateofthetask}} </p>
        <p>Status: {{StatusoftheTask}} </p>
        </br></br>
        <p>Go to Task: {{TaskUrl}} </p>";

        $taskRequestRejectedSubject = 'Task No. {{TaskId}} has been rejected by {{NameoftheAssigner}}';

        $taskRequestRejectedBody = "<p style='margin-top: 10px;'>Dear {{Assignee}}</p>
        <p> A task request sent by you has been rejected. Following are the details: </p>
        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
        <tbody> <tr> <td align='left'> <table role='presentation' border='0' cellpadding='0' cellspacing='0'> <tbody>
        <tr> <td>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody> </table>
        <p>Task Name: {{Nameofthetask}} </p>
        <p>Assigned By: {{NameoftheAssigner}}</p>
        <p>Priority: {{PriorityoftheTask}}</p>
        <p>Start date: {{StartDateofthetask}} </p>
        <p>End date: {{EndDateofthetask}} </p>
        <p>Status: {{StatusoftheTask}} </p>
        </br></br>
        <p>Go to Task: {{TaskUrl}} </p>";









        $taskCompletionSubject = 'Task No. {{TaskId}} has been completed by {{Assignee}}';

        $taskCompletionBody = "<p style='margin-top: 10px;'>Dear {{NameoftheAssigner}}</p>
        <p> A task assigned by you has been completed. The following are the details: </p>
        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
        <tbody> <tr> <td align='left'> <table role='presentation' border='0' cellpadding='0' cellspacing='0'> <tbody>
        <tr> <td>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody> </table>
        <p>Task Name: {{Nameofthetask}} </p>
        <p>Assigned By: {{NameoftheAssigner}}</p>
        <p>Priority: {{PriorityoftheTask}}</p>
        <p>Start date: {{StartDateofthetask}} </p>
        <p>End date: {{EndDateofthetask}} </p>
        <p>Status: {{StatusoftheTask}} </p>
        </br></br>
        <p>Go to Task: {{TaskUrl}} </p>";







        $taskEditedSubject = 'A Task assigned to you has been updated – Task No. {{TaskId}}';

        $taskEditedBody = "<p style='margin-top: 10px;'>Dear {{Assignee}}</p>
        <p> A task assigned to you has been updated. Following are the details: </p>
        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
        <tbody> <tr> <td align='left'> <table role='presentation' border='0' cellpadding='0' cellspacing='0'> <tbody>
        <tr> <td>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody> </table>
        <p>Task Name: {{Nameofthetask}} </p>
        <p>Assigned By: {{NameoftheAssigner}}</p>
        <p>Priority: {{PriorityoftheTask}}</p>
        <p>Start date: {{StartDateofthetask}} </p>
        <p>End date: {{EndDateofthetask}} </p>
        <p>Status: {{StatusoftheTask}} </p>
        </br></br>
        <p>Go to Task: {{TaskUrl}} </p>";









        $taskRequestAdditionalInfoSubject = '{{Assignee}} has requested for additional information for Task No. {{TaskId}}';

        $taskRequestAdditionalInfoBody = "<p style='margin-top: 10px;'>Dear {{NameoftheAssigner}}</p>
        <p> A task assignee has requested for additional information. The following are the details: </p>
        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
        <tbody> <tr> <td align='left'> <table role='presentation' border='0' cellpadding='0' cellspacing='0'> <tbody>
        <tr> <td>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody> </table>
        <p>Task Name: {{Nameofthetask}} </p>
        <p>Assigned By: {{NameoftheAssigner}}</p>
        <p>Priority: {{PriorityoftheTask}}</p>
        <p>Start date: {{StartDateofthetask}} </p>
        <p>End date: {{EndDateofthetask}} </p>
        <p>Status: {{StatusoftheTask}} </p>
        </br></br>
        <p>Go to Task: {{TaskUrl}} </p>";









        $taskRequestTimeExtensionSubject = '{{Assignee}} has requested for time extension for Task No. {{TaskId}}';

        $taskRequestTimeExtensionBody = "<p style='margin-top: 10px;'>Dear {{NameoftheAssigner}}</p>
        <p> A task assignee has some other request. The following are the details: </p>
        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
        <tbody> <tr> <td align='left'> <table role='presentation' border='0' cellpadding='0' cellspacing='0'> <tbody>
        <tr> <td>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody> </table>
        <p>Task Name: {{Nameofthetask}} </p>
        <p>Assigned By: {{NameoftheAssigner}}</p>
        <p>Priority: {{PriorityoftheTask}}</p>
        <p>Start date: {{StartDateofthetask}} </p>
        <p>End date: {{EndDateofthetask}} </p>
        <p>Status: {{StatusoftheTask}} </p>
        </br></br>
        <p>Go to Task: {{TaskUrl}} </p>";








        $taskRequestOthersSubject = '{{Assignee}} has some other request for Task No. {{TaskId}}';

        $taskRequestOthersBody = "<p style='margin-top: 10px;'>Dear {{NameoftheAssigner}}</p>
        <p> A task assignee has requested for time extension. The following are the details: </p>
        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
        <tbody> <tr> <td align='left'> <table role='presentation' border='0' cellpadding='0' cellspacing='0'> <tbody>
        <tr> <td>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody> </table>
        <p>Task Name: {{Nameofthetask}} </p>
        <p>Assigned By: {{NameoftheAssigner}}</p>
        <p>Priority: {{PriorityoftheTask}}</p>
        <p>Start date: {{StartDateofthetask}} </p>
        <p>End date: {{EndDateofthetask}} </p>
        <p>Status: {{StatusoftheTask}} </p>
        </br></br>
        <p>Go to Task: {{TaskUrl}} </p>";









        $assigneeTaskEscalationSubject = 'The task assigned by you is not completed in due time – Task No. {{TaskId}}';

        $assigneeTaskEscalationBody = "<p style='margin-top: 10px;'>Dear {{Assignee}}</p>
        <p> A task assigned by you is not completed in due time. The following are the details: </p>
        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
        <tbody> <tr> <td align='left'> <table role='presentation' border='0' cellpadding='0' cellspacing='0'> <tbody>
        <tr> <td>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody> </table>
        <p>Task Name: {{Nameofthetask}} </p>
        <p>Assigned By: {{NameoftheAssigner}}</p>
        <p>Priority: {{PriorityoftheTask}}</p>
        <p>Start date: {{StartDateofthetask}} </p>
        <p>End date: {{EndDateofthetask}} </p>
        <p>Status: {{StatusoftheTask}} </p>
        </br></br>
        <p>Go to Task: {{TaskUrl}} </p>";








        $nudgeSubject = '{{NameoftheAssigner}} has pocked you for Task No. {{TaskId}}';

        $nudgeBody = "<p style='margin-top: 10px;'>Dear {{Assignee}}</p>
        <p> {{NameoftheAssigner}} has pocked you for the following task: </p>
        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
        <tbody> <tr> <td align='left'> <table role='presentation' border='0' cellpadding='0' cellspacing='0'> <tbody>
        <tr> <td>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody> </table>
        <p>Task Name: {{Nameofthetask}} </p>
        <p>Assigned By: {{NameoftheAssigner}}</p>
        <p>Priority: {{PriorityoftheTask}}</p>
        <p>Start date: {{StartDateofthetask}} </p>
        <p>End date: {{EndDateofthetask}} </p>
        <p>Status: {{StatusoftheTask}} </p>
        </br></br>
        <p>Go to Task: {{TaskUrl}} </p>";








        $userCreateSubject = 'Your account has been created on BRAC Task Management Platform';

        $userCreateBody = "<p style='margin-top: 10px;'>Dear {{UserFullName}}</p>
        <p> Your Account has been created on BRAC Task Management Platform. The following are the details: </p>
        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
        <tbody> <tr> <td align='left'> <table role='presentation' border='0' cellpadding='0' cellspacing='0'> <tbody>
        <tr> <td>
        </td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </tbody> </table>
        <p>Application URL: {{SiteUrl}} </p>
        <p>Email: {{Email}}</p>
        <p>Designation: {{Designation}}</p>
        <p>Phone No.: {{PhoneNo}} </p>
        <p>Program : {{Program }} </p>
        <p>Unit: {{Unit}} </p>
        <p>Password : {{Password }} </p>";

        $taskOverdueSubject = "Task Overdue";
        $taskOverdueBody ="{{Nameofthetask}}-({{TaskId}}) has been overdue.<p>Go to Task: {{TaskUrl}} </p>";

        $taskOverdueAfter2DaysSubject = "Task Overdue After 2 Days";
        $taskOverdueAfter2DaysBody ="{{Nameofthetask}}-({{TaskId}}) is going to be due soon.<p>Go to Task: {{TaskUrl}} </p>";






         // Internal Notification / InApp notification

        $taskAssignedSubjectInternal = 'Task Assigned';

        $taskAssignedBodyInternal = "{{Nameofthetask}}-({{TaskId}}) has been assigned to you";

        $taskRequestSubjectInternal = 'Task Request From {{Assignee}} to you for approval';

        $taskRequestBodyInternal = "{{Assignee}} requested for {{Nameofthetask}}-({{TaskId}}) to you for approval";



        $taskAcceptedSubjectInternal = 'Task Accepted';
        
        $taskAcceptedBodyInternal = "{{Assignee}} has accepted the task {{Nameofthetask}}-({{TaskId}}) assigned by you";

        $taskRequestAcceptedSubjectInternal = 'Task Request Accepted';
        
        $taskRequestAcceptedBodyInternal = "{{NameoftheAssigner}} has accepted the request task {{Nameofthetask}}-({{TaskId}}) sent by you";



        $taskRejectedSubjectInternal = 'Task Rejected';
        
        $taskRejectedBodyInternal = "{{Assignee}} has rejected the task {{Nameofthetask}}-({{TaskId}}) assigned by you";

        $taskRequestRejectedSubjectInternal = 'Task Request Rejected';
        
        $taskRequestRejectedBodyInternal = "{{NameoftheAssigner}} has rejected the request task {{Nameofthetask}}-({{TaskId}}) sent by you";



        $taskCompletedSubjectInternal = 'Task Completed';
        
        $taskCompletedBodyInternal = "{{Assignee}} has completed the task {{Nameofthetask}}-({{TaskId}}) assigned by you";



        $taskRequestAdditionalInfoSubjectInternal = 'Task Request for Additional Information';
        
        $taskRequestAdditionalInfoBodyInternal = "{{Assignee}} has requested for additional information for {{Nameofthetask}}-({{TaskId}}) assigned by you";



        $taskRequestTimeExtensionSubjectInternal = 'Task Request for Time Extension';
        
        $taskRequestTimeExtensionBodyInternal = "{{Assignee}} has requested for time extension for {{Nameofthetask}}-({{TaskId}}) assigned by you";



        $taskRequestOthersSubjectInternal = 'Task Request for Others';
        
        $taskRequestOthersBodyInternal = "{{Assignee}} has some other request for {{Nameofthetask}}-({{TaskId}}) assigned by you";



        

        $taskEditedSubjectInternal = 'Task Updated';

        $taskEditedBodyInternal = "{{Nameofthetask}}-({{TaskId}}) has been updated";






        $taskEscalationSubjectInternal = 'Task Overdue';
        
        $taskEscalationBodyInternal = "{{Nameofthetask}}-({{TaskId}}) is not completed in due time";






        $userCreateSubjectInternal = 'User Created';
        
        $userCreateBodyInternal = "New account created";


        $nudgeSubjectInternal = "Poke";
        $nudgeBodyInternal = "{{NameoftheAssigner}} poked you for {{Nameofthetask}}-({{TaskId}}).";

      
        $taskOverdueBodyInternal ="{{Nameofthetask}}-({{TaskId}}) has been overdue.";
        $taskOverdueAfter2DaysBodyInternal ="{{Nameofthetask}}-({{TaskId}}) is going to be due soon.";

        $sendEmailForResetSubject = "Password reset link.";
        $sendEmailForResetBody = "<a href='http://127.0.0.1:8000/password-reset/verify/{{Token}}'>Click here to change password</a>";




        $notification = [
            ['code' => NotificationCodeEnum::TaskAssigned()->getValue(), 'body' => $taskAssignedBody, 'subject' => $taskAssignedSubject, 'type' => $emailType],
            ['code' => NotificationCodeEnum::TaskRequest()->getValue(), 'body' => $taskRequestBody, 'subject' => $taskRequestSubject, 'type' => $emailType],
            ['code' => NotificationCodeEnum::TaskAccepted()->getValue(), 'body' => $taskAcceptedBody, 'subject' => $taskAcceptedSubject, 'type' => $emailType],
            ['code' => NotificationCodeEnum::TaskRejected()->getValue(), 'body' => $taskRejectedBody, 'subject' => $taskRejectedSubject, 'type' => $emailType],
            ['code' => NotificationCodeEnum::TaskRequestAccepted()->getValue(), 'body' => $taskRequestAcceptedBody, 'subject' => $taskRequestAcceptedSubject, 'type' => $emailType],
            ['code' => NotificationCodeEnum::TaskRequestRejected()->getValue(), 'body' => $taskRequestRejectedBody, 'subject' => $taskRequestRejectedSubject, 'type' => $emailType],
            ['code' => NotificationCodeEnum::TaskCompleted()->getValue(), 'body' => $taskCompletionBody, 'subject' => $taskCompletionSubject, 'type' => $emailType],
            ['code' => NotificationCodeEnum::TaskRequestAdditionalInfo()->getValue(), 'body' => $taskRequestAdditionalInfoBody, 'subject' => $taskRequestAdditionalInfoSubject, 'type' => $emailType],
            ['code' => NotificationCodeEnum::TaskRequestTimeExtension()->getValue(), 'body' => $taskRequestTimeExtensionBody, 'subject' => $taskRequestTimeExtensionSubject, 'type' => $emailType],
            ['code' => NotificationCodeEnum::TaskRequestOthers()->getValue(), 'body' => $taskRequestOthersBody, 'subject' => $taskRequestOthersSubject, 'type' => $emailType],
            ['code' => NotificationCodeEnum::TaskEdited()->getValue(), 'body' => $taskEditedBody, 'subject' => $taskEditedSubject, 'type' => $emailType],
            ['code' => NotificationCodeEnum::AssigneeTaskEscalation()->getValue(), 'body' => $assigneeTaskEscalationBody, 'subject' => $assigneeTaskEscalationSubject, 'type' => $emailType],
            ['code' => NotificationCodeEnum::Nudge()->getValue(), 'body' => $nudgeBody, 'subject' => $nudgeSubject, 'type' => $emailType],
            ['code' => NotificationCodeEnum::TaskOverdue()->getValue(), 'body' => $taskOverdueBody, 'subject' => $taskOverdueSubject, 'type' => $emailType],
            ['code' => NotificationCodeEnum::TaskOverdueAfter2Days()->getValue(), 'body' => $taskOverdueAfter2DaysBody, 'subject' => $taskOverdueAfter2DaysSubject, 'type' => $emailType],
            ['code' => NotificationCodeEnum::SendEmailForReset()->getValue(), 'body' => $sendEmailForResetBody, 'subject' => $sendEmailForResetSubject, 'type' => $emailType],
            
            ['code' => NotificationCodeEnum::UserCreate()->getValue(), 'body' => $userCreateBody, 'subject' => $userCreateSubject, 'type' => $emailType],

            ['code' => NotificationCodeEnum::TaskAssignedInternal()->getValue(), 'body' => $taskAssignedBodyInternal, 'subject' => $taskAssignedSubjectInternal, 'type' => $internalNotifierType],
            ['code' => NotificationCodeEnum::TaskRequestInternal()->getValue(), 'body' => $taskRequestBodyInternal, 'subject' => $taskRequestSubjectInternal, 'type' => $internalNotifierType],
            ['code' => NotificationCodeEnum::TaskAcceptedInternal()->getValue(), 'body' => $taskAcceptedBodyInternal, 'subject' => $taskAcceptedSubjectInternal, 'type' => $internalNotifierType],
            ['code' => NotificationCodeEnum::TaskRequestAcceptedInternal()->getValue(), 'body' => $taskRequestAcceptedBodyInternal, 'subject' => $taskRequestAcceptedSubjectInternal, 'type' => $internalNotifierType],
            ['code' => NotificationCodeEnum::TaskRejectedInternal()->getValue(), 'body' => $taskRejectedBodyInternal, 'subject' => $taskRejectedSubjectInternal, 'type' => $internalNotifierType],
            ['code' => NotificationCodeEnum::TaskRequestRejectedInternal()->getValue(), 'body' => $taskRequestRejectedBodyInternal, 'subject' => $taskRequestRejectedSubjectInternal, 'type' => $internalNotifierType],
            ['code' => NotificationCodeEnum::TaskCompletedInternal()->getValue(), 'body' => $taskCompletedBodyInternal, 'subject' => $taskCompletedSubjectInternal, 'type' => $internalNotifierType],
            ['code' => NotificationCodeEnum::TaskRequestAdditionalInfoInternal()->getValue(), 'body' => $taskRequestAdditionalInfoBodyInternal, 'subject' => $taskRequestAdditionalInfoSubjectInternal, 'type' => $internalNotifierType],
            ['code' => NotificationCodeEnum::TaskRequestTimeExtensionInternal()->getValue(), 'body' => $taskRequestTimeExtensionBodyInternal, 'subject' => $taskRequestTimeExtensionSubjectInternal, 'type' => $internalNotifierType],
            ['code' => NotificationCodeEnum::TaskRequestOthersInternal()->getValue(), 'body' => $taskRequestOthersBodyInternal, 'subject' => $taskRequestOthersSubjectInternal, 'type' => $internalNotifierType],
            ['code' => NotificationCodeEnum::TaskEditedInternal()->getValue(), 'body' => $taskEditedBodyInternal, 'subject' => $taskEditedSubjectInternal, 'type' => $internalNotifierType],
            ['code' => NotificationCodeEnum::TaskEscalationInternal()->getValue(), 'body' => $taskEscalationBodyInternal, 'subject' => $taskEscalationSubjectInternal, 'type' => $internalNotifierType],

            ['code' => NotificationCodeEnum::NudgeInternal()->getValue(), 'body' => $nudgeBodyInternal, 'subject' => $nudgeSubjectInternal, 'type' => $internalNotifierType],
            ['code' => NotificationCodeEnum::TaskOverdueInternal()->getValue(), 'body' => $taskOverdueBodyInternal, 'subject' => $taskOverdueSubject, 'type' => $internalNotifierType],
            ['code' => NotificationCodeEnum::TaskOverdueAfter2DaysInternal()->getValue(), 'body' => $taskOverdueAfter2DaysBodyInternal, 'subject' => $taskOverdueAfter2DaysSubject, 'type' => $internalNotifierType],
        ];

        NotificationTemplate::truncate()->insert($notification);
    }
}
