<?php
$sub_menu = "200900";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$token = get_token();

$sql_common = " from {$g5['poll_table']} ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "po_id";
    $sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="btn btn-default"><i class="fa fa-caret-square-o-down"></i> 전체목록</a>';

$g5['title'] = '투표관리';
include_once('./admin.head.php');

$colspan = 7;
?>

<div class="well">
    <?php echo $listall ?>
    투표수 <?php echo number_format($total_count) ?>개
</div>

<form name="fsearch" id="fsearch" class="form-inline" method="get">
    <div class="form-group">
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl" class="form-control">
        <option value="po_subject"<?php echo get_selected($_GET['sfl'], "po_subject"); ?>>제목</option>
    </select>
    </div>
	<div class="input-group">
              <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required form-control" placeholder="Search">
			  <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">검색</button>
              </span>
    </div>
</form>
<div class="clearfix"></div>
<hr>

<div>
    <a href="./poll_form.php" id="poll_add" class="btn btn-primary"><i class="fa fa-plus-square"></i> 투표 추가</a>
</div>
<div class="clearfix"></div>
<hr>

<form name="fpolllist" id="fpolllist" action="./poll_delete.php" method="post">
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
        <th scope="col">
            <label for="chkall" class="sound_only">현재 페이지 투표 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">번호</th>
        <th scope="col">제목</th>
        <th scope="col" style="width:80px;">투표권한</th>
        <th scope="col" style="width:70px;">투표수</th>
        <th scope="col">기타의견</th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $sql2 = " select sum(po_cnt1+po_cnt2+po_cnt3+po_cnt4+po_cnt5+po_cnt6+po_cnt7+po_cnt8+po_cnt9) as sum_po_cnt from {$g5['poll_table']} where po_id = '{$row['po_id']}' ";
        $row2 = sql_fetch($sql2);
        $po_etc = ($row['po_etc']) ? "사용" : "미사용";

        $s_mod = '<a href="./poll_form.php?'.$qstr.'&amp;w=u&amp;po_id='.$row['po_id'].'" class="btn btn-default">수정</a>';
    ?>

    <tr>
        <td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo cut_str(get_text($row['po_subject']),70) ?> 투표</label>
            <input type="checkbox" name="chk[]" value="<?php echo $row['po_id'] ?>" id="chk_<?php echo $i ?>">
        </td>
        <td class="td_num"><?php echo $row['po_id'] ?></td>
        <td><?php echo cut_str(get_text($row['po_subject']),70) ?></td>
        <td class="td_num"><?php echo $row['po_level'] ?></td>
        <td class="td_num"><?php echo $row2['sum_po_cnt'] ?></td>
        <td class="td_etc"><?php echo $po_etc ?></td>
        <td class="td_mngsmall"><?php echo $s_mod ?></td>
    </tr>

    <?php
    }

    if ($i==0)
        echo '<tr><td colspan="'.$colspan.'" class="text-center">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="center">
    <button type="submit" class="btn btn-default"><i class="fa fa-check-square-o"></i> 선택삭제</button>
</div>
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>

<script>
$(function() {
    $('#fpolllist').submit(function() {
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
            if (!is_checked("chk[]")) {
                alert("선택삭제 하실 항목을 하나 이상 선택하세요.");
                return false;
            }

            return true;
        } else {
            return false;
        }
    });
});
</script>

<?php
include_once ('./admin.tail.php');
?>