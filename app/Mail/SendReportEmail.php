<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReportEmail extends Mailable
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

            $listXLSX_path = public_path('\\'.$folder).'\\'.$prefix.'RPL_'.$file_Name.'.xlsx';
            $listPDF_path = public_path('\\'.$folder).'\\'.$prefix.'RPL_'.$file_Name.'.pdf';

            $Report_Row = $this->recData->data->Report_Row;
            $summaryXLSX_path = public_path('\\'.$folder).'\\'.$prefix.'RPS_'.$file_Name.'.xlsx';
            $summaryPDF_path = public_path('\\'.$folder).'\\'.$prefix.'RPS_'.$file_Name.'.pdf';

            $mail = $this->view('mails.report')
                ->from('admin@crmsquare.com','CRM Square Administrator')
                ->with(['recData' => $this->recData])
                ->subject($this->recData->Sub);

            if(!empty($this->recData->Cc)) $mail = $mail->cc($this->recData->Cc);
            if(!empty($this->recData->Bcc)) $mail = $mail->bcc($this->recData->Bcc);


            if(file_exists($listXLSX_path) && in_array($EmailAttachment,['onlylist','both'])){
                $mail = $mail->attach($listXLSX_path, [
                    'as' => $prefix.'RPL_'.$file_Name.'.xlsx',
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]);
            }

            if(file_exists($listPDF_path) && in_array($EmailAttachment,['onlylist','both'])){
                $mail = $mail->attach($listPDF_path, [
                    'as' => $prefix.'RPL_'.$file_Name.'.pdf',
                    'mime' => 'application/pdf',
                ]);
            }

            if(!empty($Report_Row) && $summaryXLSX_path && in_array($EmailAttachment,['onlyreport','both'])){
                $mail = $mail->attach($summaryXLSX_path, [
                    'as' => $prefix.'RPS_'.$tname.'.xlsx',
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]);
            }
            if(!empty($Report_Row) && $summaryPDF_path && in_array($EmailAttachment,['onlyreport','both'])){
                $mail = $mail->attach($summaryPDF_path, [
                    'as' => $prefix.'RPS_'.$file_Name.'.pdf',
                    'mime' => 'application/pdf',
                ]);
            }

            return $mail;

        }catch (\Exception $exception){
            echo $exception->getLine();
        }
    }
}
