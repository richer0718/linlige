<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadImageController extends Controller
{
    //
    public function __construct()
    {
        if(!session('username')){
            $this->middleware(function ($request, $next) {
                return redirect('admin/login');
            });
        }
    }

    public function uploadimage(Request $request){
        $file = $request -> file();
        dd($file);
    }
}
