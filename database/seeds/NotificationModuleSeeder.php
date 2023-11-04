<?php

use Illuminate\Database\Seeder;
use App\Modals\NotificationModule;

class NotificationModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notification_modules')->truncate();
        
        $notificationModule = new NotificationModule();
        $notificationModule->event = 'taskAssign';
        $notificationModule->displayName = 'Task Assign';
        $notificationModule->email = 1;
        $notificationModule->in_app = 1;
        $notificationModule->save();

        $notificationModule = new NotificationModule();
        $notificationModule->event = 'taskRequest';
        $notificationModule->displayName = 'Task Request';
        $notificationModule->email = 1;
        $notificationModule->in_app = 1;
        $notificationModule->save();

        $notificationModule = new NotificationModule();
        $notificationModule->event = 'taskRequestAccept';
        $notificationModule->displayName = 'Task Request Accept';
        $notificationModule->email = 1;
        $notificationModule->in_app = 1;
        $notificationModule->save();

        $notificationModule = new NotificationModule();
        $notificationModule->event = 'taskRequestReject';
        $notificationModule->displayName = 'Task Request Reject';
        $notificationModule->email = 1;
        $notificationModule->in_app = 1;
        $notificationModule->save();

        $notificationModule = new NotificationModule();
        $notificationModule->event = 'taskAccept';
        $notificationModule->displayName = 'Task Accept';
        $notificationModule->email = 1;
        $notificationModule->in_app = 1;
        $notificationModule->save();

        $notificationModule = new NotificationModule();
        $notificationModule->event = 'taskComplete';
        $notificationModule->displayName = 'Task Complete';
        $notificationModule->email = 1;
        $notificationModule->in_app = 1;
        $notificationModule->save();

        $notificationModule = new NotificationModule();
        $notificationModule->event = 'taskReject';
        $notificationModule->displayName = 'Task Reject';
        $notificationModule->email = 1;
        $notificationModule->in_app = 1;
        $notificationModule->save();

        $notificationModule = new NotificationModule();
        $notificationModule->event = 'taskEdit';
        $notificationModule->displayName = 'Task Edit';
        $notificationModule->email = 1;
        $notificationModule->in_app = 1;
        $notificationModule->save();

        $notificationModule = new NotificationModule();
        $notificationModule->event = 'taskRequestAdditionalInfo';
        $notificationModule->displayName = 'Task Request For Additional Info';
        $notificationModule->email = 1;
        $notificationModule->in_app = 1;
        $notificationModule->save();

        $notificationModule = new NotificationModule();
        $notificationModule->event = 'taskRequestTimeExtension';
        $notificationModule->displayName = 'Task Request For Time Extension';
        $notificationModule->email = 1;
        $notificationModule->in_app = 1;
        $notificationModule->save();

        $notificationModule = new NotificationModule();
        $notificationModule->event = 'taskRequestOthers';
        $notificationModule->displayName = 'Task Request For Others';
        $notificationModule->email = 1;
        $notificationModule->in_app = 1;
        $notificationModule->save();

        $notificationModule = new NotificationModule();
        $notificationModule->event = 'nudge';
        $notificationModule->displayName = 'Nudge';
        $notificationModule->email = 1;
        $notificationModule->in_app = 1;
        $notificationModule->save();

        $notificationModule = new NotificationModule();
        $notificationModule->event = 'taskOverdue';
        $notificationModule->displayName = 'Task Overdue';
        $notificationModule->email = 1;
        $notificationModule->in_app = 1;
        $notificationModule->save();

        $notificationModule = new NotificationModule();
        $notificationModule->event = 'taskOverdueAfter2Days';
        $notificationModule->displayName = 'Task Overdue After 2 Days';
        $notificationModule->email = 1;
        $notificationModule->in_app = 1;
        $notificationModule->save();
    }
}
