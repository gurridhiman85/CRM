<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShareModelEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $recData;
    public $prefix;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($recData)
    {
        $this->recData = $recData;
        $this->prefix = config('constant.prefix');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try{
            $mail = $this->view('mails.sharereport')->from('admin@crmsquare.com','CRM Square Administrator')->with(['recData' => $this->recData])->subject($this->recData->Sub);
            if(!empty($this->recData->Cc)){
                $mail = $mail->cc($this->recData->Cc);
            }

            if(!empty($this->recData->Bcc)) {
                $mail = $mail->bcc($this->recData->Bcc);
            }
            $is_list_exist = file_exists(public_path('\\'.$this->recData->data->promoexpo_folder).'\\'.$this->prefix.'MAL_'.$this->recData->listShortName.'.xlsx');

            if($is_list_exist){
                $mail = $mail->attach(public_path('\\'.$this->recData->data->promoexpo_folder).'\\'.$this->prefix.'MAL_'.$this->recData->listShortName.'.xlsx', [
                    'as' => $this->prefix.'CAL_'.$this->recData->listShortName.'.xlsx',
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]);        // Add attachments
            }

            $is_summary_exist = file_exists(public_path('\\'.$this->recData->data->promoexpo_folder).'\\'.$this->prefix.'MAM_'.$this->recData->listShortName.'.xlsx');

            if($is_summary_exist){
                $mail = $mail->attach(public_path('\\'.$this->recData->data->promoexpo_folder).'\\'.$this->prefix.'MAM_'.$this->recData->listShortName.'.xlsx', [
                    'as' => $this->prefix.'MAM_'.$this->recData->listShortName.'.xlsx',
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]);
            }

            $is_summary_exist = file_exists(public_path('\\'.$this->recData->data->promoexpo_folder).'\\'.$this->prefix.'MAM_'.$this->recData->listShortName.'.pdf');

            if($is_summary_exist){
                $mail = $mail->attach(public_path('\\'.$this->recData->data->promoexpo_folder).'\\'.$this->prefix.'MAM_'.$this->recData->listShortName.'.pdf', [
                    'as' => $this->prefix.'MAM_'.$this->recData->listShortName.'.pdf',
                    'mime' => 'application/pdf',
                ]);
            }

            return $mail;
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }

    }
}
