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
        //echo 11;exit;
        $name = $this-> ask('What is your name?');
    }


}