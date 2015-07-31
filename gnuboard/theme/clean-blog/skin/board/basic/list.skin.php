<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 5;

if ($is_checkbox) $colspan++;
if ($is_good) $colspan++;
if ($is_nogood) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>
<div class="container">
    <ul class="timeline">
<?php for ($i=0; $i<count($list); $i++) { ?>
        <li>
          <div class="timeline-badge neutral"><i class="fa fa-navicon"></i></div>
          <div class="timeline-panel" onclick="location.href='<?php echo $list[$i]['href'] ?>';">
            <div class="timeline-heading">
              <h4 class="timeline-title">[<?php
            if ($list[$i]['is_notice']) // 공지사항
                echo '<strong>공지</strong>';
            else if ($wr_id == $list[$i]['wr_id'])
                echo "<span class=\"bo_current\">열람중</span>";
            else
                echo $list[$i]['num'];
             ?>] <?php echo $list[$i]['subject'] ?> #<?php echo $list[$i]['comment_cnt']; ?></h4>
            </div>
            <div class="timeline-body">
              <p><?php echo $list[$i]['wr_content'] ?></p>
              <p>Post by <?php echo $list[$i]['name'] ?> , Time:<?php echo $list[$i]['datetime2'] ?>, Hit:<?php echo $list[$i]['wr_hit'] ?></p>
            </div>
          </div>
        </li>
<?php } if (count($list) == 0) { ?>
        <li>
          <div class="timeline-badge neutral"><i class="fa fa-navicon"></i></div>
          <div class="timeline-panel">
            <div class="timeline-heading">
              <h4 class="timeline-title">Board List Error #-</h4>
            </div>
            <div class="timeline-body">
              <p>No registered post.</p>
            </div>
          </div>
        </li>
<?php } ?>        
    </ul>
</div>
<?php if ($is_admin || $write_href) { ?>
<div class="btn_bo_user">
    <?php if ($is_admin ) { ?><li><a href="<?php echo $admin_href ?>" class="btn_b01">관리자</a></li><?php } ?>
    <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02">글쓰기</a></li><?php } ?>
</div>
<?php } ?>
<!-- 페이지 -->
<?php echo $write_pages;  ?>