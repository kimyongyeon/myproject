<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 쪽지 보내기 시작 { -->
<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 쪽지 목록 시작 { -->
<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <nav class="navbar navbar-default" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex5-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo G5_BBS_URL?>/memo_form.php">쪽지보내기</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex5-collapse">
          <ul class="nav navbar-nav">
            <li><a href="<?php echo G5_BBS_URL?>/memo.php?kind=recv"><?php echo  ($kind == "recv") ? "보낸사람" : "보낸사람";  ?></a></li>
            <li><a href="<?php echo G5_BBS_URL?>/memo.php?kind=send"><?php echo  ($kind == "send") ? "받는사람" : "받는사람";  ?></a></li>
            <li class="active"><a href="<?php echo G5_BBS_URL?>/memo_form.php">쪽지쓰기</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </nav>
      </div>
<form name="fmemoform" action="<?php echo $memo_action_url; ?>" onsubmit="return fmemoform_submit(this);" method="post" autocomplete="off" role="form">
    <div class="modal-body">

  	  <div class="form-group">
   	   <label for="me_recv_mb_id">받는 회원아이디<strong class="sound_only">필수</strong></label>
   	   <input  type="text" name="me_recv_mb_id" value="<?php echo $me_recv_mb_id ?>" id="me_recv_mb_id" required class="form-control required" id="exampleInputEmail1" placeholder="User ID" size="47">
  	  </div>

  	  <div class="form-group">
  	    <label for="me_memo">내용</label>
  	    <textarea name="me_memo" id="me_memo" required class="form-control textarea required"><?php echo $content ?></textarea>
 	   </div>

  	  <div class="form-group">
  	    <label class="hidden-xs">자동등록방지</label>
  	    <?php echo captcha_html(); ?>
 	   </div>

	</div>

	<div class="modal-footer">
        <button type="submit" id="btn_submit" class="btn btn-default"><i class="fa fa-send-o"></i> 보내기</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.close();"><i class="fa fa-times-circle"></i> Close</button>
    </div>
</form>
      </div>
</div>
<script>
function fmemoform_submit(f)
{
    <?php echo chk_captcha_js();  ?>

    return true;
}
</script>
<!-- } 쪽지 보내기 끝 -->