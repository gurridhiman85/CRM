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
        <th class="text-center new-rec" colspan="5">New</th>
        <th class="text-center" colspan="5">Old</th>
    </tr>

    <tr>

        <th class="new-rec">Extendedname</th>
        <th class="new-rec">Dharmaname</th>
        <th class="new-rec">Firstname</th>
        <th class="new-rec">Middlename</th>
        <th class="new-rec">Lastname</th>

        <th>Extendedname</th>
        <th>Dharmaname</th>
        <th>Firstname</th>
        <th>Middlename</th>
        <th>Lastname</th>
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

            <td class="new-rec">{{ $record['New_Extendedname'] }}</td>
            <td class="new-rec">{{ $record['New_Dharmaname'] }}</td>
            <td class="new-rec">{{ $record['New_Firstname'] }}</td>
            <td class="new-rec">{{ $record['New_Middlename'] }}</td>
            <td class="new-rec">{{ $record['New_Lastname'] }}</td>

            <td>{{ $record['Old_Extendedname'] }}</td>
            <td>{{ $record['Old_Dharmaname'] }}</td>
            <td>{{ $record['Old_Firstname'] }}</td>
            <td>{{ $record['Old_Middlename'] }}</td>
            <td>{{ $record['Old_Lastname'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
