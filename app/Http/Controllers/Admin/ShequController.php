<?php

namespace App\Http\Controllers\Admin;

use App\Shequ;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
class ShequController extends Controller
{
    //
    public function __construct()
    {

    }

    public function index(){
        if(!session('username')){
            return redirect('admin/login');
        }

        $keyword = '';
        if(!empty($_POST['keywords'])){
            $keyword = $_POST['keywords'];
        }

        $model = new Shequ();
        $res = $model -> where('title','like','%'.$keyword.'%') -> paginate('15');

        foreach($res as $k => $vo){
            $res[$k]['manage'] = DB::table('admin') -> where([
                'xiaoqu' => $vo -> id,
                'manage' => 1
            ]) -> first();
            $res[$k]['people_numberr'] = DB::table('user') -> where([
                'xiaoqu' => $vo -> id
            ]) -> count();

        }
        //dd($res);

        $count = $model -> count();
        //var_dump($count);exit;
        return view('admin/shequ/index')->with([
            'res' => $res,
            'count' => $count,
            'keywords' => $keyword
        ]);
    }

    public function changeShequ($id,$status){
        if($status == 0){
            DB::table('shequ') -> where([
                'id' => $id
            ]) -> update([
                'status' => 1
            ]);
        }else{
            DB::table('shequ') -> where([
                'id' => $id
            ]) -> update([
                'status' => 0
            ]);
        }
        return redirect('admin/shequ');
    }
    public function addShequ(){
        return view('admin/shequ/addShequ');
    }

    public function addShequRes(Request $request){
        //判断社区管理员用户是否重复
        $isset = DB::table('admin') -> where([
            'username' => $request -> input('username'),
            'password' => $request -> input('password'),
            'type' => 1
        ]) -> first();
        if($isset){
            return redirect('admin/shequ')->with([
                'isset' => 'yes'
            ]);
        }
        $model = new Shequ();
        $res = $model -> insertGetId([
            'title' => $request -> input('title'),
            'number_lou' => $request -> input('number_lou'),
            'name' => $request -> input('name'),
            'tel' => $request -> input('tel'),
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $id = DB::table('admin') -> insertGetId([
            'username' => $request -> input('username'),
            'password' => $request -> input('password'),
            'type' => 1,
            'xiaoqu' => $res,
            'manage' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);




        if($res){
            return redirect('admin/shequ')->with([
                'insertres' => 'yes'
            ]);
        }
    }

    public function enterXiaoqu($id){
        //保存小区
        session([
            'xiaoqu' => $id,
            'type' => 1,
            'entertype' => 'super',
            'header' => 'hidder'
        ]);
        //跳转到小区的管理页面
        return redirect('admin/yonghu/1');
    }

    public function editShequ($id){
        $model = new Shequ();
        $res = $model -> where(['id'=>$id]) -> first();

        $res_manage = DB::table('admin') -> where([
            'xiaoqu' => $res -> id,
            'manage' => 1
        ]) -> first();
        //var_dump($res['username']);exit;
        return redirect('admin/shequ') -> with([
            'res' => $res,
            'res_manage' => $res_manage,
            'show' => 'yes'
        ]);

    }

    public function editShequRes(Request $request){
        $model = new Shequ();
        $savearr = [
            'title' => $request -> input('title'),
            'number_lou' => $request -> input('number_lou'),
            'name' => $request -> input('name'),
            'tel' => $request -> input('tel'),
        ];
        if($request -> input('password')){
            DB::table('admin') -> where([
                'xiaoqu' => $request->input('id'),
                'manage' => 1
            ]) -> update([
                'username' => $request -> input('username'),
                'password' => $request -> input('password'),
            ]);
        }
        $res = $model -> where(['id'=>$request->input('id')]) -> update($savearr);
        return redirect('admin/shequ') -> with([
            'editres' => 'success'
        ]);

    }

    public function deleteShequ(Request $request){
        $model = new Shequ();
        $res = $model -> where(['id'=>$request->input('id')]) -> delete();

        if($res){
            echo 'success';
        }
    }

    public function shequxieyi(){
        $res = DB::table('xieyi') -> where(['id'=>1]) -> first();

        return view('admin/shequ/xieyi')->with([
            'res'=>(string)$res->content
        ]);
    }
    public function addXieyi(Request $request){
        $res = DB::table('xieyi') -> where(['id'=>1]) -> update([
            'content' => $request -> input('content')
        ]);
        return redirect('admin/shequxieyi') -> with([
            'editres' => 'success'
        ]);
    }
}
