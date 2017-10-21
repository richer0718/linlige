<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
    <style>
        body{background:#fff;}
    </style>
</head>
<body style="background:#efefef;">

<header class="public-header">
    <i class="iconfont icon-fanhui" onclick="history.go(-1)"></i>
    <img src="{{ asset('images/logo.png') }}" />
</header>

<article class="set-more">
    <a id="sharebutton">
        <section class="flex-justify">
            <span>推荐好友关注</span><i class="icon iconfont icon-icon"></i>
        </section>
    </a>
    <a href="">
        <section class="flex-justify">
            <div class="help-set">
                <span>友邻互助设置</span>
                <div><i class="iconfont icon-gantanhao"></i>关闭后不再接收“友邻互助“新任务推送</div>
            </div>
            <label class="label-switch">
                <input type="checkbox">
                <div class="checkbox"></div>
            </label>
        </section>
    </a>
    <a href="{{ url('home/aboutus') }}">
        <section class="flex-justify" >
            <span>关于我们</span><i class="icon iconfont icon-icon"></i>
        </section>
    </a>
</article>
<div class="share none">
    <img src="{{ asset('images/share.png') }}" />
</div>


</body>
<script>
$(function(){
    $('#sharebutton').click(function(){
        $('.share').show();
    })
    $('.share').click(function(){
        $('.share').hide();
    })
})
</script>
</html>


