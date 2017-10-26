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
    <span   style="position:absolute;right:5px;top:10px;" id="shenqing">申请</span>
</header>



<form id="myForm"  style="height:100%;">
    <article class="register-data" style="height:100%;">

        <input type="text" name="username" class="phone" id="username" placeholder="请输入用户名"/>
        <input type="password" name="password" class="verification-code" placeholder="请输入密码" id="password"/>

        <a  class="submit">登录</a>
    </article>
</form>

<script>

    $(".submit").click(function(){
        var username = $("#username").val();
        var password = $("#password").val();

        if(username == ''){
            layer.msg('请输入用户名');
            return false;
        }
        if(password == ''){
            layer.msg('请输入密码');
            return false;
        }



        var url = '{{ url('home/businessLoginRes') }}';
        var data = $('#myForm').serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,

            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(data){
                if(data == 'success'){
                    location.href='{{ url('home/business/index') }}';
                }else{
                    layer.msg('登陆失败');
                }
            },
            error: function(xhr, type){
                layer.msg('登陆失败');
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
                    //发送模版消息
                    layer.msg('发送模版消息:姓名：'+json.name+' '+json.tel);
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


