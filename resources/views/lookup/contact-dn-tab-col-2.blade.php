@if($section == 'contact')
    <?php
    $sets = [];
    $rs = [];
    $rows = '';
    foreach($contact_layout as $k => $contact){
        if($k <=32){
            if(empty($contact['Field_Name'])){
                array_push($rs,$contact['Label']);
                continue;
            }
            if($contact['Column'] == 'C1'){
                $sets[$rs[0]]['P1'][] = [
                    'Label' => $contact['Label'],
                    'Field_Name' => $contact['Field_Name'],
                    'Value' => $records[$contact['Field_Name']]
                ];
            }
            else if($contact['Column'] == 'C3'){
                $sets[$rs[2]]['P1'][] = [
                    'Label' => $contact['Label'],
                    'Field_Name' => $contact['Field_Name'],
                    'Value' => $records[$contact['Field_Name']]
                ];
            }
            else if($contact['Column'] == 'C2'){
                $sets[$rs[1]]['P1'][] = [
                    'Label' => $contact['Label'],
                    'Field_Name' => $contact['Field_Name'],
                    'Value' => $records[$contact['Field_Name']]
                ];
            }
        }else{
            if(empty($contact['Field_Name'])){
                $rows = $contact['Label'];
                continue;
            }
            if($contact['Column'] == 'C1'){
                $sets[$rows]['P1'][] = [
                    'Label' => $contact['Label'],
                    'Field_Name' => $contact['Field_Name'],
                    'Value' => $records[$contact['Field_Name']]
                ];
            }
            else if($contact['Column'] == 'C3'){
                $sets[$rows]['P3'][] = [
                    'Label' => $contact['Label'],
                    'Field_Name' => $contact['Field_Name'],
                    'Value' => $records[$contact['Field_Name']]
                ];
            }
            else if($contact['Column'] == 'C2'){
                $sets[$rows]['P2'][] = [
                    'Label' => $contact['Label'],
                    'Field_Name' => $contact['Field_Name'],
                    'Value' => $records[$contact['Field_Name']]
                ];
            }
        }

    }

    /*echo '<pre>';
    print_r($sets);
    echo '</pre>';
    die;*/
    ?>
    <table>
        <tbody>
        <tr>
            <td width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-indent: 1px;color: #357EC7;font-weight: 500;">Category</td>
            <td width="35" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-indent: 1px;color: #357EC7;font-weight: 500;">
                Field
            </td>
            <td width="75" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-indent: 1px;color: #357EC7;font-weight: 500;">
                Value
            </td>
        </tr>

        @foreach($sets as $set_name => $set)
            @php
                $p1 = isset($set['P1']) ? count($set['P1']) : 0;
                $p2 = isset($set['P2']) ? count($set['P2']) : 0;
                $p3 = isset($set['P3']) ? count($set['P3']) : 0;
                $rowspan = $p1+$p2+$p3;
            @endphp
            @if(isset($set['P1']))
                @foreach($set['P1'] as $i => $p1)
                    <tr>
                        @if($i == 0)
                            <td rowspan="{{ $rowspan }}" width="25" style="text-indent:1px;vertical-align:center;color: #357EC7;font-weight: 500;">{!! $set_name !!}</td>
                        @endif
                        <td width="25" style="text-indent:1px;color: #357EC7;">{{ isset($p1['Label']) ? $p1['Label'] : '' }}</td>
                        <td width="25" style="text-align: left;text-indent:1px;">{{ isset($p1['Value']) ? $p1['Value'] : '' }}</td>
                    </tr>
                @endforeach
            @endif

            @if(isset($set['P2']))
                @foreach($set['P2'] as $i => $p2)
                    <tr>
                        <td width="25" style="text-indent:1px;color: #357EC7;">{{ isset($p2['Label']) ? $p2['Label'] : '' }}</td>
                        <td width="25" style="text-align: left;text-indent:1px;">{{ isset($p2['Value']) ? $p2['Value'] : '' }}</td>
                    </tr>
                @endforeach
            @endif

            @if(isset($set['P3']))
                @foreach($set['P3'] as $i => $p3)
                    <tr>
                        <td width="25" style="text-indent:1px;color: #357EC7;">{{ isset($p3['Label']) ? $p3['Label'] : '' }}</td>
                        <td width="25" style="text-align: left;text-indent:1px;">{{ isset($p3['Value']) ? $p3['Value'] : '' }}</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
        </tbody>
    </table>

