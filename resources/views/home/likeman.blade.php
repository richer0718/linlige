<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
</head>
<body>
<header class="public-header">

    <i class="iconfont icon-fanhui" onclick="history.go(-1) " @if(session('helpres')) style="display:none;" @endif ></i>

    <img src="{{asset('images/logo.png')}}">
</header>

@if(session('helpres'))
<img src="{{asset('images/user-comment.png')}}" class="user-comment" id="user-comment"/>
@endif

<article class="user-center">

    <div class="user-main">
        <div class="user-photo">
            <div style="background:url('{{ $res -> img }}') no-repeat center;background-size:cover;"></div>
        </div>
        <h3><span>{{ $res -> name }}</span>{{ $res -> shenfen }}</h3>
        <p>{{ $res -> xiaoqu -> title }}</p>
        <img @if(!empty($islook))src="{{ asset('images/enjoy.png') }}" data="yes" @else src="{{ asset('images/noenjoy.jpg') }}" data="no" @endif  id="enjoy" />
    </div>
</article>
<article class="integral-info">
    <a href="javascript:;">
        <div class="link-list flex-justify" onclick="location.href='{{ url('home/mylinli').'/'.$res -> openid }}' " >
            <div ><img src="{{ asset('images/release.png') }}" /><span>TA的发布</span></div>
            <i class="icon iconfont icon-icon"></i>
        </div>
    </a>
    <a href="javascript:;">
        <div class="link-list flex-justify" onclick="location.href='{{ url('home/likelinju').'/'.$res -> openid }}' " >
            <div><img src="{{ asset('images/neighbor.png') }}" /><span>他喜欢的邻居</span></div>
            <i class="icon iconfont icon-icon"></i>
        </div>
    </a>
    <a href="javascript:;">
        <div class="link-list flex-justify">
            <div><img src="{{ asset('images/cooperation.png') }}" /><span>友邻互助评价</span></div>
            <i class="icon iconfont icon-icon"></i>
        </div>
    </a>
</article>
<div class="fixed-height"></div>
<script>
    $(function(){
        $('#enjoy').click(function(){
            var url = '{{url('home/likelinjuchange')}}';
            var data = $(this).attr('data');
            var openid = "{{ $res -> openid }}";
            if(data == 'yes'){
                //变成不喜欢
                $(this).attr('data','no');
                $(this).attr('src','{{ asset('images/noenjoy.jpg') }}')
            }else{
                $(this).attr('data','yes');
                $(this).attr('src','{{ asset('images/enjoy.png') }}')
            }

            $.ajax({
                type: 'POST',
                url: url,
                data: {openid_like:openid},

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(res){

                },
                error: function(xhr, type){
                    //alert('Ajax error!')
                }
            });
        })
    })

    @if(session('helpres'))
        layer.alert('ta愿意帮你，请确认');
    @endif

    $('#user-comment').click(function(){
        layer.confirm('确定让ta帮你么', {
            btn: ['确定','拒绝'] //按钮
        }, function(){
            //确定让他帮你
            var url = '{{url('home/querenhelp')}}';
            var id = {{session('helpres')}} ;

            $.ajax({
                type: 'POST',
                url: url,
                data: {id:id,res:'yes'},

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    layer.msg('确认成功');
                    setTimeout(function(){
                        location.href='{{ url('home') }}';
                    },1000);
                },
                error: function(xhr, type){
                    //alert('Ajax error!')
                }
            });


        }, function(){
//确定让他帮你
            var url = '{{url('home/querenhelp')}}';
            var id = {{session('helpres')}} ;

            $.ajax({
                type: 'POST',
                url: url,
                data: {id:id,res:'no'},

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    layer.msg('您已拒绝');
                    setTimeout(function(){
                        location.href='{{ url('home') }}';
                    },1000);
                },
                error: function(xhr, type){
                    //alert('Ajax error!')
                }
            });



        });
    })


</script>
</body>
</html>


