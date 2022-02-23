<table class="table table-bordered table-hover color-table lkp-table">
    <thead>
    <tr>
        <th>
            <label class="custom-control custom-checkbox m-b-0">
                <input type="checkbox" class="custom-control-input" id="select_all" onclick="selectAll($(this))">
                <span class="custom-control-label"></span>
            </label>
        </th>
        <th>Zoom Name</th>
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
    @foreach($sStep2TableRows as $sStep2TableRow)
        <tr>
            <td>
                <!--<input type="checkbox" class="checkbox" value="1"/>-->
                <label class="custom-control custom-checkbox m-b-0">
                    <input type="checkbox" class="custom-control-input checkbox" id="checkbox_{{ $sStep2TableRow['rowid'] }}" name="rowid[]" value="{{ $sStep2TableRow['rowid'] }}" onclick="singleCheckbox();" >
                    <span class="custom-control-label"></span>
                </label>
            </td>
            <td>{{ $sStep2TableRow['customer_s2'] }}</td>
            <td>{{ $sStep2TableRow['salutation'] }}</td>
            <td><input type="text" class="form-control border-0 form-control-sm ajax-quick-edit" data-field="Dharmaname"
                       data-rowid="{{ $sStep2TableRow['rowid'] }}" value="{{ $sStep2TableRow['dharmaname'] }}"></td>
            <td><input type="text" class="form-control border-0 form-control-sm ajax-quick-edit" data-field="Firstname"
                       data-rowid="{{ $sStep2TableRow['rowid'] }}" value="{{ $sStep2TableRow['firstname'] }}"></td>
            <td><input type="text" class="form-control border-0 form-control-sm ajax-quick-edit" data-field="Middlename"
                       data-rowid="{{ $sStep2TableRow['rowid'] }}" value="{{ $sStep2TableRow['middlename'] }}"></td>
            <td><input type="text" class="form-control border-0 form-control-sm ajax-quick-edit" data-field="Lastname"
                       data-rowid="{{ $sStep2TableRow['rowid'] }}" value="{{ $sStep2TableRow['lastname'] }}"></td>
            <td><input type="text" class="form-control border-0 form-control-sm ajax-quick-edit" data-field="Suffix"
                       data-rowid="{{ $sStep2TableRow['rowid'] }}" value="{{ $sStep2TableRow['suffix'] }}"></td>
            <td id="s7dflname_{{ $sStep2TableRow['rowid'] }}">{{ $sStep2TableRow['dflname'] }}</td>
            <td><input type="text" class="form-control border-0 form-control-sm ajax-quick-edit" data-field="Email"
                       data-rowid="{{ $sStep2TableRow['rowid'] }}" value="{{ $sStep2TableRow['email'] }}"></td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function () {
        $('.ajax-quick-edit').on('keyup',function () {
            var rowid = $(this).data('rowid');
            var fieldname = $(this).data('field');
            var fieldvalue = $(this).val();
            delay(function(){
                ACFn.sendAjax('importzoom/step7quickedit','POST',{
                    rowid : rowid,
                    fieldname : fieldname,
                    fieldvalue : fieldvalue,
                })
            }, 1000 );


        })
        
        ACFn.ajax_update_step7 = function (F , R) {
            if(R.success){
                $('#s7dflname_' + R.rowid).text(R.aData.DFLName);
                $('#checkbox_' + R.rowid).attr('checked',true);
            }
        }
    })
</script>
