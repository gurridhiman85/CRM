<?php
foreach( $ImportData[0] as $key=>$cell ){
    $found_key = array_search(trim($cell), array_column($columns, 'Field_Display_Name'));
    if($found_key > -1){
        $columns[$found_key] = [
            'RowID' => $columns[$found_key]['RowID'],
            'Field_Display_Name' => $columns[$found_key]['Field_Display_Name'],
            'Field_Db_Name' => $columns[$found_key]['Field_Db_Name'],
            'is_display' => 0,
        ];
    }
}
$matches = [];
$orgColumns = $columns;
foreach( $ImportData[0] as $key=>$cell ){
    $html = ' <select class="form-control" id="col_'.$key.'" onchange="colChange($(this),'.$key.')"><option value="">Select a field</option>';
    $is_match = 0;
    $mt = '';
    foreach($columns as $ckey => $column){
        if(trim($cell) == $column['Field_Display_Name']){
            $matches[] = strtolower($column['Field_Display_Name']);
            $mt = $column['Field_Db_Name'];
            $is_match = 1;
            $html .= '<option value="'.$column['Field_Db_Name'].'" selected>'.$column['Field_Display_Name'].'</option>';
        }else{
            if(!isset($column['is_display'])){
                $html .= '<option value="'.$column['Field_Db_Name'].'">'.$column['Field_Display_Name'].'</option>';
            }else{
                $html .= '<option style="display:none;" value="'.$column['Field_Db_Name'].'">'.$column['Field_Display_Name'].'</option>';
            }
        }
    }
    $html .= '</select><input type="hidden" id="col_hidden_'.$key.'" name="col_hidden_'.$key.'" value="'.$mt.'"/>';
    $columnsData[] = [
        'html' => $html,
        'is_match' => $is_match,
    ];
}

?>
<table class="table table-bordered table-hover color-table lkp-table" id="match_fields_table">
    <thead>
        <tr>

            @foreach( $columnsData as $key=>$column )
                <th class="{{ $column['is_match'] == 1 ? 'matched' : 'unmatched' }}" data-class="{{ $key }}_col" data-col-key="{{ $key }}">
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


