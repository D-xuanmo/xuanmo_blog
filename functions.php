<?php
/*
 ****************************************
 * 彻底关闭自动更新
 ****************************************
 */
add_filter('automatic_updater_disabled', '__return_true');

/*
 ****************************************
 * 七牛缓存gravatar头像
 ****************************************
 */
function dw_get_avatar($avatar) {
    $avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "gravatar.xuanmomo.com", $avatar);
    return $avatar;
}
add_filter('get_avatar', 'dw_get_avatar', 10, 3);

/*
 ****************************************
 * 删除谷歌字体
 ****************************************
 */
if (!function_exists('remove_wp_open_sans')) :
    function remove_wp_open_sans() {
        wp_deregister_style('open-sans');
        wp_register_style('open-sans', false);
    }
    add_action('wp_enqueue_scripts', 'remove_wp_open_sans');
endif;
function remove_open_sans() {
    wp_deregister_style('open-sans');
    wp_register_style('open-sans', false);
    wp_enqueue_style('open-sans', '');
}
add_action('init', 'remove_open_sans');

/*
 ****************************************
 * 注册菜单
 ****************************************
 */
register_nav_menus();

/*
 ****************************************
 * 后台添加主题添加设置菜单
 ****************************************
 */
require get_template_directory() . '/inc/xm_theme_options.php';

/*
 ****************************************
 * 添加特色头像
 ****************************************
 */
add_theme_support('post-thumbnails');

/*
 ****************************************
 * 设置摘要字数
 ****************************************
 */
function get_post_excerpt($post, $excerpt_length, $str) {
    if (!$post) $post = get_post();
    $post_excerpt = $post->post_excerpt;
    if ($post_excerpt == '') {
        $post_content = $post->post_content;
        $post_content = do_shortcode($post_content);
        $post_content = wp_strip_all_tags($post_content);
        $post_excerpt = mb_strimwidth($post_content, 0, $excerpt_length, '', 'utf-8');
    }
    $post_excerpt = wp_strip_all_tags($post_excerpt);
    $post_excerpt = trim(preg_replace("/[\n\r\t ]+/", ' ', $post_excerpt), ' ');
    return $post_excerpt . $str;
}

/*
 ****************************************
 * 侧边栏获取评论
 ****************************************
 */
function get_recent_comments(){
	// 不显示pingback的type=comment,不显示自己的,user_id=0.(此两个参数可有可无)
    $comments = get_comments(array('number' => 10, 'status' => 'approve', 'type' => 'comment', 'user_id' => 0));
    $output = '';
    foreach ($comments as $comment) {
        $random = mt_rand(1, 10);
			//去除评论内容中的标签
        $comment_content = strip_tags($comment->comment_content);
			//评论可能很长,所以考虑截断评论内容,只显示10个字
        // $short_comment_content = trim(mb_substr($comment_content, 0, 10, "UTF-8"));
        $output .= '
            <li class="aside-com-img" class="clearfix"><a href="'
            . get_permalink($comment->comment_post_ID) . '" title="查看 '
            . get_post($comment->comment_post_ID)->post_title . '">'
            . get_avatar($comment->comment_author_email, 50)
            . '<p><strong class="aside-com-author">' . $comment->comment_author
            . ':</strong></p><p class="aside-com-content">'
            . $comment_content . '</p></a></li>
        ';
    }
	//输出
    echo $output;
}

/*
 ****************************************
 * 邮件回复功能
 ****************************************
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
	      <p>欢迎再度光临 <a href="' . home_url() . '">' . get_option('blogname') . '</a></p>
	      <p>（此邮件由系统自动发送，请勿回复）</p>';
        $message_headers = "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\n";
	    // 不用给不填email的评论者和管理员发提醒邮件
        if ($to != '' && $to != get_bloginfo('admin_email'))
            @wp_mail($to, $subject, $message, $message_headers);
    }
}
// 编辑和管理员的回复直接发送提醒邮件，因为编辑和管理员的评论不需要审核
add_action('comment_post', 'ludou_comment_mail_notify', 20, 2);
// 普通访客发表的评论，等博主审核后再发送提醒邮件
add_action('wp_set_comment_status', 'ludou_comment_mail_notify', 20, 2);

/*
 ****************************************
 * 设置文章列表翻页
 ****************************************
 */
