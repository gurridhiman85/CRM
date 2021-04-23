@if(count($designations) > 0)
    <table id="basic_table2" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($designations as $designation)
            <tr>
                <td>{!! $designation->designation_name !!}</td>
                <td>
                    @if($designation->status)
                        <a href="/designation/changestatus/{{ Crypt::encrypt($designation->id) }}" class="ajax-Link"><i class="fas fa-check"></i> </a>
                    @else
                        <a href="/designation/changestatus/{{ Crypt::encrypt($designation->id) }}" class="ajax-Link"><i class="fas fa-times"></i> </a>
                    @endif
                </td>
                <td>
                    <a href="/settings/adddesignation/{{ Crypt::encrypt($designation->id) }}" class="ajax-Link"><i class="far fa-edit"></i> </a>
                    <a data-title="Are you sure want to delete this profile ?" data-confirm="true" href="/designation/delete/{{ Crypt::encrypt($designation->id) }}" class="ajax-Link"><i class="fas fa-trash"></i> </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else

@endif