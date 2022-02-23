<table id="basic_table2" class="table table-bordered table-hover color-table lkp-table">
    <thead>
    <tr>
        <th>Tag</th>
        <th>
            <label class="custom-control custom-checkbox m-b-0">
                <input type="checkbox" class="custom-control-input" id="select_all" onclick="selectAll($(this))">
                <span class="custom-control-label"></span>
            </label>
        </th>
        <th>New Contact</th>
        <th>Contact</th>
        <th>HH ID</th>
        <th>Email</th>
        <th>Email2</th>
        <th>DQ</th>
        <th>Phone</th>
        <th>Extended Name</th>
        <th>Company Name</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Zip</th>
        <th>DQ</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $checkDupes = 0;
    $is_blank_row = true;
    ?>
    @foreach($records as $key=>$record)
        @if($checkDupes == 0)
            @php $checkDupes = $record['ds_contactid_s2']; @endphp
        @endif

        @if ($checkDupes == $record['ds_contactid_s2'])
            <tr>
                <td>
                    <!--<input type="checkbox" class="checkbox" value="1"/>-->
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input checkbox" onclick="reviewDupsContact($(this),{!! $record['ds_mkc_contactid'] !!});" {!! $record['tag'] == 1 ? 'checked' : '' !!} value="1">
                        <span class="custom-control-label"></span>
                    </label>
                </td>

                <td>
                    <!--<input type="checkbox" class="checkbox" value="1"/>-->
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input checkbox" name="mids[{!! $record['ds_mkc_contactid'] !!}]" onclick="singleCheckbox();" value="{!! $record['ds_contactid_s2'] !!}">
                        <span class="custom-control-label"></span>
                    </label>
                </td>
                <td>{!! $record['ds_contactid_s2'] !!}</td>
                <td>{!! $record['ds_mkc_contactid'] !!}</td>
                <td>{!! $record['ds_mkc_householdid'] !!}</td>
                <td>{!! $record['email'] !!}</td>
                <td>{!! $record['email2'] !!}</td>
                <td>{!! $record['dqcode_email'] !!}</td>
                <td>{!! $record['phone'] !!}</td>
                <td>{!! $record['extendedname'] !!}</td>
                <td>{!! $record['Company'] !!}</td>
                <td>{!! $record['address'] !!}</td>
                <td>{!! $record['city'] !!}</td>
                <td>{!! $record['state'] !!}</td>
                <td>{!! $record['zip'] !!}</td>
                <td>{!! $record['dqcode_address'] !!}</td>
            </tr>

        @elseif ($checkDupes != $record['ds_contactid_s2'])
            <tr style="background-color: #f9f9f9;">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td>
                    <!--<input type="checkbox" class="checkbox" value="1"/>-->
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input checkbox" name="mids[{!! $record['ds_mkc_contactid'] !!}]" onclick="singleCheckbox();" value="{!! $record['ds_contactid_s2'] !!}">
                        <span class="custom-control-label"></span>
                    </label>
                </td>
                <td>{!! $record['ds_contactid_s2'] !!}</td>
                <td>{!! $record['ds_mkc_contactid'] !!}</td>
                <td>{!! $record['ds_mkc_householdid'] !!}</td>
                <td>{!! $record['email'] !!}</td>
                <td>{!! $record['dqcode_email'] !!}</td>
                <td>{!! $record['phone'] !!}</td>
                <td>{!! $record['extendedname'] !!}</td>
                <td>{!! $record['Company'] !!}</td>
                <td>{!! $record['LetterName'] !!}</td>
                <td>{!! $record['address'] !!}</td>
                <td>{!! $record['city'] !!}</td>
                <td>{!! $record['state'] !!}</td>
                <td>{!! $record['zip'] !!}</td>
                <td>{!! $record['dqcode_address'] !!}</td>
                <td>{!! $record['zss_segment'] !!}</td>
            </tr>
            @php $checkDupes = 0; @endphp
        @endif
    @endforeach
    </tbody>
</table>

