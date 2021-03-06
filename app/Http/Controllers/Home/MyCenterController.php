<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Support\Url as UrlHelper;

class MyCenterController extends Controller
{
    //
    public function mylinli($openid = null){
        $fabu_box = 'no';
        if(!$openid){
            $openid = session('openid');
            $mark = 1;
        }else{
            $fabu_box = 'no';
        }

        $usertype = 'person';
        //判断此人的身份
        $userinfo = DB::table('user') -> where([
            'openid' => $openid
        ]) -> first();
        if($userinfo -> status == 0 || !$userinfo){
            $usertype = 'visit';
        }

        if(!$userinfo -> wuye_pingjia && isset($mark)){
            //发布的框显示
            $fabu_box = 'yes';
        }


        return view('home/mycenter/mylinli') -> with([
            'openid' => $openid,
            'userinfo' => $userinfo,
            'usertype' => $usertype,
            'fabu_box' => $fabu_box
        ]);



    }
    //发布给物业
    public function fabuwuye(Request $request){
        $res = DB::table('news') -> where([
            'id' => $request -> input('id'),
            'openid' => session('openid')
        ]) -> update([
            'wuye_pingjia' => $request -> input('result')
        ]);


        if($res){
            echo 'success';
        }else{
            echo 'error';
        }
    }

    public function mylinliajax(Request $request){
        //DB::enableQueryLog();
        $res = [];
        $index = $request -> input('index');
        $fabuindex = $request -> input('fabuindex');
        $where = array();
        if($fabuindex == 1){
            //发布
            if($request -> openid){
                $where['openid'] = $request -> openid;
            }else{
                $where['openid'] = session('openid');
            }

            $where['type'] = $index;

            $res = DB::table('news') -> where($where) -> get();
        }else{
            if($request -> openid){
                $where_huifu['openid'] = $request -> openid;
            }else{
                $where_huifu['openid'] = session('openid');
            }

            //他回复过的
            $pinluns = DB::table('pinlun') -> where($where_huifu) -> get();
            if($pinluns){
                foreach($pinluns as $vo){
                    $ids[] = $vo -> news_id;
                }
            }else{
                $ids = [];
            }

            //回复 包括帮助的
            $bangzhus =DB::table('news') -> where([
                'openid_help' => session('openid')
            ]) -> get();
            if($bangzhus){
                foreach($bangzhus as $vo){
                    $ids[] = $vo -> id;
                }
            }


            if(!empty($ids)){
                $res = DB::table('news') -> where([
                    ['type', '=', $index],
                ])->whereIn('id', $ids) -> get();
            }

            //$queries = DB::getQueryLog(); // 获取查询日志

            //dd($queries); // 即可查看执行的sql，传入的参数等等
        }


        if($res){

            //$queries = DB::getQueryLog(); // 获取查询日志

            //dd($queries); // 即可查看执行的sql，传入的参数等等

            $res = $this -> object_array($res);

            //$this -> dump($res);exit;


            foreach($res as $k=> $vo){
                if($vo['img']){
                    $res[$k]['img'] = explode(',',$vo['img']);
                }
                $res[$k]['userinfo'] = DB::table('user') -> where([
                    'openid' => $vo['openid'],
                ]) -> first();

                if($vo['status'] == 0){
                    $res[$k]['status_manage_name'] = '进行中';
                    $res[$k]['status_name'] = '待解决';
                }else{
                    $res[$k]['status_manage_name'] = '已结束';
                    $res[$k]['status_name'] = '已解决';
                }
                $res[$k]['created_at'] = date('Y-m-d H:i',$vo['created_at']);

                //如果有帮助他的人 则把帮助他的人信息列出来
                if($vo['openid_help']){
                    $res[$k]['helpinfo'] = DB::table('user') -> where([
                        'openid' => $vo['openid_help']
                    ]) -> first();
                }

                if(session('openid') == $vo['openid'] ){
                    $res[$k]['is_manage'] = 1;
                }else{
                    //如果不是，如果status = 1 就把这条隐藏
                    if($vo['open_status'] == 1){
                        unset($res[$k]);
                        continue;
                    }
                }

            }
        }
        shuffle($res);


        return response()->json($res);
    }

    public function myorder(){
        return view('home/mycenter/order');
    }

