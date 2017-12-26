<?php
/*
Template Name: 站点地图
*/
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
    <title>站点地图 - <?php bloginfo('name'); ?></title>
    <meta name="keywords" content="站点地图,<?php bloginfo('name'); ?>" />
    <meta name="copyright" content="<?php bloginfo('name'); ?>" />
    <link rel="canonical" href="<?php echo get_permalink(); ?>" />
    <style type="text/css">
      body { margin:0 auto; font-size:14px; font-family: "PingFangSC-Regular", Microsoft Yahei;width: 1100px; }
      a:link, a:visited { text-decoration:none; color:#000; }
      a:hover { text-decoration:none; color:#08d; }
      h1, h2, h3, h4, h5, h6 { font-weight:normal; }
      img { border: 0; }
      li { margin-top: 8px; }
      .page { padding: 4px; border-top: 1px solid #eee; }
      .author { padding: 6px; border-top: 1px solid #ddddee; background-color:#EEEEFF; }
      #nav, #content, #footer { width: 95%; margin: 10px auto 0; padding: 8px; border: 1px solid #eee; clear: both;}
    </style>
  </head>
  <body>
  <h2 style="margin-top: 20px; text-align: center;"><?php bloginfo('name'); ?> SiteMap </h2>
  <div id="nav"><a href="<?php bloginfo('url'); ?>/"><strong><?php bloginfo('name'); ?></strong></a> &raquo; <a href="<?php echo get_permalink(); ?>">站点地图</a></div>
  <div id="content">
    <h3>所有文章</h3>
    <ul>
      <?php
      $previous_year = $year = 0;
      $previous_month = $month = 0;
      $ul_open = false;
      $myposts = get_posts('numberposts=-1&orderby=post_date&order=DESC');
      foreach ($myposts as $post) :
      ?>
      <li>
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
          <?php the_title(); ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div id="content">
    <ul>
      <li class="categories">分类目录<ul>
        <?php wp_list_categories('title_li='); ?>
      </li>
    </ul>
  </div>
  <div id="content">
    <div class="categories">单页面</div>
    <?php wp_page_menu(); ?>
  </div>
  <div id="footer">
    查看博客首页: <strong><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></strong>
  </div>
  <center>
    <div style="margin-top: 30px;font-size: 12px; text-algin: center;">
      <strong>Baidu-SiteMap</strong>
      Latest Update:
      <?php
        $last = $wpdb->get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')");
        $last = date('Y-m-d G:i:s', strtotime($last[0]->MAX_m));
        echo $last;
      ?>
    </div>
  </center>
  </body>
</html>
