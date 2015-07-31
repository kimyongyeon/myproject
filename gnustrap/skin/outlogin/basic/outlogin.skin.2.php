<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$outlogin_skin_url.'/style.css">', 0);
?>

<!-- 로그인 후 아웃로그인 시작 { -->
<section id="ol_after" class="well well-sm text-center">
    <div class="ol_top">
        <strong><?php echo $nick ?>님</strong>
        <?php if ($is_admin == 'super' || $is_auth) {  ?><a href="<?php echo G5_ADMIN_URL ?>"><i class="fa fa-cog fa-spin fa-fw margin-bottom tooltip-top pull-right" title="관리자모드"></i></a><?php }  ?>
	</div>
    <div class="ol_center">
            <a href="<?php echo G5_BBS_URL ?>/memo.php" target="_blank" id="ol_after_memo" class="btn btn-default tooltip-top" title="쪽지">
                <i class="fa fa-envelope-o fa-fw"></i>
                <strong><?php echo $memo_not_read ?></strong>
            </a>
            <a href="<?php echo G5_BBS_URL ?>/point.php" target="_blank" id="ol_after_pt" class="btn btn-default tooltip-top" title="포인트"><i class="fa fa-diamond fa-fw"></i><?php echo $point ?></a>
            <a href="<?php echo G5_BBS_URL ?>/scrap.php" target="_blank" id="ol_after_scrap" class="btn btn-default tooltip-top" title="스크랩"><i class="fa fa-inbox fa-fw"></i>스크랩</a>
    </div>
	<div>
        <a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=register_form.php" class="btn btn-default"><i class="fa fa-gears fa-fw"></i>정보수정</a>
        <a href="<?php echo G5_BBS_URL ?>/logout.php" class="btn btn-default"><i class="fa fa-power-off fa-fw"></i>로그아웃</a>
	</div>
</section>

<script>
// 탈퇴의 경우 아래 코드를 연동하시면 됩니다.
function member_leave()
{
    if (confirm("정말 회원에서 탈퇴 하시겠습니까?"))
        location.href = "<?php echo G5_BBS_URL ?>/member_confirm.php?url=member_leave.php";
}
</script>
<!-- } 로그인 후 아웃로그인 끝 -->
