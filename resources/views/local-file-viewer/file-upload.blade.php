@extends('master')
@section("stylesheets")
    <!-- Custom styles -->
    <link href="{{ asset('js/dm-uploader/css/jquery.dm-uploader.min.css') }}" rel="stylesheet">
    <style>
        .text-muted {
            color: #868e96!important;
        }
        .row {
            margin-bottom: 1rem;
        }
        [class*="col-"] {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        #files {
            height: 319px;
            overflow: auto;
        }
        @media (min-width: 768px) {
            #files {
                min-height: 0;
            }
        }
        hr {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        .dm-uploader {
            border: 0.25rem dashed #A5A5C7;
            text-align: center;
        }
        .dm-uploader.active {
            border-color: red;

            border-style: solid;
        }
        div#drag-and-drop-zone {
            height: 326px;
        }

        .p-5 {
            padding: 8rem!important;
        }
        .mb-5, .my-5 {
            margin-bottom: 3rem!important;
        }
        .p-2 {
            padding: .5rem!important;
        }
        .col {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            max-width: 100%;
        }
        .h-100 {
            height: 100%!important;
        }
        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }
        .media {
            margin-bottom: -30px;
        }
    </style>
@endsection
@section("page_title")
@endsection
@section("breadcrumb")

@endsection
@section("container")
    @component('components.message')@endcomponent
    <div class="row">
        <div class="col-md-6 col-lg-6 col-xs-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="card-title">
                        <div class="title">File Upload</div>
                    </div>
                </div>
                <div class="panel-body">
                    <!-- Our markup, the important part here! -->
                    <div id="drag-and-drop-zone" class="dm-uploader p-5">
                        <h3 class="mb-5 mt-5 text-muted">Drag &amp; drop files here</h3>

                        <div class="btn btn-primary btn-block mb-5">
                            <span>Open the file Browser</span>
                            <input type="file" title='Click to add Files' />
                        </div>
                    </div><!-- /uploader -->
                </div>
                <div class="panel-footer">
                    <button type="button" id="btnApiStart" class="btn btn-sm btn-success"><i class="fa fa-play"></i> Start Upload</button>
                    <button type="button" id="btnApiCancel" class="btn btn-sm btn-danger"><i class="fa fa-stop"></i> Cancel Upload</button>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-xs-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    Upload Progress
                </div>

                <div class="panel-body">
                    <div class="card h-100">
                        <ul class="list-unstyled p-2 d-flex flex-column col" id="files">
                            <li class="text-muted text-center empty">No files uploaded.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var the_url = "{{ route('file.upload') }}";
        var dir_path = "{{ $dir_path }}";
        var dir_id = "{{ $dir_id }}";
    </script>
    <script src="{{ asset('js/dm-uploader/js/jquery.dm-uploader.min.js')}}"></script>
    <script src="{{ asset('js/pages-js/uploader/ui.js')}}"></script>
    <script src="{{ asset('js/pages-js/uploader/config.js')}}"></script>
    <!-- File item template -->
    <script type="text/html" id="files-template">
        <li class="media">
            <div class="media-body mb-1">
                <p class="mb-2">
                    <strong>%%filename%%</strong> - Status: <span class="text-muted">Waiting</span>
                </p>
                <div class="progress mb-2">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                         role="progressbar"
                         style="width: 0%"
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <hr class="mt-1 mb-1" />
            </div>
        </li>
    </script>
    <script>
        $(document).ready(function () {

        });
    </script>
@endsection
