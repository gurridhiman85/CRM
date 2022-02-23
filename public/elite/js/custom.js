window.addEventListener('focus', focusInWindow);
window.addEventListener('blur', focusOutWindow);
var windowHasFocus = true;

function focusInWindow() {
    // console.log('in'+new Date().getSeconds());
    windowHasFocus = true;
}

function focusOutWindow() {
    // console.log('out'+new Date().getSeconds());
    windowHasFocus = false;
}


function parseHashUrl() {
//    console.log(window.location.hash);
    var hash = window.location.hash;
    if (!hash) {
        return {};
    }
    // hash = decodeURIComponent(hash.substr(1));
    hash = hash.substr(1);
    var arr = hash.split('&');
    //  console.log(arr);
    var params = {};
    $.each(arr, function (index, value) {
        var intarr = value.split('=');
        if (typeof (intarr[0]) != 'undefined' && typeof (intarr[1]) != 'undefined') {
            params[intarr[0]] = decodeURIComponent(intarr[1]);
        }
    });
    //console.log(params);
    return params;
}

function putHashUrl(params) {
    var hash = $.param(params);
    if (history.pushState) {
        history.replaceState(null, null, '#' + hash);
    } else {
        location.hash = '#' + hash;
    }
}

function ChangeUrl(page) {
    var params = parseHashUrl();
    var hash = $.param(params);
    if (history.pushState) {
        history.replaceState(null, null, '?page='+page+'#' + hash);
    } else {
        location.hash = '?page='+page+'#' + hash;
    }
    putHashUrl(params);
}

function removePagefilter(){
    var params = parseHashUrl();
    var hash = $.param(params);
    var url = window.location.href;
    var value = url.slice( 0, url.indexOf('?') );
    if (history.pushState) {
        history.replaceState(null, null, value+'#' + hash);
    } else {
        location.hash = value+'#' + hash;
    }
    putHashUrl(params);
}

