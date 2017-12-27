@extends('layouts.admin_common')
@section('right-box')
    <style>
        #mytable tr td{
            border:1px solid #000000;
        }
    </style>
    <script src="{{ asset('js/laydate/laydate.js') }}"></script>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main" style="max-height:800px;overflow: scroll;" >

        <h1 class="page-header">优惠券列表</h1>
<ol class="breadcrumb">
        <li><a data-toggle="modal" href="{{ url('admin/addTicket') }}">生成优惠券</a></li>
</ol>
        <h1 class="page-header">管理 <span class="badge"></span></h1>
        <div class="table-responsive"  >
            <table class="table table-striped table-hover" id="mytable">
                <thead>
                <tr>
                    <th><span class="glyphicon glyphicon-th-large"></span> <span class="visible-lg">ID</span></th>
                    <th><span class="glyphicon glyphicon-th-large"></span> <span class="visible-lg">优惠券标题</span></th>
                    <th><span class="glyphicon glyphicon-user"></span> <span class="visible-lg">优惠面额</span></th>
                    <th><span class="glyphicon glyphicon-user"></span> <span class="visible-lg">有效期</span></th>
                    <th><span class="glyphicon glyphicon-signal"></span> <span class="visible-lg">优惠券数量</span></th>
                    <th><span class="glyphicon glyphicon-camera"></span> <span class="visible-lg">优惠券已领取数</span></th>
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">优惠券剩余数量</span></th>
                    <th><span class="glyphicon glyphicon-pencil"></span> <span class="visible-lg">操作</span></th>
                </tr>
                </thead>
                <tbody>
                @unless(!$res)
                    @foreach($res as $vo)
                        <tr>
                            <td>{{$vo -> id }}</td>
                            <td>{{$vo -> title }}</td>
                            <td>{{$vo -> price }}</td>
                            <td>{{date('Y-m-d',$vo -> date) }}</td>
                            <td>{{$vo -> number }}</td>
                            <td>{{$vo -> number_has }}</td>
                            <td>{{$vo -> number_res }}</td>
                            <td><a href="{{ url('admin/delTicket').'/'.$vo->id }}">删除</a></td>
                        </tr>
                    @endforeach
                @endunless
                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>
    </div>






    <script>
        @if (session('addres'))
            layer.alert('添加成功！');
        @endif



    </script>
    <script>

    </script>
@stop