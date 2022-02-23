@if(count($departments) > 0)
    <table id="basic_table2" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($departments as $department)
            <tr>
                <td>{!! $department->department_name !!}</td>
                <td>
                    @if($department->status)
                        <a href="/department/changestatus/{{ Crypt::encrypt($department->id) }}" class="ajax-Link"><i class="fas fa-check"></i> </a>
                    @else
                        <a href="/department/changestatus/{{ Crypt::encrypt($department->id) }}" class="ajax-Link"><i class="fas fa-times"></i> </a>
                    @endif
                </td>
                <td>
                    <a href="/settings/adddepartment/{{ Crypt::encrypt($department->id) }}" class="ajax-Link"><i class="far fa-edit"></i> </a>
                    <a data-title="Are you sure want to delete this profile ?" data-confirm="true" href="/department/delete/{{ Crypt::encrypt($department->id) }}" class="ajax-Link"><i class="fas fa-trash"></i> </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else

@endif