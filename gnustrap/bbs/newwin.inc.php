<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$sql = " select * from {$g5['new_win_table']}
          where '".G5_TIME_YMDHIS."' between nw_begin_time and nw_end_time
            and nw_device IN ( 'both', 'pc' )
          order by nw_id asc ";
$result = sql_query($sql, false);
?>

<!-- 팝업레이어 시작 { -->
<?php
for ($i=0; $row_nw=sql_fetch_array($result); $i++)
{
    // 이미 체크 되었다면 Continue
    if ($_COOKIE["hd_pops_{$row_nw['nw_id']}"])
        continue;

    $sql = " select * from {$g5['new_win_table']} where nw_id = '{$row_nw['nw_id']}' ";
    $nw = sql_fetch($sql);
?>
<div id="hd_pop" class="modal-dialog" style="margin:0 auto;">
    <div id="hd_pops_<?php echo $nw['nw_id'] ?>" class="modal-content" style="width:<?php echo $nw['nw_width'] ?>px; top:<?php echo $nw['nw_top']?>px;left:<?php echo $nw['nw_left']?>px;">
	  <div class="modal-header">
        <button type="button" class="hd_pops_close hd_pops_<?php echo $nw['nw_id']; ?> close">×</button>
        <span class="modal-title"><strong>팝업레이어 안내</strong></span>
      </div>

      <div class="modal-body" style="height:<?php echo $nw['nw_height'] ?>px;">
	  <?php echo conv_content($nw['nw_content'], 1); ?>
	  </div>

	  <div class="modal-footer">
	    <button class="hd_pops_reject hd_pops_<?php echo $nw['nw_id']; ?> <?php echo $nw['nw_disable_hours']; ?> btn btn-default"><i class="fa fa-clock-o"></i> <strong><?php echo $nw['nw_disable_hours']; ?></strong>시간 동안 다시 열람하지 않습니다.</button>
        <button class="hd_pops_close hd_pops_<?php echo $nw['nw_id']; ?> btn btn-default"><i class="fa fa-times-circle"></i> Close</button>
      </div>

     </div>
</div>
	 
<?php }
if ($i == 0) echo '<span class="sound_only">팝업레이어 알림이 없습니다.</span>';
?>

<script>
$(function() {
    $(".hd_pops_reject").click(function() {
        var id = $(this).attr('class').split(' ');
        var ck_name = id[1];
        var exp_time = parseInt(id[2]);
        $("#"+id[1]).css("display", "none");
        set_cookie(ck_name, 1, exp_time, g5_cookie_domain);
    });
    $('.hd_pops_close').click(function() {
        var idb = $(this).attr('class').split(' ');
        $('#'+idb[1]).css('display','none');
    });
    $("#hd").css("z-index", 1000);
});
</script>
<!-- } 팝업레이어 끝 -->