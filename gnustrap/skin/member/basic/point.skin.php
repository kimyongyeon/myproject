<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
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
<div class="well well-sm" style="margin:5px;">
            포인트 사용내역 목록
        </div>
	<div class="table-responsive">
	<table class="table table-striped table-bordered">
        <thead>
        <tr role="row">
            <th scope="col">일시</th>
            <th scope="col">내용</th>
            <th scope="col">만료일</th>
            <th scope="col">지급포인트</th>
            <th scope="col">사용포인트</th>
        </tr>
        </thead>
        <tbody role="alert" aria-live="polite" aria-relevant="all">
        <?php
        $sum_point1 = $sum_point2 = $sum_point3 = 0;

        $sql = " select *
                    {$sql_common}
                    {$sql_order}
                    limit {$from_record}, {$rows} ";
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            $point1 = $point2 = 0;
            if ($row['po_point'] > 0) {
                $point1 = '+' .number_format($row['po_point']);
                $sum_point1 += $row['po_point'];
            } else {
                $point2 = number_format($row['po_point']);
                $sum_point2 += $row['po_point'];
            }

            $po_content = $row['po_content'];

            $expr = '';
            if($row['po_expired'] == 1)
                $expr = ' txt_expired';
        ?>
        <tr>
            <td><?php echo $row['po_datetime']; ?></td>
            <td><?php echo $po_content; ?></td>
            <td class="td_date<?php echo $expr; ?>">
                <?php if ($row['po_expired'] == 1) { ?>
                만료<?php echo substr(str_replace('-', '', $row['po_expire_date']), 2); ?>
                <?php } else echo $row['po_expire_date'] == '9999-12-31' ? '&nbsp;' : $row['po_expire_date']; ?>
            </td>
            <td><?php echo $point1; ?></td>
            <td><?php echo $point2; ?></td>
        </tr>
        <?php
        }

        if ($i == 0)
            echo '<tr><td colspan="5" class="text-center">자료가 없습니다.</td></tr>';
        else {
            if ($sum_point1 > 0)
                $sum_point1 = "+" . number_format($sum_point1);
            $sum_point2 = number_format($sum_point2);
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <th scope="row" colspan="3">소계</th>
            <td><?php echo $sum_point1; ?></td>
            <td><?php echo $sum_point2; ?></td>
        </tr>
        <tr>
            <th scope="row" colspan="3">보유포인트</th>
            <td colspan="2"><?php echo number_format($member['mb_point']); ?></td>
        </tr>
        </tfoot>
        </table>
    </div>

    <div class="alert alert-info" style="margin:5px;">
        <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['PHP_SELF'].'?'.$qstr.'&amp;page='); ?>
    </div>
 
	</div>

  	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.close();"><i class="fa fa-times-circle"></i> Close</button>
    </div>

      </div>
</div>