    //喜欢的邻居
    public function likelinju($openid = ''){
        if(!$openid){
            $openid = session('openid');
        }
        $res = DB::table('like_people') -> where([
            'openid' => $openid
        ]) -> get();
        foreach ($res as $k => $vo){
            $res[$k] -> likeuser = DB::table('user') -> where([
                'openid' => $vo -> openid_like
            ]) -> first();
        }

        //dd($res);
        return view('home/mycenter/likelinju') -> with([
            'res' => $res
        ]);
    }

    //互助评价
    public function huzhupingjia($openid = ''){
        if(!$openid){
            $openid = session('openid');
        }
        $res = DB::table('news') -> where([
            'openid_help' => $openid,
            'type' => 1,
        ]) -> get();

        if($res){
            foreach($res as $k => $vo){
                if(!$vo -> help_pingjia){
                    unset($res[$k]);
                    continue;
                }
                if($vo -> openid_help){
                    $res[$k] -> helpinfo = DB::table('user') -> where([
                        'openid' => $vo -> openid_help
                    ]) -> first();
                }
                if($vo->img){
                    $res[$k]->img = explode(',',$vo->img);
                }
                $res[$k] -> userinfo = DB::table('user') -> where([
                    'openid' => $vo->openid,
                ]) -> first();

            }
        }



        return view('home/huzhupingjia') -> with([
            'res' => $res
        ]);
    }

    //喜欢的邻居  取消／选中
    public function likelinjuchange(Request $request){
        $res = DB::table('like_people') -> where([
            'openid' => session('openid'),
            'openid_like' => $request -> input('openid_like'),
        ]) -> first();

        if($res){
            //删除此条
            DB::table('like_people') -> where([
                'openid' => session('openid'),
                'openid_like' => $request -> input('openid_like'),
            ]) -> delete();
        }else{
            //添加
            DB::table('like_people') -> insert([
                'openid' => session('openid'),
                'openid_like' => $request -> input('openid_like'),
            ]);
        }


    }

    //我的收藏
    public function myshoucang(){
        $res = DB::table('shoucang_article') -> where([
            'openid' => session('openid')
        ]) -> get();
        $arr = [];
        foreach($res as $k => $vo){
            $arr[] = $vo -> article_id;
        }
        if($arr){
            $result = DB::table('article') -> whereIn('id',$arr) -> get();
        }else{
            $result = null;
        }

        //dd($result);
        return view('home/mycenter/myshoucang') -> with([
            'res' => $result
        ]);
    }

    //关于我们
    public function aboutus(){
        return view('home/mycenter/aboutus');
    }

    //优惠券
    public function ticket(){
        $arr1 = [];
        $arr2 = [];
        $tickets = DB::table('ticket') -> get();
        foreach($tickets as $vo){
            $temp = DB::table('get_ticket') -> where([
                'openid' => session('openid'),
                'ticket_id' => $vo -> id
            ]) -> first();
            if(count($temp)){
                //领取的
                $vo -> code = $temp -> code;
                $arr1[] = $vo;
            }else{
                //如果没有过期 或者 number_res > 0
                if($vo -> date > time() && $vo -> number_res > 0){
                    //没领取的
                    $arr2[] = $vo;
                }
            }

        }


        return view('home/mycenter/ticket') -> with([
            'res1' => $arr1,
            'res2' => $arr2
        ]);
    }

    public function getTicket(Request $request){
        //领取优惠券
        //看他是否领了
        $res = DB::table('get_ticket') -> where([
            'openid' => session('openid'),
            'ticket_id' => $request -> input('id')
        ]) -> first();
        if(!count($res)){
            DB::table('get_ticket') -> insert([
               'ticket_id' => $request -> input('id'),
                'openid' => session('openid'),
                'created_at' => time(),
                'code' => rand(10000000,99999999)
            ]);

            DB::table('ticket') -> where([
                'id' => $request -> input('id'),
            ]) -> decrement('number_res');

            DB::table('ticket') -> where([
                'id' => $request -> input('id'),
            ]) -> increment('number_has');


        }
    }

    public function mydata(){
        $res = DB::table('user') -> where([
            'openid' => session('openid')
        ]) -> first();

        //查找小区名称
        $res -> xiaoquname = DB::table('shequ') -> where([
            'id' => $res -> xiaoqu
        ]) -> first();
        //dd($res);
        return view('home/mycenter/mydata')->with([
            'res' => $res
        ]);
    }

    public function myservice(){
        $bodas = DB::table('boda_service') -> where([
            'openid' => session('openid')
        ]) -> get();
        $ids = array();
        foreach($bodas as $vo){
            $ids[] = $vo -> service_id;
        }

        $res = array();
        if($ids){
            $res = DB::table('service') ->whereIn('id', $ids) -> get();
        }else{
            $res = null;
        }


        return view('home/mycenter/myservice') -> with([
            'res' => $res
        ]);
    }

