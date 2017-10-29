<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AdminUser;
use App\Business;
use Illuminate\Support\Facades\DB;

class ShequNumberController extends Controller
{
    public function __construct()
    {

    }

//社区管理员
    public function shequ_number(){
        if(!session('username')){
            return redirect('admin/login');
        }
        //dd(session('type'));
        $model = new AdminUser();
        $res = $model -> where(['type' => 1,'xiaoqu'=>session('xiaoqu')]) ->paginate(15);
        return view('admin/number') -> with([
            'res' => $res,
            'type'=>'shequ',
        ]);
    }

    public function shequ_addRes(Request $request){
        $model = new AdminUser();
        $is_set = $model -> where(['username'=>$request -> input('username'),'type'=>1]) -> first();
        if($is_set){
            return redirect('admin/shequ_number')->with('isset', 'yes');
        }

        $res = $model -> insert([
            'username' =>  $request -> input('username'),
            'password' => $request -> input('password'),
            'type' => 1,
            'xiaoqu' => session('xiaoqu'),
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);
        if($res){
            return redirect('admin/shequ_number')->with('insertres', 'success');
        }

    }

    public function shequ_editUser($id){
        $model = new AdminUser();
        $res = $model -> where(['id'=>$id]) -> first();
        //var_dump($res['username']);exit;
        return redirect('admin/shequ_number') -> with([
            'userres' => $res,
            'show' => 'yes'
        ]);

    }
    public function shequ_editUserRes(Request $request){
        $model = new AdminUser();
        $res = $model -> where(['id'=>$request->input('id')]) -> update([
            'password' => $request -> input('password')
        ]);
        return redirect('admin/shequ_number') -> with([
            'editres' => 'success'
        ]);

    }

    //物业设置
    public function wuye_number(){
        $model = new Business();
        $res = $model -> where(['type' => 1,'xiaoqu'=>session('xiaoqu')]) ->paginate(15);

        //计算总解决数
        $count_number[] = DB::table('news') -> where(function($query){
            $query -> where('xiaoqu','=',session('xiaoqu'));
            $query -> where('type','=',0);
            $query -> where('status','=',1);
        }) -> count();
        //满意数
        $count_number[] = DB::table('user') -> where(function($query){
            $query -> where('xiaoqu','=',session('xiaoqu'));
            $query -> where('wuye_pingjia','=','yes');
        }) -> count();
        //不满意数
        $count_number[] = DB::table('user') -> where(function($query){
            $query -> where('xiaoqu','=',session('xiaoqu'));
            $query -> where('wuye_pingjia','=','no');
        }) -> count();

        return view('admin/number') -> with([
            'res' => $res,
            'type'=>'wuye',
            'count_number' => $count_number
        ]);
    }

    public function wuye_addRes(Request $request){
        $model = new Business();
        $is_set = $model -> where(['username'=>$request -> input('username'),'type'=>1]) -> first();
        if($is_set){
            return redirect('admin/wuye_number')->with('isset', 'yes');
        }
        $res = $model -> insert([
            'username' =>  $request -> input('username'),
            'password' => $request -> input('password'),
            'type' => 1,
            'xiaoqu' => session('xiaoqu'),
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);
        if($res){
            return redirect('admin/wuye_number')->with('insertres', 'success');
        }

    }

    public function wuye_editUser($id){
        $model = new Business();
        $res = $model -> where(['id'=>$id]) -> first();
        //var_dump($res['username']);exit;
        return redirect('admin/wuye_number') -> with([
            'userres' => $res,
            'show' => 'yes'
        ]);

    }
    public function wuye_editUserRes(Request $request){
        $model = new Business();
        $res = $model -> where(['id'=>$request->input('id')]) -> update([
            'password' => $request -> input('password')
        ]);
        return redirect('admin/wuye_number') -> with([
            'editres' => 'success'
        ]);

    }
}
