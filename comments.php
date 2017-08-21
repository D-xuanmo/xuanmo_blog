<?php
	if ( post_password_required() ) { return; }
?>
<div id="comments" class="comments-area">
	<!-- 评论输入框 -->
	<?php comment_form(); ?>
	<!-- 评论列表 -->
	<ul class="comment-list">
		<?php
		    function qiuye_comment($comment, $args, $depth) {
		        $GLOBALS['comment'] = $comment;
		?>
		    <li class="comment" id="li-comment-<?php comment_ID(); ?>">
		        <div class="comment-body">
		            <div class="comment-author">
		                <?php
							if (function_exists('get_avatar') && get_option('show_avatars'))
								echo get_avatar($comment, 80);
								// 获取评论者的url
								$comment_author_url
									= ($comment->comment_author_url)
									? $comment->comment_author_url
									: 'javascript:;';
						?>
						<a href="<?php echo $comment_author_url; ?>" class="comment-author-url" target="_blank">
							<?php echo $comment->comment_author; ?>
						</a>
		                <?php get_author_class($comment->comment_author_email, $comment->comment_author_url, $comment->user_id); ?>
		                <div class="comment-meta commentmetadata">发表于：<?php echo get_comment_time('Y-m-d H:i'); ?></div>
		            </div>
		            <div class="comment_content" id="comment-<?php comment_ID(); ?>">
		                <div class="comment_text">
		                    <?php if ($comment->comment_approved == '0') : ?>
		                        <span>您的评论正在审核，稍后会显示出来！</span><br />
		                    <?php endif; ?>
		                    <?php comment_text(); ?>
		                    <div class="reply">
		                        <?php
									comment_reply_link(
										array_merge(
											$args,
											array(
												'reply_text' => '回复',
												'depth' => $depth,
												'max_depth' => $args['max_depth']
											)
										)
									);
								?>
		                        <?php edit_comment_link('修改'); ?>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </li>
		<?php
		}
		?>
		<?php wp_list_comments('type=comment&callback=qiuye_comment');?>
	</ul>
	<!-- 评论导航 -->
	<?php if ( have_comments() ) : ?>
		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php _e( 'Comments are closed.', 'twentyfourteen' ); ?></p>
		<?php endif; ?>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<div id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<h3 class="screen-reader-text"><?php _e( 'Comment navigation', 'twentyfourteen' ); ?></h3>
			<div class="comment-page-btn">
				<?php previous_comments_link( __( '上一页', 'twentyfourteen' ) ); ?>
				<?php next_comments_link( __( '下一页', 'twentyfourteen' ) ); ?>
			</div>
		</div>
		<!-- #comment-nav-below -->
		<?php endif; // Check for comment navigation. ?>
	<?php endif; // have_comments() ?>
</div>
