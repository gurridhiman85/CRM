$(document).ready(function () {
    $('.cnt-btn , .prev-btn , .dwn-btn').hide();
    $('.seg-clr-btn').show();
    $('#save').attr('disabled',true).attr('onclick','next();');
    $('#savebottom').attr('disabled',true);
    $('#saveoption').hide();
    getFormVal();
    //init();
})

var optarray = ['>', '<', '>=', '<=', '=', '!=', 'in', 'not in', 'like', 'like', 'like', 'not like', 'not like', 'not like'];
var yrow;
var logarray = ['AND', 'OR'];
var opt1 = "", opt2 = "", opt3 = "";
var row = "";
var name11 = 'ccol1', name12 = 'op1', name13 = 'val1', name14 = 'log1';
var name21 = 'ccol2', name22 = 'op2', name23 = 'val2', name24 = 'log2';
var name31 = 'ccol3', name32 = 'op3', name33 = 'val3';
var WhereArray = new Array();
var val = new Array();
var resArray = new Array();
var res;
var outputStr;
var noCondition = 0;
var seq_num = 0;
var noRows;
var byField_flag = 0;
var params = JSON.parse(localStorage.getItem('params'));

function next() {

    var SD = document.getElementById('cmbSD').value;
    var SC = document.getElementById('cmbSC').value;
    //console.log(SD+'---'+SC);
    if (SD == 'cmbUG') {
        var noCG = document.getElementById('cmbnogroup').value;
        var chk = document.getElementById('chkgroup').value;
        var gp;
        if (chk == 'Y')
            gp = 0;
        else
            gp = 1;
        for (var i = 1; i <= noRows; i++) {
            sum = 0;
            if (i <= 9)
                segID = '0' + i;
            else
                segID = i;
            for (var j = gp; j <= noCG; j++) {
                if (j <= 9)
                    gpID = '0' + j;
                else
                    gpID = j;
                rec = parseInt(document.getElementById('rec' + segID + gpID).innerText, 10);
                sum += rec;

            }
            var totalRec;
            if (document.getElementById('ssID' + i).tagName == 'INPUT')
                totalRec = parseInt(document.getElementById('ssID' + i).value, 10);
            else
                totalRec = parseInt(document.getElementById('ssID' + i).innerText, 10);

            if (sum > totalRec) {
                alert("Sample Size is more than Universe in Segment " + i);
                return;
            }

        }
    }
    if ((SD != '') && (SC != '')) { //console.log('1');
        parent.addsubgroupchk = 'Y';
        var c1;
        var c2 = document.getElementById('cmbnogroup').value;
        var chk = document.getElementById('chkgroup').value;
        var gp;
        if (chk == 'Y')
            gp = 0;
        else
            gp = 1;
        for (var i = gp; i <= c2; i++) {

            if (document.getElementById('cGDis' + i).value == 'cGDisCust') {
                if (document.getElementById('cGDisCust' + i).innerText != '----') {
                    var l1 = document.getElementById('cGDisCust' + i).innerText;
                    document.getElementById('cGDisCust' + i).value = l1;
                    parent.CGD[i] = l1;
                    /* 2020-12-08 - var handleSuccess = function (o) {
                            if (o.responseText !== undefined) {

                            }
                        }
                        var callback = {success: handleSuccess};

                        var postData = "pgaction=StoreLookupCGD&LookupCol=" + l1 + "&rand=" + Math.random();

                        var request = YAHOO.util.Connect.asyncRequest('POST', 'addsub_data.php', callback, postData);*/


                } else {
                    parent.CGD[i] = '';

                }


            } else {
                parent.CGD[i] = document.getElementById('cGDis' + i).value;

            }

            if (document.getElementById('cOffer' + i).value == 'cOfferCust') {
                if (document.getElementById('cOfferCust' + i).innerText != '----') {
                    var l1 = document.getElementById('cOfferCust' + i).innerText;
                    document.getElementById('cOfferCust' + i).value = l1;
                    parent.CGOf[i] = l1;
                    /*2020-12-08 -- var handleSuccess = function (o) {
                            if (o.responseText !== undefined) {

                            }
                        }
                        var callback = {success: handleSuccess};

                        var postData = "pgaction=StoreLookupOff&LookupCol=" + l1 + "&rand=" + Math.random();

                        var request = YAHOO.util.Connect.asyncRequest('POST', 'addsub_data.php', callback, postData);*/

                    parent.CGOf[i] = l1;

                    parent.CGOf[i] = document.getElementById('cOfferCust' + i).innerText;

                } else {
                    parent.CGOf[i] = '';

                }

            } else
                parent.CGOf[i] = document.getElementById('cOffer' + i).value;

            parent.CC[i] = document.getElementById('cCost' + i).value;

        }
        //console.log('2');

        /*if (parent.up_flag == 'update')
                parent.metadateSQL();
            else
                parent.promoExportSQL();*/

        //console.log('here');
    }

    segsession();

}

function segsession() {

    var ADQsql = '';//escape(parent.window.document.Form1.qb.LayoutSQL);
//alert(parent.window.document.Form1.qb.LayoutSQL);
//document.frm.ADQsql.value = ADQsql;
//alert(ADQsql);
//var sSQL = window.document.Form1.sql.value;
    var sSQL = params.sSQL;
//alert(sSQL);
//alert(parent.window.document.Form1.qb.LayoutSQL);
    sSQL = sSQL.replace(/(\r\n|[\r\n])/g, ' ');
    var lssc;

    //var addsub = parent.frames['iframeaddsub'].document;

//Define List Segments
    var DFS = document.getElementById('cmbDFS').value;
//Define List Segments

    var element = Array('', 'ccol', 'op', 'val', 'log');
    var segFilterCriteria = '';
    var segFilterCondition = '';
//List Segment Selection Criteria
    if (DFS == 'custom')   //List Segment Selection Method Custom
    {

        /*noLS = document.getElementById('numRows_4').value;
        var ccol = new Array();
        var op = new Array();
        var val = new Array();
        var log = new Array();
        var index = 1;
        for (var i = 1; i <= noLS; i++) {
            for (var j = 1; j <= 3; j++) {
                var id1 = (element[1] + i) + j.toString();
                var id2 = (element[2] + i) + j.toString();
                var id3 = (element[3] + i) + j.toString();
                var id4 = (element[4] + i) + j.toString();
                /!*
		ccol[index]=addsub.getElementById(id1).value;
		op[index]=addsub.getElementById(id2).value;
		val[index]=addsub.getElementById(id3).value;
		*!/
                if ($('[id=' + id1 + ']').val() != "") {
                    ccol[index] = $('[id=' + id1 + ']').val();
                    ;
                    op[index] = $('[id=' + id2 + ']').val();
                    ;
                    val[index] = $('[id=' + id3 + ']').val();
                    ;
                    if (j != 3) {
                        //var id4=(element[4]+j)+i.toString();
                        //log[index]=addsub.getElementById(id4).value;
                        //log[index] = ' OR ';//$('[name='+id4+']').val();;
                        log[index] = $('[name='+id4+']').val();;
                    }
                    index++;
                }

            }
        }
        ccolStr = ccol.join(':');
        opStr = op.join(':');
        valStr = val.join(':');
        logStr = log.join(':');
        lssc = ccolStr + '^' + opStr + '^' + valStr + '^' + logStr;*/
        noLS = document.getElementById('numRows_4').value;
        segFilterCriteria = customFiltersJSON();
        segFilterCondition = seg_filter_summary();
        lssc ='';

    }//Custom
    else if (DFS == 'byfield') {
        noLS = document.getElementById('cmbNoFields').value;
        var colArray = new Array();
        for (i = 1; i <= noLS; i++) {
            colArray[i] = document.getElementById('col' + i).value;
        }
        lssc = colArray.join(":");

    } else if (DFS == 'none') {
        noLS = 0;
        lssc = '';
    }
//List Segment Selection Criteria


// List Segment Selection method
    var lssm = document.getElementById('cmbLSM').value;
// List Segment Selection method

    var criteria = new Array();
    var desc = new Array();
    var universe = new Array();
    var sample = new Array();
    var sSize = new Array();

//List Segment Details
    for (i = 1; i <= noRows; i++) {
        criteria[i] = $.trim(document.getElementById('cri' + i).innerText);
        desc[i] = $.trim(document.getElementById('ld' + i).value);
        if (document.getElementById('sp' + i).tagName == 'INPUT')
            universe[i] = $.trim(document.getElementById('sp' + i).value);
        else
            universe[i] = $.trim(document.getElementById('sp' + i).innerText);
        if (document.getElementById('spID' + i).tagName == 'INPUT')
            sample[i] = $.trim(document.getElementById('spID' + i).value);
        else
            sample[i] = $.trim(document.getElementById('spID' + i).innerText);
        if (document.getElementById('ssID' + i).tagName == 'INPUT')
            sSize[i] = $.trim(document.getElementById('ssID' + i).value);
        else
            sSize[i] = $.trim(document.getElementById('ssID' + i).innerText);
    }

    LSD = criteria.join(":") + '^' + desc.join(":") + "^" + universe.join(":") + "^" + sample.join(":") + "^" + sSize.join(":");
//List Segment Details

//var noCG = addsub.getElementById('cmbnogroup').value;
    noCG = document.getElementById('cmbnogroup').value
    chkCG = document.getElementById('chkgroup').value;
//SC = addsub.getElementById('cmbSC').value;
//SD = addsub.getElementById('cmbSD').value;
    if (chkCG == 'Y')
        CGStart = 0;
    else
        CGStart = 1;


//Campaign Group Details
    var cGDis = new Array();
    var cOffer = new Array();
    var cCost = new Array();
    for (var i = CGStart; i <= noCG; i++) {

        if (document.getElementById('cGDis' + i).value == 'cGDisCust')
            cGDis[i] = '';
        else
            cGDis[i] = document.getElementById('cGDis' + i).value;
        if (document.getElementById('cOffer' + i).value == 'cOfferCust')
            cOffer[i] = '';
        else
            cOffer[i] = document.getElementById('cOffer' + i).value;
        cCost[i] = document.getElementById('cCost' + i).value;
    }
    var cGDisStr = cGDis.join(":");
    var cOfferStr = cOffer.join(":");
    var cCostStr = cCost.join(":");
    var CGD = cGDisStr + "^" + cOfferStr + "^" + cCostStr;


//Campaign Group Details

//Campaign Group Y/N
    var cg = document.getElementById('chkgroup').value;
//Campaign Group Y/N

//seg_camp_grp_proportion
    var proporation = document.getElementById('cmbSD').value;
//seg_camp_grp_proportion

//seg_camp_grp_sel_cri
    var sel_criteria = document.getElementById('cmbSC').value;
//seg_camp_grp_sel_cri

//Samples
    var index = 0;
    var Sample = new Array();
    var SampleSize = new Array();
    for (i = 1; i <= noRows; i++) {
        for (j = CGStart; j <= noCG; j++) {
            if (i < 10)
                id1 = "0" + i;
            else
                id1 = i.toString(10);
            if (j < 10)
                id2 = "0" + j;
            else
                id2 = j.toString(10);
            id = id1 + id2;

            Sample[index] = document.getElementById('rec' + id).innerText;
            if (document.getElementById('list' + id).tagName == 'INPUT')
                SampleSize[index] = document.getElementById('list' + id).value;
            else
                SampleSize[index] = document.getElementById('list' + id).innerText;
            index = index + 1;
        }
    }
    cellSample = SampleSize.join(":") + "^" + Sample.join(":");

    params.ADQsql = ADQsql;
    params.DFS = DFS;
    params.noLS = noLS;
    params.lssm = lssm;
    params.lssc = lssc;
    params.segFilterCriteria = segFilterCriteria;
    params.segFilterCondition = segFilterCondition;
    params.noCG = noCG;
    params.cg = cg;
    params.LSD = LSD;
    params.CGD = CGD;
    params.chkCG = chkCG;
    params.proporation = proporation;
    params.sel_criteria = sel_criteria;
    params.cellSample = cellSample;
    params.sSQL = sSQL;
    localStorage.setItem('params',JSON.stringify(params))
    var promoExpo_openFlag = 'Y';
    if (promoexportchk == 'N') {
        $('[href="#tab_11"]').trigger('click');
    }
}

function seg_filter_summary() {

    var val = new Array();


    var WhereArray = new Array();

    var noRows = document.getElementById("numRows_4").value;
    var ccol = new Array();
    var op = new Array();
    var is_nm = new Array();//check is numeric
    var log = new Array();
    var k = 0;
    var element = [' ', 'ccol', 'op', 'val', 'log'];

    var p = 31;
    var c = parseInt(noRows) + parseInt(p);

    for (var i = p; i <= c; i++) {
        for (j = 1; j <= 3; j++) {

            var id1 = (element[1] + i) + j.toString();
            var id2 = (element[2] + i) + j.toString();
            var id3 = (element[3] + i) + j.toString();
            var id4 = (element[4] + i) + j.toString();

            if ($('[id=' + id1 + ']').val() != "") {
                ccol[k] = $('[id=' + id1 + ']').val();
                op[k] = $('[id=' + id2 + ']').val();
                is_nm[k] = $('[id=' + id2 + ']').attr('rel');
                val[k] = $('[id=' + id3 + ']').val();
                if (j != 3) {
                    //log[k] = ' OR '; //$('[id='+id4+']').val();
                    if($('[id='+id4+']').length > 0){
                        log[k] = $('[id='+id4+']').val();
                    }
                }
                k++;
            } else {
                continue;
            }
        }
    }

    var where = new Array(), op1, flag;

    for (var j = 0; j < noRows; j++) {

        flag = 0;
        where[j] = "";
        for (var i = (j * 3); i < (j * 3) + 3; i++) {
            switch (op[i]) {

                case '0':
                    op1 = " > ";
                    break;
                case '1':
                    op1 = " < ";
                    break;
                case '2':
                    op1 = " >= ";
                    break;
                case '3':
                    op1 = " <= ";
                    break;
                case '4':
                    op1 = " = ";
                    break;
                case '5':
                    op1 = " != ";
                    break;
                case '6':
                    op1 = " in ";
                    break;
                case '7':
                    op1 = " not in ";
                    break;
                case '8':
                    op1 = " like ";
                    break;
                case '8.1':
                    op1 = " like ";
                    break;
                case '8.2':
                    op1 = " like ";
                    break;
                case '9':
                    op1 = " not like ";
                    break;
                case '9.1':
                    op1 = " not like ";
                    break;
                case '9.2':
                    op1 = " not like ";
                    break;
                default:
                    op1 = "";
                    break;
            }
            //if ((ccol[i] != "") && (op1 != "") && (val[i] != "")) {
            if ((ccol[i] != "") && (op1 != "")) {
                var reg = /^\d+$/;
                if (flag == 0) {
                    if (op[i] > '5' && op[i] < '8') {

                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(' + vValArray[kk] + ')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + ')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(\'' + vValArray[kk] + '\')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '(' + val[i] + ')';
                            } else {
                                nNewValVar = '(\'' + val[i] + '\')';
                            }
                        }

                        if (ccol[i + 1] != " " && ccol[i + 1] != null) {
                            where[j] += ' ' + ccol[i] + ' ' + op1 + nNewValVar;
                        } else {
                            where[j] += '( ' + ccol[i] + ' ' + op1 + nNewValVar + ' )';
                        }
                        flag = 1;
                    } else if (op[i] > '7') {

                        if (op[i] == '8') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '8.1') {
                            var st = '', ed = '%';
                        } else if (op[i] === '8.2') {
                            var st = '%', ed = '';
                        } else if (op[i] == '9') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '9.1') {
                            var st = '', ed = '%';
                        } else if (op[i] == '9.2') {
                            var st = '%', ed = '';
                        }

                        if (val[i].indexOf(",") > -1) {
                            var vValArray = val[i].split(",");
                            var nNewValVar = '(';

                            var collog = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (vValArray.length == 1)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (kk + 1 == vValArray.length)
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (vValArray.length > 1 && kk == 0)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';
                                else
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';   //After Start if like have more then one options
                            }
                        } else {
                            collog = '(' + ccol[i] + ' ' + op1 + ' \'' + st + val[i] + ed + '\')';
                        }


                        if (ccol[i + 1] != " ") // For two columns
                            where[j] += '' + collog;
                        else
                            where[j] += collog;

                        flag = 1;
                    } else {
                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                //if (reg.test(vValArray[kk]) == true) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '' + val[i] + '';
                            } else {
                                nNewValVar = '\'' + val[i] + '\'';
                            }
                        }
                        if (ccol[i + 1] != " ") {
                            where[j] += ' ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' ';

                        } else {
                            where[j] += '( ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' )';
                        }
                        flag = 1;
                    }
                } else if (log[i - 1] != " ") {
                    if (op[i] > '5' && op[i] < '8') {
                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(' + vValArray[kk] + ')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + ')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(\'' + vValArray[kk] + '\')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '(' + val[i] + ')';
                            } else {
                                nNewValVar = '(\'' + val[i] + '\')';
                            }
                        }

                        var vl = (j * 3) + 2;

                        if ((i == vl) && (ccol[vl] != ""))
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + nNewValVar + '';
                        else
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + nNewValVar + '';


                    } else if (op[i] > '7') {
                        if (op[i] == '8') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '8.1') {
                            var st = '', ed = '%';
                        } else if (op[i] === '8.2') {
                            var st = '%', ed = '';
                        } else if (op[i] == '9') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '9.1') {
                            var st = '', ed = '%';
                        } else if (op[i] == '9.2') {
                            var st = '%', ed = '';
                        }

                        if (val[i].indexOf(",") > -1) {
                            var vValArray = val[i].split(",");
                            var nNewValVar = '(';
                            var collog = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (vValArray.length == 1)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (kk + 1 == vValArray.length)
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (vValArray.length > 1 && kk == 0)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';
                                else
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';
                            }
                        } else {
                            collog = '(' + ccol[i] + ' ' + op1 + ' \'' + st + val[i] + ed + '\')';
                        }
                        var vl = (j * 3) + 2;

                        if ((i == vl) && (ccol[vl] != "")) {
                            where[j] += log[i - 1] + collog + '';
                        } else {
                            where[j] += log[i - 1] + collog + '';
                        }

                    } else {
                        var vl = (j * 3) + 2;

                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '' + val[i] + '';
                            } else {
                                nNewValVar = '\'' + val[i] + '\'';
                            }
                        }
                        if ((i == vl) && (ccol[vl] != ""))
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' ';
                        else {
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' ';
                        }
                    }
                }  // Else if
            }  // If

        }  // Inner For
    }// Outer For

    var k = 0;
    for (var i = 0; i < where.length; i++) {
        if (where[i] != "") {

            var n = where[i].indexOf("OR");
            if (n > 0) {
                WhereArray[k] = " (" + where[i] + ") ";
            } else {
                WhereArray[k] = "" + where[i];
            }
            k++;
        }
    }
    var str = WhereArray.join(" AND ");
    if (str != "") {
        str = str;
    } else {
        str = "";
    }
    var sSQL;

    /***************** Change 2017-03-07 Start ***********************/
    str = str.replace(/::/g, ",");
    /***************** Change 2017-03-07 End ***********************/


    /************************ Without run query get filter summery Start **********************************/
    return str;

    /************************ Without run query get filter summery End **********************************/
}

