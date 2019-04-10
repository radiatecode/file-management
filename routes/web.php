<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//Route::get("/get/sub/dir/{filename?}","FileViewerController@getSubDir")
//    ->where('filename','(.*)');

Route::get("/dashboard","HomeController@dashboard");

Route::get("/file/viewer","FileViewerController@getFileViewer");
Route::post("/add/sub/dir","FileViewerController@addSubDir");

Route::get("/get/root/dir","FileViewerController@getRootDir");
Route::get("/get/dir/{root_id}","FileViewerController@getRootContainDirs");
Route::get("/get/sub/dir/{sub_dir_id}","FileViewerController@getSubDir");
Route::post("/move/dir","FileViewerController@moveDir")->name('move.dir');
Route::post("/move/files","FileViewerController@moveFiles")->name('move.files');
Route::get("/moved","FileViewerController@moved");
//Route::get("/get/directory/files","FileViewerController@getFiles");

Route::get('/file/types','SetupController@fileTypeList');
Route::get('/generate/file/types/tables','SetupController@generateDataTables')->name('ssr.file.type');
Route::post('/add/file/types','SetupController@storeFileType')->name('add.file.type');

Route::get("/file/upload/{dir_id}","FileViewerController@getUploadForm");
Route::post("/file/upload","FileViewerController@fileUpload")->name('file.upload');

Route::get("get/dir/files/{dir_id}","FileViewerController@getDirFiles")->name('get.files');
