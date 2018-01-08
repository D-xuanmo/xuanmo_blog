<?php
/*
 ****************************************
 * 彻底关闭自动更新
 ****************************************
 */
add_filter('automatic_updater_disabled', '__return_true');

/*
 ****************************************
 * 删除谷歌字体
 ****************************************
 */
if (!function_exists('remove_wp_open_sans')) :
  function remove_wp_open_sans()
  {
    wp_deregister_style('open-sans');
    wp_register_style('open-sans', false);
  }
  add_action('wp_enqueue_scripts', 'remove_wp_open_sans');
endif;
function remove_open_sans()
{
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
 * 后台设置用户头像
 ****************************************
 */
require get_template_directory() . '/inc/author-avatars.php';

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
function get_post_excerpt($length, $str)
{
  $post_content = wp_strip_all_tags(get_post()->post_content, true);
  return wp_trim_words($post_content, $length, $str);
}

/*
 ****************************************
 * 侧边栏获取评论
 ****************************************
 */
function get_recent_comments()
{
  // 不显示pingback的type=comment,不显示自己的,user_id=0.(此两个参数可有可无)
  $comments = get_comments(array('number' => 10, 'status' => 'approve', 'type' => 'comment', 'user_id' => 0));
  $output = '';

  foreach ($comments as $comment) {
    $random = mt_rand(1, 10);
    $color = '#' . mb_substr( md5(strtolower($comment->comment_author_email)), 0, 6 ,'UTF8');
    $author = mb_substr( $comment->comment_author, 0, 1 ,'UTF8');
    //去除评论内容中的标签
    $comment_content = strip_tags($comment->comment_content);
    $avatar_pic = (get_option('xm_options')['text_pic'] === 'off')
                ? get_avatar($comment->comment_author_email, 50)
                : '<span class="avatar" style="background-color:' . $color . ';">' . $author . '</span>';
    $output .= '
      <li class="aside-com-img" class="clearfix"><a href="'
      . get_permalink($comment->comment_post_ID) . '#'
      . $comment->comment_ID
      . '" title="查看 '
      . get_post($comment->comment_post_ID)->post_title . '">'
      . $avatar_pic
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
function ludou_comment_mail_notify($comment_id, $comment_status)
{
  // 评论必须经过审核才会发送通知邮件
  if ($comment_status !== 'approve' && $comment_status !== 1) {
    return;
  }
  $comment = get_comment($comment_id);
  if ($comment->comment_parent != '0') {
    $parent_comment = get_comment($comment->comment_parent);
    // 邮件接收者email
    $to = trim($parent_comment->comment_author_email);
    // 邮件标题
    $subject = '您在[' . get_option("blogname") . ']的留言有了新的回复!';
    // 邮件内容，自行修改，支持HTML
    $message = '
      <div style="width:90%; margin:10px auto 0; border:1px solid #eee; border-radius:8px; font-size:12px; font-family:PingFangSC,Microsoft Yahei; color:#111;">
        <div style="width:100%; height:60px; border-radius:6px 6px 0 0; background:#eee; color:#333;">
          <p style="margin:0 0 0 30px; line-height:60px;"> 您在 <a style="text-decoration:none; color:#2ebef3; font-weight:600;" href="' . get_option('home') . '">' . get_option('blogname') . '  </a> 的留言有新回复啦！</p>
        </div>
        <div style="width:90%; margin:0 auto">
          <p><strong>' . $parent_comment->comment_author . '</strong> 您好!</p>
          <p>您在 [' . get_option('blogname') . '] 的文章<strong style="color:#2ebef3;">《' . get_the_title($comment->comment_post_ID) . '》</strong>上发表的评论有新回复啦，快来看看吧 ^_^:</p>
          <p>这是你的评论:</p>
          <p style="margin: 15px 0; padding: 20px; border-radius: 5px; background-color: #eee;">' . $parent_comment->comment_content . '</p>
          <p><strong>' . trim($comment->comment_author) . '</strong> 给你的回复是:<br />
          <p style="margin: 15px 0; padding: 20px; border-radius: 5px; background-color: #eee;">'. trim($comment->comment_content) . '</p>
          <p>您也可移步到文章<a style="text-decoration:none; color:#2ebef3" href="' . htmlspecialchars(get_comment_link($comment->comment_parent)) . '"> 《'. get_the_title($comment->comment_post_ID) .'》 </a>查看完整回复内容</p>
          <p style="padding-bottom: 10px; border-bottom: 1px dashed #ccc;">欢迎再次光临 <a style="text-decoration:none; color:#2ebef3" href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
          <p>(此邮件由系统自动发出, 请勿回复。)</p>
          <p style="text-align: right;">如果您想更深入的和博主交流的话，欢迎回复哦^-^</p>
        </div>
      </div>';
    $message_headers = "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\n";
    // 不用给不填email的评论者和管理员发提醒邮件
    if ($to != '' && $to != get_bloginfo('admin_email')) {
      @wp_mail($to, $subject, $message, $message_headers);
    }
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
function wp_pagenavi()
{
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
  if ($wp_rewrite->using_permalinks()) {
    $pagination['base'] = user_trailingslashit(trailingslashit(remove_query_arg('s', get_pagenum_link(1))) . 'page/%#%/', 'paged');
  }
  if (!empty($wp_query->query_vars['s'])) {
    $pagination['add_args'] = array('s' => get_query_var('s'));
  }
  echo paginate_links($pagination);
}

function custom_posts_per_page($query)
{
  if (is_category()) {
    $query->set('posts_per_page', get_option('xm_options')['cat_article_num']);
  }
  if (is_author() || is_tag() || is_search()) {
    $query->set('posts_per_page', get_option('xm_options')['page_article_num']);
  }
}
add_action('pre_get_posts', 'custom_posts_per_page');

/*
 ****************************************
 * 面包屑导航
 ****************************************
 */
function dimox_breadcrumbs()
{
  $showOnHome = 0;
  $delimiter = '&gt;';
  $home = '首页';
  $showCurrent = 1;
  $before = '<span class="current">';
  $after = '</span>';
  global $post;
  $homeLink = get_bloginfo('url');
  if (is_home() || is_front_page()) {
    if ($showOnHome == 1) {
      echo '<a href="' . $homeLink . '">' . $home . '</a>';
    }
  } else {
    echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
    if (is_category()) {
      $thisCat = get_category(get_query_var('cat'), false);
      if ($thisCat->parent != 0) {
        echo get_category_parents($thisCat->parent, true, ' ' . $delimiter . ' ');
      }
      echo $before . '' . single_cat_title('', false) . '' . $after;
    } elseif (is_search()) {
      echo $before . '搜索结果来自于关键词: "' . get_search_query() . '"' . $after;
    } elseif (is_day()) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;
    } elseif (is_month()) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;
    } elseif (is_year()) {
      echo $before . get_the_time('Y') . $after;
    } elseif (is_single()) {
      if (get_post_type() != 'post') {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
        if ($showCurrent == 1) {
          echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
        }
      } else {
        $cat = get_the_category();
        $cat = $cat[0];
        $cats = get_category_parents($cat, true, ' ' . $delimiter . ' ');
        if ($showCurrent == 0) {
          $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
        }
        echo $cats . get_the_title();
      }
    } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;
    } elseif (is_attachment()) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID);
      $cat = $cat[0];
      echo get_category_parents($cat, true, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
      if ($showCurrent == 1) {
        echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
      }
    } elseif (is_page() && !$post->post_parent) {
      if ($showCurrent == 1) {
        echo $before . get_the_title() . $after;
      }
    } elseif (is_page() && $post->post_parent) {
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
        if ($i != count($breadcrumbs) - 1) {
          echo ' ' . $delimiter . ' ';
        }
      }
      if ($showCurrent == 1) {
        echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
      }
    } elseif (is_tag()) {
      echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
    } elseif (is_author()) {
      global $author;
      $userdata = get_userdata($author);
      echo $before . '' . $userdata->display_name . $after;
    } elseif (is_404()) {
      echo $before . 'Error 404' . $after;
    }
    if (get_query_var('paged')) {
      if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
        echo ' (';
      }
      echo __('Page') . ' ' . get_query_var('paged');
      if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
        echo ')';
      }
    }
    echo '';
  }
}

