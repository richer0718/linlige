<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\PDF;

class ToupiaoController extends Controller
{
    //
    public function __construct()
    {

    }

    public function index(){
        if(!session('username')){
            return redirect('admin/login');
        }

        $res = DB::table('toupiao') ->where(function($query){
            $query -> where('xiaoqu','=',session('xiaoqu'));
            if(!empty($_POST['keywords'])){
                $query -> where('title','like','%'.$_POST['keywords'].'%');
            }
        }) -> orderBy('created_at','desc') -> get();
        $res = $this->object_array($res);


        $shenfen_arr = [
            '产权人',
            '居民',
            '业委会主任',
            '业委会副主任',
            '业委会秘书',
            '业委会委员',
            '业主代表',
            '社区管理员'
        ];


        foreach($res as $k => $vo){
            $temp = explode(',',$vo['shenfen']);
            $newarr = [];
            foreach($temp as $vol){
                $newarr[] = $shenfen_arr[$vol];
            }
            $res[$k]['shenfen'] = implode(',',$newarr);
        }

        //dd($res);

        return view('admin/toupiao/index') -> with([
            'res' => $res
        ]);
    }

    public function addtoupiao(){
        //查看小区楼号
        $res = DB::table('shequ') -> where([
            'id' => session('xiaoqu')
        ]) -> first();
        //dd($res);
        return view('admin/toupiao/addtoupiao') -> with([
            'res' => $res
        ]);
    }

