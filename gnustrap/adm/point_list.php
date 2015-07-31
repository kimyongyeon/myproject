<?php
$sub_menu = "200200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$token = get_token();

$sql_common = " from {$g5['point_table']} ";

$sql_search = " where (1) ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_id' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
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

$mb = array();
if ($sfl == 'mb_id' && $stx)
    $mb = get_member($stx);

$g5['title'] = '포인트관리';
include_once ('./admin.head.php');

$colspan = 9;

$po_expire_term = '';
if($config['cf_point_term'] > 0) {
    $po_expire_term = $config['cf_point_term'];
}

if (strstr($sfl, "mb_id"))
    $mb_id = $stx;
else
    $mb_id = "";
?>

<div class="well">
<?php echo $listall ?>
    전체 <?php echo number_format($total_count) ?> 건
    <?php
    if (isset($mb['mb_id']) && $mb['mb_id']) {
        echo '&nbsp;(' . $mb['mb_id'] .' 님 포인트 합계 : ' . number_format($mb['mb_point']) . '점)';
    } else {
        $row2 = sql_fetch(" select sum(po_point) as sum_point from {$g5['point_table']} ");
        echo '&nbsp;(전체 합계 '.number_format($row2['sum_point']).'점)';
    }
    ?>
</div>

<form name="fsearch" id="fsearch" class="form-inline" method="get">
    <div class="form-group">
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl" class="form-control">
    <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>
    <option value="po_content"<?php echo get_selected($_GET['sfl'], "po_content"); ?>>내용</option>
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

<form name="fpointlist" id="fpointlist" method="post" action="./point_list_delete.php" onsubmit="return fpointlist_submit(this);">
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
            <label for="chkall" class="sound_only">포인트 내역 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col"><?php echo subject_sort_link('mb_id') ?>회원아이디</a></th>
        <th scope="col">이름</th>
        <th scope="col">닉네임</th>
        <th scope="col"><?php echo subject_sort_link('po_content') ?>포인트 내용</a></th>
        <th scope="col" style="width:70px;"><?php echo subject_sort_link('po_point') ?>포인트</a></th>
        <th scope="col"><?php echo subject_sort_link('po_datetime') ?>일시</a></th>
        <th scope="col" style="width:100px;">만료일</th>
        <th scope="col" style="width:80px;">포인트합</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        if ($i==0 || ($row2['mb_id'] != $row['mb_id'])) {
            $sql2 = " select mb_id, mb_name, mb_nick, mb_email, mb_homepage, mb_point from {$g5['member_table']} where mb_id = '{$row['mb_id']}' ";
            $row2 = sql_fetch($sql2);
        }

        $mb_nick = get_sideview($row['mb_id'], $row2['mb_nick'], $row2['mb_email'], $row2['mb_homepage']);

        $link1 = $link2 = '';
        if (!preg_match("/^\@/", $row['po_rel_table']) && $row['po_rel_table']) {
            $link1 = '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$row['po_rel_table'].'&amp;wr_id='.$row['po_rel_id'].'" target="_blank">';
            $link2 = '</a>';
        }

        $expr = '';
        if($row['po_expired'] == 1)
            $expr = ' txt_expired';

        //$bg = 'bg'.($i%2);
    ?>

    <tr>
        <td>
            <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
            <input type="hidden" name="po_id[<?php echo $i ?>]" value="<?php echo $row['po_id'] ?>" id="po_id_<?php echo $i ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['po_content'] ?> 내역</label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
        <td><a href="?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo $row['mb_id'] ?></a></td>
        <td><?php echo get_text($row2['mb_name']); ?></td>
        <td><div><?php echo $mb_nick ?></div></td>
        <td><?php echo $link1 ?><?php echo $row['po_content'] ?><?php echo $link2 ?></td>
        <td><?php echo number_format($row['po_point']) ?></td>
        <td><?php echo $row['po_datetime'] ?></td>
        <td class="td_date<?php echo $expr; ?>">
            <?php if ($row['po_expired'] == 1) { ?>
            만료<?php echo substr(str_replace('-', '', $row['po_expire_date']), 2); ?>
            <?php } else echo $row['po_expire_date'] == '9999-12-31' ? '&nbsp;' : $row['po_expire_date']; ?>
        </td>
        <td><?php echo number_format($row['po_mb_point']) ?></td>
    </tr>

    <?php
    }

    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="text-center">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div>
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-default">
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>


<section id="point_mng">
    <div class="well">개별회원 포인트 증감 설정</div>

    <form name="fpointlist2" method="post" id="fpointlist2" action="./point_update.php" autocomplete="off">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="<?php echo $token ?>">

        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
		<div class="row">
		        <div class="form-group col-md-3">
                    <label for="mb_id">회원아이디<strong class="sound_only">필수</strong></label>
					<input type="text" name="mb_id" value="<?php echo $mb_id ?>" id="mb_id" class="form-control required" required placeholder="admin">
                </div>
		        <div class="form-group col-md-3">
				    <label for="po_content">포인트 내용<strong class="sound_only">필수</strong></label>
					<input type="text" name="po_content" id="po_content" required class="form-control required" size="80" placeholder="이벤트">
				</div>
		        <div class="form-group col-md-3">
				    <label for="po_point">포인트<strong class="sound_only">필수</strong></label>
                    <input type="text" name="po_point" id="po_point" required class="form-control required" placeholder="1000">
				</div>
				<?php if($config['cf_point_term'] > 0) { ?>
		        <div class="form-group col-md-3">
				    <label for="po_expire_term">포인트 유효기간</label>
					<input type="text" name="po_expire_term" value="<?php echo $po_expire_term; ?>" id="po_expire_term" class="form-control" size="5">
				</div>
                <?php } ?>
		</div>
        </tbody>
        </table>

    <div class="center">
        <input type="submit" value="확인" class="btn btn-default">
    </div>

    </form>

</section>

<script>
function fpointlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
