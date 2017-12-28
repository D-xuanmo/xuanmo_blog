$(function() {
  var $window = $(window);
  var $header = $('header');

  // 为文章列表没有图片的添加默认缩略图
  (function() {
    var $comment = $('#comments ol.comment-list li');
    var arrImg = [
      'https://upyun.xuanmo.xin/blog/bg1.jpg',
      'https://upyun.xuanmo.xin/blog/bg2.jpg',
      'https://upyun.xuanmo.xin/blog/bg3.jpg',
      'https://upyun.xuanmo.xin/blog/bg4.jpg',
      'https://upyun.xuanmo.xin/blog/bg5.jpg'
    ];
    var random = 0;
    // 删除head里的style
    $('head style[media="screen"], head style[media="print"]').remove();
    $('.comment-author .says', $comment).remove();
    // 为没有缩略图的img添加别的图片
    $('article .article-img').each(function() {
      random = Math.floor(Math.random() * arrImg.length);
      var $thisImg = $(this).children('img');
      if ($thisImg.attr('src') == '') {
        $thisImg.attr('src', arrImg[random]);
      } else if (Number($thisImg.attr('height')) < 150) {
        $thisImg.css({
          'height': $thisImg.attr('height'),
          'vertical-align': 'middle'
        });
      }
    });
    $('.content img').each(function() {
      $(this).removeAttr('width').removeAttr('height');
    });
  })();

  // 搜索框动画
  (function() {
    var bMark = true;
    $('.search-txt')
      .click(function(e) {
        e.stopPropagation();
      })
      .blur(function(e) {
        e.stopPropagation();
        $(this).val('');
        $header.css('top', '-120px');
        bMark = true;
      });
    $('.contact .icon-search').click(function(e) {
      e.stopPropagation();
      bMark ? $header.css('top', '0') : $header.css('top', '-120px');
      bMark = !bMark;
    });
    $('form .icon-close1, body').click(function() {
      $header.css('top', '-120px');
      bMark = true;
    });
  })();

  // 滚动显示
  scrollAnimate({
    obj: $('.main article , .right article')
  });
  function scrollAnimate(options) {
    var y = 0;
    options.obj.each(function () {
      var $this = $(this);
      if ($(window).scrollTop() > $(this).offset().top - $(window).height() / 2 - $(this).height()) {
        setTimeout(function () {
          $this.addClass('on');
        }, 0);
      }
    });
    $(window).bind('scroll', function () {
      y = $(this).scrollTop();
      options.obj.each(function () {
        if (y > $(this).offset().top - $(window).height() / 2 - $(this).height() / 2) {
          $(this).addClass('on');
          if (options.mode == 'transition') {
            $(this).bind('webkitTransitionEnd transitionend', function () {
              options.callback && options.callback($(this));
            });
          }
        }
      });
    });
  }

  // 手机端显示导航菜单
  (function() {
    var $nav = $('nav'),
      $menuList = $('ul.menu > li'),
      y = 0,
      _y = 0;

    $('.icon-menu-list2').bind('touchstart', function() {
      $nav.css('left', 0);
    });
    $nav.bind('touchstart', function(e) {
        y = e.originalEvent.touches[0].pageX;
      })
      .bind('touchmove', function(e) {
        _y = e.originalEvent.touches[0].pageX;
        if (y - _y > 20) $(this).css('left', '-100%');
      });
    $menuList.bind('click', function() {
      $(this).children('.sub-menu').stop().slideToggle()
        .parent().siblings()
        .children('.sub-menu').slideUp();
    });
    $menuList.each(function() {
      $(this).children('a').attr('data-href', function() {
        return $(this).attr('href');
      });
    });
    removeLink();
    $window.bind('resize', removeLink);

    function removeLink() {
      if (navigator.appVersion.match('iPhone') || navigator.appVersion.match('Android')) {
        $menuList.each(function() {
          if ($(this).children('.sub-menu').length)
            $(this).children('a').attr('href', 'javascript:;');
        });
      } else {
        $menuList.children('a').attr('href', function() {
          return $(this).attr('data-href');
        })
      }
    }
  })();

  // 判断浏览器滚动高度
  (function() {
    var $scroll;
    var $backTop = $('div.icon-backtop');
    $window.on('scroll', function() {
      $scroll = $(this).scrollTop();
      if ($scroll > 100) {
        // $header.addClass('on');
        $('ul.sub-menu', $header).addClass('bg282828');
        $backTop.css('right', '30px');
      } else if ($scroll < 300) {
        // $header.removeClass('on');
        $('ul.sub-menu', $header).removeClass('bg282828');
        $backTop.css('right', '-50px');
      }
    });
    $backTop.click(function() {
      $('html,body').animate({
        scrollTop: 0
      }, 800);
    });
    $(window).bind('onmousewheel mousewheel DOMMouseScroll', function(e) {
      // 判断滚动的方向
      var differ = e.originalEvent.detail
                  ? ( e.originalEvent.detail > 0 ? 1 : 0 )
                  : ( e.originalEvent.wheelDelta > 0 ? 0 : 1 );
      differ ? $header.removeClass('on') : $header.addClass('on');
    });
  })();

  // 放大图片预览
  (function() {
    var $img, i = 0;
    $('.content img').each(function(n) {
      $(this).click(function() {
        i = n;
        $('.content img').clone().appendTo($('.cover-img'));
        $img = $('.cover-img img');
        $('.cover').show();
        $img.eq(i).show();
      });
    });
    $('.cover .icon-menu-left').click(function() {
      i--;
      if (i < 0) {
        i = 0;
        tipsShow();
        return;
      }
      $img.eq(i).show().siblings().hide();
    });
    $('.cover .icon-menu-right').click(function() {
      i++;
      if (i > $img.length - 1) {
        i = $img.length - 1;
        tipsShow();
        return;
      }
      $img.eq(i).show().siblings().hide();
    });

    function tipsShow() {
      $('.cover p').css({
        'opacity': '1',
        'transform': 'translateY(0px)'
      });
      setTimeout(function() {
        $('.cover p').css({
          'opacity': '0',
          'transform': 'translateY(-30px)'
        });
      }, 1500);
    }
    $('.cover-hide , .cover .icon-close1').click(function() {
      $('.cover').hide();
      $('.cover-img').children().remove();
    });
  })();

  // 文章页微信显示与关闭
  $('.article-about-author .share-btn a:nth-of-type(3)').click(function() {
    // 微信盒子显示
    $(this).children('span').show()
      // 关闭按钮隐藏span
      .children('i').click(function(e) {
        e.stopPropagation();
        $(this).parent('span').hide();
      });
  });

  // 验证码功能
  (function() {
    var canvas = document.querySelector('.canvas-img-code');
    if (canvas) {
      var ctx = canvas.getContext('2d'),
        $submit = $('#submit');
      // 禁止提交按钮
      $submit.prop('disabled', true).css('backgroundColor', '#b5b5b5');
      var nResult = result();
      // 验证码功能
      $('#img-code').keyup(function() {
        if (Number($(this).val()) == nResult) {
          $(this).css('borderColor', '#7e7e7e');
          $submit.prop('disabled', false).css('backgroundColor', '#16c0f8');
        } else {
          $(this).css('borderColor', '#f00');
          $submit.prop('disabled', true).css('backgroundColor', '#b5b5b5');
        }
      });
      // 换一张验证码
      $('.tab-img-code').click(function() {
        nResult = result();
        $('#img-code').val('');
        $submit.prop('disabled', true).css('backgroundColor', '#b5b5b5');
      });

      function result() {
        var nRandom1 = Math.floor(Math.random() * 10 + 5),
          nRandom2 = Math.floor(Math.random() * 5),
          nRandomResult = Math.floor(Math.random() * 3),
          aOperator = ['+', '-', '*'],
          nProcess = nRandom1 + aOperator[nRandomResult] + nRandom2;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.font = '20px microsoft yahei';
        ctx.fillStyle = '#333';
        ctx.fillText(nRandom1 + ' ' + aOperator[nRandomResult] + ' ' + nRandom2 + ' = ?', 10, 23);
        return eval(nProcess);
      }
    }
  })();

  // 加载表情
  (function() {
    var bMark = true,
      bExpression = true;
    $('.first-img').click(function(e) {
      e.stopPropagation();
      if (bMark) {
        $(this).siblings('.expression-hide-wrap').show();
        if (bExpression) {
          $.ajax({
            url: $(this).siblings('.express-url').text(),
            type: 'POST',
            success: function(data) {
              $('.expression-hide-wrap').children().remove();
              $('.expression-hide-wrap').append(data);
              bExpression = false;
            }
          });
        }
      } else {
        $(this).siblings('.expression-hide-wrap').hide();
      }
      bMark = !bMark;

      $('body').bind('click', function() {
        $(this).unbind('click');
        if (bMark == false) $('.expression-hide-wrap').hide();
        bMark = true;
      });
    });

    // 添加图片
    var data = new FormData();
    $('#comments-upload-img').change(function() {
      var path = $(this).siblings('.path').text();
      if ($(this).get(0).files[0].size / 1024 > 1024) {
        alert('请上传小于1024Kb的图片！');
      } else {
        data.append('file', $(this).get(0).files[0]);
        ajax({
          url: path + '/xm_upload.php',
          type: 'POST',
          dataType: 'json',
          data: data,
          success: function(res) {
            $('#comment').val(function() {
              return $(this).val() + '[img]' + path + '/images/comments/' + res.name + '[/img]';
            });
          }
        });
      }
    });

    // 显示代码按钮
    $('.comment-code-btn-wrap').click(function() {
      $(this).children('p').slideToggle();
    });

    $('.comment-code-btn-wrap .comment-code-btn').click(function(e) {
      e.stopPropagation();
      var $this = $(this);
      $("#comment").val(function() {
        return $(this).val() + ' <pre class="line-numbers language-' + $this.text() + '"><code class="language-' + $this.text() + '"></code></pre>';
      });
    });
  })();

  (function () {
    $('.new-article .cur').click(function () {
      $(this).addClass('on').siblings().removeClass('on');
      $('.list-article-title').eq($(this).index()).show().siblings().hide();
    });
  })();
});
