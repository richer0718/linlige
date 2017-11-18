<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
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

    <title>首页</title>

</head>
<body>


    <header class="public-header keys-search" id="header-search" style="display:none;" >

        <div>
            <i class="iconfont icon-search-copy"></i>
            <input type="text" placeholder="请输入服务关键词搜索" id="servicetoptext"/>
        </div>
        <span id="sousuo">搜索</span>
    </header>


    @if($manage_xiaoqu)
    <header class="public-header" id="header-fanhui">

        <span onclick="location.href='{{ url('guanjia/select') }}' "><i class="iconfont icon-dizhi"></i>{{ $manage_xiaoqu -> title }}</span>
    </header>
    @else
    <header class="public-header" id="header-fanhui">

        <img src="{{asset('images/logo.png')}}">
    </header>
    @endif


@include('layouts.common_zhide')

<article class="community-details">

        <div class="flex-justify community-search" id="navigation2" style="display:none;">

            <a class="hover">全部<div><img src="{{asset('images/interaction-up2.png')}}"></div></a>
            <a >公共服务</a>
            <a >家居维修</a>
            <a >家政上门</a>
            <a >家庭生活</a>
        </div>

        <div class="flex-justify community-nav" id="navigation" >
            <a @if($select_id == 0)class="hover"@endif>邻居说@if($select_id == 0)<div><img src="{{asset('images/interaction-up2.png')}}" /></div>@endif</a>
            <a @if($select_id == 1)class="hover"@endif >友邻互助@if($select_id == 1)<div><img src="{{asset('images/interaction-up2.png')}}" /></div>@endif</a>
            <a @if($select_id == 2)class="hover"@endif>社区活动@if($select_id == 2)<div><img src="{{asset('images/interaction-up2.png')}}" /></div>@endif</a>
            <a @if($select_id == 3)class="hover"@endif>共享车位@if($select_id == 3)<div><img src="{{asset('images/interaction-up2.png')}}" /></div>@endif</a>
        </div>




    @section('volist')

    @show


</article>
    @if(!$manage_xiaoqu)
        @if(!isset($usertype) || $usertype != 'visit')

            @if(!$first_look)
            <img src="{{asset('images/user-comment.png')}}" class="user-comment" id="user-comment"/>
            @endif
        @endif
    @endif
<div class="fixed-height"></div>
    @if(!isset($usertype) || $usertype != 'visit' )
@include('layouts.common_foot')
    @endif
<div class="pop-bg none" @if(!empty($ismark))style="display:block;"@endif>
    <div class="welcome-main">
        <div>
            <img src="{{asset('images/welcome.png')}}" />
            <div>
                <p>您是第<span>@if(!empty($ismark) && $userinfo){{$userinfo -> order_number}}@endif</span>位</p>
                <p>入驻本小区哦</p>
            </div>
        </div>
        <img src="{{asset('images/woman.png')}}" />
        <i class="iconfont icon-icon-test" id="closepop"></i>
    </div>
</div><input type="hidden" id="usertype" value="@if($userinfo){{ $userinfo  -> shenfen}}@endif" />
    <div class="pop-bg none" id="sendwuye">
        <div class="send-info">
            <form>
                <input type="text" placeholder="补充说明，无则空置" name="content" id="fankui_content"/>
                <input type="hidden" name="id" id="fankui_news_id" />
                <p>将本条信息转派给物业？</p>
                <div class="flex-justify"><a id="quxiao">取消</a><a id="quedingwuye">确定</a></div>
            </form>
        </div>
    </div>

