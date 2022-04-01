@php
$class = 'badge badge-light';
if($record->TouchDate != null){
    switch ($record->TouchStatus){
        case 'Assigned':
        $class = 'badge badge-info';
        break;

        case 'Spoke on Phone':
        $class = 'badge badge-success';
        break;

        case 'User Returned Call':
        $class = 'badge badge-success';
        break;

        case 'User Returned Text':
        $class = 'badge badge-success';
        break;

        case 'Left Voicemail':
        $class = 'badge badge-warning';
        break;

        case 'Could not leave Voicemail':
        $class = 'badge badge-danger';
        break;

        case 'Phone not in service':
        $class = 'badge badge-danger';
        break;

        case 'Phone belongs to someone else':
        $class = 'badge badge-danger';
        break;

        case 'Suppressed':
        $class = 'badge badge-light';
        break;

        default:
        $class = 'badge badge-light';
        break;
    }
}
@endphp

<tr id="row_{{ $record->DS_MKC_ContactID }}">
    <td class="text-center">
        <div class="d-none">{{ $record->TouchStatus }}</div>
        <select
                class='form-control-sm'
                onchange="changeStatus($(this))"
                data-ds_mkc_contactid="{!! $record->DS_MKC_ContactID !!}"
                style="border-color: #bfe6f6;"
        >
            <option value="">Select</option>
            <option class="badge badge-info font-12" {!! $record->TouchStatus == 'Assigned' ? 'selected' : '' !!} value="Assigned">Assigned</option>
            <option class="badge badge-success font-12" {!! $record->TouchStatus == 'Spoke on Phone' ? 'selected' : '' !!} value="Spoke on Phone">Spoke on Phone</option>
            <option class="badge badge-success font-12" {!! $record->TouchStatus == 'User Returned Call' ? 'selected' : '' !!} value="User Returned Call">User Returned Call</option>
            <option class="badge badge-success font-12" {!! $record->TouchStatus == 'User Returned Text' ? 'selected' : '' !!} value="User Returned Text">User Returned Text</option>
            <option class="badge badge-warning font-12" {!! $record->TouchStatus == 'Left Voicemail' ? 'selected' : '' !!} value="Left Voicemail">Left Voicemail</option>
            <option class="badge badge-danger font-12" {!! $record->TouchStatus == 'Could not leave Voicemail' ? 'selected' : '' !!} value="Could not leave Voicemail">Could not leave Voicemail</option>
            <option class="badge badge-danger font-12" {!! $record->TouchStatus == 'Phone not in service' ? 'selected' : '' !!} value="Phone not in service">Phone not in service</option>
            <option class="badge badge-danger font-12" {!! $record->TouchStatus == 'Phone belongs to someone else' ? 'selected' : '' !!} value="Phone belongs to someone else">Phone belongs to someone else</option>
            <option class="badge badge-light font-12" {!! $record->TouchStatus == 'Suppressed' ? 'selected' : '' !!}  value="Suppressed">Suppressed</option>
        </select>

    </td>
    <td class="text-center" id="DS_MKC_ContactID_{!! $record->DS_MKC_ContactID !!}">
        <span class="{!! $class !!}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    </td>

    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! $record->TouchCampaign !!}
    </td>



    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! $record->DS_MKC_ContactID !!}
    </td>

    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! $record->DS_MKC_HouseholdID !!}
    </td>

    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! $record->Extendedname !!}
    </td>

    <td>
        <a href="tel:{!! $record->phone !!}" >{!! $record->phone !!}</a>
    </td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! $record->Email !!}
    </td>

    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! $record->email2 !!}
    </td>

    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! $record->Address !!}
    </td>

    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! $record->City !!}
    </td>

    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! $record->State !!}
    </td>

    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! $record->Zip !!}
    </td>

    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! $record->Company !!}
    </td>

    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! $record->update_date !!}
    </td>

    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! $record->ZSS_Segment !!}
    </td>

    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! isset($record->Last_3Yrs_GiftsAmt) ? number_format($record->Last_3Yrs_GiftsAmt) : 0  !!}
    </td>

    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! isset($record->Life_BHse_GiftsAmt) ? number_format($record->Life_BHse_GiftsAmt) : 0 !!}
    </td>

    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! isset($record->CurrentYr_DonorAmt) ? number_format($record->CurrentYr_DonorAmt) : 0 !!}
    </td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! isset($record->Last_2Yrs_DonorAmt) ? number_format($record->Last_2Yrs_DonorAmt) : 0 !!}
    </td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! isset($record->Life2date_SpendAmt) ? number_format($record->Life2date_SpendAmt) : 0 !!}
    </td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! isset($record->dayssincelastvisit) ? number_format($record->dayssincelastvisit) : 0 !!}
    </td>

    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">
        {!! $record->EmailSegment !!}
    </td>
    <td>
        <input
                type="text"
                class="form-control form-control-sm border-0"
                onkeyup="fillComment($(this),event)"
                data-ds_mkc_contactid="{{ $record->DS_MKC_ContactID }}"
                value="{{ $record->TouchNotes }}"
        />
    </td>
</tr>
