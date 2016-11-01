<?php include('header-page.php'); ?>
<section style="background:#fff">
  <div class="wrap clearfix">
    <!-- <div class="mobile-hide fl" style="width: 25%;">
      <?php //get_sidebar(); ?>
    </div> -->
    <div class="mobile-article-lg fr right" style="width: 100%; padding-top: 14px;">
      <?php
        $page_id = 297;
        $page_data = get_page( $page_id );
        //必须传递个变量. 如果传递值 (e.g. get_page ( 123 ); ), WordPress会报错. 默认将会返回一个对象.
        echo '<h3 style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #ccc;">'. $page_data->post_title .'</h3>';// 输出标题
        echo apply_filters('the_content', $page_data->post_content); //输出内容
      ?>
    </div>
  </div>
</section>
<?php get_footer(); ?>
