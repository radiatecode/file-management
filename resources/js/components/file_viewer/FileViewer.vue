<template>
    <div>
        <div v-show="loader" class="loader"></div>
        <div v-show="!loader">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <button class="btn btn-info btn-sm">
                    <i class="fa fa-download"></i> Download
                </button>
                <a :href="'/file/upload/'+container.get_dir_id" class="btn btn-warning btn-sm">
                    <i class="fa fa-upload"></i> Upload
                </a>
                <button class="btn btn-danger btn-sm">
                    <i class="fa fa-trash-o"></i> Delete
                </button>
                <button class="btn btn-primary btn-sm">
                    <i class="fa fa-arrows-alt"></i> Move
                </button>
                <button class="btn btn-success btn-sm">
                    <i class="fa fa-copy"></i> Copy
                </button>
                <button class="btn btn-success btn-sm">
                    <i class="fa fa-copy"></i> Rename
                </button>
                <button class="btn btn-warning btn-sm">
                    <i class="fa fa-refresh"></i> Refresh
                </button>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Files Container {{ container.get_dir_name }}
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTables-example"
                                   class="table table-striped table-bordered table-hover dataTable no-footer"
                                   aria-describedby="dataTables-example_info">
                                <thead>
                                <tr>
                                    <th style="width: 15px">
                                        <div class="checkbox3 checkbox-success checkbox-inline checkbox-check checkbox-round checkbox-light">
                                            <input type="checkbox" id="checkbox-fa-light-2" checked="">
                                            <label for="checkbox-fa-light-2">

                                            </label>
                                        </div>
                                        <br>
                                    </th>
                                    <th  colspan="2">Name</th>
                                    <th>Extension</th>
                                    <th>Size</th>
                                    <th>Created Date</th>
                                </tr>
                                </thead>
                                <tbody v-if="response_error==='null'">
                                    <tr v-for="file in file_list">
                                        <td>
                                            <div class="checkbox3 checkbox-danger checkbox-inline checkbox-check  checkbox-circle checkbox-light">
                                                <input type="checkbox" id="checkbox-fa-light-3" checked="">
                                                <label for="checkbox-fa-light-3">
                                                </label>
                                            </div>
                                        </td>
                                        <td v-if="file.file_extension==='JPG'">
                                            <img class="img-circle img-rounded img-size" :src="'/storage/'+file.file_path">
                                        </td>
                                        <td v-if="file.file_extension==='DOCX'">
                                            <span class="icon text-center">
                                                <i class="fa fa-file-o fa-2x"></i>
                                            </span>
                                        </td>
                                        <td>
                                            {{ file.file_name }}
                                        </td>
                                        <td>{{ file.file_extension }}</td>
                                        <td>555kb</td>
                                        <td>{{ file.created_at }}</td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr >
                                        <td colspan="6"> {{ response_error }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "FileViewer",
        data(){
            return{
                loader:true,
                file_list:{},
                response_error:'null'
            }
        },
        props:[
            'container',
            'change_dir'
        ],
        mounted(){
           //console.log(this.container)
            this.getFiles();
        },
        methods:{
            getFiles(){
                this.loader = false;
                axios.get('/get/dir/files/'+this.container.get_dir_id)
                    .then((response) =>
                        {
                            if (response.data.response==="success"){
                                this.file_list = response.data.data;
                            } else{
                                this.response_error = response.data.data
                            }
                            this.loader = false;
                            console.log(response.data.data)
                        }
                    )
                    .catch((error) =>
                        console.log(error)
                    );
                //console.log("hello "+this.container.get_dir_id);
            }
        },
        watch:{
            change_dir:function () {
                this.getFiles();
            }
        }
    }
</script>

<style scoped>
  .img-size{
      width: 40px;
      height: 40px;
  }
</style>
