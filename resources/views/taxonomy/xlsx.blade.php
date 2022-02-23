<?php
foreach ($columns as $colIndex => $column){
    if(!in_array($colIndex, $downloadColumnsIndex) ){
        unset($columns[$colIndex]);
        array_values($columns);
    }
}
?>

<table>
    <tbody>
    <tr>
        @foreach($columns as $column)
            <?php
            $key = array_search($column, array_column($visible_columns, 'Field_Name'));
            ?>
            <th width="30"  style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">{{ ucfirst($visible_columns[$key]['Field_Display_Name']) }}</th>
        @endforeach
    </tr>
    @foreach($records as $record)
        <tr>
            @foreach($columns as $column)
                <?php
                $key = array_search($column, array_column($visible_columns, 'Field_Name'));
                ?>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{{ $record[$visible_columns[$key]['Field_Name']] }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
