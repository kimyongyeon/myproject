<?php
$sub_menu = "100290";
include_once('./_common.php');

if ($is_admin != 'super')
    alert_close('최고관리자만 접근 가능합니다.');

$g5['title'] = '메뉴 추가';
include_once(G5_PATH.'/head.sub.php');

// 코드
if($new == 'new' || !$code) {
    $code = base_convert(substr($code,0, 2), 36, 10);
    $code += 36;
    $code = base_convert($code, 10, 36);
}
?>
<div class="container">
<hr>
<div id="menu_frm" class="new_win">
<div class="well">
        <strong><?php echo $g5['title']; ?></strong>
      </div>

    <form name="fmenuform" id="fmenuform">

    <div class="new_win_desc">
        <label for="me_type">대상선택</label>
        <select name="me_type" id="me_type" class="form-control">
            <option value="">직접입력</option>
            <option value="group">게시판그룹</option>
            <option value="board">게시판</option>
            <option value="content">내용관리</option>
        </select>
    </div>

    <div id="menu_result"></div>

    </form>

</div>
</div>
<script>
$(function() {
    $("#menu_result").load(
        "./menu_form_search.php"
    );

    $("#me_type").on("change", function() {
        var type = $(this).val();

        $("#menu_result").empty().load(
            "./menu_form_search.php",
            { type : type }
        );
    });

    $("#add_manual").live("click", function() {
        var me_name = $.trim($("#me_name").val());
        var me_link = $.trim($("#me_link").val());

        add_menu_list(me_name, me_link, "<?php echo $code; ?>");
    });

    $(".add_select").live("click", function() {
        var me_name = $.trim($(this).siblings("input[name='subject[]']").val());
        var me_link = $.trim($(this).siblings("input[name='link[]']").val());

        add_menu_list(me_name, me_link, "<?php echo $code; ?>");
    });
});

function add_menu_list(name, link, code)
{
    var $menulist = $("#menulist", opener.document);
    var ms = new Date().getTime();
    var sub_menu_class;
    <?php if($new == 'new') { ?>
    sub_menu_class = " class=\"td_category\"";
    <?php } else { ?>
    sub_menu_class = " class=\"td_category sub_menu_class\"";
    <?php } ?>

    var list = "<tr class=\"menu_list menu_group_<?php echo $code; ?>\">";
    list += "<td>";
    list += "<label for=\"me_name_"+ms+"\"  class=\"sound_only\">메뉴<strong class=\"sound_only\"> 필수</strong></label>";
    list += "<input type=\"hidden\" name=\"code[]\" value=\"<?php echo $code; ?>\">";
    list += "<input type=\"text\" name=\"me_name[]\" value=\""+name+"\" id=\"me_name_"+ms+"\" required class=\"required form-control\">";
    list += "</td>";
    list += "<td>";
    list += "<label for=\"me_link_"+ms+"\"  class=\"sound_only\">링크<strong class=\"sound_only\"> 필수</strong></label>";
    list += "<input type=\"text\" name=\"me_link[]\" value=\""+link+"\" id=\"me_link_"+ms+"\" required class=\"required form-control\">";
    list += "</td>";
    list += "<td>";
    list += "<label for=\"me_target_"+ms+"\"  class=\"sound_only\">새창</label>";
    list += "<select name=\"me_target[]\" id=\"me_target_"+ms+"\" class=\"form-control\">";
    list += "<option value=\"self\">사용안함</option>";
    list += "<option value=\"blank\">사용함</option>";
    list += "</select>";
    list += "</td>";
    list += "<td>";
    list += "<label for=\"me_order_"+ms+"\"  class=\"sound_only\">순서<strong class=\"sound_only\"> 필수</strong></label>";
    list += "<input type=\"text\" name=\"me_order[]\" value=\"0\" id=\"me_order_"+ms+"\" required class=\"required form-control\" size=\"5\">";
    list += "</td>";
    list += "<td>";
    list += "<label for=\"me_use_"+ms+"\"  class=\"sound_only\">PC사용</label>";
    list += "<select name=\"me_use[]\" id=\"me_use_"+ms+"\" class=\"form-control\">";
    list += "<option value=\"1\">사용함</option>";
    list += "<option value=\"0\">사용안함</option>";
    list += "</select>";
    list += "</td>";
    list += "<td>";
    list += "<label for=\"me_mobile_use_"+ms+"\"  class=\"sound_only\">모바일사용</label>";
    list += "<select name=\"me_mobile_use[]\" id=\"me_mobile_use_"+ms+"\" class=\"form-control\">";
    list += "<option value=\"1\">사용함</option>";
    list += "<option value=\"0\">사용안함</option>";
    list += "</select>";
    list += "</td>";
    list += "<td>";
    <?php if($new == 'new') { ?>
    list += "<button type=\"button\" class=\"btn_add_submenu btn btn-default\"><i class=\"fa fa-plus-square\"></i> 추가</button>";
    <?php } ?>
    list += "<button type=\"button\" class=\"btn_del_menu btn btn-default\"><i class=\"fa fa-minus-square\"></i> 삭제</button>";
    list += "</td>";
    list += "</tr>";

    var $menu_last = null;

    if(code)
        $menu_last = $menulist.find("tr.menu_group_"+code+":last");
    else
        $menu_last = $menulist.find("tr.menu_list:last");

	if($menu_last.size() > 0) {
        $menu_last.after(list);
    } else {
        if($menulist.find("#empty_menu_list").size() > 0)
            $menulist.find("#empty_menu_list").remove();

        $menulist.find("table tbody").append(list);
    }

    $menulist.find("tr.menu_list").each(function(index) {
        $(this).removeClass("")
            .addClass(""+(index % 2));
    });

    window.close();
}
</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>