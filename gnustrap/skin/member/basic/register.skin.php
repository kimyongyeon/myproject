<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
<!-- 회원가입약관 동의 시작 { -->
<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="window.location.reload();">×</button>
        <span class="modal-title"><strong>Join</strong></span>
      </div>
    <div class="modal-body">
      <form  name="fregister" id="fregister" action="<?php echo $register_action_url ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off" role="form">
 
        <div class="last-col">
          <div class="demo-col">
            <textarea class="form-control textarea" rows="3" readonly=""><?php echo get_text($config['cf_stipulation']) ?></textarea>
			<label class="btn btn-primary" for="checkbox1" data-toggle="buttons" style="width:100%; margin:15px 0;">
		    <input type="checkbox" name="agree" value="1" id="agree11" data-toggle="checkbox"> <i class="fa fa-shield"></i> 회원가입약관의 내용에 동의합니다.</label>
          </div>
          <div class="demo-col">
            <textarea class="form-control textarea" rows="3" readonly=""><?php echo get_text($config['cf_privacy']) ?></textarea>
			<label class="btn btn-primary" for="checkbox2" data-toggle="buttons" style="width:100%; margin:15px 0;">
		    <input type="checkbox" name="agree2" value="1" id="agree21" data-toggle="checkbox"> <i class="fa fa-shield"></i> 개인정보처리방침안내의 내용에 동의합니다.</label>
          </div>
        </div><!-- /content -->

	<div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-user"></i> 회원가입</button>
	    <a href="<?php echo G5_URL ?>/" class="btn btn-default"><i class="fa fa-rotate-left"></i> 메인으로 돌아가기</a>
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload();"><i class="fa fa-times-circle"></i> Close</button>
      </div>
</form>

      </div>

    <script>
    function fregister_submit(f)
    {
        if (!f.agree.checked) {
            alert("회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
            f.agree.focus();
            return false;
        }

        if (!f.agree2.checked) {
            alert("개인정보처리방침안내의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
            f.agree2.focus();
            return false;
        }

        return true;
    }
    </script>
</div>
<!-- } 회원가입 약관 동의 끝 -->