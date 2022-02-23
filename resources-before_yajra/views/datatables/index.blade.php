@extends('layouts.docker-horizontal')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered" id="users-table">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Landing Page</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

        <script>
            $(function() {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    paging: false,
                    ajax: '{!! route('datatables.data') !!}',
                    columns: [
                        { data: 'User_ID', name: 'User_ID' },
                        { data: 'User_FName', name: 'User_FName' },
                        { data: 'authenticate.LandingPage', name: 'LandingPage', sortable : false },
                        { data: 'User_Email', name: 'User_Email' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'updated_at', name: 'updated_at' }
                    ]
                });
            });
        </script>

@stop