function CGroupDetails() {
    document.getElementById('divCG1').style.display = 'none';
    var list = document.getElementById('cmbnogroup');
    var chk = document.getElementById('chkgroup');
    var div2 = document.getElementById('divCG2');
    div2.innerHTML = "";

    // AJAX
    var op1 = "", op2 = "", op11 = "", op21 = "";
    $.ajax({
        url : 'model/cg',
        type : 'POST',
        data : {
            _token : $('[name="_token"]').val()
        },
        success : function (response){
            if (response !== undefined) {
                CGD = response.aDataCGD
                COff = response.aDataCOff
                // COff = COffStr.split(',');
                // CGD = CGDStr.split(',');
                for (var i = 0; i < CGD.length; i++) {
                    op1 += "<option value='" + CGD[i].code_value + "' >" + CGD[i].code_value + "</option>";

                    if (CGD[i].code_value != "CTRL-Control")
                        op11 += "<option value='" + CGD[i].code_value + "' >" + CGD[i].code_value + "</option>";
                    else
                        op11 += "<option value='" + CGD[i].code_value + "' selected >" + CGD[i].code_value + "</option>";

                }

                for (var i = 1; i <= 20; i++) {
                    op21 += "<option value='" + i + "' >" + i + "</option>";
                    if (i != 1)
                        op2 += "<option value='" + i + "' >" + i + "</option>";
                    else
                        op2 += "<option value='" + i + "' selected >" + i + "</option>";
                }

                for (var i = 0; i < COff.length; i++) {
                    op2 += "<option value='" + COff[i].code_value + "' >" + COff[i].code_value + "</option>";
                    op21 += "<option value='" + COff[i].code_value + "' >" + COff[i].code_value + "</option>";

                }
                var groupid, strHTML = "", row = "";

                if (list.value != "") {
                    if (chk.value == 'Y')
                        groupid = 0;
                    else
                        groupid = 1;
                }
                strHTML = '<table class="c1" style="width:970px"><tr><td height="10px"></td></tr><tr><td style="width:164px">Campaign Group Details &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td><td style="font-weight: 400;"></td><td style="font-weight: 400;" width="240px">Campaign Group Description</td><td style="font-weight: 400;">SummaryID</td><td width="370px" style="font-weight: 400;">Campaign Cost</td></tr><tr><tr><td height="10px"></td></tr>';
                for (var i = groupid; i <= list.value; i++) {

                    if (i != 0)
                        row += '<tr><td></td><td style="width:20px"><label class="label1">' + i + '</label></td><td><select class="form-control form-control-sm" name="lstDropDown_A" style="" onKeyDown="fnKeyDownHandler_A(this, event);" onKeyUp="fnKeySegmentUpHandler_A(this, event); return false;" onKeyPress = "return fnKeySegmentPressHandler_A(this, event);"  onChange="fnChangeHandler_A(this, event);" style="width:200px"  onblur="changeSample()" id=cGDis' + i + '><option value="cGDisCust" id=cGDisCust' + i + '>----</option>' + op1 + '</select></td><td style="width:80px"><select class="form-control form-control-sm" name="lstDropDown_A" style="" onKeyDown="fnKeyDownHandler_A(this, event);" onKeyUp="fnKeyUpHandler_A(this, event); return false;" onKeyPress = "return fnKeyPressHandler_A(this, event);"  onChange="fnChangeHandler_A(this, event);" style="width:60px" id=cOffer' + i + '><option value="cOfferCust" id=cOfferCust' + i + '>----</option>' + op2 + '</select></td><td><input style="width:75px" class="form-control form-control-sm" id=cCost' + i + ' onkeypress="return numeric_only(event);" /></td></tr>';
                    else
                        row += '<tr><td></td><td style="width:20px"><label class="label1">' + i + '</label></td><td><select class="form-control form-control-sm" name="lstDropDown_A" style="" onKeyDown="fnKeyDownHandler_A(this, event);" onKeyUp="fnKeySegmentUpHandler_A(this, event); return false;" onKeyPress = "return fnKeySegmentPressHandler_A(this, event);"  onChange="fnChangeHandler_A(this, event);" style="width:200px" onblur="changeSample()" id=cGDis' + i + '><option value="cGDisCust" id=cGDisCust' + i + '>----</option>' + op11 + '</select></td><td><select class="form-control form-control-sm" name="lstDropDown_A" style="" onKeyDown="fnKeyDownHandler_A(this, event);" onKeyUp="fnKeyUpHandler_A(this, event); return false;" onKeyPress = "return fnKeyPressHandler_A(this, event);"  onChange="fnChangeHandler_A(this, event);" style="width:60px" id=cOffer' + i + '><option value="cOfferCust" id=cOfferCust' + i + '>----</option>' + op21 + '</select></td><td><input style="width:75px" class="form-control form-control-sm" value=0 id=cCost' + i + ' onkeypress="return numeric_only(event);" /></td></tr>';
                }

                strHTML += row + "</table>";

                div2.innerHTML = strHTML;
                div2.style.display = 'none';
                document.getElementById('divCG3').style.display = 'none';
                $('#cmbSD').val('cmbAEG');
                $('#cmbSC').val('cmbPU');
                setDefaultFromSelect();
                sample();
            }
        }
    });


    var f = new Array('cmbSC', 'cmbSD');
    for (var i = 0; i < f.length; i++)
        document.getElementById(f[i]).value = '';
    document.getElementById('divCG4').style.display = 'none';
    //AJAX
}

function setDefaultFromSelect() {
    var CGD = params.CGD;
    var chkCG = params.chkCG;

    //$('#chkgroup').val(chkCG);

    var CGDArray = CGD.split('^');
    var cgdArr = CGDArray[0].split(':');
    $('#cGDis0').val(cgdArr[0]);

    var segArr = CGDArray[1].split(':');
    $('#cOffer0').val(segArr[0]);

    var costArr = CGDArray[2].split(':');
    $('#cCost0').val(costArr[0]);
}

function ViewCGroupDetails() {
    document.getElementById('divCG1').style.display = 'none';
    var list = document.getElementById('cmbnogroup');
    var chk = document.getElementById('chkgroup');
    var div2 = document.getElementById('divCG2');
    div2.innerHTML = "";
    // AJAX
    var op1 = "", op2 = "", op11 = "", op21 = "";
    $.ajax({
        url: 'model/cg',
        type: 'POST',
        data: {
            _token: $('[name="_token"]').val()
        },
        success: function (response) {
            if (response !== undefined) {

                CGD = response.aDataCGD;
                COff = response.aDataCOff;
                //COff = COffStr.split(',');
                //CGD = CGDStr.split(',');
                for (var i = 0; i < CGD.length; i++) {
                    op1 += "<option value='" + CGD[i].code_value + "' >" + CGD[i].code_value + "</option>";

                    if (CGD[i].code_value != "CTRL-Control")
                        op11 += "<option value='" + CGD[i].code_value + "' >" + CGD[i].code_value + "</option>";
                    else {
                        op11 += "<option value='" + CGD[i].code_value + "' selected >" + CGD[i].code_value + "</option>";
                    }
                }

                for (var i = 1; i <= 20; i++) {
                    op21 += "<option value='" + i + "' >" + i + "</option>";

                    if (i != 1)
                        op2 += "<option value='" + i + "' >" + i + "</option>";
                    else
                        op2 += "<option value='" + i + "' selected >" + i + "</option>";
                }

                for (var i = 0; i < COff.length; i++) {
                    op2 += "<option value='" + COff[i].code_value + "' >" + COff[i].code_value + "</option>";
                    op21 += "<option value='" + COff[i].code_value + "' >" + COff[i].code_value + "</option>";

                }

                var groupid, strHTML = "", row = "";

                if (list.value != "") {
                    if (chk.value == 'Y')
                        groupid = 0;
                    else
                        groupid = 1;
                }
                strHTML = '<table class="c1" style="width:970px"><tr><td height="10px"></td></tr><tr><td style="width:164px">Campaign Group Details &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td><td style="font-weight: 400;"></td><td style="font-weight: 400;" width="240px">Campaign Group Description</td><td style="font-weight: 400;">SummaryID</td><td width="370px" style="font-weight: 400;">Campaign Cost</td></tr><tr><tr><td height="10px"></td></tr>';
                for (var i = groupid; i <= list.value; i++) {

                    if (i != 0)
                        row += '<tr><td></td><td style="width:20px"><label class="label1">' + i + '</label></td><td><select class="form-control form-control-sm" name="lstDropDown_A" style="" onKeyDown="fnKeyDownHandler_A(this, event);" onKeyUp="fnKeySegmentUpHandler_A(this, event); return false;" onKeyPress = "return fnKeySegmentPressHandler_A(this, event);"  onChange="fnChangeHandler_A(this, event);" style="width:200px" onblur="changeSample()" id=cGDis' + i + '><option value="cGDisCust" id=cGDisCust' + i + '>----</option>' + op1 + '</select></td><td style="width:80px"><select class="form-control form-control-sm" name="lstDropDown_A" style="" onKeyDown="fnKeyDownHandler_A(this, event);" onKeyUp="fnKeyUpHandler_A(this, event); return false;" onKeyPress = "return fnKeyPressHandler_A(this, event);"  onChange="fnChangeHandler_A(this, event);" style="width:60px" id=cOffer' + i + '><option value="cOfferCust" id=cOfferCust' + i + '>----</option>' + op2 + '</select></td><td><input style="width:75px" class="form-control form-control-sm"  id=cCost' + i + ' onkeypress="return numeric_only(event);" /></td></tr>';
                    else
                        row += '<tr><td></td><td style="width:20px"><label class="label1">' + i + '</label></td><td><select class="form-control form-control-sm" name="lstDropDown_A" style="" onKeyDown="fnKeyDownHandler_A(this, event);" onKeyUp="fnKeySegmentUpHandler_A(this, event); return false;" onKeyPress = "return fnKeySegmentPressHandler_A(this, event);"  onChange="fnChangeHandler_A(this, event);" style="width:200px" onblur="changeSample()" id=cGDis' + i + '><option value="cGDisCust" id=cGDisCust' + i + '>----</option>' + op11 + '</select></td><td><select class="form-control form-control-sm" name="lstDropDown_A" style="" onKeyDown="fnKeyDownHandler_A(this, event);" onKeyUp="fnKeyUpHandler_A(this, event); return false;" onKeyPress = "return fnKeyPressHandler_A(this, event);"  onChange="fnChangeHandler_A(this, event);" style="width:60px" id=cOffer' + i + '><option value="cOfferCust" id=cOfferCust' + i + '>----</option>' + op21 + '</select></td><td><input style="width:75px" class="form-control form-control-sm" value=0 id=cCost' + i + ' onkeypress="return numeric_only(event);" /></td></tr>';
                }

                strHTML += row + "</table>";


                div2.innerHTML = strHTML;
                div2.style.display = 'none';
                document.getElementById('divCG3').style.display = 'none';
                var CGD = new Array();
                var cGDis = new Array();
                var cOffer = new Array();
                var cCost = new Array();
                CGD = (res.seg_camp_grp_dtls).split("^");
                cGDis = CGD[0].split(":");
                cOffer = CGD[1].split(":");
                cCost = CGD[2].split(":");
                var gp;
                if (res.seg_ctrl_grp_opt == 'Y')
                    gp = 0;
                else
                    gp = 1;
                for (var i = gp; i <= res.seg_grp_no; i++) {
                    if (cGDis[i] != '')
                        document.getElementById('cGDis' + i).value = cGDis[i];
                    else
                        document.getElementById('cGDis' + i).value = 'cGDisCust';
                    if (cOffer[i] != '')
                        document.getElementById('cOffer' + i).value = cOffer[i];
                    else
                        document.getElementById('cOffer' + i).value = 'cOfferCust';

                    document.getElementById('cCost' + i).value = cCost[i];
                }
                document.getElementById('cmbSD').value = res.seg_camp_grp_proportion;
                document.getElementById('cmbSC').value = res.seg_camp_grp_sel_cri;

            }
        }
    });
}

function calculate(obj, spID, ssID) {

    var p = obj.value;
    id1 = spID + "";
    id2 = ssID + "";
    if (p <= 100) {
        var totRec = Math.ceil(parseInt(document.getElementById(id1).innerText) / 100 * p);
        document.getElementById(id2).innerText = totRec + "";
    } else {
        alert("Sample Percentage can not be more than 100");
        document.getElementById(obj.id).value = 100;
    }

    /*  if(parent.up_flag == 'new')
         sample(); */

}

function ssCal(obj, spID, sp) {
    var p = obj.value;
    id1 = spID + "";
    id2 = sp + "";
    var ssRecords;
    if (document.getElementById(id1).tagName == 'INPUT')
        ssRecords = parseInt(document.getElementById(id1).value);
    else
        ssRecords = parseInt(document.getElementById(id1).innerText);
    if (p <= ssRecords) {
        var PR = ((p * 100) / ssRecords).toFixed(2);
        noRec = Math.ceil((PR / 100) * ssRecords.toFixed(2));
        document.getElementById(obj.id).value = noRec;
        document.getElementById(id2).innerText = PR + "";
    } else {
        alert("Sample Size can not be more than Universe");
        document.getElementById(obj.id).value = ssRecords;
    }


}

function changeSampleEG(obj) {
    var noCG = document.getElementById('cmbnogroup').value;
    var SC = document.getElementById('cmbSC').value;
    var ssID = "ssID" + parseInt(obj.id.substring(4, 6), 10);
    var totalRec;
    if (document.getElementById(ssID).tagName == 'INPUT')
        totalRec = parseInt(document.getElementById(ssID).value);
    else
        totalRec = parseInt(document.getElementById(ssID).innerText);
    var universe;
    var flag = 0;
    if (SC == 'cmbNR') {
        if (obj.value > totalRec) {
            alert("Sample can not be more than Universe");
            flag = 1;
            universe = totalRec;
        }
    } else if (SC == 'cmbPU') {
        if (obj.value > 100) {
            alert("Sample size can not be more than 100");
            flag = 1;
            universe = 100;
        }
    }
    if (flag == 1) {
        var oldvalue = 0;
        for (var i = 1; i <= noCG; i++) {
            id = parseInt(obj.id.substring(7, 9)) + i;
            if (id < 10)
                id = "0" + id;
            oldvalue += parseInt(document.getElementById('list' + obj.id.substring(4, 6) + id).innerText);
        }

        oldvalue = universe - oldvalue;
        document.getElementById(obj.id).value = oldvalue;
    } else {
        if (SC == 'cmbNR') {
            var CustRec = obj.value;
            var CustPer = (obj.value / totalRec) * 100;
            var ren = Math.round((totalRec - CustRec) / noCG);      // same for both
            var renPer = Math.round((totalRec - CustRec) / noCG);
        } else {
            var CustPer = obj.value;
            var CustRec = Math.round((CustPer / 100) * totalRec);
            var renPer = Math.round((100 - CustPer) / noCG);
            var ren = Math.round((renPer / 100) * totalRec);
        }
        document.getElementById('rec' + obj.id.substring(4)).innerText = CustRec + "";
        var RT = 0; // Record Total
        var PT = 0;  //Percentage Total
        RT = RT + parseInt(CustRec);

        PT = PT + parseInt(CustPer);

        for (var i = 1; i <= noCG; i++) {
            id = parseInt(obj.id.substring(7, 9)) + i;
            if (id < 10)
                id = "0" + id;
            if (i < noCG) {
                RT = RT + ren;
                PT = PT + renPer;
                document.getElementById('list' + obj.id.substring(4, 6) + id).innerText = renPer + "";
                document.getElementById('rec' + obj.id.substring(4, 6) + id).innerText = ren + "";
            } else {
                ren = totalRec - RT;
                if (SC == 'cmbPU')
                    renPer = 100 - PT;
                else
                    renPer = ren;

                document.getElementById('list' + obj.id.substring(4, 6) + id).innerText = renPer + "";
                document.getElementById('rec' + obj.id.substring(4, 6) + id).innerText = ren + "";
            }
        }
    }
}//Function changeSampleEG

function changeSampleUG(obj) {

    var totRec;
    var SC = document.getElementById('cmbSC').value;
    var ssID = "ssID" + parseInt(obj.id.substring(4, 6), 10);
    var totRec;
    if (document.getElementById(ssID).tagName == 'INPUT')
        totRec = parseInt(document.getElementById(ssID).value);
    else
        totRec = parseInt(document.getElementById(ssID).innerText);
    var custPer = parseInt(obj.value);
    //  alert(custPer);
    if (SC == 'cmbPU') {
        if (custPer < 100) {
            var custRec = Math.round((custPer / 100) * totRec);
            //		alert(custRec);
            document.getElementById('rec' + obj.id.substring(4)).innerText = custRec + "";
        } else {
            alert("Sample size can not be more than 100");
            obj.value = '';
            return;
        }
    } else {
        if (custPer < totRec) {
            document.getElementById('rec' + obj.id.substring(4)).innerText = custPer + "";
        } else {
            alert("Sample size can not be more than Total Universe");
            obj.value = '';
            return;
        }

        // Have to do
    }
    /*  if(SC == 'cmbPU')
   {
     var  custRec=Math.round((custPer/100)*totRec);
     document.getElementById('rec'+obj.id.substring(4)).innerText=custRec+"";
   }
   else
       {
	var  custRec=obj.value;
	document.getElementById('rec'+obj.id.substring(4)).innerText=custRec+"";
       } */
}

