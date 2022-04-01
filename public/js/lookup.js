$(document).ready(function () {
   // setTimeout(function () {


        $('#slimtest1').perfectScrollbar();

    //},1000);

    var autosearch_field = $('.ajax-search');
    autosearch_field.on('keyup change paste',function(e) {
        if((e.type == 'keyup' && e.target.tagName == 'INPUT') || (e.type == 'change' && e.target.tagName == 'SELECT')){
            var obj = $(this);
            var oldVal = obj.val();
            delay(function(){
                var fvalue = $.trim(obj.val());
                $('[name="searchterm"]').val(fvalue);
                $('.tab-ajax li a.active').trigger('show.bs.tab');
            }, 1000 );
        }
    });

    ACFn.ajax_download_file = function(F,R){
        if(R.success){
            window.location.href = R.download_url;
        }
    };

    ACFn.ajax_second_screen = function( F,R){
        $('.lookup-filters').hide();
        $('.lookup-content').removeClass('col-lg-10')
            .addClass('col-lg-12');

        $('.text-themecolor').text('Lookup');
        $('.after-filter').hide();
        $("#updateContactBtn").text('Save Contact');
        if (R.response !== undefined) {
            $('.firstscreen').hide();
            $('.secondscreen').show();
            $('.asBtn').show();
            $('#customCheck').hide();

            $('#tab_20').removeClass('active');
            $('#tab_20').html('');
            $('#tab_21').addClass('active');
            $('#tab_21').html(R.html);
            initJS($('#tab_21'));
            $('#edit_type').val('old')
            $('.sas').show();
            $('.sd').show();
            var is_person2_exist = 0;
            var is_person1_email_exist = 0;
            var is_person2_email_exist = 0;
            $.each(R.response, function(idx, obj) {

                idx = idx.toLowerCase();
                //console.log(idx,'------------');
                if($.trim(obj) != null  && $.trim(obj) != ''){

                    if(idx == 'ds_mkc_contactid'){
                        $('.txt'+idx).val('');
                        $('.txt'+idx).val(obj);


                    }else if(idx == 'ds_mkc_household_num'){

                        $('.txt'+idx).html(obj);

                    }else if(idx == 'ds_mkc_householdid'){
                        //console.log(idx+'---('+obj+')');
                        /*var link = '<a href="javascript:void(0);" onclick="jsfilterfiller($(this))" data-target-id="txtExtendedname">' + obj + '</a>';
                        $('.txt'+idx).html('');
                        $('.txt'+idx).html(link);*/
                        $('.txt'+idx).val(obj);

                    }else if(idx == 'phone_type' || idx == 'phone2_type' || idx == 'opt_email' || idx == 'opt_email_sec'){
                        //console.log('ENTER ===='+obj)
                        if(obj != 'TBD'){
                            $('.txt'+idx).val('');
                            $('.txt'+idx).val(obj);
                        }else
                            $('.txt'+idx).val('');

                    }else{
                        //console.log(' enter -- ',obj);

                        if($('.txt'+idx).length && $('.txt'+idx)[0].nodeName == 'TD'){
                            $('.txt'+idx).html('');
                            $('.txt'+idx).html(obj);
                        }else{
                            obj = obj != '1900-01-01' ? obj : '';
                            $('.txt'+idx).val('');
                            $('.txt'+idx).val(obj);
                        }
                    }
                }else if(idx == 'email' || idx == 'email2' || idx == 'email_sec2' || idx == 'phone' || idx == 'phone2' || idx == 'phone2'){
                    //console.log(idx,obj)
                    if($.trim(obj) == null || $.trim(obj) == ''){
                        $('.txt'+idx).val('');
                        //console.log('Enter')
                        setTimeout(function () {
                            if(idx == 'email')
                                $('.txtopt_email').val('')
                            if(idx == 'email2')
                                $('.txtopt_email2').val('');
                            if(idx == 'phone')
                                $('.txtphone_type').val('');
                            if(idx == 'phone2')
                                $('.txtphone2_type').val('');
                        },500)

                    }
                }else if(idx == 'firstname2' || idx == 'lastname2' || idx == 'dharmaname2' || idx == 'middlename2'){

                    $('.txt'+idx).val('');
                    if(obj == "" || obj == null){
                        is_person2_exist++;
                        //console.log(is_person2_exist,idx,obj)
                    }
                }else if(idx == 'company'){
                    $('.txt'+idx).val('');
                    //console.log(idx+"-----------------------","("+obj+")")
                    if($.trim(obj) == "" || obj == null){
                        setTimeout(function () {
                            $('.txtcompanyinclude').val('');
                        },2000)
                    }
                }else{
                    $('.txt' + idx).val('');
                    /*$('.txt' + idx).html('&nbsp;');*/
                    if(idx == 'ds_mkc_household_num'){

                        $('.txt' + idx).html('&nbsp;');

                    }
                    //console.log(obj);
                }

            });

            if(is_person2_exist == 4){  // If firstname2, lastname2,DharmaName2 &Middlename2 is empty then, person2 data will blank
                //$('select.p2').val('');
                //$('input.p2').val('');

            }
            $('.dis').attr('disabled',true);

            /*************** Header Label Start****************************************/
            $('#selectedCompId').val(R.response.ds_mkc_contactid);
            $('#DownloadAllBtn').attr('data-href', 'lookup/downloadallreports/' + R.response.ds_mkc_contactid);

            /*$('#ContactIDLabel').html("&nbsp;&nbsp;&nbsp;     ContactID:  " + R.response.DS_MKC_ContactID);

            $('#HouseHoldIDLabel').html("&nbsp;&nbsp;&nbsp;     Household ID:  " + R.response.ds_mkc_householdid);
           */

            $('#HouseHoldNameLabel').html(" Extended Name:  " + R.response.Extendedname);
            //$('#HouseHoldNameLabel').attr("style", "color:rgb(0, 102, 204);");

            $('#OverallLabelSegment').html(" ZSS Segment:  " + R.response.ZSS_Segment);
            //$('#OverallLabelSegment').attr("style", "color:rgb(0, 102, 204);");

            $('#LifeCycleLabelSegment').html(" Life Cycle Segment:  " + R.response.LifecycleSegment);
            //$('#LifeCycleLabelSegment').attr("style", "color:rgb(0, 102, 204);");
            /*************** Header Label End****************************************/
            /*var filters = getFilters($('#filter_form'));
            ACFn.sendAjax('lookup/sadetails/'+R.contactid,'get',{filters : filters},$('#salesDiv'));
            ACFn.sendAjax('lookup/touchesdetails/'+R.contactid,'get',{filters : filters},$('#touchDiv'));*/
        }

    };

    ACFn.ajax_add_contact = function(F,R){
        $('.lookup-filters').hide();
        $('.lookup-content').removeClass('col-lg-10')
            .addClass('col-lg-12');

        $('.text-themecolor').text('Add Contact');
        $('.firstscreen').hide();
        $('.secondscreen').hide();
        $('.asBtn').show();
        $('#customCheck').hide();
        $('.after-filter').hide();
        $('#tab_20').removeClass('active');
        $('#tab_20').html('');
        $('#tab_21').addClass('active');
        $('#tab_21').html(R.html);
        initJS($('#tab_21'));
        //$('#updateContactBtn').text('Save');
        $('#edit_type').val('new');
        $('.c2').show();
        $('input[type=text]').css('background-color', '#eff6ff');
        $('[data-editable=true]').css('background-color', '#ffffff');
        $('.p1').val('');
        $('.p2').val('');
        $('.h').val('');
        $('.dis').val('');
        $('.txtnotes').val('');
        $('.input').html('');
        $('.dh').hide();
        $('.sas').hide();
        $('.sd').hide();
        $("#updateContactBtn").text('Add Contact');

        $('.txtds_mkc_householdid').html(R.newid);
        $('.txtds_mkc_contactid').text(R.newid);

        $('.txtds_mkc_household_num').html('&nbsp;');


    }

    ACFn.ajax_import_contact = function(F,R){
        $('.text-themecolor').text('Import Contact');
        $('.firstscreen').hide();
        $('.secondscreen').hide();
        $('.asBtn').hide();
        $('#customCheck').hide();
        $('.after-filter').hide();
        $('#tab_20').removeClass('active');
        $('#tab_20').html('');
        $('#tab_21').addClass('active');
        $('#tab_21').html(R.html);
        initJS($('#tab_21'));
        //$('#updateContactBtn').text('Save');
        $('#edit_type').val('new');
        $('.c2').show();
        $('input[type=text]').css('background-color', '#eff6ff');
        $('[data-editable=true]').css('background-color', '#ffffff');
        $('.p1').val('');
        $('.p2').val('');
        $('.h').val('');
        $('.dis').val('');
        $('.txtnotes').val('');
        $('.input').html('');
        $('.dh').hide();
        $('.sas').hide();
        $('.sd').hide();



    }

    ACFn.ajax_SA_Details = function(F,R){
        if(R.success){
            F.html(R.html);
            setTimeout(function () {
                $('.SA-pagination').html(R.pagination_html);
                //$('.SA-pagination').show();
            },4000)

        }
    }

    ACFn.ajax_touch_Details = function(F,R){
        if(R.success){
            $('#touchDiv').html('');
            $('#touchDiv').html(R.html);
            setTimeout(function () {
                $('.touch-pagination').html(R.pagination_html);
                //$('.SA-pagination').show();
            },4000)

        }
    }

    ACFn.ajax_bulk_merge = function (F,R) {
        if(R.success){
            $('.loading-info').hide();
            $('#modal-popup').modal('hide');
            $('.tab-ajax li a.active').trigger('show.bs.tab');
            ACFn.display_message("Congratulations! The selected records have been merged",'','success');

        }else{
            $('.loading-info').hide();
            ACFn.display_message("Sorry ! Something is wrong.");
        }
    }

    $("#filtersApplied li").length == 0 ? $(".clear-btn").hide() : $(".clear-btn").show();
})


