var timer = new IntervalTimer(function () {}, 15000);
timer.pause();

$(document).ready(function () {
    var current = 1,current_step,next_step,steps;
    localStorage.setItem('stepsCompleted',0);
    var steps = $(".fieldset").length;
    $('.continue').on('click',function () {
        var currentStep = parseInt($('.fieldset.active').data('step'));
        var nextStep = parseInt(currentStep) + 1;
        $('.stepsCnt').text(nextStep);
        $('.fieldset[data-step=' + currentStep + ']').hide().removeClass('active');
        $('.fieldset[data-step=' + nextStep + ']').show().addClass('active');
        if(nextStep > 1){
            $('.continue').attr('disabled',false)
            $('.back').attr('disabled',false)
        }else if(nextStep == 7){
            $('.continue').attr('disabled',true)
        }else{
            $('.continue').attr('disabled',true)
            $('.back').attr('disabled',true)
        }
        setProgressBar(++current,steps);
        if(nextStep == 3){
            if($('[name="source3"]').val() == ""){
                $('#sourceOuterSelector').parent('.form-group').addClass('has-error');
                $('.help-block-SourceOuter').text('Source is required');

                $('.glyphicon-upload').attr('class','').attr('class','fas fa-upload')
                $('.glyphicon-trash').attr('class','').attr('class','fas fa-trash')
                $('.glyphicon-zoom-in').parent('button').remove();

                setTimeout(function () {
                    $('#sourceOuterSelector').parent('.form-group').removeClass('has-error');
                    $('.help-block-SourceOuter').text('');
                },5000)

                return false;
            }
            if(localStorage.getItem('stepsCompleted') == 2){
                $('#importExecuteFrm').submit();
                //countdown();
                $('#cd_start').trigger('click');
            }


        }else if(nextStep == 5){
            if(localStorage.getItem('stepsCompleted') == 4) {
                $('#updateaddress').submit();
            }
        }else if(nextStep == 6){
            if(localStorage.getItem('stepsCompleted') == 5) {
                $('#updatename').submit();
            }
        }

        $('#steps-title').text($('.fieldset.active').data('title'));
    });

    $('.back').on('click',function () {
        var currentStep = parseInt($('.fieldset.active').data('step'));
        var backStep = parseInt(currentStep) - 1;

        if(backStep <= 1){
            $('.back').attr('disabled',true)
            $('.continue').attr('disabled',true)
            //return false;
        }else{
            if(localStorage.getItem('stepsCompleted') == 0){
                $('.continue').attr('disabled',false)
            }

            $('.back').attr('disabled',false)
        }

        $('.stepsCnt').text(backStep);
        $('.fieldset[data-step=' + currentStep + ']').hide().removeClass('active');;
        $('.fieldset[data-step=' + backStep + ']').show().addClass('active');
        setProgressBar(--current,steps);
        $('#steps-title').text($('.fieldset.active').data('title'));
    });

    setProgressBar(current,steps);

    var $docs_input_files = $("#docs-input-files");
    /*var $docs_upload_files_modal = $("#upload-modal-file");*/
    if ($docs_input_files.length) {
        $docs_input_files.fileinput({
            uploadUrl: 'import/show', //File upload call
            showUpload: false, // hide upload button
            showRemove: true, // hide remove button
            uploadAsync: false,
            uploadExtraData: appendExtraDataFiles,
            //allowedFileExtensions : ['xlsx','xls'],
            //msgInvalidFileExtension : 'Invalid extension for file "{name}". Only "{extensions}" files are supported.',
            previewFileIconSettings: {
                'xlsx': '<i class="fas fa-file-excel text-success"></i>',
                'csv': '<i class="fas fas fa-file-alt text-danger"></i>',

            }
        });
        $('.fileinput-remove').hide();

        $('.input-group').hide();

        $('.file-drop-zone').on('click',function () {
            if($('[name="source"]').val() == ""){
                $('#sourceOuterSelector').parent('.form-group').addClass('has-error');
                $('.help-block-SourceOuter').text('Source is required');
                ACFn.display_message('Source is required','','success');

                $('.glyphicon-upload').attr('class','').attr('class','fas fa-upload')
                $('.glyphicon-trash').attr('class','').attr('class','fas fa-trash')
                $('.glyphicon-zoom-in').parent('button').remove();

                setTimeout(function () {
                    $('#sourceOuterSelector').parent('.form-group').removeClass('has-error');
                    $('.help-block-SourceOuter').text('');
                },5000)

                return false;
            }
            $('#docs-input-files').trigger('click');
        })
        /**
         * Created By : Gurpreet Singh
         * Purpose    : To create a batch of all selected files.
         *              There is ajax call that check is user is authorized.
         *              If it will true then user can upload files
         */
        $docs_input_files.on("filebatchselected", function (event, files) {
            if($('[name="source"]').val() == ""){
                $('#sourceOuterSelector').parent('.form-group').addClass('has-error');
                $('.help-block-SourceOuter').text('Source is required');
                ACFn.display_message('Source is required','','success');

                $('.glyphicon-upload').attr('class','').attr('class','fas fa-upload')
                $('.glyphicon-trash').attr('class','').attr('class','fas fa-trash')
                $('.glyphicon-zoom-in').parent('button').remove();

                setTimeout(function () {
                    $('#sourceOuterSelector').parent('.form-group').removeClass('has-error');
                    $('.help-block-SourceOuter').text('');
                },5000)

                return false;
            }
            localStorage.setItem('stepsCompleted',1);
            $docs_input_files.fileinput("upload");
            $('.kv-file-remove').hide();
            return false;
            var obj = {};
            obj['_token'] = '{!! csrf_token() !!}';
        });

        /**
         * Created By : Gurpreet Singh
         * Purpose    : To append some extra parameters like Csrf token and folder id.
         *
         * @param  previewId
         * @param  index
         */
        function appendExtraDataFiles(previewId, index) {
            var obj = {};
            var form = $('#filessubmit');
            obj['_token'] = form.find('[name="_token"]').val();
            obj['source'] = form.find('[name="source"]').val();
            return obj;
        }

        /**
         * Created By : Gurpreet Singh
         * Purpose    : Callback function to reload tree when all files will uploaded.
         *
         */

        $docs_input_files.on('filebatchuploadsuccess', function(event, data) {
            var formdata = data.form, files = data.files,
                extradata = data.extra, R = data.response;
            if(R.success){
                $('#overviewImport').html(R.html);
                $('#hidden_fields').html(R.hidden_fields_html);
                localStorage.removeItem('fieldMapping');
                localStorage.setItem('fieldMapping',JSON.stringify(R.columns));
                $('#Import_Filename').val(R.Import_Filename);
                $('#Import_Id').val(R.Import_Id);
                initJS($('#overviewImport'))
                $('.continue').trigger('click');
                $('#basic_table_without_dynamic_pagination').attr('style','width:100% !important');
                if($('#is_no_address').is(':checked')){
                    $('#no_address').val(1);
                }
                localStorage.setItem('stepsCompleted',2);
            }else{
                if(!R.success){
                    localStorage.setItem('stepsCompleted',0);
                    var msg = 'Invalid extension for file. Only xlsx,xlx files are supported.';
                    if(R.messageTitle){
                        msg = R.messageTitle;
                    }
                    $('.kv-fileinput-error').html('<span class="pull-left">' + msg + '</span>')
                    $('.kv-fileinput-error').show();

                    setTimeout(function () {
                        $('.kv-fileinput-error').html('')
                        $('.kv-fileinput-error').hide();
                        $docs_input_files.fileinput('clear');
                    },12000)
                }
            }
        });
    }

    $('.btn-reset').on('click',function () {
        $docs_input_files.fileinput('clear');
    });

    $('#is_no_address').on('click',function () {
        if($(this).is(':checked')) {
            $('#waitingText').html("Please wait..........<br/>File Import and Cleansing is in progress.<br/> Your input will be required in approximately 1 minute.");
            $('#cd_seconds').val(60)
            $('[name="no_address"]').val(1)
        }else{
            $('#waitingText').html("Please wait..........<br/>File Import and Cleansing is in progress.<br/> Your input will be required in approximately 3-5 minutes.")
            $('#cd_seconds').val(300)
            $('[name="no_address"]').val(0);
        }

    })

    ACFn.ajax_import_execute = function (F, R) {
        if(R.success){
            if(R.no_address){
                localStorage.setItem('stepsCompleted',5);
                $('#updateAddressList').html(R.html);
                initJS($('#updateAddressList'));
                $('.continue').trigger('click');

            }else{
                localStorage.removeItem('step');
                timer.resume();
            }

        }else{
            var data = {
                'title': R.messageTitle,
                'text' : '',
                'butttontext' : 'Ok',
                'cbutttonflag' : false
            };
            ACFn.display_confirm_message(data,resetIMP);
        }
    };

    ACFn.ajax_update_address = function (F, R) {
        localStorage.setItem('stepsCompleted',5)
        var data = {
            'title': R.messageTitle,
            'text' : '',
            'butttontext' : 'Ok',
            'cbutttonflag' : false
        };
        ACFn.display_confirm_message(data);
        //ACFn.display_message(R.messageTitle,'','success',5000);
        if(R.success){
            $('#updateNameList').html(R.html);
            initJS($('#updateNameList'))
        }else{
            resetIMP();

        }
    };

    ACFn.ajax_import_completed = function (F,R) {
        localStorage.setItem('stepsCompleted',0);
        var data = {
            'title': R.messageTitle,
            'text' : '',
            'butttontext' : 'Ok',
            'cbutttonflag' : false
        };
        ACFn.display_confirm_message(data,redirectLKP);
        resetIMP();
    }
});