function sample() {

    /*********************** 2017-10-12 show loaiding in List Segment Details section Start ******************/
    strHTML1 = '<table style="width:950px" id="halfseg" class="c1"><tr><td height="10px"></td></tr><tr><td style="width:200px">List and Campaign Details &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td><td style="font-weight: 400;" >List Segment Description</td><td style="font-weight: 400;" >Campaign Group Description</td><td style="font-weight: 400;" width="110px">Sample % by List</td><td style="font-weight: 400;">Sample Size</td></tr><tr><tr><td height="10px"></td><td></td></tr><tr><td></td><td><label class="l" >Please wait,Loading...</label></td><td><label style="width:200px" class="l"></label></td><td><label class="l1" ></label></td><td><label class="l1" ></label></td></tr></tr></table>';
//strHTML1='<table style="width:890px" class="c1"><tr><td height="10px"></td></tr><tr><td style="width:200px">List and Campaign Details &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></div></td><td>Please wait...Loading List Segment Details content.</td></tr></table>';
    document.getElementById('divCG4').innerHTML = strHTML1;
    document.getElementById('divCG4').style.display = 'none';
    /*********************** 2017-10-12 show loaiding in List Segment Details section End ******************/

    setTimeout(function () {
        var x = true;
        var index = 1;
        //cmbSD,cmbSC
        var prvrslt = document.getElementById('prvrslt').value;
        var SD = document.getElementById('cmbSD').value;
        var SC = document.getElementById('cmbSC').value;
        var noCG = document.getElementById('cmbnogroup').value;
        var clk = document.getElementById('chkgroup').value;
        var div4 = document.getElementById('divCG4');
        var SS = new Array();
        var CG = new Array();
        var ListSegDes = new Array();
        var part, row = "", strHTML = "", strHTML1 = "";

        if ((SD != '') && (SC != '')) {
            while (x) {
                //alert(x);
                id = 'ssID' + index;
                id1 = 'ld' + index;
                if (document.getElementById(id)) {
                    if (document.getElementById(id).tagName == 'INPUT')
                        SS[index] = document.getElementById(id).value;  //Text box
                    else
                        SS[index] = document.getElementById(id).innerText;    //label

                    ListSegDes[index] = document.getElementById(id1).value;

                } else
                    x = false;
                index = index + 1;
            } // while (x)
            if (clk == 'Y') {
                var GID = 0;
                part = parseInt(noCG) + 1;
            } else {
                var GID = 1;
                part = noCG;
            }
            for (var i = GID; i <= noCG; i++) {
                console.log(i + '---' + noCG);
                if (document.getElementById('cGDis' + i).value != 'cGDisCust')
                    CG[i] = document.getElementById('cGDis' + i).value;
                else if (document.getElementById('cGDisCust' + i).innerText == '----')
                    CG[i] = '';
                else
                    CG[i] = document.getElementById('cGDisCust' + i).innerText;
            }


            if (SC == 'cmbPU')
                strHTML = '<table style="width:1050px" id="halfseg" class="c1"><tr><td height="10px"></td></tr><tr><td style="width:179px">List and Campaign Details &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td><td width="20px"></td><td style="font-weight: 400;" >List Segment Description</td><td style="font-weight: 400;" >Campaign Group Description</td><td style="font-weight: 400;" width="110px">Sample % by List</td><td style="font-weight: 400;">Sample Size</td></tr><tr><tr><td height="10px"></td><td></td></tr>';
            else
                strHTML = '<table  style="width:1050px" id="halfseg" class="c1"><tr><td height="10px"></td></tr><tr><td style="width:179px">List and Campaign Details &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td><td width="20px"></td><td style="font-weight: 400;" >List Segment Description &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td><td style="font-weight: 400;" >Campaign Group Description</td><td style="font-weight: 400;" width="110px" >Sample # By List</td><td style="font-weight: 400;">Sample Size</td></tr><tr><tr><td height="10px"></td><td></td></tr>';
            var pRec = Math.round(100 / part);
            old_pRec = pRec;
            //Sample = resArray[10].split("^");;

            //SSize = Sample[0].split(":");
            //SRec = Sample[1].split(":");
            //alert(SSize+'--'+index);
            var pRec = 95;
            old_pRec = 5;

            for (var j = 1; j < index - 1; j++) {
                total = 0;
                totalRec = 0;
                pRec = old_pRec;

                var noRec = Math.round((SS[j] / 100) * pRec);
                // alert(noRec);
                for (i = GID; i <= noCG; i++) {

                    // Get ID
                    if (i < 10)
                        id1 = "0" + i; else id1 = i + "";
                    if (j < 10)
                        id2 = "0" + j; else id2 = j + "";
                    id = id2 + id1;
                    // Get ID

                    total += pRec;
                    totalRec += noRec;
                    if (i == noCG) {
                        old_pRec = pRec;
                        pRec = (100 - total) + pRec;
                        noRec = SS[j] - totalRec + noRec;

                    }
                    if (prvrslt == 'noprvw')  //  2013
                    {
                        if (SC == 'cmbPU') {
                            switch (SD) {
                                case 'cmbAEG':
                                    row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l" >' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label></td><td><label class="l1" style="margin-left: 9px" id=list' + id + ' >' + pRec + '</label></td><td><label class="l1" id=rec' + id + ' ></label></td></tr>';

                                    break;
                                case 'cmbEPG':
                                    if (i == 0)
                                        row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l">' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label></td><td><input type="text" id=list' + id + ' value=' + pRec + ' onChange="changeSampleEG(this)" style="width:75px" class="form-control form-control-sm" onkeypress="return numeric_only(event);" /></td><td><label class="l1" id=rec' + id + ' ></label></td></tr>';
                                    else
                                        row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l" >' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label></td><td><label class="l1" style="margin-left: 9px" id=list' + id + ' >' + pRec + '</label></td><td><label class="l1" id=rec' + id + ' ></label></td></tr>';
                                    break;
                                case 'cmbUG':
                                    row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l" >' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label></td><td><input type="text" value=' + pRec + ' id=list' + id + ' onChange="changeSampleUG(this)" style="width:75px" class="form-control form-control-sm" onkeypress="return numeric_only(event);" /></td><td><label class="l1" id=rec' + id + ' ></label></td></tr>';
                                    break;
                            } //switch
                        }  // If
                        else {
                            switch (SD) {
                                case 'cmbAEG':
                                    row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l" >' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label></td><td><label class="l1" style="margin-left: 9px" id=list' + id + ' >' + noRec + '</label></td><td><label class="l1"  id=rec' + id + ' ></label></td></tr>';
                                    break;
                                case 'cmbEPG':
                                    if (i == 0)
                                        row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l" >' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label></td><td><input type="text" id=list' + id + ' value=' + noRec + ' onChange="changeSampleEG(this)"  style="width:75px" class="form-control form-control-sm" onkeypress="return numeric_only(event);" /></td><td><label class="l1" id=rec' + id + ' ></label></td></tr>';
                                    else
                                        row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l" >' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label class="l1"></td><td><label class="l1" style="margin-left: 9px" id=list' + id + ' >' + noRec + '</td><td><label class="l1" id=rec' + id + ' ></label></td></tr>';
                                    break;
                                case 'cmbUG':
                                    row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l" >' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label class="l1" ></td><td><input type="text" id=list' + id + ' value=' + noRec + ' onChange="changeSampleUG(this)" style="width:75px" class="form-control form-control-sm" onkeypress="return numeric_only(event);" /></td><td><label  class="l1" id=rec' + id + ' ></label></td></tr><tr>';
                            } //switch
                        }
                    }  //  2013
                    else {
                        if (SC == 'cmbPU') {
                            switch (SD) {
                                case 'cmbAEG':
                                    row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l" >' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label></td><td><label class="l1" style="margin-left: 9px" id=list' + id + ' >' + pRec + '</label></td><td><label class="l1" id=rec' + id + ' >' + noRec + '</label></td></tr>';

                                    break;
                                case 'cmbEPG':
                                    if (i == 0)
                                        row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l">' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label></td><td><input type="text" id=list' + id + ' value=' + pRec + ' onChange="changeSampleEG(this)" style="width:75px" class="form-control form-control-sm" onkeypress="return numeric_only(event);" /></td><td><label class="l1" id=rec' + id + ' >' + noRec + '</label></td></tr>';
                                    else
                                        row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l" >' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label></td><td><label class="l1" style="margin-left: 9px" id=list' + id + ' >' + pRec + '</label></td><td><label class="l1" id=rec' + id + ' >' + noRec + '</label></td></tr>';
                                    break;
                                case 'cmbUG':
                                    row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l" >' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label></td><td><input type="text" value=' + pRec + ' id=list' + id + ' onChange="changeSampleUG(this)" style="width:75px" class="form-control form-control-sm" onkeypress="return numeric_only(event);" /></td><td><label class="l1" id=rec' + id + ' >' + noRec + '</label></td></tr>';
                                    break;
                            } //switch
                        }  // If
                        else {
                            switch (SD) {
                                case 'cmbAEG':
                                    row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l" >' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label></td><td><label class="l1" style="margin-left: 9px" id=list' + id + ' >' + noRec + '</label></td><td><label class="l1"  id=rec' + id + ' >' + noRec + '</label></td></tr>';
                                    break;
                                case 'cmbEPG':
                                    if (i == 0)
                                        row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l" >' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label></td><td><input type="text" id=list' + id + ' value=' + noRec + ' onChange="changeSampleEG(this)"  style="width:75px" class="form-control form-control-sm" onkeypress="return numeric_only(event);" /></td><td><label class="l1" id=rec' + id + ' >' + noRec + '</label></td></tr>';
                                    else
                                        row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l" >' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label class="l1"></td><td><label class="l1" style="margin-left: 9px" id=list' + id + ' >' + noRec + '</td><td><label class="l1" id=rec' + id + ' >' + noRec + '</label></td></tr>';
                                    break;
                                case 'cmbUG':
                                    row += '<td></td><td width="20px"><label class="l">'+j+'</label></td><td><label class="l" >' + ListSegDes[j] + '</label></td><td><label style="width:200px" class="l">' + CG[i] + '</label class="l1" ></td><td><input type="text" id=list' + id + ' value=' + noRec + ' onChange="changeSampleUG(this)" style="width:75px" class="form-control form-control-sm" onkeypress="return numeric_only(event);" /></td><td><label  class="l1" id=rec' + id + ' >' + noRec + '</label></td></tr><tr>';
                            } //switch
                        }//else
                    } // esle  for preview result
                }
            }
            strHTML += row + "</table>";
            div4.innerHTML = '';
            div4.innerHTML = strHTML;
            div4.style.display = 'none';

            $('#savebottom').attr('disabled',false);
            $('#save').attr('disabled',false);
        } //if((SD != '')&&(SC != ''))
    }, 4000);


}

function disableEle() {
    // var f = document.getElementsByTagName('input');
    var f = document.getElementsByTagName('input');
    for (var i = 0; i < f.length; i++) {
        if (f[i].getAttribute('type') == 'text') {
            f[i].setAttribute('disabled', true)
        }
    }
    var f = document.getElementsByTagName('select');
    for (var i = 0; i < f.length; i++) {
        f[i].setAttribute('disabled', true)
    }
    if (parent.up_flag == 'view') {
        document.getElementById('save').setAttribute('disabled', true);
        document.getElementById('savebottom').setAttribute('disabled', true);
        //document.getElementById('clear').setAttribute('disabled', true);
        //document.getElementById('btnGo').setAttribute('disabled', true);
        /* var d = document.getElementById('btnSpan');
		var clear = document.getElementById('clear');
		d.removeChild(clear); */

    } else {
        document.getElementById('savebottom').innerText = 'Update';
        //document.getElementById('clear').setAttribute('disabled', true);
        //document.getElementById('btnGo').setAttribute('disabled', true);
        var lseg = document.getElementById('txtnogrps').value;
        if (document.getElementById('cmbDFS').value == 'byfield')
            lseg = noRows;
        //alert(noRows);
        for (var i = 1; i <= lseg; i++)
            document.getElementById('ld' + i).setAttribute('disabled', false);
        document.getElementById('ld1').setAttribute('disabled', false);
        var c2 = document.getElementById('cmbnogroup').value;
        var chk = document.getElementById('chkgroup').value;
        var gp;
        if (chk == 'Y')
            gp = 0;
        else
            gp = 1;

        for (var i = gp; i <= c2; i++) {
            document.getElementById('cCost' + i).setAttribute('disabled', false);
            document.getElementById('cGDis' + i).setAttribute('disabled', false);
            document.getElementById('cOffer' + i).setAttribute('disabled', false);
        }
    }

    /*for (var i = 0; i < document.frmcalc.elements.length; i++)
        document.frmcalc.elements[i].disabled = false;
    YAHOO.csr.container.wait.hide();*/
}

function changeSample() {
    if ((parent.up_flag == 'update') || (parent.up_flag == 'new'))
        sample();
    /*
            if (parent.up_flag == 'update') {
                disableEle();
            }*/
//sample();

}

function ViewSample() { //alert('here');
    var sampleDiv = document.getElementById('divCG4');
    var strHTML = "", strHTML1 = "", row = "", k = -1;

    if (res.seg_camp_grp_sel_cri == 'cmbPU')
        strHTML = '<table style="width:890px" class="c1"><tr><td height="10px"></td></tr><tr><td style="width:200px">List and Campaign Details &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td><td style="font-weight: 400;" >List Segment Description</td><td style="font-weight: 400;" >Campaign Group Description</td><td style="font-weight: 400;" width="110px">Sample % by List</td><td style="font-weight: 400;">Sample Size</td></tr><tr><tr><td height="10px"></td><td></td></tr>';
    else
        strHTML = '<table  style="width:890px"  class="c1"><tr><td height="10px"></td></tr><tr><td style="width:200px">List and Campaign Details &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td><td style="font-weight: 400;" >List Segment Description</td><td style="font-weight: 400;" >Campaign Group Description</td><td style="font-weight: 400;" width="110px" >Sample # By List</td><td style="font-weight: 400;">Sample Size</td></tr><tr><tr><td height="10px"></td><td></td></tr>';

    if (res.seg_ctrl_grp_opt == 'Y')
        groupid = 0;
    else
        groupid = 1;
    CGArrayStr = (res.seg_camp_grp_dtls).split("^")[0];
    CGArray = CGArrayStr.split(":");
    listSegDetails = (res.seg_selected_criteria).split("^");
    listDis = listSegDetails[1].split(":");
    Sample = (res.seg_sample).split("^");
    ;
    SSize = Sample[0].split(":");
    SRec = Sample[1].split(":");
    //SRec =  document.getElementById('ssID1').value;
    for (var j = 1; j <= noRows; j++) {
        for (var i = groupid; i <= res.seg_grp_no; i++) {
            if ((i + "").length == 1)
                id1 = "0" + i; else id1 = i + "";
            if ((j + "").length == 1)
                id2 = "0" + j; else id2 = j + "";
            id = id2 + id1;
            k = k + 1;
            if (res.seg_camp_grp_sel_cri == 'cmbPU') {
                switch (res.seg_camp_grp_proportion) {
                    case 'cmbAEG':
                        row += '<td></td><td><label class="l" >' + listDis[j] + '</label></td><td><label style="width:200px" class="l">' + CGArray[i] + '</label></td><td><label class="l1" id=list' + id + ' >' + SSize[k] + '</label></td><td><label class="l1" id=rec' + id + ' >' + SRec[k] + '</label></td></tr>';

                        break;
                    case 'cmbEPG':
                        if (i == 0)
                            row += '<td></td><td><label class="l">' + listDis[j] + '</label></td><td><label style="width:200px" class="l">' + CGArray[i] + '</label></td><td><input type="text" id=list' + id + ' value=' + SSize[k] + ' onChange="changeSampleEG(this)" style="width:75px" class="form-control form-control-sm" /></td><td><label class="l1" id=rec' + id + ' >' + SRec[k] + '</label></td></tr>';
                        else
                            row += '<td></td><td><label class="l" >' + listDis[j] + '</label></td><td><label style="width:200px" class="l">' + CGArray[i] + '</label></td><td><label class="l1" id=list' + id + ' >' + SSize[k] + '</label></td><td><label class="l1" id=rec' + id + ' >' + SRec[k] + '</label></td></tr>';
                        break;
                    case 'cmbUG':
                        row += '<td></td><td><label class="l" >' + listDis[j] + '</label></td><td><label style="width:200px" class="l">' + CGArray[i] + '</label></td><td><input type="text" value=' + SSize[k] + ' id=list' + id + ' onChange="changeSampleUG(this)" style="width:75px" class="form-control form-control-sm" /></td><td><label class="l1" id=rec' + id + ' >' + SRec[k] + '</label></td></tr>';
                        break;
                } //switch
            }  // If
            else {
                switch (res.seg_camp_grp_proportion) {
                    case 'cmbAEG':
                        row += '<td></td><td><label class="l" >' + listDis[j] + '</label></td><td><label style="width:200px" class="l">' + CGArray[i] + '</label></td><td><label class="l1" id=list' + id + ' >' + SSize[k] + '</label></td><td><label class="l1"  id=rec' + id + ' >' + SRec[k] + '</label></td></tr>';
                        break;
                    case 'cmbEPG':
                        if (i == 0)
                            row += '<td></td><td><label class="l" >' + listDis[j] + '</label></td><td><label style="width:200px" class="l">' + CGArray[i] + '</label></td><td><input type="text" id=list' + id + ' value=' + SSize[k] + ' onChange="changeSampleEG(this)"  style="width:75px" class="form-control form-control-sm" /></td><td><label class="l1" id=rec' + id + ' >' + SRec[k] + '</label></td></tr>';
                        else
                            row += '<td></td><td><label class="l" >' + listDis[j] + '</label></td><td><label style="width:200px" class="l">' + CGArray[i] + '</label class="l1"></td><td><label class="l1" id=list' + id + ' >' + SSize[k] + '</td><td><label class="l1" id=rec' + id + ' >' + SRec[k] + '</label></td></tr>';
                        break;
                    case 'cmbUG':
                        row += '<td></td><td><label class="l" >' + listDis[j] + '</label></td><td><label style="width:200px" class="l">' + CGArray[i] + '</label class="l1" ></td><td><input type="text" id=list' + id + ' value=' + SSize[k] + ' onChange="changeSampleUG(this)" style="width:75px" class="form-control form-control-sm" /></td><td><label  class="l1" id=rec' + id + ' >' + SRec[k] + '</label></td></tr><tr>';
                } //switch
            }//else
        }
    }
    //alert('here');
    strHTML += row + "</table>";
    sampleDiv.innerHTML = '';
    sampleDiv.innerHTML = strHTML;
    sampleDiv.style.display = 'none';
}

function ViewSelectionCriteria() {

    $.ajax({
        url : 'model/getcol',
        type : 'POST',
        data : {
            _token : $('[name="_token"]').val(),
            sSQL : params.sSQL
        },
        success : function (response){
            if (response !== undefined) {
                var y = document.getElementById("txtnogrps");
                var x = document.getElementById("divLs2");
                opt1 = '<option value=" "></option>', opt2 = '<option value=" "></option>', opt3 = '<option value=" "></option>';
                var colarray = response.aData;
                //var colarray = colstr.split(",");
                //x.innerHTML="";
                for (var j = 0; j < colarray.length; j++) {
                    opt1 += '<option value=' + colarray[j] + '>' + colarray[j] + '</option>';
                }
                for (var j = 0; j < optarray.length; j++) {
                    var opVal, opDis;
                    if (j >= 8) {
                        var rem = parseInt(j) - 8;
                        if (rem == 0) {
                            var opVal = '8';
                            var opDis = 'Contains';

                        } else if (rem == 1) {
                            var opVal = '8.1';
                            var opDis = 'Start with';
                        } else if (rem == 2) {
                            var opVal = '8.2';
                            var opDis = 'End with';
                        } else if (rem == 3) {
                            var opVal = '9';
                            var opDis = 'Doesn\'t Contains';
                        } else if (rem == 4) {
                            var opVal = '9.1';
                            var opDis = 'Doesn\'t start with';
                        } else if (rem == 5) {
                            var opVal = '9.2';
                            var opDis = 'Doesn\'t end with';
                        }
                        if (opVal != "" && opDis != "") { //console.log('if-'+j+'=='+opVal);
                            opt2 += '<option style="height: 35px;" value=' + opVal + '>' + opDis + '</option>';
                        }
                    } else {	//console.log('else-'+j+'=='+optarray[j]);
                        opt2 += '<option style="height: 35px;" value=' + j + '>' + optarray[j] + '</option>';
                    }
                }
                for (var j = 0; j < logarray.length; j++) {
                    opt3 += '<option value=' + logarray[j] + '>' + logarray[j] + '</option>';
                }


                yrow = '<table   class="c1" >';
                yrow += '<tr><td colspan="3" style="width:200px">List Segment Selection Criteria &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td></tr>';

                for (var i = 1; i <= y.value; i++) {
                    name1 = +i;

                    yrow += '<tr>';

                    yrow += '<td></td><td width="175px"></td><td  width="20px"><label>' + i + '</label></td><td><select value=">" class="t1" id=' + name11 + i + ' name=' + name11 + i + '>' + opt1 + '<select></td><td><select id=' + name12 + i + ' name=' + name12 + i + '>' + opt2 + '<select></td><td><input type="text" id=' + name13 + i + ' name="' + name13 + i + '" /></td><td><select id=' + name14 + i + ' name=' + name14 + i + '>' + opt3 + '<select></td>';

                    yrow += '<td><select  class="t1" id=' + name21 + i + ' name=' + name21 + i + '>' + opt1 + '<select></td><td><select id=' + name22 + i + ' name=' + name22 + i + '>' + opt2 + '<select></td><td><input type="text" id=' + name23 + i + ' name="' + name23 + i + '" /></td><td><select id=' + name24 + i + ' name=' + name24 + i + '>' + opt3 + '<select></td>';

                    yrow += '<td><select class="t1" id=' + name31 + i + ' name=' + name31 + i + '>' + opt1 + '<select></td><td><select id=' + name32 + i + ' name=' + name32 + i + '>' + opt2 + '<select></td><td><input type="text" name="' + name33 + i + '" id="' + name33 + i + '"/></td><td>';
                }
                yrow += '</table>';

                // x.innerHTML +=yrow;

                x.style.display = "block";

                cri = resArray[3].split("^");


                ccol = cri[0].split(":");
                op = cri[1].split(":");
                val = cri[2].split(":");
                log = cri[3].split(":");


                //Remove Zero Index
                ccol.shift();
                op.shift();
                val.shift();
                log.shift();
                //Remove Zero Index
                var k = 0;
                k = 0;
                $('#numRows').val(resArray[1]);
                for (var i = 1; i <= resArray[1]; i++) {
                    var ids = (i * 10) + 1;
                    var newRowIds = parseInt(ids) + 10;
                    var newSecIds1 = parseInt(ids) + 10;
                    var newSecIds2 = parseInt(ids) + 11;
                    var newSecIds3 = parseInt(ids) + 12;
                    //alert('RowID ='+newRowIds+'---Sec 1 = '+newSecIds1+'--Sec2 = '+newSecIds2+"---sec 3 = "+newSecIds1);
                    $('#row_' + ids).after('<div class="divTableRow" id="row_' + newRowIds + '"><div style="width:6%" class="divTableCell"><input type="hidden" id="countSec_' + newRowIds + '" value="0" /></div><div style="width:1%; text-align:right !important;" id="preCross_' + newRowIds + '" onmouseover="showCross(' + newRowIds + ');" onmouseout="hideCross(' + newRowIds + ');" class="divTableCell" ></div><div style="width:2%;font-size: 10px;text-align:center;" class="divTableCell" id="plusDiv_' + newSecIds1 + '"><a onclick="addSectionNew(' + newRowIds + ',' + newRowIds + ',0);" href="javascript:void(0);"><img width="15" height="14"   src="images/add_v3.png"></img></a></div><div class="divTableCell" style="width:7%" id="ccolCell_' + newSecIds1 + '"></div><div style="width:4%" class="divTableCell" id="opCell_' + newSecIds1 + '"></div><div style="width:5%" class="divTableCell" id="valCell_' + newSecIds1 + '"></div><div style="width:1%;text-align: center;font-size: 8px;" class="divTableCell" id="plusCell_' + newSecIds2 + '"></div><div class="divTableCell" style="width:7%" id="ccolCell_' + newSecIds2 + '"></div><div style="width:4%" class="divTableCell" id="opCell_' + newSecIds2 + '"></div><div style="width:5%" class="divTableCell" id="valCell_' + newSecIds2 + '"></div><div style="width:1%;text-align: center;font-size: 8px;" class="divTableCell" id="plusCell_' + newSecIds3 + '"></div><div class="divTableCell" style="width:7%" id="ccolCell_' + newSecIds3 + '"></div><div style="width:4%" class="divTableCell" id="opCell_' + newSecIds3 + '"></div><div style="width:5%" class="divTableCell" id="valCell_' + newSecIds3 + '"></div></div>');
                    for (var j = 1; j <= 3; j++) {
                        /*************** new code to get filter values 2017-09-28 start ****************************/

                        if (ccol[k] != "") {


                            $('#ccolCell_' + ids).html('<select style="width:100%;" onchange="getCol(this.value);" class="t1" name="ccol' + ids + '" id="ccol' + ids + '" value=">">' + opt1 + '</select>');


                            $('#opCell_' + ids).html('<select style="width:100%;" id="op' + ids + '" name="op' + ids + '">' + opt2 + '</select>');

                            $('#valCell_' + ids).html('<input style="width:100%;" id="val' + ids + '" name="val' + ids + '" type="text">');

                            document.getElementById('ccol' + ids).value = ccol[k];
                            document.getElementById('op' + ids).value = op[k];
                            document.getElementById('val' + ids).value = val[k];

                            var nextIds = parseInt(ids) + 1;

                            if (j == 1) {
                                var rowIds = ids;
                                if (ids == 11) {
                                    $('#plusDiv_' + rowIds).html('');
                                } else {
                                    $('#plusDiv_' + rowIds).text('AND');
                                }
                                $('#preCross_' + rowIds).html('<a onclick="removeSection(' + rowIds + ',' + ids + ');" href="javascript:void(0);"><img style="padding-top:2px;" width="10" height="10"   src="images/remove_v2.png"></img></a>');
                            } else {

                                $('#plusCell_' + ids).html('');
                                $('#plusCell_' + ids).text('OR');
                            }
                            var secIds = ids;

                            $('#plusCell_' + nextIds).html('<a onclick="addSectionNew(' + rowIds + ',' + nextIds + ',1);" href="javascript:void(0);"><img width="13" height="13"   src="images/add_v3.png"></img></a>');


                            ids++;
                        }
                        /*************** new code to get filter values 2017-09-28 end ****************************/


                        //if(j<3)
                        //document.getElementById('log'+j+i).value=log[k];
                        k++;
                    }


                }


            }
        }
    })
}

