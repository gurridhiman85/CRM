<?php
namespace App\Helpers;
use Auth;
use Intervention\Image\Image;

class FileUpload
{
    public static function uploadSingle($file,$destination,$thumbnailOn = 0) {
        $a_title = $file->getClientOriginalName();
        $a_url = sha1(time().'_'.$file->getClientOriginalName()).'.'.$file->getClientOriginalExtension();
        $type = 'user';
        $file->move($destination, $a_url);
        if($thumbnailOn == 1 && in_array($file->getClientOriginalExtension(),['jpg','jpeg','gif','png'])){
            self::createThumbnail($a_url,$destination,$destination.'/thumb/');
        }
        $data = [
            'u_dataid' => Auth::user()->u_dataid,
            'type' => $type,
            'attachment_title' => $a_title,
            'attachment_url' => $a_url,
            'thumbnail_on' => $thumbnailOn
        ];
        return $data;
    }

    public static function uploadMultiple($files,$destination,$thumbnailOn = 0) {
        $data = [];
        foreach($files as $file)
        {
            $a_title = $file->getClientOriginalName();
            $a_url = sha1(time().'_'.$file->getClientOriginalName()).'.'.$file->getClientOriginalExtension();
            $type = 'user';
            $file->move($destination, $a_url);
            if($thumbnailOn == 1 && in_array($file->getClientOriginalExtension(),['jpg','jpeg','gif','png'])){
                self::createThumbnail($a_url,$destination,$destination.'/thumb/');
            }
            $data[] = [
                'u_dataid' => Auth::user()->u_dataid,
                'type' => $type,
                'attachment_title' => $a_title,
                'attachment_url' => $a_url,
                'thumbnail_on' => $thumbnailOn
            ];
        }
        return $data;
    }

    public static function createThumbnail($filename,$largeSizePath, $thumbPth) {

        $final_width_of_image = 600;
        $path_to_image_directory = $largeSizePath;
        $path_to_thumbs_directory = $thumbPth;

        if(preg_match('/[.](jpg)$/', $filename)) {
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);
        } else if (preg_match('/[.](gif)$/', $filename)) {
            $im = imagecreatefromgif($path_to_image_directory . $filename);
        } else if (preg_match('/[.](png)$/', $filename)) {
            $im = imagecreatefrompng($path_to_image_directory . $filename);
        }

        $ox = imagesx($im);
        $oy = imagesy($im);

        $nx = $final_width_of_image;
        $ny = floor($oy * ($final_width_of_image / $ox));

        $nm = imagecreatetruecolor($nx, $ny);

        imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);

        if(!file_exists($path_to_thumbs_directory)) {
            if(!mkdir($path_to_thumbs_directory)) {
                //die("There was a problem. Please try again!");
            }
        }

        imagejpeg($nm, $path_to_thumbs_directory . $filename);
    }

    public static function is_base64($s)
    {
        return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s);
    }
}
?>