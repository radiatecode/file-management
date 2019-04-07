@extends('master')
@section("stylesheets")
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
    </style>
@endsection
@section("page_title")
@endsection
@section("breadcrumb")

@endsection
@section("container")
    <div id="app">
        <div class="row">
            <div class="col-md-3 col-sm-12 col-xs-12">
                <a href="{{ url('file/types') }}" class="btn btn-success btn-sm"><i class="fa fa-plus-circle"></i> Add Root</a>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Root Folders
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            @foreach($root_dir as $value)
                            <a href="#" @click="show('{{ $value->id }}','{{ $value->dir_name }}','{{ $value->dir_path }}')" class="list-group-item">
                                <i class="fa fa-fw fa-folder-o"></i> {{ $value->dir_name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-9 col-sm-12 col-xs-12">
                {{--directory component--}}
                <Directories v-if="dir_id" :directory="dir_name" :root_dir_id="dir_id" :dir_path="dir_path"></Directories>
            </div>
        </div>

        <div class="row">
            <file_viewer v-if="container.get_dir_path" :container="pass_dir" :change_dir="container.get_dir_path"></file_viewer>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        const app = new Vue({
            el: '#app',
            data:{
                dir_id:'',
                dir_name:'',
                dir_path:'',
                container:{
                    get_dir_path:null,
                    get_dir_name:null,
                    get_dir_id:0
                }
            },
            methods:{
                show(dir_id,dir_name,dir_path){
                    this.dir_id = dir_id;
                    this.dir_name = dir_name;
                    this.dir_path = dir_path;
                }
            },
            computed:{
                pass_dir:function(){
                   return this.container;
                }
            }
        });
    </script>
@endsection
