{!! csrf_field() !!}
<input type="hidden" id="SR_list_level" value="{!! $list_level !!}">
<input type="hidden" id="SR_sql" value="{!! $sql !!}">
<div class="row">
    <div class="col-md-2 pl-0">
        <div class="form-group">
            <small class="form-control-feedback ml-2 pl-1 ds-l">Row Variable</small>
            <select name="row_variable" @if(isset($popup)) multiple="multiple" id="row_variable" @endif class="form-control form-control-sm row_variable">
                @if(count($lkpOptions))
                    @foreach($lkpOptions as $lkpOption)
                        <option {!! is_array($params['row_variable']) ? (in_array($lkpOption,$params['row_variable']) ? 'selected' : '') : $params['row_variable'] == $lkpOption ? 'selected' : '' !!} value="{!! $lkpOption !!}">{!! $lkpOption !!}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <small class="form-control-feedback ml-2 pl-1 ds-l">Column Variable</small>
            <select name="column_variable" @if(isset($popup)) id="column_variable" @endif onchange="ChangeColumn($(this))" class="form-control form-control-sm column_variable">
                <option value="">Select</option>
                @if(count($lkpOptions))
                    @foreach($lkpOptions as $lkpOption)
                        <option {!! $params['column_variable'] == $lkpOption ? 'selected' : '' !!} value="{!! $lkpOption !!}">{!! $lkpOption !!}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="col-md-1">
        <div class="form-group">
            <small class="form-control-feedback ml-2 pl-1 ds-l">Function</small>
            <select name="function_variable" @if(isset($popup)) id="function_variable" @endif class="form-control form-control-sm function_variable" onchange="$(this).val() == 'count' ? ($('.sf').hide() , $('.btn-box').removeClass('col-md-1').addClass('col-md-2') ) : ($('.sf').show(), $('.btn-box').removeClass('col-md-2').addClass('col-md-1'));">
                <option {!! $params['function_variable'] == 'count' ? 'selected' : '' !!} value=count>Count</option>
                <option {!! $params['function_variable'] == 'sum' ? 'selected' : '' !!} value=sum>Sum</option>
                <option {!! $params['function_variable'] == 'cs' ? 'selected' : '' !!} style="{!! !empty($params['column_variable']) ? 'display: none;' : '' !!}" value=cs>Count & Sum</option>
                <option {!! $params['function_variable'] == 'sc' ? 'selected' : '' !!} style="{!! !empty($params['column_variable']) ? 'display: none;' : '' !!}" value=sc>Sum & Count</option>
            </select>
        </div>
    </div>

    <div class="col-md-1 sf" @if(empty($params['sum_variable']) && $params['function_variable'] == 'count') style="display: none;" @endif>
        <div class="form-group">
            <small class="form-control-feedback ml-2 pl-1 ds-l">Sum Variable</small>
            <select name="sum_variable" @if(isset($popup)) id="sum_variable" @endif class="form-control form-control-sm sum_variable">
                <option value="">Select</option>
                @if(count($numOptions))
                    @foreach($numOptions as $numOption)
                        <option {!! $params['sum_variable'] == $numOption ? 'selected' : '' !!} value="{!! $numOption !!}">{!! $numOption !!}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <small class="form-control-feedback ml-2 pl-1 ds-l">Show Table Values as</small>
            <select name="show_as" @if(isset($popup)) id="show_as" @endif class="form-control form-control-sm show_as">

                <option {!! $params['show_as'] == 'np' ? 'selected' : '' !!} style="{!! !empty($params['column_variable']) ? 'display:none' : '' !!}" rel="Number and Percent" value="np">Number and Percent</option>
                <option {!! $params['show_as'] == 'pn' ? 'selected' : '' !!} style="{!! !empty($params['column_variable']) ? 'display:none;' : '' !!}" rel="Percent and Number" value="pn">Percent and Number</option>
                <option {!! $params['show_as'] == 'number' ? 'selected' : '' !!} style="{!! empty($params['column_variable']) ? 'display:none;' : '' !!}" rel="Number" value="number">Number</option>
                <option {!! $params['show_as'] == 'sbn' ? 'selected' : '' !!} style="{!! empty($params['column_variable']) ? 'display:none;' : '' !!}" rel="Side By Number" value="sbn">Side By Number</option>
                <option {!! $params['show_as'] == 'prt' ? 'selected' : '' !!} style="{!! empty($params['column_variable']) ? 'display:none;' : '' !!}" rel="Percent of Row Total" value="prt">Percent of Row Total</option>
                <option {!! $params['show_as'] == 'pct' ? 'selected' : '' !!} style="{!! empty($params['column_variable']) ? 'display:none;' : '' !!}" rel="Percent of Column Total" value="pct">Percent of Column Total</option>
                <option {!! $params['show_as'] == 'pgt' ? 'selected' : '' !!} style="{!! empty($params['column_variable']) ? 'display:none;' : '' !!}" rel="Percent of Grand Total" value="pgt">Percent of Grand Total</option>
                <option {!! $params['show_as'] == 'nprt' ? 'selected' : '' !!} style="{!! empty($params['column_variable']) ? 'display:none;' : '' !!}" rel="Number and Percent of Row Total" value="nprt">Number and Percent of Row Total</option>
                <option {!! $params['show_as'] == 'prtn' ? 'selected' : '' !!} style="{!! empty($params['column_variable']) ? 'display:none;' : '' !!}" rel="Percent of Row Total and Number" value="prtn">Percent of Row Total and Number</option>
                <option {!! $params['show_as'] == 'npct' ? 'selected' : '' !!} style="{!! empty($params['column_variable']) ? 'display:none;' : '' !!}" rel="Number and Percent of Column Total" value="npct">Number and Percent of Column Total</option>
                <option {!! $params['show_as'] == 'pctn' ? 'selected' : '' !!} style="{!! empty($params['column_variable']) ? 'display:none;' : '' !!}" rel="Percent of Column Total and Number" value="pctn">Percent of Column Total and Number</option>
            </select>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <small class="form-control-feedback ml-2 pl-1 ds-l">Chart Type</small>
            <select name="chart_variable" @if(isset($popup)) onchange="comChangeOnSrPopup();" id="chart_variable" @endif class="form-control form-control-sm chart_variable chart_change">
                <option value="">None</option>
                <option {!! $params['chart_variable'] == 'pie' ? 'selected' : '' !!} value="pie">Pie Chart</option>
                <option {!! $params['chart_variable'] == 'column' ? 'selected' : '' !!} value="column">Bar Chart</option>
                <option {!! $params['chart_variable'] == 'line' ? 'selected' : '' !!} value="line">Line Chart</option>
            </select>

        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <small class="form-control-feedback ml-2 pl-1 ds-l">Chart Scale</small>
            <select name="chart_axis_scale" @if(isset($popup)) onchange="comChangeOnSrPopup();" id="chart_axis_scale" @endif class="form-control form-control-sm chart_axis_scale">
                <option {!! $params['chart_axis_scale'] == 'lin' ? 'selected' : '' !!}  value="lin">Linear</option>
                <option {!! $params['chart_axis_scale'] == 'log' ? 'selected' : '' !!}  value="log">Log</option>
            </select>

        </div>
    </div>
    <div class="col-md-1 pr-0">
        <div class="form-group">
            <small class="form-control-feedback ml-2 pl-1 ds-l">Chart Values</small>
            <select name="chart_label_value" onchange="comChangeOnSrPopup();" id="chart_label_value" class="form-control form-control-sm chart_label_value">
                <option {!! $params['chart_label_value'] == 0 ? 'selected' : '' !!} value="0">Hide Value & Hide Axis</option>
                <option {!! $params['chart_label_value'] == 1 ? 'selected' : '' !!} value="1">Hide Value & Show Axis</option>
                <option {!! $params['chart_label_value'] == 2 ? 'selected' : '' !!} value="2">Show Value & Hide Axis</option>
                <option {!! $params['chart_label_value'] == 3 ? 'selected' : '' !!} value="3">Show Value & Show Axis</option>
            </select>

        </div>
    </div>

    <div class="{!! $params['function_variable'] == 'count' ? 'col-md-2' : 'col-md-1' !!} pt-2 pl-1 pr-0 btn-box">
        <div class="btn-toolbar pull-right" role="toolbar" aria-label="Toolbar with button groups">
            <div class="input-group">
                <button type="button" class="btn-light border-right-0 font-18 s-f sr-btn" title="Run Report" onclick="run_report();"><i class="fas fa-play-circle" style="color: #90c3d7"></i></button>
                <button type="button" class="btn-light border-right-0 font-18 s-f sr-btn" title="Download Pdf" onclick="d_pdf(1);"><i class="fas fa-file-pdf" style="color: #e92639;"></i></button>
                <button type="button" class="btn-light font-18 s-f sr-btn" title="Download XLSX" onclick="d_pdf(2);"><i class="fas fa-file-excel" style="color: #06b489;"></i></button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <span id="indicationCFMsgForDistribution" style="color:#5eb5d7;font-size:13px"></span>
</div>

@if(isset($popup))

    <div class="row" id="distributionResultHtml" style="overflow: auto;"></div>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div id="chartP" style="display: block;width: 100%; max-width:800px; height: 530px; "></div>
        </div>
        <div class="col-md-1"></div>
    </div>
    <script>
        $(document).ready(function () {

            if($('#row_variable').val() != ""){
                run_report();
            }
        })

        function comChangeOnSrPopup() {
            chart_change($('#row_variable').val(),$('#column_variable').val(),$('#sum_variable').val(),$('#function_variable').val(),$('#show_as option:selected').val(),$('#chart_variable').val(),'column_variable',$('#chart_axis_scale').val(),$('#chart_label_value').val(),document.getElementById('chartP'));
        }
    </script>
@endif
