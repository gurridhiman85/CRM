$(document).ready(function () {
    CKEDITOR.disableAutoInline = true;
    //CKEDITOR.extraPlugins = 'dialogui,dialog,fakeobjects,forms';


    var stickyLoaded = false;
    if ($("#top-menu").length) {
        $("#top-menu").append('<a title="Sticky Note" class="sticky_open_btn"><i class="fa fa-sticky-note-o"></i></a><div class="sticky_inner">Loading...</div>');
        setTimeout(function () {
            if (typeof CopyToClipboardObj == 'object') {
                CopyToClipboardObj.addException('.sticky_inner');
                CopyToClipboardObj.addException('.cke');
                CopyToClipboardObj.addException('.cke_dialog_background_cover');
                CopyToClipboardObj.addException('.cke_editor_sticky_textarea_dialog');
            }
        }, 1000);
    }
    var sticky_editor;
    ACFn.loadstickypopup = function (F, R) {
        F.html(R.html);
        // Turn off automatic editor creation first.
        // CKEDITOR.disableAutoInline = true;
        //CKEDITOR.extraPlugins = 'dialogui,dialog,fakeobjects,forms';
        sticky_editor = CKEDITOR.inline('sticky_textarea', {
            toolbarGroups: [
                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                {name: 'clipboard', groups: ['clipboard', 'undo']},
                {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
                {name: 'links', groups: ['links']},
                {name: 'insert', groups: ['insert']},
                {name: 'forms', groups: ['forms']},
                {name: 'tools', groups: ['tools']},
                {name: 'document', groups: ['mode', 'document', 'doctools']},
                {name: 'others', groups: ['others']},
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
                {name: 'styles', groups: ['styles']},
                {name: 'colors', groups: ['colors']},
                {name: 'about', groups: ['about']}
            ],
            removeButtons: 'Subscript,Superscript,Cut,Undo,Redo,Copy,Paste,PasteText,PasteFromWord,Scayt,Image,Link,Unlink,Anchor,Table,HorizontalRule,SpecialChar,Maximize,Source,RemoveFormat,Outdent,Indent,Styles,Format,About,Blockquote'

        });

        sticky_editor.on('instanceReady', function () {
            $(".sticky").addClass("sticky_open");
            // $("#top-menu .sticky_inner").css('opacity', '1');
        })

        sticky_editor.on('change', function (evt) {
            // getData() returns CKEditor's HTML content.
            /*var content = evt.editor.getData();
            console.log('Total bytes: ' + evt.editor.getData().length);*/
            saveStickyTimeout();
        });

    }
    $("body").removeClass("ajax-loading");

    var stickykeytimeout;
    // $("body").on('keyup', '.sticky_inner textarea', function () {
    //
    //
    // });

    $('body').on('input propertychange', '.sticky_inner textarea', function () {
        $(".sticky .saved_text").hide();
        saveStickyTimeout();
    });

    function saveStickyTimeout() {
        clearTimeout(stickykeytimeout);

        stickykeytimeout = setTimeout(function () {
            saveSticky();
        }, 1000);
    }


    $("body").click(function (e) {
        var container = $(".sticky");
        var c2 = $(".cke");
        var c3 = $(".cke_dialog_background_cover");
        var c4 = $(".cke_editor_sticky_textarea_dialog");
        if (
            !container.is(e.target) && container.has(e.target).length === 0
            && !c2.is(e.target) && c2.has(e.target).length === 0
            && !c3.is(e.target) && c3.has(e.target).length === 0
            && !c4.is(e.target) && c4.has(e.target).length === 0
        ) {
            $(".sticky").removeClass("sticky_open");
        }
    });

    $("body").on("click", ".sticky_open_btn", function (e) {
        e.preventDefault();
        loadSticky();
        $("body").removeClass("main-right-open");
        $("body").removeClass("xyz");
        $(".notififyclick").fadeOut();
    });


    $("body").on("click", ".add-sticky", function (e) {
        stickyLoaded = true;
        // $("#top-menu .sticky_inner").css('opacity', '0');
        ACFn.sendAjax('/sticky/new', 'get', {}, $("#top-menu .sticky_inner"), {
            progress: 'nprogress'
        });
    })

    function loadSticky() {
        if (stickyLoaded) {
            $(".sticky").toggleClass("sticky_open");
            return false;
        }

        stickyLoaded = true;
        // $("#top-menu .sticky_inner").css('opacity', '0');
        ACFn.sendAjax('/sticky/get', 'get', {}, $("#top-menu .sticky_inner"), {
            progress: 'nprogress'
        });
    }

    function saveSticky() {
        // var $textarea = $(".sticky textarea");
        sticky_editor.updateElement();
        // $(".sticky_inner .console").html($textarea.val());

        $('.sticky form').submit();
        $("body").removeClass("ajax-loading");
        // ACFn.sendAjax('/sticky/save', 'post', {
        //     'content' : $textarea.val()
        // }, $textarea);
    }

    ACFn.savedstickypopup = function (F, R) {
        $(".sticky .saved_text").fadeIn();
    }

    $("body").on("click", "#color-selection li a", function (e) {
        e.preventDefault();
        $("#note_class").val($(this).attr('class'));
        $("#color-selection li a").removeClass('selected');
        $(this).addClass('selected');
        $("#stickyform").attr('class', 'ajax-Form sticky-note pull-right ' + $(this).attr('class'));
        $(".sticky-sidebar ul").find('li.active').attr('class', 'active ' + $(this).attr('class'));
        saveSticky();
    });

    $("body").on("click", ".sticky-sidebar ul li .ti-close", function (e) {
        var $id = $(this).parent("li").attr('data-sticky-id');
        stickyLoaded = true;
        // $("#top-menu .sticky_inner").css('opacity', '0');

        var title = "Are you sure?";
        var text = "You might not be able to revert this!";
        var butttontext = "Yes";
        if (typeof swal === 'function') {
            swal({
                title: title,
                text: text,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                customClass: 'swal-wd',
                confirmButtonText: butttontext
            }).then(function (result) {
                if (result.value) {
                    ACFn.sendAjax('/sticky/delete/' + $id, 'get', {}, $("#top-menu .sticky_inner"), {
                        progress: 'nprogress'
                    });
                    return false;
                }else{
                    $(".sticky").toggleClass("sticky_open");
                    return false;

                }
            });
        } else if (confirm(title + '\n' + text)) {
            ACFn.sendAjax('/sticky/delete/' + $id, 'get', {}, $("#top-menu .sticky_inner"), {
                progress: 'nprogress'
            });
        }
    });

    $('.sticky_inner').bind('contextmenu', function (e) {
        $("#cke_sticky_textarea").show();
    });


});

function ChangeNote(obj) {
    var id = $(obj).attr('data-id');
    stickyLoaded = true;
    // $("#top-menu .sticky_inner").css('opacity', '0');
    ACFn.sendAjax('/sticky/single/' + id, 'get', {}, $("#top-menu .sticky_inner"), {
        progress: 'nprogress'
    });
}

var BrowserNotificationsClass = function () {
    this.hasPermission = false;
    this.lastNotification = "";
    this.init = function () {
        var _this = this;
        if (!("Notification" in window)) {
            _this.hasPermission = false;
        } else if (Notification.permission === "granted") {
            _this.hasPermission = true;
        }
    }
    this.requestPermission = function (callback) {
        var _this = this;
        if (_this.hasPermission) {
            if (typeof callback == 'function') {
                callback();
            }
        } else if (!("Notification" in window)) {
            _this.hasPermission = false;
        } else if (Notification.permission === "granted") {
            _this.hasPermission = true;
            if (typeof callback == 'function') {
                callback();
            }
        } else if (Notification.permission !== "denied") {
            Notification.requestPermission(function (permission) {
                if (permission === "granted") {
                    _this.hasPermission = true;
                    if (typeof callback == 'function') {
                        callback();
                    }
                }
            });
        }
    }
    this.sendNotification = function (title, opt, callback) {
        var _this = this;
        if (_this.lastNotification == title) {
            return;
        }
        console.log('displaying notification');
        _this.requestPermission(function () {
            _this.lastNotification = title;
            console.log('displaying notification callback');
            var options = {
                // body: body,
                // icon: icon
            };
            var n = new Notification(title, options);
            n.onclick = function (event) {
                console.log(event);
                if (typeof callback == 'function') {
                    callback(n, event);
                }
                // event.preventDefault(); // prevent the browser from focusing the Notification's tab
                // window.open('http://www.mozilla.org', '_blank');
            }
        });

    }
};


var BrowserNotification = new BrowserNotificationsClass();
BrowserNotification.init();