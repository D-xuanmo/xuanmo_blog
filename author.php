<?php get_header(); ?>
<section class="main page">
    <div class="wrap article-wrap clearfix">
        <p class="margin-bottom-20" style="text-indent: 30px;">
            <?php the_author(); ?>发布的文章：
        </p>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" class="mobile-article-lg">
            <a href="<?php the_permalink(); ?>" class="article-img"><?php the_post_thumbnail(); ?></a>
            <div class="con">
                <h2 class="font-size-16 article-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <div class="time">
                    <time class="ccc"><?php the_time('Y-m-d H:i'); ?></time>
                    <!-- 评论数 -->
                    <span class="iconfont icon-comment1 ccc"><?php echo get_comments_number(); ?></span>
                    <!-- 阅读数 -->
                    <span class="iconfont icon-eye-open ccc"><?php echo getPostViews(get_the_ID()); ?></span>
                    <!-- 点赞 -->
                    <span data-action="ding" data-id="<?php the_ID(); ?>" class="cur ccc link-btn<?php if(isset($_COOKIE['bigfa_ding_'.$post->ID])) echo ' done';?>">
                        <span class="iconfont icon-thumbs-up2"></span>
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
                    </span>
                </div>
                <!-- 摘要 -->
                <p class="summary">
                    <?php echo get_post_excerpt('', 160, ' <a href="' . get_the_permalink() . '" class="article-more">MORE...</a>'); ?>
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
