 @if($part == 'top-header')

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
					<button type="button" title="Download" class="btn btn-light no-border font-12" onclick="downloadCMLink($(this))" data-href="activity/downloadreport" data-prefix="{{$option}}" data-screen="first" aria-expanded="false" style="float: right;box-shadow: none;"><i class="fas fa-download ds-c"></i></button>
				</div>

			</div>
		</div>
	</div>

@endif
