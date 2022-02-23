<?php $notAllowed = ['ROWN','ROWNUMBER']; ?>
<table class="table table-bordered table-hover color-table sr-table">
    <thead>
    <tr>
        @foreach ($records[0] as $cName =>$colArr)
            @if(!in_array($cName,$notAllowed))
                <th>{!! $cName !!}</th>
            @endif
        @endforeach
    </tr>
    </thead>

    <tbody>
    @foreach ($records as $ValArr)
        <tr>
            @foreach ($ValArr as $cName => $value)
                @if(!in_array($cName,$notAllowed))
                    <td>{!! $value !!}</td>
                @endif
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
