<?php get_header(); ?>
<?php include(TEMPLATEPATH .'/fullScreen.php'); ?>
<section class="main">
    <!-- 最新文章 -->
    <div class="wrap clearfix">
        <div class="demo-title">
            <h2>Article</h2>
            <h4>最新文章</h4>
        </div>
        <div class="article-box clearfix">
            <?php $posts = query_posts($query_string . '&orderby=date&showposts=4'); ?>
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
                    <a href="<?php the_permalink(); ?>" class="article-more">阅&nbsp;读</a>
                </div>
            </article>
            <?php endwhile; ?>
            <?php else : ?>
            <p>暂无文章！ </p>
            <?php endif; ?>
        </div>
    </div>
    <!-- 案例展示 -->
    <div class="wrap clearfix">
        <div class="demo-title">
            <h2>Case show</h2>
            <h4>最新案例展示</h4>
            <a href="<?php bloginfo('home'); ?>/?cat=8" class="more"><i></i>more</a>
        </div>
        <div class="clearfix">
            <?php
                $args=array(
                    'cat' => 8,   // 分类ID
                    'posts_per_page' => 6, // 显示篇数
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
                    <a href="<?php the_permalink(); ?>" class="article-more">阅&nbsp;读</a>
                </div>
            </article>
            <?php endwhile; ?>
            <?php else : ?>
                <p>暂无文章！</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- 我的笔记 -->
    <div class="wrap clearfix">
        <div class="demo-title">
            <h2>Note</h2>
            <h4>最新笔记</h4>
            <a href="<?php bloginfo('home'); ?>/?cat=1" class="more"><i></i>more</a>
        </div>
        <div class="clearfix">
            <?php
                $args=array(
                    'cat' => 1,   // 分类ID
                    'posts_per_page' => 6, // 显示篇数
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
                    <?php the_excerpt(); ?>
                    <a href="<?php the_permalink(); ?>" class="article-more">阅&nbsp;读</a>
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
