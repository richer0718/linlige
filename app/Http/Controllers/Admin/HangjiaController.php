<?php

namespace App\Http\Controllers\Admin;

use App\Business;
use App\Hangjia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class HangjiaController extends Controller
{
    //


    public function index(){

        if(!session('username')){
            return redirect('admin/login');
        }

        $keyword = '';
        if(!empty($_POST['keywords'])){
            $keyword = $_POST['keywords'];
        }

        $model = new Hangjia();
        $res = $model -> where('title','like','%'.$keyword.'%') -> paginate('15');
        $count = $model -> count();
        //var_dump($count);exit;
        return view('admin/hangjia/index')->with([
            'res' => $res,
            'count' => $count
        ]);
    }

    public function addHangjia(){
        return view('admin/hangjia/addHangjia');
    }
    public function addHangjiaRes(Request $request){
        $file = $request -> file('file');
        if($file -> isValid()){
            //如果上传成功
            $ext = $file -> getClientOriginalExtension();
            $realpath = $file  -> getRealPath();
            $filename = date('Y-m-d H:i:s').'-'.uniqid().'.'.$ext;
            $bool = Storage::disk('uploads')->put($filename,file_get_contents($realpath));
        }

        $model = new Hangjia();
        if(session('type') == 1){
            $is_mark = session('xiaoqu');
        }else{
            $is_mark = 0;
        }
        $res = $model -> insert([
            'title' => mb_substr($request -> input('title'),0,12,'utf-8'),
            'name' => mb_substr($request -> input('name'),0,4,'utf-8'),
            'tel' => $request -> input('tel'),
            'tel2' => $request -> input('tel2'),
            'xuanze' => $request -> input('xuanze'),
            'img' => $filename,
            'is_mark' => $is_mark,
            'content' => mb_substr($request -> input('content'),0,54,'utf-8'),
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        if($res){
            return redirect('admin/hangjiazaixian')->with([
                'insertres' => 'yes'
            ]);
        }
    }

    public function editHangjia($id){
        $model = new Hangjia();
        $res = $model -> where(['id'=> $id]) -> first();
        return view('admin/hangjia/editHangjia') -> with([
            'res' => $res
        ]);
    }

    public function editHangjiaRes(Request $request){
        $model = new Hangjia();
        $savearr = [
            'title' => mb_substr($request -> input('title'),0,12,'utf-8'),
            'name' => mb_substr($request -> input('name'),0,4,'utf-8'),
            'tel' => $request -> input('tel'),
            'content' => mb_substr($request -> input('content'),0,54,'utf-8'),
        ];

        $file = $request -> file('file');
        if($file && $file -> isValid()){
            //如果上传成功
            $ext = $file -> getClientOriginalExtension();
            $realpath = $file  -> getRealPath();
            $filename = date('Y-m-d H:i:s').'-'.uniqid().'.'.$ext;
            $bool = Storage::disk('uploads')->put($filename,file_get_contents($realpath));
            $savearr['img'] = $filename;
        }



        $res = $model -> where(['id'=> $request -> input('id')]) -> update($savearr);
        if($res){
            return redirect('admin/hangjiazaixian')->with([
                'editres' => 'yes'
            ]);
        }
    }

    public function deleteHangjia(Request $request){
        $model = new Hangjia();
        $res = $model -> where(['id'=>$request->input('id')]) -> delete();

        if($res){
            echo 'success';
        }
    }

    public function checkstatus(Request $request){
        $res = DB::table('hangjia') -> where([
            'id' => $request -> input('id')
        ]) -> update([
            'status' => $request -> input('status')
        ]);
        if($res){
            echo 'success';
        }else{
            echo 'error';
        }
    }


}
