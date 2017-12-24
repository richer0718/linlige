<?php
/**
 * Created by PhpStorm.
 * User: richer
 * Date: 2017/7/29
 * Time: 上午8:43
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test(){
        DB::table('message') -> insert([
            'openid' => 'sadf',
            'message' => '您的注册申请已被退回，请重新申请',
            'created_at' => time()
        ]);
    }


}