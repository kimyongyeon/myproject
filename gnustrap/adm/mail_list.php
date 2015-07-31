<?php
$sub_menu = '200300';
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['mail_table']} ";

// 테이블의 전체 레코드수만 얻음
$sql = " select COUNT(*) as cnt {$sql_common} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$page = 1;

$sql = " select * {$sql_common} order by ma_id desc ";
$result = sql_query($sql);

$g5['title'] = '회원메일발송';
include_once('./admin.head.php');

$colspan = 7;
?>
<div class="well">
        <strong>테스트</strong>는 등록된 최고관리자의 이메일로 테스트 메일을 발송합니다.<br />
        <strong>
		현재 등록된 메일은 총 <?php echo $total_count ?>건입니다.</strong>
      </div>

<div>
    <a href="./mail_form.php" id="mail_add" class="btn btn-primary"><i class="fa fa-plus-circle"></i> 메일내용추가</a>
</div>
<hr>

<form name="fmaillist" id="fmaillist" action="./mail_delete.php" method="post">
<div class="table-responsive">
    <table class="table table-striped table-bordered">
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col"><input type="checkbox" name="chkall" value="1" id="chkall" title="현재 페이지 목록 전체선택" onclick="check_all(this.form)"></th>
        <th scope="col">번호</th>
        <th scope="col">제목</th>
        <th scope="col">작성일시</th>
        <th scope="col">테스트</th>
        <th scope="col">보내기</th>
        <th scope="col">미리보기</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=mysql_fetch_array($result); $i++) {
        $s_vie = '<a href="./mail_preview.php?ma_id='.$row['ma_id'].'" target="_blank" class="btn btn-default"><i class="fa fa-eye fa-fw"></i> 미리보기</a>';

        $num = number_format($total_count - ($page - 1) * $config['cf_page_rows'] - $i);

        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['ma_subject']; ?> 메일</label>
            <input type="checkbox" id="chk_<?php echo $i ?>" name="chk[]" value="<?php echo $row['ma_id'] ?>">
        </td>
        <td class="td_num"><?php echo $num ?></td>
        <td><a href="./mail_form.php?w=u&amp;ma_id=<?php echo $row['ma_id'] ?>"><?php echo $row['ma_subject'] ?></a></td>
        <td class="td_datetime"><?php echo $row['ma_time'] ?></td>
        <td class="td_test"><a href="./mail_test.php?ma_id=<?php echo $row['ma_id'] ?>" class="btn btn-default"><i class="fa fa-smile-o"></i> 테스트</a></td>
        <td class="td_send"><a href="./mail_select_form.php?ma_id=<?php echo $row['ma_id'] ?>" class="btn btn-default"><i class="fa fa-send-o"></i> 보내기</a></td>
        <td class="td_mngsmall"><?php echo $s_vie ?></td>
    </tr>

    <?php
    }
    if (!$i)
        echo "<tr><td colspan=\"".$colspan."\" class=\"text-center\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

<div class="center">
    <button type="submit" class="btn btn-default"><i class="fa fa-check-square-o"></i> 선택삭제</button>
</div>
</form>
<hr>

        <div class="alert alert-warning">
        <strong>주의!</strong> 수신자가 동의하지 않은 대량 메일 발송에는 적합하지 않습니다. 수십건 단위로 발송해 주십시오.
        </div>
<script>
$(function() {
    $('#fmaillist').submit(function() {
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