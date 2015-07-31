<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 스크랩 목록 시작 { -->
<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <a class="navbar-brand" href="#"><?php echo $g5['title'] ?></a>
        </div>
      </nav>
      </div>
    <div class="modal-body">
<div class="table-responsive">
	<table class="table table-striped table-bordered">
        <thead>
        <tr role="row">
            <th scope="col">번호</th>
            <th scope="col">게시판</th>
            <th scope="col">제목</th>
            <th scope="col">보관일시</th>
            <th scope="col">삭제</th>
        </tr>
        </thead>
        <tbody role="alert" aria-live="polite" aria-relevant="all">
        <?php for ($i=0; $i<count($list); $i++) {  ?>
        <tr>
            <td><?php echo $list[$i]['num'] ?></td>
            <td><a href="<?php echo $list[$i]['opener_href'] ?>" target="_blank" onclick="opener.document.location.href='<?php echo $list[$i]['opener_href'] ?>'; return false;"><?php echo $list[$i]['bo_subject'] ?></a></td>
            <td><a href="<?php echo $list[$i]['opener_href_wr_id'] ?>" target="_blank" onclick="opener.document.location.href='<?php echo $list[$i]['opener_href_wr_id'] ?>'; return false;"><?php echo $list[$i]['subject'] ?></a></td>
            <td><?php echo $list[$i]['ms_datetime'] ?></td>
            <td><a href="<?php echo $list[$i]['del_href'];  ?>" onclick="del(this.href); return false;" class="btn btn-default"><i class="fa fa-minus-square"></i> 삭제</a></td>
        </tr>
        <?php }  ?>

        <?php if ($i == 0) echo "<tr><td colspan=\"5\" class=\"empty_table\">자료가 없습니다.</td></tr>";  ?>
        </tbody>
        </table>
    </div>

    <?php echo get_paging($config['cf_write_pages'], $page, $total_page, "?$qstr&amp;page="); ?>
 
	</div>

  	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.close();"><i class="fa fa-times-circle"></i> Close</button>
    </div>

      </div>
</div>
<!-- } 스크랩 목록 끝 -->