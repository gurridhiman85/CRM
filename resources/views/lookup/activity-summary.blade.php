<?php
$row = count($activity_summary_layout) > 0 ? $activity_summary_layout[count($activity_summary_layout)-1]['Row'] : 'R0';
$column = count($activity_summary_layout) > 0 ? $activity_summary_layout[count($activity_summary_layout)-1]['Column'] : 'C0';

$row = (int) str_replace("R","",$row);
$column = (int) str_replace("C","",$column);

function findASSet($find,$set){
    $key = array_search($find, array_column($set, 'Position'));
    return $key !== false ? $set[$key] : false;
}
?>
<table id="detailTable" cellspacing="6" style=" margin: 0pt 0pt;">
    <tbody>
    <?php
    $labelEntries = ['R1','C1'];
    ?>
    @for($i =1; $i <= $row; $i++ )
        <tr>
        @for($j =1; $j <= $column; $j++ )
            @php
            $set = findASSet('R'.$i.'C'.$j,$activity_summary_layout);
            @endphp

            @if($set === false)
                <td class="asAllTD" style="height: 24px;"></td>
            @else
                @if(in_array('R'.$i, $labelEntries) || in_array('C'.$j, $labelEntries))
                    <td class="{{ $set['Class'] }}">{{ $set['Label'] }}</td>
                @else
                    <td class="{{ $set['Class'] }} txt{{ $set['Field_Name'] }}"></td>
                @endif
            @endif

        @endfor
        </tr>
    @endfor

    {{--<tr style="height: 24px;">
        <td class="asFstTd" style="text-align: left !important;width: 13%;">Metric</td>
        <td class="asFstTd">Lifetime</td>
        <td class="asFstTd">Last 5 Years</td>
        <td class="asFstTd">Last 6 Months</td>
        <td class="asFstTd">Current Year</td>
        <td class="asFstTd">Prior Year 1</td>
        <td class="asFstTd">Prior Year 2</td>
        <td class="asFstTd">Prior Year 3</td>
        <td class="asFstTd">Prior Year 4</td>

    </tr>

    <tr>
        <td class="asParentTDLabel asttRow">Total Spend Amount</td>
        <td class="asAllTD asttRow txtlife2date_spendamt"></td>
        <td class="asAllTD asttRow txtlast_5yrs_spendamt"></td>
        <td class="asAllTD asttRow txtlast_6mth_spendamt"></td>
        <td class="asAllTD asttRow txtcurrentyr_spendamt"></td>
        <td class="asAllTD asttRow txtprior_yr1_spendamt"></td>
        <td class="asAllTD asttRow txtprior_yr2_spendamt"></td>
        <td class="asAllTD asttRow txtprior_yr3_spendamt"></td>
        <td class="asAllTD asttRow txtprior_yr4_spendamt"></td>

    </tr>

    <tr>
        <td class="asChildTDLabel">Gift $</td>
        <td class="asAllTD txtlife2date_giftsamt"></td>
        <td class="asAllTD txtlast_5yrs_giftsamt"></td>
        <td class="asAllTD txtlast_6mth_giftsamt"></td>
        <td class="asAllTD txtcurrentyr_giftsamt"></td>
        <td class="asAllTD txtprior_yr1_giftsamt"></td>
        <td class="asAllTD txtprior_yr2_giftsamt"></td>
        <td class="asAllTD txtprior_yr3_giftsamt"></td>
        <td class="asAllTD txtprior_yr4_giftsamt"></td>

    </tr>

    <tr>
        <td class="asChildTDLabel">Membership $</td>

        <td class="asAllTD txtlife2date_membramt"></td>
        <td class="asAllTD txtlast_5yrs_membramt"></td>
        <td class="asAllTD txtlast_6mth_membramt"></td>
        <td class="asAllTD txtcurrentyr_membramt"></td>
        <td class="asAllTD txtprior_yr1_membramt"></td>
        <td class="asAllTD txtprior_yr2_membramt"></td>
        <td class="asAllTD txtprior_yr3_membramt"></td>
        <td class="asAllTD txtprior_yr4_membramt"></td>

    </tr>

    <tr>
        <td class="asChildTDLabel">Event $</td>

        <td class="asAllTD txtlife2date_eventamt"></td>
        <td class="asAllTD txtlast_5yrs_eventamt"></td>
        <td class="asAllTD txtlast_6mth_eventamt"></td>
        <td class="asAllTD txtcurrentyr_eventamt"></td>
        <td class="asAllTD txtprior_yr1_eventamt"></td>
        <td class="asAllTD txtprior_yr2_eventamt"></td>
        <td class="asAllTD txtprior_yr3_eventamt"></td>
        <td class="asAllTD txtprior_yr4_eventamt"></td>
    </tr>

    <tr>
        <td class="asChildTDLabel">Retail $</td>

        <td class="asAllTD txtlife2date_rtailamt"></td>
        <td class="asAllTD txtlast_5yrs_rtailamt"></td>
        <td class="asAllTD txtlast_6mth_rtailamt"></td>
        <td class="asAllTD txtcurrentyr_rtailamt"></td>
        <td class="asAllTD txtprior_yr1_rtailamt"></td>
        <td class="asAllTD txtprior_yr2_rtailamt"></td>
        <td class="asAllTD txtprior_yr3_rtailamt"></td>
        <td class="asAllTD txtprior_yr4_rtailamt"></td>
    </tr>

    <tr>
        <td class="asChildTDLabel">Rental $</td>

        <td class="asAllTD txtlife2date_rentlamt"></td>
        <td class="asAllTD txtlast_5yrs_rentlamt"></td>
        <td class="asAllTD txtlast_6mth_rentlamt"></td>
        <td class="asAllTD txtcurrentyr_rentlamt"></td>
        <td class="asAllTD txtprior_yr1_rentlamt"></td>
        <td class="asAllTD txtprior_yr2_rentlamt"></td>
        <td class="asAllTD txtprior_yr3_rentlamt"></td>
        <td class="asAllTD txtprior_yr4_rentlamt"></td>
    </tr>

    <tr>
        <td class="asChildTDLabel">Miscellaneous $</td>

        <td class="asAllTD txtlife2date_misclamt"></td>
        <td class="asAllTD txtlast_5yrs_misclamt"></td>
        <td class="asAllTD txtlast_6mth_misclamt"></td>
        <td class="asAllTD txtcurrentyr_misclamt"></td>
        <td class="asAllTD txtprior_yr1_misclamt"></td>
        <td class="asAllTD txtprior_yr2_misclamt"></td>
        <td class="asAllTD txtprior_yr3_misclamt"></td>
        <td class="asAllTD txtprior_yr4_misclamt"></td>
    </tr>

    <tr style="height: 24px;">
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
    </tr>

    <tr>
        <td class="asParentTDLabel asttRow">Total Activities</td>

        <td class="asAllTD asttRow txtlife2date_nactivty"></td>
        <td class="asAllTD asttRow txtlast_5yrs_nactivty"></td>
        <td class="asAllTD asttRow txtlast_6mth_nactivty"></td>
        <td class="asAllTD asttRow txtcurrentyr_nactivty"></td>
        <td class="asAllTD asttRow txtprior_yr1_nactivty"></td>
        <td class="asAllTD asttRow txtprior_yr2_nactivty"></td>
        <td class="asAllTD asttRow txtprior_yr3_nactivty"></td>
        <td class="asAllTD asttRow txtprior_yr4_nactivty"></td>
    </tr>

    <tr style="height: 24px;">
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
    </tr>

    <tr>
        <td class="asParentTDLabel asttRow">Total Paid Events Attended</td>

        <td class="asAllTD asttRow txtlife2date_npaidevt"></td>
        <td class="asAllTD asttRow txtlast_5yrs_npaidevt"></td>
        <td class="asAllTD asttRow txtlast_6mth_npaidevt"></td>
        <td class="asAllTD asttRow txtcurrentyr_npaidevt"></td>
        <td class="asAllTD asttRow txtprior_yr1_npaidevt"></td>
        <td class="asAllTD asttRow txtprior_yr2_npaidevt"></td>
        <td class="asAllTD asttRow txtprior_yr3_npaidevt"></td>
        <td class="asAllTD asttRow txtprior_yr4_npaidevt"></td>
    </tr>

    <tr>
        <td class="asChildTDLabel">Kessei</td>

        <td class="asAllTD txtlife2date_nkessei_"></td>
        <td class="asAllTD txtlast_5yrs_nkessei_"></td>
        <td class="asAllTD txtlast_6mth_nkessei_"></td>
        <td class="asAllTD txtcurrentyr_nkessei_"></td>
        <td class="asAllTD txtprior_yr1_nkessei_"></td>
        <td class="asAllTD txtprior_yr2_nkessei_"></td>
        <td class="asAllTD txtprior_yr3_nkessei_"></td>
        <td class="asAllTD txtprior_yr4_nkessei_"></td>
    </tr>

    <tr>
        <td class="asChildTDLabel">ZSS Sesshins</td>

        <td class="asAllTD txtlife2date_nzss_ses"></td>
        <td class="asAllTD txtlast_5yrs_nzss_ses"></td>
        <td class="asAllTD txtlast_6mth_nzss_ses"></td>
        <td class="asAllTD txtcurrentyr_nzss_ses"></td>
        <td class="asAllTD txtprior_yr1_nzss_ses"></td>
        <td class="asAllTD txtprior_yr2_nzss_ses"></td>
        <td class="asAllTD txtprior_yr3_nzss_ses"></td>
        <td class="asAllTD txtprior_yr4_nzss_ses"></td>
    </tr>

    <tr>
        <td class="asChildTDLabel">Open Sesshins</td>

        <td class="asAllTD txtlife2date_nopn_ses"></td>
        <td class="asAllTD txtlast_5yrs_nopn_ses"></td>
        <td class="asAllTD txtlast_6mth_nopn_ses"></td>
        <td class="asAllTD txtcurrentyr_nopn_ses"></td>
        <td class="asAllTD txtprior_yr1_nopn_ses"></td>
        <td class="asAllTD txtprior_yr2_nopn_ses"></td>
        <td class="asAllTD txtprior_yr3_nopn_ses"></td>
        <td class="asAllTD txtprior_yr4_nopn_ses"></td>
    </tr>

    <tr>
        <td class="asChildTDLabel">Intro to Zen</td>

        <td class="asAllTD txtlife2date_nitz_wkd"></td>
        <td class="asAllTD txtlast_5yrs_nitz_wkd"></td>
        <td class="asAllTD txtlast_6mth_nitz_wkd"></td>
        <td class="asAllTD txtcurrentyr_nitz_wkd"></td>
        <td class="asAllTD txtprior_yr1_nitz_wkd"></td>
        <td class="asAllTD txtprior_yr2_nitz_wkd"></td>
        <td class="asAllTD txtprior_yr3_nitz_wkd"></td>
        <td class="asAllTD txtprior_yr4_nitz_wkd"></td>
    </tr>

    <tr>
        <td class="asChildTDLabel">All-Day Sits</td>

        <td class="asAllTD txtlife2date_nall_day"></td>
        <td class="asAllTD txtlast_5yrs_nall_day"></td>
        <td class="asAllTD txtlast_6mth_nall_day"></td>
        <td class="asAllTD txtcurrentyr_nall_day"></td>
        <td class="asAllTD txtprior_yr1_nall_day"></td>
        <td class="asAllTD txtprior_yr2_nall_day"></td>
        <td class="asAllTD txtprior_yr3_nall_day"></td>
        <td class="asAllTD txtprior_yr4_nall_day"></td>
    </tr>

    <tr>
        <td class="asChildTDLabel">Zazenkai</td>

        <td class="asAllTD txtlife2date_nsitting"></td>
        <td class="asAllTD txtlast_5yrs_nsitting"></td>
        <td class="asAllTD txtlast_6mth_nsitting"></td>
        <td class="asAllTD txtcurrentyr_nsitting"></td>
        <td class="asAllTD txtprior_yr1_nsitting"></td>
        <td class="asAllTD txtprior_yr2_nsitting"></td>
        <td class="asAllTD txtprior_yr3_nsitting"></td>
        <td class="asAllTD txtprior_yr4_nsitting"></td>
    </tr>

    <tr>
        <td class="asChildTDLabel">ZSS Programs</td>

        <td class="asAllTD txtlife2date_nzss_pgm"></td>
        <td class="asAllTD txtlast_5yrs_nzss_pgm"></td>
        <td class="asAllTD txtlast_6mth_nzss_pgm"></td>
        <td class="asAllTD txtcurrentyr_nzss_pgm"></td>
        <td class="asAllTD txtprior_yr1_nzss_pgm"></td>
        <td class="asAllTD txtprior_yr2_nzss_pgm"></td>
        <td class="asAllTD txtprior_yr3_nzss_pgm"></td>
        <td class="asAllTD txtprior_yr4_nzss_pgm"></td>
    </tr>

    <tr>
        <td class="asChildTDLabel">Open Programs</td>

        <td class="asAllTD txtlife2date_nopn_pgm"></td>
        <td class="asAllTD txtlast_5yrs_nopn_pgm"></td>
        <td class="asAllTD txtlast_6mth_nopn_pgm"></td>
        <td class="asAllTD txtcurrentyr_nopn_pgm"></td>
        <td class="asAllTD txtprior_yr1_nopn_pgm"></td>
        <td class="asAllTD txtprior_yr2_nopn_pgm"></td>
        <td class="asAllTD txtprior_yr3_nopn_pgm"></td>
        <td class="asAllTD txtprior_yr4_nopn_pgm"></td>
    </tr>

    <tr style="height: 24px;">
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
        <td class="asAllTD"></td>
    </tr>

    <tr>
        <td class="asParentTDLabel asttRow">Total Free Events Attended</td>

        <td class="asAllTD asttRow txtlife2date_nfreeevt"></td>
        <td class="asAllTD asttRow txtlast_5yrs_nfreeevt"></td>
        <td class="asAllTD asttRow txtlast_6mth_nfreeevt"></td>
        <td class="asAllTD asttRow txtcurrentyr_nfreeevt"></td>
        <td class="asAllTD asttRow txtprior_yr1_nfreeevt"></td>
        <td class="asAllTD asttRow txtprior_yr2_nfreeevt"></td>
        <td class="asAllTD asttRow txtprior_yr3_nfreeevt"></td>
        <td class="asAllTD asttRow txtprior_yr4_nfreeevt"></td>
    </tr>

    <tr>
        <td class="asChildTDLabel">Triple Sangha</td>

        <td class="asAllTD txtlife2date_nzm3fold"></td>
        <td class="asAllTD txtlast_5yrs_nzm3fold"></td>
        <td class="asAllTD txtlast_6mth_nzm3fold"></td>
        <td class="asAllTD txtcurrentyr_nzm3fold"></td>
        <td class="asAllTD txtprior_yr1_nzm3fold"></td>
        <td class="asAllTD txtprior_yr2_nzm3fold"></td>
        <td class="asAllTD txtprior_yr3_nzm3fold"></td>
        <td class="asAllTD txtprior_yr4_nzm3fold"></td>
    </tr>

    <tr>
        <td class="asChildTDLabel">Other Zoom Meetings</td>

        <td class="asAllTD txtlife2date_nzoom_ot"></td>
        <td class="asAllTD txtlast_5yrs_nzoom_ot"></td>
        <td class="asAllTD txtlast_6mth_nzoom_ot"></td>
        <td class="asAllTD txtcurrentyr_nzoom_ot"></td>
        <td class="asAllTD txtprior_yr1_nzoom_ot"></td>
        <td class="asAllTD txtprior_yr2_nzoom_ot"></td>
        <td class="asAllTD txtprior_yr3_nzoom_ot"></td>
        <td class="asAllTD txtprior_yr4_nzoom_ot"></td>
    </tr>--}}
    <tr>
        <td><input type="hidden" name="hid"></td>
        <td colspan="11" align="right"></td>
    </tr>
    </tbody>
</table>
