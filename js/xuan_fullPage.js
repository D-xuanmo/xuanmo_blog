/**
* Author: xuanmo
* Version: v1.0
* 基于jquery的全屏滚动插件
*/
(function($){
  $.fn.fullScreen = function( mJson ){
    var _index;
    this.callback = mJson.callback;
    this.aImg = mJson.images;
    this.time = mJson.time || 1000;
    init.call(this);
  }
  // 初始化
  function init(){
    var $this = this;
    this.after('<ul class="ui-tab"></ul>');
    var $uiTab = $('.ui-tab');
    this.attr('data-index','0');
    var initElement = $this.children('.ui-page').length > 0  ?  $this.children('.ui-page') : $this.aImg;
    $.each(initElement , function(i){
      // 生成图片
      if( $this.aImg ) $this.aImg[i].match(/^\#/) ? $this.append('<li class="ui-page" style="background:' + $this.aImg[i] + '"></li>') : $this.append('<li class="ui-page" style="background:url(' + $this.aImg[i] + ') no-repeat" /></li>');
      // 生成小圆点按钮
      $uiTab.append('<li class="action"></li>');
    });
    var $uiBtn = $('.action',$uiTab);
    var $uiPage = $('.ui-page',this);
    // 给第一个按钮添加默认class
    $uiBtn.eq(0).addClass('on');
    // 小圆点大盒子计算高度
    $uiTab.css({
      'height' : function(){
        return $(this).children('.action').length * $(this).children('.action').innerHeight;
      },
      'margin-top' : function(){
        return $(this).height() / -2;
      }
    });
    roll.call(this , $uiPage , $uiBtn);
  }
  // 滚动部分
  function roll($uiPage , $uiBtn ){
    var $this = this;
    var dire , differ;
    var y = 0;
    var _y = 0;
    var touch = null;
    _index = this.attr('data-index')
    var maxIndex = $uiPage.length - 1;
    // pc端滚动
    $this.on('onmousewheel mousewheel DOMMouseScroll' , function(e){
      // 火狐和非火狐兼容代码
      dire = e.originalEvent.detail ? (e.originalEvent.detail > 0 ? 1 : 0) : (e.originalEvent.wheelDelta > 0 ? 0 : 1);
      _index = $this.attr('data-index');
      if( dire ){
        if( ++_index > maxIndex ){
          _index = maxIndex;
        }
      }else{
        if( --_index < 0 ){
          _index = 0;
        }
      }
      _index = _index;
      if( !$this.is(':animated') ){
        rollFn( _index );
      }
    });
    $(document).on('touchstart' , function(e){
      y = e.originalEvent.touches[0].pageY;
    }).on('touchmove' , function(e){
      e.preventDefault();
      _y = e.originalEvent.touches[0].pageY;
    }).on('touchend' , function(){
      differ = y - _y > 0 ? 0 : 1;
      if( differ ){
        if( --_index < 0 ) {
          _index = 0;
        }
      }else{
        if( ++_index > maxIndex ) {
          _index = maxIndex;
        }
      }
      _y = _y;
      _index = _index;
      rollFn( _index );
    });
    // 默认点击方式
    $('.action').click(function(i){
      if( !$this.is(':animated') ){
        _index = $(this).index();
        rollFn( _index );
      }
    });
    function rollFn( i ){
      $uiBtn.eq(i).addClass('on').siblings().removeClass('on');
      $this.animate({'top' : '-' + i + '00%'} , $this.time , function(){
        $this.callback && $this.callback( i );
      });
      $this.attr('data-index' , i);
    }
  }
})(jQuery);
