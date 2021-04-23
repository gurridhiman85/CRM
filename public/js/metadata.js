
var metaStr;
var resArray = {};
var params = JSON.parse(localStorage.getItem('params'));
$(document).ready(function () {
    $('.meta-go-btn').show();
    $('#save').attr('onclick','nextMeta();');
    oldcampMeta();
    setTimeout(function () {
        dispReportMeta();
    },2000)
})

function nextMeta() {

    //Save New User defined Values in the Lookup table
    var LValue = '';
    var PC1 = $('#cmbPcat1');
    var PC2 = $('#cmbPcat2');
    var Obj = $('#cmbObj');
    if ((PC1.val() == 'Cust1') || (PC2.val() == 'Cust2') || (Obj.val() == 'CustObj'))//CustObj
    {
        if (PC1.val() == 'Cust1') {
            LValue += $.trim($('#Cust1').text());
            $('#Cust1').val($.trim($('#Cust1').text()));
            PC1.val($.trim($('#Cust1').text()));
        }
        LValue += ":";
        if (PC2.val() == 'Cust2') {
            LValue += $.trim($('#Cust2').text());
            $('#Cust2').val($.trim($('#Cust2').text()));
            PC2.val($.trim($('#Cust2').text()));
        }
        LValue += ":";
        if (Obj.val() == 'CustObj') {
            LValue += $.trim($('#CustObj').text());
            $('#CustObj').val($.trim($('#CustObj').text()));
            Obj.val($.trim($('#CustObj').text()));
        }

        $.ajax({
            url : 'campaign/metasavelkp',
            type : 'POST',
            data : {
                LValue : LValue,
                _token : $('[name="_token"]').val()
            },
            success : function (response) {}
        });
    }
    //Save New User defined Values in the Lookup table

    parent.metadatachk = 'Y';
    sessionMeta();
}

function sessionMeta() {
    //var meta = parent.frames['iframeMetadata'].document;
    var r1 = document.getElementById('cmbObj').value;
    var r2 = document.getElementById('cmbBand').value;
    var r3 = document.getElementById('cmbChannel').value;
    var r4 = document.getElementById('txtCat').value;
    var r5 = document.getElementById('txtListDis').value;
    var r6 = document.getElementById('cmbWave').value;
    // var r7= document.getElementById('txtCost').value;
    var r8 = document.getElementById('cmbStartYr').value;
    var r9 = document.getElementById('cmbMonth').value;
    var r10 = document.getElementById('cmbDay').value;
    var r11 = document.getElementById('cmbInterval').value;
    var r12 = document.getElementById('cmbPcat1').value;
    var r13 = document.getElementById('cmbPcat2').value;
    var r14 = document.getElementById('txtSKU').value;
    var r15 = document.getElementById('txtCoupon').value;
    // var goflag = parent.iframeMetadata.goflag;
    //alert(parent.iframeMetadata.goflag);
    params.CampaignID = params.CID,
    params.Type = 'C',
    params.Objective = r1,
    params.Brand = r2,
    params.Channel = r3,
    params.Category = r4,
    params.ListDes = r5,
    params.Wave = r6,
    params.Start_Date = r8 + "/" + r9 + "/" + r10,
    params.Interval = r11,
    params.ProductCat1 = r12,
    params.ProductCat2 = r13,
    params.SKU = r14,
    params.Coupon = r15,
    params.meta_description = r5;
    localStorage.setItem('params',JSON.stringify(params));
    parent.promoexportchk = 'N';
    $('#schedulePopup').modal('show');
    document.frmExec.action = "campaign/reschedule";
    document.frmExec.sSQL.value = params.sSQL;
    document.frmExec.target = "iframeSchedule";
    document.frmExec.submit();
}


