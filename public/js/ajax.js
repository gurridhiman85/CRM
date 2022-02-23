$(document).ready(function () {
    if ($(".ajax-loader").length == 0) {
        $("body").append('<div class="ajax-loader-outer" ><div class="ajax-loader" ></div></div>');
    }
    function getFilters(F) {
        if (typeof F == 'undefined') {
            F = $("#filter_form");
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
                if (elcheckbox.length && elcheckbox.next('label').length && !elcheckbox.is(':disabled')) {
                    long_name = elcheckbox.next('label').html();
                } else if (elradio.length && elradio.next('label').length && !elradio.is(':disabled')) {
                    long_name = elradio.next('label').html();
                } else if (elselect.length && !elselect.is(':disabled')) {
                    var opt = elselect.find('option[value="' + value + '"]');
                    if (opt.length) {
                        long_name = opt.html();
                    }
                } else if (elinput.length && !elinput.is(':disabled')) {
                    long_name = elinput.attr('placeholder') + ' ' + elinput.val();
                }
                fouter.append('<li class="selected-filter"><span>' + long_name + '</span><a href="#" class="removeFilter" data-name="' + name + '" data-value="' + value + '" ><i class="fa fa-close"></i></a></li>');
            });
        });
    }

    if($('form#filter_form_projects').length > 0){
        function initHashUrlFilterForm() {
            var params = parseHashUrl();

            if (typeof params['filters'] != 'undefined') {
                var jfilters = atob(params['filters']);
//            console.log(jfilters);
                if (jfilters) {
                    var arrfilters = JSON.parse(jfilters);
                    //              console.log(arrfilters);
                    var $form = $("#filter_form_projects");


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
                                }
                            } else if (elinput.length) {
                                elinput.val(value);
                            }
                        })
                    })
                }
            }


        }

        initHashUrlFilterForm();
        setTimeout(function () {
            $('form#filter_form_projects').trigger('submit');
        },500)
    }

    $(document).on("submit", ".ajax-Form", function (e) {

        e.preventDefault();
        var F = $(this);
        if(F.attr('id') == "filter_form_projects"){
            $('#collapseFilters').collapse('hide');
            var filtersarr = getFilters(F);
            var params = parseHashUrl();
            params['filters'] = btoa(JSON.stringify(filtersarr));
            var attr = F.data('title');
            var pass = false;
            if (typeof attr !== typeof undefined && attr !== false) {
                var con = F.data('title').split('#');
                pass = true;
            }

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
        }
        var file_input = F.find('input[type="file"]');
        if (file_input.length > 0) {
            F.attr("enctype", "multipart/form-data");
        }
        var url = F.attr("action");
        var type = F.attr("method") ? F.attr("method").toLowerCase() : "post";
        var enc = F.attr("enctype");
        var contentType = "application/x-www-form-urlencoded; charset=UTF-8";
        var processData = true;
        var formdata = new FormData();

        if (enc == "multipart/formdata" || enc == "multipart/form-data") {
            contentType = false;
            processData = false;
            var file_input = F.find('input[type="file"]');
            $.each(file_input, function (index, element) {
                var files = $(element)[0].files;
                var fname = $(element).attr("name");
                for (var i = 0; i < files.length; i++) {
                    formdata.append(fname, files[i]);
                }
            });
            var other_data = F.serializeArray();
            $.each(other_data, function (key, input) {
                formdata.append(input.name, input.value);
            });

        } else {
            formdata = F.serializeArray();
        }
        var clicked_btn = F.find("input[type=submit]:focus");
        if (clicked_btn.length == 0) {
            clicked_btn = F.find("button[type=submit]:focus");
        }
        if (clicked_btn.length > 0) {
            var clicked_btn_name = clicked_btn.attr('name');
            var clicked_btn_val = clicked_btn.val();
            if (clicked_btn_name && clicked_btn_val) {
                formdata.append(clicked_btn_name, clicked_btn_val);
            }
        }


        NProgress.configure({ parent: '#appprogress' });
        $.ajax({
            url: url,
            data: formdata,
            contentType: contentType,
            processData: processData,
            type: type,
            method: type,
            dataType: "json",
            beforeSend: function () {
                ACFn.clear_errors(F);
                NProgress.start();
                setTimeout(function () {
                    $("button[type='submit']", this).attr('disabled', true);
                    $("input[type='submit']", this).attr('disabled', true);
                },1000)
                /*$("body").addClass(
                    "ajax-loading");*/
            },
            success: function (data56653245645563722) {
                F.find("button[type='submit']").removeAttr('disabled');
                F.find("input[type='submit']").removeAttr('disabled');
                var R = ACFn.json_parse(data56653245645563722);
                if (R.completefn) {
                    ACFn[R.completefn](F, R);
                } else if (R.success) {

                } else {
                    ACFn.display_errors(F, R);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                F.find("button[type='submit']").removeAttr('disabled');
                F.find("input[type='submit']").removeAttr('disabled');

                if(xhr.status == 401){
                    message = 'The session has expired. Please login again.';
                    ACFn.display_message(
                        message,
                        "",
                        "error");
                    window.location.href = 'login';
                }else if(xhr.status == 419) {
                    message = 'The session has expired. Please login again.';
                    ACFn.display_message(
                        message,
                        "",
                        "error");
                    window.location.href = 'login';
                }else{
                    var message = 'There was an error with this query. Please try a different query.!';
                    ACFn.display_message(
                        message,
                        "",
                        "error");
                }

            },
            complete: function () {
                /* $("body").removeClass(
                     "ajax-loading");*/
                NProgress.done(true);
            }
        });
    });

    $("body").on("click", ".ajax-Link", function (e) {
        e.preventDefault();
        $("body").removeClass("main-right-open");
        $("body").removeClass("xyz");
        $(".notififyclick").fadeOut();
        var F = $(this);
        ACFn.confirm_message(F, function () {
            sendAjaxButton(F);
        });
        // console.log(F.data("confirm"));

    });

    $("body").on("keydown", ".has-error input, .has-error textarea", function (e) {
        // console.log(e);
        var pe = $(this).parents(".has-error");
        // if (pe.length > 0) {
        pe.removeClass("has-error");
        // }
    });
    $("body").on("change", ".has-error select", function () {
        var pe = $(this).parents(".has-error");
        // if (pe.length > 0) {
        pe.removeClass("has-error");
        // }
    });

    $("body").on("change", ".has-error input[type='checkbox'], .has-error input[type='radio']", function () {
        var pe = $(this).parents(".has-error");
        // if (pe.length > 0) {
        pe.removeClass("has-error");
        // }
    });

});


