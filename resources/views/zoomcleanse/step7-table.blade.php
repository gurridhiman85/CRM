<table id="basic_table_without_dynamic_pagination" class="table table-bordered table-hover color-table lkp-table">
    <thead>
    <tr>
        <th>
            <label class="custom-control custom-checkbox m-b-0">
                <input type="checkbox" class="custom-control-input" id="select_all" onclick="selectAll($(this))">
                <span class="custom-control-label"></span>
            </label>
        </th>
        <th>Zoom Name S2</th>
        <th>Salutation</th>
        <th>Dharma Name</th>
        <th>First Name</th>
        <th>Middle Name</th>
        <th>Last Name</th>
        <th>Suffix</th>
        <th>DFL Name</th>
        <th>Email</th>

    </tr>
    </thead>
    <tbody>
    @foreach($sStep7TableRows as $sStep7TableRow)
        <tr>
            <td>
                <?php $is_checked = !empty($sStep7TableRow['email']) && $sStep7TableRow['email'] != null   ? 'checked' : ''; ?>
                <label class="custom-control custom-checkbox m-b-0">
                    <input type="checkbox" class="custom-control-input checkbox" id="checkbox_{!! $sStep7TableRow['rowid'] !!}" onclick="singleCheckbox();" {!! $is_checked !!} name="rowid[]" value="{!! $sStep7TableRow['rowid'] !!}">
                    <span class="custom-control-label"></span>
                </label>
            </td>
            <td>{{ $sStep7TableRow['customer_s2'] }}</td>
            <td>{{ $sStep7TableRow['salutation'] }}</td>
            <td>
                <input type="text"
                       class="form-control border-0 form-control-sm "
                       onkeyup="ajax_quick_edit($(this))"
                       data-field="Dharmaname"
                       data-rowid="{{ $sStep7TableRow['rowid'] }}"
                       value="{{ $sStep7TableRow['dharmaname'] }}">
            </td>
            <td>
                <input type="text"
                       class="form-control border-0 form-control-sm "
                       onkeyup="ajax_quick_edit($(this))"
                       data-field="Firstname"
                       data-rowid="{{ $sStep7TableRow['rowid'] }}"
                       value="{{ $sStep7TableRow['firstname'] }}">
            </td>
            <td>
                <input type="text"
                       class="form-control border-0 form-control-sm "
                       onkeyup="ajax_quick_edit($(this))"
                       data-field="Middlename"
                       data-rowid="{{ $sStep7TableRow['rowid'] }}"
                       value="{{ $sStep7TableRow['middlename'] }}">
            </td>
            <td>
                <input type="text"
                       class="form-control border-0 form-control-sm "
                       onkeyup="ajax_quick_edit($(this))"
                       data-field="Lastname"
                       data-rowid="{{ $sStep7TableRow['rowid'] }}"
                       value="{{ $sStep7TableRow['lastname'] }}">
            </td>
            <td>
                <input type="text"
                       class="form-control border-0 form-control-sm "
                       onkeyup="ajax_quick_edit($(this))"
                       data-field="Suffix"
                       data-rowid="{{ $sStep7TableRow['rowid'] }}"
                       value="{{ $sStep7TableRow['suffix'] }}">
            </td>
            <td>
                <span id="s7dflname_{{ $sStep7TableRow['rowid'] }}">{{ $sStep7TableRow['dflname'] }}</span>
            </td>
            <td>
                <input type="text"
                       class="form-control border-0 form-control-sm "
                       onkeyup="ajax_quick_edit($(this))"
                       data-field="Email"
                       data-rowid="{{ $sStep7TableRow['rowid'] }}"
                       value="{{ $sStep7TableRow['email'] }}">
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    function ajax_quick_edit(obj) {
        var rowid = obj.data('rowid');
        var fieldname = obj.data('field');
        var fieldvalue = obj.val();
        delay(function(){
            ACFn.sendAjax('zoomcleanse/step7quickedit','POST',{
                rowid : rowid,
                fieldname : fieldname,
                fieldvalue : fieldvalue,
            })
        }, 1000 );
    }
</script>