function dispReportMeta() {
    var CGStart, index = 1, noRows;
    var LSD = new Array();
    if (parent.addsubgroupchk == 'Y') {
        /*var addsub = parent.frames['iframeaddsub'].document;
        var flag = true;
        while (flag) {
            if (addsub.getElementById('c' + index)) {

                LSD[index] = addsub.getElementById('ld' + index).value;
                index = index + 1;
            } else {
                flag = false;
                index = index - 1;
            }

        }
        noRows = index;*/

        noRows = (params.LSD).split('^')[0].split(':').length;
        LSD = (params.LSD).split('^')[0].split(':');
        var noCG = params.noCG; //addsub.getElementById('cmbnogroup').value;
        var chkCG = params.chkCG//addsub.getElementById('chkgroup').value;
        if (chkCG == 'Y')
            CGStart = 0;
        else
            CGStart = 1;
        //index = 1;

        // Add Subgroup details
        d1 = document.getElementById('divRep');

        var r1 = document.getElementById('cmbObj').value;
        if (document.getElementById('cmbObj').value == 'CustObj') {
            r1 = document.getElementById('CustObj').innerText;
        } else
            r1 = document.getElementById('cmbObj').value;

        var r2 = document.getElementById('cmbBand').value;
        var r3 = document.getElementById('cmbChannel').value;
        var r4 = document.getElementById('txtCat').value;
        var r5 = document.getElementById('txtListDis').value;
        var r6 = document.getElementById('cmbWave').value;
        // var r7= document.getElementById('txtCost').value;
        var r8 = document.getElementById('cmbStartYr').value;
        var r9 = document.getElementById('cmbMonth').value;
        var r10 = document.getElementById('cmbDay').value;
        var r11 = document.getElementById('cmbInterval').value;
        var r12, r13;
        if (document.getElementById('cmbPcat1').value == 'Cust1') {
            r12 = document.getElementById('Cust1').innerText;
        } else
            r12 = document.getElementById('cmbPcat1').value;

        if (document.getElementById('cmbPcat2').value == 'Cust2')
            r13 = document.getElementById('Cust2').innerText;
        else
            r13 = document.getElementById('cmbPcat2').value;


        var r14 = document.getElementById('txtSKU').value;
        var r15 = document.getElementById('txtCoupon').value;

        var strHTML = '<table class="table table-bordered table-hover color-table lkp-table"><thead><tr><th><label>Objective</label></th><th><label>Brand</label></th><th><label>Channel</label></th>';
        strHTML += '<th><label>Category</label></th><th><label>List Description</label></th><th><label>Wave</label></th><th><label>Cost</label></th>';
        strHTML += '<th><label>Start Yr</label></th><th><label>Mth</label></th><th><label>Day</label></th><th><label>Interval</label></th>';
        strHTML += '<th><label>Product Cat1</label></th><th><label>Product Cat2</label></th><th><label>SKU</label></th><th><label>Coupon</label></th><th><label>Segment ID</label></th>';
        strHTML += '<th><label>Segment Description</label></th><th><label>Group ID</label></th><th><label>Group Description</label></th><th><label>SummaryID</label></th></tr></thead>';
        row = "";
        var k = 1;
        var start;
        // alert(parent.nolistseg);

        for (var i = CGStart; i <= noCG; i++) {
            for (var j = 1; j <= noRows; j++) {
                t = (typeof LSD[j] !== 'undefined' && LSD[j] !== null) ?  LSD[j] : '';
                r = ((k % 2 == 0) ? 'even' : 'odd');
                row += '<tr class=' + r + '><td><label>' + r1 + '</label></td><td><label>' + r2 + '</label></td><td><label>' + r3 + '</label></td><td><label>' + r4 + '</label></td><td><label>' + r5 + '</label></td><td><label>' + r6 + '</label></td><td><label>' + parent.CC[i] + '</label></td>';
                row += '<td><label>' + r8 + '</label></td><td><label>' + r9 + '</label></td><td><label>' + r10 + '</label></td><td><label>' + r11 + '</label></td><td><label>' + r12 + '</label></td><td><label>' + r13 + '</label></td><td><label>' + r14 + '</label></td><td><label>' + r15 + '</label></td><td><label>' + j + '</label></td>';
                row += '<td><label>' + t + '</label></td><td><label>' + i + '</label></td><td><label>' + parent.CGD[i] + '</label></td><td><label>' + parent.CGOf[i] + '</label></td></tr>';
                k++;

            }
        }


        strHTML += row + '</table>';
        d1.innerHTML = strHTML;
        d1.style.display = 'block';
    } else if ((parent.oldcampclk == 'Y') && (parent.addsubgroupchk == 'N'))
        viewDispReportMeta();

}


