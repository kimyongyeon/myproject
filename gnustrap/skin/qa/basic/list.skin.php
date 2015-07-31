<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 6;

if ($is_checkbox) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);
?>
<div class="panel panel-default">
<div class="panel-heading">
		<h5><i class="fa fa-bar-chart-o"></i> 1:1 문의<span class="sound_only"> 목록</span></h5>
</div>

<div id="bo_list"  class="panel-body table-content">
    <?php if ($category_option) { ?>
    <!-- 카테고리 시작 { -->
    <nav id="bo_cate">
        <ul class="breadcrumb" id="bo_cate_ul">
            <?php echo $category_option ?>
        </ul>
    </nav>
    <!-- } 카테고리 끝 -->
    <?php } ?>

     <!-- 게시판 페이지 정보 및 버튼 시작 { -->
	    <div class="pull-left">
		    <span class="btn btn-default tooltip-top" title="전체 글수" data-original-title=" 전체 글수 ">
		    <i class="fa fa-tags fa-lg"></i> <?php echo number_format($total_count) ?> 개</span>
			<span class="btn btn-default tooltip-top" title="현재 페이지" data-original-title=" 현재 페이지 ">
			<?php echo $page ?> 페이지</span>
	    </div>
		
		<div class="pull-right">
			<div class="btn-group">
		    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			<i class="fa fa-cog"></i> &nbsp;&nbsp;<span class="caret"></span>
			</button>
        <?php if ($admin_href || $write_href) { ?>
			<ul class="dropdown-menu pull-right" role="menu">
            <?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>"><i class="fa fa-wrench"></i> 관리자</a></li><?php } ?>
            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>"><i class="fa fa-edit"></i> 문의등록</a></li><?php } ?>
			</ul>
        <?php } ?>
			</div>
		</div>

<div class="clearfix"></div>
<hr>

    <!-- } 게시판 페이지 정보 및 버튼 끝 -->

    <form name="fqalist" id="fqalist" action="./qadelete.php" onsubmit="return fqalist_submit(this);" method="post" class="form-inline">
    <input type="hidden" name="stx" value="<?php echo $stx; ?>">
    <input type="hidden" name="sca" value="<?php echo $sca; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">

        <table class="panel-body table table-bordered media-table">
        <!--<caption><?php echo $board['bo_subject'] ?> 목록</caption>-->
        <thead>
        <tr>
            <th scope="col" class="text-center">번호</th>
            <?php if ($is_checkbox) { ?>
            <th scope="col">
                <label for="chkall" class="sound_only">현재 페이지 게시물 전체</label>
                <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
            </th>
            <?php } ?>
            <th scope="col" class="hidden-xs text-center">분류</th>
            <th scope="col" class="hidden-xs text-center">제목</th>
            <th scope="col" class="hidden-xs text-center">글쓴이</th>
            <th scope="col" class="hidden-xs text-center">상태</th>
            <th scope="col" class="hidden-xs text-center">등록일</th>
            <th scope="col" class="visible-xs text-center">컨텐츠</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for ($i=0; $i<count($list); $i++) {
        ?>
        <tr>
            <td class="td_num text-center"><?php echo $list[$i]['num']; ?></td>
            <?php if ($is_checkbox) { ?>
            <td class="td_chk text-left">
                <label for="chk_qa_id_<?php echo $i ?>" class="sound_only"><?php echo $list[$i]['subject']; ?></label>
                <input type="checkbox" name="chk_qa_id[]" value="<?php echo $list[$i]['qa_id'] ?>" id="chk_qa_id_<?php echo $i ?>">
            </td>
            <?php } ?>
            <td class="td_stat hidden-xs text-center"><?php echo $list[$i]['category']; ?></td>
            <td class="td_subject hidden-xs text-left">
                <a href="<?php echo $list[$i]['view_href']; ?>">
                    <?php echo $list[$i]['subject']; ?>
                </a>
                <?php echo $list[$i]['icon_file']; ?>
            </td>
            <td class="td_nick hidden-xs text-left"><?php echo $list[$i]['name']; ?></td>
            <td class="td_stat hidden-xs text-center <?php echo ($list[$i]['qa_status'] ? 'txt_done' : 'txt_rdy'); ?>"><?php echo ($list[$i]['qa_status'] ? '<span class="text-success">답변완료</span>' : '<span class="text-info">답변대기</span>'); ?></td>
            <td class="td_date hidden-xs text-center"><?php echo $list[$i]['date']; ?></td>

			<!--모바일-->
            <td class="visible-xs text-left">
			<span class="badge"><?php echo $list[$i]['category']; ?></span>
                <a href="<?php echo $list[$i]['view_href']; ?>">
                    <?php echo $list[$i]['subject']; ?>
                </a>
                <?php echo $list[$i]['icon_file']; ?>
				<div class="well well-sm clearfix" style="margin: 15px 0 0 0; line-height: 35px;">
				<span class="pull-left"><i class="fa fa-calendar-o fa-fw"></i> <?php echo $list[$i]['date']; ?></span>
				<span class="pull-right"><?php echo $list[$i]['name']; ?></span>
				</div>
			</td>
			<!--모바일 끝-->
        </tr>
		<tr>
		<td colspan="3" class="td_stat visible-xs text-center <?php echo ($list[$i]['qa_status'] ? 'txt_done' : 'txt_rdy'); ?>"><?php echo ($list[$i]['qa_status'] ? '<span class="text-success">답변완료</span>' : '<span class="text-info">답변대기</span>'); ?></td>
		</tr>
        <?php
        }
        ?>

        <?php if ($i == 0) { echo '<tr><td colspan="'.$colspan.'" class="text-center">게시물이 없습니다.</td></tr>'; } ?>
        </tbody>
        </table>

<div class="clearfix">
        <?php if ($is_checkbox) { ?>
        <div class="btn-group pull-left">
            <input type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-default">
        </div>
        <?php } ?>

        <div class="btn-group pull-right">
            <?php if ($list_href) { ?><a href="<?php echo $list_href ?>" class="btn btn-default"><i class="fa fa-list"></i> 목록</a><?php } ?>
            <?php if ($write_href) { ?><a href="<?php echo $write_href ?>" class="btn btn-default"><i class="fa fa-edit"></i> 문의등록</a><?php } ?>
        </div>
    </div>
    </form>
</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>
</div>
<!-- 페이지 -->
<?php echo $list_pages;  ?>

<!-- 게시판 검색 시작 { -->
<fieldset id="bo_sch" class="well text-center">
    <form name="fsearch" method="get" class="form-inline">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
	<div class="input-group" style="margin-top:5px;">
    <input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" id="stx" required  class="form-control required" size="15" maxlength="15" placeholder="검색어">
    <span class="input-group-btn">
    <input type="submit" value="검색" class="btn btn-primary">
    </span>
	</div>
    </form>
</fieldset>
<!-- } 게시판 검색 끝 -->

<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fqalist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_qa_id[]")
            f.elements[i].checked = sw;
    }
}

function fqalist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_qa_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다"))
            return false;
    }

    return true;
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->