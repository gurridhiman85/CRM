<tr>
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
                <td
                    class="{!! $visible_column['Class_Name'] !!}"
                    @if($visible_column['Field_Visibility'] == 1)
                    data-visible="false"
                    @endif>
                    {!!  isset($record[$Field_Name] ) ? $record[$Field_Name]  : '' !!}
                </td>
            @endif
        @endforeach
</tr>