var MergeKeys = [];
function singleClick(obj) {

    if(obj.is(':checked') == true){ // Checked

        MergeKeys = MergeKeys.length > 0 ? MergeKeys : [];

        if(MergeKeys.length == 0){
            var entry = {
                'id': obj.val(),
                'pk': true
            };
            MergeKeys.push(entry);
        }else if(MergeKeys.length == 1){
            var entry = {
                'id' : obj.val(),
                'pk' : false
            };
            MergeKeys.push(entry);

        }else if(MergeKeys.length > 1){
            ACFn.display_message("Only two records can be merged at one time.",'error');
            obj.prop('checked',false);
            return false;
        }
    }else{ // unchecked

        var id = obj.val();
        MergeKeys = MergeKeys.filter(function(obj , idx) {
            if(idx == 0 && MergeKeys.length > 1){
                MergeKeys[1].pk = true;
            }
            return obj.id !== id;
        });
    }
    MergeKeys.length == 0 ? $('#refreshBtn').hide() : $('#refreshBtn').show();
    console.log(MergeKeys);
    localStorage.removeItem('MergeKeys');
    localStorage.setItem('MergeKeys',JSON.stringify(MergeKeys));
}

function refreshMergeList() {
    var contactIds = [];

    MergeKeys.forEach(function(item) {
        contactIds.push(item.id)
        $('#contactid').val(contactIds);
    });
    $('.tab-ajax li a.active').trigger('show.bs.tab');
}

