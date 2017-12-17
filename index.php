<?php
  get_header();
  $xm_options = get_option('xm_options');
?>
<!-- 首屏 start -->
<div class="first-screen mobile-hide" style="background-image: url(<?php echo $xm_options['home_banner'][0]['big_img']; ?>);">
  <div class="txt">
    <p class="move-left"><?php echo $xm_options['home_banner'][0]['cn_title_0']; ?></p>
    <p class="move-left"><?php echo $xm_options['home_banner'][0]['cn_title_1']; ?></p>
    <h1>
      <span class="img-title" style="background-image: url(<?php echo $xm_options['home_banner'][0]['img_title']; ?>);"></span>
      <i class="hide"><?php echo $xm_options['title']; ?></i>
    </h1>
    <p class="move-right"><?php echo $xm_options['home_banner'][0]['en_title_0']; ?></p>
    <p class="move-right"><?php echo $xm_options['home_banner'][0]['en_title_1']; ?></p>
  </div>
  <i class="iconfont icon-menu-up"></i>
</div>
<div class="mobile-banner pc-none">
  <ul class="roll-banner clearfix" style="width: 500%;">
    <?php
      $arr_img = $xm_options['mobile_banner'];
      foreach($arr_img as $value) {
        if($value) echo '<li><img src="' . $value . '" alt=""></li>';
      };
    ?>
  </ul>
  <ul class="plugin-banner-btn"></ul>
</div>
<!-- 首屏 end -->
<section class="main home">
  <!-- 置顶文章 -->
  <div class="wrap clearfix">
    <div class="demo-title">
      <h2>Sticky</h2>
      <h4>置顶文章</h4>
    </div>
    <div class="article-wrap article-box new-article-wrap clearfix">
      <?php
        /* 获取所有置顶文章 */
        $sticky = get_option( 'sticky_posts' );
        /* 对这些文章排序, 日期最新的在最上 */
        rsort( $sticky ); /* 获取5篇文章 */
        $sticky = array_slice( $sticky, 0, 5 );
        /* 输出这些文章 */
        query_posts( array( 'post__in' => $sticky, 'ignore_sticky_posts' => 1 ) );
        while ( have_posts() ) : the_post();
      ?>
      <article id="post-<?php the_ID(); ?>" class="mobile-article-lg">
        <a href="<?php the_permalink(); ?>" class="article-img">
          <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'Full' )[0]; ?>" class="black" alt="">
        </a>
        <div class="con">
          <h2 class="article-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>
          <div class="time">
            <time class="ccc"><?php the_time('Y-m-d H:i'); ?></time>
            <!-- 评论数 -->
            <span class="iconfont icon-comment1 ccc"></span><?php echo get_comments_number(); ?>
            <!-- 阅读数 -->
            <span class="iconfont icon-fire ccc"></span><?php echo getPostViews(get_the_ID()); ?>
            <!-- 点赞 -->
            <a
              href="javascript:void(0);"
              data-action="ding"
              data-id="<?php the_ID(); ?>"
              class="cur ccc link-btn<?php if(isset($_COOKIE['bigfa_ding_'.$post->ID])) echo ' done';?>"
            >
              <span class="iconfont icon-thumbs-up1"></span>
              <span class="hide blog-url"><?php bloginfo('url'); ?>/wp-admin/admin-ajax.php</span>
              <span class="count">
                <?php
                  if( get_post_meta($post->ID,'bigfa_ding',true) ){
                    echo get_post_meta($post->ID,'bigfa_ding',true);
                  } else {
                    echo '0';
                  }
                ?>
              </span>
            </a>
          </div>
          <!-- 摘要 -->
          <p class="summary">
            <?php echo get_post_excerpt('', 160, ' <a href="' . get_the_permalink() . '" class="article-more">MORE...</a>'); ?>
          </p>
        </div>
      </article>
      <?php
        endwhile;
        wp_reset_query();
      ?>
    </div>

    <div class="wrap margin-top-20 clearfix">
      <div class="demo-title">
        <h2>Article</h2>
        <h4>所有文章</h4>
      </div>
      <div id="total-article" class="article-wrap clearfix"></div>
      <div class="more-btn">
        <img src="<?php bloginfo('template_url'); ?>/images/loading.svg" alt="">
        <div class="hide text">加载更多...</div>
      </div>
  </div>
</section>
<?php get_footer(); ?>
