<style>
    button.ds-c4:hover {
        background-color: #3ea6d0;
        color: #fff;
    }
    button.ds-c4 {
        color: #5f93b2;
        background-color: #bfe6f6;
        border-color: #dae0e5
    }

    .dropdown-toggle::after {
        color: #5f93b2;
    }

    .asFstTd{
        padding: 4px 9px;
        font-size: 13px;
        border: 1px solid #d0d0d0;
        text-indent: 1px;
        height: 21px;
        background-color: #e1eeff;
        font-weight: 500;
        text-align: right;
    }

    .asAllTD{
        padding: 4px 9px;
        text-align: right;
        text-indent: 1px;
        direction: rtl;
        color: #000;
        font-weight: 400;
        font-size: .76563rem;
        border: 1px solid #d0d0d0;
    }

    .asParentTDLabel{
        padding-left:9px;
        color:#357EC7;
        font-weight:500;
        font-size: .76563rem;
        border: 1px solid #d0d0d0;
    }
    .asttRow{
        background-color: #f4f4f4;
    }
    .asChildTDLabel{
        padding-left:18px;
        color:#357EC7;
        font-weight:300;
        font-size: .76563rem;
        border: 1px solid #d0d0d0;
    }

    .row-heading {
        color: #357EC7;
        font-weight: 500;
        padding: 3px 4px !important;
    }

</style>
<?php
function findSet($find,$cColumns){
    try{
        $key = array_search($find, array_column($cColumns, 'Position'));
        return $cColumns[$key];
    }catch (Exception $exception){
        //dd($find.'---'.$exception->getMessage());
    }

}
?>
<form class="ajax-Form" action="lookup/savepagesettings" method="post">
    {!! csrf_field() !!}
    <div class="row">
        <div class="after-filter mt-1"></div>
    </div>
    <div class="row mb-2" style="border-bottom: 1px solid #dee2e6;">
        <div class="col-md-8">
            <ul class="nav nav-tabs customtab2 mt-2 border-bottom-0 font-14" role="tablist">

                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#pscontact" role="tab" aria-selected="true">
                        <span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down">Contact</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#pssummary" role="tab" aria-selected="false">
                        <span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down">Activity Summary</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-md-4">
            <div class="btn-toolbar pull-right mr-2" role="toolbar" aria-label="Toolbar with button groups">
                <div class="all-pagination pt-2 pr-2 sub-pagination"></div>
                <div class="input-group">
                    {{--<button type="button" onclick="properties();" href="javascript:void(0);" title="Properties" class="btn btn-light font-16" style="float: right;box-shadow: none;"><i class="fas fa-cog ds-c"></i></button>--}}
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content p-0" style="padding-left: 12px !important; display: contents !important;">
        <div id="pscontact" class="tab-pane active" style="padding-left: 12px !important;">
            <div class="row">
                <div class="col-md-2 pt-2 border properties d-none">
                    <input type="hidden" id="targetfieldboxclass" value="">
                    <input type="hidden" id="targetfieldboxfield_name" value="">
                    <div class="form-group">
                        <label class="control-label">Field Type</label>
                        <select class="form-control form-control-sm" id="field_type">
                            <option value="">Choose</option>
                            <option value="text">Input</option>
                            <option value="readonly">Readonly</option>
                            <option value="textarea">Textarea</option>
                            <option value="date">Date</option>
                            <option value="select">Select</option>
                        </select>
                    </div>

                    <div class="form-group" style="display: none;" id="select_field_values">
                        <label class="control-label">Add Values</label>
                        <button type="button" id="addvalues" class="btn btn-light font-10" title="Add"><i class="fas fa-plus-circle ds-c font-14"></i></button>
                        <div id="valuesbox"></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Field Name</label>
                        <input type="text" name="field_name" id="field_name" class="form-control form-control-sm">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Class</label>
                        <input type="text" name="classname" id="classname" class="form-control form-control-sm">
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-3">Custom</label>
                        <label class="col-md-9 custom-control custom-checkbox m-b-0">
                            <input type="checkbox" class="custom-control-input checkbox" id="custom_options" name="custom_options" value="1">
                            <span class="custom-control-label"></span>
                        </label>
                    </div>

                    <div class="form-group sql" style="display: none;">
                        <label class="control-label">SQL</label>
                        <div class="input-group">
                            <textarea class="form-control form-control-sm" cols="5" rows="10" id="SQL"></textarea>
                            <div class="input-group-append">
                                <span class="input-group-text" id="execute_sql">
                                    <i class="fas fa-arrow-circle-right font-14 ds-c"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="btn-toolbar mt-2" role="toolbar" aria-label="Toolbar with button groups" style="display: block !important;">
                        <div class="input-group pull-right">
                            <button type="button"
                                    class="btn btn-danger font-12 s-f"
                                    title="Delete Field"
                                    id="delete_field_btn">
                                Delete
                            </button>
                        </div>
                    </div>

                    {{--<div class="btn-toolbar mt-2 mr-2" role="toolbar" aria-label="Toolbar with button groups" style="display: block !important;">
                        <div class="input-group pull-right">
                            <button type="button" class="btn btn-info font-12 s-f" title="Save Properties" id="save_properties">Save</button>
                        </div>
                    </div>--}}
                </div>

                <div class="col-md-12 contentbox">
                    <?php
                    $R1C1Set = findSet('R1C1',$cContactColumns);
                    $R1C2Set = findSet('R1C2',$cContactColumns);
                    $R1C3Set = findSet('R1C3',$cContactColumns);

                    $R2C1Set = findSet('R2C1',$cContactColumns);
                    $R2C2Set = findSet('R2C2',$cContactColumns);
                    $R2C3Set = findSet('R2C3',$cContactColumns);

                    $R3C1Set = findSet('R3C1',$cContactColumns);
                    $R3C2Set = findSet('R3C2',$cContactColumns);
                    $R3C3Set = findSet('R3C3',$cContactColumns);

                    $R4C1Set = findSet('R4C1',$cContactColumns);
                    $R4C2Set = findSet('R4C2',$cContactColumns);
                    $R4C3Set = findSet('R4C3',$cContactColumns);

                    $R5C1Set = findSet('R5C1',$cContactColumns);
                    $R5C2Set = findSet('R5C2',$cContactColumns);
                    $R5C4Set = findSet('R5C3',$cContactColumns);

                    $R5C1Set = findSet('R5C1',$cContactColumns);
                    $R5C2Set = findSet('R5C2',$cContactColumns);
                    $R5C3Set = findSet('R5C3',$cContactColumns);

                    $R6C1Set = findSet('R6C1',$cContactColumns);
                    $R6C2Set = findSet('R6C2',$cContactColumns);
                    $R6C3Set = findSet('R6C3',$cContactColumns);

                    $R7C1Set = findSet('R7C1',$cContactColumns);
                    $R7C2Set = findSet('R7C2',$cContactColumns);
                    $R7C3Set = findSet('R7C3',$cContactColumns);

                    $R8C1Set = findSet('R8C1',$cContactColumns);
                    $R8C2Set = findSet('R8C2',$cContactColumns);
                    $R8C3Set = findSet('R8C3',$cContactColumns);

                    $R9C1Set = findSet('R9C1',$cContactColumns);
                    $R9C2Set = findSet('R9C2',$cContactColumns);
                    $R9C3Set = findSet('R9C3',$cContactColumns);

                    $R10C1Set = findSet('R10C1',$cContactColumns);
                    $R10C2Set = findSet('R10C2',$cContactColumns);
                    $R10C3Set = findSet('R10C3',$cContactColumns);

                    $R11C1Set = findSet('R11C1',$cContactColumns);
                    $R11C2Set = findSet('R11C2',$cContactColumns);
                    $R11C3Set = findSet('R11C3',$cContactColumns);

                    $R13C1Set = findSet('R13C1',$cContactColumns);

                    $R14C1Set = findSet('R14C1',$cContactColumns);
                    $R14C2Set = findSet('R14C2',$cContactColumns);
                    $R14C3Set = findSet('R14C3',$cContactColumns);

                    $R15C1Set = findSet('R15C1',$cContactColumns);
                    $R15C2Set = findSet('R15C2',$cContactColumns);
                    $R15C3Set = findSet('R15C3',$cContactColumns);

                    $R16C1Set = findSet('R16C1',$cContactColumns);
                    $R16C2Set = findSet('R16C2',$cContactColumns);
                    $R16C3Set = findSet('R16C3',$cContactColumns);

                    $R17C1Set = findSet('R17C1',$cContactColumns);
                    $R17C2Set = findSet('R17C2',$cContactColumns);
                    $R17C3Set = findSet('R17C3',$cContactColumns);

                    $R18C1Set = findSet('R18C1',$cContactColumns);
                    $R18C2Set = findSet('R18C2',$cContactColumns);
                    $R18C3Set = findSet('R18C3',$cContactColumns);

                    $R19C1Set = findSet('R19C1',$cContactColumns);
                    $R20C1Set = findSet('R20C1',$cContactColumns);

                    $R21C1Set = findSet('R21C1',$cContactColumns);
                    $R21C2Set = findSet('R21C2',$cContactColumns);
                    $R21C3Set = findSet('R21C3',$cContactColumns);

                    $R22C1Set = findSet('R22C1',$cContactColumns);
                    $R22C2Set = findSet('R22C2',$cContactColumns);
                    $R22C3Set = findSet('R22C3',$cContactColumns);

                    $R23C1Set = findSet('R23C1',$cContactColumns);
                    $R23C2Set = findSet('R23C2',$cContactColumns);
                    $R23C3Set = findSet('R23C3',$cContactColumns);

                    $R25C1Set = findSet('R25C1',$cContactColumns);

                    $R26C1Set = findSet('R26C1',$cContactColumns);

                    ?>

                    <div class="divTable">
                        <div class="divTableBody">

                            <!-------------------- Name and Address  - Start -------------->

                            <div class="divTableRow">

                                <div class="divTableCell" style="width: 14.6%;color: #357EC7;
