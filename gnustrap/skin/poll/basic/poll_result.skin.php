<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$poll_skin_url.'/style.css">', 0);
?>

<!-- 설문조사 결과 시작 { -->
<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="window.close();">×</button>
          <?php echo $g5['title'] ?>
      </div>
    <div class="modal-body">
        <div class="well well-sm" style="margin:5px;">
            <?php echo $po_subject ?> 결과
			<span class="label label-info pull-right">전체 <?php echo $nf_total_po_cnt ?>표</span>
        </div>

        <?php for ($i=1; $i<=count($list); $i++) {  ?>
		<div style="margin:15px 0;"><span class="label label-info"><?php echo $list[$i]['content'] ?></span> <strong><?php echo $list[$i]['cnt'] ?> 표</strong></div>
		
        <div class="progress tooltip-top" title="" data-original-title="<?php echo number_format($list[$i]['rate'], 1) ?>%" >
          <div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo number_format($list[$i]['rate'], 1) ?>%">
           <span class="sr-only"><?php echo number_format($list[$i]['rate'], 1) ?>%</span>
          </div>
        </div>
        <?php }  ?>
    <!-- } 설문조사 결과 그래프 끝 -->

    <!-- 설문조사 기타의견 시작 { -->
	<?php if ($is_etc) {  ?>
    <section id="poll_result_cmt">
        <?php for ($i=0; $i<count($list2); $i++) {  ?>
        <div class="well well-sm" style="margin:5px;">이 설문에 대한 기타의견<span class="label label-info pull-right"><?php echo $list2[$i]['pc_name'] ?><span class="sound_only">님의 의견</span></span></div>
               <span class="label label-info pull-right"><?php echo $list2[$i]['datetime'] ?></span>
                <?php echo $list2[$i]['idea'] ?>
                <span class="poll_cmt_del"><?php if ($list2[$i]['del']) { echo $list2[$i]['del']."<i class='fa fa-cut fa-fw'></i>삭제</a>"; }  ?></span>
        <?php }  ?>

        <?php if ($member['mb_level'] >= $po['po_level']) {  ?>
        <form name="fpollresult" action="<?php echo G5_BBS_URL?>/poll_etc_update.php" onsubmit="return fpollresult_submit(this);" method="post" autocomplete="off" class="form-inline">
        <input type="hidden" name="po_id" value="<?php echo $po_id ?>">
        <input type="hidden" name="w" value="">
        <input type="hidden" name="skin_dir" value="<?php echo $skin_dir ?>">
        <?php if ($is_member) {  ?><input type="hidden" name="pc_name" value="<?php echo cut_str($member['mb_nick'],255) ?>"><?php }  ?>

       <div class="well well-sm" style="margin:5px;"><?php echo $po_etc ?></span></div>

        <div class="table-responsive">
         <table class="table table-bordered table-striped">
            <tbody>
            <?php if ($is_guest) {  ?>
            <tr>
                <td><input type="text" name="pc_name" id="pc_name" required class="form-control required" size="10" placeholder="이름" style="width:100%;"></td>
            </tr>
            <?php }  ?>
            <tr>
                <td><input type="text" id="pc_idea" name="pc_idea" required class="form-control required" size="47" maxlength="100" placeholder="의견" style="width:100%;"></td>
            </tr>
            <?php if ($is_guest) {  ?>
            <tr>
                <td><?php echo captcha_html(); ?></td>
            </tr>
            <?php }  ?>
            </tbody>
            </table>
        </div>

        <div class="text-center">
            <input type="submit" value="의견남기기" class="btn btn-default">
        </div>
        </form>
        <?php }  ?>

    </section>
    <?php }  ?>
    <!-- } 설문조사 기타의견 끝 -->

	<!-- 설문조사 다른 결과 보기 시작 { -->
    <aside id="poll_result_oth">
        <div class="well well-sm" style="margin:5px;">다른 투표 결과 보기</div>
        <ul class="list-group">
            <?php for ($i=0; $i<count($list3); $i++) {  ?>
            <li class="list-group-item"><a href="<?php echo G5_BBS_URL?>/poll_result.php?po_id=<?php echo $list3[$i]['po_id'] ?>&amp;skin_dir=<?php echo $skin_dir ?>">[<?php echo $list3[$i]['date'] ?>] <?php echo $list3[$i]['subject'] ?></a></li>
            <?php }  ?>
        </ul>
    </aside>
    <!-- } 설문조사 다른 결과 보기 끝 -->

	</div>

	<div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="window.close();"><i class="fa fa-times-circle"></i> Close</button>
    </div>


      </div>
</div>

<script>
$(function() {
    $(".poll_delete").click(function() {
        if(!confirm("해당 기타의견을 삭제하시겠습니까?"))
            return false;
    });
});

function fpollresult_submit(f)
{
    <?php if ($is_guest) { echo chk_captcha_js(); }  ?>

    return true;
}
</script>
<!-- } 설문조사 결과 끝 -->