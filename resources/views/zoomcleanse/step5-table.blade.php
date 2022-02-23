<table class="table table-bordered table-hover color-table lkp-table" id="basic_table_without_dynamic_pagination">
    <thead>
    <tr>
        <th>
            <label class="custom-control custom-checkbox m-b-0">
                <input type="checkbox" class="custom-control-input" id="select_all" onclick="selectAll($(this))">
                <span class="custom-control-label"></span>
            </label>
        </th>
        <th>Zoom Name</th>
        <th>DFL Name Suggested</th>
        <th>DFL Name</th>
        <th>Zoom Email</th>
        <th>DS_MKC_ContactID</th>

    </tr>
    </thead>
    <tbody>
    @foreach($sStep2TableRows as $sStep2TableRow)
        <tr>
            <td>
                <!--<input type="checkbox" class="checkbox" value="1"/>-->
                <label class="custom-control custom-checkbox m-b-0">
                    <input type="checkbox" class="custom-control-input checkbox" name="rowid[]" value="{{ $sStep2TableRow['rowid'] }}" id="step5checkbox_{{ $sStep2TableRow['rowid'] }}" onclick="singleCheckbox();addInsertRecord($(this));" >
                    <span class="custom-control-label"></span>
                </label>
            </td>
            <td id="customer_s2_{{ $sStep2TableRow['rowid'] }}">{{ $sStep2TableRow['customer_s2'] }}</td>

            <td>
                <div class="ui-widget">
                    <input class="form-control typeahead" id="{{ $sStep2TableRow['rowid'] }}" type="text" value="{{ $sStep2TableRow['dflname_Suggested'] }}" onclick="$('[id={{ $sStep2TableRow['rowid'] }}]').trigger('focus'); addAutoComplete($(this));" onkeyup="addAutoComplete($(this))">
                </div>

            </td>
            <td id="dflname_{{ $sStep2TableRow['rowid'] }}">{{ $sStep2TableRow['dflname'] }}</td>
            <td id="email_{{ $sStep2TableRow['rowid'] }}">{{ $sStep2TableRow['email'] }}</td>
            <td id="DS_MKC_ContactID_{{ $sStep2TableRow['rowid'] }}">{{ $sStep2TableRow['DS_MKC_ContactID'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    function addAutoComplete(obj){
        obj.autocomplete({
            source: function( request, response ) {
                $.ajax( {
                    url: "zoomcleanse/step5autofill",
                    dataType: "json",
                    data: {
                        term: request.term,
                        update : false
                    },
                    success: function( data ) {
                        response( data );
                    }
                } );
            },
            minLength: 0,
            select: function( event, ui ) {
                $('#step5checkbox_' + obj.attr('id')).attr('checked',true);
                $.ajax( {
                    url: "zoomcleanse/step5autofill",
                    dataType: "json",
                    data: {
                        itemSelId: obj.attr('id'),
                        itemSelVal: ui.item.value,
                        update : true,
                    },
                    success: function( data ) {
                        $('#customer_s2_' + obj.attr('id')).text(data.sStep5TableRow.customer_s2 != null ? data.sStep5TableRow.customer_s2 : '');
                        $('#dflname_' + obj.attr('id')).text(data.sStep5TableRow.dflname != null ? data.sStep5TableRow.dflname : '');
                        $('#email_' + obj.attr('id')).text(data.sStep5TableRow.email != null ? data.sStep5TableRow.email : '');
                        $('#DS_MKC_ContactID_' + obj.attr('id')).text(data.sStep5TableRow.DS_MKC_ContactID != null ? data.sStep5TableRow.DS_MKC_ContactID : '');
                    }
                } );
            }
        } ).bind('focus', function(){ $(this).autocomplete("search"); } );;
    }

    function addInsertRecord(obj) {
        var is_checked = obj.is(':checked') ? 1 : 0;
        obj.val()

        $.ajax( {
            url: "zoomcleanse/step5addinsertrecord",
            dataType: "json",
            data: {
                is_checked : is_checked,
                itemSelId : obj.val(),
                itemSelVal : $('#'+obj.val()).val()
            },
            success: function( data ) {
                $('#DS_MKC_ContactID_' + obj.val()).text(data.sStep5TableRow.DS_MKC_ContactID != null ? data.sStep5TableRow.DS_MKC_ContactID : '');
            }
        } );

    }
</script>

