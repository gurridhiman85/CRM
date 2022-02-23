<?php
$columnsData = array();
$matches = [];
$orgColumns = $columns;
foreach( $ImportData[0] as $key=>$cell ){
    $html = ' <select class="form-control" onchange="changeMatchSel($(this))"><option value="">Select a field</option>';
    $is_match = 0;
    $mt = '';
    foreach($columns as $ckey => $column){
        if(strtolower(trim($cell)) == strtolower($column['Field_Display_Name'])){
            $matches[] = strtolower($column['Field_Display_Name']);
            $is_match = 1;
            $mt = $column['Field_Db_Name'];
            $html .= '<option value="'.$column['Field_Db_Name'].'" selected>'.$column['Field_Display_Name'].'</option>';
            //unset($columns[$ckey]);
            $orgColumns[$ckey]['is_match'] = 1;
        }else{
            $orgColumns[$ckey]['is_match'] = 0;
            $html .= '<option value="'.$column['Field_Db_Name'].'">'.$column['Field_Display_Name'].'</option>';
        }
    }
    $html .= '</select><input type="hidden" id="'.$key.'_col_hidden" value="'.$mt.'"/>';
    $columnsData[] = [
        'html' => $html,
        'is_match' => $is_match,
    ];
}
/*echo '<pre>';
print_r($columnsData);
print_r($matches);
print_r($ImportData[0]);

die;*/
?>
<div class="row">
    {{--<div class="col-md-6">
        {{count($columnsData) - count($matches)}} <b> unmatched columns</b> <a href="javascript:void(0)">Skip unmatched</a>
    </div>
    <div class="col-md-6">
        <span class="pull-right">Preview ({{count($ImportData) -1 }} of {{count($ImportData) -1 }} contacts shown, {{count($ImportData) -1 }}) columns total</span>
    </div>--}}
</div>
<table class="table table-bordered table-hover color-table lkp-table" id="table_task">
    <thead>
    <tr>

        @foreach( $columnsData as $key=>$column )
            <th class="{{ $column['is_match'] == 1 ? 'matched' : 'unmatched' }}" data-class="{{ $key }}_col">
                {!! $column['html'] !!}
            </th>
        @endforeach

    </tr>
    </thead>
    <tbody>
    @php $columnslist = []; @endphp
    @foreach( $ImportData as $key=>$singleRow )
        @if($key == 0)
            @foreach( $singleRow as $cell )
                <?php $columnslist[] = trim(strtolower($cell)); ?>
            @endforeach
        @endif
        @if($key > 0)
            <tr>
                @foreach( $singleRow as $cKey=>$cell )
                    <td class="{{ in_array($columnslist[$cKey],$matches) ? 'matched' : 'unmatched' }} {{ $cKey }}_col">
                        {!! $cell !!}
                    </td>
                @endforeach
            </tr>
        @endif
    @endforeach
    </tbody>
</table>

<script type="application/javascript">
    function changeMatchSel(obj) {
        var columnsData = @php echo json_encode($orgColumns); @endphp;

        /*$('#match_fields_table').find("tr th").each(function() {

            var quantity1 = $(this).find("input.name").val(),
                quantity2 = $(this).find("input.id").val();
        });*/

        var efCls = obj.parents().data('class');
        if(obj.val() != ""){
            $('.' + efCls).removeClass('unmatched').addClass('matched');
            obj.parents('th').removeClass('unmatched').addClass('matched');
            $('#' + efCls + '_hidden').val(obj.val());

        }else{
            $('.' + efCls).removeClass('matched').addClass('unmatched');
            obj.parents('th').removeClass('matched').addClass('unmatched');
            $('#' + efCls + '_hidden').val('');
        }
        console.log(columnsData);
    }
</script>