function wp_pagenavi() {
    global $wp_query, $wp_rewrite;
    //判断当前页面
    $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
    $pagination = array(
        'base' => @add_query_arg('paged', '%#%'),
        'format' => '',
        'total' => $wp_query->max_num_pages, //总共显示的页码数
        'current' => $current, //当前页码数
        'show_all' => false, //是否将所有页码都显示出来,需配合下两个参数
        'type' => 'plain',
        'end_size' => '1', //在最后面和最前面至少显示多少个数，
        'mid_size' => '3', //在当前页码的前后至少显示多少个页码数
        'prev_text' => '&lt;',
        'next_text' => '&gt;'
    );
    if ($wp_rewrite->using_permalinks())
        $pagination['base'] = user_trailingslashit(trailingslashit(remove_query_arg('s', get_pagenum_link(1))) . 'page/%#%/', 'paged');
    if (!empty($wp_query->query_vars['s']))
        $pagination['add_args'] = array('s' => get_query_var('s'));
    echo paginate_links($pagination);
}

function custom_posts_per_page($query) {
    if (is_category()) $query->set('posts_per_page', get_option('xm_options')['cat_article_num']);
    if (is_author() || is_tag() || is_search()) $query->set('posts_per_page', get_option('xm_options')['page_article_num']);
}
add_action('pre_get_posts', 'custom_posts_per_page');

/*
 ****************************************
 * 面包屑导航
 ****************************************
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
    }
    else {
        echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
        if (is_category()) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
            echo $before . '' . single_cat_title('', false) . '' . $after;
        }
        else if (is_search()) {
            echo $before . '搜索结果来自于关键词: "' . get_search_query() . '"' . $after;
        }
        else if (is_day()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('d') . $after;
        }
        else if (is_month()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('F') . $after;
        }
        else if (is_year()) {
            echo $before . get_the_time('Y') . $after;
        }
        else if (is_single()) {
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
                if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
            else {
                $cat = get_the_category();
                $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
                if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
                echo $cats . get_the_title();
            }
        }
        else if (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;
        }
        else if (is_attachment()) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
            echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
            if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
        }
        else if (is_page() && !$post->post_parent) {
            if ($showCurrent == 1) echo $before . get_the_title() . $after;
        }
        else if (is_page() && $post->post_parent) {
            $parent_id = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for ($i = 0; $i < count($breadcrumbs); $i++) {
                echo $breadcrumbs[$i];
                if ($i != count($breadcrumbs) - 1) echo ' ' . $delimiter . ' ';
            }
            if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
        }
        else if (is_tag()) {
            echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
        }
        else if (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . '' . $userdata->display_name . $after;
        }
        else if (is_404()) {
            echo $before . 'Error 404' . $after;
        }
        if (get_query_var('paged')) {
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
                echo ' (';
            echo __('Page') . ' ' . get_query_var('paged');
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
                echo ')';
        }
        echo '';
    }
}

/*
 ****************************************
 * 获取文章阅读量
 ****************************************
 */
function getPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count . '';
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }
    else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

/*
 ****************************************
 * 解决php添加分号斜杠问题
 ****************************************
 */
