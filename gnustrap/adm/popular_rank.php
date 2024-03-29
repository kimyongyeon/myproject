<?php
$sub_menu = "300400";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

if (empty($fr_date)) $fr_date = G5_TIME_YMD;
if (empty($to_date)) $to_date = G5_TIME_YMD;

$qstr = "fr_date={$fr_date}{&amp;to_date}={$to_date}";

$sql_common = " from {$g5['popular_table']} a ";
$sql_search = " where trim(pp_word) <> '' and pp_date between '{$fr_date}' and '{$to_date}' ";
$sql_group = " group by pp_word ";
$sql_order = " order by cnt desc ";

$sql = " select pp_word {$sql_common} {$sql_search} {$sql_group} ";
$result = sql_query($sql);
$total_count = mysql_num_rows($result);

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select pp_word, count(*) as cnt {$sql_common} {$sql_search} {$sql_group} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="btn btn-default"><i class="fa fa-caret-square-o-down fa-fw"></i>전체목록</a>';

$g5['title'] = '인기검색어순위';
include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$colspan = 3;
?>

<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>


<div class="well">
    <?php echo $listall ?>
    건수 <?php echo number_format($total_count) ?>개
</div>

<form name="fsearch" id="fsearch" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" name="fr_date" value="<?php echo $fr_date ?>" id="fr_date" class="form-control" size="15" maxlength="10">
                        <span class="input-group-btn">
                           <button type="button" class="btn btn-default">
                              <i class="fa fa-calendar"></i> 시작일
                           </button>
                        </span>
                    </div>
                    <div class="input-group">
                        <input type="text" name="to_date" value="<?php echo $to_date ?>" id="to_date" class="form-control" size="15" maxlength="10">
                        <span class="input-group-btn">
                           <button type="button" class="btn btn-default">
                              <i class="fa fa-calendar"></i> 종료일
                           </button>
                        </span>
                     </div>
<input type="submit" class="btn btn-primary" value="검색">
</form>
<div class="clearfix"></div>
<hr>

<form name="fpopularrank" id="fpopularrank" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="table-responsive">
    <table class="table table-bordered table-striped">
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">순위</th>
        <th scope="col">검색어</th>
        <th scope="col">검색회수</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {

        $word = get_text($row['pp_word']);
        $rank = ($i + 1 + ($rows * ($page - 1)));

    ?>

    <tr>
        <td><?php echo $rank ?></td>
        <td><?php echo $word ?></td>
        <td><?php echo $row['cnt'] ?></td>
    </tr>

    <?php
    }

    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="text-center">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

</form>

<?php
echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page=");
?>

<?php
include_once('./admin.tail.php');
?>
