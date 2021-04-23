@php

    $TouchStatus = '';
    $is_delete = true;
    if($record->TouchDate != null){
        switch ($record->TouchStatus){
            case 'Assigned':
            $TouchStatus = 'Assigned';
            $is_delete = false;
            break;
            case 'Spoke on Phone':
            $TouchStatus = 'Spoke on Phone';
            break;

            case 'User Returned Call':
            $TouchStatus = 'User Returned Call';
            break;

            case 'User Returned Text':
            $TouchStatus = 'User Returned Text';
            break;

            case 'Left Voicemail':
            $TouchStatus = 'Left Voicemail';
            break;

            case 'Could not leave Voicemail':
            $TouchStatus = 'Could not leave Voicemail';
            break;

            case 'Phone not in service':
            $TouchStatus = 'Phone not in service';
            break;

            case 'Phone belongs to someone else':
            $TouchStatus = 'Phone belongs to someone else';
            break;

            case 'Suppressed':
            $TouchStatus = 'Suppressed';
            break;

            default:
            $TouchStatus = $record->TouchStatus;
            break;
        }
    }
@endphp
<tr>
    <td>
        {!! $record->dflname !!}
    </td>

    <td>
        {!! $record->TouchCampaign !!}
    </td>

    <td>
        {!! $TouchStatus !!}
    </td>

    <td>
        {!! $record->TouchChannel !!}
    </td>

    <td>
        {!! $record->TouchDate !!}
    </td>

    <td>
        {!! $record->TouchNotes !!}
    </td>

    <td class="text-center">
        @if($is_delete)
            <a
                href="javascript:void(0);"
                data-href="phone/delete/{!! $record->RowID !!}/{!! $record->DS_MKC_ContactID !!}"
                data-ajax-ds_mkc_contactid = "{!! $record->DS_MKC_ContactID !!}"
                data-confirm="ture"
                data-title="Are you sure you want to delete ?"
                class="ajax-Link"
            >
                <i class="fas fa-trash font-14"></i>
            </a>
        @endif
    </td>
</tr>
