<?php

namespace App\Console\Commands\Notification;

use App\Enums\Notification\NotifierEnum;
use App\Events\NotificationEvent;
use App\Services\Notification\NotifierService;
use Illuminate\Console\Command;

/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@gmail.com]
 * @create date 2021-03-23 12:49:51
 * @modify date 2021-03-23 12:49:51
 * @desc [description]
 */

class InternalNotificationTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:internal-test {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Internal notification test';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $array = [
            'data' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
        ];
        $user_id = $this->argument('user_id');
        if($user_id) {
            NotifierService::sendIndividualPerson($user_id, 2001);
        } else {
            event(new NotificationEvent(NotifierEnum::ChannelName()->getValue(), 
            NotifierEnum::GeneralEvent()->getValue(), $array));
        } 
    }
}