function SelectionCriteria() {
    $.ajax({
        url: 'model/getcol',
        type: 'POST',
        data:
            {
                'sSQL': params.sSQL,
                _token : $('[name="_token"]').val(),
            },
        success: function (response) {
            if (response !== undefined) {
                var y = document.getElementById("txtnogrps");
                var x = document.getElementById("divLs2");
                opt1 = '<option value=" "></option>', opt2 = '<option value=" "></option>', opt3 = '<option value=" "></option>';
                opt21 = '<option value=" "></option>'
                if (y.value > 0) {
                    var colarray = response.aData;


                    x.innerHTML = "";
                    for (var j = 0; j < colarray.length; j++) {
                        opt1 += '<option value=' + colarray[j] + '>' + colarray[j] + '</option>';
                    }
                    for (var j = 0; j < optarray.length; j++) {
                        opt2 += '<option value=' + j + '>' + optarray[j] + '</option>';
                    }
                    for (var j = 0; j < optarray.length; j++) {
                        if (optarray[j] != '=')
                            opt21 += '<option value=' + j + '>' + optarray[j] + '</option>';
                        else
                            opt21 += '<option selected value=' + j + '>' + optarray[j] + '</option>';
                    }
                    for (var j = 0; j < logarray.length; j++) {
                        opt3 += '<option value=' + logarray[j] + '>' + logarray[j] + '</option>';
                    }
                    row = '<table   class="c1">';
                    row += '<tr><td colspan="3" style="width:200px">List Segment Selection Criteria &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td></tr>';

                    for (var i = 1; i <= y.value; i++) {
                        name1 = +i;

                        row += '<tr>';
                        if (i == 1)
                            row += '<td></td><td width="175px"></td><td  width="20px"><label>' + i + '</label></td><td><select value=">" class="t1" name=' + name11 + i + '>' + opt1 + '<select></td><td><select name=' + name12 + i + '>' + opt21 + '<select></td><td><input type="text" id=' + name13 + i + ' name="' + name13 + i + '"/></td><td><select name=' + name14 + i + '>' + opt3 + '<select></td>';
                        else
                            row += '<td></td><td width="175px"></td><td  width="20px"><label>' + i + '</label></td><td><select value=">" class="t1" name=' + name11 + i + '>' + opt1 + '<select></td><td><select name=' + name12 + i + '>' + opt2 + '<select></td><td><input type="text" id=' + name13 + i + ' name="' + name13 + i + '" /></td><td><select name=' + name14 + i + '>' + opt3 + '<select></td>';

                        row += '<td><select  class="t1" name=' + name21 + i + '>' + opt1 + '<select></td><td><select  name=' + name22 + i + '>' + opt2 + '<select></td><td><input type="text" id=' + name23 + i + ' name="' + name23 + i + '" /></td><td><select name=' + name24 + i + '>' + opt3 + '<select></td>';
                        row += '<td><select class="t1" name=' + name31 + i + '>' + opt1 + '<select></td><td><select name=' + name32 + i + '>' + opt2 + '<select></td><td><input type="text" id=' + name33 + i + ' name="' + name33 + i + '" /></td><td>';
                        //row+='<tr><tr><td height="10px"></td></tr>';

                    }
                    row += '</table>';

                    x.innerHTML += row;

                    x.style.display = "block";
                    document.getElementById('tdLs5').style.display = 'block';
                    return (row);
                } else {


                    x.style.display = "none";
                    document.getElementById('tdLs5').style.display = 'none';

                }
            }
        }
    });
    // document.getElementById('cmbDFS').setAttribute('disabled',true)//Disable//
}

function defineList(obj) {

    switch (obj.value) {

        case 'byfield':
            // document.getElementById('divLs1').style.display = 'none';
            document.getElementById('divLs2').style.display = 'none';
            byField(document.getElementById('cmbNoFields'));
            break;

        case 'custom':
            //document.getElementById('divLs1').style.setAttribute('display', 'block');
            document.getElementById('divLs2').style.display = 'block';
            document.getElementById('divByField').style.display = 'none';
            document.getElementById('divByFieldCol').style.display = 'none';

            break;

        case 'none':
            //document.getElementById('divLs1').style.display = 'none';
            document.getElementById('divByField').style.display = 'none';
            document.getElementById('tdLs5').style.display = 'block';
            //alert('here');
            $('#tdLs5').show();
            $('#divByFieldCol').hide();
            $('#divLs2').hide();
            document.getElementById('divByFieldCol').style.display = 'none';
            document.getElementById('divLs2').style.display = 'none';

            break;
    }

    var f = new Array('cmbNoFields', 'txtnogrps', 'cmbLSM', 'cmbnogroup', 'cmbSC', 'cmbSD', 'cmbLSM');
    for (var i = 0; i < f.length; i++)
        document.getElementById(f[i]).value = '';


    document.getElementById('cmbNoFields').value = 1;
    document.getElementById('cmbnogroup').value = 0;
    document.getElementById('chkgroup').value = 'Y';

    //parent.oldcampclk == 'N';


    var f = new Array("divCG1", "divCG2", "divCG3", "divCG4", "LsSQL", "divByFieldCol", "tdAction");
    for (var i = 0; i < f.length; i++) {
        // alert(f[i].id);
        document.getElementById(f[i]).style.display = 'none';

        //doit Here
    }


}

function ListSegDetails() {

    var PrevResilt = document.getElementById('prvrslt').value;

    if (PrevResilt == 'noprvw') {
        //alert('no preview');

        //document.getElementById('c1').style.display='none';
        // document.getElementById('tdAction').style.display="none";
        // document.getElementById('halfseg').style.display="none";


        var DFS = document.getElementById('cmbDFS').value;
        var listdis = document.getElementById('LsSQL');

        var sql = params.sSQL;
        var ListSegCri = new Array();
        var ListSegDes = new Array();
        var ZeroRec = 0, index = 1;
        if (DFS == 'custom') {
            noRows = document.getElementById("txtnogrps").value;
            var WhereArray = [];
            var str = '';
            var result = customFiltersCondition();

            var k = 0;
            for (var i = 0; i < result.where.length; i++) {
                if (result.where[i] != "") {
                    var n = result.where[i].indexOf("OR");
                    if (n > 0) {
                        WhereArray[k] = " (" + result.where[i] + ") ";
                    } else {
                        WhereArray[k] = "" + result.where[i];
                    }
                    k++;
                }
                //else
                //WhereArray[i] = "NULL";
            }
            // console.log(WhereArray);

            if (WhereArray != "") {
                str = WhereArray.join(":WHERE:");
            } else {
                str = "";
            }
            console.log(str);
            var postData = {
                'pgaction' : 'countNopreview',
                'WhereArray' : str,
                'sSQL' : sql,
                '_token' : $('[name="_token"]').val()
            };
        }
        // DFS = 'custom'
        else if (DFS == 'byfield') {
            var colflag = 0;
            var colArray = new Array();
            var noColumn = document.getElementById('cmbNoFields').value;
            for (i = 1; i <= noColumn; i++) {

                colArray[i - 1] = document.getElementById('col' + i).value;

                if (document.getElementById('col' + i).value == '') {
                    colflag = 1;
                    break;
                }
            }
            if (colflag == 0) {
                var postData = {
                    'pgaction' : 'byFieldValueNopreview',
                    'sSQL' : sql,
                    'colName' : colArray,
                    '_token' : $('[name="_token"]').val()
                };

            } else {
                alert("Columns Should not be Empty");
            }
            // DFS = 'byfield'
        }
        else if (DFS == 'none') {
            var postData = {
                'pgaction' : 'getCountNopreview',
                'sSQL' : sql,
                '_token' : $('[name="_token"]').val()
            };
        }
        // DFS = 'none'
        $.ajax({
            url : 'model/lsdetails',
            type : 'POST',
            data : postData,
            success : function (response) {
                if (response !== undefined) {
                    var listdis = document.getElementById('LsSQL');
                    listdis.innerHTML = "";
                    var listdisHTML = "";
                    listdisHTML += '<table class="c1" id="c1" style="width:1005px" ><tr><td style="width:136px;">List Segment Details &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td><td style="width:20px;font-weight: 400;"></td><td style="width:10px;"></td><td style="font-weight: 400;">List Segment Selection Criteria</td><td></td><td style="font-weight: 400">List Segment Description</td><td style="font-weight: 400;width:88px">Universe</td><td style="font-weight: 400; width:60px">Sample %</td><td style="font-weight: 400;">Sample Size</td></tr><tr><td height="10px"></td></tr>';
                    var noRec = new Array();
                    if (DFS == 'custom') {
                        // Add 0: just to state index from 1 //
                        //noRec = ("0,"+o.responseText).split(",");

                        ListSegCri = ("W1," + WhereArray.join(",")).split(",");
                        temp = 1;
                        for (i = 0; i < noRows * 3; i = i + 3) {

                            ListSegDes[temp] = val[i]
                            temp = temp + 1;
                        }

                    }
                    else if (DFS == 'byfield') {
                        Str = ("0:" + response.str).split(":");

                        ListCon = "";
                        ListDetail_temp = response.str.split(":");
                        noRows = Str.length - 2;

                        for (i = 1; i < Str.length - 1; i++) {
                            temp = Str[i].split(":"); // 2013
                            //noRec[i] = temp.pop();
                            ListSegDes[i] = temp.join("_");
                            //alert(ListSegDes[i]); right
                        }
                        temp_C = ListDetail_temp[0].length;

                        //temp_C = temp_CC+1; // 2013
                        //alert(temp_C);
                        temp = 1;
                        for (i = 1; i <= noRows; i++) {
                            Con_flag = 0;
                            ListDetail = ListDetail_temp[i - 1].split(":"); //2013

                            for (j = 0; j < temp_C - 1; j++) {
                                if (j == 0) {
                                    ListCon += "( " + colArray[j] + "='" + $.trim(ListDetail[j]) + "' )";

                                } else {
                                    ListCon += " and ( " + colArray[j] + "='" + $.trim(ListDetail[j]) + "' )";
                                    Con_flag = 1;
                                }

                                if (Con_flag == 1)
                                    ListSegCri[i] = "( " + ListCon + " )";
                                else
                                    ListSegCri[i] = ListCon;

                            }

                            ListCon = "";
                        }

                    }
                    else if (DFS == 'none') {
                        //noRec[1] = o.responseText;
                        ListSegCri[1] = "None";  //Blank /****2017-10-12 ****/
                        ListSegDes[1] = "All";
                        noRows = 1;
                        //document.getElementById('btnGo').setAttribute('disabled', true);
                    }

                    row = "";
                    var LSSM = document.getElementById('cmbLSM').value;
                    var i = 1;
                    for (var j = 1; j <= noRows; j++) {
                        if (ZeroRec >= 0) {

                            AI = i + ZeroRec;
                            cid = "spID" + i;
                            coid = "ssID" + i;
                            sp = "sp" + i;

                            switch (LSSM)//Hema
                            {
                                case 'ranNum':
                                case 'topNum':
                                    row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td></td><td width="200px"><label class="txt" id=cri' + i + ' >' + ListSegCri[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input class="form-control form-control-sm" type="text"  id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '></label></td><td><label id=sp' + i + ' class="l1" style="width:50px">100</label></td><td width="100px"><Input type="text" class="form-control form-control-sm" onChange="ssCal(this,\'' + cid + '\',\'' + sp + '\')" style="width:100px" value="" id=' + coid + ' onkeypress="return numeric_only(event);" ></td></tr>';
                                    break;
                                case 'ranPer':
                                case 'topPer':
                                    row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td></td><td width="200px"><label class="txt" id=cri' + i + ' >' + ListSegCri[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input class="form-control form-control-sm" type="text"  id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '></label></td><td><Input type="text" value="100" id=sp' + i + ' class="form-control form-control-sm" onChange="calculate(this,\'' + cid + '\',\'' + coid + '\')" onkeypress="return numeric_only(event);" /></td><td width="100px"><label class="l1" id=' + coid + '></label></td></tr>';
                                    break;
                                case 'none':
                                    row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td></td><td width="200px"><label class="txt" id=cri' + i + ' >' + ListSegCri[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input class="form-control form-control-sm" type="text"  id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '></label></td><td><label id=sp' + i + ' class="l1" style="width:50px">100</label></td><td width="100px"><label class="l1" id=' + coid + '></label></td></tr>';
                                    break;
                            }
                            i++;
                        } else {
                            ZeroRec = ZeroRec + 1;
                        }
                    }

                    listdisHTML += row;
                    listdisHTML += '<tr><td height="10px"></td></tr><tr><td colspan="10"><hr></td></tr></table>';

                    listdis.innerHTML = listdisHTML;
                    listdis.style.display = 'block';
                    if (DFS == 'none')
                        document.getElementById('tdAction').style.display = 'none';
                    else
                        document.getElementById('tdAction').style.display = 'block';
                    /*if (noRows == 1)
                        document.getElementById('btnGo').setAttribute('disabled', true);
                    else
                        document.getElementById('btnGo').setAttribute('disabled', false);*/
                }
            }
        });
        //alert(postData);
        //For All Type


    }   // 2013
    else {
        var DFS = document.getElementById('cmbDFS').value;
        var listdis = document.getElementById('LsSQL');

        //var sql=document.Form1.sql.value;
        var sql = params.sSQL;

        var ListSegCri = new Array();
        var ListSegDes = new Array();
        var ZeroRec = 0, index = 1;
        if (DFS == 'custom') {
            noRows = document.getElementById("txtnogrps").value;
            var WhereArray = [];
            var str = '';
            var result = customFiltersCondition();

            var k = 0;
            for (var i = 0; i < result.where.length; i++) {
                if (result.where[i] != "") {
                    var n = result.where[i].indexOf("OR");
                    if (n > 0) {
                        WhereArray[k] = " (" + result.where[i] + ") ";
                    } else {
                        WhereArray[k] = "" + result.where[i];
                    }
                    k++;
                }
                //else
                //WhereArray[i] = "NULL";
            }
            // console.log(WhereArray);

            if (WhereArray != "") {
                str = WhereArray.join(":WHERE:");
            } else {
                str = "";
            }
            console.log(str);
            var postData = {
                'pgaction' : 'count',
                'WhereArray' : str,
                'sSQL' : sql,
                '_token' : $('[name="_token"]').val()
            };
        }
        // DFS = 'custom'
        else if (DFS == 'byfield') {
            var colflag = 0;
            var colArray = new Array();
            var noColumn = document.getElementById('cmbNoFields').value;
            for (i = 1; i <= noColumn; i++) {

                colArray[i - 1] = document.getElementById('col' + i).value;

                if (document.getElementById('col' + i).value == '') {
                    colflag = 1;
                    break;
                }
            }
            if (colflag == 0) {
                var postData = {
                    'pgaction':'byFieldValue',
                    'sSQL' : sql,
                    'colName' : colArray.join(','),
                    '_token' : $('[name="_token"]').val()
                };


            } else {
                alert("Columns Should not be Empty");
            }
            // DFS = 'byfield'
        } else if (DFS == 'none') {

            var postData = {
                'pgaction' : 'getCount',
                'sSQL' : sql,
                '_token' : $('[name="_token"]').val()
            };
        }
        // DFS = 'none'

        //For All Type
        $.ajax({
            url: 'model/lsdetails',
            type: 'POST',
            data: postData,
            success: function (response) {
                if (response !== undefined) {

                    var listdis = document.getElementById('LsSQL');
                    listdis.innerHTML = "";
                    var listdisHTML = "";
                    listdisHTML += '<table class="c1" id="c1" style="width:1005px" ><tr><td style="width:136px;">List Segment Details &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td><td style="width:20px;font-weight: 400;"></td><td style="width:10px;"></td><td style="font-weight: 400;">List Segment Selection Criteria</td><td></td><td style="font-weight: 400">List Segment Description</td><td style="font-weight: 400;width:88px">Universe</td><td style="font-weight: 400; width:60px">Sample %</td><td style="font-weight: 400;">Sample Size</td></tr><tr><td height="10px"></td></tr>';
                    var noRec = new Array();
                    if (DFS == 'custom') {
                        // Add 0: just to state index from 1 //
                        noRows = response.count.length;
                        var resStr = (response.count).join(',');
                        noRec = ("0," + resStr).split(",");
                        ListSegCri = ("W1#" + result.where.join("#")).split("#");
                        temp = 1;
                        for (i = 0; i < noRows * 3; i = i + 3) {
                            ListSegDes[temp] = val[i];
                            temp = temp + 1;
                        }

                    }
                    else if (DFS == 'byfield') {
                        Str = ("0:" + response.str).split(":");

                        ListCon = "";
                        ListDetail_temp = response.str.split(":");
                        noRows = Str.length - 2;
                        for (i = 1; i < Str.length - 1; i++) {
                            temp = Str[i].split(",");
                            noRec[i] = temp.pop();
                            ListSegDes[i] = temp.join("_");
                        }
                        temp_C = ListDetail_temp[0].split(",").length;

                        temp = 1;
                        for (i = 1; i <= noRows; i++) {
                            Con_flag = 0;
                            ListDetail = ListDetail_temp[i - 1].split(",");
                            for (j = 0; j < temp_C - 1; j++) {
                                if (j == 0) {
                                    ListCon += "( " + colArray[j] + "='" + $.trim(ListDetail[j]) + "' )";
                                } else {
                                    ListCon += " and ( " + colArray[j] + "='" + $.trim(ListDetail[j]) + "' )";
                                    Con_flag = 1;
                                }

                                if (Con_flag == 1)
                                    ListSegCri[i] = "( " + ListCon + " )";
                                else
                                    ListSegCri[i] = ListCon;

                            }

                            ListCon = "";
                        }

                    }
                    else if (DFS == 'none') {
                        noRec[1] = response.count;
                        ListSegCri[1] = "None";  //Blank /****2017-10-12 ****/
                        ListSegDes[1] = "All";
                        noRows = 1;
                        //document.getElementById('btnGo').setAttribute('disabled', true);
                    }

                    row = "";
                    var LSSM = document.getElementById('cmbLSM').value;
                    var i = 1;
                    for (var j = 1; j <= noRows; j++) {
                        if (noRec[j] > 0) {
                            AI = i + ZeroRec;
                            cid = "spID" + i;
                            coid = "ssID" + i;
                            sp = "sp" + i;

                            switch (LSSM)//Hema
                            {                           //ListSegDes for description
                                case 'ranNum':
                                case 'topNum':
                                    row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td></td><td width="200px"><label class="txt" id=cri' + i + ' >' + ListSegCri[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input class="form-control form-control-sm" type="text"   id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '>' + noRec[AI] + '</label></td><td><label id=sp' + i + ' class="l1" style="width:50px">100</label></td><td width="100px"><Input type="text" class="form-control form-control-sm" onChange="ssCal(this,\'' + cid + '\',\'' + sp + '\')" style="width:100px" value=' + noRec[AI] + ' id=' + coid + ' onkeypress="return numeric_only(event);" ></td></tr>';
                                    break;
                                case 'ranPer':
                                case 'topPer':
                                    row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td></td><td width="200px"><label class="txt" id=cri' + i + ' >' + ListSegCri[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input class="form-control form-control-sm" type="text"   id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '>' + noRec[AI] + '</label></td><td><Input type="text" value="100" id=sp' + i + ' class="form-control form-control-sm" onChange="calculate(this,\'' + cid + '\',\'' + coid + '\')" onkeypress="return numeric_only(event);" /></td><td width="100px"><label class="l1" id=' + coid + '>' + noRec[AI] + '</label></td></tr>';
                                    break;
                                case 'none':
                                    row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td></td><td width="200px"><label class="txt" id=cri' + i + ' >' + ListSegCri[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input class="form-control form-control-sm" type="text"   id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '>' + noRec[AI] + '</label></td><td><label id=sp' + i + ' class="l1" style="width:50px">100</label></td><td width="100px"><label class="l1" id=' + coid + '>' + noRec[AI] + '</label></td></tr>';
                                    break;
                            }
                            i++;
                        } else {
                            ZeroRec = ZeroRec + 1;
                        }
                    }

                    listdisHTML += row;
                    listdisHTML += '<tr><td height="10px"></td></tr><tr><td colspan="10"><hr></td></tr></table>';

                    listdis.innerHTML = listdisHTML;
                    listdis.style.display = 'block';
                    if (DFS == 'none')
                        document.getElementById('tdAction').style.display = 'none';
                    else
                        document.getElementById('tdAction').style.display = 'block';
                    /*if (noRows == 1)
                        document.getElementById('btnGo').setAttribute('disabled', true);
                    else
                        document.getElementById('btnGo').setAttribute('disabled', false);*/
                }
            }
        })

        //For All Type
    } // else finished //2013
}