$(document).ready(function () {
    //localStorage.clear();
    if($("#filter_form").length > 0){
        var $form = $("#filter_form");
    }else if($("#filter_milestone_form").length > 0){
        var $form = $("#filter_milestone_form");
    }else if($("#filter_form_projects").length > 0){
        var $form = $("#filter_form_projects");
    }

    if($form){
        var attr = $form.attr('title');
        if (typeof attr !== typeof undefined && attr !== false) {
            var con = $form.attr('title').split('#');
            var existingEntries = JSON.parse(localStorage.getItem("allEntries"));
            if(existingEntries == null) existingEntries = [];
            var length = existingEntries.length;
            for(var i = 0; i<length; i++){
                if (existingEntries[i].title === con[0] && existingEntries[i].projectId == con[1] && existingEntries[i].userId == con[2]) {
                    var hash = $.param(existingEntries[i].url);
                    if (history.pushState) {
                        history.replaceState(null, null, '#' +hash);
                    } else {
                        location.hash = '#' + hash;
                    }
                }
            }
        }
    }

    var existingEntries = JSON.parse(localStorage.getItem("allEntries"));
     console.log(existingEntries);
    function getExpandedMilestones() {
        var milestones_ids = [];
        $(".js-milestone-button").each(function (index, element) {
            // console.log(element);
            if ($(element).hasClass('collapsed')) {

            } else {
                milestones_ids.push($(element).data('milestone-id'));
            }
        });
        return milestones_ids;
    }

    function loadMilestones(milestones_ids, milestoneurl) {
        //var milestoneurl = $("#milestone_url").val();
        if($('#filter_milestone_form').length > 0){
            $form = $("#filter_milestone_form")
        }else{
            $form = $("#filter_form")
        }
        ACFn.sendAjax(milestoneurl, 'get', {
            milestone_ids: milestones_ids,
            filters: getFilters($form)
        }, null, {
            'progress': 'nprogress'
        });
    }

    function getExpandedModules() {
        var modules_ids = [];
        $(".js-module-button").each(function (index, element) {
            // console.log(element);
            if ($(element).hasClass('collapsed')) {

            } else {
                modules_ids.push($(element).data('module-id'));
            }
        });
        return modules_ids;
    }

    function loadModules(modules_ids, moduleurl) {
        //var milestoneurl = $("#milestone_url").val();
        if($('#filter_milestone_form').length > 0){
            $form = $("#filter_milestone_form")
        }else{
            $form = $("#filter_form")
        }
        ACFn.sendAjax(moduleurl, 'get', {
            modules_ids: modules_ids,
            filters: getFilters($form)
        }, null, {
            'progress': 'nprogress'
        });
    }


    $("body").on('submit', "#filter_milestone_form", function (e) {
        //var entryTitle = $(this).attr('title');
        var attr = $(this).attr('title');
        var pass = false;
        if (typeof attr !== typeof undefined && attr !== false) {
            var con = $(this).attr('title').split('#');
            pass = true;

        }
        e.preventDefault();
        var F = $(this);
        setTimeout(function () {
            $('#collapseFilters').collapse('hide');
            var filtersarr = getFilters(F);
            var params = parseHashUrl();
            params['filters'] = btoa(JSON.stringify(filtersarr));


            if (typeof(Storage) !== "undefined" && pass == true) {

                var existingEntries = JSON.parse(localStorage.getItem("allEntries"));
                if(existingEntries == null) existingEntries = [];

                var entryUrl =   params['filters'];
                var checkExist = false;
                var length = existingEntries.length;
                for (var i = 0; i < length; i++) {
                    if (existingEntries[i].title == con[0] && existingEntries[i].projectId == con[1] && existingEntries[i].userId == con[3]) {
                        checkExist = false;
                        existingEntries.splice(i, 1);
                        break;
                    }
                }

                if(checkExist == false){
                    var entry = {
                        "userId": con[2],
                        "projectId": con[1],
                        "title": con[0],
                        "url": params,
                        "date_time" : new Date($.now())
                    };
                    localStorage.setItem("entry", JSON.stringify(entry));
                    existingEntries.push(entry);
                    localStorage.setItem("allEntries", JSON.stringify(existingEntries));
                }
            }
            putHashUrl(params);
            var milestones_ids = getExpandedMilestones();
            var milestoneurl = $("#milestone_url").val();
            if (milestones_ids) {
                loadMilestones(milestones_ids, milestoneurl);
            }

            var modules_ids = getExpandedModules();
            var moduleurl = $("#module_url").val();
            if (modules_ids) {
                loadModules(modules_ids, moduleurl);
            }




        }, 100);
    });

    $("body").on('show.bs.collapse', '.js-milestones-table .collapse ', function (e) {
        // console.log($(this));
        var milestone_id = $(this).data('milestone-id');
        var milestone_url = $(this).data('milestone-url');
        if (milestone_id) {
            loadMilestones([milestone_id], milestone_url);
        }
    });

    $("body").on('show.bs.collapse', '.js-modules-table .collapse ', function (e) {
        // console.log($(this));
        var module_id = $(this).data('module-id');
        var module_url = $(this).data('module-url');
        if (module_id) {
            loadModules([module_id], module_url);
        }
    });



    if ($("#filter_milestone_form").length > 0) {
        initHashUrlFilterForm();
        var filtersarr = getFilters($("#filter_milestone_form"));
        var milestones_ids = getExpandedMilestones();
        var milestoneurl = $("#milestone_url").val();
        loadMilestones(milestones_ids, milestoneurl);
    }

    function mark_active_class($menu) {
//        var url = location;
        //      console.log(url);
        var url = '';
        url = location.protocol + '//' + location.host + '' + location.pathname;
        var url2 = location.href;
        var url3 = location.pathname;


        console.log(url, url2, url3, "----------------");
        $.each($menu.find('a'), function (index, element) {
            var href = $(element).attr('href');
            // console.log(href, "===========");
            var href2 = href.split("?");
            if (href == url || href == url2 || href == url3 || href2[0] == url3) {
                $(element).parents('li').addClass('active');
            }
        });
    }

    mark_active_class($("#side-menu"));

    $(function () {
        $(".preloader").hide();
        if ($('#side-menu').length) {
            // $('#side-menu').metisMenu();
        }
    });

    if ($("#export-timeline-form").length) {
        $("body").on('submit', '#export-timeline-form', function () {
            $("#export-timeline-form-filters").remove();
            var F = $("#filters-timeline-form");
            var nF = $(this);
            var fields = F.serializeArray();
            // console.log(fields);
            var div = $("<div></div>").attr('id', 'export-timeline-form-filters');
            $.each(fields, function (index, value) {
                var input = $("<input>").attr('name', value.name).attr('value', value.value).attr('type', 'hidden');
                div.append(input);
            });
            nF.append(div);
            // var filters = getFilters();
            //  $(this).find('[name="filters"]').val(JSON.stringify(filters));
        })
    }

    if ($(".tab-ajax").length > 0) {
        // console.log("to run");
        ACFn.load_ajax_tab = function (F, R) {
            var tab = F.attr("href");
            //alert(tab);
            $('body.fixed-layout').css({'padding':0});
            $(tab).html(R.html);
            if(R.paginationHtml){
                $('.all-pagination').html(R.paginationHtml);
            }else{
                $('.all-pagination').html('');
            }

            if(R.is_show_entries && R.paginationHtml){
                $('body .showentries').show();
            }else{
                $('body .showentries').hide();
            }

            initJS($(tab));
        }

        ACFn.load_ajax_tab_count = function (F, R) {

            $.each(F.find('a'), function (index, element) {
                var tabi = $(element).data('tabid');
                if (tabi && typeof (R['count'][tabi]) != 'undefined') {
                    var etabcount = $(element).find('.tab-count');
                    if (etabcount.length == 0) {
                        var html = $(element).html();
                        $(element).html(html + '<span class="tab-count"></span>')
                        etabcount = $(element).find('.tab-count');
                    }
                    if (R['count'][tabi] == 0) {
                        etabcount.remove();
                    } else {
                        etabcount.html("(" + R['count'][tabi] + ")");

                    }
                }
            });

        }
        $('.tab-ajax a[data-toggle="tab"]').on('show.bs.tab', function (e) {

            $('.c-btn').hide();
            // var nav = $(e.target).parents(".tab-ajax");
            var url = $(e.target).parents(".tab-ajax").data("href");
            var method = $(e.target).parents(".tab-ajax").data("method").length ? $(e.target).parents(".tab-ajax").data("method") : 'get';
            //var tab = $(e.target).attr("href").substr(1);
            var tabid = $(e.target).data("tabid");
            var shouldblank = $(e.target).data("shouldblank") ? $(e.target).data("shouldblank") : 'Yes';
            var row_id = $(e.target).attr("data-row_id") ? $(e.target).attr("data-row_id") : '';
            /*var page = $(e.target).data("page");
            if (page == undefined) {
                page = 1;
            }*/
            //console.log(e,tabid,shouldblank);
            // console.log(tab);
            load_tabs_count($(e.target).parents(".tab-ajax"));
            var filtersarr = getFilters();

            if($('#tab_' + tabid).html() != "" && shouldblank == 'no'){
            }else{
                $(".customtab").html("");
                var show_entries = 20;
                if($('body').find('.js-show-entries').length > 0){
                    show_entries = $('body').find('.js-show-entries').val();
                }
                ACFn.sendAjax(url, method, {
                    tabid: tabid,
                    filters: filtersarr,
                    show_entries: show_entries,
                    row_id: row_id,
                    mergeKeys : JSON.parse(localStorage.getItem('MergeKeys')),
                    _token : $('[name="_token"]').length ? $('[name="_token"]').val() : '',
                }, $(e.target), {
                    // progress: 'nprogress'
                });
            }


        });

        var temp_loaded_count = false;

        function load_tabs_count($tab_outer) {
            if (temp_loaded_count) {
                // return;
            }
            temp_loaded_count = true;
            var url = $tab_outer.data("count-href");
            if (!url) {
                return;
            }
            var other_tabs = [];
            $.each($tab_outer.find('a'), function (index, element) {
                var tabi = $(element).data('tabid');
                if (tabi) {
                    other_tabs.push(tabi);
                }
            });
            if (!other_tabs) {
                return;
            }
            var filtersarr = getFilters();
            ACFn.sendAjax(url, 'get', {tabids: other_tabs, count: 3, filters: filtersarr}, $tab_outer, {
                progress: 'nprogress'
            });
            // console.log(other_tabs);
        }


        /*$("body").on('click', '.removeFilter', function (e) {
            e.preventDefault();
            var name = $(this).data('name');
            var value = $(this).data('value');
            var $form = $("#filter_form");
            var elcheckbox = $form.find("[name='" + name + "'][value='" + value + "'][type='checkbox']");
            var elselect = $form.find("select[name='" + name + "']");
            var eltext = $form.find("input[name='" + name + "'][type='text']");
            if (elcheckbox.length) {
                elcheckbox.prop('checked', false);
            }
            if (elselect.length) {
                var option = elselect.find("option[value='" + value + "']");
                console.log(option.length);
                if (option) {
                    option.prop('selected', false);
                }
                elselect.trigger("chosen:updated");
            }
            if (eltext.length) {
                eltext.val('');
            }
            if($("#filtersearch").length > 0){
                $("#filtersearch").val('');
            }
            if($('[name="searchterm"]').length > 0){
                $('[name="searchterm"]').val('')
            }
            $("#filter_form").trigger('submit');
        });*/

        $("body").on('submit', '#export-form', function () {
            $(this).find(".filter_fields").remove();
            $(this).find('[name="tabid"]').val($(".tab-ajax li.active a").data('tabid'));
            var filters = getFilters();
            $(this).find('[name="filters"]').val(JSON.stringify(filters));
        })
        /*$("body").on('submit', '.Export_form', function () {
            //alert("asdf");

        })*/

        $("body").on('submit', "#filter_form", function (e) {
            //localStorage.clear();
            var attr = $(this).attr('title');
            var pass = false;
            if (typeof attr !== typeof undefined && attr !== false) {
                var con = $(this).attr('title').split('#');
                pass = true;
            }
            e.preventDefault();
            setTimeout(function () {
                $('#collapseFilters').collapse('hide');
                var filtersarr = getFilters();
                var params = parseHashUrl();
                params['filters'] = btoa(JSON.stringify(filtersarr));



                if (typeof(Storage) !== "undefined" && pass == true) {

                    var existingEntries = JSON.parse(localStorage.getItem("allEntries"));
                    if(existingEntries == null) existingEntries = [];

                    var entryUrl =   params['filters'];
                    var checkExist = false;
                    var length = existingEntries.length;
                    for (var i = 0; i < length; i++) {
                        if (existingEntries[i].title == con[0] && existingEntries[i].projectId == con[1] && existingEntries[i].userId == con[2]) {
                            checkExist = false;
                            existingEntries.splice(i, 1);
                            break;
                        }
                    }

                    if(checkExist == false){
                        var entry = {
                            "userId": con[2],
                            "projectId": con[1],
                            "title": con[0],
                            "url": params,
                            "date_time" : new Date($.now())
                        };
                        localStorage.setItem("entry", JSON.stringify(entry));
                        existingEntries.push(entry);
                        localStorage.setItem("allEntries", JSON.stringify(existingEntries));
                    }
                }



                putHashUrl(params);
                $('#filtersModel').modal('hide');
                // load_tabs_count($(".tab-ajax"));
                var sel_tab = $('.tab-ajax li a.active');
                if (sel_tab.length) {
                    sel_tab.trigger('show.bs.tab');
                }


            }, 100);

        });

        $("body").on('reset', "#filter_form", function (e) {
            $form = $("#filter_form");
            $form.find('select').val('').trigger('chosen:updated')

            //$(".removeFilter").trigger('click');
            setTimeout(function () {
                $('#collapseFilters').collapse('hide');
                load_tabs_count($(".tab-ajax"));
                var sel_tab = $('.tab-ajax li a.active');
                if (sel_tab.length) {
                    sel_tab.trigger('show.bs.tab');
                }
            }, 100);
            $(".clear-btn").hide();
        });
    }


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
                $('.clear-btn').hide();
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
                    $(".clear-btn").show();
                }

            });

        });
    }


    function filtersApplied____Working(filters, $form) {
        console.log('starrtttt filter');
        console.log(filters);
        console.log($form);
        console.log('enddddd filter');
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
			$(".after-filter").html('<ul id="filtersApplied" class="selected-filters" ></ul><button type="button" class="btn clear-btn" onclick="clearFilters()"><i class="fa fa-refresh" aria-hidden="true"></i> Clear Filter</button>');
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
                    long_name = elinput.attr('data-placeholder') + ' ' + elinput.val();
                }
                fouter.append('<li class="selected-filter"><span>' + long_name + '</span><a href="#" class="removeFilter" data-name="' + name + '" data-value="' + value + '" ><i class="fas fa-times-circle"></i></a></li>');
            });

        });
    }

    $("body").on("change",".js-show-entries",function (e) {
        $('.tab-ajax li.active a[data-toggle="tab"]').trigger('show.bs.tab');
        return false;
        /*var url = $(this).data('href');
        var tabid = $('.nav-tabs li.active a').data('tabid');
        var show_entries = $(this).val();
        var $form = $($(this).data('filter-form-id'));
        var filters = getFilters($form);

        if(url){
            ACFn.sendAjax(url, 'get', {
                tabid: tabid,
                filters: filters,
                show_entries: show_entries
            }, $(e.target), {
                'progress': 'nprogress'
            });
        }*/

    })

    $("body").on('click', '.removeFilter', function (e) {
        e.preventDefault();
        var name = $(this).data('name');
        var value = $(this).data('value');
        var $form = $("#filter_form");
        var attr = $(this).attr('title');
        if (typeof attr !== typeof undefined && attr !== false) {
            if (typeof(Storage) !== "undefined") {
                var existingEntries = JSON.parse(localStorage.getItem("allEntries"));
                if (existingEntries == null) existingEntries = [];
                var con = $form.attr('title').split('#');

                var checkExist = false;
                var length = existingEntries.length;
                for (var i = 0; i < length; i++) {
                    if (existingEntries[i].title == con[0] && existingEntries[i].projectId == con[1] && existingEntries[i].userId == con[2]) {
                        checkExist = false;
                        var index = existingEntries.indexOf(i);
                        if (index > -1) {
                            existingEntries.splice(index, 1);
                        }
                        break;
                    }
                }
            }
        }

        var elcheckbox = $form.find("[name='" + name + "'][value='" + value + "'][type='checkbox']");
        var elselect = $form.find("select[name='" + name + "']");
        var eltext = $form.find("input[name='" + name + "'][type='text']");
        var elhidden = $form.find("input[name='" + name + "'][type='hidden']");
        if (elcheckbox.length) {
            elcheckbox.prop('checked', false);
        }
        if (elselect.length) {

            var option = elselect.find("option[value='" + value + "']");
            if (option) {
                option.prop('selected', false);
            }
            var elselectid = elselect.attr('id');
            $('#'+ elselectid).multiselect('refresh');
            $('#'+ elselectid + '_ms').css('width','100%');
            elselect.trigger("chosen:updated");
        }
        if (eltext.length) {
            eltext.val('');
        }
        if (elhidden.length) {
            elhidden.val('');
        }

        if($("#filtersearch").length > 0){
            $("#filtersearch").val('');
        }
        if($('[name="searchterm"]').length > 0){
            $('[name="searchterm"]').val('')
        }
        blankMergeData();
        $(".clear-btn").hide();
        $form.trigger('submit');

    });

    function initHashUrlFilterForm() {
        var params = parseHashUrl();

        if (typeof params['filters'] != 'undefined') {
            var jfilters = atob(params['filters']);
//            console.log(jfilters);
            if (jfilters) {
                var arrfilters = JSON.parse(jfilters);
                //              console.log(arrfilters);

                var $form = $("#filter_form");
                if ($form.length == 0) {
                    $form = $("#filter_milestone_form");
                }
                $.each(arrfilters, function (key, allvalues) {
                    $.each(allvalues, function (index, value) {
                        var elcheckbox = $form.find("[name='" + key + "'][value='" + value + "'][type='checkbox']");
                        var elradio = $form.find("[name='" + key + "'][value='" + value + "'][type='radio']");
                        var elselect = $form.find("select[name='" + key + "']");
                        var elinput = $form.find("input[name='" + key + "']");

                        if (elcheckbox.length) {
                            elcheckbox.prop('checked', true);
                        } else if (elradio.length) {
                            elradio.prop('checked', true);
                        } else if (elselect.length) {
                            var option = elselect.find("option[value='" + value + "']");
                            if (option) {
                                option.prop('selected', true);
                                if(key == 'task_category'){
                                    showSubcategory(elselect);
                                }
                            }
                        } else if (elinput.length) {
                            elinput.val(value);
                        }
                    })
                })
            }
        }


    }

    if ($(".tab-hash").length > 0) {
        var params = parseHashUrl();
        initHashUrlFilterForm();


        if (typeof params['tab'] != 'undefined') {
            if ($('.tab-hash a[href="#' + params['tab'] + '"]').length) {
                $('.tab-hash a[href="#' + params['tab'] + '"]').tab('show');
            }
        } else {
            var def_tab = $('.tab-hash').data('default-tab');
            if (def_tab && $('.tab-hash a[href="#' + def_tab + '"]').length) {
                $('.tab-hash a[href="#' + def_tab + '"]').tab('show');
            } else {
                $('.tab-hash li:first-child a').tab('show');
            }
        }

        if (typeof params['comment_id'] != 'undefined') {
            // console.log(params['comment_id']);
            // console.log('diedfad');
            var comment_id = '#comment_' + params['comment_id'];
            if ($(comment_id).length > 0) {
                $(comment_id).addClass('comment-highlight');
                setTimeout(function () {
                    $(comment_id).removeClass('comment-highlight');
                }, 5000);
            }
        }

        // var filtersarr = getFilters();
        // var params = parseHashUrl();
        // params['filters'] = btoa(JSON.stringify(filtersarr));

        /*
                if (location.hash !== '') {
                    // console.log(location.hash);
                    if ($('.tab-hash a[href="' + location.hash + '"]').length) {
                        $('.tab-hash a[href="' + location.hash + '"]').tab('show');
                    }
                    // $('.tab-hash a[href="' + location.hash + '"]').trigger('click');
                } else {
                    var def_tab = $('.tab-hash').data('default-tab');
                    if (def_tab && $('.tab-hash a[href="#' + def_tab + '"]').length) {
                        $('.tab-hash a[href="#' + def_tab + '"]').tab('show');
                    } else {
                        $('.tab-hash li:first-child a').tab('show');
                    }
                }
        */
        $('.tab-hash a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
             console.log($(e.target).attr('href'),"----------------");
            var params = parseHashUrl();
            params['tab'] = $(e.target).attr('href').substr(1);
            $(".note-popover").hide();
            console.log(params);


            putHashUrl(params);

            // if (history.pushState) {
            //     history.replaceState(null, null, '#' + $(e.target).attr('href').substr(1));
            // } else {
            //     location.hash = '#' + $(e.target).attr('href').substr(1);
            // }
        });
    }

    // ACFn.jsUpdateTicket = function (F, R) {
    //     var st = "#panel-ticket-form-outer";
    //     var div = $(st);
    //     var html = $("R.html").find(st).html();
    //     div.html(html);
    // }


    // show active tab on reload


    // remember the hash in the URL without jumping


    // Theme settings

    //Open-Close-right sidebar
    $(".right-side-toggle").click(function () {
        $(".r_side").slideDown(50);
        $(".r_side").toggleClass("shw-rside");
       /*if($("#page-wrapper").hasClass('xyz')){
            setTimeout(function () {
                //$("body").removeClass('xyz');
                $("#page-wrapper").removeClass('xyz');
            },2000)
        }else{
            //$("body").toggleClass('xyz');
            $("#page-wrapper").toggleClass('xyz');
        }*/


        // Fix header

        $(".fxhdr").click(function () {
            $("body").toggleClass("fix-header");
        });

        // Fix sidebar

        $(".fxsdr").click(function () {
            $("body").toggleClass("fix-sidebar");
        });

        // Service panel js

        if ($("body").hasClass("fix-header")) {
            $('.fxhdr').attr('checked', true);
        } else {
            $('.fxhdr').attr('checked', false);
        }

        if ($("body").hasClass("fix-sidebar")) {
            $('.fxsdr').attr('checked', true);
        } else {
            $('.fxsdr').attr('checked', false);
        }

    });

    //Loads the correct sidebar on window load,
    //collapses the sidebar on window resize.
    // Sets the min-height of #page-wrapper to window size
    $(function () {
        $(window).bind("load resize", function () {
            topOffset = 120;
            width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
            if (width < 768) {
                $('div.navbar-collapse').addClass('collapse');
                topOffset = 100; // 2-row-menu
            } else {
                $('div.navbar-collapse').removeClass('collapse');
            }

            height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
            height = height - topOffset;
            if (height < 1) height = 1;
            if (height > topOffset) {
                $("#page-wrapper").css("min-height", (height) + "px");
            }
        });

        /*var url = window.location;
        var element = $('ul.nav a').filter(function () {
            return this.href == url || url.href.indexOf(this.href) == 0;
        }).addClass('active').parent().parent().addClass('in').parent();
        if (element.is('li')) {
            element.addClass('active');
        }*/
    });

    // This is for resize window
    $(function () {
        $(window).bind("load resize", function () {
            width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
            if (width < 1170) {
                $('body').addClass('content-wrapper');
                $(".open-close i").removeClass('icon-arrow-left-circle');
                $(".sidebar").css("overflow", "inherit").parent().css("overflow", "visible");

            } else {
                $('body').removeClass('content-wrapper');
                $(".open-close i").addClass('icon-arrow-left-circle');

            }
        });
    });


    // This is for click on open close button
    // Sidebar open close
    $(".open-close").on('click', function () {
        if ($("body").hasClass("content-wrapper")) {
            $("body").trigger("resize");

            $(".sidebar-nav, .slimScrollDiv").css("overflow", "hidden").parent().css("overflow", "visible");
            $("body").removeClass("content-wrapper");
            $(".open-close i").addClass("icon-arrow-left-circle");
            $(".logo span").show();

        } else {
            $("body").trigger("resize");
            $(".sidebar-nav, .slimScrollDiv").css("overflow", "inherit").parent().css("overflow", "visible");

            $("body").addClass("content-wrapper");
            $(".open-close i").removeClass("icon-arrow-left-circle");
            $(".logo span").hide();
        }
    });


    // Collapse Panels

    (function ($, window, document) {
        var panelSelector = '[data-perform="panel-collapse"]';

        $(panelSelector).each(function () {
            var $this = $(this),
                parent = $this.closest('.panel'),
                wrapper = parent.find('.panel-wrapper'),
                collapseOpts = {
                    toggle: false
                };

            if (!wrapper.length) {
                wrapper =
                    parent.children('.panel-heading').nextAll()
                        .wrapAll('<div/>')
                        .parent()
                        .addClass('panel-wrapper');
                collapseOpts = {};
            }
            wrapper
                .collapse(collapseOpts)
                .on('hide.bs.collapse', function () {
                    $this.children('i').removeClass('ti-minus').addClass('ti-plus');
                })
                .on('show.bs.collapse', function () {
                    $this.children('i').removeClass('ti-plus').addClass('ti-minus');
                });
        });
        $(document).on('click', panelSelector, function (e) {
            e.preventDefault();
            var parent = $(this).closest('.panel');
            var wrapper = parent.find('.panel-wrapper');
            wrapper.collapse('toggle');
        });
    }(jQuery, window, document));

    // Remove Panels

    (function ($, window, document) {
        var panelSelector = '[data-perform="panel-dismiss"]';
        $(document).on('click', panelSelector, function (e) {
            e.preventDefault();
            var parent = $(this).closest('.panel');
            removeElement();

            function removeElement() {
                var col = parent.parent();
                parent.remove();
                col.filter(function () {
                    var el = $(this);
                    return (el.is('[class*="col-"]') && el.children('*').length === 0);
                }).remove();
            }
        });
    }(jQuery, window, document));


    //tooltip
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })


    //Popover
    $(function () {
        $('[data-toggle="popover"]').popover()
    })


    // Task
    $(".list-task li label").click(function () {
        $(this).toggleClass("task-done");
    });

    $(".settings_box a").click(function () {
        $("ul.theme_color").toggleClass("theme_block");
    });

    $('.main-table.collapse ').on('show.bs.collapse', function (e) {
        console.log(e);
    })


});

