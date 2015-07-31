<?php
$sub_menu = '300700';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = 'FAQ 상세관리';
if ($fm_subject) $g5['title'] .= ' : '.$fm_subject;
include_once (G5_ADMIN_PATH.'/admin.head.php');

$sql = " select * from {$g5['faq_master_table']} where fm_id = '$fm_id' ";
$fm = sql_fetch($sql);

$sql_common = " from {$g5['faq_table']} where fm_id = '$fm_id' ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row[cnt];

$sql = "select * $sql_common order by fa_order , fa_id ";
$result = sql_query($sql);
?>
<div class="well">
    등록된 FAQ 상세내용 <?php echo $total_count; ?>건
</div>

<div class="well well-sm">
        FAQ는 무제한으로 등록할 수 있습니다<br />
        <a href="./faqform.php?fm_id=<?php echo $fm['fm_id']; ?>" class="btn btn-default"><i class="fa fa-plus-circle"></i> FAQ 상세내용 추가</a>를 눌러 자주하는 질문과 답변을 입력합니다.
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">번호</th>
        <th scope="col">제목</th>
        <th scope="col">순서</th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $row1 = sql_fetch(" select COUNT(*) as cnt from {$g5['faq_table']} where fm_id = '{$row['fm_id']}' ");
        $cnt = $row1[cnt];

        $s_mod = icon("수정", "");
        $s_del = icon("삭제", "");

        $num = $i + 1;

        //$bg = 'bg'.($i%2);
    ?>

    <tr>
        <td><?php echo $num; ?></td>
        <td><?php echo stripslashes($row['fa_subject']); ?></td>
        <td><?php echo $row['fa_order']; ?></td>
        <td>
            <a href="./faqform.php?w=u&amp;fm_id=<?php echo $row['fm_id']; ?>&amp;fa_id=<?php echo $row['fa_id']; ?>" class="btn btn-default"><span class="sound_only"><?php echo stripslashes($row['fa_subject']); ?> </span><i class="fa fa-file-text-o fa-fw"></i>수정</a>
            <a href="javascript:del('./faqformupdate.php?w=d&amp;fm_id=<?php echo $row['fm_id']; ?>&amp;fa_id=<?php echo $row['fa_id']; ?>');" class="btn btn-default"><span class="sound_only"><?php echo stripslashes($row['fa_subject']); ?> </span><i class="fa fa-cut fa-fw"></i> 삭제</a>
        </td>
    </tr>

    <?php
    }

    if ($i == 0) {
        echo '<tr><td colspan="4" class="text-center">자료가 없습니다.</td></tr>';
    }
    ?>
    </tbody>
    </table>

</div>

<div class="text-center">
    <a href="./faqmasterlist.php" class="btn btn-default">FAQ 관리</a>
</div>


<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
