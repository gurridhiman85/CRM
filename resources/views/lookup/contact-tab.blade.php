<?php
function findSet($find,$contact_layout){
    $key = array_search($find, array_column($contact_layout, 'Position'));
    return $contact_layout[$key];
}

$R1C1Set = findSet('R1C1',$contact_layout);
$R1C2Set = findSet('R1C2',$contact_layout);
$R1C3Set = findSet('R1C3',$contact_layout);

$R2C1Set = findSet('R2C1',$contact_layout);
$R2C2Set = findSet('R2C2',$contact_layout);
$R2C3Set = findSet('R2C3',$contact_layout);

$R3C1Set = findSet('R3C1',$contact_layout);
$R3C2Set = findSet('R3C2',$contact_layout);
$R3C3Set = findSet('R3C3',$contact_layout);

$R4C1Set = findSet('R4C1',$contact_layout);
$R4C2Set = findSet('R4C2',$contact_layout);
$R4C3Set = findSet('R4C3',$contact_layout);

$R5C1Set = findSet('R5C1',$contact_layout);
$R5C2Set = findSet('R5C2',$contact_layout);
$R5C3Set = findSet('R5C3',$contact_layout);

$R6C1Set = findSet('R6C1',$contact_layout);
$R6C2Set = findSet('R6C2',$contact_layout);
$R6C3Set = findSet('R6C3',$contact_layout);

$R7C1Set = findSet('R7C1',$contact_layout);
$R7C2Set = findSet('R7C2',$contact_layout);
$R7C3Set = findSet('R7C3',$contact_layout);

$R8C1Set = findSet('R8C1',$contact_layout);
$R8C2Set = findSet('R8C2',$contact_layout);
$R8C3Set = findSet('R8C3',$contact_layout);

$R9C1Set = findSet('R9C1',$contact_layout);
$R9C2Set = findSet('R9C2',$contact_layout);
$R9C3Set = findSet('R9C3',$contact_layout);

$R10C1Set = findSet('R10C1',$contact_layout);
$R10C2Set = findSet('R10C2',$contact_layout);
$R10C3Set = findSet('R10C3',$contact_layout);

$R11C1Set = findSet('R11C1',$contact_layout);
$R11C2Set = findSet('R11C2',$contact_layout);
$R11C3Set = findSet('R11C3',$contact_layout);

$R13C1Set = findSet('R13C1',$contact_layout);

$R14C1Set = findSet('R14C1',$contact_layout);
$R14C2Set = findSet('R14C2',$contact_layout);
$R14C3Set = findSet('R14C3',$contact_layout);

$R15C1Set = findSet('R15C1',$contact_layout);
$R15C2Set = findSet('R15C2',$contact_layout);
$R15C3Set = findSet('R15C3',$contact_layout);

$R16C1Set = findSet('R16C1',$contact_layout);
$R16C2Set = findSet('R16C2',$contact_layout);
$R16C3Set = findSet('R16C3',$contact_layout);

$R17C1Set = findSet('R17C1',$contact_layout);
$R17C2Set = findSet('R17C2',$contact_layout);
$R17C3Set = findSet('R17C3',$contact_layout);

$R18C1Set = findSet('R18C1',$contact_layout);
$R18C2Set = findSet('R18C2',$contact_layout);
$R18C3Set = findSet('R18C3',$contact_layout);

$R19C1Set = findSet('R19C1',$contact_layout);
$R20C1Set = findSet('R20C1',$contact_layout);

$R21C1Set = findSet('R21C1',$contact_layout);
$R21C2Set = findSet('R21C2',$contact_layout);
$R21C3Set = findSet('R21C3',$contact_layout);

$R22C1Set = findSet('R22C1',$contact_layout);
$R22C2Set = findSet('R22C2',$contact_layout);
$R22C3Set = findSet('R22C3',$contact_layout);

$R23C1Set = findSet('R23C1',$contact_layout);
$R23C2Set = findSet('R23C2',$contact_layout);
$R23C3Set = findSet('R23C3',$contact_layout);

