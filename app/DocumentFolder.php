<?php

namespace App;

use App\Model\DocumentAttachment;
use Illuminate\Database\Eloquent\Model;
use Auth;

class DocumentFolder extends Model
{
    protected $table = 'document_folders';
    protected $primaryKey = 'folder_id';

    const ALL_MY_FOLDERS = 'C-1';
    const ALL_MY_DOCS_FOLDERS = 'C-2';
    const SHARED_WITH_ME = 'C-3';
    const SHARED_BY_ME = 'C-4';

    public function files(){
        return $this->hasMany(DocumentAttachment::class,'folder_id');
    }

    public function folders(){
        return $this->hasMany(self::class,'parent_id','folder_id');
    }

    public function getCanCreateAttribute(){
        if($this->u_dataid == Auth::user()->u_dataid){
            return true;
        }
        return false;
    }

    public function getCanEditAttribute(){
        if($this->u_dataid == Auth::user()->u_dataid){
            return true;
        }
        return false;
    }
    public function getCanDeleteAttribute(){
        if($this->u_dataid == Auth::user()->u_dataid){
            return true;
        }
        return false;
    }

    public function getCanShareAttribute(){
        if($this->u_dataid == Auth::user()->u_dataid && $this->is_private == 0){
            return true;
        }
        return false;
    }

    public function getSizeAttribute(){
        $files = $this->files()->get();

        $size = 0;
        $precision = 2;
        foreach ($files as $file){
            $size += $file->FileSize;
        }

        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }

    public function getFoldersCountAttribute(){
        $fCount = $this->folders()->count();
        return $fCount > 0 ? $fCount : 0;
    }

    public function getFilesCountAttribute(){
        $fCount = $this->files()->count();
        return $fCount > 0 ? $fCount : 0;
    }

    public function getCreatorAttribute(){
        return $this->user->FullName;
    }

    public function user(){
        return $this->hasOne(User::class,'u_dataid','u_dataid');
    }


    public static function folderJsStructure(){
        return [
            'id'       => "0", // required
            'parent'   => "#", // required
            'text'     => "Folder Name", // node text
            'icon'     => "fa fa-folder",
            'children' => true
        ];
    }

    public static function folderDataJsStructure(){
        $folder = Collect([]);
        $folder->put('id',base64_encode(self::ALL_MY_FOLDERS));
        $folder->put('parent','#');
        $folder->put('text','Folder Name');
        $folder->put('icon','fa fa-folder');
        $folder->put('folders',[]);
        $folder->put('files',[]);
        return $folder;
    }

    public static function all_my_folders(){
        $folder = self::folderJsStructure();
        $folder['id'] = base64_encode(self::ALL_MY_FOLDERS);
        $folder['text'] = 'All';
        return $folder;
    }

    public static function all_my_docs_folders(){
        $folder = self::folderJsStructure();
        $folder['id'] = base64_encode(self::ALL_MY_DOCS_FOLDERS);
        $folder['text'] = 'My Docs';
        return $folder;
    }

    public static function all_shared_with_me_folders(){
        $folder = self::folderJsStructure();
        $folder['id'] = base64_encode(self::SHARED_WITH_ME);
        $folder['text'] = 'Shared With Me';
        return $folder;
    }

    public static function all_shared_by_me_folders(){
        $folder = self::folderJsStructure();
        $folder['id'] = base64_encode(self::SHARED_BY_ME);
        $folder['text'] = 'Shared By Me';
        return $folder;
    }

    public static function getAllMyFolders($foldersDatum,$pid){
        $folder = self::folderDataJsStructure();
        $folder->put('id',base64_encode($foldersDatum->folder_id));
        $folder->put('text',$foldersDatum->folder_name);
        $folder->put('parent',base64_encode($pid));
        $fIcon = ($foldersDatum->FoldersCount + $foldersDatum->FilesCount) > 0 ? 'fa fa-folder' : 'far fa-folder';
        $folder->put('icon',$fIcon );
        $folder->put('CanEdit',$foldersDatum->CanEdit);
        $folder->put('CanDelete',$foldersDatum->CanDelete);
        $folder->put('folders',$foldersDatum->folders);
        $folder->put('files',$foldersDatum->files);
        return $folder;

    }
}
