<form action="lookup/bulkmerge" class="ajax-Form" method="post" id="bulkMergeForm">
    {!! csrf_field() !!}
    <input type="hidden" name="type" value="{!! $type !!}">
    <div id="duplicates" class="table-responsive m-t-2" >
        @if(count($records) > 0)
            @include('lookup.find-duplicate.table')
        @else
            <center><h4>No duplicate contacts were found using a {!! $type !!} matching process</h4></center>
        @endif
    </div>
</form>