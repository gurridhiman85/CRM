<div id="singlecamp_tab" class="table-responsive m-t-5" >
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 pl-0">
                            <div class="form-group row">
                                {{--<label class="control-label text-left col-md-2 pt-1">Campaign</label>--}}
                                <div class="col-md-6 pl-0">
                                    <select name="modelscoreid" id="modelscoreid" onchange="changeModelScore($(this))" class="form-control form-control-sm text-box1 chosen-select">
                                        <option value="">Select</option>
                                        @foreach($modelscores as $modelscore)
                                            <option value="{!! $modelscore->ModelScoreID !!}">{!! $modelscore->ModelScore_Name . " - " . date('d-m-Y',strtotime($modelscore->ModelScore_Cutoff_Date)) .' - '. $modelscore->ModelScoreID !!}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-1 m-0 p-0">
                                    <div class="form-group ">
                                        <small class="form-control-feedback ds-l">&nbsp;</small>
                                        <button type="button" class="btn btn-info font-10 s-f  ajax-Link" data-href="model/scorepreview" title="Preview">
                                            <i class="fas fa-eye font-10"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7"></div>
                    </div>
                    <div class="row">
                        <div id="singlecampevals" class="table-responsive m-t-5"></div>
                    </div>
                    <div class="row">
                        <div id="singlecampevald" class="table-responsive m-t-5"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 pl-1 pr-1">
                            <div class="card mb-1">
                                <div class="card-body p-2">
                                    <h5 class="card-title text-center mt-3" id="can-title-1"></h5>
                                    <div id="can-1" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 pl-1 pr-1">
                            <div class="card mb-1">
                                <div class="card-body p-2">
                                    <h5 class="card-title text-center mt-3" id="can-title-2"></h5>
                                    <div id="can-2" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6 pl-1 pr-1">
                            <div class="card mb-1">
                                <div class="card-body p-2">
                                    <h5 class="card-title text-center mt-3" id="can-title-3"></h5>
                                    <div id="can-3" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 pl-1 pr-1">
                            <div class="card mb-1">
                                <div class="card-body p-2">
                                    <h5 class="card-title text-center mt-3" id="can-title-4"></h5>
                                    <div id="can-4" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.c-btn').html('');

        ACFn.ajax_single_modelscore = function (F, R) {
            localStorage.removeItem("allEntries");
            if(R.success){
                var pdfdata = [];
                var charts = [];

                $('#singlecampevals').html(R.eEvalSumHtml);
                $('#singlecampevald').html(R.eEvalDetailHtml);
                $.each(R.chart_data, function (i) {
                    var legendColors = [];
                    var chart_type = R.chart_data[i].chart_type;
                    var position = R.chart_data[i].chart_position;
                    var title = R.chart_data[i].chart_title;
                    var legend1 = R.chart_data[i].chart_legend1;
                    var legend2 = R.chart_data[i].chart_legend2;
                    var legend3 = R.chart_data[i].chart_legend3;
                    var legend4 = R.chart_data[i].chart_legend4;
                    legendColors.push({
                        'legend1_Background_Color': R.chart_data[i].Legend1_Background_Color,
                        'legend2_Background_Color': R.chart_data[i].Legend2_Background_Color,
                        'legend3_Background_Color': R.chart_data[i].Legend3_Background_Color,
                        'legend4_Background_Color': R.chart_data[i].Legend4_Background_Color,
                        'legend5_Background_Color': R.chart_data[i].Legend5_Background_Color,
                        'legend6_Background_Color': R.chart_data[i].Legend6_Background_Color,

                        'legend1_Border_Color': R.chart_data[i].Legend1_Border_Color,
                        'legend2_Border_Color': R.chart_data[i].Legend2_Border_Color,
                        'legend3_Border_Color': R.chart_data[i].Legend3_Border_Color,
                        'legend4_Border_Color': R.chart_data[i].Legend4_Border_Color,
                        'legend5_Border_Color': R.chart_data[i].Legend5_Border_Color,
                        'legend6_Border_Color': R.chart_data[i].Legend6_Border_Color,
                        'Chart_Scale': R.chart_data[i].Chart_Scale,
                        'Format': R.chart_data[i].Format,
                    });

                    var label = [];
                    var value1 = [];
                    var value2 = [];
                    var value3 = [];
                    var value4 = [];
                    $.each(R.chart_data[i].chart_detail, function (j) {
                        label[j] = R.chart_data[i].chart_detail[j].chart_label;
                        value1[j] = R.chart_data[i].chart_detail[j].chart_value1;
                        value2[j] = R.chart_data[i].chart_detail[j].chart_value2;
                        value3[j] = R.chart_data[i].chart_detail[j].chart_value3;
                        value4[j] = R.chart_data[i].chart_detail[j].chart_value4;
                    });

                    var params = {
                        chart_type : chart_type,
                        position : position,
                        title : title,
                        legend1 : legend1,
                        legend2 : legend2,
                        legend3 : legend3,
                        legend4 : legend4,
                        label : label,
                        value1 : value1,
                        value2 : value2,
                        value3 : value3,
                        value4 : value4,
                        legendColors : legendColors
                    }
                    if ($('#can-' + position).length > 0) {
                        $('#can-' + position).closest('div.card').attr('style','border: 1px solid #bbbfc3 !important;');
                        if (R.chart_data[i].chart_type === 'googlebarline') {
                            google.setOnLoadCallback(function() {
                                drawGoogleBarLineChart(params);
                            });
                            $('#can-title-' + position).text(title);

                        }
                        if (R.chart_data[i].chart_type === 'googlebar') {
                            google.setOnLoadCallback(function() {
                                googleBarChart(params);
                            });
                            $('#can-title-' + position).text(title);
                        }
                        if (R.chart_data[i].chart_type === 'googleline') {
                            google.setOnLoadCallback(function() {
                                googleLineChart(params);
                            });
                            $('#can-title-' + position).text(title);
                        }
                        //charts.push(chart_img)
                    }
                });

                pdfdata.push({
                    eval_sum_table : R.eEvalSumHtml,
                    eval_dtl_table : R.eEvalDetailHtml,
                    //charts : charts
                });

                console.log('pdfdata ----',pdfdata);
            }else{
                $('#singlecampevals').html('');
                $('#singlecampevald').html('');
            }
        }
    });

    function changeModelScore(obj) {
        if(obj.val() !== ""){
            ACFn.sendAjax('model/single','get',{
                ModelScoreID : obj.val()
            })
        }else{
            $('#singlecampevals').html('');
            $('#singlecampevald').html('');
        }
    }
</script>
