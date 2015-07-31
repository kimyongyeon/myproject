<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 폼메일 시작 { -->
<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <a class="navbar-brand" href="#"><?php echo $name ?>님께 메일보내기</a>
        </div>
        </nav>
      </div>
    <div class="modal-body">
<form name="fformmail" action="./formmail_send.php" onsubmit="return fformmail_submit(this);" method="post" enctype="multipart/form-data" style="margin:0px;">
    <input type="hidden" name="to" value="<?php echo $email ?>">
    <input type="hidden" name="attach" value="2">
    <input type="hidden" name="token" value="<?php echo $token ?>">
    <?php if ($is_member) { // 회원이면  ?>
    <input type="hidden" name="fnick" value="<?php echo $member['mb_nick'] ?>">
    <input type="hidden" name="fmail" value="<?php echo $member['mb_email'] ?>">
    <?php }  ?>

    <?php if (!$is_member) {  ?>
      <div class="form-group">
   	   <label for="fnick">이름<strong class="sound_only">필수</strong></label>
   	   <input type="text" name="fnick" id="fnick" required class="form-control required" placeholder="이름">
  	  </div>

      <div class="form-group">
   	   <label for="fmail">E-mail<strong class="sound_only">필수</strong></label>
   	   <input type="text" name="fmail"  id="fmail" required class="form-control required" placeholder="gnustrap@****.com">
  	  </div>
    <?php }  ?>

      <div class="form-group">
   	   <label for="subject">제목<strong class="sound_only">필수</strong></label>
   	   <input type="text" name="subject" id="subject" required class="form-control required" placeholder="제목">
  	  </div>

      <div class="form-group">
   	   <label>형식</label><br />
      <div class="form-group btn-group" data-toggle="buttons">
       <label class="btn btn-primary">
   	   <input type="radio" name="type" value="0" id="type_text" checked> TEXT</label>
       <label class="btn btn-primary">
       <input type="radio" name="type" value="1" id="type_html"> HTML</label>
       <label class="btn btn-primary">
       <input type="radio" name="type" value="2" id="type_both"> TEXT+HTML</label>
  	  </div>
	  </div>

      <div class="form-group">
   	   <label for="content">내용<strong class="sound_only">필수</strong></label>
   	   <textarea name="content" required class="form-control textarea required" style="width:100%;"></textarea>
  	  </div>

      <div class="form-group">
   	   <label for="file1">첨부 파일 1</label>
   	   <input type="file" name="file1"  id="file1"  class="form-control">
                첨부 파일은 누락될 수 있으므로 메일을 보낸 후 파일이 첨부 되었는지 반드시 확인해 주시기 바랍니다.
  	  </div>

      <div class="form-group">
   	   <label for="file2">첨부 파일 2</label>
   	   <input type="file" name="file2" id="file2" class="form-control">
  	  </div>

      <div class="form-group">
   	   <label class="hidden-xs">자동등록방지</label>
   	   <?php echo captcha_html(); ?>
  	  </div>

  	<div class="modal-footer">
        <button type="submit" id="btn_submit" class="btn btn-default"><i class="fa fa-send-o"></i> 보내기</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.close();"><i class="fa fa-times-circle"></i> Close</button>
    </div>
</form>

      </div>
</div>

<script>
with (document.fformmail) {
    if (typeof fname != "undefined")
        fname.focus();
    else if (typeof subject != "undefined")
        subject.focus();
}

function fformmail_submit(f)
{
    <?php echo chk_captcha_js();  ?>

    if (f.file1.value || f.file2.value) {
        // 4.00.11
        if (!confirm("첨부파일의 용량이 큰경우 전송시간이 오래 걸립니다.\n\n메일보내기가 완료되기 전에 창을 닫거나 새로고침 하지 마십시오."))
            return false;
    }

    document.getElementById('btn_submit').disabled = true;

    return true;
}
</script>
<!-- } 폼메일 끝 -->