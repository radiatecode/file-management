<template>
    <div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <button type="button" @click="showFolderModal" class="btn btn-success btn-sm">
                    <i class="fa fa-plus-square"></i> Folder
                </button>
                <button class="btn btn-danger btn-sm">
                    <i class="fa fa-trash-o"></i> Delete
                </button>
                <button @click="showMoveModal" class="btn btn-primary btn-sm">
                    <i class="fa fa-arrows-alt"></i> Move
                </button>
                <button class="btn btn-primary btn-sm">
                    <i class="fa fa-arrows-alt"></i> Rename
                </button>
                <button class="btn btn-warning btn-sm">
                    <i class="fa fa-backward"></i> Back
                </button>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                Sub Directories of {{ prop_data.dir }}
            </div>
            <div class="panel-body">
                <div v-show="loader" class="loader"></div>
                <div v-show="!loader" class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
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
                            <th>Dir Name</th>
                            <th></th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="dir in getDirList">
                            <td>
                                <div class="checkbox3 checkbox-danger checkbox-inline checkbox-check  checkbox-circle checkbox-light">
                                    <input type="checkbox" :id="'checkbox-fa-light-'+dir.id"
                                           v-bind:value="dir.id" v-model="move_dir.selected_dir">
                                    <label :for="'checkbox-fa-light-'+dir.id">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <a href="#" @click="getSubDir(dir.id,dir.sub_dir,dir.dir_path)">
                                    <span class="icon text-center">
                                        <i class="fa fa-folder-o fa-2x"></i> {{ dir.sub_dir }}
                                    </span>
                                </a>
                            </td>
                            <td>
                                <i class="fa fa-folder fa-1x"></i>
                                <span class="badge">{{ dir.folders }}</span>
                                <i class="fa fa-files-o fa-1x"></i>
                                <span class="badge">{{ dir.files }}</span>
                            </td>
                            <td>{{ dir.created_at }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Add Folder Modal Box -->
        <div :class="'modal fade'+modal.folderModalIn" id="add_folder" tabindex="-1" role="dialog" :style="modal.folderModalStyle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeFolderModal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Add Sub Directories of {{ prop_data.dir }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Directory Name</label>
                            <div class="form-line">
                                <input v-model="dir_name" type="text" name="dir_name" class="form-control" placeholder="Enter Directory name..">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeFolderModal">Close</button>
                        <button type="button" class="btn btn-primary" @click="saveSubDir">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->
        <!-- Move Modal Box -->
        <div :class="'modal fade'+modal.moveModalIn" id="move_folder" tabindex="-1" role="dialog" :style="modal.moveModalStyle">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeMoveModal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Move Folder</h4>
                    </div>
                    <div class="modal-body">
                        <div v-show="move_dir.error" class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label class="alert alert-danger">{{ move_dir.error }} For {{ move_dir.dir }} Folder</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label class="alert alert-danger">Move To > {{ move_dir.dir }}</label>
                                <label class="alert alert-info">Path > {{ move_dir.dir_path }}</label>
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
                        <button type="button" class="btn btn-primary" @click="moveDir">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->
    </div>
</template>

<script>
    export default {
        name: "Directories",
        data(){
            return{
                loader:true,
                small_loader:true,
                modal:{
                    folderModalStyle:"display: none;",
                    moveModalStyle:"display: none;",
                    folderModalIn:"",
                    moveModalIn:"",
                },
                dir_list:{},
                dir_name:'',
                sub_dir_id:0,
                prop_data:{
                    dir:'',
                    dir_path:''
                },
                move_dir:{
                    root_id:0,
                    sub_id:0,
                    dir:'',
                    dir_path:'',
                    error:'',
                    selected_dir:[]
                },

                root_dir_list:{},
                sub_dir_list:{}
            }
        },
        props:[
            'directory',
            'root_dir_id',
            'dir_path'
        ],
        mounted(){
            this.getDir();
            this.getRootDir();
        },
        methods:{
            getSubDir(id,dir,dir_path){
                this.loader = true;
                this.prop_data.dir = dir;
                this.prop_data.dir_path = dir_path;
                this.$root.container.get_dir_id = id;
                this.$root.container.get_dir_path = dir_path;
                this.$root.container.get_dir_name = dir;
                this.sub_dir_id = id;
                axios.get('/get/sub/dir/'+this.sub_dir_id)
                    .then((response) => {
                        this.loader = false;
                        if (response.data.length===0){
                            alert('no dir found')
                        } else{
                            this.dir_list = response.data;
                        }
                        //console.log(response.data)
                    })
                    .catch((error) =>
                        console.log(error)
                    );
            },
            getDir(){
                this.loader = true;
                this.prop_data.dir = this.directory;
                this.prop_data.dir_path = this.dir_path;
                axios.get('/get/dir/'+this.root_dir_id)
                    .then((response) => {
                        this.loader = false;
                        this.dir_list = response.data;
                    })
                    .catch((error) =>
                        console.log(error)
                    );
            },
            getDirectory(root_dir_id,dir,dir_path){
                this.small_loader = true;
                this.move_dir.dir = dir;
                this.move_dir.dir_path = dir_path;
                this.move_dir.root_id = root_dir_id;
                this.move_dir.sub_id = 0;
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
                this.move_dir.dir = dir;
                this.move_dir.dir_path = dir_path;
                this.move_dir.sub_id = id;
                axios.get('/get/sub/dir/'+id)
                    .then((response) => {
                        this.small_loader = false;
                        if (response.data.length===0){
                            this.move_dir.error = "No Folder Or Dir Found";
                        } else{
                            this.sub_dir_list = response.data;
                        }
                        //console.log(response.data)
                    })
                    .catch((error) =>
                        console.log(error)
                    );
            },
            getRootDir(){
                axios.get('/get/root/dir/')
                    .then((response) => {
                        this.root_dir_list = response.data;
                        console.log(this.root_dir_list)
                    })
                    .catch((error) =>
                        console.log(error)
                    );
            },
            moveDir(){
                let data = {
                    root_dir_id:this.move_dir.root_id,
                    sub_dir_id:this.move_dir.sub_id,
                    selected_dirs:this.move_dir.selected_dir
                };
                console.log(data);
                if (this.move_dir.selected_dir.length>0){
                    axios.post('/move/dir',data)
                        .then((response) => {
                            console.log(response.data)
                        })
                        .catch((error) =>
                            console.log(error)
                        );
                }else{
                    alert('nor dir selected to move');
                }
            },
            showFolderModal(){
                this.modal.folderModalIn="in";
                this.modal.folderModalStyle="display: block;"
            },
            closeFolderModal(){
                this.modal.folderModalIn="";
                this.modal.folderModalStyle="display: none;"
            },
            showMoveModal(){
                this.modal.moveModalIn="in";
                this.modal.moveModalStyle="display: block;"
            },
            closeMoveModal(){
                this.sub_dir_list={};
                this.small_loader=true;
                this.move_dir.error = '';
                this.modal.moveModalIn="";
                this.modal.moveModalStyle="display: none;"
            },
            saveSubDir(){
                let data = {
                    dir_name:this.dir_name,
                    root_dir_id:this.root_dir_id,
                    sub_dir_id:this.sub_dir_id,
                    dir_path:this.prop_data.dir_path
                };
                axios.post('/add/sub/dir',data)
                    .then((response) => {
                        console.log(response.data)
                    })
                    .catch((error) =>
                        console.log(error)
                    );
            }
        },
        computed:{
           getDirList(){
               return this.dir_list;
           }
        },
        watch:{
            directory:function () {
                this.sub_dir_id=0;
                this.$root.container.get_dir_path='';
                this.getDir();
            },
        }
    }
</script>

<style scoped>

</style>
