
<tr id="{{ isset($record['DS_MKC_ContactID']) ? 'row_'.$record['DS_MKC_ContactID'] : '' }}">
        @php
                $pkey = array_search('1', array_column($visible_columns, 'Primary_Column'));
                $primary_column = $visible_columns[$pkey]['Field_Name'];
        @endphp
        @foreach($visible_columns as $visible_column)
            @if(in_array($visible_column['Field_Visibility'],[1,2]))
                @php
                    if(strpos($visible_column['Field_Name'],'.') != false){
                        $Field_Name_Split = explode('.',$visible_column['Field_Name']);
                        $Field_Name = $Field_Name_Split[1];
                    }else{
                        $Field_Name = $visible_column['Field_Name'];
                    }
                @endphp
                @if($Field_Name == 'TouchStatus')
                    <td class="text-left">
                        <div class="d-none">{{ $record[$Field_Name] }}</div>
                        <select
                                class='form-control-sm'
                                onchange="changeStatus($(this))"
                                data-ds_mkc_contactid="{!! $record['DS_MKC_ContactID'] !!}"
                                style="border-color: #bfe6f6;"
                        >
                            <option value="">Select</option>
                            <option class="badge badge-info font-12" {!! $record[$Field_Name] == 'Assigned' ? 'selected' : '' !!} value="Assigned">Assigned</option>
                            <option class="badge badge-success font-12" {!! $record[$Field_Name] == 'Spoke on Phone' ? 'selected' : '' !!} value="Spoke on Phone">Spoke on Phone</option>
                            <option class="badge badge-success font-12" {!! $record[$Field_Name] == 'User Returned Call' ? 'selected' : '' !!} value="User Returned Call">User Returned Call</option>
                            <option class="badge badge-success font-12" {!! $record[$Field_Name] == 'User Returned Text' ? 'selected' : '' !!} value="User Returned Text">User Returned Text</option>
                            <option class="badge badge-warning font-12" {!! $record[$Field_Name] == 'Left Voicemail' ? 'selected' : '' !!} value="Left Voicemail">Left Voicemail</option>
                            <option class="badge badge-danger font-12" {!! $record[$Field_Name] == 'Could not leave Voicemail' ? 'selected' : '' !!} value="Could not leave Voicemail">Could not leave Voicemail</option>
                            <option class="badge badge-danger font-12" {!! $record[$Field_Name] == 'Phone not in service' ? 'selected' : '' !!} value="Phone not in service">Phone not in service</option>
                            <option class="badge badge-danger font-12" {!! $record[$Field_Name] == 'Phone belongs to someone else' ? 'selected' : '' !!} value="Phone belongs to someone else">Phone belongs to someone else</option>
                            <option class="badge badge-light font-12" {!! $record[$Field_Name] == 'Suppressed' ? 'selected' : '' !!}  value="Suppressed">Suppressed</option>
                        </select>
                    </td>

                @elseif(strtolower($Field_Name) == 'call')
                    @php
                        $class = 'badge badge-light';
                        if($record['TouchDate'] != null){
                            switch ($record['TouchStatus']){
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
                    <td class="text-center" id="DS_MKC_ContactID_{!! $record['DS_MKC_ContactID'] !!}">
                        <span class="{!! $class !!}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </td>
                @else
                    <td
                            class="{!! $visible_column['Class_Name'] !!} {{ isset($record['DS_MKC_ContactID']) ? 'ajax-Link' : '' }}"
                            @if(isset($record['DS_MKC_ContactID']))
                                data-href="lookup/secondscreen/{!! $record['DS_MKC_ContactID'] !!}"
                            @endif
                            @if($visible_column['Field_Visibility'] == 1)
                            data-visible="false"
                            @endif>
                        {!!  isset($record[$Field_Name] ) ? $record[$Field_Name]  : '' !!}
                    </td>
                @endif

            @endif
        @endforeach
</tr>
