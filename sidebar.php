<?php
  if( is_single() ){
?>
  <aside class="new-article single-page">
<?php
  }else{
?>
  <aside class="new-article">
<?php
  }
?>
    <div class="aside-comment">
      <div class="aside-header margin-top-10 clearfix">
        <h3 class="fl cur on">最新文章</h3>
        <h3 class="fl cur">阅读量最高</h3>
      </div>
      <!-- 显示10篇最新更新文章 -->
      <div class="aside-tab-wrap">
        <ul class="list-article-title">
          <?php $result = $wpdb->get_results("SELECT ID,post_title FROM $wpdb->posts where post_status='publish' and post_type='post' ORDER BY ID DESC LIMIT 0 , 10");
            foreach ($result as $post) {
              setup_postdata($post);
              $postid = $post->ID;
              $title = $post->post_title;
          ?>
            <li>
              <a
                href="<?php echo get_permalink($postid); ?>"
                title="<?php echo $title ?>"
              >
                <?php echo $title ?>
              </a>
            </li>
          <?php
            }
          ?>
        </ul>
        <ul class="list-article-title hide">
          <?php
            $post_num = 10; // 设置调用条数
            $args = array(
            "meta_key" => "post_views_count",
            "orderby" => "meta_value_num",
            "order" => "DESC",
            "posts_per_page" => $post_num
            );
            $query_posts = new WP_Query();
            $query_posts->query($args);
            while( $query_posts->have_posts() ) {
              $query_posts->the_post();
          ?>
            <li class="clearfix">
              <a
                href="<?php the_permalink(); ?>"
                title="<?php the_title_attribute(); ?>"
                class="fl"
                style="width: 74%"
              >
                <?php the_title();?>
              </a>
              <i class="fr iconfont icon-eye-open"><?php echo getPostViews(get_the_ID()); ?></i>
            </li>
            <?php } wp_reset_query();?>
        </ul>
      </div>
    </div>
  </aside>
  <!-- 近期评论 -->
  <aside>
    <ul class="aside-comment">
      <li class="aside-header">
        <h3>近期评论</h3>
      </li>
      <?php get_recent_comments(); ?>
    </ul>
  </aside>
  <!-- 标签云 -->
  <aside class="tag-cloud">
    <div class="aside-conment">
      <div class="aside-header">
        <h3>标签云</h3>
      </div>
      <?php wp_tag_cloud('smallest=10&largest=20&number=20'); ?>
    </div>
  </aside>
  <!-- 网站统计 -->
  <?php if(get_option('xm_options')['aside_count'] == 'on') { ?>
  <aside class="count">
    <div class="aside-conment">
      <div class="aside-header">
        <h3>网站统计</h3>
      </div>
      <ul class="count-wrap clearfix">
        <li class="fl count-list">
          文章：<?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish . '篇'; ?>
        </li>
        <li class="fr count-list">
          评论：<?php echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments") . '条';?>
        </li>
        <li class="fl count-list">
          分类：<?php echo $count_categories = wp_count_terms('category') . '个'; ?>
        </li>
        <li class="fr count-list">
          标签：<?php echo $count_tags = wp_count_terms('post_tag') . '个'; ?>
        </li>
        <li class="fl count-list">
          页面：<?php $count_pages = wp_count_posts('page'); echo $page_posts = $count_pages->publish . '个'; ?>
        </li>
        <li class="fr count-list">
          最后更新：<?php $last = $wpdb->get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')"); $last = date('Y-n-j', strtotime($last[0]->MAX_m)); echo $last; ?>
        </li>
      </ul>
    </div>
  </aside>
<?php } ?>
