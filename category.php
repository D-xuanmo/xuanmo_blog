<?php include('header-list.php'); ?>
<section>
  <div class="wrap clearfix">
    <div class="mobile-hide fl left">
      <?php get_sidebar(); ?>
    </div>
      <div class="fr right">
        <div class="right-ar clearfix">
            <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
            <article class="mobile-article-lg">
              <i class="line-top"></i>
              <i class="line-right"></i>
              <i class="line-bottom"></i>
              <i class="line-left"></i>
              <a href="<?php the_permalink(); ?>" class="article-img">
                <?php the_post_thumbnail(); ?>
              </a>
              <div class="con">
                <a href="<?php the_permalink(); ?>" class="ar-title">
                  <h2 class="font-size-16"><?php the_title(); ?></h2>
                </a>
                <div class="time">
                  <time class="ccc"><?php the_time('Y-m-d H:i'); ?></time>
                  <!-- 评论数 -->
                  <span class="iconfont icon-comment1 ccc"><?php echo get_comments_number(); ?></span>
                  <!-- 阅读数 -->
                  <span class="iconfont icon-eye-open ccc"><?php echo getPostViews(get_the_ID()); ?></span>
                  <!-- 点赞 -->
                  <span href="javascript:;" data-action="ding" data-id="<?php the_ID(); ?>" class="cur ccc favorite<?php if(isset($_COOKIE['bigfa_ding_'.$post->ID])) echo ' done';?>">
                    <span class="iconfont icon-thumbs-up2"></span>
                    <span class="count">
                      <?php
                        if( get_post_meta($post->ID,'bigfa_ding',true) ){
                          echo get_post_meta($post->ID,'bigfa_ding',true);
                        } else {
                          echo '0';
                        }
                      ?>
                    </span>
                  </span>
                </div>
                <!-- 摘要 -->
                <?php the_excerpt(); ?>
                <a href="<?php the_permalink(); ?>" class="more">查看更多</a>
              </div>
            </article>
          <?php endwhile; ?>
          <?php else : ?>
            <p>
              暂无文章！
            </p>
          <?php endif; ?>
        </div>
        <?php wp_pagenavi(); ?>
    </div>
  </div>
</section>
<?php get_footer(); ?>
