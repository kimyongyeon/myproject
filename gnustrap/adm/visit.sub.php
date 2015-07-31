<?php
if (!defined('_GNUBOARD_')) exit;

include_once(G5_LIB_PATH.'/visit.lib.php');
include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if (empty($fr_date)) $fr_date = G5_TIME_YMD;
if (empty($to_date)) $to_date = G5_TIME_YMD;

$qstr = "fr_date=".$fr_date."&amp;to_date=".$to_date;
$query_string = $qstr ? '?'.$qstr : '';
?>

<form name="fvisit" id="fvisit" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" name="fr_date" value="<?php echo $fr_date ?>" id="fr_date" class="form-control" size="15" maxlength="10">
                        <span class="input-group-btn">
                           <button type="button" class="btn btn-default">
                              <i class="fa fa-calendar" name="fr_date" value="<?php echo $fr_date ?>" id="fr_date"></i> 시작일
                           </button>
                        </span>
                    </div>
                    <div class="input-group">
                        <input type="text" name="to_date" value="<?php echo $to_date ?>" id="to_date" class="form-control" size="15" maxlength="10">
                        <span class="input-group-btn">
                           <button type="button" class="btn btn-default">
                              <i class="fa fa-calendar" name="to_date" value="<?php echo $to_date ?>" id="to_date"></i> 종료일
                           </button>
                        </span>
                     </div>

<input type="submit" class="btn btn-primary" value="검색">
</form>
<div class="clearfix"></div>
<hr>

<nav class="navbar navbar-default navbar-static-top" role="navigation" style="z-index: 0;">
<div class="navbar-header">
			<span class="navbar-brand visible-xs">QUICK</span>
<button type="button" class="navbar-toggle2 collapsed visible-xs animated flip" data-toggle="collapse" data-target=".navbar-ex8-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
</button>
        </div>
<div class="subbar-collapse navbar-ex8-collapse collapse" style="height: 1px;">
<ul class="nav navbar-nav">
    <li><a href="./visit_list.php<?php echo $query_string ?>">접속자</a></li>
    <li><a href="./visit_domain.php<?php echo $query_string ?>">도메인</a></li>
    <li><a href="./visit_browser.php<?php echo $query_string ?>">브라우저</a></li>
    <li><a href="./visit_os.php<?php echo $query_string ?>">운영체제</a></li>
    <li><a href="./visit_hour.php<?php echo $query_string ?>">시간</a></li>
    <li><a href="./visit_week.php<?php echo $query_string ?>">요일</a></li>
    <li><a href="./visit_date.php<?php echo $query_string ?>">일</a></li>
    <li><a href="./visit_month.php<?php echo $query_string ?>">월</a></li>
    <li><a href="./visit_year.php<?php echo $query_string ?>">년</a></li>
</ul>
</div></nav>

<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});

function fvisit_submit(act)
{
    var f = document.fvisit;
    f.action = act;
    f.submit();
}
</script>
