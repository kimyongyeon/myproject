<?php
$sub_menu = "300100";
include_once("./_common.php");

auth_check($auth[$sub_menu], 'w');

$g5['title'] = '게시판 복사';
include_once(G5_PATH.'/head.sub.php');
?>

<div class="alert alert-info">
    <strong><?php echo $g5['title']; ?></strong>
</div>

    <form name="fboardcopy" id="fboardcopy" action="./board_copy_update.php" onsubmit="return fboardcopy_check(this);" method="post">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>" id="bo_table">

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
        <caption><?php echo $g5['title']; ?></caption>
        <tbody>
        <tr>
            <th scope="col">원본 테이블명</th>
            <td><?php echo $bo_table ?></td>
        </tr>
        <tr>
            <th scope="col"><label for="target_table">복사 테이블명<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="target_table" id="target_table" required class="required alnum_ form-control" maxlength="20" placeholder="영문자, 숫자, _ 만 가능 (공백없이)"></td>
        </tr>
        <tr>
            <th scope="col"><label for="target_subject">게시판 제목<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="target_subject" value="[복사본] <?php echo $board['bo_subject'] ?>" id="target_subject" required class="required form-control" maxlength="120"></td>
        </tr>
        <tr>
            <th scope="col">복사 유형</th>
            <td>
			<div class="btn-group" data-toggle="buttons" style="width: 100%;">
				 <label class="btn btn-default active popover-top" title="" data-content="해당 게시판에 세팅된 설정을 그대로 복사합니다." data-original-title="구조만">
 				   <input type="radio" name="copy_case" value="schema_only" id="copy_case" checked> 구조만
 				 </label>
 				 <label class="btn btn-default popover-top" title="" data-content="해당 게시판에 세팅된 설정과 해당 게시판에 게시물을 모두 복사합니다." data-original-title="구조와 데이터">
 				   <input type="radio" name="copy_case" value="schema_data_both" id="copy_case2"> 구조와 데이터
 				 </label>
			</div>
            </td>
        </tr>
        </tbody>
        </table>
    </div>

    <div class="center">
        <input type="submit" value="복사" class="btn btn-primary">
        <input type="button" value="창닫기" onclick="window.close();" class="btn btn-default">
    </div>

    </form>

</div>

<script>
function fboardcopy_check(f)
{
    if (f.bo_table.value == f.target_table.value) {
        alert("원본 테이블명과 복사할 테이블명이 달라야 합니다.");
        return false;
    }

    return true;
}
</script>


<?php
include_once(G5_PATH.'/tail.sub.php');
?>