//Colepsible toggle

$(".collapseble").click(function () {
    $(".collapseblebox").fadeToggle(350);
});

// Sidebar
/*if ($('.slimscrollright').length) {
    $('.slimscrollright').slimScroll({
        height: '100%',
        position: 'right',
        size: "5px",
        color: '#dcdcdc',

    });
}
if ($('.chat-list').length) {
    $('.chat-list').slimScroll({
        height: '100%',
        position: 'right',
        size: "0px",
        color: '#dcdcdc'
    });
}*/

// Resize all elements
$("body").trigger("resize");

// visited ul li
$('.visited li a').click(function (e) {

    $('.visited li').removeClass('active');

    var $parent = $(this).parent();
    if (!$parent.hasClass('active')) {
        $parent.addClass('active');
    }
    e.preventDefault();
});

// Login and recover password
$('#to-recover').click(function () {
    $("#loginform").slideUp();
    $("#recoverform").fadeIn();
});

// Update 1.5

// this is for close icon when navigation open in mobile view
$(".navbar-toggle").click(function () {
    $(".navbar-toggle i").toggleClass("ti-menu");
    $(".navbar-toggle i").addClass("ti-close");
});

// Update 1.6

// this is for the left-aside-fix in content area with scroll
/*if ($('.chatonline').length) {
    $('.chatonline').slimScroll({
        height: '96%',
        position: 'right',
        size: "0px",
        color: '#dcdcdc',
    });
}*/


