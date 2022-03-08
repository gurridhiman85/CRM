$(document).ready(function () {
    $('#slimtest1').perfectScrollbar();

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


function applyFilters() {
    delay(function(){
        $('#filter_form').submit();
    }, 1000 );

}
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
    var screen = obj.data('screen');
    var prefix = obj.data('prefix');
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
