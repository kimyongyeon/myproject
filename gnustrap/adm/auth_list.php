<?php
$sub_menu = "100200";
include_once('./_common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$token = get_token();

$sql_common = " from {$g5['auth_table']} a left join {$g5['member_table']} b on (a.mb_id=b.mb_id) ";

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
    $sst  = "a.mb_id, au_menu";
    $sod = "";
}
$sql_order = " order by $sst $sod ";

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

$g5['title'] = "관리권한설정";
include_once('./admin.head.php');

$colspan = 5;
?>
<div class="well">
        <strong>
		<?php echo $listall ?> 설정된 관리권한 <?php echo number_format($total_count) ?>건</strong>
      </div>

<form class="navbar-form navbar-left" role="search" name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
	    <label for="stx" class="sound_only">회원아이디<strong class="sound_only"> 필수</strong></label>
	    <input type="hidden" name="sfl" value="a.mb_id" id="sfl" >
<div class="input-group">
        <input name="stx" value="<?php echo $stx ?>" id="stx" required class="form-control required" placeholder="Search">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">검색</button>
            </span>
</div>

</form>

<form name="fauthlist" id="fauthlist" method="post" action="./auth_list_delete.php" onsubmit="return fauthlist_submit(this);">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">


<table class="table table-striped table-bordered">
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr role="row">
	<th class="sorting_asc" style="width:30px;">
	<label for="chkall" class="sound_only">현재 페이지 회원 전체</label>
	<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
	</th>
	<th class="sorting_asc" style="width:100px;">
	<?php echo subject_sort_link('a.mb_id') ?><span class="hidden-xs">회원</span>ID</th>
	<th class="sorting_asc" style="width:100px;">
	<?php echo subject_sort_link('mb_nick') ?>닉네임</th>
	<th class="sorting_asc" style="width:200px;">메뉴</th>
	<th class="sorting_asc">권한</th>
	</tr>
    </thead>
    
    <tbody role="alert" aria-live="polite" aria-relevant="all">
	<?php
    $count = 0;
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $is_continue = false;
        // 회원아이디가 없는 메뉴는 삭제함
        if($row['mb_id'] == '' && $row['mb_nick'] == '') {
            sql_query(" delete from {$g5['auth_table']} where au_menu = '{$row['au_menu']}' ");
            $is_continue = true;
        }

        // 메뉴번호가 바뀌는 경우에 현재 없는 저장된 메뉴는 삭제함
        if (!isset($auth_menu[$row['au_menu']]))
        {
            sql_query(" delete from {$g5['auth_table']} where au_menu = '{$row['au_menu']}' ");
            $is_continue = true;
        }

        if($is_continue)
            continue;

        $mb_nick = get_sideview($row['mb_id'], $row['mb_nick'], $row['mb_email'], $row['mb_homepage']);

        $bg = 'bg'.($i%2);
    ?>
	<tr>
        <td class=" sorting_1">
            <input type="hidden" name="au_menu[<?php echo $i ?>]" value="<?php echo $row['au_menu'] ?>">
            <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['mb_nick'] ?>님 권한</label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
        <td class="center">
		    <a href="?sfl=a.mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo $row['mb_id'] ?></a>
		</td>
        <td class="center">
		    <?php echo $mb_nick ?>
		</td>
        <td class="center">
		    <span class="hidden-xs"><?php echo $row['au_menu'] ?></span>
            <?php echo $auth_menu[$row['au_menu']] ?>
		</td>
        <td class="td_auth"><?php echo $row['au_auth'] ?></td>
    </tr>
	<?php
        $count++;
    }

    if ($count == 0)
        echo '<tr><td colspan="'.$colspan.'" class="text-center">자료가 없습니다.</td></tr>';
    ?>
		</tbody></table>
	
<div class="center">
    <button type="submit" name="act_button" onclick="document.pressed=this.value" class="btn btn-default"><i class="fa fa-cut"></i> 선택삭제</button>
</div>

<hr>

<?php
//if (isset($stx))
//    echo '<script>document.fsearch.sfl.value = "'.$sfl.'";</script>'."\n";

if (strstr($sfl, 'mb_id'))
    $mb_id = $stx;
else
    $mb_id = '';
?>
</form>

<?php
$pagelist = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['PHP_SELF'].'?'.$qstr.'&amp;page=');
echo $pagelist;
?>

<form name="fauthlist2" id="fauthlist2" action="./auth_update.php" method="post" autocomplete="off">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="well">
        <strong>관리권한 추가</strong>
</div>

	  <div class="well well-sm">
        다음 양식에서 회원에게 관리권한을 부여하실 수 있습니다.<br>
        권한 <strong>r</strong>은 읽기권한, <strong>w</strong>는 쓰기권한, <strong>d</strong>는 삭제권한입니다.
      </div>

        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
		<div class="row">
		        <div class="form-group col-md-4">
                    <label for="mb_id">회원아이디<strong class="sound_only">필수</strong></label>
                    <strong id="msg_mb_id" class="msg_sound_only"></strong>
                    <input type="text" name="mb_id" value="<?php echo $mb_id ?>" id="mb_id" required class="required form-control">
                </div>
		        <div class="form-group col-md-4">
                    <label for="au_menu">접근가능메뉴<strong class="sound_only">필수</strong></label>
                    <select id="au_menu" name="au_menu" required class="required form-control">
                    <option value=''>선택하세요</option>
                    <?php
                    foreach($auth_menu as $key=>$value)
                    {
                        if (!(substr($key, -3) == '000' || $key == '-' || !$key))
                            echo '<option value="'.$key.'">'.$key.' '.$value.'</option>';
                    }
                    ?>
                    </select> 
				</div>
		        <div class="form-group col-md-4">
                    <label>권한지정</label><br />
				<div class="btn-group" data-toggle="buttons" style="width: 100%;">
				  <label class="btn btn-primary popover-top active" title="해당 회원에게 읽기권한을 부여합니다." data-content="관리자모드에 해당 접근가능메뉴에 대해 볼수 있습니다.">
 				   <input type="checkbox" name="r" value="r" id="r" checked> 읽기권한
 				 </label>
 				 <label class="btn btn-primary popover-top" title="해당 회원에게 쓰기권한을 부여합니다." data-content="관리자모드에 해당 접근가능메뉴에 수정 및 추가 할수 있습니다.">
 				   <input type="checkbox" name="w" value="w" id="w"> 쓰기권한
 				 </label>
 				 <label class="btn btn-primary popover-top" title="해당 회원에게 삭제권한을 부여합니다." data-content="관리자모드에 해당 접근가능메뉴에 대해 필요없는 자료 및 메뉴,게시판등등 삭제가 가능합니다.">
 				   <input type="checkbox"> <input type="checkbox" name="d" value="d" id="d"> 삭제권한
 				 </label>
				</div>
        </div>
				
        </tbody>
        </table>

<div class="center">
    <button type="submit" class="btn btn-default"><i class="fa fa-plus-circle fa-fw"></i>추가</button>
</div>
</form>
<script>
function fauthlist_submit(f)
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