$(".open-panel").click(function () {
    $(".chat-left-aside").toggleClass("open-pnl");
    $(".open-panel i").toggleClass("ti-angle-left");
});

// Update 1.7

// For navigation fix top

function collapseNavbar() {
    if ($(window).scrollTop() > 30) {
        // $("#wrapper").addClass("fix-top");
    } else {
        $("#wrapper").removeClass("fix-top");
    }
}

$(window).scroll(collapseNavbar);
$(document).ready(collapseNavbar);

function disableSelectedOption(usedId,notUsedIds){

    jQuery('#'+usedId+' option:not(:selected)').each(function(){
        for(var i=0; i< notUsedIds.length;i++){
            if(jQuery("#" + notUsedIds[i]).length > 0) {
                jQuery("#"+notUsedIds[i]+" option[value='"+$(this).val()+"']").removeAttr('disabled');
            }
        }
    });

    jQuery('#'+usedId+' :selected').each(function(){
        for(var i=0; i< notUsedIds.length;i++){
            if(jQuery("#" + notUsedIds[i]).length > 0) {
                jQuery("#"+notUsedIds[i]+" option[value='"+$(this).val()+"']").attr('disabled',true);
            }
        }
    });
    for(var i=0; i< notUsedIds.length;i++){
        if(jQuery("#" + notUsedIds[i]).length > 0) {
            jQuery("#"+notUsedIds[i]).trigger("chosen:updated");
        }
    }
}


