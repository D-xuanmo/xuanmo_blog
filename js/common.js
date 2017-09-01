$(function(){
    var $window = $(window);
    var $header = $('header');

    (function(){
        var $comment = $('#comments ol.comment-list li');
        var $respond = $('#respond .comment-form-comment');
        var arrImg = ['<img src="https://www.xuanmo.xin/bg1.jpg">', '<img src="https://www.xuanmo.xin/bg2.jpg">', '<img src="https://www.xuanmo.xin/bg3.jpg">', '<img src="https://www.xuanmo.xin/bg4.jpg">', '<img src="https://www.xuanmo.xin/bg5.jpg">'];
        var random = 0;
        // 删除head里的style
        $('head style[media="screen"], head style[media="print"]').remove();
        $('.comment-author .says' , $comment).remove();
        // 为没有缩略图的img添加别的图片
        $('article .article-img').each(function(){
            random = Math.floor( Math.random() * arrImg.length );
            var $thisImg = $(this).children('img');
            if( $thisImg.length == 0 ){
                $(this).append(arrImg[random]);
            }else if( Number( $thisImg.attr('height') ) < 150 ){
                $thisImg.css({
                    'height' : $thisImg.attr('height'),
                    'vertical-align' : 'middle'
                });
            }
        });
        $('.content img').each(function(){
            $(this).removeAttr('width').removeAttr('height');
        });
    })();

    (function(){
        // 搜索框动画
        var bMark = true;
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
    })();

    // 滚动显示
    (function(){
        scrollAnimate($('.main article , .right article'));
        function scrollAnimate(obj) {
            var y;
            obj.each(function () {
                if ($window.scrollTop() > $(this).offset().top - $window.height() / 2) $(this).addClass('on');
            });
            $window.on('scroll', function () {
                y = $(this).scrollTop();
                obj.each(function () {
                    if (y > $(this).offset().top - $window.height() / 2) $(this).addClass('on');
                });
            });
        }
    })();

    // 手机端显示导航菜单
    (function(){
        var $nav = $('nav'),
            $menuList = $('ul.menu > li'),
            y = 0,
            _y = 0;

        $('.icon-menu-list2').bind('touchstart', function () {
            $nav.css('left', 0);
        });
        $nav.bind('touchstart', function (e) {
                y = e.originalEvent.touches[0].pageX;
            })
            .bind('touchmove', function (e) {
                _y = e.originalEvent.touches[0].pageX;
                if (y - _y > 20) $(this).css('left', '-100%');
            });
        $menuList.bind('click', function () {
            $(this).children('.sub-menu').stop().slideToggle()
                .parent().siblings()
                .children('.sub-menu').slideUp();
        });
        $menuList.each(function () {
            $(this).children('a').attr('data-href', function () {
                return $(this).attr('href');
            });
        });
        removeLink();
        $window.bind('resize', removeLink);
        function removeLink() {
            if (navigator.appVersion.match('iPhone') || navigator.appVersion.match('Android')) {
                $menuList.each(function () {
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

    (function(){
        // 返回顶部动画
        var $scroll;
        var $backTop = $('div.icon-backtop');
        $window.on('scroll' , function(){
            var $this = $(this);
            $scroll = $(this).scrollTop();
            if( $scroll > 300 ){
                $header.addClass('on');
                $('ul.sub-menu' , $header).addClass('bg282828');
                $backTop.css('right','30px');
            }else if( $scroll < 300 ){
                $header.removeClass('on');
                $('ul.sub-menu' , $header).removeClass('bg282828');
                $backTop.css('right','-50px');
            }
        });
        $backTop.click(function(){
            $('html,body').animate({scrollTop : 0},800);
        });
    })();

    // 放大图片预览
    (function(){
        var $content = $('.content');
        var $img , i = 0;
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
        $('.cover-hide , .cover .icon-close1').click(function(){
            $('.cover').hide();
            $('.cover-img').children().remove();
        });
    })();

    // 点赞提交
    $('.link-btn').click(function() {
        if ($(this).hasClass('done')) {
            alert('^_^您已赞过此文章了');
        } else {
            var $this = $(this);
            $this.addClass('done');
            document.cookie = 'bigfa_ding_' + $(this).data('id') + '=' + $(this).data('id');
            $.ajax({
                url: $(this).children('.blog-url').text(),
                type: 'POST',
                data: {
                    action: "bigfa_like",
                    um_id: $(this).data("id"),
                    um_action: $(this).data('action')
                },
                success: function(data) {
                    $this.children('.count').text(data);
                }
            });
        }
    });

    // 文章页微信显示与关闭
    $('.article-about-author .share-btn a:nth-of-type(3)').click(function(){
        // 微信盒子显示
        $(this).children('span').show()
        // 关闭按钮隐藏span
        .children('i').click(function(e){
            e.stopPropagation();
            $(this).parent('span').hide();
        });
    });

    // 验证码功能
    (function() {
        var canvas = document.querySelector('.canvas-img-code');
        if( canvas ) {
            var ctx = canvas.getContext('2d'),
                $submit = $('#submit');
            // 禁止提交按钮
            $submit.prop('disabled', true).css('backgroundColor', '#b5b5b5');
            var nResult = result();
            // 验证码功能
            $('#img-code').keyup(function() {
                if( Number($(this).val()) == nResult ) {
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
            if( bMark ) {
                $(this).siblings('.expression-hide-wrap').show();
                if( bExpression ) {
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
        });

        $('body').bind('click', function() {
            if( bMark == false ) $('.expression-hide-wrap').hide();
            bMark = true;
        });

        // 添加图片
        $('.comment-pic-btn').click(function() {
            $("#comment").val(function() {
                return $(this).val() + ' <img src="' + prompt('请输入图片地址') + '" alt="' + prompt('请输入图片描述') + '"> ';
            });
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
});
