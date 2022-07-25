<div class="dataTables_info " id="taskList_info" role="status" aria-live="polite">


    <div class="ticket-pagination">
        {!! \App\Helpers\Helper::pagination_v3($total_records,$records_per_page,$page,'first_screen',$position,count($records)) !!}
    </div>
</div>
<script>

    function pagination_v2(obj, type) {

        var track_page = $(obj).attr('data-idx');
        var url = 'phone/getfirstscreen?show_entries=20&rtype=pagination&page=' + track_page;
        $(".resall").removeClass('current');
        $(obj).addClass('current');
        //$('#first_screen').css("opacity", "0.6");
        load_contents_v2(track_page, type, url);
    }

    //Ajax load function
    function load_contents_v2(track_page, type, url) {
        NProgress.start(true);
        $("html, body").animate({scrollTop: 0}, "slow");
        $.ajaxSetup({
            headers: {'X-CSRF-Token': $('input[name="_token"]').val()}
        });

        var filtersArr = getFilters($('#filter_form'));
        var tabid = $('.customtab2 li a.active').attr('data-tabid');
        var tabname = tabid.replace('_',' ');
        $.post(url, {
            'page': track_page,
            type: type,
            tabname : tabname,
            tabid : tabid,
            filters : filtersArr,
            mergeKeys : JSON.parse(localStorage.getItem('MergeKeys'))
        }, function (data) {
            NProgress.done();
            loading = false; //set loading flag off once the content is loaded
            if (data.html.trim().length == 0) {
                //notify user if nothing to load
                $('.loading-info').html("No more records!");
                return;
            }
            $('.loading-info').hide(); //hide loading animation once data is received
            $("#first_screen").html(data.html); //append data into #results element
            initJS($("#first_screen"));
            $(".all-pagination").html(data.paginationHtml); //append data into #results element
            //$('#first_screen').css("opacity", "1");
//            $("#tickets_table").DataTable(dtobj);
        }).fail(function (xhr, ajaxOptions, thrownError) { //any errors?
            alert(thrownError); //alert with HTTP error
        })
    }

</script>