function colChange(obj,key){

    var efCls = obj.parents('th').data('class');
    var efCKey = obj.parents('th').data('col-key');
    if(obj.val() != ""){
        $('.' + efCls).removeClass('unmatched').addClass('matched');
        obj.parents('th').removeClass('unmatched').addClass('matched');
        $('#' + efCls + '_hidden').val(obj.val());

    }else{
        $('.' + efCls).removeClass('matched').addClass('unmatched');
        obj.parents('th').removeClass('matched').addClass('unmatched');
        $('#' + efCls + '_hidden').val('');
    }

    var selVal = obj.val();
    var selId = obj.attr('id');
    var selOV = $('#col_hidden_'+key).val();
    var selbox = 25;
    for(i = 0; i< 50;i++){
        if($('#col_' + i).length && i != key){ console.log(i);
            if(selVal != "") {
                $('#col_' + i + ' option[value=' + selVal + ']').hide();
            }
            if(selOV != ""){
                $('#col_' + i + ' option[value=' + selOV + ']').show();
            }
        }
    }
    $('#col_hidden_' + key).val(selVal);
}

function redirectLKP() {
    window.location.href = 'lookup';
}

function setProgressBar(curStep,steps){
    var percent = parseFloat(100 / steps) * curStep;
    percent = percent.toFixed();
    $(".progress-bar")
        .css("width",percent+"%")
        .html(percent+"%");
}

