<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/test', 'TestController@test') ;

//首页微信跳转
Route::get('/homeJump', 'Home\HomeController@indexjump') ;

//邻里互助
Route::get('/home/{id?}', 'Home\HomeController@index')->where('id', '[0-9]+'); ;
Route::get('/homes/look', 'Home\HomeController@look') ;
//邻里互助ajax 请求
Route::any('/home/ajax', 'Home\HomeController@ajax') ;
Route::any('/home/delete_data', 'Home\HomeController@delete_data') ;
Route::any('/home/open_data', 'Home\HomeController@open_data') ;
Route::any('/home/close_data', 'Home\HomeController@close_data');
Route::any('/home/helppingjia', 'Home\MyCenterController@helppingjia') ;
//便民服务 ajax 请求
Route::any('/home/ajax2', 'Home\HomeController@ajax2') ;
//行家在线
Route::any('/hangjia', 'Home\HomeController@hangjia') ;
Route::any('/home/fankuiRes', 'Home\HomeController@fankuiRes') ;


//发布
Route::any('/home/fabu/{id}', 'Home\HomeController@fabu') ;
//被帮助点击的链接 news id
Route::any('/home/helpPage/{id}', 'Home\HomeController@helpPage') ;

//发布结果
Route::any('/home/fabuRes', 'Home\HomeController@fabuRes') ;
Route::any('/home/saveImg', 'Home\HomeController@saveImg') ;

//评论
Route::any('/home/pinlun/{id}', 'Home\HomeController@pinlun') ;
Route::any('/home/deleteMarket', 'Home\MarketController@deleteMarket') ;
Route::any('/home/pinlunRes', 'Home\HomeController@pinlunRes') ;
Route::any('/home/deletePinlun', 'Home\HomeController@deletePinlun') ;
Route::any('/home/newszan', 'Home\HomeController@newszan') ;
Route::any('/home/newszant', 'Home\HomeController@newszant') ;

//跳蚤市场
Route::any('/home/marketJump', 'Home\MarketController@indexJump') ;
Route::any('/home/market', 'Home\MarketController@index') ;
Route::any('/home/marketfabu', 'Home\MarketController@fabu') ;
Route::any('/home/marketfabuRes', 'Home\MarketController@fabuRes') ;

Route::get('/home/jindu/{name?}', 'Home\HomeController@jindu')->name('jindu');
Route::get('/home/reg/{isreg?}', 'Home\HomeController@reg');
Route::any('/home/regRes', 'Home\HomeController@regRes');
Route::any('/sendMessage', 'Home\HomeController@sendMessage');
Route::any('/checkMessageCode', 'Home\HomeController@checkMessageCode');
Route::any('/home/sign', 'Home\HomeController@sign');
Route::any('/home/signRes', 'Home\HomeController@signRes');
Route::any('/home/getLoudongFromXiaoqu', 'Home\HomeController@getLoudongFromXiaoqu');


Route::any('/home/regSearch', 'Home\HomeController@regSearch');
Route::any('/home/xieyi', 'Home\HomeController@xieyi');
//商城
//值得看列表
Route::any('/home/zhidelist', 'Home\HomeController@zhidelist');
//值得看详情
Route::any('/home/zhidedetail/{id}', 'Home\HomeController@zhidedetail');
Route::any('/home/toupiaodetail/{id}', 'Home\HomeController@toupiaodetail');
Route::any('/home/toupiaoRes/id/{id}', 'Home\HomeController@toupiaoRes');
Route::any('/home/toupiaosign/id/{id}', 'Home\HomeController@toupiaosign');
Route::any('/home/articleshoucang', 'Home\HomeController@articleshoucang');
Route::any('/home/articlezan', 'Home\HomeController@articlezan');
//帮他
Route::any('/home/helphim', 'Home\HomeController@helphim');
//服务拨打次数
Route::any('/home/calltime', 'Home\HomeController@calltime');
//商城

