<?php get_header(); ?>
<section>
  <div class="wrap clearfix">
    <div class="mobile-hide fl left">
      <?php get_sidebar(); ?>
    </div>
    <div class="fr right">
      <div class="right-article clearfix">
        <div class="breadcrumbs">
          <i class="iconfont icon-home2"></i><?php if (function_exists('dimox_breadcrumbs')) dimox_breadcrumbs(); ?>
        </div>
        <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
        <article class="mobile-article-lg article-list">
          <i class="line-top"></i>
          <i class="line-right"></i>
          <i class="line-bottom"></i>
          <i class="line-left"></i>
          <a href="<?php the_permalink(); ?>" class="article-img">
            <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'Full' )[0]; ?>" class="black" alt="">
          </a>
          <div class="con">
            <h2 class="font-size-16">
              <a href="<?php the_permalink(); ?>" class="ar-title">
                <?php the_title(); ?>
              </a>
            </h2>
            <div class="time">
              <time class="ccc"><?php the_time('Y-m-d H:i'); ?></time>
              <!-- 评论数 -->
              <span class="iconfont icon-comment1 ccc"></span><?php echo get_comments_number(); ?>
              <!-- 阅读数 -->
              <span class="iconfont icon-fire ccc"></span><?php echo getPostViews(get_the_ID()); ?>
              <!-- 点赞 -->
              <!-- 点赞 -->
              <span class="iconfont icon-thumbs-up1"></span><?php echo get_post_meta($post->ID,'bigfa_ding',true) ? get_post_meta($post->ID,'bigfa_ding',true) : 0;
              ?>
            </div>
            <!-- 摘要 -->
            <p class="summary">
              <?php echo get_post_excerpt('', 160, ' <a href="' . get_the_permalink() . '" class="article-more">MORE...</a>'); ?>
            </p>
          </div>
        </article>
        <?php endwhile; ?>
        <?php else : ?>
          <p>暂无文章！</p>
        <?php endif; ?>
      </div>
      <div class="wp-pagenavi">
        <?php wp_pagenavi(); ?>
      </div>
    </div>
  </div>
</section>
<?php get_footer(); ?>
