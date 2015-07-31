<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/style.css">', 0);
?>
<div class="panel panel-default">
<div class="panel-heading">
		<h5><i class="fa fa-bar-chart-o"></i> <?php echo $board['bo_subject'] ?><span class="sound_only"> 목록</span></h5>
</div>

<!-- 게시판 목록 시작 { -->
<div id="bo_gall" style="width:<?php echo $width; ?>" class="panel-body table-content">

    <!-- 게시판 카테고리 시작 { -->
    <?php if ($is_category) { ?>
    <nav id="bo_cate">
        <ol class="breadcrumb" id="bo_cate_ul">
            <?php echo $category_option ?>
        </ol>
    </nav>
    <?php } ?>
    <!-- } 게시판 카테고리 끝 -->

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
        <?php if ($rss_href || $write_href) { ?>
			<ul class="dropdown-menu pull-right" role="menu">
                <?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>"><i class="fa fa-wrench"></i> 관리자</a></li><?php } ?>
                <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>"><i class="fa fa-edit"></i> 글쓰기</a></li><?php } ?>
				<?php if ($rss_href) { ?><li><a href="<?php echo $rss_href ?>"><i class="fa fa-rss"></i> RSS</a></li><?php } ?>
			</ul>
        <?php } ?>
			</div>
		</div>
<div class="clearfix"></div>
<hr>

    <!-- } 게시판 페이지 정보 및 버튼 끝 -->

    <form name="fboardlist"  id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="sw" value="">

    <?php if ($is_checkbox) { ?>
    <div id="gall_allchk">
	<label class="btn btn-default" for="chkall">
	<input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);" data-toggle="checkbox"> 전체체크
	</label>
    </div>
    <?php } ?>

    <div id="gall_ul">
        <?php for ($i=0; $i<count($list); $i++) {
            if($i>0 && ($i % $bo_gallery_cols == 0))
                $style = 'clear:both;';
            else
                $style = '';
            if ($i == 0) $k = 0;
            $k += 1;
            if ($k % $bo_gallery_cols == 0) $style .= "margin:0 !important;";
         ?>
        <div class="col-sm-12 col-md-6 col-lg-3">
            <?php if ($is_checkbox) { ?>
            <label for="chk_wr_id_<?php echo $i ?>" class="sound_only"><?php echo $list[$i]['subject'] ?></label>
            <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
            <?php } ?>
            <span class="sound_only">
                <?php
                if ($wr_id == $list[$i]['wr_id'])
                    echo "<span class=\"bo_current\">열람중</span>";
                else
                    echo $list[$i]['num'];
                 ?>
            </span>
            <div class="thumbnail">
			<a href="<?php echo $list[$i]['href'] ?>">
                <div class="gall_href text-center">
                    <?php
                    if ($list[$i]['is_notice']) { // 공지사항  ?>
                        <i class="notice fa fa-microphone fa-border tooltip-top" title="공지사항"></i>
                    <?php } else {
                        $thumb = get_list_thumbnail($board['bo_table'], $list[$i]['wr_id'], $board['bo_gallery_width'], $board['bo_gallery_height']);

                        if($thumb['src']) {
                            $img_content = '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'">';
                        } else {
                            $img_content = '<span>
							<span class="fa-stack fa-lg">
							<i class="fa fa-image fa-stack-1x"></i>
							<i class="fa fa-ban fa-stack-2x text-danger"></i>
							</span>
							</span>';
                        }

                        echo $img_content;
                    }
                     ?>
                </div>
             </a>

                <div class="caption">
                    <?php
                    // echo $list[$i]['icon_reply']; 갤러리는 reply 를 사용 안 할 것 같습니다. - 지운아빠 2013-03-04
                    if ($is_category && $list[$i]['ca_name']) {
                     ?>
                    <a href="<?php echo $list[$i]['ca_name_href'] ?>" class="label label-info"><?php echo $list[$i]['ca_name'] ?></a>
                    <?php } ?>
                    <a href="<?php echo $list[$i]['href'] ?>">
                        <?php echo $list[$i]['subject'] ?>
                        <?php if ($list[$i]['comment_cnt']) { ?><span class="sound_only">댓글</span><?php echo $list[$i]['comment_cnt']; ?><span class="sound_only">개</span><?php } ?>
                    </a>
                    <?php
                    // if ($list[$i]['link']['count']) { echo '['.$list[$i]['link']['count']}.']'; }
                    // if ($list[$i]['file']['count']) { echo '<'.$list[$i]['file']['count'].'>'; }

                    if (isset($list[$i]['icon_new'])) echo $list[$i]['icon_new'];
                    if (isset($list[$i]['icon_hot'])) echo $list[$i]['icon_hot'];
                    //if (isset($list[$i]['icon_file'])) echo $list[$i]['icon_file'];
                    //if (isset($list[$i]['icon_link'])) echo $list[$i]['icon_link'];
                    //if (isset($list[$i]['icon_secret'])) echo $list[$i]['icon_secret'];
                     ?>
                </div>
				<div class="clearfix"></div>
				<hr>
                <div class="caption">
				<span class="pull-left namecard"><?php echo $list[$i]['name'] ?></span>
				<span class="pull-right">
				<i class="fa fa-clock-o fa-fw tooltip-top" title="작성일"></i> <?php echo $list[$i]['datetime2'] ?>
				<i class="fa fa-comments fa-fw tooltip-top" title="조회수"></i> <?php echo $list[$i]['wr_hit'] ?>
				</span>
				</div>
				<div class="clearfix"></div>
                <?php if ($is_good) { ?>
				<div class="text-right good">
				<span class="fa-stack fa-lg tooltip-top" title="추천">
				<i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-thumbs-o-up fa-stack-1x"></i>
				</span>
				<strong><?php echo $list[$i]['wr_good'] ?></strong>
				<?php } ?>
                <?php if ($is_nogood) { ?>
				<span class="fa-stack fa-lg tooltip-top" title="비추천">
				<i class="fa fa-square-o fa-stack-2x"></i>
				<i class="fa fa-thumbs-o-down fa-stack-1x"></i>
				</span>
				<strong><?php echo $list[$i]['wr_nogood'] ?></strong>
				</div>
				<?php } ?>
            </div>
        </div>
        <?php } ?>
        <?php if (count($list) == 0) { echo "<li class=\"text-center\">게시물이 없습니다.</li>"; } ?>
    </div>

