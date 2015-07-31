<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

    <!-- 게시물 상단 버튼 시작 { -->
    <?php ob_start();?>
    <div id="bo_v_top" class="clearfix" style="margin-bottom:5px;">
        <?php if ($prev_href || $next_href) { ?>
        <div class="btn-group hidden-xs pull-left" style="margin-right:5px;">
            <?php if ($prev_href) { ?><a href="<?php echo $prev_href ?>" class="btn btn-default"><i class="fa fa-chevron-left fa-fw"></i>이전글</a><?php } ?>
            <?php if ($next_href) { ?><a href="<?php echo $next_href ?>" class="btn btn-default"><i class="fa fa-chevron-right fa-fw"></i>다음글</a><?php } ?>
        </div>
        <?php } ?>

        <div class="btn-group hidden-xs pull-left">
			<?php if ($update_href) { ?><a href="<?php echo $update_href ?>" class="btn btn-default"><i class="fa fa-exchange fa-fw"></i>수정</a><?php } ?>
            <?php if ($delete_href) { ?><a href="<?php echo $delete_href ?>" class="btn btn-default" onclick="del(this.href); return false;"><i class="fa fa-scissors fa-fw"></i>삭제</a><?php } ?>
            <?php if ($copy_href) { ?><a href="<?php echo $copy_href ?>" class="btn btn-danger" onclick="board_move(this.href); return false;"><i class="fa fa-files-o fa-fw"></i>복사</a><?php } ?>
            <?php if ($move_href) { ?><a href="<?php echo $move_href ?>" class="btn btn-danger" onclick="board_move(this.href); return false;"><i class="fa fa-indent fa-fw"></i>이동</a><?php } ?>
            <?php if ($search_href) { ?><a href="<?php echo $search_href ?>" class="btn btn-default"><i class="fa fa-search fa-fw"></i>검색</a><?php } ?>
        </div>
        <div class="btn-group hidden-xs pull-right">
            <a href="<?php echo $list_href ?>" class="btn btn-default"><i class="fa fa-list-alt fa-fw"></i>목록</a>
            <?php if ($reply_href) { ?><a href="<?php echo $reply_href ?>" class="btn btn-default"><i class="fa fa-reply fa-fw"></i>답변</a><?php } ?>
            <?php if ($write_href) { ?><a href="<?php echo $write_href ?>" class="btn btn-default"><i class="fa fa-edit fa-fw"></i>글쓰기</a><?php } ?>
		</div>

        <div class="btn-group visible-xs">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="width:100%;">
		<span class="caret"></span></button>
          <ul class="dropdown-menu" role="menu">
            <li><?php if ($prev_href) { ?><a href="<?php echo $prev_href ?>"><i class="fa fa-chevron-left fa-fw"></i>이전글</a><?php } ?></li>
            <li><?php if ($next_href) { ?><a href="<?php echo $next_href ?>"><i class="fa fa-chevron-right fa-fw"></i>다음글</a><?php } ?></li>
            <li><?php if ($update_href) { ?><a href="<?php echo $update_href ?>"><i class="fa fa-exchange fa-fw"></i>수정</a><?php } ?></li>
            <li><?php if ($delete_href) { ?><a href="<?php echo $delete_href ?>" onclick="del(this.href); return false;"><i class="fa fa-scissors fa-fw"></i>삭제</a><?php } ?></li>
            <li><?php if ($copy_href) { ?><a href="<?php echo $copy_href ?>" onclick="board_move(this.href); return false;"><i class="fa fa-files-o fa-fw"></i>복사</a><?php } ?></li>
            <li><?php if ($move_href) { ?><a href="<?php echo $move_href ?>" onclick="board_move(this.href); return false;"><i class="fa fa-indent fa-fw"></i>이동</a><?php } ?></li>
            <li><a href="<?php echo $list_href ?>"><i class="fa fa-list-alt fa-fw"></i>목록</a></li>
            <li><?php if ($reply_href) { ?><a href="<?php echo $reply_href ?>"><i class="fa fa-reply fa-fw"></i>답변</a><?php } ?></li>
            <li><?php if ($write_href) { ?><a href="<?php echo $write_href ?>"><i class="fa fa-edit fa-fw"></i>글쓰기</a><?php } ?></li>
          </ul>
        </div>
    </div>
	
        <?php
        $link_buttons = ob_get_contents();
        ob_end_flush();
         ?>
    <!-- } 게시물 상단 버튼 끝 -->

