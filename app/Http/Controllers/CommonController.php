<?php
/**
 * Created by PhpStorm.
 * User: Gurpreet Singh
 * Date: 05-11-2021
 * Time: 04:43 PM
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Library\Ajax;
use App\Helpers\Helper;
use App\User;
use mysql_xdevapi\Schema;
use Validator;
use Auth;
use Crypt;
use DB;
use \Illuminate\Support\Facades\View as View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Session;
use Yajra\Datatables\Datatables;

class CommonController
{
    public $prefix;
    public $headerCells;

    public function __construct()
    {
        $this->prefix = config('constant.prefix');
        $this->headerCells = config('constant.XlsxHeaderCells');;

    }

    public function showEditable(Request $request,Ajax $ajax){
        $term = $request->input('term', '');
        $field = $request->input('field', '');
        $visible_column = Helper::getSingleColumn('Activity','Activity',$field);
        $sSql = str_replace('?term?',$term,$visible_column[0]['Editable_Show_Value_SQL']);
        $results = DB::select($sSql);
        $results = collect($results)->map(function($x){ return (array) $x; })->toArray();
        $json = array();
        foreach ($results as $result) {
            $keys = array_keys($result);
            $json[] = ['id' => $result[$keys[0]], 'label' => $result[$keys[1]], 'value' => $result[$keys[1]]];
        }
        return json_encode($json);

        /*select DS_MKC_ContactID,DFLName from contact where DFLName = 'Steve Kanai'

--old 3105060 -- new 4102486

 select * from sales_t2 where DS_MKC_ContactID= 4102486
update  s set s.DS_MKC_ContactID=x.DS_MKC_ContactID , s.DS_MKC_householdID=x.DS_MKC_householdid ,s.DFLName= x.DFLName, s.DFLName_Suggested=x.DFLName from sales_t2 s inner join contact x on x.DFLName ='Steve Kanai'
 where s.rowid= 17578*/
    }

    public function updateEditable(Request $request,Ajax $ajax){
        $field = $request->input('field', '');
        $field_value = $request->input('field_value', '');
        $primary_column_value = $request->input('primary_column_value', '');
        $visible_column = Helper::getSingleColumn('Activity','Activity',$field);
        $sSql = str_replace('?primary?',$primary_column_value,$visible_column[0]['Editable_Update_Value_SQL']);
        $sSql = str_replace('?field?',$field_value,$sSql);
        DB::update($sSql);
        return json_encode([]);
    }

}
