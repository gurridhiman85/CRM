<form class="form-horizontal ajax-Form" id="createcamp" action="createcampaign" class="ajax-Form" method="post">
    {!! csrf_field() !!}
    <div class="form-body">
        <div class="card-body p-2">

            <div class="row">
                <div class="col-md-12 p-0">
                    <div class="form-group">
                        <label class="control-label">Name</label>
                        <input type="text" name="campaign_name" class="form-control form-control-sm" id="campaign_name" autocomplete="off" onkeypress="return campaignname();" onkeyup="return campaignname();">
                        <small id="indicationMsg" class="form-control-feedback ds-l"></small>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12 p-0">
                    <div class="form-group">
                        <label class="control-label">Description</label>
                        <input id="description" class="form-control form-control-sm" name="description">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 p-0">
                    <div class="form-group">
                        <label class="control-label">Type</label>
                        <select id="lookuptype" name="lookuptype" class="form-control form-control-sm">
                            <option value="phone">Phone</option>
                            <option value="Taxable">Taxable</option>
                            <option value="Emailable">Emailable</option>
                            <option value="Mailable">Mailable</option>
                            <option value="Callable">Callable</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 p-0">
                    <div class="form-group">
                        <label class="control-label">List</label>
                        <select id="list" name="list" class="form-control form-control-sm">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row pull-right">
                <input type="hidden" name="sql" class="form-control form-control-sm" id="sql" value="{!! $sql !!}">
                <input type="hidden" name="camp_id" class="form-control form-control-sm" id="camp_id">
                <input type="hidden" name="params" class="form-control form-control-sm" id="params">
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="display: block !important;">
                    <div class="input-group pull-right">
                        <button type="button" onclick="createcampaign()" class="btn btn-info font-12 s-f" title="Send Report" >Create</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="application/javascript">

    function getDefaultStorage(postData){
        $.ajax({
            url: 'campaign/generatequickmeta',
            type: 'POST',
            data: postData,
            async : false,
            beforeSend : function(){
                NProgress.start(true);
            },
            success: function (response) {
                NProgress.done();
                if (response !== undefined) {
                    if(response.count > 0){
                        var fivePer = Math.round(parseInt(response.count) * 5/100);
                        var ninetyFivePer = parseInt(response.count) - parseInt(fivePer);



                        //Segment Section default - start
                        var cgcol0 = $('#crGDis0').val();
                        var cgcol1 = '';//$('#crGDis1').val();
                        var cgsumid0 = $('#crOfferCust0').val();
                        var cgsumid1 = '';//$('#crOfferCust1').val();
                        var cgcost0 = $('#crCost0').val();
                        var cgcost1 = '';//$('#crCost1').val();
                        var crchkgroup = $('#crchkgroup').val();
                        var CGD = '';  var cellSample = '';
                        if(crchkgroup == 'Y'){
                            CGD = cgcol0+":"+cgcol1+"^"+cgsumid0+":"+cgsumid1+"^"+cgcost0 +":" + cgcost1;// "CTRL-Control:^:1^0:";
                            cellSample = "5:95^" + fivePer + ":" + ninetyFivePer;
                        }else{
                            CGD = ":^:1^:";
                            cellSample = "100^" + response.count;
                        }

                        var LSD = ":None^:All^:100^:" + response.count + "^:" + response.count;

                        if (localStorage.getItem("params") === null) {
                            var parms = {
                                CGD : CGD,
                            };
                            localStorage.setItem('params',JSON.stringify(parms));
                            var parm = JSON.parse(localStorage.getItem('params'));
                        }else{
                            var parm = JSON.parse(localStorage.getItem('params'));
                        }

                        var r4 = $('#description').val();

                        var currentTime = new Date();
                        var r9 = currentTime.getMonth() + 1;
                        var r10 = currentTime.getDate();
                        var r8 = currentTime.getFullYear();

                        parm.ADQsql =  "";
                        parm.CGD = CGD;
                        parm.DFS = "none";
                        parm.LSD = LSD;
                        parm.cellSample = cellSample;
                        parm.cg = "N";
                        parm.chkCG = crchkgroup;
                        parm.lssc = "";
                        parm.segFilterCriteria = "";
                        parm.segFilterCondition = "";
                        parm.lssm = "none";
                        parm.noCG = "1";
                        parm.noLS = 0;
                        parm.proporation = "cmbAEG";
                        parm.sel_criteria = "cmbPU";

                        var selectedFields = (parm.selected_fields).split(',');
                        var expoCol = 'CampaignID:false|SegmentID:true|GroupID:true|';
                        $.each(selectedFields,function(key,item){
                            expoCol += item + ':true|';
                        })
                        parm.CGOpt = "Y";
                        parm.eData = expoCol;
                        parm.eExt = "xlsx";
                        parm.eFile = parm.listShortName;
                        parm.eFolder = "Public";
                        parm.saveCD = "Y";
                        parm.saveFile = "Y";
                        parm.Type = 'C';
                        parm.Objective = 'Protect';
                        parm.Brand = 'RD';
                        parm.Channel = 'EM';
                        parm.Category = r4;
                        parm.ListDes = '';
                        parm.Wave = '1';
                        parm.Start_Date = r8 + '/' + r9 + '/' + r10;
                        parm.Interval = '45';
                        parm.ProductCat1 = '';
                        parm.ProductCat2 = '';
                        parm.SKU = '';
                        parm.Coupon = '';
                        localStorage.setItem('params',JSON.stringify(parm));
                    }
                }
            }
        })
    }
    var dDate = '{!! date('Ymd_Hi') !!}';
    function createcampaign(){
        if($('#campaign_name').val() != ""){
            $.ajax({
                url: 'campaign/getlist',
                type: 'GET',
                async: false,
                success: function (data) {
                    var flag = 0;
                    var CampNameStr = data;
                    var nameArray = CampNameStr.list;
                    for (var i = 0; i < nameArray.length; i++) {
                        if (nameArray[i].t_name.toLowerCase() == ($('#campaign_name').val()+ '_' + dDate).toLowerCase()){
                            flag = 1;
                        }

                    }
                    if (flag == 1) {
                        var data = {
                            'title': 'Campaign Name already exists....Please enter new campaign name',
                            'text' : '',
                            'butttontext' : 'Ok',
                            'cbutttonflag' : false
                        };
                        ACFn.display_confirm_message(data);
                    }else{
                        $.ajax({
                            url: 'campaign/seq',
                            type: 'GET',
                            async: false,
                            success: function (data) {
                                var campid = data.cid;
                                $('#camp_id').val($.trim(data.cid));



                                var listShortName = $('#campaign_name').val();
                                var list_name = listShortName + '_' + dDate;
                                var list_level = 'Contact_View';
                                var list_fields = '{!! $columns !!}';
                                var description = $('#description').val()
                                var is_public = 'N';
                                var custom_sql = 'Y';
                                var currentTime = new Date();
                                var r9 = currentTime.getMonth() + 1;
                                var r10 = currentTime.getDate();
                                var r8 = currentTime.getFullYear();
                                var filter_condition = '';
                                var Customer_Exclusion_Condition = '';
                                var Customer_Inclusion_Condition = '';
                                var selected_fields = '{!! $columns !!}';
                                var sqlQuery = $('#sql').val();
                                //var sql = sqlQuery.replace(/\\\"/g, "\"");
                                //var sqlQuery = sqlQuery.replace(/\'/g, "\''");
                                var rv = '';
                                var cv = '';
                                var fu = '';
                                var sv = '';
                                var sa = '';
                                var ct = '';
                                var cI = '';
                                var as = '';
                                var lv = '';
                                var rstr = rv + "^" + cv + "^" + fu + "^" + sv + "^" + sa + "^" + ct + "^" + as + "^" + lv;

                                var list_format = 'default';
                                var report_orientation = 'landscape';

                                var params = {
                                    'row_id' : '',
                                    'CID' : campid,
                                    'CampaignID' : campid,
                                    'CName' : list_name,
                                    'sSQL' : sqlQuery,
                                    'listShortName' : listShortName,
                                    'list_level' : list_level,
                                    'list_fields' : list_fields,
                                    'rStr' : rstr,
                                    'list_format' : list_format,
                                    'report_orientation' : report_orientation,
                                    'filter_condition' : filter_condition,
                                    'Customer_Exclusion_Condition' : Customer_Exclusion_Condition,
                                    'Customer_Inclusion_Condition' : Customer_Inclusion_Condition,
                                    'selected_fields' : selected_fields,
                                    'Sort_Column' : 'DS_MKC_ContactID',
                                    'Sort_Order' : 'DESC',
                                    'is_public' : is_public,
                                    'custom_sql' : custom_sql,
                                    'meta_description' : description,
                                    'cI' : cI,
                                    'schedule_action' : 'Sch_campaign1',
                                };
                                localStorage.setItem('params',JSON.stringify(params));
                                //var sSql =  sqlQuery.split('Order By');
                                var postData = {
                                    'pgaction' : 'getCount',
                                    'sSQL' : sqlQuery,
                                    '_token' : $('[name="_token"]').val()
                                };
                                getDefaultStorage(postData);

                                var contactfilters = '';
                                var exclusionsfilters = '';
                                var inclusionsfilters = '';
                                var params = JSON.parse(localStorage.getItem('params'));

                                $.ajax({
                                    url : 'campaign/cc_sch_data',
                                    type : 'POST',
                                    async : false,
                                    data : {
                                        pgaction : 'Sch_campaign1',
                                        CID : campid,
                                        CName : list_name,
                                        SMTPStr : 'N',
                                        ftp_flag : 'N',
                                        ftpData : '',
                                        SFTP_Attachment : '',
                                        SR_Attachment : $('#list').val() == 'Y' ? 'onlylist' : 'none',
                                        Lookup_Type : $('#lookuptype').val(),
                                        SREmailStr : 'N',
                                        ShareStr : 'N',
                                        rtype : 'RI',
                                        params : JSON.stringify(params),
                                        filterVal : contactfilters,
                                        customerExclusionVal : exclusionsfilters,
                                        customerInclusionVal : inclusionsfilters,
                                        _token : $('[name="_token"]').val()
                                    },
                                    beforeSend : function(){
                                        NProgress.start(true);
                                    },
                                    success : function (data) {
                                        NProgress.done();
                                        $('#modal-popup').modal('hide');
                                        localStorage.removeItem('record');
                                        localStorage.removeItem('params');
                                        localStorage.removeItem('contactfilters');
                                        localStorage.removeItem('exclusionsfilters');
                                        localStorage.removeItem('inclusionsfilters');
                                    },
                                    complete : function () {
                                        $('#modal-popup').modal('hide');
                                        ACFn.display_message('Campaign created successfully','','success');
                                    }
                                })
                            }
                        });
                    }
                }
            });

        }
    }

    function campaignname() {
        var string = document.getElementById("campaign_name").value;
        if (string.length > 20) {
            $('#indicationMsg').fadeIn('slow');
            $('#indicationMsg').text('Campaign Name should be less than 20 characters');
            $('#indicationMsg').attr('style', 'color:red;');
            setTimeout(function () {
                $('#indicationMsg').fadeOut(2000);
                $('#indicationMsg').text('');
            }, 3000);
            return false;
        } else if (/[^a-zA-Z0-9_\-\/]/.test(string)) {

            $("#campaign_name").val($.trim(string.replace(/[`~!@#$%^&*()|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '')));
            $('#indicationMsg').fadeIn('slow');
            $('#indicationMsg').text('Special Characters are not Valid for this field');
            $('#indicationMsg').attr('style', 'color:red;');
            setTimeout(function () {
                $('#indicationMsg').fadeOut(2000);
                $('#indicationMsg').text('');
            }, 3000);
            return false;
        }
    }

</script>

