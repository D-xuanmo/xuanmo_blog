<?php
	add_filter('automatic_updater_disabled', '__return_true');	// 彻底关闭自动更新
	//七牛缓存gravatar头像
	function dw_get_avatar($avatar) {
		$avatar = str_replace(array("www.gravatar.com","0.gravatar.com","1.gravatar.com","2.gravatar.com"),"gravatar.xuanmomo.com",$avatar);
		return $avatar;
	}
	add_filter( 'get_avatar', 'dw_get_avatar', 10, 3 );

	remove_filter('the_content', 'wptexturize');
	/*
	*注册菜单
	*=============================================================================
	*/
	register_nav_menus();

	/*
	 * delete google fonts
	 * ===========================================================================
	*/
	// Remove Open Sans that WP adds from frontend
	if (!function_exists('remove_wp_open_sans')) :
    function remove_wp_open_sans() {
      wp_deregister_style( 'open-sans' );
      wp_register_style( 'open-sans', false );
    }
    add_action('wp_enqueue_scripts', 'remove_wp_open_sans');
    // Uncomment below to remove from admin
    // add_action('admin_enqueue_scripts', 'remove_wp_open_sans');
	endif;
	function remove_open_sans() {
    wp_deregister_style( 'open-sans' );
    wp_register_style( 'open-sans', false );
    wp_enqueue_style('open-sans','');
	}
	add_action( 'init', 'remove_open_sans' );

	/*
	*特色头像
	*=============================================================================
	*/
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(370, 200, true); // 图片宽度与高度

	/*
	*文章翻页
	*=============================================================================
	*/
	function custom_posts_per_page($query){
		if(is_home()){
			$query->set('posts_per_page',5);//首页每页显示几篇文章
		}
		if(is_search()){
			$query->set('posts_per_page',9);//搜索页显示所有匹配的文章，不分页
		}
		if(is_archive()){
			$query->set('posts_per_page',9);//archive每页显示几篇文章
		}//endif
	}//function
	add_action('pre_get_posts','custom_posts_per_page');

	/*
	*面包屑导航
	*=============================================================================
	*/
	function dimox_breadcrumbs() {
		$showOnHome = 0;
		$delimiter = '&gt;';
		$home = '首页';
		$showCurrent = 1;
		$before = '<span class="current">';
		$after = '</span>';
		global $post;
		$homeLink = get_bloginfo('url');
		if (is_home() || is_front_page()) {
			if ($showOnHome == 1) echo '<a href="' . $homeLink . '">' . $home . '</a>';
		} else {
			echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
		if ( is_category() ) {
			$thisCat = get_category(get_query_var('cat'), false);
			if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
			echo $before . '' . single_cat_title('', false) . '' . $after;
		} elseif ( is_search() ) {
			echo $before . '搜索结果来自于关键词: "' . get_search_query() . '"' . $after;
		} elseif ( is_day() ) {
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('d') . $after;
		} elseif ( is_month() ) {
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('F') . $after;
		} elseif ( is_year() ) {
			echo $before . get_the_time('Y') . $after;
		} elseif ( is_single() && !is_attachment() ) {
			if ( get_post_type() != 'post' ) {
		$post_type = get_post_type_object(get_post_type());
		$slug = $post_type->rewrite;
		echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
		if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
			} else {
		$cat = get_the_category(); $cat = $cat[0];
		$cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
		if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
		echo $cats;
			 }
		} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
			$post_type = get_post_type_object(get_post_type());
			echo $before . $post_type->labels->singular_name . $after;
		} elseif ( is_attachment() ) {
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID); $cat = $cat[0];
			echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
			echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
			if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
		} elseif ( is_page() && !$post->post_parent ) {
			if ($showCurrent == 1) echo $before . get_the_title() . $after;
		} elseif ( is_page() && $post->post_parent ) {
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
		$page = get_page($parent_id);
		$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
		$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			for ($i = 0; $i < count($breadcrumbs); $i++) {
		echo $breadcrumbs[$i];
		if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
			}
			if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
		} elseif ( is_tag() ) {
			echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
		} elseif ( is_author() ) {
			 global $author;
			$userdata = get_userdata($author);
			echo $before . '' . $userdata->display_name . $after;
		} elseif ( is_404() ) {
			echo $before . 'Error 404' . $after;
		}
		if ( get_query_var('paged') ) {
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
			echo __('Page') . ' ' . get_query_var('paged');
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
		}
		echo '';
		}
	}

	/*
	*取得文章的阅读次数
	*=============================================================================
	*/
	function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count.'';
	}
	function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
	}
	function xuanmo(){
		$categories = get_the_category();
		$categoryIDS = array();
		foreach ($categories as $category) {
		array_push($categoryIDS, $category->term_id);
		}
		$categoryIDS = implode(",", $categoryIDS);
	}

	/*
	*后台添加菜单
	*=============================================================================
	*/
	function themeoptions_admin_menu(){
		// 在控制面板的侧边栏添加设置选项页链接
		add_theme_page('xuan主题设置', 'xuan主题设置','edit_themes', basename(__FILE__), 'themeoptions_page');
	}
	if ( $_POST['update_themeoptions'] == 'true' ) { themeoptions_update(); }
	function themeoptions_page()
	{
		// 这是产生主题选项页面的主要功能
		?>
		<div>
			<div id="icon-themes"><br /></div>
			<h2>主题设置</h2>
			<form method="POST" action="">
				<input type="hidden" name="update_themeoptions" value="true" />
				<p>
					电子邮箱：<br />
					<textarea name="x_mail" id="x_mail" class="large-text code" rows="2" cols="40"><?php echo stripslashes ( get_option('x_email') ); ?></textarea>
				</p>
				<p>
					qq链接：<br />
					<textarea name="x_qq" id="x_qq" class="large-text code" rows="2" cols="40"><?php echo stripslashes( get_option('x_t_qq') ); ?></textarea>
				</p>
				<p>
					github链接：<br />
					<textarea name="x_git" id="x_git" class="large-text code" rows="2" cols="40"><?php echo stripslashes( get_option('x_github') ); ?></textarea>
				</p>
				<p>
					新浪微博链接：<br />
					<textarea name="x_sina" id="x_sina" class="large-text code" rows="2" cols="40"><?php echo stripslashes( get_option('x_sinas') ); ?></textarea>
				</p>
				<!-- <p>
					<input type="file" name="x_file" value="<?php echo get_option('x_files'); ?>">
				</p> -->
				<p>
					微信图片地址：<br />
					<textarea name="x_wechat" id="x_wechat" class="large-text code" rows="2" cols="40"><?php echo stripslashes( get_option('x_wechats') ); ?></textarea>
				</p>
				<p>
					网站关键字（keywords）：<br />
					<textarea name="x_key" id="x_key" class="large-text code" rows="2" cols="40"><?php echo stripslashes( get_option('x_keywords') ); ?></textarea>
				</p>
				<p>
					网站描述（description）：<br />
					<textarea name="x_des" id="x_des" class="large-text code" rows="8" cols="40"><?php echo get_option('x_description'); ?></textarea>
				</p>
				<p>
					网站底部一句话（word）：<br />
					<textarea name="x_word" id="x_word" class="large-text code" rows="8" cols="40"><?php echo stripslashes ( get_option('x_words') ); ?></textarea>
				</p>
				<p>
					友情链接（link）：<br />
					<textarea name="x_link" id="x_link" class="large-text code" rows="8" cols="40"><?php echo stripslashes( get_option('x_links') ); ?></textarea>
				</p>
				<p>
					版权信息（copyright）：<br />
					<textarea name="x_copy" id="x_copy" class="large-text code" rows="8" cols="40"><?php echo stripslashes( get_option('x_copyright') ); ?></textarea>
				</p>
				<p>
					尾部自定义JavaScript代码：<br />
					<textarea name="x_js" id="x_js" class="large-text code" rows="8" cols="40"><?php echo stripslashes( get_option('x_javascript') ); ?></textarea>
				</p>
				<p>
					<input type="submit" name="bcn_admin_options" value="保存更改"/>
				</p>
			</form>
		</div>
		<?php
	}
	function themeoptions_update()
	{
		// 数据更新验证
		update_option('x_email', $_POST['x_mail']);
		update_option('x_t_qq', $_POST['x_qq']);
		update_option('x_github', $_POST['x_git']);
		update_option('x_sinas', $_POST['x_sina']);
		update_option('x_wechats', $_POST['x_wechat']);
		// update_option('x_files', $_POST['x_file']);
		update_option('x_keywords', $_POST['x_key']);
		update_option('x_description', $_POST['x_des']);
		update_option('x_words', $_POST['x_word']);
		update_option('x_links', $_POST['x_link']);
		update_option('x_copyright', $_POST['x_copy']);
		update_option('x_javascript', $_POST['x_js']);
		$str=$_POST['x_git'];//读取str的内容赋值给$str变量

if(get_magic_quotes_gpc())//如果get_magic_quotes_gpc()是打开的

{
$str=stripslashes($str);//将字符串进行处理

}
	}
	add_action('admin_menu', 'themeoptions_admin_menu');

	/*
	 *邮件回复功能
	 * ====================================================
	*/
	function ludou_comment_mail_notify($comment_id, $comment_status) {
	  // 评论必须经过审核才会发送通知邮件
	  if ($comment_status !== 'approve' && $comment_status !== 1)
	    return;
	  $comment = get_comment($comment_id);
	  if ($comment->comment_parent != '0') {
	    $parent_comment = get_comment($comment->comment_parent);
	    // 邮件接收者email
	    $to = trim($parent_comment->comment_author_email);
	    // 邮件标题
	    $subject = '您在[' . get_option("blogname") . ']的留言有了新的回复';
	    // 邮件内容，自行修改，支持HTML
	    $message = '
	      <p>Hi, ' . $parent_comment->comment_author . '</p>
	      <p>您之前在《' . get_the_title($comment->comment_post_ID) . '》的留言：<br />'
	       . $parent_comment->comment_content . '</p>
	      <p>' . $comment->comment_author . ' 给您回复:<br />'
	       . $comment->comment_content . '<br /><br /></p>
	      <p>您可以 <a href="' . htmlspecialchars(get_comment_link($comment->comment_parent)) . '">点此查看回复完整內容</a></p>
	      <p>欢迎再度光临 <a href="'.home_url().'">' . get_option('blogname') . '</a></p>
	      <p>（此邮件由系统自动发送，请勿回复）</p>';
	    $message_headers = "Content-Type: text/html; charset=\"".get_option('blog_charset')."\"\n";
	    // 不用给不填email的评论者和管理员发提醒邮件
	    if($to != '' && $to != get_bloginfo('admin_email'))
	      @wp_mail($to, $subject, $message, $message_headers);
	  }
	}
	// 编辑和管理员的回复直接发送提醒邮件，因为编辑和管理员的评论不需要审核
	add_action('comment_post', 'ludou_comment_mail_notify', 20, 2);
	// 普通访客发表的评论，等博主审核后再发送提醒邮件
	add_action('wp_set_comment_status', 'ludou_comment_mail_notify', 20, 2);

