<table class="table table-bordered table-hover color-table lkp-table">
    <thead>
    <tr>
        <th>
            <label class="custom-control custom-checkbox m-b-0">
                <input type="checkbox" class="custom-control-input" id="select_all" checked onclick="selectAll($(this))">
                <span class="custom-control-label"></span>
            </label>
        </th>
        <th>Zoom Name</th>
        <th>DFL Name</th>
        <th>Zoom Email</th>
        <th>DS_MKC_ContactID</th>

    </tr>
    </thead>
    <tbody>
    @foreach($sStep2TableRows as $sStep2TableRow)
        <tr>
            <td>
                <!--<input type="checkbox" class="checkbox" value="1"/>-->
                <label class="custom-control custom-checkbox m-b-0">
                    <input type="checkbox" class="custom-control-input checkbox" checked name="rowid[]" value="{{ $sStep2TableRow['rowid'] }}" onclick="singleCheckbox();" >
                    <span class="custom-control-label"></span>
                </label>
            </td>
            <td>{{ $sStep2TableRow['customer_s2'] }}</td>
            <td>{{ $sStep2TableRow['dflname'] }}</td>
            <td>{{ $sStep2TableRow['email'] }}</td>
            <td>{{ $sStep2TableRow['DS_MKC_ContactID'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

