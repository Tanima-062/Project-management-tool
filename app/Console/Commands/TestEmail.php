<?php

namespace App\Console\Commands;

use App\Services\Notification\EmailNotificationService;
use Illuminate\Console\Command;

/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@mail.com]
 * @create date 2021-03-16 15:00:51
 * @modify date 2021-03-16 15:00:51
 * @desc [description]
 */

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email Testing with soap client';

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
     * @return int
     */
    public function handle()
    {
        $emailService = new EmailNotificationService(1000, 'email' ,'shohag.linkvision@gmail.com');
        $emailService->replaceTempateVariable(['assignee'=> "Fazlul Kabir SHohag"]);
        $emailService->sendEmail();
    }
}