/*
* 侧边栏获取评论
*===============================================================================
*/
	function get_recent_comments(){
		// 不显示pingback的type=comment,不显示自己的,user_id=0.(此两个参数可有可无)
		$comments=get_comments(array('number'=>10,'status'=>'approve','type'=>'comment','user_id'=>0));
		$output = '';
		foreach($comments as $comment) {
			$random = mt_rand(1, 10);
			//去除评论内容中的标签
			$comment_content = strip_tags($comment->comment_content);
			//评论可能很长,所以考虑截断评论内容,只显示10个字
			$short_comment_content = trim(mb_substr($comment_content ,0, 10,"UTF-8"));
			//先获取头像 get_avatar( $comment->comment_author_email, 50 )
			$output .= '<li class="aside-com-img" class="clearfix"><p>'.get_avatar( $comment->comment_author_email, 50 ) .'</p> ';
			//获取作者
			 $output .= '<strong class="aside-content">'.$comment->comment_author .':</strong><p><a href="';
			//评论内容和链接
			 $output .= get_permalink( $comment->comment_post_ID ) .'" title="查看 '.get_post( $comment->comment_post_ID )->post_title .'">';
			 $output .= $short_comment_content .'...</a></p></li>';
		}
		//输出
		echo $output;
	}
	/*
	*点赞功能
	*=============================================================================
	*/
	add_action('wp_ajax_nopriv_bigfa_like', 'bigfa_like');
	add_action('wp_ajax_bigfa_like', 'bigfa_like');
	function bigfa_like(){
    global $wpdb,$post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ( $action == 'ding'){
		$bigfa_raters = get_post_meta($id,'bigfa_ding',true);
		$expire = time() + 99999999;
		$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
		setcookie('bigfa_ding_'.$id,$id,$expire,'/',$domain,false);
		if (!$bigfa_raters || !is_numeric($bigfa_raters)) {
			update_post_meta($id, 'bigfa_ding', 1);
		}else {
			update_post_meta($id, 'bigfa_ding', ($bigfa_raters + 1));
		}
		echo get_post_meta($id,'bigfa_ding',true);
    }
    die;
	}
?>
