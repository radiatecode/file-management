<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileType extends Model
{
    public function directory(){
        return $this->hasMany('App\Directory','root_dir_id','id');
    }
}
