<?php

namespace App\Console\Commands\Notification;

use App\Services\Notification\PushNotificatonService;
use Illuminate\Console\Command;

/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@gmail.com]
 * @create date 2021-03-23 12:49:51
 * @modify date 2021-03-23 12:49:51
 * @desc [description]
 */

class PushNotificationTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pushnotification:test {token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push Notification test';

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
        $notification = new PushNotificatonService();
        $notification->setTitle('Task manager');
        $notification->setBody("Hello notification man");
        $token = $this->argument('token');
         $notification->send($token);
        // $notification->sendTopic('hello man');
    }
}
