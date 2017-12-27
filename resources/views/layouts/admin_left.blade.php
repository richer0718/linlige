<aside class="col-sm-1 col-md-1 col-lg-2 sidebar">
    <ul class="nav nav-sidebar">
        <li><a></a></li>
    </ul>
    <style>
        .dropdown-menu-new {
            width:100%;

            display: none;

            min-width: 160px;
            padding: 5px 0;
            margin: 2px 0 0;
            font-size: 14px;
            text-align: left;
            list-style: none;
            background-color: #fff;

            background-clip: padding-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .dropdown-menu-new>li>a {
            display: block;
            padding: 3px 20px;
            clear: both;
            font-weight: 400;
            line-height: 1.42857143;
            color: #333;
            white-space: nowrap;
        }
        .show{
            display:block;
        }

    </style>
    <script>
        $(function(){
            $('.dropdown-toggle-d').click(function(){
                if($(this).parent().find('.dropdown-menu-new').hasClass('show')){
                    $(this).parent().find('.dropdown-menu-new').removeClass('show');

                }else{
                    $('.dropdown-menu-new').removeClass('show');
                    $(this).parent().find('.dropdown-menu-new').addClass('show');

                }

            })
        })
    </script>
    <ul class="nav nav-sidebar">
        <li @if(Route::currentRouteName() == 'number' )class="active" @endif ><a class="dropdown-toggle-d" id="number"   >账号管理</a>
            <ul class="dropdown-menu-new @if(Route::currentRouteName() == 'number' ) show @endif "  >
                @if(session('type') == 0)
                <li><a href="{{url('admin/numberBack')}}">后台账户</a></li>
                <li><a href="{{url('admin/numberBusiness')}}">商户设置</a></li>
                <li><a href="{{url('admin/shenqingbusiness')}}">商户申请</a></li>
                @else
                <li><a href="{{url('admin/shequ_number')}}">账户列表</a></li>
                <li><a href="{{url('admin/wuye_number')}}">物业设置</a></li>
                @endif


            </ul>
        </li>
        <li  @if(Route::currentRouteName() == 'hangjia' )class="active" @endif><a href="{{url('admin/hangjiazaixian')}}">行家在线</a></li>
        @if(session('type') == 0)

        <li  @if(Route::currentRouteName() == 'shequ' )class="active" @endif ><a class="dropdown-toggle-d" id="shequ"   >社区管理</a>
            <ul class="dropdown-menu-new  @if(Route::currentRouteName() == 'shequ' ) show @endif " >
                <li><a href="{{url('admin/shequ')}}">社区列表</a></li>
                <li><a href="{{url('admin/shequxieyi')}}" >社区协议设置</a></li>
            </ul>
        </li>
        <li  @if(Route::currentRouteName() == 'shangcheng' )class="active" @endif ><a class="dropdown-toggle-d" id="market"   >商城管理</a>
            <ul class="dropdown-menu-new  @if(Route::currentRouteName() == 'shangcheng' ) show @endif " >
                <li><a href="{{url('admin/lunbo')}}">轮播管理</a></li>
                <li><a href="{{url('admin/gongyingshang')}}">供应商管理</a></li>
                <li><a href="{{url('admin/fenlei')}}">商品分类</a></li>
                <li><a href="{{url('admin/goods')}}">商品列表</a></li>
            </ul>
        </li>

        <li  @if(Route::currentRouteName() == 'order' )class="active" @endif ><a class="dropdown-toggle-d" id="market"   >订单管理</a>
            <ul class="dropdown-menu-new  @if(Route::currentRouteName() == 'shequ' ) order @endif " >
                <li><a href="{{url('admin/orderlist')}}">订单列表</a></li>
                <!--
                <li><a href="{{url('admin/tuihuolist')}}">退货待处理列表</a></li>
                -->
            </ul>
        </li>
            <li  @if(Route::currentRouteName() == 'tiaozao' )class="active" @endif><a href="{{url('admin/tiaozao/4')}}">跳蚤市场</a></li>
            <li   @if(Route::currentRouteName() == 'message' )class="active" @endif ><a href="{{url('admin/sendMessage')}}">群发消息</a></li>
            <li   @if(Route::currentRouteName() == 'ticket' )class="active" @endif ><a href="{{url('admin/ticket')}}">优惠券</a></li>
        @endif


        @if(session('type') == 1)
        <li  @if(Route::currentRouteName() == 'zhidekan' )class="active" @endif><a href="{{url('admin/zhidekan')}}">值得看</a></li>

        <li  @if(Route::currentRouteName() == 'linlihudong' )class="active" @endif ><a class="dropdown-toggle-d" id="market"   >邻里互动管理</a>
            <ul class="dropdown-menu-new @if(Route::currentRouteName() == 'linlihudong' ) show @endif "  >
                <li><a href="{{url('admin/linlihudong')}}">邻里说</a></li>
                <li><a href="{{url('admin/linlihudong/1')}}">友邻互助</a></li>
                <li><a href="{{url('admin/linlihudong/2')}}">社区活动</a></li>
                <li><a href="{{url('admin/linlihudong/3')}}">共享车位</a></li>
            </ul>
        </li>

        <li  @if(Route::currentRouteName() == 'bianminfuwu' )class="active" @endif><a href="{{url('admin/bianminfuwu')}}">便民服务</a></li>
        <li  @if(Route::currentRouteName() == 'toupiao' )class="active" @endif><a href="{{url('admin/toupiao')}}">投票设置</a></li>

            <li  @if(Route::currentRouteName() == 'yonghu' )class="active" @endif ><a class="dropdown-toggle-d" id="market"   >用户列表</a>
                <ul class="dropdown-menu-new @if(Route::currentRouteName() == 'yonghu' ) show @endif "  >
                    <li><a href="{{url('admin/yonghu/1')}}">用户列表</a></li>
                    <li><a href="{{url('admin/yonghu/0')}}">待审核列表</a></li>
                </ul>
            </li>



        @endif

    </ul>


    <!--
    <ul class="nav nav-sidebar">
        <li><a href="category.html">栏目</a></li>
        <li><a class="dropdown-toggle-d" id="otherMenu"   >其他</a>
            <ul class="dropdown-menu-new" >
                <li><a href="flink.html">友情链接</a></li>
                <li><a data-toggle="modal" data-target="#areDeveloping">访问记录</a></li>
            </ul>
        </li>
    </ul>
    <ul class="nav nav-sidebar">
        <li><a class="dropdown-toggle-d" id="userMenu"   >用户</a>
            <ul class="dropdown-menu-new" aria-labelledby="userMenu">
                <li><a data-toggle="modal" data-target="#areDeveloping">管理用户组</a></li>
                <li><a href="manage-user.html">管理用户</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="loginlog.html">管理登录日志</a></li>
            </ul>
        </li>
        <li><a class="dropdown-toggle-d" id="settingMenu"   >设置</a>
            <ul class="dropdown-menu-new" aria-labelledby="settingMenu">
                <li><a href="setting.html">基本设置</a></li>
                <li><a href="readset.html">阅读设置</a></li>
                <li role="separator" class="divider"></li>
                <li><a data-toggle="modal" data-target="#areDeveloping">安全配置</a></li>
                <li role="separator" class="divider"></li>
                <li class="disabled"><a>扩展菜单</a></li>
            </ul>
        </li>
    </ul>
    -->

</aside>