function IntervalTimer(callback, interval) {
    var timerId, startTime, remaining = 0;
    var state = 0; //  0 = idle, 1 = running, 2 = paused, 3= resumed

    this.pause = function () {
        if (state != 1) return;

        remaining = interval - (new Date() - startTime);
        window.clearInterval(timerId);
        state = 2;
    };

    this.resume = function () {
        if (state != 2) return;

        state = 3;
        window.setTimeout(this.timeoutCallback, remaining);
    };

    this.timeoutCallback = function () {
        if (state != 3) return;

        //checkFile();

        startTime = new Date();
        timerId = window.setInterval(checkFile, interval);
        state = 1;
    };

    startTime = new Date();
    timerId = window.setInterval(checkFile, interval);
    state = 1;
}

function checkFile() {
    var cstep = localStorage.getItem('step') ? localStorage.getItem('step') : '4a';
    $.ajax({
        url : 'import/checkfile',
        data : {
            step : cstep
        },
        type : 'GET',
        async : false,
        success : function (res) {
            if(res.success){
                if(res.file_found == true){
                    if(localStorage.getItem('step') == '5'){
                        timer.pause();
                        $('#cd_reset').trigger('click');
                        JumpOnStep(4);
                        var data = {
                            'title': 'Please tag the records where address should be updated',
                            'text' : '',
                            'butttontext' : 'Ok',
                            'cbutttonflag' : false
                        };
                        ACFn.display_confirm_message(data);
                        $('#updateAddressList').html(res.html)
                        initJS($('#updateAddressList'));
                        localStorage.setItem('stepsCompleted',4);
                    }
                    localStorage.removeItem('step');
                    localStorage.setItem('step','5');
                } else{
                    timer.resume();
                }
            }else{
                localStorage.setItem('stepsCompleted',0);
                if(res.move_on_11_step){
                    resetIMP();
                    ACFn.sendAjax('import/figure','get',{})
                }
                timer.pause();
                $('#cd_reset').trigger('click');
                var data = {
                    'title': res.messageTitle,
                    'text' : '',
                    'butttontext' : 'Ok',
                    'cbutttonflag' : false
                };
                ACFn.display_confirm_message(data,resetIMP);
                //Abort the import process
            }
        }
    })
}

