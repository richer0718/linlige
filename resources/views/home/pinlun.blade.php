<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
	<link rel="stylesheet" href="{{ asset('css/weui2.css') }}"/>
    <style>
        .public-header>i{
            top:38%;
        }
    </style>
</head>
<body>
@include('layouts.common_fanhui')
<section class="comment-title">
    <header class="comment-head flex-justify">
        <h3>{{$res['title']}}@if($res['type'] == 3)<strong><em>¥ </em>{{ $res['price'] }}</strong> <a href="tel:{{ $res['userinfo'] -> tel }}" ><i class="iconfont icon-dianhua"></i></a>@endif @if($res['type'] == 4)<a href="tel:{{ $res['userinfo'] -> tel }}" ><i class="iconfont icon-dianhua"></i></a>@endif </h3>
        <span>{{ $res['userinfo'] -> name }}</span>
    </header>
    @if($res['type'] == 3) <div class="comment-time"><span>时间</span> {{ $res['date'] }} 至 {{ $res['date_right'] }} </div> @endif
    <p>{{$res['miaoshu']}}</p>
    @if(!empty($res['img']))
    <div class="comment-img clearfix">
        @foreach($res['img'] as $vo)
        <div style="background:url('{{asset('images')}}/{{$vo}}') no-repeat center;background-size:cover;"  onclick="showimg(this) "></div>
        @endforeach
    </div>
    @endif
    <footer class="comment-foot flex-justify">
        <span>{{ date('Y-m-d H:i:s',$res['created_at']) }}</span>
        <div>
            <i class="iconfont icon-good"></i>
            <span id="dianzan_num">{{$res['dianzan']}}</span>
            <img src="{{asset('images/comment.png')}}" />
            <span>{{$res['liulan']}}</span>
        </div>
    </footer>
    @if(count($res['wuye_huifu']))
    <div class="comment-answer">
        @foreach($res['wuye_huifu'] as $vo)
        <p>物业回复：{{ $vo -> content }}</p>
            @if($vo -> imgs[0])
        <div class="answer-img flex">
            @foreach($vo -> imgs as $value)
            <div style="background:url('{{ asset('images').'/'.$value }}') no-repeat center;background-size:cover;">
                <input type="file" />
            </div>
            @endforeach

        </div>
            @endif

        @endforeach
    </div>
    @endif

</section>

@if(count($res['who']))
<div class="interested">
    <h3>感兴趣</h3>
    <div class="interested-foot">
        <div class="interested-img clearfix">
            @foreach($res['who'] as $vo)
            <div style="background:url('{{ $vo -> img }}') no-repeat center;background-size:cover;" onclick="location.href='{{ url('home/likeman').'/'.$vo -> openid }}'"></div>
            @endforeach
        </div>
        <div class="interested-more">
            <img src="{{ asset('images/interested-more.png') }}" />
        </div>
    </div>
</div>
@endif

@if(!empty(count($res_pinlun)))
<article class="comment-wrap">
    <h3>评论</h3>
    <div class="comment-content">

            @foreach($res_pinlun as $vo)
                <div class="flex comment-list">
                    <div style="background:url('@if(!empty($vo -> userinfo)){{$vo -> userinfo ->img}}@endif') no-repeat center;background-size:cover;" onclick="location.href='{{ url('home/likeman').'/'.$vo -> openid }}'"></div>
                    <div class="flex-1 comment-main">
                        <div class="flex-justify"><span>{{ $vo -> userinfo -> name }}</span><span>{{ date('Y-m-d H:i',$vo -> created_at) }}</span></div>
                        <p>{!! $vo -> content !!}</p>
                    </div>
                    @if(session('openid') == $vo -> userinfo -> openid)
                    <a style="font-size:10px;padding-left:5px;" onclick="shanchu({{ $vo -> id }})">删除</a>
                        @endif
                </div>
            @endforeach
    </div>
</article>
@endif
<div class="fixed-height"></div>
<div class="text-input-foot" style="z-index:999;">
    <div class="text-input flex-align">
        <i class="iconfont expression icon-xiaolian"></i>
        <input type="text" placeholder="评论: " class="flex-1" id="pinlun">

        <a id="fasong">发送</a>
    </div>
    <div class="replay-operate">
        <div class="faceList">
            <ul class="swiper-wrapper">
                <li class="swiper-slide"></li>
                <li class="swiper-slide"></li>
                <li class="swiper-slide"></li>
            </ul>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <input type="hidden" name="news_id" id="news_id" value="{{ $res['id'] }}" />
