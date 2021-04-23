<?php

namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class ShareCampaignEmail extends Mailable
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
            $lastPart = explode($this->recData->data->list_short_name,$this->recData->data->t_name);
            $filename = count($lastPart) > 0 ? $this->recData->data->promoexpo_file.$lastPart[1] : $this->recData->data->t_name;

            $mail = $this->view('mails.sharecampaign')->from('admin@crmsquare.com','CRM Square Administrator')->with(['recData' => $this->recData])->subject($this->recData->Sub);
            if(!empty($this->recData->Cc)){
                $mail = $mail->cc($this->recData->Cc);
            }

            if(!empty($this->recData->Bcc)) {
                $mail = $mail->bcc($this->recData->Bcc);
            }
            $is_list_exist = file_exists(public_path('\\'.$this->recData->data->promoexpo_folder).'\\'.$this->prefix.'CAL_'.$filename.'.xlsx');

            if($is_list_exist){
                $mail = $mail->attach(public_path('\\'.$this->recData->data->promoexpo_folder).'\\'.$this->prefix.'CAL_'.$filename.'.xlsx', [
                    'as' => $this->prefix.'CAL_'.$filename.'.xlsx',
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]);        // Add attachments
            }


            $Report_Row = $this->recData->data->Report_Row;
            $is_summary_existPDF = file_exists(public_path('\\'.$this->recData->data->promoexpo_folder).'\\'.$this->prefix.'CAM_'.$filename.'.pdf');

            $is_summary_existXLSX = file_exists(public_path('\\'.$this->recData->data->promoexpo_folder).'\\'.$this->prefix.'CAM_'.$filename.'.xlsx');

            if(!empty($Report_Row) && $is_summary_existPDF){
                $mail = $mail->attach(public_path('\\'.$this->recData->data->promoexpo_folder).'\\'.$this->prefix.'CAM_'.$filename.'.pdf', [
                    'as' => $this->prefix.'CAM_'.$filename.'.pdf',
                    'mime' => 'application/pdf',
                ]);
            }

            if(!empty($Report_Row) && $is_summary_existXLSX){
                $mail = $mail->attach(public_path('\\'.$this->recData->data->promoexpo_folder).'\\'.$this->prefix.'CAM_'.$filename.'.xlsx', [
                    'as' => $this->prefix.'CAM_'.$filename.'.xlsx',
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]);
            }

            return $mail;
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }

    }
}