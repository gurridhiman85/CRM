<script type='text/javascript'>
    var expfilename = '', expfoldername = '', expfileformat = '', expfileCC = 'N/A', strExport = 'N/A';
    var upArray = new Array();
    var noRows;
    var noCG, chkCG, SC, SD, CGStart;
    var params = JSON.parse(localStorage.getItem('params'));
    var record = JSON.parse(localStorage.getItem('record'));

    $(document).ready(function () {
        $('#save').attr('onclick','executebefore();');
        if (parent.up_flag == 'view') {
            $('.ft').hide();
        }
        calculate();
    })


    function showrunat(o) {
        var Camp_Name = params.CName

        if (o.value == 'RA') {
            $('#trDate').show();
            $('#trTimeRA').show();
            $('#trTimeSP').hide();
            $('#trTaskP1').hide();
            $('#trTaskP2').hide();
            $('#trTaskP3').hide();
            $('#trScheSP').hide();
            $('#txtSNSP').val('');
            $('#trScheSA').show();
            $('.trEWeek').hide();
            $('.trMonth').hide();
            $('.trdays').hide();
            $('#txtSNSA').val("S_" + Camp_Name.replace(' ', '_'));
            $('#date1').val('<?=date('m/d/Y');?>');
        } else if (o.value == 'RP') {
            $('#trDate').hide();
            $('#trTimeRA').hide();
            $('#trTimeSP').show();
            $('#trTaskP1').show();
            $('#trTaskP2').show();
            $('#trTaskP3').show();
            $('#trScheSA').hide();
            $('#txtSNSA').val('');
            $('#trScheSP').show();
            $('#txtSNSP').val("S_" + Camp_Name.replace(' ', '_'));
        } else {
            $('#trDate').hide();
            $('#trTimeRA').hide();
            $('#trTimeSP').hide();
            $('#trTaskP1').hide();
            $('#trTaskP2').hide();
            $('#trTaskP3').hide();
            $('#trScheSA').hide();
            $('#trScheSP').hide();
            $('.trEDay').hide();
            $('.trEWeek').hide();
            $('.trMonth').hide();
            $('.trdays').hide();
            $('#cmbPeriod').val('daily');
        }
    }