    //增加投票处理
    public function addToupiaoRes(Request $request){
        //dd($request);
        if($request -> loudong_type == 0){
            //全部
            $loudong = 0;
        }else{
            $loudong = $request -> input('loudong');
            if($loudong){
                $loudong = implode(',',$loudong);
            }else{
                $loudong = 0;
            }

        }
        //dd($loudong);
        //小title
        $toupiao_id = DB::table('toupiao') -> insertGetId([
            'title' => $request -> input('toupiao_title'),
            'type' => $request -> input('type'),
            'status' => $request -> input('status'),
            'ishot' => $request -> input('ishot'),
            'is_sign' => $request -> input('is_sign'),
            'qianyan' => $request -> input('qianyan'),
            'jieshu' => $request -> input('jieshu'),
            'loudong' => $loudong,
            'shenfen' => implode(',',$request -> input('shenfen')),
            'xiaoqu' => session('xiaoqu'),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        //累加number
        $item = 0;
        foreach($request -> input('title') as $k => $vo){
            //每条内容
            $id = DB::table('toupiao_title') -> insertGetId([
                'title' => $vo,
                'fid' => $toupiao_id,
                'type' => $request -> input('select_type')[$k]
            ]);

            //添加选项
            //判断下此标题有多少选项
            $temp_number  = $request -> input('number')[$k];
            // item -> $temp_number - 1
            $j = $item+intval($temp_number)-1;


            for($i = $item;$i <= $j;$i++){
                $temp_option = $request -> input('option')[$i];
                //添加到option表中
                DB::table('toupiao_detail') -> insert([
                    'fid' => $id,
                    'name' => $temp_option
                ]);
            }

            $item = $item + intval($temp_number);

        }

        //如果有填空题
        $temp = $request -> tiankong;
        if($temp[0]){
            foreach($temp as $vo){
                //加入填空题表
                DB::table('tiankong') -> insert([
                    'toupiao_id' => $toupiao_id,
                    'title' => $vo
                ]);
            }
        }


        return redirect('admin/toupiao')->with([
            'insertres' => 'yes'
        ]);

    }

    //修改投票处理
    public function editToupiaoRes(Request $request){
        //把旧的这条删掉，再新增
        DB::table('toupiao') -> where([
            'id' => $request -> input('id')
        ]) -> delete();
        if($request -> loudong_type == 0){
            //全部
            $loudong = 0;
        }else{
            $loudong = $request -> input('loudong');
            $loudong = implode(',',$loudong);
        }
        //dd($loudong);
        //小title
        $toupiao_id = DB::table('toupiao') -> insertGetId([
            'title' => $request -> input('toupiao_title'),
            'type' => $request -> input('type'),
            'status' => $request -> input('status'),
            'ishot' => $request -> input('ishot'),
            'qianyan' => $request -> input('qianyan'),
            'jieshu' => $request -> input('jieshu'),
            'loudong' => $loudong,
            'shenfen' => implode(',',$request -> input('shenfen')),
            'xiaoqu' => session('xiaoqu'),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        //累加number
        $item = 0;
        foreach($request -> input('title') as $k => $vo){
            //每条内容
            $id = DB::table('toupiao_title') -> insertGetId([
                'title' => $vo,
                'fid' => $toupiao_id,
                'type' => $request -> input('select_type')[$k]
            ]);

            //添加选项
            //判断下此标题有多少选项
            $temp_number  = $request -> input('number')[$k];
            // item -> $temp_number - 1
            $j = $item+intval($temp_number)-1;


            for($i = $item;$i <= $j;$i++){
                $temp_option = $request -> input('option')[$i];
                //添加到option表中
                DB::table('toupiao_detail') -> insert([
                    'fid' => $id,
                    'name' => $temp_option
                ]);
            }

            $item = $item + intval($temp_number);

        }

        //如果有填空题
        $temp = $request -> tiankong;
        if($temp[0]){
            //把旧的全部删掉
            DB::table('tiankong') -> where([
                'toupiao_id' => $toupiao_id,
            ]) -> delete();
            foreach($temp as $vo){
                //加入填空题表
                DB::table('tiankong') -> insert([
                    'toupiao_id' => $toupiao_id,
                    'title' => $vo
                ]);
            }
        }


        return redirect('admin/toupiao')->with([
            'editres' => 'yes'
        ]);

    }

    public function editToupiao($id){
        //查下他有没有被投票
        $is_toupiao = DB::table('toupiao_result') -> where([
            'toupiao_id' => $id
        ]) -> first();
        if($is_toupiao){
            return redirect('admin/toupiao') -> with('is_toupiao','yes');
        }
        $toupiao = DB::table('toupiao') -> where([
            'id' => $id
        ]) -> first();
        $detail = DB::table('toupiao_title') -> where([
            'fid' => $id
        ]) -> get();
        foreach($detail as $k => $vo){
            $detail[$k] -> info = DB::table('toupiao_detail') -> where([
                'fid' => $vo -> id
            ]) -> get();

        }

        if($toupiao -> loudong){
            //如果有楼号 把楼号变成数组遍历
            $toupiao -> loudong = explode(',',$toupiao -> loudong);
        }
        $toupiao -> shenfen = explode(',',$toupiao -> shenfen);
        //dd($toupiao);

        //查看小区楼号
        $louhao = DB::table('shequ') -> where([
            'id' => session('xiaoqu')
        ]) -> first();

        //查看下有没有填空题
        $tiankongs = DB::table('tiankong') -> where([
            'toupiao_id' => $id
        ]) -> get();


        //dd($detail);
        return view('admin/toupiao/edittoupiao')->with([
            'res' => $toupiao,
            'detail' => $detail,
            'louhao' => $louhao,
            'tiankongs' => $tiankongs
        ]);
    }

    //关闭投票
    public function close($id){
        $res = DB::table('toupiao') -> where([
            'id' => $id
        ]) -> update([
            'status' => 1
        ]);
        return redirect('admin/toupiao')->with([
            'editres' => 'yes'
        ]);
    }
    //开启投票
    public function open($id){
        $res = DB::table('toupiao') -> where([
            'id' => $id
        ]) -> update([
            'status' => 0
        ]);
        return redirect('admin/toupiao')->with([
            'editres' => 'yes'
        ]);
    }

    //投票结果
    public function toupiaoRes($id){
        //先通过投票id查找title列表
        $title_list = DB::table('toupiao_title') -> where([
            'fid' => $id
        ]) -> get();

        //查看有没有填空题
        $tiankongs = DB::table('tiankong') -> where([
            'toupiao_id' => $id
        ]) -> get();
        //看下有没有回答填空题

        //查找答案
        $result_list = DB::table('toupiao_result') -> where([
            'toupiao_id' => $id
        ]) -> get();

        if($result_list){
            foreach($result_list as $k => $vo){
                //var_dump($vo);
                //var_dump($result_list);
                //$result_list[$k] -> results = explode(',',$vo -> result);
                if($vo -> result){
                    $temp = json_decode($vo -> result,true);
                    //dd($temp);
                    foreach($temp as $key => $value){
                        if(!is_array($value)){
                            //不是array 直接显示结果
                            $temp_new[$key] = DB::table('toupiao_detail') -> where([
                                'id' => $value
                            ]) -> first()->name;
                        }else{
                            //多选 遍历value 拼接结果
                            $temp_value = [];
                            foreach($value as $value_value){
                                $temp_value[] = DB::table('toupiao_detail') -> where([
                                    'id' => $value_value
                                ]) -> first()->name;
                            }
                            $temp_new[$key] = implode(',',$temp_value);
                        }

                    }

                    $result_list[$k] -> results = $temp_new;

                    $result_list[$k] -> userinfo = DB::table('user') -> where([
                        'openid' => $vo -> openid
                    ]) -> first();
                }
                if($vo -> tiankong_res){
                    $vo -> tiankong_res = explode('&&',$vo -> tiankong_res);
                }


            }
        }


        //dd($result_list);
        return view('admin/toupiao/toupiaoRes') -> with([
            'title_list' => $title_list,
            'result_list' => $result_list,
            'tiankongs' => $tiankongs,
            'id' => $id
        ]);
        /*
        foreach($title_list as $k => $vo){
            $title_list[$k]['result']
        }
        */


    }

    public function exportExcel(){
        //直接下载
        $download = true;

        $phpWord = new PhpWord();
        //添加页面
        $section = $phpWord->addSection();

        //添加目录
        $styleTOC  = ['tabLeader' => \PHPWord_Style_TOC::TABLEADER_DOT];
        $styleFont = ['spaceAfter' => 60, 'name' => 'Tahoma', 'size' => 12];
        $section->addTOC($styleFont, $styleTOC);

        //设置默认样式
        $phpWord->setDefaultFontName('仿宋');//字体
        $phpWord->setDefaultFontSize(16);//字号


        //
        $toupiaos = DB::table('toupiao') -> get();

        if($toupiaos){

            foreach($toupiaos as $k => $vo){
                //每次投票
                //默认样式
                $section->addText('投票'.($k+1));
                $section->addTextBreak();//换行符
                //标题
                $section->addText('标题：'.$vo -> title);
                $section->addTextBreak();//换行符
                //显示每道题目
                $titles = DB::table('toupiao_title') -> where([
                    'fid' => $vo -> id
                ]) -> get();
                if($titles){
                    //显示每道题目
                    foreach($titles as $key => $vol){
                        $section->addText(($key+1).'、'.$vol -> title);
                        $section->addTextBreak();//换行符
                    }

                    $section->addTextBreak();//换行符
                    //循环显示每组答案
                    $result_list = DB::table('toupiao_result') -> where([
                        'toupiao_id' => $vo -> id
                    ]) -> get();

                    if($result_list){

                        foreach($result_list as $key_temp => $vo_temp){
                            //先输出名字
                            $out_name = DB::table('user') -> where([
                                'openid' => $vo_temp -> openid
                            ]) -> first();
                            $section->addText($out_name -> name);
                            $section->addTextBreak();//换行符


                            //$result_list[$k] -> results = explode(',',$vo -> result);
                            $temp = json_decode($vo_temp -> result);
                            //dd($temp);
                            foreach($temp as $key => $value){

                                //先标题号
                                $section->addText(($key+1).'、');
                                if(!is_array($value)){
                                    //不是array 直接显示结果
                                    $temp_answer = DB::table('toupiao_detail') -> where([
                                        'id' => $value
                                    ]) -> first()->name;
                                    $section ->addText($temp_answer);
                                    $section->addTextBreak();//换行符

                                }else{
                                    //多选 遍历value 拼接结果
                                    $temp_value = [];
                                    foreach($value as $value_value){
                                        $temp_value[] = DB::table('toupiao_detail') -> where([
                                            'id' => $value_value
                                        ]) -> first()->name;
                                    }
                                    $temp_answer = implode(',',$temp_value);

                                    $section ->addText($temp_answer);
                                    $section->addTextBreak();//换行符
                                }

                            }

                            //$result_list[$k] -> results = $temp_new;


                        }
                    }



                }


            }

        }





        // Set writers
        $writers = [
            'Word2007' => 'docx',
        ];
        if ($download == false) {
            $writers['ODText'] = 'odt';
            $writers['RTF']    = 'rtf';
            $writers['HTML']   = 'html';
            $writers['PDF']    = null;// 不生成pdf了，因为没试成功
        }
        $result = '';
        foreach ($writers as $writer => $extension) {
            if ($extension == null) {
                $result .= '<p>'.date('H:i:s')." Write to {$writer} format   <font color='red'>fail</font></p>";
            } else {
                $result .= '<p>'.date('H:i:s')." Write to {$writer} format</p>";
                $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, $writer);
                //是否下载
                if ($download === true) {
                    $mime = [
                        'Word2007' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'ODText' => 'application/vnd.oasis.opendocument.text',
                        'RTF' => 'application/rtf',
                        'HTML' => 'text/html',
                        'PDF' => 'application/pdf',
                    ];
                    header('Content-Description: File Transfer');
                    header('Content-Disposition: attachment; filename="hello.'.$extension.'"');
                    header('Content-Type: '.$mime[$writer]);
                    header('Content-Transfer-Encoding: binary');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Expires: 0');
                    $filename = 'php://output'; // Change filename to force download
                    $xmlWriter->save($filename);
                } else {
                    $xmlWriter->save("hello.{$extension}");
                }
            }
        }
        if (!$download) {
            return $result;
        }

        /*
        $cellData = [
            ['学号','姓名','成绩'],
            ['10001','AAAAA','99'],
            ['10002','BBBBB','92'],
            ['10003','CCCCC','95'],
            ['10004','DDDDD','89'],
            ['10005','EEEEE','96'],
        ];
        Excel::create('学生成绩',function($excel) use ($cellData){
            $excel->sheet('投票结果', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
        */
    }


    //导出pdf
    public function exportPdf($id){
        header("Content-type:text/html;charset=utf-8");
        ///linli/public/index.php
        $selfurl =  $_SERVER['PHP_SELF'];
        //去掉index.php
        $url_arr = explode('/',$selfurl);
        //dump($url_arr);exit;
        unset($url_arr[count($url_arr) - 1]);
        $public_url = $url_arr;

        $url_arr[] = 'admin';
        $url_arr[] = 'pdfPage';
        $url_arr[] = $id;
        $url_pdf = implode('/',$url_arr);

        $pdfurl = 'http://'.$_SERVER['HTTP_HOST'].$url_pdf;
        //echo $pdfurl;exit;
        $time = time();
        exec("wkhtmltopdf ".$pdfurl." /data/wwwroot/www.szyeweihui.com/public/pdf/pdf".$time.".pdf 2>&1",$output);
        ///data/wwwroot/www.szyeweihui.com/public/pdf/pdf
        //echo "wkhtmltopdf ".$pdfurl." /webdata/laravel/public/pdf/pdf.pdf 2>&1" ;
        //dump($output);
        if(count($output)){
            $save_file = 'http://'.$_SERVER['HTTP_HOST'].implode('/',$public_url).'/pdf/pdf'.$time.'.pdf';
            echo "<a href='".$save_file."'>下载</a>";
            //dump($save_file);exit;
        }
    }

    //导出统计url
    public function exportNumberPdf($id){
        header("Content-type:text/html;charset=utf-8");
        ///linli/public/index.php
        $selfurl =  $_SERVER['PHP_SELF'];
        //去掉index.php
        $url_arr = explode('/',$selfurl);
        //dump($url_arr);exit;
        unset($url_arr[count($url_arr) - 1]);
        $public_url = $url_arr;

        $url_arr[] = 'admin';
        $url_arr[] = 'pdfNumberPage';
        $url_arr[] = $id;
        $url_pdf = implode('/',$url_arr);

        $pdfurl = 'http://'.$_SERVER['HTTP_HOST'].$url_pdf;
        //echo $pdfurl;exit;
        $time = time();
        exec("wkhtmltopdf ".$pdfurl." /data/wwwroot/www.szyeweihui.com/public/pdf/pdf".$time.".pdf 2>&1",$output);
        //echo "wkhtmltopdf ".$pdfurl." /webdata/laravel/public/pdf/pdf.pdf 2>&1" ;
        //dump($output);
        if(count($output)){
            $save_file = 'http://'.$_SERVER['HTTP_HOST'].implode('/',$public_url).'/pdf/pdf'.$time.'.pdf';
            echo "<a href='".$save_file."'>下载</a>";
            //dump($save_file);exit;
        }
    }

    public function pdfPage($id){
        //先通过投票id查找title列表
        $title_list = DB::table('toupiao_title') -> where([
            'fid' => $id
        ]) -> get();

        //查看有没有填空题
        $tiankongs = DB::table('tiankong') -> where([
            'toupiao_id' => $id
        ]) -> get();
        //看下有没有回答填空题

        //查找答案
        $result_list = DB::table('toupiao_result') -> where([
            'toupiao_id' => $id
        ]) -> get();

        if($result_list){
            foreach($result_list as $k => $vo){
                //var_dump($vo);
                //var_dump($result_list);
                //$result_list[$k] -> results = explode(',',$vo -> result);
                if($vo -> result){
                    $temp = json_decode($vo -> result,true);
                    //dd($temp);
                    foreach($temp as $key => $value){
                        if(!is_array($value)){
                            //不是array 直接显示结果
                            $temp_new[$key] = DB::table('toupiao_detail') -> where([
                                'id' => $value
                            ]) -> first()->name;
                        }else{
                            //多选 遍历value 拼接结果
                            $temp_value = [];
                            foreach($value as $value_value){
                                $temp_value[] = DB::table('toupiao_detail') -> where([
                                    'id' => $value_value
                                ]) -> first()->name;
                            }
                            $temp_new[$key] = implode(',',$temp_value);
                        }

                    }

                    $result_list[$k] -> results = $temp_new;

                    $result_list[$k] -> userinfo = DB::table('user') -> where([
                        'openid' => $vo -> openid
                    ]) -> first();
                }
                if($vo -> tiankong_res){
                    $vo -> tiankong_res = explode('&&',$vo -> tiankong_res);
                }


            }
        }


        //dd($result_list);
        return view('admin/toupiao/pdfPage') -> with([
            'title_list' => $title_list,
            'result_list' => $result_list,
            'tiankongs' => $tiankongs
        ]);
    }

    //pdf 页统计
    //统计每道题每个选项有多少人
    public function pdfNumberPage($id){
        //先通过投票id查找title列表
        $title_list = DB::table('toupiao_title') -> where([
            'fid' => $id
        ]) -> get();
        //查找答案
        $result_list = DB::table('toupiao_result') -> where([
            'toupiao_id' => $id
        ]) -> get();


        //把每个人的选项取出来
        $lists = [];
        foreach($result_list as $k => $vo){
            $lists[] = json_decode($vo -> result,true);
        }



        //找每道题的选项
        foreach($title_list as $k => $vo){
            $temp = DB::table('toupiao_detail') -> where([
                'fid' => $vo -> id
            ]) ->get();


            //dump($temp);
            foreach($temp as $key => $value){
                $temp[$key] -> sum = 0;
                //dump($value);exit;
                //看下 每个人 $k 的选项是什么
                foreach($lists as $voo){
                    //每个人
                    foreach($voo as $kkk => $vvv){

                        if(intval($vvv) == $value -> id){
                            //说明是此道题
                            $temp[$key] -> sum ++;
                        }
                    }

                }
            }

            $temp_str = '';
            foreach($temp as $kkkk => $vvvv){
                $temp_str .= $vvvv->name.':'.$vvvv -> sum.'人 ';
            }
            //dump($temp_str);exit;
            $title_list[$k] -> xuanxiang = $temp_str;
            //统计

        }

        //dump($title_list);

        return view('admin/toupiao/pdfNumberPage') -> with([
            'title_list' => $title_list
        ]);

    }





}
