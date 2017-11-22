<?php

namespace App\Http\Controllers\Home;

use App\Article;
use App\News;
use App\User;
use App\Toupiao;
use App\WxModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Support\Url as UrlHelper;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function sendMessage(Request $request){
        require (app_path() . '/Tools/ChuanglanSmsApi.php');
        //判断是否存在
        if(!$request -> input('mobile')){
            return response() -> json(['status'=>'error']);
        }

        $is_set = !is_null(Cache::get($request -> input('mobile')));
        if(!$is_set){
            //代表重复获取
            return response() -> json(['status'=>'waiting']);
        }

        $api = new \ChuanglanSmsApi();
        $mobile = $request -> input('mobile');
        $code = mt_rand(100000,999999);

        $msg = '【商联拓铺】您的验证码是'.$code;
        $res = $api -> sendSMS( $mobile, $msg);
        var_dump($res);
        if($res){
            //存入缓存
            Cache::put($request -> input('mobile'),$code,2);
            return response() -> json(['status'=>'success']);
        }

    }

    public function checkMessageCode(Request $request){
        $code = $request -> input('code');
        $mobile = $request -> input('mobile');

        $isset = is_null(Cache::get($mobile));
        if($isset){
            return response() -> json([
                'status' => 'error'
            ]);
        }


        $cache = Cache::get($mobile);
        //var_dump($cache);exit;
        if($cache != $mobile){
            return response() -> json([
                'status' => 'error'
            ]);
        }
        return response() -> json([
            'status' => 'success'
        ]);

    }



    public function object_array($array,$mark = null) {
        if(is_object($array)) {
            $array = (array)$array;
            //dump($array);exit;
        }
        if(is_array($array)) {
            foreach($array as $key=>$value) {
                $array[$key] = $this -> object_array($value,1);
            }
        }

        if(!$mark){
            foreach($array as $vo){
                $newarray = $vo;
            }

            return $newarray;
        }

        return $array;
    }

    public function helpPage($id){
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
        //拿到news id 取帮助人的open id
        $newsinfo = DB::table('news') -> where([
            'id' => $id
        ]) -> first();
        $openid_him = $newsinfo -> openid_help;

        return redirect('home/likeman/'.$openid_him) -> with('helpres',$id);

    }

    public function indexJump(){

        $url = urlencode(config('wxsetting.url1'));

        $appId = config('wxsetting.appid');

        $trueurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appId."&redirect_uri=".$url."&response_type=code&scope=snsapi_base&state=state#wechat_redirect";
        //echo 11;exit;
        //dump($trueurl);
        //Header("Location: $trueurl");
        header("Location: $trueurl");exit;
    }

    //首页
    public function index($id=0)
    {
        $manage_xiaoqu = '';
        if(session('is_manage')){

            //把第一个小区拿出来
            $manage_xiaoqu = DB::table('shequ') -> orderBy('created_at','asc') -> first();
            session([
                'xiaoqu' => $manage_xiaoqu -> id
            ]);
        }

        $usertype = 'person';
        $model = new WxModel();
        $mark = $model -> checkOpenid();
        if(session('is_manage_jump')){

            //把第一个小区拿出来
            $manage_xiaoqu = DB::table('shequ') -> where([
                'id' => session('is_manage_jump')
            ]) -> first();
            session([
                'xiaoqu' => $manage_xiaoqu -> id
            ]);
            //var_dump(session('xiaoqu'));
        }

        if($mark == 'redirect_reg'){
            return redirect('home/reg/isreg');
        }
        //如果是第一次进入 则给个标志
        $ismark = 0;
        if($mark -> ismark == 0 && $mark -> status == 1){
            //$ismark = DB::table('user') -> count();
            $ismark = 1;
            //再把ismark改为1
            DB::table('user') -> where([
                'openid' => session('openid')
            ]) -> update([
                'ismark' => 1
            ]);
        }
        //循环数据 - 今日值得看
        $model_article = new Article();
        $list_article = $model_article -> where(['flag'=>'0','xiaoqu'=>session('xiaoqu')]) -> orderBy('created_at','desc') -> limit(2) -> get();
        //var_dump($list_article);exit;

        //判断此人的身份
        $userinfo = DB::table('user') -> where([
            'openid' => session('openid')
        ]) -> first();

        //投票
        $model_toupiao = new Toupiao();
        $list_toupiao = $model_toupiao -> where(function($query){
            $query -> where('flag','=','0');
            $query -> where('xiaoqu' ,'=', session('xiaoqu'));

        }) -> orderBy('created_at','desc') ->limit(2) -> get();
        foreach($list_toupiao as $k => $vo){

            //如果存在楼号，则显示固定楼号的
            if($vo -> loudong){
                $temp_loudong = explode(',',$vo -> loudong);
                if(!in_array($userinfo -> louhao,$temp_loudong)){
                    unset($list_toupiao[$k]);continue;
                }
            }
            if($vo -> shenfen != '100'){
                $temp = explode(',',$vo -> shenfen);
                if(!in_array($userinfo -> shenfen,$temp)){
                    unset($list_toupiao[$k]);continue;
                }
            }
        }

        if($userinfo -> status == 0 || !$userinfo){
            $usertype = 'visit';
        }

        //dd($userinfo);
        return view('home/home') -> with([
            'list_article'=>$list_article,
            'list_toupiao'=>$list_toupiao,
            'ismark' => $ismark,
            'hover'=>'linlihudong',
            'userinfo' => $userinfo,
            'usertype' => $usertype,
            'manage_xiaoqu' => $manage_xiaoqu,
            'select_id' => $id,
            'first_look' => 0
        ]);
    }

    //查看人气小区
    public function look(){
        $select_id = 0;
        session([
            'xiaoqu'=>'13'
        ]);
        $xiaoqu = 13;
        //循环数据 - 今日值得看
        $model_article = new Article();
        $list_article = $model_article -> where(['flag'=>'0','xiaoqu'=>$xiaoqu,'flag'=>0]) -> orderBy('created_at','desc') -> limit(2) -> get();
        //var_dump($list_article);exit;
        //投票
        $model_toupiao = new Toupiao();
        $list_toupiao = $model_toupiao -> where(['flag'=>'0','xiaoqu' => $xiaoqu,'flag'=>0]) -> orderBy('created_at','desc') ->limit(2) -> get();
        $userinfo = [];

        //dd($userinfo);
        return view('home/home') -> with([
            'list_article'=>$list_article,
            'list_toupiao'=>$list_toupiao,
            'hover'=>'linlihudong',
            'userinfo' => $userinfo,
            //身份为游客
            'usertype' => 'visit',
            'manage_xiaoqu' => '',
            'select_id' => $select_id,
            'first_look' => 1

        ]);
    }

    public function ajax(Request $request){
        //DB::enableQueryLog();
        $index = $request -> input('index');
        $page = $request -> input('page');
        $pagesize = 25;
        $res = DB::table('news') -> where(['type'=>$index,'xiaoqu'=>session('xiaoqu'),'flag'=>0])  -> offset($page*$pagesize) -> orderBy('created_at','desc') -> limit($pagesize) -> get();

        if($page == 0){
            //如果是第一次查 把总页数返回
            $count = DB::table('news') -> where(['type'=>$index,'xiaoqu'=>session('xiaoqu'),'flag'=>0]) -> count();
        }

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

            //看下此人是否是这条的发布者
            if(session('openid') == $vo['openid']){
                $res[$k]['is_manage'] = 'yes';
            }

            if($vo['status'] == 0){
                $res[$k]['status_manage_name'] = '进行中';
                $res[$k]['status_name'] = '待解决';
            }else{
                $res[$k]['status_manage_name'] = '已结束';
                $res[$k]['status_name'] = '已解决';
            }
            $res[$k]['created_at'] = date('Y-m-d H:i',$vo['created_at']);

            //查找每条记录的物业回复
            $temp = DB::table('wuye_huifu') -> where([
                'news_id' => $vo['id']
            ]) -> get();
            $temp = $this -> object_array($temp);
            if($temp){
                $res[$k]['wuyehuifu'] = $temp;
            }


        }

        return response()->json($res);
    }

    //服务请求
    public function ajax2(Request $request){
        $index = $request -> input('index');

        if($index == 0){
            $res = DB::table('service') -> where(function($query){
                $query -> where('xiaoqu','=',session('xiaoqu'));
                $query -> where('status','=','0');
                $query -> where('flag','=','0');
                if(isset($_POST['keywords'])){
                    $query -> where('title','like','%'.$_POST['keywords'].'%');
                }
            }) -> orderBy('created_at','desc') -> get();
        }else{
            $index --;
            $res = DB::table('service') -> where(['type'=>$index,'xiaoqu'=>session('xiaoqu'),'status'=>0,'flag'=>0]) -> orderBy('created_at','desc') -> get();
        }

        $res = $this -> object_array($res);
        return response() -> json($res);

    }

    //反馈
    public function fankui($id){
        return view('home/fankui');
    }

    public function fankuiRes(Request $request){
        DB::table('news') -> where([
            'id' => $request -> input('id')
        ]) -> update([
            'huifu' => $request -> input('content')
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
            'url' => config('wxsetting.youlin_url').$request -> input('id'),
            'data' => [
                'first' => '尊敬的'.$info -> name,
                'keyword1' => '您的反馈已提交给物业，请等待处理',
                'keyword2' => date('Y-m-d'),
                'keyword3' => '',
                'remark' => '感谢您的使用'
            ],
        ]);

        //找到小区
        $xiaoqu = DB::table('business') -> where([
            'xiaoqu' => $info -> xiaoqu,
            'type' => 1
        ]) -> get();
        foreach($xiaoqu as $vo){
            //找到每个openid
            if($vo -> openid){
                $messageId = $notice->send([
                    'touser' => $vo -> openid,
                    'template_id' => config('wxsetting.moban1'),
                    'url' => config('wxsetting.youlin_url').$request -> input('id'),
                    'data' => [
                        'first' => '您好',
                        'keyword1' => '您有反馈需要处理',
                        'keyword2' => date('Y-m-d'),
                        'keyword3' => '',
                        'remark' => '感谢您的使用'
                    ],
                ]);
            }
        }



        echo 'success';
    }

    //行家在线
    public function hangjia(){
        $res = DB::table('hangjia') -> where('is_mark','=',session('xiaoqu')) -> orWhere('is_mark','=',0) -> get();
        $res = $this -> object_array($res);
        foreach($res as $k => $vo){
			if($vo['xuanze'] == 0){
				$res[$k]['tel_rel'] = $vo['tel'];
			}else{
				$res[$k]['tel_rel'] = $vo['tel2'];
			}
		}
	
        
        return view('home/hangjia')->with([
            'res'=> $res
        ]);
    }

    //删除邻里说
    public function delete_data(Request $request){
        DB::table('news') -> where([
            'id' => $request -> input('id')
        ]) -> update([
            'flag' => 1
        ]);
        echo 'success';
    }

    //开启
    public function open_data(Request $request){
        DB::table('news') -> where([
            'id' => $request -> input('id')
        ]) -> update([
            'status' => 0
        ]);
        echo 'success';
    }
    //关闭
    public function close_data(Request $request){
        $newsinfo = DB::table('news') -> where([
            'id' => $request -> input('id')
        ]) -> first();
        if(session('openid') == $newsinfo -> openid_help){
            //如果不是他本人关的，则是帮助的这个人关的
            //完成了 给a发送消息
            $info = DB::table('user') -> where([
                'openid' => $newsinfo -> openid
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
                'url' => config('wxsetting.youlin_url').$request -> input('id'),
                'data' => [
                    'first' => '尊敬的'.$info -> name,
                    'keyword1' => '您发布的"'.$newsinfo -> title.'"需求，邻居已经完成，快去看看吧',
                    'keyword2' => date('Y-m-d'),
                    'keyword3' => '',
                    'remark' => '感谢您的使用'
                ],
            ]);
        }

        DB::table('news') -> where([
            'id' => $request -> input('id')
        ]) -> update([
            'status' => 1
        ]);


        echo 'success';
    }


    //发布
    public function fabu($id){


        return view('home/fabu') -> with([
            'index' => $id
        ]);
    }

    //保存图片
    public  function saveImg(Request $request){
        //判断请求中是否包含name=file的上传文件
        if(!$request->hasFile('file')){
            echo 'error';exit;
        }
        $imgsrc = $request -> input('imgsrc');
        if($imgsrc){
            $imgsrc = explode(',',$imgsrc);
        }
        $file = $request->file('file');
        //判断文件上传过程中是否出错
        if(!$file->isValid()){
            exit('文件上传出错！');
        }
        $newFileName = md5(time().rand(0,10000)).'.'.$file->getClientOriginalExtension();
        $savePath = $newFileName;
        $bytes = Storage::put(
            $savePath,
            file_get_contents($file->getRealPath())
        );
        if(!Storage::exists($savePath)){
            exit('保存文件失败！');
        }
        $imgsrc[] = $newFileName;
        $returndata['imgsrc'] =  implode(',',$imgsrc);
        $returndata['img'] = $newFileName;
        //header("Content-Type: ".Storage::mimeType($savePath));
        return response() -> json($returndata);
        //echo $newFileName;
        //echo Storage::get($savePath);
    }

    //发布结果
    public function fabuRes(Request $request){
        //先查下上条发的时间
        $lastnews = DB::table('news') -> where([
            'openid' => session('openid')
        ]) ->orderBy('created_at','desc')-> first();
        if($lastnews && time() - $lastnews -> created_at <= 30){
            echo 'error';exit;
        }
        //存储到news表中
        $model = new News();
        $model -> type = $request -> input('index');
        $model -> title = $request -> input('title');
        $model -> label = $request -> input('label');
        $model -> miaoshu = $request -> input('content');
        $model -> img = $request -> input('img');
        $model -> date = $request -> input('date');
        $model -> date_right = $request -> input('date_right');
        if($request -> input('price')){
            $model -> price = $request -> input('price');
        }else{
            $model -> price = 0.00;
        }

        $model -> xiaoqu = session('xiaoqu');
        $model -> openid = session('openid');



        $res = $model -> save();
        echo 'success';
    }

    //评论
    public function pinlun($id){
        //echo 111;exit;
        $res = DB::table('news') -> find($id);
        $res = (array)$res;
        if($res['img']){
            $res['img'] = explode(',',$res['img']);
        }
        //查看是否有人帮助
        //如果有帮助他的人 则把帮助他的人信息列出来
        if($res['openid_help']){
            $res['helpinfo'] = DB::table('user') -> where([
                'openid' => $res['openid_help']
            ]) -> first();
        }

        //看物业回复
        //查找每条记录的物业回复
        $res['wuye_huifu'] = DB::table('wuye_huifu') -> where([
            'news_id' => $res['id']
        ]) -> get();


        foreach($res['wuye_huifu'] as $k => $vo){
            $res['wuye_huifu'][$k]-> imgs = explode(',',$vo -> imgs);
        }
        //dd($res['wuye_huifu']);
        //查下谁赞过
        $res['who'] = DB::table('zan_news')

            -> leftjoin ('user', 'zan_news.openid', '=', 'user.openid')
            -> where([
            'news_id' => $res['id']
        ]) -> get();
        //dd($res);


        $res['userinfo'] = DB::table('user') -> where([
            'openid' => $res['openid']
        ]) -> first();
        //通过news id 查评论记录
        $res_pinlun = DB::table('pinlun') -> where(['news_id'=>$id,'flag'=>0]) -> get();
        //dd($res_pinlun);
        if($res_pinlun){
            foreach($res_pinlun as $k => $vo){
                $res_pinlun[$k] -> content = $this -> ubbReplace($vo->content);
                if($vo -> openid){
                    $res_pinlun[$k] -> userinfo = DB::table('user') -> where([
                        'openid' => $vo -> openid
                    ]) -> first();
                }
            }
        }

        //dd($res_pinlun);
        return view('home/pinlun')->with([
            'res' => $res,
            'res_pinlun' => $res_pinlun
        ]);
    }


    //删除评论
    public function deletePinlun(Request $request){
        $id = $request -> input('id');
        DB::table('pinlun') -> where([
            'id' => $id
        ]) -> update([
            'flag' => 1
        ]);
        //找到new_id 评论减一
        $pinlun = DB::table('pinlun') -> where([
            'id' => $id
        ]) -> first();
        DB::table('news') -> where([
            'id' => $pinlun -> news_id
        ]) -> decrement('liulan');
        echo 'success';
    }



    //替换表情内容
    function ubbReplace($str) {
        $str = str_replace ( ">", '<；', $str );
        $str = str_replace ( ">", '>；', $str );
        $str = str_replace ( "\n", '>；br/>；', $str );
        $str = preg_replace ( "[\[em_([0-9]*)\]]", "<img src=\"".asset('face')."/$1.gif\" />", $str );
        return $str;
    }

    public function pinlunRes(Request $request){
        $res = DB::table('pinlun') -> insert([
           'news_id' => $request -> input('news_id'),
           'content' => $request -> input('content'),
            'created_at' => time(),
            'updated_at' => time(),
            'openid' => session('openid'),
        ]);
        if($res){
            DB::table('news') -> where([
                'id' => $request -> input('news_id'),
            ]) -> increment('liulan');
            echo 'success';
        }
    }

    //news 点赞
    public function newszan(Request $request){
        //先查他有没有点过赞
        $isset = DB::table('zan_news') -> where([
            'news_id' => $request -> input('news_id'),
            'openid' => session('openid'),
        ]) -> first();
        if(!$isset){
            DB::table('zan_news') -> insert([
                'news_id' => $request -> input('news_id'),
                'openid' => session('openid'),
            ]);
            //累计加一
            DB::table('news') -> where([
                'id' => $request -> input('news_id')
            ]) ->increment('dianzan');
            echo 'success';
        }
    }

    //service 点赞
    public function newszant(Request $request){
        //先查他有没有点过赞
        $isset = DB::table('zan_service') -> where([
            'news_id' => $request -> input('news_id'),
            'openid' => session('openid'),
        ]) -> first();
        if(!$isset){
            DB::table('zan_service') -> insert([
                'news_id' => $request -> input('news_id'),
                'openid' => session('openid'),
            ]);
            //累计加一
            DB::table('service') -> where([
                'id' => $request -> input('news_id')
            ]) ->increment('dianzan');
            echo 'success';
        }
    }

    //服务拨打次数
    public function calltime(Request $request){
        //拨打记录
        $isset = DB::table('boda_service') -> where([
            'service_id' => $request -> input('id')
        ]) -> first();
        if(!$isset){
            DB::table('boda_service') -> insert([
                'service_id' => $request -> input('id'),
                'openid' => session('openid'),
                'created_at' => time()
            ]);
        }


        DB::table('service') -> where([
            'id' => $request -> input('id')
        ]) ->increment('boda');
    }





    //值得看列表
    public function zhidelist(){
        //判断此人的身份
        $userinfo = DB::table('user') -> where([
            'openid' => session('openid')
        ]) -> first();



        //循环数据 - 今日值得看
        $list_article = DB::table('article') -> where(['flag'=>'0','xiaoqu'=>session('xiaoqu')]) -> orderBy('created_at','desc')  -> get();
        //var_dump($list_article);exit;
        //投票
        $list_toupiao = DB::table('toupiao') -> where(['flag'=>'0','xiaoqu'=>session('xiaoqu')]) -> orderBy('created_at','desc')  -> get();
        if($list_toupiao){
            foreach($list_toupiao as $k => $vo){

                //如果存在楼号，则显示固定楼号的
                if($vo -> loudong){
                    $temp_loudong = explode(',',$vo -> loudong);
                    if(!in_array($userinfo -> louhao,$temp_loudong)){
                        unset($list_toupiao[$k]);continue;
                    }
                }
                if($vo -> shenfen != '100'){
                    $temp = explode(',',$vo -> shenfen);
                    if(!in_array($userinfo -> shenfen,$temp)){
                        unset($list_toupiao[$k]);continue;
                    }
                }
            }
        }


        //dd($list_article);
        return view('home/zhidelist') -> with([
            'list_article'=>$list_article,
            'list_toupiao'=>$list_toupiao,

        ]);

    }



    //值得看详情
    public function zhidedetail($id){
        $res = DB::table('article') -> find($id);
        if(!$res){
            return redirect('/home');
        }
        //dd($res);
        //看这篇文章 此人有没有收藏过 点赞过
        $res -> zan = DB::table('zan_article') -> where([
            'openid' => session('openid'),
            'article_id' => $res -> id
        ]) -> first();
        $res -> shoucang = DB::table('shoucang_article') -> where([
            'openid' => session('openid'),
            'article_id' => $res -> id
        ]) -> first();
        //dd($res);

        return view('home/zhidedetail') -> with([
            'data'=>$res,

        ]);
    }
    //收藏
    public function articleshoucang(Request $request){
        //看此人有没有收藏过这篇
        $isset = DB::table('shoucang_article') -> where([
            'openid' => session('openid'),
            'article_id' => $request -> input('id')
        ]) -> first();

        if(!$isset){
            DB::table('shoucang_article') -> insert([
                'openid' => session('openid'),
                'article_id' => $request -> input('id')
            ]);

            //收藏数加一
            DB::table('article') -> where([
                'id' => $request -> input('id')
            ]) -> increment('shoucang');
            echo 'success';
        }else{
            //删除
            DB::table('shoucang_article') -> where([
                'openid' => session('openid'),
                'article_id' => $request -> input('id')
            ]) -> delete();
            echo 'error';
        }

    }

    public function articlezan(Request $request){
        $isset = DB::table('zan_article') -> where([
            'openid' => session('openid'),
            'article_id' => $request -> input('id')
        ]) -> first();

        if(!$isset){
            DB::table('zan_article') -> insert([
                'openid' => session('openid'),
                'article_id' => $request -> input('id')
            ]);

            //收藏数加一
            DB::table('article') -> where([
                'id' => $request -> input('id')
            ]) -> increment('dianzan');
            echo 'success';
        }else{
            //删除
            DB::table('zan_article') -> where([
                'openid' => session('openid'),
                'article_id' => $request -> input('id')
            ]) -> delete();
            echo 'error';
        }

    }



    //投票详情
    public function toupiaodetail($id){
        $toupiaodetail = DB::table('toupiao') -> where([
            'id' => $id
        ]) -> first();
        $res = DB::table('toupiao_title') -> where([
            'fid' => $id
        ]) -> get();
        foreach($res as $k=>$vo){
            $res[$k] -> detail = DB::table('toupiao_detail') -> where([
                'fid' => $vo -> id
            ]) -> get();
        }
        //dd($res);
        //填空题
        $tiankongtis = DB::table('tiankong') -> where([
            'toupiao_id' => $id
        ]) -> get();

        return view('home/toupiao/toupiaodetail')->with([
            'res' => $res,
            'id' => $id,
            'toupiaodetail'=>$toupiaodetail,
            'tiankongtis' => $tiankongtis
        ]);
    }

    public function toupiaosign(Request $request,$id){
        return view('home/toupiao/sign') -> with([
            'id' => $id,
            'data' => $request -> input('data'),
            'tiankongdata' => $request -> input('tiankongdata'),
        ]);
    }

    //投票结果
    public function toupiaoRes(Request $request,$id){

        //存储记录
        //先查看此人有没有投过票
        $isset = DB::table('toupiao_result') -> where([
            'openid' => session('openid'),
            'toupiao_id' => $id,
        ]) -> first();
        if($isset){
            echo 'isset';exit;
        }
        //加入记录
        //如果有填空题 则录入tiankong_res

        $res = DB::table('toupiao_result') -> insert([
            'openid' => session('openid'),
            'toupiao_id' => $id,
            'result' => json_encode(json_decode($request -> input('json'),true)),
            'created_at' => time(),
            'tiankong_res' => $request -> input('tiankongdata'),
            'updated_at' => time(),
        ]);




        //加入记录的时候，投票累计人数加一
        DB::table('toupiao') -> where([
            'id' => $id
        ]) -> increment('number');

        if($res){
            echo 'success';
        }else{
            echo 'error';
        }

    }

    //帮他
    public function helphim(Request $request){
        //跟新news表中，openid_help 字段
        $isset = DB::table('news') -> where([
            'id' => $request -> input('id')
        ]) -> first();


        if(session('openid') != $isset -> openid){
            $info = DB::table('user') -> where([
                'openid' => $isset -> openid
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
                'url' => config('wxsetting.helpurl').$request -> input('id'),
                'data' => [
                    'first' => '尊敬的'.$info -> name,
                    'keyword1' => '您发布的需求已有邻居愿意帮忙，快去看看吧~',
                    'keyword2' => date('Y-m-d'),
                    'keyword3' => '',
                    'remark' => '感谢您的使用'
                ],
            ]);



            DB::table('news') -> where([
                'id' => $request -> input('id')
            ]) -> update([
                'openid_help' => session('openid')
            ]);
            echo 'success';
        }

    }


    //注册
    public function reg($isreg = null){
        $options = [
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id'  => config('wxsetting.appid'),         // AppID
            'secret'  => config('wxsetting.secret'),     // AppSecret
        ];
        $app = new Application($options);
        $js = $app -> js;
        //dd($js);

        $xiaoqu = DB::table('shequ') -> where([
            'status' => 0
        ]) -> get();
        //查下此人有没有注册，区分 没注册 和 正在审核
        $ischeck = null;
        $ischeck = DB::table('user') -> where([
            'openid' => session('openid'),
        ]) -> first();

        //所有

        return view('home/reg') -> with([
            'xiaoqu' => $xiaoqu,
            'isreg' => $isreg,
            'ischeck' => $ischeck,
            'js' => $js
        ]);
    }

    //根据小区id 查找楼号
    public function getLoudongFromXiaoqu(Request $request){
        $loudongs = DB::table('shequ') -> where([
            'id' => $request -> input('id')
        ]) -> first();
        return response() -> json($loudongs);
    }



    //查找小区名
    public function regSearch(Request $request){
        $keywords = $request -> input('keywords');
        $xiaoqu = DB::table('shequ') -> where(function($query) use ($keywords){
            $query -> where('status','=',0);
            $query -> where('title','like','%'.$keywords.'%');
        })-> get();
        return response()->json($xiaoqu);
    }

    public function xieyi(){
        $res = DB::table('xieyi') -> first();
        if($res){
            $content = $res -> content;
        }else{
            $content = '后台暂未填写';
        }
        return view('home/xieyi') -> with([
            'content' => $content
        ]);
    }

    public function regRes(Request $request){
        $isset = DB::table('user') -> where([
            'openid' => session('openid')
        ]) -> first();
        if($isset){
            echo 'isset';exit;
        }

        //dd($userinfo);
        $res = DB::table('user') -> insert([
            'name'=>$request -> input('name'),
            'tel'=>$request -> input('tel'),
            'xiaoqu'=>$request -> input('xiaoquname'), //带过去编号
            'louhao'=>$request -> input('louhao'),
            'menpaihao'=>$request -> input('menpaihao'),
            'shenfen'=>$request -> input('shenfen'),
            'jiating'=>$request -> input('jiating'),
            'created_at'=>time(),
            'updated_at'=>time(),
            'openid' => session('openid'),
            'img' => session('headimgurl'),
            'sex' => session('sex'),
        ]);

        if($res){
            echo "success";
        }else{
            echo "error";
        }
    }

    public function sign(Request $request){
        $return_arr = [
            'name'=>$request -> input('name'),
            'tel'=>$request -> input('tel'),
            'xiaoqu'=>$request -> input('xiaoquname'), //带过去编号
            'louhao'=>$request -> input('louhao'),
            'menpaihao'=>$request -> input('menpaihao'),
            'shenfen'=>$request -> input('shenfen'),
            'jiating'=>$request -> input('jiating'),
        ];
        return view('home/sign') -> with([
            'return_arr' => $return_arr
        ]);
    }

    public function signRes(Request $request){
        //解json
        $data = json_decode($request -> input('json'),true);
        $data['sign'] = $request -> input('imgurl');
        $data['created_at'] = time();
        $data['updated_at'] = time();
        $data['openid'] = session('openid');
        $data['img'] = session('headimgurl');
        $data['sex'] = session('sex');


        $isset = DB::table('user') -> where([
            'openid' => session('openid')
        ]) -> first();
        if($isset){
            echo 'isset';exit;
        }

        //dd($userinfo);
        $res = DB::table('user') -> insert($data);

        if($res){
            echo "success";
        }else{
            echo "error";
        }

    }

    public function jindu($name){
        if($name == 'all' || empty($name)){

        }elseif($name == 'dai'){
            $where['status'] = '0';
        }elseif($name == 'yi'){
            $where['status'] = '1';
        }
        $where['openid'] = session('openid');
        $where['type'] = 0;
        //dump($where);
        //查找他自己发的
        $res = DB::table('news') -> where($where) -> get();
        //dd($res);
        $res = $this -> object_array($res);
        foreach($res as $k=> $vo){
            if($vo['img']){
                $res[$k]['img'] = explode(',',$vo['img']);
            }

            $res[$k]['userinfo'] = DB::table('user') -> where([
                'openid' => $vo['openid'],
            ]) -> first();

            //查找每条记录的物业回复
            $temp = DB::table('wuye_huifu') -> where([
                'news_id' => $vo['id']
            ]) -> get();
            $temp = $this -> object_array($temp);
            if($temp){
                $res[$k]['wuyehuifu'] = $temp;
            }

        }



        return view('home/jindu_all') -> with([
            'res' => $res
        ]);
    }

    public function mycenter(){
        //dump(11);exit;
        //查找userinfo
        $res = DB::table('user') -> where([
            'openid' => session('openid')
        ]) -> first();
        //小区
        $res -> xiaoqu = DB::table('shequ') -> where([
            'id' => $res -> xiaoqu
        ]) -> first();
        //dd($res);
        return view('home/mycenter') -> with([
            'res' => $res
        ]);
    }

    public function likeman($openid){
        //如果是他自己  则跳个人中心
        if($openid == session('openid')){
            return redirect('home/mycenter');
        }
        $res = DB::table('user') -> where([
            'openid' => $openid
        ]) -> first();
        $res -> xiaoqu = DB::table('shequ') -> where([
            'id' => $res -> xiaoqu
        ]) -> first();
        //看下我有没有关注此人
        $islook = DB::table('like_people') -> where([
            'openid'=>session('openid'),
            'openid_like' => $openid
        ]) -> first();
        switch ($res -> shenfen){
            case '0':$res -> shenfen = '产权人';break;
            case '1':$res -> shenfen = '居民';break;
            case '2':$res -> shenfen = '业委会主任';break;
            case '3':$res -> shenfen = '业委会副主任';break;
            case '4':$res -> shenfen = '业委会秘书';break;
            case '5':$res -> shenfen = '业委会委员';break;
            case '6':$res -> shenfen = '业主代表';break;
            case '7':$res -> shenfen = '社区管理员';break;
        }

        return view('home/likeman') -> with([
            'res' => $res,
            'islook' => $islook
        ]);
    }

    //确认让他帮
    public function querenhelp(Request $request){
        $id = $request -> input('id');
        $res = $request -> input('res');
        if($res == 'yes'){

            //允许帮助
            //给此人发送模版消息
            $data = DB::table('news') -> where([
                'id' => $id
            ]) -> first();
            $info = DB::table('user') -> where([
                'openid' => $data -> openid_help
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
                'url' => config('wxsetting.youlin_url').$request -> input('id'),
                'data' => [
                    'first' => '尊敬的'.$info -> name,
                    'keyword1' => '邻居已接受您的帮助，快去帮忙吧~',
                    'keyword2' => date('Y-m-d'),
                    'keyword3' => '',
                    'remark' => '感谢您的使用'
                ],
            ]);


        }else{
            //删除他
            DB::table('news') -> where([
                'id' => $id
            ]) -> update([
                'openid_help' => ''
            ]);
            echo 'success';
        }
    }


}