function doMerge() {
    if(MergeKeys.length == 0){
        ACFn.display_message("Please select two records for merge. Check the primary record first");
    }else if(MergeKeys.length == 1){
        ACFn.display_message("Please select two records for merge. Check the primary record first.");
    }else{
        refreshMergeList();
        setTimeout(function () {
            var x = confirm("Are you sure you want to merge these two contacts?");
            if(x == true){
                $.ajax({
                    url : 'lookup/domerge',
                    type : 'POST',
                    data : {
                        _token: $('[name="_token"]').val(),
                        MergeKeys: MergeKeys,
                        username: username
                    },
                    dataType : 'JSON',
                    success : function (response) {
                        console.log(response);
                        if(response.success){
                            MergeKeys = [];
                            localStorage.removeItem('MergeKeys');
                            ACFn.display_message("Congratulations! You successfully merged the records.");
                            $('#contactid').val('');
                            $('.tab-ajax li a.active').trigger('show.bs.tab');

                        }
                    }
                });
            }
        },3000)


    }
}

function blankMergeData() {
    //MergeKeys = [];
    //localStorage.removeItem('MergeKeys');
    $('#refreshBtn').hide();
    $('.firstscreen').show();
    $('.secondscreen').hide();
    $('.asBtn').hide();
    $('#customCheck').hide();
    $('#tab_20').addClass('active');
    $('#tab_21').html('');
    $('#tab_21').removeClass('active');
}

