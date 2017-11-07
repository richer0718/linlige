<!doctype html>
<html lang="zh">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	@include('layouts.common')
	<title>签名</title>
    <script src="{{ asset('js/jquery-1.11.1.min.js') }}"></script>
	<script src="{{ asset('js/jq-signature.js') }}"></script>

</head>
<body>
    <div class="js-signature" style=""></div>
	<div style="height:80px;">
		<a href="javascript:;" class="submit" style="float:left;width:48%;" onclick="saveSignature()">提交</a>
		<a href="javascript:;" class="submit" style="float:right;width:48%;" onclick="clearCanvas()" >清除</a>
	</div>
	<input type="hidden" id="json" value="{{ $data }}"/>
	<input type="hidden" id="tiankongdata" value="{{ $tiankongdata }}"/>
	<script type="text/javascript">
		$(document).on('ready', function() {
            var height_all = parseInt($(window).height()) - 100 ;
            var width_all = parseInt($(window).width());


            $('.js-signature').jqSignature({
				height:height_all,
				width:300,
                background: '#fff',
                lineColor: '#bc0000',
                lineWidth: 1,
                autoFit: true
            });
		});
		function clearCanvas() {
            $('.js-signature').jqSignature('clearCanvas');    
		}
		function saveSignature() {
			var dataUrl = $('.js-signature').jqSignature('getDataURL');


            var url = '{{ url('home/toupiaoRes') }}' + '/id/' + '{{ $id }}';
            var data = $('#json').val();
            var tiankongdata = $('#tiankongdata').val();
            $.ajax({
                type: 'POST',
                url: url,
                data: {json:data,imgurl:dataUrl,tiankongdata:tiankongdata},
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
                    layer.msg('Ajax error!')
                }
            });
		}
	</script>
<!--
iVBORw0KGgoAAAANSUhEUgAAAoAAAAGRCAYAAAD1rwKAAAAUjklEQ
iVBORw0KGgoAAAANSUhEUgAAAoAAAAGRCAYAAAD1rwKAAAAUjklEQ
-->
</body>
</html>