if (get_magic_quotes_gpc()) {
    function stripslashes_deep($value)
    {
        $value = is_array($value) ?
            array_map('stripslashes_deep', $value) :
            stripslashes($value);
        return $value;
    }
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

/*
 ****************************************
 * 点赞功能
 ****************************************
 */
add_action('wp_ajax_nopriv_bigfa_like', 'bigfa_like');
add_action('wp_ajax_bigfa_like', 'bigfa_like');
function bigfa_like() {
    global $wpdb, $post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ($action == 'ding') {
        $bigfa_raters = get_post_meta($id, 'bigfa_ding', true);
        $expire = time() + 99999999;
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie('bigfa_ding_' . $id, $id, $expire, '/', $domain, false);
        if (!$bigfa_raters || !is_numeric($bigfa_raters)) {
            update_post_meta($id, 'bigfa_ding', 1);
        }
        else {
            update_post_meta($id, 'bigfa_ding', ($bigfa_raters + 1));
        }
        echo get_post_meta($id, 'bigfa_ding', true);
    }
    die;
}

/*
 ****************************************
 * 丰富发文按钮功能
 ****************************************
 */
function add_editor_buttons($buttons) {
    $buttons = array('fontselect', 'fontsizeselect', 'copy', 'paste', 'cut', 'backcolor');
    return $buttons;
}
add_filter("mce_buttons_3", "add_editor_buttons");

/*
 ****************************************
 * 添加自定义编辑器按钮
 ****************************************
 */
function appthemes_add_quicktags() {
?>
    <script>
        var aLanguage = ['html', 'css', 'sass', 'scss', 'less', 'javascript', 'php', 'json', 'git'];
        for( var i = 0, length = aLanguage.length; i < length; i++ ) {
            QTags.addButton(aLanguage[i], aLanguage[i], '\n<pre class="line-numbers language-' + aLanguage[i] + '"><code class="language-' + aLanguage[i] + '">\n', '\n</code></pre>\n');
        }
        QTags.addButton('c-code', 'c-code', '<span class="code">', '</span>');
    </script>
<?php
}
add_action('admin_print_footer_scripts', 'appthemes_add_quicktags');

/*
 ****************************************
 * 添加一个验证码
 ****************************************
 */
function my_fields($fields) {
	$fields['img-code'] = '<p class="comment-form-img-code">' . '<label for="img-code">' . __('验证码 * ') . '</label> ' . '<input id="img-code" name="img-code" type="text" value="" size="30" /><canvas width="120" height="28" class="canvas-img-code"></canvas><span class="tab-img-code">换一张</span></p>';
	return $fields;
}
add_filter('comment_form_default_fields', 'my_fields');

/*
 ****************************************
 * 自定义表情路径和名称
 ****************************************
 */
function custom_smilies_src($src, $img){
    return get_bloginfo('template_directory') . '/images/smilies/' . $img;
}
add_filter('smilies_src', 'custom_smilies_src', 10, 2);
if ( !isset( $wpsmiliestrans ) ) {
    $wpsmiliestrans = array(
        "/weixiao"     => "weixiao.gif",
        "/nanguo"      => "nanguo.gif",
        "/qiudale"     => "qiudale.gif",
        "/penxue"      => "penxue.gif",
        "/piezui"      => "piezui.gif",
        "/aoman"       => "aoman.gif",
        "/baiyan"      => "baiyan.gif",
        "/bishi"       => "bishi.gif",
        "/bizui"       => "bizui.gif",
        "/cahan"       => "cahan.gif",
        "/ciya"        => "ciya.gif",
        "/dabing"      => "dabing.gif",
        "/daku"        => "daku.gif",
        "/deyi"        => "deyi.gif",
        "/doge"        => "doge.gif",
        "/fadai"       => "fadai.gif",
        "/fanu"        => "fanu.gif",
        "/fendou"      => "fendou.gif",
        "/ganga"       => "ganga.gif",
        "/guzhang"     => "guzhang.gif",
        "/haixiu"      => "haixiu.gif",
        "/hanxiao"     => "hanxiao.gif",
        "/haqian"      => "haqian.gif",
        "/huaixiao"    => "huaixiao.gif",
        "/jie"         => "jie.gif",
        "/jingkong"    => "jingkong.gif",
        "/jingxi"      => "jingxi.gif",
        "/jingya"      => "jingya.gif",
        "/keai"        => "keai.gif",
        "/kelian"      => "kelian.gif",
        "/koubi"       => "koubi.gif",
        "/ku"          => "ku.gif",
        "/kuaikule"    => "kuaikule.gif",
        "/kulou"       => "kulou.gif",
        "/kun"         => "kun.gif",
        "/leiben"      => "leiben.gif",
        "/lenghan"     => "lenghan.gif",
        "/liuhan"      => "liuhan.gif",
        "/liulei"      => "liulei.gif",
        "/qiaoda"      => "qiaoda.gif",
        "/qinqin"      => "qinqin.gif",
        "/saorao"      => "saorao.gif",
        "/se"          => "se.gif",
        "/shuai"       => "shuai.gif",
        "/shui"        => "shui.gif",
        "/tiaopi"      => "tiaopi.gif",
        "/touxiao"     => "touxiao.gif",
        "/tu"          => "tu.gif",
        "/tuosai"      => "tuosai.gif",
        "/weiqu"       => "weiqu.gif",
        "/wozuimei"    => "wozuimei.gif",
        "/wunai"       => "wunai.gif",
        "/xia"         => "xia.gif",
        "/xiaojiujie"  => "xiaojiujie.gif",
        "/xiaoku"      => "xiaoku.gif",
        "/xieyanxiao"  => "xieyanxiao.gif",
        "/xu"          => "xu.gif",
        "/yinxian"     => "yinxian.gif",
        "/yiwen"       => "yiwen.gif",
        "/zuohengheng" => "zuohengheng.gif",
        "/youhengheng" => "youhengheng.gif",
        "/yun"         => "yun.gif",
        "/zaijian"     => "zaijian.gif",
        "/zhayanjian"  => "zhayanjian.gif",
        "/zhemo"       => "zhemo.gif",
        "/zhouma"      => "zhouma.gif",
        "/zhuakuang"   => "zhuakuang.gif",
        "/aini"        => "aini.gif",
        "/baoquan"     => "baoquan.gif",
        "/gouyin"      => "gouyin.gif",
        "/qiang"       => "qiang.gif",
        "OK"           => "OK.gif",
        "/woshou"      => "woshou.gif",
        "/quantou"     => "quantou.gif",
        "/shengli"     => "shengli.gif",
        "/aixin"       => "aixin.gif",
        "/bangbangtang"=> "bangbangtang.gif",
        "/baojin"      => "baojin.gif",
        "/caidao"      => "caidao.gif",
        "/lanqiu"      => "lanqiu.gif",
        "/chi"         => "chi.gif",
        "/dan"         => "dan.gif",
        "/haobang"     => "haobang.gif",
        "/hecai"       => "hecai.gif",
        "/hexie"       => "hexie.gif",
        "/juhua"       => "juhua.gif",
        "/pijiu"       => "pijiu.gif",
        "/shouqiang"   => "shouqiang.gif",
        "/xiaoyanger"  => "xiaoyanger.gif",
        "/xigua"       => "xigua.gif",
        "/yangtuo"     => "yangtuo.gif",
        "/youling"     => "youling.gif",
        '/色'           => 'icon_razz.gif',
        '/难过'         => 'icon_sad.gif',
        '/闭嘴'         => 'icon_evil.gif',
        '/吐舌头'       => 'icon_exclaim.gif',
        '/微笑'         => 'icon_smile.gif',
        '/可爱'         => 'icon_redface.gif',
        '/kiss'        => 'icon_biggrin.gif',
        '/惊讶'         => 'icon_surprised.gif',
        '/饥饿'         => 'icon_eek.gif',
        '/晕'           => 'icon_confused.gif',
        '/酷'           => 'icon_cool.gif',
        '/坏笑'         => 'icon_lol.gif',
        '/发怒'         => 'icon_mad.gif',
        '/憨笑'         => 'icon_twisted.gif',
        '/萌萌哒'       => 'icon_rolleyes.gif',
        '/吃东西'       => 'icon_wink.gif',
        '/色咪咪'       => 'icon_idea.gif',
        '/囧'          => 'icon_arrow.gif',
        '/害羞'        => 'icon_neutral.gif',
        '/流泪'        => 'icon_cry.gif',
        '/流汗'        => 'icon_question.gif',
        '/你懂的'      => 'icon_mrgreen.gif'
    );
}
function add_my_tips() {
	echo '
        <div class="expression-wrap clearfix">
            <span class="hide express-url">'
            . get_bloginfo('template_directory')
            . '/expression.php</span>
            <div class="fl first-img comment-icon">
                <i class="iconfont icon-grin"></i>表情
            </div>
            <div class="fl comment-icon comment-pic-btn">
                <i class="iconfont icon-picture2"></i>贴图
            </div>
            <div class="fl comment-icon comment-code-btn-wrap">
                <i class="iconfont icon-code"></i>代码
                <p>
                    <a href="javascript:;" class="btn comment-code-btn">html</a>
                    <a href="javascript:;" class="btn comment-code-btn">css</a>
                    <a href="javascript:;" class="btn comment-code-btn">JavaScript</a>
                    <a href="javascript:;" class="btn comment-code-btn">php</a>
                </p>
            </div>
            <div class="expression-hide-wrap text-center">
                <img src="'
                . get_bloginfo('template_directory')
                . '/images/smilies/yangtuo.gif" style="margin-top: 80px; vertical-align: bottom;">
                <span>表情加载中...</span>
            </div>
        </div>
    ';
}
add_filter('comment_form_before_fields', 'add_my_tips');
add_filter('comment_form_logged_in_after', 'add_my_tips');

/*
 ****************************************
 * 禁止emoji表情
 ****************************************
 */
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}
add_action( 'init', 'disable_emojis' );

