<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$outlogin_skin_url.'/style.css">', 0);
?>

<!-- 로그인 전 아웃로그인 시작 { -->
<section id="ol_before" class="well well-sm">
    <h6>회원로그인
	<label for="auto_login" class="ol_auto popover-top" data-toggle="popover" data-placement="top" data-content="자동로그인" data-original-title="">
	<input type="checkbox" name="auto_login" id="auto_login" data-toggle="chechkbox"> <i class="fa fa-shield fa-fw"></i>Auto</label>
	</h6>
    <form name="foutlogin" action="<?php echo $outlogin_action_url ?>" onsubmit="return fhead_submit(this);" method="post" autocomplete="off" role="form">
    <fieldset>
        <input type="hidden" name="url" value="<?php echo $outlogin_url ?>">
		<div class="form-group input-group">
        <span class="input-group-addon"><i class="fa fa-tag fa-fw"></i></span>
        <input type="text" name="mb_id" id="login_id" required="" class="form-control required" size="20" maxlength="20" placeholder="Enter ID">
        </div>
		<div class="form-group input-group">
        <span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
        <input type="password" name="mb_password" id="login_pw" required="" class="form-control required" size="20" maxlength="20" placeholder="Password">
        </div>
        <div class="text-center">
            <a href="<?php echo G5_BBS_URL ?>/register.php" class="btn btn-default"><i class="fa fa-sign-in fa-fw"></i>회원가입</a>
            <a href="<?php echo G5_BBS_URL ?>/password_lost.php" id="ol_password_lost" class="btn btn-default"><i class="fa fa-search fa-fw"></i>정보찾기</a>
        </div>
        <div class="ol_login">
            <button type="submit" class="btn btn-info"><i class="fa fa-power-off fa-fw"></i>Login</button>
        </div>
    </fieldset>
    </form>
</section>

<script>
$omi = $('#ol_id');
$omp = $('#ol_pw');
$omp.css('display','inline-block').css('width',104);
$omi_label = $('#ol_idlabel');
$omi_label.addClass('ol_idlabel');
$omp_label = $('#ol_pwlabel');
$omp_label.addClass('ol_pwlabel');

$(function() {
    $omi.focus(function() {
        $omi_label.css('visibility','hidden');
    });
    $omp.focus(function() {
        $omp_label.css('visibility','hidden');
    });
    $omi.blur(function() {
        $this = $(this);
        if($this.attr('id') == "ol_id" && $this.attr('value') == "") $omi_label.css('visibility','visible');
    });
    $omp.blur(function() {
        $this = $(this);
        if($this.attr('id') == "ol_pw" && $this.attr('value') == "") $omp_label.css('visibility','visible');
    });

    $("#auto_login").click(function(){
        if ($(this).is(":checked")) {
            if(!confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?"))
                return false;
        }
    });
});

function fhead_submit(f)
{
    return true;
}
</script>
<!-- } 로그인 전 아웃로그인 끝 -->