function goBack() {
    $('.lookup-filters').show();
    $('.lookup-content').removeClass('col-lg-12')
        .addClass('col-lg-10');

    $('.text-themecolor').text('Lookup');
    //MergeKeys = [];
    $('.firstscreen').show();
    $('.secondscreen').hide();
    $('.asBtn').hide();
    $('#customCheck').hide();
    $('#tab_20').addClass('active');
    $('#tab_21').html('');
    $('#tab_21').removeClass('active');

    $('#filter_form').trigger('submit');
    $('.after-filter').show();
}

function selectAll(obj){
    if(obj.is(':checked')){
        $('.checkbox').each(function(){
            this.checked = true;
        });
    }else{
        $('.checkbox').each(function(){
            this.checked = false;
        });
    }
};

function singleCheckbox(){
    if($('.checkbox:checked').length == $('.checkbox').length){
        $('#select_all').prop('checked',true);
    }else{
        $('#select_all').prop('checked',false);
    }
}

function mergeBulk() {
    var x = confirm("Are you sure you want to merge these contacts ?");
    if(x == true){
        $('.loading-info').show();
        $('#bulkMergeForm').submit();
    }
}

function reviewContact(obj,conId,actionType) {
    var tag = obj.is(':checked') ? 1 : 0;
    ACFn.sendAjax('lookup/reviewcontact','get',{
        tag : tag,
        actionType : actionType,
        contactid : conId,
        type : 'con'
    })

}

function reviewDupsContact(obj,conId) {
    var tag = obj.is(':checked') ? 1 : 0;
    ACFn.sendAjax('lookup/reviewcontact','get',{
        tag : tag,
        contactid : conId,
        type : 'dupsCon'
    })

}

function downloadCMLink(obj){
    var url = obj.data('href');
    var screen = obj.attr('data-screen');
    var prefix = obj.attr('data-prefix');
    var contactid = $('#selectedCompId').val();
    var filters = getFilters($('#filter_form'));
    var downloadableColumns = $('#basic_table2').attr('data-columns-visible') ? $('#basic_table2').attr('data-columns-visible') : '';
    ACFn.sendAjax(url,'GET',{
        prefix : prefix,
        screen : screen,
        filters : filters,
        downloadableColumns : downloadableColumns,
        contactid : contactid
    },obj);
}

function showCreateCampaign() {
    var filters = getFilters($('#filter_form'));
    var downloadableColumns = $('#yajra-table').attr('data-columns-visible') ? $('#yajra-table').attr('data-columns-visible') : '';
    ACFn.sendAjax('lookup/showcreatecampaign','GET',{
        filters : filters,
        downloadableColumns : downloadableColumns
    });
}

function setTab (tabname,tabid,contactid) {
    $('.custom-tab').html('');
    $('.s-f').hide();
    $('.sub-pagination').show();
    $('#DownloadBtn').attr('data-screen',tabname);
    var filters = getFilters($('#filter_form'));

    ACFn.sendAjax(
        'lookup/subtabs',
        'get',
        {
            tabname : tabname,
            tabid : tabid,
            contactid : contactid,
            filters : filters
        },
        $('[data-tabid="'+tabid+'"]')
    );

    //ACFn.sendAjax('lookup/touchesdetails/'+R.contactid,'get',{filters : filters},$('#touchDiv'));


}
