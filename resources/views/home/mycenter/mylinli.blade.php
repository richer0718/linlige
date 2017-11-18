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
        .demand-time h4 span {
            color: #4ee4c2;
        }
        .neighbor p {
            margin-top: 0.23rem;
            color: #333;
            font-size: 0.28rem;
        }
    </style>
</head>
<body>
<header class="public-header">
    <i class="iconfont icon-fanhui" onclick="history.go(-1)"></i>
    <img src="{{asset('images/logo.png')}}">
</header>
<article class="vote vote2">
    <header class="flex-justify" id="navigation">
        <a href="javascript:;" class="hover"><span>邻居说</span></a>
        <a href="javascript:;"><span>友邻互助</span></a>
        <a href="javascript:;"><span>社区活动</span></a>
        <a href="javascript:;"><span>共享车位</span></a>
        <a href="javascript:;"><span>跳蚤市场</span></a>
    </header>
</article>
<article class="community-details">
    <div class="release">
        <img src="{{asset('images/interaction-up2.png')}}" style="left:8%;" id="leftimg"/>
        <a  class="hover">发布</a>
        <a >回复</a>
    </div>
    <!-- 内容 -->
    <div id="content-box">

    </div>


    <!-- 邻里说 友邻互助 社区活动 的切换 -->
    <input type="hidden" name="index" id="index" />
    <!-- 发布回复的切换 -->
    <input type="hidden" name="fabuindex" id="fabuindex" value="1"/>
    <input type="hidden" name="openid" id="openid" value="{{ $openid }}" />
</article>


<!-- 评价物业开始 -->
<!--
<footer class="property-tips">已转派给物业，请注意跟进</footer>
-->
<!-- 评价物业结束 -->

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
<input type="hidden" id="usertype" value="@if($userinfo){{ $userinfo  -> shenfen}}@endif" />

<div class="pop-bg none" id="sendwuye">
    <div class="send-info">
        <form>
            <input type="text" placeholder="请填写评价" name="content" id="pingjia"/>
            <input type="hidden" name="id" id="news_id" />

            <div class="flex-justify"><a id="quxiao">取消</a><a id="quedingwuye">确定</a></div>
        </form>
    </div>
</div>

</body>
<script>
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

