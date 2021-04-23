@if($section == 'view')
    @extends('layouts.docker-horizontal')
    @section('content')
        <div class="container-fluid">
            <div class="row page-titles p-t-15 p-r-10 pb-2">
                <div class="col-md-12 align-self-center">
                    <div class="row pt-2">
                        <div class="col-md-12">
                            <h6 class="text-themecolor" style="color: #3ea6d0;font-weight: 500;">SQL</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="sqlinterface" class="ajax-Form">
                            {!! csrf_field() !!}
                            <textarea class="form-control" cols="50" rows="5" name="sqlquery"></textarea>
                            <button type="submit" class="btn btn-info" name="submit">Execute</button>
                        </form>

                        <div class="row" id="resultTable">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @stop
@elseif($section == 'result')
    <div id="sql_tab" class="table-responsive m-t-5" >
        @include('sql.table')
    </div>
@endif
