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
@if(session('helpres'))
<div class="property-evaluate" style="position:fixed;bottom:0;left:0;width:100%;">

    <div class="flex-align pingjia">
        <div class="selectbox select" data="yes" onclick="needhelp()" style="width:50%;margin-left: 1.3rem;"><i class="iconfont icon-xiaolian"></i>同意帮助</div>
        <div class="selectbox" data="no" onclick="nohelp()" style="width:50%;margin-left:0;" ><i class="iconfont icon-bumanyi"></i>拒绝帮助</div>
    </div>
</div>
@endif
<div class="fixed-height"></div>
<script>
    function nohlep(){
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
    }
    function needhelp(){
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
    }

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


