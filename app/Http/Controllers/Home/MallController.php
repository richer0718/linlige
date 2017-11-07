<?php

namespace App\Http\Controllers\Home;

use App\WxModel;
use EasyWeChat\Url\Url;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Support\Url as UrlHelper;
use EasyWeChat\Payment\Order;

class MallController extends Controller
{
    public function indexJump(){
        $url = urlencode(config('wxsetting.url2'));

        $appId = config('wxsetting.appid');

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
        return redirect('home/buypay') -> with('data',[$id,$number]);
    }
    public function buypay(){
        $id = session('data')[0];
        $number = session('data')[1];
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
            'app_id'  => config('wxsetting.appid'),         // AppID
            'secret'  => config('wxsetting.secret'),     // AppSecret
            //'token'   => 'yangxiaojie',          // Token
            'payment' => [
                'merchant_id'        => config('wxsetting.machid'),
                'key'                => config('wxsetting.businesskey'),
                'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
                'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
                'notify_url'         => 'https://tianluyangfa.com',       // 你也可以在下单时单独设置来想覆盖它
                // 'device_info'     => '013467007045764',
                // 'sub_app_id'      => '',
                // 'sub_merchant_id' => '',
                // ...
            ],
            'log' => [
                'level'      => 'debug',
                'permission' => 0777,
                'file'       => storage_path('/tmp/easywechat/easywechat_'.date('Ymd').'.log'),
            ],
        ];
        $app = new Application($options);
        $payment = $app->payment;
        //$config = $payment->configForJSSDKPayment($prepayId);

        if (empty($_GET['code'])) {
            $currentUrl = url()->full();; // 获取当前页 URL
            //var_dump($currentUrl);exit;
            $response = $app->oauth->scopes(['snsapi_base'])->redirect($currentUrl);
            return $response; // or echo $response;
        }



        $js = $app -> js;

        //dd(session('openid'));
        $res = DB::table('goods') -> where(['id'=>$id]) -> first();
        $res -> peisongfangshi = explode(',',$res -> peisongfangshi);
        return view('home/mall/buynow') -> with([
            'res' => $res,
            'number' => $number,
            //'configForPickAddress' => $configForPickAddress,
            'js' => $js
        ]);
    }

    //下单
    public function payRequest(){
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
            'app_id'  => config('wxsetting.appid'),         // AppID
            'secret'  => config('wxsetting.secret'),     // AppSecret
            //'token'   => 'yangxiaojie',          // Token
            'payment' => [
                'merchant_id'        => config('wxsetting.machid'),
                'key'                => config('wxsetting.businesskey'),
                'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
                'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
                'notify_url'         => 'https://tianluyangfa.com',       // 你也可以在下单时单独设置来想覆盖它
                // 'device_info'     => '013467007045764',
                // 'sub_app_id'      => '',
                // 'sub_merchant_id' => '',
                // ...
            ],
            'log' => [
                'level'      => 'debug',
                'permission' => 0777,
                'file'       => storage_path('/tmp/easywechat/easywechat_'.date('Ymd').'.log'),
            ],
        ];
        $app = new Application($options);
        $payment = $app->payment;

        $attributes = [
            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
            'body'             => 'iPad mini 16G 白色',
            'detail'           => 'iPad mini 16G 白色',
            'out_trade_no'     => date("YmdHis").rand(1,10000),
            'total_fee'        => 789, // 单位：分
            'notify_url'       => config('wxsetting.noticy_url'), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid'           => session('openid'), // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
        $order = new Order($attributes);
        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            $prepayId = $result->prepay_id;
        }
        $config = $payment->configForJSSDKPayment($prepayId); // 返回数组
        //dd($config);
        return response() -> json($config);


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
