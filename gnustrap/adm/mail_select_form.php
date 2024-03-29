<?php
$sub_menu = "200300";
include_once('./_common.php');

if (!$config['cf_email_use'])
    alert('환경설정에서 \'메일발송 사용\'에 체크하셔야 메일을 발송할 수 있습니다.');

auth_check($auth[$sub_menu], 'r');

$sql = " select * from {$g5['mail_table']} where ma_id = '$ma_id' ";
$ma = sql_fetch($sql);
if (!$ma['ma_id'])
    alert('보내실 내용을 선택하여 주십시오.');

// 전체회원수
$sql = " select COUNT(*) as cnt from {$g5['member_table']} ";
$row = sql_fetch($sql);
$tot_cnt = $row['cnt'];

// 탈퇴대기회원수
$sql = " select COUNT(*) as cnt from {$g5['member_table']} where mb_leave_date <> '' ";
$row = sql_fetch($sql);
$finish_cnt = $row['cnt'];

$last_option = explode('||', $ma['ma_last_option']);
for ($i=0; $i<count($last_option); $i++) {
    $option = explode('=', $last_option[$i]);
    // 동적변수
    $var = $option[0];
    $$var = $option[1];
}

if (!isset($mb_id1)) $mb_id1 = 1;
if (!isset($mb_level_from)) $mb_level_from = 1;
if (!isset($mb_level_to)) $mb_level_to = 10;
if (!isset($mb_mailling)) $mb_mailling = 1;

$g5['title'] = '회원메일발송';
include_once('./admin.head.php');
?>
<div class="alert alert-info">
        <strong>
		전체회원 <?php echo number_format($tot_cnt) ?>명 , 탈퇴대기회원 <?php echo number_format($finish_cnt) ?>명, 정상회원 <?php echo number_format($tot_cnt - $finish_cnt) ?>명 중 메일 발송 대상 선택</strong>
      </div>

<form name="frmsendmailselectform" id="frmsendmailselectform" action="./mail_select_list.php" method="post" autocomplete="off">
<input type="hidden" name="ma_id" value="<?php echo $ma_id ?>">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 대상선택</caption>
    <tbody>
	
	<div class="row">
		        <div class="form-group col-md-12">
		        <label>회원 ID</label>
				<br>

				<div class="btn-group btn-group">
					<input type="radio" name="mb_id1" value="1" id="mb_id1_all" <?php echo $mb_id1?"checked":""; ?>>
                    <label for="mb_id1_all">전체</label>
					<input type="radio" name="mb_id1" value="0" id="mb_id1_section" <?php echo !$mb_id1?"checked":""; ?>>
                    <label for="mb_id1_section">구간</label>
                </div>
				</div>

                <div class="btn-group col-md-6">
				<label>시작구간</label>
                <input type="text" name="mb_id1_from" value="<?php echo $mb_id1_from ?>" id="mb_id1_from" title="시작구간" class="form-control" placeholder="여기서부터"></div>
				<div class="btn-group col-md-6">
				<label>종료구간</label>
                <input type="text" name="mb_id1_to" value="<?php echo $mb_id1_to ?>" id="mb_id1_to" title="종료구간" class="form-control" placeholder="여기까지"></div>
     </div>
	 <hr>
	<div class="row">

		        <div class="form-group col-md-6">
                <label for="mb_email">E-mail</label>
                <input type="text" name="mb_email" value="<?php echo $mb_email ?>" id="mb_email" class="form-control" size="50" placeholder="메일 주소에 단어 포함 (예 : www.gnustrap.com)">
				</div>

		        <div class="form-group col-md-6">
                <label for="mb_mailling">메일링</label>
                <select name="mb_mailling" id="mb_mailling" class="form-control">
                <option value="1">수신동의한 회원만
                <option value="">전체
                </select>
				</div>
     </div>
	 <hr>
	<div class="row">
	            <div class="form-group col-md-6">
                <label>권한</label>
				<br>

                <div class="btn-group">
				<label for="mb_level_from" class="sound_only">최소권한</label>
                <select name="mb_level_from" id="mb_level_from" class="form-control">
                <?php for ($i=1; $i<=10; $i++) { ?>
                <option value="<?php echo $i ?>"><?php echo $i ?></option>
                <?php } ?>
                </select>
				</div>
                -
				<div class="btn-group">
				<label for="mb_level_to" class="sound_only">최대권한</label>
                <select name="mb_level_to" id="mb_level_to">
                <?php for ($i=1; $i<=10; $i++) { ?>
                <option value="<?php echo $i ?>"<?php echo $i==10 ? " selected" : ""; ?>><?php echo $i ?></option>
                <?php } ?>
                </select>
				</div>

		        </div>

		        <div class="form-group col-md-6">
                <label for="gr_id">게시판그룹회원</label>
                <select name="gr_id" id="gr_id" class="form-control">
                <option value=''>전체</option>
                <?php
                $sql = " select gr_id, gr_subject from {$g5['group_table']} order by gr_subject ";
                $result = sql_query($sql);
                for ($i=0; $row=sql_fetch_array($result); $i++) {
                    echo '<option value="'.$row['gr_id'].'">'.$row['gr_subject'].'</option>';
                }
                ?>
                </select>
				</div>

				<div class="col-md-6">
			    <span class="glyphicon glyphicon-pushpin"></span> 권한
				<br />
				<span>레벨에서~ 레벨까지 전송</span>
				</div>
			    <div class="col-md-6">
			    </div>

	</div>
	<hr>
    </tbody>
    </table>
</div>

<div class="center">
    <input type="submit" value="확인" class="btn btn-default">
    <a href="./mail_list.php" class="btn btn-default">목록 </a>
</div>
</form>

<?php
include_once('./admin.tail.php');
?>
