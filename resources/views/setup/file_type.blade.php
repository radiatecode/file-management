@extends('master')
@section("stylesheets")
    <link href="{{ asset('js/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet" />
    <style>
    </style>
@endsection
@section("page_title")
@endsection
@section("breadcrumb")

@endsection
@section("container")
        @component('components.message')@endcomponent
        <div class="panel panel-default">
            <div class="panel-heading">
               File Types
            </div>
            <div class="panel-body">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <button type="button" data-toggle="modal" data-target="#add" class="btn btn-success btn-sm">
                        <i class="fa fa-plus-circle"></i> Add
                    </button>
                    <button class="btn btn-danger btn-sm">
                        <i class="fa fa-trash-o"></i> Delete
                    </button>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <table id="file_types" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px">
                                <div class="checkbox3 checkbox-success checkbox-inline checkbox-check checkbox-round  checkbox-light">
                                    <input type="checkbox" id="checkbox-fa-light-2" checked="">
                                    <label for="checkbox-fa-light-2">

                                    </label>
                                </div>
                                <br>
                            </th>
                            <th>Dir</th>
                            <th>Type</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
        <!-- Modal Box -->
        <div class="modal fade in" id="add" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Root File Directories</h4>
                    </div>
                    <form action="{{ route('add.file.type') }}" method="post">
                        <div class="modal-body">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <label for="password_2">File Type</label>
                                    <div class="form-line">
                                        <input type="text" name="file_type" class="form-control" placeholder="Enter File Type..">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password_2">Directory Name</label>
                                    <div class="form-line">
                                        <input type="text" name="dir_name" class="form-control" placeholder="Enter Directory name..">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password_2">Icon</label>
                                    <div class="form-line">
                                        <input type="text" name="icon" class="form-control" placeholder="Enter Bootstrap Icon Class..">
                                    </div>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Modal -->
@endsection
@section('script')
    <!-- DATA TABLE SCRIPTS -->
    <script src="{{ asset('js/dataTables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('js/dataTables/dataTables.bootstrap.js') }}"></script>
    <script>
        $(document).ready(function () {
            let otable = $('#file_types').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! route('ssr.file.type') !!}",
                columns: [
                    {data:'hash',name:'hash'},
                    {data:'dir_name',name:'dir_name'},
                    {data:'file_type',name:'file_type'},
                    {data:'created_at',name:'created_at'},
                    {data:'action',name:'action'}
                ]
            });
        });
    </script>
@endsection
