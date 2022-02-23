<?php
namespace App\Helpers;
use App\Mail\ShareCampaignEmail;
use App\Model\CampaignTemplate;
use App\Model\ReportTemplate;
use App\Model\UAFieldMapping;
use Auth;
use Intervention\Image\Image;
use DB;
use PDF;
use App\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Mail\ShareReportEmail;
use Illuminate\Support\Facades\Mail;

class Helper
{
    /**
     * Created By : Gurpreet Singh
     * Purpose : Get dynamic pagination
     *
     * @param $page
     * @param $records
     * @param $total_records
     * @param $title
     * @param $record_per_page
     * @return string
     */
    public static function ajax_pagination($page,$records,$total_records,$title,$record_per_page) {
        $prev = $page - 1;
        $next = $page + 1;
        $adjacents = "2";
        $lastpage = ceil($total_records/$record_per_page);
        $lpm1 = $lastpage - 1;
        $pagination = "";
        if($lastpage > 1)
        {
            $pagination .= "<div class='pagination'>";
            if ($page > 1)
                $pagination.= "<a href=\"#Page=".($prev)."\" onClick='changePagination(".($prev).");'>&laquo; Previous&nbsp;&nbsp;</a>";
            else
                $pagination.= "<span class='disabled'>&laquo; Previous&nbsp;&nbsp;</span>";
            if ($lastpage < 7 + ($adjacents * 2))
            {
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class='current'>$counter</span>";
                    else
                        $pagination.= "<a href=\"#Page=".($counter)."\" onClick='changePagination(".($counter).");'>$counter</a>";

                }
            }

            elseif($lastpage > 5 + ($adjacents * 2))
            {
                if($page < 1 + ($adjacents * 2))
                {
                    for($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                    {
                        if($counter == $page)
                            $pagination.= "<span class='current'>$counter</span>";
                        else
                            $pagination.= "<a href=\"#Page=".($counter)."\" onClick='changePagination(".($counter).");'>$counter</a>";
                    }
                    $pagination.= "...";
                    $pagination.= "<a href=\"#Page=".($lpm1)."\" onClick='changePagination(".($lpm1).");'>$lpm1</a>";
                    $pagination.= "<a href=\"#Page=".($lastpage)."\" onClick='changePagination(".($lastpage).");'>$lastpage</a>";

                }
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $pagination.= "<a href=\"#Page=\"1\"\" onClick='changePagination(1);'>1</a>";
                    $pagination.= "<a href=\"#Page=\"2\"\" onClick='changePagination(2);'>2</a>";
                    $pagination.= "...";
                    for($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if($counter == $page)
                            $pagination.= "<span class='current'>$counter</span>";
                        else
                            $pagination.= "<a href=\"#Page=".($counter)."\" onClick='changePagination(".($counter).");'>$counter</a>";
                    }
                    $pagination.= "..";
                    $pagination.= "<a href=\"#Page=".($lpm1)."\" onClick='changePagination(".($lpm1).");'>$lpm1</a>";
                    $pagination.= "<a href=\"#Page=".($lastpage)."\" onClick='changePagination(".($lastpage).");'>$lastpage</a>";
                }
                else
                {
                    $pagination.= "<a href=\"#Page=\"1\"\" onClick='changePagination(1);'>1</a>";
                    $pagination.= "<a href=\"#Page=\"2\"\" onClick='changePagination(2);'>2</a>";
                    $pagination.= "..";
                    for($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if($counter == $page)
                            $pagination.= "<span class='current'>$counter</span>";
                        else
                            $pagination.= "<a href=\"#Page=".($counter)."\" onClick='changePagination(".($counter).");'>$counter</a>";
                    }
                }
            }
            if($page < $counter - 1)
                $pagination.= "<a href=\"#Page=".($next)."\" onClick='changePagination(".($next).");'>Next &raquo;</a>";
            else
                $pagination.= "<span class='disabled'>Next &raquo;</span>";

            $pagination.= "</div>";
            return $pagination;
        }

    }

    /**
     * Created By : Gurpreet Singh
     * Purpose : Get dynamic pagination version 2
     *
     * @param $page
     * @param $position
     * @param $record_per_page
     * @param $records
     * @param $total_records
     * @return string
     */
    public static function ajax_pagination_v2($page,$position,$record_per_page,$records,$total_records){
        $pagination = '';

        /************ Previous button ----- *********************/
        if($page == 1){
            $pagination .= '<a class="paginate_button" aria-controls="taskList" )=""><i class="fa fa-chevron-left"></i></a>';
        }elseif ($page > 1){
            $pagination .= '<a class="paginate_button" aria-controls="taskList" data-idx="'.($page - 1).'" tabindex="'.($page - 1).'" onclick="pagination_v2(this,\'All\')"><i class="fa fa-chevron-left"></i></a>';
        }
        /************ Previous button ----- *********************/

        $pagination .= '<b>'.($position + 1).'</b> - <b>'.($total_records >= $record_per_page ? $record_per_page : $total_records).' of '.$total_records.'</b>';


        /************ Next button ----- *********************/
        if(($total_records) > $record_per_page){
            $pagination .= '<a class="paginate_button" aria-controls="taskList" data-idx="'.($page + 1).'" tabindex="'.($page + 1).'" onclick="pagination_v2(this,\'All\')"><i class="fa fa-chevron-right"></i></a>';
        }else{
            $pagination .= '<a class="paginate_button" aria-controls="taskList" )=""><i class="fa fa-chevron-right"></i></a>';
        }
        /************ Next button ----- *********************/
        return $pagination;
    }

    /**
     * Created By : Gurpreet Singh
     * Purpose    : To get particular value from associative array
     *
     * @param null $parent
     * @param $module
     * @param $right
     * @param $array
     * @return null
     */
    public static function searchForId($parent = null,$module,$right, $array) {
        foreach ($array as $key => $val) {
            if ($val['parents'] === $parent && $val['module'] === $module && $val['rights_name'] == $right) {
                return $val['is_rights'];
            }
        }
        return null;
    }

    /**
     * Created By : Gurpreet Singh
     * Purpose    : To check the permission for active user.
     *
     * @param null $parent
     * @param $module
     * @param $right
     * @return bool
     */
    public static function CheckPermission($parent = null,$module,$right){
        $permissions = Auth::user()->permissions;
        foreach ($permissions as $key => $val) {
            if ($val->parents === $parent && $val->module === $module && $val->rights_name == $right) {
                return $val->is_rights == 1 ? true : false;
            }
        }
        return false;
    }

    public static function checkVisiblities($email){
        $sSql = DB::select("SELECT Visibilities,User_Type FROM User_Authenticate WHERE User_Email = '".trim($email)."'");
        $aData = collect($sSql)->map(function($x){ return (array) $x; })->toArray();
        $Visibilities = [];
        if(count($aData) > 0){
            $Visibilities = !empty($aData[0]['Visibilities']) ? explode(',',$aData[0]['Visibilities']) : [];
        }
        return $Visibilities;
    }

    public static function pagination_v1($total_records,$records_per_page,$page,$type,$position =0,$taskcount = 0){
        $start = ($page - 1) * $records_per_page;
        $prev = $page - 1;
        $next = $page + 1;
        $pagination = "";
        $lastpage = ceil($total_records / $records_per_page);
        if($lastpage > 1){
            $pagination .= "<div class='dataTables_paginate paging_simple_numbers mlst-pgn-poschn' id='taskList_paginate'>";

            if($prev == 0){
                $pagination .= "<a class='paginate_button disabled' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fa fa-chevron-left p-1'></i></a>";
            } else {
                $pagination .= "<a class='paginate_button' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='".($prev)."' onclick=pagination_v2(this,'$type')><i class='fa fa-chevron-left p-1'></i></a>";
            }
            $nPos = $position + 1;
            if(($taskcount >= $records_per_page) && $position == 0) {
                $pagination .= " <b>" . $nPos . "</b> - <b>" . $records_per_page . " of " . $total_records ."</b>";
            } else if(($taskcount >= $records_per_page) && $position > 0) {
                $recds = $position + $taskcount;
                $pagination .= " <b>" . $nPos. "</b> - <b>" . $recds . " of " . $total_records ."</b>";
            } else {
                $recds = $position + $taskcount;
                $pagination .= " <b>" . $nPos . "</b> - <b>" . $recds . " of " . $total_records ."</b>";
            }

            if($next == ($lastpage + 1)){
                $pagination .= " <a class='paginate_button disabled' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fa fa-chevron-right p-1'></i></a>";
            } else {
                $pagination .= " <a class='paginate_button' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='".($next)."' tabindex='" .($next). "' onclick=pagination_v2(this,'$type')><i class='fa fa-chevron-right p-1'></i></a>";
            }

            $pagination .="</div>";
        }
        return $pagination;
    }

    public static function pagination_v2($total_records,$records_per_page,$page,$type,$position =0,$taskcount = 0,$funCnt = 2){
        $start = ($page - 1) * $records_per_page;
        $prev = $page - 1;
        $next = $page + 1;
        $pagination = "";
        $lastpage = ceil($total_records / $records_per_page);
        if($lastpage > 1){
            $pagination .= "<div class='dataTables_paginate paging_simple_numbers mlst-pgn-poschn' id='taskList_paginate'>";

            if($prev == 0){
                $pagination .= "<a class='paginate_button disabled mr-1' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fas fa-angle-double-left p-1' style='color: #b7dee8;'></i></a>";

                $pagination .= "<a class='paginate_button disabled' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fa fa-chevron-left p-1' style='color: #b7dee8;'></i></a>";

            } else {
                $pagination .= "<a class='paginate_button mr-1' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='1' onclick=pagination_v".$funCnt."(this,'$type')><i class='fas fa-angle-double-left p-1' style='color: #b7dee8;'></i></a>";

                $pagination .= "<a class='paginate_button' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='".($prev)."' onclick=pagination_v".$funCnt."(this,'$type')><i class='fa fa-chevron-left p-1' style='color: #b7dee8;'></i></a>";
            }
            $nPos = $position + 1;
            if(($taskcount >= $records_per_page) && $position == 0) {
                $pagination .= " <b>" . $nPos . "</b> - <b>" . $records_per_page . " of " . $total_records ."</b>";
            } else if(($taskcount >= $records_per_page) && $position > 0) {
                $recds = $position + $taskcount;
                $pagination .= " <b>" . $nPos. "</b> - <b>" . $recds . " of " . $total_records ."</b>";
            } else {
                $recds = $position + $taskcount;
                $pagination .= " <b>" . $nPos . "</b> - <b>" . $recds . " of " . $total_records ."</b>";
            }

            if($next == ($lastpage + 1)){
                $pagination .= " <a class='paginate_button disabled' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fa fa-chevron-right p-1' style='color: #b7dee8;'></i></a>";

                $pagination .= " <a class='paginate_button disabled' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fas fa-angle-double-right p-1' style='color: #b7dee8;'></i></a>";

            } else {
                $pagination .= " <a class='paginate_button' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='".($next)."' tabindex='" .($next). "' onclick=pagination_v".$funCnt."(this,'$type')><i class='fa fa-chevron-right p-1' style='color: #b7dee8;'></i></a>";

                $pagination .= " <a class='paginate_button' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='".($lastpage)."' tabindex='" .($lastpage). "' onclick=pagination_v".$funCnt."(this,'$type')><i class='fas fa-angle-double-right p-1' style='color: #b7dee8;'></i></a>";
            }

            $pagination .="</div>";
        }
        return $pagination;
    }

    public static function print_report_datatable_NPCS($aData, $col, $sum)
    {
        $xHeaders = $bHeaders = $xVal = array();
        $strHTML = "";
        $i = 0;
        if (!empty($aData)) {
            /** Header - Section - start */
            if ($i == 0) {
                $strHTML = "<table";

                $strHTML .= " class='table table-bordered table-hover color-table sr-table'><thead><tr>";
                /*********** Number - column header *******/
                $colspan = '';
                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i == 0) {
                        $strHTML .= "<th rowspan='2' style='text-align:left;'>$k</th>";
                        array_push($xHeaders,
                            [
                                'rowspan' => 2,
                                'colspan'=>0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);
                    } else if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>Count</th>";
                        $colspan = "Full";
                        array_push($xHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> (count($aData[0]) - 1),
                                'label' => "Count",
                                'type' => is_numeric($col) ? 'integer': 'string'
                            ]);
                    }
                    $i++;
                }
                $colspan = '';
                foreach ($aData[0] as $k => $v) {
                    if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>".$sum."</th>";
                        $colspan = "Full";
                    }

                }
                /*********** Percent - column header *******/
                $strHTML .= "</tr><tr>";


                $secondHeader = ['Number','Percent'];
                $secondHeader2 = ['Sum','Percent'];
                $l = 0;
                foreach ($secondHeader as $v) {
                    $strHTML .= "<th style='text-align:right;'>$v</th>";
                    if($l == 0){
                        array_push($bHeaders,
                        [
                            'rowspan' => 0,
                            'colspan'=> 0,
                            'label' => $v,
                            'type' => is_numeric($v) ? 'integer': 'string'
                        ]);
                    }
                    $l++;
                }

                foreach ($secondHeader2 as $v) {
                    $strHTML .= "<th style='text-align:right;'>$v</th>";
                }
                $strHTML .= "</tr></thead><tbody>";
            }
            /** Header - Section - End */

            $rowcls = "odd";
            $columns_total = array();
            $rows_total = 0;
            $grand_total1 = 0;
            $grand_total2 = 0;

            foreach ($aData as $r=>$row) {
                $rowcls = ($rowcls == "odd") ? "even" : "odd";

                $rows_total1 = 0;
                $rows_total2 = 0;
                $j = 0;
                $nRow = $row;
                foreach ($nRow as $rKey => $cell) {
                    if ($j > 0 && $j < 2) {
                        $rows_total1 += $cell;
                    }else if ($j == 2) {
                        $rows_total2 += $cell;
                    }
                    $j++;
                }
                $grand_total1 += $rows_total1;
                $grand_total2 += $rows_total2;
            }

            foreach ($aData as $r=>$row) {
                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $strHTML .= "<tr class='$rowcls'>";
                $rows_total = 0;
                $j = 0;
                $nRow = $row;
                foreach ($nRow as $rKey => $cell) {

                    if ($j > 0 && $j < 2) {

                        $rows_total += $cell;
                        if(!isset($columns_total[$rKey])){
                            $columns_total[$rKey] = $cell;
                        }else{
                            $columns_total[$rKey] += $cell;
                        }

                        if($rKey == 'Distribution') continue;
                        $strHTML .= "<td style='text-align:right;'>" . number_format($cell) . "</td>";

                        $cell = ($cell > 0) ? ($cell / $grand_total1) * 100 : 0;
                        $strHTML .= "<td style='text-align:right;'>" . round($cell) . "%</td>";
                    }else if ($j == 2) {

                        $rows_total += $cell;
                        if(!isset($columns_total[$rKey])){
                            $columns_total[$rKey] = $cell;
                        }else{
                            $columns_total[$rKey] += $cell;
                        }

                        if($rKey == 'Distribution') continue;
                        $strHTML .= "<td style='text-align:right;'>" . number_format($cell) . "</td>";

                        $cell = ($cell > 0) ? ($cell / $grand_total2) * 100 : 0;
                        $strHTML .= "<td style='text-align:right;'>" . round($cell) . "%</td>";
                    } else {
                        $strHTML .= "<td style='text-align:left;'>" . $cell . "</td>";
                    }
                    $j++;
                }
                array_push($xVal,$row);

                $strHTML .= "</tr>";

            }
            array_push($xVal,['Total',$columns_total,'Total'=>$grand_total1]);
            $strHTML .= "<tr class='totalCL $rowcls'>";
            $strHTML .= "<td style='text-align:left;'>Total</td>";

            $l = 0;
            foreach ($columns_total as $key=>$column) {
                if($l == 0){
                    $strHTML .= "<td style='text-align:right;'>" . number_format($column) . "</td>";

                    if($key === count($columns_total)){  continue; }
                    $column = ($column / $grand_total1) * 100;
                    $strHTML .= "<td style='text-align:right;'>" . round($column) . "%</td>";
                }
                $l++;
            }

            $l = 0;
            foreach ($columns_total as $key=>$column) {
                if($l == 1){
                $strHTML .= "<td style='text-align:right;'>" . number_format($column) . "</td>";

                if($key === count($columns_total)){  continue; }
                $column = $column > 0 ? ($column / $grand_total2) * 100 : 0;
                $strHTML .= "<td style='text-align:right;'>" . round($column) . "%</td>";
                }
                $l++;
            }

            $strHTML .= "</tr>";
            $strHTML .= "</tbody></table>";
        }
        return ['html' => $strHTML,'xHeaders' => $xHeaders,'bHeaders' => $bHeaders,'val' => $xVal,'postfix' => 'Percent_of_Row_Total'];
    }

    public static function print_report_datatable_PNCS($aData, $col, $sum)
    {
        $xHeaders = $bHeaders = $xVal = array();
        $strHTML = "";
        $i = 0;
        if (!empty($aData)) {
            /** Header - Section - start */
            if ($i == 0) {
                $strHTML = "<table";

                $strHTML .= " class='table table-bordered table-hover color-table sr-table'><thead><tr>";
                /*********** Number - column header *******/
                $colspan = '';
                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i == 0) {
                        $strHTML .= "<th rowspan='2' style='text-align:left;'>$k</th>";
                        array_push($xHeaders,
                            [
                                'rowspan' => 2,
                                'colspan'=>0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);
                    } else if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>Count</th>";
                        $colspan = "Full";
                        array_push($xHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> (count($aData[0]) - 1),
                                'label' => "Count",
                                'type' => is_numeric($col) ? 'integer': 'string'
                            ]);
                    }
                    $i++;
                }
                $colspan = '';
                foreach ($aData[0] as $k => $v) {
                    if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>".$sum."</th>";
                        $colspan = "Full";
                    }

                }
                /*********** Percent - column header *******/
                $strHTML .= "</tr><tr>";


                $secondHeader = ['Number','Percent'];
                $secondHeader2 = ['Sum','Percent'];
                $l = 0;
                foreach ($secondHeader as $v) {
                    $strHTML .= "<th style='text-align:right;'>$v</th>";
                    if($l > 0){
                        array_push($bHeaders,
                        [
                            'rowspan' => 0,
                            'colspan'=> 0,
                            'label' => $v,
                            'type' => is_numeric($v) ? 'integer': 'string'
                        ]);
                    }
                    $l++;

                }

                foreach ($secondHeader2 as $v) {
                    $strHTML .= "<th style='text-align:right;'>$v</th>";
                }
                $strHTML .= "</tr></thead><tbody>";
            }
            /** Header - Section - End */

            $rowcls = "odd";
            $columns_total = array();
            $rows_total = 0;
            $grand_total1 = 0;
            $grand_total2 = 0;

            foreach ($aData as $r=>$row) {
                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $rows_total1 = 0;
                $rows_total2 = 0;
                $j = 0;
                $nRow = $row;
                foreach ($nRow as $rKey => $cell) {
                    if ($j > 0 && $j < 2) {
                        $rows_total1 += $cell;
                    }else if ($j == 2) {
                        $rows_total2 += $cell;
                    }
                    $j++;
                }
                $grand_total1 += $rows_total1;
                $grand_total2 += $rows_total2;
            }

            foreach ($aData as $r=>$row) {
                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $strHTML .= "<tr class='$rowcls'>";
                $rows_total = 0;
                $j = 0;
                $nRow = [];
                foreach ($row as $rKey => $cell) {

                    if ($j > 0 && $j < 2) {

                        $rows_total += $cell;
                        if(!isset($columns_total[$rKey])){
                            $columns_total[$rKey] = $cell;
                        }else{
                            $columns_total[$rKey] += $cell;
                        }

                        if($rKey == 'Distribution') continue;
                        $strHTML .= "<td style='text-align:right;'>" . number_format($cell) . "</td>";

                        $cell = ($cell > 0) ? ($cell / $grand_total1) * 100 : 0;
                        $strHTML .= "<td style='text-align:right;'>" . round($cell) . "%</td>";
                        $nRow[1] = round($cell);
                    }else if ($j == 2) {

                        $rows_total += $cell;
                        if(!isset($columns_total[$rKey])){
                            $columns_total[$rKey] = $cell;
                        }else{
                            $columns_total[$rKey] += $cell;
                        }

                        if($rKey == 'Distribution') continue;
                        $strHTML .= "<td style='text-align:right;'>" . number_format($cell) . "</td>";

                        $cell = ($cell > 0) ? ($cell / $grand_total2) * 100 : 0;
                        $strHTML .= "<td style='text-align:right;'>" . round($cell) . "%</td>";

                    } else {
                        $nRow[0] = $cell;
                        $strHTML .= "<td class='left-side-cell' style='text-align:left;'>" . $cell . "</td>";
                    }
                    $j++;
                }

                array_push($xVal,$nRow);

                $strHTML .= "</tr>";

            }
            array_push($xVal,['Total',$columns_total,'Total'=>$grand_total1]);

            $strHTML .= "<tr class='totalCL $rowcls'>";
            $strHTML .= "<td class='left-side-cell' style='text-align:left;'>Total</td>";

            $l = 0;
            foreach ($columns_total as $key=>$column) {
                if($l == 0){
                    $strHTML .= "<td style='text-align:right;'>" . number_format($column) . "</td>";

                    if($key === count($columns_total)){  continue; }
                    $column = ($column / $grand_total1) * 100;
                    $strHTML .= "<td style='text-align:right;'>" . round($column) . "%</td>";
                }
                $l++;
            }

            $l = 0;
            foreach ($columns_total as $key=>$column) {
                if($l == 1){
                    $strHTML .= "<td style='text-align:right;'>" . number_format($column) . "</td>";

                    if($key === count($columns_total)){  continue; }
                    $column = ($column / $grand_total2) * 100;
                    $strHTML .= "<td style='text-align:right;'>" . round($column) . "%</td>";
                }
                $l++;
            }

            $strHTML .= "</tr>";
            $strHTML .= "</tbody></table>";
        }
        return ['html' => $strHTML,'xHeaders' => $xHeaders,'bHeaders' => $bHeaders,'val' => $xVal,'postfix' => 'Percent_of_Row_Total'];
    }

    public static function print_report_datatable_NPSC($aData, $col, $sum)
    {
        $xHeaders = $bHeaders = $xVal = array();
        $strHTML = "";
        $i = 0;
        if (!empty($aData)) {
            /** Header - Section - start */
            if ($i == 0) {
                $strHTML = "<table";

                $strHTML .= " class='table table-bordered table-hover color-table sr-table'><thead><tr>";
                /*********** Number - column header *******/
                $colspan = '';
                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i == 0) {
                        $strHTML .= "<th rowspan='2' style='text-align:left;'>$k</th>";
                        array_push($xHeaders,
                            [
                                'rowspan' => 2,
                                'colspan'=>0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);
                    } else if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>Count</th>";
                        $colspan = "Full";
                        array_push($xHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> (count($aData[0]) - 1),
                                'label' => "Count",
                                'type' => is_numeric($col) ? 'integer': 'string'
                            ]);
                    }
                    $i++;
                }
                $colspan = '';
                foreach ($aData[0] as $k => $v) {
                    if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>".$sum."</th>";
                        $colspan = "Full";
                    }

                }
                /*********** Percent - column header *******/
                $strHTML .= "</tr><tr>";


                $secondHeader = ['Number','Percent'];
                $secondHeader2 = ['Sum','Percent'];

                foreach ($secondHeader as $v) {
                    $strHTML .= "<th style='text-align:right;'>$v</th>";

                }
                $l = 0;
                foreach ($secondHeader2 as $v) {
                    $strHTML .= "<th style='text-align:right;'>$v</th>";
                    if($l == 0){
                        array_push($bHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> 0,
                                'label' => $v,
                                'type' => is_numeric($v) ? 'integer': 'string'
                            ]);
                    }
                    $l++;
                }
                $strHTML .= "</tr></thead><tbody>";
            }
            /** Header - Section - End */

            $rowcls = "odd";
            $columns_total = array();
            $rows_total = 0;
            $grand_total1 = 0;
            $grand_total2 = 0;

            foreach ($aData as $r=>$row) {
                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $rows_total1 = 0;
                $rows_total2 = 0;
                $j = 0;
                $nRow = $row;
                foreach ($nRow as $rKey => $cell) {
                    if ($j > 0 && $j < 2) {
                        $rows_total1 += $cell;
                    }else if ($j == 2) {
                        $rows_total2 += $cell;
                    }
                    $j++;
                }
                $grand_total1 += $rows_total1;
                $grand_total2 += $rows_total2;
            }

            foreach ($aData as $r=>$row) {
                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $strHTML .= "<tr class='$rowcls'>";
                $rows_total = 0;
                $j = 0;
                $nRow = [];
                foreach ($row as $rKey => $cell) {

                    if ($j > 0 && $j < 2) {

                        $rows_total += $cell;
                        if(!isset($columns_total[$rKey])){
                            $columns_total[$rKey] = $cell;
                        }else{
                            $columns_total[$rKey] += $cell;
                        }

                        if($rKey == 'Distribution') continue;
                        $strHTML .= "<td style='text-align:right;'>" . number_format($cell) . "</td>";

                        $cell = ($cell > 0) ? ($cell / $grand_total1) * 100 : 0;
                        $strHTML .= "<td style='text-align:right;'>" . round($cell) . "%</td>";
                    }else if ($j == 2) {

                        $rows_total += $cell;
                        if(!isset($columns_total[$rKey])){
                            $columns_total[$rKey] = $cell;
                        }else{
                            $columns_total[$rKey] += $cell;
                        }

                        if($rKey == 'Distribution') continue;
                        $strHTML .= "<td style='text-align:right;'>" . number_format($cell) . "</td>";
                        $nRow[1] = $cell;
                        $cell = ($cell > 0) ? ($cell / $grand_total2) * 100 : 0;
                        $strHTML .= "<td style='text-align:right;'>" . round($cell) . "%</td>";
                    } else {
                        $strHTML .= "<td class='left-side-cell' style='text-align:left;'>" . $cell . "</td>";
                        $nRow[0] = $cell;
                    }
                    $j++;
                }
                array_push($xVal,$nRow);

                $strHTML .= "</tr>";

            }
            array_push($xVal,['Total',$columns_total,'Total'=>$grand_total1]);
            /*if(!isset($columns_total[(count($columns_total) + 1)])){
                $columns_total[(count($columns_total) + 1)] = $grand_total;
            }else{
                $columns_total[(count($columns_total) + 1)] += $grand_total;
            }*/

            $strHTML .= "<tr class='totalCL $rowcls'>";
            $strHTML .= "<td class='left-side-cell' style='text-align:left;'>Total</td>";

            $l = 0;
            foreach ($columns_total as $key=>$column) {
                if($l == 0){
                    $strHTML .= "<td style='text-align:right;'>" . number_format($column) . "</td>";

                    if($key === count($columns_total)){  continue; }
                    $column = ($column / $grand_total1) * 100;
                    $strHTML .= "<td style='text-align:right;'>" . round($column) . "%</td>";
                }
                $l++;
            }

            $l = 0;
            foreach ($columns_total as $key=>$column) {
                if($l == 1){
                    $strHTML .= "<td style='text-align:right;'>" . number_format($column) . "</td>";

                    if($key === count($columns_total)){  continue; }
                    $column = ($column / $grand_total2) * 100;
                    $strHTML .= "<td style='text-align:right;'>" . round($column) . "%</td>";
                }
                $l++;
            }

            $strHTML .= "</tr>";
            $strHTML .= "</tbody></table>";
        }
        return ['html' => $strHTML,'xHeaders' => $xHeaders,'bHeaders' => $bHeaders,'val' => $xVal,'postfix' => 'Percent_of_Row_Total'];
    }

    public static function print_report_datatable_PNSC($aData, $col, $sum)
    {
        $xHeaders = $bHeaders = $xVal = array();
        $strHTML = "";
        $i = 0;
        if (!empty($aData)) {
            /** Header - Section - start */
            if ($i == 0) {
                $strHTML = "<table";

                $strHTML .= " class='table table-bordered table-hover color-table sr-table'><thead><tr>";
                /*********** Number - column header *******/
                $colspan = '';
                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i == 0) {
                        $strHTML .= "<th rowspan='2' style='text-align:left;'>$k</th>";
                        array_push($xHeaders,
                            [
                                'rowspan' => 2,
                                'colspan'=>0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);
                    } else if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>Count</th>";
                        $colspan = "Full";
                        array_push($xHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> (count($aData[0]) - 1),
                                'label' => "Count",
                                'type' => is_numeric($col) ? 'integer': 'string'
                            ]);
                    }
                    $i++;
                }
                $colspan = '';
                foreach ($aData[0] as $k => $v) {
                    if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>".$sum."</th>";
                        $colspan = "Full";
                    }

                }
                /*********** Percent - column header *******/
                $strHTML .= "</tr><tr>";


                $secondHeader = ['Number','Percent'];
                $secondHeader2 = ['Sum','Percent'];
                $l = 0;
                foreach ($secondHeader as $v) {
                    $strHTML .= "<th style='text-align:right;'>$v</th>";
                    if($l > 0){
                        array_push($bHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> 0,
                                'label' => $v,
                                'type' => is_numeric($v) ? 'integer': 'string'
                            ]);
                    }
                    $l++;

                }

                foreach ($secondHeader2 as $v) {
                    $strHTML .= "<th style='text-align:right;'>$v</th>";
                }
                $strHTML .= "</tr></thead><tbody>";
            }
            /** Header - Section - End */

            $rowcls = "odd";
            $columns_total = array();
            $rows_total = 0;
            $grand_total1 = 0;
            $grand_total2 = 0;

            foreach ($aData as $r=>$row) {
                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $rows_total1 = 0;
                $rows_total2 = 0;
                $j = 0;
                $nRow = $row;
                foreach ($nRow as $rKey => $cell) {
                    if ($j > 0 && $j < 2) {
                        $rows_total1 += $cell;
                    }else if ($j == 2) {
                        $rows_total2 += $cell;
                    }
                    $j++;
                }
                $grand_total1 += $rows_total1;
                $grand_total2 += $rows_total2;
            }

            foreach ($aData as $r=>$row) {
                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $strHTML .= "<tr class='$rowcls'>";
                $rows_total = 0;
                $j = 0;
                $nRow = [];
                foreach ($row as $rKey => $cell) {

                    if ($j > 0 && $j < 2) {

                        $rows_total += $cell;
                        if(!isset($columns_total[$rKey])){
                            $columns_total[$rKey] = $cell;
                        }else{
                            $columns_total[$rKey] += $cell;
                        }

                        if($rKey == 'Distribution') continue;
                        $strHTML .= "<td style='text-align:right;'>" . number_format($cell) . "</td>";

                        $cell = ($cell > 0) ? ($cell / $grand_total1) * 100 : 0;
                        $strHTML .= "<td style='text-align:right;'>" . round($cell) . "%</td>";

                    }else if ($j == 2) {

                        $rows_total += $cell;
                        if(!isset($columns_total[$rKey])){
                            $columns_total[$rKey] = $cell;
                        }else{
                            $columns_total[$rKey] += $cell;
                        }

                        if($rKey == 'Distribution') continue;
                        $strHTML .= "<td style='text-align:right;'>" . number_format($cell) . "</td>";

                        $cell = ($cell > 0) ? ($cell / $grand_total2) * 100 : 0;
                        $strHTML .= "<td style='text-align:right;'>" . round($cell) . "%</td>";
                        $nRow[1] = round($cell);
                    } else {
                        $nRow[0] = $cell;
                        $strHTML .= "<td class='left-side-cell' style='text-align:left;'>" . $cell . "</td>";
                    }
                    $j++;
                }

                array_push($xVal,$nRow);

                $strHTML .= "</tr>";

            }
            array_push($xVal,['Total',$columns_total,'Total'=>$grand_total1]);

            $strHTML .= "<tr class='totalCL $rowcls'>";
            $strHTML .= "<td class='left-side-cell' style='text-align:left;'>Total</td>";

            $l = 0;
            foreach ($columns_total as $key=>$column) {
                if($l == 0){
                    $strHTML .= "<td style='text-align:right;'>" . number_format($column) . "</td>";

                    if($key === count($columns_total)){  continue; }
                    $column = ($column / $grand_total1) * 100;
                    $strHTML .= "<td style='text-align:right;'>" . round($column) . "%</td>";
                }
                $l++;
            }

            $l = 0;
            foreach ($columns_total as $key=>$column) {
                if($l == 1){
                    $strHTML .= "<td style='text-align:right;'>" . number_format($column) . "</td>";

                    if($key === count($columns_total)){  continue; }
                    $column = ($column / $grand_total2) * 100;
                    $strHTML .= "<td style='text-align:right;'>" . round($column) . "%</td>";
                }
                $l++;
            }

            $strHTML .= "</tr>";
            $strHTML .= "</tbody></table>";
        }
        return ['html' => $strHTML,'xHeaders' => $xHeaders,'bHeaders' => $bHeaders,'val' => $xVal,'postfix' => 'Percent_of_Row_Total'];
    }

    public static function print_report_datatable_numberNPWC($aData, $col)
    {
        $xHeaders = $bHeaders = $xVal = array();
        $strHTML = "";
        $i = 0;
        if (!empty($aData)) {
            if ($i == 0) {
                $strHTML = "<table";
                $strHTML .= " class='table table-bordered table-hover color-table sr-table'><thead><tr>";
                $colspan = '';
                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i == 0) {
                        $strHTML .= "<th ";
                        if ($col != 'Distribution') {$strHTML .= "rowspan='2'";}
                        $strHTML .= " style='text-align:left;'>$k</th>";
                        array_push($xHeaders,
                            [
                                'rowspan' => ($col == 'Distribution') ? 0 : 2,
                                'colspan'=>0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);
                    } else if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:right;'>Number</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>" . $col . "</th>";
                        $colspan = "Full";
                        array_push($xHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> ($col == 'Distribution') ? 0 : (count($aData[0]) - 1),
                                'label' => 'Number',
                                'type' => is_numeric($col) ? 'integer': 'string'
                            ]);
                    }
                    $i++;
                }
                $strHTML .= "<th ";
                if ($col != 'Distribution') {$strHTML .= "rowspan='2' ";}
                $strHTML .= "style='text-align:right;'>Percent</th>";
                array_push($xHeaders,
                    [
                        'rowspan' => ($col == 'Distribution') ? 0 : 2,
                        'colspan'=> 0,
                        'label' => 'Percent',
                        'type' => 'string'
                    ]);
                //$strHTML .= "</tr><tr>";

                $i = 0;

                if($col == 'Distribution'){
                    array_push($bHeaders,
                        [
                            'rowspan' => 0,
                            'colspan'=> 0,
                            'label' => 'Number',
                            'type' => 'string'
                        ]);
                }

                $strHTML .= "</tr></thead><tbody>";
            }
            $rowcls = "odd";
            $columns_total = array();
            $rows_total = 0;
            $grand_total = 0;

            foreach ($aData as $row) {

                $rows_total = 0;
                $j = 0;

                foreach ($row as $rKey => $cell) {

                    if ($j > 0) {
                        if(isset($columns_total[$rKey])){
                            $columns_total[$rKey] += $cell;
                        }else{
                            $columns_total[$rKey] = $cell;
                        }
                        if(isset($row_total)){
                            $row_total += $cell;
                        }else{
                            $row_total = $cell;
                        }
                    }
                    $j++;
                }
                $columns_total[($j - 1)] = $row_total;
            }

            $new_column_total = array();
            $xlsxColTotal = '';
            foreach ($aData as $row) {

                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $strHTML .= "<tr class='$rowcls'>";

                $j = 0;

                $row_total = 0;
                foreach ($row as $rKey => $cell) {

                    if ($j > 0) {
                        $strHTML .= "<td style='text-align:right;'>" . number_format($cell) . "</td>";
                        $row_total += $cell;
                        if(isset($new_column_total[$rKey])){
                            $new_column_total[$rKey] = ($new_column_total[$rKey] + $cell);
                        }else{
                            $new_column_total[$rKey] = $cell;
                        }

                        $row[$rKey] = round($cell).'%';

                    } else {
                        $row[$rKey] = $cell.'%';
                        $strHTML .= "<td class='left-side-cell' style='text-align:left;'>" . $cell . "</td>";
                    }

                    $j++;
                }
                $row_cell = ($row_total / $columns_total[($j - 1)]) * 100;

                if(isset($new_column_total[($j - 1)])){
                    $new_column_total[($j - 1)] = ($new_column_total[($j - 1)] + $row_cell);
                }else{
                    $new_column_total[($j - 1)] = $row_cell;
                }

                $xlsxColTotal = $new_column_total[$j - 1];
                $strHTML .= "<td style='text-align:right;'>" . round($row_cell) . "%</td>";
                $strHTML .= "</tr>";
                $row['Total'] = round($row_cell).'%';
                array_push($xVal,$row);
            }

            $strHTML .= "<tr class='totalCL $rowcls'>";
            $strHTML .= "<td class='left-side-cell' style='text-align:left;'>Total</td>";

            $v = 1;
            foreach($new_column_total as $key=>$ctotal){
                if(count($new_column_total) == $v){
                    $strHTML .= "<td style='text-align:right;'>" . $ctotal ."%</td>";
                }else{
                    $strHTML .= "<td style='text-align:right;'>" . number_format($ctotal) . "</td>";
                }
                $v++;
            }
            $strHTML .= "</tr>";
            array_push($xVal,['Total',$new_column_total,'Total'=>$xlsxColTotal]);

            $strHTML .= "</tbody></table>";

        }
        //return $strHTML;
        return ['html' => $strHTML,'xHeaders' => $xHeaders,'bHeaders' => $bHeaders,'val' => $xVal,'postfix' => 'Percent_of_Column_Total'];
    }

    public static function print_report_datatable_numberPNWC($aData, $col)
    {
        $xHeaders = $bHeaders = $xVal = array();
        $strHTML = "";
        $i = 0;
        if (!empty($aData)) {
            if ($i == 0) {
                $strHTML = "<table";

                $strHTML .= " class='table table-bordered table-hover color-table sr-table'><thead><tr>";
                $colspan = '';
                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i == 0) {
                        $strHTML .= "<th ";
                        if ($col != 'Distribution') {$strHTML .= "rowspan='2'";}
                        $strHTML .= " style='text-align:left;'>$k</th>";
                        array_push($xHeaders,
                            [
                                'rowspan' => ($col == 'Distribution') ? 0 : 2,
                                'colspan'=>0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);
                    } else if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:right;'>Number</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>" . $col . "</th>";
                        $colspan = "Full";
                    }
                    $i++;
                }
                $strHTML .= "<th ";
                if ($col != 'Distribution') {$strHTML .= "rowspan='2' ";}
                $strHTML .= "style='text-align:right;'>Percent</th>";
                array_push($xHeaders,
                    [
                        'rowspan' => ($col == 'Distribution') ? 0 : 2,
                        'colspan'=> 0,
                        'label' => 'Percent',
                        'type' => 'string'
                    ]);
                $i = 0;

                if($col == 'Distribution'){
                    array_push($bHeaders,
                        [
                            'rowspan' => 0,
                            'colspan'=> 0,
                            'label' => 'Number',
                            'type' => 'string'
                        ]);
                }

                $strHTML .= "</tr></thead><tbody>";
            }
            $rowcls = "odd";
            $columns_total = array();
            $rows_total = 0;
            $grand_total = 0;

            foreach ($aData as $row) {

                $rows_total = 0;
                $j = 0;

                foreach ($row as $rKey => $cell) {

                    if ($j > 0) {
                        if(isset($columns_total[$rKey])){
                            $columns_total[$rKey] += $cell;
                        }else{
                            $columns_total[$rKey] = $cell;
                        }
                        if(isset($row_total)){
                            $row_total += $cell;
                        }else{
                            $row_total = $cell;
                        }
                    }
                    $j++;
                }
                $columns_total[($j - 1)] = $row_total;
            }

            $new_column_total = array();
            $nxRow = array();
            $xlsxColTotal = '';
            foreach ($aData as $row) {

                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $strHTML .= "<tr class='$rowcls'>";

                $j = 0;

                $row_total = 0;

                foreach ($row as $rKey => $cell) {

                    if ($j > 0) {
                        $strHTML .= "<td style='text-align:right;'>" . number_format($cell) . "</td>";
                        $row_total += $cell;
                        if(isset($new_column_total[$rKey])){
                            $new_column_total[$rKey] = ($new_column_total[$rKey] + $cell);
                        }else{
                            $new_column_total[$rKey] = $cell;
                        }

                        //$nxRow[$rKey] = round($cell).'%';

                    } else {
                        $nxRow[$rKey] = $cell.'%';
                        $strHTML .= "<td class='left-side-cell' style='text-align:left;'>" . $cell . "</td>";
                    }

                    $j++;
                }
                $row_cell = ($row_total / $columns_total[($j - 1)]) * 100;
                $nxRow[1] = $row_cell.'%';
                if(isset($new_column_total[($j - 1)])){
                    $new_column_total[($j - 1)] = ($new_column_total[($j - 1)] + $row_cell);
                }else{
                    $new_column_total[($j - 1)] = $row_cell;
                }

                $xlsxColTotal = $new_column_total[$j - 1];
                $strHTML .= "<td style='text-align:right;'>" . round($row_cell) . "%</td>";
                $strHTML .= "</tr>";
                $nxRow['Total'] = round($row_cell).'%';
                array_push($xVal,$nxRow);
            }

            $strHTML .= "<tr class='totalCL $rowcls'>";
            $strHTML .= "<td class='left-side-cell' style='text-align:left;'>Total</td>";

            $v = 1;
            foreach($new_column_total as $key=>$ctotal){
                if(count($new_column_total) == $v){
                    $strHTML .= "<td style='text-align:right;'>" . $ctotal ."%</td>";
                }else{
                    $strHTML .= "<td style='text-align:right;'>" . number_format($ctotal) . "</td>";
                }
                $v++;
            }
            $strHTML .= "</tr>";
            array_push($xVal,['Total',$new_column_total,'Total'=>$xlsxColTotal]);

            $strHTML .= "</tbody></table>";

        }
        //return $strHTML;
        return ['html' => $strHTML,'xHeaders' => $xHeaders,'bHeaders' => $bHeaders,'val' => $xVal,'postfix' => 'Percent_of_Column_Total'];
    }

    public static function print_report_datatable_SideByNumber($aData,$rowVar, $colVar, $sumVar){
        $xHeaders = $bHeaders = $xVal = array();
        $strHTML = "";
        $nRow = array();
        $i = 0;
        if (!empty($aData)) {
            if ($i == 0) {
                $strHTML = "<table";

                $strHTML .= " class='table table-bordered table-hover color-table sr-table'><thead><tr>";
                $colspan = '';
                $i = 0;
                $strHTML .= '<th>'.$rowVar.'</th><th>'.$colVar.'</th><th>'.$sumVar.'</th></tr></thead>';
                array_push($xHeaders,
                    [
                        'rowspan' => 0,
                        'colspan'=>0,
                        'label' => $rowVar,
                        'type' => is_numeric($rowVar) ? 'integer': 'string'
                    ]);
                array_push($xHeaders,[
                        'rowspan' => 0,
                        'colspan'=>0,
                        'label' => $colVar,
                        'type' => is_numeric($colVar) ? 'integer': 'string'
                    ]);
                array_push($xHeaders,[
                        'rowspan' => 0,
                        'colspan'=>0,
                        'label' => $sumVar,
                        'type' => is_numeric($sumVar) ? 'integer': 'string'
                    ]);
                foreach ($aData as $key => $row){
                    //echo '<pre>'; print_r($row);

                    foreach ($row as $c => $cell){
                        //echo '<pre>'; print_r($row);
                        if($c == $rowVar) continue;
                        $strHTML .= '<tr><td>'.$row[$rowVar].'</td>';
                        $strHTML .= '<td>'.$c.'</td><td>'.number_format($cell).'</td>';
                        array_push($nRow,[$row[$rowVar],$c,$cell]);

                    }
                    array_push($xVal,$nRow);
                    $strHTML .= '</tr>';

                }
                $strHTML .= '</table>';

            }
        }
        //die('ddd');
        return ['html' => $strHTML,'xHeaders' => $xHeaders,'bHeaders' => $bHeaders,'val' => $xVal,'postfix' => 'Side By Number'];
    }

    public static function print_report_datatable_number($aData, $col)
    {
        $xHeaders = $bHeaders = $xVal = array();
        $strHTML = "";
        $i = 0;
        if (!empty($aData)) {
            if ($i == 0) {
                $strHTML = "<table";

                $strHTML .= " class='table table-bordered table-hover color-table sr-table'><thead><tr>";
                $colspan = '';
                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i == 0) {
                        $strHTML .= "<th ";
                        if ($col != 'Distribution'){$strHTML .= "rowspan='2'";}

                        $strHTML .= "style='text-align:left;'>$k</th>";
                        array_push($xHeaders,
                            [
                                'rowspan' => ($col == 'Distribution') ? 0 : 2,
                                'colspan'=>0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);
                    } else if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "" /*"<th style='text-align:center;'>" . $col . "</th>"*/ : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>" . $col . "</th>";
                        $colspan = "Full";
                        array_push($xHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> ($col == 'Distribution') ? 0 : (count($aData[0]) - 1),
                                'label' => $col,
                                'type' => is_numeric($col) ? 'integer': 'string'
                            ]);
                    }
                    $i++;
                }
                $strHTML .= "<th ";

                if ($col != 'Distribution'){ $strHTML .= "rowspan='2' ";}
                $strHTML .= "style='text-align:right;'>Total</th>";
                array_push($xHeaders,
                    [
                        'rowspan' => ($col == 'Distribution') ? 0 : 2,
                        'colspan'=> 0,
                        'label' => 'Total',
                        'type' => 'string'
                    ]);
                $strHTML .= "</tr><tr>";

                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i > 0 && $k != "Distribution") {
                        $strHTML .= "<th style='text-align:right;'>$k</th>";
                        array_push($bHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> 0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);

                    }
                    $i++;
                }


                if($col == 'Distribution'){
                    array_push($bHeaders,
                        [
                            'rowspan' => 0,
                            'colspan'=> 0,
                            'label' => 'Distribution',
                            'type' => 'string'
                        ]);
                }


                $strHTML .= "</tr></thead><tbody>";
            }
            $rowcls = "odd";
            $columns_total = array();
            $rows_total = 0;
            $grand_total = 0;

            foreach ($aData as $r=>$row) {
                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $strHTML .= "<tr class='$rowcls'>";
                $rows_total = 0;
                $j = 0;
                foreach ($row as $rKey => $cell) {
                    //echo "---".$rKey."---";

                    if ($j > 0) {

                        $rows_total += $cell;
                        if(!isset($columns_total[$rKey]))
                        {
                            $columns_total[$rKey] = $cell;
                        }else{
                            $columns_total[$rKey] += $cell;
                        }


                        if($rKey == 'Distribution') continue;
                        $strHTML .= "<td style='text-align:right;'>" . number_format($cell) . "</td>";
                    } else {
                        $strHTML .= "<td class='left-side-cell' style='text-align:left;'>" . $cell . "</td>";

                    }
                    $j++;
                }

                $strHTML .= "<td style='text-align:right;'>" . number_format($rows_total) . "</td>";
                $row['Total'] = number_format($rows_total);

                array_push($xVal,$row);
                $grand_total += $rows_total;
                $strHTML .= "</tr>";

            }
            array_push($xVal,['Total',$columns_total,'Total'=>$grand_total]);
            if(!isset($columns_total[(count($columns_total) + 1)])){
                $columns_total[(count($columns_total) + 1)] = $grand_total;
            }else{
                $columns_total[(count($columns_total) + 1)] += $grand_total;
            }

            $strHTML .= "<tr class='totalCL $rowcls'>";
            $strHTML .= "<td class='left-side-cell' style='text-align:left;'>Total</td>";

            foreach ($columns_total as $key=>$column) {
                if($key === 'Distribution'){  continue; }
                $strHTML .= "<td style='text-align:right;'>" . number_format($column) . "</td>";
            }
            $strHTML .= "</tr>";
            $strHTML .= "</tbody></table>";
        }

        //return $strHTML;
        return ['html' => $strHTML,'xHeaders' => $xHeaders,'bHeaders' => $bHeaders,'val' => $xVal,'postfix' => 'Number'];
    }

    public static function print_report_datatable_PRT($aData, $col)
    {
        $xHeaders = $bHeaders = $xVal = array();
        $strHTML = "";
        $i = 0;
        if (!empty($aData)) {
            if ($i == 0) {
                $strHTML = "<table";
                if ($col == 'Distribution') {
                    //$strHTML .= " style='text-align:center; width: 100% !important; float: none !important;' ";
                } else {
                    //$strHTML .= " style='text-align:center;' ";
                }


                $strHTML .= " class='table table-bordered table-hover color-table sr-table'><thead><tr>";
                $colspan = '';
                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i == 0) {
                        $strHTML .= "<th rowspan='2' style='text-align:left;'>$k</th>";
                        array_push($xHeaders,
                            [
                                'rowspan' => ($col == 'Distribution') ? 0 : 2,
                                'colspan'=>0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);
                    } else if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>" . $col . "</th>";
                        $colspan = "Full";
                        array_push($xHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> ($col == 'Distribution') ? 0 : (count($aData[0]) - 1),
                                'label' => $col,
                                'type' => is_numeric($col) ? 'integer': 'string'
                            ]);
                    }
                    $i++;
                }
                $strHTML .= "<th rowspan='2' style='text-align:right;'>Total</th>";
                array_push($xHeaders,
                    [
                        'rowspan' => ($col == 'Distribution') ? 0 : 2,
                        'colspan'=> 0,
                        'label' => 'Total',
                        'type' => 'string'
                    ]);
                $strHTML .= "</tr><tr>";

                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i > 0 && $k != "Distribution") {
                        $strHTML .= "<th style='text-align:right;'>$k</th>";
                        array_push($bHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> 0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);

                    }
                    $i++;
                }

                if($col == 'Distribution'){
                    array_push($bHeaders,
                        [
                            'rowspan' => 0,
                            'colspan'=> 0,
                            'label' => 'Distribution',
                            'type' => 'string'
                        ]);
                }

                $strHTML .= "</tr></thead><tbody>";
            }
            $rowcls = "odd";
            $columns_total = array();
            $rows_total = 0;
            $grand_total = 0;
            $cell_values = array();
            $new_rows_total = 0;
            foreach ($aData as $row) {
                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $strHTML .= "<tr class='$rowcls'>";
                $rows_total = 0;
                $j = 0;

                foreach ($row as $rKey => $cell) {
                    if ($j > 0) {
                        $rows_total += $cell;
                        $cell_values[$rKey] = $cell;
                        if(!isset($columns_total[$rKey])){
                            $columns_total[$rKey] = $cell;
                        }else{
                            $columns_total[$rKey] += $cell;
                        }

                    }
                    $j++;
                }
                $j = 0;
                $new_rows_total = 0;
                foreach ($row as $rKey => $cell) {

                    if ($j > 0) {

                        $cell = ($cell_values[$rKey] > 0) ? ($cell_values[$rKey] / $rows_total) * 100 : 0;
                        $new_rows_total += $cell;
                        $row[$rKey] = round($cell).'%';
                        $strHTML .= "<td style='text-align:right;'>" . round($cell) . "%</td>";
                    } else {
                        $strHTML .= "<td class='left-side-cell' style='text-align:left;'>" . $cell . "</td>";
                        $row[$rKey] = $cell;
                    }
                    $j++;
                }

                $row['Total'] = number_format($new_rows_total).'%';

                array_push($xVal,$row);
                $grand_total += $rows_total;
                $strHTML .= "<td style='text-align:right;'>" . $new_rows_total . "%</td>";
                $strHTML .= "</tr>";
            }
            $xlsxColTotal = '';

            if(!isset($columns_total[(count($columns_total) + 1)])){
                $columns_total[(count($columns_total) + 1)] = $grand_total;
            }else{
                $columns_total[(count($columns_total) + 1)] += $grand_total;
            }
            $strHTML .= "<tr class='totalCL $rowcls'>";
            $strHTML .= "<td class='left-side-cell' style='text-align:left;'>Total</td>";
            foreach ($columns_total as $key => $column) {
                if ($key == count($columns_total)) {
                    $columns_total[$key] = (($column / $columns_total[count($columns_total)]) * 100).'%';

                    $strHTML .= "<td style='text-align:right;'>" . $columns_total[$key] . "</td>";
                    $xlsxColTotal = $columns_total[$key];
                } else {
                    $columns_total[$key] = round(($column / $columns_total[count($columns_total)]) * 100).'%';
                    $xlsxColTotal = $columns_total[$key];
                    $strHTML .= "<td style='text-align:right;'>" . $columns_total[$key] . "</td>";
                }


            }
            array_push($xVal,['Total',$columns_total,'Total'=>$xlsxColTotal]);

            $strHTML .= "</tr>";
            $strHTML .= "</tbody></table>";
        }
        //return $strHTML;
        return ['html' => $strHTML,'xHeaders' => $xHeaders,'bHeaders' => $bHeaders,'val' => $xVal,'postfix' => 'Percent_of_Row_Total'];
    }

    public static function print_report_datatable_PCT($aData, $col)
    {
        $xHeaders = $bHeaders = $xVal = array();
        $strHTML = "";
        $i = 0;
        if (!empty($aData)) {
            if ($i == 0) {
                $strHTML = "<table";

                $strHTML .= " class='table table-bordered table-hover color-table sr-table'><thead><tr>";
                $colspan = '';
                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i == 0) {
                        $strHTML .= "<th rowspan='2' style='text-align:left;'>$k</th>";
                        array_push($xHeaders,
                            [
                                'rowspan' => ($col == 'Distribution') ? 0 : 2,
                                'colspan'=>0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);
                    } else if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>" . $col . "</th>";
                        $colspan = "Full";
                        array_push($xHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> ($col == 'Distribution') ? 0 : (count($aData[0]) - 1),
                                'label' => $col,
                                'type' => is_numeric($col) ? 'integer': 'string'
                            ]);
                    }
                    $i++;
                }
                $strHTML .= "<th rowspan='2' style='text-align:right;'>Total</th>";
                array_push($xHeaders,
                    [
                        'rowspan' => ($col == 'Distribution') ? 0 : 2,
                        'colspan'=> 0,
                        'label' => 'Total',
                        'type' => 'string'
                    ]);
                $strHTML .= "</tr><tr>";

                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i > 0 && $k != "Distribution") {
                        $strHTML .= "<th style='text-align:right;'>$k</th>";
                        array_push($bHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> 0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);

                    }
                    $i++;
                }

                if($col == 'Distribution'){
                    array_push($bHeaders,
                        [
                            'rowspan' => 0,
                            'colspan'=> 0,
                            'label' => 'Distribution',
                            'type' => 'string'
                        ]);
                }

                $strHTML .= "</tr></thead><tbody>";
            }
            $rowcls = "odd";
            $columns_total = array();
            $rows_total = 0;
            $grand_total = 0;

            foreach ($aData as $row) {

                $row_total = 0;
                $j = 0;

                foreach ($row as $rKey => $cell) {

                    if ($j > 0) {
                        if(!isset($columns_total[$rKey])){
                            $columns_total[$rKey] = $cell;
                        }else{
                            $columns_total[$rKey] += $cell;
                        }

                        $row_total += $cell;

                    }
                    $j++;
                }
                if(!isset($columns_total[($j - 1)])){
                    $columns_total[($j - 1)] = $row_total;
                }else{
                    $columns_total[($j - 1)] += $row_total;
                }
            }

            $new_column_total = array();
            $xlsxColTotal = '';
            foreach ($aData as $row) {

                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $strHTML .= "<tr class='$rowcls'>";

                $j = 0;

                $row_total = 0;
                foreach ($row as $rKey => $cell) {

                    if ($j > 0) {
                        $row_total += $cell;
                        $cell = ($cell / $columns_total[$rKey]) * 100;
                        if(!isset($new_column_total[$rKey])){
                            $new_column_total[$rKey] = $cell;
                        }else{
                            $new_column_total[$rKey] += $cell;
                        }

                        $row[$rKey] = round($cell).'%';
                        $strHTML .= "<td style='text-align:right;'>" . round($cell) . "%</td>";
                    } else {
                        $row[$rKey] = $cell.'%';
                        $strHTML .= "<td class='left-side-cell' style='text-align:left;'>" . $cell . "</td>";
                    }

                    $j++;
                }

                $row_cell = ($row_total > 0) ? ($row_total / $columns_total[($j - 1)]) * 100 : 0; //doubt
                if(!isset($new_column_total[$j - 1])){
                    $new_column_total[$j - 1] = $row_cell;
                }else{
                    $new_column_total[$j - 1] += $row_cell;
                }

                $xlsxColTotal = $new_column_total[$j - 1];
                $strHTML .= "<td style='text-align:right;'>" . round($row_cell) . "%</td>";
                $strHTML .= "</tr>";
                $row['Total'] = round($row_cell).'%';
                array_push($xVal,$row);
            }


            $strHTML .= "<tr class='totalCL $rowcls'>";
            $strHTML .= "<td class='left-side-cell' style='text-align:left;'>Total</td>";
            foreach($new_column_total as $columnTotal){
                $strHTML .= "<td style='text-align:right;'>" .round($columnTotal). "%</td>";
            }
            $strHTML .= "</tr>";
            array_push($xVal,['Total',$new_column_total,'Total'=>$xlsxColTotal]);

            $strHTML .= "</tbody></table>";

        }
        //return $strHTML;
        return ['html' => $strHTML,'xHeaders' => $xHeaders,'bHeaders' => $bHeaders,'val' => $xVal,'postfix' => 'Percent_of_Column_Total'];
    }

    public static function print_report_datatable_PGT($aData, $col)
    {
        $xHeaders = $bHeaders = $xVal = array();
        $strHTML = "";
        $i = 0;
        if (!empty($aData)) {
            if ($i == 0) {
                $strHTML = "<table";

                $strHTML .= " class='table table-bordered table-hover color-table sr-table' ><thead><tr>";
                $colspan = '';
                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i == 0) {
                        $strHTML .= "<th rowspan='2' style='text-align:left;'>$k</th>";
                        array_push($xHeaders,
                            [
                                'rowspan' => ($col == 'Distribution') ? 0 : 2,
                                'colspan'=>0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);
                    } else if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>" . $col . "</th>";
                        $colspan = "Full";
                        array_push($xHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> ($col == 'Distribution') ? 0 : (count($aData[0]) - 1),
                                'label' => $col,
                                'type' => is_numeric($col) ? 'integer': 'string'
                            ]);
                    }
                    $i++;
                }
                $strHTML .= "<th rowspan='2' style='text-align:right;'>Total</th>";
                array_push($xHeaders,
                    [
                        'rowspan' => ($col == 'Distribution') ? 0 : 2,
                        'colspan'=> 0,
                        'label' => 'Total',
                        'type' => 'string'
                    ]);
                $strHTML .= "</tr><tr>";

                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i > 0 && $k != "Distribution") {
                        $strHTML .= "<th style='text-align:right;'>$k</th>";
                        array_push($bHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> 0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);

                    }
                    $i++;
                }

                if($col == 'Distribution'){
                    array_push($bHeaders,
                        [
                            'rowspan' => 0,
                            'colspan'=> 0,
                            'label' => 'Distribution',
                            'type' => 'string'
                        ]);
                }

                $strHTML .= "</tr></thead><tbody>";
            }
            $rowcls = "odd";
            $columns_total = array();
            $rows_total = 0;
            $grand_total = 0;

            foreach ($aData as $row) {

                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $rows_total = 0;
                $j = 0;

                foreach ($row as $rKey => $cell) {

                    if ($j > 0) {
                        $rows_total += $cell;
                        if(!isset($columns_total[$rKey])){
                            $columns_total[$rKey] = $cell;
                        }else {
                            $columns_total[$rKey] += $cell;
                        }
                    }
                    $j++;
                }
                $grand_total += $rows_total;
            }


            if(!isset($columns_total[(count($columns_total) + 1)])){
                $columns_total[(count($columns_total) + 1)] = $grand_total;
            }else{
                $columns_total[(count($columns_total) + 1)] += $grand_total;
            }


            foreach ($aData as $row) {

                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $strHTML .= "<tr class='$rowcls'>";
                $rows_total = 0;
                $j = 0;

                foreach ($row as $rKey => $cell) {

                    if ($j > 0) {
                        $rows_total += $cell;
                        $cell = ($cell / $grand_total) * 100;
                        $row[$rKey] = round($cell).'%';
                        $strHTML .= "<td style='text-align:right;'>" . round($cell) . "%</td>";
                    } else {
                        $row[$rKey] = $cell.'%';
                        $strHTML .= "<td class='left-side-cell' style='text-align:left;'>" . $cell . "</td>";
                    }

                    $j++;
                }
                $rows_total = ($rows_total / $grand_total) * 100;
                $strHTML .= "<td style='text-align:right;'>" . round($rows_total) . "%</td>";

                //$grand_total += $rows_total;
                $strHTML .= "</tr>";

                $row['Total'] = round($rows_total).'%';
                array_push($xVal,$row);
            }



            $strHTML .= "<tr class='totalCL $rowcls'>";
            $strHTML .= "<td class='left-side-cell' style='text-align:left;'>Total</td>";
            $color = "";
            $xlsxColTotal = array();
            foreach ($columns_total as $key => $column) {
                $column = ($column / $grand_total) * 100;
                $columns_total[$key] = round($column).'%';
                $xlsxColTotal = round($column).'%';
                $strHTML .= "<td style='text-align:right;'>" . round($column) . "%</td>";
            }

            array_push($xVal,['Total',$columns_total,'Total'=>$xlsxColTotal]);
            $strHTML .= "</tr>";
            $strHTML .= "</tbody></table>";

        }
        //return $strHTML;
        return ['html' => $strHTML,'xHeaders' => $xHeaders,'bHeaders' => $bHeaders,'val' => $xVal,'postfix' => 'Percent_of_Grand_Total'];
    }

    public static function print_report_datatable_NPRT($aData, $col)
    {
        $xHeaders = $bHeaders = $xVal = array();
        $strHTML = "";
        $i = 0;
        if (!empty($aData)) {
            /** Header - Section - start */
            if ($i == 0) {
                $strHTML = "<table";

                $strHTML .= " class='table table-bordered table-hover color-table sr-table'><thead><tr>";
                /*********** Number - column header *******/
                $colspan = '';
                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i == 0) {
                        $strHTML .= "<th rowspan='2' style='text-align:left;'>$k</th>";
                        array_push($xHeaders,
                            [
                                'rowspan' => ($col == 'Distribution') ? 0 : 2,
                                'colspan'=>0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);
                    } else if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>" . $col . " - Number</th>";
                        $colspan = "Full";
                        array_push($xHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> ($col == 'Distribution') ? 0 : (count($aData[0]) - 1),
                                'label' => $col . " - Number",
                                'type' => is_numeric($col) ? 'integer': 'string'
                            ]);
                    }
                    $i++;
                }
                /*********** Number - column header *******/

                $strHTML .= "<th rowspan='2' style='text-align:right;'>Total</th>";
                array_push($xHeaders,
                    [
                        'rowspan' => ($col == 'Distribution') ? 0 : 2,
                        'colspan'=> 0,
                        'label' => 'Total',
                        'type' => 'string'
                    ]);

                /*********** Percent - column header *******/
                $colspan = '';
                foreach ($aData[0] as $k => $v) {
                    if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>" . $col . " - Percent</th>";
                        $colspan = "Full";
                    }

                }
                /*********** Percent - column header *******/
                $strHTML .= "</tr><tr>";

                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i > 0 && $k != "Distribution") {
                        $strHTML .= "<th style='text-align:right;'>$k</th>";
                        array_push($bHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> 0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);

                    }
                    $i++;
                }

                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i > 0 && $k != "Distribution") {
                        $strHTML .= "<th style='text-align:right;'>$k</th>";
                    }
                    $i++;
                }

                if($col == 'Distribution'){
                    array_push($bHeaders,
                        [
                            'rowspan' => 0,
                            'colspan'=> 0,
                            'label' => 'Distribution',
                            'type' => 'string'
                        ]);
                }

                $strHTML .= "</tr></thead><tbody>";
            }
            /** Header - Section - End */

            $rowcls = "odd";
            $columns_total = array();
            $rows_total = 0;
            $grand_total = 0;

            foreach ($aData as $r=>$row) {
                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $strHTML .= "<tr class='$rowcls'>";
                $rows_total = 0;
                $j = 0;
                $nRow = $row;
                foreach ($nRow as $rKey => $cell) {
                    //echo "---".$rKey."---";

                    if ($j > 0) {

                        $rows_total += $cell;
                        if(!isset($columns_total[$rKey])){
                            $columns_total[$rKey] = $cell;
                        }else{
                            $columns_total[$rKey] += $cell;
                        }

                        if($rKey == 'Distribution') continue;
                        $strHTML .= "<td style='text-align:right;'>" . number_format($cell) . "</td>";
                    } else {
                        $strHTML .= "<td class='left-side-cell' style='text-align:left;'>" . $cell . "</td>";
                    }
                    $j++;
                }

                $strHTML .= "<td style='text-align:right;'>" . number_format($rows_total) . "</td>";
                $row['Total'] = number_format($rows_total);

                array_push($xVal,$row);
                $grand_total += $rows_total;


                $j = 0;
                foreach ($nRow as $rKey => $cell) {
                    if ($j > 0) {
                        if($rKey == 'Distribution') continue;
                        $cell = ($cell > 0) ? ($cell / $rows_total) * 100 : 0;

                        $strHTML .= "<td style='text-align:right;'>" . round($cell) . "%</td>";
                    }
                    $j++;
                }
                $strHTML .= "</tr>";

            }
            array_push($xVal,['Total',$columns_total,'Total'=>$grand_total]);
            if(!isset($columns_total[(count($columns_total) + 1)])){
                $columns_total[(count($columns_total) + 1)] = $grand_total;
            }else{
                $columns_total[(count($columns_total) + 1)] += $grand_total;
            }

            $strHTML .= "<tr class='totalCL $rowcls'>";
            $strHTML .= "<td class='left-side-cell' style='text-align:left;'>Total</td>";

            foreach ($columns_total as $key=>$column) {
                if($key === 'Distribution'){  continue; }
                $strHTML .= "<td style='text-align:right;'>" . number_format($column) . "</td>";
            }

            foreach ($columns_total as $key=>$column) {
                if($key === 'Distribution' || $key === count($columns_total)){  continue; }
                $column = ($column / $grand_total) * 100;
                $strHTML .= "<td style='text-align:right;'>" . round($column) . "%</td>";
            }

            $strHTML .= "</tr>";
            $strHTML .= "</tbody></table>";
        }
        return ['html' => $strHTML,'xHeaders' => $xHeaders,'bHeaders' => $bHeaders,'val' => $xVal,'postfix' => 'Percent_of_Row_Total'];
    }

    public static function print_report_datatable_PRTN($aData, $col)
    {
        $xHeaders = $bHeaders = $xVal = array();
        $strHTML = "";
        $i = 0;
        if (!empty($aData)) {
            /** Header - Section - start */
            if ($i == 0) {
                $strHTML = "<table";

                $strHTML .= " class='table table-bordered table-hover color-table sr-table'><thead><tr>";
                /*********** Number - column header *******/
                $colspan = '';
                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i == 0) {
                        $strHTML .= "<th rowspan='2' style='text-align:left;'>$k</th>";
                        array_push($xHeaders,
                            [
                                'rowspan' => ($col == 'Distribution') ? 0 : 2,
                                'colspan'=>0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);
                    } else if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>" . $col . " - Number</th>";
                        $colspan = "Full";
                        array_push($xHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> ($col == 'Distribution') ? 0 : (count($aData[0]) - 1),
                                'label' => $col . " - Number",
                                'type' => is_numeric($col) ? 'integer': 'string'
                            ]);
                    }
                    $i++;
                }
                /*********** Number - column header *******/


                $strHTML .= "<th rowspan='2' style='text-align:right;'>Total</th>";
                array_push($xHeaders,
                    [
                        'rowspan' => ($col == 'Distribution') ? 0 : 2,
                        'colspan'=> 0,
                        'label' => 'Total',
                        'type' => 'string'
                    ]);

                /*********** Percent - column header *******/
                $colspan = '';
                foreach ($aData[0] as $k => $v) {
                    if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>" . $col . " - Percent</th>";
                        $colspan = "Full";
                    }

                }
                /*********** Percent - column header *******/
                $strHTML .= "</tr><tr>";

                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i > 0 && $k != "Distribution") {
                        $strHTML .= "<th style='text-align:right;'>$k</th>";
                        array_push($bHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> 0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);

                    }
                    $i++;
                }

                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i > 0 && $k != "Distribution") {
                        $strHTML .= "<th style='text-align:right;'>$k</th>";
                    }
                    $i++;
                }

                if($col == 'Distribution'){
                    array_push($bHeaders,
                        [
                            'rowspan' => 0,
                            'colspan'=> 0,
                            'label' => 'Distribution',
                            'type' => 'string'
                        ]);
                }

                $strHTML .= "</tr></thead><tbody>";
            }
            /** Header - Section - End */

            $rowcls = "odd";
            $columns_total = array();
            $rows_total = 0;
            $grand_total = 0;

            foreach ($aData as $r=>$row) {
                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $strHTML .= "<tr class='$rowcls'>";
                $rows_total = 0;
                $j = 0;
                $nRow = $row;
                foreach ($row as $rKey => $cell) {
                    //echo "---".$rKey."---";

                    if ($j > 0) {

                        $rows_total += $cell;
                        if(!isset($columns_total[$rKey])){
                            $columns_total[$rKey] = $cell;
                        }else{
                            $columns_total[$rKey] += $cell;
                        }

                        if($rKey == 'Distribution') continue;
                        $strHTML .= "<td style='text-align:right;'>" . number_format($cell) . "</td>";
                    } else {
                        $strHTML .= "<td class='left-side-cell' style='text-align:left;'>" . $cell . "</td>";

                    }
                    $j++;
                }

                $strHTML .= "<td style='text-align:right;'>" . number_format($rows_total) . "</td>";
                $row['Total'] = number_format($rows_total);

                //array_push($xVal,$row);
                $grand_total += $rows_total;


                $j = 0;
                $new_rows_total = 0;
                foreach ($nRow as $rKey => $cell) {
                    if ($j > 0) {
                        if($rKey == 'Distribution') continue;
                        $cell = ($cell > 0) ? ($cell / $rows_total) * 100 : 0;
                        $new_rows_total += $cell;
                        $nRow[$rKey] = round($cell).'%';

                        $strHTML .= "<td style='text-align:right;'>" . round($cell) . "%</td>";
                    }
                    $j++;
                }
                $strHTML .= "</tr>";

                $nRow['Total'] = number_format($new_rows_total).'%';

                array_push($xVal,$nRow);

            }
            array_push($xVal,['Total',$columns_total,'Total'=>$grand_total]);

            if(!isset($columns_total[(count($columns_total) + 1)])){
                $columns_total[(count($columns_total) + 1)] = $grand_total;
            }else{
                $columns_total[(count($columns_total) + 1)] += $grand_total;
            }
            $strHTML .= "<tr class='totalCL $rowcls'>";
            $strHTML .= "<td class='left-side-cell' style='text-align:left;'>Total</td>";

            foreach ($columns_total as $key=>$column) {
                if($key === 'Distribution'){  continue; }
                $strHTML .= "<td style='text-align:right;'>" . number_format($column) . "</td>";
            }

            foreach ($columns_total as $key=>$column) {
                if($key === 'Distribution' || $key === count($columns_total)){  continue; }
                $column = ($column / $grand_total) * 100;
                $strHTML .= "<td style='text-align:right;'>" . round($column) . "%</td>";
            }

            $strHTML .= "</tr>";
            $strHTML .= "</tbody></table>";
        }
        return ['html' => $strHTML,'xHeaders' => $xHeaders,'bHeaders' => $bHeaders,'val' => $xVal,'postfix' => 'Percent_of_Row_Total'];
    }

    public static function print_report_datatable_NPCT($aData, $col)
    {
        $xHeaders = $bHeaders = $xVal = array();
        $strHTML = "";
        $i = 0;
        if (!empty($aData)) {
            /** Header - Section - start */
            if ($i == 0) {
                $strHTML = "<table";

                $strHTML .= " class='table table-bordered table-hover color-table sr-table'><thead><tr>";
                /*********** Number - column header *******/
                $colspan = '';
                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i == 0) {
                        $strHTML .= "<th rowspan='2' style='text-align:left;'>$k</th>";
                        array_push($xHeaders,
                            [
                                'rowspan' => ($col == 'Distribution') ? 0 : 2,
                                'colspan'=>0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);
                    } else if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>" . $col . " - Number</th>";
                        $colspan = "Full";
                        array_push($xHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> ($col == 'Distribution') ? 0 : (count($aData[0]) - 1),
                                'label' => $col . " - Number",
                                'type' => is_numeric($col) ? 'integer': 'string'
                            ]);
                    }
                    $i++;
                }
                /*********** Number - column header *******/


                $strHTML .= "<th rowspan='2' style='text-align:right;'>Total</th>";
                array_push($xHeaders,
                    [
                        'rowspan' => ($col == 'Distribution') ? 0 : 2,
                        'colspan'=> 0,
                        'label' => 'Total',
                        'type' => 'string'
                    ]);

                /*********** Percent - column header *******/
                $colspan = '';
                foreach ($aData[0] as $k => $v) {
                    if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>" . $col . " - Percent</th>";
                        $colspan = "Full";
                    }

                }
                /*********** Percent - column header *******/
                $strHTML .= "</tr><tr>";

                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i > 0 && $k != "Distribution") {
                        $strHTML .= "<th style='text-align:right;'>$k</th>";
                        array_push($bHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> 0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);

                    }
                    $i++;
                }

                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i > 0 && $k != "Distribution") {
                        $strHTML .= "<th style='text-align:right;'>$k</th>";
                    }
                    $i++;
                }

                if($col == 'Distribution'){
                    array_push($bHeaders,
                        [
                            'rowspan' => 0,
                            'colspan'=> 0,
                            'label' => 'Distribution',
                            'type' => 'string'
                        ]);
                }

                $strHTML .= "</tr></thead><tbody>";
            }
            /** Header - Section - End */
            $rowcls = "odd";
            $columns_total = array();
            $rows_total = 0;
            $grand_total = 0;
            $row_total = 0;


            foreach ($aData as $row) {

                //$row_total = 0;
                $j = 0;

                foreach ($row as $rKey => $cell) {

                    if ($j > 0) {
                        if(!isset($columns_total[$rKey])){
                            $columns_total[$rKey] = $cell;
                        }else{
                            $columns_total[$rKey] += $cell;
                        }

                        $row_total += $cell;
                    }
                    $j++;
                }
                $columns_total[($j - 1)] = $row_total;
            }

            $new_column_total = array();
            $new_column_total1 = array();
            $xlsxColTotal = '';
            foreach ($aData as $row) {

                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $strHTML .= "<tr class='$rowcls'>";

                $j = 0;

                $row_total = 0;
                $nRow = [];

                foreach ($row as $rKey => $cell) {

                    if ($j > 0) {
                        $row_total += $cell;
                        //$cell = ($cell / $columns_total[$rKey]) * 100;
                        if(!isset($new_column_total[$rKey])){
                            $new_column_total[$rKey] = $cell;
                        }else{
                            $new_column_total[$rKey] += $cell;
                        }

                        $nRow[$rKey] = round($cell);
                        $strHTML .= "<td style='text-align:right;'>" . number_format(round($cell)) . "</td>";
                    } else {
                        $nRow[$rKey] = $cell;
                        $strHTML .= "<td class='left-side-cell' style='text-align:left;'>" . $cell . "</td>";
                    }

                    $j++;
                }
               // $row_cell = ($row_total < 0) ? ($row_total / $columns_total[($j - 1)]) * 100 : 0; //doubt
                if(!isset($new_column_total[$j - 1])){
                    $new_column_total[$j - 1] = $row_total;
                }else{
                    $new_column_total[$j - 1] += $row_total;
                }

                $strHTML .= "<td style='text-align:right;'>" . number_format($row_total) . "</td>";
                $nRow['Total'] = number_format($row_total);
                $j = 0;
                foreach ($row as $rKey => $cell) {
                    if ($j > 0) {

                        if(!is_null($cell) && $cell > 0 && !empty($cell)){
                            $cell = ($cell / $columns_total[$rKey]) * 100;
                            if(!isset($new_column_total1[$rKey])){
                                $new_column_total1[$rKey] = $cell;
                            }else{
                                $new_column_total1[$rKey] += $cell;
                            }
                            $strHTML .= "<td style='text-align:right;'>" . round($cell) . "%</td>";
                        }else{
                            $strHTML .= "<td style='text-align:right;'>0%</td>";
                        }
                    }
                    $j++;
                }

                $xlsxColTotal = $new_column_total[$j - 1];
                $strHTML .= "</tr>";

                array_push($xVal,$nRow);
            }

            //die('cool');

            $strHTML .= "<tr class='totalCL $rowcls'>";
            $strHTML .= "<td class='left-side-cell' style='text-align:left;'>Total</td>";
            foreach($new_column_total as $columnTotal){
                $strHTML .= "<td style='text-align:right;'>" .number_format(round($columnTotal)). "</td>";
            }
            foreach($new_column_total1 as $columnTotal){
                $strHTML .= "<td style='text-align:right;'>" .round($columnTotal). "%</td>";
            }
            $strHTML .= "</tr>";
            array_push($xVal,['Total',$new_column_total,'Total'=>$xlsxColTotal]);

            $strHTML .= "</tbody></table>";

        }
        //return $strHTML;
        return ['html' => $strHTML,'xHeaders' => $xHeaders,'bHeaders' => $bHeaders,'val' => $xVal,'postfix' => 'Percent_of_Column_Total'];
    }

    public static function print_report_datatable_PCTN($aData, $col)
    {
        $xHeaders = $bHeaders = $xVal = array();
        $strHTML = "";
        $i = 0;
        if (!empty($aData)) {
            /** Header - Section - start */
            if ($i == 0) {
                $strHTML = "<table";

                $strHTML .= " class='table table-bordered table-hover color-table sr-table'><thead><tr>";
                /*********** Number - column header *******/
                $colspan = '';
                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i == 0) {
                        $strHTML .= "<th rowspan='2' style='text-align:left;'>$k</th>";
                        array_push($xHeaders,
                            [
                                'rowspan' => ($col == 'Distribution') ? 0 : 2,
                                'colspan'=>0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);
                    } else if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>" . $col . " - Number</th>";
                        $colspan = "Full";
                        array_push($xHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> ($col == 'Distribution') ? 0 : (count($aData[0]) - 1),
                                'label' => $col . " - Number",
                                'type' => is_numeric($col) ? 'integer': 'string'
                            ]);
                    }
                    $i++;
                }
                /*********** Number - column header *******/


                $strHTML .= "<th rowspan='2' style='text-align:right;'>Total</th>";
                array_push($xHeaders,
                    [
                        'rowspan' => ($col == 'Distribution') ? 0 : 2,
                        'colspan'=> 0,
                        'label' => 'Total',
                        'type' => 'string'
                    ]);

                /*********** Percent - column header *******/
                $colspan = '';
                foreach ($aData[0] as $k => $v) {
                    if (empty($colspan)) {
                        $strHTML .= $col == 'Distribution' ? "<th style='text-align:center;'>" . $col . "</th>" : "<th style='text-align:center;' colspan='" . (count($aData[0]) - 1) . "'>" . $col . " - Percent</th>";
                        $colspan = "Full";
                    }

                }
                /*********** Percent - column header *******/
                $strHTML .= "</tr><tr>";

                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i > 0 && $k != "Distribution") {
                        $strHTML .= "<th style='text-align:right;'>$k</th>";
                        array_push($bHeaders,
                            [
                                'rowspan' => 0,
                                'colspan'=> 0,
                                'label' => $k,
                                'type' => is_numeric($k) ? 'integer': 'string'
                            ]);

                    }
                    $i++;
                }

                $i = 0;
                foreach ($aData[0] as $k => $v) {
                    if ($i > 0 && $k != "Distribution") {
                        $strHTML .= "<th style='text-align:right;'>$k</th>";
                    }
                    $i++;
                }

                if($col == 'Distribution'){
                    array_push($bHeaders,
                        [
                            'rowspan' => 0,
                            'colspan'=> 0,
                            'label' => 'Distribution',
                            'type' => 'string'
                        ]);
                }

                $strHTML .= "</tr></thead><tbody>";
            }
            /** Header - Section - End */
            $rowcls = "odd";
            $columns_total = array();
            $rows_total = 0;
            $grand_total = 0;
            $row_total = 0;


            foreach ($aData as $row) {

                //$row_total = 0;
                $j = 0;

                foreach ($row as $rKey => $cell) {

                    if ($j > 0) {
                        if(!isset($columns_total[$rKey])){
                            $columns_total[$rKey] = $cell;
                        }else{
                            $columns_total[$rKey] += $cell;
                        }

                        $row_total += $cell;
                    }
                    $j++;
                }
                $columns_total[($j - 1)] = $row_total;
            }

            $new_column_total = array();
            $new_column_total1 = array();
            $xlsxColTotal = '';
            foreach ($aData as $row) {

                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $strHTML .= "<tr class='$rowcls'>";

                $j = 0;

                $row_total = 0;
                $nRow = [];

                foreach ($row as $rKey => $cell) {

                    if ($j > 0) {
                        $row_total += $cell;
                        //$cell = ($cell / $columns_total[$rKey]) * 100;
                        if(!isset($new_column_total[$rKey])){
                            $new_column_total[$rKey] = $cell;
                        }else{
                            $new_column_total[$rKey] += $cell;
                        }

                        //$nRow[$rKey] = round($cell);
                        $strHTML .= "<td style='text-align:right;'>" . number_format(round($cell)) . "</td>";
                    } else {
                        //$nRow[$rKey] = $cell.'%';
                        $strHTML .= "<td class='left-side-cell' style='text-align:left;'>" . $cell . "</td>";
                    }

                    $j++;
                }
                // $row_cell = ($row_total < 0) ? ($row_total / $columns_total[($j - 1)]) * 100 : 0; //doubt
                if(!isset($new_column_total[$j - 1])){
                    $new_column_total[$j - 1] = $row_total;
                }else{
                    $new_column_total[$j - 1] += $row_total;
                }

                $strHTML .= "<td style='text-align:right;'>" . number_format($row_total) . "</td>";

                $j = 0;
                foreach ($row as $rKey => $cell) {
                    if ($j > 0) {

                        if(!is_null($cell) && $cell > 0 && !empty($cell)){
                            $cell = ($cell / $columns_total[$rKey]) * 100;
                            if(!isset($new_column_total1[$rKey])){
                                $new_column_total1[$rKey] = $cell;
                            }else{
                                $new_column_total1[$rKey] += $cell;
                            }

                            $nRow[$rKey] = round($cell).'%';
                            $strHTML .= "<td style='text-align:right;'>" . round($cell) . "%</td>";
                        }else{
                            $nRow[$rKey] = round($cell).'%';
                            $strHTML .= "<td style='text-align:right;'>0%</td>";
                        }
                    }else{
                        $nRow[$rKey] = $cell.'%';
                    }
                    $j++;
                }
                $nRow['Total'] = number_format($row_total);
                $xlsxColTotal = $new_column_total[$j - 1];
                $strHTML .= "</tr>";

                array_push($xVal,$nRow);
            }

            //die('cool');

            $strHTML .= "<tr class='totalCL $rowcls'>";
            $strHTML .= "<td class='left-side-cell' style='text-align:left;'>Total</td>";
            foreach($new_column_total as $columnTotal){
                $strHTML .= "<td style='text-align:right;'>" .number_format(round($columnTotal)). "</td>";
            }
            foreach($new_column_total1 as $columnTotal){
                $strHTML .= "<td style='text-align:right;'>" .round($columnTotal). "%</td>";
            }
            $strHTML .= "</tr>";
            array_push($xVal,['Total',$new_column_total,'Total'=>$xlsxColTotal]);

            $strHTML .= "</tbody></table>";

        }
        //return $strHTML;
        return ['html' => $strHTML,'xHeaders' => $xHeaders,'bHeaders' => $bHeaders,'val' => $xVal,'postfix' => 'Percent_of_Column_Total'];
    }

    public static function generateSrPDF($row_variable,$column_variable,$function,$sum_variable,$show_as,$sSQL,$list_level,$listShortName,$imgTag,$imgPath,$eFolder,$t_name,$prefix,$SR_Attachment,$rpDesc,$report_orientation = 'portrait'){
        if (!empty($row_variable) && in_array($SR_Attachment,['onlylist','onlyreport','both'])) {
            if(file_exists(public_path() . '/' . $eFolder . '/'.$prefix . $t_name . '.pdf')){
                unlink(public_path() . '/' . $eFolder . '/'.$prefix . $t_name . '.pdf');
            }

            if(!empty($row_variable) && count($row_variable) > 1){
                $row_variablearr = $row_variable;
                $row_variable = $row_variable_groupBy = $row_variable_orderBy = '';
                foreach ($row_variablearr as $k => $rv){
                    if($k+1 == count($row_variablearr)){
                        $row_variable .= "LEFT(".$rv." + space(15), 15) as '".implode(' | ',$row_variablearr)."'";
                        $row_variable_groupBy .= "LEFT(".$rv." + space(15), 15) ";
                    }else{
                        $row_variable .= "LEFT(".$rv." + space(15), 15) + ' | ' +";
                        $row_variable_groupBy .= "LEFT(".$rv." + space(15), 15) + ' | ' +";
                    }
                    $row_variable_orderBy = "'".implode(' | ',$row_variablearr)."'";
                }
            }else{
                $row_variablearr = $row_variable;
                $row_variable = implode('',$row_variable);
                $row_variable_groupBy = $row_variable;
                $row_variable_orderBy = $row_variable;

            }

            try{
                DB::statement("drop table  temp1");
            }catch (\Exception $e){

            }
            $sSQL = strtolower($sSQL);
            $pos = stripos($sSQL, "order By");
            if ($pos != false) {
                $sSQL = substr($sSQL, 0, $pos - 1);
            }

            $sSQL = str_ireplace('where','WHERE',$sSQL);
            $sqlQuery = !empty($sSQL) ? explode("WHERE", $sSQL) : '';
            $where = "";
            if (is_array($sqlQuery) && count($sqlQuery) > 1) {
                $where = " WHERE " . $sqlQuery[1];
            }

            if (empty($column_variable) && $function == "Count") {

                DB::statement("SET ANSI_NULLS OFF; SET ANSI_WARNINGS OFF;select * into temp1 from (select " . $row_variable . " , count(*) as Distribution from " . $list_level . " " . $where . " group by " . $row_variable_groupBy . ") t");

                $sSqlTempSelect = DB::select("select * from temp1 Order By ".$row_variable_orderBy);
                $dData = collect($sSqlTempSelect)->map(function($x){ return (array) $x; })->toArray();

                $colVar = 'Distribution';
            }
            else if (empty($column_variable) && $function == "Sum") {

                DB::statement("SET ANSI_NULLS OFF; SET ANSI_WARNINGS OFF;select * into temp1 from (select " . $row_variable . ", sum(" . $sum_variable . ") as Distribution from " . $list_level . "  " . $where . " group by " . $row_variable_groupBy . ") t");

                $sSqlTempSelect = DB::select("select * from temp1 Order By ".$row_variable_orderBy);
                $dData = collect($sSqlTempSelect)->map(function($x){ return (array) $x; })->toArray();
                $colVar = 'Distribution';

            }
            else if (empty($column_variable) && in_array($function , ['Cs','Sc'])) {

                if(empty($sum_variable)){
                    return ['status' => false,'message' => 'Please select sum valriable'];
                }
                DB::statement("SET ANSI_NULLS OFF; SET ANSI_WARNINGS OFF;select * into temp1 from (select " . $row_variable . ",count(*) as Number, sum(" . $sum_variable . ") as [Total] from " . $list_level . "  " . $where . " group by " . $row_variable_groupBy . ") t");

                $dData = DB::select("select * from temp1 Order By ".$row_variable_orderBy);
                $dData = collect($dData)->map(function($x){ return (array) $x; })->toArray();
                $colVar = 'Count';

                $result = [];
                if(strtoupper($show_as) == 'NP' && $function == 'Cs'){
                    $result = Helper::print_report_datatable_NPCS($dData, $colVar, $sum_variable);
                } else if(strtoupper($show_as) == 'PN' && $function == 'Cs'){
                    $result = Helper::print_report_datatable_PNCS($dData, $colVar, $sum_variable);
                }else if(strtoupper($show_as) == 'NP'  && $function == 'Sc'){
                    $result = Helper::print_report_datatable_NPSC($dData, $colVar, $sum_variable);
                }else if(strtoupper($show_as) == 'PN'  && $function == 'Sc'){
                    $result = Helper::print_report_datatable_PNSC($dData, $colVar, $sum_variable);
                }
                $colVariable = empty($column_variable) ? 'Distribution' : $column_variable;
                $rowVariable = empty($row_variable) ? 'Summary' : "'".implode(' | ',$row_variablearr)."'";
                $sumVariable = empty($sum_variable) ? '' : $sum_variable;
                ;
                $rpfooter = !empty(trim($listShortName)) ? $listShortName : '';
                if(!empty($rpDesc)){
                    $rpheader = $rpDesc;
                }else{
                    $rpheader = $function == 'Count' ? (empty($column_variable) ? $colVariable . ' by ' . $rowVariable : $rowVariable . ' by ' . $colVariable) : ($sumVariable . ' by ' . $rowVariable . ' and ' . $colVariable);
                }
                if(in_array($SR_Attachment,['onlyreport','both'])){

                    PDF::loadView('layouts.pdf', [
                        'header' => preg_replace('/\_+/', ' ', ucwords($rpheader)),
                        'footer' => preg_replace('/\_+/', ' ', ucwords($rpfooter)),
                        'tablehtml' => $result['html'],
                        'charthtml' => $imgTag,
                        'filename' => $prefix . $t_name . '.pdf',
                    ])->setPaper('letter', $report_orientation)->setWarnings(false)->save(public_path() . '/' . $eFolder . '/'.$prefix . $t_name . '.pdf');

                    self::generateSrXLSX($result['html'],$prefix,$eFolder,$t_name,$imgPath);
                }
                return true;
            }
            else {

                $sSqlInsert = "SET ANSI_NULLS OFF; SET ANSI_WARNINGS OFF;select * into temp1 from (select " . $row_variable . " , " . $column_variable . ", ";

                if ($function == "Sum") {
                    $column = $sum_variable;
                } else {
                    $column = "*";
                }

                $sSqlInsert .= $function . "(" . $column . ")";
                $sSqlInsert .= "as Distribution from " . $list_level . " " . $where . " group by " . $row_variable_groupBy . ", " . $column_variable . ") t";
                DB::statement($sSqlInsert);

                $sSqlSelect = DB::select("select distinct " . $column_variable . " from temp1 Order By " . $column_variable);
                $dData = collect($sSqlSelect)->map(function($x){ return (array) $x; })->toArray();


                $cv = [];
                foreach ($dData as $key => $column) {
                    if(!empty($column[$column_variable])){
                        array_push($cv,"[".$column[$column_variable]."]");
                    }
                }
                $sSqlTempSelect = "select * from temp1 pivot (sum(Distribution) for " . $column_variable . " in(".implode(',',$cv).")) as rv";
                $aData = DB::select($sSqlTempSelect);
                $dData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
                $colVar = $column_variable;
            }

            if ($show_as == 'PRT') {
                $result = Helper::print_report_datatable_PRT($dData, $colVar);
            } else if ($show_as == 'PCT') {
                $result = Helper::print_report_datatable_PCT($dData, $colVar);
            } else if ($show_as == 'PGT') {
                $result = Helper::print_report_datatable_PGT($dData, $colVar);
            } else if (strtoupper($show_as) == 'NPCT') {
                $result = Helper::print_report_datatable_NPCT($dData, $colVar);
            } else if (strtoupper($show_as) == 'PCTN') {
                $result = Helper::print_report_datatable_PCTN($dData, $colVar);
            } else if(strtoupper($show_as) == 'NP'){
                $result = Helper::print_report_datatable_numberNPWC($dData, $colVar);
            } else if(strtoupper($show_as) == 'PN'){
                $result = Helper::print_report_datatable_numberPNWC($dData, $colVar);
            }  else {
                $result = Helper::print_report_datatable_number($dData, $colVar);
            }

            $colVariable = empty($column_variable) ? 'Distribution' : $column_variable;
            $rowVariable = empty($row_variable) ? 'Summary' : "'".implode(' | ',$row_variablearr)."'";
            $sumVariable = empty($sum_variable) ? '' : $sum_variable;

            $rpfooter = !empty(trim($listShortName)) ? $listShortName : '';
            if(!empty($rpDesc)){
                $rpheader = $rpDesc;
            }else{
                $rpheader = $function == 'Count' ? (empty($column_variable) ? $colVariable . ' by ' . $rowVariable : $rowVariable . ' by ' . $colVariable) : ($sumVariable . ' by ' . $rowVariable . ' and ' . $colVariable);
            }
            if(in_array($SR_Attachment,['onlyreport','both'])) {
                PDF::loadView('layouts.pdf', [
                    'header' => preg_replace('/\_+/', ' ', ucwords($rpheader)),
                    'footer' => preg_replace('/\_+/', ' ', ucwords($rpfooter)),
                    'tablehtml' => $result['html'],
                    'charthtml' => $imgTag,
                    'filename' => $prefix . $t_name . '.pdf',
                ])->setPaper('letter', $report_orientation)->setWarnings(false)->save(public_path() . '/' . $eFolder . '/' . $prefix . $t_name . '.pdf');

                self::generateSrXLSX($result['html'], $prefix, $eFolder, $t_name, $imgPath);
            }
            return true;
        }
    }

    public static function generateSrXLSX($tablehtml,$prefix,$eFolder,$t_name,$imgPath){
        try{
            if(file_exists(public_path() . '\\' . $eFolder . '\\'.$prefix . $t_name . '.xlsx')){
                unlink(public_path() . '\\' . $eFolder . '\\'.$prefix . $t_name . '.xlsx');
            }

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            $spreadhseet = $reader->loadFromString($tablehtml);
            $sheet = $spreadhseet->getActiveSheet();
            $sheet->getStyle('B3')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT );
            $sheet->getStyle('C3')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT );
            $sheet->getStyle('D3')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT );
            $sheet->getStyle('E3')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT );
            $spreadhseet->setActiveSheetIndex(0);
            $spreadhseet->getActiveSheet()->setTitle('Table');

            $spreadhseet->createSheet();
            $spreadhseet->setActiveSheetIndex(1);
            $spreadhseet->getActiveSheet()->setTitle('Chart');


            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Paid');
            $drawing->setDescription('Paid');
            $drawing->setPath($imgPath); // put your path and image here
            $drawing->setCoordinates('A2');
            $spreadhseet->getActiveSheet()->setShowGridlines(False);
            $drawing->setOffsetX(110);
            $drawing->setRotation(360);
            $drawing->getShadow()->setVisible(false);
            $drawing->getShadow()->setDirection(45);
            $drawing->setWorksheet($spreadhseet->getActiveSheet());

            $spreadhseet->setActiveSheetIndex(0);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadhseet, 'Xlsx');

            $writer->save(public_path() . '\\' . $eFolder . '\\'.$prefix . $t_name . '.xlsx');
        }catch (\Exception $exception){
            dd($exception->getMessage().'--'.public_path() . '\\' . $eFolder . '\\'.$prefix . $t_name . '.xlsx');
        }
        return true;
    }

    public static function getHighest($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::getHighest($value);
            }
        }

        sort($array);

        return array_pop($array);
    }

    public static function print_datatable($aData)
    {
        $strHTML = "";
        $i = 0;
        if (!empty($aData)) {
            if ($i == 0) {
                $strHTML = "<table class='table table-bordered table-hover color-table lkp-table'><thead><tr>";
                foreach ($aData[0] as $k => $v) {
                    if($k == 'Row_id'){
                        $strHTML .= "<th>Action</th>";
                    }else{
                        $strHTML .= "<th>$k</th>";
                    }
                }
                $strHTML .= "</thead><tbody>";
            }
            $rowcls = "odd";
            foreach ($aData as $v) {
                $rowcls = ($rowcls == "odd") ? "even" : "odd";
                $strHTML .= "<tr class='$rowcls'>";

                foreach ($v as $k=>$c) {
                    if($k == 'Row_id'){
                        //$strHTML .= "<td class='label'>" . $c . "</td>";
                        $strHTML .= "<td><a onclick='delete_row_comp(this)' class='cursor' id='" . $c . "'><img src='images/bin.jpg' style='width:15px;height:15px'></a></td></tr>";
                    }else{
                        $strHTML .= "<td>" . $c . "</td>";
                    }
                }
                $strHTML .= "</tr>";

            }
            $strHTML .= "</tbody></table>";
        }
        return $strHTML;
    }

    public static function get_ftpcombo()
    {
        $vsql = DB::select("select [row_id], [ftp_temp_name] from [UL_RepCmp_SFTP] order by row_id");
        $vData = collect($vsql)->map(function($x){ return (array) $x; })->toArray();
        $vsD = array();
        if (!empty($vData)) {
            foreach ($vData as $k => $row) {
                $vsD[] = implode(",", $row);
            }
        }
        $optstr = "<option value=''></option>";

        if(Auth::user()->User_Type == 'Full_Access'){
            $optstr .= "<option value='new'>Create New</option>";
        }

        for ($d = 0; $d < count($vsD); $d++) {
            $vrec = explode(",", $vsD[$d]);
            $optstr = $optstr . " <option value='" . $vrec[0] . "'>" . $vrec[1] . "</option>";
        }
        return $optstr;
    }

    public static function  get_ProCatOption($op)
    {
        $SQL = "SELECT [code_value] from [UC_Campaign_Lookup] WHERE code_type = '$op'";
        $aData = DB::select($SQL);
        $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
        $str = '';
        if (!empty($aData)) {
            foreach ($aData as $k => $row) {
                $op = implode(",", $row);
                $str .= "<option value='$op'>$op</option>";
            }
        }
        return $str;

    }

    public static function objectToArray($d)
    {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $d);
        } else {
            // Return array
            return $d;
        }
    }

    public static function getUsers($nuid){
        //$sSql = DB::select("Select * from User_Detail WHERE user_id <> $nuid");
        $sSql = DB::select("Select * from User_Detail");
        $users = collect($sSql)->map(function($x){ return (array) $x; })->toArray();
        return $users;
    }

    public static function getUserRecord($keyValue,$key){
        $sSql = DB::select("Select * from User_Detail WHERE $key = '$keyValue'");
        $user = collect($sSql)->map(function($x){ return (array) $x; })->toArray();
        return isset($user[0]) ? $user[0] : [];
    }


    public static function getSource(){
        $sSql = DB::select("Select * from Lookup");
        $sources = collect($sSql)->map(function($x){ return (array) $x; })->toArray();
        return $sources;
    }

    public static function add_date($givendate, $day = 0, $mth = 0, $yr = 0)
    {

        $cd = strtotime($givendate);
        $newdate = date('Y-m-d h:i:s', mktime(date('h', $cd),
            date('i', $cd), date('s', $cd), date('m', $cd) + $mth,
            date('d', $cd) + $day, date('Y', $cd) + $yr));

        return $newdate;
    }

    public static function dateDiff($startDate, $endDate)
    {
        // Parse dates for conversion
        $startArry = date_parse($startDate);
        $endArry = date_parse($endDate);

        // Convert dates to Julian Days
        $start_date = gregoriantojd($startArry["month"], $startArry["day"], $startArry["year"]);
        $end_date = gregoriantojd($endArry["month"], $endArry["day"], $endArry["year"]);

        // Return difference
        return round(($end_date - $start_date), 0);
    }

    public static function schtask_curl($sch_command){
        $params = array(
            "sch_command" => $sch_command
        );
        $hostUrl = config('constant.hostUrl');
        $schtasks_dir = config('constant.schtasks_dir');
        $curlcert = config('constant.curlcert');

        $url = $hostUrl.$schtasks_dir."/task_request.php";
        $postData = '';
        //create name value pairs seperated by &
        foreach($params as $k => $v)
        {
            $postData .= $k . '='.$v.'&';
        }
        $postData = rtrim($postData, '&');

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_CAINFO, $curlcert);
        curl_setopt($ch,CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output=curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno= curl_errno($ch);
        //$error_msg = curl_error($ch);
        //echo "Curl Errno returned $curl_errno <br/> $error_msg";
        curl_close($ch);
    }

    public static function shareReport($eCampid,$t_type,$user_id,$users,$custom_message,$clientname,$email_flag = 0,$Is_Delivered = 0){
        try{
            /*$tTable = $t_type == 'A' ? 'UR_Report_Templates' : 'UC_Campaign_Templates';
            $sSql = DB::select("SELECT * FROM $tTable WHERE row_id='".$eCampid."' AND t_type='".$t_type."'");
            $result = collect($sSql)->map(function($x){ return (array) $x; })->toArray();
            $listShortName = '';
            if(isset($result[0])){
                $result = $result[0];
                $listShortName = $result['list_short_name'];
            }*/

            if($t_type == 'C'){
                $result = CampaignTemplate::with('rpschedule.ccschstatusmap')->where('row_id',$eCampid)->where('t_type',$t_type)->first()->toArray();
                $listShortName = $result['list_short_name'];
                $file_Name = isset($result['rpschedule']['ccschstatusmap'][0]) ? $result['rpschedule']['ccschstatusmap'][0]['file_name'] : $listShortName;
            }else{
                $result = ReportTemplate::with('rpschedule.rpschstatusmap')->where('row_id',$eCampid)->where('t_type',$t_type)->first()->toArray();
                $listShortName = $result['list_short_name'];
                $file_Name = isset($result['rpschedule']['rpschstatusmap'][0]) ? $result['rpschedule']['rpschstatusmap'][0]['file_name'] : $listShortName;
            }




            DB::statement("DELETE FROM UL_RepCmp_Share WHERE User_id = $user_id AND camp_tmpl_id = $eCampid AND t_type = '$t_type'");
            if(isset($users) && count($users) > 0){
                foreach ($users as $ToUser){
                    $user = User::where('User_ID',$ToUser)->first();
                    if($user) {
                        if($email_flag == 1){
                            self::sendShareEmail($result,$user,$clientname,$custom_message,$file_Name,$t_type);
                        }
                        if(!empty($custom_message)){
                            $Is_Delivered = 1;
                        }
                        DB::insert("Insert INTO UL_RepCmp_Share (User_id,camp_tmpl_id,Shared_With_User_id,t_type,Is_Delivered,Custom_Message) VALUES ($user_id, $eCampid, $ToUser, '$t_type',$Is_Delivered,'$custom_message')");
                    }
                }
            }
            return true;
        }catch (\Exception $exception){
            return false;
        }
    }

    public static function sendShareEmail($result,$user,$clientname,$custom_message,$listShortName,$type){
        $objDemo = new \stdClass();
        $objDemo->data = (object)$result;
        $objDemo->To = $user->User_Email;
        $objDemo->Cc = 'gurri.dhiman85@gmail.com';
        $objDemo->Bcc = '';

        $cm = $type == 'A' ? ' - Report ' : ' - Campaign ';
        $objDemo->Sub = !empty($Sub) ? $clientname . ' - ' . $Sub : $clientname . $cm . $listShortName;
        $objDemo->limitedtextarea1 = $custom_message;

        $objDemo->sender = 'Data Square Support Team';
        $objDemo->senderEmail = 'esupport@datasquare.com';
        $objDemo->receiver = $user->User_FName . ' ' . $user->User_LName;
        $objDemo->sharedByName = Auth::user()->User_FName . ' ' . Auth::user()->User_LName;
        $objDemo->sharedByEmail = Auth::user()->User_Email;
        $objDemo->listShortName = $listShortName;
        $objDemo->clientname = $clientname;

        $type == 'A' ? Mail::to($user->User_Email)->send(new ShareReportEmail($objDemo)) : Mail::to($user->User_Email)->send(new ShareCampaignEmail($objDemo));

        if (count(Mail::failures()) > 0) {

            $emsg = "There was one or more failures. They were: <br />";

            foreach (Mail::failures() as $email_address) {
                $emsg .= " - $email_address <br />";
            }

        }
    }

    public static function getLookupFiltersFieldsValues($params = [
            'campaigns' => false,
            'countries' => false,
            'ZSS_Segments' => false,
            'MemberSegments' => false,
            'AddressQualities' => false,
            'DonorSegments' => false,
            'EventSegments' => false,
            'LifecycleSegments' => false,
            'Productcat1_Des'      =>  false,
            'Productcat2_Des'      =>  false,
            'Product'          =>  false,
        ]){

		$campaigns = $countries = $ZSS_Segments = $MemberSegments = $AddressQualities = $DonorSegments = $EventSegments =$LifecycleSegments = $Productcat1_Des = $Productcat2_Des = $Product = [];

        if($params['campaigns']){
            $campaigns = DB::table('Contact_View')
                ->distinct()
                ->whereNotNull('TouchCampaign')
                ->where('TouchCampaign','<>','')
                ->orderBy('TouchCampaign')
                ->pluck('TouchCampaign')->toArray();
        }

        if($params['countries']) {
            $countries = DB::table('Contact_View')
                ->distinct()
                ->whereNotNull('country')
                ->where('country', '<>', '')
                ->orderBy('country')
                ->pluck('country')->toArray();
        }

        if($params['ZSS_Segments']) {
            $ZSS_Segments = DB::table('Contact_View')
                ->distinct()
                ->whereNotNull('ZSS_Segment')
                ->where('ZSS_Segment', '<>', '')
                ->orderBy('ZSS_Segment')
                ->pluck('ZSS_Segment')->toArray();
        }

        if($params['MemberSegments']) {
            $MemberSegments = DB::table('Contact_View')
                ->distinct()
                ->whereNotNull('MemberSegment')
                ->where('MemberSegment', '<>', '')
                ->orderBy('MemberSegment')
                ->pluck('MemberSegment')->toArray();
        }

        if($params['AddressQualities']) {
            $AddressQualities = DB::table('Contact_View')
                ->distinct()
                ->whereNotNull('AddressQuality')
                ->where('AddressQuality', '<>', '')
                ->orderBy('AddressQuality')
                ->pluck('AddressQuality')->toArray();
        }

        if($params['DonorSegments']) {
            $DonorSegments = DB::table('Contact_View')
                ->distinct()
                ->whereNotNull('DonorSegment')
                ->where('DonorSegment', '<>', '')
                ->orderBy('DonorSegment')
                ->pluck('DonorSegment')->toArray();
        }

        if($params['EventSegments']) {
            $EventSegments = DB::table('Contact_View')
                ->distinct()
                ->whereNotNull('EventSegment')
                ->where('EventSegment', '<>', '')
                ->orderBy('EventSegment')
                ->pluck('EventSegment')->toArray();
        }

        if($params['LifecycleSegments']) {
            $LifecycleSegments = DB::table('Contact_View')
                ->distinct()
                ->whereNotNull('LifecycleSegment')
                ->where('LifecycleSegment', '<>', '')
                ->orderBy('LifecycleSegment')
                ->pluck('LifecycleSegment')->toArray();
        }

        if($params['Productcat1_Des']) {
            $Productcat1_Des = DB::table('Sales_View')
                ->distinct()
                ->whereNotNull('Productcat1_Des')
                ->where('Productcat1_Des', '<>', '')
                ->orderBy('Productcat1_Des')
                ->pluck('Productcat1_Des')->toArray();
        }

        if($params['Productcat2_Des']) {
            $Productcat2_Des = DB::table('Sales_View')
                ->distinct()
                ->whereNotNull('Productcat2_Des')
                ->where('Productcat2_Des', '<>', '')
                ->orderBy('Productcat2_Des')
                ->pluck('Productcat2_Des')->toArray();
        }

        if($params['Product']) {
            /*$Product = DB::table('Sales_View')
                ->distinct()
                ->whereNotNull('Product')
                ->where('Product', '<>', '')
                ->orderBy('Product')
                ->pluck('Product')->toArray();*/
            $Product = DB::table('Sales_View')
                ->distinct('Product')
                ->whereNotNull('Product')
                ->where('Product', '<>', '')
                ->orderBy('ProdA')
                ->get([DB::raw('Product, productcat1 + \'-\'+productcat2+\'-\'+product as ProdA, productcat1 + \'-\'+product as ProdB')])->toArray();

        }
        return [
            'campaigns' => $campaigns,
            'countries' => $countries,
            'ZSS_Segments' => $ZSS_Segments,
            'MemberSegments' => $MemberSegments,
            'AddressQualities' => $AddressQualities,
            'DonorSegments' => $DonorSegments,
            'EventSegments' => $EventSegments,
            'LifecycleSegments' => $LifecycleSegments,
            'Productcat1_Des' => $Productcat1_Des,
            'Productcat2_Des' => $Productcat2_Des,
            'Product' => $Product,
        ];
    }


    public static function getActivityFiltersFieldsValues($params = [
        'Productcat1_Des'      =>  false,
        'Productcat2_Des'      =>  false,
        'Product'          =>  false,
        'Class'             =>  false
    ]){

        $Productcat1_Des = $Productcat2_Des = $Product = $Class = [];

        if($params['Productcat1_Des']) {
            $Productcat1_Des = DB::table('Sales_View')
                ->distinct()
                ->whereNotNull('Productcat1_Des')
                ->where('Productcat1_Des', '<>', '')
                ->orderBy('Productcat1_Des')
                ->pluck('Productcat1_Des')->toArray();
        }

        if($params['Productcat2_Des']) {
            $Productcat2_Des = DB::table('Sales_View')
                ->distinct()
                ->whereNotNull('Productcat2_Des')
                ->where('Productcat2_Des', '<>', '')
                ->orderBy('Productcat2_Des')
                ->pluck('Productcat2_Des')->toArray();
        }

        if($params['Product']) {
            /*$Product = DB::table('Sales_View')
                ->distinct()
                ->whereNotNull('Product')
                ->where('Product', '<>', '')
                ->orderBy('Product')
                ->pluck('Product')->toArray();*/
            $Product = DB::table('Sales_View')
                ->distinct('Product')
                ->whereNotNull('Product')
                ->where('Product', '<>', '')
                ->orderBy('ProdA')
                ->get([DB::raw('Product, productcat1 + \'-\'+productcat2+\'-\'+product as ProdA, productcat1 + \'-\'+product as ProdB')])->toArray();

        }

        if($params['Class']) {
            $Class = DB::table('Sales_View')
                ->distinct()
                ->whereNotNull('Class')
                ->where('Class', '<>', '')
                ->orderBy('Class')
                ->pluck('Class')->toArray();
        }
        return [

            'Productcat1_Des' => $Productcat1_Des,
            'Productcat2_Des' => $Productcat2_Des,
            'Product' => $Product,
            'Class' => $Class,
        ];
    }

    public static function ApplyFiltersCondition($filters,$user_id){
        $tCArray = array();
        $tStatusArray = array();
        $tTCampaignArray = array();
        $tOSArray = array();
        $tMSArray = array();
        $tESArray = array();
        $tDSArray = array();
        $tEVSArray = array();
        $tLSArray = array();
        $tPC1Array = array();
        $tPC2Array = array();
        $tPArray = array();
        $tTagArray = array();

        if(isset($filters['Tag'])){
            foreach ($filters['Tag'] as $tTag) {
                $tTagArray[] = str_replace("'", "''", $tTag);
            }
        }
        if(isset($filters['ZSS_Segment'])){
            foreach ($filters['ZSS_Segment'] as $tOS) {
                $tOSArray[] = "'%" . str_replace("'", "''", $tOS) . "%'";
            }
        }

        if(isset($filters['MemberSegment'])) {
            foreach ($filters['MemberSegment'] as $tMS) {
                $tMSArray[] = "'%" . str_replace("'", "''", $tMS) . "%'";
            }
        }

        if(isset($filters['DonorSegment'])) {
            foreach ($filters['DonorSegment'] as $tDS) {
                $tDSArray[] = "'%" . str_replace("'", "''", $tDS) . "%'";
            }
        }

        if(isset($filters['EventSegment'])) {
            foreach ($filters['EventSegment'] as $tEVS) {
                $tEVSArray[] = "'%" . str_replace("'", "''", $tEVS) . "%'";
            }
        }

        if(isset($filters['LifecyleSegment'])) {
            foreach ($filters['LifecyleSegment'] as $tLS) {
                $tLSArray[] = "'%" . str_replace("'", "''", $tLS) . "%'";
            }
        }

        if(isset($filters['AddressQuality'])) {
            foreach ($filters['AddressQuality'] as $tES) {
                $tESArray[] = "'%" . str_replace("'", "''", $tES) . "%'";
            }
        }

        if(isset($filters['country'])) {
            foreach ($filters['country'] as $tC) {
                $tCArray[] = "'%" . str_replace("'", "''", $tC) . "%'";
            }
        }

        $txtExtendedname = isset($filters['Extendedname']) ? $filters['Extendedname'][0] : '';

        $txtCompany = isset($filters['Company']) ? $filters['Company'][0] : '';
        $txtAddress = isset($filters['Address']) ? $filters['Address'][0] : '';
        $txtEmail = isset($filters['Email']) ? $filters['Email'][0] : '';
        $txtPhone = isset($filters['Phone']) ? $filters['Phone'][0] : '';

        $txtNotes = isset($filters['Notes']) ? $filters['Notes'][0] : '';
        $txtTDate = isset($filters['TouchDate']) ? $filters['TouchDate'][0] : '';
        $txtSearch = isset($filters['searchterm']) ? $filters['searchterm'][0] : '';

        $txtLast_5Yrs_GiftsAmt = isset($filters['Last_5Yrs_GiftsAmt'][0]) ? [$filters['Last_5Yrs_GiftsAmt_op'][0],$filters['Last_5Yrs_GiftsAmt'][0]] : ['',''];

        $txtLife2date_GiftsAmt = isset($filters['Life2date_GiftsAmt'][0]) ? [$filters['Life2date_GiftsAmt_op'][0],$filters['Life2date_GiftsAmt'][0]] : ['',''];

        $txtLast_5Yrs_SpendAmt = isset($filters['Last_5Yrs_SpendAmt'][0]) ? [$filters['Last_5Yrs_SpendAmt_op'][0],$filters['Last_5Yrs_SpendAmt'][0]] : ['',''];

        $txtLife2date_SpendAmt = isset($filters['Life2date_SpendAmt'][0]) ? [$filters['Life2date_SpendAmt_op'][0],$filters['Life2date_SpendAmt'][0]] : ['',''];

        $txtDayssincelastvisit = isset($filters['Dayssincelastvisit'][0]) ? [$filters['Dayssincelastvisit_op'][0],$filters['Dayssincelastvisit'][0]] : ['',''];

        $txtYearssincefirstvisit = isset($filters['Yearssincefirstvisit'][0]) ? [$filters['Yearssincefirstvisit_op'][0],$filters['Yearssincefirstvisit'][0]] : ['',''];

        $txtDaysSince1stCreate = isset($filters['DaysSince1stCreate'][0]) ? [$filters['DaysSince1stCreate_op'][0],$filters['DaysSince1stCreate'][0]] : ['',''];

        $txtDaysSinceLastUpdate = isset($filters['DaysSinceLastUpdate'][0]) ? [$filters['DaysSinceLastUpdate_op'][0],$filters['DaysSinceLastUpdate'][0]] : ['',''];

        $txtProductcat1_Des = isset($filters['Productcat1_Des']) ? $filters['Productcat1_Des'][0] : '';
        $txtProductcat2_Des = isset($filters['Productcat2_Des']) ? $filters['Productcat2_Des'][0] : '';
        $txtProduct = isset($filters['Product']) ? $filters['Product'][0] : '';

        $contactids = isset($filters['contactids']) ? $filters['contactids'][0] : '';

        $sWhere = "";

        $specWhere = "";
        $aAd = 0;

        if(isset($filters['Productcat1_Des'])) {
            foreach ($filters['Productcat1_Des'] as $PC1) {
                $tPC1Array[] = "'" . str_replace("'", "''", $PC1) . "'";
            }

            if (count($tPC1Array) > 0) {
                if(!empty($sWhere)){
                    $sWhere .= ' and ';
                }
                $specWhere .= " ds_mkc_contactid in (select distinct ds_mkc_contactid from Sales_View where Productcat1_Des= " . implode(" OR Productcat1_Des= ", $tPC1Array) . ")";
                $aAd++;
            }
        }

        if(isset($filters['Productcat2_Des'])) {
            foreach ($filters['Productcat2_Des'] as $PC2) {
                $tPC2Array[] = "'" . str_replace("'", "''", $PC2) . "'";
            }

            if (count($tPC2Array) > 0) {
                $specWhere .= $aAd > 0 ? " and " : "";
                $specWhere .= " ds_mkc_contactid in (select distinct ds_mkc_contactid from Sales_View where Productcat2_Des= " . implode(" OR Productcat2_Des= ", $tPC2Array) . ")";
                $aAd++;
            }
        }

        if(isset($filters['Product'])) {
            foreach ($filters['Product'] as $P) {
                $tPArray[] = "'%" . str_replace("'", "''", $P) . "%'";
            }

            if (count($tPArray) > 0) {
                $specWhere .= $aAd > 0 ? " and " : "";
                $specWhere .= " ds_mkc_contactid in (select distinct ds_mkc_contactid from Sales_View where productcat1 + '-'+product like " . implode(" OR productcat1 + '-'+product like ", $tPArray) . ")";
                $aAd++;
            }
        }


        /*if ($txtProductcat1_Des != "") {
            $specWhere .= "ds_mkc_contactid in (select distinct ds_mkc_contactid from Sales_View where Productcat1_Des='" . trim($txtProductcat1_Des) . "')";
            $aAd++;
        }

        if ($txtProductcat2_Des != "") {
            $specWhere .= $aAd > 0 ? " and " : "";
            $specWhere .= " ds_mkc_contactid in (select distinct ds_mkc_contactid from Sales_View where Productcat2_Des= '" . trim($txtProductcat2_Des) . "')";
        }*/

        /*if ($txtProduct != "") {
            $specWhere .= $aAd > 0 ? " and " : "";
            $specWhere .= " ds_mkc_contactid in (select distinct ds_mkc_contactid from Sales_View where productcat1 + '-'+product like '%" . trim($txtProduct) . "%')";
        }*/
        if(!empty($specWhere)){
            $sWhere .=  '('.$specWhere.') ';
        }


        if (count($tCArray) > 0) {
            if(!empty($specWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (isnull(Country,'') like  " . implode(" OR Country like ", $tCArray) . ")";
        }

        if (count($tTagArray) > 0) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (isnull(Tag,'') In (" . implode(",", $tTagArray) . "))";
        }

        if (count($tOSArray) > 0) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (isnull(ZSS_Segment,'') like " . implode(" OR ZSS_Segment like ", $tOSArray) . ")";
        }

        if (count($tMSArray) > 0) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (isnull(MemberSegment,'') like " . implode(" OR MemberSegment like ", $tMSArray) . ")";
        }

        if (count($tESArray) > 0) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (isnull(AddressQuality,'') like " . implode(" OR AddressQuality like ", $tESArray) . ")";
        }

        if (count($tDSArray) > 0) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (isnull(DonorSegment,'') like " . implode(" OR DonorSegment like ", $tDSArray) . ")";
        }

        if (count($tEVSArray) > 0) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (isnull(EventSegment,'') like " . implode(" OR EventSegment like ", $tEVSArray) . ")";
        }

        if (count($tLSArray) > 0) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (isnull(LifecycleSegment,'') like " . implode(" OR LifecycleSegment like ", $tLSArray) . ")";
        }

        if ($txtExtendedname != "") {
            //$num = is_numeric($txtExtendedname) ? $txtExtendedname : '';
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (isnull(DS_MKC_ContactID,'') like '%" . $txtExtendedname . "%' OR isnull(ds_mkc_householdid,'') like '%" . $txtExtendedname . "%' OR isnull(DS_MKC_Household_Num,'') like '%" . $txtExtendedname . "%' OR isnull(Extendedname,'') like '%" . $txtExtendedname . "%' OR isnull(Salutation,'') like '%" . $txtExtendedname . "%' OR isnull(Suffix,'') like '%" . $txtExtendedname . "%')";
        }

        if ($contactids != "") {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (isnull(DS_MKC_ContactID,'') in (" . $contactids . "))";
        }

        if ($txtCompany != "" || in_array($txtCompany,["blank","Blank","not blank","Not Blank"])) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            if ($txtCompany != "" && !in_array($txtCompany,["blank","Blank","not blank","Not Blank"])) {
                $sWhere .= " (isnull(Company,'') like '%" . $txtCompany . "%')";

            }elseif (in_array($txtCompany,["blank","Blank"])){
                $sWhere .= " (isnull(Company,'') =  '')";

            }elseif (in_array($txtCompany,["not blank","Not Blank"])){
                $sWhere .= " (isnull(Company,'') !=  '')";
            }
        }

        if ($txtAddress != "" || in_array($txtAddress,["blank","Blank","not blank","Not Blank"])) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            if ($txtAddress != "" && !in_array($txtAddress,["blank","Blank","not blank","Not Blank"])) {
                $sWhere .= " (isnull(address,'') like '%" . $txtAddress . "%' OR city like '%" . $txtAddress . "%' OR state like '%" . $txtAddress . "%' OR Zip like '%" . $txtAddress . "%')";

            }elseif (in_array($txtAddress,["blank","Blank"])){
                $sWhere .= " (isnull(address,'') = '' AND city = '' AND state = '' AND Zip = '')";

            }elseif (in_array($txtAddress,["not blank","Not Blank"])){
                $sWhere .= " (isnull(address,'') != '' AND city != '' AND state != '' AND Zip != '')";
            }
        }

        if ($txtEmail != "" || in_array($txtEmail,["blank","Blank","not blank","Not Blank"])) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            if ($txtEmail != "" && !in_array($txtEmail,["blank","Blank","not blank","Not Blank"])) {
                $sWhere .= " (isnull(Email,'') like '%" . $txtEmail . "%')";

            }elseif (in_array($txtEmail,["blank","Blank"])){
                $sWhere .= " (isnull(Email,'') = '')";

            }elseif (in_array($txtEmail,["not blank","Not Blank"])){
                $sWhere .= " (isnull(Email,'') != '')";
            }

        }
        if ($txtPhone != "" || in_array($txtPhone,["blank","Blank","not blank","Not Blank"])) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            if ($txtPhone != "" && !in_array($txtPhone,["blank","Blank","not blank","Not Blank"])) {
                $sWhere .= " (isnull(phone,'') like '%" . $txtPhone . "%')";

            }elseif (in_array($txtPhone,["blank","Blank"])){
                $sWhere .= " (isnull(phone,'') = '')";

            }elseif (in_array($txtPhone,["not blank","Not Blank"])){
                $sWhere .= " (isnull(phone,'') != '')";
            }
        }

        if ($txtNotes != "" || in_array($txtPhone,["blank","Blank","not blank","Not Blank"])) {
            if (!empty($sWhere)) {
                $sWhere .= ' and ';
            }
            if ($txtNotes != "" && !in_array($txtNotes, ["blank", "Blank", "not blank", "Not Blank"])) {
                $sWhere .= " (notes like '%" . $txtNotes . "%')";

            } elseif (in_array($txtNotes, ["blank", "Blank"])) {
                $sWhere .= " (notes = '')";

            } elseif (in_array($txtNotes, ["not blank", "Not Blank"])) {
                $sWhere .= " (notes != '')";
            }
        }

        if ($txtSearch != "") {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            //$num = is_numeric($txtSearch) ? $txtSearch : '';
            $sWhere .= " (isnull(DS_MKC_ContactID,'') like '%" . $txtSearch . "%' OR isnull(ds_mkc_householdid,'') like '%" . $txtSearch . "%' OR isnull(DS_MKC_Household_Num,'') like '%" . $txtSearch . "%' OR isnull(Extendedname,'') like '%" . $txtSearch . "%' OR isnull(Salutation,'') like '%" . $txtSearch . "%' OR isnull(Suffix,'') like '%" . $txtSearch . "%' OR isnull(Company,'') like '%" . $txtSearch . "%')";
        }

        if ($txtLast_5Yrs_GiftsAmt[1] != "") {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (Last_5Yrs_GiftsAmt " . $txtLast_5Yrs_GiftsAmt[0] . " " . $txtLast_5Yrs_GiftsAmt[1] . ")";
        }

        if ($txtLife2date_GiftsAmt[1] != "") {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (Life2date_GiftsAmt " . $txtLife2date_GiftsAmt[0] . " " . $txtLife2date_GiftsAmt[1] . ")";
        }
        if ($txtLast_5Yrs_SpendAmt[1] != "") {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (Last_5Yrs_SpendAmt " . $txtLast_5Yrs_SpendAmt[0] . " " . $txtLast_5Yrs_SpendAmt[1] . ")";
        }

        if ($txtLife2date_SpendAmt[1] != "") {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (Life2date_SpendAmt " . $txtLife2date_SpendAmt[0] . " " . $txtLife2date_SpendAmt[1] . ")";
        }

        if ($txtDayssincelastvisit[1] != "") {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (DaysSinceLastVisit " . $txtDayssincelastvisit[0] . " " . $txtDayssincelastvisit[1] . ")";
        }

        if ($txtYearssincefirstvisit[1] != "") {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (YearsSinceFirstVisit " . $txtYearssincefirstvisit[0] . " " . $txtYearssincefirstvisit[1] . ")";
        }

        if($txtDaysSince1stCreate[1]!=""){
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (DaysSince1stCreate ".$txtDaysSince1stCreate[0]." ".$txtDaysSince1stCreate[1].")";
        }

        if($txtDaysSinceLastUpdate[1]!=""){
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (DaysSinceLastUpdate ".$txtDaysSinceLastUpdate[0]." ".$txtDaysSinceLastUpdate[1].")";
        }



        /************************************* Phone Filters **********************************/
        $tStatusArray = array();
        $tTCampaignArray = array();
        $txtTDate = isset($filters['TouchDate']) ? $filters['TouchDate'][0] : '';

        if(isset($filters['status'])){
            foreach ($filters['status'] as $tSt) {
                $tStatusArray[] = "'%" . str_replace("'", "''", $tSt) . "%'";
            }
        }

        if(isset($filters['TouchCampaign'])){
            foreach ($filters['TouchCampaign'] as $tTC) {
                $tTCampaignArray[] = "'%" . str_replace("'", "''", $tTC) . "%'";
            }
        }

        $sPWhere = "";

        $specWhere = "";
        if (count($tStatusArray) > 0) {
            if(!empty($sPWhere)){
                $sPWhere .= ' and ';
            }
            $sPWhere .= " (isnull(TouchStatus,'') like " . implode(" OR isnull(TouchStatus,'') like ", $tStatusArray) . ")";
        }
        if (count($tTCampaignArray) > 0) {
            if(!empty($sPWhere)){
                $sPWhere .= ' and ';
            }
            $sPWhere .= " (isnull(TouchCampaign,'') like " . implode(" OR isnull(TouchCampaign,'') like ", $tTCampaignArray) . ")";
        }

        if ($txtTDate != "" || in_array($txtTDate,["blank","Blank","not blank","Not Blank"])) {
            if(!empty($sPWhere)){
                $sPWhere .= ' and ';
            }
            if ($txtTDate != "" && !in_array($txtTDate,["blank","Blank","not blank","Not Blank"])) {
                $sPWhere .= " (isnull(TouchDate,'') like '%" . $txtTDate . "%')";

            }
        }

        /************************************* Phone Filters **********************************/

        $aDataAuth = DB::table('User_Authenticate')->where('User_ID',$user_id)->where('User_Records','Few')->first(['User_Records']);
        $urCon = '';
        if($aDataAuth){
            $urCon = " (mgr1 in (select user_id from User_Authenticate where user_id = ".$user_id.") or mgr2 in (select user_id from User_Authenticate where user_id = ".$user_id."))";
        }

        return [
            'lookupWhere' => $sWhere,
            'urCon' => $urCon,
            'phoneWhere' => !empty($sPWhere) ? ' WHERE '.$sPWhere : '',
            'finalClause' => !empty($sWhere) ?  (!empty($urCon) ? ' WHERE '.$sWhere.' AND '.$urCon : ' WHERE '.$sWhere) : (!empty($urCon) ? ' WHERE '.$urCon : ''),
        ];
        //return !empty($sWhere) ?  (!empty($urCon) ? ' WHERE '.$sWhere.' AND '.$urCon : ' WHERE '.$sWhere) : (!empty($urCon) ? ' WHERE '.$urCon : '');
    }

    public static function ApplyFiltersConditionForRP($filters, $query, $ver = false){
        $txtSearch = isset($filters['searchterm']) ? $filters['searchterm'][0] : '';

        if(!empty($txtSearch)){
            $query->where(function ($qry) use ($ver,$txtSearch){
                if($ver){
                    $qry->whereHas('rpschedule.rpschstatusmap',function ($qry) use ($txtSearch){
                        $qry->where('file_name','like',"%{$txtSearch}%");
                    });

                }else{
                    $qry->where('list_short_name','like', '%'.$txtSearch.'%');

                }
                $qry->orWhereHas('rpmeta',function ($subqry) use ($txtSearch){
                    $subqry->where('Category','like',"%{$txtSearch}%");
                });
            });


        }
    }

    public static function ApplyFiltersConditionForCC($filters, $query, $ver = false){
        $txtSearch = isset($filters['searchterm']) ? $filters['searchterm'][0] : '';

        if(!empty($txtSearch)){
            $query->where(function ($qry) use ($ver,$txtSearch){
                if($ver){
                    $qry->whereHas('rpschedule.ccschstatusmap',function ($qry) use ($txtSearch){
                        $qry->where('file_name','like',"%{$txtSearch}%");
                    });

                }else{
                    $qry->where('list_short_name','like', '%'.$txtSearch.'%');

                }
                $qry->orWhereHas('rpmeta',function ($subqry) use ($txtSearch){
                    $subqry->where('Category','like',"%{$txtSearch}%");
                });
            });


        }
    }

    public static function getDownloadableColumns($haveColumns = [],$downloadableColumns = [],$ignorePositions = []){
        try{
            $selectColumnsAS = [];
            $selectColumns = [];
            foreach ($downloadableColumns as $columnposition => $downloadableColumn){
                if(!in_array($downloadableColumn,$ignorePositions)){
                    array_push($selectColumnsAS, $haveColumns[$downloadableColumn][0].' as ['.$haveColumns[$downloadableColumn][1].']');
                    array_push($selectColumns, $haveColumns[$downloadableColumn][0]);
                }
            }
            return [
                'column_as' => count($selectColumnsAS) > 0 ? implode(',',$selectColumnsAS) : ' * ',
                'column' => count($selectColumns) > 0 ? implode(',',$selectColumns) : ' * ',
            ];
        }catch (\Exception $exception){
            return ' * ';
        }
    }

    public static function affectedRowsTagLine($rows_affected = 0){
        return $rows_affected . " row(s) affected.\n";
    }

    public static function messageTabContent($rows){
        $msghtml = '<table class="table table-bordered table-hover color-table lkp-table"><thead><tr><th>Message</th></tr></thead><tbody>';
        foreach ($rows as $row){
            if (isset($row)){
                $msghtml .= '<tr><td>' . self::affectedRowsTagLine($row)  . '</td></tr>';
            }
        }
        $msghtml .= '</tbody></table>';

        return $msghtml;
    }

    public static function queryTabContent($sqlQueries = []){
        $sqlHtml = '';
        if (count($sqlQueries) > 0) {
            $sqlHtml = '<table class="table table-bordered table-hover color-table lkp-table">';
            for ($k = 1; $k <= count($sqlQueries); $k++) {
                if (!empty($sqlQueries[$k])) {
                    $sqlHtml .= '<tr><td>' . $sqlQueries[$k] . '</td></tr>';
                }
            }
            $sqlHtml .= '</table>';
        }
        return $sqlHtml;
    }

    public static function format_size($size)
    {
        $sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        if ($size == 0) {
            return ('n/a');
        } else {
            return (round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]);
        }
    }

    public static function getColumns($menu_level1,$menu_level2){
        $columns = UAFieldMapping::where('Menu_level1',$menu_level1)->where('Menu_level2',$menu_level2)->orderBy('Column_Order')->get()->toArray();
        $visible_columns = $hidden_columns = $all_columns = [];
        $table_name = '';
        if(count($columns) > 0){
            foreach ($columns as $column){
                if(in_array($column['Field_Visibility'],[1,2])){
                    if(!is_null($column['Format'])){
                        array_push($all_columns,$column['Format'].' as '.$column['Field_Name'] );
                    }else{
                        array_push($all_columns,$column['Field_Name'] );
                    }


                    array_push($visible_columns,$column);
                    $table_name = $column['Table_Name'];
                }
                else
                    array_push($hidden_columns,$column);
            }
        }

        return [
            'visible_columns' => $visible_columns,
            'hidden_columns'  => $hidden_columns,
            'all_columns'  => $all_columns,
            'table_name'  => $table_name
        ];
    }

    public static function getSingleColumn($menu_level1, $menu_level2,$field_name){
        $column = UAFieldMapping::where('Menu_level1',$menu_level1)
            ->where('Menu_level2',$menu_level2)
            ->where('Field_Name',$field_name)
            ->orderBy('Column_Order')
            ->get()
            ->toArray();

        return $column;
    }

    public static function getFilterValues($evalColumns){
        $Filters = [];
        foreach ($evalColumns as $evalColumn){
            if($evalColumn['Filter'] == 1){

                if(!is_null($evalColumn['Format'])){
                    $Field_Name_as = $evalColumn['Format'].' as '.$evalColumn['Field_Name'];
                    $Field_Name = $evalColumn['Format'];
                }
                else{
                    $Field_Name = $evalColumn['Field_Name'];
                    $Field_Name_as = $Field_Name;
                }

                if($evalColumn['Numeric'] == 1){
                    $records = DB::table($evalColumn['Table_Name'])
                        ->distinct()
                        ->whereNotNull(DB::raw($Field_Name))
                        ->where(DB::raw($Field_Name), '<>', '')
                        ->orderBy(DB::raw($Field_Name))
                        ->pluck(DB::raw($Field_Name_as))->toArray();
                }else{
                    $records = DB::table($evalColumn['Table_Name'])
                        ->distinct()
                        ->whereNotNull($evalColumn['Field_Name'])
                        ->where($evalColumn['Field_Name'], '<>', '')
                        ->orderBy($evalColumn['Field_Name'])
                        ->pluck(DB::raw($Field_Name_as))->toArray();
                }

                $Filters[$evalColumn['Field_Name']] = $records;
                $Filters[$evalColumn['Field_Name']]['Field_Display_Name'] = $evalColumn['Field_Display_Name'];
            }
        }
        return $Filters;
    }

    public static function getFiltersCondition($filters, $section = '',$visible_columns = []){
        $txtSearch = isset($filters['searchterm']) ? $filters['searchterm'][0] : '';
        $sWhere = '';
        if(count($filters) > 0){
            $applied = [];
            foreach ($filters as $keyColumn => $filter){
                if($keyColumn != 'searchterm'){

                    if(stripos($keyColumn,'_op_') != true){
                        if(stripos($keyColumn,'-') != false){
                            $sepColumn = explode('-',$keyColumn);
                            $keyColumn = $sepColumn[1];
                        }
                        $key = array_search($keyColumn, array_column($visible_columns, 'Field_Name'));
                        if(isset($filters[$keyColumn.'_op_']) && !in_array($keyColumn,$applied) && $visible_columns[$key]['Filter'] == 1){
                            $Field_Name = !is_null($visible_columns[$key]['Format']) ? $visible_columns[$key]['Format'] : $visible_columns[$key]['Field_Name'];
                            if(!empty($sWhere)){
                                $sWhere .= ' AND ';
                            }
                            $sWhere .= " (isnull(".$Field_Name.",'') ".$filters[$keyColumn.'_op_'][0]." ".$filter[0].")";
                            array_push($applied,$keyColumn.'_op_');
                            array_push($applied,$keyColumn);

                        }else if(!in_array($keyColumn,$applied) && $visible_columns[$key]['Filter'] == 1){

                            $Field_Name = !is_null($visible_columns[$key]['Format']) ? $visible_columns[$key]['Format'] : $visible_columns[$key]['Field_Name'];
                            if(!empty($sWhere)){
                                $sWhere .= ' AND ';
                            }
                            $sWhere .= " (isnull(".$Field_Name.",'') like '%" . implode("%' OR isnull(".$Field_Name.",'') like '%", $filter) . "%')";
                            array_push($applied,$keyColumn);
                        }

                    }else if (!in_array($keyColumn,$applied) && strpos($keyColumn,'_op_') != false){

                        $column = explode('_op_',$keyColumn);
                        if(isset($filters[$column[0]])){
                            if(stripos($column[0],'-') != false){
                                $sepColumn = explode('-',$column[0]);
                                $column[0] = $sepColumn[1];
                            }
                            $key = array_search($column[0], array_column($visible_columns, 'Field_Name'));
                            if($visible_columns[$key]['Filter'] == 1){
                                $Field_Name = !is_null($visible_columns[$key]['Format']) ? $visible_columns[$key]['Format'] : $visible_columns[$key]['Field_Name'];
                                if(!empty($sWhere)){
                                    $sWhere .= ' AND ';
                                }
                                $sWhere .= " (isnull(".$Field_Name.",'') ".$filters[$keyColumn][0]." " . $filters[$column[0]][0] . ")";
                                array_push($applied,$column[0]);
                                array_push($applied,$keyColumn);
                            }
                        }
                    }
                }
            }
        }

        if ($txtSearch != "") {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            foreach ($visible_columns as $key=> $visible_column){
                if($visible_column['Filter'] == 1) {
                    $sWhere .= $key == 0 ? ' (' : '';
                    if (($key + 1) == count($visible_columns)) {
                        if (!is_null($visible_column['Format'])) {
                            $sWhere .= " isnull(" . $visible_column['Format'] . " ,'') like '%" . $txtSearch . "%' )";
                        } else {
                            $sWhere .= " isnull(" . $visible_column['Field_Name'] . " ,'') like '%" . $txtSearch . "%' )";
                        }

                    } else {
                        if (!is_null($visible_column['Format'])) {
                            $sWhere .= " isnull(" . $visible_column['Format'] . " ,'') like '%" . $txtSearch . "%' OR ";
                        } else {
                            $sWhere .= " isnull(" . $visible_column['Field_Name'] . " ,'') like '%" . $txtSearch . "%' OR ";
                        }
                    }
                }
            }
        }

        return [
            'Where' => !empty($sWhere) ? ' WHERE '.$sWhere : '',
            'filters' => $filters,
            'section' => $section
        ];
    }

    public static function getFiltersSummaryDetail($sumColumns = [],$detailColumns = []){
        $sumFilters = self::getFilterValues($sumColumns);
        $detailFilters = self::getFilterValues($detailColumns);

        return [
            'sumFilters' => $sumFilters,
            'detailFilters' => $detailFilters,
        ];
    }


}
?>
