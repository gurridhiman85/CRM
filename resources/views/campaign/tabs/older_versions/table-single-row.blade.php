<?php
$ccschstatusmaps = isset($record['rpschedule']['ccschstatusmap']) ? $record['rpschedule']['ccschstatusmap'] : [];
foreach ($ccschstatusmaps as $ccschstatusmap){
    if($ccschstatusmap['status'] == 'Completed') continue;
    $start_time = !empty($ccschstatusmap['start_time']) ? $ccschstatusmap['start_time'] : date('Y-m-d h:i');
    $completed_time = !empty($ccschstatusmap['completed_time']) ? $ccschstatusmap['completed_time'] : date('Y-m-d h:i');
    $date1 = new DateTime($start_time);
    $date2 = new DateTime($completed_time);
    $interval = $date1->diff($date2);
    $category = isset($record->rpmeta->Category) ? $record->rpmeta->Category : '';
?>
    <tr>
        <td>{!! $record->t_id !!}</td>
        <td>{!! ucfirst($record->list_level) !!}</td>
        <td>{!! !empty($ccschstatusmap['file_name']) ? $ccschstatusmap['file_name'] : $record->list_short_name !!}</td>
        <td>
            @if(!empty($category))
                @php $category = strip_tags($category); @endphp
                @if (strlen($category) > 50)
                    @php
                        // truncate string
                        $categoryCut = substr($category, 0, 50);
                        $endPoint = strrpos($categoryCut, ' ');

                        //if the string doesn't contain any space then it will cut without word basis.
                        $string = $endPoint? substr($categoryCut, 0, $endPoint) : substr($categoryCut, 0);
                    @endphp
                    <span class="teaser">{!! $string !!}</span>
                    <span class="complete">{!! $category !!}</span>
                    <span class="more font-14" onclick="readmore($(this))">+</span>
                @else
                    {!! $category !!}
                @endif
            @endif
        </td>

        <?php
        $dDatePart = explode(" ", $completed_time);
        $tTimePart = explode(":", $dDatePart[1]);
        ?>
        <td class="text-center">{!! $dDatePart[0] . ' ' . $tTimePart[0] . ':' . $tTimePart[1] !!}</td>
        <?php
        unset($dDatePart);
        unset($tTimePart);

        $cCompleteTime = '';
        if ($interval->h != 0) {
            $cCompleteTime .= $interval->h . ':';
        }
        if ($interval->i != 0) {
            $cCompleteTime .= $interval->h . ':';
        }
        ?>
        <td class="text-center">{!! $cCompleteTime . $interval->s !!}</td>
        <td class="text-center">{!! !empty($ccschstatusmap['ftp_flag']) ? $ccschstatusmap['ftp_flag'] : 'N' !!}</td>
        <td class="text-center">{!! $record->is_public !!}</td>

        <?php
        /*    $sSql = "SELECT count(*) as cnt FROM UL_RepCmp_Share WHERE User_id = '".$uid."' AND camp_tmpl_id = '".$record->row_id."' AND t_type = 'A'";
            $sData = DB::select($sSql);
            $sData = collect($sData)->map(function($x){ return (array) $x; })->toArray();
            */?>
        <td class="text-center"><?php echo isset($record->rpshare) && !empty($record->rpshare->Shared_With_User_id) && $record->rpshare->Shared_With_User_id == Auth::user()->User_ID > 0 ? 'Y' : 'N'; ?></td>
        <td class="text-center">{!! $record->Custom_SQL !!}</td>
        <td class="text-center">{!! !empty($ccschstatusmap['total_records']) ? number_format($ccschstatusmap['total_records']) : 0 !!}</td>
        <td class="text-center">
            <?php

            $ListXLSX = $record->promoexpo_folder . '\\' . $prefix . 'CAL_' . $ccschstatusmap['file_name'] . '.' . $record['promoexpo_ext'];
            ?>
            @if(!empty($ListXLSX) && file_exists(public_path($ListXLSX)))
                <a class="btn no-border font-16 p-0" download href="{!! $ListXLSX !!}" title="Download" id="DownloadBtn">
                    <i class="fas fa-file-excel" style="color: #06b489;"></i>
                </a>
            @endif
        </td>

        <td class="text-center pl-0 pt-1">
            @php
                $ListPDF = $record->promoexpo_folder.'\\'.$prefix.'CAL_'.$ccschstatusmap['file_name'].'.pdf';
            @endphp
            @if(!empty($ListPDF) && file_exists(public_path($ListPDF)))
                <a class="btn no-border font-16 p-0" download href="{!! $ListPDF !!}" title="Download" id="DownloadBtn">
                    <i class="fas fa-file-pdf" style="color: #e92639;"></i>
                </a>
                &nbsp;
                <div class="checkbox">
                    <input id="{!! $ListPDF !!}" type="checkbox" class="po_status" value="{!! $record->row_id !!}"
                           onchange="mPdfChecked($(this),'list');"/>
                    <label for="{!! $ListPDF !!}" style="margin-bottom: 16px;"></label>

                    <div class="space"></div>
                </div>
            @endif
        </td>

        <td class="text-center">
            @php
                $SummaryXLSX = $record->promoexpo_folder.'\\'.$prefix.'CAM_'.$ccschstatusmap['file_name'].'.xlsx';
            @endphp
            @if(!empty($SummaryXLSX) && file_exists(public_path($SummaryXLSX)))
                <a class="btn no-border font-16 p-0" download href="{!! $SummaryXLSX !!}" download title="Download"
                   id="DownloadBtn">
                    <i class="fas fa-file-excel" style="color: #06b489;"></i>
                </a>
            @endif
        </td>

        <td class="text-center pl-0 pt-1">
            @php
                $SummaryPDF = $record->promoexpo_folder.'\\'.$prefix.'CAM_'.$ccschstatusmap['file_name'].'.pdf';
            @endphp
            @if(!empty($SummaryPDF) && file_exists(public_path($SummaryPDF)))
                <a class="btn no-border font-16 p-0" download href="{!! $SummaryPDF !!}" download title="Download"
                   id="DownloadBtn">
                    <i class="fas fa-file-pdf" style="color: #e92639;"></i>
                </a>
                &nbsp;
                <div class="checkbox">
                    <input id="{!! $SummaryPDF !!}" type="checkbox" class="po_status" value="{!! $record->row_id !!}"
                           onchange="mPdfChecked($(this),'rpt');"/>
                    <label for="{!! $SummaryPDF !!}" style="margin-bottom: 16px;"></label>

                    <div class="space"></div>
                </div>
            @endif
        </td>

        <td class="text-center">

            <div class="checkbox">
                <input id="{!! $record->t_id !!}" type="checkbox" class="em_report"
                       value='{!! json_encode(['row_id' => $record->row_id,'t_id' => $record->t_id,'list_level' => $record->list_level,'list_short_name' => $record->list_short_name,'t_name' => $record->t_name,'sql' => base64_encode($record->sql),'selected_fields' => $record->selected_fields,'meta_data' => $category,'Report_Row' => $record->Report_Row,'Report_Column' => $record->Report_Column,'Report_Function' => $record->Report_Function,'Report_Sum' => $record->Report_Sum,'Report_Show' => $record->Report_Show,'Chart_Type' => trim($record->Chart_Type),'Axis_Scale' => $record->Axis_Scale,'Label_Value' => $record->Label_Value]) !!}'/>
                <label for="{!! $record->t_id !!}"></label>

                <div class="space"></div>
            </div>
        </td>

        {{-- <td class="text-center">
             @if(!empty($record->SummaryPDF) && file_exists(public_path($record->SummaryPDF)))
                 <input type="checkbox" class="po_status" value="{!! $record->row_id !!}" onchange="mPdfChecked($(this));"/>
             @endif
         </td>--}}

        <td class="text-center">
            <select onchange='show_Create_library($(this))' class='form-control-sm'
                    style="border-color: #bfe6f6;text-align-last: center;">
                <option value='0'>Select</option>
                <option value='view,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>View
                </option>
                <option value='new,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>Save
                    As
                </option>

                <option value='run,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>Run
                    Report
                </option>
                <option value='replica,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>
                    Run List
                </option>
                <option value='schedule,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>
                    Schedule
                </option>
                <option value='email,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>
                    Email
                </option>
                <option value='share,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>
                    Share
                </option>
                <option value='deleteolderversion,{!! $ccschstatusmap['row_id'] !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>
                    Delete
                </option>

            </select>
        </td>
    </tr>
<?php } ?>
