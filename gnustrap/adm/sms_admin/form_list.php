<?php
$sub_menu = "900600";
include_once("./_common.php");

$page_size = 12;
$colspan = 2;

auth_check($auth[$sub_menu], "r");

$token = get_token();

$g5['title'] = "이모티콘 관리";

if ($page < 1) $page = 1;

if (is_numeric($fg_no))
    $sql_group = " and fg_no='$fg_no' ";
else
    $sql_group = "";

if ($st == 'all') {
    $sql_search = "and (fo_name like '%{$sv}%' or fo_content like '%{$sv}%')";
} else if ($st == 'name') {
    $sql_search = "and fo_name like '%{$sv}%'";
} else if ($st == 'content') {
    $sql_search = "and fo_content like '%{$sv}%'";
} else {
    $sql_search = '';
}

$total_res = sql_fetch("select count(*) as cnt from {$g5['sms5_form_table']} where 1 $sql_group $sql_search");
$total_count = $total_res['cnt'];

$total_page = (int)($total_count/$page_size) + ($total_count%$page_size==0 ? 0 : 1);
$page_start = $page_size * ( $page - 1 );

$vnum = $total_count - (($page-1) * $page_size);

$group = array();
$qry = sql_query("select * from {$g5['sms5_form_group_table']} order by fg_name");
while ($res = sql_fetch_array($qry)) array_push($group, $res);

$res = sql_fetch("select count(*) as cnt from {$g5['sms5_form_table']} where fg_no=0");
$no_count = $res['cnt'];

include_once(G5_ADMIN_PATH.'/admin.head.php');
?>

<script>

function book_all_checked(chk)
{
    if (chk) {
        jQuery('[name="fo_no[]"]').attr('checked', true);
    } else {
        jQuery('[name="fo_no[]"]').attr('checked', false);
    }
}

