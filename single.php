<?php include('header-article.php'); ?>
<section>
  <div class="wrap clearfix">
    <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
      <?php setPostViews(get_the_ID());//记录阅读 ?>
      <article class="fl single-article">
        <div class="breadcrumbs">
          <span>当前位置：</span>
          <?php if (function_exists('dimox_breadcrumbs')) dimox_breadcrumbs(); ?>
          <span>正文</span>
        </div>
        <div class="header">
          <a href="<?php the_permalink(); ?>"><h2><?php the_title(); ?></h2></a>
          <?php the_author_posts_link(); ?>
          <span>发布于</span>
          <time><?php the_time('Y-m-d H:i'); ?></time>
          <span>分类:</span>
          <a href="#"><?php foreach((get_the_category()) as $category) { echo $category->cat_name; } ?></a>
          <span class="iconfont icon-comment1"><?php echo get_comments_number(); ?></span>
          <span class="iconfont icon-eye-open"><?php echo getPostViews(get_the_ID()); ?></span>
          <?php edit_post_link(); ?>
        </div>
        <div class="content">
            <?php the_content(); ?>
        </div>
        <p class="like">
          <a href="javascript:;" data-action="ding" data-id="<?php the_ID(); ?>" class="favorite<?php if(isset($_COOKIE['bigfa_ding_'.$post->ID])) echo ' done';?>">
            <i class="iconfont icon-thumbs-up2"></i>赞
            <span class="count">
              <?php
                if( get_post_meta($post->ID,'bigfa_ding',true) ){
                  echo get_post_meta($post->ID,'bigfa_ding',true);
                } else {
                  echo '0';
                }
              ?>
            </span>
          </a>
        </p>
        <p class="share">
          分享到：
          <a href="http://connect.qq.com/widget/shareqq/index.html?url=<?php echo home_url('/'); ?>%3Fp%3D<?php the_ID(); ?>&title=【<?php the_title(); ?> | <?php bloginfo('name'); ?>】&summary=" class="qq" target="blank" title="分享到QQ好友"></a>
          <a href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo home_url('/'); ?>%3Fp%3D<?php the_ID(); ?>&title=【<?php the_title(); ?> | <?php bloginfo('name'); ?>】&summary=" class="qqzone" target="blank" title="分享到QQ空间"></a>
          <a href="javascript:;" class="wechat" title="分享到微信">
            <span id="qrcode">
              <i class="iconfont icon-close1"></i>
              <strong>分享到朋友圈</strong>
              <i>
                打开微信，点击底部的“发现”，<br />
                使用“扫一扫”即可将网页分享至朋友圈。
              </i>
            </span>
          </a>
          <a href="http://service.weibo.com/share/share.php?url=<?php echo home_url('/'); ?>%3Fp%3D<?php the_ID(); ?>%230-tsina-1-21107-397232819ff9a47a7b7e80a40613cfe1&title=【<?php the_title(); ?> | <?php bloginfo('name'); ?>】&appkey=1343713053&searchPic=true#_loginLayer_1473259217614" class="sina" target="blank" title="分享到新浪微博"></a>
          <a href="http://tieba.baidu.com/f/commit/share/openShareApi?url=<?php echo home_url('/'); ?>%3Fp%3D<?php the_ID(); ?>&desc=<?php the_title(); ?> | <?php bloginfo('name'); ?>&comment=" class="baidu" target="blank" title="分享到百度贴吧"></a>
        </p>
        <?php endwhile; ?>
      	<p class="mark">
      		<?php the_tags('<strong class="iconfont icon-tag"></strong>', ' '); ?>
      	</p>
        <?php comments_template(); ?>
        <div class="tab-article clearfix">
          <div class="fl tab-left">
            <p>
              <!-- 上一篇： -->
              <a href="javascript:;">
                <?php if (get_previous_post($categoryIDS)) {
                  previous_post_link('上一篇：%link','%title',true);
                } else {
                  echo "没有了，已经是最新文章";
                } ?>
              </a>
            </p>
          </div>
          <div class="tab-right fr">
            <p>
              <!-- 下一篇： -->
              <a href="javascript:;">
                <?php if (get_next_post($categoryIDS)) {
                  next_post_link('下一篇：%link','%title',true);
                } else {
                  echo "没有了，已经是最后文章";
                } ?>
              </a>
            </p>
          </div>
        </div>
      </article>
    <?php endif; ?>
    <div class=" mobile-hide aside-fr fr">
      <?php get_sidebar(); ?>
    </div>
  </div>
  <div class="cover">
    <div class="cover-img"></div>
    <div class="cover-hide"></div>
    <i class="iconfont icon-menu-left"></i>
    <i class="iconfont icon-menu-right"></i>
    <p>已经没有了！</p>
  </div>
</section>
<?php get_footer(); ?>
