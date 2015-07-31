<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 회원정보 찾기 시작 { -->
<div id="find_info" class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="window.location.reload();">×</button>
        <span class="modal-title"><strong>회원정보 찾기</strong></span>
      </div>

	<form name="fpasswordlost" action="<?php echo $action_url ?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">
    <div class="modal-body">
            회원가입 시 등록하신 이메일 주소를 입력해 주세요.<br>
            해당 이메일로 아이디와 비밀번호 정보를 보내드립니다.
            <input type="text" name="mb_email" id="mb_email" required class="required form-control email" size="30" placeholder="gnustrap@gnustrap.com">
	</div>
    <div class="well text-center" style="margin:0 10px 20px 10px;">
       <?php echo captcha_html();  ?>
    </div>

	<div class="modal-footer">
	    <input type="submit" value="확인" class="btn btn-primary">
	    <a href="<?php echo G5_URL ?>/" class="btn btn-default"><i class="fa fa-rotate-left"></i> 메인으로 돌아가기</a>
    </div>
    </form>

    
</div>
</div>

<script>
function fpasswordlost_submit(f)
{
    <?php echo chk_captcha_js();  ?>

    return true;
}

$(function() {
    var sw = screen.width;
    var sh = screen.height;
    var cw = document.body.clientWidth;
    var ch = document.body.clientHeight;
    var top  = sh / 2 - ch / 2 - 100;
    var left = sw / 2 - cw / 2;
    moveTo(left, top);
});
</script>
<!-- } 회원정보 찾기 끝 -->