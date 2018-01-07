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
  <?php
    // 根据不同页面设置不同的标题、关键词、描述
    $title = get_bloginfo('name');
    $keywords = wp_get_post_tags($post->ID);
    if ( is_single() ) {
      if ($keywords) {
        for ($i=0; $i < count($keywords); $i++) {
          $key[$i] = $keywords[$i]->name;
        }
        $key = implode(',', $key);
      }
      $currentTitle = get_the_title();
      $description = get_post_excerpt(200, '');
    } else if( is_category() ) {
      $currentTitle = get_category(get_query_var('cat'))->name;
      $key = get_option('xm_options')['keywords'];
      $description = get_option('xm_options')['description'];
    } else {
      $currentTitle = get_bloginfo('description');
      $key = get_option('xm_options')['keywords'];
      $description = get_option('xm_options')['description'];
    }
  ?>
  <title><?php echo $title . ' | ' . $currentTitle; ?></title>
  <meta name="keywords" content="<?php echo $key; ?>">
  <meta name="description" content="<?php echo $description; ?>" >
  <?php wp_head(); ?>
  <link rel="icon" href="<?php echo get_option('xm_options')['favicon']; ?>">
  <link rel="stylesheet" href="https://upyun.xuanmo.xin/iconfont.css">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style.css">
  <script src="<?php bloginfo('template_url'); ?>/js/jquery-2.1.4.min.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/js/common.js"></script>
  <?php
  $templateUrl = get_template_directory_uri();
  $xm_options = get_option('xm_options');
  if (!empty($xm_options['home_css'])) $home_css = '<style>' . $xm_options['home_css'] . '</style>';
  if(is_home()) {
    echo '
      <link rel="stylesheet" href="' . $templateUrl . '/css/index.css">
      <style>
        .hide-header{ display: none; }
        ul.menu > li ul.sub-menu{ background: rgba(210, 210, 210, 0.6); }
        .time .iconfont{ margin: 0 2px 0 5px; }
      </style>
      ' . $home_css . '
      <script src="//upyun.xuanmo.xin/js/jquery.seamlessBanner.min.js"></script>
    ';
  } else if(is_category() ) {
    echo '
      <link rel="stylesheet" href="' . $templateUrl . '/css/list.css">
    ';
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
      <link rel="stylesheet" href="' . $templateUrl . '/css/article.css">
      <script src="https://upyun.xuanmo.xin/js/ajax.min.js"></script>
      <style>
        body{ background: #fff; }
        header { background: rgba(40, 40, 40, 0.7); }
        section{ background: #fff; }
        section h2{ margin: 20px 0; padding-left: 10px; background: #f5f5f5; border-left: 5px solid #0cf; font-size: 18px; line-height: 40px; color: #292929; }
      </style>
    ';
  } else if(is_single()) {
    echo '
      <link rel="stylesheet" href="' . $templateUrl . '/css/article.css">
      <link rel="stylesheet" href="' . $templateUrl . '/css/prism.css">
      <script src="' . $templateUrl . '/js/qrcode.js"></script>
      <script src="' . $templateUrl . '/js/prism.js"></script>
      <script src="https://upyun.xuanmo.xin/js/ajax.min.js"></script>
    ';
  }
  ?>
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/mobile.css">
  <?php
  if (!empty($xm_options['all_css'])) echo '<style>' . $xm_options['all_css'] . '</style>';
  ?>
</head>
<body>
  <!-- header start -->
  <div class="hide-header"></div>
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
        <a href="<?php echo get_the_author_meta('github_url', 1); ?>" class="mobile-hide iconfont icon-github1" target="_blank"></a>
        <a href="<?php echo get_the_author_meta('qq', 1); ?>" class="mobile-hide iconfont icon-qq" target="_blank"></a>
        <a href="javascript:;" class="mobile-hide iconfont icon-wechat">
          <span>
            <img src="<?php echo get_the_author_meta('wechat_img', 1); ?>" alt="扫一扫 加博主微信" />
          </span>
        </a>
        <a href="<?php echo get_the_author_meta('sina_url', 1); ?>" class="mobile-hide iconfont icon-sina" target="_blank"></a>
        <a href="mailto:<?php echo get_the_author_meta('user_email', 1); ?>?subject=Hello <?php echo get_the_author_meta('display_name', 1); ?>" class="mobile-hide iconfont icon-email2"></a>
        <a href="javascript:;" class="pc-none iconfont icon-menu-list2"></a>
      </div>
      <nav class="fl clearfix">
        <i class="iconfont icon-menu-left"></i>
        <?php wp_nav_menu( array( 'depth' => 0) ); ?>
      </nav>
    </div>
  </header>
  <!-- header end -->
