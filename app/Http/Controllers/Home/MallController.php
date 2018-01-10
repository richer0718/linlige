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
        $options = [
            'app_id'  => config('wxsetting.appid'),         // AppID
            'secret'  => config('wxsetting.secret'),     // AppSecret

        ];
        $app = new Application($options);
        if (empty($_GET['code'])) {
            $currentUrl = url()->full();; // 获取当前页 URL
            //var_dump($currentUrl);exit;
            $response = $app->oauth->scopes(['snsapi_base'])->redirect($currentUrl);
            return $response; // or echo $response;
        }

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
        $options = [
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id'  => config('wxsetting.appid'),         // AppID
            'secret'  => config('wxsetting.secret'),     // AppSecret
        ];
        $app = new Application($options);
        if (empty($_GET['code'])) {
            $currentUrl = url()->full();; // 获取当前页 URL
            //var_dump($currentUrl);exit;
            $response = $app->oauth->scopes(['snsapi_base'])->redirect($currentUrl);
            return $response; // or echo $response;
        }
        $js = $app -> js;




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
            'people' => $newarr,
            'js' => $js
        ]);
    }

    public function buynow($id,$number){
        return redirect('home/buypay') -> with('data',[$id,$number]);
    }
    public function buypay(){
        $id = session('data')[0];
        $number = session('data')[1];
        if(!$id){
            $this -> redirect('home/mall');
        }

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
    public function payRequest(Request $request){
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


        $goods_info = DB::table('goods') -> where([
            'id' => $request -> input('id')
        ]) -> first();
        $price = intval($goods_info -> price_no * $request -> input('number') * 100 );
        //看需不需要快递费
        if($request -> input('get_type') == 1){
            $price = intval(($goods_info -> price_no * $request -> input('number') +$goods_info -> price_kuaidi)* 100 );
        }
        $price = 1;
        $order_id = date("YmdHis").rand(1,10000);
        $attributes = [
            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
            'body'             => $goods_info -> title,
            'detail'           => $goods_info -> title,
            'out_trade_no'     => $order_id,
            'total_fee'        => $price, // 单位：分
            'notify_url'       => config('wxsetting.noticy_url'), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid'           => session('openid'), // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
        $order = new Order($attributes);
        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            $prepayId = $result->prepay_id;
        }

        if($result -> prepay_id){
            $res = DB::table('order') -> insert([
                'goods_id' => $request -> input('id'),
                'number' => $request -> input('number'),
                'address' => $request -> input('address'),
                'remark' => $request -> input('remark'),
                'created_at' => time(),
                'updated_at' => time(),
                'openid' => session('openid'),
                'order_id' => $order_id,
                'fukuan_status' => 0,
                'status' => 4,
                'show_status' => '待付款',
                'peisong_type' => $request -> input('get_type')
            ]);
        }



        $config = $payment->configForJSSDKPayment($prepayId); // 返回数组
        //dd($config);
        return response() -> json($config);


    }

    //支付成功回调地址
    public function payNotify(){
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
                'notify_url'         => config('wxsetting.noticy_url'),      // 你也可以在下单时单独设置来想覆盖它
            ],
            'log' => [
                'level'      => 'debug',
                'permission' => 0777,
                'file'       => storage_path('/tmp/easywechat/easywechat_'.date('Ymd').'.log'),
            ],
        ];
        $app = new Application($options);
        $response = $app->payment->handleNotify(function($notify, $successful){
            if($successful){
                //支付成功
                //根据订单号更新状态
                DB::table('order') -> where([
                    'order_id' => $notify -> out_trade_no
                ]) -> update([
                    'status' => 0,
                    'fukuan_status' => 1,
                    'show_status' => '待收货',

                ]);
                //发送模版消息
                $info = DB::table('user') -> where([
                    'openid' => $notify -> openid
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
                    'touser' => $notify -> openid,
                    'template_id' => config('wxsetting.moban1'),
                    'url' => config('wxsetting.url4'),
                    'data' => [
                        'first' => '尊敬的'.$info -> name,
                        'keyword1' => '您的订单已提交，请等待发货~',
                        'keyword2' => date('Y-m-d'),
                        'keyword3' => '',
                        'remark' => '感谢您的使用'
                    ],
                ]);

                DB::table('message') -> insert([
                    'openid' => $info -> openid,
                    'message' => '您的订单已提交，请等待发货~',
                    'created_at' => time()
                ]);


            }
            return true; // 或者错误消息
        });
        return $response;
    }


    /*
{
    "appid":"wx47ffe2d49c271963",
    "bank_type":"CFT",
    "cash_fee":"1",
    "fee_type":"CNY",
    "is_subscribe":"Y",
    "mch_id":"1453827602",
    "nonce_str":"5a0241caee13e",
    "openid":"oODSSwLXWcSRPF6gTWW2fUMlmtHk",
    "out_trade_no":"201711080729143866",
    "result_code":"SUCCESS",
    "return_code":"SUCCESS",
    "sign":"49E7AF536CBD626EE8B06FD138FFE0A6",
    "time_end":"20171108072919",
    "total_fee":"1",
    "trade_type":"JSAPI",
    "transaction_id":"4200000022201711083186568462"
}

    */


}