function resetIMP() {
    localStorage.setItem('stepsCompleted',0);
    $('.fieldset').hide().removeClass('active');
    $('[data-step="1"]').show().addClass('active');
    $('.stepsCnt').text(1);
    var steps = $(".fieldset").length;
    setProgressBar(1,steps);
    $('#steps-title').text($('.fieldset.active').data('title'));

    $('#showMessages').html('');
    $('#updateAddressList').html('');
    $('#overviewImport').html('');
    $('#sourceOuterSelector').val('');
    $('#sourceOuterSelector').trigger('change');
    $("#docs-input-files").fileinput('clear');
    $('.continue').attr('disabled',true)
    $('.back').attr('disabled',true)
    timer.pause();
    //Decrement(true);
    $('#cd_reset').trigger('click');
    $('#is_no_address').prop('checked',false)
}

function JumpOnStep(stp) {
    $('.fieldset').hide().removeClass('active');
    $('[data-step="'+ stp +'"]').show().addClass('active');
    $('.stepsCnt').text(stp);
    $('#steps-title').text($('.fieldset.active').data('title'));
    var steps = $(".fieldset").length;
    setProgressBar(stp,steps);
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


$(document).ready(function () {
    (function($){
        $.extend({
            APP : {
                formatTimer : function(a) {
                    if (a < 10) {
                        a = '0' + a;
                    }
                    return a;
                },

                startTimer : function(dir) {
                    var a;
                    // save type
                    $.APP.dir = dir;
                    // get current date
                    $.APP.d1 = new Date();
                    switch($.APP.state) {
                        case 'pause' :
                            // resume timer
                            // get current timestamp (for calculations) and
                            // substract time difference between pause and now
                            $.APP.t1 = $.APP.d1.getTime() - $.APP.td;
                            break;
                        default :
                            // get current timestamp (for calculations)
                            $.APP.t1 = $.APP.d1.getTime();
                            // if countdown add ms based on seconds in textfield
                            if ($.APP.dir === 'cd') {
                                $.APP.t1 += parseInt($('#cd_seconds').val())*1000;
                            }
                            break;
                    }

                    // reset state
                    $.APP.state = 'alive';
                    $('#' + $.APP.dir + '_status').html('Running');
                    // start loop
                    $.APP.loopTimer();
                },

                pauseTimer : function() {
                    // save timestamp of pause
                    $.APP.dp = new Date();
                    $.APP.tp = $.APP.dp.getTime();
                    // save elapsed time (until pause)
                    $.APP.td = $.APP.tp - $.APP.t1;
                    // change button value
                    $('#' + $.APP.dir + '_start').val('Resume');
                    // set state
                    $.APP.state = 'pause';
                    $('#' + $.APP.dir + '_status').html('Paused');
                },

                stopTimer : function() {
                    // change button value
                    $('#' + $.APP.dir + '_start').val('Restart');
                    // set state
                    $.APP.state = 'stop';
                    $('#' + $.APP.dir + '_status').html('Stopped');
                },

                resetTimer : function() {
                    // reset display
                    $('#' + $.APP.dir + '_ms,#' + $.APP.dir + '_s,#' + $.APP.dir + '_m,#' + $.APP.dir + '_h').html('00');
                    // change button value
                    $('#' + $.APP.dir + '_start').val('Start');
                    // set state
                    $.APP.state = 'reset';
                    $('#' + $.APP.dir + '_status').html('Reset & Idle again');
                },

                endTimer : function(callback) {
                    // change button value
                    $('#' + $.APP.dir + '_start').val('Restart');
                    // set state
                    $.APP.state = 'end';
                    // invoke callback
                    if (typeof callback === 'function') {
                        callback();
                    }
                },

                loopTimer : function() {

                    var td;
                    var d2,t2;
                    var ms = 0;
                    var s  = 0;
                    var m  = 0;
                    var h  = 0;

                    if ($.APP.state === 'alive') {

                        // get current date and convert it into
                        // timestamp for calculations
                        d2 = new Date();
                        t2 = d2.getTime();
                        // calculate time difference between
                        // initial and current timestamp
                        if ($.APP.dir === 'sw') {
                            td = t2 - $.APP.t1;
                            // reversed if countdown
                        } else {
                            td = $.APP.t1 - t2;
                            if (td <= 0) {
                                // if time difference is 0 end countdown
                                $.APP.endTimer(function(){
                                    $.APP.resetTimer();
                                    $('#' + $.APP.dir + '_status').html('Ended & Reset');
                                });
                            }
                        }

                        // calculate milliseconds
                        ms = td%1000;
                        if (ms < 1) {
                            ms = 0;
                        } else {
                            // calculate seconds
                            s = (td-ms)/1000;
                            if (s < 1) {
                                s = 0;
                            } else {
                                // calculate minutes
                                var m = (s-(s%60))/60;
                                if (m < 1) {
                                    m = 0;
                                } else {
                                    // calculate hours
                                    var h = (m-(m%60))/60;
                                    if (h < 1) {
                                        h = 0;
                                    }
                                }
                            }
                        }

                        // substract elapsed minutes & hours
                        ms = Math.round(ms/100);
                        s  = s-(m*60);
                        m  = m-(h*60);

                        // update display
                        $('#' + $.APP.dir + '_ms').html($.APP.formatTimer(ms));
                        $('#' + $.APP.dir + '_s').html($.APP.formatTimer(s));
                        $('#' + $.APP.dir + '_m').html($.APP.formatTimer(m));
                        $('#' + $.APP.dir + '_h').html($.APP.formatTimer(h));
                        // loop
                        $.APP.t = setTimeout($.APP.loopTimer,1);
                    } else {
                        // kill loop
                        clearTimeout($.APP.t);
                        return true;
                    }
                }
            }
        });

        $('#sw_start').on('click', function() {
            $.APP.startTimer('sw');
        });

        $('#cd_start').on('click', function() {
            $.APP.startTimer('cd');
        });

        $('#sw_stop,#cd_stop').on('click', function() {
            $.APP.stopTimer();
        });

        $('#sw_reset,#cd_reset').on('click', function() {
            $.APP.resetTimer();
        });

        $('#sw_pause,#cd_pause').on('click', function() {
            $.APP.pauseTimer();
        });

    })(jQuery);
});
