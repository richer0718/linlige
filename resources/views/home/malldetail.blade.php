<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        wx.config(<?php echo $js->config(array('onMenuShareAppMessage', 'onMenuShareWeibo'), true) ?>);
    </script>
    <script>
        wx.onMenuShareAppMessage({
            title: '分享标题', // 分享标题
            desc: '', // 分享描述
            link: '', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
// 用户确认分享后执行的回调函数
            },
            cancel: function () {
// 用户取消分享后执行的回调函数
            }
        });
    </script>
</head>
<body>
<header class="public-header">
    <i class="iconfont icon-fanhui" onclick="history.go(-1)"></i>
    <img src="{{asset('images/logo.png')}}">
</header>
<div class="swiper-container send-banner">
    <div class="swiper-wrapper">
        @foreach($res -> imgs as $vo)
        <div class="swiper-slide" style="background:url('{{ asset('uploads').'/'.$vo }}') no-repeat center;background-size:cover;"></div>
        @endforeach
    </div>
    <div class="swiper-pagination"></div>
</div>
<div class="send-goods">
    <h3>{{ $res -> title }}</h3>
    <div class="flex-justify">
        <div class="send-goods-data"><i>¥</i> {{ $res -> price_no }} <span>¥ {{ $res -> price_pre }}</span></div>
        <div class="send-goods-price"><span>库存 {{ $res -> kucun }}</span> <span>已售{{ $res -> xuni }}</span></div>
    </div>
</div>
<div class="baby-description">
    <nav class="flex-align" id="navigation">
        <a id="top1" class="hover">宝贝详情</a>
        <a id="top2">宝贝评价</a>
        <a id="top3">已购的邻居</a>
    </nav>
    <div class="baby-details" style="padding-bottom:10px;" id="box1">
        {!! $res -> content !!}
    </div>
    <div class="baby-details" id="box2" style="display:none;">
        @if(!empty($orders))
            @foreach($orders as $vo)
                @if($vo -> pinglun)
                <div class="flex baby-evaluation">
                    <div style="background:url('{{ $vo -> userinfo -> img }}') no-repeat center;background-size: 100% 100%;"></div>
                    <div class="flex-1">
                        <div class="star-num flex-justify">
                            <div class="flex">
                                <span>{{ $vo -> userinfo -> name }}</span>
                                <div class="evaluation-star">
                                    @for($i = 1;$i <= $vo -> star;$i++)
                                        <img src="{{asset('images/stars.png')}}" class="e-stars"/>
                                    @endfor
                                </div>
                            </div>
                            <span>{{ date('Y-m-d H:i',$vo -> created_at) }}</span>
                        </div>
                        <h3>{{ $vo -> pinglun }}</h3>
                        @if($vo -> imgs)
                            <div class="evaluation-img clearfix">
                            @foreach($vo -> imgs as $img)

                                <div style="background:url('{{asset('images').'/'.$img}}') no-repeat center;background-size:cover;"></div>

                            @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            @endforeach
        @endif
    </div>

    <div class="baby-details" style="display:none;">
        <div class="purchase-user">
            @if(!empty($people))
            <ul class="clearfix">
                @foreach($people as $vo)
                <li>
                    <div style="background:url('{{ $vo ->img }}') no-repeat center;background-size:cover;"></div>
                    <h4>{{ $vo -> name }}</h4>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>


</div>

<div class="fixed-height"></div>
<footer class="flex-align buy">
    <div class="flex-1">
        <div class="flex-align">
            <i class="iconfont icon-jian"></i>
            <span class="buy-num">1</span>
            <i class="iconfont icon-jia"></i>

            @if(!empty($res -> xiangou))<span>限购{{ $res -> xiangou }}个</span><input type="hidden" id="xiangou" value="{{ $res -> xiangou }}" />@endif
        </div>
    </div>
    <a id="buynow" >立即购买</a>
</footer>

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
        $('#navigation a').click(function(){
            var index = $(this).index();
            $('#navigation a').removeClass('hover');
            $(this).addClass('hover');
            $('.baby-details').hide();
            $('.baby-details').eq(index).show();
        })
        $('.icon-jian').click(function(){
            if(parseInt($('.buy-num').text()) > 1){
                //执行减操作
                $('.buy-num').text(parseInt($('.buy-num').text()) - 1);
            }
        })
        $('.icon-jia').click(function(){
            if($('#xiangou').val()){
                if(parseInt($('.buy-num').text()) < parseInt($('#xiangou').val())){
                    //执行加操作
                    $('.buy-num').text(parseInt($('.buy-num').text()) + 1);
                }
            }else{
                $('.buy-num').text(parseInt($('.buy-num').text()) + 1);
            }

        })

        //立即购买
        $('#buynow').click(function(){
            location.href='{{ url('home/buynow',['id'=>$res -> id])}}'+'/'+$('.buy-num').text();
        })

    });

</script>
</body>
</html>


