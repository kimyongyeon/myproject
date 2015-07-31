<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$nick = get_sideview($mb['mb_id'], $mb['mb_nick'], $mb['mb_email'], $mb['mb_homepage']);
if($kind == "recv") {
    $kind_str = "보낸";
    $kind_date = "받은";
}
else {
    $kind_str = "받는";
    $kind_date = "보낸";
}

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 쪽지보기 시작 { -->
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
          <a class="navbar-brand" href="<?php echo G5_BBS_URL?>/memo.php?kind=recv">받은쪽지</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex5-collapse">
          <ul class="nav navbar-nav">
            <li><a href="<?php echo G5_BBS_URL?>/memo.php?kind=recv"><?php echo  ($kind == "recv") ? "보낸사람" : "보낸사람";  ?></a></li>
            <li><a href="<?php echo G5_BBS_URL?>/memo.php?kind=send"><?php echo  ($kind == "send") ? "받는사람" : "받는사람";  ?></a></li>
            <li><a href="<?php echo G5_BBS_URL?>/memo_form.php">쪽지쓰기</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </nav>
      </div>
    <div class="modal-body">
          <div style="margin:5px;" class="panel panel-default">
          <div class="panel-heading">
          <div class="pull-left"><?php echo $kind_str ?>사람 <?php echo $nick ?></div>
          <div class="pull-right"><?php echo $kind_date ?>시간 <?php echo $memo['me_send_datetime'] ?></div>
          <div class="clearfix"></div>
          </div>
          <div class="panel-body">
          <?php echo conv_content($memo['me_memo'], 0) ?>
          </div>
          </div>

    <div class="alert alert-info" style="margin:5px;">
        쪽지 보관일수는 최장 <strong><?php echo $config['cf_memo_del'] ?></strong>일 입니다.
    </div>    
	</div>

  	<div class="modal-footer">
	    <?php if($prev_link) {  ?>
        <a href="<?php echo $prev_link ?>" class="btn btn-default"><i class="fa fa-angle-double-left"></i> 이전</a>
        <?php }  ?>
        <?php if($next_link) {  ?>
        <a href="<?php echo $next_link ?>" class="btn btn-default"><i class="fa fa-angle-double-right"></i> 다음</a>
        <?php }  ?>
        <?php if ($kind == 'recv') {  ?><a href="<?php echo G5_BBS_URL?>/memo_form.php?me_recv_mb_id=<?php echo $mb['mb_id'] ?>&amp;me_id=<?php echo $memo['me_id'] ?>" class="btn btn-default"><i class="fa fa-rotate-left"></i> 답장</a><?php }  ?>
        <a href="<?php echo G5_BBS_URL?>/memo.php?kind=<?php echo $kind ?>" class="btn btn-default"><i class="fa fa-tasks"></i> 목록보기</a>
    </div>

      </div>
</div>

<!-- } 쪽지보기 끝 -->