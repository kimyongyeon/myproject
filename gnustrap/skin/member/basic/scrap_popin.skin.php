<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 스크랩 시작 { -->
<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">스크랩하기</a>
        </div>
        </nav>
      </div>
    <div class="modal-body">
<form name="f_scrap_popin" action="./scrap_popin_update.php" method="post">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">

  	  <div class="form-group">
   	   <label for="me_recv_mb_id">제목</label><br />
   	   <?php echo get_text(cut_str($write['wr_subject'], 255)) ?>
  	  </div>

  	  <div class="form-group">
  	    <label for="wr_content">댓글</label>
  	    <textarea name="wr_content" id="wr_content" class="form-control textarea"></textarea>
 	   </div>

  	   <div class="alert alert-info">
  	    스크랩을 하시면서 감사 혹은 격려의 댓글을 남기실 수 있습니다.
 	   </div>
  	<div class="modal-footer">
        <input type="submit" value="스크랩 확인" class="btn btn-default">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.close();"><i class="fa fa-times-circle"></i> Close</button>
    </div>
</form>

      </div>
</div>
</div>
<!-- } 스크랩 끝 -->