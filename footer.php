    <div class="wrap footer-link">
        <?php echo get_option('x_links'); ?>
    </div>
    <!-- footer start -->
    <footer>
        <div class="wrap">
            <div class="clearfix">
                <div class="fl footer-left clearfix">
                    <h2 class="fl footer-blog-name"><?php echo bloginfo('name'); ?></h2>
                    <p class="fl introduce"><?php echo stripslashes(get_option('x_words')); ?></p>
                </div>
                <div class="fr footer-right">
                    <a href="http//cn.wordpress.org/">
                        <img src="<?php bloginfo('template_url'); ?>/images/wordpress-logo.png" width="50" alt="" />
                        <span>WordPress</span>
                    </a>
                    <a href="http://www.aliyun.com">
                        <img src="<?php bloginfo('template_url'); ?>/images/aliyun.png" width="50" alt="" />
                        <span>阿里云</span>
                    </a>
                    <a href="https://portal.qiniu.com/signup?code=3lga7p37qtkcy">
                        <img src="<?php bloginfo('template_url'); ?>/images/qiniuyun.png" width="50" alt="" />
                        <span>七牛云</span>
                    </a>
                </div>
            </div>
            <div class="copyright-wrap clearfix">
                <p class="fl copyright"><?php echo stripslashes(get_option('x_copyright')); ?></p>
                <p class="fr">Theme by <a href="http://www.xuanmomo.com">Xuanmo</a></p>
            </div>
        </div>
    </footer>
    <!-- footer end -->
    <div class="iconfont icon-backtop"></div>
    <script src="<?php bloginfo('template_url'); ?>/js/common.js" async="async"></script>
    <script type="text/javascript">
    //xuanmomo.com Baidu tongji analytics
    var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "https://hm.baidu.com/hm.js?e4f9f13922648691dd3ce1a36c073884";
      var s = document.getElementsByTagName("script")[0];
      s.parentNode.insertBefore(hm, s);
    })();
    </script>
</body>
</html>
