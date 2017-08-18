(function($) {
    $.fn.seamlessBanner = function( options ) {
        var $bannerWrap = this,
            $bannerList = this.children(),
            $bannerBtnWrap = options.bannerBtnWrap || this.siblings('.banner-btn-wrap'),
            $arrowLeftBtn = options.arrowLeftBtn || this.siblings('.banner-arrow-left'),
            $arrowRightBtn = options.arrowRightBtn || this.siblings('.banner-arrow-right'),
            bAutoBanner = options.autoBanner,
            nTime = options.time || 5000,
            nTransition = options.transition || 1000,
            nWindowWidth = window.innerWidth,
            n = 1,
            oTimer = null,
            x = 0,
            _x = 0;
        if( $bannerList.length > 1 ){
            // 生成banner按钮
            $bannerList.each(function(i){
                $(this).width( nWindowWidth );
                $bannerBtnWrap.append('<li></li>');
            });
            // 拷贝第一张和最后一张分别到第一张前面和最后一张后边
            $bannerList.first().before( $bannerList.last().clone() );
            $bannerList.last().after( $bannerList.first().clone() );
            // 重新获取banner
            $bannerList = this.children();
            // 获取banner按钮
            var $bannerBtn = $('li', $bannerBtnWrap);
            // banner盒子计算宽度
            $bannerWrap.css({
                width: $bannerList.length * nWindowWidth + 'px',
                marginLeft: n * -nWindowWidth +'px'
            });
            // banner按钮盒子计算宽度
            $bannerBtnWrap.css({
                width: $bannerBtn.length * $bannerBtn.outerWidth(true) + 'px',
                marginLeft: function(){
                    return $(this).width() / -2 + 'px'
                }
            })
            .children('li:eq(0)').addClass('on');
            // 自动轮播
            bAutoBanner && autoBanner();
            // 窗口发生改变改变轮播宽度
            $(window).on('resize', function(){
                nWindowWidth = $(this).width();
                $bannerList.each(function(){
                    $(this).width( nWindowWidth );
                });
                $bannerWrap.css({
                    width: $bannerList.length * nWindowWidth + 'px',
                    marginLeft: n * -nWindowWidth +'px'
                });
            });
            // 按钮切换功能
            $bannerBtn.mouseenter(function(){
                if( !$bannerWrap.is(':animated') ){
                    n = $(this).index() + 1;
                    move( $(this), $bannerWrap );
                }
            });
            // 向左切换
            $arrowLeftBtn.click(function() {
                if( !$bannerWrap.is(':animated') ){
                    n--;
                    if( n < 0 ){
                        n = $bannerList.length - 3;
                        $bannerWrap.css('marginLeft', ($bannerList.length - 2) * -nWindowWidth + 'px');
                    }
                    move( $bannerBtn.eq(n-1), $bannerWrap );
                }
            });
            // 向右切换
            $arrowRightBtn.click(function() {
                if( !$bannerWrap.is(':animated') ){
                    n++;
                    if( n > $bannerList.length - 1 ){
                        n = 2;
                        $bannerWrap.css('marginLeft', -nWindowWidth + 'px');
                    }else if( n > $bannerList.length - 2 ){
                        $bannerBtn.eq(0).addClass('on').siblings().removeClass('on');
                    }
                    move( $bannerBtn.eq(n-1), $bannerWrap );
                }
            });
            // 鼠标移入停止自动轮播
            $bannerWrap.parent().hover(function(){
                clearInterval( oTimer );
            },function(){
                autoBanner();
            });
            // 自动轮播
            function autoBanner(){
                oTimer = setInterval(function(){
                    n++;
                    if( n > $bannerList.length - 1 ){
                        n = 2;
                        $bannerWrap.css('marginLeft', -nWindowWidth + 'px');
                    }else if( n > $bannerList.length - 2 ){
                        $bannerBtn.eq(0).addClass('on').siblings().removeClass('on');
                    }
                    move( $bannerBtn.eq(n-1), $bannerWrap );
                }, nTime);
            }
            function move( btnObj, bannerObj, callback ){
                btnObj.addClass('on').siblings().removeClass('on');
                bannerObj.animate({'marginLeft': n * -nWindowWidth +'px'}, nTransition);
            }
            // 移动端无缝滚动
            $bannerWrap.on('touchstart', function(e){
                x = e.originalEvent.touches[0].pageX;
            })
            .on('touchmove', function(e){
                // e.preventDefault();
                _x = e.originalEvent.touches[0].pageX;
                var differ = x - _x > 0 ? 1 : 0;
                if( differ ){
                    // 向左滑动
                    if( !$(this).is(':animated') ){
                        n++;
                        if( n > $bannerList.length - 1 ){
                            n = 2;
                            $(this).css('marginLeft', -nWindowWidth + 'px');
                        }else if( n > $bannerList.length - 2 ){
                            $bannerBtn.eq(0).addClass('on').siblings().removeClass('on');
                        }
                        $bannerBtn.eq(n-1).addClass('on').siblings().removeClass('on');
                        $(this).animate({ 'marginLeft': n * -nWindowWidth +'px' }, 700);
                    }
                }else{
                    // 向右滑动
                    if( !$(this).is(':animated') ){
                        n--;
                        if( n < 0 ){
                            n = $bannerList.length - 3;
                            $(this).css('marginLeft', ($bannerList.length - 2) * -nWindowWidth + 'px');
                        }
                        $bannerBtn.eq(n-1).addClass('on').siblings().removeClass('on');
                        $(this).animate({ 'marginLeft': n * -nWindowWidth +'px' }, 700);
                    }
                }
            });
        }
    }
})(jQuery);