function viewDispReportMeta() {
    //alert("Test1");

    var d1 = document.getElementById('divRep');
    var r1 = document.getElementById('cmbObj').value;
    var r2 = document.getElementById('cmbBand').value;
    var r3 = document.getElementById('cmbChannel').value;
    var r4 = document.getElementById('txtCat').value;
    var r5 = document.getElementById('txtListDis').value;
    var r6 = document.getElementById('cmbWave').value;
    // var r7= document.getElementById('txtCost').value;
    var r8 = document.getElementById('cmbStartYr').value;
    var r9 = document.getElementById('cmbMonth').value;
    var r10 = document.getElementById('cmbDay').value;
    var r11 = document.getElementById('cmbInterval').value;
    var r12 = document.getElementById('cmbPcat1').value;
    var r13 = document.getElementById('cmbPcat2').value;


    var r14 = document.getElementById('txtSKU').value;
    var r15 = document.getElementById('txtCoupon').value;
    var gp;

    if (resArray.seg_ctrl_grp_opt == 'N')
        gp = 1;
    else
        gp = 0;

    var noCGroup = resArray.seg_grp_no;
    var ListSeg = (resArray.seg_selected_criteria).split("^");
    var nolistseg = (ListSeg[0].split(":")).length - 1;
    var LSDArray = (resArray.seg_camp_grp_dtls).split("^");
    var LSD = new Array();
    LSD = ListSeg[1].split(":");
    var CGD = new Array();
    CGD = LSDArray[0].split(":");
    var CGOf = new Array();
    CGOf = LSDArray[1].split(":");
    var CC = new Array();
    CC = LSDArray[2].split(":");


    //alert(metaArray[18]);
    var strHTML = '<table class="table table-bordered table-hover color-table lkp-table"><thead><tr><th><label>Objective</label></th><th><label>Brand</label></th><th><label>Channel</label></th>';
    strHTML += '<th><label>Campaign Detail</label></th><th><label>List Description</label></th><th><label>Wave</label></th><th><label>Cost</label></th>';
    strHTML += '<th><label>Start Yr</label></th><th><label>Mth</label></th><th><label>Day</label></th><th><label>Interval</label></th>';
    strHTML += '<th><label>Product Cat1</label></th><th><label>Product Cat2</label></th><th><label>SKU</label></th><th><label>Coupon</label></th><th><label>Segment ID</label></th>';
    strHTML += '<th><label>Segment Description</label></th><th><label>Group ID</label></th><th><label>Group Description</label></th><th><label>SummaryID</label></th></tr></thead>';
    row = "";
    var k = 1;

    for (var i = gp; i <= noCGroup; i++) {
        for (var j = 1; j <= nolistseg; j++) {
            t = (typeof LSD[j] !== 'undefined' && LSD[j] !== null) ?  LSD[j] : '';
            r = ((k % 2 == 0) ? 'even' : 'odd');
            row += '<tr class=' + r + '><td><label>' + r1 + '</label></td><td><label>' + r2 + '</label></td><td><label>' + r3 + '</label></td><td><label>' + r4 + '</label></td><td><label>' + r5 + '</label></td><td><label>' + r6 + '</label></td><td><label>' + CC[i] + '</label></td>';
            row += '<td><label>' + r8 + '</label></td><td><label>' + r9 + '</label></td><td><label>' + r10 + '</label></td><td><label>' + r11 + '</label></td><td><label>' + r12 + '</label></td><td><label>' + r13 + '</label></td><td><label>' + r14 + '</label></td><td><label>' + r15 + '</label></td><td><label>' + j + '</label></td>';
            row += '<td><label>' + t + '</label></td><td><label>' + i + '</label></td><td><label>' + CGD[i] + '</label></td><td><label>' + CGOf[i] + '</label></td></tr>';
            k++;

        }
    }
    strHTML += row + '</table>';
    d1.innerHTML = strHTML;
    d1.style.display = 'block';


}

function disableEleMeta() {

//alert(parent.up_flag);
//  Disable dropdown
    var disable_flag = 0;
    if (parent.up_flag == 'view') {

        document.getElementById("btnGo").disabled = true;
        disable_flag = 1;
    } else if (parent.up_flag == 'update') {
        document.getElementById('savebottom').innerHTML = 'Update';
    } else if (parent.up_flag = 'new') {
        if (params.saveCD == 'N') {
            document.getElementById("btnGo").disabled = true;
            disable_flag = 1;
        }
    }

    if (disable_flag == 1) {
        var f = document.getElementsByTagName('select');
        for (var i = 0; i < f.length; i++) {
            f[i].setAttribute('disabled', true)
        }

        // Input ---  Checkbox and Input box
        var f = document.getElementsByTagName('input');
        for (var i = 0; i < f.length; i++) {
            f[i].setAttribute('disabled', true)
        }
        document.getElementById('save').disabled = true;
        document.getElementById('savebottom').disabled = true;
    }


}

