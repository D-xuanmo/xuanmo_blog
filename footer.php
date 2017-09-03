    <div class="wrap footer-link">
        <?php if( !is_page() ) echo get_option('xm_options')['link']; ?>
    </div>
    <!-- footer start -->
    <footer>
        <div class="wrap">
            <div class="clearfix">
                <div class="fl footer-left clearfix">
                    <h2 class="fl footer-blog-name"><?php echo bloginfo('name'); ?></h2>
                    <div class="fl introduce"><?php echo get_option('xm_options')['footer_text']; ?></div>
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
                    <?php
                        if(strpos(home_url(), 'xuanmo.xin')) {
                            echo '
                                <a href="https://verify.nic.xin/xinDetail/xinAuthInfoDetail?domainName=xuanmo.xin" target="_blank">
                                    <img src="https://www.xuanmo.xin/images/xin.png" alt="" />
                                </a>
                            ';
                        }
                    ?>
                </div>
            </div>
            <div class="copyright-wrap clearfix">
                <div class="fl copyright"><?php echo get_option('xm_options')['footer_copyright']; ?></div>
                <p class="fr">Theme by <a href="https://www.xuanmo.xin">Xuanmo</a></p>
            </div>
        </div>
    </footer>
    <!-- footer end -->
    <div class="iconfont icon-backtop"></div>
    <?php
    $js_code = get_option('xm_options')['footer_js'];
    if (!empty($js_code)) echo '<script>' . $js_code . '</script>';
    ?>
</body>
</html>
