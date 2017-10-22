<?php

namespace App\Http\Controllers\Home;

use App\WxModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Foundation\Application;

class MallController extends Controller
{
    public function indexJump(){
        $url = urlencode('http://m.tianluyangfa.com/laravel/public/home/mall');

        $appId = 'wx68099d0c30ed4f39';

        $trueurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appId."&redirect_uri=".$url."&response_type=code&scope=snsapi_base&state=state#wechat_redirect";
        //echo 11;exit;
        //dump($trueurl);
        //Header("Location: $trueurl");
        header("Location: $trueurl");exit;
    }

    //商城
    public function index(){
        $model = new WxModel();
        $model -> checkOpenid();
        //轮播
        $res_lunbo = DB::table('lunbo') -> get();
        //找出真实连接
        foreach($res_lunbo as $k => $vo){
            if($vo -> url_out){
                $res_lunbo[$k] -> url_true = $vo -> url_out;
            }else{
                $res_lunbo[$k] -> url_true = url('home/malldetail').'/'.$vo -> url_in;
            }

        }
        $res = DB::table('goods') -> get();

        //分类
        $res_fenlei = DB::table('fenlei') -> get();

        //dd($res);
        return view('home/mall')->with([
            'res' => $res,
            'res_lunbo' => $res_lunbo,
            'res_fenlei' => $res_fenlei
        ]);
    }


    public function malldetail($id){
        $res = DB::table('goods') -> where(['id'=>$id]) -> first();
        if($res -> imgs){
            $res -> imgs = explode(',',$res -> imgs);
        }

        //找评论
        $orders = DB::table('order') -> where([
            'goods_id' => $id
        ]) -> get();

        foreach($orders as $k => $vo){
            $orders[$k] -> userinfo = DB::table('user') -> where([
                'openid' => $vo -> openid
            ]) -> first();
            if($vo -> imgs){
                $orders[$k] -> imgs = explode(',',$vo -> imgs);
            }
        }

        //找买过的人
        $peoples = DB::table('order') -> select('openid') -> groupBy('openid') -> get();
        if($peoples){
            $newarr = [];
            foreach($peoples as $vo){
                $newarr[] = DB::table('user') -> where([
                    'openid' => $vo -> openid
                ]) -> first();
            }
        }
        //dd($newarr);

        //dd($orders);
        return view('home/malldetail') -> with([
            'res' => $res,
            'orders' => $orders,
            'people' => $newarr
        ]);
    }

    public function buynow($id,$number){
        $options = [
            /**
             * Debug 模式，bool 值：true/false
             *
             * 当值为 false 时，所有的日志都不会记录
             */
            'debug'  => true,
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id'  => 'wx68099d0c30ed4f39',         // AppID
            'secret'  => 'd4624c36b6795d1d99dcf0547af5443d',     // AppSecret
            'token'   => 'yangxiaojie',          // Token
            'log' => [
                'level'      => 'debug',
                'permission' => 0777,
                'file'       => storage_path('/tmp/easywechat/easywechat_'.date('Ymd').'.log'),
            ],
        ];
        $app = new Application($options);
        $js = $app -> js;

        //dd(session('openid'));
        $res = DB::table('goods') -> where(['id'=>$id]) -> first();
        $res -> peisongfangshi = explode(',',$res -> peisongfangshi);
        return view('home/mall/buynow') -> with([
            'res' => $res,
            'number' => $number,
            'js' => $js
        ]);
    }

    //支付
    public function payAjax(Request $request){
        //dd($request);
        $res = DB::table('order') -> insert([
            'goods_id' => $request -> input('id'),
            'number' => $request -> input('number'),
            'address' => '上海市浦东新区',
            'remark' => $request -> input('remark'),
            'created_at' => time(),
            'updated_at' => time(),
            'openid' => session('openid'),
            'order_id' => time().rand(1000,9999),
            'fukuan_status' => 1,
            'show_status' => '待收货',
        ]);
        echo 'success';


    }



}
