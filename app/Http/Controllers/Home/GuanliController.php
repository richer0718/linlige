<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GuanliController extends Controller
{
    //
    public function login(){
        session([
            'login_type' => ''
        ]);
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

    public function select(){
        $xiaoqu = DB::table('shequ') -> where([
            'status' => 0
        ]) -> get();

        return view('home/guanli/select')->with([
            'xiaoqu' => $xiaoqu
        ]);
    }

    //选择好小区后，跳转
    public function jumpHome($id){
        //var_dump($id);exit;
        return redirect('home')->with('is_manage_jump',$id);
    }

}
