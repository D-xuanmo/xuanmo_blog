<?php
if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">
	<!-- 评论输入框 -->
  <?php comment_form(); ?>
	<!-- 评论列表 -->
  <ol class="comment-list">
    <?php
      wp_list_comments( array(
        'style'      => 'ol',
        'short_ping' => true,
        'avatar_size'=> 34,
      ) );
    ?>
  </ol>
	<!-- 评论导航 -->
	<?php if ( have_comments() ) : ?>
    <?php if ( ! comments_open() ) : ?>
       <p class="no-comments"><?php _e( 'Comments are closed.', 'twentyfourteen' ); ?></p>
    <?php endif; ?>
    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
    <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
      <h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'twentyfourteen' ); ?></h1>
      <div class="nav-previous"><?php previous_comments_link( __( '← 上一页', 'twentyfourteen' ) ); ?></div>
      <div class="nav-next"><?php next_comments_link( __( '下一页 →', 'twentyfourteen' ) ); ?></div>
    </nav>
		<!-- #comment-nav-below -->
    <?php endif; // Check for comment navigation. ?>
  <?php endif; // have_comments() ?>
</div>
