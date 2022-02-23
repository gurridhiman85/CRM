<form id="emailForm" action="email/sendemail" class="ajax-Form">
    @csrf
    <div class="form-body pl-3 pt-3 pb-0 pr-0">
        @include('email.tabs.new.inner-tab-content')
    </div>
</form>

{{--<div class="col-md-12">
    <div class="row">
        <div class="col-md-12" id="responseHtml" style="padding-left: 0px; display:none;">
            --}}{{--<div class="tab">
                <button class="tablinks active" id="resulttab" onclick="changeTab(event, 'Result')">
                    Results
                </button>
                <button class="tablinks" id="messagetab" onclick="changeTab(event, 'Message')">Messages
                </button>
                <button class="tablinks" id="querytab" onclick="changeTab(event, 'Queries')">SQL Query
                </button>
            </div>
            <div id="Result" class="tabcontent" style="display: block !important;padding-left: 0px;"></div>
            <div id="Message" class="tabcontent"></div>
            <div id="Queries" class="tabcontent"></div>--}}{{--

            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#Resulttab" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Results</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#Messagetab" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Messages</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#Queriestab" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">SQL Query</span></a>
                </li>
            </ul>

            <div class="tab-content tabcontent-border">
                <div class="tab-pane active" id="Resulttab" role="tabpanel">
                    <div class="overflow-auto" id="Result"></div>
                </div>
                <div class="tab-pane" id="Messagetab" role="tabpanel">
                    <div class="overflow-auto" id="Message"></div>
                </div>
                <div class="tab-pane" id="Queriestab" role="tabpanel">
                    <div class="overflow-auto" id="Queries"></div>
                </div>
            </div>
        </div>
    </div>
</div>--}}

@if($rType == 'pagination')
    @include('email.tabs.completed.table',[
        //'records' => $records,
        'tabName' => $tabName,
        'sort_column' => $sort_column,
        'sort_dir' => $sort_dir,
    ])
@else
    @include('email.tabs.completed.index',[
        //'records' => $records,
        'tabName' => $tabName,
        'sort_column' => $sort_column,
        'sort_dir' => $sort_dir,
    ])
@endif

<script>
    $(document).ready(function () {
        //$('.close-email-btn').show();
        //$('.open-email-btn').hide();

        @if($tabid == 'ECinsert')
            decideSection('ECinsert',['Schedule','Re-ReDeploy','ECupdate','Proofs','deploy','TFD','ReDeploy','CR','PR','TR'],$('[data-tabid="ECinsert"]'));

        @elseif($tabid == 'Proofs')
            decideSection('Proofs',['Schedule','Re-ReDeploy','ECinsert','ECupdate','deploy','TFD','ReDeploy','PR','CR','TR','htmlid'],$('[data-tabid="Proofs"]'));

        @elseif($tabid == 'deploy')
            decideSection('deploy',['Schedule','Re-ReDeploy','ECinsert','ECupdate','Proofs','TFD','ReDeploy','PR','CR','TR','htmlid'],$('[data-tabid="deploy"]'));

        @elseif($tabid == 'TFD')
            decideSection('TFD',['Schedule','Re-ReDeploy','ECinsert','ECupdate','Proofs','deploy','ReDeploy','PR','CR','TR','htmlid'],$('[data-tabid="TFD"]'));

        @elseif($tabid == 'ReDeploy')
            decideSection('ReDeploy',['Schedule','Re-ReDeploy','ECinsert','ECupdate','Proofs','TFD','deploy','PR','TR','CR','htmlid'],$('[data-tabid="ReDeploy"]'));

        @elseif($tabid == 'Re-ReDeploy')
            decideSection('Re-ReDeploy',['Schedule','ReDeploy','ECinsert','ECupdate','Proofs','TFD','deploy','PR','TR','CR','htmlid'],$('[data-tabid="Re-ReDeploy"]'));

        @elseif($tabid == 'PR')
            decideSection('PR',['Schedule','Re-ReDeploy','ECinsert','ECupdate','Proofs','TFD','deploy','ReDeploy','TR','CR','htmlid'],$('[data-tabid="PR"]'));
            addEmailReport(10,'process_report','','Progress Report','deploy_campaign_Re-ReDeploy');

        @elseif($tabid == 'CR')
            decideSection('CR',['Schedule','Re-ReDeploy','ECinsert','ECupdate','Proofs','TFD','deploy','ReDeploy','TR','PR','CR','htmlid'],$('[data-tabid="CR"]'));
            addEmailReport(11,'campaign_delivery_report2','','Campaign Report','process_report');

        @endif

        @if(in_array($tabid,['PR','CR']))
            $('#input_html_id').on('change', function () {
                @if($tabid == 'PR')
                    addEmailReport(10,'process_report','','Progress Report','deploy_campaign_Re-ReDeploy');
                @else
                    addEmailReport(11,'campaign_delivery_report2','','Campaign Report','process_report');
                @endif
            });
        @endif
    });
</script>
