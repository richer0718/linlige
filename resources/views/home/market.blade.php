<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
    <script src="{{asset('js/jquery.lazyload.min.js')}}"></script>
    <style>

        .weui-gallery {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: #000000;
            z-index: 1000;
        }
        .weui-gallery__img {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 60px;
            left: 0;
        }
        .weui-gallery__opr {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: #0D0D0D;
            color: #FFFFFF;
            line-height: 20px;
            text-align: center;
        }
        .weui-gallery__del {
            display: block;
        }
    </style>
</head>
<body>
@include('layouts.common_fanhui')
@if(!empty($userinfo))
<img src="{{asset('images/user-comment.png')}}" class="user-comment" id="user-comment"/>
@endif
@if(!empty($res))
    @foreach($res as $vo)
        <section class="comment-title" style="border-bottom: 1px solid #efefef;" >
            <header class="comment-head flex-justify"  >
                <h3 onclick="location.href='{{ url('home/pinlun',['id'=>$vo['id']]) }}'"  >{{ $vo['title'] }}</h3>
                <span onclick="location.href='{{ url('home/likeman').'/'.$vo['userinfo'] -> openid }}'"  >{{ $vo['userinfo'] -> name }}</span>
            </header>
            <p>{{  $vo['miaoshu'] }}</p>

            @if(!empty($vo['img']))
            <div class="comment-img clearfix">
                @foreach($vo['img'] as $vol)

                    <div>
                        <img style="width:100%;height:100%;" class="lazy" src="{{ asset('images/lazyload.png') }}" data-original="{{asset('images').'/'.$vol}}" onclick="showimg(this)" />
                    </div>
                @endforeach
            </div>
            @endif

            <footer class="comment-foot flex-justify">
                <span>{{ date('Y-m-d H:i',$vo['created_at'] ) }}</span>
                <div>
                    @if(session('openid') == $vo['userinfo'] -> openid)
                    <a style="font-size:10px;" onclick="shanchu({{ $vo['id'] }})">删除 </a>
                        @if($vo['open_status'] == '1')
                            <a style="font-size:10px;" onclick="open_data({{ $vo['id'] }})">开启</a>
                            @else
                            <a style="font-size:10px;" onclick="close_data({{ $vo['id'] }})">关闭</a>
                        @endif
                    @endif

                    <i class="iconfont icon-good" ></i>
                    <span onclick="dianzan(this,'{{ $vo['id'] }}')">{{ $vo['dianzan'] }}</span>
                    <img src="{{asset('images/comment.png')}}" />
                    <span>{{ $vo['liulan'] }}</span>
                </div>
            </footer>
        </section>
    @endforeach
@endif

<div class="weui-gallery"  id="gallery" >
    <!--
    <span class="weui-gallery__img" id="galleryImg" ></span>
    -->
    <img id="galleryImg" class="weui-gallery__img" />
    <div class="weui-gallery__opr" style="padding-top:6px;width:48px;position:absolute;top:0;right:0;left:auto;bottom:auto;">
        <a href="javascript:" class="weui-gallery__del">
            <img src="{{ asset('images/close.png') }}" />
        </a>
    </div>
</div>

<script>
    $(function () {
        //点击发布
        $('#user-comment').click(function(){
            var index = $('#box-index').val();
            location.href='{{url('/home/marketfabu')}}';
        })
    })

    function dianzan(what,id){
        var url = '{{url('home/newszan')}}';
        $.ajax({
            type: 'POST',
            url: url,
            data: {news_id:id},

            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(data){
                if(data == 'success'){
                    //$('#dianzan_num').text(parseInt($('#dianzan_num').text() + 1));
                    $(what).text(parseInt($(what).text() + 1));
                    layer.msg('点赞+1');
                }else{
                    layer.msg('您已经点过赞了');
                }
            },
            error: function(xhr, type){
                alert('Ajax error!')
            }
        });
    }

    function shanchu(id){
        //询问框
        layer.confirm('您确定删除么', {
            btn: ['确定','取消'] //按钮
        }, function(){

            var url = '{{url('home/deleteMarket')}}';
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

    function close_data(id){
        layer.confirm('您确认关闭吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){

            //alert(id);return false;
            var url = '{{ url('home/close_data') }}';
            $.ajax({
                type: 'POST',
                url: url,
                data: {id:id},

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    if(data == 'success'){
                        layer.msg('已关闭');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);

                    }
                },
                error: function(xhr, type){
                    //alert('Ajax error!')
                }
            });


        }, function(){

            layer.msg('您已取消', {time:200});


        });
    }


    function open_data(id){
        layer.confirm('您确认开启吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){

            //alert(id);return false;
            var url = '{{ url('home/open_data') }}';
            $.ajax({
                type: 'POST',
                url: url,
                data: {id:id},

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    if(data == 'success'){
                        layer.msg('已开启');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);

                    }
                },
                error: function(xhr, type){
                    //alert('Ajax error!')
                }
            });


        }, function(){

            layer.msg('您已取消', {time:200});


        });
    }

</script>
<script>
    $("img.lazy").lazyload({
        effect:'fadeIn' //懒加载淡入
    })
    var hei = $(window).height();
    var win = $(window).width();
    //$('#galleryImg').css('height',hei - 60);
    //$('#galleryImg').css('width',win);
    function showimg(th){
        $('#galleryImg').attr('src',$(th).attr('src'));
        //计算galleryImg的宽高
        var w = $(window).width();

        var img_w = $(th).width();//图片宽度
        var img_h = $(th).height();//图片高度
        if (img_w > w) {//如果图片宽度超出指定最大宽度
            var height = (w * img_h) / img_w; //高度等比缩放
            $('#galleryImg').css( {
                "width" : w,"height" : height
            });//设置缩放后的宽度和高度
        }


        //$('#galleryImg').css('background-image',"url("+$(th).attr('src')+")");
        $('#gallery').show();
    }
    $('.weui-gallery__opr').click(function(){
        $('#gallery').hide();
    })
</script>

</body>
</html>


