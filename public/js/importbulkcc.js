

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
            if(localStorage.getItem('stepsCompleted') == 2){
                $('#step2form').submit();
                //$('#cd_start').trigger('click');
            }

            if(localStorage.getItem('stepsCompleted') == 4){
                $('#step4form').submit();
                //$('#cd_start').trigger('click');
            }


        }else if(nextStep == 4){
            $('#step3form').submit();
        }else if(nextStep == 5){
            if(localStorage.getItem('stepsCompleted') == 4) {
                $('#step5form').submit();
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
            uploadUrl: 'importbulkcc/step1', //File upload call
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

        $('form#filessubmit .file-drop-zone').on('click',function () {
            $('#docs-input-files').trigger('click');
        })
        /**
         * Created By : Gurpreet Singh
         * Purpose    : To create a batch of all selected files.
         *              There is ajax call that check is user is authorized.
         *              If it will true then user can upload files
         */
        $docs_input_files.on("filebatchselected", function (event, files) {
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
                var data = {
                    'title': 'Import 1-month Opens Count : ' + R.count,
                    'text' : '',
                    'butttontext' : 'Ok',
                    'cbutttonflag' : false
                };
                ACFn.display_confirm_message(data);

                $('.continue').trigger('click');
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

    var $docs_input_files_step2 = $("#docs-input-files_step2");
    if ($docs_input_files_step2.length) {
        $docs_input_files_step2.fileinput({
            uploadUrl: 'importbulkcc/step2', //File upload call
            showUpload: false, // hide upload button
            showRemove: true, // hide remove button
            uploadAsync: false,
            uploadExtraData: appendExtraDataFilesStep2,
            //allowedFileExtensions : ['xlsx','xls'],
            //msgInvalidFileExtension : 'Invalid extension for file "{name}". Only "{extensions}" files are supported.',
            previewFileIconSettings: {
                'xlsx': '<i class="fas fa-file-excel text-success"></i>',
                'csv': '<i class="fas fas fa-file-alt text-danger"></i>',

            }
        });
        $('.fileinput-remove').hide();

        $('.input-group').hide();

        $('form#formstep2 .file-drop-zone').on('click',function () {
            $docs_input_files_step2.trigger('click');
        })
        /**
         * Created By : Gurpreet Singh
         * Purpose    : To create a batch of all selected files.
         *              There is ajax call that check is user is authorized.
         *              If it will true then user can upload files
         */
        $docs_input_files_step2.on("filebatchselected", function (event, files) {
            localStorage.setItem('stepsCompleted',2);
            $docs_input_files_step2.fileinput("upload");
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
        function appendExtraDataFilesStep2(previewId, index) {
            var obj = {};
            var form = $('#formstep2')
            obj['_token'] = form.find('[name="_token"]').val();
            obj['source'] = form.find('[name="source"]').val();
            return obj;
        }

        /**
         * Created By : Gurpreet Singh
         * Purpose    : Callback function to reload tree when all files will uploaded.
         *
         */

        $docs_input_files_step2.on('filebatchuploadsuccess', function(event, data) {
            var formdata = data.form, files = data.files,
                extradata = data.extra, R = data.response;
            if(R.success){
                var data = {
                    'title': 'Import 6-month Opens Count : ' + R.count,
                    'text' : '',
                    'butttontext' : 'Ok',
                    'cbutttonflag' : false
                };
                ACFn.display_confirm_message(data);
                $('.continue').trigger('click');
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
                        $docs_input_files_step2.fileinput('clear');
                    },12000)
                }
            }
        });
    }

    var $docs_input_files_step3 = $("#docs-input-files_step3");
    if ($docs_input_files_step3.length) {
        $docs_input_files_step3.fileinput({
            uploadUrl: 'importbulkcc/step3', //File upload call
            showUpload: false, // hide upload button
            showRemove: true, // hide remove button
            uploadAsync: false,
            uploadExtraData: appendExtraDataFilesStep3,
            //allowedFileExtensions : ['xlsx','xls'],
            //msgInvalidFileExtension : 'Invalid extension for file "{name}". Only "{extensions}" files are supported.',
            previewFileIconSettings: {
                'xlsx': '<i class="fas fa-file-excel text-success"></i>',
                'csv': '<i class="fas fas fa-file-alt text-danger"></i>',

            }
        });
        $('.fileinput-remove').hide();

        $('.input-group').hide();

        $('form#formstep3 .file-drop-zone').on('click',function () {
            $docs_input_files_step3.trigger('click');
        })
        /**
         * Created By : Gurpreet Singh
         * Purpose    : To create a batch of all selected files.
         *              There is ajax call that check is user is authorized.
         *              If it will true then user can upload files
         */
        $docs_input_files_step3.on("filebatchselected", function (event, files) {
            localStorage.setItem('stepsCompleted',2);
            $docs_input_files_step3.fileinput("upload");
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
        function appendExtraDataFilesStep3(previewId, index) {
            var obj = {};
            var form = $('#formstep3')
            obj['_token'] = form.find('[name="_token"]').val();
            obj['source'] = form.find('[name="source"]').val();
            return obj;
        }

        /**
         * Created By : Gurpreet Singh
         * Purpose    : Callback function to reload tree when all files will uploaded.
         *
         */

        $docs_input_files_step3.on('filebatchuploadsuccess', function(event, data) {
            var formdata = data.form, files = data.files,
                extradata = data.extra, R = data.response;
            if(R.success){
                var data = {
                    'title': 'Import 12-month Opens Count : ' + R.count,
                    'text' : '',
                    'butttontext' : 'Ok',
                    'cbutttonflag' : false
                };
                ACFn.display_confirm_message(data);

                $('.continue').trigger('click');
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
                        $docs_input_files_step3.fileinput('clear');
                    },12000)
                }
            }
        });
    }

    var $docs_input_files_step4 = $("#docs-input-files_step4");
    if ($docs_input_files_step4.length) {
        $docs_input_files_step4.fileinput({
            uploadUrl: 'importbulkcc/step4', //File upload call
            showUpload: false, // hide upload button
            showRemove: true, // hide remove button
            uploadAsync: false,
            uploadExtraData: appendExtraDataFilesStep4,
            //allowedFileExtensions : ['xlsx','xls'],
            //msgInvalidFileExtension : 'Invalid extension for file "{name}". Only "{extensions}" files are supported.',
            previewFileIconSettings: {
                'xlsx': '<i class="fas fa-file-excel text-success"></i>',
                'csv': '<i class="fas fas fa-file-alt text-danger"></i>',

            }
        });
        $('.fileinput-remove').hide();

        $('.input-group').hide();

        $('form#formstep4 .file-drop-zone').on('click',function () {
            $docs_input_files_step4.trigger('click');
        })
        /**
         * Created By : Gurpreet Singh
         * Purpose    : To create a batch of all selected files.
         *              There is ajax call that check is user is authorized.
         *              If it will true then user can upload files
         */
        $docs_input_files_step4.on("filebatchselected", function (event, files) {
            localStorage.setItem('stepsCompleted',2);
            $docs_input_files_step4.fileinput("upload");
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
        function appendExtraDataFilesStep4(previewId, index) {
            var obj = {};
            var form = $('#formstep4')
            obj['_token'] = form.find('[name="_token"]').val();
            obj['source'] = form.find('[name="source"]').val();
            return obj;
        }

        /**
         * Created By : Gurpreet Singh
         * Purpose    : Callback function to reload tree when all files will uploaded.
         *
         */

        $docs_input_files_step4.on('filebatchuploadsuccess', function(event, data) {
            var formdata = data.form, files = data.files,
                extradata = data.extra, R = data.response;
            if(R.success){
                var data = {
                    'title': 'Import All Emails Count : ' + R.count,
                    'text' : '',
                    'butttontext' : 'Ok',
                    'cbutttonflag' : false
                };
                ACFn.display_confirm_message(data,finalcountshow);


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
                        $docs_input_files_step4.fileinput('clear');
                    },12000)
                }
            }
        });
    }

    $('.btn-reset').on('click',function () {
        $docs_input_files.fileinput('clear');
        $docs_input_files_step2.fileinput('clear');
        $docs_input_files_step3.fileinput('clear');
        $docs_input_files_step4.fileinput('clear');
    });

    ACFn.ajax_step5 = function (F,R) {
        if(R.success){
            var data = {
                'title': R.messageTitle,
                'text' : '',
                'butttontext' : 'Ok',
                'cbutttonflag' : false
            };
            ACFn.display_confirm_message(data);

            if(R.download_url)
                $('#downloadfinalfile').attr('href',R.download_url).show();

            localStorage.setItem('stepsCompleted',0);

        }else{
            var data = {
                'title': R.messageTitle,
                'text' : '',
                'butttontext' : 'Ok',
                'cbutttonflag' : false
            };
            ACFn.display_confirm_message(data);
        }
    }
});

function finalcountshow() {
    $('.continue').trigger('click');
    $('#formstep5').submit();
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
