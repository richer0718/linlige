<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
    <title>商户登陆</title>

</head>
<body>
<style>
    body{background:#efefef;}
    .service-input>div>span{
        width: 1.5rem;
    }
    .superselect a {
        height: 0.56rem;
        background: url('{{ asset('images/right.png') }}') no-repeat 95% center;
        background-size: 0.13rem;
    }
</style>

<header class="public-header">

    <i class="iconfont icon-fanhui" style="display:none;"></i>

    <img src="{{asset('images/logo.png')}}" />
</header>

<article class="vote">
    <header class="flex-justify" id="top-nav">
        <a  class="hover" ><span>我的客户</span></a>
        <a ><span>我发布的服务</span></a>
    </header>

    <article class="customer-main" id="box-0">
        @if(!empty($mycustomer))
            @foreach($mycustomer as $vo)
                <section class="customer-list">
                    <div>
                        <div class="flex-justify">
                            <h3 class="flex-1">{{ $vo -> userinfo -> name  }}</h3>
                            <a href="tel:{{ $vo -> userinfo -> tel }}"><img src="{{ asset('images/tel.png') }}" ></a>
                        </div>
                        <p>{{ date('Y-m-d H:i',$vo -> created_at) }}</p>
                    </div>
                </section>
            @endforeach
        @endif
    </article>

    <article class="customer-main" id="box-1" style="display:none;">
        @if(!empty($newarr))
            @foreach($newarr as  $k => $vo)
                <div class="service-area">
                    <h3>服务名称<span>{{ $titlearr[$k]['title'] }}</span></h3>
                    <h4>服务小区</h4>
                    <div class="area-main">
                        @foreach($vo as $vol)
                        <div class="flex-justify">
                            <div class="area-time">{{ $vol -> xiaoquinfo -> title }}<span>到期时间：{{ date('Y-m-d H:i',$vol -> created_at+86400*360) }}</span></div>
                            <a>续费</a>
                        </div>
                        @endforeach

                    </div>
                    <p>联系电话：{{ $titlearr[$k]['tel'] }}</p>
                </div>
            @endforeach
        @endif
        <footer class="publish-service">
            <a id="fabufuwu">+ 发布服务</a>
        </footer>
    </article>

    <article class="customer-main" id="box-2" style="display:none;">
        <div class="service-input">
            <div class="flex-align" style="margin-top:0.2rem;">
                <span>服务名称</span>
                <input type="text" placeholder="请输入服务名称" class="flex-1" id="servicename"/>
            </div>
            <div class="flex-align service-select" style="margin-top:0.2rem;">
                <div>选择服务小区</div>
                <a id="selectxiaoqu" class="flex-1"></a>
            </div>

            <div class="service-txt" style="display:none;" id="xiaoqu-bbox">
                <div id="xiaoqu-box">


                </div>
            </div>
            <div class="flex-align superselect" style="position:relative;margin-top:0.2rem;" id="changeselect" >
                <span>服务类别</span>
                <select name="type" style="font-size: 15px;width:100px;" id="type">
                    <option value="0">公共服务</option>
                    <option value="1">家居维修</option>
                    <option value="2">家政上门</option>
                    <option value="3">家庭生活</option>
                </select>
                <a id="rightchangeselect" class="flex-1"></a>
            </div>
            <script>
                $(function(){
                    $('#changeselect').click(function(){
                        $('#type').attr('size',4);
                    })
                    $('#rightchangeselect').click(function(){
                        $('#type').attr('size',4);
                    })
                })
            </script>
            <div class="flex-align" style="margin-top:0.2rem;">
                <span>联系电话</span>
                <input type="text" placeholder="请输入电话" id="tel" class="flex-1"/>
            </div>
            <div class="flex-align" style="margin-top:0.2rem;">
                <span>费用</span>
                <input type="text" placeholder=""  value="180元/小区/年" class="flex-1"/>
            </div>
        </div>
        <footer class="apply flex-align">
            <div class="flex-1">共需支付 <span id="paynumber">180</span> 元</div>
            <a id="pay">去支付</a>
        </footer>
    </article>

    <article class="customer-main" id="box-3" style="display:none;">
        <div>
            @if(!empty($select_res))
                @foreach($select_res as $vo)
                    <div class="area-search-list flex-align"><input type="checkbox"  value="{{ $vo -> id }}" data='{{ $vo -> title }}' ids="{{ $vo -> id }}" class="xiaoqus" name="xiaoqus"/><i class="iconfont"></i><span>{{ $vo -> title }}</span></div>
                @endforeach
            @endif
        </div>
        <a id="save" class="area-preservation">保存</a>
    </article>

</article>
<input type="hidden" id="ids"  />
</body>
<script src="https://res.wx.qq.com/open/js/jweixin-1.1.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    wx.config(<?php echo $js->config(
            array('checkJsApi',
                'openAddress',
                'editAddress',
                'chooseWXPay'), false)
        ?>);

    wx.ready(function(){





    });
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
            //$('.icon-fanhui').show();
        })

        $('#box-2 #selectxiaoqu').click(function(){
            $('#box-2').hide();
            $('#box-3').show();
            //隐藏返回按钮

        })

        //保存选中的服务
        $('#box-3 #save').click(function(){
            $('#xiaoqu-box').empty();
            var ids = '';
            var number = 0;
            $("input[name=xiaoqus]:checked").each(function(){
                $('#xiaoqu-box').append('<span>'+$(this).attr('data')+'</span>');
                ids += $(this).attr('ids')+',';
                number ++;
            });

            $('#ids').val(ids.substr(0,ids.length));
            number = 180 + (number - 1)*180;
            $('#paynumber').text(number);
            $('#box-2').show();
            $('#box-3').hide();

            $('#xiaoqu-bbox').show();
        })

        $('#pay').click(function(){



             var url = '{{ url('home/business/fabuRes') }}';
             var servicename = $.trim($('#servicename').val());
             var ids = $('#ids').val();
             var tel = $('#tel').val();
             var type = $('#type').val();
             var price = $('#paynumber').text();

            //判断必填
            if(!servicename){
                layer.msg('请填写服务名称');return false;
            }
            if(!ids){
                layer.msg('请选择小区');return false;
            }
            if(!tel){
                layer.msg('请填写联系电话');return false;
            }




             $.ajax({
             type: 'POST',
             url: url,
             data: {servicename:servicename,ids:ids,tel:tel,type:type,price:price},

             headers: {
             'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
             },
             success: function(data){
                 wx.chooseWXPay({
                     timestamp: data['timestamp'], // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
                     nonceStr: data['nonceStr'], // 支付签名随机串，不长于 32 位
                     package: data['package'], // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
                     signType: data['signType'], // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
                     paySign: data['paySign'], // 支付签名
                     success: function (res) {
                         if(res.errMsg == 'chooseWXPay:ok'){
                             location.reload();
                         }
                     },
                     complete: function(res) {
                         if(res.errMsg == 'chooseWXPay:ok'){
                             location.reload();
                         }
                     },
                 });
             },
             error: function(xhr, type){
             //alert('Ajax error!')
             }
             });


        })

    })

    function zhifuchenggong(){
        //支付成功跳转
        location.reload();
    }
</script>
</html>


