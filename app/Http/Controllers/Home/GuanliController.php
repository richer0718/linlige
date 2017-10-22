<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GuanliController extends Controller
{
    //
    public function login(){
        //dd(session());
        if(session('login_type') == 'guanli' && session('adminusername')){
            return redirect('home/guanli/index');
        }
        return view('home/guanli/login');
    }

    public function loginRes(Request $request){
        //dd($request);
        $res = DB::table('admin') -> where([
            'username' => $request -> input('username'),
            'password' => $request -> input('password'),
            'type' => 0
        ]) -> first();
        if($res){

            //保存管理原名称
            session([
                'adminusername' => $request -> input('username'),
                'login_type' => 'guanli'
            ]);
            echo 'success';
        }else{
            echo 'error';
        }
    }

    public function index(){
        return redirect('home')->with('is_manage','yes');
    }

}
