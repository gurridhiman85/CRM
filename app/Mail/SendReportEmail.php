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

            $is_listXLSX_exist = file_exists(public_path('\\'.$folder).'\\'.$prefix.'RPL_'.$tname.'.xlsx');
            $is_listPDF_exist = file_exists(public_path('\\'.$folder).'\\'.$prefix.'RPL_'.$tname.'.pdf');

            $Report_Row = $this->recData->data->Report_Row;
            $is_summaryXLSX_exist = file_exists(public_path('\\'.$folder).'\\'.$prefix.'RPS_'.$tname.'.xlsx');
            $is_summaryPDF_exist = file_exists(public_path('\\'.$folder).'\\'.$prefix.'RPS_'.$tname.'.pdf');

            $mail = $this->view('mails.report')->from('admin@crmsquare.com','CRM Square Administrator')->with(['recData' => $this->recData])->subject($this->recData->Sub);

            if(!empty($this->recData->Cc)) $mail = $mail->cc($this->recData->Cc);
            if(!empty($this->recData->Bcc)) $mail = $mail->bcc($this->recData->Bcc);


            if($is_listXLSX_exist && in_array($EmailAttachment,['onlylist','both'])){
                $mail = $mail->attach(public_path('\\'.$folder).'\\'.$prefix.'RPL_'.$tname.'.xlsx', [
                    'as' => $prefix.'RPL_'.$tname.'.xlsx',
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]);
            }

            if($is_listPDF_exist && in_array($EmailAttachment,['onlylist','both'])){
                $mail = $mail->attach(public_path('\\'.$folder).'\\'.$prefix.'RPL_'.$tname.'.pdf', [
                    'as' => $prefix.'RPL_'.$tname.'.pdf',
                    'mime' => 'application/pdf',
                ]);
            }

            if(!empty($Report_Row) && $is_summaryXLSX_exist && in_array($EmailAttachment,['onlyreport','both'])){
                $mail = $mail->attach(public_path('\\'.$folder).'\\'.$prefix.'RPS_'.$tname.'.xlsx', [
                    'as' => $prefix.'RPS_'.$tname.'.xlsx',
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]);
            }
            if(!empty($Report_Row) && $is_summaryPDF_exist && in_array($EmailAttachment,['onlyreport','both'])){
                $mail = $mail->attach(public_path('\\'.$folder).'\\'.$prefix.'RPS_'.$tname.'.pdf', [
                    'as' => $prefix.'RPS_'.$tname.'.pdf',
                    'mime' => 'application/pdf',
                ]);
            }

            return $mail;

        }catch (\Exception $exception){
            echo $exception->getMessage();
        }
    }
}