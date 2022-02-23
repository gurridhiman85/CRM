<div class="dataTables_info " id="taskList_info" role="status" aria-live="polite">


    <div class="ticket-pagination">
        {!! \App\Helpers\Helper::pagination_v2($total_records,$records_per_page,$page,'first_screen',$position,count($records),3) !!}
    </div>
</div>
<script>


    function pagination_v3(obj, type) {

        var track_page = $(obj).attr('data-idx');
        var url = 'lookup/finddupes/{!! $atype !!}?show_entries=20&tabid=20&rtype=pagination&page=' + track_page;
        //$(".resall").removeClass('current');
        //$(obj).addClass('current');
        //$('#first_screen').css("opacity", "0.6");
        load_contents_v3(track_page, type, url);
    }

    //Ajax load function
    function load_contents_v3(track_page, type, url) {
        $('.loading-info').show();
        $("html, body").animate({scrollTop: 0}, "slow");
        $.ajaxSetup({
            headers: {'X-CSRF-Token': $('input[name="_token"]').val()}
        });

        var filtersArr = getFilters($('#filter_form'));
        $.get(url, {'page': track_page, type: type,filters : filtersArr,mergeKeys : JSON.parse(localStorage.getItem('MergeKeys'))}, function (data) {
            loading = false; //set loading flag off once the content is loaded
            if (data.html.trim().length == 0) {
                //notify user if nothing to load
                $('.loading-info').html("No more records!");
                return;
            }
            $('.loading-info').hide(); //hide loading animation once data is received
            $("#duplicates").html(data.html); //append data into #results element
            initJS($("#duplicates"));
            $(".dupes-page").html(data.paginationHtml); //append data into #results element
            //$('#first_screen').css("opacity", "1");
//            $("#tickets_table").DataTable(dtobj);
        }).fail(function (xhr, ajaxOptions, thrownError) { //any errors?
            alert(thrownError); //alert with HTTP error
        })
    }

</script>