<script>
    //发布评价给物业
    function fabuwuyu(){
        //评价结果
        var result = $('.pingjia .select').attr('data');
        var url = '{{url('/home/fabuwuye')}}';
        $.ajax({
            type: 'POST',
            url: url,
            data: {result:result},
            //dataType:'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(data){
                if(data == 'success'){
                    layer.msg('发布成功');
                    location.reload();
                }else{
                    layer.msg('发布失败');
                }
            },
            error: function(xhr, type){
                //alert('Ajax error!')
            }
        });
    }
    $(function(){
        var index = 0;
        $('#index').val(index);
        var url = '{{url('/home/mylinliajax')}}';
        $('#content-box').empty();
        var fabuindex = $('#fabuindex').val();
        var openid = $('#openid').val();
        $.ajax({
            type: 'POST',
            url: url,
            data: {index:index,fabuindex:fabuindex,openid:openid},
            dataType:'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(data){
                if(data.length){
                    var html = getInfo(index,data);

                    $('#content-box').append(html);
                }else{
                    $('#box-'+index).attr('data',1);
                }
                $("img.yes").lazyload({
                    effect:'fadeIn' //懒加载淡入
                })
                $('img').removeClass('yes');


            },
            error: function(xhr, type){
                alert('Ajax error!')
            }
        });


        //评价点击切换颜色
        $('.pingjia .selectbox').click(function(){
            $('.pingjia .selectbox').css('color','#000');
            $('.pingjia .selectbox i').css('color','#000');
            $(this).css('color','#f4c600');
            $(this).children('i').css('color','#f4c600');
            $('.pingjia .selectbox').removeClass('select');
            $(this).addClass('select');

        })

        //评价点击切换颜色


        //切换
        $('#navigation a').click(function(){
            var fabuindex = $('#fabuindex').val();
            var openid = $('#openid').val();
            var index = $(this).index();
            if(index == 0){
                $('.property-evaluate').show();
                $('.property-release').show();
            }else{
                $('.property-evaluate').hide();
                $('.property-release').hide();
            }
            $('#index').val(index);

            var left = 8 + index*20;
            $('#leftimg').css('left',left+'%');
            $('#navigation a').removeClass('hover');
            $(this).addClass('hover');
            var url = '{{url('/home/mylinliajax')}}';
            $('#content-box').empty();
            //切换时复位发布回复
            $('.release a').removeClass('hover');
            $('.release a').eq(0).addClass('hover');
            $('#fabuindex').val(1);
            $.ajax({
                type: 'POST',
                url: url,
                data: {index:index,fabuindex:fabuindex,openid:openid},
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    if(data.length){
                        var html = getInfo(index,data);
                        $('#content-box').append(html);
                        $("img.yes").lazyload({
                            effect:'fadeIn' //懒加载淡入
                        })
                        $('img').removeClass('yes');
                    }
                },
                error: function(xhr, type){
                    alert('Ajax error!')
                }
            });

        });

        //发布 | 回复的切换
        $('.release a').click(function(){
            var openid = $('#openid').val();
            var fabu = $('#fabuindex').val();
            var fabuindex = 0;
            if(fabu == 1){
                fabuindex = 2;
                $('#fabuindex').val(2);
                $('.property-evaluate').hide();
                $('.property-release').hide();
            }else{
                fabuindex = 1;
                $('#fabuindex').val(1);

                $('.property-evaluate').show();
                $('.property-release').show();
            }


            var index = $('#index').val();
            $('.release a').removeClass('hover');
            $(this).addClass('hover');
            var url = '{{url('/home/mylinliajax')}}';
            $('#content-box').empty();
            $.ajax({
                type: 'POST',
                url: url,
                data: {index:index,fabuindex:fabuindex,openid:openid},
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    if(data.length){
                        var html = getInfo(index,data);
                        $('#content-box').append(html);
                        $("img.yes").lazyload({
                            effect:'fadeIn' //懒加载淡入
                        })
                        $('img').removeClass('yes');
                    }
                },
                error: function(xhr, type){
                    alert('Ajax error!')
                }
            });

        });

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
                html += '<span>'+data[i].created_at+'</span>';
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


                if(!data[i].wuye_pingjia){
                    //物业评价
                    html += '<div class="property-evaluate" style="margin:0 auto;" >';
                    html += '<h3>请评价物业服务：</h3>';
                    html += '<div class="flex-align pingjia">';
                    html += '<div class="selectbox select" data="yes" onclick="manyi()"><i class="iconfont icon-xiaolian"></i>满意</div>';
                    html += '<div class="selectbox" data="no" onclick="manyi()" ><i class="iconfont icon-bumanyi"></i>不满意</div>';
                    html += '</div></div>';
                    html += '<a onclick="fabuwuyu_manyi('+data[i].id+')" class="property-release">评价</a>';
                }


            }

        }

        //友邻互助
        if(index == 1){
            var myopenid = "{{ session('openid') }}";
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
                html += '">'+data[i].status_name+'</span>';


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
                html += '<span  >'+data[i].dianzan+'</span> ';
                html += '</div></footer><div class="property-reply flex-justify">';



                //看下这个有没有人帮助
                if(data[i].openid_help){
                    //有人帮助
                    //判断此人是谁
                    if(data[i].is_manage ||  data[i].openid_help == myopenid){
                        //是被帮助的这个人 或者帮助的这个人
                        if(data[i].is_manage){
                            //被帮助的这个人
                            //查看是否评价
                            if(data[i].help_pingjia){
                                //直接显示评价
                                html += '<div class="demand-time neighbor">';
                                html+= '<p>'+data[i].help_pingjia+'</p>';
                                html += '</div>';
                                html += '<div class="demand-btn demand-btn-no">';
                                html += '<a  >已完成</a>';
                                html += '</div>';

                            }else{
                                //没有评价过
                                //判断是否完成
                                if(data[i].status == 1){
                                    html += '<div class="demand-btn">';
                                    html += '<a onclick="pingjia('+data[i].id+')" class="hover">评价</a>';
                                    html += '</div>';
                                }else{
                                    html +='<div class="demand-time neighbor">';
                                    html += '<h4>需求时间<sapn> '+data[i].date+'</sapn></h4><p>好邻居：'+data[i]['helpinfo'].name+'<span>(已接任务)</span></p></div>';
                                    html += '<div class="demand-btn"><a href="tel:'+ data[i]['helpinfo'].tel +'" class="hover">联系TA</a></div>';
                                }

                            }
                        }else{
                            //帮助的这个人
//查看是否评价
                            if(data[i].help_pingjia){
                                //直接显示评价
                                html += '<div class="demand-time neighbor">';
                                html+= '<p>'+data[i].help_pingjia+'</p>';
                                html += '</div>';
                                html += '<div class="demand-btn demand-btn-no">';
                                html += '<a  >已完成</a>';
                                html += '</div>';

                            }else{
                                //没有评价过
                                //有没有完成
                                if(data[i].status == 1){
                                    html += '<div class="demand-time neighbor">';
                                    html+= '<p>'+data[i].help_pingjia+'</p>';
                                    html += '</div>';
                                    html += '<div class="demand-btn demand-btn-no">';
                                    html += '<a  >已完成</a>';
                                    html += '</div>';
                                }else{
                                    html += '<div class="demand-time neighbor">';
                                    html+= '<p>请确保任务完成，再点击按钮</p>';
                                    html += '</div>';
                                    html += '<div class="demand-btn demand-btn-no">';
                                    html += '<a  class="hover" onclick="wancheng('+data[i].id+')" style="background:#ffffff;">确认完成</a>';
                                    html += '</div>';
                                }

                            }


                        }


                    }else{
                        //谁也不是 - 什么也不用考虑
                        html +='<div class="demand-time">';
                        html += '<h4>需求时间</h4><p>'+data[i].date+'</p></div>';
                        html += '<div class="demand-btn"><a  ';
                        //html += ' onclick="helphim('+ data[i].id +')" ';
                        html += ' >帮他</a><a>联系看看</a></div>';

                    }


                }else{
                    //没有人帮助 - 什么也不用考虑
                    html +='<div class="demand-time">';
                    html += '<h4>需求时间</h4><p>'+data[i].date+'</p></div>';
                    html += '<div class="demand-btn"><a  ';
                    html += ' onclick="helphim('+ data[i].id +')" ';
                    html += ' class="hover" >帮他</a><a href="tel:'+ data[i]['userinfo'].tel +'" class="hover" >联系看看</a></div>';
                }


                html +='</div></section>';









            }

        }

        //社区活动
        if(index == 2){
            for(var i=0; i<data.length; i++) {
                var img_list = data[i].img;

                html += '<section class="comment-title" ';
                @if($usertype == 'person')
                    html += 'onclick="location.href='+"'{{url('home/pinlun/')}}"+'/'+data[i].id+"'"+'"';
                @else
                    html += 'onclick="noReg()"';
                @endif
                    html +='>';
                html += '<header class="comment-head flex-justify">';
                html += '<h3>'+data[i].title+'</h3>';
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
                html += '<span>'+data[i].dianzan+'</span><img src="images/comment.png" /><span>'+data[i].liulan+'</span>';
                html += '</div></footer></section>';

            }
        }

        //
        if(index == 3){
            for(var i=0; i<data.length; i++) {
                var img_list = data[i].img;
                html += '<section class="comment-title" ';
                @if($usertype == 'person')
                    html += 'onclick="location.href='+"'{{url('home/pinlun/')}}"+'/'+data[i].id+"'"+'"';
                @else
                    html += 'onclick="noReg()"';
                @endif
                    html +='>';
                html += '<header class="comment-head flex-justify">';
                html += '<h3>'+data[i].title+'<strong><em>¥ </em>'+data[i].price+'</strong></h3>';
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

</script>
<script>
    //点击切换满意不满意
    function manyi(){
        alert(111);
        $(this).parent('.pingjia').children('.selectbox').css('color','#000');
        $(this).parent('.pingjia').children('.selectbox').children('i').css('color','#000');
        $(this).css('color','#f4c600');
        $(this).children('i').css('color','#f4c600');

    }

    function wancheng(id){
        layer.confirm('您确认完成吗？', {
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
                        layer.msg('已完成');
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

    function pingjia(id){
        $('#sendwuye').show();
        $('#news_id').val(id);
    }

    $('#quxiao').click(function(){
        $('#sendwuye').hide();
    })


    //确定提交给物业
    $('#quedingwuye').click(function(){


        var id = $('#news_id').val();
        var content = $('#pingjia').val();
        var url = '{{url('/home/helppingjia')}}';
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
                    layer.msg('评价成功');
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
        layer.msg('您的身份还未审核通过，<br>请耐心等待下');
    }



</script>
<script src="{{asset('js/jquery.lazyload.min.js')}}"></script>
</html>


