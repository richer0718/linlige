@extends('layouts.admin_common')
@section('right-box')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main">
        <div class="row">
            <form action="{{ url('admin/editHangjiaRes') }}" method="post" class="add-article-form" enctype="multipart/form-data">
                <input type="hidden" name="id" value="{{ $res['id'] }}" />
                <div class="col-md-9">
                    <h1 class="page-header">修改行家</h1>
                    <div class="form-group">
                        <label for="article-title" class="">标题</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="在此处输入标题" value="{{ $res['title'] }}" required autofocus autocomplete="off">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="article-title" class="">姓名</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="在此处输入行家姓名" value="{{ $res['name'] }}" required autofocus autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="article-title" style="float:left;line-height:34px;">行家电话：&nbsp;&nbsp;&nbsp;</label>
                        <input type="text" id="tel" name="tel" class="form-control" value="{{ $res['tel'] }}" placeholder="在此处输入行家电话" required autofocus autocomplete="off" style="float:left;width:200px;">
                        <div style="float:left;line-height:34px;"><label style="padding-left:20px;">显示<input type="radio" name="xuanze" value="0" @if($res['xuanze'] == 0) checked @endif /></label></div>
                        <div style="clear:both;"></div>
                    </div>
                    <div class="form-group">
                        <label for="article-title" style="float:left;line-height:34px;">管理员电话：</label>
                        <input type="text" id="tel" name="tel2" class="form-control" value="{{ $res['tel2'] }}" placeholder="在此处输入行家电话" required autofocus autocomplete="off" style="float:left;width:200px;">
                        <div style="float:left;line-height:34px;"><label style="padding-left:20px;">显示<input type="radio" name="xuanze" value="1" @if($res['xuanze'] == 1) checked @endif  /></label></div>
                        <div style="clear:both;"></div>
                    </div>

                    <div class="add-article-box">
                        <h2 class="add-article-box-title"><span>描述</span></h2>
                        <div class="add-article-box-content">
                            <textarea class="form-control" name="content" autocomplete="off" required>{{ $res['content'] }}</textarea>
                            <span class="prompt-text">最多54个字</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <h1 class="page-header">操作</h1>
                    <div class="add-article-box">
                        <h2 class="add-article-box-title"><span>图片</span></h2>
                        <img src="{{ asset('uploads').'/'.$res['img'] }}"  style="width:160px;height:200px;"/>
                    </div>

                    <div class="add-article-box" style="text-align: center">
                        <p style="line-height:30px;">如不需修改，请误上传</p>
                        <div class="add-article-box-content">
                            <input type="file" class="form-control" placeholder="点击按钮选择图片" id="pictureUpload" name="file" autocomplete="off" >
                        </div>
                        <div class="add-article-box-footer">
                            <button class="btn btn-primary" type="submit" name="submit">发布</button>
                        </div>
                    </div>

                </div>
                {{ csrf_field() }}
            </form>
        </div>
    </div>

@stop