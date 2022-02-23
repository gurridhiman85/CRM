<form action="phone/add" class="ajax-Form" method="post">
    {!! csrf_field() !!}
    <div class="form-body">
        <div class="card-body pt-0">
            <div class="form-group">
                <label class="control-label">Campaign</label>
                <select name="Campaign" class="form-control form-control-sm" onchange="populatecampaign($(this))">
                    <option value="">Select Campaign</option>
                    @foreach($camapigns as $camapign)
                        <option data-campaign='{!! json_encode($camapign) !!}' value='{!! json_encode($camapign) !!}'>{!! $camapign->CampaignDes !!}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="control-label">Name</label>
                <div class="row pr-2 pl-2">
                    <input type="text" name="Current_Year_Month" class="form-control form-control-sm col-md-2" readonly value="{!! date('Y_m_') !!}">
                    <input type="text" name="Name" class="form-control form-control-sm col-md-10" placeholder="Enter Name" maxlength="10">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label">Channel</label>
                <input type="text" name="Channel" id="Channel" readonly class="form-control form-control-sm">
            </div>

            <div class="form-group">
                <label class="control-label">Status</label>
                <input type="text" name="Status" id="Status" readonly class="form-control form-control-sm" value="Assigned">
            </div>

            <div class="form-actions pull-right">
                <button type="submit" class="btn btn-info">Insert</button>
                <button type="button" class="btn border-secondary waves-effect waves-light btn-outline-secondary" data-dismiss="modal" style="border-color: #dee2e6;">Cancel</button>
            </div>
        </div>
    </div>
</form>

<script type="application/javascript">
    function populatecampaign(obj) {
        if(obj.find('option:selected').attr('value') != ""){
            var campaign = JSON.parse(obj.find('option:selected').attr('data-campaign'));
            $('#Channel').val(campaign.Channel);
        }else{
            $('#Channel').val('');
        }
    }

    $(document).ready(function () {
        ACFn.ajax_add_to_phone = function (F,R) {
            if(R.success){
                F[0].reset();
                $('#modal-popup').modal('hide');
                $('.tab-ajax li a.active').trigger('show.bs.tab');
            }
        }
    })
</script>
