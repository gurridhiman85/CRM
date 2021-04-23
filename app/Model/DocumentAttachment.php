<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class DocumentAttachment extends Model
{
    use SoftDeletes;
    protected $table = 'document_attachments';

    public static function getMyFiles($pid){
        $files = array();

        $filesData = DocumentAttachment::where('folder_id',$pid)->where('u_dataid', Auth::user()->u_dataid)->get();
        if ($filesData->count() > 0) {
            foreach ($filesData as $file) {
                array_push($files, $file);
            }
        }
        return $files;
    }

    public function getFilePathAttribute(){
        $path = public_path('\ds_attachments\documents\\'.$this->attachment_url);
        return $path;
    }

    public function getFileSizeAttribute(){
        $fileSizeBytes = filesize($this->FilePath);
        return $fileSizeBytes;
    }
}
