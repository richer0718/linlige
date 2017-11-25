<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('layouts.common')
</head>
<body>
<style>
    /*全局样式*/
    html{height:100%;font-size:50px;}
    html,body,span,object,iframe,div,
    h1,h2,h3,h4,h5,h6,p,blockquote,pre,
    a,abbr,acronym,address,big,cite,code,
    del,dfn,em,font,img,ins,kbd,q,s,samp,
    small,strike,strong,sub,sup,tt,var,
    b,u,i,center,
    dl,dt,dd,ol,ul,li,
    input,button,textarea,select,
    fieldset,form,label,legend,
    table,caption,tbody,tfoot,thead,tr,th,td,
    article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video,progress{
        margin:0;
        padding:0;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        vertical-align:baseline;
    }
    body{max-width:640px;height:100%;color: #333;-webkit-text-size-adjust: 100%!important;background-color:#efefef;font-size:0.28rem;line-height: 1;margin:0 auto;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif, "Microsoft YaHei";}
    html,body{-webkit-user-select:none;user-select:none;}
    a{text-decoration: none;-webkit-touch-callout:none;background-color: transparent;}
    a:active,a:hover{text-decoration:none;outline:0;}
    article,aside,details,figcaption,figure,footer,header,hgroup,main,menu,nav,section,summary{display:block;}
    audio, canvas, progress, video{display: inline-block; vertical-align: baseline;}
    audio:not([controls]){display:none;height:0;}
    [hidden],template{display: none;}
    svg:not(:root){overflow: hidden;}
    fieldset, img {border:0;}
    ol,ul{ list-style:none;}
    h1, h2, h3, h4, h5, h6{font-size:100%;font-weight:normal;}
    input, button, select, textarea {border:0;outline:none;background:none; -webkit-appearance:none;-moz-appearance:none;appearance:none;font-size:0.28rem;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif;}
    input::-webkit-input-placeholder, textarea::-webkit-input-placeholder { line-height:normal;font-size:0.28rem;color: #ccc;}
    textarea {resize:none;}
    p{text-align:justify;}
    table{border-collapse:collapse;border-spacing:0;table-layout:fixed;word-wrap:break-word;word-break:break-all;}
    img{max-width:100%;}
    sub,sup {position:relative;font-size:75%;line-height:0;vertical-align:baseline;}
    sup{top: -.5em;}
    sub {bottom: -.25em;}
    input[type="number"]::-webkit-inner-spin-button, input[type="number"]::-webkit-outer-spin-button { height: auto; -webkit-appearance: none; -moz-appearance: textfield; }
    svg:not(:root){overflow:hidden;}
    a,button,input,textarea{-webkit-tap-highlight-color: rgba(0,0,0,0)}
    /*常用样式*/
    .scroll{-webkit-overflow-scrolling: touch;overflow-y:scroll;}
    .clearfix:after {visibility:hidden;display:block;font-size:0;content:'';clear:both;height:0;}
    .none{display:none;}
    /*清除input缓存填充*/
    input:-webkit-autofill,textarea:-webkit-autofill{
        -webkit-box-shadow:0 0 0px 1000px #fff inset!important;
    }

    /*单选框*/

    /*
    <label class="label-switch">
        <input type="checkbox">
        <div class="checkbox"></div>
    </label>*/

    .label-switch {
        position:relative;
        display: inline-block;
        vertical-align: middle;
        width: 0.92rem;
        border-radius: 0.8rem;
        box-sizing: border-box;
        height: 0.54rem;
        position: relative;
        cursor: pointer;
        -webkit-align-self: center;
        align-self: center;
    }
    .label-switch .checkbox {
        width: 0.92rem;
        border-radius: 0.8rem;
        box-sizing: border-box;
        height: 0.54rem;
        background: #e5e5e5;
        z-index: 0;
        margin: 0;
        padding: 0;
        -webkit-appearance: none;
        -moz-appearance: none;
        -ms-appearance: none;
        appearance: none;
        border: none;
        cursor: pointer;
        position: relative;
        -webkit-transition-duration: 300ms;
        transition-duration: 300ms;
    }

    .label-switch input[type="checkbox"] {
        position:absolute;left:0;width:100%;top:0;height:100%;opacity:0;z-index:100;
    }
    .label-switch input[type="checkbox"]:checked + .checkbox {
        background: #3fb994;
    }
    .label-switch input[type="checkbox"]:checked + .checkbox:before {
        -webkit-transform: scale(0);
        transform: scale(0);
    }
    .label-switch input[type="checkbox"]:checked + .checkbox:after {
        -webkit-transform: translateX(0.46rem);
        transform: translateX(0.46rem);
    }

    .label-switch .checkbox:before {
        content: ' ';
        position: absolute;
        left: 0.02rem;
        top: 0.02rem;
        width: 0.88rem;
        border-radius: 1rem;
        box-sizing: border-box;
        height: 0.5rem;
        background: #fff;
        z-index: 1;
        -webkit-transition-duration: 300ms;
        transition-duration: 300ms;
        -webkit-transform: scale(1);
        transform: scale(1);
    }
    .label-switch .checkbox:after {
        content: ' ';
        height: 0.5rem;
        width: 0.5rem;
        border-radius: 0.5rem;
        background: #fff;
        position: absolute;
        z-index: 2;
        top: 0.02rem;
        left: 0.02rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
        -webkit-transform: translateX(0px);
        transform: translateX(0px);
        -webkit-transition-duration: 300ms;
        transition-duration: 300ms;
    }
    /* ============================================================
       flex：定义布局为盒模型
       flex-v：盒模型垂直布局
       flex-1：子元素占据剩余的空间
       flex-align-center：子元素垂直居中
       flex-pack-center：子元素水平居中
       flex-pack-justify：子元素两端对齐
       兼容性：ios 4+、android 2.3+、winphone8+
       ============================================================ */
    .flex{display:-webkit-box;display:-webkit-flex;display:flex;display:-ms-flexbox;}
    .flex-v{-webkit-box-orient:vertical;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;}
    .flex-1{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;}
    .flex-align-center{-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;}
    .flex-pack-center{-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;}
    .flex-pack-justify{-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;}
    .flex-align{display:-webkit-box;display:-webkit-flex;display:flex;display:-ms-flexbox;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;}
    .flex-justify{display:-webkit-box;display:-webkit-flex;display:flex;display:-ms-flexbox;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;}
    @media screen and (min-width: 320px) and (max-width: 359px) {
        /*当屏幕尺寸大于等于320px小于359px时，应用下面的CSS样式*/
    }
    @media (device-height:480px) and (-webkit-min-device-pixel-ratio:2) {/* 兼容iphone4/4s */
    }
    @media (device-height:568px) and (-webkit-min-device-pixel-ratio:2) {/* 兼容iphone5 */
    }
    @media (device-width:375px) and (-webkit-min-device-pixel-ratio:2) {/* 兼容iphone6 */
    }
    @media (device-width:414px) and (-webkit-min-device-pixel-ratio:3) {/* 兼容iphone6plus */
    }
    @media screen and (orientation:portrait) {
        /* 纵向显示 */
    }
    @media screen and (orientation:landscape) {
        /* 横向显示 */
    }
    @media screen and (-webkit-device-pixel-ratio:2) {
        /* 设备像素比 */
    }
    /*注册*/
    .register-pop{position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.8);}
    .register-pop i{position:absolute;top:1.06rem;right:0.56rem;color:#fff;font-size:0.6rem;}
    .register-pop>div{position:absolute;top:20%;left:18.7%;width:62.6%;}
    .register-pop a{display:block;width:100%;height:0.95rem;line-height:0.95rem;margin-top:0.5rem;font-size:0.3rem;background:#f77215;border-radius:10px;color:#fff;text-align:center;}
    .public-header{position:relative;width:7.5rem;height:0.78rem;display: table-cell; vertical-align: middle; background:#3fb994;text-align:center;}
    .public-header>i{position:absolute;left:0.22rem;top:50%;margin-top:-0.2rem;color:#fff;font-size:0.4rem;}
    .public-header span{color:#fff;font-size:0.36rem;}
    .public-header span i{margin-right:0.1rem;vertical-align:-2px;font-size:0.4rem;}
    .public-header img{width:1.36rem;}
    .search{width:100%;padding:0.1rem 0;background:#ebebeb;text-align:center;}
    .search-main{display:inline-block;width:81%;height:0.56rem;border-radius:30px;line-height:0.56rem;padding-left:0.16rem;text-align:left;background:#fff;}
    .search-main i{font-size:0.3rem;color:#bbb;}
    .search-main input{margin-left:0.08rem;font-size:0.3rem;color:#333;}
    .search-main input::-webkit-input-placeholder{color:#bbb;}
    .search a{margin-left:0.2rem;font-size:0.3rem;color:#bbb;}
    .key-list{padding-left:0.22rem;background:#fff;}
    .key-list a{display:block;padding:0.3rem 0;border-bottom:1px solid #f2f2f2;font-size:0.3rem;color:#bbb;}
    .search-page{position:absolute;left:0;top:0;width:100%;z-index:100;}
    /*注册*/
    .header{position:relative;width:100%;height:0.78rem;line-height:0.78rem;background:#3fb994;color:#fff;}
    .header i{position:absolute;left:0.21rem;font-size:0.4rem;}
    .header a{position:absolute;right:0.21rem;font-size:0.26rem;color:#fff;}

    .register-data{padding:1.22rem 1.07rem 1.72rem;background:url('../images/register-bg.png') no-repeat center bottom;background-size:100% auto;}
    .load-data{padding:3rem 1.07rem 1.72rem;}
    .register-data>input{width:100%;height:0.9rem;margin-bottom:0.3rem;padding-left:0.92rem;border:1px solid #e0e0e0 ;border-radius:25px;}
    .load-data>input{margin-bottom:0.6rem;}
    .name{background:url('../images/name-icon.png') no-repeat 0.35rem center;background-size:0.33rem;}
    .phone{background:url('../images/phone-icon.png') no-repeat 0.35rem center;background-size:0.28rem;}
    .password{background:url('../images/password.png') no-repeat 0.35rem center;background-size:0.38rem;}
    .verification-code{background:url('../images/code-icon.png') no-repeat 0.35rem center;background-size:0.35rem;}
    .key-search{background:url('../images/key-icon.png') no-repeat 0.35rem center;background-size:0.36rem;}
    .floor{background:url('../images/floor-icon.png') no-repeat 0.35rem center;background-size:0.38rem;}
    .house-number{background:url('../images/doorplate-icon.png') no-repeat 0.35rem center;background-size:0.41rem;}
    .identity{background:url('../images/identity-icon.png') no-repeat 0.35rem center;background-size:0.31rem;}
    .status{background:url('../images/family-icon.png') no-repeat 0.35rem center;background-size:0.4rem;}
    .data-select input,.data-select select{width:100%;height:0.9rem;padding-left:0.92rem;border:1px solid #e0e0e0 ;border-radius:25px;}
    .code{position:relative;margin-bottom:0.3rem;}
    .code>input:first-child{width:100%;height:0.9rem;padding-right:1.85rem;padding-left:0.92rem;border:1px solid #e0e0e0 ;border-radius:25px;}
    .code input:last-child{position:absolute;right:0.16rem;top:0.165rem;width:1.7rem;height:0.57rem;line-height:0.57rem;text-align:center;color:#fff;background:#5ecaae;border-radius:15px;}
    .code  a{display:block;position:absolute;right:0.16rem;top:0.165rem;width:1.7rem;height:0.57rem;line-height:0.57rem;text-align:center;color:#fff;background:#5ecaae;border-radius:15px;}
    .code  a i{color:#fff;font-size:0.3rem;margin-right:0.1rem;}
    .agreement{position:relative;margin-top:0.15rem;color:#5ecaae;font-size:0.22rem;}
    .agreement input{position:absolute;left:0;top:0;width:100%;height:100%;opacity:0;z-index:100;}
    .agreement span{display:inline-block;vertical-align:middle;width:0.28rem;height:0.28rem;background:url('../images/check.png') no-repeat center;background-size:contain;}
    .agreement input:checked+span{background:url('../images/checked.png') no-repeat center;background-size:contain;}
    .submit{display:block;width:100%;height:0.86rem;margin-top:0.55rem;line-height:0.86rem;font-size:0.3rem;line-height:0.86rem;text-align:center;color:#fff;background:#5ecaae;border-radius:25px;}
    .data-select{margin-bottom:0.3rem;background:url('../images/down.png') no-repeat 95% center;background-size:0.25rem;}
    .data-select-last{margin-bottom:0;}

    .info-head{position:relative;width:100%;height:0.78rem;line-height:0.78rem;background:#3fb994;text-align:center;color:#fff;font-size:0.34rem;}
    .info-head i{position:absolute;left:0.28rem;color:#fff;font-size:0.4rem;}
    .user-main{position:relative;padding:0.45rem 0 0.32rem;background:url('../images/user-bg.jpg') no-repeat center;background-size:cover;}
    .user-main h3{margin-top:0.3rem;color:#fff;font-size:0.26rem;}
    .user-main h3 span{font-size:0.32rem;margin-right:0.15rem;}
    .user-main p{margin-top:0.06rem;text-align:center;color:#fff;font-size:0.26rem;}
    .user-main>img{position:absolute;right:0.24rem;top:0.24rem;width:0.67rem;}
    .user-center{text-align:center;}
    .user-photo{display:inline-block;width:1.66rem;height:1.66rem;padding:2px;background:#fff;border-radius:100%;}
    .user-photo>div{width:100%;height:100%;border-radius:100%;}
    .user-center>span{display:block;margin:0.2rem 0 0;text-align:center;font-size:0.3rem;color:#666;}
    .integral-info>a>div{margin-top:0.18rem;padding:0.28rem;background:#fff;}
    .integral-info>a:active>div{background:#f9f9f9;}
    .link-list span{display:inline-block;vertical-align:middle;font-size:0.28rem;color:#666;}
    .link-list img{width:0.35rem;vertical-align:middle;margin-right:0.2rem;}
    .link-list i{color:#878787;}


    /*文章页*/
    .article-page{padding:0.32rem 0.25rem 0.36rem;}
    .article-page h3{font-size:0.28rem;text-align:center;}
    .article-time{margin:0.18rem 0 0.3rem;color:#bbb;text-align:center;font-size:0.2rem;}
    .article-main{}
    .article-main p{color:#999;font-size:0.26rem;line-height:1.5;}
    .article-main img{width:100%!important;margin:0.15rem 0;}
    .interaction{padding:0.28rem 0.2rem 0 0;border-top:2px solid #f2f2f2;text-align:right;font-size:0;}
    .interaction i{font-size:0.4rem;}
    .interaction i:first-child{vertical-align:-0.05rem;padding-right:0.33rem;border-right:1px solid #f2f2f2;color:#ffb882;}
    .interaction i:last-child{padding-left:0.28rem;color:#f86257;}
    .vote{padding-bottom:0.3rem;}
    .vote header{padding-top:0.21rem;background:#fff;}
    .vote header a{width:50%;text-align:center;}
    .vote1 header a{width:33.333%;}
    .vote header a.hover{color:#ff7d1f;}
    .vote header span{display:inline-block;font-size:0.3rem;color:#666;padding:0 0.1rem 0.2rem;border-bottom:2px solid #fff;}
    .vote a.hover span{border-bottom:2px solid #ff7d1f;color:#ff7d1f;}
    .vote-main{}
    .cancel-reservation{width:1.4rem;height:0.6rem;color:#999;border:1px solid #dfdfdf;border-radius:3px;line-height:0.6rem;text-align:center;}
    .cancel-reservation:active{border:1px solid #ff7d1f;color:#ff7d1f;}
    .vote-list{margin-top:0.25rem;padding:0.3rem 0.25rem 0.32rem;background:#fff;}
    .vote-list h3{margin-bottom:0.12rem;font-size:0.28rem;}
    .vote-list h3 span{display:inline-block;max-width:93%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#666;font-size:0.28rem;}
    .vote-list p{color:#999;font-size:0.20rem;}
    .vote-list h3 img{width:0.34rem;margin-left:0.1rem;vertical-align:0.15rem;}
    .vote-list a img{width:0.51rem;}
    .load-more{margin-top:0.5rem;text-align:center;}
    .load-more p{margin-bottom:0.12rem;color:#bbb;font-size:0.2rem;text-align:center;}
    .load-more img{width:0.89rem;}

    .coupon-main{}
    .coupon-main section{width:94%;height:2.37rem;margin:0.2rem auto 0;}
    .coupon-time{width:32.6%;height:100%;padding-top:0.45rem;text-align:center;background:url('../images/coupon-bg.png') no-repeat center;background-size:100% 100%;color:#fff;}
    .coupon-time h3{margin-bottom:0.15rem;font-size:0.4rem;}
    .coupon-time h3 span{font-size:0.95rem;font-weight:bold;}
    .coupon-time h3 i{font-style:normal;margin-right:-0.15rem;}
    .coupon-time h4{font-size:0.22rem;line-height:1.5;}
    .coupon-time p{text-align:center;font-size:0.22rem;}
    .coupon-content{padding:0.36rem 0.2rem 0.25rem 0.28rem;background:#fdfdfd;}
    .coupon-content h3{margin-bottom:0.2rem;font-weight:bold;font-size:231815;font-size:0.32rem;}
    .coupon-content p{color:#727171;line-height:1.5;}
    .coupon-content div{text-align:right;}
    .coupon-content a{display:inline-block;width:1.35rem;height:0.47rem;text-align:center;border:1px solid #fa7356;border-radius:15px;line-height:0.47rem;font-size:0.24rem;color:#fa7356;}
    .coupon-content a:active{background:#fa7356;color:#fff;}

    .customer-main{margin-top:0.2rem;}
    .customer-list{padding-left:0.25rem;background:#fff;}
    .customer-list>div{padding:0.26rem 0.25rem 0.3rem 0;border-bottom:1px solid #f2f2f2;}
    .customer-list div div{width:100%;margin-bottom:0.12rem;}
    .vote1 .customer-list div div{margin-bottom:0;}
    .customer-list h3{font-size:0.3rem;max-width:93%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .vote1 .customer-list h3{margin-bottom:0.14rem;}
    .customer-list p{font-size:0.22rem;color:#999;}
    .customer-list img{width:0.39rem;}
    .customer-list:last-child>div{border-bottom:0;}

    .prompt{padding:0.48rem 0 0.58rem 0.35rem;background:#fff;}
    .prompt section{margin-top:0.45rem;}
    .prompt p{text-align:center;color:#bbb;font-size:0.2rem;margin-bottom:0.12rem;}
    .prompt div{padding:0.2rem 0.2rem 0.3rem;line-height:1.5;background:#edf9fc;width:86%;}
    .prompt section:first-child{margin-top:0;}
    .love ul{padding:0.47rem 0.25rem 0.4rem;}
    .love li{position:relative;float:left;width:22%;margin:0 4% 0.5rem 0;}
    .love li:nth-child(4n+4){margin-right:0;}
    .love-bg{width:100%;}
    .love-bg:after{content:'';display:block;padding-top:100%;}
    .love-icon{position:absolute;top:-0.18rem;right:-0.18rem;width:0.36rem;height:0.36rem;background:url("../images/no-love.png") no-repeat center;background-size:cover;}
    .love div{margin-bottom:0.1rem;}
    .love h4{text-align:center;color:#999;font-size:0.24rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
    .love input{position:absolute;right:-0.18rem;top:-0.18rem;;width:100%;height:100%;opacity:0;z-index:100;}
    .love input:checked+.love-icon{background: url(../images/love.png) no-repeat center;background-size: cover;}

    .expert section{padding:0.25rem;margin-bottom:0.2rem;background:#fff;}
    .expert section div:first-child{width:1.6rem;height:2.06rem;}
    .expert-main{margin-left:0.15rem;}
    .expert-main h3{font-size:0.32rem;}
    .expert-main h3 span{display:inline-block;margin-left:0.18rem;color:#999;font-size:10px;padding:0.04rem;text-align:center;border:1px solid #f1f1f1;border-radius:3px;}
    @media (device-width:375px) and (-webkit-min-device-pixel-ratio:2) {/* 兼容iphone6 */
        .expert-main h3 span{font-size:11px;}
    }
    @media (device-width:414px) and (-webkit-min-device-pixel-ratio:3) {/* 兼容iphone6plus */
        .expert-main h3 span{font-size:12px;}
    }
    .expert-main div{margin:0.18rem 0;color:#999;font-size:0.26rem;}
    .expert-main div span{margin-left:0.34rem;color:#3fb994;font-size:0.24rem;}
    .expert-main p{line-height:1.3;color:#999;}

    .sale{padding-bottom:0.3rem;}
    .sale section{padding:0.38rem 0.25rem;background:#fff;}
    .sale section input{height:0.8rem;width:100%;text-indent:0.15rem;font-size:0.32rem;border:1px solid #dcdcdc;border-radius:3px;}
    .sale section input:focus{border:1px solid #3fb994;box-shadow:0 0 3px #3fb994;-webkit-box-shadow:0 0 3px #3fb994;}
    .sale section input::-webkit-input-placeholder{font-size:0.32rem;}
    .sale-text{padding:0.28rem 0.25rem 0.38rem;margin-top:0.2rem;background:#fff;}
    .sale-text textarea{width:100%;height:3.05rem;padding:0.16rem;font-size:0.26rem;border:1px solid #dcdcdc;border-radius:3px;}
    .sale-text textarea:focus{border:1px solid #3fb994;box-shadow:0 0 3px #3fb994;-webkit-box-shadow:0 0 3px #3fb994;}
    .sale-upload{margin-top:0.25rem;}
    .sale-upload>div{position:relative;float:left;border:1px solid #efefef;width:1.86rem;height:1.86rem;margin-right:0.25rem;}
    .sale-upload div div{position:absolute;right:0;top:-1px;width:0.32rem;height:0.32rem;z-index:100;background:url(../images/file-img.png) no-repeat center;background-size:contain;}
    .sale-upload input{position:absolute;left:0;top:0;width:100%;height:100%;opacity:0;z-index:100;}
    .upload-img{background:url('../images/upload-img.png') no-repeat center;background-size:contain;}
    .upload-file{background:url('../images/upload-file.png') no-repeat center;background-size:contain;}
    .publish{display:block;margin:0.65rem auto 0;width:80%;height:0.8rem;line-height:0.8rem;text-align:center;background:#3fb994;color:#fff;font-size:0.3rem;border-radius:3px;}
    .upload-tips{display:inline-block;padding:0.05rem 0.14rem;margin-top:0.22rem;font-size:0.2rem;color:#bbb;padding-left:0.15rem;border-radius:15px;background:#ebf7f1;}
    .upload-tips i{margin-right:0.05rem;color:#fe7074;font-size:0.2rem;}
    .sale ul{margin-top:0.05rem;}
    .sale li{position:relative;float:left;margin:0.15rem 0.24rem 0 0; }
    .sale li a{display:block;width:1.4rem;height:0.56rem;line-height:0.56rem;border:1px solid #f2f2f2;color:#666;border-radius:3px;text-align:center;font-size:0.26rem;}
    .sale li input:checked+a{border:1px solid #3fb994;color:#3fb994;}
    .sale li input{position:absolute;left:0;top:0;width:100%;height:100%;opacity:0;z-index:100;}
    .help-title{height:0.8rem;width:100%;margin:0.23rem 0;text-indent:0.15rem;font-size:0.32rem;border:1px solid #dcdcdc;border-radius:3px;}
    .help-date{height:0.8rem;width:100%;text-indent:0.15rem;background:url('../images/date.png') no-repeat 95% center;background-size:0.43rem;font-size:0.32rem;border:1px solid #dcdcdc;border-radius:3px;}
    .remuneration div{font-size:0;}
    .remuneration div input{width:3.85rem;height:0.8rem;margin-right:0.1rem;vertical-align:middle;text-indent:0.15rem;font-size:0.32rem;border:1px solid #dcdcdc;border-radius:3px;}
    .remuneration div input:focus{border:1px solid #3fb994;box-shadow:0 0 3px #3fb994;-webkit-box-shadow:0 0 3px #3fb994;}
    .remuneration div span{display:inline-block;vertical-align:middle;font-size:0.32rem;color:#bbb;}
    .remuneration div a{display:inline-block;width:1.5rem;height:0.78rem;margin-left:0.1rem;color:#fff;font-size:0.32rem;line-height:0.78rem;text-align:center;border-radius:3px;background:#3fb994;color:#fff;vertical-align:middle;}
    .help-time{font-size:0;border:1px solid #dcdcdc;border-radius:3px;}
    .help-time span{display:inline-block;color:#bbb;}
    .help-time input,.help-time span{font-size:0.32rem;vertical-align:middle;}
    .sale .help-time input{width:46%;height:0.8rem;text-indent:0.15rem;border:0;}
    .help-time input:last-child{background:url('../images/date.png') no-repeat 100% center;background-size:0.43rem;}
    .return-goods{margin-top:0.16rem;padding: 0.26rem 0.25rem;background: #fff;}
    .return-goods span{font-size:0.28rem;margin-right:0.15rem;}
    .return-goods input{height:0.4rem;font-size:0.28rem;}
    .sale h3{margin-bottom:0.25rem;font-size:0.28rem;}
    .return-goods-text textarea{height:1.3rem;}
    .return-goods-text .upload-file{background:url('../images/upload-file1.png') no-repeat center;background-size:contain;}
    .score{background:#fff;}
    .score h3{padding:0.3rem;margin:0.2rem 0 0 0;border-bottom:1px solid #efefef;font-size:0.3rem;}
    .score div{font-size:0;padding:0.3rem 0 0.4rem 0.3rem;}
    .score div img{width:0.45rem;margin-right:0.1rem;}
    .evaluate .sale-text{margin-top:0;}

    .share{position:absolute;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.6);}
    .share img{position:absolute;right:0.36rem;top:0.63rem;width:5.3rem;}
    .set-more a{display:block;margin-top:0.18rem;background:#fff;}
    .set-more a:active{background:#f9f9f9;}
    .set-more section{padding:0.32rem 0.21rem;}
    .set-more section>span{color:#666;font-size:0.28rem;}
    .set-more section>i{color:#878787;}
    .help-set>span{color:#666;font-size:0.28rem;}
    .help-set>div{display:inline-block;padding:0.04rem 0.1rem;margin-left:0.05rem;background:#ebf7f1;color:#bbb;font-size:0.2rem;border-radius:15px;}
    .help-set>div i{margin-right:0.06rem;color:#fd7579;font-size:0.2rem;}
    .set-more section .help-select{color:#3fb994;font-size:0.5rem;}

    .process{padding-top:0.16rem;background:#fff;}
    .process a{width:25%;text-align:center;}
    .process i{font-size:0.4rem;display:block;color:#666;margin-bottom:0.07rem;}
    .process a.hover span,.process a.hover i{color:#ff7d1f;}
    .process span{display:inline-block;font-size:0.26rem;color:#666;padding:0 0.1rem 0.16rem;border-bottom:2px solid #fff;}
    .process a.hover span{border-bottom:2px solid #ff7d1f;}

    .process-main>img{display:block;margin:0 auto;padding-top:1.36rem;width:3rem;}
    .process-main>a{display:block;width:72%;height:0.86rem;margin:0.66rem auto 0;line-height:0.86rem;font-size:0.3rem;text-align:center;background:#3fb994;border-radius:25px;color:#fff;}
    .process-main>a:active{background:#1CD89F;}
    .process-list{margin-top:0.2rem;background:#fff;}
    .process-list>h3{padding:0.37rem 0.25rem 0.2rem;border-bottom:1px solid #efefef;}
    .process-list>h3 div{font-size:0.26rem;color:#666;}
    .process-list>h3 div span{margin-left:0.24rem;font-size:0.2rem;color:#bbb;}
    .process-list>h3>span{font-size:0.26rem;color:#3fb994;}
    .details-main{padding:0 0.25rem 0.25rem;}
    .process-details{padding:0.18rem 0;}
    .process-details>div:first-child{width:1.48rem;height:1.48rem;}
    .goods-details{margin-left:0.12rem;}
    .goods-details h4{margin-bottom:0.3rem;color:#535d69;font-size:0.3rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
    .goods-details span:first-child{color:#ff423e;font-size:0.3rem;}
    .goods-details span:last-child{font-size:0.26rem;}
    .goods-details span i{font-size:0.2rem;font-style:normal;}
    .pick-up{padding:0.2rem 0 0.2rem 0.12rem;-webkit-box-align:start;-webkit-align-items:flex-start;align-items:flex-start;font-size:0.24rem;background:#f9f9f9;color:#999;}
    .pick-up>span{color:#666;line-height:1.3;}
    .pick-up div{margin-left:0.16rem;color:#999;}
    .pick-up div span{display:inline-block;line-height:1.3;}
    .goods-total{margin-top:0.15rem;}
    .goods-total div{font-size:0.26rem;color:#666;}
    .goods-total div em{color:#999;font-size:0.24rem;font-style:normal;}
    .goods-total span{font-size:0.38rem;color:#ff423e;}
    .goods-total a{display:inline-block;width:1.42rem;height:0.55rem;line-height:0.55rem;border-radius:5px;font-size:0.27rem;border:1px solid #f86257;color:#f86257;text-align:center;}
    .goods-total span i{font-style:normal;font-size:0.28rem;}
    .evaluate>a:first-child{color:#999;border:1px solid #e9e9e9;margin-right:0.12rem;}
    .goods-total div a:active{background:#f86257;border:1px solid #f86257;color:#fff;}

    .comment-title{padding:0.28rem 0.25rem 0.32rem;background:#fff;}
    .comment-head{margin-bottom:0.26rem;font-size:0.3rem;}
    .comment-head h3 span{display:inline-block;vertical-align:middle;font-size:0.2rem;margin-left:0.08rem;padding:0.05rem;text-align:center;border-radius:3px;}
    .resolved{background:#4ee4c2;color:#fff;}
    .hygiene{color:#999;border:1px solid #f0f0f0;}
    .pending{background:#ffb07f;color:#fff;border:1px solid #ffb07f;}
    .comment-head h3 i{margin-left:0.1rem;color:#3fb994;font-size:0.3rem;}
    .comment-head>span{font-size:0.2rem;color:#999;}
    .comment-title>p{margin-bottom:0.25rem;font-size:0.26rem;color:#999;line-height:1.3;}
    .comment-answer{padding-top:0.26rem;margin-top:0.26rem;border-top:1px solid #f0f0f0;}
    .comment-answer p{font-size:0.26rem;color:#d37d42;}

    .answer-img{flex-flow:row wrap;-webkit-flex-flow:row wrap;}
    .answer-img div{position:relative;width:1.9rem;height:1.9rem;margin:0.26rem 0.3rem 0 0;}
    .answer-img input{position:absolute;left:0;top:0;width:100%;height:100%;opacity:0;z-index:100;}
    .interested{margin-top: 0.2rem;background:#fff;}
    .interested h3{padding:0.32rem 0 0.32rem 0.25rem;color:#999;font-size:0.28rem;border-bottom:1px solid #efefef;}
    .interested-foot{padding:0 0.25rem;}
    .interested-img{}
    .interested-img div{float:left;width:15%;border-radius:100%;margin:0.2rem 2% 0 0;}
    .interested-img div:after{content:'';padding-top:100%;display:block;}
    .interested-img div:nth-child(6n+6){margin-right:0;}
    .interested-more{margin-top:0.25rem;padding:0.25rem 0;border-top:1px solid #efefef;text-align:center;}
    .interested-more img{width:0.3rem;}

    .comment-foot>span{font-size:0.22rem;color:#999;}
    .comment-foot i{font-size:0.26rem;color:#bbb;}
    .comment-foot div span{color:#bbb;font-size:0.2rem;}
    .comment-foot img{width:0.25rem;margin:0 0.05rem 0 0.2rem;vertical-align:-2px;}
    .comment-wrap{margin-top:0.2rem;background:#fff;}
    .comment-wrap h3{padding:0.32rem 0 0.32rem 0.25rem;color:#999;font-size:0.28rem;border-bottom:1px solid #efefef;}
    .comment-content{padding-left:0.24rem;}
    .comment-list{padding:0.26rem 0.25rem 0.46rem 0;border-bottom:1px solid #efefef;}
    .comment-list>div:first-child{width:0.88rem;height:0.88rem;margin-right:0.2rem;border-radius:100%;}
    .comment-main span:first-child{color:#3fb994;font-size:0.28rem;}
    .comment-main span:last-child{color:#bbb;font-size:0.2rem;}
    .comment-main p{margin-top:0.16rem;color:#999;font-size:0.26rem;line-height:1.3;}
    .comment-list:last-child{margin-bottom:0;}
    .comment-img{margin:-0.12rem 0 0.16rem;}
    .comment-img div{width:1.91rem;height:1.91rem;float:left;margin-right:0.27rem;}
    .comment-head h3 strong{margin:0 0.18rem 0 0.1rem;color:#ff423e;font-size:0.32rem;font-weight:normal;}
    .comment-head h3 em{font-style:normal;font-size:0.24rem;}
    .comment-time{margin:-0.06rem 0 0.2rem;color:#3fb994;}
    .comment-time span{margin-right:0.16rem;}
    .community-details .comment-title{border-bottom:1px solid #efefef;}
    .property-reply{margin:0.2rem 0 0 0;font-size:0.26rem;color:#d37d42;padding: 0.25rem 0 0;border-top:1px solid #f7f7f7;}

    .replay-operate{width:100%;margin-top: 0.1rem;background:rgba(255,255,255,1);}
    .faceList{height:0;overflow:hidden;position:relative;}
    .replay-operate .active{height:2.8rem; border-top: 1px solid #dedee0;}
    .faceList li{overflow:hidden;width:100%;padding:0.24rem;}
    .faceList div{float:left;width:16.666%;margin-bottom:0.1rem;text-align: center;}
    .faceList .swiper-pagination-bullet{background:#cecece;}
    .faceList .swiper-pagination-bullet-active{background:#999999;}
    .imgList {
        padding: 1rem 1rem 0 1rem;
        display: none;
    }

    .text-input-foot{position:fixed;z-index:1001;left:0;width:100%;bottom:0;padding:0.15rem 0;background:#fff;}
    .text-input{position:relative;padding:0 0.28rem;}
    .text-input input{text-indent:3px;margin-left:0.1rem;height:0.74rem;border:1px solid #e8e8e8;border-radius:3px;}
    .text-input input:focus{border:1px solid #3fb994;-webkit-box-shadow:0 0 2px #3fb994;transition:all .5s;}
    .text-input>div{position:absolute;right:1.54rem;text-align:center;top:1px;bottom:1px;background:#fafafa;z-index:100;width:10%;}
    .text-input i{font-size:0.45rem;color:#bdbdbd;}
    .text-input a{display:block;border-radius:3px;width:1rem;height:0.74rem;margin-left:0.24rem;background:#3fb994;color:#fff;text-align:center;line-height:0.64rem;}
    .fixed-height{height:1.2rem;}
    .worth-see{background:#fff;}
    .worth-see h3{padding:0.34rem 0.2rem 0.28rem;border-bottom:1px solid #efefef;}
    .worth-see h3 span{font-size:0.3rem;color:#3fb994;}
    .worth-see h3 img{width:0.34rem;}
    .worth-see h3 a{color:#999;font-size:0.2rem;}
    .worth-main{padding:0 0.2rem;}
    .worth-list{border-bottom:1px solid #f6f6f6;padding:0.28rem 0;}
    .worth-list span{font-size:0.26rem;color:#666;}
    .worth-list img{width:0.39rem;}

    .service-list{margin:0.2rem 0 0.1rem;background:#fff;}
    .service-list>div{position:relative;width:25%;padding:0.53rem 0 0.38rem;text-align:center;}
    .service-list a>img{margin-bottom:0.3rem;}
    .service-list>div:first-child a>img{width:0.97rem;}
    .service-list>div:nth-child(2) a>img{width:0.8rem;}
    .service-list>div:nth-child(3) a>img{width:0.86rem;}
    .service-list>div:nth-child(4) a>img{width:0.68rem;}
    .service-list>div div{position:absolute;left:0;width:100%;bottom:-2px;}
    .service-list>div div img{display:block;margin:0 auto;width:0.27rem;}
    .service-list h4{text-align:center;color:#999;}
    .service-list  a.hover h4{color:#2dd6b9;}
    .community-details{background:#fff;}
    .community-nav{border-bottom:1px solid #efefef;}
    .community-nav a{position:relative;width:25%;padding:0.36rem 0 0.32rem;text-align:center;color:#999;font-size:0.28rem;}
    .community-nav a div{position:absolute;left:0;width:100%;top:-0.13rem;text-align:center;}
    .community-nav a div img{width:0.27rem;display:block;margin:0 auto;}
    .community-nav a.hover{color:#ff7d1f;}
    .foot-nav{position:fixed;left:0;width:100%;bottom:0;z-index:100;background:#fff;padding:0.1rem 0;border-top:1px solid #ddd;}
    .foot-nav a{width:33.333%;text-align:center;}
    .foot-nav i{font-size:0.25rem;color:#999;}
    .foot-nav h4{margin-top:0.08rem;font-size:0.22rem;color:#999;}
    .foot-nav a.hover i,.foot-nav a.hover h4{color:#5ecaae;}
    .user-comment{position:fixed;bottom:15%;right:0.2rem;width:1.58rem;}
    .pop-bg{position:fixed;left:0;width:100%;height:100%;z-index:101;text-align:center;top:0;background:rgba(0,0,0,0.8) no-repeat center;}
    .welcome-main{position:relative;top:20%;display:inline-block;}
    .welcome-main>div{position:relative;display:inline-block;vertical-align:top;}
    .welcome-main>div img{width:3.1rem;}
    .welcome-main>div div{position:absolute;left:14%;bottom:27%;line-height:1.3;}
    .welcome-main>div{font-size:0.35rem;color:#fff;}
    .welcome-main>div span{color:#e2450d;}
    .welcome-main>img{width:3.55rem;margin-left:-0.3rem;vertical-align:top;}
    .welcome-main i{position:absolute;top:-5%;right:-5%;color:#fff;font-size:0.5rem;}
    .write {margin-right:0.16rem;color:#ff7277;font-size:0.25rem;}
    .send-info{position:absolute;left:6.15%;top:15%;width:87.7%;padding-top:0.45rem;border-radius:5px;background:#fff;}
    .send-info input{display:block;margin:0 5%;width:90%;height:0.8rem;text-indent:0.12rem;border:1px solid #f2f2f2;border-radius:5px;}
    .send-info p{margin:0.35rem 0 0.54rem;text-align:center;color:#666;font-size:0.3rem;}
    .send-tel p{margin:0.4rem 0 0.6rem;padding:0 0.45rem;color:#666;font-size:0.3rem;line-height:1.5;}
    .send-info div a{width:50%;height:1.12rem;line-height:1.12rem;text-align:center;font-size:0.3rem;color:#3fb994;}
    .send-info div{border-top:1px solid #efefef;}
    .send-info div a:first-child{border-right:1px solid #efefef;}
    .send-info h3{text-align:center;font-weight:bold;font-size:0.36rem;}
    .demand-time h4{color:#999;font-size:0.3rem;margin-bottom:0.08rem;}
    .demand-time h4 span{color:#4ee4c2;}
    .demand-time p{color:#4ee4c2;font-size:0.3rem;}
    .demand-time p span{color:#f86257;}
    .demand-btn a{display:inline-block;width:1.42rem;height:0.56rem;line-height:0.56rem;text-align:center;border:1px solid #dadada;color:#999;border-radius:5px;font-size:0.26rem;}
    .demand-btn a.hover{color:#f86257;border:1px solid #f86257;}
    .demand-btn a:first-child{margin-right:0.1rem;}
    .demand-btn-no a{background:#bbb;border:1px solid #bbb;color:#fff;}

    .vote2 header a{width:20%;}
    .release{position:relative;padding:0.26rem 0;background:#fff;border-bottom:1px solid #efefef;}
    .release a{display:inline-block;width:1.4rem;line-height:0.4rem;text-align:center;color:#999;font-size:0.28rem;}
    .release a:last-child{border-left:1px solid #efefef;}
    .release img{position:absolute;left:8%;top:-0.13rem;width:0.27rem;}
    .release .hover{color:#ff7d1f;}
    .comment-head h3 i.face{color:#fbeaa0;}
    .neighbor p{margin-top:0.23rem;color:#333;font-size:0.28rem;}
    .comment-head h3 .resolved-price{color:#999;}

    .questionnaire-head{position:relative;}
    .questionnaire-head div{position:absolute;top:4rem;left:0.5rem;right:0.5rem;text-align:center;}
    .questionnaire-head p{line-height:1.3;color:#b7fdef;}
    .questionnaire-head div img{width:2.15rem;margin-bottom:0.25rem;}
    .questionnaire{background:#fff;}
    .questionnaire>img{display:block;width:100%;}
    .questionnaire-main{}
    .questionnaire-model{padding:0.33rem 0 0.4rem 0.1rem;border-bottom:1px solid #f7f7f7;margin-bottom:0.07rem;}
    .questionnaire-model h3{font-size:0.28rem;color:#666;}
    .questionnaire-model h3 span{display:inline-block;width:5%;text-align:right;}
    .radio-model div{display:inline-block;vertical-align:middle;margin:0.25rem 0 0 0;padding-left:5%;width:41%;color:#999;}
    .radio-model div:nth-child(2n+2){margin-right:0;}
    .radio-model label{position:relative;display:block;}
    .radio-model input{position:absolute;left:0;top:0;width:100%;height:0.9rem;opacity:0;}
    .radio-model i{margin-right:0.12rem;font-size:0.3rem;color:#bababb;}
    .radio-model i:after{content:"\e72b";}
    .radio-model input:checked+i:after{content:"\e61e";color:#3fb994;}
    .radio-vote{display:block;margin:0.7rem auto 0.4rem;width:80%;height:0.8rem;text-align:center;line-height:0.8rem;color:#fff;background:#5ecaae;font-size:0.3rem;color:#fff;border-radius:5px;}
    .questionnaire-model:nth-child(3){border-bottom:0;}

    .personal-data{margin-top:0.2rem;background:#fff;}
    .data-list{position:relative;padding:0 0.2rem;border-bottom:1px solid #f7f7f7;}
    .data-name span{width:1.3rem;}
    .data-name  div{display:inline-block;margin-left:0.06rem;width:2.2rem;text-align:center;padding:0.06rem 0;background:#ebf7f1;border-radius:10px;font-size:0.2rem;color:#bbb;}
    .data-name  div i{font-size:0.2rem;color:#ff6a6f;margin-right:0.03rem;}
    .data-name label{}
    .data-list>input{font-size:0.28rem;text-align:right;}
    .data-list{height:0.85rem;}
    .data-list>div{display:inline-block;white-space:nowrap;}
    .data-list .label-switch {width: 0.54rem;height: 0.33rem;}
    .data-list .label-switch .checkbox {width: 0.54rem;height: 0.33rem;}
    .data-list .label-switch input[type="checkbox"]:checked + .checkbox:after {
        -webkit-transform: translateX(0.2rem);
        transform: translateX(0.2rem);
    }
    .data-list .label-switch .checkbox:before {
        width: 0.5rem;
        height: 0.29rem;
    }
    .data-list .label-switch .checkbox:after {
        content: ' ';
        height: 0.29rem;
        width: 0.29rem;
        border-radius: 0.29rem;
    }
    .data-code input:first-child{width:100%;padding-right:2rem;text-align:right;}
    .data-code input:last-child{position:absolute;right:0.2rem;top:0.135rem;width:1.7rem;height:0.58rem;border-radius:15px;line-height:0.58rem;text-align:center;background:#3fb994;color:#fff;font-size:0.24rem;}
    .data-list select{padding-right: 0.26rem;background:url('../images/right.png') no-repeat 95% center;background-size:0.13rem;}
    .id{margin-top:0.2rem;background:#fff;padding:0.37rem 0.25rem 0.4rem;}
    .id h3{margin-bottom:0.3rem;font-size:0.28rem;color:#666;}
    .id div{position:relative;width:3.55rem;height:2.22rem;background:url('../images/id-upload.png') no-repeat center;background-size:contain;}
    .id div input{position:absolute;left:0;top:0;width:100%;height:100%;opacity:0;z-index:100;}
    .certificate div{width:1.89rem;height:1.83rem;background:url('../images/upload-file1.png') no-repeat center;background-size:contain;}
    .hold a{display:inline-block;width:80%;height:0.84rem;line-height:0.84rem;text-align:center;background:#3fb994;color:#fff;border-radius:5px;}
    .hold{margin:0.5rem 0 0;text-align:center;padding-bottom:0.95rem;}
    .center-nav{margin-top:0.24rem;padding:0.5rem 0.18rem 0.4rem;background:#fff;}
    .center-nav a{position:relative;float:left;width:33.333%;text-align:center;padding:0.42rem 0;}
    .center-nav a h4{margin-top:0.14rem;color:#666;font-size:0.26rem;}
    .center-nav a img{height:0.43rem;}
    .center-nav a:after{content:'';position:absolute;right:0;bottom:0;height:100%;width:1px;background:#f7f7f7;}
    .center-nav a:before{content:'';position:absolute;left:0;bottom:0;width:100%;height:1px;background:#f7f7f7;}
    .center-nav a:nth-child(3n+3):after{content:'';position:absolute;right:0;top:0;height:100%;width:0;background:#f7f7f7;}
    .center-nav a:nth-child(7):before,.center-nav a:nth-child(8):before,.center-nav a:nth-child(9):before{content:'';position:absolute;left:0;bottom:0;width:0;height:1px;background:#f7f7f7;}
    .center-nav a:nth-child(1):after,.center-nav a:nth-child(2):after{content:'';position:absolute;right:0;bottom:0;height:90%;width:1px;background:#f7f7f7;}
    .center-nav a:nth-child(7):after,.center-nav a:nth-child(8):after{content:'';position:absolute;right:0;top:0;bottom:auto;height:90%;width:1px;background:#f7f7f7;}

    .property-evaluate{margin-top:0.2rem;padding:0.35rem 0 0.45rem 0.28rem;background:#fff;}
    .property-evaluate h3{margin-bottom:0.3rem;font-size:0.3rem;}
    .property-evaluate>div>div i{margin-right:0.1rem;}
    .property-evaluate>div>div:first-child{color:#f4c600;}
    .property-evaluate>div>div:first-child i{font-size:0.3rem;color:#f4c600;}
    .property-evaluate>div>div:last-child{margin-left:1.3rem;color:#333;}
    .property-evaluate>div>div:last-child i{font-size:0.3rem;color:#333;}
    .property-release{display:block;width:80%;height:0.8rem;margin:0.3rem auto 0.5rem;border-radius:5px;line-height:0.8rem;text-align:center;background:#3fb994;color:#fff;font-size:0.3rem;}
    .property-tips{display:block;width:100%;height:0.96rem;line-height:0.96rem;text-align:center;background:#fd7479;color:#fff;font-size:0.3rem;}

    .feedback{padding:0.16rem 0 0;margin:0.3rem -0.25rem 0;background:#f9f9f9;}
    .feedback-main{padding:0 0.25rem;}
    .feedback-main textarea{padding:0.1rem;width:100%;height:1.36rem;border:1px solid #ddd;border-radius:5px;}
    .feedback-main a{display:block;width:1.85rem;height:0.68rem;border:1px solid #c7c7c7;margin:0.3rem auto 0;line-height:0.68rem;text-align:center;border-radius:5px;color:#858585;font-size:0.28rem;}
    .feedback-solve{padding:0.34rem 0.75rem;margin-top:0.25rem;background:#fff;}
    .feedback-solve a{display:block;height:0.8rem;line-height:0.8rem;text-align:center;background:#3fb994;border-radius:3px;color:#fff;font-size:0.3rem;}
    .community-property .comment-title{padding: 0.28rem 0.25rem 0;}

    .service-area{margin-top:0.2rem;background:#fff;}
    .service-area h3{padding:0.25rem 0 0.25rem 0.25rem;font-size:0.3rem;font-weight:500;border-bottom:1px solid #efefef;}
    .service-area h3 span{margin-left:0.25rem;}
    .service-area h4{padding:0.25rem 0 0 0.25rem;font-weight:500;font-size:0.3rem;}
    .area-main{padding:0 0.25rem;}
    .area-main>div{height:0.78rem;border-bottom:1px solid #efefef;}
    .area-time{font-size:0.28rem;color:#999;}
    .area-time span{margin-left:0.25rem;}
    .area-main a{width:0.94rem;height:0.4rem;line-height:0.4rem;text-align:center;border-radius:3px;border:1px solid #f86257;font-size:0.26rem;color:#f86257;}
    .service-area>p{padding:0.28rem 0 0.28rem 0.25rem;font-size:0.3rem;}
    .publish-service{position:fixed;left:0;bottom:0;width:100%;padding:0.15rem 0;text-align:center;background:#fff;box-shadow:0 5px 5px -5px #d8d8d8;-webkit-box-shadow:0 5px 25px -5px #333;}
    .publish-service a{display:inline-block;width:3.16rem;height:0.68rem;line-height:0.68rem;text-align:center;background:#3fb994;border-radius:20px;color:#fff;font-size:0.3rem;}
    .service-input>div{height:0.88rem;margin-top:0.2rem;padding:0 0 0 0.25rem;background:#fff;}
    .service-input>div>span{width:1.2rem;font-size:0.3rem;}
    .service-input>div>input{margin-left:0.2rem;font-size:0.3rem;}
    .service-select>div{font-size:0.3rem;}
    .service-select>div span{display:inline-block;margin-left:0.12rem;font-size:0.2rem;background:#ebf7f1;border-radius:15px;padding:0.04rem 0.1rem;font-size:0.2rem;color:#bbb;}
    .service-select>div i{margin-right:0.1rem;color:#fd7579;font-size:0.2rem;}
    .service-select a{height:0.56rem;background:url('../images/right.png') no-repeat 95% center;background-size:0.13rem;}
    .apply {position:fixed;left:0;bottom:0;width:100%;height:1rem;background:#fff;line-height:1rem;}
    .apply  div{height:100%;padding-left:0.25rem;font-size:0.3rem;border-top:1px solid #ddd;}
    .apply span{color:#ff423e;}
    .apply a{width:2.45rem;height:100%;text-align:center;background:#f86257;color:#fff;font-size:0.3rem;line-height:1rem;}
    .service-txt h3{margin-left:0.25rem;padding:0.3rem 0;font-size:0.3rem;border-bottom:1px solid #f5f5f5;}
    .service-txt div{padding:0.26rem 0 0.1rem 0;font-size:0;color:#666;border-top:1px solid #f5f5f5;}
    .service-input>div.service-txt{margin-top:0;height:auto;}
    .service-txt div span{display:inline-block;width:50%;margin-bottom:0.22rem;font-size:0.3rem;}
    .area-search-list{position:relative;height:0.9rem;padding-left:0.3rem;border-bottom:1px solid #f5f5f5;font-size:0.3rem;}
    .area-search-list input{position:absolute;left:0;top:0;width:100%;height:100%;opacity:0;z-index:100;}
    .area-search-list i{margin-right:0.1rem;font-size:0.3rem;color:#f86257;}
    .area-search-list span{font-size:0.3rem;}
    .area-search-list i:after{content:'\e625';}
    .area-search-list input:checked+i:after{content:'\e650';}
    .area-search-list input:checked~span{color:#f86257;}
    .area-search-list:last-child{border-bottom:0;}
    .area-preservation{display:block;width:4.8rem;height:0.8rem;margin:0.85rem auto 0;line-height:0.8rem;text-align:center;border-radius:5px;border:1px solid #3fb994;color:#3fb994;font-size:0.3rem;}
    .area-preservation:active{background:#3fb994;color:#fff;}
    .zan{margin-top:0.2rem;color:#cacaca;font-size:0.24rem;}
    .zan i{margin-right:0.06rem;font-size:0.2rem;}
    .icon-dianhua1{margin-left:0.28rem;}

    .vote-shop header a{width:25%;}
    .shop-banner .swiper-slide{width:100%;height:3.96rem;}
    .swiper-pagination-bullet-active{background: #fff;}
    .shop-goods{margin-top:0.2rem;background:#fff;}
    .shop-img{height:3.97rem;border-bottom:1px solid #f5f5f5;}
    .shop-goods>h3{padding:0.22rem 0 0.13rem 0.25rem;color:#535d69;font-size:0.26rem;}
    .shop-data{padding:0 0.25rem 0.3rem;}
    .shop-data span{width:1.6rem;text-align:center;color:#999;}
    .shop-data a{width:1.64rem;height:0.55rem;line-height:0.55rem;text-align:center;background:#f86257;border-radius:5px;color:#fff;line-height:0.55rem;}
    .shop-data div{color:#ff423e;font-size:0.32rem;}
    .shop-data div i{font-size:0.24rem;font-style:normal;}
    .shop-data div span{color:#999;font-size:0.24rem;margin-left:0.12rem;text-decoration:line-through;}

    .goods-wrap{margin-top:0.2rem;padding:0.2rem;background:#fff;}
    .goods-wrap>div:first-child{width:1.48rem;height:1.48rem;}
    .goods-wrap>div:last-child{margin-left:0.1rem;}
    .goods-wrap h3{color:#535d69;font-size:0.3rem;margin-bottom:0.23rem;overflow:hidden;line-height:0.35rem;height:0.35rem;}
    .goods-data div span:first-child{color:#ff423e;font-size:0.38rem;}
    .goods-data div span:first-child i{font-size:0.28rem;font-style:normal;}
    .goods-data div span:last-child{font-size:0.26rem;}
    .pay-type{margin-top:0.2rem;padding:0 0.25rem;height:1rem;background:#fff;}
    .pay-type span{font-size:0.28rem;}
    .pay-type span:first-child{color:#3fb994;}
    .pay-type span:last-child{color:#666;margin-left:0.15rem;}

    .remarks{margin-top:0.2rem;background:#fff;}
    .remarks h3{padding:0.22rem 0.25rem;color:#3fb994;border-bottom:1px solid #f5f5f5;}
    .remarks textarea{width:100%;padding:0.2rem;height:1.46rem;}
    .delivery-mode{margin-top:0.2rem;background:#fff;}
    .delivery-mode h3{padding:0.25rem;color:#3fb994;font-size:0.28rem;border-bottom:1px solid #f5f5f5;}
    .layout{height:0.86rem;padding:0 0.25rem;}
    .layout span{font-size:0.28rem;color:#666;}
    .pick-goods div{position:relative;width:0.25rem;height:0.25rem;}
    .pick-goods div input{position:absolute;left:0;top:0;width:100%;height:100%;opacity:0;z-index:100;}
    .pick-goods div i{font-size:0.3rem;color:#3fb994;}
    .pick-goods div i:before{content:'\e72b';}
    .pick-goods div input:checked+i:before{content:'\e61e';}
    .since{background:#f9f9f9;}
    .since>div{border-bottom:1px solid #f0f0f0;}
    .since>div select{padding-right:0.2rem;background:url('../images/right.png') no-repeat right center;background-size:0.13rem;}
    .door{background:#f9f9f9;}
    .door-tips{background:#fff;padding:0 0 0.2rem 0.25rem;}
    .door-tips div{color:#bbb;font-size:0.2rem;background:#ebf7f1;padding:0.06rem 0.12rem;border-radius:10px;}
    .door-tips div i{font-size:0.2rem;color:#f79d9e;margin-right:0.05rem;;}
    .door input,.door textarea{text-align:right;font-size:0.28rem;}
    .door>div{border-bottom: 1px solid #f0f0f0;}
    .door-textarea{padding:0.2rem 0.25rem;}
    .door-textarea sapn{font-size:0.28rem;color:#666;}
    .door-textarea{height:}
    .area input{padding-right:0.2rem;background:url('../images/right.png') no-repeat right center;background-size:0.13rem;}
    .apply .total{color:#999;font-size:0.24rem;}
    .apply .total i{color:#ff423e;font-style:normal;font-size:0.24rem;}
    .apply .total span{margin-left:0.1rem;color:#ff423e;font-size:0.32rem;}

    .send-banner .swiper-slide{height:5.85rem;}
    .send-banner .swiper-pagination-bullet-active {background: #989999;}
    .send-goods{padding:0.2rem 0.25rem 0.4rem;background:#fff;}
    .send-goods h3{margin-bottom:0.2rem;font-size:0.3rem;color:#535d69;}
    .send-goods-data{color:#ff423e;font-size:0.32rem;}
    .send-goods-data  i{font-size:0.24rem;font-style:normal;}
    .send-goods-data  span{color:#999;font-size:0.24rem;margin-left:0.28rem;text-decoration:line-through;}
    .send-goods-price span{color:#999;font-size:0.24rem;}
    .send-goods-price span:first-child{margin-right:0.4rem;}
    .baby-description{margin-top:0.2rem;padding: 0 0.2rem;background:#fff;}
    .baby-description nav{margin-bottom:0.16rem;padding:0.2rem 0 0.2rem 0;border-bottom:1px solid #f5f5f5;}
    .baby-description nav a{padding:0 0.2rem;font-size:0.3rem;color:#535d69;border-right:1px solid #f5f5f5;}
    .baby-description nav a.hover{color:#3fb994;}
    .baby-description nav a:last-child{border-right:0;}
    .buy{position:fixed;left:0;width:100%;bottom:0;height:0.98rem;background:#fff;}
    .buy a{width:2.43rem;height:0.98rem;background:#f86257;text-align:center;font-size:0.3rem;line-height:0.98rem;color:#fff;background:;}
    .buy div{height:0.98rem;line-height:0.98rem;border-top:1px solid #d3d3d3;padding-left:0.25rem;}
    .buy div i.hover{;color:#999;}
    .buy div i{font-size:0.5rem;color:#ebebeb;}
    .buy div span{color:#f86257;}
    .buy div span:last-child{font-size:0.2rem;margin-left:0.22rem;}
    .buy-num{margin:0 0.25rem;font-size:0.38rem;}
    .icon-jia{margin-top:0.04rem;}
    .purchase-user{margin-top:0.2rem;}
    .purchase-user li{float:left;width:22%;margin:0 4% 0.5rem 0;}
    .purchase-user li div{width:100%;}
    .purchase-user li div:after{content:'';padding-top:100%;display:block;}
    .purchase-user li  h4{text-align:center;margin-top:0.12rem;color:#999;}
    .purchase-user li:nth-child(4n+4){margin-right:0;}

    .baby-evaluation>div:first-child{width:0.86rem;height:0.86rem;border-radius:100%;}
    .baby-evaluation>div:last-child{margin-left:0.15rem;}
    .star-num div span:first-child{font-size:0.26rem;color:#3fb994;}
    .star-num>span{color:#bbb;font-size:0.2rem;}
    .evaluation-star{margin-left:0.22rem;}
    .evaluation-star img{width:0.22rem;margin-right:0.04rem;}
    .evaluation-img{margin:0.12rem 0 0.16rem;}
    .evaluation-img div {width:1.5rem;height:1.5rem;float: left;margin-right: 0.22rem;}
    .baby-evaluation h3{margin:0.2rem 0;}
    .baby-evaluation{padding:0.2rem 0;border-bottom:1px solid #f5f5f5;}

    .keys-search span{position:absolute;right:0.22rem;top:50%;margin-top:-0.2rem;line-height:0.4rem;color:#fff;font-size:0.3rem;}
    .keys-search div{position:relative;display:inline-block;}
    .keys-search div input{height:0.55rem;padding-left:0.6rem;background:#fff;border-radius:15px;}
    .keys-search div i{position:absolute;left:0.15rem;top:50%;margin-top:-0.15rem;font-size:0.3rem;color:#c2c2c2;}
    .keys-banner{width:100%;}
    .community-search{position:relative;border-bottom:1px solid #efefef;}
    .community-search a{width:20%;padding:0.36rem 0 0.32rem;text-align:center;color:#999;font-size:0.28rem;}
    .community-search div{position:absolute;left:35.5%;display:inline-block;top:-0.13rem;text-align:center;}
    .community-search div img{width:0.27rem;display:block;margin:0 auto;}
    .community-search a.hover{color:#ff7d1f;}
    .concact{padding:0 0.2rem;}
    .concact>div{height:1.28rem;border-bottom:1px solid #f5f5f5;}
    .concact>div a{width:1.4rem;height:0.56rem;text-align:center;line-height:0.56rem;border-radius:5px;border:1px solid #f86257;color:#f86257;}
    .concact>div a:active{background:#f86257;color:#fff;}
    .concact-main h3{margin-bottom:0.14rem;font-size:0.3rem;}
    .concact-icon{font-size:0;}
    .concact-icon i{margin-right:0.1rem;color:#bbb;font-size:0.2rem;}
    .concact-icon span{color:#bbb;font-size:0.2rem;}
    .concact-icon .icon-shouye{margin-left:0.4rem;}

    .reservation{background:#fff;}
    .reservation h3{padding:0.2rem 0.25rem;font-size:0.28rem;border-bottom:1px solid #d2d2d2;color:#ff7d1f;}
    .reservation-main{padding-left:0.25rem;}
    .reservation-main>div{padding:0.2rem 0;border-bottom:1px solid #f5f5f5;}
    .reservation-main h4{margin-bottom:0.18rem;font-size:0.3rem;}
    .reservation-main p{font-size:0.22rem;color:#999;}






    .toupiao_tiankong{
        width: 90%;
        height: 30px;
        margin: 0 auto;
        border: 1px solid #dedede;
        border-radius: 3px;
        margin-top: 5px;
        padding-left: 4px;
    }
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
                <h3><span>{{ $k + 1 }}.</span>{{ $vo -> title }}@if($vo -> type == 1) (单选题) @else (多选题) @endif</h3>
                <div class="radio-model">
                    @foreach($vo -> detail as $vol)
                    <div class="flex-align"><label><input @if($vo -> type == 1)type="radio"@else type="checkbox" @endif name="{{ $vo -> id }}" class="inputs" value="{{ $vol -> id }}" ><i class="iconfont"></i>{{ $vol -> name }}</label></div>
                    @endforeach
                </div>
            </div>
            @endforeach
                @if($tiankongtis)
                    @foreach($tiankongtis as $k => $vo)
                    <div class="questionnaire-model">
                        <h3><span></span>{{ $vo -> title }}</h3>
                        <input type="text" class="toupiao_tiankong" name="tiankong"/>
                    </div>
                    @endforeach
                @endif

            <p style="padding:10px;text-indent: 2em">{!! $toupiaodetail -> jieshu !!}</p>
            <a  class="radio-vote" id="newtoupiao">投票</a>
        </div>
    </form>
    <img src="{{asset('images/questionnaire-bottom.png')}}" />
</article>
<!-- 提交到签名 -->
<form method="post" action="{{ url('home/toupiaosign').'/id/'.$id }}" id="superform">
    <input type="hidden" name="data" id="formdata"/>
    <input type="hidden" name="tiankongdata" id="tiankongdata"/>
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

            //填空题
            var tiankong_length = $('.toupiao_tiankong').length;
            var tiankongs = '';
            if(tiankong_length > 0){
                for(var i=0;i<tiankong_length;i++){
                    //获取每道填空题
                    if(!$.trim($('.toupiao_tiankong').eq(i).val())){
                        layer.msg('请填写全部');return false;
                    }
                    tiankongs = tiankongs + $.trim($('.toupiao_tiankong').eq(i).val()) + '&&';
                }
                tiankongs = tiankongs.substr(0,tiankongs.length - 2);
            }


            //判断下是否需要签名
            @if($toupiaodetail -> is_sign)
                //赋值给form input
                $('#formdata').val(JSON.stringify(arr));
                $('#tiankongdata').val(tiankongs);
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
                data: {json:data,tiankongdata:tiankongs},

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


