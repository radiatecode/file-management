<?php

namespace App\Http\Controllers;

use App\FileType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function dashboard(){
        return view('dashboard.dashboard');
    }



}
