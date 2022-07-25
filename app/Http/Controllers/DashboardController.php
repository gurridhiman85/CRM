<?php

namespace App\Http\Controllers;

use App\Library\Ajax;
use App\Model\Zchart;
use App\Model\Zchartdetails;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View as View;
use DB;
use Auth;
use Session;
use Illuminate\Support\Facades\URL;

class DashboardController extends Controller
{


    public function index(Request $request){

        if(!Auth::check() && !Session::get('dummyuserlogin')){
            Session::put('backUrl', URL::current());
            return redirect('dlogin');
        }

        /*$chartfilters1 = DB::select("SELECT distinct filter1  FROM Zchart_Details");
        $chartfilters2 = DB::select("SELECT distinct filter2  FROM Zchart_Details");
        $chartfilters3 = DB::select("SELECT distinct filter3  FROM Zchart_Details");
        $chartfilters4 = DB::select("SELECT distinct filter4  FROM Zchart_Details");
        $f1Options[] = $f2Options = $f3Options = $f4Options = '';
        foreach ($chartfilters1 as $chartfilter1){
            //$f1Options .= '<option value="'.$chartfilter1->filter1.'">'.$chartfilter1->filter1.'</option>';
            $f1Options[] = $chartfilter1->filter1;
        }
        foreach ($chartfilters2 as $chartfilter2){
            $f2Options .= '<option value="'.$chartfilter2->filter2.'">'.$chartfilter2->filter2.'</option>';
        }
        foreach ($chartfilters3 as $chartfilter3){
            $f3Options .= '<option value="'.$chartfilter3->filter3.'">'.$chartfilter3->filter3.'</option>';
        }
        foreach ($chartfilters4 as $chartfilter4){
            $f4Options .= '<option value="'.$chartfilter4->filter4.'">'.$chartfilter4->filter4.'</option>';
        }*/


        $chartLabels = DB::select("SELECT Notes1, Notes2, Notes3, Notes4  FROM ZChart_Links WHERE link = '".$request->segment(1)."'");
        $chartLabels = collect($chartLabels)->map(function($x){ return (array) $x; })->toArray();


        return view('dashboard.index',[
            'chartLabels' => $chartLabels,
            /*'f1Options' => $f1Options,
            'f2Options' => $f2Options,
            'f3Options' => $f3Options,
            'f4Options' => $f4Options*/
        ]);
    }

    public function getDashboardInfo(Request $request,Ajax $ajax){
        $filter1 = $request->input('filter1','All');
        $filter2 = $request->input('filter2','All');
        $filter3 = $request->input('filter3','All');
        $filter4 = $request->input('filter4','All');
        $filtertype = $request->input('filtertype','Donor');
        $aData = Zchart::where('Dashboard',$filtertype)
            ->get()
            ->toArray();

        $chartLabels = DB::select("SELECT Filter1_Label,Filter2_Label,Filter3_Label,Filter4_Label, Notes1, Notes2, Notes3, Notes4 FROM ZChart_Links where Dashboard = '$filtertype'");
        $chartLabels = collect($chartLabels)->map(function($x){ return (array) $x; })->toArray();

        $chartfilters1 = DB::select("SELECT distinct filter1  FROM Zchart_Details");
        $chartfilters2 = DB::select("SELECT distinct filter2  FROM Zchart_Details");

        $linechart_data = array();

        foreach($aData as $key=>$data){

            $linechart_data[$key]['chart_type'] = trim($data['chart_type']);
            $linechart_data[$key]['chart_position'] = trim($data['chart_position']);
            $linechart_data[$key]['chart_title'] = trim($data['chart_title']);
            $linechart_data[$key]['chart_legend1'] = trim($data['chart_legend1']);
            $linechart_data[$key]['chart_legend2'] = trim($data['chart_legend2']);
            $linechart_data[$key]['chart_legend3'] = trim($data['chart_legend3']);
            $linechart_data[$key]['chart_legend4'] = trim($data['chart_legend4']);
            $linechart_data[$key]['Legend1_Background_Color'] = trim($data['Legend1_Background_Color']);
            $linechart_data[$key]['Legend2_Background_Color'] = trim($data['Legend2_Background_Color']);
            $linechart_data[$key]['Legend3_Background_Color'] = trim($data['Legend3_Background_Color']);
            $linechart_data[$key]['Legend4_Background_Color'] = trim($data['Legend4_Background_Color']);
            $linechart_data[$key]['Legend5_Background_Color'] = trim($data['Legend5_Background_Color']);
            $linechart_data[$key]['Legend6_Background_Color'] = trim($data['Legend6_Background_Color']);
            $linechart_data[$key]['Legend1_Border_Color'] = trim($data['Legend1_Border_Color']);
            $linechart_data[$key]['Legend2_Border_Color'] = trim($data['Legend2_Border_Color']);
            $linechart_data[$key]['Legend3_Border_Color'] = trim($data['Legend3_Border_Color']);
            $linechart_data[$key]['Legend4_Border_Color'] = trim($data['Legend4_Border_Color']);
            $linechart_data[$key]['Legend5_Border_Color'] = trim($data['Legend5_Border_Color']);
            $linechart_data[$key]['Legend6_Border_Color'] = trim($data['Legend6_Border_Color']);
            $linechart_data[$key]['Format'] = trim($data['Format']);
            $linechart_data[$key]['Chart_Scale'] = trim($data['Chart_Scale']);

            $chart_id = $data['id'];
            $aCData = Zchartdetails::where('chart_id',$chart_id)
                ->where('Dashboard',$filtertype)
                ->where('filter1',$filter1)
                ->where('filter2',$filter2)
                /*->where('filter2',$filter2)
                ->where('filter3',$filter3)
                ->where('filter4',$filter4)*/
                ->orderBy('row_id')
                ->get()
                ->toArray();

            //$nSQL = DB::select("SELECT * FROM Zchart_Details where chart_id='".$chart_id."' and Dashboard = '$filtertype' and filter1='$filter1' and filter2='$filter2' and filter3='$filter3' and filter4='$filter4'");
            //$aCData = $oDb->executeSelect($nSQL);
            $detail = array();
            foreach($aCData as $ckey=>$dRata){


                $detail[$ckey]['chart_label'] = $dRata['chart_label'];


                $detail[$ckey]['chart_value1'] = $dRata['chart_value1'];
                $detail[$ckey]['chart_value2'] = $dRata['chart_value2'];
                $detail[$ckey]['chart_value3'] = $dRata['chart_value3'];
                $detail[$ckey]['chart_value4'] = $dRata['chart_value4'];
            }
            $linechart_data[$key]['chart_detail'] = $detail;
        }

        $html = View::make('dashboard.chart')->render();
        return $ajax->success()
            ->jscallback('loadstickypopup')
            ->appendParam('html',$html)
            ->appendParam('type','standard')
            ->appendParam('filter1',$filter1)
            ->appendParam('filter2',$filter2)
            ->appendParam('chartfilters1',$chartfilters1)
            ->appendParam('chartfilters2',$chartfilters2)
            ->appendParam('chartLabels',$chartLabels)
            ->appendParam('linechart_data',$linechart_data)
            ->response();
    }
}
