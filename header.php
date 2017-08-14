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
    <meta http-equiv="X-UA-Compatible" content="ie=edge, chrome=1">
    <title>
        <?php 
        if (is_single()) {
            the_title();
        } else if (is_category()) {
            echo get_category(get_query_var('cat'))->name;
        } else {
            bloginfo('description');
        }
        ?>
        | <?php bloginfo('name'); ?>
    </title>
    <meta name="keywords" content="<?php echo get_option('xm_options')['keywords']; ?>">
    <meta name="description" content="<?php echo get_option('xm_options')['description']; ?>" >
    <?php wp_head(); ?>
    <link rel="icon" href="<?php echo get_option('xm_options')['favicon']; ?>">
    <link rel="stylesheet" href="https://www.xuanmo.xin/iconfont.css">
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style.css">
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/mobile.css">
    <script src="<?php bloginfo('template_url'); ?>/js/jquery-2.1.4.min.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/common.js"></script>
    <?php 
    $templateUrl = get_template_directory_uri();
    if(is_home()) {
        echo '
            <link rel="stylesheet" href="' . $templateUrl . '/css/index.css">
            <style>
                ul.menu > li ul.sub-menu{ background: rgba(210, 210, 210, 0.6); }
            </style>
            ';
    } else if(is_category() ) {
        echo '<link rel="stylesheet" href="' . $templateUrl . '/css/list.css">';
    } else if(is_search() || is_author() || is_tag() || is_404()) {
        echo '
            <link rel="stylesheet" href="' . $templateUrl . '/css/index.css">
            <style>
                header { background: rgba(40, 40, 40, 0.7); }
            </style>
        ';
    } else if(is_page()) {
        echo '
            <link rel="stylesheet" href="' . $templateUrl . '/css/index.css">
            <style>
                header { background: rgba(40, 40, 40, 0.7); }
                a{ display: inline-block; padding: 0 5px; }
                a img{ display: block; width: 32px; height: 32px; margin: 3px auto; border-radius: 8px; }
                section{ background: #fff; }
                section h2{ margin: 20px 0; padding-left: 10px; background: #f5f5f5; border-left: 5px solid #0cf; font-size: 18px; line-height: 40px; color: #292929; }
            </style>
        ';
    } else if(is_single()) {
        echo '
            <link rel="stylesheet" href="' . $templateUrl . '/css/article.css">
            <script src="' . $templateUrl . '/js/qrcode.js"></script>
        ';
    }
    ?>
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
                <a href="<?php echo get_option('xm_options')['github_url']; ?>" class="mobile-hide iconfont icon-github1" target="_blank"></a>
                <a href="<?php echo get_option('xm_options')['qq_url']; ?>" class="mobile-hide iconfont icon-qq" target="_blank"></a>
                <a href="javascript:;" class="mobile-hide iconfont icon-wechat">
                    <span>
                        <img src="<?php echo get_option('xm_options')['wechat_img']; ?>" alt="扫一扫 加博主微信" />
                    </span>
                </a>
                <a href="<?php echo get_option('xm_options')['sina_url']; ?>" class="mobile-hide iconfont icon-sina" target="_blank"></a>
                <a href="mailto:<?php echo get_option('xm_options')['email']; ?>?subject=Hello <?php echo bloginfo('name'); ?>" class="mobile-hide iconfont icon-email2"></a>
                <a href="javascript:;" class="pc-none iconfont icon-menu-list2"></a>
            </div>
            <nav class="fl clearfix">
                <?php wp_nav_menu( array( 'depth' => 0) ); ?>
            </nav>
        </div>
    </header>
    <!-- header end -->
