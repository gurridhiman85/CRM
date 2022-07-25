<div id="singlecamp_tab" class="table-responsive m-t-5" >
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 pl-0">
                            <div class="form-group row">
                                {{--<label class="control-label text-left col-md-2 pt-1">Campaign</label>--}}
                                <div class="col-md-6 pl-0">
                                    <select class="form-control form-control-sm" id="singlecampaignid" name="singlecampaignid" onChange="singleCampaign($(this))">
                                        <option value="">Select Campaign</option>
                                        @foreach($campaigns as $campaign)
                                            <option value="{!! $campaign->t_id !!}">{!! $campaign->t_name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7"></div>
                    </div>
                    <div class="row">
                        <div id="singlecampevals" class="table-responsive m-t-5"></div>
                    </div>
                    <div class="row">
                        <div id="singlecampevald" class="table-responsive m-t-5"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.c-btn').html('');

        ACFn.ajax_single_campaign = function (F, R) {
            if(R.success){
                $('#singlecampevals').html(R.eEvalSumHtml);
                $('#singlecampevald').html(R.eEvalDetailHtml);
            }else{
                $('#singlecampevals').html('');
                $('#singlecampevald').html('');
            }
        }
    })

    function singleCampaign(obj) {
        if(obj.val() != ""){
            ACFn.sendAjax('campaign/single','get',{
                t_id : obj.val()
            })
        }else{
            $('#singlecampevals').html('');
            $('#singlecampevald').html('');
        }
    }
</script>
