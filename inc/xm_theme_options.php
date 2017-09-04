<?php
    function themeoptions_admin_menu() {
		// 在控制面板的侧边栏添加设置选项页链接
		add_theme_page('xuan主题设置', 'xuan主题设置','edit_themes', basename(__FILE__), 'themeoptions_page');
	}
	if ( $_POST['update_themeoptions'] == 'true' ) { themeoptions_update(); }
	function themeoptions_page() {
        // 获取提交的数据
        $a_options = get_option('xm_options');
        // //加载上传图片的js(wp自带)
        wp_enqueue_script('thickbox');
        // //加载css(wp自带)
        wp_enqueue_style('thickbox');
?>
        <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/inc/css/set.css">
		<div class="wrap">
			<h2>主题设置</h2>
            <ul class="nav-wrap clearfix">
                <li class="nav-list on">基本</li>
                <li class="nav-list">SEO</li>
                <li class="nav-list">首页</li>
                <li class="nav-list">社交</li>
                <li class="nav-list">自定义代码</li>
            </ul>
			<form method="POST" action="">
				<input type="hidden" name="update_themeoptions" value="true" >
                <!-- 内容一 基本 -->
                <div class="content-wrap content1">
                    <div class="row clearfix">
                        <label class="fl left-wrap" for="aside-count">侧边栏统计功能：</label>
                        <div class="fr right-wrap">
                            <label for="aside-count-on">开</label>
                            <input type="radio" id="aside-count-on" name="aside-count" value="on" <?php if($a_options['aside_count'] == 'on') echo 'checked'; ?>>
                            <label for="aside-count-off">关</label>
                            <input type="radio" id="aside-count-off" name="aside-count" value="off" <?php if($a_options['aside_count'] == 'off' || $a_options['aside_count'] == '') echo 'checked'; ?>>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <label class="fl left-wrap" for="aside-count">评论区vip等级样式：</label>
                        <div class="fr right-wrap">
                            <label for="vip-style-1" class="vip-style" style="display: inline-block; width: 18px; height: 18px; background: url(<?php bloginfo('template_url'); ?>/images/vip.png) -35px -51px;"></label>
                            <input type="radio" id="vip-style-1" name="vip-style" value="vip-style-1" <?php if($a_options['vip_style'] == 'vip-style-1' || $a_options['vip_style'] == '') echo 'checked'; ?>>

                            <label for="vip-style-2" class="vip-style" style="display: inline-block; width: 15px; height: 15px; background: url(<?php bloginfo('template_url'); ?>/images/vip.png) -147px -70px;"></label>
                            <input type="radio" id="vip-style-2" name="vip-style" value="vip-style-2" <?php if($a_options['vip_style'] == 'vip-style-2') echo 'checked'; ?>>

                            <label for="vip-style-3" class="vip-style" style="display: inline-block; width: 42px; height: 15px; background: url(<?php bloginfo('template_url'); ?>/images/vip.png) -2px -2px;"></label>
                            <input type="radio" id="vip-style-3" name="vip-style" value="vip-style-3" <?php if($a_options['vip_style'] == 'vip-style-3') echo 'checked'; ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="margin-top-15 clearfix">
                            <label class="fl left-wrap" for="">窗口小图标：</label>
                            <div class="fr right-wrap">
                                <input type="text" class="url-inp" name="favicon-img" id="favicon-img" value="<?php echo $a_options['favicon']; ?>">
                                <input type="button" name="img-upload" value="选择文件">
                            </div>
                        </div>
                        <div class="margin-top-15 clearfix">
                            <div class="fl left-wrap">
                                窗口小图标预览：
                            </div>
                            <div class="fr right-wrap">
                                <img src="<?php echo $a_options['favicon']; ?>" class="preview-img" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <label class="fl left-wrap" for="title">h1标题（这个标题为网站的主标题）：</label>
                        <div class="fr right-wrap">
                            <input type="text" id="title" name="title" value="<?php echo $a_options['title']; ?>">
                        </div>
                    </div>
                    <div class="row clearfix">
                        <label class="fl left-wrap" for="home-article-num">首页最新文章篇数：</label>
                        <div class="fr right-wrap">
                            <input type="text" id="home-article-num" name="home-article-num" value="<?php echo empty($a_options['home_column']['home_article_num']) ? 6 : $a_options['home_column']['home_article_num']; ?>">
                        </div>
                    </div>
                    <div class="row clearfix">
                        <label class="fl left-wrap" for="cat-article-num">列表页文章显示篇数：</label>
                        <div class="fr right-wrap">
                            <input type="text" id="cat-article-num" name="cat-article-num" value="<?php echo empty($a_options['cat_article_num']) ? 9 : $a_options['cat_article_num']; ?>">
                        </div>
                    </div>
                    <div class="row clearfix">
                        <label class="fl left-wrap" for="page-article-num">作者、搜索结果、标签页显示篇数：</label>
                        <div class="fr right-wrap">
                            <input type="text" id="page-article-num" name="page-article-num" value="<?php echo empty($a_options['page_article_num']) ? 12 : $a_options['page_article_num']; ?>">
                        </div>
                    </div>
                    <div class="row clearfix">
                        <label for="footer-copyright" class="fl left-wrap">底部版权文字：</label>
                        <div class="fr right-wrap">
                            <textarea id="footer-copyright" name="footer-copyright" rows="5" cols="100"><?php echo $a_options['footer_copyright']; ?></textarea>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <label for="footer-txt" class="fl left-wrap">网站底部一句话：</label>
                        <div class="fr right-wrap">
                            <textarea id="footer-txt" name="footer-txt" rows="8" cols="100"><?php echo $a_options['footer_text'] ?></textarea>
                        </div>
                    </div>
                </div>
                <!-- 内容二 SEO -->
                <div class="content-wrap content2">
                    <div class="row clearfix">
                        <label for="keywords" class="fl left-wrap">首页关键词(keywords)：</label>
                        <div class="fr right-wrap">
                            <textarea id="keywords" name="keywords" rows="8" cols="100"><?php echo $a_options['keywords'] ?></textarea>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <label for="description class="fl left-wrap"">首页描述(description)：</label>
                        <div class="fr right-wrap">
                            <textarea id="description" name="description" rows="8" cols="100"><?php echo $a_options['description'] ?></textarea>
                        </div>
                    </div>
                </div>
                <!-- 内容三 首页 -->
                <div class="content-wrap content3">
                    <h3>首页栏目设置</h3>
                    <div class="row">
                        <div class="clearfix">
                            <label class="fl left-wrap">设置说明：</label>
                            <div class="fr right-wrap">
                                <img src="<?php bloginfo('template_url'); ?>/images/home_set.png" alt="">
                                <p>ID是分类目录的<span class="highlighted">tag_ID</span>号，在<span class="highlighted">文章 -> 分类目录</span>这个菜单下，这里面的分类目录链接会有这个参数</p>
                            </div>
                        </div>
                        <div class="clearfix">
                            <label class="fl left-wrap" for="cat-id-1">首页第二个栏目分类ID：</label>
                            <div class="fr right-wrap">
                                <input type="text" id="cat-id-1" name="cat-id-1" value="<?php echo $a_options['home_column'][0]['cat_id']; ?>">
                            </div>
                        </div>
                        <div class="clearfix">
                            <label class="fl left-wrap" for="cat-title-1">首页第二个栏目标题：</label>
                            <div class="fr right-wrap">
                                <input type="text" id="cat-title-1" name="cat-title-1" value="<?php echo empty($a_options['home_column'][0]['cat_title']) ? 'Case show' : $a_options['home_column'][0]['cat_title'];?>">
                            </div>
                        </div>
                        <div class="clearfix">
                            <label class="fl left-wrap" for="cat-sub-title-1">首页第二个栏目副标题：</label>
                            <div class="fr right-wrap">
                                <input type="text" id="cat-sub-title-1" name="cat-sub-title-1" value="<?php echo empty($a_options['home_column'][0]['cat_sub_title']) ? '最新案例展示' : $a_options['home_column'][0]['cat_sub_title'];?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="clearfix">
                            <label class="fl left-wrap" for="cat-id-2">首页第三个栏目分类ID：</label>
                            <div class="fr right-wrap">
                                <input type="text" id="cat-id-2" name="cat-id-2" value="<?php echo $a_options['home_column'][1]['cat_id']; ?>">
                            </div>
                        </div>
                        <div class="clearfix">
                            <label class="fl left-wrap" for="cat-title-2">首页第三个栏目标题：</label>
                            <div class="fr right-wrap">
                                <input type="text" id="cat-title-2" name="cat-title-2" value="<?php echo empty($a_options['home_column'][1]['cat_title']) ? 'Note' : $a_options['home_column'][1]['cat_title'];?>">
                            </div>
                        </div>
                        <div class="clearfix">
                            <label class="fl left-wrap" for="cat-sub-title-2">首页第三个栏目副标题：</label>
                            <div class="fr right-wrap">
                                <input type="text" id="cat-sub-title-2" name="cat-sub-title-2" value="<?php echo empty($a_options['home_column'][1]['cat_sub_title']) ? '最新笔记' : $a_options['home_column'][1]['cat_sub_title'];?>">
                            </div>
                        </div>
                    </div>
                    <h3>banner图片设置</h3>
                    <div class="row">
                        <div class="margin-top-15 clearfix">
                            <label class="fl left-wrap" for="">banner图片设置：</label>
                            <div class="fr right-wrap">
                                <input type="text" class="url-inp" name="banner-img" value="<?php echo $a_options['home_banner'][0]['big_img']; ?>">
                                <input type="button" name="img-upload" value="选择文件">
                            </div>
                        </div>
                        <div class="margin-top-15 clearfix">
                            <div class="fl left-wrap">
                                图片预览：
                            </div>
                            <div class="fr right-wrap">
                                <img src="<?php echo $a_options['home_banner'][0]['big_img']; ?>" class="preview-img" width="300" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="margin-top-15 clearfix">
                            <label class="fl left-wrap" for="banner-img-title">banner图片标题：</label>
                            <div class="fr right-wrap">
                                <input type="text" class="url-inp" name="banner-img-title" value="<?php echo $a_options['home_banner'][0]['img_title']; ?>">
                                <input type="button" name="img-upload" value="选择文件">
                            </div>
                        </div>
                        <div class="margin-top-15 clearfix">
                            <div class="fl left-wrap">
                                图片预览：
                            </div>
                            <div class="fr right-wrap">
                                <img src="<?php echo $a_options['home_banner'][0]['img_title']; ?>" class="preview-img" width="300" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="margin-top-15 clearfix">
                            <label class="fl left-wrap" for="banner-cn-title-0">banner中文标题1：</label>
                            <div class="fr right-wrap">
                                <input type="text" id="banner-cn-title-0" name="banner-cn-title-0" value="<?php echo $a_options['home_banner'][0]['cn_title_0']; ?>">
                            </div>
                        </div>
                        <div class="margin-top-15 clearfix">
                            <label class="fl left-wrap" for="banner-cn-title-1">banner中文标题2：</label>
                            <div class="fr right-wrap">
                                <input type="text" id="banner-cn-title-1" name="banner-cn-title-1" value="<?php echo $a_options['home_banner'][0]['cn_title_1']; ?>">
                            </div>
                        </div>
                        <div class="margin-top-15 clearfix">
                            <label class="fl left-wrap" for="banner-en-title-0">banner英文标题1：</label>
                            <div class="fr right-wrap">
                                <input type="text" id="banner-en-title-0" name="banner-en-title-0" value="<?php echo $a_options['home_banner'][0]['en_title_0']; ?>">
                            </div>
                        </div>
                        <div class="margin-top-15 clearfix">
                            <label class="fl left-wrap" for="banner-en-title-1">banner英文标题2：</label>
                            <div class="fr right-wrap">
                                <input type="text" id="banner-en-title-1" name="banner-en-title-1" value="<?php echo $a_options['home_banner'][0]['en_title_1']; ?>">
                            </div>
                        </div>
                    </div>
                    <?php
                    for($i = 0; $i < 3; $i++) {
                    ?>
                    <div class="row">
                        <div class="margin-top-15 clearfix">
                            <label class="fl left-wrap" for="">移动端banner<?php echo $i + 1; ?>：</label>
                            <div class="fr right-wrap">
                                <input type="text" class="url-inp" name="banner-img-<?php echo $i; ?>" value="<?php echo $a_options['mobile_banner']['img_' . $i]; ?>">
                                <input type="button" name="img-upload" value="选择文件">
                            </div>
                        </div>
                        <div class="margin-top-15 clearfix">
                            <div class="fl left-wrap">
                                图片预览：
                            </div>
                            <div class="fr right-wrap">
                                <img src="<?php echo $a_options['mobile_banner']['img_' . $i]; ?>" class="preview-img" width="300" alt="">
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
                <!-- 内容四 社交 -->
                <div class="content-wrap content4">
                    <div class="row">
                        <div class="margin-top-15 clearfix">
                            <label class="fl left-wrap" for="author-des">作者简介（文章详情页用）：</label>
                            <div class="fr right-wrap">
                                <textarea rows="5" cols="100" id="author-des" name="author-des"><?php echo $a_options['author_des']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="margin-top-15 clearfix">
                            <label class="fl left-wrap" for="email">电子邮箱：</label>
                            <div class="fr right-wrap">
                                <textarea rows="5" cols="100" id="email" name="email"><?php echo $a_options['email']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="margin-top-15 clearfix">
                            <label class="fl left-wrap" for="qq-url">QQ链接：</label>
                            <div class="fr right-wrap">
                                <textarea rows="5" cols="100" id="qq-url" name="qq-url"><?php echo $a_options['qq_url']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="margin-top-15 clearfix">
                            <label class="fl left-wrap" for="github-url">Github链接：</label>
                            <div class="fr right-wrap">
                                <textarea rows="5" cols="100" id="github-url" name="github-url"><?php echo $a_options['github_url']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="margin-top-15 clearfix">
                            <label class="fl left-wrap" for="sina-url">新浪微博链接：</label>
                            <div class="fr right-wrap">
                                <textarea rows="5" cols="100" id="sina-url" name="sina-url"><?php echo $a_options['sina_url']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="margin-top-15 clearfix">
                            <label class="fl left-wrap" for="wechat-num">微信号：</label>
                            <div class="fr right-wrap">
                                <textarea rows="5" cols="100" id="wechat-num" name="wechat-num"><?php echo $a_options['wechat_num']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="margin-top-15 clearfix">
                            <label class="fl left-wrap" for="">微信二维码图片：</label>
                            <div class="fr right-wrap">
                                <input type="text" class="url-inp" name="wechat-img" id="wechat-img" value="<?php echo $a_options['wechat_img']; ?>">
                                <input type="button" name="img-upload" value="选择文件">
                            </div>
                        </div>
                        <div class="margin-top-15 clearfix">
                            <div class="fl left-wrap">
                                微信二维码图片预览：
                            </div>
                            <div class="fr right-wrap">
                                <img src="<?php echo $a_options['wechat_img']; ?>" class="preview-img" width="300" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <label class="fl left-wrap" for="link">友情链接：</label>
                        <div class="fr right-wrap">
                            <textarea id="link" name="link" rows="8" cols="100"><?php echo $a_options['link']; ?></textarea>
                        </div>
                    </div>
                </div>
                <!-- 内容五 自定义代码 -->
                <div class="content-wrap content5">
                    <div class="row clearfix">
                        <label class="fl left-wrap" for="home-css">首页自定义css：</label>
                        <div class="fr right-wrap">
                            <textarea id="home-css" name="home-css" rows="8" cols="100"><?php echo $a_options['home_css']; ?></textarea>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <label class="fl left-wrap" for="all-css">全站公用css：</label>
                        <div class="fr right-wrap">
                            <textarea id="all-css" name="all-css" rows="8" cols="100"><?php echo $a_options['all_css']; ?></textarea>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <label class="fl left-wrap" for="footer-js">页面公用js：</label>
                        <div class="fr right-wrap">
                            <textarea id="footer-js" name="footer-js" rows="8" cols="100"><?php echo $a_options['footer_js']; ?></textarea>
                        </div>
                    </div>
                </div>
				<div class="row btn-wrap">
					<input type="submit" class="submit-btn" name="bcn-admin-options" value="保存更改">
				</div>
			</form>
		</div>
        <script src="<?php bloginfo('template_url'); ?>/inc/js/set.js"></script>
<?php
	}
	function themeoptions_update() {
		// 数据提交
        $options = array(
            'aside_count'       => $_POST['aside-count'],
            'title'                   => $_POST['title'],
            'favicon'              => $_POST['favicon-img'],
            'footer_copyright' => $_POST['footer-copyright'],
            'footer_text'         => $_POST['footer-txt'],
            'home_css'          => $_POST['home-css'],
            'all_css'               => $_POST['all-css'],
            'footer_js'            => $_POST['footer-js'],
            'keywords'           => $_POST['keywords'],
            'description'         => $_POST['description'],
            'author_des'         => $_POST['author-des'],
            'email'                 => $_POST['email'],
            'qq_url'                => $_POST['qq-url'],
            'github_url'           => $_POST['github-url'],
            'sina_url'              => $_POST['sina-url'],
            'wechat_num'       => $_POST['wechat-num'],
            'wechat_img'        => $_POST['wechat-img'],
            'link'                    => $_POST['link'],
            'cat_article_num'   => $_POST['cat-article-num'],
            'page_article_num' => $_POST['page-article-num'],
            'vip_style'             => $_POST['vip-style'],
            'home_column'      => array(
                'home_article_num'     => $_POST['home-article-num'],
                array(
                    'cat_id'                  => $_POST['cat-id-1'],
                    'cat_title'                => $_POST['cat-title-1'],
                    'cat_sub_title'         => $_POST['cat-sub-title-1']
                ),
                array(
                    'cat_id'                  => $_POST['cat-id-2'],
                    'cat_title'                => $_POST['cat-title-2'],
                    'cat_sub_title'         => $_POST['cat-sub-title-2']
                ),
            ),
            'home_banner'      => array(
                array(
                    'big_img'      => $_POST['banner-img'],
                    'img_title'     => $_POST['banner-img-title'],
                    'cn_title_0'    => $_POST['banner-cn-title-0'],
                    'cn_title_1'     => $_POST['banner-cn-title-1'],
                    'en_title_0'    => $_POST['banner-en-title-0'],
                    'en_title_1'     => $_POST['banner-en-title-1']
                )
            ),
            'mobile_banner'      => array(
                'img_0'              => $_POST['banner-img-0'],
                'img_1'              => $_POST['banner-img-1'],
                'img_2'              => $_POST['banner-img-2']
            )
        );
        update_option('xm_options', stripslashes_deep($options));
	}
	add_action('admin_menu', 'themeoptions_admin_menu');
?>
