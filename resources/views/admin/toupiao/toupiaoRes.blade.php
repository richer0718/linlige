@extends('layouts.admin_common')
@section('right-box')

    <script src="{{ asset('admin/lib/ueditor/ueditor.config.js') }}"></script>
    <script src="{{ asset('admin/lib/ueditor/ueditor.all.min.js') }}"> </script>
    <style>
        table tr{
            margin-top:5px;
        }
    </style>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main" style="height:800px;overflow-y: scroll;padding-bottom:100px;">
        <div class="row">
            <h1 class="page-header">投票详情</h1>
            <ol class="breadcrumb">
                <li><a data-toggle="modal" href="{{ url('admin/exportPdf').'/'.$id }}">导出投票结果</a></li>
            </ol>

                <div class="col-md-12">

                    <table class="table table-striped table-bordered" >
                        <tr><td colspan="2">选择题</td></tr>
                        @foreach($title_list as $k => $vo)
                        <tr>
                            <td style="width:10px;">{{ $k+1 }}</td>
                            <td class="hovertd"><a  >{{ $vo -> title }}</a></td>
                        </tr>
                        @endforeach
                        @if($tiankongs)
                            <tr><td colspan="2">填空题</td></tr>
                            @foreach($tiankongs as $k => $vo)
                                <tr>
                                    <td style="width:10px;">{{ $k+1 }}</td>
                                    <td class="hovertd"><a  >{{ $vo -> title }}</a></td>
                                </tr>
                            @endforeach
                        @endif

                    </table>

                </div>

                <div class="col-md-12">
                    <a>回答</a><br/>
                    @foreach($result_list as $vo )

                        <table class="table table-striped table-bordered" >
                            <tr><td colspan="2">{{ $vo -> userinfo -> name }}</td></tr>
                            <tr><td colspan="2">选择题</td></tr>
                            @foreach($vo -> results as $key => $vol)
                                <tr>
                                    <td style="width:10px;">{{ $key+1 }}、</td>
                                    <td class="hovertd"><a  >{{ $vol }}</a></td>
                                </tr>
                            @endforeach
                            @if($tiankongs)
                                <tr><td colspan="2">填空题</td></tr>
                                @foreach($vo -> tiankong_res as $key => $vol)
                                    <tr>
                                        <td style="width:10px;">{{ $key+1 }}、</td>
                                        <td class="hovertd"><a  >{{ $vol }}</a></td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>



                    @endforeach
                </div>
        </div>
    </div>


@stop