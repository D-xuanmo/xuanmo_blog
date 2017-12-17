  <?php
    wp_reset_query();
    if( is_home() ) echo '<div class="wrap footer-link">' . get_option('xm_options')['link'] . '</div>';
  ?>
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
          <a href="https://console.upyun.com/register/?invite=BylFlCEWG">
            <img src="https://upyun.xuanmo.xin/upyun.png" width="60" alt="" />
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
    if (is_home()) {
  ?>
  <script>
    $(function() {
      var nPage = 1,
          $moreBtn = $('.more-btn .text');
      getMoreArticle(nPage);
      $moreBtn.click(function() {
        $(this).hide().siblings().show();
        nPage++;
        getMoreArticle(nPage);
      });
      function getMoreArticle(n) {
        $.ajax({
          url: '<?php echo home_url(); ?>/wp-json/wp/v2/posts',
          data: {
            page: n,
            per_page: 3,
            _embed: true
          },
          success: function(res) {
            if (n == res[0].total_article) $moreBtn.unbind('click').text('--我是有底线的--');
            $moreBtn.show().siblings().hide();
            var sResult = '';
            var aCookie = document.cookie.split(';');
            res.forEach(function (val) {
              sResult += '<article id="post-' + val.id + '" class="mobile-article-lg on">\n                  <a href="' + val.link + '" class="article-img">\n                    <img src="' + (val._embedded["wp:featuredmedia"] ? val._embedded["wp:featuredmedia"][0].source_url : 'https://upyun.xuanmo.xin/blog/bg4.jpg') + '" class="black" alt="">\n                  </a>\n                  <div class="con">\n                    <h2 class="article-title">\n                      <a href="' + val.link + '">' + val.title.rendered + '</a>\n                    </h2>\n                    <div class="time">\n                      <time class="ccc">' + val.date.replace('T', ' ') + '</time>\n                      <span class="iconfont icon-comment1 ccc"></span>' + val.comments_views + '\n                      <span class="iconfont icon-fire ccc"></span>' + val.post_meta_field.post_views_count + '\n                      <a href="javascript:void(0);" data-action="ding" data-id="' + val.id + '" class="cur ccc link-btn">\n                        <span class="iconfont icon-thumbs-up1"></span>\n                        <span class="hide blog-url"><?php echo home_url(); ?>/wp-admin/admin-ajax.php</span>\n                        <span class="count">' + (val.post_meta_field.bigfa_ding ? val.post_meta_field.bigfa_ding : 0) + '</span>\n                      </a>\n                    </div>\n                    <p class="summary">' + val.summary + '</p>\n                  </div>\n                </article>';
            });
            $('#total-article').append(sResult);
            $('.link-btn').click(function() {
              var $this = $(this);
              $.each(aCookie, function(i, val) {
                if (val.indexOf('bigfa_ding_' + $this.data('id')) == 1) $this.addClass('done');
              });
              if ($(this).hasClass('done')) {
                alert('^_^您已赞过此文章了');
              } else {
                $this.addClass('done');
                document.cookie = 'bigfa_ding_' + $this.data('id') + '=' + $this.data('id');
                $.ajax({
                  url: $this.children('.blog-url').text(),
                  type: 'POST',
                  data: {
                    action: "bigfa_like",
                    um_id: $this.data("id"),
                    um_action: $this.data('action')
                  },
                  success: function(data) {
                    $this.children('.count').text(data);
                  }
                });
              }
            });
          },
          error: function(err) {
            console.log(err);
          }
        });
      }
    });
  </script>
  <?php
    }
    $js_code = get_option('xm_options')['footer_js'];
    if (!empty($js_code)) echo '<script>' . $js_code . '</script>';
  ?>
</body>
</html>
