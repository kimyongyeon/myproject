<?php
$sub_menu = "100290";
include_once('./_common.php');
if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');
$token = get_token();
// 메뉴테이블 생성
if( !isset($g5['menu_table']) ){
    die('<meta charset="utf-8">dbconfig.php 파일에 <strong>$g5[\'menu_table\'] = G5_TABLE_PREFIX.\'menu\';</strong> 를 추가해 주세요.');
}
if(!sql_query(" DESCRIBE {$g5['menu_table']} ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['menu_table']}` (
                  `me_id` int(11) NOT NULL AUTO_INCREMENT,
                  `me_code` varchar(255) NOT NULL DEFAULT '',
                  `me_name` varchar(255) NOT NULL DEFAULT '',
                  `me_link` varchar(255) NOT NULL DEFAULT '',
                  `me_target` varchar(255) NOT NULL DEFAULT '0',
                  `me_order` int(11) NOT NULL DEFAULT '0',
                  `me_use` tinyint(4) NOT NULL DEFAULT '0',
                  `me_mobile_use` tinyint(4) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`me_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ", true);
}
$sql = " select * from {$g5['menu_table']} where LENGTH(me_code) = '2' order by `me_order`  ";
$result = sql_query($sql);
$row = array();
for ($i=0; $rows = sql_fetch_array($result); $i++) {
    $row[$i] =$rows;
$sql = " select * from {$g5['menu_table']} where LENGTH(me_code) = '4' and SUBSTRING(me_code,1,2) = '{$rows[me_code]}' order by `me_order`  ";
$result2 = sql_query($sql);
$total_count2= mysql_num_rows($result2);
for ($c=0; $rows2 = sql_fetch_array($result2); $c++) {
$i++;
$row[$i] =$rows2;
}
}
$g5['title'] = "메뉴설정";
include_once('./admin.head.php');

$colspan = 7;
?>
<div class="alert alert-warning">
    <strong>주의!</strong> 메뉴설정 작업 후 반드시 <strong>확인</strong>을 누르셔야 저장됩니다.
</div>

<form name="fmenulist" id="fmenulist" method="post" action="./menu_list_update.php" onsubmit="return fmenulist_submit(this);">
<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="pull-left">
           <button type="button" onclick="return add_menu();" class="pull-right btn btn-primary"><i class="fa fa-plus-circle"></i> 메뉴추가</a></button>
</div>

<div class="clearfix"></div>
<hr>

<div id="menulist" class="table-responsive">
<table class="table table-striped table-bordered" style="max-width:100%; min-width:650px;">
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr role="row">
	<th class="sorting_asc">메뉴</th>
	<th class="sorting_asc">링크</th>
	<th class="sorting_asc">새창</th>
	<th class="sorting_asc">순서</th>
	<th class="sorting_asc">PC사용</th>
	<th class="sorting_asc">모바일사용</th>
	<th class="sorting_asc">관리</th>
	</tr>
    </thead>
    
    <tbody role="alert" aria-live="polite" aria-relevant="all">
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $bg = 'bg'.($i%2);
        $sub_menu_class = '';
        if(strlen($row['me_code']) == 4) {
            $sub_menu_class = ' sub_menu_class';
            $sub_menu_info = '<span class="sound_only">'.$row['me_name'].'의 서브</span>';
            $sub_menu_ico = '<span class="sub_menu_ico"></span>';
        }
        $search  = array('"', "'");
        $replace = array('&#34;', '&#39;');
        $me_name = str_replace($search, $replace, $row['me_name']);
    ?>

    <tr class="menu_list menu_group_<?php echo substr($row[$i]['me_code'], 0, 2); ?>">
        <td class="td_category<?php echo $sub_menu_class; ?>">
            <input type="hidden" name="code[]" value="<?php echo substr($row[$i]['me_code'], 0, 2) ?>">
            <label for="me_name_<?php echo $i; ?>" class="sound_only"><?php echo $sub_menu_info; ?> 메뉴<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="me_name[]" value="<?php echo $me_name; ?>" id="me_name_<?php echo $i; ?>" required class="required form-control full_input">
        </td>
        <td>
            <label for="me_link_<?php echo $i; ?>" class="sound_only">링크<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="me_link[]" value="<?php echo $row[$i]['me_link'] ?>" id="me_link_<?php echo $i; ?>" required class="required form-control">
        </td>
        <td>
            <label for="me_target_<?php echo $i; ?>" class="sound_only">새창</label>
            <select name="me_target[]" id="me_target_<?php echo $i; ?>" class="form-control">
                <option value="self"<?php echo get_selected($row[$i]['me_target'], 'self', true); ?>>안함</option>
                <option value="blank"<?php echo get_selected($row[$i]['me_target'], 'blank', true); ?>>사용</option>
            </select>
        </td>
        <td>
            <label for="me_order_<?php echo $i; ?>" class="sound_only">순서</label>
            <input type="text" name="me_order[]" value="<?php echo $row[$i]['me_order'] ?>" id="me_order_<?php echo $i; ?>" class="form-control" size="5">
        </td>
        <td>
            <label for="me_use_<?php echo $i; ?>" class="sound_only">PC사용</label>
            <select name="me_use[]" id="me_use_<?php echo $i; ?>" class="form-control">
                <option value="1"<?php echo get_selected($row[$i]['me_use'], '1', true); ?>>사용</option>
                <option value="0"<?php echo get_selected($row[$i]['me_use'], '0', true); ?>>안함</option>
            </select>
        </td>
        <td>
            <label for="me_mobile_use_<?php echo $i; ?>" class="sound_only">모바일사용</label>
            <select name="me_mobile_use[]" id="me_mobile_use_<?php echo $i; ?>" class="form-control">
                <option value="1"<?php echo get_selected($row[$i]['me_mobile_use'], '1', true); ?>>사용</option>
                <option value="0"<?php echo get_selected($row[$i]['me_mobile_use'], '0', true); ?>>안함</option>
            </select>
        </td>
        <td>
            <?php if(strlen($row[$i]['me_code']) == 2) { ?>
            <button type="button" class="btn_add_submenu btn btn-default"><i class="fa fa-plus-square"></i> 추가</button>
            <?php } ?>
            <button type="button" class="btn_del_menu btn btn-default"><i class="fa fa-minus-square"></i> 삭제</button>
        </td>
    </tr>
    <?php
    }

    if ($i==0)
        echo '<tr id="empty_menu_list"><td colspan="'.$colspan.'" class="text-center">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="center">
    <input type="submit" name="act_button" value="확인" class="btn btn-primary">
</div>

</form>

<script>
$(function() {
    $(".btn_add_submenu").live("click", function() {
        var code = $(this).closest("tr").find("input[name='code[]']").val().substr(0, 2);
        add_submenu(code);
    });

    $(".btn_del_menu").live("click", function() {
        if(!confirm("메뉴를 삭제하시겠습니까?"))
            return false;

        var $tr = $(this).closest("tr");
        if($tr.find("td.sub_menu_class").size() > 0) {
            $tr.remove();
        } else {
            var code = $(this).closest("tr").find("input[name='code[]']").val().substr(0, 2);
            $("tr.menu_group_"+code).remove();
        }

        if($("#menulist tr.menu_list").size() < 1) {
            var list = "<tr id=\"empty_menu_list\"><td colspan=\"<?php echo $colspan; ?>\" class=\"text-center\">자료가 없습니다.</td></tr>\n";
            $("#menulist table tbody").append(list);
        } else {
            $("#menulist tr.menu_list").each(function(index) {
                $(this).removeClass("bg0 bg1")
                    .addClass("bg");
            });
        }
    });
});

function add_menu()
{
    var max_code = base_convert(0, 10, 36);
    $("#menulist tr.menu_list").each(function() {
        var me_code = $(this).find("input[name='code[]']").val().substr(0, 2);
        if(max_code < me_code)
            max_code = me_code;
    });

    var url = "./menu_form.php?code="+max_code+"&new=new";
    window.open(url, "add_menu", "left=100,top=100,width=550,height=650,scrollbars=yes,resizable=yes");
    return false;
}

function add_submenu(code)
{
    var url = "./menu_form.php?code="+code;
    window.open(url, "add_menu", "left=100,top=100,width=550,height=650,scrollbars=yes,resizable=yes");
    return false;
}

function base_convert(number, frombase, tobase) {
  //  discuss at: http://phpjs.org/functions/base_convert/
  // original by: Philippe Baumann
  // improved by: Rafał Kukawski (http://blog.kukawski.pl)
  //   example 1: base_convert('A37334', 16, 2);
  //   returns 1: '101000110111001100110100'

  return parseInt(number + '', frombase | 0)
    .toString(tobase | 0);
}

function fmenulist_submit(f)
{
    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
