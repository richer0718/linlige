<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Support\Url as UrlHelper;

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
            if(!empty($_POST['fukuan'])){
                $query -> where('fukuan_status','=',$_POST['fukuan']);
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

    public function fahuoRes(Request $request){
        DB::table('order') -> where([
            'id' => $request -> input('id')
        ]) -> update([
            'kuaidi' => $request -> input('kuaidi'),
            'danhao' => $request -> input('danhao'),
            'fahuo_status' => 1,
            'show_status' => '已发货',
        ]);

        //发送模版消息
        //查找openid
        $data = DB::table('order') -> where([
            'id' => $request -> input('id')
        ]) -> first();
        $goods_info = DB::table('goods') -> where([
            'id' => $data -> goods_id
        ]) -> first();
        $info = DB::table('user') -> where([
            'openid' => $data -> openid
        ]) -> first();
        $options = [
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id'  => config('wxsetting.appid'),         // AppID
            'secret'  => config('wxsetting.secret'),     // AppSecret
        ];
        $app = new Application($options);
        $notice = $app->notice;
        $messageId = $notice->send([
            'touser' => $info -> openid,
            'template_id' => config('wxsetting.moban1'),
            'url' => config('wxsetting.url5'),
            'data' => [
                'first' => '尊敬的'.$info -> name,
                'keyword1' => '您的宝贝'.$goods_info -> title.'已发货，物流单号为'.$request -> input('danhao').'，请注意查收~',
                'keyword2' => date('Y-m-d'),
                'keyword3' => '',
                'remark' => '感谢您的使用'
            ],
        ]);

        return redirect('admin/orderlist') -> with('fahuores','yes');

    }

    //同意退款
    public function agree($id){
        $orderinfo = DB::table('order') -> where([
            'id' => $id
        ]) -> first();
        //给他发模版消息

        $info = DB::table('user') -> where([
            'openid' => $orderinfo -> openid
        ]) -> first();
        $options = [
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id'  => config('wxsetting.appid'),         // AppID
            'secret'  => config('wxsetting.secret'),     // AppSecret
        ];
        $app = new Application($options);
        $notice = $app->notice;
        $messageId = $notice->send([
            'touser' => $info -> openid,
            'template_id' => config('wxsetting.moban1'),
            'url' => config('wxsetting.url5'),
            'data' => [
                'first' => '尊敬的'.$info -> name,
                'keyword1' => '您的退货申请通过了，请等待退款',
                'keyword2' => date('Y-m-d'),
                'keyword3' => '',
                'remark' => '感谢您的使用'
            ],
        ]);

        return redirect('/admin/orderDetail/'.$id)->with('tuihuo','agree');
    }

    public function jujue($id){
        $orderinfo = DB::table('order') -> where([
            'id' => $id
        ]) -> first();
        //给他发模版消息

        $info = DB::table('user') -> where([
            'openid' => $orderinfo -> openid
        ]) -> first();
        $options = [
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id'  => config('wxsetting.appid'),         // AppID
            'secret'  => config('wxsetting.secret'),     // AppSecret
        ];
        $app = new Application($options);
        $notice = $app->notice;
        $messageId = $notice->send([
            'touser' => $info -> openid,
            'template_id' => config('wxsetting.moban1'),
            'url' => config('wxsetting.url5'),
            'data' => [
                'first' => '尊敬的'.$info -> name,
                'keyword1' => '您的退货申请被拒绝了',
                'keyword2' => date('Y-m-d'),
                'keyword3' => '',
                'remark' => '感谢您的使用'
            ],
        ]);

        return redirect('/admin/orderDetail/'.$id)->with('tuihuo','agree');
    }

}
