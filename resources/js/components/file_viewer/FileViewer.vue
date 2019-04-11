<template>
    <div>
        <div v-show="loader" class="loader"></div>
        <div v-show="!loader">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <button @click="download" class="btn btn-info btn-sm">
                    <i class="fa fa-download"></i> Download
                </button>
                <a :href="'/file/upload/'+container.get_dir_id" class="btn btn-warning btn-sm">
                    <i class="fa fa-upload"></i> Upload
                </a>
                <button class="btn btn-danger btn-sm">
                    <i class="fa fa-trash-o"></i> Delete
                </button>
                <button @click="showMoveModal('move')" class="btn btn-primary btn-sm">
                    <i class="fa fa-arrows-alt"></i> Move
                </button>
                <button @click="showMoveModal('copy')" class="btn btn-success btn-sm">
                    <i class="fa fa-copy"></i> Copy
                </button>
                <button class="btn btn-warning btn-sm">
                    <i class="fa fa-refresh"></i> Refresh
                </button>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Files Container | Directory: {{ container.get_dir_name }}
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
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody v-if="response_error==='null'">
                                    <tr v-for="file in file_list">
                                        <td>
                                            <div class="checkbox3 checkbox-danger checkbox-inline checkbox-check  checkbox-circle checkbox-light">
                                                <input type="checkbox" :id="'checkbox-fa-light-'+file.id+'-file'"
                                                       :value="file.id" v-model="move_files.selected_files">
                                                <label :for="'checkbox-fa-light-'+file.id+'-file'">
                                                </label>
                                            </div>
                                        </td>
                                        <td v-if="file.file_type==='Images'">
                                            <img class="img-circle img-rounded img-size" :src="'/storage/'+file.file_path">
                                        </td>
                                        <td v-if="file.file_type==='Documents'">
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
                                        <td>
                                            <button @click="showEditModal(file.id,file.file_name)" class="bt btn-info btn-xs"><i class="fa fa-edit"></i></button>
                                            <button @click="downloadSingleFile(file.id)" class="bt btn-info btn-xs"><i class="fa fa-download"></i></button>
                                            <button class="bt btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
                                        </td>
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
            <!-- Move Modal Box -->
            <div :class="'modal fade'+modal.moveModalIn" id="move_folder" tabindex="-1" role="dialog" :style="modal.moveModalStyle">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" @click="closeMoveModal" aria-hidden="true">×</button>
                            <h4 v-if="move_files.mode==='move'" class="modal-title">Move Folder</h4>
                            <h4 v-else class="modal-title">Copy Folder</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div v-if="move_files.mode==='move'" class="col-md-12 col-sm-12 col-xs-12">
                                    <label class="alert alert-danger">Move To > {{ move_files.dir }}</label>
                                    <label class="alert alert-info">Path > {{ move_files.dir_path }}</label>
                                </div>
                                <div v-else class="col-md-12 col-sm-12 col-xs-12">
                                    <label class="alert alert-danger">Copy To > {{ move_files.dir }}</label>
                                    <label class="alert alert-info">Path > {{ move_files.dir_path }}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            Root Folders
                                        </div>
                                        <div class="panel-body">
                                            <div class="list-group">
                                                <a v-for="root_dir in root_dir_list" href="#"
                                                   @click="getDirectory(root_dir.id,root_dir.dir_name,root_dir.dir_path)"
                                                   class="list-group-item">
                                                    <i class="fa fa-fw fa-folder-o"></i> {{ root_dir.dir_name }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            Sub Folders
                                        </div>
                                        <div class="panel-body">
                                            <div v-show="small_loader" class="small_loader"></div>
                                            <table v-show="!small_loader" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Dir Name</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="sub_dir in sub_dir_list">
                                                    <td>
                                                        <a href="#" @click="getSubDirectory(sub_dir.id,sub_dir.sub_dir,sub_dir.dir_path)" >
                                                            <span class="icon text-center">
                                                                <i class="fa fa-folder-o fa-2x"></i> {{ sub_dir.sub_dir }}
                                                            </span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" @click="closeMoveModal">Close</button>
                            <button type="button" class="btn btn-primary" @click="handleFiles">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal -->
            <!-- Edit File Modal Box -->
            <div :class="'modal fade'+modal.editModalIn" id="add_folder" tabindex="-1" role="dialog" :style="modal.editModalStyle">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" @click="closeEditModal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">Rename File Name</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>File Name</label>
                                <div class="form-line">
                                    <input v-model="files.file_name" type="text" name="file_name" class="form-control" placeholder="Rename File Name..">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" @click="closeEditModal">Close</button>
                            <button type="button" class="btn btn-primary" @click="renameFiles" >Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Edit File Modal -->
        </div>
    </div>
</template>

<script>
    export default {
        name: "FileViewerInterface",
        data(){
            return{
                loader:true,
                file_list:{},
                modal:{
                    moveModalStyle:"display: none;",
                    moveModalIn:"",
                    editModalStyle:"display:none",
                    editModalIn:""
                },
                response_error:'null',
                small_loader:true,
                move_files:{
                    mode:"",
                    dir_id:0,
                    dir:'',
                    dir_path:'',
                    selected_files:[]
                },
                root_dir_list:{},
                sub_dir_list:{},
                files:{
                    file_id:0,
                    file_name:''
                }
            }
        },
        props:[
            'container',
            'change_dir'
        ],
        mounted(){
           //console.log(this.container)
            this.getFiles();
            this.getRootDir();
        },
        methods:{
            getFiles(){
                this.loader = false;
                axios.get('/get/dir/files/'+this.container.get_dir_id)
                    .then((response) =>
                        {
                            if (response.data.response==="success"){
                                this.file_list = response.data.data;
                                //console.log(response.data.data)
                            } else{
                                this.response_error = response.data.data
                            }
                            this.loader = false;
                            console.log(response.data.data)
                        }
                    ).catch((error) =>
                        console.log(error)
                    );
                //console.log("hello "+this.container.get_dir_id);
            },
            getRootDir(){
                axios.get('/get/root/dir/')
                    .then((response) => {
                        this.root_dir_list = response.data;
                        //console.log(this.root_dir_list)
                    })
                    .catch((error) =>
                        console.log(error)
                    );
            },
            getDirectory(root_dir_id,dir,dir_path){
                this.small_loader = true;
                this.move_files.dir = dir;
                this.move_files.dir_path = dir_path;
                this.move_files.dir_id = 0;
                axios.get('/get/dir/'+root_dir_id)
                    .then((response) => {
                        this.small_loader = false;
                        this.sub_dir_list = response.data;
                    })
                    .catch((error) =>
                        console.log(error)
                    );
            },
            getSubDirectory(id,dir,dir_path){
                this.small_loader = true;
                this.move_files.dir = dir;
                this.move_files.dir_path = dir_path;
                this.move_files.dir_id = id;
                axios.get('/get/sub/dir/'+id)
                    .then((response) => {
                        this.small_loader = false;
                        if (response.data.length===0){
                            alert('no dir')
                        } else{
                            this.sub_dir_list = response.data;
                        }
                        //console.log(response.data)
                    })
                    .catch((error) =>
                        console.log(error)
                    );
            },
            handleFiles(){
                let data = {
                    move_dir_id:this.move_files.dir_id,
                    selected_files:this.move_files.selected_files,
                    mode:this.move_files.mode
                };
                if (this.move_files.selected_files.length>0){
                    let url = '';
                    this.move_files.mode==='move'?url='/move/files':url='/copy/files';
                    axios.post(url,data)
                        .then((response) => {
                            console.log(response.data)
                        })
                        .catch((error) =>
                            console.log(error)
                        );
                }else{
                    alert('No Dir Selected To Move');
                }
            },
            renameFiles(){
                let data = {
                    file_id:this.files.file_id,
                    file_name:this.files.file_name
                };
                if (this.files.file_name!==''){
                    axios.post('/rename/files',data)
                        .then((response) => {
                           console.log(response.data)
                        }).catch((error)=>{
                            console.log(error)
                        });
                }
            },
            download(){
                let data = {
                    selected_files:this.move_files.selected_files,
                };
                if (this.move_files.selected_files.length>0){
                    axios.post('/download/as/zip',data)
                        .then((response) => {
                            console.log(response.data)
                        })
                        .catch((error) =>
                            console.log(error)
                        );
                }else{
                    alert('No File Selected');
                }
            },
            downloadSingleFile(id){
               axios.get('/download/'+id)
                   .then((response)=>{
                       console.log(response.data)
                   })
                   .catch((error)=>{
                       console.log(error)
                   });
            },
            showMoveModal(val){
                this.move_files.mode = val;
                this.modal.moveModalIn="in";
                this.modal.moveModalStyle="display: block;"
            },
            closeMoveModal(){
                this.sub_dir_list={};
                this.small_loader=true;
                this.move_files.dir_id = 0;
                this.move_files.mode = "";
                this.modal.moveModalIn="";
                this.modal.moveModalStyle="display: none;"
            },
            showEditModal(id,name){
                this.files.file_id = id;
                this.files.file_name = name;
                this.modal.editModalIn="in";
                this.modal.editModalStyle="display: block;"
            },
            closeEditModal(){
                this.files.file_id = 0;
                this.files.file_name = '';
                this.modal.editModalIn="";
                this.modal.editModalStyle="display: none;"
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
