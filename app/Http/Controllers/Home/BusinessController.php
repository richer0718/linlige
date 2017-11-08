<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Support\Url as UrlHelper;
use EasyWeChat\Payment\Order;


//商户登陆
class BusinessController extends Controller
{
    //
    public function login(){
        if(session('login_type') == 'business'){
            $res = DB::table('business') -> where([
                'username' => session('username'),
                'password' => session('password'),
                'status' => 0,
                'type' => 0
            ]) -> first();
            if($res){
                return redirect('home/business/index');
            }
        }
        return view('home/business/login');
    }

    public function loginRes(Request $request){
        //dd($request);
        $res = DB::table('business') -> where([
            'username' => $request -> input('username'),
            'password' => $request -> input('password'),
            'status' => 0,
            'type' => 0
         ]) -> first();
        if($res){
            //保存 此商户的username
            session([
                'username' => $res -> username,
                'password' => $res -> password,
                'login_type'=>'business',

            ]);
            echo 'success';
        }else{
            echo 'error';
        }
    }

    public function index($ids = null){
        //我的客户
        //dd(session('username'));
        //找他自己的服务
        $myservice = DB::table('service') -> where([
            'username' => session('username'),
            'flag' => 0
        ]) -> orderBy('created_at','desc') -> get();
        $newarr = [];
        $titlearr = [];

        //通过服务小区号 找到小区
        if(count($myservice)){
            foreach($myservice as $k => $vo){
                $service_ids[] = $vo -> id;
            }
            $mycustomer = DB::table('boda_service') -> whereIn(
                'service_id',$service_ids
            ) -> get();
            foreach($mycustomer as $k => $vo){
                $mycustomer[$k] -> userinfo = DB::table('user') -> where([
                    'openid' => $vo -> openid
                ]) -> first();
            }

            //我发布的服务
            //将自己的服务分组
            $newarr = array();
            foreach($myservice as $vo){
                $newarr[$vo->created_at][] = $vo;
                $titlearr[$vo->created_at]['title'] = $vo -> title;
                $titlearr[$vo->created_at]['tel'] = $vo -> tel;
            }
            //dd($newarr);
            //找服务下小区的名称
            foreach($newarr as $k => $vo){

                foreach($vo as $key => $vol){
                    $vo[$key]->xiaoquinfo = DB::table('shequ') -> where([
                        'id' => $vol -> xiaoqu
                    ]) -> first();
                }
                //dd($vo);
            }

        }else{
            $mycustomer = null;
        }


        $select_res = DB::table('shequ') -> get();


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


        //dd($newarr);
        return view('home/business/index')->with([
            'select_res' => $select_res,
            'mycustomer' => $mycustomer,
            'newarr' => $newarr,
            'titlearr' => $titlearr,
            'js' => $js
        ]);
    }




    //发布服务
    public function fabufuwu($ids = null){
        $newarr = array();
        if($ids){
            $ids = explode(',',$ids);
            foreach($ids as $vo){
                $newarr[] = DB::table('shequ') -> where([
                    'id' => $vo
                ]) -> first();
            }
            //dd($newarr);
        }
        return view('home/business/fabu') ->with([
            'newarr' => $newarr
        ]);
    }
    //发布服务选择小区
    public function selectxiaoqu(){
        $res = DB::table('shequ') -> get();
        return view('home/business/selectxiaoqu') -> with([
            'res' => $res
        ]);
    }

    public function fabuRes(Request $request){

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
        $price = intval($request -> input('price')) * 100;
        $price = 1;

        $order_id = date("YmdHis").rand(1,10000);
        $attributes = [
            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
            'body'             => $request -> input('servicename'),
            'detail'           => $request -> input('servicename'),
            'out_trade_no'     => $order_id,
            'total_fee'        => $price, // 单位：分
            'notify_url'       => config('wxsetting.noticy_url_service'), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid'           => session('openid'), // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
        $order = new Order($attributes);
        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            $prepayId = $result->prepay_id;
        }

        if($prepayId){
            $ids = $request -> input('ids');
            $ids = trim($ids,',');
            $ids = explode(',',$ids);

            foreach($ids as $vo){
                //插入 service
                DB::table('service') -> insert([
                    'title' => $request -> input('servicename'),
                    'username' => session('username'),
                    'tel' => $request -> input('tel'),
                    'type' => $request -> input('type'),
                    'created_at' => time(),
                    'updated_at' => time(),
                    'xiaoqu' => $vo,
                    'boda' => 0,
                    'dianzan' => 0,
                    'flag' => 1,
                    'order_id' => $order_id
                ]);
            }
        }


        $config = $payment->configForJSSDKPayment($prepayId); // 返回数组
        //dd($config);
        return response() -> json($config);





    }


    //申请商户
    public function shenqing(){
        if(!session('openid')){
            echo 'error';exit;
        }else{
            //如果有openid 判断他是否注册 并通过审核
            $is_check = DB::table('user') -> where([
                'openid' => session('openid'),
                'status' => 1
            ]) -> first();
            if(!$is_check){
                //没有通过审核
                echo 'error';exit;
            }else{
                //判断下 他是业主还是啥
                //0产权人1居民2业委会主任3业委会副主任4业委会秘书5业委会委员6业主代表
                $shenfen = $is_check -> shenfen ;
                if($shenfen == 0){
                    return response() -> json($is_check);
                }else{
                    //非业主 要填写
                    echo 'showpage';exit;
                }
            }
        }
    }

    //支付成功回调地址
    public function payServiceNotify(){
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
                DB::table('business') -> where([
                    'order_id' => $notify -> out_trade_no
                ]) -> update([
                    'flag' => 0,
                ]);

            }
            return true; // 或者错误消息
        });
        return $response;
    }


    public function shenqingPage(){
        return view('home/business/shenqing');
    }
    public function shenqingBusinessRes(Request $request){
        //申请
        $is_shenqing = DB::table('shenqing') -> where([
            'tel' => $request -> input('tel')
        ]) -> first();
        if($is_shenqing){
            echo 'isset';exit;
        }
        DB::table('shenqing') -> insert([
            'tel' => $request -> input('tel'),
            'name' => $request -> input('name'),
            'created_at' => time()
        ]);
        echo 'success';
    }
}
