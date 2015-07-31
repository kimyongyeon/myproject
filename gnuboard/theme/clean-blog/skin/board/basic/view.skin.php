<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
?>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>
<div class="container">
    <div class="row">
        <h2 class="section-heading"><?php if ($category_name) echo $view['ca_name'].' | '; echo cut_str(get_text($view['wr_subject']), 70); ?></h2>
        <p>작성자 <strong><?php echo $view['name'] ?><?php if ($is_ip_view) { echo "&nbsp;($ip)"; } ?></strong>, <span class="sound_only">작성일</span><strong><?php echo date("y-m-d H:i", strtotime($view['wr_datetime'])) ?></strong>, 조회<strong><?php echo number_format($view['wr_hit']) ?>회</strong>, 댓글<strong><?php echo number_format($view['wr_comment']) ?>건</strong></p>
        <?php
        // 파일 출력
        $v_img_count = count($view['file']);
        if($v_img_count) {
            echo "<div id=\"bo_v_img\">\n";

            for ($i=0; $i<=count($view['file']); $i++) {
                if ($view['file'][$i]['view']) {
                    //echo $view['file'][$i]['view'];
                    echo get_view_thumbnail($view['file'][$i]['view']);
                }
            }

            echo "</div>\n";
        }
        ?>
        <div id="bo_v_con"><?php echo get_view_thumbnail($view['content']); ?></div>
        <?php if ($is_signature) { ?><p><?php echo $signature ?></p><?php } ?>

<?php
include_once(G5_SNS_PATH."/view.sns.skin.php");
?>

<?php
// 코멘트 입출력
include_once(G5_BBS_PATH.'/view_comment.php');
 ?>
    </div>
</div>
<script>
function board_move(href)
{
    window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
</script>

<script>
$(function() {
    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });

    // 이미지 리사이즈
    $("#bo_v_atc").viewimageresize();
});
</script>