// Created by STRd6
// MIT License
// jquery.paste_image_reader.js
(function ($) {
    var defaults;
    $.event.fix = (function (originalFix) {
        return function (event) {
            event = originalFix.apply(this, arguments);
            if (event.type.indexOf('copy') === 0 || event.type.indexOf('paste') === 0) {
                event.clipboardData = event.originalEvent.clipboardData;
            }
            return event;
        };
    })($.event.fix);
    defaults = {
        callback: $.noop,
        matchType: /image.*/
    };
    return $.fn.pasteImageReader = function (options) {
        if (typeof options === "function") {
            options = {
                callback: options
            };
        }
        options = $.extend({}, defaults, options);
        return this.each(function () {
            var $this, element;
            element = this;
            $this = $(this);
            return $this.bind('paste', function (event) {
                var clipboardData, found;
                found = false;
                clipboardData = event.clipboardData;
                return Array.prototype.forEach.call(clipboardData.types, function (type, i) {
                    var file, reader;
                    if (found) {
                        return;
                    }
                    if (type.match(options.matchType) || clipboardData.items[i].type.match(options.matchType)) {
                        file = clipboardData.items[i].getAsFile();
                        reader = new FileReader();
                        reader.onload = function (evt) {
                            return options.callback.call(element, {
                                dataURL: evt.target.result,
                                event: evt,
                                file: file,
                                name: file.name
                            });
                        };
                        reader.readAsDataURL(file);
                        return found = true;
                    }
                });
            });
        });
    };
})(jQuery);
var imageEditor = null;
$(document).ready(function () {

    var myPixiePaste = false;
    var Cmodal = $("#clipboardModal");
    var CmodelDataField = Cmodal.find("[name='data']");
    if (typeof (Pixie) != 'undefined') {
        myPixiePaste = Pixie.setOptions({
            replaceOriginal: true,
            appendTo: 'body',
            hideOpenButton: true,
            onSave: function (data, img) {
                // data //base64 encoded image data
                // img  //img element with src set to image data
                // Cmodal.find(".img-outer").html("<img src='" + results.dataURL + "' class='img-responsive' >");
                // alert("save");
                Cmodal.modal('show');
                Cmodal.find("[name='data']").val(data);
                Cmodal.find(".img-outer").html("<img src='" + data + "' class='img-responsive' >");
                // Cmodal.find('form').trigger('submit');
                if (myPixiePaste) {
                    myPixiePaste.close();
                }

            },
            onSaveButtonClick: function () {
                // alert("btnsave");
                myPixiePaste.save('jpeg', 10);
                //format - png, jpeg or json
                //quality - 1 to 10
            }
            // onSaveButtonClick: function () {
            // myPixie.save(format, quality);
            //format - png, jpeg or json
            //quality - 1 to 10
            // }
        });
    }
    ACFn.append_image_ticket_attachment = function (F, R) {
        $("#appendfile").find('.js-append-attachment').append(R.html);

        var Cmodal = $("#clipboardModal");
        if (Cmodal.length) {
            Cmodal.modal('hide');
        }
        if (F.find('.dropify-clear').length) {
            F.find('.dropify-clear').trigger('click');
        }
        if ($(".zero_attachments").length) {
            $(".zero_attachments").remove();
        }
        var tab = $('[href="#attachmenttab"]');
        if (tab.length > 0 && tab.find('.notify').length > 0) {
            tab.find('.notify').html("("+$(".tr-attachment").length+")");
        }
        $(".attachcount").html("("+R.attachcount+")");
        ACFn.form_reset(F);
        
    }

    $("#clipboardModal").on('click', '.edit_clipboard', function () {

        if (myPixiePaste) {
            myPixiePaste.open({
                url: CmodelDataField.val(),
                // image: e.target
            });
        }
    })

    ACFn.remove_image_ticket_attachment = function (F, R) {
        ACFn.show_message(F, R);
        F.parents("tr").remove();
    }

    $("html").pasteImageReader(function (results) {
        // console.log(results);
        var Cmodal = $("#clipboardModal");
        if (myPixiePaste && Cmodal.length) {
            myPixiePaste.open({
                url: results.dataURL,

                // image: e.target
            });
        } else if (Cmodal.length) {
            Cmodal.modal('show');
            Cmodal.find(".img-outer").html("<img src='" + results.dataURL + "' class='img-responsive' >");
            Cmodal.find("[name='data']").val(results.dataURL);
        }
        return;
        if (!results.dataURL) {
            return;
        }
        var F = $(".clipboard_form");
        if (F.length) {
            F.find("[name='data']").val(results.dataURL);
            F.submit();
        }
        return;
        var dataURL, filename;
        filename = results.filename, dataURL = results.dataURL;
        $data.text(dataURL);
        $size.val(results.file.size);
        $type.val(results.file.type);
        $test.attr('href', dataURL);
        var img = document.createElement('img');
        img.src = dataURL;
        var w = img.width;
        var h = img.height;
        $width.val(w)
        $height.val(h);
        return $(".active").css({
            backgroundImage: "url(" + dataURL + ")"
        }).data({'width': w, 'height': h});
    });
})
