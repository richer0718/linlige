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
                    <h1 class="page-header">群发信息</h1>
                    <form method="post">
                        <table class="table table-striped table-bordered" style="width:400px;">
                            <tr>
                                <td style="width:120px;">内容：</td>
                                <td>
                                    <textarea class="form-control" name="first" rows="6">

                                    </textarea>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <input type="submit" value="发送" />
                                </td>
                            </tr>

                        </table>
                        {{ csrf_field() }}
                    </form>
                </div>


        </div>
    </div>


    <script>
        @if($sendres == 'success')
            alert('发送成功')
            @endif
    </script>

@stop