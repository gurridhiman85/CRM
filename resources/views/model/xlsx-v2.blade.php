<?php
$headings = [
    [
        'title' => 'Variable Type',
        'width' => '20',
        'align' => ''
    ],[
        'title' => 'Variable',
        'width' => '20',
        'align' => ''
    ],[
        'title' => 'Value',
        'width' => '20',
        'align' => ''
    ],[
        'title' => 'Universe',
        'width' => '12',
        'align' => 'text-align: right;'
    ],[
        'title' => 'Percent of Universe',
        'width' => '20',
        'align' => 'text-align: right;'
    ],[
        'title' => 'Predicted Response',
        'width' => '20',
        'align' => 'text-align: right;'
    ]
];
?>
<table>
    <tbody>
    @foreach ($dData as $colArr)
        <tr>
            @foreach ($headings as $key => $head)
                <th width="{{ $head['width'] }}" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;{{ $head['align'] }} text-indent: 1px;">{{ $head['title'] }}</th>
            @endforeach
        </tr>
        @break;
    @endforeach

@foreach ($dData as $key => $ValArr)
    @php
    if(in_array($ValArr['RowVariable'], $overalldistribution)){
        $variable_type = 'Overall Distribution';
    }elseif (in_array($ValArr['RowVariable'], $model_variables)){
        $variable_type = 'Model Variables';
    }else{
        $variable_type = 'Profile Variables';
    }

    $bgColor = '';
    if($ValArr['Value'] == 'Total'){
        $bgColor = 'background-color:#e1eeff;font-weight: 500;';
    }
    @endphp
    <tr>
        <td style="border: 1px solid #d0d0d0;{{ $bgColor }} text-align: left;text-indent: 1px;">{{ $variable_type }}</td>
        <td style="border: 1px solid #d0d0d0;{{ $bgColor }} text-align: left;text-indent: 1px;">{{ str_replace( array('[',']') , ''  , $ValArr['RowVariable'] ) }}</td>
        <td style="border: 1px solid #d0d0d0;{{ $bgColor }} text-align: left;text-indent: 1px;">{!! $ValArr['Value'] !!}</td>
        <td style="border: 1px solid #d0d0d0;{{ $bgColor }} text-align: right;text-indent: 1px;">{{ number_format($ValArr['Universe']) }}</td>
        <td style="border: 1px solid #d0d0d0;{{ $bgColor }} text-align: right;text-indent: 1px;">{{ number_format($ValArr['PercentofUniverse']) }}</td>
        <td style="border: 1px solid #d0d0d0;{{ $bgColor }} text-align: right;text-indent: 1px;">{{ number_format($ValArr['PredictedResponse'], 1) }}%</td>
    </tr>
@endforeach
