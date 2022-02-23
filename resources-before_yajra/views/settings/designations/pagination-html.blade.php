<div class="dataTables_info " id="taskList_info" role="status" aria-live="polite"> Showing 1 to 20 of 1975 entries
</div>
<div class="ticket-pagination">
    <div class="dataTables_paginate paging_simple_numbers" id="taskList_paginate"><a class="paginate_button"
                                                                                     aria-controls="taskList"
                                                                                     )=""><i
                    class="fa fa-chevron-left"></i></a> <b>1</b> - <b>20 of 1975</b><a class="paginate_button"
                                                                                       aria-controls="taskList"
                                                                                       data-idx="2" tabindex="2"
                                                                                       onclick="pagination_v2(this,'All')"><i
                    class="fa fa-chevron-right"></i></a></div>
</div>
<div class="tickets-showentries showentries no-padding">

    <select class="form-control js-show-entries">
        <option selected="" value="20">20</option>
        <option value="30">30</option>
        <option value="40">40</option>
        <option value="50">50</option>
    </select>
</div>
<script>

        function pagination_v2(obj, type) {

            var track_page = $(obj).attr('data-idx');
            var url = '/WdProjects/view/eyJpdiI6ImwxWVRvVHZPNlBkSkM2cjdkMWF0S1E9PSIsInZhbHVlIjoiYlFnYnJQNEQ1U0FpRXNCVjVNZk94Zz09IiwibWFjIjoiYjhlOTZiNWM3MjAyMjEzMWQyYTUzYThlZTY5ZmNkNzUyNDQ1YmIyMTZkN2EwYjJiMjk0OGMyM2M4NDkzODFlNyJ9/gettickets?rtype=pagination&_token=KBlT4pBTVBtnNSZtIzwaCBJYicgyllZU7GLb58rR&show_entries=20&sortby=&tabid=21&page=' + track_page;
            $(".resall").removeClass('current');
            $(obj).addClass('current');
            $('#All').css("opacity", "0.6");
            load_contents_v2(track_page, type, url);
        }

        //Ajax load function
        function load_contents_v2(track_page, type, url) {
            $("html, body").animate({scrollTop: 0}, "slow");
            $.ajaxSetup({
                headers: {'X-CSRF-Token': $('input[name="_token"]').val()}
            });
            $.get(url, {'page': track_page, type: type}, function (data) {
                console.log(data)
                loading = false; //set loading flag off once the content is loaded
                if (data.html.trim().length == 0) {
                    //notify user if nothing to load
                    $('.loading-info').html("No more records!");
                    return;
                }
                $('.loading-info').hide(); //hide loading animation once data is received
                $("#All").html(data.html); //append data into #results element
                $(".all-pagination").html(data.paginationHtml); //append data into #results element
                $('#All').css("opacity", "1");
//            $("#tickets_table").DataTable(dtobj);
            }).fail(function (xhr, ajaxOptions, thrownError) { //any errors?
                alert(thrownError); //alert with HTTP error
            })
        }


        $(document).ready(function () {
            var is_admin = "0";
            if (is_admin == "1") {
                setTimeout(function () {
                    $(".dash-table").removeClass("search-pagination");
                }, 300);
            }

        });
        $(document).ready(function () {
            ACFn.timer_callback = function (F, R) {
                $("#timer-" + R.id).append(R.html);
                $("#timer-" + R.id + " a").removeClass("ajax-Link");
                $("#timer-" + R.id + " .addtimer").html('<i class="ti-alarm-clock active"></i>');

            }

            ACFn.timer_details = function (F, R) {
                $("#timer-" + R.id + " .timerdetails").html(R.html);
                $("#timer-" + R.id + " .startButton").hide();
                $("#timer-" + R.id + " .pauseButton").hide();
                $("#timer-" + R.id + " .stopButton").hide();
                $("#timer-" + R.id).unbind('mouseleave');
                $(".timer-" + R.id + " .timerdetails").html(R.html);
                $(".timer-" + R.id + " .startButton").hide();
                $(".timer-" + R.id + " .pauseButton").hide();
                $(".timer-" + R.id + " .stopButton").hide();
                $(".timer-" + R.id).unbind('mouseleave');
            }
        })


        /*function pagination(obj, type){
         var page = "";
         if (page == undefined) {
         page = 1;
         }
         var data_arr = {"tabid": type, 'page': page};
         $.ajaxSetup({
         headers: {'X-CSRF-Token': $('input[name="_token"]').val()}
         });
         $.ajax({
         url: '/WdProjects/tickets/get',
         method: 'get',
         data: data_arr, /!*
         * { '_token': token },
         *!/

         success: function (data64564364234234) {
         var R = ACFn.json_parse(data64564364234234);
         if (R.html) {
         $("#tab_" + type).html(R.html);
         } else {

         }
         },


         });
         }*/
    </script>
