<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Support\Url as UrlHelper;

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

    public function sendMessage(Request $request){
        if($request -> input('first')){

            $options = [
                /**
                 * 账号基本信息，请从微信公众平台/开放平台获取
                 */
                'app_id'  => config('wxsetting.appid'),         // AppID
                'secret'  => config('wxsetting.secret'),     // AppSecret
            ];
            $app = new Application($options);
            $notice = $app->notice;
            $users = DB::table('user') -> select('openid') ->  where('status',1) ->  get();
            foreach($users as $key => $vo){
                $user = $app->user->get($vo -> openid);
                if(!isset($user -> subscribe_time)){
                    continue;
                }
                echo $key;echo "<br>";continue;
                $messageId = $notice->send([
                    'touser' => $vo -> openid,
                    'template_id' => config('wxsetting.moban2'),
                    'url' => config('wxsetting.superurl'),
                    'data' => [
                        'first' => $request -> input('first'),
                        'keyword1' => '所有会员',
                        'keyword2' => date('Y-m-d'),
                        'remark' => '祝邻居们有个美好的一天！'
                    ],
                ]);
            }


            return view('admin/sendMessage')->with([
                'sendres' => 'success'
            ]);

        }
        return view('admin/sendMessage')->with([
            'sendres' => 'no'
        ]);
    }


}
