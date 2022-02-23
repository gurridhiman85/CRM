<div class="dataTables_info " id="taskList_info" role="status" aria-live="polite">
    <div class="ticket-pagination">
        {!! \App\Helpers\Helper::pagination_v2($total_records,$records_per_page,$page,'completed_tab',$position,count($records)) !!}
    </div>
</div>

<script>
    function getFilters(F) {
        if (typeof F == 'undefined') {
            if($("#filter_milestone_form").length > 0){
                F = $("#filter_milestone_form");
            }else{
                F = $("#filter_form");
            }

        }
        var filters = [];
        var filtersFlag = false;
        if (F.length) {
            $.each(F.serializeArray(), function (index, element) {
                console.log(element);
                if (typeof filters[element.name] == 'undefined') {
                    filters[element.name] = [];
                }
                if (element.value) {
                    filters[element.name].push(element.value);
                    filtersFlag = true;
                }
            });
        }
        var obj = $.extend({}, filters);
        if(filtersFlag == true){
            filtersApplied(obj, F);

        }else{
            if($("#filtersApplied").length > 0){
                $('#filtersApplied').remove();
                $('.clear-btn').remove();
            }
        }
        console.log('Form elements');
        console.log(obj);
        console.log('Form elements end');
        return obj;
    }

    function filtersApplied(filters, $form) {
        if (typeof $form == 'undefined') {
            $form = $("#filter_form");
        }
        var key = null;
        for (var prop in filters) {
            if (filters.hasOwnProperty(prop)) {
                key++;
            }
        }
        if (key > 0 && $("#filtersApplied").length == 0) {
            //$("#collapseFilters").after('<ul id="filtersApplied" class="selected-filters" ></ul>');
            $(".after-filter").html('<ul id="filtersApplied" class="selected-filters" ></ul>'); //<button type="button" class="btn clear-btn" onclick="clearFilters()"><i class="fa fa-refresh" aria-hidden="true"></i> Clear Filter</button>
        }
        var fouter = $("#filtersApplied");
        fouter.empty();
        $.each(filters, function (name, element) {
            var elselect = $form.find("select[name='" + name + "']");
            var elinput = $form.find("input[name='" + name + "']");
            $.each(element, function (key, value) {
                if (value == '') {
                    return;
                }
                var long_name = value;
                var elcheckbox = $form.find("[name='" + name + "'][value='" + value + "'][type='checkbox']");
                var elradio = $form.find("[name='" + name + "'][value='" + value + "'][type='radio']");
                if (elcheckbox.length && elcheckbox.next('label').length) {
                    long_name = elcheckbox.next('label').html();
                } else if (elradio.length && elradio.next('label').length) {
                    long_name = elradio.next('label').html();
                } else if (elselect.length) {
                    var opt = elselect.find('option[value="' + value + '"]');
                    if (opt.length) {
                        long_name = opt.html();
                    }
                } else if (elinput.length) {
                    var opr = $form.find("select[name='" + name + "_op']").length ?  $form.find("select[name='" + name + "_op']").val() : '';
                    long_name = elinput.attr('data-placeholder') + ' '+ opr + ' ' + elinput.val();
                }
                //console.log("not allowed----",elselect.data('notallowed'));
                if(elselect.data('notallowed') == false || elselect.data('notallowed') == undefined){
                    fouter.append('<li class="selected-filter mr-1"><span>' + long_name + '</span><a href="#" class="removeFilter" data-name="' + name + '" data-value="' + value + '" ><i class="fas fa-times-circle"></i></a></li>');
                }

            });

        });
    }

    function pagination_v2(obj, type) {

        var track_page = $(obj).attr('data-idx');
        var url = 'report/get?show_entries=20&tabid=21&rtype=pagination&page=' + track_page;
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
        $.get(url, {'page': track_page, type: type,filters : filtersArr}, function (data) {
            NProgress.done();
            loading = false; //set loading flag off once the content is loaded
            if (data.html.trim().length == 0) {
                //notify user if nothing to load
                $('.loading-info').html("No more records!");
                return;
            }
            $('.loading-info').hide(); //hide loading animation once data is received
            $("#scheduled_tab").html(data.html); //append data into #results element
            initJS($("#scheduled_tab"));
            $(".all-pagination").html(data.paginationHtml); //append data into #results element
            //$('#first_screen').css("opacity", "1");
//            $("#tickets_table").DataTable(dtobj);
        }).fail(function (xhr, ajaxOptions, thrownError) { //any errors?
            alert(thrownError); //alert with HTTP error
        })
    }
</script>