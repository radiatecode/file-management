<?php

namespace App\Http\Controllers;
use App\Directory;
use App\File;
use App\FileType;
use App\HelperClass\ValidationErrors;
use App\Repository\FileViewer\FileViewerInterface;
use Faker\Provider\Image;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileViewerController extends Controller
{

    private $fileViewer;

    public function __construct(FileViewerInterface $fileViewer)
    {
        $this->fileViewer = $fileViewer;
    }

    /**
     * get file viewer blade
     * @return mixed
     */
    public function getFileViewer(){
       return $this->fileViewer->getFileViewer();
    }

    /**
     * Add Directory
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSubDir(Request $request){
        $validation = Validator::make($request->all(),[
            'dir_name'=>'required|unique:directories,sub_dir',
            'root_dir_id'=>'required',
            'dir_path'=>'required'
        ]);
        if ($validation->fails()){
            return response()->json(ValidationErrors::getErrors($validation),201);
        }

        return $this->fileViewer->addSubDirs($request);
    }

    /**
     * Get Files From Specific Directory
     * @param $id
     * @return mixed
     */
    public function getDirFiles($id){
        return $this->fileViewer->getJsonDirFiles($id);
    }

    /**
     * Ger Directories of root directory
     * @param $root_id
     * @return mixed
     */
    public function getRootContainDirs($root_id){
        return $this->fileViewer->getJsonRootContainDirs($root_id);
    }

    /**
     * Get Directories of a directory
     * @param $sub_dir_id
     * @return mixed
     */
    public function getSubDir($sub_dir_id){
        return $this->fileViewer->getJsonSubDirs($sub_dir_id);
    }

    /**
     * Get File Upload Form
     * @param $id
     * @return mixed
     */
    public function getUploadForm($id){
        return $this->fileViewer->getUploadForm($id);
    }

    /**
     * File Upload To A Directory
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fileUpload(Request $request){
        $validation = Validator::make($request->all(),[
            'file'=>'required|file'
        ]);
        if ($validation->fails()){
            return response()->json(
                ValidationErrors::getErrors($validation),
                201);
        }
        return $this->fileViewer->fileUpload($request);
    }

    /**
     * Get Root Dir For Move Files
     * @return mixed
     */
    public function getRootDir(){
        return $this->fileViewer->getJsonRootDir();
    }

    /**
     * Move Directory
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function moveDir(Request $request){
        $validation = Validator::make($request->all(),[
           'root_dir_id'=>'required',
           'sub_dir_id'=>'required',
           'selected_dirs'=>'required|array|min:1'
        ]);
        if ($validation->fails()){
            return response()->json(ValidationErrors::getErrors($validation),201);
        }

        return $this->fileViewer->moveDirs($request);
    }
    public function moved(){
        Storage::move("Radiate/Documents/Important/Nur","Radiate/Images/Nur");
    }

    /**
     * Move Files To Another Directory
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function moveFiles(Request $request){
        $validation = Validator::make($request->all(),[
            'move_dir_id'=>'required',
            'selected_files'=>'required|array|min:1'
        ]);
        if ($validation->fails()){
            return response()->json(ValidationErrors::getErrors($validation),201);
        }
        return $this->fileViewer->moveFiles($request);
    }


    /**
     * Copy Files To Another Directory
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function copyFiles(Request $request){
        $validation = Validator::make($request->all(),[
            'move_dir_id'=>'required',
            'selected_files'=>'required|array|min:1'
        ]);
        if ($validation->fails()){
            return response()->json(ValidationErrors::getErrors($validation),201);
        }
        return $this->fileViewer->copyFiles($request);
    }

    /**
     * Rename Files
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function renameFiles(Request $request){
        $validation = Validator::make($request->all(),[
            'file_id'=>'required',
            'file_name'=>'required'
        ]);
        if ($validation->fails()){
            return response()->json(ValidationErrors::getErrors($validation),201);
        }
        return $this->fileViewer->renameFiles($request);
    }

    /**
     * Delete Multiple Files
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFiles(Request $request){
        $validation = Validator::make($request->all(),[
            'selected_files'=>'required|array|min:1'
        ]);
        if ($validation->fails()){
            return response()->json(ValidationErrors::getErrors($validation),201);
        }
        return $this->fileViewer->deleteFiles($request);
    }

    /**
     * Delete Single File
     * @param $id
     * @return mixed
     */
    public function deleteFile($id){
        return $this->fileViewer->deleteFile($id);
    }

    public function downloadAsZip(Request $request){
       return $this->fileViewer->downloadAsZip($request);
    }

    public function singleDownload($id){
       return $this->fileViewer->downloadSingleFile($id);
    }
}
