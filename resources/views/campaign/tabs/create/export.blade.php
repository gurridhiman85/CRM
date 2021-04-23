<div id="doc3" class="mt-2 ml-3">
    <div class='yui-panel'>

        <div class='bd'>
            <div id='divPromoExport' style='overflow-y:auto;overflow-x:hidden;height:476px;'>
                <table>
                    <tr>
                        <td style='width:200px;' valign=top>Save to CampaignData Table</td>
                        <td valign=top>
                            <select id='cmbsavepromoopt' class="form-control form-control-sm" onChange='checkPromoExp(this);'>
                                <option value='Y' selected>Y</option>
                                <option value='N'>N</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:10px"></td>
                    </tr>
                    <tr>
                        <td style='width:120px;' valign=top>Create Export File</td>
                        <td valign=top>
                            <select id='cmbsaveexportopt' class="form-control form-control-sm" onChange='showExportExp(this);'>
                                <option value='Y' selected>Y</option>
                                <option value='N'>N</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td style="height:10px"></td>
                    </tr>
                    <tr id="trFolder" style="display:none">
                        <td valign=top>Export File Type</td>
                        <td valign=top>
                            <select name='foldername' class="form-control form-control-sm" id="foldername">
                                <option value='Public' selected>Public</option>
                                <option value='Private'>Private</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:10px"></td>
                    </tr>
                    <tr id="trFile" style="display:none">
                        <td valign=top>Export File Name</td>

                        <td valign=top>
                            <input type='text' class="form-control form-control-sm" id="filename" name='filename'>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:10px"></td>
                    </tr>
                    <tr id="trFF" style="display:none">
                        <td valign=top style='width:150px;'> Export File Format</td>
                        <td valign=top>
                            <select name='cmbexport' class="form-control form-control-sm" id='cmbexport'>
                                <option value='csv'>CSV</option>
                                {{--<option value='tab'>TAB</option>--}}
                                {{--<option value='xml'>XML</option>--}}
                                <option value='xlsx'>XLSX</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:10px"></td>
                    </tr>
                    <tr id="trCtrl" style="display:none">
                        <td>Exclude Control from Export</td>
                        <td>
                            <select class="form-control form-control-sm" name='cmbCtrlopt' id='cmbCtrlopt'>
                                <option value='Y' selected>Y</option>
                                <option value='N'>N</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div id="divCol" style="display:none"></div>
                        </td>
                    </tr>
                </table>

            </div>

        </div>
        <a class="container-close" href="javascript:parent.librarySQL()"></a>
        <div class='ft' style='text-align:right'>
		<span class="button-group">
			{{--<span id="yui-gen9" class="yui-button yui-push-button">
				<span class="first-child">
					<button type="button" onClick='parent.librarySQL()'>Cancel</button>
				</span>
			</span>
			<span id="yui-gen9" class="yui-button yui-push-button">
				<span class="first-child">
					<button type="button" id="clear" onClick='promoExpoClearExp()'>Clear</button>
				</span>
			</span>--}}
			<span id="yui-gen9" class="yui-button yui-push-button">
				<span class="first-child">
					<button type="button" class="btn btn-info" id="savebottom" onClick='nextExp()'>Next</button>
				</span>
			</span>
		</span>
        </div>
    </div>
</div>
<script src="js/export.js" type="application/javascript"></script>