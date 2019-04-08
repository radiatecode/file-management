<?php

namespace App\Http\Controllers;
use App\Directory;
use App\File;
use App\FileType;
use Faker\Provider\Image;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FileViewerController extends Controller
{
    private static $BASE_DIR = "Radiate/";
    private static $STORE = "public/";
    private static $READ =  "storage/";

    public function fileViewer(){
        $rootDir = FileType::orderBy('dir_name','ASC')->get();
        return view('local-file-viewer.local-file-viewer')
            ->with('root_dir',$rootDir);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addSubDir(Request $request){
        $this->validate($request,[
            'dir_name'=>'required|unique:directories,sub_dir',
            'root_dir_id'=>'required',
            'dir_path'=>'required'
        ]);

        $generate_path = $request->dir_path."/".$request->dir_name;
        $dir = new Directory();
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

    public function getDirFiles($id){
        $response = [];
        $file = File::where('dir_id',$id)->get();
        $response = [
            'response'=>'success',
            'data'=>$file
        ];
        return response()->json($response,201);
    }

    public function getRootDir(){
        $rootDir = Storage::directories("Radiate");
        return response()->json($rootDir,201);
    }

    public function getDir($root_id){
        $dirData = [];
        $dirs = Directory::where('root_dir_id',$root_id)
            ->where('sub_dir_id',0)->get();
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

    public function getSubDir($sub_dir_id){
        $dirData = [];
        $dirs= Directory::where('sub_dir_id',$sub_dir_id)->get();
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

//    public function getFiles(){
//        $url = '/storage/images/nur/1552936091_1490293163_1.JPG';
//        return view('local-file-viewer.file-upload')
//            ->with('dir_id','')
//            ->with('dir_path','')
//            ->with('url',$url);
//
//    }

    public function fileUploadView($id){
        $dir = Directory::find($id);
        $dir_path = $dir->dir_path;
        return view('local-file-viewer.file-upload')
            ->with('dir_id',$id)
            ->with('dir_path',$dir_path);
    }

    public function fileUpload(Request $request){
            if ($request->hasFile('file')){
                $directory = Directory::find($request->dir_id);
                $image = $request->file('file');
                $filename = time()."_".$image->getClientOriginalName();
                $path = $request->file('file')->storeAs(
                    self::$STORE.$request->dir_path,$filename);
                $file = new File();
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
}
