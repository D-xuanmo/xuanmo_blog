/**
* Author: xuanmo
* Version: v1.0
* 基于jquery的全屏滚动插件
*/
(function($) {
    function Page( element, options ) {
        this.pageList = options.pageList;
        this.btnList = null;
        this.differ = 1;
        this.dire = 1;
        this.nIndex = element.attr('data-index'),
        this.pageBtnWrap = options.pageBtnWrap;
        this.beforeFunction = options.beforeFunction;
        this.callback = options.callback;
        this.maxIndex = this.pageList.length - 1;
        this.transition = options.transition;
    }

    Page.prototype = {
        constructor: Page,
        // 初始化
        init: function( $this ) {
            var This = this;
            // 创建滚动按钮容器
            $this.attr('data-index', 0);
            this.pageBtnWrap
                =  this.pageBtnWrap
                || (function() { $this.after('<ul class="ui-btn-wrap"></ul>'); return $('.ui-btn-wrap'); }());

            // 生成滚动按钮
            this.pageList.each(function(i) {
                This.pageBtnWrap.append('<li></li>');
            });

            // 保存滚动按钮并为第一个添加class
            this.btnList = this.pageBtnWrap.children();
            this.btnList.first().addClass('on');

            // 滚动按钮容器计算顶部偏移量
            this.pageBtnWrap.css('marginTop', this.btnList.first().outerHeight(true) * this.btnList.length / -2);

            this.beforeFunction && this.beforeFunction();
            this.scrollFn( $this );
            this.clickRoll( $this );
        },
        // 处理浏览器滚动方向
        scrollFn: function( $this ) {
            var This = this,
                y = 0,
                _y = 0
                touch = null,

            // pc端滚动
            // DOMMouseScroll 火狐浏览器滚动事件
            $(window).bind('onmousewheel mousewheel DOMMouseScroll', function(e) {
                // e.preventDefault();
                // 判断滚动的方向
                This.differ = e.originalEvent.detail
                            ? ( e.originalEvent.detail > 0 ? 1 : 0 )
                            : ( e.originalEvent.wheelDelta > 0 ? 0 : 1 );
                This.nIndex = $this.attr('data-index');
                if( This.differ ) {
                    if( ++This.nIndex > This.maxIndex ) {
                        This.nIndex = This.maxIndex;
                        return;
                    }
                } else {
                    if( --This.nIndex < 0 ) {
                        This.nIndex = 0;
                        return;
                    }
                }
                This.nIndex = This.nIndex;
                if( !$this.is(':animated') ) {
                    This.whellRoll( This, $this, This.nIndex );
                }
            });

            // 移动端滚动
            $(document).bind('touchstart', function(e) {
                y = e.originalEvent.touches[0].pageY;
                This.nIndex = $this.attr('data-index');
            })
            .bind('touchmove', function(e) {
                e.preventDefault();
                _y = e.originalEvent.touches[0].pageY;

                // 判断滑动方向
                dire = y - _y > 0 ? 1 : 0;
                if( dire ) {
                    if( ++This.nIndex > This.maxIndex ) {
                        This.nIndex = This.maxIndex;
                        return;
                    }
                } else {
                    if( --This.nIndex < 0 ) {
                        This.nIndex = 0;
                        return;
                    }
                }
                _y = _y;
                This.nIndex = This.nIndex;
                if( !$this.is(':animated') ) {
                    This.whellRoll( This, $this, This.nIndex );
                }
            });
        },
        // 按钮点击切换功能
        clickRoll: function( $this ) {
            var This = this;
            this.btnList.click(function() {
                This.nIndex = $(this).index();
                $(this).addClass('on').siblings().removeClass('on');
                if( !$this.is(':animated') ) {
                    This.whellRoll( This, $this, This.nIndex );
                }
            });
        },
        // 滚动功能
        whellRoll: function( This, obj, n ) {
            // 滚动按钮添加class
            This.btnList.eq(n).addClass('on').siblings().removeClass('on');
            obj.animate({
                top: '-' + n + '00%'
            }, This.transition, function() {
                // 执行回调
                This.callback && This.callback( n, This.maxIndex );
            })
            // 将更新后的index重新赋值
            .attr('data-index', n);
        }
    }

    var init = {
        pageList: $('.ui-page'),
        transition: 1000
    }

    $.fn.fullPage = function( options ) {
        var oPage = new Page(this, $.extend(init, options));
        oPage.init(this);
    }
}(jQuery));
