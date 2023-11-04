<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@mail.com]
 * @create date 2021-02-03 15:21:02
 * @modify date 2021-02-03 15:21:02
 * @desc [description]
 */

class EmailForQueuing extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->from('hr4u@robi.com.bd')
                ->subject($this->data['subject'])
                ->with($this->data['email_data'])
                ->view($this->data['view']);

        if(isset($this->data['cc']) && gettype($this->data['cc']) =='array'){
            $email->cc($this->data['cc']);
        }

        if (isset($this->data['file']) && count($this->data['file'])>0){
            foreach ($this->data['file'] as $file){
                $filePath = str_replace("\\",'/',$file);
                $email->attach($filePath);
            }
        }
        return  $email;
    }
}
