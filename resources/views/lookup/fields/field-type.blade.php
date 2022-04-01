<?php
$Field_Name = $set['Field_Name'];
$Class = $set['Class'];
$Custom = $set['Custom'];
$Options = htmlspecialchars(json_encode(json_decode($set['Options'])), ENT_QUOTES, 'UTF-8');
$SQL = $set['SQL'];
$data_attr = 'data-field_name="'.$Field_Name.'" data-class_name="'.$Class.'" data-custom_option="'.$Custom.'" data-options="'.$Options.'" data-SQL="'.$SQL.'" name="'.$targetFieldName.'"';

$options = [];
if($set['Custom']){
    if(!empty($set['SQL'])){
        $records = DB::select($set['SQL']);
        $options = collect($records)->map(function($x){ return (array) $x; })->toArray();
    }
}else{
    $options = json_decode($set['Options'],true);
}

?>
@if($set['Field_Type'] == 'select')
    <select
            data-field_type="select"
            {!! $data_attr !!}
            onclick="updateField('{{ $targetClass }}' ,'{{ $targetFieldName }}',$(this))"
            class="form-control form-control-sm">
        <option value="">Select</option>
        @if(is_array($options))
            @foreach($options as $option)
                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
            @endforeach
        @endif
    </select>

@elseif($set['Field_Type'] == 'date')
    <div class="input-group">
        <input type="text"
               data-field_type="date"
               {!! $data_attr !!}
               class="t8 form-control form-control-sm js-datepicker"
               onclick="updateField('{{ $targetClass }}' ,'{{ $targetFieldName }}',$(this))"
               style="height: 28px !important;"
               autocomplete="off">
        <div class="input-group-append">
            <span class="input-group-text" onclick="$('input').closest().trigger('focus');"><i class="fas fa-calendar-alt font-14 ds-c"></i></span>
        </div>
    </div>
 {{--initJS($('.' + targetClass + '_fieldbox'))--}}
@elseif($set['Field_Type'] == 'readonly')
    <input type="text"
           {!! $data_attr !!}
           data-field_type="readonly"
           onclick="updateField('{{ $targetClass }}' ,'{{ $targetFieldName }}',$(this))"
           class="form-control form-control-sm" readonly
           autocomplete="off">
@elseif($set['Field_Type'] == 'textarea')
    <textarea  data-field_type="textarea"
               {!! $data_attr !!}
               onclick="updateField('{{ $targetClass }}' ,'{{ $targetFieldName }}',$(this))"
               class="form-control form-control-sm"
               autocomplete="off"></textarea>
@else
    <input type="text"
           data-field_type="text"
           {!! $data_attr !!}
           onclick="updateField('{{ $targetClass }}' ,'{{ $targetFieldName }}',$(this))"
           class="form-control form-control-sm"
           autocomplete="off">
@endif
