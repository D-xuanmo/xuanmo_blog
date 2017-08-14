<?php
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
function get_post_excerpt($post, $excerpt_length) {
    if (!$post) $post = get_post();
    $post_excerpt = $post->post_excerpt;
    if ($post_excerpt == '') {
        $post_content = $post->post_content;
        $post_content = do_shortcode($post_content);
        $post_content = wp_strip_all_tags($post_content);
        $post_excerpt = mb_strimwidth($post_content, 0, $excerpt_length, '...', 'utf-8');
    }
    $post_excerpt = wp_strip_all_tags($post_excerpt);
    $post_excerpt = trim(preg_replace("/[\n\r\t ]+/", ' ', $post_excerpt), ' ');
    return $post_excerpt;
}

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
        'prev_text' => '上一页',
        'next_text' => '下一页'
    );
    if ($wp_rewrite->using_permalinks())
        $pagination['base'] = user_trailingslashit(trailingslashit(remove_query_arg('s', get_pagenum_link(1))) . 'page/%#%/', 'paged');
    if (!empty($wp_query->query_vars['s']))
        $pagination['add_args'] = array('s' => get_query_var('s'));
    echo paginate_links($pagination);
}

function custom_posts_per_page($query) {
    if (is_home()) $query->set('posts_per_page', 5);
    if (is_category()) $query->set('posts_per_page', 9);
    if (is_author() || is_tag() || is_search()) $query->set('posts_per_page', 12);
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
 * 解决php添加分斜杠问题                   
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
?>