function sendAjaxButton(F) {
    var url = F.data("href") ? F.data("href") : (F.attr("href") ? F.attr("href") : "");
    var method = F.data("method") ? F.data("method") : "GET";
    var token = F.data("token");
    var data_arr = {};
    $.each(F.data(), function (key, value) {
        console.log(key);
        console.log(key.indexOf('bs.'));
        if (key.indexOf('bs.') === 0 || key.indexOf('confirm') === 0 ||
            key.indexOf('originalTitle') === 0
        ) {

        }
        else if (key.indexOf('ajax-') === 0) {
            data_arr[key] = value;
        } else if (key.indexOf('ajax_') === 0) {
            data_arr[key.substring(5)] = value;
        }
    });
    // console.log(data_arr);
    NProgress.configure({ parent: '#appprogress' });
    $.ajax({
        url: url,
        method: method,
        data: data_arr, /*
         * { '_token': token },
         */
        beforeSend: function () {
            //$("body").addClass("ajax-loading");
            NProgress.start();
        },
        success: function (data64564364234234) {
            var R = ACFn.json_parse(data64564364234234);
            if (R.completefn) {
                ACFn[R.completefn](F, R);
            } else if (R.success) {

            } else {
                ACFn.display_errors(F, R);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {

            if(xhr.status == 401) {
                message = 'The session has expired. Please login again.';
                ACFn.display_message(
                    message,
                    "",
                    "error");
                window.location.href = 'login';
            }else if(xhr.status == 419) {
                message = 'The session has expired. Please login again.';
                ACFn.display_message(
                    message,
                    "",
                    "error");
                window.location.href = 'login';
            }else{
                var message = 'There was an error with this query. Please try a different query.!';
                ACFn.display_message(
                    message,
                    "",
                    "error");
            }
            // alert("Server Error! Try again later");
        },
        complete: function () {
            //$("body").removeClass("ajax-loading");
            NProgress.done(true);
        }
    });
}

function AjaxCompleteFunctions() {
    // other properties and functions...

    this.sendAjax = function (url, method, data_arr, F, options,request = true) {
        if (typeof options === 'undefined') {
            options = {};
        }
        var defaultOptions = $.extend({
            progress: 'default',
            showServerError: true
        }, options);
        // console.log(typeof data_arr['_token']);
        if (typeof data_arr['_token'] === 'undefined') {
            var tokenfield = $('[name="_token"]');
            // console.log(tokenfield.length);
            if (tokenfield.length) {
                data_arr['_token'] = tokenfield.val();
            }
        }
//        console.log(defaultOptions);
        NProgress.configure({ parent: '#appprogress' });
        $.ajax({
            url: url,
            method: method,
            data: data_arr,
            async: request,/*
             * { '_token': token },
             */
            beforeSend: function () {
                switch (defaultOptions.progress) {
                    case 'default':
                        //$("body").addClass("ajax-loading");
                        NProgress.start();
                        break;
                    case 'nprogress':
                        NProgress.start();
                        break;
                    case 'none':
                        break;
                }
            },
            success: function (data64564364234234) {
                var R = ACFn.json_parse(data64564364234234);
                if (R.completefn) {
                    ACFn[R.completefn](F, R);
                } else if (R.success) {

                } else {
                    ACFn.display_errors(F, R);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                if (defaultOptions.showServerError) {

                    if(xhr.status == 401) {
                        message = 'The session has expired. Please login again.';
                        ACFn.display_message(
                            message,
                            "",
                            "error");
                        window.location.href = 'login';
                    }else if(xhr.status == 419) {
                        message = 'The session has expired. Please login again.';
                        ACFn.display_message(
                            message,
                            "",
                            "error");
                        window.location.href = 'login';
                    }else{
                        var message = 'There was an error with this query. Please try a different query.!';
                        ACFn.display_message(
                            message,
                            "",
                            "error");
                    }
                }

                // alert("Server Error! Try again later");
            },
            complete: function () {
                switch (defaultOptions.progress) {
                    case 'default':
                        //$("body").removeClass("ajax-loading");
                        NProgress.done(true);
                        break;
                    case 'nprogress':
                        NProgress.done(true);
                        break;
                    case 'none':
                        break;
                }
            }
        });
    }

    this.general_form = function (F, R) {
        NProgress.done(true);
        if (R.success) {
            if (R.form_reset == false) {

            } else if (F.hasClass("ajax-Form")) {
                F[0].reset();
            }
            if (R.message) {
                this.show_message(F, R, function () {
                    if (R.page_reload) {
                        setTimeout(function () {
                            //$("body").addClass("ajax-loading");
                            //NProgress.start();
                        }, 100);
                        //$("body").addClass("ajax-loading");
                        setTimeout(function () {
                            window.location.reload();
                        }, 200);

                    }
                    if (R.redirect) {
                        setTimeout(function () {
                            //$("body").addClass("ajax-loading");
                            //NProgress.start();
                        }, 100);
                        //$("body").addClass("ajax-loading");
                        setTimeout(function () {
                            window.location = R.redirectURL;
                        }, 200);
                    }
                });
            } else {
                if (R.page_reload) {
                    setTimeout(function () {
                        //$("body").addClass("ajax-loading");
                        //NProgress.start();
                    }, 100);
                    //$("body").addClass("ajax-loading");
                    setTimeout(function () {
                        window.location.reload();
                    }, 200);
                }
                if (R.redirect) {
                    //$("body").addClass("ajax-loading");
                    setTimeout(function () {
                        window.location.href = R.redirectURL;
                    }, 200);
                }
            }
        } else {
            ACFn.display_errors(F, R);
            if (R.message) {
                ACFn.show_message(F, R);
            }
        }
    };
    this.form_reset = function (F, callback) {
        F[0].reset();
        if (typeof callback === "function") {
            callback();
        }
    }
    this.show_message = function (F, R, callback) {
        if (R.messageTitle) {
            this.display_message(R.messageTitle, R.messageDescription,
                R.messageType,2000, callback);
        }
    };
    this.display_message = function (title, description, type, timer = 2000, callback) {
        if (typeof (swal) === 'function') { //console.log("swal32312");
            // swal(title, description, type, callback)
            var icontype = '<i class="icon-check"  style="color: #39586b;"></i>&nbsp;&nbsp;';
            if(type != 'success'){
                var icontype = '<i class="icon-close" style="color: #39586b;"></i>&nbsp;&nbsp;';
            }
            var swal32312 = swal.fire({
                title: icontype+title,
                text: description,
                timer: timer,
                customClass: 'swal-wd',
                type: type,
                showConfirmButton:false,
            }).then(function () {
                if (typeof callback === "function") {
                    callback();
                }
            });

        } else { console.log("2");
            alert(title + '\n' + description);
            if (typeof callback === "function") {
                callback();
            }
        }
    };
    this.display_errors = function (F, R) {
        var scroll_to_field = 100000;
        var scrollfield = false;
        if (R.form_errors) {
            $.each(R.form_errors, function (index, element) {
                var field = F.find("[name='" + index + "']");
                if (field.length == 0) {
                    field = F.find("[name='" + index + "[]']");
                }
                var field_top = field.parents(".form-group");
                if (field_top.length == 0) {
                    field_top = field.parent();
                }
                var err_field = field_top.find(".error-block");
                if (err_field.length == 0) {
                    field_top
                        .append("<span class='error-block help-block'></span>");
                    err_field = field_top.find(".error-block");
                }
                // console.log(typeof (element));
                if (typeof (element) == "object") {
                    var xelement = "";
                    $.each(element, function (k, v) {
                        xelement += v;
                    });
                    // var xelement =
                    // $.makeArray(element).join(",");
                    err_field.html(xelement);
                } else {
                    err_field.html(element);
                }
                field_top.addClass("has-error");
                var of = field_top.offset();
                if (typeof (of) != 'undefined') {
                    var oft = of.top;
                    if (scroll_to_field > oft) {
                        scroll_to_field = oft;
                        scrollfield = field_top;
                    }
                }
            });
        }
        if (R.form_error) {
            var err_field = F.find(".form-error-block");
            if (err_field.length == 0) {
                F.prepend("<span class='form-error-block help-block'></span>");
                err_field = F.find(".error-block");
            }
            err_field.html(R.form_error);
            F.addClass("form-has-error");
        }
        if (scroll_to_field != 100000) {
            // console.log(scroll_to_field);
            // console.log($("body").scrollTop());
            if ((scroll_to_field - 90) < $("body").scrollTop()) {
                if (scrollfield.length) {
                    // console.log(scrollfield);
                    scrollfield[0].scrollIntoView();
                }
                // $("html, body").animate({scrollTop: scroll_to_field - 90},
                // 600);
            }
        }
        setTimeout(function () {
            ACFn.clear_errors(F);
        },3000)
    };
    this.clear_errors = function (F) {
        F.find(".error-block").html("");
        F.find(".form-error-block").html("");
        F.find(".form-group").removeClass("has-error",5000);
        F.find(".has-error").removeClass("has-error",5000);
        F.removeClass("form-has-error",5000);
    };
    this.confirm_message = function (F, callback) {
        var confirmx = F.data("confirm") ? true : false;
        // console.log(confirmx);
        if (confirmx) {
            var title = F.data("title") ? F.data("title") : "Are you sure?";
            var text = F.data("text") ? F.data("text")
                : "You might not be able to revert this!";
            var butttontext = F.data("butttontext") ? F.data("butttontext")
                : "Yes";
            if (typeof swal === 'function') {
                swal.fire({
                    title: title,
                    text: text,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3ea6d0',
                    cancelButtonColor: '#00000033',
                    customClass: 'swal-wd',
                    confirmButtonText: butttontext
                }).then(function (result) {
                    if (result.value) {
                        callback();
                    }
                });
            } else if (confirm(title + '\n' + text)) {
                callback();
            }
        } else {
            callback();
        }
    };
    this.display_confirm_message = function (data, callback) {
        // console.log(confirmx);
        var title = data["title"] ? data["title"] : "Are you sure?";
        var text = data["text"] ? data["text"]
            : "You might not be able to revert this!";
        var butttontext = data["butttontext"] ? data["butttontext"] : "Yes";
        var cbutttonflag = !data["cbutttonflag"] ? data["cbutttonflag"] : true;
        if (typeof swal === 'function') {
            swal.fire({
                title: title,
                text: text,
                type: 'warning',
                showCancelButton: cbutttonflag,
                confirmButtonColor: '#3ea6d0',
                cancelButtonColor: '#00000033',
                //customClass: 'swal-wd',
                confirmButtonText: butttontext,
                allowOutsideClick: false,
                customClass: {
                    popup: 'swal-wd',
                    confirmButton: 'btn btn-info'
                },
                onBeforeOpen: function(ele) {
                    $(ele).find('button.swal2-confirm.swal2-styled')
                        .toggleClass('swal2-styled')
                }
            }).then(function (result) {
                if (result.value) {
                    callback();
                }
            });
        } else if (confirm(title + '\n' + text)) {
            callback();
        }
    };
    this.json_parse = function (data54654645234523) {
        if (typeof data54654645234523 === "object") {
            return data54654645234523;
        }
        try {
            var R = JSON.parse(data54654645234523);
            return R;
        } catch (err54654634) {
            console.log(err54654634);
            return [];
        }
    }

}

var ACFn = new AjaxCompleteFunctions();