function customFiltersJSON() {
    var tType = new Array();
    var tTable = new Array();
    var ccol = new Array();
    var op = new Array();
    var val = new Array();
    var log = new Array();
    var rows = new Array();
    var element = Array('', 'ccol', 'op', 'val', 'log');
    var k = 4;
    var noLS = $('#numRows_' + k).val();
    var index = 1;
    var p = 31;
    var c = parseInt(noLS) + parseInt(p);
    var customFiltersVal = [];
    m = 0;
    for (var i = p; i <= c; i++) {
        var ccol = new Array();
        var op = new Array();
        var val = new Array();
        var log = new Array();
        for (var j = 1; j <= 3; j++) {

            var id1 = (element[1] + i) + j.toString();
            var id2 = (element[2] + i) + j.toString();
            var id3 = (element[3] + i) + j.toString();
            var id4 = (element[4] + i) + j.toString();
            if ($('[id=' + id1 + ']').val() != undefined || $('[id=' + id1 + ']').val() != null) {
                if ((j == 1) && k == 2 || k == 3) {
                    tType[m] = $('#typebox_' + i + j).val();
                    tTable[m] = $('#tablename_' + i + j).val();
                    m++;
                }

                var coloptions = $('[id=' + id1 + '] option');
                var opoptions = $('[id=' + id2 + '] option');
                var valoptions = $('[id=' + id3 + ']').prop("tagName") == 'SELECT' ? $('[id=' + id3 + '] option') : [];
                var logoptions = $('[id=' + id4 + '] option');
                var colvalues = $.map(coloptions, function (option) {
                    return {
                        'value': option.value,
                        'selected': option.selected
                    };
                });

                var opvalues = $.map(opoptions, function (option) {
                    return {
                        'value': option.value,
                        'text': option.text,
                        'selected': option.selected
                    };
                });
                var valvalues = [];
                if (valoptions.length > 0) {
                    var valvalues = $.map(valoptions, function (option) {
                        return {
                            'value': option.value,
                            'selected': option.selected
                        };
                    });
                }

                var logoptions = $.map(logoptions, function (option) {
                    return {
                        'value': option.value,
                        'text': option.text,
                        'selected': option.selected
                    };
                });


                ccol.push({
                    'index': j,
                    'value': $('[id=' + id1 + ']').val(),
                    'options': colvalues
                });

                op.push({
                    'index': j,
                    'value': $('[id=' + id2 + ']').val(),
                    'options': opvalues
                });

                val.push({
                    'index': j,
                    'value': $('[id=' + id3 + ']').val(),
                    'options': valvalues.length > 0 ? valvalues : ''
                });

                if($('[id=' + id4 + ']').length > 0){
                    log.push({
                        'index': j,
                        'value': $('[id=' + id4 + ']').val(),
                        'options': logoptions.length > 0 ? logoptions : ''
                    });
                }
            }
            index++;
        }
        if (ccol.length > 0) {
            rows.push({
                'table': tTable,
                'type': tType,
                'col': ccol,
                'op': op,
                'val': val,
                'log' : log
            })
        }
    }

    if (noLS > 0) {
        logStr = log;
        if (k == 4) {

            customFiltersVal.push({
                'noLS': noLS,
                'rows': rows,
                'logStr': logStr
            });

            //localStorage.setItem('customFiltersVal', JSON.stringify(customFiltersVal))
            //console.log(JSON.parse(localStorage.getItem('customFiltersVal')));
        }
    }

    return JSON.stringify(customFiltersVal);
}

function customFiltersCondition() {
    var ccol = new Array();
    var op = new Array();
    var is_nm = new Array();//check is numeric
    var log = new Array();

    var element = [' ', 'ccol', 'op', 'val', 'log'];
    var WhereArray = new Array();

    var p = 31;
    var k = 0;
    var noLS = $('#numRows_4').val();
    var c = parseInt(noLS) + parseInt(p);

    for (var i = p; i <= c; i++) {
        for (j = 1; j <= 3; j++) {

            var id1 = (element[1] + i) + j.toString();
            var id2 = (element[2] + i) + j.toString();
            var id3 = (element[3] + i) + j.toString();
            var id4 = (element[4] + i) + j.toString();

            if ($('[id=' + id1 + ']').val() != "") {
                ccol[k] = $('[id=' + id1 + ']').val();
                op[k] = $('[id=' + id2 + ']').val();
                is_nm[k] = $('[id=' + id2 + ']').attr('rel');
                val[k] = $('[id=' + id3 + ']').val();
                if (j != 3) {
                    //log[k] = ' OR '; //$('[id='+id4+']').val();
                    if($('[id='+id4+']').length > 0){
                        log[k] = $('[id='+id4+']').val();
                    }
                }
                k++;
            } else {
                continue;
            }
        }
    }

    var where = new Array(), op1, flag;

    for (var j = 0; j < noLS; j++) {

        flag = 0;
        where[j] = "";
        for (var i = (j * 3); i < (j * 3) + 3; i++) {
            switch (op[i]) {

                case '0':
                    op1 = " > ";
                    break;
                case '1':
                    op1 = " < ";
                    break;
                case '2':
                    op1 = " >= ";
                    break;
                case '3':
                    op1 = " <= ";
                    break;
                case '4':
                    op1 = " = ";
                    break;
                case '5':
                    op1 = " != ";
                    break;
                case '6':
                    op1 = " in ";
                    break;
                case '7':
                    op1 = " not in ";
                    break;
                case '8':
                    op1 = " like ";
                    break;
                case '8.1':
                    op1 = " like ";
                    break;
                case '8.2':
                    op1 = " like ";
                    break;
                case '9':
                    op1 = " not like ";
                    break;
                case '9.1':
                    op1 = " not like ";
                    break;
                case '9.2':
                    op1 = " not like ";
                    break;
                default:
                    op1 = "";
                    break;
            }
            //if ((ccol[i] != "") && (op1 != "") && (val[i] != "")) {
            if ((ccol[i] != "") && (op1 != "")) {
                var reg = /^\d+$/;
                if (flag == 0) {
                    if (op[i] > '5' && op[i] < '8') {

                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(' + vValArray[kk] + ')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + ')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(\'' + vValArray[kk] + '\')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '(' + val[i] + ')';
                            } else {
                                nNewValVar = '(\'' + val[i] + '\')';
                            }
                        }

                        if (ccol[i + 1] != " " && ccol[i + 1] != null) {
                            where[j] += ' ' + ccol[i] + ' ' + op1 + nNewValVar;
                        } else {
                            where[j] += '( ' + ccol[i] + ' ' + op1 + nNewValVar + ' )';
                        }
                        flag = 1;
                    } else if (op[i] > '7') {

                        if (op[i] == '8') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '8.1') {
                            var st = '', ed = '%';
                        } else if (op[i] === '8.2') {
                            var st = '%', ed = '';
                        } else if (op[i] == '9') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '9.1') {
                            var st = '', ed = '%';
                        } else if (op[i] == '9.2') {
                            var st = '%', ed = '';
                        }

                        if (val[i].indexOf(",") > -1) {
                            var vValArray = val[i].split(",");
                            var nNewValVar = '(';

                            var collog = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (vValArray.length == 1)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (kk + 1 == vValArray.length)
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (vValArray.length > 1 && kk == 0)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';
                                else
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';   //After Start if like have more then one options
                            }
                        } else {
                            collog = '(' + ccol[i] + ' ' + op1 + ' \'' + st + val[i] + ed + '\')';
                        }


                        if (ccol[i + 1] != " ") // For two columns
                            where[j] += '' + collog;
                        else
                            where[j] += collog;

                        flag = 1;
                    } else {
                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                //if (reg.test(vValArray[kk]) == true) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '' + val[i] + '';
                            } else {
                                nNewValVar = '\'' + val[i] + '\'';
                            }
                        }
                        if (ccol[i + 1] != " ") {
                            where[j] += ' ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' ';

                        } else {
                            where[j] += '( ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' )';
                        }
                        flag = 1;
                    }
                } else if (log[i - 1] != " ") {
                    if (op[i] > '5' && op[i] < '8') {
                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(' + vValArray[kk] + ')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + ')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(\'' + vValArray[kk] + '\')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '(' + val[i] + ')';
                            } else {
                                nNewValVar = '(\'' + val[i] + '\')';
                            }
                        }

                        var vl = (j * 3) + 2;

                        if ((i == vl) && (ccol[vl] != ""))
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + nNewValVar + '';
                        else
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + nNewValVar + '';


                    } else if (op[i] > '7') {
                        if (op[i] == '8') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '8.1') {
                            var st = '', ed = '%';
                        } else if (op[i] === '8.2') {
                            var st = '%', ed = '';
                        } else if (op[i] == '9') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '9.1') {
                            var st = '', ed = '%';
                        } else if (op[i] == '9.2') {
                            var st = '%', ed = '';
                        }

                        if (val[i].indexOf(",") > -1) {
                            var vValArray = val[i].split(",");
                            var nNewValVar = '(';
                            var collog = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (vValArray.length == 1)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (kk + 1 == vValArray.length)
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (vValArray.length > 1 && kk == 0)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';
                                else
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';
                            }
                        } else {
                            collog = '(' + ccol[i] + ' ' + op1 + ' \'' + st + val[i] + ed + '\')';
                        }
                        var vl = (j * 3) + 2;

                        if ((i == vl) && (ccol[vl] != "")) {
                            where[j] += log[i - 1] + collog + '';
                        } else {
                            where[j] += log[i - 1] + collog + '';
                        }

                    } else {
                        var vl = (j * 3) + 2;

                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '' + val[i] + '';
                            } else {
                                nNewValVar = '\'' + val[i] + '\'';
                            }
                        }
                        if ((i == vl) && (ccol[vl] != ""))
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' ';
                        else {
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' ';
                        }
                    }
                }  // Else if
            }  // If

        }  // Inner For
    }// Outer For

    var k = 0;
    for (var i = 0; i < where.length; i++) {
        if (where[i] != "") {

            var n = where[i].indexOf("OR");
            if (n > 0) {
                WhereArray[k] = " (" + where[i] + ") ";
            } else {
                WhereArray[k] = "" + where[i];
            }
            k++;
        }
    }
    var str = WhereArray.join(" AND ");
    if (str != "") {
        str = str;
    } else {
        str = "";
    }

    return {str : str , where : where };
}

function getAction(flag = true) {
    var x = true;
    var index = 1, i = 1, j;
    var id;
    var newWhere = new Array();
    var ListSegDes = new Array();
    var action = document.getElementById('cmbAction').value;
    if (action == 'Delete') {

        while (x) {
            id = 'c' + i;

            if (document.getElementById(id)) {
                if (!$('#'+id).is(':checked')) {
                    newWhere[index] = document.getElementById('cri' + i).innerText;
                    ListSegDes[index] = document.getElementById('ld' + i).value;
                    index = index + 1;
                }
            } else {
                x = false;
                index = index - 1;
            }
            i++;
        }
        if (index == 0) {
            ACFn.display_message("Atleast One Criteria you should have...",'','success');
            document.getElementById('cmbAction').value = 'act';
            return;
        }
    }
    else if (action == 'Join') // Join
    {
        var index = 1;
        var newWhereStr = '', newListSegDesStr = '';
        var flag = 0;
        var min, Cflag = 0;
        while (x) {
            id = 'c' + i;

            if (document.getElementById(id)) {
                if (document.getElementById(id).checked == true) {
                    if (flag == 0) {
                        newWhereStr += document.getElementById('cri' + i).innerText;
                        newListSegDesStr += document.getElementById('ld' + i).value;
                        min = i;
                        index = index + 1;
                        flag = 1;

                    } else {
                        newWhereStr += " or " + document.getElementById('cri' + i).innerText;
                        newListSegDesStr += "_" + document.getElementById('ld' + i).value;
                        Cflag = 1;
                        noRows = noRows - 1;
                    }

                } else {
                    newWhere[index] = document.getElementById('cri' + i).innerText;
                    ListSegDes[index] = document.getElementById('ld' + i).value;
                    index = index + 1;

                }
            } else {
                x = false;
            }
            i++;
        }

        if (Cflag == 1)
            newWhere[min] = "( " + newWhereStr + " )";
        else
            newWhere[min] = newWhereStr;

        ListSegDes[min] = newListSegDesStr;
        console.log("newWhereStr--",newWhereStr,newWhere);
    } // Join

    if(flag == true){
        console.log('newWhere --',newWhere);
        var WhereArray = newWhere.slice(1, newWhere.length);
        console.log('WhereArray --',WhereArray);
        var str = WhereArray.join(":WHERE:");
        var sql = params.sSQL;

        $.ajax({
            url : 'model/lsdetails',
            type : 'POST',
            data : {
                'pgaction' : 'count',
                'WhereArray' : str,
                'sSQL' : sql,
                '_token' : $('[name="_token"]').val()
            },
            success : function (response) {
                if (response !== undefined) {
                    row = "";
                    listdisHTML = "";
                    var LSSM = document.getElementById('cmbLSM').value;
                    var listdis = document.getElementById('LsSQL');
                    var i = 1, ZeroRec = 0;
                    noRows = response.noRows;
                    var myArray = [];
                    $.each(response.count, function (index, value) {
                        myArray.push([value]);
                    });
                    noRecSmp = (myArray).join(',');
                    noRec = ("0," + noRecSmp).split(",");
                    ListSegCri = newWhere.slice();
                    listdisHTML += '<table class="c1" style="width:1005px" ><tr><td style="width:136px">List Segment Details &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td><td style="width:20px;font-weight: 400;"></td><td style="width:10px;"></td><td style="font-weight: 400;">List Segment Selection Criteria</td><td></td><td style="font-weight: 400">List Segment Description</td><td style="font-weight: 400;width:88px">Universe</td><td style="font-weight: 400; width:60px">Sample %</td><td style="font-weight: 400;">Sample Size</td></tr><tr><td height="10px"></td></tr>';
                    for (var j = 1; j <= noRows; j++) {

                        if (noRec[j] > 0) {
                            AI = i + ZeroRec;
                            cid = "spID" + i;
                            coid = "ssID" + i;
                            sp = "sp" + i;

                            switch (LSSM) {
                                case 'ranNum':
                                case 'topNum':
                                    row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td></td><td width="200px"><label class="txt" id=cri' + i + ' >' + ListSegCri[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input class="form-control form-control-sm" type="text"   id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '>' + noRec[AI] + '</label></td><td><label id=sp' + i + ' class="l1" style="width:50px">100</label></td><td width="100px"><Input type="text" class="form-control form-control-sm" onChange="ssCal(this,\'' + cid + '\',\'' + sp + '\')" style="width:100px" value=' + noRec[AI] + ' id=' + coid + '></td></tr>';
                                    break;
                                case 'ranPer':
                                case 'topPer':
                                    row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td></td><td width="200px"><label class="txt" id=cri' + i + ' >' + ListSegCri[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input class="form-control form-control-sm" type="text"   id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '>' + noRec[AI] + '</label></td><td><Input type="text" value="100" id=sp' + i + ' class="form-control form-control-sm" onChange="calculate(this,\'' + cid + '\',\'' + coid + '\')" /></td><td width="100px"><label class="l1" id=' + coid + '>' + noRec[AI] + '</label></td></tr>';
                                    break;
                                case 'none':
                                    row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td></td><td width="200px"><label class="txt" id=cri' + i + ' >' + ListSegCri[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input class="form-control form-control-sm" type="text"   id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '>' + noRec[AI] + '</label></td><td><label id=sp' + i + ' class="l1" style="width:50px">100</label></td><td width="100px"><label class="l1" id=' + coid + '>' + noRec[AI] + '</label></td></tr>';
                                    break;
                            }
                            i++;
                        } else {
                            ZeroRec = ZeroRec + 1;
                        }
                    }

                    listdisHTML += row;
                    listdisHTML += '<tr><td height="10px"></td></tr><tr><td colspan="10"><hr></td></tr></table>';

                    listdis.innerHTML = listdisHTML;
                    listdis.style.display = 'block';
                    // do //
                    document.getElementById('tdAction').style.display = 'block';
                    document.getElementById('cmbAction').value = 'act';
                    /*if (noRows == 1) {
                        document.getElementById('btnGo').setAttribute('disabled', true);
                    }*/
                    //if(noRows == 1)
                }
            }

        });
    }
}

