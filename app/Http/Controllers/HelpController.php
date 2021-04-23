<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\Ajax;
use App\User;
use Validator;
use Auth;
use Crypt;
use DB;
use \Illuminate\Support\Facades\View as View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;




class HelpController extends Controller
{
    public function index(){
        return view('help.index',[]);
    }

    public function getSection($section_id, Request $request, Ajax $ajax){
        $link = '';
        $sBaseUrl = config('constant.BaseUrl');
        //if($section_id == 'ajson2'){
            $link = $sBaseUrl. "help/CRM Square User Guide v5.4.pdf";
        //}

        return $ajax->success()
            ->appendParam('link',$link)
            ->jscallback('load_folder_contents')
            ->response();
    }
}
