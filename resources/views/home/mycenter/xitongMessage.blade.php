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
    <article class="prompt">
        @if($res)
            @foreach($res as $vo)
        <section>
            <p>{{ date('Y-m-d H:i',$vo -> created_at) }}</p>
            <div>{{ $vo -> message }}</div>
        </section>
            @endforeach
        @endif
    </article>
</body>
</html>


