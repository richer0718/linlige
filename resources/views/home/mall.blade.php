<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        wx.config(<?php echo $js->config(array('onMenuShareAppMessage', 'onMenuShareWeibo'), false) ?>);
    </script>
    <script>
        wx.ready(function(){
            // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。


            wx.onMenuShareAppMessage({
                title: '邻里格', // 分享标题
                desc: '', // 分享描述
                link: '', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'http://www.szyeweihui.com/public/images/logo.jpg', // 分享图标
                type: 'link', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
// 用户确认分享后执行的回调函数
                },
                cancel: function () {
// 用户取消分享后执行的回调函数
                }
            });

        });



    </script>
</head>
<body>
<header class="public-header">
    <img src="{{asset('images/logo.png')}}">
    <span onclick="location.href='{{ url('home/myorder') }}' " style="position:absolute;top:10px;right:5px;" >我的订单</span>
</header>
<article class="vote vote-shop">
    <header class="flex-justify" id="top-fenlei">
        <a class="hover"><span>全部</span></a>
        @if(!empty($res_fenlei))
            @foreach($res_fenlei as $vo)
                <a><span>{{ $vo -> name }}</span></a>
            @endforeach
        @endif
    </header>
    <div class="swiper-container shop-banner">
        <div class="swiper-wrapper">
            @foreach($res_lunbo as $vo)
            <div class="swiper-slide" onclick="location.href='{{ $vo -> url_true }}' "  style="background:url('{{asset('uploads/').'/'.$vo->img}}') no-repeat center;background-size:cover;"></div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <div id="vo-box1" class="hidebox">
    @if(!empty($res))
        @foreach($res as $vo)
            <div class="shop-goods">
                <div class="shop-img" onclick="location.href='{{ url('home/malldetail').'/'.$vo -> id }}'" style="background:url('{{asset('uploads/').'/'.$vo->img}}') no-repeat center;background-size:100% 100%;"></div>
                <h3>{{ $vo -> title }}</h3>
                <div class="flex-align shop-data">
                    <div class="flex-1"><i>¥</i> {{ $vo -> price_no }} <span>¥ {{ $vo -> price_pre }}</span></div>
                    <span>已售 {{$vo -> xuni}}</span>
                    <a href="{{ url('home/malldetail').'/'.$vo -> id }}">立即购买</a>
                </div>
            </div>
        @endforeach
    @endif
    </div>


        @foreach($res_fenlei as $key => $value)
            <div id="vo-box{{ intval($key)+2 }}" style="display:none;" class="hidebox" >
            @if(!empty($res))
                @foreach($res as $vo)
                    @if($vo -> type == $value -> id)
                    <div class="shop-goods">
                        <div class="shop-img" onclick="location.href='{{ url('home/malldetail').'/'.$vo -> id }}'" style="background:url('{{asset('uploads/').'/'.$vo->img}}') no-repeat center;background-size:cover;"></div>
                        <h3>{{ $vo -> title }}</h3>
                        <div class="flex-align shop-data">
                            <div class="flex-1"><i>¥</i> {{ $vo -> price_no }} <span>¥ {{ $vo -> price_pre }}</span></div>
                            <span>已售 {{$vo -> number}}</span>
                            <a href="{{ url('home/malldetail').'/'.$vo -> id }}">立即购买</a>
                        </div>
                    </div>
                    @endif
                @endforeach
            @endif
            </div>
        @endforeach


</article>
<div class="fixed-height"></div>

<script src="{{asset('js/jquery-1.11.1.min.js')}}"></script>
<script src="{{asset('js/fastclick-min.js')}}"></script>
<script src="{{asset('js/swiper.3.2.0.jquery.min.js')}}"></script>
<script src="{{asset('js/script.js')}}"></script>
<script>
    var mySwiper = new Swiper ('.swiper-container', {
        loop: true,
        pagination: '.swiper-pagination',
        autoplayDisableOnInteraction : false,
    })
</script>
<script>
    $(function(){
        $('#top-fenlei a').click(function(){
            $('#top-fenlei a').removeClass('hover');
            $(this).addClass('hover');
            var index = $(this).index()+1;
            $('.vote-shop .hidebox').hide();
            $('#vo-box'+index).show();
        })
    })
</script>
</body>
</html>