$R25C1Set = findSet('R25C1',$contact_layout);

$R26C1Set = findSet('R26C1',$contact_layout);



?>
<div class="divTable">
    <div class="divTableBody">

        <!-------------------- Name and Address  - Start -------------->

        <div class="divTableRow">
            <div class="divTableCell" style="width: 14.6%;color: #357EC7;
font-weight: 500;font-size:14px;">{{ $R1C1Set['Label'] }}</div> {{--$R1C1Set['Label']padding: 3px 4px !important;--}}
            <div class="divTableCell" style="width: 18.6%;text-align: left;"><b></b></div>
            <div class="divTableCell" style="width: 14.6%;text-align: left;color: #357EC7;
font-weight: 500;font-size:14px;">{{ $R1C2Set['Label'] }}</div>
            <div class="divTableCell" style="width: 18.6%;text-align: left;"><b></b></div>
            <div class="divTableCell" style="width: 14.6%;color: #357EC7;
font-weight: 500;font-size:14px;">{{ $R1C3Set['Label'] }}</div>
            <div class="divTableCell" style="width: 18.6%;text-align: left;"></div>

        </div>

        <div class="divTableRow">
            <div class="divTableCell">
                <label class="l1">{{ $R2C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R2C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R2C1Set,
                        'extra_class' => 't8 input p1 dis'
                    ])
                @endif
                {{--<div class="input t8 p1 dis txtds_mkc_contactid form-control form-control-sm"></div>--}}
                <!--<input type="text" class="t8 dis txtDS_MKC_ContactID" name="txtDS_MKC_ContactID">-->
            </div>
            <div class="divTableCell">
                <label class="l1">{{ $R2C2Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R2C2Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R2C2Set,
                        'extra_class' => 't8 input p2 dis'
                    ])
                @endif
                {{--<div class="input t8 p2 dis txtds_mkc_householdid form-control form-control-sm"></div>--}}
            </div>
            <div class="divTableCell"><label class="l1">{{ $R2C3Set['Label'] }}</label></div>
            <div class="divTableCell">

                {{--<input type="text"
                       style=" font-size:10px; width:504px"
                       class="t6 h dis txtextendedname form-control form-control-sm"
                       name="extendedname">--}}
                @if(!empty($R2C3Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R2C3Set,
                        'extra_class' => 't6 h dis'
                    ])
                @endif
            </div>
        </div>

        <div class="divTableRow">
            <div class="divTableCell">
                <label class="l1">{{ $R3C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R3C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R3C1Set,
                        'extra_class' => 't8 p1 '
                    ])
                @endif
            </div>
            <div class="divTableCell">
                <label class="l1">{{ $R3C2Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R3C2Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R3C2Set,
                        'extra_class' => 't8 p2 '
                    ])
                @endif
            </div>
            <div class="divTableCell">
                <label class="l1">{{ $R3C3Set['Label'] }}</label>
            </div>

            <div class="divTableCell">
                @if(!empty($R3C3Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R3C3Set,
                        'extra_class' => 't6 '
                    ])
                @endif
            </div>
        </div>

        <div class="divTableRow">
            <div class="divTableCell">
                <label class="l1">{{ $R4C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R4C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R4C1Set,
                        'extra_class' => 't8 p1 '
                    ])
                @endif
            </div>
            <div class="divTableCell">
                <label class="l1">{{ $R4C2Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R4C2Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R4C2Set,
                        'extra_class' => 't8 p2 '
                    ])
                @endif
            </div>
            <div class="divTableCell">
                <label class="l1">{{ $R4C3Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R4C3Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R4C3Set,
                        'extra_class' => 't6 '
                    ])
                @endif
            </div>
        </div>

        <div class="divTableRow">

            <div class="divTableCell">
                <label class="l1">{{ $R5C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R5C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R5C1Set,
                        'extra_class' => 't8 p1 '
                    ])
                @endif
            </div>

            <div class="divTableCell">
                <label class="l1">{{ $R5C2Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R5C2Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R5C2Set,
                        'extra_class' => 't8 p2 '
                    ])
                @endif
            </div>
            <div class="divTableCell">
                <label class="l1">{{ $R5C3Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R5C3Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R5C3Set,
                        'extra_class' => 't6 h '
                    ])
                @endif
            </div>
        </div>

        <div class="divTableRow">
            <div class="divTableCell">
                <label class="l1">{{ $R6C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R6C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R6C1Set,
                        'extra_class' => 't8 p1 '
                    ])
                @endif
            </div>

            <div class="divTableCell">
                <label class="l1">{{ $R6C2Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R6C2Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R6C2Set,
                        'extra_class' => 't8 p2 '
                    ])
                @endif
            </div>
            <div class="divTableCell">
                <label class="l1">{{ $R6C3Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R6C3Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R6C3Set,
                        'extra_class' => 't6 h '
                    ])
                @endif
            </div>
        </div>

        <div class="divTableRow">
            <div class="divTableCell">
                <label class="l1">{{ $R7C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R7C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R7C1Set,
                        'extra_class' => 't8 p1 '
                    ])
                @endif
            </div>

            <div class="divTableCell">
                <label class="l1">{{ $R7C2Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R7C2Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R7C2Set,
                        'extra_class' => 't8 p2 '
                    ])
                @endif
            </div>
            <div class="divTableCell">
                <label class="l1">{{ $R7C3Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R7C2Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R7C2Set,
                        'extra_class' => 't6 h '
                    ])
                @endif
            </div>
        </div>

        <div class="divTableRow">
            <div class="divTableCell">
                <label class="l1">{{ $R8C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R8C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R8C1Set,
                        'extra_class' => 't8 p1 '
                    ])
                @endif
            </div>

            <div class="divTableCell">
                <label class="l1">{{ $R8C2Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R8C2Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R8C2Set,
                        'extra_class' => 't8 p2 '
                    ])
                @endif
            </div>
            <div class="divTableCell">
                <label class="l1">{{ $R8C3Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R8C3Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R8C3Set,
                        'extra_class' => 't6 h '
                    ])
                @endif
            </div>
        </div>

        <div class="divTableRow">
            <div class="divTableCell">
                <label class="l1">{{ $R9C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R9C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R9C1Set,
                        'extra_class' => 't8 p1 '
                    ])
                @endif
            </div>

            <div class="divTableCell">
                <label class="l1">{{ $R9C2Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R9C2Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R9C2Set,
                        'extra_class' => 't8 p2 '
                    ])
                @endif
            </div>

            <div class="divTableCell">
                <label class="l1">{{ $R9C3Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R9C3Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R9C3Set,
                        'extra_class' => 't6 h '
                    ])
                @endif
            </div>
        </div>

        <div class="divTableRow">
            <div class="divTableCell">
                <label class="l1">{{ $R10C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R10C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R10C1Set,
                        'extra_class' => 't8 p1 '
                    ])
                @endif
            </div>

            <div class="divTableCell">
                <label class="l1">{{ $R10C2Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R10C2Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R10C2Set,
                        'extra_class' => 't8 p2 '
                    ])
                @endif
            </div>


            <div class="divTableCell">
                <label class="l1">{{ $R10C3Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R10C3Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R10C3Set,
                        'extra_class' => 't6 h '
                    ])
                @endif
            </div>
        </div>

        <div class="divTableRow">
            <div class="divTableCell">
                <label class="l1">{{ $R11C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R11C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R11C1Set,
                        'extra_class' => 't8 p1 '
                    ])
                @endif
            </div>

            <div class="divTableCell">
                <label class="l1">{{ $R11C2Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R11C2Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R11C2Set,
                        'extra_class' => 't8 p2 '
                    ])
                @endif
            </div>


            <div class="divTableCell">
                <label class="l1">{{ $R11C3Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R11C3Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R11C3Set,
                        'extra_class' => 't6 h '
                    ])
                @endif
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
font-weight: 500;font-size:14px;">{{ $R13C1Set['Label'] }}</div>
            <div class="divTableCell"></div>
            <div class="divTableCell"></div>
            <div class="divTableCell"></div>
            <div class="divTableCell"></div>
            <div class="divTableCell"></div>
        </div>

        <div class="divTableRow">
            <div class="divTableCell">
                <label class="l1">{{ $R14C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R14C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R14C1Set,
                        'extra_class' => 't8 p1 '
                    ])
                @endif
            </div>


            <div class="divTableCell">
                <label class="l1">{{ $R14C2Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R14C2Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R14C2Set,
                        'extra_class' => 't8 p2 '
                    ])
                @endif
            </div>


            <div class="divTableCell"><label class="l1">{{ $R14C3Set['Label'] }}</label></div>
            <div class="divTableCell">
                @if(!empty($R14C3Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R14C3Set,
                        'extra_class' => 't6 h '
                    ])
                @endif
            </div>
        </div>

        <div class="divTableRow">
            <div class="divTableCell">
                <label class="l1">{{ $R15C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R15C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R15C1Set,
                        'extra_class' => 't8 p1 '
                    ])
                @endif
            </div>


            <div class="divTableCell">
                <label class="l1">{{ $R15C2Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R15C2Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R15C2Set,
                        'extra_class' => 't8 p2 '
                    ])
                @endif
            </div>



            <div class="divTableCell"><label class="l1">{{ $R15C3Set['Label'] }}</label></div>
            <div class="divTableCell">
                @if(!empty($R15C3Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R15C3Set,
                        'extra_class' => 't6 h '
                    ])
                @endif
            </div>
        </div>

        <div class="divTableRow">
            <div class="divTableCell">
                <label class="l1">{{ $R16C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R16C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R16C1Set,
                        'extra_class' => 't8 p1 '
                    ])
                @endif
            </div>

            <div class="divTableCell">
                <label class="l1">{{ $R16C2Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R16C2Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R16C2Set,
                        'extra_class' => 't8 p2 '
                    ])
                @endif
            </div>

            <div class="divTableCell">
                <label class="l1">{{ $R16C3Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R16C3Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R16C3Set,
                        'extra_class' => 't6 h '
                    ])
                @endif
            </div>
        </div>

        <div class="divTableRow">
            <div class="divTableCell">
                <label class="l1">{{ $R17C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R17C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R17C1Set,
                        'extra_class' => 't8 p1 '
                    ])
                @endif
            </div>

            <div class="divTableCell">
                <label class="l1">{{ $R17C2Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R17C2Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R17C2Set,
                        'extra_class' => 't8 p2 '
                    ])
                @endif
            </div>

            <div class="divTableCell">
                <label class="l1">{{ $R17C3Set['Label'] }}</label>
            </div>
            <div class="divTableCell">
                @if(!empty($R17C3Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R17C3Set,
                        'extra_class' => 't6 h '
                    ])
                @endif
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

        <!--------------- Touch - Start --------------------->
        <div class="divTableRow">
            <div class="divTableCell" style="width: 14%;color: #357EC7;
font-weight: 500;font-size:14px;">{{ $R19C1Set['Label'] }}</div>
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
            <div class="divTableCell" style="width: 11.4% !important;
    padding: 3px 9px !important;"><label class="l1">{{ $R20C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell" style="width: 100% !important;">
                @if(!empty($R20C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R20C1Set,
                        'extra_class' => ' '
                    ])
                @endif
            </div>
        </div>

        <div class="divTableRow">
            <div class="divTableCell" style="width: 11.4% !important;
    padding: 3px 9px !important;"><label class="l1">{{ $R21C1Set['Label'] }}</label>
            </div>
            <div class="divTableCell" style="width: 100% !important;">
                @if(!empty($R21C1Set['Field_Type']))
                    @include('lookup.fields.contact-fields',[
                        'set' => $R21C1Set,
                        'extra_class' => ' '
                    ])
                @endif
            </div>
        </div>
    </div>

</div>

<!-------------------- Notes - End -------------->