<script>
    $(function(){
        $('#closepop').click(function(){
            $('.pop-bg').hide();
        })

        //alert(111);
        var index = {{ $select_id }};
        var page = 0;
        $('#box-index').val({{ $select_id }});
        $('#box-0').val(0);
        $('#box-1').val(0);
        $('#box-2').val(0);
        $('#box-3').val(0);
        var url = '{{url('/home/ajax')}}';

        $.ajax({
            type: 'POST',
            url: url,
            data: {index:index,page:page},
            dataType:'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(data){
                if(data.length){
                    var html = getInfo(index,data);

                    $('#content-box .vo-box').eq(index).append(html);

                    $('#box-'+index).val(parseInt(page)+1);
                }else{
                    $('#box-'+index).attr('data',1);
                }

                $("img.yes").lazyload({
                    effect:'fadeIn' //懒加载淡入
                })
                $('img').removeClass('yes');


            },
            error: function(xhr, type){
                //alert('Ajax error!')
            }
        });



    })


    //邻里互动的切换
    $('#navigation a').click(function(){


        var index = $(this).index();
        $('#navigation a').removeClass('hover');
        $('#content-box .vo-box').hide();
        $('#box-index').val(index);
        //alert(index)

        $(this).addClass('hover');
        //获取当前选择的页数
        var page = $('#box-'+index).val();

        var url = '{{url('/home/ajax')}}';
        //alert(url);return false;
        if(page == 0){
            $.ajax({
                type: 'POST',
                url: url,
                data: {index:index,page:page},
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    if(data.length){
                        var html = getInfo(index,data);

                        $('#content-box .vo-box').eq(index).append(html);

                        $('#box-'+index).val(parseInt(page)+1);
                    }else{
                        $('#box-'+index).attr('data',1);
                    }

                    $("img.yes").lazyload({
                        effect:'fadeIn' //懒加载淡入
                    })
                    $('img').removeClass('yes');


                },
                error: function(xhr, type){
                    //alert('Ajax error!')
                }
            });
        }
        $('#content-box .vo-box').eq(index).show();
        //结束滚动的标志
        $('#scroll').val(1);




    })


    //便民服务点击上方搜索
    //$('#servicetoptext').blur(function(){
    $('#sousuo').click(function(){
        var url = '{{url('/home/ajax2')}}';
        //alert(url);return false;
        var keywords = $.trim($('#servicetoptext').val());
        //alert(keywords);return false;
        if(1){
            $('#content-box2').empty();
            $.ajax({
                type: 'POST',
                url: url,
                data: {index:0,keywords:keywords},
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    var html = getInfo2(data);
                    $('#content-box2').append(html);
                },
                error: function(xhr, type){
                    //alert('Ajax error!')
                }
            });
        }




    })

    //便民服务的切换
    $('#navigation2 a').click(function(){


        var index = $(this).index();
        $('#navigation2 a').removeClass('hover');
        $(this).addClass('hover');


        $('#content-box .vo-box').hide();
        $('#box-index2').val(index);


        var url = '{{url('/home/ajax2')}}';
        $('#content-box2').empty();
        //alert(url);return false;

            $.ajax({
                type: 'POST',
                url: url,
                data: {index:index},
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    var html = getInfo2(data);

                    $('#content-box2').append(html);
                    $("img.yes").lazyload({
                        effect:'fadeIn' //懒加载淡入
                    })
                    $('img').removeClass('yes');

                },
                error: function(xhr, type){
                    //alert('Ajax error!')
                }
            });






    })


    document.onscroll = function(){

        if(document.body.scrollTop+document.body.clientHeight>=document.body.scrollHeight){
            //是否滚动的标志
            var scroll = $('#scroll').val();

            if(scroll == 0 ){
                //获取当前选择的页数
                var index = $('#box-index').val();
                var page = $('#box-'+index).val();
                var url = '{{url('/home/ajax')}}';
                //判断有没有数据  有数据就请求
                if(!$('#box-'+index).attr('data')){
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {index:index,page:page},
                        dataType:'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        success: function(data){
                            if(data.length){
                                var html = getInfo(index,data);

                                $('#content-box .vo-box').eq(index).append(html);

                                $('#box-'+index).val(parseInt(page)+1);
                            }else{
                                $('#box-'+index).attr('data',1);
                            }
                            $("img.yes").lazyload({
                                effect:'fadeIn' //懒加载淡入
                            })
                            $('img').removeClass('yes');


                        },
                        error: function(xhr, type){
                            //alert('Ajax error!')
                        }
                    });
                }
            }else{
                $('#scroll').val(0)
            }



        }
    }


    //点击发布
    $('#user-comment').click(function(){
        var index = $('#box-index').val();
        location.href='{{url('/home/fabu')}}'+'/'+index;
    })


    //获取邻里互动
    function getInfo(index,data){
        var html = '';
        var usertype = $('#usertype').val();
        if(index == 0){
            for(var i=0; i<data.length; i++) {
                var img_list = data[i].img;
                var huifu_list = data[i].wuyehuifu;
                //邻里说
                html += '<section class="comment-title" ';
                

                html += ' >';
                html += '<header class="comment-head flex-justify">';
                html += '<h3 ';
				@if($usertype == 'person')
                    html += 'onclick="location.href='+"'{{url('home/pinlun/')}}"+'/'+data[i].id+"'"+'" ';
                @else
                    html += 'onclick="noReg()"';
                @endif
				html += '>'+data[i].title+'<span class="hygiene">'+data[i].label+'</span>';


                if(data[i].huifu){
                    html += '<span class="';
                    if(data[i].status == 1){
                        html  += 'resolved';
                    }else{
                        html += 'pending';
                    }
                    html += '">'+data[i].status_name+'</span>';
                }

                //看有没有物业回复
                if(data[i].wuye_pingjia){
                    //满意
                    html += '<span class="';
                    if(data[i].wuye_pingjia == 'yes'){
                        html  += 'resolved';
                        html += '">满意</span>';
                    }else{
                        html += 'pending';
                        html += '">不满意</span>';
                    }


                }


                html +='</h3>';
                html += '<span>';

                if(usertype  >= 2 && !data[i].huifu){
                    html += '<i class="iconfont write icon-xieriji" style="z-index:100;" onclick="zhuanpai('+data[i].id+')"></i>';
                }


                html += '<a style="color:#999;" href="{{ url('home/likeman')}}'+'/'+data[i]['userinfo'].openid+'"  >'+data[i]['userinfo'].name+'</a></span>';
                html += '</header>';
                html += '<p>'+data[i].miaoshu+'</p>';

                if(img_list){
                    html += '<div class="comment-img clearfix">';
                    for(var j=0;j<img_list.length;j++){
                        html += '<div>';
                        html += '<img style="width:100%;height:100%;" class="lazy yes" src="{{ asset('images/lazyload.png') }}" data-original="'+"{{asset('images/')}}"+'/'+img_list[j]+'" onclick="showimg(this)" />';
                        html += '</div>';
                        //html += '<div style="' + "background:url('{{asset('images/')}}"+'/'+img_list[j]+"') no-repeat center;background-size:cover;" + '"  onclick="showimg(this)" ></div>';
                    }
                    html += '</div>';
                }
                img_list = null;


                html += '<footer class="comment-foot flex-justify">';
                html += '<span>'+data[i].created_at;
                if(data[i].is_manage){
                    html += ' <a onclick="delete_data('+ data[i].id +')">删除</a>';
                    if(data[i].status == 0){
                        html += ' <a onclick="close_data('+ data[i].id +')">关闭</a>';
                    }else{
                        html += ' <a onclick="open_data('+ data[i].id +')">开启</a>';
                    }
                }

                html += '</span>';
                html += '<div><i class="iconfont icon-good"></i> ';
                html += '<span  onclick="dianzan(this,'+data[i].id+')" >'+data[i].dianzan+'</span>';
                html += '<img src="{{asset('images/comment.png')}}" /> <span>'+data[i].liulan+'</span>';
                html += '</div></footer>';

                if(huifu_list){
                    for(var k=0;k<huifu_list.length;k++){
                        html += '<div class="property-reply">物业回复：'+huifu_list[k].content+'</div>';
                    }
                }

                /*
                if(data[i].wuyehuifu){
                    data[i].wuyehuifu.each(function(j){
                        html += '<div class="property-reply">物业回复：'+j.content+'</div>';
                    })
                }
                */


                html += ' </section>';
            }

        }

        //友邻互助
        if(index == 1){
            for(var i=0; i<data.length; i++) {
                var img_list = data[i].img;
                html += '<section class="comment-title"><header class="comment-head flex-justify">';
                html += '<h3 ';

                @if($usertype == 'person')
                    html += ' onclick="location.href='+"'{{url('home/pinlun/')}}"+'/'+data[i].id+"'"+'" ';
                @else
                    html += ' onclick="noReg()"';
                @endif


                html += ' >'+data[i].title+'<strong>';

                if(data[i].price == '0.00'){
                    html += '面议';
                }else{
                    html += '<em>¥ </em>'+data[i].price;
                }

                html += '</strong>';

                html += '<span class="';
                if(data[i].status == 1){
                    html  += 'resolved';
                }else{
                    html += 'pending';
                }
                html += '">'+data[i].status_manage_name+'</span>';


                html += '</h3>';
                html += '<span >';

                html +='<a style="color:#999;" href="{{ url('home/likeman')}}'+'/'+data[i]['userinfo'].openid+'"  >'+data[i]['userinfo'].name+'</a>';
                html += '</span></header>';
                html += '<p>'+data[i].miaoshu+'</p>';


                if(img_list){
                    html += '<div class="comment-img clearfix">';
                    for(var j=0;j<img_list.length;j++){
                        html += '<div>';
                        html += '<img style="width:100%;height:100%;" class="lazy yes" src="{{ asset('images/lazyload.png') }}" data-original="'+"{{asset('images/')}}"+'/'+img_list[j]+'" onclick="showimg(this)" />';
                        html += '</div>';
                        //html += '<div style="' + "background:url('{{asset('images/')}}"+'/'+img_list[j]+"') no-repeat center;background-size:cover;" + '"  onclick="showimg(this)"  ></div>';
                    }
                    html += '</div>';
                }



                html += '<footer class="comment-foot flex-justify">';
                html += '<span>'+data[i].created_at;

                if(data[i].is_manage){
                    html += ' <a onclick="delete_data('+ data[i].id +')">删除</a>';
                    if(data[i].status == 0){
                        html += ' <a onclick="close_data('+ data[i].id +')">关闭</a>';
                    }else{
                        html += ' <a onclick="open_data('+ data[i].id +')">开启</a>';
                    }
                }


                html += '</span>';

                //html += '<span>'+data[i].created_at+'</span>';
                html += '<div><i class="iconfont icon-good"></i> ';
                html += '<span onclick="dianzan(this,'+data[i].id+')" >'+data[i].dianzan+'</span> ';
                html += '<img src="{{asset('images/comment.png')}}" /> <span>'+data[i].liulan+'</span>';
                html += '</div></footer><div class="property-reply flex-justify"><div class="demand-time">';
                html += '<h4>需求时间</h4><p>'+data[i].date+'</p></div>';
                html += '<div class="demand-btn"><a  ';
                if(!data[i].openid_help){
                    html += 'class="hover" onclick="helphim('+data[i].id+')" ';
                }
                //tel:'+data[i]['userinfo'].tel+'
                html += ' >帮TA</a><a href="tel:';
                html += data[i]['userinfo'].tel;
                html += '">联系看看</a></div></div></section>';
            }

        }

        //社区活动
        if(index == 2){
            for(var i=0; i<data.length; i++) {
                var img_list = data[i].img;

                html += '<section class="comment-title" ';

                html +='>';
                html += '<header class="comment-head flex-justify">';
                html += '<h3';
                @if($usertype == 'person')
                    html += ' onclick="location.href='+"'{{url('home/pinlun/')}}"+'/'+data[i].id+"'"+'"';
                @else
                    html += ' onclick="noReg()"';
                @endif
                html += '>'+data[i].title;

                html += '<span class="';
                if(data[i].status == 1){
                    html  += 'resolved';
                }else{
                    html += 'pending';
                }
                html += '">'+data[i].status_manage_name+'</span>';

                html += '</h3>';

                html += '<span>';
                html += '<a style="color:#999;" href="{{ url('home/likeman')}}'+'/'+data[i]['userinfo'].openid+'"  >'+data[i]['userinfo'].name+'</a>';
                html += '</span></header>';
                html += '<p>'+data[i].miaoshu+'</p>';


                if(img_list){
                    html += '<div class="comment-img clearfix">';
                    for(var j=0;j<img_list.length;j++){
                        //html += '<div style="' + "background:url('{{asset('images/')}}"+'/'+img_list[j]+"') no-repeat center;background-size:cover;" + '"  onclick="showimg(this)"  ></div>';
                        html += '<div>';
                        html += '<img style="width:100%;height:100%;" class="lazy yes" src="{{ asset('images/lazyload.png') }}" data-original="'+"{{asset('images/')}}"+'/'+img_list[j]+'" onclick="showimg(this)" />';
                        html += '</div>';
                    }
                    html += '</div>';
                }



                html += '<footer class="comment-foot flex-justify">';
                html += '<span>'+data[i].created_at;

                if(data[i].is_manage){
                    html += ' <a onclick="delete_data('+ data[i].id +')">删除</a>';
                    if(data[i].status == 0){
                        html += ' <a onclick="close_data('+ data[i].id +')">关闭</a>';
                    }else{
                        html += ' <a onclick="open_data('+ data[i].id +')">开启</a>';
                    }
                }

                html += '</span>';



                html += '<div><i class="iconfont icon-good"></i>';
                html += '<span>'+data[i].dianzan+'</span><img src="{{ asset('images/comment.png') }}" /><span>'+data[i].liulan+'</span>';
                html += '</div></footer></section>';

            }
        }

        //
        if(index == 3){
            for(var i=0; i<data.length; i++) {
                var img_list = data[i].img;
                html += '<section class="comment-title" >';
                html += '<header class="comment-head flex-justify">';
                html += '<h3 ';

                @if($usertype == 'person')
                    html += 'onclick="location.href='+"'{{url('home/pinlun/')}}"+'/'+data[i].id+"'"+'"';
                @else
                    html += 'onclick="noReg()"';
                @endif

                html +='>'+data[i].title+'<strong><em>¥ </em>'+data[i].price+'</strong><a href="tel:'+ data[i]['userinfo'].tel +'"><i class="iconfont icon-dianhua" ></i></a>';

                html += '<span class="';
                if(data[i].status == 1){
                    html  += 'resolved';
                }else{
                    html += 'pending';
                }

                html += '">'+data[i].status_manage_name+'</span>';

                html += '</h3>';
                html += '<span>';
                html += '<a style="color:#999;" href="{{ url('home/likeman')}}'+'/'+data[i]['userinfo'].openid+'"  >'+data[i]['userinfo'].name+'</a>';
                html += '</span></header>';
                html += '<div class="comment-time"><span>时间</span> '+data[i].date+' 至 '+data[i].date_right+' </div>';
                html += '<p>'+data[i].miaoshu+'</p>';
                //html += '<div class="comment-img clearfix">';

                if(img_list){
                    html += '<div class="comment-img clearfix">';
                    for(var j=0;j<img_list.length;j++){
                        html += '<div>';
                        html += '<img style="width:100%;height:100%;" class="lazy yes" src="{{ asset('images/lazyload.png') }}" data-original="'+"{{asset('images/')}}"+'/'+img_list[j]+'" onclick="showimg(this)" />';
                        html += '</div>';
                        //html += '<div style="' + "background:url('{{asset('images/')}}"+'/'+img_list[j]+"') no-repeat center;background-size:cover;" + '"  onclick="showimg(this)"  ></div>';
                    }
                    html += '</div>';
                }
                html += '</div><footer class="comment-foot flex-justify">';
                html += '<span>'+data[i].created_at;

                if(data[i].is_manage){
                    html += ' <a onclick="delete_data('+ data[i].id +')">删除</a>';
                    if(data[i].status == 0){
                        html += ' <a onclick="close_data('+ data[i].id +')">关闭</a>';
                    }else{
                        html += ' <a onclick="open_data('+ data[i].id +')">开启</a>';
                    }
                }

                html += '</span>';
                //html += '<span>'+data[i].created_at+'</span>';

                html += '</footer></section>';

            }
        }
        return html;
    }

    //便民服务
    function getInfo2(data){
        var html = '';
        for(var i=0; i<data.length; i++) {
            html += '<div class="flex-justify"><div class="concact-main">';
            html += '<h3>'+data[i].title+'</h3>';
            html += '<div class="concact-icon flex-align"><i class="iconfont icon-good"></i>';
            html += '<span onclick="dianzan2(this,'+data[i].id+')"  >'+data[i].dianzan+'</span><i class="iconfont icon-dianhua1"></i><span>'+data[i].boda+'</span></div></div><a href="tel:'+ data[i].tel +'" onclick="tel('+data[i].id+')">马上联系</a></div>';


        }

        return html;
    }



