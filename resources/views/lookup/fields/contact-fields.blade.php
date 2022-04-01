@if($set['Field_Type'] == 'select')
    <?php
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

    <select
            class="form-control form-control-sm txt{{ strtolower($set['Field_Name']) }} {{ $set['Class'] }} {{ $extra_class }}"
            name="{{ $set['Field_Name'] }}">
        <option value="">Select</option>
        @if(is_array($options))
            @foreach($options as $option)
                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
            @endforeach
        @endif
    </select>

@elseif($set['Field_Type'] == 'date')
    <div class="input-group {{ $extra_class }}">
        <input type="text"
               name="{{ $set['Field_Name'] }}"
               class="form-control form-control-sm js-datepicker txt{{ strtolower($set['Field_Name']) }} {{ $set['Class'] }}  {{ $extra_class }}"
               style="height: 28px !important;"
               autocomplete="off">
        <div class="input-group-append">
            <span class="input-group-text" onclick="$('input').closest().trigger('focus');">
                <i class="fas fa-calendar-alt font-14 ds-c"></i>
            </span>
        </div>
    </div>
    {{--initJS($('.' + targetClass + '_fieldbox'))--}}
@elseif($set['Field_Type'] == 'readonly')
    <?php
    $value = '';
    /*if($set['Custom']){
        if(!empty($set['SQL'])){
            $records = DB::select($set['SQL']);
            $value = collect($records)->map(function($x){ return (array) $x; })->toArray();
            if(isset($value[0]))
                $value = $value[0]['value'];
        }
    }*/
    ?>
    <input type="text"
           name="{{ $set['Field_Name'] }}"
           class="form-control form-control-sm txt{{ strtolower($set['Field_Name']) }} {{ $set['Class'] }}  {{ $extra_class }}"
           readonly
           autocomplete="off">
@elseif($set['Field_Type'] == 'textarea')
    <textarea
            name="{{ $set['Field_Name'] }}"
           class="form-control form-control-sm txt{{ strtolower($set['Field_Name']) }} {{ $set['Class'] }}  {{ $extra_class }}"
           autocomplete="off"></textarea>
@else
    <input type="text"
           name="{{ $set['Field_Name'] }}"
           class="form-control form-control-sm txt{{ strtolower($set['Field_Name']) }} {{ $set['Class'] }}  {{ $extra_class }}"
           autocomplete="off">
@endif
