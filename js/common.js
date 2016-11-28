$(function(){
  $window = $(window);
  (function(){
    var $comment = $('#comments ol.comment-list li');
    var $respond = $('#respond .comment-form-comment');
    var arrImg = [ '<img src="http://xuanmomo.com/bg1.jpg">' , '<img src="http://xuanmomo.com/bg2.jpg">' , '<img src="http://xuanmomo.com/bg3.jpg">' , '<img src="http://xuanmomo.com/bg4.jpg">' , '<img src="http://xuanmomo.com/bg5.jpg">' , ];
    var random = 0;
    // 删除head里的style
    $('head style[media="screen"],head style[media="print"]').remove();
    $('.comment-author .says' , $comment).remove();
    // 为没有缩略图的img添加别的图片
    $('article .article-img').each(function(){
      random = Math.floor( Math.random() * arrImg.length );
      if( $(this).children('img').length == 0 ){
        $(this).append(arrImg[random]);
      }
    });
    $('.content img').each(function(){
      $(this).removeAttr('width').removeAttr('height');
    });
  })();
  // 搜索框动画
  var bMark = true;
  var $header = $('header');
  $('.search-txt').blur(function(e){
    e.stopPropagation();
    $(this).val('');
    $header.css('top','-120px');
    bMark = !bMark;
  });
  $('.contact .icon-search').click(function(){
    bMark ? $header.css('top','0') : $header.css('top','-120px');
    bMark = !bMark;
  });
  $('form .icon-close1').click(function(){
    $header.css('top','-120px');
    bMark = !bMark;
  });
  // 无缝滚动
  (function(){
    var i = 1;
    var timer = null;
    var $bannerBox = $('.roll-banner');
    var $bannerLi = $('li',$bannerBox);
    var $btn = $('.plugin-banner-btn');
    var $li_btn;
    var x , _x;
    $bannerLi.css('width' , $bannerBox.parent().width())
             .parent().css('left' , -$bannerLi.width());
    // 复制第一张到最后一张后面
    $bannerLi.eq($bannerLi.length - 1).after( $bannerLi.eq(0).clone() );
    // 复制最后一张图片到第一张前面
    $bannerLi.eq(0).before($bannerLi.eq( $bannerLi.length - 1).clone() );
    $bannerLi = $('li',$bannerBox)
    var $bannerLength = $('li',$bannerBox).length;
    // 给图片ul计算宽度
    $bannerBox.css('width',$bannerLength * $bannerLi.width() + 'px');
    for( var j = 0; j < $bannerLength - 2; j++ ){
      $btn.append('<li></li>')
    }
    $li_btn = $('li',$btn);
    $li_btn.eq(0).addClass('on');
    $btn.css('margin-left',$btn.width() / -2);
    // 窗口改变大小给图片重新计算宽度
    $window.resize(function(){
      $bannerLi.css('width' , $bannerBox.parent().width())
               .parent().css('left' , -$bannerLi.width());
      $bannerBox.css('width',$bannerLength * $bannerLi.width() + 'px');
    });
    // 小圆点切换
    $li_btn.click(function(){
      if( !$bannerBox.is(':animated') ){
        i = $(this).index() + 1;
        cut( $bannerBox , $(this) , 1000 );
      }
    });
    $('.mobile-banner').on('touchstart' , function(e){
      x = e.originalEvent.touches[0].pageX;
    })
    .on('touchmove' , function(e){
      e.preventDefault();
      _x = e.originalEvent.touches[0].pageX;
    })
    .on('touchend' , function(){
      var differ = x - _x > 0 ? 1 : 0;
      if( differ ){
        if( !$bannerBox.is(':animated') ){
          i++;
          if( i > $bannerLength - 1 ){
            i = 2;
            $bannerBox.css( 'left' , -$bannerLi.width() );
          }else if( i > $bannerLength - 2 ){
            $li_btn.eq(0).addClass('on').siblings().removeClass('on');
          }
          cut( $bannerBox , $li_btn.eq(i - 1) , 1000 );
        }
      }else{
        if( !$bannerBox.is(':animated') ){
          i--;
          if( i < 0 ){
            i = $bannerLength - 3;
            $bannerBox.css( 'left' , ($bannerLength - 2) * -$bannerLi.width() );
          }
          cut( $bannerBox , $li_btn.eq(i - 1) , 1000 );
        }
      }
    });
    function cut( obj_box , obj_btn , time ){
      obj_btn.addClass('on').siblings().removeClass('on');
      obj_box.animate({ 'left' : i * -$bannerLi.width() + 'px' },time);
    }
  })();
  // 显示导航菜单
  (function(){
    var $nav = $('nav');
    var $menu = $('ul.menu > li');
    var bMark = true;
    if( $(this).width() < 981 ) {
      $('a.icon-menu-list2').click(function(e){
        e.stopPropagation();
        if( bMark ){
          $nav.css({
            'opacity' : '1',
            'transform' : 'translateX(0)'
          });
        }else{
          $nav.css({
            'opacity' : '0',
            'transform' : 'translateX(100%)'
          });
        }
        bMark = !bMark;
      });
      // 手机导航判断有ul子元素禁止a跳转
      $menu.each(function(){
        if( $(this).children('ul').length >= 1 ){
          $(this).children('li > a').attr('href','javascript:;')
        }
      });
      // 菜单打开与闭合
      $menu.click(function(e){
        e.stopPropagation();
        $(this).children('ul').slideToggle().parent().siblings().children('ul').slideUp();
      });
    }
  })();
  // 返回顶部动画
  var $backTop = $('div.icon-backtop');
  $window.on('scroll' , function(){
    var $this = $(this);
    var $scroll = $(this).scrollTop();
    if( $scroll > 100 ){
      $header.addClass('on');
      $('ul.sub-menu' , $header).addClass('bg282828');
      $backTop.css('right','30px');
    }else if( $scroll < 100 ){
      $header.removeClass('on');
      $('ul.sub-menu' , $header).removeClass('bg282828');
      $backTop.css('right','-50px');
    }
  });
  $backTop.click(function(){
    $('body,html').animate({scrollTop : 0},800);
  });
  // 放大图片预览
  (function(){
    var $content = $('.content');
    var $img;
    var i = 0;
    $('.content img').each(function(n){
      $(this).click(function(){
        i = n;
        $('.content img').clone().appendTo($('.cover-img'));
        $img = $('.cover-img img');
        $('.cover').show();
        $img.eq(i).show();
      });
    });
    $('.cover .icon-menu-left').click(function(){
      i--;
      if( i < 0 ) {
        i = 0;
        tipsShow();
        return;
      }
      $img.eq(i).show().siblings().hide();
    });
    $('.cover .icon-menu-right').click(function(){
      i++;
      if( i > $img.length - 1 ){
        i = $img.length - 1;
        tipsShow();
        return;
      }
      $img.eq(i).show().siblings().hide();
    });
    function tipsShow(){
      $('.cover p').css({
        'opacity' : '1',
        'transform' : 'translateY(0px)'
      });
      setTimeout(function(){
        $('.cover p').css({
          'opacity' : '0',
          'transform' : 'translateY(-30px)'
        });
      },1500);
    }
    $('.cover-hide , .icon-close1').click(function(){
      $('.cover').hide();
      $('.cover-img').children().remove();
    });
  })();
  // 标签云
  (function(){
    $('.tag-cloud a').each(function(){
      $(this).contents().filter(function(){
        return this.nodeType
      })
      .before('<i class="iconfont icon-tag1"></i> ');
    });
  })();
});
