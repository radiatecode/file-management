<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    public function file(){
       return $this->hasMany('App\File','dir_id','id');
    }

    public function file_types(){
        return $this->belongsTo('App\FileType','root_dir_id');
    }

    public function folders(){
        return $this->hasMany('App\Directory','sub_dir_id','id');
    }

    public function folder(){
        return $this->belongsTo('App\Directory','sub_dir_id');
    }
}
