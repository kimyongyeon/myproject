<?php
$sub_menu = "900300";
include_once("./_common.php");

$colspan = 3;

auth_check($auth[$sub_menu], "r");

$no_group = sql_fetch("select * from {$g5['sms5_book_group_table']} where bg_no=1");

$group = array();
$qry = sql_query("select * from {$g5['sms5_book_group_table']} where bg_no>1 order by bg_name");
while ($res = sql_fetch_array($qry)) array_push($group, $res);
?>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th scope="col">그룹명</th>
        <th scope="col">수신가능</th>
        <th scope="col">추가</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><a href="javascript:sms_obj.person(1)"><?php echo $no_group['bg_name']?></a></td>
        <td><?php echo number_format($no_group['bg_receipt'])?></td>
        <td><button type="button" class="btn btn-default" onclick="sms_obj.group_add(1, '<?php echo $no_group['bg_name']?>', '<?php echo number_format($no_group['bg_receipt'])?>')">추가</button></td>
    </tr>
    <?php
    $line = 1;
    for ($i=0; $i<count($group); $i++) {
        //$bg = 'bg'.($line++%2);
    ?>
    <tr>
        <td><a href="javascript:sms_obj.person(<?php echo $group[$i]['bg_no']?>)"><?php echo $group[$i]['bg_name']?></a></td>
        <td><?php echo number_format($group[$i]['bg_receipt'])?></td>
        <td><button type="button" class="btn btn-default" onclick="sms_obj.group_add(<?php echo $group[$i]['bg_no']?>, '<?php echo $group[$i]['bg_name']?>', '<?php echo number_format($group[$i]['bg_receipt'])?>')">추가</button></td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
</div>