function shiftMeOnTop(sec_id,user_id){
    var is_exist = false;
    var is_selected = false;
    if($('#'+sec_id+' option[value="'+user_id+'"]').length > 0){
        is_exist = true;
    }

    if(is_exist == true){
        if($('#'+sec_id+' option[value="'+user_id+'"]').prop('selected') == true){
            is_selected = true;
        }
        $('#'+sec_id+' option[value="'+user_id+'"]').remove();
        if(is_selected == true){
            $('#'+sec_id).prepend($('<option>', {value: user_id, text:'Me', selected: true}));
        }else{
            $('#'+sec_id).prepend($('<option>', {value: user_id, text:'Me'}));
        }
        $('#'+sec_id).trigger("chosen:updated");
    }

}

function clearFilters(){
    var $form = $("#filter_form");
    $form.find('select').val('');
    $form.find('input').val('');
    var filtersMS = [
        'status-filter',
        'zss_segment-filter',
        'campaign-filter',
        'member_segment-filter',
        'donor_segment-filter',
        'event_segment-filter',
        'lifecycle_segment-filter',
        'address-filter',
        'country-filter',
        'Productcat1_Des-filter',
        'Productcat2_Des-filter',
        'Product-filter',
    ];

    $.each( filtersMS, function( index, value ) {
        $("#" + value).multiselect('refresh');
        $('#'+ value + '_ms').css('width','100%');
    });
    $form.submit();
}


