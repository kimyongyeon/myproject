<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

global $is_admin;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$visit_skin_url.'/style.css">', 0);
?>

<!-- 접속자집계 시작 { -->
<section id="visit">
<div class="panel panel-default">
  <div class="panel-body">
      <ul class="list-unstyled" style="padding:0;">
        <?php if ($is_admin == "super") {  ?><a href="<?php echo G5_ADMIN_URL ?>/visit_list.php" style="float: right;"><i class="fa fa-cog tooltip-top" title="" data-original-title="설정"></i></a><?php } ?>
            <li>오늘 <?php echo number_format($visit[1]) ?></li>
            <li>어제 <?php echo number_format($visit[2]) ?></li>
            <li>최대 <?php echo number_format($visit[3]) ?></li>
            <li>전체 <?php echo number_format($visit[4]) ?></li>
	  </ul>
  </div>
    </div>
</section>
<!-- } 접속자집계 끝 -->