function ViewListSegDetails() {
    if (parent.up_flag == 'view' || parent.up_flag == 'update') {
        /******* Added condition to differentiate between (view,update) and (new campaign and save as) 2017-03-23 */
        var listSegDetails = new Array();
        listSegDetails = (res.seg_selected_criteria).split("^");
        var WhereArray = listSegDetails[0].split(":");
        var ListSegDes = listSegDetails[1].split(":");
        var Universe = listSegDetails[2].split(":");
        var SamplePer = listSegDetails[3].split(":");
        var SampleSize = listSegDetails[4].split(":");

        //Remove Zero Index
        /*WhereArray.shift();
		 ListSegDes.shift();
		 Universe.shift();
		 SamplePer.shift();
		 SampleSize.shift();*/
        //Remove Zero Index

        noRows = WhereArray.length - 1;
        var DFS = document.getElementById('cmbDFS').value;
        var listdis = document.getElementById('LsSQL');

        listdis.innerHTML = "";
        var listdisHTML = "";
        listdisHTML += '<table class="c1" style="width:1050px" ><tr><td style="width:136px">List Segment Details &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td><td style="width:20px;font-weight: 400;"></td><td style="font-weight: 400;">List Segment Selection Criteria</td><td></td><td style="font-weight: 400">List Segment Description</td><td style="font-weight: 400;width:88px">Universe</td><td style="font-weight: 400; width:60px">Sample %</td><td style="font-weight: 400;">Sample Size</td></tr><tr><td height="10px"></td></tr>';
        row = "";
        noRec = SampleSize;
        var LSSM = document.getElementById('cmbLSM').value;
        var i = 1, ZeroRec = 0;
        for (var j = 1; j <= noRows; j++) {

            if (noRec[j] > 0) {
                AI = i + ZeroRec;
                cid = "spID" + i;
                coid = "ssID" + i;
                sp = "sp" + i;

                switch (LSSM)//Hema
                {
                    case 'ranNum':
                    case 'topNum':
                        row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td width="200px"><label class="txt" id=cri' + i + ' >' + WhereArray[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input  type="text" class="form-control form-control-sm" id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '>' + SamplePer[AI] + '</label></td><td><label id=sp' + i + ' class="l1" style="width:50px">' + Universe[AI] + '</label></td><td width="100px"><Input type="text" class="form-control form-control-sm" onChange="ssCal(this,\'' + cid + '\',\'' + sp + '\')" style="width:100px" value=' + SampleSize[AI] + ' id=' + coid + ' onkeypress="return numeric_only(event);" ></td></tr>';
                        break;
                    case 'ranPer':
                    case 'topPer':
                        row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td width="200px"><label class="txt" id=cri' + i + ' >' + WhereArray[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input  type="text" class="form-control form-control-sm"  id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '>' + SamplePer[AI] + '</label></td><td><Input type="text" value="' + Universe[AI] + '" id=sp' + i + ' class="form-control form-control-sm" onChange="calculate(this,\'' + cid + '\',\'' + coid + '\')" onkeypress="return numeric_only(event);" /></td><td width="100px"><label class="l1" id=' + coid + '>' + SampleSize[AI] + '</label></td></tr>';
                        break;
                    case 'none':
                        row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td width="200px"><label class="txt" id=cri' + i + ' >' + WhereArray[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input  type="text" class="form-control form-control-sm" id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '>' + SamplePer[AI] + '</label></td><td><label id=sp' + i + ' class="l1" style="width:50px">' + Universe[AI] + '</label></td><td width="100px"><label class="l1" id=' + coid + '>' + SampleSize[AI] + '</label></td></tr>';
                        break;
                }
                i++;
            } else {
                ZeroRec = ZeroRec + 1;
            }
        }


        /*	for(var i=0;i<noRows;i++)
			 {
				 seq_num = i + 1;
				 cid="cid"+seq_num;
				 coid="coid"+seq_num;
				 row+='<tr><td></td><td><input type="checkbox" id="c'+seq_num+'" ></td><td width="200px"><label class="txt" id=cri'+seq_num+ ' >'+WhereArray[i]+'</label></td><td width="10px"></td><td style="width:210px" ><input  type="text"  name=ld'+seq_num+ ' value=\"'+ListSegDes[i]+'\"  style="width:200px" onChange="changeSample()" /></td><td><label class="l1" id='+cid+'>'+SamplePer[i]+'</label></td><td><Input type="text" value='+Universe[i]+' id=sp'+seq_num+ ' class="txt1" onChange="calculate(this,\''+cid+'\',\''+coid+'\')" /></td><td width="100px"><label class="l1" id='+coid+'>'+SampleSize[i]+'</label></td></tr>';
				 //change
			 }*/

        listdisHTML += row;
        listdisHTML += '<tr><td height="10px"></td></tr><tr><td colspan="10"><hr></td></tr></table>';
        /************* Remove 2017-03-07 Start ******************/
        listdis.innerHTML = listdisHTML;
        listdis.style.display = 'block';
        /************* Remove 2017-03-07 Start ******************/
        ViewSample();
    } else {
        /******************* Change 2017-03-16 Start for add waiting in list segment details ******************/
        listdis = document.getElementById('LsSQL');
        var listdisHTML_Wait = '<table class="c1" style="width:1050px" ><tr><td style="width:136px">List Segment Details &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td><td style="width:20px;font-weight: 400;"></td><td style="font-weight: 400;">List Segment Selection Criteria</td><td></td><td style="font-weight: 400">List Segment Description</td><td style="font-weight: 400;width:88px">Universe</td><td style="font-weight: 400; width:60px">Sample %</td><td style="font-weight: 400;">Sample Size</td></tr><tr><td height="10px"></td></tr>';

        listdisHTML_Wait += '<tr><td></td><td></td><td width="200px">Please wait, Loading...</label></td><td width="10px"></td><td style="width:210px" ></td><td><label  ></label></td><td><label  ></label></td><td width="100px"></td></tr>';

        listdisHTML_Wait += '<tr><td height="10px"></td></tr><tr><td colspan="10"><hr></td></tr></table>';
        listdis.innerHTML = listdisHTML_Wait;
        /******************* Change 2017-03-16 End for add waiting in list segment details ******************/
        listdis.style.display = 'block';
        setTimeout(function () {
            refreshSegment();
        }, 3000);
    }

}

function byFieldCheck(obj) {
    $.ajax({
        url : 'model/byfieldcheck',
        type : 'POST',
        data : {
            colName : obj.value,
            _token : $('[name="_token"]').val()
        },
        success : function (response) {
            if (response !== undefined) {
                if (response.str == 'NOT') {
                    ACFn.display_message('Column Name \"' + obj.value + '\" not qualified for list segmentation. Please select some other Column','','success');
                    obj.value = '';
                }

            }
        }
    });
}

function byField(obj) {

    //alert("ByField"+YAHOO.lang.dump(resArray)); //Hema//
    noField = obj.value;
    document.getElementById('divByField').style.display = 'block';
    document.getElementById('cmbNoFields').style.display = 'inline';
    $.ajax({
        url: 'model/getcol',
        type: 'POST',
        data:
            {
                'sSQL': params.sSQL,
                _token : $('[name="_token"]').val(),
            },
        success: function (response) {
            if (response !== undefined) {

                // var x = document.getElementById('divByFieldCol');
                $('#divByFieldCol').html("");
                opt = '<option value=" "></option>';
                var colarray = response.columns;
                //x.innerHTML="";
                var col;

                for (var j = 0; j < colarray.length; j++) {

                    opt += '<option value=' + colarray[j] + '>' + colarray[j] + '</option>';

                }
                // col='<table   class="c1" style="width:0px" >';
                col = '<tr><td style="width:200px" >Select Fields</td>';

                for (var i = 1; i <= obj.value; i++) {
                    col += '<td  ><select style="width:150px;" class="form-control form-control-sm" onChange="byFieldCheck(this)" id=col' + i + '>' + opt + '</select>&nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></div></td>';

                }

                //col+='<tr></table>';
                col += '<tr>';
                $('#divByFieldCol').append(col);
                $('#divByFieldCol').show();
                //x.innerHTML +=col;
                //x.style.display="block";


                if (byField_flag == 1) {
                    var colStr = (res.seg_criteria).split(":");
                    for (i = 1; i < colStr.length; i++) {
                        document.getElementById('col' + i).value = colStr[i];
                        byField_flag = 0;
                    }
                }
            }
        }
    })


}

function segRenderFilters(start, section, title, clsname, color, filters) {
    var ntitle = title.replace('^', '"');
    ntitle = ntitle.replace('^', '"');
    $.each(filters, function (key, data) {
        $('#numRows_4').val(data.noLS);
        $.each(data.rows, function (ikey, idata) {
            var ids = (start * 10) + 1;
            var newRowIds = parseInt(ids) + 10;
            var newSecIds1 = parseInt(ids) + 10;
            var newSecIds2 = parseInt(ids) + 11;
            var newSecIds3 = parseInt(ids) + 12;

            $('#row_' + ids).after('<div class="divTableRow" id="row_' + newRowIds + '"><div class="divTableCell ff"><div class="divTable blueTable"><div class="divTableBody"><div class="divTableRow"><div style="' + color + 'vertical-align: middle; visibility: hidden;" class="divTableCell" id="info_' + newRowIds + '">' + ntitle + '<input type="hidden" id="tablename_' + newRowIds + '" value="" /><input type="hidden" id="typebox_' + newRowIds + '" value="" /><input type="hidden" id="countSec_' + newRowIds + '" value="0" /></div><div style="width:1%; text-align:right !important;" id="preCross_' + newRowIds + '" class="divTableCell"></div><div style="width:1%;font-size: 10px;text-align:center;" class="divTableCell" id="plusDiv_' + newSecIds1 + '"><a onclick="addSectionNew(' + newRowIds + ',' + newRowIds + ',0,' + section + ',\'' + title + '\');" href="javascript:void(0);"><i class="fas fa-plus-circle font-14 ds-c"></i> </a></div></div></div></div></div><div class="divTableCell"><div class="divTable blueTable"><div class="divTableBody"><div class="divTableRow"><div style="width:7%" class="divTableCell" id="ccolCell_' + newSecIds1 + '"></div><div style="width:3%" class="divTableCell"  id="opCell_' + newSecIds1 + '"></div><div style="width:5%" class="divTableCell"  id="valCell_' + newSecIds1 + '"></div><div style="width:3%;text-align: center;font-size: 8px;" class="divTableCell" id="plusCell_' + newSecIds2 + '"></div><div style="width:7%" class="divTableCell" id="ccolCell_' + newSecIds2 + '"></div><div style="width:3%" class="divTableCell" id="opCell_' + newSecIds2 + '"></div><div style="width:5%" class="divTableCell" id="valCell_' + newSecIds2 + '"></div><div style="width:3%;text-align: center;font-size: 8px;" class="divTableCell" id="plusCell_' + newSecIds3 + '"></div><div style="width:7%" class="divTableCell" id="ccolCell_' + newSecIds3 + '"></div><div style="width:3%" class="divTableCell" id="opCell_' + newSecIds3 + '"></div><div style="width:5%" class="divTableCell" id="valCell_' + newSecIds3 + '"></div></div></div></div></div></div>');
            var colids = ids;
            var opids = ids;
            var valds = ids;
            var logids = ids;
            $('#tablename_' + colids).val(idata.table[ikey]);
            $('#typebox_' + colids).val(idata.type[ikey]);

            $.each(idata.col, function (ckey, cdata) {
                $('#ccolCell_' + colids).html('<select class="form-control form-control-sm ' + clsname + '" style="width:100%;" onchange="getCol(this,\'op' + colids + '\',\'' + colids + '\',\'' + section + '\');" class="t1" name="ccol' + colids + '" id="ccol' + colids + '"></select>');

                $.each(cdata.options, function (key, val) {
                    $('#ccol' + colids).append($("<option></option>")
                        .attr("value", val.value).attr("selected", val.selected)
                        .text(val.value));
                });


                var nextIds = parseInt(colids) + 1;

                if (ckey == 0) {
                    var rowIds = colids;
                    $('#plusDiv_' + rowIds).html('');
                    $('#preCross_' + rowIds).html('<a class="crosss" onclick="removeSection(' + rowIds + ',' + colids + ',' + section + ');" href="javascript:void(0);"><i class="fas fa-trash font-14"></i> </a>');
                } else {
                    $('#plusCell_' + colids).html('');
                    //$('#plusCell_' + colids).text('OR');
                }
                var secIds = ids;

                $('#plusCell_' + nextIds).html('<a onclick="addSectionNew(' + rowIds + ',' + nextIds + ',1,' + section + ',\'' + title + '\');" href="javascript:void(0);"><i class="fas fa-plus-circle font-14 ds-c"></i> </a>');

                colids++;
            });
            $.each(idata.op, function (okey, odata) {

                $('#opCell_' + opids).html('<select class="form-control form-control-sm ' + clsname + '" onchange="changeVal(this.value,' + opids + ',1);" style="width:100%;" id="op' + opids + '" name="op' + opids + '"></select>')
                $.each(odata.options, function (key, val) {
                    $('#op' + opids).append($("<option></option>")
                        .attr("value", val.value).attr("selected", val.selected)
                        .text(val.text));
                });
                opids++;
            });
            $.each(idata.val, function (vkey, vdata) {
                if (vdata.options != "") {
                    $('#valCell_' + valds).html('<select class="form-control form-control-sm ' + clsname + '" style="width: 100%;" id="val' + valds + '"  onkeypress="GetTextInfo(this,event);" multiple="multiple"></select>');
                    $.each(vdata.options, function (key, val) {
                        $('#val' + valds).append($("<option></option>")
                            .attr("value", val.value).attr("selected", val.selected)
                            .text(val.value));
                    });
                    $("#val" + valds).multiselect({
                        close: function () {
                            var txtT = [];
                            $('#val' + valds + ' :selected').each(function (i, selected) {
                                txtT[i] = $(selected).text();
                            });
                        },
                        header: true, //"Region",
                        selectedList: 1, // 0-based index
                        nonSelectedText: 'Select Fields'
                    }).multiselectfilter({label: 'Search'});


                    $("#val" + valds).multiselect('refresh');

                    $("#val" + valds + "_ms").attr('style', 'width:100% !important;height: 28px; background-color: white !important;height: calc(1.5em + .5rem + 2px);padding: .25rem .5rem;border-radius: .2rem;background-clip: padding-box;border: 1px solid #e9ecef;font-size: .76563rem;');

                } else {
                    $('#valCell_' + valds).html('<input class="form-control form-control-sm ' + clsname + '" style="width:100%;" id="val' + valds + '" name="val' + valds + '" type="text" value="' + vdata.value + '">');
                }
                valds++;
            });

            $.each(idata.log, function (okey, odata) {
                console.log("logids------" + logids)
                $('#plusCell_' + parseInt(logids+1)).html('<select class="form-control form-control-sm dvd ' + clsname + '" style="width:100%;" name="log' + logids + '" id="log' + logids + '"></select>')

                $.each(odata.options, function (key, val) {
                    $('#log' + logids).append($("<option></option>")
                        .attr("value", val.value).attr("selected", val.selected)
                        .text(val.text));
                });
                logids++;
            });
            ids++;
            start++;
        })
    });
}

function getFormVal() {

    /********* 2018-03-23 - changes for hide buttons when view selected -- start ********/
    if (parent.up_flag == 'new') {
        $('#save').show();
        $('#savebottom').show();
    }
    if (parent.up_flag == 'view') {
        $('.ft').hide();
        $('#save').hide();
        $('#savebottom').hide();
    }
    /********* 2018-03-23 - changes for hide buttons when view selected -- end ********/

    if ((parent.oldcampclk == 'Y') && (parent.seg_clear_flag == 0)) {
        var tempid = params.row_id;
        $.ajax({
            url: 'model/getaddsubval',
            type: 'POST',
            data: {
                _token: $('[name="_token"]').val(),
                tempid: tempid
            },
            success: function (response) {
                if (response !== undefined) {
                    res = response.aData;
                    var temp = document.getElementById('cmbDFS');
                    temp.value = $.trim(res.seg_def);
                    if (res.seg_method != 'byfield') {
                        defineList(temp);
                    }

                    document.getElementById('cmbLSM').value = res.seg_method;
                    if (temp.value == 'custom') {
                        document.getElementById('txtnogrps').value = res.seg_noLS;
                        var color = '';
                        var customClass = '';
                        var filtersVal = JSON.parse(res.seg_filters_criteria);
                        var p = 31;
                        var title = 'Filters';
                        segRenderFilters(p, 1, title, customClass, color, filtersVal);

                        //ViewSelectionCriteria();
                        document.getElementById('tdAction').style.display = 'block';
                    } else if (temp.value == 'byfield') {
                        byField_flag = 1;
                        var noF = document.getElementById('cmbNoFields');
                        noF.value = res.seg_noLS;
                        document.getElementById('tdAction').style.display = 'block';
                        //byField(noF);
                        //ViewByField();

                    } else {
                        //For None
                        document.getElementById('tdAction').style.display = 'none';
                    }

                    ViewListSegDetails();
                    document.getElementById('cmbnogroup').value = res.seg_grp_no;
                    document.getElementById('chkgroup').value = res.seg_ctrl_grp_opt;
                    document.getElementById('divCG1').style.display = 'none';
                    document.getElementById('tdLs5').style.display = 'block';
                    //  document.getElementById('tdAction').style.display='block';
                    ViewCGroupDetails();
                    /****************** Change 2017-03-09 Start *****************************/
                    //setTimeout(function (){ sample();  },6000);
                    /****************** Change 2017-03-09 End *****************************/

                    if ((parent.up_flag == 'view')) // || (parent.up_flag == 'update')
                        window.setTimeout(disableEle, 4000);  /**** Change for disable fields in case of update and view, I ave increase the time due to some console issue 2017-03-16 */
                    //else
                    //YAHOO.csr.container.wait.hide();


                }
            }
        })
    } else if (parent.oldcampclk == 'N') {
        sSQL = params.sSQL;
        if (sSQL != '') {
            var temp = document.getElementById('cmbNoFields');
            setTimeout(function () {
                showDefaultSelection();
            }, 2000);
            byField(temp);
        } else {
            //alert("Design Mode");
            parent.designmode();
            //window.setTimeout('alert("Define Universe")', 2500);
        }
    }
}

function showDefaultSelection() {
    $('#cmbDFS').val('none');
    $('#divByField').hide();

    $('#cmbLSM').val('none');
    setTimeout(function () {
        $('#divByFieldCol').hide();
    },1000);
    //alert('ithe');
    ListSegDetails();
    $('#cmbnogroup').val(1);
    console.log('params.chkCG',params.chkCG)
    $('#chkgroup').val(params.chkCG);
    CGroupDetails();

}

function addsubClear() {
    parent.addsubgroupchk = 'N';
    parent.addsubSQL();
    $('#cmbDFS').val('none').trigger('change')
    parent.seg_clear_flag = 1;

}