</script>
    <script>


        function tel(id){
            layer.msg('正在呼叫');
            //service 呼叫次数加一
            var url = '{{url('home/calltime')}}';
            $.ajax({
                type: 'POST',
                url: url,
                data: {id:id},

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    location.reload();
                },
                error: function(xhr, type){
                    //alert('Ajax error!')
                }
            });
        }
        function helphim(id){
            layer.confirm('确定帮他（她）么', {
                btn: ['确定','取消'] //按钮
            }, function(){
                //确定帮他
                var url = '{{url('home/helphim')}}';
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {id:id},

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success: function(data){
                        if(data == 'success'){
                            layer.msg('帮助成功');
                            setTimeout(function(){location.reload()},1000);
                        }else{
                            layer.msg('请找他人帮忙');
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
                        $(what).text(parseInt($(what).text()) + 1);
                        layer.msg('点赞+1');
                    }else{
                        layer.msg('您已经点过赞了');
                    }
                },
                error: function(xhr, type){
                    //alert('Ajax error!')
                }
            });
        }

        function dianzan2(what,id){
            var url = '{{url('home/newszant')}}';
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
                        $(what).text(parseInt($(what).text()) + 1);
                        layer.msg('点赞+1');
                    }else{
                        layer.msg('您已经点过赞了');
                    }
                },
                error: function(xhr, type){
                    //alert('Ajax error!')
                }
            });
        }

        function zhuanpai(id){
            //alert(id);
            $('#fankui_news_id').val(id);
            $('#sendwuye').show();

        }

        $('#quxiao').click(function(){
            $('#sendwuye').hide();
        })
        //确定提交给物业
        $('#quedingwuye').click(function(){
            var id = $('#fankui_news_id').val();
            var content = $('#fankui_content').val();
            var url = '{{url('/home/fankuiRes')}}';
            $.ajax({
                type: 'POST',
                url: url,
                data: {id:id,content:content},
                //dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    if(data == 'success'){
                        layer.msg('转交成功');
                        $('#sendwuye').hide();
                        setTimeout(function(){location.reload()},1000);
                    }

                },
                error: function(xhr, type){
                    //alert('Ajax error!')
                }
            });
        })

        function noReg(){
            @if(!$manage_xiaoqu)
            layer.msg('您的身份还未审核通过，<br>请耐心等待下');
            @endif
        }
    </script>
	
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
        var hei = $(window).height();
        var win = $(window).width();
        //$('#galleryImg').css('height',hei - 60);
        //$('#galleryImg').css('width',win);
		function showimg(th){
            $('#galleryImg').attr('src',$(th).attr('src'));
            //计算galleryImg的宽高
            var w = $(window).width();
            var hei = $(window).height();
            var img_w = $(th).width();//图片宽度
            var img_h = $(th).height();//图片高度
            console.log(img_w);
            console.log(img_h);
            var top = (hei - img_h)/2;
            $('#galleryImg').css( {
                "margin" : "auto",
            });
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
    <script>
        function delete_data(id){
            layer.confirm('您确认删除吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){

                //alert(id);return false;
                var url = '{{ url('home/delete_data') }}';
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {id:id},

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success: function(data){
                        if(data == 'success'){
                            layer.msg('已删除');
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
    <script src="{{asset('js/jquery.lazyload.min.js')}}"></script>
</body>
</html>