</script>
<script type='text/javascript'>

    function ftpdetail() {

        if ($('#chkFTP').is(':checked')) {
            $('#divftp').show()
        } else {
            $('#divftp').hide()
        }
    }

    function scheopt(o) {
        //alert(o.value);//Hema//
        switch (o.value) {
            case 'daily':
                $('.trEWeek').hide();
                $('#trEDay').hide();
                $('.trMonth').hide();
                $('.trdays').hide();
                break;

            case 'weekly':
                $('.trEWeek').show();
                $('#trEDay').hide();
                $('.trMonth').hide();
                $('.trdays').hide();
                break;

            case 'monthly':
                $('.trEWeek').hide();
                $('#trEDay').hide();
                $('.trMonth').show();
                $('.trdays').show();
                break;
        }
    }

    function SCExpand(arg) {
        var SCStr;
        switch (arg) {
            case 'cmbPU':
                SCStr = "Percent of Universe";
                break;
            case 'cmbNR':
                SCStr = "Number of Records";
                break;

        }
        return (SCStr);
    }

    function SDExpand(arg) {
        var SDStr;
        switch (arg) {
            case 'cmbAEG':
                SDStr = "All Equal Groups";
                break;
            case 'cmbEPG':
                SDStr = "Equal Program Groups";
                break;
            case 'cmbUG':
                SDStr = "Unequal Groups";
                break;
        }
        return (SDStr);
    }

    function Method_ListSeg(arg) {
        //Take List Segment
        var lsm;
        switch (arg) {
            case 'ranNum':
                lsm = "Random Selection By Number";
                break;
            case 'topNum':
                lsm = "Top Records By Number";
                break;
            case 'ranPer':
                lsm = "Random Selection By Percentage";
                break;
            case 'topPer':
                lsm = "Top Records By Percentage";
                break;
            case 'none':
                lsm = "None";
                break;
        }
        //Take List Segment
        return lsm;
    }

    function ucwords (str) {
        return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
            return $1.toUpperCase();
        });
    }

    function calculate() {
        var cId = $.trim(params.CID);
        var cName = $.trim(params.CName);
        var cDesc = $.trim(params.meta_description);
        var LL = ucwords(params.list_level);
        var LCFL = params.selected_fields;

        var view_seg = 'Y', view_promoExpo = 'Y';

        if ((parent.addsubgroupchk == 'Y') || (parent.promoexportchk == 'Y'))//((view_seg == 'Y')||(view_promoExpo == 'Y'))
        {
            var flag = true, index = 1;//var view_flag = 0;
            if (parent.promoexportchk == 'Y') {
                //PromoExport
                //var promoExport = parent.frames['iframePromoExport'].document;
                var PT;
                var strExport = 'N/A';
                PT = params.saveFile;//promoExport.getElementById('cmbsaveexportopt').value;
                if (PT == 'Y') {
                    folderName = params.eFolder; //promoExport.getElementById('foldername').value;
                    fileName = params.eFile; //promoExport.getElementById('filename').value;
                    fileExt = params.eExt; //promoExport.getElementById('cmbexport').value;
                    strExport = folderName + "\\" + fileName + "." + fileExt;
                    expfileCC = params.CGOpt; //promoExport.getElementById('cmbCtrlopt').value;
                }
                document.getElementById('PT').innerText = PT;
                document.getElementById('EF').innerText = strExport;
                document.getElementById('ECEF').innerText = expfileCC;


                //PromoExport
            }
        }
        //Only for Existing Template

        //Only for Existing Template

        setTimeout(function () {
            if ($('#txtTo1').length){
                var MSele = [
                    'txtTo1',
                ];

                $.each(MSele, function (index, value) {
                    $("#" + value).multiselect({
                        appendTo: '#emailExeBox',
                        close: function () {
                        },
                        header: true, //"Region",
                        selectedList: 1, // 0-based index
                        nonSelectedText: 'Select Values',
                        enableFiltering: true,
                        filterBehavior: 'text',
                    }).multiselectfilter({label: 'Search'});

                    $("#" + value + "_ms").attr('style', 'height: 30px !important; background-color: white !important;padding: .25rem .5rem;border-radius: .2rem;background-clip: padding-box;border: 1px solid #e9ecef;font-size: .76563rem;min-height: 30px;');
                    $("#" + value).multiselect('refresh');
                });
            }

            if ($('#txtTo2').length){
                var MSele = [
                    'txtTo2',
                    'txtTo3',
                ];

                $.each(MSele, function (index, value) {
                    $("#" + value).multiselect({
                        appendTo: '#notificationBox',
                        close: function () {
                        },
                        header: true, //"Region",
                        selectedList: 1, // 0-based index
                        nonSelectedText: 'Select Values',
                        enableFiltering: true,
                        filterBehavior: 'text',
                    }).multiselectfilter({label: 'Search'});

                    $("#" + value + "_ms").attr('style', 'height: 30px !important; background-color: white !important;padding: .25rem .5rem;border-radius: .2rem;background-clip: padding-box;border: 1px solid #e9ecef;font-size: .76563rem;min-height: 30px;');
                    $("#" + value).multiselect('refresh');
                });
            }

            if ($('#weeks').length){
                var MSele = [
                    'weeks',
                    'months'
                ];

                $.each(MSele, function (index, value) {
                    $("#" + value).multiselect({
                        //appendTo: '#emailExeBox',
                        close: function () {
                        },
                        header: true, //"Region",
                        selectedList: 1, // 0-based index
                        nonSelectedText: 'Select Values',
                        enableFiltering: true,
                        filterBehavior: 'text',
                    }).multiselectfilter({label: 'Search'});

                    $("#" + value + "_ms").attr('style', 'width:100% !important;height: 30px !important; background-color: white !important;padding: .25rem .5rem;border-radius: .2rem;background-clip: padding-box;border: 1px solid #e9ecef;font-size: .76563rem;min-height: 30px;');
                    $("#" + value).multiselect('refresh');
                });
            }


        },2000);

        if(parent.sch_val_flag == 1){
            if($('#libcId').val() != ""){
                 cId = $('#libcId').val();
            }
            renderSchedule(cId);
        }
    }
    function tConvert (time) {
        // Check correct time format and split into components
        time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

        if (time.length > 1) { // If time format correct
            time = time.slice (1);  // Remove full string match value
            time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
            time[0] = +time[0] % 12 || 12; // Adjust hours
        }
        return time.join (''); // return adjusted time or original string
    }

    function getFormattedDate(date) {
        //var date= '21/01/2015';
        var d = new Date(date.split("-").join("/"));
        var dd = d.getDate();
        var mm = d.getMonth()+1;
        var yy = d.getFullYear();

        return mm+"/"+dd+"/"+yy;
    }


    function renderSchedule(cid) {
        console.log('renderSchedule ---' + cid);
        $.ajax({
            url : '{!! url('/').'/profile/getexecutedata' !!}',
            type: 'POST',
            data: {
                tempid: cid,
                _token: $('[name="_token"]').val()
            },
            success : function (response) {
                if (response !== undefined) {
                    var exeResponse = response.aData;
                    $('#SR_Attachment').val(exeResponse.SR_Attachment);

                    $('#cmbschduletype').val(exeResponse.rpschedule.Schedule_type);
                    $('#cmbschduletype').trigger('change');

                    if(exeResponse.rpschedule.Schedule_type == 'RA'){
                        var runat_date = getFormattedDate(exeResponse.rpschedule.runat_date);
                        $('#date1').val(runat_date);
                        var runat_time = tConvert (exeResponse.rpschedule.runat_time);
                        $('#ra_timeRA').val(runat_time);

                    }
                    else if(exeResponse.rpschedule.Schedule_type == 'RP'){
                        var rp_start_date = getFormattedDate(exeResponse.rpschedule.rp_start_date);
                        $('#date2').val(rp_start_date);

                        var rp_end_date = getFormattedDate(exeResponse.rpschedule.rp_end_date);
                        $('#date3').val(rp_end_date);

                        var rp_run_time = tConvert (exeResponse.rpschedule.rp_run_time);
                        $('#ra_timeSP').val(rp_run_time);

                        $('#cmbPeriod').val(exeResponse.rpschedule.rp_run_sch)
                        $('#cmbPeriod').trigger('change');

                        if(exeResponse.rpschedule.rp_run_sch == 'weekly'){
                            $('#txtMod').val(exeResponse.rpschedule.rp_days);
                            setTimeout(function () {
                                var weeks = exeResponse.rpschedule.rp_months_weeks;
                                var weeksArr = weeks.split(",");
                                $("#months option:selected").removeAttr('selected');
                                var obj = $('#weeks');
                                $.each(weeksArr, function(i,wk){
                                    obj.find('option[value=' + wk + ']').attr('selected', true);
                                });
                                $("#weeks").multiselect('refresh');
                            },3000)

                        }else if(exeResponse.rpschedule.rp_run_sch == 'monthly'){
                            setTimeout(function () {
                                var months = exeResponse.rpschedule.rp_months_weeks;
                                var monthsArr = months.split(",");
                                $("#months option:selected").removeAttr('selected');
                                var obj = $('#months');
                                $.each(monthsArr, function(i,mn){
                                    obj.find('option[value=' + mn + ']').attr('selected', true);
                                });
                                $("#months").multiselect('refresh');
                            },3000)
                        }
                    }

                    if(exeResponse.rpemail != null){
                        $('#chkSREmail').attr('checked',true)
                    }

                    if(exeResponse.rpshare != null){
                        $('#chkShare').attr('checked',true)
                    }

                    if(exeResponse.rpschedule.ftp_tmpl_id != '0'){
                        $('#chkFTP').trigger('click');
                        if(exeResponse.rpschedule.sftp != null){
                            $('#cmbFTPtemp').val(exeResponse.rpschedule.sftp.row_id);
                            $('#cmbFTPtemp').trigger('change');
                        }
                    }
                }
            }
        });
    }

    function showftpval(o) {
        d = o.value;

        //var t1 = document.getElementById("txtFTPsite");
        var t2 = document.getElementById("txtHostAdd");
        var t3 = document.getElementById("txtPort");
        var t4 = document.getElementById("txtUsername");
        var t5 = document.getElementById("txtPass");
        //var t6 = document.getElementById("txtAcc");
        var t7 = document.getElementById("txtFLoc");
        var t8 = document.getElementById("txtSiteType");
        //var t8 = document.getElementById("cmbPType");
        if (d == 'new') {
            $('#txtTemp').show();
            t8.removeAttribute('readonly');
            $('.sftpbox').show();
        } else if (d == '') {
            @if(Auth::user()->User_Type != 'Full_Access')
            $('.sftpbox').hide();
            @endif
        } else {
            $.ajax({
                url : '{!! url('/').'/getftpdata' !!}',
                type : 'GET',
                data : {
                    row_id : d
                },
                success : function (res) {
                    @if(Auth::user()->User_Type == 'Full_Access')
                    $('.sftpbox').show();
                    @else
                    $('.sftpbox').hide();
                    @endif

                    //$("#txtFTPsite").val(res.odata.ftp_site_name);
                    $("#txtFTPName").val(res.odata.ftp_temp_name);
                    $("#txtHostAdd").val(res.odata.ftp_host_address);
                    $("#txtPort").val(res.odata.ftp_port_no == '0' ? '' : res.odata.ftp_port_no);
                    $("#txtUsername").val(res.odata.ftp_user_name);
                    $("#txtPass").val(res.odata.ftp_password);
                    $("#txtFLoc").val(res.odata.folder_loc);
                    $("#txtSiteType").val(res.odata.site_type).attr('readonly',true);
                }
            })

        }

        if ((d == '') || (d == 'new')) {
            //t1.value = '';
            t2.value = '';
            t3.value = '';
            t4.value = '';
            t5.value = '';
            //t6.value = '';
            t7.value = '';
            t8.value = '';
            //t8.value = '';
            if (d == 'new') {
                //t1.removeAttribute('disabled');
                t2.removeAttribute('disabled');
                t3.removeAttribute('disabled');
                t4.removeAttribute('disabled');
                t5.removeAttribute('disabled');
                //  t6.setAttribute('disabled', false);
                t7.removeAttribute('disabled');
                t8.removeAttribute('disabled');
                //  t8.setAttribute('disabled', false);
            }
        } else if ((d == 'temp1') || (d == 'temp2')) {
            //t1.setAttribute('disabled', true);
            t2.setAttribute('disabled', true);
            t3.setAttribute('disabled', true);
            t4.setAttribute('disabled', true);
            t5.setAttribute('disabled', true);
            // t6.setAttribute('disabled', true);
            t7.setAttribute('disabled', true);
            t8.setAttribute('disabled', true);
            //   t8.setAttribute('disabled', true);
        }


    }

    function AddValidator(elem,msg){
        if($('#' + elem).val() == "") {
            $('#' + elem).parent('.' + elem + '_block').addClass('has-error');
            $('.' + elem + '_block').after('<span class="error-block help-block" style="color:#fb9678;">' + msg + '</span>');
            RemoveValidator(elem);
        }
    }

    function RemoveValidator(effClass){
        setTimeout(function(){
            $('.' + effClass + '_block').parent('.' + effClass + '_box').find('span.error-block').remove()
            $('.' + effClass + '_block').removeClass('has-error');
        },5000)
    }

    function executebefore()   // 2013
    {
        if($('#cmbschduletype').val() == 'RA' && $('#ra_timeRA').val() == ""){
            AddValidator('ra_timeRA','Please select the time');
            return false;
        }else if($('#cmbschduletype').val() == 'RP'){
            var rt = 1;

            /********************* For Daily - start***************************/
            if($('#cmbPeriod').val() == 'daily'){
                if($('#date2').val() == ""){ rt = 0; AddValidator('date2','Please select the date');}
                if($('#date3').val() == ""){ rt = 0; AddValidator('date3','Please select the date');}
                if($('#ra_timeSP').val() == ""){ rt = 0; AddValidator('ra_timeSP','Please select the time');}
            }
            if($('#cmbPeriod').val() == 'weekly'){
                if($('#date2').val() == ""){ rt = 0; AddValidator('date2','Please select the date');}
                if($('#date3').val() == ""){ rt = 0; AddValidator('date3','Please select the date');}
                if($('#txtMod').val() == ""){ rt = 0; AddValidator('txtMod','Please select the month');}
                if($('#weeks').val() == ""){ rt = 0; AddValidator('weeks','Please enter week in number');}
                if($('#ra_timeSP').val() == ""){ rt = 0; AddValidator('ra_timeSP','Please select the time');}
            }
            if($('#cmbPeriod').val() == 'monthly'){
                if($('#date2').val() == ""){ rt = 0; AddValidator('date2','Please select the date');}
                if($('#date3').val() == ""){ rt = 0; AddValidator('date3','Please select the date');}
                if($('#ra_timeSP').val() == ""){ rt = 0; AddValidator('ra_timeSP','Please select the time');}
                if($('#months').val() == ""){ rt = 0; AddValidator('months','Please select the month');}
                if($('#cmbdays').val() == ""){ rt = 0; AddValidator('cmbdays','Please select the days');}
            }

            /********************* For Daily - End ***************************/
            if(rt == 0) return false;
        }

        $('#btnExec').attr('disabled',true);
        execute();
    }                                 /// 2013
    function execute() {

        var noCondition;
        var ccolStr = '';
        var opStr = '';
        var valStr = '';
        var logStr = '';
        var LSD;

        var metaStr = "";
        var record = JSON.parse(localStorage.getItem('record'));
        var params = JSON.parse(localStorage.getItem('params'));
        var contactfilters = localStorage.getItem('contactfilters');
        var exclusionsfilters = localStorage.getItem('exclusionsfilters');
        var inclusionsfilters = localStorage.getItem('inclusionsfilters');
        var schedule_action = params.schedule_action;
        var camp_id = params.CID;
        var cname = params.CName;
        metaStr = params.metaStr



        if ((cname == '') && (parent.update_flag == 0)) {
            parent.show_Create();
        }
        //Promo & Export Data
        var eData = '';
        var expff = '';
        var promoExport, saveCD = 'N', folderName = '', fileName = '', fileExt = '', CGOpt = 'N';
        var saveFile;

        var SMTPStr = 'N';

        if ($('#notify').is(':checked')) {

            var txtTo2Str = '';
            var txtTo3Str = '';
            SMTPStr = 'Y';
            if ($('#clkSuccess').is(':checked')){
                var txtTo2 = [];
                $('#txtTo2 :selected').each(function (i, selected) {
                    txtTo2[i] = $(selected).val();
                });

                if(txtTo2.length == 0) $('#notificationBox').modal('show');
                var txtTo2Str = txtTo2.join(',');
                SMTPStr += ':Y';
            } else
                SMTPStr += ':N';

            if ($('#clkFail').is(':checked')){
                var txtTo3 = [];
                $('#txtTo3 :selected').each(function (i, selected) {
                    txtTo3[i] = $(selected).val();
                });

                if(txtTo3.length == 0) $('#notificationBox').modal('show');
                var txtTo3Str = txtTo3.join(',');
                SMTPStr += ':Y';
            } else
                SMTPStr += ':N';
            SMTPStr += ':' +  txtTo2Str;//$('#txtTo2').val();  //get To address
            SMTPStr += ':' + $('#txtCc2').val();  //get CC address
            SMTPStr += ':' + $('#txtBcc2').val();  //get BCC address
            SMTPStr += ':' + $('#txtSub2').val();  //get message
            SMTPStr += ':' + $('#limitedtextarea2').val();  //get message

            SMTPStr += ':' + txtTo3Str;//$('#txtTo3').val();  //get To address
            SMTPStr += ':' + $('#txtCc3').val();  //get CC address
            SMTPStr += ':' + $('#txtBcc3').val();  //get BCC address
            SMTPStr += ':' + $('#txtSub3').val();  //get message
            SMTPStr += ':' + $('#limitedtextarea3').val();  //get message
        }

        var SREmailStr = 'N';

        if ($('#chkSREmail').is(':checked')) {

            var txtTo1Str = '';
            SREmailStr = 'Y';
            var txtTo1 = [];
            $('#txtTo1 :selected').each(function (i, selected) {
                txtTo1[i] = $(selected).val();
            });

            if(txtTo1.length == 0) $('#emailExeBox').modal('show');
            txtTo1Str = txtTo1.join(',');

            SREmailStr += ':' +  txtTo1Str;//$('#txtTo2').val();  //get To address
            SREmailStr += ':' + $('#txtCc1').val();  //get CC address
            SREmailStr += ':' + $('#txtBcc1').val();  //get BCC address
            SREmailStr += ':' + $('#txtSub1').val();  //get message
            SREmailStr += ':' + $('#limitedtextarea1').val();  //get message
            SREmailStr += ':' + $('#Email_Attachment').val();  //get message
        }

        var ShareStr = 'N';
        if ($('#chkShare').is(':checked')) {

            var userStr = '';
            ShareStr = 'Y';
            var userFieldList = [];
            $('#userFieldList :selected').each(function (i, selected) {
                userFieldList[i] = $(selected).val();
            });

            if(userFieldList.length == 0) $('#shareExeBox').modal('show');
            userStr = userFieldList.join(',');
            ShareStr += ':' +  userStr;  //get To address
            var cmsg = $('#chkShareMsg').is(':checked') ? $('#limitedtextarea4').val() : '';
            ShareStr += ':' + cmsg;  //get message
        }

        var ftp_flag;
        var SFTP_Attachment = '';
        var SR_Attachment = '';
        if (!$('#chkFTP').is(':checked'))
            ftp_flag = 'N';
        else if ($('#chkFTP').is(':checked') && $('#cmbFTPtemp').val() == 'new') {

            var ftpData = '';
            ftp_flag = 'Y';
            var ftpName = $('#txtFTPName').val();
            var host = $("#txtHostAdd").val();
            var port = $("#txtPort").val();
            var user = $("#txtUsername").val();
            var pass = $("#txtPass").val();
            var loc = $("#txtFLoc").val();
            var siteType = $("#txtSiteType").val();
            SFTP_Attachment = $("#SFTP_Attachment").val();
            ftpData = ftpName + ":" + ftpName + ":" + host + ":" + port + ":" + user + ":" + pass + ":" + loc + ":" + siteType;
        } else {
            var ftpData = $('#cmbFTPtemp').val();
            SFTP_Attachment = $("#SFTP_Attachment").val();
            ftp_flag = 'Y';
        }
        SR_Attachment = $("#SR_Attachment").val();

        if ($('#cmbschduletype').val() == 'RI') {
            //YAHOO.csr.container.wait.setHeader("Scheduling report, please wait...");

           /* $.ajax({
                url : ,
                type : 'POST',
                data : {
                    pgaction : schedule_action,
                    CID : camp_id,
                    CName : cname,
                    metaStr : metaStr,
                    SMTPStr : SMTPStr,
                    ftp_flag : ftp_flag,
                    ftpData : ftpData,
                    SFTP_Attachment : SFTP_Attachment,
                    SR_Attachment : SR_Attachment,
                    SREmailStr : SREmailStr,
                    ShareStr : ShareStr,
                    rtype : 'RI',
                    params : JSON.stringify(params),
                    filterVal : contactfilters,
                    customerExclusionVal : exclusionsfilters,
                    customerInclusionVal : inclusionsfilters,
                    _token :
                },
                success : function (data) {
                    //parent.$('#schedulePopup').modal('hide');
                    setTimeout(function () {
                        window.parent.$('.clr-btn').trigger('click');
                        //$('#tab_26, #tab_27, #tab_28 , #tab_29').html('');
                    },1500)
                    localStorage.removeItem('record');
                    localStorage.removeItem('params');
                    //localStorage.removeItem('contactfilters');
                    //localStorage.removeItem('exclusionsfilters');
                    //localStorage.removeItem('inclusionsfilters');
                    setTimeout(function () {
                        runprofileafter(2,'save');
                        setTimeout(function () {
                            updateprofile({
                                t_id : data.t_id,
                                pdfdata : localStorage.getItem('pdfdata'),
                                profileXLSXdata : localStorage.getItem('profileXLSXdata'),
                                _token : $('[name="_token"]').val()
                            });
                        },2000)

                    },5000)
                },
                complete : function () {
                    //parent.$('#schedulePopup').modal('hide');
                    setTimeout(function () {
                        window.parent.$('.clr-btn').trigger('click');
                        //$('#tab_26, #tab_27, #tab_28 , #tab_29').html('');
                    },1500)
                }
            })*/
            finalsave({
                pgaction : schedule_action,
                CID : camp_id,
                CName : cname,
                metaStr : metaStr,
                SMTPStr : SMTPStr,
                ftp_flag : ftp_flag,
                ftpData : ftpData,
                SFTP_Attachment : SFTP_Attachment,
                SR_Attachment : SR_Attachment,
                SREmailStr : SREmailStr,
                ShareStr : ShareStr,
                rtype : 'RI',
                params : JSON.stringify(params),
                filterVal : contactfilters,
                customerExclusionVal : exclusionsfilters,
                customerInclusionVal : inclusionsfilters,
                _token : '{!! csrf_token() !!}'
            });
        } // Schdule check if
        else {
            if ($('#cmbschduletype').val() == 'RA') {

                if (($('#date1').val() != '') && ($('#txtSN').val() != '')) {
                    var rtype = "RA";
                    var sName = "S_" + cname.replace(' ', '_');
                    var RA_Dt = $('#date1').val();

                    var RA_time = $('#ra_timeRA').val().replace('AM', '').replace('PM', '').split(':');
                    if(($('#ra_timeRA').val()).indexOf('PM') != -1){
                        RA_time[0] = parseInt(RA_time[0]) == 12 ? parseInt(RA_time[0]) : parseInt(RA_time[0]) + 12;
                    }else if(($('#ra_timeSP').val()).indexOf('AM') != -1){
                        RA_time[0] = parseInt(RA_time[0]) == 12 ? '00' : parseInt(RA_time[0]);
                    }
                    RA_time = RA_time.join(':');

                    //YAHOO.csr.container.wait.setHeader("Scheduling List, please wait...");

                    /*$.ajax({
                        url : ,
                        type : 'POST',
                        data : {
                            pgaction : schedule_action,
                            CID : $.trim(camp_id),
                            CName : cname,
                            metaStr : metaStr,
                            SMTPStr : SMTPStr,
                            ftp_flag : ftp_flag,
                            ftpData : ftpData,
                            SFTP_Attachment : SFTP_Attachment,
                            SR_Attachment : SR_Attachment,
                            SREmailStr : SREmailStr,
                            rtype : rtype,
                            RA_Dt : RA_Dt,
                            RA_time : RA_time,
                            sName : sName,
                            params : JSON.stringify(params),
                            filterVal : contactfilters,
                            customerExclusionVal : exclusionsfilters,
                            customerInclusionVal : inclusionsfilters,
                            _token :
                        },
                        success : function (data) {
                            //window.parent.$('#schedulePopup').modal('hide');
                            setTimeout(function () {
                                window.parent.$('.clr-btn').trigger('click');
                                //$('#tab_26, #tab_27, #tab_28 , #tab_29').html('');
                            },1500)

                            localStorage.removeItem('record');
                            localStorage.removeItem('params');
                            //localStorage.removeItem('contactfilters');
                            //localStorage.removeItem('exclusionsfilters');
                            //localStorage.removeItem('inclusionsfilters');
                            setTimeout(function () {
                                runprofileafter(2,'save');
                                setTimeout(function () {
                                    updateprofile({
                                        t_id : data.t_id,
                                        pdfdata : localStorage.getItem('pdfdata'),
                                        profileXLSXdata : localStorage.getItem('profileXLSXdata'),
                                        _token : $('[name="_token"]').val()
                                    });
                                },2000)

                            },5000)
                        },
                        complete : function () {
                            //window.parent.$('#schedulePopup').modal('hide');
                            setTimeout(function () {
                                window.parent.$('.clr-btn').trigger('click');
                                //$('#tab_26, #tab_27, #tab_28 , #tab_29').html('');
                            },1500)
                        }
                    })*/
                    finalsave({
                        pgaction : schedule_action,
                        CID : $.trim(camp_id),
                        CName : cname,
                        metaStr : metaStr,
                        SMTPStr : SMTPStr,
                        ftp_flag : ftp_flag,
                        ftpData : ftpData,
                        SFTP_Attachment : SFTP_Attachment,
                        SR_Attachment : SR_Attachment,
                        SREmailStr : SREmailStr,
                        rtype : rtype,
                        RA_Dt : RA_Dt,
                        RA_time : RA_time,
                        sName : sName,
                        params : JSON.stringify(params),
                        filterVal : contactfilters,
                        customerExclusionVal : exclusionsfilters,
                        customerInclusionVal : inclusionsfilters,
                        _token : '{!! csrf_token() !!}'
                    });
                } else {
                    alert("Please fill in all required fields");
                }
            }
            else   //RP - Run Periodically
            {
                if (($('#date2').val() != '') && ($('#date3').val() != '')) {
                    var rtype = "RP";
                    var sName = "S_" + cname.replace(' ', '_');
                    var RP_Start_Dt = $('#date2').val();
                    var RP_end_Dt = $('#date3').val();

                    var RA_time = $('#ra_timeSP').val().replace('AM', '').replace('PM', '').split(':');
                    if(($('#ra_timeSP').val()).indexOf('PM') != -1){
                        RA_time[0] = parseInt(RA_time[0]) == 12 ? parseInt(RA_time[0]) : parseInt(RA_time[0]) + 12;
                    }else if(($('#ra_timeSP').val()).indexOf('AM') != -1){
                        RA_time[0] = parseInt(RA_time[0]) == 12 ? '00' : parseInt(RA_time[0]);
                    }
                    RA_time = RA_time.join(':');
                    RA_time = RA_time + ':00';

                    var rp_run_sch = $('#cmbPeriod').val();
                    var monthStr = '';
                    switch (rp_run_sch) {

                        case 'weekly':
                            var txtWeekDays = [];
                            $('#weeks :selected').each(function (i, selected) {
                                txtWeekDays[i] = $(selected).val();
                            });

                            var dayStr = txtWeekDays.join(',');
                            var mo = $('#txtMod').val();
                            break;
                        case 'monthly':
                            var dayStr = $('#cmbdays').val();
                            var txtMonths = [];
                            $('#months :selected').each(function (i, selected) {
                                txtMonths[i] = $(selected).val();
                            });
                            monthStr = txtMonths.join(',');
                            break;

                    }
                    //YAHOO.csr.container.wait.setHeader("Scheduling List, please wait...");

                    /*$.ajax({
                        url : '',
                        type : 'POST',
                        data : {
                            pgaction : schedule_action,
                            CID : $.trim(camp_id),
                            CName : cname,
                            metaStr : metaStr,
                            SMTPStr : SMTPStr,
                            ftp_flag : ftp_flag,
                            ftpData : ftpData,
                            SFTP_Attachment : SFTP_Attachment,
                            SR_Attachment : SR_Attachment,
                            SREmailStr : SREmailStr,
                            rtype : rtype,
                            RP_Start_Dt : RP_Start_Dt,
                            RP_end_Dt : RP_end_Dt,
                            rp_run_sch : rp_run_sch,
                            RA_time : RA_time,
                            dayStr : dayStr,
                            mo : mo,
                            monthStr : monthStr,
                            sName : sName,
                            params : JSON.stringify(params),
                            filterVal : contactfilters,
                            customerExclusionVal : exclusionsfilters,
                            customerInclusionVal : inclusionsfilters,
                            _token :
                        },
                        success : function (data) {
                            ///window.parent.$('#schedulePopup').modal('hide');
                            setTimeout(function () {
                                window.parent.$('.clr-btn').trigger('click');
                                //$('#tab_26, #tab_27, #tab_28 , #tab_29').html('');
                            },1500)

                            localStorage.removeItem('record');
                            localStorage.removeItem('params');
                            //localStorage.removeItem('contactfilters');
                            //localStorage.removeItem('exclusionsfilters');
                            //localStorage.removeItem('inclusionsfilters');

                            setTimeout(function () {
                                runprofileafter(2,'save');
                                setTimeout(function () {
                                    updateprofile({
                                        t_id : data.t_id,
                                        pdfdata : localStorage.getItem('pdfdata'),
                                        profileXLSXdata : localStorage.getItem('profileXLSXdata'),
                                        _token : $('[name="_token"]').val()
                                    });
                                },2000)

                            },5000)
                        },
                        complete : function () {
                            //window.parent.$('#schedulePopup').modal('hide');
                            setTimeout(function () {
                                window.parent.$('.clr-btn').trigger('click');
                                //$('#tab_26, #tab_27, #tab_28 , #tab_29').html('');
                            },1500)
                        }
                    })*/
                    finalsave({
                        pgaction : schedule_action,
                        CID : $.trim(camp_id),
                        CName : cname,
                        metaStr : metaStr,
                        SMTPStr : SMTPStr,
                        ftp_flag : ftp_flag,
                        ftpData : ftpData,
                        SFTP_Attachment : SFTP_Attachment,
                        SR_Attachment : SR_Attachment,
                        SREmailStr : SREmailStr,
                        rtype : rtype,
                        RP_Start_Dt : RP_Start_Dt,
                        RP_end_Dt : RP_end_Dt,
                        rp_run_sch : rp_run_sch,
                        RA_time : RA_time,
                        dayStr : dayStr,
                        mo : mo,
                        monthStr : monthStr,
                        sName : sName,
                        params : JSON.stringify(params),
                        filterVal : contactfilters,
                        customerExclusionVal : exclusionsfilters,
                        customerInclusionVal : inclusionsfilters,
                        _token : '{!! csrf_token() !!}'
                    });
                } else {
                    ACFn.display_message("Please fill in all required fields",'','success',3000);
                }
            }
        }

    } // Execute Function

    function finalsave(dataarr = {}){
        $.ajax({
            url : '{!! url('/').'/profile/ar_sch_data' !!}',
            type : 'POST',
            data : dataarr,
            success : function (data) {
                ///window.parent.$('#schedulePopup').modal('hide');
                setTimeout(function () {
                    window.parent.$('.clr-btn').trigger('click');
                    //$('#tab_26, #tab_27, #tab_28 , #tab_29').html('');
                },1500)

                //localStorage.removeItem('record');
                //localStorage.removeItem('params');
                //localStorage.removeItem('contactfilters');
                //localStorage.removeItem('exclusionsfilters');
                //localStorage.removeItem('inclusionsfilters');

                setTimeout(function () {
                    runprofileafter(2,'save');
                    setTimeout(function () {
                        updateprofile({
                            t_id : data.t_id,
                            pdfdata : localStorage.getItem('pdfdata'),
                            profileXLSXdata : localStorage.getItem('profileXLSXdata'),
                            _token : $('[name="_token"]').val()
                        });
                    },2000)

                },5000)
            },
            complete : function () {
                //window.parent.$('#schedulePopup').modal('hide');
                setTimeout(function () {
                    window.parent.$('.clr-btn').trigger('click');
                    //$('#tab_26, #tab_27, #tab_28 , #tab_29').html('');
                },1500)
            }
        })
    }
    function limitText(fobj, limitCount, limitNum) {
        if (fobj.val().length > limitNum) {
            fobj.val(fobj.val().substring(0, limitNum));
        } else {
            limitCount.val(parseInt(limitNum) - parseInt(fobj.val().length));
        }
    }

    function showShare(obj) {
        if(obj.is(':checked')){
            $('#shareExeBox').modal('show');

            $("#userFieldList").multiselect({
                header: true, //"Region",
                selectedList: 1, // 0-based index
                nonSelectedText: 'Select Users'
            }).multiselectfilter({label: 'Search'});

            $("#userFieldList").multiselect('uncheckAll');
            $("#userFieldList").multiselect('refresh');
            $('#userFieldList_ms').css('width','100%');
        }else{
            $('#shareExeBox').modal('hide');
        }
    }

    function showSREmail(obj) {
        if(obj.is(':checked')){
            $('#emailExeBox').modal('show');
            setTimeout(function () {
                $('#txtTo1_ms').css({'width': '100%'})
            },1500)

            var list_desc = params.Category;
            $('#txtSub1').val($('#Email_Attachment').val() == 'none' ? '{{ config("constant.client_name") }}' + ' - ' + list_desc : '{{ config("constant.client_name") }}' + ' - ' + list_desc);
        }else{
            $('#emailExeBox').modal('hide');
        }
    }

    function changeEmailSub(obj) {
        var list_desc;
        var record = JSON.parse(localStorage.getItem('record'));
        $.each(record, function(i, item) {
            //list_short_name = item.list_short_name;
            var metaStr = item.metaStr.split('^');
            list_desc = metaStr[3];
            $('#txtSub1').val(obj.val() == 'none' ? '{{ config("constant.client_name") }}' + ' - ' + list_desc : '{{ config("constant.client_name") }}' + ' - ' + list_desc);
        });
    }
