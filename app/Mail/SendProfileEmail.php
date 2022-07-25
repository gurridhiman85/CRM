<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendProfileEmail extends Mailable
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
            $prefix = config('constant.prefix');
            $EmailAttachment = $this->recData->Email_Attachment;
            $folder = $this->recData->data->promoexpo_folder;
            $tname = $this->recData->data->t_name;
            $file_Name = $this->recData->file_Name;

            $listXLSX_path = public_path('\\'.$folder).'\\'.$prefix.'PRF_'.$file_Name.'.xlsx';
            $listPDF_path = public_path('\\'.$folder).'\\'.$prefix.'PRF_'.$file_Name.'.pdf';

            $mail = $this->view('mails.report')
                ->from('admin@crmsquare.com','CRM Square Administrator')
                ->with(['recData' => $this->recData])
                ->subject($this->recData->Sub);

            if(!empty($this->recData->Cc)) $mail = $mail->cc($this->recData->Cc);
            if(!empty($this->recData->Bcc)) $mail = $mail->bcc($this->recData->Bcc);


            if(file_exists($listXLSX_path) && in_array($EmailAttachment,['xlsx','both'])){
                $mail = $mail->attach($listXLSX_path, [
                    'as' => $prefix.'PRF_'.$file_Name.'.xlsx',
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]);
            }

            if(file_exists($listPDF_path) && in_array($EmailAttachment,['xlsx','both'])){
                $mail = $mail->attach($listPDF_path, [
                    'as' => $prefix.'PRF_'.$file_Name.'.pdf',
                    'mime' => 'application/pdf',
                ]);
            }

            return $mail;

        }catch (\Exception $exception){
            echo $exception->getLine();
        }
    }
}
