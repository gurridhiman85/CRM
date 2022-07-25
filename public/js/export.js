var colStr = '';
var colArray = new Array();
var resArray = new Array();
var params = JSON.parse(localStorage.getItem('params'));

$(document).ready(function () {
    $('#save').attr('onclick','nextExp();');
    $('.seg-clr-btn').hide();
    oldCampExp();
})

function showExportExp(obj) {
    if (obj.value == 'Y') {
        $('#trFolder').show();
        $('#trFile').show();
        $('#trFF').show();
        $('#trCtrl').show();
        $('#divCol').show();

        //document.getElementById('trFolder').style.display = 'block';
        //document.getElementById('trFile').style.display = 'block';
        //document.getElementById('trFF').style.display = 'block';
        //document.getElementById('trCtrl').style.display = 'block';
        //document.getElementById('divCol').style.display = 'block';
        if (parent.deflag == 1)
            document.getElementById('filename').value = parent.Camp_Name;

        $.ajax({
            url: 'campaign/expgetcol',
            type: 'POST',
            data:
                {
                    'sSQL': params.sSQL,
                    //'selected_fields': $.trim($('#fieldSummaryVal', window.parent.document).text().replace(/&nbsp;/g, ''))+'<|>'+$.trim($('#filtersfieldSummaryVal', window.parent.document).val()),
                    'selected_fields': $.trim(params.selected_fields),
                    '_token': $('[name="_token"]').val()
                },
            success: function (response) {
                if (response !== undefined) {
                    colStr = 'CampaignID,SegmentID,GroupID,' + response.columns;

                    colArray = colStr.split(',');
                    var coldiv = document.getElementById('divCol');
                    var HTMLStr = '<table>', row = '';
                    if (colArray[3] != '')

                        for (var i = 0; i < colArray.length; i++) {
                            id = colArray[i].replace(/ /g, '_');

                            if (colArray[i] == 'CampaignID')
                                row += '<tr height="5px"></tr><tr><td style="width:10px"><label class="custom-control custom-checkbox m-b-0">\n' +
                                    '            <input type="checkbox" id="' + id + '" value="'+ colArray[i] +'" class="custom-control-input checkbox">\n' +
                                    '            <span class="custom-control-label"></span>\n' +
                                    '        </label></td><td>' + colArray[i] + '</td></tr>';
                            else
                                row += '<tr height="5px"></tr><tr><td style="width:10px"><label class="custom-control custom-checkbox m-b-0">\n' +
                                    '            <input type="checkbox" checked id="' + id + '" class="custom-control-input checkbox" value="'+ colArray[i] +'">\n' +
                                    '            <span class="custom-control-label"></span>\n' +
                                    '        </label></td><td>' + colArray[i] + '</td></tr>';
                        }
                    HTMLStr += row + '</table>';
                    coldiv.innerHTML = HTMLStr;

                }
            }
        });
    } else {
        $('#trFolder').hide();
        $('#trFile').hide();
        $('#trFF').hide();
        $('#trCtrl').hide();
        $('#divCol').hide();
        //document.getElementById('trFolder').style.display = 'none';
        //document.getElementById('trFile').style.display = 'none';
        //document.getElementById('trFF').style.display = 'none';
        //document.getElementById('trCtrl').style.display = 'none';
        //document.getElementById('divCol').style.display = 'none';
    }
}

function disableEleExp() {


//  Disable dropdown
    if ((parent.up_flag == 'view') || (parent.up_flag == 'update')) {
        var f = document.getElementsByTagName('select');
        for (var i = 0; i < f.length; i++) {
            f[i].setAttribute('disabled', true)
        }

// Input ---  Checkbox and Input box
        var f = document.getElementsByTagName('input');
        for (var i = 0; i < f.length; i++) {
            if(f[i].type != 'checkbox')
                f[i].setAttribute('disabled', true);
        }
        document.getElementById('save').disabled = true;
        document.getElementById('savebottom').disabled = true;
    }
    /*if (parent.up_flag == 'update')
    {
        document.getElementById('save').disabled = false;
        document.getElementById('save').value = "Next";
    }*/
    //YAHOO.csr.container.wait.hide();

}

function nextExp() {
    var obj = document.getElementById('filename');
    var exportOpt = document.getElementById('cmbsaveexportopt').value;
    if (exportOpt == 'Y') {
        if (obj.value != '') {

            if (/^[a-z][a-z0-9-_]*$/i.exec(obj.value)) {
                var FName = obj.value;
                var Folder = document.getElementById('foldername').value;
                var FType = document.getElementById('cmbexport').value;
                $.ajax({
                    url : 'campaign/expfileexists',
                    type : 'POST',
                    data : {
                        FName : FName,
                        Folder : Folder,
                        FType : FType,
                        _token: $('[name="_token"]').val()
                    },
                    success : function (response) {
                        if (!response.success) {
                            //ACFn.display_message('enter in metadata','','success');
                            parent.promoexportchk = 'Y';
                            //parent.metadateSQL();
                        } else {
                            ACFn.display_message("File Already Exists..Enter New File Name...",'','success');
                            obj.value = '';
                            obj.focus();
                        }
                    }
                });
            } else
                ACFn.display_message("Invalid File Name",'','success');

        } else {
            ACFn.display_message("Invalid File Name",'','success');
        }
    } else {
        parent.promoexportchk = 'Y';
        //parent.metadateSQL();
    }
    sessionExp();
}

