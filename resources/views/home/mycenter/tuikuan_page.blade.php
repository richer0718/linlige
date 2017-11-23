<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
    <link rel="stylesheet" href="{{ asset('css/weui2.css') }}"/>
</head>
<body>
<header class="public-header">
    <i class="iconfont icon-fanhui" onclick="history.go(-1)" style="top:37%;"></i>
    <img src="{{asset('images/logo.png')}}">
</header>
<article class="sale">
    <form id="myForm">
        <div class="return-goods" style="display:none;">
            <span>退货金额</span><input type="tel" class="flex-1" placeholder="请输入退款金额" price />
        </div>
        <div class="sale-text return-goods-text">
            <h3>退货说明</h3>
            <textarea placeholder="请输入退货说明" id="content"></textarea>
            <div class="sale-upload clearfix">
                <a id="mark"></a>
                <div class="upload-file" id="uploadfile" ><input type="file" name="file" /></div>
            </div>
        </div>
        <a class="publish">提交申请</a>

        <input type="hidden" id="imgsrc" name="imgsrc" value="" />
        <input type="hidden" id="imgmark" />

        <!-- 计数 -->
        <input type="hidden" id="count" value="0" />
    </form>
</article>

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


<script>
    function showimg(th){
        var data = $(th).attr("data");
        //把他复制到del中
        $('.weui-gallery__del').attr('data',data);
        $('#galleryImg').css('background-image',$(th).css("background-image"));
        $('#gallery').show();
    }

    $(function(){
        $('.weui-gallery__img').click(function(){
            $('#gallery').hide();
        });
        $('.weui-gallery__del').click(function(){
            $('input[type=file]').val('');
            var imgid = $(this). attr('data');
            //删除这个imgid
            var length = $('.superimg').length;
            for(var i=0;i<length;i++){
                //alert($('.superimg').eq(i).attr('data'));
                //删除有这个data的superimg
                if( $('.superimg').eq(i).attr('data') == imgid ){
                    $('.superimg').eq(i).remove();
                }

                if( $('.upload-img').eq(i).attr('data') == imgid ){
                    $('.upload-img').eq(i).remove();
                }



            }

            //删除之后 imgsrc 重新赋值
            //将所有的图片路径取出来 赋值给imgsrc
            var length = $('.superimg').length;
            var temp = '';
            for(var i=0;i<length;i++){
                temp += $('.superimg').eq(i).attr('data') + ',';
            }
            temp = temp.substr(0,temp.length-1);
            $('#imgsrc').val(temp);

            var count = $('#count').val();
            $('#count').val(parseInt(count)-1);
            var count = $('#count').val();
            if(count <= 2){
                $('#uploadfile').show();
            }

            //最后隐藏
            $('#gallery').hide();

        })


        //发表
        $('.publish').click(function(){
            var orderid = '{{ $orderid }}';
            var content = $('#content').val();
            var img = $('#imgsrc').val();

            var url = '{{ url('home/myorder/tuikuanRes') }}';
            $.ajax({
                type: 'POST',
                url: url,
                data: {content:content,img:img,orderid:orderid},
                //dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    if(data == 'success'){
                        layer.msg('发送成功');
                        setInterval ("tiaozhuanorder()", 1000);
                    }

                },
                error: function(xhr, type){
                    //alert('Ajax error!')
                }
            });
        })

        $(".upload-file").change(function(){
            $('#loading').show();
            var formData = new FormData(document.getElementById("myForm"));
            var url = '{{url('home/saveImg')}}';
            if(!$('input[name=file]').val()){
                $('#loading').hide();
                return false;
            }
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
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    var img = '{{asset('images')}}'+'/'+data.img;
                    //alert(img);
                    var length = $(".sale-upload>div").length;
                    var html = '<div class="upload-img" style="background:url'+"('" +img+ "')" + ' no-repeat center;background-size:cover;"  data="'+data.img+'" onclick="showimg(this)"></div>';
                    //储存图片路径
                    var imgsave = '<input type="hidden" class="superimg" data="'+data.img+'" />';
                    $('#imgmark').after(imgsave);

                    //将所有的图片路径取出来 赋值给imgsrc
                    var length = $('.superimg').length;
                    var temp = '';
                    for(var i=0;i<length;i++){
                        temp += $('.superimg').eq(i).attr('data') + ',';
                    }
                    temp = temp.substr(0,temp.length-1);
                    $('#imgsrc').val(temp);


                    $('#mark').before(html);

                    var count = $('#count').val();
                    $('#count').val(parseInt(count)+1);

                    if(count == 2){
                        $('#uploadfile').hide();
                    }
                    $('#loading').hide();


                },
                error: function(xhr, type){
                    layer.msg('网络出错');
                }
            });
        });




    });

    function tiaozhuanorder(){
        var url ='{{ url('home/myorder') }}';
        //alert(url);return false;
        location.href=url;
    }
</script>
</body>
</html>