/*********************** Changed 2017-03-07 Start ***************************/
function refreshSegment() {

    var PrevResilt = document.getElementById('prvrslt').value;

    if (PrevResilt == 'noprvw') {
        //alert('no preview');

        //document.getElementById('c1').style.display='none';
        // document.getElementById('tdAction').style.display="none";
        // document.getElementById('halfseg').style.display="none";


        var DFS = document.getElementById('cmbDFS').value;
        var listdis = document.getElementById('LsSQL');

        var sql = params.sSQL;
        var ListSegCri = new Array();
        var ListSegDes = new Array();
        var ZeroRec = 0, index = 1;
        if (DFS == 'custom') {
            noRows = document.getElementById("txtnogrps").value;
            var ccol = new Array();
            var op = new Array();
            var log = new Array();
            var k = 0;
            var element = [' ', 'ccol', 'op', 'val', 'log'];

            for (var i = 1; i <= noRows; i++) {
                for (var j = 1; j <= 3; j++) {

                    var id1 = (element[1] + j) + i.toString();
                    var id2 = (element[2] + j) + i.toString();
                    var id3 = (element[3] + j) + i.toString();
                    ccol[k] = $('[name=' + id1 + ']').val();
                    op[k] = $('[name=' + id2 + ']').val();
                    val[k] = $('[name=' + id3 + ']').val();
                    if (j != 3) {
                        var id4 = (element[4] + j) + i.toString();
                        log[k] = $('[name=' + id4 + ']').val();
                    }
                    k++;

                }
            }

            var where = new Array(), op1, flag;
            for (var j = 0; j < noRows; j++) {
                flag = 0;
                where[j] = "";
                for (var i = (j * 3); i < (j * 3) + 3; i++) {

                    switch (op[i]) {

                        case '0':
                            op1 = " > ";
                            break;
                        case '1':
                            op1 = " < ";
                            break;
                        case '2':
                            op1 = " >= ";
                            break;
                        case '3':
                            op1 = " <= ";
                            break;
                        case '4':
                            op1 = " = ";
                            break;
                        case '5':
                            op1 = " != ";
                            break;
                        case '6':
                            op1 = " in ";
                            break;
                        case '7':
                            op1 = " not in ";
                            break;
                        case '8':
                            op1 = " like ";
                            break;
                        case '9':
                            op1 = " not like ";
                            break;
                        default:
                            op1 = "";
                            break;
                    }
                    if ((ccol[i] != "") && (op1 != "") && (val[i] != "")) {

                        if (flag == 0) {
                            if (op[i] > '5') {
                                where[j] += '( ' + ccol[i] + ' ' + op1 + ' (\'' + val[i] + '\'))';
                                flag = 1;
                            } else {
                                where[j] += '( ' + ccol[i] + ' ' + op1 + ' \'' + val[i] + '\' )';
                                flag = 1;
                            }

                        } else if (log[i - 1] != " ") {
                            if (op[i] > '5') {
                                where[j] += log[i - 1] + '( ' + ccol[i] + ' ' + op1 + ' (\'' + val[i] + '\'))';
                            } else {
                                where[j] += log[i - 1] + '( ' + ccol[i] + ' ' + op1 + ' \'' + val[i] + '\' )';
                            }
                        }  // Else if
                    }  // If
                }  // Inner For
                //  document.getElementById('divCG1').style.display='block';
            }// Outer For
            /* To Get Count of Records */


            var k = 0;
            for (var i = 0; i < where.length; i++) {
                if (where[i] != "")
                    WhereArray[i] = "" + where[i];
                else
                    WhereArray[i] = "NULL";
            }
            str = WhereArray.join(":WHERE:");

            var postData = {
                'pgaction' : 'countNopreview',
                'sSQL' : sql,
                'WhereArray' : str,
                '_token' : $('[name="_token"]').val()
            };
        }
        // DFS = 'custom'
        else if (DFS == 'byfield') {
            var colflag = 0;
            var colArray = new Array();
            var noColumn = document.getElementById('cmbNoFields').value;
            for (i = 1; i <= noColumn; i++) {

                colArray[i - 1] = document.getElementById('col' + i).value;

                if (document.getElementById('col' + i).value == '') {
                    colflag = 1;
                    break;
                }
            }
            if (colflag == 0) {

                var postData = {
                    'pgaction' : 'byFieldValueNopreview',
                    'sSQL' : window.document.Form1.sql.value,
                    'colName' : colArray.join(','),
                    '_token' : $('[name="_token"]').val()
                };

            } else {
                alert("Columns Should not be Empty");
            }
            // DFS = 'byfield'
        } else if (DFS == 'none') {
            var postData = {
                'pgaction' : 'getCountNopreview',
                'sSQL' : sql,
                '_token' : $('[name="_token"]').val()
            };
        }
        // DFS = 'none'

        //For All Type

        $.ajax({
            url : 'model/lsdetails',
            type : 'POST',
            data : postData,
            success : function (response) {
                if (response !== undefined) {
                    var listdis = document.getElementById('LsSQL');
                    listdis.innerHTML = "";
                    var listdisHTML = "";
                    listdisHTML += '<table class="c1" id="c1" style="width:1005px" ><tr><td style="width:136px;">List Segment Details &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td><td style="width:20px;font-weight: 400;"></td><td style="width:10px;"></td><td style="font-weight: 400;">List Segment Selection Criteria</td><td></td><td style="font-weight: 400">List Segment Description</td><td style="font-weight: 400;width:88px">Universe</td><td style="font-weight: 400; width:60px">Sample %</td><td style="font-weight: 400;">Sample Size</td></tr><tr><td height="10px"></td></tr>';
                    var noRec = new Array();
                    if (DFS == 'custom') {
                        // Add 0: just to state index from 1 //
                        //noRec = ("0,"+o.responseText).split(",");

                        ListSegCri = ("W1," + WhereArray.join(",")).split(",");
                        temp = 1;
                        for (i = 0; i < noRows * 3; i = i + 3) {
                            ListSegDes[temp] = val[i]
                            temp = temp + 1;
                        }

                    } else if (DFS == 'byfield') {
                        Str = ("0:" + response).split(":");

                        ListCon = "";
                        ListDetail_temp = response.split(":");
                        noRows = Str.length - 2;

                        for (i = 1; i < Str.length - 1; i++) {
                            temp = Str[i].split(":"); // 2013
                            //noRec[i] = temp.pop();
                            ListSegDes[i] = temp.join("_");
                            //alert(ListSegDes[i]); right
                        }
                        temp_C = ListDetail_temp[0].length;

                        //temp_C = temp_CC+1; // 2013
                        //alert(temp_C);
                        temp = 1;
                        for (i = 1; i <= noRows; i++) {
                            Con_flag = 0;
                            ListDetail = ListDetail_temp[i - 1].split(":"); //2013

                            for (j = 0; j < temp_C - 1; j++) {
                                if (j == 0) {
                                    ListCon += "( " + colArray[j] + "='" + $.trim(ListDetail[j]) + "' )";

                                } else {
                                    ListCon += " and ( " + colArray[j] + "='" + $.trim(ListDetail[j]) + "' )";
                                    Con_flag = 1;
                                }

                                if (Con_flag == 1)
                                    ListSegCri[i] = "( " + ListCon + " )";
                                else
                                    ListSegCri[i] = ListCon;

                            }

                            ListCon = "";
                        }

                    } else if (DFS == 'none') {
                        noRec[1] = response.count;
                        ListSegCri[1] = "None";  //Blank /****2017-10-12 ****/
                        ListSegDes[1] = "All";
                        noRows = 1;
                        //document.getElementById('btnGo').setAttribute('disabled', true);
                    }

                    row = "";
                    var LSSM = document.getElementById('cmbLSM').value;
                    var i = 1;
                    for (var j = 1; j <= noRows; j++) {
                        if (ZeroRec >= 0) {

                            AI = i + ZeroRec;
                            cid = "spID" + i;
                            coid = "ssID" + i;
                            sp = "sp" + i;

                            switch (LSSM)//Hema
                            {
                                case 'ranNum':
                                case 'topNum':
                                    row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td></td><td width="200px"><label class="txt" id=cri' + i + ' >' + ListSegCri[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input  type="text" class="form-control form-control-sm" id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '></label></td><td><label id=sp' + i + ' class="l1" style="width:50px">100</label></td><td width="100px"><Input type="text" class="form-control form-control-sm" onChange="ssCal(this,\'' + cid + '\',\'' + sp + '\')" style="width:100px" value="" id=' + coid + ' onkeypress="return numeric_only(event);" ></td></tr>';
                                    break;
                                case 'ranPer':
                                case 'topPer':
                                    row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td></td><td width="200px"><label class="txt" id=cri' + i + ' >' + ListSegCri[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input  type="text" class="form-control form-control-sm" id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '></label></td><td><Input type="text" class="form-control form-control-sm" value="100" id=sp' + i + ' class="txt1" onChange="calculate(this,\'' + cid + '\',\'' + coid + '\')" onkeypress="return numeric_only(event);" /></td><td width="100px"><label class="l1" id=' + coid + '></label></td></tr>';
                                    break;
                                case 'none':
                                    row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td></td><td width="200px"><label class="txt" id=cri' + i + ' >' + ListSegCri[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input class="form-control form-control-sm" type="text"  id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '>' + noRec[1] + '</label></td><td><label id=sp' + i + ' class="l1" style="width:50px">100</label></td><td width="100px"><label class="l1" id=' + coid + '>' + noRec[1] + '</label></td></tr>';
                                    break;
                            }
                            i++;
                        } else {
                            ZeroRec = ZeroRec + 1;
                        }
                    }

                    listdisHTML += row;
                    listdisHTML += '<tr><td height="10px"></td></tr><tr><td colspan="10"><hr></td></tr></table>';

                    listdis.innerHTML = listdisHTML;
                    listdis.style.display = 'block';
                    if (DFS == 'none')
                        document.getElementById('tdAction').style.display = 'none';
                    else
                        document.getElementById('tdAction').style.display = 'block';
                    /*if (noRows == 1)
                        document.getElementById('btnGo').setAttribute('disabled', true);
                    else
                        document.getElementById('btnGo').setAttribute('disabled', false);
*/

                    /****************** Change 2017-03-16 Start *****************************/
                    // sample();
                    setTimeout(function () {
                        sample();
                    }, 1500);
                    /****************** Change 2017-03-16 End *****************************/


                }
            }
        });
        //alert(postData);
        //For All Type


    }   // 2013
    else {
        var DFS = document.getElementById('cmbDFS').value;
        var listdis = document.getElementById('LsSQL');

        var sql = params.sSQL;
        var ListSegCri = new Array();
        var ListSegDes = new Array();
        var ZeroRec = 0, index = 1;
        if (DFS == 'custom') {
            noRows = document.getElementById("numRows_4").value;
            var ccol = new Array();
            var op = new Array();
            var is_nm = new Array();//check is numeric
            var WhereArray = new Array();
            var log = new Array();
            var k = 0;
            var element = [' ', 'ccol', 'op', 'val', 'log'];

            var p = 31;
            var c = parseInt(noRows) + parseInt(p);

            for (var i = p; i <= c; i++) {
                for (j = 1; j <= 3; j++) {

                    var id1 = (element[1] + i) + j.toString();
                    var id2 = (element[2] + i) + j.toString();
                    var id3 = (element[3] + i) + j.toString();
                    var id4 = (element[4] + i) + j.toString();

                    if ($('[id=' + id1 + ']').val() != "") {
                        ccol[k] = $('[id=' + id1 + ']').val();
                        op[k] = $('[id=' + id2 + ']').val();
                        is_nm[k] = $('[id=' + id2 + ']').attr('rel');
                        val[k] = $('[id=' + id3 + ']').val();
                        if (j != 3) {
                            //log[k] = ' OR '; //$('[id='+id4+']').val();
                            if($('[id='+id4+']').length > 0){
                                log[k] = $('[id='+id4+']').val();
                            }
                        }
                        k++;
                    } else {
                        continue;
                    }
                }
            }

            var where = new Array(), op1, flag;
            for (var j = 0; j < noRows; j++) {

                flag = 0;
                where[j] = "";
                for (var i = (j * 3); i < (j * 3) + 3; i++) {
                    switch (op[i]) {

                        case '0':
                            op1 = " > ";
                            break;
                        case '1':
                            op1 = " < ";
                            break;
                        case '2':
                            op1 = " >= ";
                            break;
                        case '3':
                            op1 = " <= ";
                            break;
                        case '4':
                            op1 = " = ";
                            break;
                        case '5':
                            op1 = " != ";
                            break;
                        case '6':
                            op1 = " in ";
                            break;
                        case '7':
                            op1 = " not in ";
                            break;
                        case '8':
                            op1 = " like ";
                            break;
                        case '8.1':
                            op1 = " like ";
                            break;
                        case '8.2':
                            op1 = " like ";
                            break;
                        case '9':
                            op1 = " not like ";
                            break;
                        case '9.1':
                            op1 = " not like ";
                            break;
                        case '9.2':
                            op1 = " not like ";
                            break;
                        default:
                            op1 = "";
                            break;
                    }
                    //if ((ccol[i] != "") && (op1 != "") && (val[i] != "")) {
                    if ((ccol[i] != "") && (op1 != "")) {
                        var reg = /^\d+$/;
                        if (flag == 0) {
                            if (op[i] > '5' && op[i] < '8') {

                                if (val[i].toString().indexOf(",") > -1) {
                                    var vValArray = val[i].toString().split(",");
                                    var nNewValVar = '';
                                    for (kk = 0; kk < vValArray.length; kk++) {
                                        if (is_nm[i] == 1) {
                                            if (vValArray.length == 1)
                                                nNewValVar += '(' + vValArray[kk] + ')';
                                            else if (kk + 1 == vValArray.length)
                                                nNewValVar += '' + vValArray[kk] + ')';
                                            else if (vValArray.length > 1 && kk == 0)
                                                nNewValVar += '(' + vValArray[kk] + ',';
                                            else
                                                nNewValVar += '' + vValArray[kk] + ',';
                                        } else {
                                            if (vValArray.length == 1)
                                                nNewValVar += '(\'' + vValArray[kk] + '\')';
                                            else if (kk + 1 == vValArray.length)
                                                nNewValVar += '\'' + vValArray[kk] + '\')';
                                            else if (vValArray.length > 1 && kk == 0)
                                                nNewValVar += '(\'' + vValArray[kk] + '\',';
                                            else
                                                nNewValVar += '\'' + vValArray[kk] + '\',';
                                        }
                                    }
                                } else {
                                    if (is_nm[i] == 1) {
                                        nNewValVar = '(' + val[i] + ')';
                                    } else {
                                        nNewValVar = '(\'' + val[i] + '\')';
                                    }
                                }

                                if (ccol[i + 1] != " " && ccol[i + 1] != null) {
                                    where[j] += ' ' + ccol[i] + ' ' + op1 + nNewValVar;
                                } else {
                                    where[j] += '( ' + ccol[i] + ' ' + op1 + nNewValVar + ' )';
                                }
                                flag = 1;
                            } else if (op[i] > '7') {

                                if (op[i] == '8') {
                                    var st = '%', ed = '%';
                                } else if (op[i] == '8.1') {
                                    var st = '', ed = '%';
                                } else if (op[i] === '8.2') {
                                    var st = '%', ed = '';
                                } else if (op[i] == '9') {
                                    var st = '%', ed = '%';
                                } else if (op[i] == '9.1') {
                                    var st = '', ed = '%';
                                } else if (op[i] == '9.2') {
                                    var st = '%', ed = '';
                                }

                                if (val[i].indexOf(",") > -1) {
                                    var vValArray = val[i].split(",");
                                    var nNewValVar = '(';

                                    var collog = '';
                                    for (kk = 0; kk < vValArray.length; kk++) {
                                        if (vValArray.length == 1)
                                            collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                        else if (kk + 1 == vValArray.length)
                                            collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                        else if (vValArray.length > 1 && kk == 0)
                                            collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';
                                        else
                                            collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';   //After Start if like have more then one options
                                    }
                                } else {
                                    collog = '(' + ccol[i] + ' ' + op1 + ' \'' + st + val[i] + ed + '\')';
                                }


                                if (ccol[i + 1] != " ") // For two columns
                                    where[j] += '' + collog;
                                else
                                    where[j] += collog;

                                flag = 1;
                            } else {
                                if (val[i].toString().indexOf(",") > -1) {
                                    var vValArray = val[i].toString().split(",");
                                    var nNewValVar = '';
                                    for (kk = 0; kk < vValArray.length; kk++) {
                                        //if (reg.test(vValArray[kk]) == true) {
                                        if (is_nm[i] == 1) {
                                            if (vValArray.length == 1)
                                                nNewValVar += '' + vValArray[kk] + '';
                                            else if (kk + 1 == vValArray.length)
                                                nNewValVar += '' + vValArray[kk] + '';
                                            else if (vValArray.length > 1 && kk == 0)
                                                nNewValVar += '' + vValArray[kk] + ',';
                                            else
                                                nNewValVar += '' + vValArray[kk] + ',';
                                        } else {
                                            if (vValArray.length == 1)
                                                nNewValVar += '\'' + vValArray[kk] + '\'';
                                            else if (kk + 1 == vValArray.length)
                                                nNewValVar += '\'' + vValArray[kk] + '\'';
                                            else if (vValArray.length > 1 && kk == 0)
                                                nNewValVar += '\'' + vValArray[kk] + '\',';
                                            else
                                                nNewValVar += '\'' + vValArray[kk] + '\',';
                                        }
                                    }
                                } else {
                                    if (is_nm[i] == 1) {
                                        nNewValVar = '' + val[i] + '';
                                    } else {
                                        nNewValVar = '\'' + val[i] + '\'';
                                    }
                                }
                                if (ccol[i + 1] != " ") {
                                    where[j] += ' ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' ';

                                } else {
                                    where[j] += '( ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' )';
                                }
                                flag = 1;
                            }
                        } else if (log[i - 1] != " ") {
                            if (op[i] > '5' && op[i] < '8') {
                                if (val[i].toString().indexOf(",") > -1) {
                                    var vValArray = val[i].toString().split(",");
                                    var nNewValVar = '';
                                    for (kk = 0; kk < vValArray.length; kk++) {
                                        if (is_nm[i] == 1) {
                                            if (vValArray.length == 1)
                                                nNewValVar += '(' + vValArray[kk] + ')';
                                            else if (kk + 1 == vValArray.length)
                                                nNewValVar += '' + vValArray[kk] + ')';
                                            else if (vValArray.length > 1 && kk == 0)
                                                nNewValVar += '(' + vValArray[kk] + ',';
                                            else
                                                nNewValVar += '' + vValArray[kk] + ',';
                                        } else {
                                            if (vValArray.length == 1)
                                                nNewValVar += '(\'' + vValArray[kk] + '\')';
                                            else if (kk + 1 == vValArray.length)
                                                nNewValVar += '\'' + vValArray[kk] + '\')';
                                            else if (vValArray.length > 1 && kk == 0)
                                                nNewValVar += '(\'' + vValArray[kk] + '\',';
                                            else
                                                nNewValVar += '\'' + vValArray[kk] + '\',';
                                        }
                                    }
                                } else {
                                    if (is_nm[i] == 1) {
                                        nNewValVar = '(' + val[i] + ')';
                                    } else {
                                        nNewValVar = '(\'' + val[i] + '\')';
                                    }
                                }

                                var vl = (j * 3) + 2;

                                if ((i == vl) && (ccol[vl] != ""))
                                    where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + nNewValVar + '';
                                else
                                    where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + nNewValVar + '';


                            } else if (op[i] > '7') {
                                if (op[i] == '8') {
                                    var st = '%', ed = '%';
                                } else if (op[i] == '8.1') {
                                    var st = '', ed = '%';
                                } else if (op[i] === '8.2') {
                                    var st = '%', ed = '';
                                } else if (op[i] == '9') {
                                    var st = '%', ed = '%';
                                } else if (op[i] == '9.1') {
                                    var st = '', ed = '%';
                                } else if (op[i] == '9.2') {
                                    var st = '%', ed = '';
                                }

                                if (val[i].indexOf(",") > -1) {
                                    var vValArray = val[i].split(",");
                                    var nNewValVar = '(';
                                    var collog = '';
                                    for (kk = 0; kk < vValArray.length; kk++) {
                                        if (vValArray.length == 1)
                                            collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                        else if (kk + 1 == vValArray.length)
                                            collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                        else if (vValArray.length > 1 && kk == 0)
                                            collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';
                                        else
                                            collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';
                                    }
                                } else {
                                    collog = '(' + ccol[i] + ' ' + op1 + ' \'' + st + val[i] + ed + '\')';
                                }
                                var vl = (j * 3) + 2;

                                if ((i == vl) && (ccol[vl] != "")) {
                                    where[j] += log[i - 1] + collog + '';
                                } else {
                                    where[j] += log[i - 1] + collog + '';
                                }

                            } else {
                                var vl = (j * 3) + 2;

                                if (val[i].toString().indexOf(",") > -1) {
                                    var vValArray = val[i].toString().split(",");
                                    var nNewValVar = '';
                                    for (kk = 0; kk < vValArray.length; kk++) {
                                        if (is_nm[i] == 1) {
                                            if (vValArray.length == 1)
                                                nNewValVar += '' + vValArray[kk] + '';
                                            else if (kk + 1 == vValArray.length)
                                                nNewValVar += '' + vValArray[kk] + '';
                                            else if (vValArray.length > 1 && kk == 0)
                                                nNewValVar += '' + vValArray[kk] + ',';
                                            else
                                                nNewValVar += '' + vValArray[kk] + ',';
                                        } else {
                                            if (vValArray.length == 1)
                                                nNewValVar += '\'' + vValArray[kk] + '\'';
                                            else if (kk + 1 == vValArray.length)
                                                nNewValVar += '\'' + vValArray[kk] + '\'';
                                            else if (vValArray.length > 1 && kk == 0)
                                                nNewValVar += '\'' + vValArray[kk] + '\',';
                                            else
                                                nNewValVar += '\'' + vValArray[kk] + '\',';
                                        }
                                    }
                                } else {
                                    if (is_nm[i] == 1) {
                                        nNewValVar = '' + val[i] + '';
                                    } else {
                                        nNewValVar = '\'' + val[i] + '\'';
                                    }
                                }
                                if ((i == vl) && (ccol[vl] != ""))
                                    where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' ';
                                else {
                                    where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' ';
                                }
                            }
                        }  // Else if
                    }  // If

                }  // Inner For
            }// Outer For
            /* To Get Count of Records */


            var k = 0;
            for (var i = 0; i < where.length; i++) {
                if (where[i] != "") {
                    WhereArray[k] = "" + where[i];
                    k++;
                }
                //else
                //WhereArray[i] = "NULL";
            }
            str = WhereArray.join(":WHERE:");
            //alert(str+'--ithe');
            var postData = {
                'pgaction' : 'count',
                'sSQL' : sql,
                'WhereArray' : str,
                '_token' : $('[name="_token"]').val()
            };
        }
        // DFS = 'custom'
        else if (DFS == 'byfield') {

            var colflag = 0;
            var colArray = new Array();
            var noColumn = $('#cmbNoFields').val();
            for (i = 1; i <= noColumn; i++) {
                //console.log(i+"-------"+$('#col' + i).val())
                colArray[i - 1] = $('#col' + i).val();

                if ($('#col' + i).val() == '') {
                    colflag = 1;
                    break;
                }
            }
            if (colflag == 0) {
                var postData = {
                    'pgaction' : 'byFieldValue',
                    'sSQL' : params.sSQL,
                    'colName' : colArray.join(','),
                    '_token' : $('[name="_token"]').val()
                };

            } else {
                alert("Columns Should not be Empty");
            }
            // DFS = 'byfield'
        } else if (DFS == 'none') {

            var postData = {
                'pgaction' : 'getCount',
                'sSQL' : sql,
                '_token' : $('[name="_token"]').val()
            };
        }
        // DFS = 'none'

        //For All Type

        $.ajax({
            url : 'model/lsdetails',
            type : 'POST',
            data : postData,
            success : function (response) {
                if (response !== undefined) {
                    var listdis = document.getElementById('LsSQL');
                    listdis.innerHTML = "";
                    var listdisHTML = "";
                    listdisHTML += '<table class="c1" id="c1" style="width:1005px" ><tr><td style="width:136px;">List Segment Details &nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span class="tooltiptext">Sample text.</span></td><td style="width:20px;font-weight: 400;"></td><td style="width:10px;"></td><td style="font-weight: 400;">List Segment Selection Criteria</td><td></td><td style="font-weight: 400">List Segment Description</td><td style="font-weight: 400;width:88px">Universe</td><td style="font-weight: 400; width:60px">Sample %</td><td style="font-weight: 400;">Sample Size</td></tr><tr><td height="10px"></td></tr>';
                    var noRec = new Array();
                    if (DFS == 'custom') {
                        // Add 0: just to state index from 1 //
                        noRows = response.count.length;
                        var resStr = (response.count).join(',');
                        noRec = ("0," + resStr).split(",");
                        ListSegCri = ("W1#" + WhereArray.join("#")).split("#");
                        temp = 1;
                        for (i = 0; i < noRows * 3; i = i + 3) {
                            ListSegDes[temp] = val[i]
                            temp = temp + 1;
                        }

                    } else if (DFS == 'byfield') {
                        Str = ("0:" + response.str).split(":");

                        ListCon = "";
                        ListDetail_temp = response.str.split(":");
                        noRows = Str.length - 2;
                        for (i = 1; i < Str.length - 1; i++) {
                            temp = Str[i].split(",");
                            noRec[i] = temp.pop();
                            ListSegDes[i] = temp.join("_");
                        }
                        temp_C = ListDetail_temp[0].split(",").length;

                        temp = 1;
                        for (i = 1; i <= noRows; i++) {
                            Con_flag = 0;
                            ListDetail = ListDetail_temp[i - 1].split(",");
                            for (j = 0; j < temp_C - 1; j++) {
                                if (j == 0) {
                                    ListCon += "( " + colArray[j] + "='" + $.trim(ListDetail[j]) + "' )";
                                } else {
                                    ListCon += " and ( " + colArray[j] + "='" + $.trim(ListDetail[j]) + "' )";
                                    Con_flag = 1;
                                }

                                if (Con_flag == 1)
                                    ListSegCri[i] = "( " + ListCon + " )";
                                else
                                    ListSegCri[i] = ListCon;

                            }

                            ListCon = "";
                        }

                    } else if (DFS == 'none') {
                        noRec[1] = response.count;
                        ListSegCri[1] = "None";  //Blank /****2017-10-12 ****/
                        ListSegDes[1] = "All";
                        noRows = 1;
                        //document.getElementById('btnGo').setAttribute('disabled', true);
                    }

                    row = "";
                    var LSSM = document.getElementById('cmbLSM').value;
                    var i = 1;
                    for (var j = 1; j <= noRows; j++) {
                        if (noRec[j] > 0) {
                            AI = i + ZeroRec;
                            cid = "spID" + i;
                            coid = "ssID" + i;
                            sp = "sp" + i;

                            switch (LSSM)//Hema
                            {
                                case 'ranNum':
                                case 'topNum':
                                    row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td></td><td width="200px"><label class="txt" id=cri' + i + ' >' + ListSegCri[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input  type="text" class="form-control form-control-sm" id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '>' + noRec[AI] + '</label></td><td><label id=sp' + i + ' class="l1" style="width:50px">100</label></td><td width="100px"><Input type="text" class="form-control form-control-sm" onChange="ssCal(this,\'' + cid + '\',\'' + sp + '\')" style="width:100px" value=' + noRec[AI] + ' id=' + coid + ' onkeypress="return numeric_only(event);" ></td></tr>';
                                    break;
                                case 'ranPer':
                                case 'topPer':
                                    row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td></td><td width="200px"><label class="txt" id=cri' + i + ' >' + ListSegCri[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input class="form-control form-control-sm" type="text"  id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '>' + noRec[AI] + '</label></td><td><Input class="form-control form-control-sm" type="text" value="100" id=sp' + i + ' class="txt1" onChange="calculate(this,\'' + cid + '\',\'' + coid + '\')" onkeypress="return numeric_only(event);" /></td><td width="100px"><label class="l1" id=' + coid + '>' + noRec[AI] + '</label></td></tr>';
                                    break;
                                case 'none':
                                    row += '<tr><td></td><td><label class="custom-control custom-checkbox m-b-3"><input type="checkbox" class="custom-control-input checkbox" id="c' + i + '"><span class="custom-control-label"></span></label></td><td></td><td width="200px"><label class="txt" id=cri' + i + ' >' + ListSegCri[AI] + '</label></td><td width="10px"></td><td style="width:210px" ><input class="form-control form-control-sm" type="text"  id="ld' + i + '" name="ld' + i + '" value=\"' + ListSegDes[AI] + '\"  style="width:200px;font-size: 11px;" onChange="changeSample()" /></td><td><label class="l1" id=' + cid + '>' + noRec[AI] + '</label></td><td><label id=sp' + i + ' class="l1" style="width:50px">100</label></td><td width="100px"><label class="l1" id=' + coid + '>' + noRec[AI] + '</label></td></tr>';
                                    break;
                            }
                            i++;
                        } else {
                            ZeroRec = ZeroRec + 1;
                        }
                    }

                    listdisHTML += row;
                    listdisHTML += '<tr><td height="10px"></td></tr><tr><td colspan="10"><hr></td></tr></table>';

                    listdis.innerHTML = listdisHTML;
                    listdis.style.display = 'block';
                    if (DFS == 'none')
                        document.getElementById('tdAction').style.display = 'none';
                    else
                        document.getElementById('tdAction').style.display = 'block';

                    /*if (noRows == 1)
                        document.getElementById('btnGo').setAttribute('disabled', true);
                    else
                        document.getElementById('btnGo').setAttribute('disabled', false);*/

                    /****************** Change 2017-03-09 Start *****************************/
                    //sample();
                    setTimeout(function () {
                        sample();
                    }, 1500);
                    /****************** Change 2017-03-09 End *****************************/
                }
            }
        });

        //For All Type
    } // else finished //2013


}
/*********************** Changed 2017-03-07 End ***************************/

