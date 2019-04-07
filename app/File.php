<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public function directory(){
        return $this->belongsTo('App\Directory','dir_id');
    }
}
