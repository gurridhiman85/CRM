<table id="basic_table2" class="table table-bordered table-hover color-table lkp-table" style="width: 100%;" data-message="No campaign available">
    <thead>
        <tr>
            {{--<th class="text-center">#</th>--}}
            <th>Campaign ID</th>
            <th>Campaign Name</th>
            <th>Template</th>
            <th class="text-center">Time</th>
            <th class="text-center">StartDate</th>
            <th class="text-center">EndDate</th>
            <th>Subject1</th>
            <th>Subject2</th>
            <th>Subject3</th>
            <th>Test Subject</th>
            <th>CampaignID_Data</th>
            <th>TestSubjectPct</th>
            <th>SubjectWin</th>
        </tr>
    </thead>
    <tbody>
        @foreach($records as $record)
            @include('email.tabs.completed.table-single-row')
        @endforeach
    </tbody>
</table>


