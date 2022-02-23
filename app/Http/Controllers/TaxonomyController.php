<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Model\EmailConfiguration;
use App\Model\ReportTemplate;
use App\Model\UAFieldMapping;
use App\Model\XrefProductProductdes;
use Illuminate\Http\Request;
use App\Library\Ajax;
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
use Illuminate\Support\Facades\Artisan;
use Yajra\Datatables\Datatables;
use DateTime;


class TaxonomyController extends Controller
{
    public $db;
    public $prefix;

    public function __construct()
    {
        $this->db = DB::connection('sqlsrv');
        $this->prefix = config('constant.prefix');
    }

    public function index(){

        $levels = UAFieldMapping::distinct()
            ->where('menu_level1', 'Taxonomy')
            ->orderBy('menu_level2')
            ->pluck('menu_level2')
            ->toArray();

        foreach ($levels as $level){
            $lLevelColumns = Helper::getColumns('Taxonomy',$level);
            $lLevelFilters[$level] = Helper::getFilterValues($lLevelColumns['visible_columns']);
        }

        return view('taxonomy.index',[
            'lLevelFilters'       => $lLevelFilters,
            'alllevels' => $levels
        ]);
    }

    public static function implement_query($reqlevel,$filters = []){

        $lLevel = Helper::getColumns('Taxonomy',$reqlevel);

        $levels = [
            $reqlevel => [
                'columns' => $lLevel['all_columns'],
                'visible_columns' => $lLevel['visible_columns'],
                'sql'   => 'select '.implode(',',$lLevel['all_columns']).' from '.$lLevel['table_name'],
                'filter' => 1
            ],
        ];

        foreach ($levels as $level=>$level_values){
            if($level == $reqlevel){
                if($level_values['filter'] == 1){
                    $where = Helper::getFiltersCondition($filters,$reqlevel,$level_values['visible_columns']);
                    $sql = $level_values['sql']." ".$where['Where'];
                }else{
                    $sql = $level_values['sql'];
                }

                $records = DB::select($sql);
                $records = collect($records)->map(function($x){ return (array) $x; })->toArray();
                return [
                    'sql'  => $sql,
                    'records' => $records,
                    'columns' => $level_values['columns'],
                    'visible_columns' => $level_values['visible_columns'],
                ];
            }
        }
    }

    public function getTaxonomy(Request $request,Ajax $ajax){
        $tabid = $request->input('tabid');
        $filters = $request->input('filters',[]);

        $tabName = 'Completed';
        $results = self::implement_query($tabid,$filters);
        $data = [
            'records' => $results['records'],
            'visible_columns' => $results['visible_columns'],
            'tab' => $tabName,
            'filters' => json_encode($filters)
        ];

        $html = View::make('taxonomy.tabs.level.index',$data)->render();

        return $ajax->success()
            ->appendParam('records',$results['records'])
            ->appendParam('html',$html)
            ->appendParam('paginationHtml','')
            ->jscallback('load_ajax_tab')
            ->response();
    }

    public function quickUpdate(Request $request,Ajax $ajax){
        try{

            $primary_colum = $request->input('primary_colum');
            $primary_column_value = $request->input('primary_column_value');
            $fieldname = $request->input('fieldname');
            $fieldvalue = $request->input('fieldvalue');

            $record = XrefProductProductdes::where($primary_colum,$primary_column_value)->first();
            if($record){
                XrefProductProductdes::where($primary_colum,$primary_column_value)->update([$fieldname => $fieldvalue]);
            }
            $record1 = XrefProductProductdes::where($primary_colum,$primary_column_value)->first();
            return $ajax->success()
                ->appendParam('record',$record1)
                ->jscallback('ajax_field_update')
                ->response();
        }catch (\Exception $exception){
            return $ajax->fail()
                ->message($exception->getMessage())
                ->response();
        }

    }

    public function download(Request $request,Ajax $ajax){
        $level = $request->input('tab');
        $filters = $request->input('filters',[]);
        $downloadableColumns = json_decode($request->input('downloadableColumns',''));
        $results = self::implement_query($level,$filters);

        $view = View::make('taxonomy.xlsx',[
            'reqlevel' => $level,
            'records' => $results['records'],
            'downloadColumnsIndex' => $downloadableColumns,
            'columns' => $results['columns'],
            'visible_columns' => $results['visible_columns']
        ])->render();


        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadhseet = $reader->loadFromString($view);
        $sheet = $spreadhseet->getActiveSheet();
        $spreadhseet->setActiveSheetIndex(0);
        $spreadhseet->getActiveSheet()->setTitle(ucfirst($level));

        $spreadhseet->setActiveSheetIndex(0);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadhseet, 'Xlsx');

        $file_Name =  $this->prefix.ucfirst($level) . '_' . date('Y') . date('m') . date('d');

        $writer->save(public_path()."\\downloads\\".$file_Name.'.xlsx');

        $sBaseUrl = config('constant.BaseUrl');
        return $ajax->success()
            ->appendParam('html',$view)
            ->appendParam('download_url',$sBaseUrl . "downloads/" . $file_Name . '.xlsx')
            ->jscallback('ajax_download_file')
            ->response();
    }

}
