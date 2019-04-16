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
                <button @click="backDir" class="btn btn-warning btn-sm">
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
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="dir in getDirList">
                            <td>
                                <div class="checkbox3 checkbox-danger checkbox-inline checkbox-check  checkbox-circle checkbox-light">
                                    <input type="checkbox" :id="'checkbox-fa-light-'+dir.id"
                                           v-bind:value="dir.id" v-model="selected_dirs">
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
                            <td>
                                <button class="bt btn-info btn-xs"><i class="fa fa-edit"></i></button>
                                <button class="bt btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
                            </td>
                        </tr>
                        <tr v-show="error.dir_error">
                            <td colspan="5">{{ error.dir_error }}</td>
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
    </div>
</template>

<script>
    export default {
        name: "Directories",
        data(){
            return{
                loader:true,
                modal:{
                    folderModalStyle:"display: none;",
                    folderModalIn:"",
                },
                dir_list:{},
                dir_name:'',
                sub_dir_id:0,
                prev_dirs:{},
                prop_data:{
                    root_id:0,
                    dir:'',
                    dir_path:''
                },
                selected_dirs:[],
                move_dir:{
                    root_id:0,
                    dir_id:0,
                    dir:'',
                    dir_path:'',
                    error:'',
                    selected_files:[]
                },
                error:{
                    dir_error:''
                }
            }
        },
        props:[
            'directory',
            'root_dir_id',
            'dir_path'
        ],
        mounted(){
            this.getDir();
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
                        if (response.data.dir_data.length===0){
                            this.error.dir_error = "No Directory Found!";
                            this.dir_list = response.data.dir_data;
                        } else{
                            this.error.dir_error = "";
                            this.dir_list = response.data.dir_data;
                        }
                        this.prev_dirs = response.data.root_data;
                        console.log(this.sub_dir_id);
                        console.log(this.prev_dirs);
                        //console.log(response.data)
                    })
                    .catch((error) => {
                        this.error.dir_error = "";
                        console.log(error)
                    });
            },
            getDir(){
                this.loader = true;
                this.prop_data.dir = this.directory;
                this.prop_data.dir_path = this.dir_path;
                this.prop_data.root_id = this.root_dir_id;
                axios.get('/get/dir/'+this.prop_data.root_id)
                    .then((response) => {
                        this.loader = false;
                        if (response.data.length===0){
                            this.error.dir_error = "No Directory Found!";
                            this.dir_list = response.data;
                        } else{
                            this.error.dir_error = "";
                            this.dir_list = response.data;
                        }
                    })
                    .catch((error) => {
                        this.error.dir_error = "";
                        console.log(error);
                    });
            },
            backDir(){
                if (this.prev_dirs ) {
                    if (this.prev_dirs.prev_dir_id!==0) {
                        this.getSubDir(
                            this.prev_dirs.prev_dir_id,
                            this.prev_dirs.prev_dir,
                            this.prev_dirs.prev_path
                        );
                    }else{
                        this.prop_data.root_id = this.prev_dirs.prev_root_id;
                        this.getDir();
                    }
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
