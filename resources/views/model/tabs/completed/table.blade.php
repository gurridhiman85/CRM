<style>


    .space {
        height: 8px;
    }

    .checkbox * {
        box-sizing: border-box;
        position: relative;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .checkbox {
        display: inline-block;
    }

    .checkbox > input {
        display: none;
    }

    .checkbox > label {
        vertical-align: middle;
        font-size: 18px;
        padding-left: 10px;
    }

    .checkbox > [type="checkbox"] + label:before {
        color: #777;
        content: '';
        position: absolute;
        left: 0px;
        display: inline-block;
        min-height: 15px;
        height: 15px;
        width: 15px;
        border: 1px solid #d0d0d0;
        font-size: 15px;
        vertical-align: middle;
        text-align: center;
        transition: all 0.2s ease-in;
        content: '';
        /*top: 4px;*/
    }

    .checkbox.radio-square > [type="checkbox"] + label:before {
        border-radius: 0px;
    }

    .checkbox.radio-rounded > [type="checkbox"] + label:before {
        border-radius: 25%;
    }

    .checkbox.radio-blue > [type="checkbox"] + label:before {
        border: 2px solid #ccc;
    }

    /*.checkbox > [type="checkbox"] + label:hover:before {
        border-color: lightgreen;
    }*/

    .checkbox > [type="checkbox"]:checked + label:before {
        width: 7px;
        height: 7px;
        border-top: transparent;
        border-left: transparent;
        border-color: #e92639;
        border-width: 2px;
        transform: rotate(45deg);
        /*top: 4px;*/
        left: 4px;
        margin-bottom: 16px;
    }



</style>
<table id="basic_table2" class="table table-bordered table-hover color-table lkp-table" style="width: 100%;" data-message="No Model available" data-order="[[ 1, &quot;desc&quot; ],[ 5, &quot;desc&quot; ]]">
    <thead>
    <tr>
        @foreach($fFields as $fField)
                @if($fField['Field_Visibility'] != 0)
                    <th @if($fField['Field_Visibility'] == 2) data-visible="false" @endif class="{{ $fField['Class_Name'] }}">{{ $fField['Field_Display_Name'] }}</th>
                @endif
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($records as $record)
        @include('model.tabs.completed.table-single-row')
    @endforeach
    </tbody>
</table>


