<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$faq_skin_url.'/style.css">', 0);
?>

<!-- FAQ 시작 { -->
<?php
if ($himg_src)
    echo '<div id="faq_himg" class="faq_img"><img src="'.$himg_src.'" alt=""></div>';

// 상단 HTML
echo '<div id="faq_hhtml">'.conv_content($fm['fm_head_html'], 1).'</div>';
?>

<?php
if( count($faq_master_list) ){
?>
<div class="panel panel-default">
<div class="panel-heading">
		<h5><i class="fa fa-bar-chart-o fa-fw"></i> 자주하시는질문</h5>
        <?php
        if ($admin_href)
        echo '<a href="'.$admin_href.'" class="btn btn-default pull-right" style="margin-top:-36px;"><i class="fa fa-wrench fa-fw"></i> FAQ 수정</a>';
        ?>
</div>
<div id="bo_list" class="panel-body table table-bordered media-table">
<nav id="bo_cate">
        <ul class="breadcrumb" id="bo_cate_ul">
        <?php
        foreach( $faq_master_list as $v ){
            $category_msg = '';
            $category_option = '';
            if($v['fm_id'] == $fm_id){ // 현재 선택된 카테고리라면
                $category_option = ' id="bo_cate_on"';
                $category_msg = '<span class="sound_only">열린 분류 </span>';
            }
        ?>
        <li><a href="<?php echo $category_href;?>?fm_id=<?php echo $v['fm_id'];?>" <?php echo $category_option;?> ><?php echo $category_msg.$v['fm_subject'];?></a></li>
        <?php
        }
        ?>
    </ul>
</nav>
<?php } ?>

<div id="faq_wrap" class="faq_<?php echo $fm_id; ?>">
    <?php // FAQ 내용
    if( count($faq_list) ){
    ?>
  <?php foreach($faq_list as $key=>$v){
   if(empty($v)) continue;
   //아코디언 사용을 위한 숫자랜덤뽑기
   $count = rand();?>
   <div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$count?>">
          <?php echo conv_content($v['fa_content'], 1); ?>
        </a>
      </h4>
    </div>
    <div id="collapse<?=$count?>" class="panel-collapse collapse">
      <div class="panel-body">
        <?php echo conv_content($v['fa_subject'], 1); ?>
      </div>
    </div>
  </div>
</div>
  <?php }?>
    <?php

    } else {
        if($stx){
            echo '<p class="text-center">검색된 게시물이 없습니다.</p>';
        } else {
            echo '<div class="text-center">등록된 FAQ가 없습니다.<br /><br />';
            if($is_admin)
                echo '<a href="'.G5_ADMIN_URL.'/faqmasterlist.php" class="btn btn-default"><i class="fa fa-wrench fa-fw"></i> FAQ 관리</a>';
            echo '</div>';
        }
    }
    ?>
</div>
</div>
</div>

<?php echo get_paging($page_rows, $page, $total_page, $_SERVER['PHP_SELF'].'?'.$qstr.'&amp;page='); ?>

<?php
// 하단 HTML
echo '<div id="faq_thtml">'.conv_content($fm['fm_tail_html'], 1).'</div>';

if ($timg_src)
    echo '<div id="faq_timg" class="faq_img"><img src="'.$timg_src.'" alt=""></div>';
?>
<fieldset id="faq_sch" class="well text-center">
    <form name="faq_search_form" method="get" class="form-inline">
    <input type="hidden" name="fm_id" value="<?php echo $fm_id;?>">
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
	<div class="input-group" style="margin-top:5px;">
    <input type="text" name="stx" value="<?php echo $stx;?>" required id="stx" class="form-control required" size="15" maxlength="15" placeholder="검색어">
    <span class="input-group-btn">
    <input type="submit" value="검색" class="btn btn-primary">
    </span>
	</div>
    </form>
</fieldset>
<!-- } FAQ 끝 -->

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>
<script>
$(function() {
    $(".closer_btn").on("click", function() {
        $(this).closest(".con_inner").slideToggle();
    });
});

function faq_open(el)
{
    var $con = $(el).closest("li").find(".con_inner");

    if($con.is(":visible")) {
        $con.slideUp();
    } else {
        $("#faq_con .con_inner:visible").css("display", "none");

        $con.slideDown(
            function() {
                // 이미지 리사이즈
                $con.viewimageresize2();
            }
        );
    }

    return false;
}
</script>