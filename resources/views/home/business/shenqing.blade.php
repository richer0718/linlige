<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
    <title>商户登陆</title>

</head>
<body>
<style>
    body{background:#fff;}
</style>
<div class="register-pop none">
    <i class="iconfont icon-cancel"></i>
    <div>
        <img src="{{asset('images/popularity.png')}}" />
        <a href="">看看人气小区</a>
    </div>
</div>

<header class="public-header">


    <img src="{{asset('images/logo.png')}}" />
</header>



<form id="myForm"  style="height:100%;">
    <article class="register-data" style="height:100%;">

        <input type="text" name="username" class="name" id="name" placeholder="请输入姓名"/>
        <input type="text" name="username" class="phone" id="tel" placeholder="请输入手机号"/>


        <a  class="submit">登录</a>
    </article>
</form>

<script>

    $(".submit").click(function(){
        var name = $.trim($("#name").val());
        var tel = $.trim($("#tel").val());

        if(name == ''){
            layer.msg('请输入姓名');
            return false;
        }
        if(tel == ''){
            layer.msg('请输入手机号');
            return false;
        }
        var reg = /^1[3578]\d{9}$/;
        if (!reg.test(tel)) {
            layer.msg('请输入正确手机号');
            return false;
        }



        var url = '{{ url('home/shenqingBusinessRes') }}';

        $.ajax({
            type: 'POST',
            url: url,
            data: {name:name,tel:tel},

            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(data){

                if(data == 'success'){
                    layer.msg('申请成功');
                    //location.href='{{ url('home/business/index') }}';
                }else if(data == 'isset'){
                    layer.msg('您已经申请过了');
                }else{
                    layer.msg('申请失败');
                }
            },
            error: function(xhr, type){
                layer.msg('申请失败');
            }
        });



    });

    $('#find').click(function(){
        $('.icon-fanhui').show();
        $('#myForm').hide();
        $('#search-box').show();
    })

    $('.icon-fanhui').click(function(){
        $('.icon-fanhui').hide();
        $('#myForm').show();
        $('#search-box').hide();
    })

    $('.xiaoqu_name').click(function(){
        var text = $(this).text();
        $('input[name=xiaoqu]').val(text);
        $('.icon-fanhui').hide();
        $('#myForm').show();
        $('#search-box').hide();
    })

    $('#shenqing').click(function(){
        var url = '{{ url('home/shenqingBusiness') }}';
        $.ajax({
            type: 'GET',
            url: url,
            //data: data,

            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(data){
                if(data == 'error'){
                    layer.msg('您没有权限');
                }else if(data == 'showpage'){
                    //跳转
                    location.href='{{ url('home/shenqingPage') }}';
                }else{
                    //业主
                    //data 是json
                    var json = JSON.parse(data);
                    console.info(json);
                }

            },
            error: function(xhr, type){
                layer.msg('登陆失败');
            }
        });
    })



</script>
</body>
</html>