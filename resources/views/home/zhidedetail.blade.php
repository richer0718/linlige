<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
</head>
<body>
<style>
    body{background:#fff;}

</style>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    wx.config(<?php echo $js->config(array('onMenuShareAppMessage', 'onMenuShareWeibo'), false) ?>);
</script>
<script>
    wx.ready(function(){
        // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。


        wx.onMenuShareAppMessage({
            title: '邻里格', // 分享标题
            desc: '{{ $data -> title }}', // 分享描述
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

@include('layouts.common_fanhui')
<article class="article-page">
    <h3>{{$data -> title}}</h3>
    <div class="article-time">{{date('Y-m-d H:i',$data->created_at)}}</div>
    <div class="article-main">
        @if($data -> is_old == 1)
            {!! htmlspecialchars_decode($data -> content) !!}
            @else
            {!! $data -> content !!}
        @endif

    </div>
</article>
<div class="interaction" style="padding-bottom:20px;">
    <i class="iconfont icon-heart  "  ></i>
    <i class="iconfont icon-good @if(!$data -> zan){{ 'nocolor' }}@endif "  ></i>
</div>

</body>
<script>
    $(function(){
        @if(!$data -> shoucang)
            $('.icon-heart').css('color','#000000');
        @endif
        @if(!$data -> zan)
            $('.icon-good').css('color','#000000');
        @endif

        //点收藏
        $('.icon-heart').click(function(){


            var id = '{{ $data -> id }}';
            var url = '{{url('home/articleshoucang')}}';
            $.ajax({
                type: 'POST',
                url: url,
                data: {id:id},

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    if(data == 'success'){
                        $('.icon-heart').css('color','');
                    }else{
                        $('.icon-heart').css('color','#000000');
                    }
                },
                error: function(xhr, type){
                    alert('Ajax error!')
                }
            });
        })

        //点赞
        $('.icon-good').click(function(){


            var id = '{{ $data -> id }}';
            var url = '{{url('home/articlezan')}}';
            $.ajax({
                type: 'POST',
                url: url,
                data: {id:id},

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    if(data == 'success'){
                        $('.icon-good').css('color','');
                    }else{
                        $('.icon-good').css('color','#000000');
                    }
                },
                error: function(xhr, type){
                    alert('Ajax error!')
                }
            });
        })


    })
</script>
</html>


