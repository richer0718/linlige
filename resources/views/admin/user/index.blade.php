@extends('layouts.admin_common')
@section('right-box')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main" style="max-height:800px;overflow: scroll;" >

        <ol class="breadcrumb">
            <li><a>总解决数：{{$count_number[0]}} 满意数：{{$count_number[1]}} 不满意数：{{$count_number[2]}} </a></li>
        </ol>
        <h1 class="page-header">@if($type == 0)待审核列表@else用户列表@endif <span class="badge">{{ count($res) }}</span></h1>
        <div class="table-responsive"  >
            <table class="table table-striped table-hover" >
                <thead>
                <tr>
                    <th><span class="glyphicon glyphicon-th-large"></span> <span class="visible-lg">ID</span></th>
                    <th><span class="glyphicon glyphicon-user"></span> <span class="visible-lg">姓名</span></th>
                    <th><span class="glyphicon glyphicon-user"></span> <span class="visible-lg">性别</span></th>
                    <th><span class="glyphicon glyphicon-user"></span> <span class="visible-lg">签名</span></th>
                    <th><span class="glyphicon glyphicon-earphone"></span> <span class="visible-lg">手机号</span></th>
                    <th><span class="glyphicon glyphicon-camera"></span> <span class="visible-lg">小区</span></th>
                    <th><span class="glyphicon glyphicon-bookmark"></span> <span class="visible-lg">楼号</span></th>
                    <th><span class="glyphicon glyphicon-bookmark"></span> <span class="visible-lg">门牌号</span></th>
                    <th><span class="glyphicon glyphicon-bookmark"></span> <span class="visible-lg">身份</span></th>
                    <th><span class="glyphicon glyphicon-bookmark"></span> <span class="visible-lg">状态</span></th>
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">创建时间</span></th>
                    <th><span class="glyphicon glyphicon-pencil"></span> <span class="visible-lg">操作</span></th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($res))
                    @foreach($res as $vo)
                        <tr>
                            <td>{{$vo -> id}}</td>
                            <td>{{$vo -> name}}</td>
                            <td>@if($vo -> sex == 1)男@else女@endif</td>
                            <td><img src="{{ $vo -> sign }}" style="width:80px;height:100px;" /></td>
                            <td>{{$vo -> tel}}</td>
                            <td>{{$vo -> xiaoqu -> title}}</td>
                            <td>{{$vo -> louhao}}</td>
                            <td>{{$vo -> menpaihao}}</td>
                            <td>{{$vo -> shenfen}}</td>
                            <td>@if($vo -> status == 0)待审核@elseif($vo -> status == 1)审核通过@else禁用@endif</td>
                            <td>{{ date('Y-m-d H:i',$vo -> created_at) }}</td>
                            <td data="{{$vo -> id}}" >
                                <a  class="shezhi">设置身份</a>
                                @if($vo -> status == 0)
                                    <a class="checkdata">审核</a>
                                @elseif($vo -> status == 1)
                                    <a class="jinyong">禁用</a>
                                @else
                                    <a class="huifu">恢复</a>

                                @endif</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">@if(!empty($res)){{ $res -> links() }}@endif</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>



    <script>
        @if (session('insertres'))
            layer.alert('添加成功！');
        @endif
        @if (session('editres'))
            layer.alert('修改成功！');
        @endif

    </script>
    <script>
        $(function(){
            $('.checkdata').click(
                function(){
                    var id =  $(this).parent().attr('data');
                    var url = '{{ url('admin/user/checkstatus') }}'


                    layer.confirm('请选择审核结果', {
                        btn: ['通过','不通过'] //按钮
                    }, function(){

                        //修改用户状态
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {id:id,status:1},

                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                            success: function(data){
                                layer.alert('审核成功');
                                location.reload();
                            },
                            error: function(xhr, type){
                                layer.alert('Ajax error!')
                            }
                        });

                    }, function(){
                        var url = '{{ url('admin/user/checkstatus') }}'
                        //修改用户状态
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {id:id,status:3},

                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                            success: function(data){
                                layer.alert('审核成功');
                                location.reload();
                            },
                            error: function(xhr, type){
                                layer.alert('Ajax error!')
                            }
                        });

                    });
                }
            );

            //禁用
            $('.jinyong').click(
                function(){
                    var id =  $(this).parent().attr('data');
                    var url = '{{ url('admin/user/checkstatus') }}'


                    layer.confirm('是否禁用', {
                        btn: ['是','否'] //按钮
                    }, function(){
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {id:id,status:0},

                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                            success: function(data){
                                layer.alert('已禁用');
                                location.reload();
                            },
                            error: function(xhr, type){
                                layer.alert('Ajax error!')
                            }
                        });


                    }, function(){
                        layer.msg('已取消');
                        //修改用户状态
                    });
                }
            );


            //恢复
            $('.huifu').click(
                function(){
                    var id =  $(this).parent().attr('data');
                    var url = '{{ url('admin/user/checkstatus') }}'


                    layer.confirm('是否恢复', {
                        btn: ['是','否'] //按钮
                    }, function(){

                        //修改用户状态
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {id:id,status:1},

                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                            success: function(data){
                                layer.alert('已恢复');
                                location.reload();
                            },
                            error: function(xhr, type){
                                layer.alert('Ajax error!')
                            }
                        });

                    }, function(){
                        layer.msg('已取消');


                    });
                }
            );



            //设置身份
            $('.shezhi').click(function(){
                var id =  $(this).parent().attr('data');
                window.open('{{ url('admin/user/userdetail') }}/'+id);
                /*
                var id =  $(this).parent().attr('data');
                layer.open({
                    type: 2,
                    title: '设置身份',
                    shadeClose: true,
                    shade: 0.8,
                    area: ['380px', '350px'],
                    content: '{{ url('admin/user/shezhi')}}/'+id
                });
                */
            })
        })
        @if (session('check'))
            layer.alert('设置成功！');
        @endif
    </script>

@stop