function sessionExp() {
    var eData = '';
    var expff = '';
    var promoExport, saveCD = 'N', folderName = '', fileName = '', fileExt = '', CGOpt = 'N';
    var saveFile;
    //promoExport = parent.frames['iframePromoExport'].document;
    saveCD = document.getElementById('cmbsavepromoopt').value;
    saveFile = document.getElementById('cmbsaveexportopt').value;
    //HEMA

    if (saveFile == 'Y') {

        folderName = document.getElementById('foldername').value;
        fileName = document.getElementById('filename').value;
        fileExt = document.getElementById('cmbexport').value;
        CGOpt = document.getElementById('cmbCtrlopt').value;

        if($('#divCol input[type="checkbox"]').length > 0){
            $('#divCol input[type="checkbox"]').each(function(i,elem){
                eData += elem.value + ":" + elem.checked + "|";
            })
        }
    }

    params.saveCD = saveCD;
    params.saveFile = saveFile;
    params.eFolder = folderName;
    params.eFile = fileName;
    params.eExt = fileExt;
    params.CGOpt = CGOpt;
    params.eData = eData;
    localStorage.setItem('params',JSON.stringify(params));
    $('[href="#tab_12"]').trigger('click');

}

function checkPromoExp(obj) {

    if (obj.value == 'Y') {
        $.ajax({
            url: 'campaign/expgetcol',
            type: 'POST',
            data:
                {
                    'sSQL': params.sSQL,
                    //'selected_fields': $.trim($('#fieldSummaryVal', window.parent.document).text().replace(/&nbsp;/g, ''))+'<|>'+$.trim($('#filtersfieldSummaryVal', window.parent.document).val()),
                    'selected_fields': $.trim(params.selected_fields),
                    '_token': $('[name="_token"]').val()
                },
            success: function (response) {
                if (response !== undefined) {

                    var colStr = response.columns;
                    var colArray = new Array();
                    colArray = colStr.split(",");
                    console.log(colArray);
                    var flag = 0;
                    for (var i = 0; i <= colArray.length; i++) {
                        if ($.trim(colArray[i]) == 'DS_MKC_ContactID')
                            flag = 1;
                    }
                    if (flag == 0) {
                        ACFn.display_message("Enter a Query with DS_MKC_ContactID",'','success',5000);
                        obj.value = 'N';
                    }
                }
            }
        });
    }
}

function oldCampExp() {
    /********* 2018-03-23 - changes for hide buttons when view selected -- start ********/
    if (parent.up_flag == 'view') {
        $('.ft').hide();
    }
    /********* 2018-03-23 - changes for hide buttons when view selected -- end ********/
    if ((parent.oldcampclk == 'Y') && (parent.proExp_clrear_flag == 0) && (parent.up_flag != 'new'))  /* && (parent.up_flag != 'new') List selected fields in case of save as 2017-03-07*/
    {
        $.ajax({
            url : 'campaign/expgetpromodata',
            type : 'POST',
            data : {
                tempid : params.CID,
                _token: $('[name="_token"]').val()
            },
            success : function (response) {
                if (response !== undefined) {
                    var resStr = response.promo_data;
                    resArray = resStr.split(",");
                    document.getElementById('cmbsavepromoopt').value = resArray[0];
                    document.getElementById('cmbsaveexportopt').value = resArray[1];
                    if (resArray[1] == 'Y') {
                        document.getElementById('foldername').value = resArray[2];
                        if (parent.up_flag == 'new')
                            document.getElementById('filename').value = parent.Camp_Name;
                        else
                            document.getElementById('filename').value = resArray[3];
                        document.getElementById('cmbexport').value = resArray[4];
                        document.getElementById('cmbCtrlopt').value = resArray[5];

                        $('#trFolder').show();
                        $('#trFile').show();
                        $('#trFF').show();
                        $('#trCtrl').show();
                        //document.getElementById('trFolder').style.display = "block";
                        //document.getElementById('trFile').style.display = "block";
                        //document.getElementById('trFF').style.display = "block";
                        //document.getElementById('trCtrl').style.display = "block";
                        cols = resArray[6].split("|");
                        //colArray = (resArray[6].split("|"))

                        var coldiv = document.getElementById('divCol');
                        var HTMLStr = '<table>', row = '';
                        // alert(resArray[6]);
                        //if(colArray[3]!= '')
                        for (var i = 0; i < (cols.length - 1); i++) {

                            colArray[i] = cols[i].split(":")[0];
                            colName = cols[i].split(":");
                            id = colName[0].replace(/ /g, '_');
                            if (colName[1] == 'false')
                                row += '<tr height="5px"></tr><tr><td style="width:10px"><label class="custom-control custom-checkbox m-b-0">\n' +
                                    '            <input type="checkbox" id="' + id + '" value="'+ colName[0] +'" class="custom-control-input checkbox">\n' +
                                    '            <span class="custom-control-label"></span>\n' +
                                    '        </label></td><td>' + colName[0] + '</td></tr>';
                            else
                                row += '<tr height="5px"></tr><tr><td style="width:10px"><label class="custom-control custom-checkbox m-b-0">\n' +
                                    '            <input type="checkbox" checked id="' + id + '"  class="custom-control-input checkbox checked" value="'+ colName[0] +'">\n' +
                                    '            <span class="custom-control-label"></span>\n' +
                                    '        </label></td><td>' + colName[0] + '</td></tr>';
                        }

                        HTMLStr += row + '</table>';
                        coldiv.innerHTML = HTMLStr;
                        document.getElementById('divCol').style.display = 'block';
                    }
                    if ((parent.up_flag == 'view') || (parent.up_flag == 'update'))
                        window.setTimeout(disableEleExp, 500);
                }
            }
        });
    } else {
        showExportExp(document.getElementById('cmbsaveexportopt'));
        checkPromoExp(document.getElementById('cmbsavepromoopt'));
    }

}

function promoExpoClearExp() {
    parent.promoexportchk = 'N';
    //parent.promoExportSQL();
    parent.proExp_clrear_flag = 1;
}
