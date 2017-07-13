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
                    <a href="https://www.aliyun.com">
                        <img src="<?php bloginfo('template_url'); ?>/images/aliyun.png" width="50" alt="" />
                        <span>阿里云</span>
                    </a>
                    <a href="https://portal.qiniu.com/signup?code=3lga7p37qtkcy">
                        <img src="<?php bloginfo('template_url'); ?>/images/qiniuyun.png" width="50" alt="" />
                        <span>七牛云</span>
                    </a>
                    <a href="https://verify.nic.xin/xinDetail/xinAuthInfoDetail?domainName=xuanmo.xin" target="_blank">
                        <!-- <img src="https://verify.nic.xin/api/domain/showCreditLogo.do?logoToken=8684925de81ba07468c5842304116843" alt="" /> -->
                        <img src="https://img.alicdn.com/tps/TB1.fDdNpXXXXajXpXXXXXXXXXX-96-42.png" alt="" />
                    </a>
                </div>
            </div>
            <div class="copyright-wrap clearfix">
                <p class="fl copyright"><?php echo stripslashes(get_option('x_copyright')); ?></p>
                <p class="fr">Theme by <a href="https://www.xuanmo.xin">Xuanmo</a></p>
            </div>
        </div>
    </footer>
    <!-- footer end -->
    <div class="iconfont icon-backtop"></div>
    <script src="<?php bloginfo('template_url'); ?>/js/common.js"></script>
    <script><?php echo stripslashes(get_option('x_javascript')); ?></script>
</body>
</html>
