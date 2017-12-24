<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
    <style>
        body{background:#efefef;}
    </style>
</head>
<body>
    <header class="public-header">
        <i class="iconfont icon-fanhui" onclick="history.go(-1)"></i>
        <img src="{{asset('images/logo.png')}}">
    </header>
    @if($res)
    <article class="prompt">

            @foreach($res as $vo)
        <section>
            <p>{{ date('Y-m-d H:i',$vo -> created_at) }}</p>
            <div>{{ $vo -> message }}</div>
        </section>
            @endforeach

    </article>
    @endif
</body>
</html>