    //订单ajax
    public function orderlist(Request $request){
        $index = $request -> input('index');
        $res = DB::table('order') -> where([
            'openid' => session('openid'),
            'status' => $index
        ]) -> get();
        foreach($res as $k => $vo){
            $res[$k] -> created_at = date('Y-m-d H:i',$vo -> created_at);
            switch ($vo -> status){
                case 0:$res[$k] -> status_name = '待收货';break;
                case 1:$res[$k] -> status_name = '待评价';break;
                case 2:$res[$k] -> status_name = '已完成';break;
                case 3:$res[$k] -> status_name = '退货';break;
            }

            $res[$k] -> goods_info = DB::table('goods') -> where([
                'id'=> $vo -> goods_id
            ]) -> first();
            $res[$k] -> user_info = DB::table('user') -> where([
                'openid'=> $vo -> openid
            ]) -> first();

            if($vo -> peisong_type == 0){
                $res[$k] -> peisong_type = '货物自提';
                //如果显示货物自提，则显示地址和电话
                $res[$k] -> xianshi =  $vo -> address.' '.$vo -> user_info -> tel;
            }else{
                $res[$k] -> peisong_type = '送货上门';
                $res[$k] -> xianshi = $vo -> kuaidi.' '.$vo -> danhao;
            }




        }
        return response() -> json($res);
    }

    public function tuikuan_page($orderid){
        return view('home/mycenter/tuikuan_page') -> with([
            'orderid'=>$orderid
        ]);
    }
    public function tuikuanRes(Request $request){
        $res = DB::table('order') -> where([
            'order_id' => $request -> input('orderid')
        ]) -> update([
            'status' => 3,
            'shouhou_status' => 1,
            'show_status' => '申请退款',
            'tuikuan_content' => $request -> input('content'),
            'tuikuan_imgs' => $request -> input('img'),
        ]);

        //发送模板消息
        //找到他的openid
        $order_info = DB::table('order') -> where([
            'order_id' => $request -> input('orderid')
        ]) -> first();
        $info = DB::table('user') -> where([
            'openid' => $order_info -> openid
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
            'url' => config('wxsetting.myorder_url'),
            'data' => [
                'first' => '尊敬的'.$info -> name,
                'keyword1' => '您的退货申请已经提交，请等待审核',
                'keyword2' => date('Y-m-d'),
                'keyword3' => '',
                'remark' => '感谢您的使用'
            ],
        ]);
        DB::table('message') -> insert([
            'openid' => $info -> openid,
            'message' => '您的退货申请已经提交，请等待审核',
            'created_at' => time()
        ]);


        echo 'success';
    }

    //退款
    public function tuikuan(Request $request){

        $res = DB::table('order') -> where([
            'order_id' => $request -> input('orderid')
        ]) -> update([
            'status' => 3,
            'show_status' => '申请退款',
        ]);
        echo 'success';
    }
    //确认收货
    public function querenshouhuo(Request $request){
        //dd($request -> input('orderid'));
        $res = DB::table('order') -> where([
            'order_id' => $request -> input('orderid')
        ]) -> update([
            'status' => 1,
            'show_status' => '待评价',
        ]);
        echo 'success';
    }

    //评价
    public function pingjia($orderid){
        return view('home/mycenter/pingjia') -> with([
            'orderid'=>$orderid
        ]);
    }

    public function fabiaopinglun(Request $request){
        $res = DB::table('order') -> where([
            'order_id' => $request -> input('orderid')
        ]) -> update([
            'pinglun' => $request -> input('content'),
            'imgs' => $request -> input('img'),
            'star' => $request -> input('star'),
            'status' => 2,
            'show_status' => '已完成'
        ]);


        if($res){
            echo 'success';
        }else{
            echo 'error';
        }

    }

    //更多设置页面
    public function moresetting(){
        return view('home/mycenter/moresetting');
    }

    //互助评价
    public function helppingjia(Request $request){
        DB::table('news') -> where([
            'id' => $request -> input('id')
        ]) -> update([
            'help_pingjia' => $request -> input('content')
        ]);
        echo 'success';
    }

    //系统消息
    public function xitongMessage(){
        $res = DB::table('message') -> where([
            'openid' => session('openid')
        ]) -> get();

        return view('home/mycenter/xitongMessage') -> with([
            'res' => $res
        ]);
    }

}
