<?php get_header(); ?>
<section class="main page">
  <div class="wrap article-wrap clearfix">
    <p class="margin-bottom-20" style="text-indent: 30px;">
      <?php the_tags(); ?>
    </p>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" class="mobile-article-lg">
      <a href="<?php the_permalink(); ?>" class="article-img">
        <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'Full' )[0]; ?>" class="black" alt="">
      </a>
      <div class="con">
        <h2 class="font-size-16 article-title">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <div class="time">
          <time class="ccc"><?php the_time('Y-m-d H:i'); ?></time>
          <!-- 评论数 -->
          <span class="iconfont icon-comment1 ccc"></span><?php echo get_comments_number(); ?>
          <!-- 阅读数 -->
          <span class="iconfont icon-fire ccc"></span><?php echo getPostViews(get_the_ID()); ?>
          <!-- 点赞 -->
          <span class="iconfont icon-thumbs-up1"></span><?php echo get_post_meta(get_the_ID(), 'xm_post_link', true)['very_good']; ?>
        </div>
        <!-- 摘要 -->
        <p class="summary">
          <?php echo get_post_excerpt(160, ' <a href="' . get_the_permalink() . '" class="article-more">MORE...</a>'); ?>
        </p>
      </div>
    </article>
    <?php endwhile; else: ?>
    <p class="search_text">
      <?php _e('您要搜索的内容不存在'); ?>
    </p>
    <?php endif; ?>
  </div>
  <div class="wp-pagenavi">
    <?php wp_pagenavi(); ?>
  </div>
</section>
<?php get_footer(); ?>
