<?php

namespace App\Http\Controllers;

use App\FileType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class SetupController extends Controller
{
    private static $BASE_DIR = "Radiate/";
    private static $STORE = "public/";
    private static $READ =  "storage/";

    public function fileTypeList(){
        return view('setup.file_type');
    }

    public function generateDataTables(){
        $fileTypes = FileType::all();
        $dataTables = DataTables::of($fileTypes)
            ->addColumn('action',function ($fileTypes){
                return '<button onclick="editForm('.$fileTypes->id.')" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> Edit</button>'.' '.
                    '<button onclick="deleteData('.$fileTypes->id.')" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i> Delete</button>';
            })
            ->addColumn('hash', function($fileTypes) {
                $ids = $fileTypes->id;
                return view('datatables.checkbox',compact('ids'))->render();
            })
            ->rawColumns(['hash','action'])
            ->toJson();
        return $dataTables;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeFileType(Request $request){
        $this->validate($request,[
            'file_type'=>'required|min:6',
            'dir_name'=>'required|min:6',
            'icon'=>'required|min:6'
        ]);

        // store data in database
        $generate_path = self::$BASE_DIR.$request->dir_name;
        $fileType = new FileType();
        $fileType->file_type = $request->file_type;
        $fileType->dir_name = $request->dir_name;
        $fileType->icon = $request->icon;
        $fileType->dir_path = $generate_path;
        $fileType->save();

        // create the directory for that file type
        Storage::makeDirectory(self::$STORE.$generate_path);

        Session::flash("success","Successfully File Type Added");
        return redirect()->back();
    }
}