font-weight: 500;padding: 3px 4px !important;">
                                    <input type="text" name="R[1][C1]" class="form-control form-control-sm font-14 border-0 pl-0 pr-0" data-class_name="row-heading" style="color: #357EC7;
font-weight: 500;" placeholder="R1:C1" value="{{ $R1C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell" style="width: 18.6%;text-align: center;color: #ffffff;"><b></b></div>
                                <div class="divTableCell" style="width: 14.6%;text-align: center;">
                                    <input type="text" name="R[1][C2]" class="form-control form-control-sm font-14 border-0 pl-0 pr-0" data-class_name="row-heading" style="color: #357EC7;
font-weight: 500;" placeholder="R1:C2" value="{{ $R1C2Set['Label'] }}">
                                </div>
                                <div class="divTableCell" style="width: 18.6%;text-align: center;color: #ffffff;"><b></b></div>
                                <div class="divTableCell" style="width: 14.6%;"><input type="text" name="R[1][C3]" class="form-control form-control-sm font-14 border-0 pl-0 pr-0" data-class_name="row-heading" style="color: #357EC7;
font-weight: 500;" placeholder="R1:C3" value="{{ $R1C3Set['Label'] }}"></div>
                                <div class="divTableCell" style="width: 18.6%;text-align: right;"><!--<b>Household</b>-->


                                </div>

                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[2][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R2:C1" value="{{ $R2C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R2_C1_fieldbox">
                                        @if(!empty($R2C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                                'set' => $R2C1Set,
                                                'targetClass'=> 'R2_C1',
                                                'targetFieldName'=> 'R[2][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R2_C1_btn {{ !empty($R2C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R2_C1','R[2][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                    <!--<input type="text" class="t8 dis txtDS_MKC_ContactID" name="txtDS_MKC_ContactID">-->
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[2][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R2:C2" value="{{ $R2C2Set['Label'] }}">

                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R2_C2_fieldbox">
                                        @if(!empty($R2C2Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                                'set' => $R2C2Set,
                                                'targetClass'=> 'R2_C2',
                                                'targetFieldName'=> 'R[2][V2]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R2_C2_btn {{ !empty($R2C2Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R2_C2','R[2][V2]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                                <div class="divTableCell">
                                   <input type="text" name="R[2][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R2:C3" value="{{ $R2C3Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t6 p1 R2_C3_fieldbox">
                                        @if(!empty($R2C3Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                                'set' => $R2C3Set,
                                                'targetClass'=> 'R2_C3',
                                                'targetFieldName'=> 'R[2][V3]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R2_C3_btn {{ !empty($R2C3Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R2_C3','R[2][V3]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[3][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R3:C1" value="{{ $R3C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R3_C1_fieldbox">
                                        @if(!empty($R3C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                                'set' => $R3C1Set,
                                                'targetClass'=> 'R3_C1',
                                                'targetFieldName'=> 'R[3][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R3_C1_btn {{ !empty($R3C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R3_C1','R[3][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[3][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R3:C2" value="{{ $R3C2Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R3_C2_fieldbox">
                                        @if(!empty($R3C2Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                                'set' => $R3C2Set,
                                                'targetClass'=> 'R3_C2',
                                                'targetFieldName'=> 'R[3][V2]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R3_C2_btn {{ !empty($R3C2Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R3_C2','R[3][V2]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[3][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R3:C3" value="{{ $R3C3Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t6 p1 R3_C3_fieldbox">
                                        @if(!empty($R3C3Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                                'set' => $R3C3Set,
                                                'targetClass'=> 'R3_C3',
                                                'targetFieldName'=> 'R[3][V3]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R3_C3_btn {{ !empty($R3C3Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R3_C3','R[3][V3]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[4][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R4:C1" value="{{ $R4C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R4_C1_fieldbox">
                                        @if(!empty($R4C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                                'set' => $R4C1Set,
                                                'targetClass'=> 'R4_C1',
                                                'targetFieldName'=> 'R[4][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R4_C1_btn {{ !empty($R4C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R4_C1','R[4][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[4][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R4:C2" value="{{ $R4C2Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R4_C2_fieldbox">
                                        @if(!empty($R4C2Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                                'set' => $R4C2Set,
                                                'targetClass'=> 'R4_C2',
                                                'targetFieldName'=> 'R[4][V2]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R4_C2_btn {{ !empty($R4C2Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R4_C2','R[4][V2]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[4][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R4:C3" value="{{ $R4C3Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t6 p1 R4_C3_fieldbox">
                                        @if(!empty($R4C3Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                                'set' => $R4C3Set,
                                                'targetClass'=> 'R4_C3',
                                                'targetFieldName'=> 'R[4][V3]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R4_C3_btn {{ !empty($R4C3Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R4_C3','R[4][V3]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>

                            <div class="divTableRow">

                                <div class="divTableCell">
                                    <input type="text" name="R[5][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R5:C1" value="{{ $R5C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R5_C1_fieldbox">
                                        @if(!empty($R5C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                                'set' => $R5C1Set,
                                                'targetClass'=> 'R5_C1',
                                                'targetFieldName'=> 'R[5][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R5_C1_btn {{ !empty($R5C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R5_C1','R[5][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[5][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R5:C2" value="{{ $R5C2Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R5_C2_fieldbox">
                                        @if(!empty($R5C2Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                                'set' => $R5C2Set,
                                                'targetClass'=> 'R5_C2',
                                                'targetFieldName'=> 'R[5][V2]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R5_C2_btn {{ !empty($R5C2Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R5_C2','R[5][V2]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[5][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R5:C3" value="{{ $R5C3Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t6 p1 R5_C3_fieldbox">
                                        @if(!empty($R5C3Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R5C3Set,
                                                'targetClass'=> 'R5_C3',
                                                'targetFieldName'=> 'R[5][V3]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R5_C3_btn {{ !empty($R5C3Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R5_C3','R[5][V3]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[6][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R6:C1" value="{{ $R6C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R6_C1_fieldbox">
                                        @if(!empty($R6C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R6C1Set,
                                                'targetClass'=> 'R6_C1',
                                                'targetFieldName'=> 'R[6][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R6_C1_btn {{ !empty($R6C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R6_C1','R[6][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[6][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R6:C2" value="{{ $R6C2Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R6_C2_fieldbox">
                                        @if(!empty($R6C2Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R6C2Set,
                                                'targetClass'=> 'R6_C2',
                                                'targetFieldName'=> 'R[6][V2]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R6_C2_btn {{ !empty($R6C2Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R6_C2','R[6][V2]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[6][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R6:C3" value="{{ $R6C3Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t6 p1 R6_C3_fieldbox">
                                        @if(!empty($R6C3Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R6C3Set,
                                                'targetClass'=> 'R6_C3',
                                                'targetFieldName'=> 'R[6][V3]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R6_C3_btn {{ !empty($R6C3Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R6_C3','R[6][V3]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[7][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R7:C1" value="{{ $R7C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R7_C1_fieldbox">
                                        @if(!empty($R7C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R7C1Set,
                                                'targetClass'=> 'R7_C1',
                                                'targetFieldName'=> 'R[7][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R7_C1_btn {{ !empty($R7C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R7_C1','R[7][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[7][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R7:C2" value="{{ $R7C2Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R7_C2_fieldbox">
                                        @if(!empty($R7C2Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R7C2Set,
                                                'targetClass'=> 'R7_C2',
                                                'targetFieldName'=> 'R[7][V2]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R7_C2_btn {{ !empty($R7C2Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R7_C2','R[7][V2]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[7][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R7:C3" value="{{ $R7C3Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t6 p1 R7_C3_fieldbox">
                                        @if(!empty($R7C3Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R7C3Set,
                                                'targetClass'=> 'R7_C3',
                                                'targetFieldName'=> 'R[7][V3]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R7_C3_btn {{ !empty($R7C3Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R7_C3','R[7][V3]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[8][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R8:C1" value="{{ $R8C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R8_C1_fieldbox">
                                        @if(!empty($R8C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R8C1Set,
                                                'targetClass'=> 'R8_C1',
                                                'targetFieldName'=> 'R[8][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R8_C1_btn {{ !empty($R8C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R8_C1','R[8][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[8][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R8:C2" value="{{ $R8C2Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R8_C2_fieldbox">
                                        @if(!empty($R8C2Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R8C2Set,
                                                'targetClass'=> 'R8_C2',
                                                'targetFieldName'=> 'R[8][V2]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R8_C2_btn {{ !empty($R8C2Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R8_C2','R[8][V2]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[8][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R8:C3" value="{{ $R8C3Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t6 p1 R8_C3_fieldbox">
                                        @if(!empty($R8C3Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R8C3Set,
                                                'targetClass'=> 'R8_C3',
                                                'targetFieldName'=> 'R[8][V3]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R8_C3_btn {{ !empty($R8C3Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R8_C3','R[8][V3]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[9][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R9:C1" value="{{ $R9C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R9_C1_fieldbox">
                                        @if(!empty($R9C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R9C1Set,
                                                'targetClass'=> 'R9_C1',
                                                'targetFieldName'=> 'R[9][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R9_C1_btn {{ !empty($R9C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R9_C1','R[9][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[9][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R9:C2" value="{{ $R9C2Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R9_C2_fieldbox">
                                        @if(!empty($R9C2Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R9C2Set,
                                                'targetClass'=> 'R9_C2',
                                                'targetFieldName'=> 'R[9][V2]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R9_C2_btn {{ !empty($R9C2Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R9_C2','R[9][V2]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[9][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R9:C3" value="{{ $R9C3Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t6 p1 R9_C3_fieldbox">
                                        @if(!empty($R9C3Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R9C3Set,
                                                'targetClass'=> 'R9_C3',
                                                'targetFieldName'=> 'R[9][V3]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R9_C3_btn {{ !empty($R9C3Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R9_C3','R[9][V3]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[10][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R10:C1" value="{{ $R10C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R10_C1_fieldbox">
                                        @if(!empty($R10C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R10C1Set,
                                                'targetClass'=> 'R10_C1',
                                                'targetFieldName'=> 'R[10][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R10_C1_btn {{ !empty($R10C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R10_C1','R[10][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[10][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R10:C2" value="{{ $R10C2Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R10_C2_fieldbox">
                                        @if(!empty($R10C2Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R10C2Set,
                                                'targetClass'=> 'R10_C2',
                                                'targetFieldName'=> 'R[10][V2]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R10_C2_btn {{ !empty($R10C2Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R10_C2','R[10][V2]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[10][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R10:C3" value="{{ $R10C3Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t6 p1 R10_C3_fieldbox">
                                        @if(!empty($R10C3Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R10C3Set,
                                                'targetClass'=> 'R10_C3',
                                                'targetFieldName'=> 'R[10][V3]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R10_C3_btn {{ !empty($R10C3Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R10_C3','R[10][V3]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[11][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R11:C1" value="{{ $R11C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R11_C1_fieldbox">
                                        @if(!empty($R11C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R11C1Set,
                                                'targetClass'=> 'R11_C1',
                                                'targetFieldName'=> 'R[11][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R11_C1_btn {{ !empty($R11C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R11_C1','R[11][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[11][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R11:C2" value="{{ $R11C2Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R11_C2_fieldbox">
                                        @if(!empty($R11C2Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R11C2Set,
                                                'targetClass'=> 'R11_C2',
                                                'targetFieldName'=> 'R[11][V2]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R11_C2_btn {{ !empty($R11C2Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R11_C2','R[11][V2]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>


                                <div class="divTableCell">
                                    <input type="text" name="R[11][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R11:C3" value="{{ $R11C3Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t6 p1 R11_C3_fieldbox">
                                        @if(!empty($R11C3Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R11C3Set,
                                                'targetClass'=> 'R11_C3',
                                                'targetFieldName'=> 'R[11][V3]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R11_C3_btn {{ !empty($R11C3Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R11_C3','R[11][V3]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>

                            <!-------------------- Name and Address  - End -------------->

                            <div class="divTableRow">
                                <div class="divTableCell" style="width: 14%;color: #ffffff;
font-weight: bold;padding: 3px 4px !important;">
                                </div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                            </div>

                            <!-------------------- Contactability Start -------------->
                            <div class="divTableRow">
                                <div class="divTableCell" style="width: 14%;color: #357EC7;
font-weight: 500;padding: 3px 4px !important;"><input type="text" name="R[13][C1]" class="form-control form-control-sm font-14 border-0 pl-0 pr-0"  data-class_name="row-heading" placeholder="R13:C1" value="{{ $R13C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[14][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R14:C1" value="{{ $R14C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R14_C1_fieldbox">
                                        @if(!empty($R14C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R14C1Set,
                                                'targetClass'=> 'R14_C1',
                                                'targetFieldName'=> 'R[14][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R14_C1_btn {{ !empty($R14C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R14_C1','R[14][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>


                                <div class="divTableCell">
                                    <input type="text" name="R[14][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R14:C2" value="{{ $R14C2Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R14_C2_fieldbox">
                                        @if(!empty($R14C2Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R14C2Set,
                                                'targetClass'=> 'R14_C2',
                                                'targetFieldName'=> 'R[14][V2]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R14_C2_btn {{ !empty($R14C2Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R14_C2','R[14][V2]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>


                                <div class="divTableCell">
                                    <input type="text" name="R[14][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R14:C3" value="{{ $R14C3Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t6 p1 R14_C3_fieldbox">
                                        @if(!empty($R14C3Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R14C3Set,
                                                'targetClass'=> 'R14_C3',
                                                'targetFieldName'=> 'R[14][V3]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R14_C3_btn {{ !empty($R14C3Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R14_C3','R[14][V3]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[15][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R15:C1" value="{{ $R15C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R15_C1_fieldbox">
                                        @if(!empty($R15C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R15C1Set,
                                                'targetClass'=> 'R15_C1',
                                                'targetFieldName'=> 'R[15][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R15_C1_btn {{ !empty($R15C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R15_C1','R[15][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>


                                <div class="divTableCell">
                                    <input type="text" name="R[15][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R15:C2" value="{{ $R15C2Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R15_C2_fieldbox">
                                        @if(!empty($R15C2Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R15C2Set,
                                                'targetClass'=> 'R15_C2',
                                                'targetFieldName'=> 'R[15][V2]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R15_C2_btn {{ !empty($R15C2Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R15_C2','R[15][V2]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[15][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R15:C3" value="{{ $R15C3Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t6 p1 R15_C3_fieldbox">
                                        @if(!empty($R15C3Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R15C3Set,
                                                'targetClass'=> 'R15_C3',
                                                'targetFieldName'=> 'R[15][V3]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R15_C3_btn {{ !empty($R15C3Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R15_C3','R[15][V3]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[16][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R16:C1" value="{{ $R16C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R16_C1_fieldbox">
                                        @if(!empty($R16C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R16C1Set,
                                                'targetClass'=> 'R16_C1',
                                                'targetFieldName'=> 'R[16][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R16_C1_btn {{ !empty($R16C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R16_C1','R[16][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>


                                <div class="divTableCell">
                                    <input type="text" name="R[16][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R16:C2" value="{{ $R16C2Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R16_C2_fieldbox">
                                        @if(!empty($R16C2Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R16C2Set,
                                                'targetClass'=> 'R16_C2',
                                                'targetFieldName'=> 'R[16][V2]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R16_C2_btn {{ !empty($R16C2Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R16_C2','R[16][V2]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[16][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R16:C3" value="{{ $R16C3Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t6 p1 R16_C3_fieldbox">
                                        @if(!empty($R16C3Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R16C3Set,
                                                'targetClass'=> 'R16_C3',
                                                'targetFieldName'=> 'R[16][V3]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R16_C3_btn {{ !empty($R16C3Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R16_C3','R[16][V3]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>


                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[17][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R17:C1" value="{{ $R17C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R17_C1_fieldbox">
                                        @if(!empty($R17C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R17C1Set,
                                                'targetClass'=> 'R17_C1',
                                                'targetFieldName'=> 'R[17][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R17_C1_btn {{ !empty($R17C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R17_C1','R[17][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>


                                <div class="divTableCell">
                                    <input type="text" name="R[17][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R17:C2" value="{{ $R17C2Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 R17_C2_fieldbox">
                                        @if(!empty($R17C2Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R17C2Set,
                                                'targetClass'=> 'R17_C2',
                                                'targetFieldName'=> 'R[17][V2]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R17_C2_btn {{ !empty($R17C2Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R17_C2','R[17][V2]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[17][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R17:C3" value="{{ $R17C3Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t6 p1 R17_C3_fieldbox">
                                        @if(!empty($R17C3Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R17C3Set,
                                                'targetClass'=> 'R17_C3',
                                                'targetFieldName'=> 'R[17][V3]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R17_C3_btn {{ !empty($R17C3Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R17_C3','R[17][V3]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>


                        <!-------------------- Contactability End -------------->

                            <div class="divTableRow">
                                <div class="divTableCell" style="width: 14%;color: #ffffff;
font-weight: bold;padding: 3px 4px !important;">
                                </div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                            </div>

                            <!-------------------- Notes - Start -------------->

                            <div class="divTableRow" style="height: 14px !important;">
                                <div class="divTableCell" style="width: 14%;color: #357EC7;
font-weight: 500;padding: 3px 4px !important;">
                                    <input type="text" name="R[19][C1]" class="form-control form-control-sm font-14 border-0 pl-0 pr-0"  data-class_name="row-heading" placeholder="R19:C1" value="{{ $R19C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                            </div>

                        </div>
                    </div>
                    <div class="divTable">
                        <div class="divTableBody">
                            <div class="divTableRow">
                                <div class="divTableCell" style="width: 14.6% !important;padding: 3px 0px !important;">
                                    <input type="text" name="R[20][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R20:C1" value="{{ $R20C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input R20_C1_fieldbox" style=" font-size:10px; height:50px;width: 100%;">
                                        @if(!empty($R20C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R20C1Set,
                                                'targetClass'=> 'R20_C1',
                                                'targetFieldName'=> 'R[20][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R20_C1_btn {{ !empty($R20C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R20_C1','R[20][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="divTable">
                        <div class="divTableBody">
                            <div class="divTableRow">
                                <div class="divTableCell" style="width: 14.6% !important;padding: 3px 0px !important;">
                                    <input type="text" name="R[21][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R21:C1" value="{{ $R21C1Set['Label'] }}">
                                </div>
                                <div class="divTableCell">
                                    <div class="input R21_C1_fieldbox" style=" font-size:10px; height:50px;width: 100%;">
                                        @if(!empty($R21C1Set['Field_Type']))
                                            @include('lookup.fields.field-type',[
                                               'set' => $R21C1Set,
                                                'targetClass'=> 'R21_C1',
                                                'targetFieldName'=> 'R[21][V1]'
                                            ])
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-light font-10 R21_C1_btn {{ !empty($R21C1Set['Field_Type']) ? 'd-none' : '' }}" title="Add" onclick="addField('R21_C1','R[21][V1]')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!-------------------- Notes - End -------------->

            <div class="btn-toolbar mt-2 mr-2" role="toolbar" aria-label="Toolbar with button groups" style="display: block !important;">
                <div class="input-group pull-right">
                    <button type="button" class="btn btn-info font-12 s-f" title="Save Contact" id="updateContactLayoutBtn">Save</button>
                </div>
            </div>
        </div>
        <div id="pssummary" class="tab-pane" style="padding-left: 12px !important;">
            <?php
            $R1C1ASSet = findSet('R1C1',$sSummarycolumns);
            $R1C2ASSet = findSet('R1C2',$sSummarycolumns);
            $R1C3ASSet = findSet('R1C3',$sSummarycolumns);
            $R1C4ASSet = findSet('R1C4',$sSummarycolumns);
            $R1C5ASSet = findSet('R1C5',$sSummarycolumns);
            $R1C6ASSet = findSet('R1C6',$sSummarycolumns);
            $R1C7ASSet = findSet('R1C7',$sSummarycolumns);
            $R1C8ASSet = findSet('R1C8',$sSummarycolumns);
            $R1C9ASSet = findSet('R1C9',$sSummarycolumns);

            $R2C1ASSet = findSet('R2C1',$sSummarycolumns);
            $R2C2ASSet = findSet('R2C2',$sSummarycolumns);
            $R2C3ASSet = findSet('R2C3',$sSummarycolumns);
            $R2C4ASSet = findSet('R2C4',$sSummarycolumns);
            $R2C5ASSet = findSet('R2C5',$sSummarycolumns);
            $R2C6ASSet = findSet('R2C6',$sSummarycolumns);
            $R2C7ASSet = findSet('R2C7',$sSummarycolumns);
            $R2C8ASSet = findSet('R2C8',$sSummarycolumns);
            $R2C9ASSet = findSet('R2C9',$sSummarycolumns);

            $R3C1ASSet = findSet('R3C1',$sSummarycolumns);
            $R3C2ASSet = findSet('R3C2',$sSummarycolumns);
            $R3C3ASSet = findSet('R3C3',$sSummarycolumns);
            $R3C4ASSet = findSet('R3C4',$sSummarycolumns);
            $R3C5ASSet = findSet('R3C5',$sSummarycolumns);
            $R3C6ASSet = findSet('R3C6',$sSummarycolumns);
            $R3C7ASSet = findSet('R3C7',$sSummarycolumns);
            $R3C8ASSet = findSet('R3C8',$sSummarycolumns);
            $R3C9ASSet = findSet('R3C9',$sSummarycolumns);

            $R4C1ASSet = findSet('R4C1',$sSummarycolumns);
            $R4C2ASSet = findSet('R4C2',$sSummarycolumns);
            $R4C3ASSet = findSet('R4C3',$sSummarycolumns);
            $R4C4ASSet = findSet('R4C4',$sSummarycolumns);
            $R4C5ASSet = findSet('R4C5',$sSummarycolumns);
            $R4C6ASSet = findSet('R4C6',$sSummarycolumns);
            $R4C7ASSet = findSet('R4C7',$sSummarycolumns);
            $R4C8ASSet = findSet('R4C8',$sSummarycolumns);
            $R4C9ASSet = findSet('R4C9',$sSummarycolumns);

            $R5C1ASSet = findSet('R5C1',$sSummarycolumns);
            $R5C2ASSet = findSet('R5C2',$sSummarycolumns);
            $R5C3ASSet = findSet('R5C3',$sSummarycolumns);
            $R5C4ASSet = findSet('R5C4',$sSummarycolumns);
            $R5C5ASSet = findSet('R5C5',$sSummarycolumns);
            $R5C6ASSet = findSet('R5C6',$sSummarycolumns);
            $R5C7ASSet = findSet('R5C7',$sSummarycolumns);
            $R5C8ASSet = findSet('R5C8',$sSummarycolumns);
            $R5C9ASSet = findSet('R5C9',$sSummarycolumns);

            $R6C1ASSet = findSet('R6C1',$sSummarycolumns);
            $R6C2ASSet = findSet('R6C2',$sSummarycolumns);
            $R6C3ASSet = findSet('R6C3',$sSummarycolumns);
            $R6C4ASSet = findSet('R6C4',$sSummarycolumns);
            $R6C5ASSet = findSet('R6C5',$sSummarycolumns);
            $R6C6ASSet = findSet('R6C6',$sSummarycolumns);
            $R6C7ASSet = findSet('R6C7',$sSummarycolumns);
            $R6C8ASSet = findSet('R6C8',$sSummarycolumns);
            $R6C9ASSet = findSet('R6C9',$sSummarycolumns);

            $R7C1ASSet = findSet('R7C1',$sSummarycolumns);
            $R7C2ASSet = findSet('R7C2',$sSummarycolumns);
            $R7C3ASSet = findSet('R7C3',$sSummarycolumns);
            $R7C4ASSet = findSet('R7C4',$sSummarycolumns);
            $R7C5ASSet = findSet('R7C5',$sSummarycolumns);
            $R7C6ASSet = findSet('R7C6',$sSummarycolumns);
            $R7C7ASSet = findSet('R7C7',$sSummarycolumns);
            $R7C8ASSet = findSet('R7C8',$sSummarycolumns);
            $R7C9ASSet = findSet('R7C9',$sSummarycolumns);

            $R8C1ASSet = findSet('R8C1',$sSummarycolumns);
            $R8C2ASSet = findSet('R8C2',$sSummarycolumns);
            $R8C3ASSet = findSet('R8C3',$sSummarycolumns);
            $R8C4ASSet = findSet('R8C4',$sSummarycolumns);
            $R8C5ASSet = findSet('R8C5',$sSummarycolumns);
            $R8C6ASSet = findSet('R8C6',$sSummarycolumns);
            $R8C7ASSet = findSet('R8C7',$sSummarycolumns);
            $R8C8ASSet = findSet('R8C8',$sSummarycolumns);
            $R8C9ASSet = findSet('R8C9',$sSummarycolumns);

            $R10C1ASSet = findSet('R10C1',$sSummarycolumns);
            $R10C2ASSet = findSet('R10C2',$sSummarycolumns);
            $R10C3ASSet = findSet('R10C3',$sSummarycolumns);
            $R10C4ASSet = findSet('R10C4',$sSummarycolumns);
            $R10C5ASSet = findSet('R10C5',$sSummarycolumns);
            $R10C6ASSet = findSet('R10C6',$sSummarycolumns);
            $R10C7ASSet = findSet('R10C7',$sSummarycolumns);
            $R10C8ASSet = findSet('R10C8',$sSummarycolumns);
            $R10C9ASSet = findSet('R10C9',$sSummarycolumns);

            $R12C1ASSet = findSet('R12C1',$sSummarycolumns);
            $R12C2ASSet = findSet('R12C2',$sSummarycolumns);
            $R12C3ASSet = findSet('R12C3',$sSummarycolumns);
            $R12C4ASSet = findSet('R12C4',$sSummarycolumns);
            $R12C5ASSet = findSet('R12C5',$sSummarycolumns);
            $R12C6ASSet = findSet('R12C6',$sSummarycolumns);
            $R12C7ASSet = findSet('R12C7',$sSummarycolumns);
            $R12C8ASSet = findSet('R12C8',$sSummarycolumns);
            $R12C9ASSet = findSet('R12C9',$sSummarycolumns);

            $R13C1ASSet = findSet('R13C1',$sSummarycolumns);
            $R13C2ASSet = findSet('R13C2',$sSummarycolumns);
            $R13C3ASSet = findSet('R13C3',$sSummarycolumns);
            $R13C4ASSet = findSet('R13C4',$sSummarycolumns);
            $R13C5ASSet = findSet('R13C5',$sSummarycolumns);
            $R13C6ASSet = findSet('R13C6',$sSummarycolumns);
            $R13C7ASSet = findSet('R13C7',$sSummarycolumns);
            $R13C8ASSet = findSet('R13C8',$sSummarycolumns);
            $R13C9ASSet = findSet('R13C9',$sSummarycolumns);

            $R14C1ASSet = findSet('R14C1',$sSummarycolumns);
            $R14C2ASSet = findSet('R14C2',$sSummarycolumns);
            $R14C3ASSet = findSet('R14C3',$sSummarycolumns);
            $R14C4ASSet = findSet('R14C4',$sSummarycolumns);
            $R14C5ASSet = findSet('R14C5',$sSummarycolumns);
            $R14C6ASSet = findSet('R14C6',$sSummarycolumns);
            $R14C7ASSet = findSet('R14C7',$sSummarycolumns);
            $R14C8ASSet = findSet('R14C8',$sSummarycolumns);
            $R14C9ASSet = findSet('R14C9',$sSummarycolumns);

            $R15C1ASSet = findSet('R15C1',$sSummarycolumns);
            $R15C2ASSet = findSet('R15C2',$sSummarycolumns);
            $R15C3ASSet = findSet('R15C3',$sSummarycolumns);
            $R15C4ASSet = findSet('R15C4',$sSummarycolumns);
            $R15C5ASSet = findSet('R15C5',$sSummarycolumns);
            $R15C6ASSet = findSet('R15C6',$sSummarycolumns);
            $R15C7ASSet = findSet('R15C7',$sSummarycolumns);
            $R15C8ASSet = findSet('R15C8',$sSummarycolumns);
            $R15C9ASSet = findSet('R15C9',$sSummarycolumns);

            $R16C1ASSet = findSet('R16C1',$sSummarycolumns);
            $R16C2ASSet = findSet('R16C2',$sSummarycolumns);
            $R16C3ASSet = findSet('R16C3',$sSummarycolumns);
            $R16C4ASSet = findSet('R16C4',$sSummarycolumns);
            $R16C5ASSet = findSet('R16C5',$sSummarycolumns);
            $R16C6ASSet = findSet('R16C6',$sSummarycolumns);
            $R16C7ASSet = findSet('R16C7',$sSummarycolumns);
            $R16C8ASSet = findSet('R16C8',$sSummarycolumns);
            $R16C9ASSet = findSet('R16C9',$sSummarycolumns);

            $R17C1ASSet = findSet('R17C1',$sSummarycolumns);
            $R17C2ASSet = findSet('R17C2',$sSummarycolumns);
            $R17C3ASSet = findSet('R17C3',$sSummarycolumns);
            $R17C4ASSet = findSet('R17C4',$sSummarycolumns);
            $R17C5ASSet = findSet('R17C5',$sSummarycolumns);
            $R17C6ASSet = findSet('R17C6',$sSummarycolumns);
            $R17C7ASSet = findSet('R17C7',$sSummarycolumns);
            $R17C8ASSet = findSet('R17C8',$sSummarycolumns);
            $R17C9ASSet = findSet('R17C9',$sSummarycolumns);

            $R18C1ASSet = findSet('R18C1',$sSummarycolumns);
            $R18C2ASSet = findSet('R18C2',$sSummarycolumns);
            $R18C3ASSet = findSet('R18C3',$sSummarycolumns);
            $R18C4ASSet = findSet('R18C4',$sSummarycolumns);
            $R18C5ASSet = findSet('R18C5',$sSummarycolumns);
            $R18C6ASSet = findSet('R18C6',$sSummarycolumns);
            $R18C7ASSet = findSet('R18C7',$sSummarycolumns);
            $R18C8ASSet = findSet('R18C8',$sSummarycolumns);
            $R18C9ASSet = findSet('R18C9',$sSummarycolumns);

            $R19C1ASSet = findSet('R19C1',$sSummarycolumns);
            $R19C2ASSet = findSet('R19C2',$sSummarycolumns);
            $R19C3ASSet = findSet('R19C3',$sSummarycolumns);
            $R19C4ASSet = findSet('R19C4',$sSummarycolumns);
            $R19C5ASSet = findSet('R19C5',$sSummarycolumns);
            $R19C6ASSet = findSet('R19C6',$sSummarycolumns);
            $R19C7ASSet = findSet('R19C7',$sSummarycolumns);
            $R19C8ASSet = findSet('R19C8',$sSummarycolumns);
            $R19C9ASSet = findSet('R19C9',$sSummarycolumns);

            $R20C1ASSet = findSet('R20C1',$sSummarycolumns);
            $R20C2ASSet = findSet('R20C2',$sSummarycolumns);
            $R20C3ASSet = findSet('R20C3',$sSummarycolumns);
            $R20C4ASSet = findSet('R20C4',$sSummarycolumns);
            $R20C5ASSet = findSet('R20C5',$sSummarycolumns);
            $R20C6ASSet = findSet('R20C6',$sSummarycolumns);
            $R20C7ASSet = findSet('R20C7',$sSummarycolumns);
            $R20C8ASSet = findSet('R20C8',$sSummarycolumns);
            $R20C9ASSet = findSet('R20C9',$sSummarycolumns);

            $R22C1ASSet = findSet('R22C1',$sSummarycolumns);
            $R22C2ASSet = findSet('R22C2',$sSummarycolumns);
            $R22C3ASSet = findSet('R22C3',$sSummarycolumns);
            $R22C4ASSet = findSet('R22C4',$sSummarycolumns);
            $R22C5ASSet = findSet('R22C5',$sSummarycolumns);
            $R22C6ASSet = findSet('R22C6',$sSummarycolumns);
            $R22C7ASSet = findSet('R22C7',$sSummarycolumns);
            $R22C8ASSet = findSet('R22C8',$sSummarycolumns);
            $R22C9ASSet = findSet('R22C9',$sSummarycolumns);

            $R23C1ASSet = findSet('R23C1',$sSummarycolumns);
            $R23C2ASSet = findSet('R23C2',$sSummarycolumns);
            $R23C3ASSet = findSet('R23C3',$sSummarycolumns);
            $R23C4ASSet = findSet('R23C4',$sSummarycolumns);
            $R23C5ASSet = findSet('R23C5',$sSummarycolumns);
            $R23C6ASSet = findSet('R23C6',$sSummarycolumns);
            $R23C7ASSet = findSet('R23C7',$sSummarycolumns);
            $R23C8ASSet = findSet('R23C8',$sSummarycolumns);
            $R23C9ASSet = findSet('R23C9',$sSummarycolumns);

            $R24C1ASSet = findSet('R24C1',$sSummarycolumns);
            $R24C2ASSet = findSet('R24C2',$sSummarycolumns);
            $R24C3ASSet = findSet('R24C3',$sSummarycolumns);
            $R24C4ASSet = findSet('R24C4',$sSummarycolumns);
            $R24C5ASSet = findSet('R24C5',$sSummarycolumns);
            $R24C6ASSet = findSet('R24C6',$sSummarycolumns);
            $R24C7ASSet = findSet('R24C7',$sSummarycolumns);
            $R24C8ASSet = findSet('R24C8',$sSummarycolumns);
            $R24C9ASSet = findSet('R24C9',$sSummarycolumns);
            ?>
            <table id="detailTable" cellspacing="6" style=" margin: 0pt 0pt;">
                <tbody>
                <tr style="height: 24px;">
                    <td class="asFstTd text-left" style="width: 13%;">
                        <input type="text" class="asFstTd border-0 form-control form-control-sm" style="text-align: left !important;" placeholder="R1:C1" autocomplete="off" name="R[1][C1]" value="{!! $R1C1ASSet['Label'] !!}">
                    </td>
                    <td class="asFstTd">
                        <input type="text" class="asFstTd border-0 form-control form-control-sm" placeholder="R1:C2" autocomplete="off" name="R[1][C2]" value="{!! $R1C2ASSet['Label'] !!}">
                    </td>
                    <td class="asFstTd">
                        <input type="text" class="asFstTd border-0 form-control form-control-sm" placeholder="R1:C3" autocomplete="off" name="R[1][C3]" value="{!! $R1C3ASSet['Label'] !!}">
                    </td>
                    <td class="asFstTd">
                        <input type="text" class="asFstTd border-0 form-control form-control-sm" placeholder="R1:C4" autocomplete="off" name="R[1][C4]" value="{!! $R1C4ASSet['Label'] !!}">
                    </td>
                    <td class="asFstTd">
                        <input type="text" class="asFstTd border-0 form-control form-control-sm" placeholder="R1:C5" autocomplete="off" name="R[1][C5]" value="{!! $R1C5ASSet['Label'] !!}">
                    </td>
                    <td class="asFstTd">
                        <input type="text" class="asFstTd border-0 form-control form-control-sm" placeholder="R1:C6" autocomplete="off" name="R[1][C6]" value="{!! $R1C6ASSet['Label'] !!}">
                    </td>
                    <td class="asFstTd">
                        <input type="text" class="asFstTd border-0 form-control form-control-sm" placeholder="R1:C7" autocomplete="off" name="R[1][C7]" value="{!! $R1C7ASSet['Label'] !!}">
                    </td>
                    <td class="asFstTd">
                        <input type="text" class="asFstTd border-0 form-control form-control-sm" placeholder="R1:C8" autocomplete="off" name="R[1][C8]" value="{!! $R1C8ASSet['Label'] !!}">
                    </td>
                    <td class="asFstTd">
                        <input type="text" class="asFstTd border-0 form-control form-control-sm" placeholder="R1:C9" autocomplete="off" name="R[1][C9]" value="{!! $R1C9ASSet['Label'] !!}">
                    </td>

                </tr>

                <tr>
                    <td class="asParentTDLabel asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R2:C1" autocomplete="off" name="R[2][C1]" value="{!! $R2C1ASSet['Label'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R2:C2" autocomplete="off" name="R[2][C2]" value="{!! $R2C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R2:C3" autocomplete="off" name="R[2][C3]" value="{!! $R2C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R2:C4" autocomplete="off" name="R[2][C4]" value="{!! $R2C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R2:C5" autocomplete="off" name="R[2][C5]" value="{!! $R2C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R2:C6" autocomplete="off" name="R[2][C6]" value="{!! $R2C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R2:C7" autocomplete="off" name="R[2][C7]" value="{!! $R2C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R2:C8" autocomplete="off" name="R[2][C8]" value="{!! $R2C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R2:C9" autocomplete="off" name="R[2][C9]" value="{!! $R2C9ASSet['Field_Name'] !!}">
                    </td>

                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R3:C1" autocomplete="off" name="R[3][C1]" value="{!! $R3C1ASSet['Label'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R3:C2" autocomplete="off" name="R[3][C2]" value="{!! $R3C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R3:C3" autocomplete="off" name="R[3][C3]" value="{!! $R3C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R3:C4" autocomplete="off" name="R[3][C4]" value="{!! $R3C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R3:C5" autocomplete="off" name="R[3][C5]" value="{!! $R3C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R3:C6" autocomplete="off" name="R[3][C6]" value="{!! $R3C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R3:C7" autocomplete="off" name="R[3][C7]" value="{!! $R3C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R3:C8" autocomplete="off" name="R[3][C8]" value="{!! $R3C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R3:C9" autocomplete="off" name="R[3][C9]" value="{!! $R3C9ASSet['Field_Name'] !!}">
                    </td>

                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R4:C1" autocomplete="off" name="R[4][C1]" value="{!! $R4C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R4:C2" autocomplete="off" name="R[4][C2]" value="{!! $R4C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R4:C3" autocomplete="off" name="R[4][C3]" value="{!! $R4C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R4:C4" autocomplete="off" name="R[4][C4]" value="{!! $R4C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R4:C5" autocomplete="off" name="R[4][C5]" value="{!! $R4C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R4:C6" autocomplete="off" name="R[4][C6]" value="{!! $R4C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R4:C7" autocomplete="off" name="R[4][C7]" value="{!! $R4C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R4:C8" autocomplete="off" name="R[4][C8]" value="{!! $R4C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R4:C9" autocomplete="off" name="R[4][C9]" value="{!! $R4C9ASSet['Field_Name'] !!}">
                    </td>

                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R5:C1" autocomplete="off" name="R[5][C1]" value="{!! $R5C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R5:C2" autocomplete="off" name="R[5][C2]" value="{!! $R5C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R5:C3" autocomplete="off" name="R[5][C3]" value="{!! $R5C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R5:C4" autocomplete="off" name="R[5][C4]" value="{!! $R5C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R5:C5" autocomplete="off" name="R[5][C5]" value="{!! $R5C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R5:C6" autocomplete="off" name="R[5][C6]" value="{!! $R5C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R5:C7" autocomplete="off" name="R[5][C7]" value="{!! $R5C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R5:C8" autocomplete="off" name="R[5][C8]" value="{!! $R5C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R5:C9" autocomplete="off" name="R[5][C9]" value="{!! $R5C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R6:C1" autocomplete="off" name="R[6][C1]" value="{!! $R6C1ASSet['Label'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R6:C2" autocomplete="off" name="R[6][C2]" value="{!! $R6C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R6:C3" autocomplete="off" name="R[6][C3]" value="{!! $R6C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R6:C4" autocomplete="off" name="R[6][C4]" value="{!! $R6C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R6:C5" autocomplete="off" name="R[6][C5]" value="{!! $R6C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R6:C6" autocomplete="off" name="R[6][C6]" value="{!! $R6C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R6:C7" autocomplete="off" name="R[6][C7]" value="{!! $R6C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R6:C8" autocomplete="off" name="R[6][C8]" value="{!! $R6C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R6:C9" autocomplete="off" name="R[6][C9]" value="{!! $R6C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R7:C1" autocomplete="off" name="R[7][C1]" value="{!! $R7C1ASSet['Label'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R7:C2" autocomplete="off" name="R[7][C2]" value="{!! $R7C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R7:C3" autocomplete="off" name="R[7][C3]" value="{!! $R7C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R7:C4" autocomplete="off" name="R[7][C4]" value="{!! $R7C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R7:C5" autocomplete="off" name="R[7][C5]" value="{!! $R7C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R7:C6" autocomplete="off" name="R[7][C6]" value="{!! $R7C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R7:C7" autocomplete="off" name="R[7][C7]" value="{!! $R7C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R7:C8" autocomplete="off" name="R[7][C8]" value="{!! $R7C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R7:C9" autocomplete="off" name="R[7][C9]" value="{!! $R7C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R8:C1" autocomplete="off" name="R[8][C1]" value="{!! $R8C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R8:C2" autocomplete="off" name="R[8][C2]" value="{!! $R8C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R8:C3" autocomplete="off" name="R[8][C3]" value="{!! $R8C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R8:C4" autocomplete="off" name="R[8][C4]" value="{!! $R8C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R8:C5" autocomplete="off" name="R[8][C5]" value="{!! $R8C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R8:C6" autocomplete="off" name="R[8][C6]" value="{!! $R8C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R8:C7" autocomplete="off" name="R[8][C7]" value="{!! $R8C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R8:C8" autocomplete="off" name="R[8][C8]" value="{!! $R8C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R8:C9" autocomplete="off" name="R[8][C9]" value="{!! $R8C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr style="height: 24px;">
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                </tr>

                <tr>
                    <td class="asParentTDLabel asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R10:C1" autocomplete="off" name="R[10][C1]" value="{!! $R10C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R10:C2" autocomplete="off" name="R[10][C2]" value="{!! $R10C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R10:C3" autocomplete="off" name="R[10][C3]" value="{!! $R10C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R10:C4" autocomplete="off" name="R[10][C4]" value="{!! $R10C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R10:C5" autocomplete="off" name="R[10][C5]" value="{!! $R10C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R10:C6" autocomplete="off" name="R[10][C6]" value="{!! $R10C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R10:C7" autocomplete="off" name="R[10][C7]" value="{!! $R10C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R10:C8" autocomplete="off" name="R[10][C8]" value="{!! $R10C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R10:C9" autocomplete="off" name="R[10][C9]" value="{!! $R10C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr style="height: 24px;">
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                </tr>

                <tr>
                    <td class="asParentTDLabel asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R12:C1" autocomplete="off" name="R[12][C1]" value="{!! $R12C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R12:C2" autocomplete="off" name="R[12][C2]" value="{!! $R12C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R12:C3" autocomplete="off" name="R[12][C3]" value="{!! $R12C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R12:C4" autocomplete="off" name="R[12][C4]" value="{!! $R12C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R12:C5" autocomplete="off" name="R[12][C5]" value="{!! $R12C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R12:C6" autocomplete="off" name="R[12][C6]" value="{!! $R12C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R12:C7" autocomplete="off" name="R[12][C7]" value="{!! $R12C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R12:C8" autocomplete="off" name="R[12][C8]" value="{!! $R12C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R12:C9" autocomplete="off" name="R[12][C9]" value="{!! $R12C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R13:C1" autocomplete="off" name="R[13][C1]" value="{!! $R13C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R13:C2" autocomplete="off" name="R[13][C2]" value="{!! $R13C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R13:C3" autocomplete="off" name="R[13][C3]" value="{!! $R13C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R13:C4" autocomplete="off" name="R[13][C4]" value="{!! $R13C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R13:C5" autocomplete="off" name="R[13][C5]" value="{!! $R13C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R13:C6" autocomplete="off" name="R[13][C6]" value="{!! $R13C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R13:C7" autocomplete="off" name="R[13][C7]" value="{!! $R13C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R13:C8" autocomplete="off" name="R[13][C8]" value="{!! $R13C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R13:C9" autocomplete="off" name="R[13][C9]" value="{!! $R13C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R14:C1" autocomplete="off" name="R[14][C1]" value="{!! $R14C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R14:C2" autocomplete="off" name="R[14][C2]" value="{!! $R14C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R14:C3" autocomplete="off" name="R[14][C3]" value="{!! $R14C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R14:C4" autocomplete="off" name="R[14][C4]" value="{!! $R14C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R14:C5" autocomplete="off" name="R[14][C5]" value="{!! $R14C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R14:C6" autocomplete="off" name="R[14][C6]" value="{!! $R14C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R14:C7" autocomplete="off" name="R[14][C7]" value="{!! $R14C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R14:C8" autocomplete="off" name="R[14][C8]" value="{!! $R14C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R14:C9" autocomplete="off" name="R[14][C9]" value="{!! $R14C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R15:C1" autocomplete="off" name="R[15][C1]" value="{!! $R15C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R15:C2" autocomplete="off" name="R[15][C2]" value="{!! $R15C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R15:C3" autocomplete="off" name="R[15][C3]" value="{!! $R15C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R15:C4" autocomplete="off" name="R[15][C4]" value="{!! $R15C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R15:C5" autocomplete="off" name="R[15][C5]" value="{!! $R15C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R15:C6" autocomplete="off" name="R[15][C6]" value="{!! $R15C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R15:C7" autocomplete="off" name="R[15][C7]" value="{!! $R15C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R15:C8" autocomplete="off" name="R[15][C8]" value="{!! $R15C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R15:C9" autocomplete="off" name="R[15][C9]" value="{!! $R15C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R16:C1" autocomplete="off" name="R[16][C1]" value="{!! $R16C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R16:C2" autocomplete="off" name="R[16][C2]" value="{!! $R16C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R16:C3" autocomplete="off" name="R[16][C3]" value="{!! $R16C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R16:C4" autocomplete="off" name="R[16][C4]" value="{!! $R16C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R16:C5" autocomplete="off" name="R[16][C5]" value="{!! $R16C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R16:C6" autocomplete="off" name="R[16][C6]" value="{!! $R16C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R16:C7" autocomplete="off" name="R[16][C7]" value="{!! $R16C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R16:C8" autocomplete="off" name="R[16][C8]" value="{!! $R16C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R16:C9" autocomplete="off" name="R[16][C9]" value="{!! $R16C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R17:C1" autocomplete="off" name="R[17][C1]" value="{!! $R17C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R17:C2" autocomplete="off" name="R[17][C2]" value="{!! $R17C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R17:C3" autocomplete="off" name="R[17][C3]" value="{!! $R17C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R17:C4" autocomplete="off" name="R[17][C4]" value="{!! $R17C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R17:C5" autocomplete="off" name="R[17][C5]" value="{!! $R17C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R17:C6" autocomplete="off" name="R[17][C6]" value="{!! $R17C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R17:C7" autocomplete="off" name="R[17][C7]" value="{!! $R17C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R17:C8" autocomplete="off" name="R[17][C8]" value="{!! $R17C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R17:C9" autocomplete="off" name="R[17][C9]" value="{!! $R17C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R18:C1" autocomplete="off" name="R[18][C1]" value="{!! $R18C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R18:C2" autocomplete="off" name="R[18][C2]" value="{!! $R18C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R18:C3" autocomplete="off" name="R[18][C3]" value="{!! $R18C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R18:C4" autocomplete="off" name="R[18][C4]" value="{!! $R18C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R18:C5" autocomplete="off" name="R[18][C5]" value="{!! $R18C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R18:C6" autocomplete="off" name="R[18][C6]" value="{!! $R18C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R18:C7" autocomplete="off" name="R[18][C7]" value="{!! $R18C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R18:C8" autocomplete="off" name="R[18][C8]" value="{!! $R18C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R18:C9" autocomplete="off" name="R[18][C9]" value="{!! $R18C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R19:C1" autocomplete="off" name="R[19][C1]" value="{!! $R19C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R19:C2" autocomplete="off" name="R[19][C2]" value="{!! $R19C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R19:C3" autocomplete="off" name="R[19][C3]" value="{!! $R19C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R19:C4" autocomplete="off" name="R[19][C4]" value="{!! $R19C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R19:C5" autocomplete="off" name="R[19][C5]" value="{!! $R19C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R19:C6" autocomplete="off" name="R[19][C6]" value="{!! $R19C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R19:C7" autocomplete="off" name="R[19][C7]" value="{!! $R19C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R19:C8" autocomplete="off" name="R[19][C8]" value="{!! $R19C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R19:C9" autocomplete="off" name="R[19][C9]" value="{!! $R19C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R20:C1" autocomplete="off" name="R[20][C1]" value="{!! $R20C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R20:C2" autocomplete="off" name="R[20][C2]" value="{!! $R20C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R20:C3" autocomplete="off" name="R[20][C3]" value="{!! $R20C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R20:C4" autocomplete="off" name="R[20][C4]" value="{!! $R20C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R20:C5" autocomplete="off" name="R[20][C5]" value="{!! $R20C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R20:C6" autocomplete="off" name="R[20][C6]" value="{!! $R20C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R20:C7" autocomplete="off" name="R[20][C7]" value="{!! $R20C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R20:C8" autocomplete="off" name="R[20][C8]" value="{!! $R20C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R20:C9" autocomplete="off" name="R[20][C9]" value="{!! $R20C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr style="height: 24px;">
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                </tr>

                <tr>
                    <td class="asParentTDLabel asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R22:C1" autocomplete="off" name="R[22][C1]" value="{!! $R22C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R22:C2" autocomplete="off" name="R[22][C2]" value="{!! $R22C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R22:C3" autocomplete="off" name="R[22][C3]" value="{!! $R22C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R22:C4" autocomplete="off" name="R[22][C4]" value="{!! $R22C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R22:C5" autocomplete="off" name="R[22][C5]" value="{!! $R22C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R22:C6" autocomplete="off" name="R[22][C6]" value="{!! $R22C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R22:C7" autocomplete="off" name="R[22][C7]" value="{!! $R22C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R22:C8" autocomplete="off" name="R[22][C8]" value="{!! $R22C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD asttRow">
                        <input type="text" class="form-control form-control-sm" placeholder="R22:C9" autocomplete="off" name="R[22][C9]" value="{!! $R22C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R23:C1" autocomplete="off" name="R[23][C1]" value="{!! $R23C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R23:C2" autocomplete="off" name="R[23][C2]" value="{!! $R23C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R23:C3" autocomplete="off" name="R[23][C3]" value="{!! $R23C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R23:C4" autocomplete="off" name="R[23][C4]" value="{!! $R23C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R23:C5" autocomplete="off" name="R[23][C5]" value="{!! $R23C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R23:C6" autocomplete="off" name="R[23][C6]" value="{!! $R23C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R23:C7" autocomplete="off" name="R[23][C7]" value="{!! $R23C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R23:C8" autocomplete="off" name="R[23][C8]" value="{!! $R23C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R23:C9" autocomplete="off" name="R[23][C9]" value="{!! $R23C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">
                        <input type="text" class="form-control form-control-sm" placeholder="R24:C1" autocomplete="off" name="R[24][C1]" value="{!! $R24C1ASSet['Label'] !!}">
                    </td>

                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R24:C2" autocomplete="off" name="R[24][C2]" value="{!! $R24C2ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R24:C3" autocomplete="off" name="R[24][C3]" value="{!! $R24C3ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R24:C4" autocomplete="off" name="R[24][C4]" value="{!! $R24C4ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R24:C5" autocomplete="off" name="R[24][C5]" value="{!! $R24C5ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R24:C6" autocomplete="off" name="R[24][C6]" value="{!! $R24C6ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R24:C7" autocomplete="off" name="R[24][C7]" value="{!! $R24C7ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R24:C8" autocomplete="off" name="R[24][C8]" value="{!! $R24C8ASSet['Field_Name'] !!}">
                    </td>
                    <td class="asAllTD">
                        <input type="text" class="form-control form-control-sm" placeholder="R24:C9" autocomplete="off" name="R[24][C9]" value="{!! $R24C9ASSet['Field_Name'] !!}">
                    </td>
                </tr>
                <tr>
                    <td><input type="hidden" name="hid"></td>
                    <td colspan="11" align="right"></td>
                </tr>
                </tbody>
            </table>

            <div class="btn-toolbar mt-2 mr-2" role="toolbar" aria-label="Toolbar with button groups" style="display: block !important;">
                <div class="input-group pull-right">
                    <button type="button" class="btn btn-info font-12 s-f" title="Save Contact" id="updateSummaryLayoutBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $.fn.hasAttr = function(name) {
        return this.attr(name) !== undefined;
    };

    $(document).ready(function () {
        if (typeof(Storage) !== "undefined") {
            localStorage.removeItem("contactpage");
        }

        $('#field_type').on('change', function () {

            $(this).val() == 'select' ? $('#select_field_values').show() : $('#select_field_values').hide();
            var targetClass = $('#targetfieldboxclass').val();
            var targetFieldName = $('#targetfieldboxfield_name').val();

            var field_name = $('#field_name').val();
            var classname = $('#classname').val();
            var custom_options = $('#custom_options').is(':checked');
            var SQL = $('#SQL').val();
            var response = getValuesJSON();

            var data_attr = 'data-field_name="'+field_name+'" data-class_name="'+classname+'" data-custom_option="'+custom_options+'" data-SQL="'+SQL+'" data-options="'+response+'" name="'+targetFieldName+'"';

            if($(this).val() == 'select'){
                $('.' + targetClass + '_fieldbox').html('<select data-field_type="select" '+data_attr+' onclick="updateField(\''+targetClass +'\',\''+targetFieldName+'\',$(this))" class="form-control form-control-sm"> <option value="">Select</option></select>');
            }else if($(this).val() == 'date'){
                $('.' + targetClass + '_fieldbox').html('<div class="input-group">\n' +
                    '                                        <input type="text" data-field_type="date" '+data_attr+' class="t8 form-control form-control-sm js-datepicker" onclick="updateField(\''+targetClass +'\',\''+targetFieldName+'\',$(this))" style="height: 28px !important;" autocomplete="off">\n' +
                    '                                        <div class="input-group-append">\n' +
                    '                                            <span class="input-group-text" onclick="$(\'input\').closest().trigger(\'focus\');"><i class="fas fa-calendar-alt font-14 ds-c"></i></span>\n' +
                    '                                        </div>\n' +
                    '                                    </div>');
                initJS($('.' + targetClass + '_fieldbox'))
            }else if($(this).val() == 'readonly'){
                $('.' + targetClass + '_fieldbox').html('<input type="text" '+data_attr+' data-field_type="readonly" onclick="updateField(\''+targetClass +'\',\''+targetFieldName+'\',$(this))" class="form-control form-control-sm" readonly autocomplete="off">');
            }else if($(this).val() == 'textarea'){
                $('.' + targetClass + '_fieldbox').html('<textarea  data-field_type="textarea" '+data_attr+' onclick="updateField(\''+targetClass +'\',\''+targetFieldName+'\',$(this))" class="form-control form-control-sm" autocomplete="off"></textarea>');
            }else{
                $('.' + targetClass + '_fieldbox').html('<input type="text" data-field_type="text" '+data_attr+' onclick="updateField(\''+targetClass +'\',\''+targetFieldName+'\',$(this))" class="form-control form-control-sm" autocomplete="off">');
            }

        });

        $('#field_name').on('keyup',function () {
            var targetClass = $('#targetfieldboxclass').val();
            var field_name = $('#targetfieldboxfield_name').val();
            if($.trim($(this).val()) != ""){
                $('.' + targetClass + '_fieldbox').children('input,textarea,select').attr('name',field_name);
                $('.' + targetClass + '_fieldbox').children('input,textarea,select').attr('data-field_name',$(this).val());
            }else{
                $('.' + targetClass + '_fieldbox').children('input,textarea,select').removeAttr('name');
                $('.' + targetClass + '_fieldbox').children('input,textarea,select').attr('data-field_name','');
            }
        })

        $('#classname').on('keyup',function () {
            var targetClass = $('#targetfieldboxclass').val();
            if($.trim($(this).val()) != ""){
                $('.' + targetClass + '_fieldbox')
                    .children('input,select')
                    .removeAttr('class')
                    .attr('class','form-control form-control-sm')
                    .addClass($(this).val());

                $('.' + targetClass + '_fieldbox')
                    .children('input,select')
                    .attr('data-class_name',$(this).val())
            }else{
                $('.' + targetClass + '_fieldbox')
                    .children('input,select')
                    .removeAttr('class')
                    .attr('class','form-control form-control-sm');

                $('.' + targetClass + '_fieldbox')
                    .children('input,select')
                    .attr('data-class_name','')
            }
        });

        var wrapper = $('#valuesbox');
        var i = 1;
        $('#addvalues').on('click', function () {
            var targetClass = $('#targetfieldboxclass').val();
            if(!$('#custom_options').is(':checked')){
                $(wrapper).append('<div class="input-group"> <input type="text" name="label_'+i+'" data-id="'+i+'" class="form-control form-control-sm option_label" placeholder="Label" autocomplete="off"><input type="text" name="value_'+i+'" data-id="'+i+'" class="form-control form-control-sm option_value" placeholder="Value" autocomplete="off"> <div class="input-group-append"> <span class="input-group-text remove_field" data-id="'+i+'"><i class="fas fa-trash font-14 ds-c "  style="color:red"></i></span> </div> </div>');

                $('.' + targetClass + '_fieldbox')
                    .children('select')
                    .append('<option data-id="op_'+i+'" value=""></option>');
                i++;
            }
        });

        $(wrapper).on("keyup",".option_label", function(e) { //user click on remove text
            e.preventDefault();
            var targetClass = $('#targetfieldboxclass').val();
            var id = $(this).attr('data-id')
            $('.' + targetClass + '_fieldbox')
                .find('select option[data-id="op_'+id+'"]')
                .text($(this).val());

            var response = getValuesJSON();
            $('.' + targetClass + '_fieldbox')
                .children('input,select')
                .attr('data-options',response)

        });

        $(wrapper).on("keyup",".option_value", function(e) { //user click on remove text
            e.preventDefault();
            var targetClass = $('#targetfieldboxclass').val();
            var id = $(this).attr('data-id')
            $('.' + targetClass + '_fieldbox')
                .find('select option[data-id="op_'+id+'"]')
                .val($(this).val());

            var response = getValuesJSON();
            $('.' + targetClass + '_fieldbox')
                .children('input,select')
                .attr('data-options',response)
        });

        $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
            e.preventDefault();
            var targetClass = $('#targetfieldboxclass').val();
            var id = $(this).attr('data-id')
            $('.' + targetClass + '_fieldbox')
                .find('select option[data-id="op_'+id+'"]')
                .remove();
            $(this).parents('div.input-group').remove();

            var response = getValuesJSON();
            $('.' + targetClass + '_fieldbox')
                .children('input,select')
                .attr('data-options',response)
        })

        $('#custom_options').on('click',function () {
            var targetClass = $('#targetfieldboxclass').val();
            var custom_option = $(this).is(':checked');
            if(custom_option){
                $('.' + targetClass + '_fieldbox')
                    .find('select').empty()
                    .append('<option value="">Select</option>');

                $('.sql').show();

                if($('#SQL').val() != ""){
                    $('#execute_sql').trigger('click');
                }
            }else{
                $('.sql').hide();

                $('.' + targetClass + '_fieldbox')
                    .find('select').empty()
                    .append('<option value="">Select</option>');

                var labels = $("#valuesbox :input.option_label");
                $.each(labels,function(index,element){
                    var id = $(element).attr('data-id');
                    var label = $(element).val();
                    var value = $('[name="value_'+id+'"]').val();
                    if(label && value){
                        $('.' + targetClass + '_fieldbox')
                            .children('select')
                            .append('<option data-id="op_'+id+'" value="'+value+'">'+label+'</option>');
                    }

                })
            }


            $('.' + targetClass + '_fieldbox')
                .children('input,select,textarea')
                .attr('data-custom_options',custom_option)
        });

        $('#execute_sql').on('click',function () {
            var targetClass = $('#targetfieldboxclass').val();
            if($('#custom_options').is(':checked')){
                var sql = $('#SQL').val();
                ACFn.sendAjax('lookup/executesql','post',{
                    sql : sql,
                });

                $('.' + targetClass + '_fieldbox')
                    .children('input,select,textarea')
                    .attr('data-SQL',sql)
            }
        });

        ACFn.ajax_fill_options = function (F,R) {
            var targetClass = $('#targetfieldboxclass').val();

            if(R.success){
                var $dropdown = $('.' + targetClass + '_fieldbox').find('select');
                var $input = $('.' + targetClass + '_fieldbox').find('input');
                if($dropdown.length > 0){
                    $.each(R.aData, function() {
                        $dropdown.append($("<option />").val(this.value).text(this.label));
                    });
                }else if($input.length > 0){
                    $.each(R.aData, function() {
                        $input.val(this.value)
                    })

                }
            }
        };

        ACFn.ajax_save_page_settings = function(F,R){
            if(R.success){
                ACFn.display_message(R.messageTitle,'','success',3000);
                $('#modal-popup').modal('hide');
            }
        }

        /*$('#save_properties').on('click',function () {
            var targetClass = $('#targetfieldboxclass').val();
            var targetFieldName = $('#targetfieldboxfield_name').val();
            tempsave(targetClass,targetFieldName);
        });*/

        $('#updateContactLayoutBtn').on('click',function () {
            $(this).html('<span class="spinner-border spinner-border-sm ds-c" role="status" aria-hidden="true"></span><span class="sr-only">Loading...</span>');
            var $parent = $('#pscontact');
            var all_entries = []
            for(var i=1;i<=26;i++){
                var $columns = [];
                for (var j=1;j<=3;j++){
                    var rcLabelPosition = 'R['+i+'][C'+j+']';

                    if($parent.find('[name="'+rcLabelPosition+'"]').length == 0)
                        continue;

                    var rcLabelValue = $parent.find('[name="'+rcLabelPosition+'"]').val();

                    var value_position = 'R['+i+'][V'+j+']';
                    var $value_elem = $parent.find('[name="'+value_position+'"]');

                    var field_type = $value_elem.hasAttr('data-field_type') ? $value_elem.attr('data-field_type') : '';
                    var field_name = $value_elem.hasAttr('data-field_name') ? $value_elem.attr('data-field_name') : '';
                    var class_name = $value_elem.hasAttr('data-class_name') ? $value_elem.attr('data-class_name') : '';
                    var custom_option = $value_elem.hasAttr('data-custom_option') ? $value_elem.prop('data-custom_option') : 0;
                    var options = $value_elem.hasAttr('data-options') ? $value_elem.attr('data-options') : '';
                    var SQL = $value_elem.hasAttr('data-SQL') ? $value_elem.attr('data-SQL') : '';

                    var entry = {
                        Menu_Level1 : 'Lookup',
                        Menu_Level2 : 'Contact',
                        Row : 'R'+i,
                        Column : 'C'+j,
                        Position : 'R'+i+'C'+j,
                        Label : rcLabelValue,
                        Field_Type : field_type,
                        Field_Name : field_name,
                        Class : class_name,
                        Custom : custom_option,
                        Options : options,
                        SQL : SQL,
                        datetime : new Date($.now())
                    };
                    $columns.push(entry);

                }
                all_entries.push($columns);
            }
            ACFn.sendAjax('lookup/savepagesettings','post',{
                all_entries : JSON.stringify(all_entries),
                tab : 'Contact'
            });
        });

        $('#updateSummaryLayoutBtn').on('click',function () {
            $(this).html('<span class="spinner-border spinner-border-sm ds-c" role="status" aria-hidden="true"></span><span class="sr-only">Loading...</span>')
            var $parent = $('#pssummary');
            var all_entries = [];
            var labelEntries = ['R1','C1'];
            for(var i=1;i<=24;i++){
                var $columns = [];
                for (var j=1;j<=9;j++){
                    var row = 'R'+i;
                    var column = 'C'+j;
                    var rcLabelPosition = 'R['+i+'][C'+j+']';

                    if($parent.find('[name="'+rcLabelPosition+'"]').length == 0)
                        continue;

                    var label = '';
                    var field_name = '';
                    var class_name = $parent.find('[name="'+rcLabelPosition+'"]').parent().attr('class');
                    if($.inArray(row, labelEntries) != -1 || $.inArray(column, labelEntries) != -1){
                        label = $parent.find('[name="'+rcLabelPosition+'"]').val();
                    }else{
                        field_name = $parent.find('[name="'+rcLabelPosition+'"]').val();
                    }

                    var entry = {
                        Menu_Level1 : 'Lookup',
                        Menu_Level2 : 'Activity Summary',
                        Row : 'R'+i,
                        Column : 'C'+j,
                        Position : 'R'+i+'C'+j,
                        Label : label,
                        Field_Type : '',
                        Field_Name : field_name,
                        Class : class_name,
                        Custom : '',
                        Options : '',
                        SQL : '',
                        datetime : new Date($.now())
                    };
                    $columns.push(entry);

                }
                all_entries.push($columns);
            }
            console.log('activity summary ---',all_entries);
            ACFn.sendAjax('lookup/savepagesettings','post',{
                all_entries : JSON.stringify(all_entries),
                tab : 'Activity Summary'
            });
        });

        $('#delete_field_btn').on('click',function () {
            var targetClass = $('#targetfieldboxclass').val();
            $('.' + targetClass + '_fieldbox').html('').addClass('d-none');
            $('.' + targetClass + '_btn').removeClass('d-none');

            $('#field_type').val('')
            $('#field_name').val('')
            $('#classname').val('')
            $('#SQL').val('')
            $('#custom_options').prop('checked',false);
        })
    });

    function getValuesJSON() {
        var responseArr = [];
        var labels = $("#valuesbox :input.option_label");
        $.each(labels,function(index,element){
            var id = $(element).attr('data-id');
            var label = $(element).val();
            var value = $('[name="value_'+id+'"]').val();
            if(label && value){
                responseArr.push({
                    label : label,
                    value : value
                });
            }
        });

        return JSON.stringify(responseArr);
    }

    function properties() {
        if($('.properties').hasClass('d-none')){
            $('.properties').removeClass('d-none')
            $('.contentbox').removeClass('col-md-12').addClass('col-md-10')
        }else{
            $('.properties').addClass('d-none')
            $('.contentbox').removeClass('col-md-10').addClass('col-md-12')
        }
    }

    function updateField(cls,label_field_name,obj) {
        /*var targetClass = $('#targetfieldboxclass').val();
        var targetFieldName = $('#targetfieldboxfield_name').val();
        tempsave(targetClass,targetFieldName);*/
        $('.properties').removeClass('d-none')
        $('.contentbox').removeClass('col-md-12').addClass('col-md-10');

        $('#field_type').val(obj.attr('data-field_type'));
        $("#valuesbox").html('');
        $('#field_name').val(obj.attr('data-field_name'));
        $('#classname').val(obj.attr('data-class_name'));
        $("#custom_options").prop('checked',obj.attr('data-custom_option') == true ? true : false);
        $("#SQL").val(obj.attr('data-SQL'));
        if(obj.attr('data-custom_option') == true){
            $(".sql").show();
            $("#select_field_values").hide();
        }else{
            if(obj.attr('data-field_type') == 'select'){
                $("#select_field_values").show();
            }
            $(".sql").show();
        }

        $('#targetfieldboxclass').val(cls);
        $('#targetfieldboxfield_name').val(label_field_name);
    }

    function addField(cls,label_field_name) {
        /*var targetClass = $('#targetfieldboxclass').val();
        var targetFieldName = $('#targetfieldboxfield_name').val();
        tempsave(targetClass,targetFieldName);*/

        $('.properties').removeClass('d-none')
        $('.contentbox').removeClass('col-md-12').addClass('col-md-10');

        $('.' + cls + '_fieldbox').removeClass('d-none');
        $('.' + cls + '_btn').addClass('d-none');

        $('#targetfieldboxclass').val(cls)
        $('#targetfieldboxfield_name').val(label_field_name);
        $('#field_type').val('');
        $("#valuesbox").html('');
        $('#field_name').val('');
        $('#classname').val('');
        $("#custom_options").prop('checked',false);
        $("#SQL").val('');
    }

   /* function tempsave(cls,clsFileName) {

        var field_type = $('#field_type').val();
        var field_name = $('#field_name').val();
        var classname = $('#classname').val();
        var custom_options = $('#custom_options').is(':checked');
        var SQL = $('#SQL').val();


        if (typeof(Storage) !== "undefined") {

            var existingEntries = JSON.parse(localStorage.getItem("contactpage"));
            if (existingEntries == null) existingEntries = [];
            var labelsp = cls.replace('_',':');
            var label = $('[placeholder="'+labelsp+'"]').val();
            var labelposition = $('[placeholder="'+labelsp+'"]').attr('name');

            console.log('labelsp --',labelsp,'--label---',label,'---labelposition--',labelposition);

            var checkExist = false;
            var length = existingEntries.length;
            for (var i = 0; i < length; i++) {
                if (existingEntries[i].labelposition == labelposition) {
                    checkExist = false;
                    existingEntries.splice(i, 1);
                    break;
                }
            }

            if(checkExist == false){


                var entry = {
                    labelposition : labelposition,
                    clsFileName : clsFileName,
                    cls : cls,
                    "date_time" : new Date($.now()),
                    data: {
                        label_position : labelposition,
                        label_name : labelsp,
                        label : label,
                        field_type : field_type,
                        field_name : field_name,
                        class_name : classname,
                        custom_options : custom_options,
                        SQL : SQL,
                    }
                };
                localStorage.setItem("entry", JSON.stringify(entry));
                existingEntries.push(entry);
                localStorage.setItem("contactpage", JSON.stringify(existingEntries));
            }
        }
        var params = JSON.parse(localStorage.getItem("contactpage"));
        console.log(params);

    }*/
</script>
