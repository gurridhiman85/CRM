<table class="table table-bordered table-hover color-table lkp-table">
    <thead>
    <tr>
        <th rowspan="2">
            <label class="custom-control custom-checkbox m-b-0">
                <input type="checkbox" class="custom-control-input" id="select_all" onclick="selectAll($(this))">
                <span class="custom-control-label"></span>
            </label>
        </th>
        <th rowspan="2">Contact ID</th>
        <th class="text-center new-rec" colspan="4">New</th>
        <th class="text-center" colspan="4">Old</th>
        <th class="text-center" colspan="3">Address Quality</th>
    </tr>
    <tr>
        <th class="new-rec">Address</th>
        <th class="new-rec">City</th>
        <th class="new-rec">State</th>
        <th class="new-rec">Zip</th>

        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Zip</th>

        <th>Compare</th>
        <th>New</th>
        <th>Old</th>
    </tr>
    </thead>
    <tbody>
    @foreach($records as $record)
        <tr>
            <td>
                <!--<input type="checkbox" class="checkbox" value="1"/>-->
                <label class="custom-control custom-checkbox m-b-0">
                    <input type="checkbox" class="custom-control-input checkbox" name="cids[{!! $record['ds_mkc_contactid'] !!}]" onclick="singleCheckbox();" value="{!! $record['ds_mkc_contactid'] !!}">
                    <span class="custom-control-label"></span>
                </label>
            </td>
            <td>{{ $record['ds_mkc_contactid'] }}</td>

            <td class="new-rec">{{ $record['New Address'] }}</td>
            <td class="new-rec">{{ $record['New City'] }}</td>
            <td class="new-rec">{{ $record['New State'] }}</td>
            <td class="new-rec">{{ $record['New Zip'] }}</td>

            <td>{{ $record['Old Address'] }}</td>
            <td>{{ $record['Old City'] }}</td>
            <td>{{ $record['Old State'] }}</td>
            <td>{{ $record['Old Zip'] }}</td>

            <td>{{ $record['Compare Address Quality'] }}</td>
            <td>{{ $record['New Address Quality'] }}</td>
            <td>{{ $record['Old Address Quality'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