</div>

<div class="weui-gallery"  id="gallery" >
        <span class="weui-gallery__img" id="galleryImg" ></span>
        <div class="weui-gallery__opr" style="display:none;">
            <a href="javascript:" class="weui-gallery__del">
                <i class="weui-icon-delete weui-icon_gallery-delete"></i>
            </a>
        </div>
    </div>
	

<script>
    $(function(){
        var length = $(".interested-img div").length;
        if(length>6){
            $(".interested-img div:gt(5)").hide();
        }
        console.log($(".interested-img div:gt(6)"));
        $(".interested-more").click(function(){
            var $shows = $(".interested-img div:gt(5)");
            if($shows.is(":visible")){
                $(".interested-img div:gt(5)").hide();
                $(".interested-more img").css("-webkit-transform","rotate(0deg)");
            }else{
                $(".interested-img div:gt(5)").show();
                $(".interested-more img").css("-webkit-transform","rotate(180deg)");
            }
        });
    });

	function showimg(th){
		var imgurl = $(th).css("background-image").replace('url(','').replace(')','');
		$('#galleryImg').css('background-image',$(th).css("background-image"));
		$('#gallery').show();
	}
	$('.weui-gallery__img').click(function(){
		$('#gallery').hide();
	})
	

    /*表情库*/
    $(".expression").click(function(){
        $(".faceList").toggleClass("active");
    })
    var mySwiper = new Swiper('.faceList', {
        pagination : '.swiper-pagination',
    });
    var i;
    for(i=1;i<=18;i++){
        $(".faceList").find("li").eq(0).append('<div data= "' + i+ '" ><img src="{{asset('face')}}'+'/'+i+'.gif"/></div>')
    };
    for(i=19;i<=36;i++){
        $(".faceList").find("li").eq(1).append('<div data= "' + i+ '" ><img src="{{asset('face')}}'+'/'+i+'.gif"/></div>')
    };
    for(i=55;i<=72;i++){
        $(".faceList").find("li").eq(2).append('<div data= "' + i+ '" ><img src="{{asset('face')}}'+'/'+i+'.gif"/></div>')
    };
    $("input,textarea").focus(function(){
        $(".faceList").removeClass("active");
    })

    $('.faceList div ').click(function(){
        //先获取输入框的值
        var value = $("#pinlun").val();
        value += '[em_'+$(this).attr('data')+']';
        $("#pinlun").val(value);
    })

    $(function(){
        $('#fasong').click(function(){

            var content = $('#pinlun').val();
            var newsid = $('#news_id').val();


            if(!$.trim(content)){
                layer.msg('请填写内容');return false;
            }
            var url = '{{url('home/pinlunRes')}}';
            $.ajax({
                type: 'POST',
                url: url,
                data: {content:content,news_id:newsid},

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    if(data == 'success'){
                        layer.msg('发送成功');
                        setTimeout(function(){location.reload()},1000);
                        //location.reload();
                    }
                },
                error: function(xhr, type){
                    alert('Ajax error!')
                }
            });

        });

        //点赞
        $('.icon-good').click(function(){
            var newsid = $('#news_id').val();
            var url = '{{url('home/newszan')}}';
            $.ajax({
                type: 'POST',
                url: url,
                data: {news_id:newsid},

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    if(data == 'success'){
                        $('#dianzan_num').text(parseInt($('#dianzan_num').text() + 1));
                        layer.msg('点赞+1');
                    }else{
                        layer.msg('您已经点过赞了');
                    }
                },
                error: function(xhr, type){
                    alert('Ajax error!')
                }
            });


        })




    })


    //删除评论
    function shanchu(id){
        //询问框
        layer.confirm('您确定删除么', {
            btn: ['确定','取消'] //按钮
        }, function(){

            var url = '{{url('home/deletePinlun')}}';
            $.ajax({
                type: 'POST',
                url: url,
                data: {id:id},

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    if(data == 'success'){
                        layer.msg('删除成功！');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr, type){
                    alert('Ajax error!')
                }
            });


        }, function(){
            layer.msg('已取消');
        });
    }

</script>
</body>
</html>


