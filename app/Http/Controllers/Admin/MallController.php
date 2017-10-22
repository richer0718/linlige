<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MallController extends Controller
{


    //轮播图
    public function lunbo(){
        if(!session('username')){
            return redirect('admin/login');
        }

        $res = DB::table('lunbo') -> get();
        $res = $this -> object_array($res);
        //dd($res);
        return view('admin/mall/lunbo')->with([
            'res'=>$res
        ]);
    }
    public function addLunbo(){
        $goods = DB::table('goods') -> get();

        return view('admin/mall/addLunbo')->with([
            'type' => 'add',
            'goods' => $goods
        ]);
    }
    public function addLunboRes(Request $request){
        $file = $request -> file('file');
        if($file -> isValid()){
            //如果上传成功
            $ext = $file -> getClientOriginalExtension();
            $realpath = $file  -> getRealPath();
            $filename = date('Y-m-d-H:i:s').'-'.uniqid().'.'.$ext;
            $bool = Storage::disk('uploads')->put($filename,file_get_contents($realpath));
        }

        $res = DB::table('lunbo') -> insert([
            'url_out' => $request -> input('url_out'),
            'url_in' => $request -> input('url_in'),
            'img' => $filename,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        if($res){
            return redirect('admin/lunbo')->with([
                'insertres' => 'yes'
            ]);
        }
    }

    public function editLunbo($id){
        $res = DB::table('lunbo') -> where(['id'=> $id]) -> first();
        $goods = DB::table('goods') -> get();
        return view('admin/mall/addLunbo') -> with([
            'res' => (array)$res,
            'type' => 'edit',
            'goods' => $goods
        ]);
    }

    public function editLunboRes(Request $request){
        $savearr = [
            'url_out' => $request -> input('url_out'),
            'url_in' => $request -> input('url_in'),
            'updated_at' => time(),
        ];

        $file = $request -> file('file');
        if($file && $file -> isValid()){
            //如果上传成功
            $ext = $file -> getClientOriginalExtension();
            $realpath = $file  -> getRealPath();
            $filename = date('Y-m-d-H:i:s').'-'.uniqid().'.'.$ext;
            $bool = Storage::disk('uploads')->put($filename,file_get_contents($realpath));
            $savearr['img'] = $filename;
        }



        $res = DB::table('lunbo') -> where(['id'=> $request -> input('id')]) -> update($savearr);
        if($res){
            return redirect('admin/lunbo')->with([
                'editres' => 'yes'
            ]);
        }
    }

    public function deleteLunbo(Request $request){
        $res = DB::table('lunbo') -> where(['id'=>$request->input('id')]) -> delete();

        if($res){
            echo 'success';
        }
    }

    //供应商
    public function gongyingshang(){
        $keyword = '';
        if(!empty($_POST['keywords'])){
            $keyword = $_POST['keywords'];
        }

        $res = DB::table('gongyingshang') -> where('name','like','%'.$keyword.'%') -> paginate(15);
        //$res = $this->object_array($res);
        //dd($res);
        $count = DB::table('gongyingshang') -> count();

        return view('admin/mall/gongyingshang') -> with([
            'res' => $res,
            'count' => $count,
            'keywords' => $keyword
        ]);
    }
    public function changeGongyingshang($id,$status){
        if($status == 0){
            DB::table('gongyingshang') -> where([
                'id' => $id
            ]) -> update([
                'status' => 1
            ]);
        }else{
            DB::table('gongyingshang') -> where([
                'id' => $id
            ]) -> update([
                'status' => 0
            ]);
        }
        return redirect('admin/gongyingshang');
    }


    public function addGongyingshangRes(Request $request){
        $res = DB::table('gongyingshang') -> insert([
            'name' => $request -> input('name'),
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        if($res){
            return redirect('admin/gongyingshang')->with([
                'insertres' => 'yes'
            ]);
        }
    }
    public function editGongyingshang($id){
        $res = DB::table('gongyingshang') -> where(['id'=>$id]) -> first();
        //var_dump((array)$res);exit;
        return redirect('admin/gongyingshang') -> with([
            'res' => (array)$res,
            'show' => 'yes'
        ]);

    }
    public function editGongyingshangRes(Request $request){
        $savearr = [
            'name' => $request -> input('name'),
            'updated_at' => time(),
        ];

        $res = DB::table('gongyingshang') -> where(['id'=>$request->input('id')]) -> update($savearr);
        return redirect('admin/gongyingshang') -> with([
            'editres' => 'success'
        ]);

    }

    public function deleteGongyingshang(Request $request){
        $res = DB::table('gongyingshang') -> where(['id'=>$request->input('id')]) -> delete();

        if($res){
            echo 'success';
        }
    }

    //分类
    public function fenlei(){
        $res = DB::table('fenlei') -> paginate(15);
        //$res = $this->object_array($res);
        //dd($res);
        $count = DB::table('fenlei') -> count();

        return view('admin/mall/fenlei') -> with([
            'res' => $res,
            'count' => $count
        ]);
    }

    public function changeFenlei($id,$status){
        if($status == 0){
            DB::table('fenlei') -> where([
                'id' => $id
            ]) -> update([
                'status' => 1
            ]);
        }else{
            DB::table('fenlei') -> where([
                'id' => $id
            ]) -> update([
                'status' => 0
            ]);
        }
        return redirect('admin/fenlei');
    }

    public function addFenleiRes(Request $request){
        $res = DB::table('fenlei') -> insert([
            'name' => $request -> input('name'),
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        if($res){
            return redirect('admin/fenlei')->with([
                'insertres' => 'yes'
            ]);
        }
    }
    public function editFenlei($id){
        $res = DB::table('fenlei') -> where(['id'=>$id]) -> first();
        //var_dump((array)$res);exit;
        return redirect('admin/fenlei') -> with([
            'res' => (array)$res,
            'show' => 'yes'
        ]);

    }
    public function editFenleiRes(Request $request){
        $savearr = [
            'name' => $request -> input('name'),
            'updated_at' => time(),
        ];

        $res = DB::table('fenlei') -> where(['id'=>$request->input('id')]) -> update($savearr);
        return redirect('admin/fenlei') -> with([
            'editres' => 'success'
        ]);

    }

    public function deleteFenlei(Request $request){
        $res = DB::table('fenlei') -> where(['id'=>$request->input('id')]) -> delete();

        if($res){
            echo 'success';
        }
    }

    public function goods(){
        $keyword = '';
        if(!empty($_POST['keywords'])){
            $keyword = $_POST['keywords'];
        }

        $res = DB::table('goods') ->where('title','like','%'.$keyword.'%')-> paginate(15);
        //dd($res);
        return view('admin/mall/goods')->with([
            'res' => $res,
            'keywords' => $keyword
        ]);
    }

    public function addGoods(){
        //分类
        $res_fenlei = DB::table('fenlei') -> get();
        $res_fenlei = $this->object_array($res_fenlei);

        $res_gongying = DB::table('gongyingshang') -> get();
        $res_gongying = $this->object_array($res_gongying);

        return view('admin/mall/addGoods')->with([
            'res_fenlei' => $res_fenlei,
            'res_gongying' => $res_gongying
        ]);
    }

    public function changeGoods($id,$status){
        if($status == 0){
            DB::table('goods') -> where([
                'id' => $id
            ]) -> update([
                'status' => 1
            ]);
        }else{
            DB::table('goods') -> where([
                'id' => $id
            ]) -> update([
                'status' => 0
            ]);
        }
        return redirect('admin/goods');
    }


    public function addGoodsRes(Request $request){
        //dd($request);

        if(!$request -> input('peisongfangshi')){
            $peisong = [1];
        }else{
            $peisong = $request -> input('peisongfangshi');
        }
        $savearr = [
            'title' => $request -> input('title'),
            'price_pre' => $request -> input('price_pre'),
            'price_no' => $request -> input('price_no'),
            'price_jin' => $request -> input('price_jin'),
            'price_kuaidi' => $request -> input('price_kuaidi'),
            'kucun' => $request -> input('kucun'),
            'xiangou' => $request -> input('xiangou'),
            'content' => $request -> input('content'),
            'type' => $request -> input('type'),
            'gongying_id' => $request -> input('gongying_id'),
            'peisongfangshi' => implode(',',$peisong),
            'status' => $request -> input('status'),
            'zitiaddress' => $request -> input('zitiaddress'),
            'updated_at' => time(),
            'created_at' => time(),
        ];

        $file = $request -> file('img');
        if($file && $file -> isValid()){
            //如果上传成功
            $ext = $file -> getClientOriginalExtension();
            $realpath = $file  -> getRealPath();
            $filename = date('Y-m-d-H:i:s').'-'.uniqid().'.'.$ext;
            $bool = Storage::disk('uploads')->put($filename,file_get_contents($realpath));
            $savearr['img'] = $filename;
        }

        $files = $request -> file('imgs');
        //dd($files);
        if($files){
            foreach($files as $vo){
                //如果上传成功
                $ext_temp = $vo -> getClientOriginalExtension();
                $path_temp = $vo  -> getRealPath();
                $filename_temp = date('Y-m-d-H:i:s').'-'.uniqid().'.'.$ext_temp;
                $bool = Storage::disk('uploads')->put($filename_temp,file_get_contents($path_temp));
                $temp_imgs[] = $filename_temp;
            }
            $savearr['imgs'] = implode(',',$temp_imgs);

        }

        //dd($savearr);


        $res = DB::table('goods') ->  insert($savearr);
        if($res){
            return redirect('admin/goods')->with([
                'insertres' => 'yes'
            ]);
        }
    }

    public function editGoods($id){
        //分类
        $res_fenlei = DB::table('fenlei') -> get();
        $res_fenlei = $this->object_array($res_fenlei);

        $res_gongying = DB::table('gongyingshang') -> get();
        $res_gongying = $this->object_array($res_gongying);

        $res = DB::table('goods') -> where(['id'=>$id]) -> first();
        $res -> peisongfangshi = explode(',',$res -> peisongfangshi);
        return view('admin/mall/addGoods') -> with([
            'res'=>$res,
            'res_fenlei' => $res_fenlei,
            'res_gongying' => $res_gongying
        ]);
    }

    public function editGoodsRes(Request $request){
        $savearr = [
            'title' => $request -> input('title'),
            'price_pre' => $request -> input('price_pre'),
            'price_no' => $request -> input('price_no'),
            'price_jin' => $request -> input('price_jin'),
            'price_kuaidi' => $request -> input('price_kuaidi'),
            'kucun' => $request -> input('kucun'),
            'xiangou' => $request -> input('xiangou'),
            'content' => $request -> input('content'),
            'type' => $request -> input('type'),
            'gongying_id' => $request -> input('gongying_id'),
            'peisongfangshi' => $request -> input('peisongfangshi'),
            'updated_at' => time(),
            'created_at' => time(),
        ];

        $file = $request -> file('img');
        if($file && $file -> isValid()){
            //如果上传成功
            $ext = $file -> getClientOriginalExtension();
            $realpath = $file  -> getRealPath();
            $filename = date('Y-m-d-H:i:s').'-'.uniqid().'.'.$ext;
            $bool = Storage::disk('uploads')->put($filename,file_get_contents($realpath));
            $savearr['img'] = $filename;
        }

        $files = $request -> file('imgs');
        //dd($files);
        if($files){
            foreach($files as $vo){
                //如果上传成功
                $ext_temp = $vo -> getClientOriginalExtension();
                $path_temp = $vo  -> getRealPath();
                $filename_temp = date('Y-m-d-H:i:s').'-'.uniqid().'.'.$ext_temp;
                $bool = Storage::disk('uploads')->put($filename,file_get_contents($path_temp));
                $temp_imgs[] = $filename_temp;
            }
            $savearr['imgs'] = implode(',',$temp_imgs);

        }




        $res = DB::table('goods') -> where(['id'=>$request -> input('id')]) ->  update($savearr);
        if($res){
            return redirect('admin/goods')->with([
                'editres' => 'yes'
            ]);
        }
    }

    public function deleteGoods(Request $request){
        $res = DB::table('goods') -> where(['id'=>$request->input('id')]) -> delete();

        if($res){
            echo 'success';
        }
    }

}