</script>
<style>
    .datepicker {
        z-index: 993 !important;
    }


    button.ds-c3 {
        color: #5f93b2;
        background-color: #bfe6f6;
        border-color: #dae0e5
    }
    button.ds-c3:hover {
        background-color: #3ea6d0;
        color: #fff;
    }

    button.ds-c4:hover {
        color: #5f93b2;
        background-color: #bfe6f6;
        border-color: #dae0e5
    }
    button.ds-c4 {
        background-color: #3ea6d0;
        color: #fff;
    }


    .ds-l{
        color: #9e9e9e;
    }
</style>
<div class="form-body">
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-md-5 pr-0">
                <div class="form-group row">
                    <label class="control-label text-left col-md-2 pt-1">Schedule</label>
                    <div class="col-md-4">
                        <select class="form-control form-control-sm" id="cmbschduletype" name="cmbschduletype" onChange="showrunat(this)">
                            <option value="RI">Run now</option>
                            <option value="RA">Run at</option>
                            <!--<option value="RP">Run periodically</option>-->
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-7"></div>
        </div>

        <div class="row">
            <div class="col-md-5 pr-0">
                <div class="form-group row" id="trDate" style="display: none">
                    <label class="control-label text-left col-md-2 pt-1">Date</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="date1" readonly="" id="date1" class="form-control form-control-sm js-datepicker input" data-date-format="mm/dd/yyyy" autocomplete="off">
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="$('[name=date1]').trigger('focus');"><i class="ti-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <label class="control-label text-right col-md-1 pr-2 pl-0 pt-1">Time</label>
                    <div class="col-md-4 ra_timeRA_box">
                        <div class="input-group ra_timeRA_block">
                            <input type="text" id="ra_timeRA" name="ra_timeRA" class="form-control form-control-sm input js-clockpicker" onclick="$('.am-button').trigger('click');" value="09:00" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="$('[name=ra_timeRA]').trigger('focus');"><i class="ti-alarm-clock"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7"></div>
        </div>

        <div class="row">
            <div class="col-md-5 pr-0">
                <div class="form-group row" id="trTaskP1" style="display:none;">
                    <label class="control-label text-left col-md-2 pt-1">Run From</label>
                    <div class="col-md-4 date2_box">
                        <div class="input-group date2_block">
                            <input type='text' name='date2' readonly id='date2' class="form-control form-control-sm js-datepicker startDateId" data-date-format="mm/dd/yyyy">
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="$('[name=date2]').trigger('focus');"><i class="ti-calendar"></i></span>
                            </div>
                        </div>
                    </div>

                    <label class="control-label text-right col-md-1 p-0 pt-1">End Date</label>
                    <div class="col-md-4 date3_box">
                        <div class="input-group date3_block">
                            <input type ='text' name='date3' class="form-control form-control-sm js-datepicker endDateId" readonly id='date3' data-date-format="mm/dd/yyyy">
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="$('[name=date3]').trigger('focus');"><i class="ti-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7"></div>
        </div>

        <div class="row">
            <div class="col-md-5 pr-0">
                <div class="form-group row" id="trTaskP3" style="display:none;">
                    <label class="control-label text-left col-md-2 pt-1">Frequency</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <select onchange="scheopt(this)" class="form-control form-control-sm" id="cmbPeriod">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                    </div>

                    <label class="control-label text-left col-md-1 pt-1">Time</label>
                    <div class="col-md-4 ra_timeSP_box">
                        <div class="input-group ra_timeSP_block">
                            <input type="text" id="ra_timeSP" name="ra_timeSP" class="form-control form-control-sm input js-clockpicker" onclick="$('.am-button').trigger('click');" value="09:00" readonly/>
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="$('[name=ra_timeSP]').trigger('focus');"><i class="ti-alarm-clock"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 pl-0 trEWeek" style="display:none;">
                <div class="form-group row">
                    <label class="control-label text-right col-md-2 pl-2 pt-1">Recur every</label>
                    <div class="col-md-4 txtMod_box">
                        <div class="input-group txtMod_block">
                            <input type='text' value='1' class="form-control form-control-sm" id="txtMod">
                            <div class="input-group-append">
                                <span class="input-group-text" style="font-size: 9px !important;">Weeks</span>
                            </div>
                        </div>
                    </div>

                    <label class="control-label text-right col-md-1 pl-2 pt-1">On</label>
                    <div class="col-md-4 weeks_box">
                        <div class="input-group weeks_block">
                            <select class="form-control form-control-sm" id="weeks" multiple="multiple">
                                <option value="SUN">Sunday</option>
                                <option selected value="MON">Monday</option>
                                <option value="TUE">Tuesday</option>
                                <option value="WED">Wednesday</option>
                                <option value="THU">Thursday</option>
                                <option value="FRI">Friday</option>
                                <option value="SAT">Saturday</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 pl-0 trMonth" style="display:none;">
                <div class="form-group row">
                    <label class="control-label text-right col-md-1 pl-2 pt-1">Month</label>
                    <div class="col-md-4 months_box">
                        <div class="input-group months_block">
                            <select class="form-control form-control-sm" id="months" multiple="multiple">
                                <option selected value="JAN">January</option>
                                <option selected value="FEB">February</option>
                                <option selected value="MAR">March</option>
                                <option selected value="APR">April</option>
                                <option selected value="MAY">May</option>
                                <option selected value="JUN">June</option>
                                <option selected value="JUL">July</option>
                                <option selected value="AUG">August</option>
                                <option selected value="SEP">September</option>
                                <option selected value="OCT">October</option>
                                <option selected value="NOV">November</option>
                                <option selected value="DEC">December</option>
                            </select>
                        </div>
                    </div>

                    <label class="control-label text-right col-md-1 pl-2 pt-1">Day</label>
                    <div class="col-md-4 cmbdays_box">
                        <div class="input-group cmbdays_block">
                            <select class="form-control form-control-sm" id="cmbdays">
                                @for ($i = 1; $i < 31; $i++)
                                    <option @if(date('d') == $i) selected @endif value="{{ $i }}">{{ $i }}</option>
                                @endfor
                                <option value='last'>Last Day</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5 pr-0">
                <div class="form-group row">
                    <label class="control-label text-left col-md-2 pt-1">Output Format</label>
                    <div class="col-md-4">
                        <select id="SR_Attachment" class="form-control form-control-sm">
                            <option value="pdf">PDF</option>
                            <option value="xlsx">XLSX</option>
                            <option value="both">Both</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-7"></div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="form-group row">
                    <label class="control-label text-left col-md-2 pt-1">Send via Email</label>
                    <div class="col-md-1">
                        <label class="custom-control custom-checkbox m-b-0">
                            <input type="checkbox" class="custom-control-input checkbox" value="1" id="chkSREmail" name="chkSREmail" onclick="showSREmail($(this));">
                            <span class="custom-control-label"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-7"></div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="form-group row">
                    <label class="control-label text-left col-md-2 pt-1">Share</label>
                    <div class="col-md-1">
                        <label class="custom-control custom-checkbox m-b-0">
                            <input type="checkbox" class="custom-control-input checkbox" value="1" id="chkShare" name="chkShare" onclick="showShare($(this));">
                            <span class="custom-control-label"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-7"></div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="form-group row">
                    <label class="control-label text-left col-md-2 pt-1">Publish to SFTP</label>
                    <div class="col-md-1">
                        <label class="custom-control custom-checkbox m-b-0">
                            <input type="checkbox" class="custom-control-input checkbox" value="1" id="chkFTP" name="chkFTP" onClick="ftpdetail()">
                            <span class="custom-control-label"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-7"></div>
        </div>

        <div class="row" id="divftp" style="display:none">
            <div class="col-md-1"></div>

            <div class="col-md-2 pl-0">
                <div class="form-group row">
                    <label class="control-label">Site Name</label>
                    <select id="cmbFTPtemp" onchange="showftpval(this)"
                            class="form-control form-control-sm">
                        {!! \App\Helpers\Helper::get_ftpcombo() !!}
                    </select>
                    <input id="txtSiteType" type="hidden" value="SFTP">
                    <input id="txtFTPName" type="hidden">
                </div>
            </div>

            <div class="col-md-2 sftpbox" @if(Auth::user()->User_Type != 'Full_Access') style="display: none;" @endif>
                <div class="form-group">
                    <label class="control-label">Host Address</label>
                    <input type='text' id="txtHostAdd"
                           class="form-control form-control-sm">
                </div>
            </div>

            <div class="col-md-2 sftpbox" @if(Auth::user()->User_Type != 'Full_Access') style="display: none;" @endif>
                <div class="form-group">
                    <label class="control-label">User Name</label>
                    <input type='text' id="txtUsername"
                           class="form-control form-control-sm" autocomplete="nofill">
                </div>
            </div>

            <div class="col-md-2 sftpbox" @if(Auth::user()->User_Type != 'Full_Access') style="display: none;" @endif>
                <div class="form-group">
                    <label class="control-label">Password</label>
                    <input type='password' id="txtPass"
                           class="form-control form-control-sm" autocomplete = "new-password">
                </div>
            </div>

            <div class="col-md-2 sftpbox" @if(Auth::user()->User_Type != 'Full_Access') style="display: none;" @endif>
                <div class="form-group">
                    <label class="control-label">Folder Location</label>
                    <input type='text' id="txtFLoc"
                           class="form-control form-control-sm">
                </div>
            </div>

            <div class="col-md-1">
                <div class="form-group">
                    <label class="control-label">Attachment</label>
                    <select id="SFTP_Attachment" class="form-control form-control-sm">
                        <option value="pdf">PDF</option>
                        <option value="xlsx">XLSX</option>
                        <option value="both">Both</option>
                        <option value="none">None</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row" style="display: none;">
            <div class="col-md-5">

                <div class="form-group row">
                    <label class="control-label text-left col-md-2">Notifications</label>
                    <div class="col-md-1">
                        <label class="custom-control custom-checkbox m-b-0">
                            <input type="checkbox" class="custom-control-input checkbox" value="1" id='notify' onclick="if($(this).is(':checked')){ $('#notificationBox').modal('show'); setTimeout(function() { $('#txtTo2_ms').css({'width': '100%'}); $('#txtTo3_ms').css({'width': '100%'}) },1000) }else{ $('#notificationBox').modal('hide'); }">
                            <span class="custom-control-label"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-7"></div>
        </div>

        <div class="row pull-right">
            <button type="button" class="btn btn-info ft" id='savebottom' onClick='executebefore();'>Finish</button>
        </div>
    </div>
