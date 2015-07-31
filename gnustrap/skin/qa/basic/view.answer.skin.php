<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>

<div class="clearfix"></div>
<hr>
<div class="panel panel-default">
  <div class="panel-heading clearfix"><i class="fa fa-mail-reply fa-fw fa-rotate-180"></i> <?php echo get_text($answer['qa_subject']); ?> <span class="pull-right"><i class="fa fa-calendar-o"></i> <?php echo $answer['qa_datetime']; ?></span></div>
  <div class="panel-body">
        <?php echo conv_content($answer['qa_content'], $answer['qa_html']); ?>
		<a href="<?php echo $rewrite_href; ?>" class="btn btn-default"><i class="fa fa-question-circle fa-fw"></i> 추가질문</a>
  </div>
</div>

    <div id="ans_add" class="pull-right">
        <?php if($answer_update_href) { ?>
        <a href="<?php echo $answer_update_href; ?>" class="btn btn-default"><i class="fa fa-exchange"></i> 답변수정</a>
        <?php } ?>
        <?php if($answer_delete_href) { ?>
        <a href="<?php echo $answer_delete_href; ?>" class="btn btn-default" onclick="del(this.href); return false;"><i class="fa fa-scissors"></i> 답변삭제</a>
        <?php } ?>
    </div>
	<div class="clearfix"></div>
	<hr>