function book_del(fo_no)
{
    if (confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n그래도 삭제하시겠습니까?"))
        location.href = "./form_update.php?w=d&fo_no=" + fo_no + "&page=<?php echo $page?>&fg_no=<?php echo $fg_no?>&st=<?php echo $st?>&sv=<?php echo $sv?>";
}

function multi_update(sel)
{
    var fo_no = document.getElementsByName('fo_no');
    var ck_no = '';
    var count = 0;

    if (!sel.value) {
        sel.selectedIndex = 0;
        return;
    }

    for (i=0; i<fo_no.length; i++) {
        if (fo_no[i].checked==true) {
            count++;
            ck_no += fo_no[i].value + ',';
        }
    }

    if (!count) {
        alert('하나이상 선택해주세요.');
        sel.selectedIndex = 0;
        return;
    }

    if (sel.value == 'del') {
        if (!confirm("선택한 이모티콘를 삭제합니다.\n\n비회원만 삭제됩니다.\n\n회원을 삭제하려면 회원관리 메뉴를 이용해주세요.\n\n실행하시겠습니까?"))
        {
            sel.selectedIndex = 0;
            return;
        }
    } else if (!confirm("선택한 이모티콘를 " + sel.options[sel.selectedIndex].innerHTML + "\n\n실행하시겠습니까?")) {
        sel.selectedIndex = 0;
        return;
    }

    location.href = "./form_multi_update.php?w=" + sel.value + "&ck_no=" + ck_no;
}
</script>

<div class="well">
<strong>건수 : <?php echo number_format($total_count);?></strong>
</div>

    <form class="pull-left" style="margin-bottom:15px;">
    <label for="fg_no" class="sound_only">그룹명</label>
    <select name="fg_no" onchange="location.href='<?php echo $_SERVER['PHP_SELF']?>?fg_no='+this.value;" class="form-control">
        <option value="" <?php echo $fg_no?'':'selected'?>> 전체 </option>
        <option value="0" <?php echo $fg_no=='0'?'selected':''?>> 미분류 (<?php echo number_format($no_count)?>) </option>
        <?php for($i=0; $i<count($group); $i++) {?>
        <option value="<?php echo $group[$i]['fg_no']?>" <?php echo ($fg_no==$group[$i]['fg_no'])?'selected':''?>> <?php echo $group[$i]['fg_name']?> (<?php echo number_format($group[$i]['fg_count'])?>) </option>
        <?php } ?>
    </select>
    </form>

	<form name="search_form" method="get" action="<?php echo $_SERVER['PHP_SELF']?>" class="form-inline pull-right">
    <div class="form-group">
    <input type="hidden" name="fg_no" value="<?php echo $fg_no;?>">
    <label for="st" class="sound_only">검색대상</label>
    <select name="st" id="st" class="form-control">
        <option value="all"<?php echo get_selected('all', $st); ?>>제목 + 이모티콘</option>
        <option value="name"<?php echo get_selected('name', $st); ?>>제목</option>
        <option value="content"<?php echo get_selected('content', $st); ?>>이모티콘</option>
    </select>
    </div>
	<div class="input-group">
            <input type="text" name="sv" value="<?php echo $sv;?>" id="sv" required class="required form-control" placeholder="Search">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">검색</button>
            </span>
    </div>
    </form>
	
<div class="clearfix"></div>
<hr>

<form name="emoticonlist" id="emoticonlist" method="post" action="./form_multi_update.php" onsubmit="return emoticonlist_submit(this);" >
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">
<input type="hidden" name="sw" value="">
<input type="hidden" name="atype" value="del">
<ul id="sms5_preset" class="sms5_box">
    <?php
    $count = 1;
    $qry = sql_query("select * from {$g5['sms5_form_table']} where 1 $sql_group $sql_search order by fo_no desc limit $page_start, $page_size");
    for($i=0;$res = sql_fetch_array($qry);$i++)
    {
        $tmp = sql_fetch("select fg_name from {$g5['sms5_form_group_table']} where fg_no='{$res['fg_no']}'");
        if (!$tmp)
            $group_name = '미분류';
        else
            $group_name = $tmp['fg_name'];

        if ($i == 0) $li_i = 1;
        else {
            if ($li_i < 12) $li_i += 1;
            else if ($li_i == 12) $li_i = 1;
        }
    ?>
    <li class="li_<?php echo $li_i; ?> sms5_box">
        <span class="box_ico"></span>
        <div class="li_chk">
            <label for="fo_no_<?php echo $i; ?>" class="sound_only"><?php echo $group_name?>의 <?php echo cut_str($res['fo_name'],10)?></label>
            <input type="checkbox" name="fo_no[]" value="<?php echo $res['fo_no']?>" id="fo_no_<?php echo $i; ?>">
        </div>
        <div class="li_preview">
            <textarea readonly class="box_txt box_square form-control textarea"><?php echo $res['fo_content']?></textarea>
        </div>
        <div class="li_info">
            <strong class="label label-default"><span class="sound_only">그룹 </span><b><?php echo $group_name?></b></strong>
            <strong class="label label-info"><span class="sound_only">제목 </span><?php echo cut_str($res['fo_name'],10)?></strong>
        </div>
        <div class="li_date text-center" style="margin:5px 0;">
            <span class="sound_only">등록 </span><?php echo date('Y-m-d', strtotime($res['fo_datetime']))?>
        </div>
        <div class="li_cmd">
            <a href="./form_write.php?w=u&amp;fo_no=<?php echo $res['fo_no']?>&amp;page=<?php echo $page;?>&amp;fg_no=<?php echo $fg_no;?>&amp;st=<?php echo $st;?>&amp;sv=<?php echo $sv;?>" class="btn btn-default">수정</a>
            <a href="javascript:void(book_del('<?php echo $res['fo_no']?>'));" class="btn btn-default">삭제</a>
            <a href="./sms_write.php?fo_no=<?php echo $res['fo_no']?>" class="btn btn-default">보내기</a>
        </div>
    </li>
    <?php } ?>
</ul>

<button type="button" class="btn btn-default" for="book_all" data-toggle="buttons" style="margin-bottom:10px;">
<input type="checkbox" id="book_all" onclick="book_all_checked(this.checked);" data-toggle="checkbox">
<label for="book_all">전체선택</label>
</button>

<div class="pull-right" style="position:relative">
    <a href="./form_write.php?page=<?php echo $page?>&amp;fg_no=<?php echo $fg_no?>" class="btn btn-default">이모티콘 추가</a>
    <input type="submit" name="act_button" value="선택이동" onclick="document.pressed=this.value" class="btn btn-default">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-default">
</div>

</form>
<script>
function emoticonlist_submit(f){
    if (!is_checked("fo_no[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }
    if(document.pressed == "선택이동") {
        select_copy("move", f);
        return;
    }
    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }
    return true;
}
// 선택한 이모티콘 그룹 이동
function select_copy(sw, f) {
    if( !f ){
        var f = document.emoticonlist;
    }
    if (sw == "copy")
        str = "복사";
    else
        str = "이동";

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = "./emoticon_move.php";
    f.submit();
}
</script>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['PHP_SELF']."?fg_no=$fg_no&amp;st=$st&amp;sv=$sv&amp;page="); ?>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>