<tr>
    {{--<td class="text-center">
        <label class="custom-control custom-checkbox m-b-0">
            <input type="checkbox" class="custom-control-input checkbox" value="{!! $record['CampaignID_Data'] !!}">
            <span class="custom-control-label"></span>
        </label>
    </td>--}}

    <td>{!! $record['CampaignId'] !!}</td>

    <td onclick="editfield($(this))"
        data-campaignid="{!! $record['CampaignId'] !!}"
        data-field-name="CampaignName"
        class="edit-field cursor-pointer">
        {!! $record['CampaignName'] !!}
    </td>

    <td onclick="editfield($(this))"
        data-campaignid="{!! $record['CampaignId'] !!}"
        data-field-name="Template"
        class="edit-field cursor-pointer">
        {!! $record['Template'] !!}
    </td>

    <td class="text-center">
        {!! $record['Time1'] !!}
    </td>

    <td class="text-center edit-field cursor-pointer"
        onclick="editfield($(this))"
        data-campaignid="{!! $record['CampaignId'] !!}"
        data-field-name="StartDate">
        {!! $record['StartDate'] !!}
    </td>

    <td class="text-center edit-field cursor-pointer"
        onclick="editfield($(this))"
        data-campaignid="{!! $record['CampaignId'] !!}"
        data-field-name="EndDate">
        {!! $record['EndDate'] !!}
    </td>

    <td class="edit-field cursor-pointer"
        onclick="editfield($(this))"
        data-campaignid="{!! $record['CampaignId'] !!}"
        data-field-name="Subject1">
        {!! $record['Subject1'] !!}
    </td>

    <td class="edit-field cursor-pointer"
        onclick="editfield($(this))"
        data-campaignid="{!! $record['CampaignId'] !!}"
        data-field-name="Subject2">
        {!! $record['Subject2'] !!}
    </td>

    <td class="edit-field cursor-pointer"
        onclick="editfield($(this))"
        data-campaignid="{!! $record['CampaignId'] !!}"
        data-field-name="Subject3">
        {!! $record['Subject3'] !!}
    </td>

    <td class="edit-field cursor-pointer"
        onclick="editfield($(this))"
        data-campaignid="{!! $record['CampaignId'] !!}"
        data-field-name="TestSubject">
        {!! $record['TestSubject'] !!}
    </td>

    <td class="edit-field cursor-pointer"
        onclick="editfield($(this))"
        data-campaignid="{!! $record['CampaignId'] !!}"
        data-field-name="CampaignID_Data">
        {!! $record['CampaignID_Data'] !!}
    </td>

    <td class="edit-field cursor-pointer"
        onclick="editfield($(this))"
        data-campaignid="{!! $record['CampaignId'] !!}"
        data-field-name="TestSubjectPct">
        {!! $record['TestSubjectPct'] !!}
    </td>

    <td class="edit-field cursor-pointer"
        onclick="editfield($(this))"
        data-campaignid="{!! $record['CampaignId'] !!}"
        data-field-name="SubjectWin">
        {!! $record['SubjectWin'] !!}
    </td>
</tr>
