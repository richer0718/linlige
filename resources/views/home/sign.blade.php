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
		<a href="javascript:;" class="submit" style="float:left;width:48%;" onclick="saveSignature()">申请注册</a>
		<a href="javascript:;" class="submit" style="float:right;width:48%;" onclick="clearCanvas()" >清除</a>
	</div>
	<input type="hidden" id="json" value="{{ json_encode($return_arr) }}" />
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


            var url = '{{ url('home/signRes') }}';
            var data = $('#json').val();
            $.ajax({
                type: 'POST',
                url: url,
                data: {json:data,imgurl:dataUrl},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){

                    if(data == 'success'){
                        layer.msg('您已成功提交注册信息，请等待审核<br>您可浏览小区');
                        setTimeout(function(){location.href='{{ url('/home') }}';},1000);
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