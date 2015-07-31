<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$search_skin_url.'/style.css">', 0);
?>

<!-- 전체검색 시작 { -->

<form name="fsearch" onsubmit="return fsearch_submit(this);" class="form-inline text-center well well-sm" method="get">
<input type="hidden" name="srows" value="<?php echo $srows ?>">
    <div class="form-group">
    <label class="sound_only">상세검색</label>
    <?php echo $group_select ?>
	<script>document.getElementById("gr_id").value = "<?php echo $gr_id ?>";</script>
    </div>
    <div class="form-group">
    <label for="sfl" class="sound_only">검색조건</label>
    <select name="sfl" id="sfl" class="form-control">
        <option value="wr_subject||wr_content"<?php echo get_selected($_GET['sfl'], "wr_subject||wr_content") ?>>제목+내용</option>
        <option value="wr_subject"<?php echo get_selected($_GET['sfl'], "wr_subject") ?>>제목</option>
        <option value="wr_content"<?php echo get_selected($_GET['sfl'], "wr_content") ?>>내용</option>
        <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id") ?>>회원아이디</option>
        <option value="wr_name"<?php echo get_selected($_GET['sfl'], "wr_name") ?>>이름</option>
    </select>
    </div>
	<div class="input-group">
            <input type="text" name="stx" value="<?php echo $text_stx ?>" id="stx" required class="required form-control" placeholder="Search" maxlength="20">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">검색</button>
            </span>
    </div>

    <script>
    function fsearch_submit(f)
    {
        if (f.stx.value.length < 2) {
            alert("검색어는 두글자 이상 입력하십시오.");
            f.stx.select();
            f.stx.focus();
            return false;
        }

        // 검색에 많은 부하가 걸리는 경우 이 주석을 제거하세요.
        var cnt = 0;
        for (var i=0; i<f.stx.value.length; i++) {
            if (f.stx.value.charAt(i) == ' ')
                cnt++;
        }

        if (cnt > 1) {
            alert("빠른 검색을 위하여 검색어에 공백은 한개만 입력할 수 있습니다.");
            f.stx.select();
            f.stx.focus();
            return false;
        }

        f.action = "";
        return true;
    }
    </script>

<div class="clearfix"></div>
	<div class="btn-group" data-toggle="buttons" style="margin:10px 0;">
     <label class="btn btn-primary">
     <input type="radio" value="or" <?php echo ($sop == "or") ? "checked" : ""; ?> id="sop_or" name="sop"> OR
     </label>
     <label class="btn btn-primary">
     <input type="radio" value="and" <?php echo ($sop == "and") ? "checked" : ""; ?> id="sop_and" name="sop"> AND
     </label>
    </div>
</form>
    <?php
    if ($stx) {
        if ($board_count) {
    ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <span class="btn btn-default tooltip-top" title="" data-original-title="게시판">
		    <i class="fa fa-tags fa-lg"></i> <?php echo $board_count ?>개</span>
			<span class="btn btn-default tooltip-top" title="" data-original-title="게시물">
			<?php echo number_format($total_count) ?>개
		</span>
        <div class="pull-right">
            <span class="label label-info">
			<i class="fa fa-eye fa-fw tooltip-top" title="열람중"></i><?php echo number_format($page) ?>/<?php echo number_format($total_page) ?>
			</span>
        </div>
    </div>
    <?php } }  ?>

    <?php
    if ($stx) {
        if ($board_count) {
     ?>
	<div style="margin:5px;">
    <ul class="nav nav-tabs nav-justified">
        <li <?php echo $sch_all ?>><a href="?<?php echo $search_query ?>&amp;gr_id=<?php echo $gr_id ?>" <?php echo $sch_all ?>>전체게시판</a></li>
        <?php echo $str_board_list; ?>
    </ul>
	</div>
    <?php } else { ?>
    <div class="text-center well text-danger"><i class="fa fa-exclamation-triangle fa-fw"></i>검색된 자료가 하나도 없습니다.</div>
    <?php } }  ?>

    <?php if ($stx && $board_count) { ?><div style="margin:0 20px;"><?php }  ?>
    <?php
    $k=0;
    for ($idx=$table_index, $k=0; $idx<count($search_table) && $k<$rows; $idx++) {
     ?>
        <div class="well well-sm">
		<a href="./board.php?bo_table=<?php echo $search_table[$idx] ?>&amp;<?php echo $search_query ?>" class="btn btn-default"><i class="fa fa-link fa-fw"></i><?php echo $bo_subject[$idx] ?> 게시판 내 결과</a>
		</div>
        <?php
        for ($i=0; $i<count($list[$idx]) && $k<$rows; $i++, $k++) {
            if ($list[$idx][$i]['wr_is_comment'])
            {
                $comment_def = '<span class="badge">댓글</span> ';
                $comment_href = '#c_'.$list[$idx][$i]['wr_id'];
            }
            else
            {
                $comment_def = '';
                $comment_href = '';
            }
         ?>
		 <div class="panel panel-default">
		<div class="panel-heading">
		<a href="<?php echo $list[$idx][$i]['href'] ?><?php echo $comment_href ?>" class="sch_res_title"><?php echo $comment_def ?><?php echo $list[$idx][$i]['subject'] ?></a>
                <a href="<?php echo $list[$idx][$i]['href'] ?><?php echo $comment_href ?>" target="_blank" class="btn btn-default"><i class="fa fa-link fa-fw"></i></a>
        <div class="pull-right">
        <i class="fa fa-clock-o fa-fw"></i><?php echo $list[$idx][$i]['wr_datetime'] ?>
		<?php echo $list[$idx][$i]['name'] ?>
		</div>
		</div>
		<div class="panel panel-body">
                <?php echo $list[$idx][$i]['content'] ?>
		</div>
		</div>
        <?php }  ?>
    <?php }  ?>
    <?php if ($stx && $board_count) {  ?></div><?php }  ?>

    <?php echo $write_pages ?>
<!-- } 전체검색 끝 -->