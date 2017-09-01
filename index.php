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
    <!-- 最新文章 -->
    <div class="wrap clearfix">
        <div class="demo-title">
            <h2>Article</h2>
            <h4>最新文章</h4>
        </div>
        <div class="article-wrap article-box new-article-wrap clearfix">
            <?php $posts = query_posts($query_string . '&orderby=date&showposts=' . $xm_options['home_column']['home_article_num']); ?>
            <?php if(have_posts()) : while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" class="mobile-article-lg">
                <a href="<?php the_permalink(); ?>" class="article-img"><?php the_post_thumbnail(); ?></a>
                <div class="con">
                    <h2 class="article-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
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
            <?php endwhile; ?>
            <?php else : ?>
            <p>暂无文章！ </p>
            <?php endif; ?>
        </div>
    </div>
    <!-- 案例展示 -->
    <div class="wrap margin-top-20 clearfix">
        <div class="demo-title">
            <h2><?php echo $xm_options['home_column'][0]['cat_title']; ?></h2>
            <h4><?php echo $xm_options['home_column'][0]['cat_sub_title']; ?></h4>
            <a href="<?php bloginfo('home'); ?>/?cat=<?php echo $xm_options['home_column'][0]['cat_id']; ?>" class="more"><i></i>more</a>
        </div>
        <div class="article-wrap clearfix">
            <?php
                $args=array(
                    'cat' => intval($xm_options['home_column'][0]['cat_id']),   // 分类ID
                    'posts_per_page' => 9, // 显示篇数
                );
                query_posts($args);
                if(have_posts()) : while (have_posts()) : the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" class="mobile-article-lg">
                <a href="<?php the_permalink(); ?>" class="article-img"><?php the_post_thumbnail(); ?></a>
                <div class="con">
                    <h2 class="article-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
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
            <?php endwhile; ?>
            <?php else : ?>
                <p>暂无文章！</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- 我的笔记 -->
    <div class="wrap margin-top-20 clearfix">
        <div class="demo-title">
            <h2><?php echo $xm_options['home_column'][1]['cat_title']; ?></h2>
            <h4><?php echo $xm_options['home_column'][1]['cat_sub_title']; ?></h4>
            <a href="<?php bloginfo('home'); ?>/?cat=<?php echo $xm_options['home_column'][1]['cat_id']; ?>" class="more"><i></i>more</a>
        </div>
        <div class="article-wrap clearfix">
            <?php
                $args=array(
                    'cat' => intval($xm_options['home_column'][1]['cat_id']),   // 分类ID
                    'posts_per_page' => 9, // 显示篇数
                );
                query_posts($args);
                if(have_posts()) : while (have_posts()) : the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" class="mobile-article-lg">
                <a href="<?php the_permalink(); ?>" class="article-img"><?php the_post_thumbnail(); ?></a>
                <div class="con">
                    <h2 class="article-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
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
    </div>
</section>
<?php get_footer(); ?>
