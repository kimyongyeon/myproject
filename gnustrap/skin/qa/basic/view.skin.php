<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

    <!-- 게시물 상단 버튼 시작 { -->
    <?php ob_start();?>
    <div id="bo_v_top" class="clearfix" style="margin-bottom:5px;">
        <?php if ($prev_href || $next_href) { ?>
        <div class="btn-group hidden-xs pull-left" style="margin-right:5px;">
            <?php if ($prev_href) { ?><a href="<?php echo $prev_href ?>" class="btn btn-default"><i class="fa fa-step-backward"></i> 이전글</a><?php } ?>
            <?php if ($next_href) { ?><a href="<?php echo $next_href ?>" class="btn btn-default"><i class="fa fa-step-forward"></i> 다음글</a><?php } ?>
        </div>
        <?php } ?>

        <div class="btn-group hidden-xs pull-left">
			<?php if ($update_href) { ?><a href="<?php echo $update_href ?>" class="btn btn-default"><i class="fa fa-exchange"></i> 수정</a><?php } ?>
            <?php if ($delete_href) { ?><a href="<?php echo $delete_href ?>" class="btn btn-default" onclick="del(this.href); return false;"><i class="fa fa-scissors"></i> 삭제</a><?php } ?>
            <?php if ($copy_href) { ?><a href="<?php echo $copy_href ?>" class="btn btn-danger" onclick="board_move(this.href); return false;"><i class="fa fa-files-o"></i> 복사</a><?php } ?>
            <?php if ($move_href) { ?><a href="<?php echo $move_href ?>" class="btn btn-danger" onclick="board_move(this.href); return false;"><i class="fa fa-indent"></i> 이동</a><?php } ?>
            <?php if ($search_href) { ?><a href="<?php echo $search_href ?>" class="btn btn-default"><i class="fa fa-search"></i> 검색</a><?php } ?>
        </div>
        <div class="btn-group hidden-xs pull-right">
            <a href="<?php echo $list_href ?>" class="btn btn-default"><i class="fa fa-list-alt"></i> 목록</a>
            <?php if ($reply_href) { ?><a href="<?php echo $reply_href ?>" class="btn btn-default"><i class="fa fa-reply"></i> 답변</a><?php } ?>
            <?php if ($write_href) { ?><a href="<?php echo $write_href ?>" class="btn btn-default"><i class="fa fa-edit"></i> 글쓰기</a><?php } ?>
		</div>

        <div class="btn-group visible-xs">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="width:100%;">
		<span class="caret"></span></button>
          <ul class="dropdown-menu" role="menu">
            <li><?php if ($prev_href) { ?><a href="<?php echo $prev_href ?>"><i class="fa fa-step-backward"></i> 이전글</a><?php } ?></li>
            <li><?php if ($next_href) { ?><a href="<?php echo $next_href ?>"><i class="fa fa-step-forward"></i> 다음글</a><?php } ?></li>
            <li><?php if ($update_href) { ?><a href="<?php echo $update_href ?>"><i class="fa fa-exchange"></i> 수정</a><?php } ?></li>
            <li><?php if ($delete_href) { ?><a href="<?php echo $delete_href ?>" onclick="del(this.href); return false;"><i class="fa fa-scissors"></i> 삭제</a><?php } ?></li>
            <li><?php if ($copy_href) { ?><a href="<?php echo $copy_href ?>" onclick="board_move(this.href); return false;"><i class="fa fa-files-o"></i> 복사</a><?php } ?></li>
            <li><?php if ($move_href) { ?><a href="<?php echo $move_href ?>" onclick="board_move(this.href); return false;"><i class="fa fa-indent"></i> 이동</a><?php } ?></li>
            <li><a href="<?php echo $list_href ?>">목록</a></li>
            <li><?php if ($reply_href) { ?><a href="<?php echo $reply_href ?>"><i class="fa fa-reply"></i> 답변</a><?php } ?></li>
            <li><?php if ($write_href) { ?><a href="<?php echo $write_href ?>"><i class="fa fa-edit"></i> 글쓰기</a><?php } ?></li>
          </ul>
        </div>
    </div>
	
        <?php
        $link_buttons = ob_get_contents();
        ob_end_flush();
         ?>
    <!-- } 게시물 상단 버튼 끝 -->

