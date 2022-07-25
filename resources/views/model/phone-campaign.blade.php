<form class="form-horizontal ajax-Form" id="createcamp" action="campaign/phone" class="ajax-Form" method="post">
    {!! csrf_field() !!}
    <div class="form-body">
        <div class="card-body p-2">

            <div class="row">
                <div class="col-md-12 p-0">
                    <div class="form-group">
                        <label class="control-label">Campaign</label>
                        <select class="form-control form-control-sm" id="campaign" name="campaign">
                            <option value="">Select Campaign</option>
                            @foreach($campaigns as $campaign)
                                <option value="{!! $campaign->t_id.'::'.$campaign->list_short_name !!}">{!! $campaign->t_name !!}</option>
                            @endforeach
                        </select>
                        <small id="indicationMsg" class="form-control-feedback ds-l"></small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 pull-right">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="display: block !important;">
                        <div class="input-group pull-right">
                            <button type="submit" class="btn btn-info font-12 s-f" title="Submit" >Submit</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="table-responsive mt-2" id="phonetable"></div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        ACFn.ajax_phone_campaign = function (F,R) {
            if(R.success){
                $('#phonetable').html(R.html);
            }
        }
    })
</script>

