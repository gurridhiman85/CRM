<form action="lookup/bulkmerge" class="ajax-Form" method="post" id="bulkMergeForm">
    {!! csrf_field() !!}
    <div class="row">
    <div class="col-md-4 pl-0">
        <div class="form-group">
            <div class="drop-file">
                <input id="docs-input-files" name="files[]" type="file" multiple>
            </div>
        </div>
    </div>
</div>
</form>