<!-- 게시물 읽기 시작 { -->
<div id="bo_v_table" class="panel panel-default">
<div class="panel-heading">
            <strong class="tooltip-top" title="제목">
			<i class="fa fa-book fa-fw"></i> 
			<?php
            echo $view['category'].' | '; // 분류 출력 끝
            echo $view['subject']; // 글제목 출력
            ?>
			</strong>
			<div style="margin-top:15px;">
			<?php echo $view['name'] ?>
		    <?php if ($is_ip_view) { echo "&nbsp;($ip)"; } ?>
	     	<span class="tooltip-top" title="작성일"><i class="fa fa-calendar-o fa-fw"></i> <?php echo date("y-m-d H:i", strtotime($view['wr_datetime'])) ?></span>
            <span class="tooltip-top" title="조회수"><i class="fa fa-eye fa-fw"></i> <?php echo number_format($view['wr_hit']) ?>회</span>
		    <span class="tooltip-top" title="댓글수"><i class="fa fa-comment-o fa-fw"></i> <?php echo number_format($view['wr_comment']) ?>건</span>
            <p><?php if ($view['file']['count']) {
             $cnt = 0;
             for ($i=0; $i<count($view['file']); $i++) {
             if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view'])
             $cnt++;
		     }
		     }?>
			</p>
			</div>
</div>
<div class="panel-body">

    <?php if($view['download_count']) { ?>
    <!-- 첨부파일 시작 { -->
    <section id="bo_v_file">
          <?php if($view['download_count']) { ?>
          <!-- 첨부파일 시작 { -->
		   <?php
            // 가변 파일
            for ($i=0; $i<$view['download_count']; $i++) {
            ?>
		   <div class="well well-sm">
                <a href="<?php echo $view['download_href'][$i];  ?>" class="view_file_download tooltip-top" title="다운로드 파일">
                  <i class="fa fa-floppy-o"></i> <?php echo $view['download_source'][$i] ?>
                </a>
           <?php } ?>
           <!-- } 첨부파일 끝 -->
           <?php } ?>
		   <span class="pull-right">
           <?php if($view['email'] || $view['hp']) { ?>
          
			<?php if($view['email']) { ?>
			<span class="tooltip-top" title="메일"><i class="fa fa-envelope-o"></i> <?php echo $view['email']; ?></span>
            <?php } ?>
            <?php if($view['hp']) { ?>
            <span class="tooltip-top" title="연락처"><i class="fa fa-mobile-phone"></i> <?php echo $view['hp']; ?></span>
            <?php } ?>
			</span>
		   </div>
           <?php } ?>
    </section>
    <!-- } 첨부파일 끝 -->
    <?php } ?>

    <section id="bo_v_atc">
        <?php
        // 파일 출력
        if($view['img_count']) {
            echo "<div id=\"bo_v_img\">\n";

            for ($i=0; $i<$view['img_count']; $i++) {
                //echo $view['img_file'][$i];
                echo get_view_thumbnail($view['img_file'][$i], $qaconfig['qa_image_width']);
            }

            echo "</div>\n";
        }
         ?>

        <!-- 본문 내용 시작 { -->
        <div id="bo_v_con"><?php echo get_view_thumbnail($view['content'], $qaconfig['qa_image_width']); ?></div>
        <!-- } 본문 내용 끝 -->

        <?php if($view['qa_type']) { ?>
        <div id="bo_v_addq"><a href="<?php echo $rewrite_href; ?>" class="btn_b01">추가질문</a></div>
        <?php } ?>

    </section>
    <?php
    // 질문글에서 답변이 있으면 답변 출력, 답변이 없고 관리자이면 답변등록폼 출력
    if(!$view['qa_type']) {
        if($view['qa_status'] && $answer['qa_id'])
            include_once($qa_skin_path.'/view.answer.skin.php');
        else
            include_once($qa_skin_path.'/view.answerform.skin.php');
    }
    ?>

<?php if($view['rel_count']) { ?>

<div class="portlet">
<div class="portlet-header">
    <div class="well well-sm"><strong>연관질문</strong></div>
</div>

        <div class="portlet-content">
            <table class="table table-bordered media-table">
            <thead>
            <tr>
                <th scope="col" class="hidden-xs text-center">분류</th>
                <th scope="col" class="text-center">제목</th>
                <th scope="col" class="hidden-xs text-center" style="width:100px;">상태</th>
                <th scope="col" class="hidden-xs text-center" style="width:80px;">등록일</th>
            </tr>
            </thead>
            <tbody>
            <?php
            for($i=0; $i<$view['rel_count']; $i++) {
            ?>
            <tr>
                <td class="td_category hidden-xs text-center"><?php echo get_text($rel_list[$i]['category']); ?></td>
                <td class="text-left">
                    <a href="<?php echo $rel_list[$i]['view_href']; ?>">
                        <?php echo $rel_list[$i]['subject']; ?>
                    </a>
					<div class="visible-xs well well-sm clearfix" style="margin: 15px 0 0 0;">
				    <span class="badge pull-left"><?php echo get_text($rel_list[$i]['category']); ?></span>
					<span class="pull-right"><i class="fa fa-calendar-o fa-fw"></i> <?php echo $rel_list[$i]['date']; ?></span>
					</div>
                </td>
                <td class="hidden-xs text-center td_stat <?php echo ($list[$i]['qa_status'] ? 'txt_done' : 'txt_rdy'); ?>"><?php echo ($rel_list[$i]['qa_status'] ? '<span class="text-success">답변완료</span>' : '<span clss="text-info">답변대기</span>'); ?></td>
                <td class="hidden-xs text-left td_date"><?php echo $rel_list[$i]['date']; ?></td>
            </tr>
			<tr>
			<td colspan="2" class="visible-xs text-center td_stat <?php echo ($list[$i]['qa_status'] ? 'txt_done' : 'text-info'); ?>"><?php echo ($rel_list[$i]['qa_status'] ? '<span class="text-success">답변완료</span>' : '<span clss="text-info">답변대기</span>'); ?>
			</td>
			</tr>
            <?php
            }
            ?>
            </tbody>
            </table>
        </div>
    </div>
    <?php } ?>

    <!-- 링크 버튼 시작 { -->
    <div id="bo_v_bot">
        <?php echo $link_buttons ?>
    </div>
    <!-- } 링크 버튼 끝 -->
</div>
<!-- } 게시판 읽기 끝 -->