Route::any('/home/mallJump', 'Home\MallController@indexJump');
Route::any('/home/mall', 'Home\MallController@index');
Route::any('/home/malldetail/{id}', 'Home\MallController@malldetail');
Route::any('/home/buynow/{id}/{number}', 'Home\MallController@buynow');
Route::any('/home/buypay', 'Home\MallController@buypay');
Route::any('/payNotify', 'Home\MallController@payNotify');
Route::any('/payServiceNotify', 'Home\BusinessController@payServiceNotify');

//个人中心
Route::any('/home/mycenter', 'Home\HomeController@mycenter');
Route::any('/home/mylinli/{openid?}', 'Home\MyCenterController@mylinli');
Route::any('/home/mylinliajax', 'Home\MyCenterController@mylinliajax');
Route::any('/home/fabuwuye', 'Home\MyCenterController@fabuwuye');
Route::any('/home/myorder', 'Home\MyCenterController@myorder');
Route::any('/home/myorder/tuikuan', 'Home\MyCenterController@tuikuan');
Route::any('/home/myorder/tuikuanRes', 'Home\MyCenterController@tuikuanRes');
Route::any('/home/myorder/tuikuan_page/{orderid}', 'Home\MyCenterController@tuikuan_page');
Route::any('/home/myorder/querenshouhuo', 'Home\MyCenterController@querenshouhuo');
Route::any('/home/myorder/pingjia/{orderid}', 'Home\MyCenterController@pingjia');
Route::any('/home/myorder/fabiaopinglun', 'Home\MyCenterController@fabiaopinglun');


Route::any('/home/likelinju/{openid?}', 'Home\MyCenterController@likelinju');
Route::any('/home/huzhupingjia/{openid?}', 'Home\MyCenterController@huzhupingjia');
Route::any('/home/likelinjuchange', 'Home\MyCenterController@likelinjuchange');
Route::any('/home/xitongMessage', 'Home\MyCenterController@xitongMessage');

Route::any('/home/myshoucang', 'Home\MyCenterController@myshoucang');
Route::any('/home/aboutus', 'Home\MyCenterController@aboutus');
Route::any('/home/moresetting', 'Home\MyCenterController@moresetting');
Route::any('/home/ticket', 'Home\MyCenterController@ticket');
Route::any('/home/getTicket', 'Home\MyCenterController@getTicket');
Route::any('/home/mydata', 'Home\MyCenterController@mydata');
//商城
Route::any('/home/orderlist', 'Home\MyCenterController@orderlist');
Route::any('/home/payRequest', 'Home\MallController@payRequest');
//历史预约服务
Route::any('/home/myservice', 'Home\MyCenterController@myservice');
Route::any('/home/likeman/{openid}', 'Home\HomeController@likeman');
Route::any('/home/querenhelp', 'Home\HomeController@querenhelp');
Route::any('/home/enjoyman', 'Home\HomeController@enjoyman');

//商户登陆
Route::any('/home/businesslogin', 'Home\BusinessController@login');
Route::any('/home/businessLoginRes', 'Home\BusinessController@loginRes');
Route::any('/home/business/index', 'Home\BusinessController@index');
Route::any('/home/business/fabufuwu/{ids?}', 'Home\BusinessController@fabufuwu');
Route::any('/home/business/selectxiaoqu', 'Home\BusinessController@selectxiaoqu');
Route::any('/home/business/fabuRes', 'Home\BusinessController@fabuRes');
Route::any('/home/shenqingBusiness', 'Home\BusinessController@shenqing');
Route::any('/home/shenqingPage', 'Home\BusinessController@shenqingPage');
Route::any('/home/shenqingBusinessRes', 'Home\BusinessController@shenqingBusinessRes');


//物业登陆

Route::any('/home/wuyelogin', 'Home\WuyeController@login');
Route::any('/home/wuyeLoginRes', 'Home\WuyeController@LoginRes');
Route::any('/home/wuye/index', 'Home\WuyeController@index');
Route::any('/home/wuye/wuyehuifu', 'Home\WuyeController@wuyehuifu');
Route::any('/home/wuye/jiejue', 'Home\WuyeController@jiejue');


