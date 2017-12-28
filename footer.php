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
      // 移动端轮播
      $(".roll-banner").seamlessBanner({
        autoBanner: false,
        bannerBtnWrap: $(".plugin-banner-btn"),
        transition: 700
      });

      // 滚动加载
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
            if (n == res[0].total_article) $moreBtn.unbind('click').text('我是有底线的 ^_^');
            $moreBtn.show().siblings().hide();
            var sResult = '';
            res.forEach(function (val) {
              sResult += '<article id="post-' + val.id + '" class="mobile-article-lg on"><a href="' + val.link + '" class="article-img"><img src="' + (val._embedded["wp:featuredmedia"] ? val._embedded["wp:featuredmedia"][0].source_url : 'https://upyun.xuanmo.xin/blog/bg4.jpg') + '" class="black" alt=""></a><div class="con"><h2 class="article-title"><a href="' + val.link + '">' + val.title.rendered + '</a></h2><div class="time"><time class="ccc">' + val.date.replace('T', ' ') + '</time><span class="iconfont icon-comment1 ccc"></span>' + val.comments_views + '<span class="iconfont icon-fire ccc"></span>' + (val.post_meta_field.post_views_count ? val.post_meta_field.post_views_count : 0) + '<span class="iconfont icon-thumbs-up1"></span>' + (val.xm_link ? val.xm_link.very_good : 0) + '</div><p class="summary">' + val.summary + '</p></div></article>';
            });
            $('#total-article').append(sResult);
          },
          error: function(err) {
            console.log(err);
          }
        });
      }
    });
  </script>
  <?php
  } else if (is_single()) {
  ?>
  <script>
    $(function() {
      // 文章二维码
      var $qrcode = $('#qrcode');
      var oQRCode = new QRCode(document.getElementById('qrcode') , {
        'width' : 150,
        'height' : 150
      });
      oQRCode.makeCode( $('#article-link').text() );
      $('#qrcode img').after($('#qrcode i'));
      $('.wechat').click(function(){
        $qrcode.css('display','block');
      });
      $('#qrcode .icon-close1').click(function(e){
        e.stopPropagation();
        $qrcode.css('display','none');
      });

      // 文章点赞
      $('.link-wrap .link').click(function() {
        if ($('.link-wrap').hasClass('active')) {
          alert('您已经发表意见了！');
        } else {
          var $this = $(this);
          var d = new Date();
          d.setHours(d.getHours() + (24 * 30));
          document.cookie = 'post_link_' + $this.data('id') + '=active; expires=' + d.toGMTString();
          $('.link-wrap').addClass('active');
          $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
              action: 'xm_post_link',
              post_id: $this.data('id'),
              post_key: $this.data('key'),
            },
            success: function(res) {
              $this.find('.people-num').text(res);
            }
          });
        }
      });

      // 评论翻页
      ajaxComments();
      function ajaxComments() {
        $('.comment-page-btn a').click(function() {
          $('.comment-list').addClass('on');
          $.ajax({
            url: $(this).attr('href'),
            success: function(res) {
              $('.comment-list').removeClass('on').html($(res).find('.comment-list').html());
              $('.comment-page-btn').html($(res).find('.comment-page-btn').html());
              ajaxComments();
            }
          });
          return false;
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