</div>
<script>
<?php if ($board['bo_download_point'] < 0) { ?>
$(function() {
    $("a.view_file_download").click(function() {
        if(!g5_is_member) {
            alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
            return false;
        }

        var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

        if(confirm(msg)) {
            var href = $(this).attr("href")+"&js=on";
            $(this).attr("href", href);

            return true;
        } else {
            return false;
        }
    });
});
<?php } ?>

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

    // 추천, 비추천
    $("#good_button, #nogood_button").click(function() {
        var $tx;
        if(this.id == "good_button")
            $tx = $("#bo_v_act_good");
        else
            $tx = $("#bo_v_act_nogood");

        excute_good(this.href, $(this), $tx);
        return false;
    });

    // 이미지 리사이즈
    $("#bo_v_atc").viewimageresize();
});

function excute_good(href, $el, $tx)
{
    $.post(
        href,
        { js: "on" },
        function(data) {
            if(data.error) {
                alert(data.error);
                return false;
            }

            if(data.count) {
                $el.find("strong").text(number_format(String(data.count)));
                if($tx.attr("id").search("nogood") > -1) {
                    $tx.text("이 글을 비추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                } else {
                    $tx.text("이 글을 추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                }
            }
        }, "json"
    );
}
</script>
<!-- } 게시글 읽기 끝 -->