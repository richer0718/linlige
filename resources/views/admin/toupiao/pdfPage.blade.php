<html lang="zh-CN">
<head>
    @include('layouts.common_admin')
    <style>
        table tr{
            margin-top:5px;
        }
    </style>
</head>
<body style="margin-top:0;">


    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main" style="width:100%;margin-left:0;margin:0 auto;padding-top:0;">
        <div class="row">
            <h1 class="page-header">投票详情</h1>

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
</body>