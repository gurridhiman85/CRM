<div id="completed_tab" class="table-responsive m-t-5" >
    @include('campaign.tabs.completed.table')
</div>
<script>
    if (typeof localStorage !== 'undefined') {
        localStorage.clear();
    }

    parent.camp_id;
    // Add Sub Group
    parent.promoexportchk = 'N';
    parent.previewchk = 'N';
    parent.addsubgroupchk = 'N';
    parent.metadatachk = 'N';
    parent.define_Flag = 1;
    /*** changed 26-06-2017 ****/             //Show the Dialog box once.
    parent.CGOf = new Array();
    parent.CC = new Array();
    parent.CGD = new Array();

    parent.oldcampclk = 'N';
    parent.Camp_Name = "";
    parent.deflag = 0;
    parent.update_flag = 0;
    parent.hide_flag = 0;

    parent.campidArray = new Array();
    parent.seq_num = 0;
    parent.seg_clear_flag = 0;
    parent.proExp_clrear_flag = 0;
    parent.sch_val_flag = 0;

    //For Execute Page
    parent.seg_openFlag = 'N';
    parent.promoExpo_openFlag = 'N';
    parent.schedule_action = 'Sch_campaign1';
    parent.up_flag = 'new';

    $(document).ready(function () {
        setTimeout(function () {
            $('.seg-clr-btn').hide();
            $('#tab_26, #tab_27, #tab_28 , #tab_29').html('');
        },2000)
    })
</script>