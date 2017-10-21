<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HuodongController extends Controller
{
    //





    public function index($type = 0 ){
        if(!session('username')){
            return redirect('admin/login');
        }

        //根据type 查找
        $res = DB::table('news') -> where(function($query) use($type){
            $query -> where('type','=',$type);
            $query -> where('xiaoqu','=',session('xiaoqu'));
            if(!empty($_POST['keywords'])){
                $query -> where('title','like','%'.$_POST['keywords'].'%');
            }

        }) -> paginate('15');
        //dd($res);
        foreach($res as $k => $vo){
            $res[$k] -> userinfo = DB::table('user') -> where([
                'openid' => $vo -> openid
            ])  -> first();
        }
        //dd($res);
        switch ($type){
            case 0;$type_res ='邻里说';break;
            case 1;$type_res ='友邻互助';break;
            case 2;$type_res ='社区活动';break;
            case 3;$type_res ='共享车位';break;
            case 4;$type_res ='跳蚤市场';break;
        }



        return view('admin/huodong/index') -> with([
            'res' => $res,
            'type' => $type_res,
            'index' => $type
        ]);
    }

    public function changeLinli($id,$flag,$index){
        if($flag == 0){
            DB::table('news') -> where([
                'id' => $id
            ]) -> update([
                'flag' => 1
            ]);
        }else{
            DB::table('news') -> where([
                'id' => $id
            ]) -> update([
                'flag' => 0
            ]);
        }

        return redirect('admin/linlihudong/'.$index);
    }

    public function checkPinlun($id){
        /*
        $res = DB::table('news') -> where([
            'id' => $id
        ]) -> first();
        */
        //查找评论
        $res_pinlun = DB::table('pinlun') -> where([
            'news_id' => $id
        ]) -> get();
        if($res_pinlun){
            foreach ($res_pinlun as $k => $item) {
                $res_pinlun[$k] -> userinfo = DB::table('user') -> where([
                    'openid' => $item -> openid
                ]) -> first();
            }
        }
        //$res -> img = explode(',',$res -> img);

        //dd($res_pinlun);
        return view('admin/huodong/checkPinlun') -> with([
            'res' => $res_pinlun
        ]);
    }

    public function changeStatus($id,$status,$newsid){
        DB::table('pinlun') -> where([
            'id' => $id
        ]) -> update([
            'flag' => $status
        ]);
        return redirect('admin/checkPinlun/'.$newsid);

    }
}
