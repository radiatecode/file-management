<template>
<div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <button type="button" @click="showModal" class="btn btn-success btn-sm">
            <i class="fa fa-plus-square"></i> Folder
        </button>
        <button class="btn btn-danger btn-sm">
            <i class="fa fa-trash-o"></i> Delete
        </button>
        <button class="btn btn-primary btn-sm">
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
                            <input type="checkbox" id="checkbox-fa-light-3" checked="">
                            <label for="checkbox-fa-light-3">
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
    <!-- Modal Box -->
    <div :class="'modal fade'+modalIn" id="add_folder" tabindex="-1" role="dialog" :style="modalStyle">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" @click="closeModal" aria-hidden="true">×</button>
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
                        <button type="button" class="btn btn-default" @click="closeModal">Close</button>
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
                modalStyle:"display: none;",
                modalIn:"",
                dir_list:{},
                dir_name:'',
                sub_dir_id:0,
                prop_data:{
                    dir:'',
                    dir_path:''
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
            showModal(){
                this.modalIn="in";
                this.modalStyle="display: block;"
            },
            closeModal(){
                this.modalIn="";
                this.modalStyle="display: none;"
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
                this.getDir();
            }
        }
    }
</script>

<style scoped>

</style>
