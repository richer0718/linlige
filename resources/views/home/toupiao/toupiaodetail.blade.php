<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
</head>
<body>
<style>
    body{background:#fff;}
</style>
@include('layouts.common_fanhui')
<article class="questionnaire">

    <div class="questionnaire-head">
        <img src="{{asset('images/questionnaire.png')}}" />
        <div>
            <img src="{{asset('images/questionnaire-title.png')}}" />
            <p style="padding:10px;text-indent: 2em">{!! $toupiaodetail -> qianyan !!}</p>
        </div>
    </div>
    <form>
        <div class="questionnaire-main">
            @foreach($res as $k => $vo)
            <div class="questionnaire-model">
                <h3><span>{{ $k + 1 }}.</span>{{ $vo -> title }}</h3>
                <div class="radio-model">
                    @foreach($vo -> detail as $vol)
                    <div class="flex-align"><label><input @if($vo -> type == 1)type="radio"@else type="checkbox" @endif name="{{ $vo -> id }}" class="inputs" value="{{ $vol -> id }}" ><i class="iconfont"></i>{{ $vol -> name }}</label></div>
                    @endforeach
                </div>
            </div>
            @endforeach
            <div class="questionnaire-model">
                <h3><span>1.</span>这是道填空题，请填写</h3>
                <div class="radio-model" style="width:100%;height:30px;">
                    <input type="text" style="width:100%;height:30px;"/>
                </div>
            </div>
                <div class="questionnaire-model">
                    <h3><span>1.</span>这是道填空题，请填写</h3>
                    <div class="radio-model" style="width:100%;height:30px;">
                        <input type="text" style="width:100%;height:30px;"/>
                    </div>
                </div>
            <p style="padding:10px;text-indent: 2em">{!! $toupiaodetail -> jieshu !!}</p>
            <a  class="radio-vote" id="newtoupiao">投票</a>
        </div>
    </form>
    <img src="{{asset('images/questionnaire-bottom.png')}}" />
</article>
<!-- 提交到签名 -->
<form method="post" action="{{ url('home/toupiaosign').'/id/'.$id }}" id="superform">
    <input type="hidden" name="data" id="formdata"/>
    {{ csrf_field() }}
</form>
<script>
    $(function(){
        $('#toupiao').click(function(){
            //检查是否全部填好
            var length = $('input[type=radio]:checked').length;
            var length_pre = '{{ count($res) }}';
            if(length != length_pre){
                layer.msg('请填写全部');return false;
            }

            var str = '';
            for(var i = 0 ;i< length ; i++){
                //alert($('input[type=radio]:checked').eq(i).val());
                str += $('input[type=radio]:checked').eq(i).val()+',';
            }
            //问题的答案
            str = str.substr(0,str.length-1);
            var url = '{{ url('home/toupiaoRes') }}';
            var id = '{{ $id }}';
            //问卷id
            $.ajax({
                type: 'POST',
                url: url,
                data: {str:str,id:id},

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    if(data == 'success'){
                        layer.msg('投票成功');
                    }else if(data == 'isset'){
                        layer.msg('您已经投票');
                    }
                },
                error: function(xhr, type){
                    alert('Ajax error!')
                }
            });



        })



        $('#newtoupiao').click(function(){
            //先检查题全部填完没有
            /*
            var length = $('.radio-model input:checked').length;
            var length_pre = '{{ count($res) }}';
            alert(length);
            if(length <= length_pre){
                layer.msg('请填写全部');return false;
            }

            alert(11);
            */
            //便利每个题 看下便有没有选中的值
            for(var i=0;i<$('.radio-model').length;i++){
                //检查每个元素下的 input 是否选中
                if(!$('.radio-model:eq'+'('+i+') input:checked').val()){
                    layer.msg('请填写全部');return false;
                }
            }

            //填写全部后，针对多选特殊处理
            var arr = {};
            for(var i=0;i<$('.radio-model').length;i++){
                //如果他下边的第一个input 是 radio 直接赋值
                if($('.radio-model:eq'+'('+i+') input').eq(0).attr('type') == 'radio'){
                    //直接赋值
                    arr[i] = $('.radio-model:eq'+'('+i+') input:checked').val();
                }else{
                    //找出所有checkbox的值
                    var temp = {};
                    for(var j=0;j<$('.radio-model:eq'+'('+i+') input:checked').length;j++){
                        //temp +=  $('.radio-model:eq'+'('+i+') input:checked').eq(j).val()+',';
                        temp[j] = $('.radio-model:eq'+'('+i+') input:checked').eq(j).val();
                    }
                    arr[i] = temp;
                    //arr[i] = temp.substr(0,temp.length-1);

                }
                //$('.radio-model:eq'+'('+i+') input:checked').val();
            }

            //判断下是否需要签名
            @if($toupiaodetail -> is_sign)
                //赋值给form input
                $('#formdata').val(JSON.stringify(arr));
                //提交form
                $('#superform').submit();
                return false;

            @endif


            var id = '{{ $id }}';
            var data = JSON.stringify(arr);
            var url = '{{ url('home/toupiaoRes') }}'+'/id/'+id;
            //location.href='{{ url('home/toupiaosign') }}';
            //问卷id
            $.ajax({
                type: 'POST',
                url: url,
                data: {json:data},

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    if(data == 'success'){
                        layer.msg('投票成功');

                        setTimeout(function () {
                            location.href='{{ url('home') }}';
                        }, 1000);

                    }else if(data == 'isset'){
                        layer.msg('您已经投票');
                        setTimeout(function () {
                            location.href='{{ url('home') }}';
                        }, 1000);
                    }
                },
                error: function(xhr, type){
                    alert('Ajax error!')
                }
            });


        })
    })
</script>
</body>
</html>


