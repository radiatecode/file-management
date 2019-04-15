<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 4/10/2019
 * Time: 1:11 PM
 */

namespace App\Repository\FileViewer;


use Illuminate\Http\Request;

interface FileViewerInterface
{
    public function getFileViewer();
    public function getUploadForm($id);

    public function getJsonRootDir();
    public function getJsonRootContainDirs($root_id);
    public function getJsonSubDirs($sub_dir_id);
    public function getJsonDirFiles($id);

    public function addSubDirs(Request $request);
    public function fileUpload(Request $request);
    public function moveDirs(Request $request);
    public function moveFiles(Request $request);
    public function copyFiles(Request $request);
    public function renameFiles(Request $request);
    public function downloadAsZip(Request $request);
    public function deleteFiles(Request $request);
    public function downloadSingleFile($id);
    public function deleteFile($id);


}