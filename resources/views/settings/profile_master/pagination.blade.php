@if(count($profiles) > 0)
    <table id="basic_table2" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($profiles as $profile)
            <tr>
                <td><a class="ajax-Link" href="/profile/permission/{{Crypt::encrypt($profile->profile_id)}}">{!! $profile->profile_name !!}</a></td>
                <td>
                    @if($profile->is_active)
                        <a href="/profile/changestatus/{{ Crypt::encrypt($profile->profile_id) }}" class="ajax-Link"><i class="fas fa-check"></i> </a>
                    @else
                        <a href="/profile/changestatus/{{ Crypt::encrypt($profile->profile_id) }}" class="ajax-Link"><i class="fas fa-times"></i> </a>
                    @endif
                </td>
                <td>
                    <a href="/settings/addprofile/{{ Crypt::encrypt($profile->profile_id) }}" class="ajax-Link"><i class="far fa-edit"></i> </a>
                    <a data-title="Are you sure want to delete this profile ?" data-confirm="true" href="/profile/delete/{{ Crypt::encrypt($profile->profile_id) }}" class="ajax-Link"><i class="fas fa-trash"></i> </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else

@endif