<?php

namespace App\Http\Controllers\Admin;

use App\AdminUser;
use App\Business;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NumberController extends Controller
{
    public function __construct()
    {


    }

    //
    public function numberBack(){
        if(!session('username')){
            return redirect('admin/login');
        }

        $keyword = '';
        if(!empty($_POST['keywords'])){
             $keyword = $_POST['keywords'];
        }

        $model = new AdminUser();
        $res = $model  -> where(['type' => 0]) -> where('username','like','%'.$keyword.'%') ->paginate(15);
        return view('admin/number') -> with([
            'res' => $res,
            'keywords' => $keyword
        ]);
    }

    public function addRes(Request $request){
        $model = new AdminUser();
        $is_set = $model -> where(['username'=>$request -> input('username'),'type'=>0]) -> first();
        if($is_set){
            return redirect('admin/numberBack')->with('isset', 'yes');
        }
        $res = $model -> insert([
            'username' =>  $request -> input('username'),
            'password' => $request -> input('password'),
            'type'=>0,
            'created_at' => time(),
            'updated_at' => time()
        ]);
        if($res){
            return redirect('admin/numberBack')->with('insertres', 'success');
        }

    }

    public function editUser($id){
        $model = new AdminUser();
        $res = $model -> where(['id'=>$id]) -> first();
        //var_dump($res['username']);exit;
        return redirect('admin/numberBack') -> with([
            'userres' => $res,
            'show' => 'yes'
        ]);

    }

    public function changeUser($id,$status){
        if($status == 0){
            DB::table('admin') -> where([
                'id' => $id
            ]) -> update([
                'status' => 1
            ]);
        }else{
            DB::table('admin') -> where([
                'id' => $id
            ]) -> update([
                'status' => 0
            ]);
        }
        if(session('type') == 1){
            return redirect('admin/shequ_number');
        }
        return redirect('admin/numberBack');
    }

    public function editUserRes(Request $request){
        $model = new AdminUser();
        $res = $model -> where(['id'=>$request->input('id')]) -> update([
            'password' => $request -> input('password')
        ]);
        return redirect('admin/numberBack') -> with([
            'editres' => 'success'
        ]);

    }

    public function deleteUser(Request $request){
        $model = new AdminUser();
        $res = $model -> where(['id'=>$request->input('id')]) -> delete();

        if($res){
            echo 'success';
        }
    }





    //商户设置
    public function numberBusiness(){
        $model = new Business();
        $res = $model  ->paginate(15);
        return view('admin/numberbusiness')->with([
            'res' => $res
        ]);
    }

    public function addBusinessRes(Request $request){
        $model = new Business();

        $isset = $model -> where(['username'=>$request -> input('username')]) -> first();
        if($isset){
            return redirect('admin/numberBusiness')->with('isset', 'success');
        }

        $res = $model -> insert([
            'username' =>  $request -> input('username'),
            'password' => $request -> input('password'),
            'created_at' => time(),
            'updated_at' => time()
        ]);
        if($res){
            return redirect('admin/numberBusiness')->with('insertres', 'success');
        }

    }

    public function changeBusiness($id,$status){
        if($status == 0){
            DB::table('business') -> where([
                'id' => $id
            ]) -> update([
                'status' => 1
            ]);
        }else{
            DB::table('business') -> where([
                'id' => $id
            ]) -> update([
                'status' => 0
            ]);
        }
        if(session('type') == 1){
            return redirect('admin/wuye_number');
        }
        return redirect('admin/numberBusiness');
    }

    public function editBusiness($id){
        $model = new Business();
        $res = $model -> where(['id'=>$id]) -> first();
        //var_dump($res['username']);exit;

        return redirect('admin/numberBusiness') -> with([
            'userres' => $res,
            'show' => 'yes'
        ]);

    }

    public function editBusinessRes(Request $request){
        $model = new Business();
        $res = $model -> where(['id'=>$request->input('id')]) -> update([
            'password' => $request -> input('password')
        ]);
        return redirect('admin/numberBusiness') -> with([
            'editres' => 'success'
        ]);
    }

    public function deleteBusiness(Request $request){
        $model = new Business();
        $res = $model -> where(['id'=>$request->input('id')]) -> delete();

        if($res){
            echo 'success';
        }
    }


}
