@extends('layouts.admin_common')
@section('right-box')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main" style="max-height:800px;overflow: scroll;" >

        <h1 class="page-header">退货待处理列表</h1>

        <h1 class="page-header">管理 <span class="badge"></span></h1>
        <div class="table-responsive"  >
            <table class="table table-striped table-hover" >
                <thead>
                <tr>
                    <th><span class="glyphicon glyphicon-th-large"></span> <span class="visible-lg">ID</span></th>
                    <th><span class="glyphicon glyphicon-th-large"></span> <span class="visible-lg">订单编号</span></th>
                    <th><span class="glyphicon glyphicon-signal"></span> <span class="visible-lg">订单详情</span></th>
                    <th><span class="glyphicon glyphicon-user"></span> <span class="visible-lg">购买人</span></th>
                    <th><span class="glyphicon glyphicon-camera"></span> <span class="visible-lg">配送详情</span></th>
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">下单时间</span></th>
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">付款状态</span></th>
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">售后状态</span></th>
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">退货原因</span></th>
                    <th><span class="glyphicon glyphicon-pencil"></span> <span class="visible-lg">操作</span></th>
                </tr>
                </thead>
                <tbody>
                @unless(!$res)
                    @foreach($res as $vo)
                        <tr>
                            <td>{{$vo -> id }}</td>
                            <td>{{$vo -> order_id }}</td>
                            <td></td>
                            <td>{{$vo -> user_info -> name}}</td>
                            <td></td>
                            <td>{{ date('Y-m-d H:i',$vo -> created_at) }}</td>
                            <td>@if($vo -> fukuan_status == 0)未付款@else已付款@endif</td>
                            <td>@if($vo -> fahuo_status == 0)未发货@else已发货@endif</td>
                            <td>退货原因</td>
                            <td data="{{$vo -> id}}"><a  name="edit"  href="{{ url('admin/fahuo',['id'=>$vo -> id ]) }}">处理</a></td>
                        </tr>
                    @endforeach
                @endunless
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5"></td>
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
            @if (session('show'))
                $('#editShequ').modal('show')
            @endif

            //数据验证
            $('#myAddShequForm').submit(function(){

                if($.trim($('#myAddShequForm input[name=password]').val()) && $.trim($('#myAddShequForm input[name=password]').val()) != $.trim($('#myAddShequForm input[name=new_password]').val())){
                    layer.alert('两次填写密码不一致');return false;
                }
            })
            $('#myEditShequForm').submit(function(){

                if($.trim($('#myEditShequForm input[name=password]').val()) != $.trim($('#myEditShequForm input[name=new_password]').val())){
                    layer.alert('两次填写密码不一致');return false;
                }
            })

            $("#main table tbody tr td a").click(function () {
                var name = $(this);
                var id = name.parent().attr("data"); //对应id
                if (name.attr("name") === "delete") {
                    if (window.confirm("此操作不可逆，是否确认？")) {

                        var url = '{{ url('admin/deleteShequ') }}';

                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {id:id},
                            //dataType:'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data){
                                if(data == 'success'){
                                    layer.alert('删除成功');
                                    location.reload();
                                }
                            },
                            error: function(xhr, type){
                                layer.alert('Ajax error!')
                            }
                        });
                    };
                };
            });
        })
    </script>
@stop