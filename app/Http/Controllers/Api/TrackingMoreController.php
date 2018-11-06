<?php

namespace App\Http\Controllers\Api;

use App\Business;
use App\Hangjia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class TrackingMoreController extends Controller
{
    public function index(){
        $arr = json_decode($_POST['numbers'],true);
        echo  $this -> getInfoByTrackingMore($arr);
    }


    public function getInfoByTrackingMore($arr){
        $url = 'https://api.trackingmore.com/v2/trackings/batch';
        $res = $this -> post($url,$arr,[
            'Content-Type: application/json',
            'Trackingmore-Api-Key:015dbffa-15ef-4f5a-83a2-dc040d64816d'
        ]);
        return $res;
    }


    public function get($url,$headers = []){
        header("Content-type:text/html;charset=utf-8");
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return  curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    public  function post($url, $post_data = '',$headers = []){
        header("Content-type:text/html;charset=utf-8");
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return  curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }


}
