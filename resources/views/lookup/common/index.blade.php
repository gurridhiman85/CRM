 @if($part == 'top-header')
 {{--
	<div class="row page-titles p-t-15 p-r-10 pb-2">
		<div class="col-md-12 align-self-center">
			<div class="row pt-2">
				<div class="col-md-1">
					<h6 class="text-themecolor" style="color: #3ea6d0;font-weight: 500;">Phone</h6>
				</div>
				<div class="col-md-11 ">
					<div class="row">

						<div class="col-md-4 font-11">
							<span id="HouseHoldNameLabel" class="secondscreen" style="display: none;">Extended Name :</span>
						</div>

						<div class="col-md-4 font-11">
							<span id="OverallLabelSegment" class="secondscreen" style="display: none;">ZSS Segment :</span>
						</div>

						<div class="col-md-3 font-11">
							<span id="LifeCycleLabelSegment" class="secondscreen" style="display: none;">Life Cycle Segment :</span>
						</div>

						<div class="col-md-1 pl-3 pr-2">
							<button type="button" class="btn border-0 s-f asBtn" id="customCheck" style="display: none;padding-top: .15rem !important;" title="Auto Save">
								<div class="custom-control custom-switch pull-right">
									<input type="checkbox" alt="AutoSave" value="1" class="custom-control-input " id="customSwitch1">
									<label class="custom-control-label" for="customSwitch1"></label>
								</div>
							</button>

							<a class="pull-right font-18 mr-3 asBtn" style="display: none;" id="backBtnSecond" onclick="goBack();" href="javascript:void(0);" title="Go Back">
								<i class="fas fa-arrow-circle-up ds-c"></i>
							</a>
						</div>
					</div>
				</div>
			</div>

		</div>


	</div>--}}
@elseif($part == 'filters-heading-wd-buttons')
	<div class="row firstscreen">
		<div class="col-lg-2 pt-3">
			<h5>Filters</h5>
		</div>
		<div class="col-lg-4">
			<div class="all-pagination pt-3"></div>
			<div class="after-filter mt-1"></div>
		</div>
		<div class="col-lg-6">
			<div class="btn-toolbar pull-right" role="toolbar" aria-label="Toolbar with button groups">
				<div class="input-group">
					<input type="text" id="filtersearch" class="form-control ajax-search" placeholder="Search" aria-label="Input group example" aria-describedby="btnGroupAddon" style="width: 430px;">
					<div class="input-group-append">
						<div class="input-group-text border-right-0 border-left-0" title="Search" id="btnGroupAddon" onclick="$('.ajax-search').trigger('keyup');">
							<i class="fas fa-search ds-c"></i>
						</div>
					</div>
				</div>

				<div class="btn-group" role="group" aria-label="First group">
					{{--<button type="button" title="Filters" class="btn btn-light border-right-0 no-border p-r-2" data-toggle="modal" data-target="#filtersModel"><i class="fas fa-filter ds-c"></i></button>--}}
					<button type="button" title="Clear Filters" class="btn btn-light font-16 clear-btn border-right-0 no-border p-r-2" onclick="clearFilters()"><i class="mdi mdi-filter-remove ds-c"></i></button>
					<button type="button" class="btn btn-light no-border border-right-0 font-12" id="refreshBtn" onclick="refreshMergeList()" title="Refresh" style="float: right;box-shadow: none;display: none;"><i class="fas fa-sync-alt ds-c" ></i></button>
					<div class="c-btn" style="display: none;"></div>
					<!--
					<button type="button" title="Merge" class="btn btn-light border-right-0 d-none d-lg-block font-12" style="float: right;box-shadow: none;" onclick="doMerge();"><i class="fas fa-boxes ds-c"></i></button>
					-->
					@if($option == 'lookup')

					<button type="button" title="Campaign" class="btn btn-light border-right-0 d-none d-lg-block font-12" style="float: right;box-shadow: none;" onclick="showCreateCampaign()"><i class="fas fa-phone ds-c"></i></button>
						<div class="btn-group">
							<button type="button" title="Merge" class="btn btn-light no-border dropdown-toggle font-12" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="float: right;box-shadow: none;"><i class="fas fa-boxes ds-c"></i></button>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="javascript:void(0)" onclick="doMerge();">Manual Merge</a>
								<a class="dropdown-item ajax-Link" href="javascript:void(0)" data-href="lookup/finddupes/tight">Find Duplicates - Tight Match</a>
								<a class="dropdown-item ajax-Link" href="javascript:void(0)" data-href="lookup/finddupes/loose">Find Duplicates - Loose Match</a>
								<a class="dropdown-item ajax-Link" href="javascript:void(0)" data-href="lookup/finddupes/newtight">Find Duplicates - Tight Match - New Records</a>
								<a class="dropdown-item ajax-Link" href="javascript:void(0)" data-href="lookup/finddupes/newloose">Find Duplicates - Loose Match - New Records</a>

							</div>
						</div>
					@elseif($option == 'phone')
						<div class="btn-group">
							<button type="button" title="Phone Campaign" data-href="phone/campaign" class="btn btn-light no-border font-16 ajax-Link" aria-expanded="false" style="float: right;box-shadow: none;"><i class="fas fa-dollar-sign ds-c"></i></button>

							<button type="button" title="Report download" class="btn btn-light no-border dropdown-toggle font-12" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="float: right;box-shadow: none;"><i class="ti-bar-chart font-weight-bold font-16 ds-c"></i></button>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="javascript:void(0)" onclick="implementReportWithPhone('report','{!! $report_row_id !!}');"><i class="fas fa-file-pdf" style="color: #e92639;"></i> PDF Report</a>
								<a class="dropdown-item" href="javascript:void(0)" onclick="implementReportWithPhone('list','{!! $report_row_id !!}');"><i class="fas fa-file-excel" style="color: #06b489;"></i> Xlsx Report</a>
							</div>
						</div>
					@endif
					<button type="button" title="Download" class="btn btn-light no-border font-12" onclick="downloadCMLink($(this))" data-href="lookup/downloadreport" data-prefix="{{$option}}" data-screen="first" aria-expanded="false" style="float: right;box-shadow: none;"><i class="fas fa-download ds-c"></i></button>
					@if($option == 'phone')
						<button type="button" title="Add to Phone" class="btn btn-light font-12 ajax-Link" data-href="phone/add" aria-expanded="false" style="float: right;box-shadow: none;"><i class="fas fa-plus ds-c"></i></button>
					@endif
				</div>

			</div>
		</div>
	</div>

@endif
