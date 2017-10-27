@extends('layouts.admin_common')
@section('right-box')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main">

        <h1 class="page-header">管理 <span class="badge">{{ count($res) }}</span></h1>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th><span class="glyphicon glyphicon-th-large"></span> <span class="visible-lg">ID</span></th>
                    <th><span class="glyphicon glyphicon-user"></span> <span class="visible-lg">姓名</span></th>
                    <th><span class="glyphicon glyphicon-user"></span> <span class="visible-lg">电话号码</span></th>

                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">申请时间</span></th>
                </tr>
                </thead>
                <tbody>
                @unless(!$res)
                    @foreach($res as $vo)
                        <tr>
                            <td>{{$vo -> id}}</td>
                            <td>{{$vo -> name}}</td>
                            <td>{{$vo -> tel}}</td>
                            <td>{{ date('Y-m-d H:i',$vo -> created_at) }}</td>

                        </tr>
                    @endforeach
                @endunless
                </tbody>

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
        $(function () {
            @if (session('isset'))
            layer.alert('用户名重复！');
            $('#addUser').modal('show');
            @endif

            @if (session('show'))
            $('#editUser').modal('show')
            @endif

        $("#main table tbody tr td a").click(function () {
                var name = $(this);
                var id = name.parent().attr("data"); //对应id
                if (name.attr("name") === "edit") {

                } else if (name.attr("name") === "delete") {
                    if (window.confirm("此操作不可逆，是否确认？")) {

                        @if(isset($type) && $type == 'wuye')
                        var url = '{{ url('admin/deleteBusiness') }}';
                        @else
                        var url = '{{ url('admin/deleteUser') }}';
                            @endif


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
        });
    </script>

@stop