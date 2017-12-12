<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ZhidekanController extends Controller
{
    //
    public function __construct()
    {

    }

    public function index(Request $request){
        if(!session('username')){
            return redirect('admin/login');
        }

        //值得看显示管理员列表
        $managelist = DB::table('admin') -> where([
            'type' => 1,
            'xiaoqu' => session('xiaoqu')
        ]) -> get();

        $res = DB::table('article') -> where(function($query) use($request){
            $query -> where('xiaoqu','=',session('xiaoqu'));
            if($request -> input('fabu_user')){
                $query -> where('fabu_user','=',$request -> input('fabu_user'));
            }
            $query -> orWhere('is_old','=',1);
        }) -> paginate(15);
        //dd($res);
        return view('admin/zhide/index')->with([
            'res' => $res,
            'managelist' => $managelist
        ]);
    }

    public function addZhide(){

        return view('admin/zhide/addZhide');
    }
    public function deleteZhide(Request $request){
        DB::table('article') -> where([
            'id' => $request -> input('id')
        ]) -> delete();
        echo 'success';
        //return redirect('admin/zhidelist');
    }
    public function addZhideRes(Request $request){
        //dd($request);
        $savearr = [
            'title' => $request -> input('title'),
            'content' => $request -> input('content'),
            'xiaoqu' => session('xiaoqu'),
            'ishot' => $request -> input('ishot'),
            'updated_at' => time(),
            'created_at' => time(),
        ];

        $res = DB::table('article') ->  insert($savearr);
        if($res){
            return redirect('admin/zhidekan')->with([
                'insertres' => 'yes'
            ]);
        }
    }

    public function editZhide($id){
        $res = DB::table('article') -> where(['id'=>$id]) -> first();
        return view('admin/zhide/addZhide') -> with([
            'res'=>$res
        ]);
    }

    public function editZhideRes(Request $request){
        $savearr = [
            'title' => $request -> input('title'),
            'content' => $request -> input('content'),
            'ishot' => $request -> input('ishot'),
            'updated_at' => time(),
            'created_at' => time(),
        ];




        $res = DB::table('article') ->  where(['id' => $request -> input('id')]) -> update($savearr);
        if($res){
            return redirect('admin/zhidekan')->with([
                'editres' => 'yes'
            ]);
        }
    }
}
