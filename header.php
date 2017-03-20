<!DOCTYPE html>
<html lang="en">
<!--
　　 へ　　　　　／|
　　/＼7　　∠＿/
　 /　│　　 ／　／
　│　Z ＿,＜　／　　 /`ヽ
　│　　　　　ヽ　　 /　　〉
　 Y　　　　　`　 /　　/
　ｲ●　､　●　　⊂⊃〈　　/
　()　 へ　　　　|　＼〈
　　>ｰ ､_　 ィ　 │ ／／
　 / へ　　 /　ﾉ＜| ＼＼
　 ヽ_ﾉ　　(_／　 │／／
　　7　　　　　　　|／
　　＞―r￣￣`ｰ―＿
皮卡丘,对偷看者使用十万伏特
-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" >
    <meta name="keywords" content="<?php echo get_option('x_keywords'); ?>">
    <meta name="description" content="<?php echo get_option('x_description'); ?>" >
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php
        if( is_single() ){
    ?>
            <title><?php the_title(); ?> | <?php bloginfo('name'); ?></title>
    <?php
        }else{
    ?>
            <title><?php bloginfo('name'); ?> | <?php bloginfo('description'); ?></title>
    <?php
        }
    ?>
    <script type="text/javascript">
        if(navigator.appName == "Microsoft Internet Explorer" ) {
            if( (navigator.appVersion.match(/MSIE 8/g) == "MSIE 8") || (navigator.appVersion.match(/MSIE 7/g) == "MSIE 7") ){
                setInterval(function(){
                    alert("您当前浏览器版本太低，请您更换浏览器！");
                },1000);
            }
        }
    </script>
    <?php wp_head(); ?>
    <link rel="stylesheet" href="http://xuanmomo.com/iconfont.css">
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/common.css">
    <?php
        if( is_home() ){
    ?>
            <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/style.css">
            <style>
                ul.menu > li ul.sub-menu{ background: rgba(210, 210, 210, 0.6); }
            </style>
    <?php
        }else if( is_category() ){
    ?>
            <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/list.css">
    <?php
        }else if( is_search() || is_author() || is_tag() || is_404() ){
    ?>
            <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/style.css">
            <style>
                header { background: rgba(40, 40, 40, 0.7); }
            </style>
    <?php
        }else if( is_page() ){
    ?>
            <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/style.css">
            <style>
                header { background: rgba(40, 40, 40, 0.7); }
                a{ display: inline-block; padding: 0 5px; }
                a img{ display: block; width: 32px; height: 32px; margin: 3px auto; border-radius: 8px; }
                section{ background: #fff; }
                section h2{ margin: 20px 0; padding-left: 10px; background: #f5f5f5; border-left: 5px solid #0cf; font-size: 18px; line-height: 40px; color: #292929; }
            </style>
    <?php
        }
    ?>
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/mobile.css">
    <script src="<?php bloginfo('template_url'); ?>/js/jquery-2.1.4.min.js"></script>
    <?php
        if( is_single() ) {
    ?>
            <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/article.css">
            <script src="<?php bloginfo('template_url'); ?>/js/qrcode.js"></script>
            <script>
                $(document).ready(function(){
                    var keywordsArr = [];
                    // 表情移动到form标签里面
                    $('p.smiley').insertBefore($('p.comment-form-comment'));
                    // 删除评论框
                    $('#respond form p.comment-form-comment label').remove();
                    $('.form-submit input:eq(0)')
                    // 插入验证码的输入框
                    .before('\
                        <label for="number-verification">输入验证码：</label>\
                        <input type="text" value="" class="num-verification" disabled> +\
                        <input type="text" value="" class="num-verification" disabled> =\
                        <input type="text" value="" id="number-verification" class="num-verification">\
                        ')
                    .siblings('.num-verification').css({
                        'width' : '40px',
                        'margin-bottom' : '10px',
                        'text-align' : 'center'
                    })
                    .siblings('input[type="submit"]').attr('disabled','disabled');
                    var $verification = $('.num-verification');
                    $verification.eq(0).val( Math.floor( Math.random() * 15 + 1 ) );
                    $verification.eq(1).val( Math.floor( Math.random() * 15 + 1 ) );
                    $verification.eq(2).keyup(function(){
                        if( Number( $(this).val() ) == Number( $verification.eq(0).val() ) + Number( $verification.eq(1).val() ) ){
                            $(this).css('border-color','#32e4af')
                            $('.form-submit input[type="submit"]').removeAttr('disabled');
                        }else{
                            $(this).css('border-color','#f00');
                            $('.form-submit input[type="submit"]').attr('disabled','disabled');
                        }
                    });
                    // 文章二维码
                    var $qrcode = $('#qrcode');
                    var qrcode = new QRCode(document.getElementById('qrcode') , {
                        'width' : 150,
                        'height' : 150
                    });
    <?php
        if(have_posts()) :  while(have_posts()) : the_post();
    ?>
                    qrcode.makeCode('<?php the_permalink(); ?>');
    <?php
        endwhile; endif;
    ?>
                    $('#qrcode img').after($('#qrcode i'));
                    $('.wechat').click(function(){
                        $qrcode.css('display','block');
                    });
                    $('#qrcode .icon-close1').click(function(e){
                        e.stopPropagation();
                        $qrcode.css('display','none');
                    });
                    $('p.mark a').each(function(){
                        keywordsArr.push( $(this).text() );
                        $('meta[name="keywords"]').attr('content' , keywordsArr.join(','));
                    });
                    $('meta[name="description"]').attr({
                        "content" : function(){
                            return $('div.content').text().substring(0,150).replace(/(\/|\.|:|\u38142)+|\s/g , '');
                        }
                    });
                });
                // 输入表情
                function grin( str ){
                    $('#comment').val(function(){
                        return $(this).val() + ' ' + str + ' ';
                    });
                }
            </script>
    <?php
        }
    ?>
    <script>
        // 点赞功能
        $(document).ready(function() {
            $.fn.postLike = function() {
            	if ($(this).hasClass('done')) {
                    alert('^_^您已赞过此文章了');
                    return false;
            	} else {
            		$(this).addClass('done');
            		var id = $(this).data("id"),
            		action = $(this).data('action'),
            		rateHolder = $(this).children('.count');
            		var ajax_data = {
            			action: "bigfa_like",
            			um_id: id,
            			um_action: action
            		};
            		$.post("<?php bloginfo('url');?>/wp-admin/admin-ajax.php", ajax_data, function(data) {
            			$(rateHolder).html(data);
            		});
            		return false;
            	}
            };
            $(document).on("click", ".favorite", function() {
                $(this).postLike();
            });
        });
    </script>
</head>
<body>
    <!-- header start -->
    <header>
        <!-- 搜索栏 -->
        <form id="searchform" class="search" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <div class="search-box">
                <input type="text" class="search-txt" name="s" id="s" value="" placeholder="请输入文字...">
            </div>
            <span class="iconfont icon-close1"></span>
        </form>
        <!-- 导航区 -->
        <div class="head clearfix">
            <strong class="fl logo">
                <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a>
            </strong>
            <!-- 联系我 -->
            <div class="fr contact">
                <a href="javascript:;" class="iconfont icon-search"></a>
                <a href="<?php echo stripslashes(get_option('x_github')); ?>" class="mobile-hide iconfont icon-github1" target="_blank"></a>
                <a href="<?php echo stripslashes(get_option('x_t_qq')); ?>" class="mobile-hide iconfont icon-qq" target="_blank"></a>
                <a href="javascript:;" class="mobile-hide iconfont icon-wechat">
                    <span>
                        <img src="<?php echo stripslashes(get_option('x_wechats')); ?>" alt="扫一扫 加博主微信" />
                    </span>
                </a>
                <a href="<?php echo stripslashes(get_option('x_sinas')); ?>" class="mobile-hide iconfont icon-sina" target="_blank"></a>
                <a href="mailto:<?php echo stripslashes(get_option('x_email')); ?>?subject=Hello <?php echo bloginfo('name'); ?>" class="mobile-hide iconfont icon-email2"></a>
                <a href="javascript:;" class="pc-none iconfont icon-menu-list2"></a>
            </div>
            <nav class="fl clearfix">
                <?php wp_nav_menu( array( 'depth' => 0) ); ?>
            </nav>
        </div>
    </header>
    <!-- header end -->
