<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 4/10/2019
 * Time: 1:11 PM
 */

namespace App\Repository\FileViewer;


use App\Directory;
use App\File;
use App\FileType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileViewerEloquent implements FileViewerInterface
{
    private $fileTypeModel;
    private $fileModel;
    private $directoryModel;

    private static $BASE_DIR = "Radiate/";
    private static $STORE = "public/";
    private static $READ =  "storage/";

    public function __construct(
        FileType $fileTypeModel,
        Directory $directoryModel,
        File $fileModel){
        $this->fileTypeModel = $fileTypeModel;
        $this->directoryModel = $directoryModel;
        $this->fileModel = $fileModel;
    }


    public function getFileViewer()
    {
        $rootDir = $this->fileTypeModel->orderBy('dir_name','ASC')->get();
        return view('local-file-viewer.local-file-viewer')
            ->with('root_dir',$rootDir);
    }

    public function getUploadForm($id)
    {
        $dir = $this->directoryModel->find($id);
        $dir_path = $dir->dir_path;
        return view('local-file-viewer.file-upload')
            ->with('dir_id',$id)
            ->with('dir_path',$dir_path);
    }

    public function getJsonRootDir()
    {
        $rootDir = $this->fileTypeModel
            ->orderBy('dir_name','ASC')
            ->get();
        return response()->json($rootDir,201);
    }

    public function getJsonRootContainDirs($root_id)
    {
        $dirData = [];
        $dirs = $this->directoryModel
            ->where('root_dir_id',$root_id)
            ->where('sub_dir_id',0)
            ->get();
        foreach ($dirs as $dir){
            $dirData[] = [
                'id'=>$dir->id,
                'sub_dir'=>$dir->sub_dir,
                'dir_path'=>$dir->dir_path,
                'folders'=>count($dir->folders),
                'files'=>count($dir->file),
                'created_at'=>''.$dir->created_at
            ];
        }
        return response()->json($dirData,201);
    }

    public function getJsonSubDirs($sub_dir_id)
    {
        $dirData = [];
        $dirs= $this->directoryModel
            ->where('sub_dir_id',$sub_dir_id)
            ->get();
        foreach ($dirs as $dir){
            $dirData[] = [
                'id'=>$dir->id,
                'sub_dir'=>$dir->sub_dir,
                'dir_path'=>$dir->dir_path,
                'folders'=>count($dir->folders),
                'files'=>count($dir->file),
                'created_at'=>''.$dir->created_at
            ];
        }
        return response()->json($dirData,201);
    }

    public function getJsonDirFiles($id)
    {
        $response = [];
        $file = $this->fileModel->where('dir_id',$id)->get();
        $response = [
            'response'=>'success',
            'data'=>$file
        ];
        return response()->json($response,201);
    }

    public function addSubDirs(Request $request)
    {
        $generate_path = $request->dir_path."/".$request->dir_name;
        $dir = $this->directoryModel;
        $dir->root_dir_id = $request->root_dir_id;
        $dir->sub_dir_id = $request->sub_dir_id;
        $dir->sub_dir = $request->dir_name;
        $dir->icon = 'fa fa-folder';
        $dir->dir_path = $generate_path;
        $dir->save();

        // create the directory for that file type
        Storage::makeDirectory(self::$STORE.$generate_path);

        return response()->json('success',201);
    }

    public function moveDirs(Request $request)
    {
        $dirs = $request->selected_dirs;
        foreach ($dirs as $id){
            $new_dir_path="";
            $old_dir_path="";
            $directory = $this->directoryModel->find($id);
            $directory->root_dir_id = $request->root_dir_id;
            $directory->sub_dir_id = $request->sub_dir_id;
            $old_dir_path = $directory->dir_path;
            if ($request->sub_dir_id!=0){
                $subDir = $this->directoryModel->find($request->sub_dir_id);
                $new_dir_path = $subDir->dir_path."/".$directory->sub_dir;
                $directory->dir_path = $subDir->dir_path."/".$directory->sub_dir;
            }else{
                $rootDir = $this->fileTypeModel->find($request->root_dir_id);
                $new_dir_path = $rootDir->dir_path."/".$directory->sub_dir;
                $directory->dir_path = $rootDir->dir_path."/".$directory->sub_dir;
            }
            $directory->save();
            // here move the directory from storage by storage class
            Storage::move(self::$STORE.$old_dir_path,self::$STORE.$new_dir_path);
        }
        return response()->json('success',201);
    }

    public function moveFiles(Request $request)
    {
        $files = $request->selected_files;
        foreach ($files as $id){
            $new_file_path="";
            $old_file_path="";
            $file = $this->fileModel->find($id);
            $directory = $this->directoryModel->find($request->move_dir_id);
            $file->dir_id = $request->move_dir_id;
            $old_file_path = $file->file_path;
            $new_file_path = $directory->dir_path."/".$file->file_name;
            $file->file_path = $new_file_path;
            $file->file_type = $directory->file_types->file_type;
            $file->save();
            // here move the directory from storage by storage class
            Storage::move(self::$STORE.$old_file_path,self::$STORE.$new_file_path);
        }
        return response()->json('success',201);
    }

    public function fileUpload(Request $request)
    {
        if ($request->hasFile('file')){
            $directory = $this->directoryModel->find($request->dir_id);
            $image = $request->file('file');
            $filename = time()."_".$image->getClientOriginalName();
            $path = $request->file('file')->storeAs(
                self::$STORE.$request->dir_path,$filename);
            $file = $this->fileModel;
            $file->dir_id = $request->dir_id;
            $file->file_name = $filename;
            $file->file_extension = ucwords($image->getClientOriginalExtension());
            $file->file_path = $request->dir_path."/".$filename;
            $file->file_type = $directory->file_types->file_type;
            $file->save();
            return response()->json([
                'status' => 'ok',
                'path' =>'new/path'
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'path' =>'new/path'
            ]);
        }
    }

    public function copyFiles(Request $request)
    {
        $files = $request->selected_files;
        foreach ($files as $id){
            $new_file_path="";
            $old_file_path="";
            $directory = $this->directoryModel->find($request->move_dir_id);
            $oldFile = $this->fileModel->find($id);
            $old_file_path = $oldFile->file_path;
            $new_file_path = $directory->dir_path."/".$oldFile->file_name;

            $file = $oldFile->replicate();
            $file->dir_id = $request->move_dir_id;
            $file->file_path = $new_file_path;
            $file->file_type = $directory->file_types->file_type;
            $file->save();
            // here move the directory from storage by storage class
            Storage::copy(self::$STORE.$old_file_path,self::$STORE.$new_file_path);
        }
        return response()->json('success',201);
    }

    public function renameFiles(Request $request)
    {
        $new_file_path="";
        $old_file_path="";
        $file = $this->fileModel->find($request->file_id);
        $old_file_path = $file->file_path;
        $renamed_file = time()."_".$request->file_name;
        $new_file_path = $file->directory->dir_path."/".$renamed_file;
        $file->file_path = $new_file_path;
        $file->file_name = $renamed_file;
        $file->save();
        // here move the directory from storage by storage class
        Storage::move(self::$STORE.$old_file_path,self::$STORE.$new_file_path);

        return response()->json('success',201);
    }

    public function downloadAsZip(Request $request)
    {
        $files=[];
        $selected_files = $request->selected_files;
        foreach ($selected_files as $id){
            $file =  $this->fileModel->find($id);
            $files[] = $file->file_path;
        }
        return response()->json($files,201);
    }

    public function downloadSingleFile($id)
    {
        $file = $this->fileModel->find($id);
        return Storage::download(self::$STORE.$file->file_path);
    }

    public function deleteFiles(Request $request)
    {
        $selected_files = $request->selected_files;
        foreach ($selected_files as $id){
            $file =  $this->fileModel->find($id);
            $path = self::$STORE.$file->file_path;
            $exist = Storage::exists($path);
            if ($exist) {
                $file->delete();
                Storage::delete($path);
            }
        }
        return response()->json('success',201);
    }

    public function deleteFile($id)
    {
        $file =  $this->fileModel->find($id);
        $path = self::$STORE.$file->file_path;
        $exist = Storage::exists($path);
        if ($exist) {
            $file->delete();
            Storage::delete($path);
        }
        return response()->json('success',201);
    }
}