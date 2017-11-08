<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
    <script src="{{asset('js/jquery.lazyload.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('css/weui2.css') }}"/>
    <title>物业回复</title>

</head>
<body>
<style>
    body{background:#fff;}
</style>

<header class="public-header">

    <i class="iconfont icon-fanhui" style="display:none;"></i>

    <img src="{{asset('images/logo.png')}}" />
</header>

<article class="community-details">
    @if(!empty($res))
        @foreach($res as $vo)

            <section class="comment-title bigbox-{{ $vo['id'] }}"  >
                <header class="comment-head flex-justify"  onclick="location.href='{{url('home/pinlun').'/'.$vo['id']}}' " >
                    <h3>{{ $vo['title'] }}@if(!empty($vo['label']))<span class="hygiene">{{ $vo['label'] }}</span>@endif @if(!empty($vo['huifu']))<span @if($vo['status'] == 0)class="pending"@else class="resolved"@endif >@if($vo['status'] == 0)待解决@else已解决@endif</span>@endif</h3>
                    <span>{{ $vo['userinfo'] -> name }}</span>
                </header>
                <p onclick="location.href='{{url('home/pinlun').'/'.$vo['id']}}' " >{{ $vo['miaoshu'] }}</p>
                @if(!empty($vo['img']))
                    <div class="comment-img clearfix" >
                        @foreach($vo['img'] as $vol)
                            <div>
                                <img style="width:100%;height:100%;" class="lazy" src="{{ asset('images/lazyload.png') }}" data-original="{{asset('images').'/'.$vol}}" onclick="showimg1(this)" />
                            </div>
                        @endforeach
                    </div>
                @endif
                <footer class="comment-foot flex-justify">
                    <span>{{ date('Y-m-d H:i',$vo['created_at'] ) }}</span>
                    <div>
                        <i class="iconfont icon-good"></i>
                        <span>{{ $vo['dianzan'] }}</span>
                        <img src="{{asset('images/comment.png')}}" />
                        <span>{{ $vo['liulan'] }}</span>
                    </div>
                </footer>
                @if(!empty($vo['huifu']))
                    <div class="property-reply">社区管理员：{{$vo['huifu']}}</div>
                @endif
                @if(!empty($vo['wuyehuifu']))
                    @foreach($vo['wuyehuifu'] as $vol)
                    <div class="property-reply">物业：{{$vol -> content}}</div>
                        @if($vol -> imgs[0])
                            <div class="answer-img flex">
                                @foreach($vol -> imgs as $value)
                                    <div style="background:url('{{ asset('images').'/'.$value }}') no-repeat center;background-size:cover;">

                                    </div>
                                @endforeach

                            </div>
                        @endif
                    @endforeach
                @endif

                <div class="feedback">
                    <form id="myForm-{{ $vo['id'] }}" >
                        <div class="feedback-main">
                            <textarea placeholder="请在此回复反馈" name="content" id="content-{{ $vo['id'] }}"></textarea>
                            <div class="sale-upload clearfix sale-upload-{{ $vo['id'] }}" data="{{ $vo['id'] }}" >
                                <a id="mark-{{ $vo['id'] }}" style="display: none;"></a>
                                <div class="upload-file" id="uploadfile-{{ $vo['id'] }}" ><input type="file" name="file" /></div>
                            </div>
                            <a class="huifu" data="{{ $vo['id'] }}" >回复</a>
                        </div>
                    </form>
                    <div class="feedback-solve"><a class="submitform" data="{{ $vo['id'] }}" >确认已解决</a></div>
                </div>

                <!-- 计数 -->
                <input type="hidden" id="count-{{ $vo['id'] }}" value="0" />
                <input type="hidden" id="imgmark-{{ $vo['id'] }}" />
                <input type="hidden" id="imgsrc-{{ $vo['id'] }}" value="" />
            </section>
        @endforeach
    @endif
</article>
<!-- 计数 -->

<div style="height:100%;width:100%;position:fixed;top:0;left:0;z-index:100;background-color:rgba(0, 0, 0, 0.2);padding-top:50%;display:none;" id="loading">
    <img src="{{ asset('images/timg.gif') }}" style="display:block;width:40px;height:40px;margin:0 auto;"/>
</div>






<div class="weui-gallery"  id="gallery" >
    <span class="weui-gallery__img" id="galleryImg" ></span>
    <div class="weui-gallery__opr">
        <a href="javascript:" class="weui-gallery__del" >
            <i class="weui-icon-delete weui-icon_gallery-delete"></i>
        </a>
    </div>
</div>


</body>
<script>
    //上传的图片显示
    function showimg2(th){
        var data = $(th).attr("data");
        var number = $(th).attr("number");
        //把他复制到del中

        $('.weui-gallery__del').attr('data',data);
        $('.weui-gallery__del').attr('number',number);

        $('#galleryImg').css('background-image',$(th).css("background-image"));

        //$('#galleryImg').src('background-image',"url("+$(th).attr('src')+")");

        $('#gallery').show();
        $('.weui-gallery__opr').show();
    }

    $(function(){


        $('.weui-gallery__img').click(function(){
            $('#gallery').hide();
        });
        $('.weui-gallery__del').click(function(){
            $('input[type=file]').val('');
            var imgid = $(this). attr('data');
            var number = $(this). attr('number');

            //删除这个imgid
            var length = $('.bigbox-'+number+' .superimg').length;

            for(var i=0;i<length;i++){
                //alert($('.superimg').eq(i).attr('data'));

                //删除有这个data的superimg
                if( $('.bigbox-'+number+' .superimg').eq(i).attr('data') == imgid ){
                    $('.bigbox-'+number+' .superimg').eq(i).remove();
                }

                if( $('.bigbox-'+number+' .upload-img').eq(i).attr('data') == imgid ){
                    $('.bigbox-'+number+' .upload-img').eq(i).remove();
                }



            }

            //删除之后 imgsrc 重新赋值
            //将所有的图片路径取出来 赋值给imgsrc
            var length = $('.bigbox-'+number+' .superimg').length;
            var temp = '';
            for(var i=0;i<length;i++){
                temp += $('.bigbox-'+number+' .superimg').eq(i).attr('data') + ',';
            }
            temp = temp.substr(0,temp.length-1);
            $('#imgsrc-'+number).val(temp);

            var count = $('#count-'+number).val();

            $('#count-'+number).val(parseInt(count)-1);

            if(count < 2){
                $('#uploadfile-'+number).show();
            }

            //最后隐藏
            $('#gallery').hide();

        })


        //提交回复
        $('.huifu').click(function(){
            var number = $(this).attr('data');
            var content = $('#content-'+number).val();
            var img = $('#imgsrc-'+number).val();
            if(!content){
                layer.alert('请填写内容');return false;
            }
            var url = '{{ url('home/wuye/wuyehuifu') }}';
            //将图片和内容上传
            $.ajax({

                type: 'POST',
                url: url,
                data: {id:number,content:content,img:img},
                //dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                },
                success: function(data){
                    layer.msg('回复成功');
                    location.reload();

                },
                error: function(xhr, type){
                    //alert('Ajax error!')
                }
            });

        })



        $(".upload-file").change(function(){
            $('#loading').show();
            var number = $(this).parent('.clearfix').attr('data');
            var i = "myForm-" + number;

            var formData = new FormData(document.getElementById(i));
            //console.info(formData);
            var url = '{{url('home/saveImg')}}';
            if(!$('input[name=file]').val()){
                $('#loading').hide();
                return false;
            }
            //alert(url);
            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                //dataType:'json',
                // 告诉jQuery不要去处理发送的数据
                processData : false,
                // 告诉jQuery不要去设置Content-Type请求头
                contentType : false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                },
                success: function(data){
                    var img = '{{asset('images')}}'+'/'+data.img;
                    //alert(img);
                    var temp = 'sale-upload-'+number;
                    var length = $("."+temp+">div").length;
                    var html = '<div class="upload-img" style="background:url'+"('" +img+ "')" + ' no-repeat center;background-size:cover;"  data="'+data.img+'"  number="'+number+'" onclick="showimg2(this)"></div>';


                    //储存图片路径
                    var imgsave = '<input type="hidden" class="superimg" data="'+data.img+'" />';
                    $('#imgmark-'+number).after(imgsave);

                    //将所有的图片路径取出来 赋值给imgsrc
                    var length = $('.bigbox-'+number+' '+'.superimg').length;
                    var temp = '';
                    for(var i=0;i<length;i++){
                        temp += $('.bigbox-'+number+' '+'.superimg').eq(i).attr('data') + ',';
                    }
                    temp = temp.substr(0,temp.length-1);
                    $('#imgsrc-'+number).val(temp);


                    $('#mark-'+number).before(html);

                    var count = $('#count-'+number).val();
                    $('#count-'+number).val(parseInt(count)+1);

                    if(count == 2){
                        $('#uploadfile-'+number).hide();
                    }
                    $('#loading').hide();






                    //存放路径
                    /*
                    $('#imgsrc-'+number).val(data.imgsrc);
                    $('#mark-'+number).before(html);
                    var count = $('#count-'+number).val();
                    $('#count-'+number).val(parseInt(count)+1);

                    if(count == 2){
                        $('#uploadfile-'+number).hide();
                    }
                    */


                },
                error: function(xhr, type){
                    layer.msg('Ajax error!')
                }
            });
        });


        //确认已解决
        $('.submitform').click(function(){
            var id = $(this).attr('data');
            layer.confirm('确认已解决后，该条信息将从这里移<br>除，如未解决可能会让业主不满，<br>您要确认吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){

                //alert(id);return false;
                var url = '{{ url('home/wuye/jiejue') }}';
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
                            location.reload();
                        }
                    },
                    error: function(xhr, type){
                        //alert('Ajax error!')
                    }
                });


            }, function(){

                layer.msg('您已取消', {time:200});


            });
        })
    })

