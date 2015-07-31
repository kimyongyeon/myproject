<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 하단 파일 경로 지정 : 이 코드는 가능한 삭제하지 마십시오.
if ($config['cf_include_tail'] && is_file(G5_PATH.'/'.$config['cf_include_tail'])) {
    include_once(G5_PATH.'/'.$config['cf_include_tail']);
    return; // 이 코드의 아래는 실행을 하지 않습니다.
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/tail.php');
    return;
}
?>

<div class="clearfix"></div>

</div>
</div>
</div>
<a href="#" class="btn btn-default back-to-top"><span class="glyphicon glyphicon-chevron-up"></span></a>
<a href="#" class="btn btn-default go-to-bottom"><span class="glyphicon glyphicon-chevron-down"></span></a>

<script type="text/javascript">
$(document).ready(function() {
    var offset1 = 300;    // 수직으로 어느정도 움직여야 버튼이 나올까?
    var offset2 = 100;    // 수직으로 어느정도 움직여야 버튼이 나올까?
    var duration = 0;     // top으로 이동할때까지의 animate 시간 (밀리세컨드, default는 400. 예제의 기본은 500)
    var delay1 = 3000;    // 버튼이 사라질때까지의 시간 (3000 = 3초)

    var timer;
    $(window).bind('scroll',function () {
        clearTimeout(timer);
        timer = setTimeout( refresh , 150 );
    });
    var refresh = function () { 
        if ($(this).scrollTop() > offset2) {
            $('.go-to-bottom').fadeIn(duration);
            setTimeout(function(){$('.go-to-bottom').hide();},2000);
        } else {
            $('.go-to-bottom').fadeOut(duration);
        }

        if ($(this).scrollTop() > offset1) {
            $('.back-to-top').fadeIn(duration);
            setTimeout(function(){$('.back-to-top').hide();},2000);
        } else {
            $('.back-to-top').fadeOut(duration);
        }
    };

    $('.back-to-top').click(function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, duration);
        return false;
    })
    $('.go-to-bottom').click(function(event) {
        event.preventDefault();
        $('html, body').animate({ scrollTop: $(document).height() }, duration);
        return false;
    })
});
</script>


<!-- 하단 시작 -->
<div id="layout-line"></div>
    <div id="footer">
        <div class="footer-box">
		     <ul>
                <a href="#" class="btn btn-default tooltip-top" title="페이스북 공유"><i class="fa fa-facebook"></i></a>
		        <a href="#" class="btn btn-default tooltip-top" title="트위터 공유"><i class="fa fa-twitter"></i></a>
		        <a href="#" class="btn btn-default tooltip-top" title="html5"><i class="fa fa-html5"></i></a>
                <li class="pull-right"><i class="fa fa-bookmark"></i> Copyright ©  <a href="#" style="color:red; font-weight:bold;">그누스트랩</a></li>
			 </ul>
        </div>
    </div>


<script>
$(function() {
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
});
</script>

<?php
include_once(G5_PATH."/tail.sub.php");
?>