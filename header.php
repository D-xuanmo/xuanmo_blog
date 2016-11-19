<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" >
  <meta name="keywords" content="<?php echo get_option('x_keywords'); ?>">
  <meta name="description" content="<?php echo get_option('x_description'); ?>" >
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php bloginfo('name'); ?> | <?php bloginfo('description'); ?></title>
  <link rel="stylesheet" href="http://xuanmomo.com/iconfont.css">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/common.css">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/style.css">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/mobile.css">
  <?php wp_head(); ?>
  <script src="<?php bloginfo('template_url'); ?>/js/jquery-2.1.4.min.js"></script>
  <script>
    var isIE = !!window.ActiveXObject;
    var isIE6 = isIE &&! window.XMLHttpRequest;
    var isIE8 = isIE &&!! document.documentMode;
    var isIE7 = isIE &&! isIE6 &&! isIE8;
    if (isIE){
      if (isIE6 || isIE7 || isIE8){
        alert('您当前浏览器版本太低，请更换浏览器！');
      }
    }
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
  <style>
    ul.menu > li ul.sub-menu{ background: rgba(210, 210, 210, 0.6); }
  </style>

</head>
<body>
  <!-- header start -->
  <header>
    <!-- 搜索栏 -->
    <form id="searchform" class="search" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
      <div class="search-box">
        <input type="text" class="search-txt" name="s" id="s" value="" placeholder="请输入文字...">
        <!-- <input type="submit" id="search-submit" class="search-btn" value="搜&nbsp;索"> -->
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
        <a href="<?php echo stripslashes(get_option('x_github')); ?>" class="mobile-hide iconfont icon-github1"></a>
        <a href="<?php echo stripslashes(get_option('x_t_qq')); ?>" class="mobile-hide iconfont icon-qq"></a>
        <a href="javascript:;" class="mobile-hide iconfont icon-wechat">
          <span>
            <img src="<?php echo stripslashes(get_option('x_wechats')); ?>" alt="扫一扫 加博主微信" />
          </span>
        </a>
        <a href="<?php echo stripslashes(get_option('x_sinas')); ?>" class="mobile-hide iconfont icon-sina"></a>
        <a href="mailto:<?php echo stripslashes(get_option('x_email')); ?>?subject=Hello <?php echo bloginfo('name'); ?>" class="mobile-hide iconfont icon-email2"></a>
        <a href="javascript:;" class="pc-none iconfont icon-menu-list2"></a>
      </div>
      <nav class="fl clearfix">
        <?php wp_nav_menu( array( 'depth' => 0) ); ?>
      </nav>
    </div>
  </header>
  <!-- header end -->