@elseif($section == 'summary')
    <style>
        .total-cell{
            background-color:#f4f4f4;
        }
    </style>
    <?php
    $row = count($activity_summary_layout) > 0 ? $activity_summary_layout[count($activity_summary_layout)-1]['Row'] : 'R0';
    $column = count($activity_summary_layout) > 0 ? $activity_summary_layout[count($activity_summary_layout)-1]['Column'] : 'C0';

    $row = (int) str_replace("R","",$row);
    $column = (int) str_replace("C","",$column);


    ?>
    <table id="detailTable" cellspacing="6" style=" margin: 0pt 0pt;">
        <tbody>
        <?php
        $labelEntries = ['R1','C1'];
        ?>

        @for($i =1; $i <= $row; $i++ )
            <?php
            $grayrow = false;
            ?>
            <tr>
                @for($j =1; $j <= $column; $j++ )
                    @php
                        $set = \App\Helpers\Helper::findAS_Set('R'.$i.'C'.$j,$activity_summary_layout);
                    @endphp

                    @if($set === false)
                        <td class="asAllTD"></td>
                    @else
                        @if(in_array('R'.$i, $labelEntries) || in_array('C'.$j, $labelEntries))
                            @if(in_array('R'.$i, $labelEntries) && in_array('C'.$j, $labelEntries))
                                <td style="border: 1px solid #d0d0d0;text-indent: 1px;background-color:#e1eeff; font-weight:500;" width="35">{{ $set['Label'] }}</td>
                            @elseif(!in_array('R'.$i, $labelEntries) && in_array('C'.$j, $labelEntries))
                                @if(strpos($set['Label'],'Total') !== false)
                                    <?php $grayrow = true; ?>
                                    <td style="border: 1px solid #d0d0d0;text-indent: 1px;background-color:#f4f4f4;color:#357EC7; font-weight:500;" width="20">{{ $set['Label'] }}</td>
                                @else
                                    <td style="border: 1px solid #d0d0d0;text-indent: 1px;" width="35">{{ $set['Label'] }}</td>
                                @endif
                            @else
                                <td style="border: 1px solid #d0d0d0;text-indent: 1px;background-color:#e1eeff; font-weight:500;text-align:right;direction: rtl;" width="20">{{ $set['Label'] }}</td>
                            @endif
                        @else
                            @if($grayrow == true)
                            <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;text-align:right;">{{ number_format($record[$set['Field_Name']]) }}</td>
                            @else
                                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;text-align:right;">{{ number_format($record[$set['Field_Name']]) }}</td>
                            @endif

                        @endif
                    @endif

                @endfor
            </tr>
        @endfor
        </tbody>
    </table>


@elseif($section == 'Activity Detail')
    <table>
        <tbody>
        <tr>
            <th width="14" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Date</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: center;text-indent: 7px;">Amount</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Activity Cat 1</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Activity Cat 2</th>
            <th width="35" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Activity</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Class</th>
            <th width="35" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Client Message</th>
        </tr>
        @foreach($records as $record)
            <tr>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['Date'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: right;text-indent: 8px;">{!! $record['Amount'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['Productcat1_Des'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['Productcat2_Des'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['Product'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['Class'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['ClientMessage'] !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>


@elseif($section == 'Phone')
    <table>
        <tbody>
        <tr>
            <th width="14" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">DFL Name</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: center;text-indent: 7px;">Campaign</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Status</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Channel</th>
            <th width="15" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Date</th>
            <th width="35" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Comment</th>
        </tr>
        @foreach($records as $record)
            <tr>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['dflname'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: right;text-indent: 8px;">{!! $record['TouchCampaign'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['TouchStatus'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['TouchChannel'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['TouchDate'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['TouchNotes'] !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
