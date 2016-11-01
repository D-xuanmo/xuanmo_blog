<aside class="new-article">
  <ul class="list-article-title">
    <li>
      <h3>最新文章</h3>
    </li>
    <!-- 显示10篇最新更新文章 -->
    <?php //get_archives('postbypost', 10); ?>
    <?php $result = $wpdb->get_results("SELECT ID,post_title FROM $wpdb->posts where post_status='publish' and post_type='post' ORDER BY ID DESC LIMIT 0 , 10");
      foreach ($result as $post) {
        setup_postdata($post);
        $postid = $post->ID;
        $title = $post->post_title;
        ?>
        <li>
          <a href="<?php echo get_permalink($postid); ?>" title="<?php echo $title ?>"><?php echo $title ?></a>
        </li>
        <?php
      }
    ?>
  </ul>
</aside>
<aside>
  <ul class="aside-conment">
    <li>
      <h3>近期评论</h3>
    </li>
    <?php get_recent_comments( $args ); ?>
  </ul>
</aside>
<aside class="tag-cloud">
  <ul class="aside-conment">
    <li>
      <h3>标签云</h3>
    </li>
    <?php wp_tag_cloud('number=20'); ?>
  </ul>
</aside>
