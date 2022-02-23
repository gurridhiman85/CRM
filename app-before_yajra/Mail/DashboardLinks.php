<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DashboardLinks extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $recData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($recData)
    {
        $this->recData = $recData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try{
            $mail = $this->view('mails.dashboard_links')
                ->from('admin@crmsquare.com','CRM Square Administrator')
                ->with(['recData' => $this->recData])
                ->subject($this->recData->Sub);

            if(!empty($this->recData->Cc)){
                $mail = $mail->cc($this->recData->Cc);
            }

            if(!empty($this->recData->Bcc)) {
                $mail = $mail->bcc($this->recData->Bcc);
            }

            return $mail;
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }

    }
}
