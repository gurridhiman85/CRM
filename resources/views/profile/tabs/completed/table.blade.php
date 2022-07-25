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
<table id="basic_table2" class="table table-bordered table-hover color-table lkp-table" data-message="No profile available" width="100%" data-order="[[ 1, &quot;desc&quot; ]]">
    <thead>
        <tr>
            <th class="text-center">Tag</th>
            <th>ID</th>
            <th>Level</th>
            <th>Name</th>
            <th>Description</th>
            <th class="text-center">Last Run</th>
            <th class="text-center">Public</th>
            <th class="text-center">Share</th>
           {{-- <th class="text-center">Records</th>--}}
            {{--<th class="text-center">List <i class="fas fa-file-excel" style = "color: #06b489;" ></i ></th>--}}
            <th class="text-center">Ver</th>
            <th data-order="false" class="text-center pr-2">Report</th>
            {{--<th class="text-center">Run</th>--}}
           {{-- <th class="text-center">Int</th>--}}
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($records) > 0)
            @foreach($records as $record)
                @include('profile.tabs.completed.table-single-row')
            @endforeach
        @endif
    </tbody>
</table>

