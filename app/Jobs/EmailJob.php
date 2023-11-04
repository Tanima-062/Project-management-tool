<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Exception;
use SoapClient;

/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@mail.com]
 * @create date 2021-02-03 15:21:02
 * @modify date 2021-02-03 15:21:02
 * @desc [description]
 */

class EmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailContent;

    public $tries = 3;

    public $timeout = 20;

    /**
     * Create a new job instance.
     *
     * @param $details
     */
    public function __construct($details)
    {
        $this->emailContent = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $soapClient = new SoapClient("http://imail.brac.net:8080/isoap.comm.imail/EmailWS?wsdl");
            $soapClient->__call('sendEmail', array($this->emailContent));
        } catch (Exception $ex) {
            Log::error($ex);
        }
    }
}