/*
 ****************************************
 * 获取文章阅读量
 ****************************************
 */
function getPostViews($postID)
{
  $count_key = 'post_views_count';
  $count = get_post_meta($postID, $count_key, true);
  if ($count == '') {
    delete_post_meta($postID, $count_key);
    add_post_meta($postID, $count_key, '0');
    return "0";
  }
  return $count . '';
}
function setPostViews($postID)
{
  $count_key = 'post_views_count';
  $count = get_post_meta($postID, $count_key, true);
  if ($count == '') {
    $count = 0;
    delete_post_meta($postID, $count_key);
    add_post_meta($postID, $count_key, '0');
  } else {
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
 * 添加自定义编辑器按钮
 ****************************************
 */
function add_my_media_button()
{
 echo '<a href="javascript:;" id="html-transform" class="button">html尖括号转义</a>';
}
function appthemes_add_quicktags()
{
?>
  <script>
    var aLanguage = ['html', 'css', 'sass', 'scss', 'less', 'javascript', 'php', 'json', 'git'];
    for( var i = 0, length = aLanguage.length; i < length; i++ ) {
      QTags.addButton(aLanguage[i], aLanguage[i], '\n<pre class="line-numbers language-' + aLanguage[i] + '"><code class="language-' + aLanguage[i] + '">\n', '\n</code></pre>\n');
    }
    QTags.addButton('c-code', 'c-code', '<span class="code">', '</span>');
    // 添加html转换容器
    jQuery(function() {
      jQuery('#html-transform').click(function() {
        jQuery('body').append(
          '<div id="xm-transform">'
          + '<textarea name="name" rows="15" cols="100"></textarea>'
          + '<span id="xm-transfom-btn">转换</span>'
          + '<span id="xm-copy-btn">复制</span>'
          + '</div>'
        );
        jQuery('#xm-transform')
          .css({
            position: 'fixed',
            top: 0,
            left: 0,
            zIndex: 99999,
            width: '100%',
            height: '100%',
            background: 'rgba(255,255,255,0.7)'
          })
          .children('textarea').css({
            resize: 'none',
            position: 'absolute',
            top: '50%',
            left: '50%',
            width: '60%',
            height: '300px',
            transform: 'translate(-50%, -50%)'
          })
          .siblings('span').css({
            position: 'absolute',
            top: '90%',
            left: '50%',
            width: '100px',
            height: '40px',
            borderRadius: '5px',
            background: '#2196F3',
            textAlign: 'center',
            lineHeight: '40px',
            color: '#fff',
            cursor: 'pointer'
          });
        jQuery('textarea').click(function(e) { e.stopPropagation(); });
        jQuery('#xm-transfom-btn')
          .css('transform', 'translateX(-115%)')
          .click(function(e) {
            e.stopPropagation();
            jQuery(this).siblings('textarea').val(function() {
              return jQuery(this).val().replace(/</g, '&lt;').replace(/>/g, '&gt;');
            });
          });
        jQuery('#xm-copy-btn').click(function(e) {
          e.stopPropagation();
          jQuery(this).siblings('textarea')[0].select();
          if (document.execCommand('Copy')) {
            jQuery(this).text('复制成功');
          }
        });
        jQuery('#xm-transform').click(function() {
          jQuery(this).remove();
        });
      });
    });
  </script>
<?php
}
add_action('media_buttons', 'add_my_media_button');
add_action('admin_print_footer_scripts', 'appthemes_add_quicktags');

/*
 ****************************************
 * 添加一个验证码
 ****************************************
 */
function my_fields($fields)
{
  $fields['img-code'] = '<p class="comment-form-img-code">' . '<label for="img-code">' . __('验证码 * ') . '</label> ' . '<input id="img-code" name="img-code" type="text" value="" size="30" /><canvas width="120" height="28" class="canvas-img-code"></canvas><span class="tab-img-code">换一张</span></p>';
  return $fields;
}
add_filter('comment_form_default_fields', 'my_fields');

/*
 ****************************************
 * 自定义表情路径和名称
 ****************************************
 */
function custom_smilies_src($src, $img)
{
  return get_bloginfo('template_directory') . '/images/smilies/' . $img;
}
add_filter('smilies_src', 'custom_smilies_src', 10, 2);
if (!isset($wpsmiliestrans)) {
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
function add_my_tips()
{
  echo '
    <div class="expression-wrap clearfix">
      <span class="hide express-url">'
      . get_bloginfo('template_directory')
      . '/expression.php</span>
      <div class="fl first-img comment-icon">
        <i class="iconfont icon-grin"></i>表情
      </div>
      <div class="fl comment-icon comment-pic-btn upload-img">
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
function disable_emojis()
{
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_styles', 'print_emoji_styles');
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
  add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
}
function disable_emojis_tinymce($plugins)
{
  if (is_array($plugins)) {
    return array_diff($plugins, array( 'wpemoji' ));
  } else {
    return array();
  }
}
add_action('init', 'disable_emojis');

/*
 ****************************************
 * 获取访客VIP样式
 ****************************************
 */
function get_author_class($comment_author_email, $comment_author_url)
{
  global $wpdb;
  $adminEmail = get_bloginfo('admin_email');
  $styleClass = get_option('xm_options')['vip_style'];
  $author_count = count($wpdb->get_results(
  "SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email =        '$comment_author_email' "
  ));
  if ($comment_author_email == $adminEmail) {
    echo '<span class="' . $styleClass . ' icon-vip vip7" title="博主"></span><span title="博主" class="icon-vip icon-admin"></span>';
  }
  $linkurls = $wpdb->get_results("SELECT link_url FROM $wpdb->links WHERE link_url = '$comment_author_url'");
  if ($author_count >= 1 && $author_count < 10 && $comment_author_email != $adminEmail) {
    echo '<a href="javascript:;" class="' . $styleClass . ' icon-vip vip1" title="评论达人 LV.1"></a>';
  } elseif ($author_count >= 10 && $author_count < 20 && $comment_author_email != $adminEmail) {
    echo '<a href="javascript:;" class="' . $styleClass . ' icon-vip vip2" title="评论达人 LV.2"></a>';
  } elseif ($author_count >= 20 && $author_count < 30 && $comment_author_email != $adminEmail) {
    echo '<a href="javascript:;" class="' . $styleClass . ' icon-vip vip3" title="评论达人 LV.3"></a>';
  } elseif ($author_count >= 30 && $author_count < 50 && $comment_author_email != $adminEmail) {
    echo '<a href="javascript:;" class="' . $styleClass . ' icon-vip vip4" title="评论达人 LV.4"></a>';
  } elseif ($author_count >= 50 && $author_count < 80 && $comment_author_email != $adminEmail) {
    echo '<a href="javascript:;" class="' . $styleClass . ' icon-vip vip5" title="评论达人 LV.5"></a>';
  } elseif ($author_count >= 80 && $author_coun < 200 && $comment_author_email != $adminEmail) {
    echo '<a href="javascript:;" class="' . $styleClass . ' icon-vip vip6" title="评论达人 LV.6"></a>';
  } elseif ($author_count >= 200 && $comment_author_email != $adminEmail) {
    echo '<a href="javascript:;" class="' . $styleClass . ' icon-vip vip7" title="评论达人 LV.7"></a>';
  }
  foreach ($linkurls as $linkurl) {
    if ($linkurl->link_url == $comment_author_url) {
      echo '<a class="vp" target="_blank" href="/links/" title="隔壁邻居的哦！">隔壁邻居的哦！</a>';
    }
  }
}

/*
 ****************************************
 * 自定义登录页面的LOGO链接为首页链接,LOGO提示为网站名称
 ****************************************
 */
add_filter('login_headerurl', create_function(false,"return get_bloginfo('url');"));
add_filter('login_headertitle', create_function(false,"return get_bloginfo('name');"));

/*
 ****************************************
 * 自定义登录页面的LOGO图片
 ****************************************
 */
function my_custom_login_logo() {
  echo '
    <style>
    .login h1 a {
      background-image:url("' . get_option('xm_options')['login_logo'] . '");
      border-radius: 50%;
    }
    ' . get_option('xm_options')['login_css'] . '
    </style>
  ';
}
add_action('login_head', 'my_custom_login_logo');

/*
 ****************************************
 * 获取到的IP转换成实际地址
 ****************************************
 */
require get_template_directory() . '/ip2c/ip2c.php';

/*
 ****************************************
 * 判断是什么系统和浏览器留言
 ****************************************
 */
function get_browser_name($str)
{
  if (preg_match('/QQBrowser/', $str)) {
   echo '<img src="'. get_bloginfo('template_directory') . '/images/QQBrowser.png" width="20" style="vertical-align: baseline;">';
 } elseif (preg_match('/MetaSr/', $str)) {
    echo '<img src="'. get_bloginfo('template_directory') . '/images/sougou_logo.png" width="20" style="vertical-align: baseline;">';
  } elseif (preg_match('/Edge/', $str)) {
    echo '<img src="'. get_bloginfo('template_directory') . '/images/ie_logo.png" width="20" style="vertical-align: baseline;">';
  } elseif (preg_match('/OPR/', $str)) {
    echo '<img src="'. get_bloginfo('template_directory') . '/images/opera_logo.png" width="20" style="vertical-align: baseline;">';
  } elseif (preg_match('/Chrome/', $str)) {
   echo '<img src="'. get_bloginfo('template_directory') . '/images/chrome_logo.png" width="20" style="vertical-align: baseline;">';
  } elseif (preg_match('/Safari/', $str)) {
    echo '<img src="'. get_bloginfo('template_directory') . '/images/safari_logo.png" width="20" style="vertical-align: baseline;">';
  } elseif (preg_match('/Firefox/', $str)) {
    echo '<img src="'. get_bloginfo('template_directory') . '/images/firefox_logo.png" width="20" style="vertical-align: baseline;">';
  } elseif (preg_match('/Trident/', $str)) {
    echo '<img src="'. get_bloginfo('template_directory') . '/images/ie_logo.png" width="20" style="vertical-align: baseline;">';
  } elseif (preg_match('/Quark/', $str)) {
    echo '<img src="'. get_bloginfo('template_directory') . '/images/quark_logo.png" width="20" style="vertical-align: baseline;">';
  }
}

function get_system_name($str)
{
  if (strpos($str, 'Windows')) {
    echo '<img src="'. get_bloginfo('template_directory') .'/images/windows_logo.png" width="18" style="vertical-align: baseline;">';
  } elseif (strpos($str, 'Mac')) {
    echo '<img src="'. get_bloginfo('template_directory') .'/images/mac_logo.png" width="18" style="vertical-align: baseline;">';
  } elseif (strpos($str, 'Android')) {
    echo '<img src="'. get_bloginfo('template_directory') .'/images/android_logo.png" width="18" style="vertical-align: baseline;">';
  }
}

/*
 ****************************************
 * 评论区@功能
 ****************************************
 */
function comment_add_at( $comment_text, $comment = '') {
  if( $comment->comment_parent > 0) {
    $comment_text = '@<a href="#comment-' . $comment->comment_parent . '" style="color: #16C0F8;">'.get_comment_author( $comment->comment_parent ) . '</a> ' . $comment_text;
  }

  return $comment_text;
}
add_filter( 'comment_text' , 'comment_add_at', 20, 2);

/*
 ****************************************
 * 解决4.9页面模板不能正常使用
 ****************************************
 */
function wp_42573_fix_template_caching( WP_Screen $current_screen ) {
	if ( ! in_array( $current_screen->base, array( 'post', 'edit', 'theme-editor' ), true ) ) {
		return;
	}
	$theme = wp_get_theme();
	if ( ! $theme ) {
		return;
	}
	$cache_hash = md5( $theme->get_theme_root() . '/' . $theme->get_stylesheet() );
	$label = sanitize_key( 'files_' . $cache_hash . '-' . $theme->get( 'Version' ) );
	$transient_key = substr( $label, 0, 29 ) . md5( $label );
	delete_transient( $transient_key );
}
add_action( 'current_screen', 'wp_42573_fix_template_caching' );

/*
 ****************************************
 * 获取自定义字段
 ****************************************
 */
function create_api_posts_meta_field() {
  // 获取自定义字段
  register_rest_field( 'post', 'post_meta_field', array(
      'get_callback'    => 'get_post_meta_for_api',
      'schema'          => null,
    )
  );

  // 获取文章评论数
  register_rest_field( 'post', 'comments_views', array(
      'get_callback'    => function () { return get_comments_number(); },
      'schema'          => null,
    )
  );

  // 获取摘要
  register_rest_field( 'post', 'summary', array(
      'get_callback'    => 'get_summary',
      'schema'          => null,
    )
  );

  // 获取总文章
  register_rest_field( 'post', 'total_article', array(
      'get_callback'    => 'get_total_article',
      'schema'          => null,
    )
  );

  // 获取点赞数
  register_rest_field( 'post', 'xm_link', array(
      'get_callback'    => function($obj) { return get_post_meta($obj['id'], 'xm_post_link', true); },
      'schema'          => null,
    )
  );
}

function get_post_meta_for_api( $object ) {
  $post_id = $object['id'];
  return get_post_meta( $post_id );
}

function get_summary() {
  return get_post_excerpt(100, ' <a href="' . get_the_permalink() . '" class="article-more">MORE...</a>');
}

function get_total_article() {
  $count_posts = wp_count_posts()->publish;
  return
    $count_posts / 3 > intval($count_posts / 3)
    ? intval($count_posts / 3 + 1)
    : intval($count_posts / 3);
}
add_action( 'rest_api_init', 'create_api_posts_meta_field' );

/*
 ****************************************
 * 非管理员上传图片
 ****************************************
 */
function comments_embed_img($comment) {
  $comment = preg_replace('/(\[img\](\S+)\[\/img\])+/','<img src="$2" style="max-width: 40%; max-height: 250px;" />', $comment);
  return $comment;
}
add_action('comment_text', 'comments_embed_img');

/*
 ****************************************
 * 设置文章喜爱度
 ****************************************
 */
add_action( 'wp_ajax_xm_post_link', 'xm_post_link' );
add_action( 'wp_ajax_nopriv_xm_post_link', 'xm_post_link' );
function xm_set_post_link($id)
{
  $count_key = 'xm_post_link';
  $count = get_post_meta($id, $count_key, true);
  if ($count == '') {

    delete_post_meta($id, $count_key);
    add_post_meta($id, $count_key, array(
      'very_good' => 0,
      'good'      => 0,
      'commonly'  => 0,
      'bad'       => 0,
      'very_bad'  => 0
    ));
  }
}
function xm_post_link()
{
  $count_key = 'xm_post_link';
  $id = $_POST['post_id'];
  $key = $_POST['post_key'];
  $count = get_post_meta($id, $count_key, true);
  update_post_meta($id, $count_key, array_merge($count, array($key => $count[$key] + 1)));
  echo $count[$key] + 1;
  die();
}

/*
 ****************************************
 * 给用户资料增加自定义字段
 ****************************************
 */
add_filter('user_contactmethods', 'xm_user_contact');
function xm_user_contact($user_contactmethods){
  unset($user_contactmethods['aim']);
  unset($user_contactmethods['yim']);
  unset($user_contactmethods['jabber']);
  $user_contactmethods['qq'] = 'QQ链接';
  $user_contactmethods['github_url'] = 'GitHub';
  $user_contactmethods['wechat_num'] = '微信号';
  $user_contactmethods['wechat_img'] = '微信二维码链接';
  $user_contactmethods['sina_url'] = '新浪微博';
  return $user_contactmethods;
}
?>
