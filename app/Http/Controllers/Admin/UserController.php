<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Foundation\Application;

class UserController extends Controller
{
    //
    public function __construct()
    {

    }

    public function index($type){
        if(!session('username')){
            return redirect('admin/login');
        }

        $res = DB::table('user') -> where(function($query) use($type){
            $query -> where('xiaoqu','=',session('xiaoqu'));
            if(isset($_POST['status'])){
                $query -> where('status','=',$_POST['status']);
            }
            if(!empty($_POST['keywords'])){
                $query -> where('name','like','%'.$_POST['keywords'].'%');
            }
            $query -> where('status','=',$type);
        }) -> paginate('15');
        //dd($res);
        $shenfen_arr = [
            '产权人',
            '居民',
            '业委会主任',
            '业委会副主任',
            '业委会秘书',
            '业委会委员',
            '业主代表',
            '社区管理员'
        ];
        foreach($res as $k => $vo){
            $res[$k] -> xiaoqu = DB::table('shequ') -> where(['id'=>$vo->xiaoqu]) -> first();
            $res[$k] -> shenfen = $shenfen_arr[$vo -> shenfen];
        }


        //dd($res);
        return view('admin/user/index') -> with([
            'res' => $res,
            'type' => $type
        ]);
    }

    //用户详情
    public function userdetail($id){
        $shenfen_arr = [
            '产权人',
            '居民',
            '业委会主任',
            '业委会副主任',
            '业委会秘书',
            '业委会委员',
            '业主代表',
            '社区管理员'
        ];

        $res = DB::table('user') -> where([
            'id' => $id
        ]) -> first();
        $res -> xiaoqu = DB::table('shequ') -> where([
            'id' => $res -> xiaoqu
        ]) -> first();
        $res -> shenfenname = $shenfen_arr[$res -> shenfen];
        return view('admin/user/userdetail')->with([
            'res' => $res
        ]);
    }

    //修改用户状态
    public function checkstatus(Request $request){
        //先查下他的openid
        $info = DB::table('user') -> where([
            'id' => $request -> input('id')
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



        if($request -> input('status') == 3){
            //审核不通过
            $messageId = $notice->send([
                'touser' => $info -> openid,
                'template_id' => config('wxsetting.moban1'),
                'url' => config('wxsetting.superurl'),
                'data' => [
                    'first' => '尊敬的'.$info -> name,
                    'keyword1' => '您的注册申请已被退回，请重新申请',
                    'keyword2' => date('Y-m-d'),
                    'keyword3' => '',
                    'remark' => '感谢您的使用'
                ],
            ]);
            DB::table('message') -> insert([
                'openid' => $info -> openid,
                'message' => '您的注册申请已被退回，请重新申请',
                'created_at' => time()
            ]);
        }else{
            $messageId = $notice->send([
                'touser' => $info -> openid,
                'template_id' => config('wxsetting.moban1'),
                'url' => config('wxsetting.superurl'),
                'data' => [
                    'first' => '尊敬的'.$info -> name,
                    'keyword1' => '您的注册申请已被审核通过',
                    'keyword2' => date('Y-m-d'),
                    'keyword3' => '',
                    'remark' => '感谢您的使用'
                ],
            ]);
            DB::table('message') -> insert([
                'openid' => $info -> openid,
                'message' => '您的注册申请已被审核通过',
                'created_at' => time()
            ]);


            //审核通过，排序字段+1 用来看他第几位进入小区的
            $number = DB::table('user') -> where([
                'xiaoqu' => $info -> xiaoqu
            ]) -> max('order_number');


        }
        //如果是审核不通过，就把他删了，让他重新提交
        if($request -> input('status') == 3){
            DB::table('user') -> where([
                'id' => $request -> input('id')
            ]) -> delete();
            echo 'success';exit;
        }

        $res = DB::table('user') -> where([
            'id' => $request -> input('id')
        ]) -> update([
            'status' => $request -> input('status'),
            'order_number' => intval($number) + 1
        ]);
        if($res){
            echo 'success';
        }else{
            echo 'error';
        }
    }

    public function shezhi($id){
        //查找他是哪个身份
        $res = DB::table('user') -> where([
            'id' => $id
        ]) -> first();
        return view('admin/user/shezhi')->with([
            'id' => $id,
            'res' => $res
        ]);
    }

    public function shenfen(Request $request){
        $res = DB::table('user') -> where([
            'id' => $request -> input('id')
        ]) -> update([
            'shenfen' => $request -> input('shenfen')
        ]);

        if($res){
            echo 'success';
        }else{
            echo 'error';
        }

    }
}
