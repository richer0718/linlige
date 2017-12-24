<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Support\Url as UrlHelper;

class WuyeController extends Controller
{
    //
    public function login(){
        if(!session('openid')){
            $options = [
                /**
                 * 账号基本信息，请从微信公众平台/开放平台获取
                 */
                'app_id'  => config('wxsetting.appid'),         // AppID
                'secret'  => config('wxsetting.secret'),     // AppSecret
            ];
            $app = new Application($options);
            if (empty($_GET['code'])) {
                $currentUrl = UrlHelper::current(); // 获取当前页 URL
                $response = $app->oauth->scopes(['snsapi_base'])->redirect($currentUrl);
                return $response; // or echo $response;
            }

            $user = $app->oauth->user();

            if($user){
                session([
                    'openid' => $user->getId(),
                ]);
            }
        }


        //dd(session());
        if(session('login_type') == 'wuye' && session('xiaoqu') && session('username')){
            return redirect('home/wuye/index');
        }
        return view('home/wuye/login');
    }

    public function loginRes(Request $request){
        //dd($request);
        $res = DB::table('business') -> where([
            'username' => $request -> input('username'),
            'password' => $request -> input('password'),
            'type' => 1
        ]) -> first();
        if($res){
            //查看他跟openid 绑定了没有
            if(!$res -> openid){
                DB::table('business') -> where([
                    'id' => $res -> id
                ]) -> update([
                    'openid' => session('openid')
                ]);
            }
            //保存物业用户名称
            session([
                'username' => $request -> input('username'),
                'xiaoqu' => $res -> xiaoqu,
                'login_type' => 'wuye'
            ]);
            echo 'success';
        }else{
            echo 'error';
        }
    }

    public function index(){
        //把未解决的news找出来
        //$where['type'] = 0;
        //$where['status'] = 0;
        $res = DB::table('news') -> where(function($query){
            $query -> where('type','=','0');
            $query -> where('status','=','0');
            $query -> where('huifu','<>','');
            $query -> where('xiaoqu','=',session('xiaoqu'));
        })  -> get();
        //dd($res);
        $res = $this -> object_array($res);
        foreach($res as $k=> $vo){
            if($vo['img']){
                $res[$k]['img'] = explode(',',$vo['img']);
            }

            $res[$k]['userinfo'] = DB::table('user') -> where([
                'openid' => $vo['openid'],
            ]) -> first();

            $temp = DB::table('wuye_huifu') -> where([
                'news_id' => $vo['id']
            ]) -> get();
            //查找每条的物业回复
            $res[$k]['wuyehuifu']= $temp;
            if($temp){
                foreach($temp as $key =>$value ){
                    $temp[$key] -> imgs = explode(',',$value -> imgs);
                }
            }

        }
        //dd($res);
        return view('home/wuye/index') -> with([
            'res' => $res
        ]);
    }

    public function wuyehuifu(Request $request){
        //存储物业回复
        DB::table('wuye_huifu') -> insert([
            'news_id' => $request -> input('id'),
            'content' => $request -> input('content'),
            'imgs' => $request -> input('img'),
            'username' => session('username')
        ]);

        //给此人发送模版消息
        $data = DB::table('news') -> where([
            'id' => $request -> input('id')
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
            'url' => config('wxsetting.superurl'),
            'data' => [
                'first' => '尊敬的'.$info -> name,
                'keyword1' => '您的建议有了新回复，点击查看',
                'keyword2' => date('Y-m-d'),
                'keyword3' => '',
                'remark' => '感谢您的使用'
            ],
        ]);

        DB::table('message') -> insert([
            'openid' => $info -> openid,
            'message' => '您的建议有了新回复，点击查看',
            'created_at' => time()
        ]);


        echo 'success';
    }

    public function jiejue(Request $request){
        DB::table('news') -> where([
            'id'=> $request -> input('id')
        ]) -> update([
            'status' => '1'
        ]);

        //给此人发送模版消息
        $data = DB::table('news') -> where([
            'id' => $request -> input('id')
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
            'url' => config('wxsetting.superurl'),
            'data' => [
                'first' => '尊敬的'.$info -> name,
                'keyword1' => '您的建议已得到了处理回复，点击查看',
                'keyword2' => date('Y-m-d'),
                'keyword3' => '',
                'remark' => '感谢您的使用'
            ],
        ]);
        DB::table('message') -> insert([
            'openid' => $info -> openid,
            'message' => '您的建议已得到了处理回复，点击查看',
            'created_at' => time()
        ]);



        echo 'success';
    }


}
