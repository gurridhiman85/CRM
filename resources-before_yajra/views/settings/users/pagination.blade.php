@if(count($users) > 0)
    <table id="basic_table" class="table table-bordered table-striped" style="width: 100% !important;">
        <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Profile</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{!! $user->first_name !!}</td>
                <td>{!! $user->last_name !!}</td>
                <td>{!! $user->email !!}</td>
                <td>{!! $user->profile->profile_name !!}</td>
                <td>
                    @if($user->is_active)
                        <a href="/users/changestatus/{{ Crypt::encrypt($user->u_dataid) }}" class="ajax-Link"><i class="fas fa-check"></i> </a>
                    @else
                        <a href="/users/changestatus/{{ Crypt::encrypt($user->u_dataid) }}" class="ajax-Link"><i class="fas fa-times"></i> </a>
                    @endif
                </td>
                <td>
                    <a href="/settings/adduser/{{ Crypt::encrypt($user->u_dataid) }}" class="ajax-Link"><i class="far fa-edit"></i> </a>
                    <a data-title="Are you sure want to delete this user ?" data-confirm="true" href="/user/delete/{{ Crypt::encrypt($user->u_dataid) }}" class="ajax-Link"><i class="fas fa-trash"></i> </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else

@endif