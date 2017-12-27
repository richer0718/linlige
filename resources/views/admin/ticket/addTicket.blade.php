@extends('layouts.admin_common')
@section('right-box')

    <style>
        table tr{
            margin-top:5px;
        }
        .laydate_box, .laydate_box * {
            box-sizing:content-box;
        }
    </style>
    <script src="{{ asset('js/laydate/laydate.js') }}"></script>
    <div style="height:100%;width:100%;position:fixed;z-index:0;" id="superdiv"></div>
    <form id="myForm" method="post" action="{{ url('admin/addTicketRes') }}" >
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main" >
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">添加优惠券</h1>
                    <div class="col-md-5">

                        <table class="table table-striped table-bordered" style="width:100%;">
                            <tr>
                                <td style="width:120px;">优惠券标题：</td>
                                <td><input type="text"  class="form-control"  name="title" required /></td>
                            </tr>
                            <tr>
                                <td style="width:120px;">优惠券面额：</td>
                                <td><input type="number"  class="form-control"  name="price"  required /></td>
                            </tr>
                            <tr>
                                <td style="width:120px;">有效期：</td>
                                <td><input type="text"  class="form-control"  name="date"  required onclick="laydate({istime: false, format: 'YYYY-MM-DD'})"  /></td>
                            </tr>
                            <tr>
                                <td style="width:120px;">优惠券数量：</td>
                                <td><input type="number"  class="form-control"  name="number"  required /></td>
                            </tr>
                            {{ csrf_field() }}
                            <tr>
                                <td colspan="2">
                                    <button class="btn btn-success" type="submit" id="addButton">添加</button>
                                    <button class="btn btn-default" type="button" id="rest">重置</button>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <div class="col-md-7">

                    </div>

                </div>


            </div>
        </div>
    </form>




@stop