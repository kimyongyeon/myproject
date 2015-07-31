<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 회원 비밀번호 확인 시작 { -->
<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="window.location.reload();">×</button>
        <?php echo $g5['title'] ?>
      </div>
    <div class="modal-body">
      <p>
        <strong>비밀번호를 한번 더 입력해주세요.</strong>
        <?php if ($url == 'member_leave.php') { ?>
        비밀번호를 입력하시면 회원탈퇴가 완료됩니다.
        <?php }else{ ?>
        회원님의 정보를 안전하게 보호하기 위해 비밀번호를 한번 더 확인합니다.
        <?php }  ?>
    </p>

    <form name="fmemberconfirm" action="<?php echo $url ?>" onsubmit="return fmemberconfirm_submit(this);" method="post">
    <input type="hidden" name="mb_id" value="<?php echo $member['mb_id'] ?>">
    <input type="hidden" name="w" value="u">

    <div class="form-group input-group">
        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
        <input type="text" class="form-control" placeholder="<?php echo $member['mb_id'] ?>" disabled>
    </div>
	<div class="form-group input-group">
        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
		<input type="password" name="mb_password" id="confirm_mb_password" required class="form-control required" size="15" maxLength="20" placeholder="Password">
    </div>
	<div class="form-group">
        <button type="submit" class="btn btn-default" style="padding:11px;"><i class="fa fa-sign-in"></i> 확인</button>
    </div>

    </form>

	<div class="modal-footer">
	    <a href="<?php echo G5_URL ?>" class="btn btn-default"><i class="fa fa-rotate-left"></i> 메인으로 돌아가기</a>
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload();"><i class="fa fa-times-circle"></i> Close</button>
      </div>
        </div><!-- /content -->


      </div>

    
</div>
<!-- } 회원가입 약관 동의 끝 --></div>

<script>
function fmemberconfirm_submit(f)
{
    document.getElementById("btn_submit").disabled = true;

    return true;
}
</script>
<!-- } 회원 비밀번호 확인 끝 -->