//后台管理员
Route::any('/home/guanliLogin', 'Home\GuanliController@login');
Route::any('/home/guanliLoginRes', 'Home\GuanliController@loginRes');
Route::any('/home/guanli/index', 'Home\GuanliController@index');
Route::any('/guanjia/select', 'Home\GuanliController@select');
Route::any('/guanli/jumpHome/{id}', 'Home\GuanliController@jumpHome');





//后台
Route::get('/admin/index', 'Admin\IndexController@index');

Route::get('/admin/login', 'Admin\IndexController@login');
Route::any('/admin/loginRes', 'Admin\IndexController@loginRes');
Route::any('/admin/loginout', 'Admin\IndexController@loginout');



Route::group(['as' => 'number'], function () {
    Route::any('/admin/numberBack', 'Admin\NumberController@numberBack');
    //添加账号处理
    Route::any('/admin/addRes', 'Admin\NumberController@addRes');
    //修改账号
    Route::any('/admin/editUser/{id}', 'Admin\NumberController@editUser');
    Route::any('/admin/editUserRes', 'Admin\NumberController@editUserRes');
    Route::any('/admin/deleteUser', 'Admin\NumberController@deleteUser');
    //修改状态
    Route::any('/admin/changeUser/{id}/{status}', 'Admin\NumberController@changeUser');

    //商户设置
    Route::any('/admin/numberBusiness', 'Admin\NumberController@numberBusiness');
    Route::any('/admin/addBusinessRes', 'Admin\NumberController@addBusinessRes');
    Route::any('/admin/editBusiness/{id}', 'Admin\NumberController@editBusiness');
    Route::any('/admin/editBusinessRes', 'Admin\NumberController@editBusinessRes');
    Route::any('/admin/deleteBusiness', 'Admin\NumberController@deleteBusiness');
    Route::any('/admin/shenqingbusiness', 'Admin\NumberController@shenqingbusiness');
    Route::any('/admin/changeBusiness/{id}/{status}', 'Admin\NumberController@changeBusiness');

    //社区管理员功能-账号管理

    Route::any('/admin/shequ_number', 'Admin\ShequNumberController@shequ_number');
    Route::any('/admin/shequ_addRes', 'Admin\ShequNumberController@shequ_addRes');
    Route::any('/admin/shequ_editUser/{id}', 'Admin\ShequNumberController@shequ_editUser');
    Route::any('/admin/shequ_editUserRes', 'Admin\ShequNumberController@shequ_editUserRes');

    //社区管理-物业设置
    Route::any('/admin/wuye_number', 'Admin\ShequNumberController@wuye_number');
    Route::any('/admin/wuye_addRes', 'Admin\ShequNumberController@wuye_addRes');
    Route::any('/admin/wuye_editUser/{id}', 'Admin\ShequNumberController@wuye_editUser');
    Route::any('/admin/wuye_editUserRes', 'Admin\ShequNumberController@wuye_editUserRes');


});


//行家在线
Route::group(['as' => 'hangjia'], function () {
    Route::any('/admin/hangjiazaixian', 'Admin\HangjiaController@index');
    Route::any('/admin/addHangjia', 'Admin\HangjiaController@addHangjia');
    Route::any('/admin/addHangjiaRes', 'Admin\HangjiaController@addHangjiaRes');
    Route::any('/admin/editHangjia/{id}', 'Admin\HangjiaController@editHangjia');
    Route::any('/admin/editHangjiaRes', 'Admin\HangjiaController@editHangjiaRes');
    Route::any('/admin/deleteHangjia', 'Admin\HangjiaController@deleteHangjia');
    Route::any('/admin/hangjia/checkstatus', 'Admin\HangjiaController@checkstatus');
});


//社区

