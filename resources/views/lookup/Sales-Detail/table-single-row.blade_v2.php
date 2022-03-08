<tr>

    <td>
        <label class="custom-control custom-checkbox m-b-0">
            <input type="checkbox" class="custom-control-input checkbox" onclick="reviewContact($(this),{!! $record->DS_MKC_ContactID !!},'tag');" {!! $record->tag == 1 ? 'checked' : '' !!} value="1">
            <span class="custom-control-label"></span>
        </label>
    </td>
    <td>
        <input type="checkbox" class="js-switch" onchange="singleClick($(this))" name="singlecheckbox" data-color="#b7dee8" data-size="small" data-switchery="true" style="display: none;" value="{!! $record->DS_MKC_ContactID !!}" <?= in_array($record->DS_MKC_ContactID,$contactids) ? 'checked' : ''; ?> {!! in_array($record->DS_MKC_ContactID, $mKeys) ? 'checked' : '' !!}>
    </td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">{!! $record->DS_MKC_ContactID !!}</td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">{!! $record->DS_MKC_HouseholdID !!}</td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">{!! $record->Extendedname !!}</td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">{!! $record->phone !!}</td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">{!! $record->Email !!}</td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">{!! $record->EmailSegment !!}</td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">{!! $record->email2 !!}</td>
    {{--<td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">{!! $record->dqcode_email2 !!}</td>--}}
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">{!! $record->Address !!}</td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">{!! $record->City !!}</td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">{!! $record->State !!}</td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">{!! $record->Zip !!}</td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">{!! $record->Company !!}</td>
    <td class="ajax-Link" data-href="lookup/secondscreen/{!! $record->DS_MKC_ContactID !!}">{!! $record->update_date !!}</td>
</tr>