<div class="clearfix"></div>
    <?php if ($list_href || $is_checkbox || $write_href) { ?>

        <?php if ($list_href || $write_href) { ?>
        <div class="btn-group pull-right">
        <?php if ($list_href) { ?><a href="<?php echo $list_href ?>" class="btn btn-default"><i class="fa fa-list"></i> 목록</a><?php } ?>
        <?php if ($write_href) { ?><a href="<?php echo $write_href ?>" class="btn btn-default"><i class="fa fa-edit"></i> 글쓰기</a><?php } ?>
        </div>
        <?php } ?>

        <?php if ($is_checkbox) { ?>
        <div class="btn-group pull-left">
            <input type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-default">
            <input type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value" class="btn btn-default">
            <input type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value" class="btn btn-default">
        </div>
        <?php } ?>
    <?php } ?>
    </form>
</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>

<!-- 페이지 -->
<?php echo $write_pages;  ?>
</div>

<!-- 게시판 검색 시작 { -->

<fieldset id="bo_sch" class="well text-center">
    <form name="fsearch" method="get" class="form-inline">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sop" value="and">
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl" class="form-control">
        <option value="wr_subject"<?php echo get_selected($sfl, 'wr_subject', true); ?>>제목</option>
        <option value="wr_content"<?php echo get_selected($sfl, 'wr_content'); ?>>내용</option>
        <option value="wr_subject||wr_content"<?php echo get_selected($sfl, 'wr_subject||wr_content'); ?>>제목+내용</option>
        <option value="mb_id,1"<?php echo get_selected($sfl, 'mb_id,1'); ?>>회원아이디</option>
        <option value="mb_id,0"<?php echo get_selected($sfl, 'mb_id,0'); ?>>회원아이디(코)</option>
        <option value="wr_name,1"<?php echo get_selected($sfl, 'wr_name,1'); ?>>글쓴이</option>
        <option value="wr_name,0"<?php echo get_selected($sfl, 'wr_name,0'); ?>>글쓴이(코)</option>
    </select>
	<div class="input-group">
            <input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" required id="stx" class="form-control required" size="15" maxlength="20" placeholder="검색어">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">검색</button>
            </span>
    </div>
    </form>
</fieldset>
<!-- } 게시판 검색 끝 -->

<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function fboardlist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택복사") {
        select_copy("copy");
        return;
    }

    if(document.pressed == "선택이동") {
        select_copy("move");
        return;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
            return false;

        f.removeAttribute("target");
        f.action = "./board_list_update.php";
    }

    return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == 'copy')
        str = "복사";
    else
        str = "이동";

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = "./move.php";
    f.submit();
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->
