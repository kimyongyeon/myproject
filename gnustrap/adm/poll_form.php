<?php
$sub_menu = "200900";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$token = get_token();

$html_title = '투표';
if ($w == '')
    $html_title .= ' 생성';
else if ($w == 'u')  {
    $html_title .= ' 수정';
    $sql = " select * from {$g5['poll_table']} where po_id = '{$po_id}' ";
    $po = sql_fetch($sql);
} else
    alert('w 값이 제대로 넘어오지 않았습니다.');

$g5['title'] = $html_title;
include_once('./admin.head.php');
?>

<form name="fpoll" id="fpoll" action="./poll_form_update.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="po_id" value="<?php echo $po_id ?>">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="well">
        <strong>투표 설정</strong>
      </div>

    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <tbody>
		
        <div class="row">
		        <div class="form-group col-md-12">
                    <label for="po_subject">투표 제목<strong class="sound_only">필수</strong></label>
					<input type="text" name="po_subject" value="<?php echo $po['po_subject'] ?>" id="po_subject" required class="form-control required" size="80" maxlength="125">
				</div>
				<div class="clearfix"></div>
				<hr>
				<?php for ($i=1; $i<=9; $i++) {
                        $required = '';
                        if ($i==1 || $i==2) {
                        $required = 'required';
                        $sound_only = '<strong class="sound_only">필수</strong>';
                        }
						$po_poll = get_text($po['po_poll'.$i]);
                ?>
		        <div class="form-group col-md-6">
                    <label for="po_poll<?php echo $i ?>">항목 <?php echo $i ?><?php echo $sound_only ?></label>
					<input type="text" name="po_poll<?php echo $i ?>" value="<?php echo $po_poll ?>" id="po_poll<?php echo $i ?>" <?php echo $required ?> class="form-control <?php echo $required ?>" maxlength="125" placeholder="투표내용 <?php echo $i ?>">
				</div>
		        <div class="form-group col-md-6">
                    <label for="po_cnt<?php echo $i ?>">항목 <?php echo $i ?> 투표수</label>
                    <input type="text" name="po_cnt<?php echo $i ?>" value="<?php echo $po['po_cnt'.$i] ?>" id="po_cnt<?php echo $i ?>" class="form-control" size="3" placeholder="임의로 투표수를 지정">
                </div>
				<div class="clearfix"></div>
				<hr>
                <?php } ?>
		</div>

	    <div class="row">
		        <div class="form-group col-md-12">
                    <label for="po_etc">기타의견</label>
					<input type="text" name="po_etc" value="<?php echo get_text($po['po_etc']) ?>" id="po_etc" class="form-control" size="80" maxlength="125" placeholder="입력시 투표할때 건의글 작성가능">
				</div>
				
			<div class="col-md-12">
			<span class="glyphicon glyphicon-pushpin"></span> 기타의견
			<?php echo help('기타 의견을 남길 수 있도록 하려면, 간단한 질문을 입력하세요.') ?>
			</div>
		</div>
		<hr>

	    <div class="row">
		        <div class="form-group col-md-6">
                    <label for="po_level">투표가능 회원레벨</label>
					<div class="input-group">
					<?php echo get_member_level_select('po_level', 1, 10, $po['po_level']) ?>
					<span class="input-group-addon">이상 투표할 수 있음</span>
                    </div> 
				</div>
				
		        <div class="form-group col-md-6">
                    <label for="po_point">포인트</label>
					<div class="input-group">
                    <input type="text" name="po_point" value="<?php echo $po['po_point'] ?>" id="po_point" class="form-control" placeholder="1000">
                    <span class="input-group-addon">점</span>
                    </div>
				</div>
			<div class="col-md-6">
			<span class="glyphicon glyphicon-pushpin"></span> 포인트<br>
			<?php echo help('투표에 참여한 회원에게 포인트를 부여합니다.') ?>
			</div>
				
			<div class="col-md-6">
			<span class="glyphicon glyphicon-pushpin"></span> 투표가능 회원레벨<br>
			<?php echo help("레벨을 1로 설정하면 손님도 투표할 수 있습니다.") ?>
			</div>
				
		</div>
		<div class="clearfix"></div>
		<hr>
    <?php if ($w == 'u') { ?>

	    <div class="row">
		        <div class="form-group col-md-12">
                    <label>투표시작일</label>
					<?php echo $po['po_date']; ?>
				</div>
		 </div>

	    <div class="row">
		        <div class="form-group col-md-12">
                    <label for="po_ips">투표참가 IP</label>
					<textarea name="po_ips" id="po_ips" readonly class="form-control textarea"><?php echo preg_replace("/\n/", " / ", $po['po_ips']) ?></textarea>
                    </div>
				</div>
		 </div>

	    <div class="row">
		        <div class="form-group col-md-12">
                    <label for="mb_ids">투표참가 회원</label>
					<textarea name="mb_ids" id="mb_ids" readonly class="form-control textarea"><?php echo preg_replace("/\n/", " / ", $po['mb_ids']) ?></textarea>
                    </div>
				</div>
		 </div>
		<div class="clearfix"></div>
		<hr>
    <?php } ?>
    </tbody>
    </table>

<div class="center">
    <input type="submit" value="확인" class="btn btn-default" accesskey="s">
    <a href="./poll_list.php?<?php echo $qstr ?>" class="btn btn-default">목록</a>
</div>

</form>

<?php
include_once('./admin.tail.php');
?>
