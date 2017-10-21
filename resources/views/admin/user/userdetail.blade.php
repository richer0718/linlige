@extends('layouts.admin_common')
@section('right-box')

    <script src="{{ asset('admin/lib/ueditor/ueditor.config.js') }}"></script>
    <script src="{{ asset('admin/lib/ueditor/ueditor.all.min.js') }}"> </script>
    <style>
        table tr{
            margin-top:5px;
        }
    </style>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main" style="height:700px;overflow-y: scroll;padding-bottom:100px;">
        <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">用户信息</h1>

                    <table class="table table-striped table-bordered" style="width:400px;">
                        <tr>
                            <td style="width:120px;">姓名：</td>
                            <td><input type="text" value="{{ $res -> name }}" class="form-control" disabled/></td>
                        </tr>
                        <tr>
                            <td>注册时间：</td>
                            <td><input type="text" value="{{ date('Y-m-d H:i',$res -> created_at) }}" class="form-control" disabled/></td>
                        </tr>
                        <tr>
                            <td>性别：</td>
                            <td>
                                <input type="text" class="form-control" disabled value="@if($res -> sex == 1)男@else女@endif"  />
                            </td>
                        </tr>
                        <tr>
                            <td>头像：</td>
                            <td>
                                <img src="{{ $res -> img }}"  style="width:80px;height:80px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td>签名：</td>
                            <td>
                                <img src="{{ $res -> sign }}"  style="width:100px;height:120px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td>手机号码：</td>
                            <td>
                                <input type="text" class="form-control" disabled value="{{ $res -> tel }}"  />
                            </td>
                        </tr>
                        <tr>
                            <td>家庭状态：</td>
                            <td>
                                <select class="status" name="jiating">
                                    <option value="1" @if($res -> jiating == 1) selected @endif >单身青年</option>
                                    <option value="2" @if($res -> jiating == 2) selected @endif >二人世界</option>
                                    <option value="3" @if($res -> jiating == 3) selected @endif >三口之家</option>
                                    <option value="4" @if($res -> jiating == 4) selected @endif >一家四口</option>
                                    <option value="5" @if($res -> jiating == 5) selected @endif >五福临门</option>
                                    <option value="6" @if($res -> jiating == 6) selected @endif >更多</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>小区：</td>
                            <td><input type="text" class="form-control" disabled value="{{ $res -> xiaoqu -> title }}"  /></td>
                        </tr>
                        <tr>
                            <td>楼号：</td>
                            <td><input type="text" class="form-control" disabled value="{{ $res -> louhao }}"  /></td>
                        </tr>
                        <tr>
                            <td>门牌号：</td>
                            <td>
                                <input type="text" class="from-control" disabled value="{{ $res -> menpaihao }}" />
                            </td>
                        </tr>


                    </table>

                    <form action="{{ url('admin/user/shenfen') }}" >
                        <input type="hidden" name="id" value="{{ $res -> id }}" />
                        <div>请设置身份</div>
                        <div><label><input type="radio" name="shenfen" value="0" @if($res -> shenfen == 0) checked @endif />产权人</label></div>
                        <div><label><input type="radio" name="shenfen" value="1" @if($res -> shenfen == 1) checked @endif />居民</label></div>
                        <div><label><input type="radio" name="shenfen" value="2" @if($res -> shenfen == 2) checked @endif />业委会主任</label></div>
                        <div><label><input type="radio" name="shenfen" value="3" @if($res -> shenfen == 3) checked @endif />业委会副主任</label></div>
                        <div><label><input type="radio" name="shenfen" value="4" @if($res -> shenfen == 4) checked @endif />业委会秘书</label></div>
                        <div><label><input type="radio" name="shenfen" value="5" @if($res -> shenfen == 5) checked @endif />业委会委员</label></div>
                        <div><label><input type="radio" name="shenfen" value="6" @if($res -> shenfen == 6) checked @endif />业主代表</label></div>
                        <div><label><input type="radio" name="shenfen" value="7" @if($res -> shenfen == 7) checked @endif />社区管理员</label></div>
                        <div><button class="btn btn-default" type="button" id="queding">确定</button></div>
                    </form>




                </div>


        </div>
    </div>


    <script>
        $(function(){
            $('#queding').click(function(){
                var id =  '{{ $res -> id }}';
                var url = '{{ url('admin/user/shenfen') }}'
                var shenfen = $('input[name=shenfen]:checked').val();
                //设置身份
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {id:id,shenfen:shenfen},

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success: function(data){
                        layer.alert('设置成功');
                        parent.location.reload();
                        /*
                         var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                         parent.layer.close(index);
                         */
                    },
                    error: function(xhr, type){
                        layer.alert('Ajax error!')
                    }
                });


            })
        })
    </script>

@stop