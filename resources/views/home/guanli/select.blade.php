<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
    <title>选择小区</title>
    <style>
        body{background:#fff;}
    </style>
</head>
<body>



<header class="public-header" style="position:relative;">

    <i class="iconfont icon-fanhui" ></i>

    <img src="{{asset('images/logo.png')}}" />

</header>

<article class="search-page none" id="search-box" style="top:43px;">
    <div class="search">
        <div class="search-main">
            <label for="key"><i class="iconfont icon-search-copy"></i></label>
            <input type="text" id="keywords" placeholder="小区名关键词查找" style="width:70%;"/>
        </div>
        <a id="search-button">搜索</a>
    </div>
    <div class="key-list">
        @foreach($xiaoqu as $vo)
            <a class="xiaoqu_name" onclick="searchXiaoquName('{{ $vo -> title }}','{{ $vo->id }}')" data="{{ $vo -> id }}">{{ $vo -> title }}</a>
        @endforeach
    </div>
</article>
<script>
    function searchXiaoquName(text,id){
        $('input[name=xiaoqu]').val(text);
        $('#xiaoquname').val(id);
        $('.icon-fanhui').hide();
        $('#myForm').show();
        $('#search-box').hide();
        //跳转
        location.href='{{ url('guanli/jumpHome') }}'+'/'+id;
    }
    $(function(){
        //ajax 搜索
        $('#search-button').click(function(){
            //获取搜索框的值
            var url = '{{ url('home/regSearch') }}';
            var keywords = $.trim($('#keywords').val());
            $.ajax({
                type: 'POST',
                url: url,
                data: {keywords:keywords},
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    var html = '';
                    if(data.length > 0){
                        for(var i=0; i<data.length; i++) {
                            html += '<a class="xiaoqu_name" onclick="searchXiaoquName('+"'" +data[i].title+"'"+','+data[i].id+')" data="'+data[i].id+'">'+ data[i].title +'</a>';
                        }
                    }else{
                        html += '<p>当前不存在该小区</p>';
                    }
                    $('.key-list').empty();
                    $('.key-list').append(html);


                },
                error: function(xhr, type){
                    layer.msg('Ajax error!')
                }
            });
        })
    })

</script>

</body>
</html>


