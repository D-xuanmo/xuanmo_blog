<?php get_header(); ?>
<section>
    <div class="wrap clearfix">
        <div class="mobile-article-lg fr right" style="width: 100%; padding-top: 14px;">
        <?php  while(have_posts()) : the_post(); ?>
        <?php the_content(); ?>
        <?php endwhile; ?>
        </div>
    </div>
    <div class="wrap">
        <?php comments_template(); ?>
    </div>
</section>
<?php get_footer(); ?>