function numeric_only(e) {
    var unicode = e.charCode ? e.charCode : e.keyCode;
    if (unicode == 8 || unicode == 9 || unicode == 46 || (unicode >= 48 && unicode <= 57)) {
        return true;
    } else {
        return false;
    }
}

function init() {

    // Define various event handlers for Dialog
    var calc = function () {
        cfl = parseFloat(document.frmcalc.cmbcfl.value);
        pp = document.frmcalc.txtrr.value;
        err = parseFloat(document.frmcalc.txterr.value);
        var z;
        if (cfl == '99') {
            z = 2.5758
        } else if (cfl == '95') {
            z = 1.96
        } else if (cfl == '90') {
            z = 1.6449
        }
        p = (100 - pp);
        fl = (z * z * pp * p) / (err * err)
        document.getElementById('txtsize').innerHTML = Math.round(fl)
    };
    var handleNo = function () {

        this.hide();
    };
    var resetcalc = function () {
        document.frmcalc.reset();
        document.getElementById('txtsize').innerHTML = "";

    };
    // Instantiate the Dialog
    YAHOO.add_Subgroup.calculator = new YAHOO.widget.SimpleDialog("divcalc",
        {
            width: "400px",
            visible: false,
            draggable: true,
            xy: [375, 20],
            modal: false,
            iframe: true,
            close: true,
            constraintoviewport: true,
            buttons: [
                {text: "Calculate", handler: calc},
                {text: "Clear", handler: resetcalc},
                {text: "Close", handler: handleNo}
            ]
        });

    YAHOO.add_Subgroup.calculator.render();
    YAHOO.util.Event.addListener("show", "click", YAHOO.add_Subgroup.calculator.show, YAHOO.add_Subgroup.calculator, true);
}

function autoGetListDetail() {
    if ($('#cmbLSM').length != 0) {
        if ($('#cmbLSM').val() != "") {
            ListSegDetails();
        }
    }

    if ($('#cmbSC').length != 0) {
        if ($('#cmbSC').val() != "") {
            sample();
        }
    }
}

function addSectionNewSeg(rowIds, secIds, flag, section, title) {
    var ntitle = title.replace('^', '"');
    ntitle = ntitle.replace('^', '"');

    var cColOptions = '<option value=""></option>';
    var List_Level = params.list_level;
    var customClass = '';
    var sectionType;
    var color = '';
    sectionType = 'F';

    $.ajax({
        type: 'GET',
        url: 'getfieldtypesforfilter',
        data : {
            _token : $('[name="_token"]').val(),
            List_Level : List_Level,
            sectiontype : sectionType
        },
        async: true,
        beforeSend: function () {
            $('#ccolCell_' + secIds).text('Loading...');
        },
        success: function (data) {
            cColOptions = data.colOptions;

            /*for (var i = 0; i < cColArray.length; i++) {
                    cColOptions += '<option value="' + $.trim(cColArray[i]) + '">' + $.trim(cColArray[i]) + '</option>';
                }*/
            if (flag == 1) {
                $('#ccolCell_' + secIds).html('<select class="form-control form-control-sm fst-f ' + customClass + '" style="width:100%;" onchange="getSectionMaterialSeg(' + rowIds + ',' + secIds + ',' + flag + ',this.value,' + section + ',\'' + title + '\');" class="t1" style="width:100%;" id="ttype' + secIds + '" name="ttype11" value=">">' + cColOptions + '</select>');
            } else {
                $('#plusDiv_' + secIds).children('a').attr('onclick', '')
                $('#ccolCell_' + secIds).html('<select class="form-control form-control-sm fst-f ' + customClass + '" style="width:68%;" onchange="getSectionMaterialSeg(' + rowIds + ',' + secIds + ',' + flag + ',this.value,' + section + ',\'' + title + '\');" class="t1" id="ttype' + secIds + '" name="ttype11" value=">">' + cColOptions + '</select>');
                var newRowIds = parseInt(rowIds) + 10;
                var newSecIds1 = parseInt(secIds) + 10;
                var newSecIds2 = parseInt(secIds) + 11;
                var newSecIds3 = parseInt(secIds) + 12;
                $('#row_' + rowIds).after('<div class="divTableRow" id="row_' + newRowIds + '"><div class="divTableCell" style="width: 196px;"><div class="divTable blueTable"><div class="divTableBody"><div class="divTableRow"><div style="' + color + 'vertical-align: middle; visibility: hidden;padding-left: 0px" class="divTableCell" id="info_' + newRowIds + '">' + ntitle + '<input type="hidden" id="tablename_' + newRowIds + '" value="" /><input type="hidden" id="typebox_' + newRowIds + '" value="" /><input type="hidden" id="countSec_' + newRowIds + '" value="0" /></div><div style="width:1%; text-align:right !important;" id="preCross_' + newRowIds + '" class="divTableCell"></div><div style="width:1%;font-size: 10px;text-align:center;" class="divTableCell" id="plusDiv_' + newSecIds1 + '"><a onclick="addSectionNewSeg(' + newRowIds + ',' + newRowIds + ',0,' + section + ',\'' + title + '\');" href="javascript:void(0);"><i class="fas fa-plus-circle font-14 ds-c"></i> </a></div></div></div></div></div><div class="divTableCell"><div class="divTable blueTable"><div class="divTableBody"><div class="divTableRow"><div class="divTableCell" id="ccolCell_' + newSecIds1 + '"></div><div class="divTableCell"  id="opCell_' + newSecIds1 + '"></div><div class="divTableCell"  id="valCell_' + newSecIds1 + '"></div><div style="text-align: center;font-size: 8px;" class="divTableCell" id="plusCell_' + newSecIds2 + '"></div><div class="divTableCell" id="ccolCell_' + newSecIds2 + '"></div><div class="divTableCell" id="opCell_' + newSecIds2 + '"></div><div class="divTableCell" id="valCell_' + newSecIds2 + '"></div><div style="text-align: center;font-size: 8px;" class="divTableCell" id="plusCell_' + newSecIds3 + '"></div><div class="divTableCell" id="ccolCell_' + newSecIds3 + '"></div><div class="divTableCell" id="opCell_' + newSecIds3 + '"></div><div class="divTableCell" id="valCell_' + newSecIds3 + '"></div></div></div></div></div></div>');
            }
        }
    });

}

function getSectionMaterialSeg(rowIds, secIds, flag, tType, section, title) {
    var customClass = '';
    var cColOptions = '<option value=""></option>';
    var List_Level = params.list_level;
    var sectionType = 'F';

    $.ajax({
        type: 'GET',
        url: 'getfields',
        data : {
            _token : $('[name="_token"]').val(),
            field_type : tType,
            sectiontype : sectionType,
            List_Level : List_Level,
        },
        async: true,
        beforeSend: function () {
            $('#ccolCell_' + secIds).text('Loading...');
        },
        success: function (data) {
            //var responseData = data.split('::^');
            $('#tablename_' + rowIds).val(data.Tb_Name);
            $('#ccolCell_' + secIds).text('');
            //var cColArray = responseData[1].split(',');

            cColOptions = data.fields;

            /*for (var i = 0; i < cColArray.length; i++) {
                    cColOptions += '<option value="' + $.trim(cColArray[i]) + '">' + $.trim(cColArray[i]) + '</option>';
                }*/
            var opId = 'op' + secIds;
            $('#ccolCell_' + secIds).html('<select class="form-control form-control-sm fst-f ' + customClass + '" style="width:100%;" onchange="getColSeg(this,\'' + opId + '\',\'' + secIds + '\',\'' + section + '\');" class="t1" style="width: 112px; !important;" name="ccol' + secIds + '" id="ccol' + secIds + '" value=">">' + cColOptions + '</select>');
        }
    });

    if (flag == 0) {
        var preRowIds = parseInt(rowIds) - 10;
        if (section != 2 && section != 3) {
            $('#plusDiv_' + rowIds).text('AND');
        } else {
            $('#plusDiv_' + rowIds).text('');
        }
        $('#plusDiv_' + rowIds).text('');
        var numRows = $('#numRows_4').val();
        numRows = parseInt(numRows) + 1;
        $('#numRows_4').val(numRows);
        console.log('numRows_4--',$('#numRows_4').val());
        $('#plusDiv_' + rowIds).html('<a style="display:block;" class="crosss" onclick="removeSectionSeg(' + rowIds + ',' + secIds + ',' + section + ');" href="javascript:void(0);"><i class="fas fa-trash font-14"></i> </a>');
    }
    if (flag == 1) {
        $('#logCell_' + secIds).hide();
        $('#plusCell_' + secIds).html('');
        //$('#plusCell_' + secIds).text('OR');
        $('#plusCell_' + secIds).html('<select class="dvd form-control form-control-sm" style="width:100%;" id="log'+parseInt(secIds - 1)+'" name="log'+parseInt(secIds - 1)+'"><option value=" OR ">OR</option><option value=" AND ">AND</option></select>');
        var countSec = $('#countSec_' + rowIds).val();
        countSec = parseInt(countSec) + 1;
        $('#countSec_' + rowIds).val(countSec);
    }

    $('#opCell_' + secIds).html('<select class="form-control form-control-sm ' + customClass + '" style="width:100%;" id="op' + secIds + '" name="op' + secIds + '"><option value=" "></option><option value="0">&gt;</option><option value="1">&lt;</option><option value="2">&gt;=</option><option value="3">&lt;=</option><option selected="" value="4">=</option><option value="5">!=</option><option value="6">in</option><option value="7">not in</option><option value="8">like</option><option value="9">not like</option></select>');
    $('#valCell_' + secIds).html('<div class="form-group"><input class="form-control form-control form-control-sm  ' + customClass + '" style="width:100%;" id="val' + secIds + '" name="val' + secIds + '" type="text"></div>');

    var nextIds = parseInt(secIds) + 1;

    $('#plusCell_' + nextIds).html('<a onclick ="addSectionNewSeg(' + rowIds + ',' + nextIds + ',1,' + section + ',\'' + title.toString() + '\');" href="javascript:void(0);"><i class="fas fa-plus-circle font-14 ds-c"></i></a>');

    if (countSec != undefined && countSec == 1 && section != 1) {
        $('#plusCell_' + nextIds).html('<a onclick ="addSectionNewSeg(' + rowIds + ',' + nextIds + ',1,' + section + ',' + title + ');" href="javascript:void(0);"><i class="fas fa-plus-circle font-14 ds-c"></i> </a>');
    }
}

function removeSectionSeg(rowIds, secIds, section) {

    var nxtRowIds = parseInt(rowIds) + 10;
    var titlelevel = $('#titlelevel_' + section).val();
    if (rowIds == $('#titlelevel_' + section).val()) {

        for (var i = 1; i <= $('#numRows_4').val(); i++) {
            titlelevel = parseInt(titlelevel) + 10;
            if ($('#info_' + titlelevel).length > 0) {
                $('#titlelevel_' + section).val(titlelevel);
                $('#info_' + titlelevel).css('visibility', 'visible');
                break;
            }

        }

    }
    $('#row_' + rowIds).remove();
}

function checkfilterval(val, id, secIds) {
    if (val.indexOf(",") > -1) {
        val = val.replace(",", "#");
        $('#' + id).val(val);
    }

}

function showCross(ids) {
    $('#preCross_' + ids + ' .cross').show();
}

function hideCross(ids) {
    $('#preCross_' + ids + ' .cross').hide();
}

function getColSeg(obj, optId, secIds) {
    var val = obj.value;
    var selId = obj.id;
    if (val != "") {
        getCustomFieldMetaSeg(val, selId, 1, 1, optId, secIds);
        var sql = params.sSQL;
        //alert(val+'--'+sql);
        var sqlPart = sql.split('FROM');
        //alert(sqlPart[0]);
        //alert(sqlPart[0].indexOf(val))

        if (sqlPart[0].indexOf('*') < 0) {
            if (sqlPart[0].indexOf(val) < 0) {
                params.sSQL = sqlPart[0] + ',' + val + ' FROM ' + sqlPart[1];
                localStorage.setItem('params',JSON.stringify(params));
            }
        }
    }
}

function changeValSeg(val, secIds) {
    var notAllowed = ['0', '1', '4', '8', '8.1', '8.2'];
    if (isInArray(val, notAllowed)) {
        $('#valCell_' + secIds).html('');
        $('#valCell_' + secIds).html('<input class="form-control form-control-sm" style="width:100%;" id="val' + secIds + '" name="val' + secIds + '" type="text">');
    } else {
        var colname = $('#ccol' + secIds).val();
        getCustomFieldMetaSeg(colname, 'ccol' + secIds, 1, 1, 'op' + secIds, secIds);
        $('#op' + secIds).val(val);
    }
}

function getCustomFieldMetaSeg(val, select_id, numF, divNum, optId, secIds) {
    //alert(optId);
    //var colNewArray = select_id.split('_');

    $.ajax({
        type: 'get',
        url: 'getcolbycustom',
        data : {
            _token : $('[name="_token"]').val(),
            colId : select_id,
            sectiontype : 1,
            colName : val,
            secIds : secIds,
        },
        async: false,
        success: function (data) {
            var responseData = data.split(':::^');
            //console.log(responseData);

            if (responseData[1] != '0') {
                $('#valCell_' + secIds).html('');
                $('#valCell_' + secIds).html(responseData[1]);
                setTimeout(function () {
                    //var idss = colNewArray[1];
                    $("#val" + secIds).multiselect({
                        close: function () {
                            //get_filter_summary();
                        },
                        header: true, //"Region",
                        selectedList: 1, // 0-based index
                        nonSelectedText: 'Select Values'
                    }).multiselectfilter({label: 'Search'});

                    $("#val" + secIds + "_ms").attr('style', 'width:100% !important;height: 16px; color: #1c94c4; background-color: white !important; border-bottom-color: #1c94c4; border-top-color: #1c94c4; border-right-color: #1c94c4; border-left-color: #1c94c4; border-bottom-right-radius : 0px; border-bottom-left-radius:0px; border-top-right-radius:0px; border-top-left-radius: 0px; font-size: 0.8em;');
                }, 200);

            } else {
                $('#valCell_' + secIds).html('<input class="form-control form-control-sm" style="width:100%;" id="val' + secIds + '" name="val' + secIds + '" type="text">');
            }

            $('#opCell_' + secIds).html('');
            $('#opCell_' + secIds).html(responseData[2]);
            $('#' + optId).attr('style', 'width:100%');
        }
    });

}

function isInArray(value, array) {
    return $.inArray(value, array) != -1;
}
