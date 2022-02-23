<div class="form-group">
    <label class="control-label">Status</label>
    <select name="status" class="form-control form-control-sm" id="status-filter" multiple="multiple" data-placeholder="Select Values">
        <option class="font-12" value="Assigned">Assigned</option>
        <option class="font-12" value="Spoke on Phone">Spoke on Phone</option>
        <option class="font-12" value="Left Voicemail">Left Voicemail</option>
        <option class="font-12" value="Could not leave Voicemail">Could not leave Voicemail</option>
        <option class="font-12" value="Phone not in service">Phone not in service</option>
        <option class="font-12" value="Phone belongs to someone else">Phone belongs to someone else</option>
        <option class="font-12" value="Suppressed">Suppressed</option>
    </select>
</div>

<div class="form-group">
    <label class="control-label">Campaign</label>
	<select name="TouchCampaign" class="form-control form-control-sm" id="campaign-filter" multiple="multiple" data-placeholder="Select Values">
		@foreach($campaigns as $campaign)
			<option value="{!! $campaign !!}">{!! $campaign !!}</option>
		@endforeach

    </select>
</div>

<div class="form-group">
    <label class="control-label">Campaign Date</label>
    <input type="text" name="TouchDate" onkeyup="applyFilters();" class="form-control form-control-sm js-datepicker" placeholder="Enter search string" data-placeholder="">
</div>