Route::group(['as' => 'shequ'], function () {
    Route::any('/admin/shequ', 'Admin\ShequController@index');
    Route::any('/admin/addShequRes', 'Admin\ShequController@addShequRes');
    Route::any('/admin/editShequ/{id}', 'Admin\ShequController@editShequ');
    Route::any('/admin/editShequRes', 'Admin\ShequController@editShequRes');
    Route::any('/admin/deleteShequ', 'Admin\ShequController@deleteShequ');
    Route::any('/admin/enterXiaoqu/{id}', 'Admin\ShequController@enterXiaoqu');
    Route::any('/admin/changeShequ/{id}/{status}', 'Admin\ShequController@changeShequ');

//社区协议
    Route::any('/admin/shequxieyi', 'Admin\ShequController@shequxieyi');
    Route::any('/admin/addXieyi', 'Admin\ShequController@addXieyi');
});


//商城管理
Route::group(['as' => 'shangcheng'], function () {
    //轮播
    Route::any('/admin/lunbo', 'Admin\MallController@lunbo');
    Route::any('/admin/addLunbo', 'Admin\MallController@addLunbo');
    Route::any('/admin/addLunboRes', 'Admin\MallController@addLunboRes');
    Route::any('/admin/editLunbo/{id}', 'Admin\MallController@editLunbo');
    Route::any('/admin/editLunboRes', 'Admin\MallController@editLunboRes');
    Route::any('/admin/deleteLunbo', 'Admin\MallController@deleteLunbo');
//供应商
    Route::any('/admin/gongyingshang', 'Admin\MallController@gongyingshang');
    Route::any('/admin/addGongyingshangRes', 'Admin\MallController@addGongyingshangRes');
    Route::any('/admin/editGongyingshang/{id}', 'Admin\MallController@editGongyingshang');
    Route::any('/admin/editGongyingshangRes', 'Admin\MallController@editGongyingshangRes');
    Route::any('/admin/deleteGongyingshang', 'Admin\MallController@deleteGongyingshang');
    Route::any('/admin/changeGongyingshang/{id}/{status}', 'Admin\MallController@changeGongyingshang');
//分类
    Route::any('/admin/fenlei', 'Admin\MallController@fenlei');
    Route::any('/admin/addFenleiRes', 'Admin\MallController@addFenleiRes');
    Route::any('/admin/editFenlei/{id}', 'Admin\MallController@editFenlei');
    Route::any('/admin/editFenleiRes', 'Admin\MallController@editFenleiRes');
    Route::any('/admin/deleteFenlei', 'Admin\MallController@deleteFenlei');
    Route::any('/admin/changeFenlei/{id}/{status}', 'Admin\MallController@changeFenlei');
//ueditor上传图片
    Route::any('/admin/uploadimage', 'Admin\UploadImageController@uploadimage');
//商品列表
    Route::any('/admin/goods', 'Admin\MallController@goods');
    Route::any('/admin/addGoods', 'Admin\MallController@addGoods');
    Route::any('/admin/addGoodsRes', 'Admin\MallController@addGoodsRes');
    Route::any('/admin/editGoods/{id}', 'Admin\MallController@editGoods');
    Route::any('/admin/editGoodsRes', 'Admin\MallController@editGoodsRes');
    Route::any('/admin/deleteGoods', 'Admin\MallController@deleteGoods');
    Route::any('/admin/changeGoods/{id}/{status}', 'Admin\MallController@changeGoods');
});

Route::group(['as' => 'zhidekan'], function () {
    Route::any('/admin/zhidekan', 'Admin\ZhidekanController@index');
    Route::any('/admin/addZhide', 'Admin\ZhidekanController@addZhide');
    Route::any('/admin/addZhideRes', 'Admin\ZhidekanController@addZhideRes');
    Route::any('/admin/editZhide/{id}', 'Admin\ZhidekanController@editZhide');
    Route::any('/admin/editZhideRes', 'Admin\ZhidekanController@editZhideRes');
    Route::any('/admin/deleteZhide', 'Admin\ZhidekanController@deleteZhide');
});

