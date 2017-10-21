<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        /*
        if(!session('username')){
            $this->middleware(function ($request, $next) {
                return redirect('admin/login');
            });
        }
        */
    }

    //订单列表
    public function index(){
        if(!session('username')){
            return redirect('admin/login');
        }

        $res = DB::table('order') -> where(function ($query) {
            if(!empty($_POST['orderid'])){
                $query -> where('order_id','=',$_POST['orderid']);
            }
            if(!empty($_POST['ordername'])){
                //下单人
                $query -> where('ordername','=',$_POST['ordername']);
            }
            if(!empty($_POST['peisong'])){
                $query -> where('peisong_type','=',$_POST['peisong']);
            }
            if(!empty($_POST['status'])){
                $query -> where('status','=',$_POST['status']);
            }
            if(!empty($_POST['shouhou'])){
                $query -> where('shouhou_status','=',$_POST['shouhou']);
            }

            if(!empty($_POST['createtime_left'])){
                $query -> where('created_at','>',strtotime($_POST['createtime_left']));
            }

            if(!empty($_POST['createtime_right'])){
                $query -> where('created_at','<',strtotime($_POST['createtime_right']));
            }
            if(!empty($_POST['gongyingshang'])){
                //先通过供应商找商品
                $goods = DB::table('goods') -> where('gongying_id','=',$_POST['gongyingshang']) -> get();
                if($goods){
                    //dump($goods);
                    foreach($goods as $vo){
                        $goods_ids[] = $vo -> id;
                    }
                    //dump($goods_ids);
                    $query -> whereIn('goods_id',$goods_ids);
                }else{
                    $query -> where('goods_id',9999);
                }

                //dd($goods);
                //$query -> where();
            }


        })  -> paginate(15);
        //dd($res);

        foreach($res as $k => $vo){
            $res[$k] -> goods_info = DB::table('goods') -> where([
                'id' => $vo -> goods_id,

            ]) -> first();

            $res[$k] -> user_info = DB::table('user') -> where([
                'openid'=> $vo -> openid
            ]) -> first();

        }

        //供应商配置
        $gongyingshang = DB::table('gongyingshang') -> get();
        //dd($res);
        return view('admin/order/index') -> with([
            'res' => $res,
            'gongyingshang' => $gongyingshang
        ]);
    }

    public function orderDetail($id){
        $res = DB::table('order') -> where([
            'id' => $id
        ]) -> first();
        $res -> goods_info = DB::table('goods') -> where([
            'id' => $res -> goods_id
        ]) -> first();
        $res -> gongying = DB::table('gongyingshang') -> where([
            'id' => $res -> goods_info -> gongying_id
        ]) -> first();

        if($res -> imgs){
            $res -> imgs = explode(',',$res -> imgs);
        }

        if($res -> tuikuan_imgs){
            $res -> tuikuan_imgs = explode(',',$res -> tuikuan_imgs);
        }




        //dd($res);
        //var_dump($res -> goods_info);exit;
        return view('admin/order/orderdetail') -> with([
            'res' => $res
        ]);
    }

    public function tuihuolist(){
        $res = DB::table('order') -> get();
        //dd($res);

        foreach($res as $k => $vo){
            $res[$k] -> goods_info = DB::table('goods') -> where([
                'id' => $vo -> goods_id,

            ]) -> first();

            $res[$k] -> user_info = DB::table('user') -> where([
                'openid'=> $vo -> openid
            ]) -> first();

        }
        //dd($res);
        return view('admin/order/tuihuolist') -> with([
            'res' => $res
        ]);
    }

    public function fahuo($id){
        //修改发货状态
        DB::table('order') -> where([
            'id' => $id
        ]) -> update([
            'fahuo_status' => 1,
            'show_status' => '已发货',
        ]);

        return redirect('admin/orderlist');
    }
}
