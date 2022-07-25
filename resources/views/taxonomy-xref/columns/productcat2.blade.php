@if(isset($editable) && $editable == 1)
    <input type="text"
           class="form-control border-0 form-control-sm "
           style="width: 100px;"
           onkeyup="ajax_quick_update($(this))"
           data-field="productcat2"
           data-productdescription="{{ $record['productdescription'] }}"
           value="{{ $record['productcat2'] }}">
@else
    {{ $record['productcat2'] }}
@endif