</script>

<script>
    $(function(){
        $('#top-nav a').click(function(){
            var index = $(this).index();
            $('#top-nav a').removeClass('hover');
            $(this).addClass('hover');
            $('.customer-main').hide();
            $('#box-'+index).show();
        })

        $('#fabufuwu').click(function(){
            $('#box-1').hide();
            $('#box-2').show();
            $('.icon-fanhui').show();
        })

        $('#box-2 #selectxiaoqu').click(function(){
            $('#box-2').hide();
            $('#box-3').show();
        })

        //保存选中的服务
        $('#box-3 #save').click(function(){
            $('#xiaoqu-box').empty();
            $("input[name=xiaoqus]:checked").each(function(){
                $('#xiaoqu-box').append('<span>'+$(this).attr('data')+'</span>');
            });
            $('#box-2').show();
            $('#box-3').hide();
            $('#xiaoqu-bbox').show();
        })
    })
</script>
<script>
    $("img.lazy").lazyload({
        effect:'fadeIn' //懒加载淡入
    })
    var hei = $(window).height();
    var win = $(window).width();
    $('#galleryImg').css('height',hei - 60);
    $('#galleryImg').css('width',win);
    //原来的图片显示
    function showimg1(th){
        //alert($(th).attr('src'));
        $('#galleryImg').css('background-image',"url("+$(th).attr('src')+")");

        //$('#galleryImg').css('background-image',"url("+$(th).attr('src')+")");
        $('#gallery').show();
        $('.weui-gallery__opr').hide();
    }

</script>

</html>