Route::group(['as' => 'linlihudong'],function(){
    Route::any('/admin/linlihudong/{id?}', 'Admin\HuodongController@index');
    Route::any('/admin/checkPinlun/{id}', 'Admin\HuodongController@checkPinlun');
    Route::any('/admin/pinlun/check/{id}/{status}/{newsid}', 'Admin\HuodongController@changeStatus');
    Route::any('/admin/changeLinli/{id}/{flag}/{index}', 'Admin\HuodongController@changeLinli');
});

Route::group(['as' => 'tiaozao'],function(){
    Route::any('/admin/tiaozao/{id}', 'Admin\HuodongController@index');

});
Route::group(['as' => 'message'],function(){
    Route::any('/admin/sendMessage', 'Admin\HuodongController@sendMessage');

});
Route::group(['as' => 'ticket'],function(){
    Route::any('/admin/ticket', 'Admin\HuodongController@ticket');
    Route::any('/admin/addTicket', 'Admin\HuodongController@addTicket');
    Route::any('/admin/addTicketRes', 'Admin\HuodongController@addTicketRes');
    Route::any('/admin/delTicket/{id}', 'Admin\HuodongController@delTicket');

});



Route::group(['as' => 'bianminfuwu'],function(){
    Route::any('/admin/bianminfuwu', 'Admin\BianminController@index');
    Route::any('/admin/changeService/{id}/{status}', 'Admin\BianminController@changeService');
});

Route::group(['as' => 'toupiao'],function(){
    Route::any('/admin/toupiao', 'Admin\ToupiaoController@index');
    Route::any('/admin/editToupiao/{id}', 'Admin\ToupiaoController@editToupiao');
    Route::any('/admin/toupiaoRes/{id}', 'Admin\ToupiaoController@toupiaoRes');
    Route::any('/admin/editToupiaoRes', 'Admin\ToupiaoController@editToupiaoRes');
    Route::any('/admin/addtoupiao', 'Admin\ToupiaoController@addtoupiao');
    Route::any('/admin/addToupiaoRes', 'Admin\ToupiaoController@addToupiaoRes');
    Route::any('/admin/toupiao/close/{id}', 'Admin\ToupiaoController@close');
    Route::any('/admin/toupiao/open/{id}', 'Admin\ToupiaoController@open');
    Route::any('/admin/exportExcel', 'Admin\ToupiaoController@exportExcel');
    Route::any('/admin/exportPdf/{id}', 'Admin\ToupiaoController@exportPdf');
    Route::any('/admin/pdfPage/{id}', 'Admin\ToupiaoController@pdfPage');
    Route::any('/admin/pdfNumberPage/{id}', 'Admin\ToupiaoController@pdfNumberPage');
    Route::any('/admin/exportNumberPdf/{id}', 'Admin\ToupiaoController@exportNumberPdf');
});

Route::group(['as' => 'order'],function(){
    Route::any('/admin/orderlist', 'Admin\OrderController@index');
    Route::any('/admin/tuihuolist', 'Admin\OrderController@tuihuolist');
    Route::any('/admin/fahuoRes', 'Admin\OrderController@fahuoRes');

    Route::any('/admin/orderDetail/{id}', 'Admin\OrderController@orderDetail');
    Route::any('/admin/fahuo/{id}', 'Admin\OrderController@fahuo');

    Route::any('/admin/agree/{id}', 'Admin\OrderController@agree');
    Route::any('/admin/jujue/{id}', 'Admin\OrderController@jujue');

});

Route::group(['as' => 'yonghu'],function(){
    Route::any('/admin/yonghu/{type}', 'Admin\UserController@index');

    Route::any('/admin/user/checkstatus', 'Admin\UserController@checkstatus');
    Route::any('/admin/user/shezhi/{id}', 'Admin\UserController@shezhi');
    Route::any('/admin/user/shenfen', 'Admin\UserController@shenfen');
    Route::any('/admin/user/userdetail/{id}', 'Admin\UserController@userdetail');

});