function oldcampMeta() {
    /********* 2018-03-23 - changes for hide buttons when view selected -- start ********/
    if (parent.up_flag == 'view') {
        $('.ft').hide();
    }
    /********* 2018-03-23 - changes for hide buttons when view selected -- end ********/

    $('#campid').html(params.CID)

    // if(((parent.oldcampclk == 'Y')&&(parent.addsubgroupchk=='N'))||(parent.up_flag == 'update'))


    if (parent.up_flag == 'new') {
        document.getElementById('cmbObj').value = params.Objective;
        document.getElementById('cmbBand').value = params.Band;
        document.getElementById('cmbChannel').value = params.Channel;
        document.getElementById('txtCat').value = params.Category;
        document.getElementById('txtListDis').value = params.ListDes;
        document.getElementById('cmbWave').value = params.Wave;
        var Start_Date = (params.Start_Date).split('-');
        document.getElementById('cmbStartYr').value = Start_Date[0];
        document.getElementById('cmbMonth').value = Start_Date[1];
        document.getElementById('cmbDay').value = Start_Date[2];

        document.getElementById('cmbInterval').value = params.Interval;
        document.getElementById('cmbPcat1').value = params.ProductCat1;
        document.getElementById('cmbPcat2').value = params.ProductCat2;


        document.getElementById('txtSKU').value = params.SKU;
        document.getElementById('txtCoupon').value = params.Coupon;

        if (params.CGOpt == 'N') {
            document.getElementById("btnGo").disabled = true;
        } else {
            document.getElementById("btnGo").disabled = false;
        }

        document.getElementById('cmbStartYr').value = yy;
        document.getElementById('cmbMonth').value = mm;
        document.getElementById('cmbDay').value = dd;
    }

    if ((parent.oldcampclk == 'Y') || (parent.up_flag == 'update') || (parent.up_flag == 'view')) {


        $.ajax({
            url : 'campaign/getmetadata',
            type : 'POST',
            data : {
                tempid : params.row_id,
                _token : $('[name="_token"]').val()
            },
            success : function (response) {
                if (response !== undefined) {
                    var metaResponse = response.aData;
                    resArray = metaResponse;
                    if(metaResponse.rpmeta && metaResponse.rpmeta != null){
                        document.getElementById('cmbObj').value = metaResponse.rpmeta.Objective;
                        document.getElementById('cmbBand').value = metaResponse.rpmeta.Band;
                        document.getElementById('cmbChannel').value = metaResponse.rpmeta.Channel;
                        document.getElementById('txtCat').value = metaResponse.rpmeta.Category;
                        document.getElementById('txtListDis').value = metaResponse.rpmeta.ListDes;
                        document.getElementById('cmbWave').value = metaResponse.rpmeta.Wave;
                        var Start_Date = (metaResponse.rpmeta.Start_Date).split('-');
                        document.getElementById('cmbStartYr').value = Start_Date[0];
                        document.getElementById('cmbMonth').value = Start_Date[1];
                        document.getElementById('cmbDay').value = Start_Date[2];

                        document.getElementById('cmbInterval').value = metaResponse.rpmeta.Interval;
                        document.getElementById('cmbPcat1').value = metaResponse.rpmeta.ProductCat1;
                        document.getElementById('cmbPcat2').value = metaResponse.rpmeta.ProductCat2;


                        document.getElementById('txtSKU').value = metaResponse.rpmeta.SKU;
                        document.getElementById('txtCoupon').value = metaResponse.rpmeta.Coupon;

                        if (metaResponse.promoexpo_cd_opt == 'N') {
                            document.getElementById("btnGo").disabled = true;
                        } else {
                            document.getElementById("btnGo").disabled = false;
                        }
                    }
                    if (parent.up_flag == 'new') {
                        document.getElementById('cmbStartYr').value = yy;
                        document.getElementById('cmbMonth').value = mm;
                        document.getElementById('cmbDay').value = dd;
                    }
                }
            }
        });
    } else {
        if (params.saveCD == 'N') {

            document.getElementById("btnGo").disabled = true;
            disableEleMeta();
        }
    }
}

function isInArray(value, array) {

    return $.inArray(value, array) != -1;
}

function AvoidSpaceMeta(event) {

    var k = event ? event.which : window.event.keyCode;
    //console.log(k);
    var notAllowedLevelArr = [126, 33, 64, 35, 94, 38, 42, 40, 41, 63];

    if (isInArray(k, notAllowedLevelArr)) {
        return false;
    }
}