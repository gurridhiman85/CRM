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

    public static function implement_query($menu_level1,$reqlevel,$filters = [],$pagination,$add_sort_end = true,$sort = ''){
        $lLevel = Helper::getColumns($menu_level1,$reqlevel);
        $sort = empty($sort) ? $lLevel['sort'] : $sort;

        $levels = [
            $reqlevel => [
                'columns' => $lLevel['all_columns'],
                'visible_columns' => $lLevel['visible_columns'],
                'filter_columns' => $lLevel['filter_columns'],
                'sql'   => 'select '.implode(',',$lLevel['all_columns']).' from (SELECT *,ROW_NUMBER() OVER (ORDER BY (SELECT 1)) AS ROWNUMBER FROM '.$lLevel['table_name'].' ?where?) as t  ',
                'filter' => 1
            ]

        ];

        foreach ($levels as $level=>$level_values){
            if($level == $reqlevel){
                if($level_values['filter'] == 1){
                    $where = Helper::getFiltersCondition($filters,$reqlevel,$level_values['filter_columns']);
                    $sql = str_replace('?where?',$where['Where'],$level_values['sql']);
                    $sSql = $add_sort_end ? $sql.$pagination.' '.$sort : $sql.$pagination;

                    $nSql = $sql;
                }else{
                    $sql = str_replace('?where?','',$level_values['sql']);
                    $sSql = $add_sort_end ? $sql.$pagination.' '.$sort : $sql.$pagination;
                    $nSql = $sql;
                }

                $records = DB::select($sSql);
                $records = collect($records)->map(function($x){ return (array) $x; })->toArray();

                $total_records = count(DB::select($nSql));
                return [
                    'sql'  => $sSql,
                    'records' => $records,
                    'total_records' => $total_records,
                    'columns' => $level_values['columns'],
                    'visible_columns' => $level_values['visible_columns'],
                ];
            }
        }
    }

    public function getTaxonomy(Request $request,Ajax $ajax){
        $tabid = $request->input('tabid');
        $filters = $request->input('filters',[]);
        $page = $request->input('page',1);
        $records_per_page = 15;
        $position = ($page-1) * $records_per_page;
        $rType = $request->input('rtype','');
        $pagination = " WHERE ROWNUMBER > $position and ROWNUMBER <= " . ($position + $records_per_page);

        $tabName = 'Completed';
        $results = self::implement_query('Taxonomy',$tabid,$filters,$pagination,false,'');
        $data = [
            'records' => $results['records'],
            'visible_columns' => $results['visible_columns'],
            'tab' => $tabName,
            'filters' => json_encode($filters)
        ];

        if($rType == 'pagination'){
            $html = View::make('taxonomy.tabs.level.table',$data)->render();
        }else {
            $html = View::make('taxonomy.tabs.level.index', $data)->render();
        }

        $paginationhtml = View::make('taxonomy.tabs.level.pagination-html',[
            'total_records' => $results['total_records'],
            'records' => $results['records'],
            'position' => $position,
            'records_per_page' => $records_per_page,
            'page' => $page
        ])->render();

        return $ajax->success()
            ->appendParam('sql',$results['sql'])
            ->appendParam('records',$results['records'])
            ->appendParam('total_records',$results['total_records'])
            ->appendParam('records',$results['records'])
            ->appendParam('html',$html)
            ->appendParam('paginationHtml',$paginationhtml)
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
