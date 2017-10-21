<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BianminController extends Controller
{
    public function __construct()
    {

    }
    //
    public function index(){
        if(!session('username')){
            return redirect('admin/login');
        }
        $res = DB::table('service')->where([
            'xiaoqu' => session('xiaoqu')
        ]) -> paginate('15');
        foreach($res as $k => $vo){
            $res[$k] -> number = DB::table('service') -> where([
                'created_at' => $vo -> created_at
            ]) -> count();
        }
        return view('admin/bianmin/index') -> with([
            'res' => $res
        ]);
    }

    public function changeService($id,$status){
        if($status == 0){
            DB::table('service') -> where([
                'id' => $id
            ]) -> update([
                'status' => 1
            ]);
        }else{
            DB::table('service') -> where([
                'id' => $id
            ]) -> update([
                'status' => 0
            ]);
        }

        return redirect('admin/bianminfuwu');
    }
}