</div>


<div class="modal bs-example-modal-lg" id="emailExeBox" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title pl-1" id="myModalLabel">Send Report via Email</h6>
                <button type="button" onclick="$('#emailExeBox').modal('hide');" class="close pr-3" data-dismiss="modal" aria-label="Close" style="background: transparent;"><i class="fas fa-times-circle" style="color: #d9d7d7;"></i></button>
            </div>
            <div class="modal-body p-1">
                <div class="card">
                    <div class="card-body pb-0">
                        <div class="form-body">
                            <div class="card-body">
                                <?php $users = \App\Helpers\Helper::getUsers(Auth::user()->User_ID); ?>
                                <div class="row">
                                    <div class="col-md-12 p-0">
                                        <div class="form-group">
                                            <label class="control-label">To:</label>
                                            <select id="txtTo1" name="txtTo[]" class="form-control form-control-sm txtTo1" multiple="multiple">
                                                <?php
                                                foreach($users as $user){
                                                    echo '<option value='.$user['User_Email'].'>'.$user['User_FName'].' '.$user['User_LName'].'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 p-0">
                                        <div class="form-group">
                                            <label class="control-label">Cc:</label>
                                            <input type="text" name="txtCc" class="form-control form-control-sm" id="txtCc1" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 p-0">
                                        <div class="form-group">
                                            <label class="control-label">Bcc:</label>
                                            <input type="text" name="txtBcc" class="form-control form-control-sm"  id="txtBcc1" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 p-0">
                                        <div class="form-group">
                                            <label class="control-label">Subject:</label>
                                            <input type="text" name="txtSub" class="form-control form-control-sm" id="txtSub1" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 p-0">
                                        <div class="form-group">
                                            <label class="control-label">Body:</label>
                                            <textarea id="limitedtextarea1" class="form-control form-control-sm" name="limitedtextarea1" onkeydown="limitText($(this),$('#countdown1'),250);" onkeyup="limitText($(this),$('#countdown1'),250);" cols="33" rows="5"></textarea><font size="1"><br>(Maximum characters: 250).
                                                You have <input readonly="" type="text" id="countdown1" name="countdown" size="3" value="250"> characters left.</font>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 p-0">
                                        <div class="form-group">
                                            <label class="control-label">Attachment</label>
                                            <select id="Email_Attachment" onchange="changeEmailSub($(this))" class="form-control form-control-sm">
                                                <option value="onlylist">List Only</option>
                                                <option value="onlyreport">Report Only</option>
                                                <option value="both">Both</option>
                                                <option value="none">None</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer mt-0">
                <div class="row pull-right">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="display: block !important;">
                        <div class="input-group pull-right">
                            <button type="button" onclick="$('#emailExeBox').modal('hide');" class="btn btn-info font-12 s-f" title="Send Report" >Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal bs-example-modal-lg" id="shareExeBox" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-md modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title pl-2" id="myModalLabel">Share Report</h6>
                <button type="button" class="close pr-3" data-dismiss="modal" aria-label="Close" style="background: transparent;"><i class="fas fa-times-circle" style="color: #d9d7d7;"></i></button>
            </div>
            <div class="modal-body p-2">
                <div class="card m-0">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 p-0">
                                        <div class="form-group row">
                                            <label class="control-label col-md-4">Users:</label>
                                            <div class="col-md-8">
                                                <select  name="users[]" id="userFieldList" class="form-control form-control-sm" multiple="multiple">
                                                    <?php
                                                    //$users = \App\Helpers\Helper::getUsers(Auth::user()->User_ID);
                                                    foreach($users as $user){
                                                        echo '<option value='.$user['User_ID'].'>'.$user['User_FName'].' '.$user['User_LName'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 p-0">
                                        <div class="form-group row">
                                            <label class="control-label col-md-4">Add Custom Message:</label>
                                            <div class="col-md-8">
                                                <label class="custom-control custom-checkbox m-b-0" style="width: fit-content;">
                                                    <input type="checkbox" class="custom-control-input checkbox" value="1" id="chkShareMsg" name="chkShareMsg" onclick="$(this).is(':checked') ? $('.s-cmessage').show() : $('.s-cmessage').hide()">
                                                    <span class="custom-control-label"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row s-cmessage" style="display: none;">
                                    <div class="col-md-12 p-0">
                                        <div class="form-group row">
                                            <label class="control-label col-md-4">Comments:</label>
                                            <div class="col-md-8">
                                                <textarea id="limitedtextarea4" class="form-control form-control-sm" name="limitedtextarea4" onkeydown="limitText($(this),$('#countdown4'),250);" onkeyup="limitText($(this),$('#countdown4'),250);" cols="33" rows="5"></textarea><font size="1"><br>(Maximum characters: 250).
                                                    You have <input readonly="" type="text" id="countdown4" name="countdown4" size="3" value="250"> characters left.</font>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row pull-right">
                                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="display: block !important;">
                                        <div class="input-group pull-right">
                                            <button type="button" onclick="$('#shareExeBox').modal('hide');" class="btn btn-info font-12 s-f" title="Share Report" >Share Report</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