<!-- 게시물 읽기 시작 { -->
<div id="bo_v" style="width:<?php echo $width; ?>" class="panel panel-default">
<div class="panel-heading">
            <strong class="tooltip-top" title="제목">
			<i class="fa fa-book fa-fw"></i> 
			<?php
            if ($category_name) echo $view['ca_name'].' | '; // 분류 출력 끝
            echo cut_str(get_text($view['wr_subject']), 70); // 글제목 출력
            ?>
			</strong>
			<div style="margin-top:15px;">
			<?php echo $view['name'] ?>
		    <?php if ($is_ip_view) { echo "&nbsp;($ip)"; } ?>
	     	<span class="tooltip-top" title="작성일"><i class="fa fa-calendar-o fa-fw"></i> <?php echo date("y-m-d H:i", strtotime($view['wr_datetime'])) ?></span>
            <span class="tooltip-top" title="조회수"><i class="fa fa-eye fa-fw"></i> <?php echo number_format($view['wr_hit']) ?>회</span>
		    <span class="tooltip-top" title="댓글수"><i class="fa fa-comment-o fa-fw"></i> <?php echo number_format($view['wr_comment']) ?>건</span>
			</div>
</div>
<div class="panel-body">
            <?php if ($view['file']['count']) {
             $cnt = 0;
             for ($i=0; $i<count($view['file']); $i++) {
             if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view'])
             $cnt++;
		     }
		     }?>
          <?php if($cnt) { ?>
          <!-- 첨부파일 시작 { -->
		   <?php
           // 가변 파일
           for ($i=0; $i<count($view['file']); $i++) {
           if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
           ?>
		   <div class="clearfix well well-sm">
               <a href="<?php echo $view['file'][$i]['href'];  ?>" class="view_file_download">
                    <span class="tooltip-top" title="다운로드 파일"><i class="fa fa-floppy-o"></i> <?php echo $view['file'][$i]['source'] ?></span>
                </a>
					<span class="pull-right tooltip-top" title="자료정보">
					<i class="fa fa-download fa-fw"></i><?php echo $view['file'][$i]['download'] ?>회 (<?php echo $view['file'][$i]['size'] ?>)
					<i class="fa fa-calendar"></i> <?php echo $view['file'][$i]['datetime'] ?>
					</span>
		   </div>
           <?php } } ?>
           <!-- } 첨부파일 끝 -->
           <?php } ?>

		   <?php if (implode('', $view['link'])) {?>
           <!-- 관련링크 시작 { -->
	       <p><?php
           // 링크
           $cnt = 0;
           for ($i=1; $i<=count($view['link']); $i++) {
           if ($view['link'][$i]) {
               $cnt++;
               $link = cut_str($view['link'][$i], 70);
           ?></p>
           <a href="<?php echo $view['link_href'][$i] ?>" target="_blank">
           <span><i class="fa fa-link tooltip-top" title="링크주소"></i></span>
           <strong class="tooltip-top" title="연결<?php echo $view['link_hit'][$i] ?>회"><?php echo $link ?></strong>
           </a>
           <?php } } ?>
           <!-- } 관련링크 끝 -->
           <?php } ?>

    <section id="bo_v_atc" class="panel-body entry-content">
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
        <!-- 본문 내용 시작 { -->
        <div id="bo_v_con" style="min-height:200px;"><?php echo get_view_thumbnail($view['content']); ?></div>
        <?php//echo $view['rich_content']; // {이미지:0} 과 같은 코드를 사용할 경우 ?>
        <!-- } 본문 내용 끝 -->

        <?php if ($is_signature) { ?><p><?php echo $signature ?></p><?php } ?>

        <!-- 스크랩 추천 비추천 시작 { -->
        <?php if ($scrap_href || $good_href || $nogood_href) { ?>
        <div id="bo_v_act" class="text-center">
            <?php if ($scrap_href) { ?>
			<a href="<?php echo $scrap_href;  ?>" class="btn btn-default tooltip-top" title="이게시물을 보관합니다." onclick="win_scrap(this.href); return false;"><i class="fa fa-archive"></i> 스크랩</a>
			<?php } ?>
            <?php if ($good_href) { ?>
                <a href="<?php echo $good_href.'&amp;'.$qstr ?>" id="good_button" class="btn btn-default tooltip-top" title="이게시물을 추천합니다"><i class="fa fa-thumbs-o-up"> 추천</i> <strong><?php echo number_format($view['wr_good']) ?></strong></a>
                <b id="bo_v_act_good"></b>
            <?php } ?>
            <?php if ($nogood_href) { ?>
                <a href="<?php echo $nogood_href.'&amp;'.$qstr ?>" id="nogood_button" class="btn btn-default tooltip-top" title="이게시물을 비추천합니다"><i class="fa fa-thumbs-o-down"></i> 비추천<strong><?php echo number_format($view['wr_nogood']) ?></strong></a>
                <b id="bo_v_act_nogood"></b>
            <?php } ?>
        </div>
        <?php } else {
            if($board['bo_use_good'] || $board['bo_use_nogood']) {
        ?>
        <div id="bo_v_act" class="text-center">
            <?php if($board['bo_use_good']) { ?><span class="btn btn-default tooltip-top" title="이게시물을 추천합니다"><i class="fa fa-thumbs-o-up"></i> 추천<strong><?php echo number_format($view['wr_good']) ?></strong></span><?php } ?>
            <?php if($board['bo_use_nogood']) { ?><span class="btn btn-default tooltip-top" title="이게시물을 비추천합니다"><i class="fa fa-thumbs-o-down"></i> 비추천<strong><?php echo number_format($view['wr_nogood']) ?></strong></span><?php } ?>
        </div>
        <?php
            }
        }
        ?>
        <!-- } 스크랩 추천 비추천 끝 -->

   </div>
</div>
    <?php
    include_once(G5_SNS_PATH."/view.sns.skin.php");
    ?>
    </section>
	<hr>

    <?php
    // 코멘트 입출력
    include_once('./view_comment.php');
     ?>

    <!-- 링크 버튼 시작 { -->
    <div id="bo_v_bot">
        <?php echo $link_buttons ?>
    </div>
    <!-- } 링크 버튼 끝 -->

</article>
<!-- } 게시판 읽기 끝 -->

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