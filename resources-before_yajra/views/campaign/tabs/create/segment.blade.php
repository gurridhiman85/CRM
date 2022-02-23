<script type="text/javascript" src="js/dropdown_up.js"></script>
<div id="doc3">
    <div class='yui-panel'>

        <div class='hd'>
            <table>
                <tr>
                    <td class="font-14" style="color:#144D62;">Segment</td>
                </tr>
            </table>
        </div>


        <!--  <div class='hd'>Add Sub-Groups</div>  -->
        <div class='bd'>
            <div id='divaddsub'></div>
            <!-- <a class="container-close" href="javascript:parent.designmode()"></a> -->
            <table class="c1">
                <tr id='divDLS'>
                    <td style='width:200px;'>Segment Selection <!--Segments --></td>
                    <td><select class="form-control form-control-sm" style='width:150px;' name='cmbDFS' id='cmbDFS' onchange="defineList(this);">
                            <option value='custom'>Custom</option>
                            <option value="byfield" selected>By Field</option>
                            <option value="none">None</option>
                        </select>&nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]
                            <span class="tooltiptext">Sample text.</span></div>
                    </td>

                </tr>
                <tr>
                    <td height="10px"></td>
                </tr>

            </table>

            <table class="c1" style='display:none'>
                <tr id='divLs1' style='display:none'>
                    <td style='width:200px;'>Number of List Segments</td>
                    <td><select class="form-control form-control-sm" style='width:150px;' id='txtnogrps' name='txtnogrps' onchange="SelectionCriteria()">
                            <!--<option value=''></option> -->
                            <?php for ($i = 1; $i < 31; $i++) {
                                echo "<option value=$i>$i</option>";
                            } ?></select>&nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]
                            <span class="tooltiptext">Sample text.</span></div>
                    </td>
                </tr>
            </table>

            <table class="c1">
                <tr id='divByField'>
                    <td style='width:200px;'>Number of Fields</td>
                    <td><select class="form-control form-control-sm" style='width:150px;' id='cmbNoFields' name='cmbNoFields' onchange="byField(this);">
                            <option value="1" selected>1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>&nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]
                            <span class="tooltiptext">Sample text.</span></div>
                    </td>
                </tr>
                <tr>
                    <td height="10px"></td>
                </tr>
            </table>

            <!--div id='divByFieldCol' style='display:none'></div-->
            <table id='divByFieldCol' style='display:none' class="c1" style="width:0px"></table>
            <!--div id='divLs2' style='display:none'></div-->

            <table>
                <tr>
                    <td height="10px"></td>
                </tr>

            </table>
            <style>
                .divTableCell a.cross {
                    display: none;
                    cursor: default;
                }

                .divTableCell .cross:hover {
                    display: inline;
                    cursor: pointer;
                }
            </style>

            <div id='divLs2' style="display:none;" class="divTable blueTable">
                <input type="hidden" id="numRows_4" value="0"/>
                <input type="hidden" id="titlelevel_4" value="311"/>
                <input type="hidden" id="next_target_4" value="plusDiv_311"/>
                <div class="divTableBody">

                    <div class="divTableRow" id="row_311">
                        <div class="divTableCell" style="width:196px;">
                            <div class="divTable blueTable">
                                <div class="divTableBody">
                                    <div class="divTableRow">
                                        <div class="divTableCell" style="padding-left: 0px;">
                                            &nbsp;&nbsp;Filters
                                            <span id="filterLoading" style="padding-left: 145px;display:none;">Loading....</span>
                                            <input type="hidden" id="tablename_311" value=""/>
                                            <input type="hidden" id="typebox_311" value=""/>
                                            <input type="hidden" id="countSec_311" value="0"/>
                                        </div>

                                        <div id="preCross_311" class="divTableCell" style="width:1%;text-align:right !important;"></div>
                                        <div style="width:1%;font-size: 10px;text-align:center;" class="divTableCell" id="plusDiv_311">
                                            <a onclick="addSectionNewSeg(311,311,0,1,'Filters');"
                                               href="javascript:void(0);">
                                                <input type="hidden" id="countSec_311" value="0"/>
                                                <i class="fa fa-plus-circle font-14 ds-c"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="divTableCell">
                            <div class="divTable blueTable">

                                <div class="divTableBody">
                                    <div class="divTableRow">
                                        <div class="divTableCell" id="ccolCell_311"></div>
                                        <div class="divTableCell" id="opCell_311"></div>
                                        <div class="divTableCell" id="valCell_311"></div>
                                        <div style="text-align: center;font-size: 10px;" class="divTableCell" id="plusCell_312"></div>
                                        <div class="divTableCell" id="ccolCell_312"></div>
                                        <div class="divTableCell" id="opCell_312"></div>
                                        <div class="divTableCell" id="valCell_312"></div>
                                        <div style="text-align: center;font-size: 10px;" class="divTableCell" id="plusCell_313"></div>
                                        <div class="divTableCell" id="ccolCell_313"></div>
                                        <div class="divTableCell" id="opCell_313"></div>
                                        <div class="divTableCell" id="valCell_313"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <table class="c1">     <!-- 2013 -->
                <tr>
                    <td height="10px"></td>
                </tr>
                <tr id="prevresult">
                    <td width="200px">Preview Results</td>
                    <td>
                        <select class="form-control form-control-sm" style='width:150px;' id='prvrslt' name='prvrslt' onchange="PrevResult()" id="prvrslt">

                            <option value="shwprw">Show Preview</option>
                            <option value="noprvw">No Preview</option>
                        </select>&nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]
                            <span class="tooltiptext">Sample text.</span></div>
                    </td>
                </tr>
                <tr>
                    <td height="10px"></td>
                </tr>

            </table>

            <table class="c1">
                <tr>
                    <td height="10px"></td>
                </tr>
                <tr id="tdLs5">
                    <td width="200px"><!--List -->Segment Sampling</td>
                    <td><select class="form-control form-control-sm" style='width:150px;' id='cmbLSM' name='cmbLSM' onchange="ListSegDetails()">
                            <option value=""></option>
                            <option value="none">None</option>
                            <option value="ranNum">Random Select By Number</option>
                            <option value="ranPer">Random by Percent</option>
                            <option value="topNum">Top Records by Number</option>
                            <option value="topPer">Top Records by Percent</option>
                        </select>&nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]
                            <span class="tooltiptext">Sample text.</span></div>
                    </td>
                </tr>
                <tr>
                    <td height="10px"></td>
                </tr>

            </table>

            <table id="c1hide" class="c1">
                <tr>
                    <td height="10px"></td>
                </tr>
                <tr id="tdAction" style="display:none">
                    <td width="200px"><!--List -->Segment Adjustment</td>
                    <td><select class="form-control form-control-sm" style='width:150px;' id='cmbAction' name='cmbAction'>
                            <option value="act">No Adjustment</option>
                            <option value="Delete">Delete Segments</option>
                            <option value="Join">Combine Segments</option>
                        </select>
                        <span class="button-group">
                             <span id="yui-gen9" class="yui-button yui-push-button">
				<span class="first-child"><button type="button" class="btn btn-info" style="height: 26.5px;padding: 1px 10px 1px 10px;" id='btnGo'
                                                  onClick='getAction();'>Go</button></span>
			      </span></span>&nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]
                            <span class="tooltiptext">Sample text.</span></div>
                    </td>
                </tr>
                <tr>
                    <td height="10px"></td>
                </tr>

            </table>

            <div id="LsSQL" style="display:none"></div>

        </div>

        <div class='hd'>
            <table>
                <tr>
                    <td class="font-14" style="color:#144D62; height:25px">Campaign
                        Groups
                    </td>
                    <td style="">
                    </td>
                </tr>
            </table>
        </div>
        <div class='bd'>
            <div id="">
                <table class="c1">
                    <tr>
                        <td height="10px"></td>
                    </tr>
                    <tr>
                        <td style="width:200px"><!--Number of-->Campaign Groups</td>
                        <td><select class="form-control form-control-sm" style='width:150px;' id='cmbnogroup' name='cmbnogroup' onChange='CGroupDetails()'>
                                <option value='0'>0</option><?php for ($i = 1; $i < 31; $i++) {
                                    echo "<option value=$i>$i</option>";
                                } ?></select>&nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]
                                <span class="tooltiptext">Sample text.</span></div>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="divCG1" style="display:none">
                <table class="c1">
                    <tr>
                        <td height="10px"></td>
                    </tr>
                    <tr>
                        <td style='width:200px;'><!--Campaign-->Control Group</td>
                        <td><select class="form-control form-control-sm" style="width:150px" id='chkgroup' name='chkgroup' onClick='CGroupDetails()'>
                                <option value="Y">Y</option>
                                <option value="N">N</option>
                            </select>&nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]
                                <span class="tooltiptext">Sample text.</span></div>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="divCG2" style="display:none"></div>

            <div id="divCG3" style="display:none">
                <table class="c1">
                    <tr>
                        <td height="10px"></td>
                    </tr>
                    <tr>
                        <td style='width:200px;'><!--Campaign Group-->Proportion</td>
                        <td><select class="form-control form-control-sm" style='width:150px;' id='cmbSD' name='cmbSD' onchange='sample()'>
                                <option value=''></option>
                                <option value='cmbAEG'>All Equal Groups</option>
                                <option value='cmbEPG'>Equal Program Groups</option>
                                <option value='cmbUG'>Unequal Groups</option>
                            </select>&nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]
                                <span class="tooltiptext">Sample text.</span></div>
                        </td>
                    </tr>
                    <tr>
                        <td height="10px"></td>
                    </tr>
                    <tr>
                        <td><!--Campaign Group-->Selection Criteria</td>
                        <td><select class="form-control form-control-sm" style='width:150px;' id='cmbSC' name='cmbSC' onchange='sample()'>
                                <option value=''></option>
                                <option value='cmbPU'>Percent of Universe</option>
                                <option value='cmbNR'> Number of Records</option>
                            </select>&nbsp;&nbsp;<div style=" color:grey;font-size:10px;" class="tooltip">[?]<span
                                        class="tooltiptext">Sample text.</span></div>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="divCG4" style="display:none;margin-bottom: 10px;"></div>

            <div class='ft' style='text-align:right;'>
                <span class="button-group">
                    <span id="yui-gen9" class="yui-button yui-push-button">
                        <span class="first-child">
                            <button type="button" class="btn btn-info font-12 s-f" id="savebottom" onClick='next()'>Next</button>
                        </span>
                    </span>
                </span>
            </div>
        </div>
    </div>
</div>
<script src="js/segment.js?ver={{time()}}" type="application/javascript"></script>

