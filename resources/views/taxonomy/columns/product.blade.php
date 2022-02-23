@if(isset($editable) && $editable == 1)
    <input type="text"
           class="form-control border-0 form-control-sm "
           style="width: 300px;"
           onkeyup="ajax_quick_update($(this))"
           data-field="product"
           data-productdescription="{{ $record['productdescription'] }}"
           value="{{ $record['product'] }}">
@else
    {{ $record['product'] }}
@endif
