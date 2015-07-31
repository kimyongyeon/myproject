<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="<?php echo G5_JS_URL ?>/html5.js"></script>
  <script src="<?php echo G5_JS_URL ?>/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo G5_CSS_URL?>/default_shop.css" />
<!-- 로그인 시작 { -->
<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="window.location.reload();">×</button>
        <span class="modal-title"><strong>Login</strong></span>
      </div>
    <div class="modal-body">
    <form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
    <input type="hidden" name="url" value='<?php echo $login_url ?>' role="form">

    <div class="form-group input-group">
        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
        <input type="text" name="mb_id" id="login_id" required class="form-control required" size="20" maxLength="20" placeholder="Enter ID">
    </div>
    <div class="form-group input-group">
        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
        <input type="password" name="mb_password" id="login_pw" required class="form-control required" size="20" maxLength="20" placeholder="Password">
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-default pull-right"><i class="fa fa-sign-in"></i> 로그인</button>
		<label class="btn btn-default popover-top pull-left" for="login_auto_login" data-toggle="popover" data-placement="right" data-content="자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다. 공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.">
		<input type="checkbox" name="auto_login" id="login_auto_login"> <i class="fa fa-shield"></i> 자동로그인</label>
    </div>
	<div class="clearfix"></div>
	</div>
	

    <div class="well text-center" style="margin:0 10px 20px 10px;">
            <a href="<?php echo G5_BBS_URL ?>/password_lost.php" class="btn btn-warning"><i class="fa fa-key"></i> 아이디 / 비밀번호 찾기</a>
    </div>
	<div class="modal-footer">
	    <a href="<?php echo G5_URL ?>/" class="btn btn-default"><i class="fa fa-rotate-left"></i> 메인으로 돌아가기</a>
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload();"><i class="fa fa-times-circle"></i> Close</button>
      </div>

    </form>
</div>
</div>

<!-- } 로그인 끝 -->