/*
 ****************************************
 * 获取访客VIP样式
 ****************************************
 */
function get_author_class($comment_author_email, $comment_author_url) {
    global $wpdb;
    $adminEmail = get_bloginfo('admin_email');
    $styleClass = get_option('xm_options')['vip_style'];
    $author_count = count($wpdb->get_results(
    "SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email =        '$comment_author_email' "));
    if($comment_author_email == $adminEmail)
        echo '<a href="javascript:;" class="' . $styleClass . ' icon-vip vip7" title="博主"></a><a href="javascript:;" title="博主" class="icon-vip icon-admin"></a>';
    $linkurls = $wpdb->get_results("SELECT link_url FROM $wpdb->links WHERE link_url = '$comment_author_url'");
    if($author_count >= 1 && $author_count < 10 && $comment_author_email != $adminEmail)
        echo '<a href="javascript:;" class="' . $styleClass . ' icon-vip vip1" title="评论达人 LV.1"></a>';
    else if($author_count >= 10 && $author_count < 20 && $comment_author_email != $adminEmail)
        echo '<a href="javascript:;" class="' . $styleClass . ' icon-vip vip2" title="评论达人 LV.2"></a>';
    else if($author_count >= 20 && $author_count < 30 && $comment_author_email != $adminEmail)
        echo '<a href="javascript:;" class="' . $styleClass . ' icon-vip vip3" title="评论达人 LV.3"></a>';
    else if($author_count >= 30 && $author_count < 50 && $comment_author_email != $adminEmail)
        echo '<a href="javascript:;" class="' . $styleClass . ' icon-vip vip4" title="评论达人 LV.4"></a>';
    else if($author_count >= 50 && $author_count < 80 && $comment_author_email != $adminEmail)
        echo '<a href="javascript:;" class="' . $styleClass . ' icon-vip vip5" title="评论达人 LV.5"></a>';
    else if($author_count >= 80 && $author_coun < 200 && $comment_author_email != $adminEmail)
        echo '<a href="javascript:;" class="' . $styleClass . ' icon-vip vip6" title="评论达人 LV.6"></a>';
    else if($author_count >= 200 && $comment_author_email != $adminEmail)
        echo '<a href="javascript:;" class="' . $styleClass . ' icon-vip vip7" title="评论达人 LV.7"></a>';
    foreach ($linkurls as $linkurl) {
        if ($linkurl->link_url == $comment_author_url )
        echo '<a class="vp" target="_blank" href="/links/" title="隔壁邻居的哦！">隔壁邻居的哦！</a>';
    }
}
?>
