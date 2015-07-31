<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$token = get_token();

if ($w == '')
{
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_';
    $required_mb_password = 'required';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $mb['mb_mailling'] = 1;
    $mb['mb_open'] = 1;
    $mb['mb_level'] = $config['cf_register_level'];
    $html_title = '추가';
}
else if ($w == 'u')
{
    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
        alert('존재하지 않는 회원자료입니다.');

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level'])
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');

    $required_mb_id = 'readonly';
    $required_mb_password = '';
    $html_title = '수정';

    $mb['mb_email'] = get_text($mb['mb_email']);
    $mb['mb_homepage'] = get_text($mb['mb_homepage']);
    $mb['mb_birth'] = get_text($mb['mb_birth']);
    $mb['mb_tel'] = get_text($mb['mb_tel']);
    $mb['mb_hp'] = get_text($mb['mb_hp']);
    $mb['mb_addr1'] = get_text($mb['mb_addr1']);
    $mb['mb_addr2'] = get_text($mb['mb_addr2']);
    $mb['mb_signature'] = get_text($mb['mb_signature']);
    $mb['mb_recommend'] = get_text($mb['mb_recommend']);
    $mb['mb_profile'] = get_text($mb['mb_profile']);
    $mb['mb_1'] = get_text($mb['mb_1']);
    $mb['mb_2'] = get_text($mb['mb_2']);
    $mb['mb_3'] = get_text($mb['mb_3']);
    $mb['mb_4'] = get_text($mb['mb_4']);
    $mb['mb_5'] = get_text($mb['mb_5']);
    $mb['mb_6'] = get_text($mb['mb_6']);
    $mb['mb_7'] = get_text($mb['mb_7']);
    $mb['mb_8'] = get_text($mb['mb_8']);
    $mb['mb_9'] = get_text($mb['mb_9']);
    $mb['mb_10'] = get_text($mb['mb_10']);
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');

// 본인확인방법
switch($mb['mb_certify']) {
    case 'hp':
        $mb_certify_case = '휴대폰';
        $mb_certify_val = 'hp';
        break;
    case 'ipin':
        $mb_certify_case = '아이핀';
        $mb_certify_val = 'ipin';
        break;
    case 'admin':
        $mb_certify_case = '관리자 수정';
        $mb_certify_val = 'admin';
        break;
    default:
        $mb_certify_case = '';
        $mb_certify_val = 'admin';
        break;
}

// 본인확인
$mb_certify_yes  =  $mb['mb_certify'] ? 'checked="checked"' : '';
$mb_certify_no   = !$mb['mb_certify'] ? 'checked="checked"' : '';

// 성인인증
$mb_adult_yes       =  $mb['mb_adult']      ? 'checked="checked"' : '';
$mb_adult_no        = !$mb['mb_adult']      ? 'checked="checked"' : '';

//메일수신
$mb_mailling_yes    =  $mb['mb_mailling']   ? 'checked="checked"' : '';
$mb_mailling_no     = !$mb['mb_mailling']   ? 'checked="checked"' : '';

// SMS 수신
$mb_sms_yes         =  $mb['mb_sms']        ? 'checked="checked"' : '';
$mb_sms_no          = !$mb['mb_sms']        ? 'checked="checked"' : '';

// 정보 공개
$mb_open_yes        =  $mb['mb_open']       ? 'checked="checked"' : '';
$mb_open_no         = !$mb['mb_open']       ? 'checked="checked"' : '';

if (isset($mb['mb_certify'])) {
    // 날짜시간형이라면 drop 시킴
    if (preg_match("/-/", $mb['mb_certify'])) {
        sql_query(" ALTER TABLE `{$g5['member_table']}` DROP `mb_certify` ", false);
    }
} else {
    sql_query(" ALTER TABLE `{$g5['member_table']}` ADD `mb_certify` TINYINT(4) NOT NULL DEFAULT '0' AFTER `mb_hp` ", false);
}

if(isset($mb['mb_adult'])) {
    sql_query(" ALTER TABLE `{$g5['member_table']}` CHANGE `mb_adult` `mb_adult` TINYINT(4) NOT NULL DEFAULT '0' ", false);
} else {
    sql_query(" ALTER TABLE `{$g5['member_table']}` ADD `mb_adult` TINYINT NOT NULL DEFAULT '0' AFTER `mb_certify` ", false);
}

// 지번주소 필드추가
if(!isset($mb['mb_addr_jibeon'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_addr_jibeon` varchar(255) NOT NULL DEFAULT '' AFTER `mb_addr2` ", false);
}

// 건물명필드추가
if(!isset($mb['mb_addr3'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_addr3` varchar(255) NOT NULL DEFAULT '' AFTER `mb_addr2` ", false);
}

// 중복가입 확인필드 추가
if(!isset($mb['mb_dupinfo'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_dupinfo` varchar(255) NOT NULL DEFAULT '' AFTER `mb_adult` ", false);
}

if ($mb['mb_intercept_date']) $g5['title'] = "차단된 ";
else $g5['title'] .= "";
$g5['title'] .= '회원 '.$html_title;
include_once('./admin.head.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
?>

<form name="fmember" id="fmember" action="./member_form_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">

<section id="anc_cf_basic">
    <div class="well">
        <strong>회원 정보</strong>
      </div>

        <div class="row">
		        <div class="form-group col-md-3">
                    <label class="control-label" for="mb_id">아이디<?php echo $sound_only ?></label>
					<div class="input-group">
                    <input type="text" name="mb_id" value="<?php echo $mb['mb_id'] ?>" id="mb_id" <?php echo $required_mb_id ?> class="form-control <?php echo $required_mb_id_class ?>" size="15" maxlength="20" >
                    <span class="input-group-addon"><?php if ($w=='u'){ ?><a href="./boardgroupmember_form.php?mb_id=<?php echo $mb['mb_id'] ?>" >접근가능그룹보기</a><?php } ?></span>
                    </div>
                </div>
		        <div class="form-group col-md-3">
				    <label class="control-label" for="mb_password">비밀번호<?php echo $sound_only ?></label>
					<input type="password" name="mb_password" id="mb_password" <?php echo $required_mb_password ?> class="form-control <?php echo $required_mb_password ?>" size="15" maxlength="20">
				</div>
		        <div class="form-group col-md-3">
				    <label class="control-label" for="mb_name">이름(실명)<strong class="sound_only">필수</strong></label>
					<input type="text" name="mb_name" value="<?php echo $mb['mb_name'] ?>" id="mb_name" required class="form-control required minlength=2" size="15" maxlength="20">
				</div>
		        <div class="form-group col-md-3">
				    <label class="control-label" for="mb_nick">닉네임<strong class="sound_only">필수</strong></label>
					<input type="text" name="mb_nick" value="<?php echo $mb['mb_nick'] ?>" id="mb_nick" required class="form-control required minlength=2" size="15" maxlength="20">
				</div>
		</div>
		<hr>

        <div class="row">
		        <div class="form-group col-md-3">
                    <label class="control-label" for="mb_level">회원 권한</label>
                    <?php echo get_member_level_select('mb_level', 1, $member['mb_level'], $mb['mb_level']) ?>
                </div>
		        <div class="form-group col-md-3">
				    <label class="control-label">포인트</label>
					<div class="well well-sm"><a href="./point_list.php?sfl=mb_id&amp;stx=<?php echo $mb['mb_id'] ?>" target="_blank"><?php echo number_format($mb['mb_point']) ?></a> 점</div>
				</div>
		        <div class="form-group col-md-3">
				    <label class="control-label" for="mb_email">E-mail<strong class="sound_only">필수</strong></label>
					<input type="text" name="mb_email" value="<?php echo $mb['mb_email'] ?>" id="mb_email" maxlength="100" required class="form-control required email" size="30">
				</div>
		        <div class="form-group col-md-3">
				    <label class="control-label" for="mb_homepage">홈페이지</label>
					<input type="text" name="mb_homepage" value="<?php echo $mb['mb_homepage'] ?>" id="mb_homepage" class="form-control" maxlength="255" size="15">
				</div>
		</div>
		<hr>

        <div class="row">
		        <div class="form-group col-md-6">
                    <label class="control-label" for="mb_hp">휴대폰번호</label>
                    <input type="text" name="mb_hp" value="<?php echo $mb['mb_hp'] ?>" id="mb_hp" class="form-control" size="15" maxlength="20">
                </div>
		        <div class="form-group col-md-6">
				    <label class="control-label" for="mb_tel">전화번호</label>
					<input type="text" name="mb_tel" value="<?php echo $mb['mb_tel'] ?>" id="mb_tel" class="form-control" size="15" maxlength="20">
				</div>
		</div>
		<hr>

        <div class="row">
		        <div class="form-group col-md-4">
                    <label class="control-label">본인확인방법</label>
					<br />
					<input type="radio" name="mb_certify_case" value="ipin" id="mb_certify_ipin" <?php if($mb['mb_certify'] == 'ipin') echo 'checked="checked"'; ?>>
            		<label for="mb_certify_ipin">아이핀</label>
            		<input type="radio" name="mb_certify_case" value="hp" id="mb_certify_hp" <?php if($mb['mb_certify'] == 'hp') echo 'checked="checked"'; ?>>
            		<label for="mb_certify_hp">휴대폰</label>

                </div>
		        <div class="form-group col-md-4">
                    <label class="control-label">본인확인</label>
					<br />
            		<input type="radio" name="mb_certify" value="1" id="mb_certify_yes" <?php echo $mb_certify_yes; ?>>
            		<label for="mb_certify_yes">예</label>
            		<input type="radio" name="mb_certify" value="" id="mb_certify_no" <?php echo $mb_certify_no; ?>>
            		<label for="mb_certify_no">아니오</label>
				</div>
		        <div class="form-group col-md-4">
                    <label class="control-label" for="mb_adult">성인인증</label>
					<br />
            		<input type="radio" name="mb_adult" value="1" id="mb_adult_yes" <?php echo $mb_adult_yes; ?>>
            		<label for="mb_adult_yes">예</label>
            		<input type="radio" name="mb_adult" value="0" id="mb_adult_no" <?php echo $mb_adult_no; ?>>
            		<label for="mb_adult_no">아니오</label>
				</div>
		</div>
		<hr>

        <div class="row">
		        <div class="form-group col-md-12">
		        <label for="mb_zip1">주소</label>
				<br />

                <div class="btn-group">
				<label for="mb_zip1" class="sound_only">우편번호 앞자리</label>
                <input type="text" name="mb_zip1" value="<?php echo $mb['mb_zip1'] ?>" id="mb_zip1" class="form-control readonly" size="3" maxlength="3"></div>
                -
				<div class="btn-group">
				<label for="mb_zip2" class="sound_only">우편번호 뒷자리</label>
                <input type="text" name="mb_zip2" value="<?php echo $mb['mb_zip2'] ?>" id="mb_zip2" class="form-control readonly" size="3" maxlength="3"></div>

				<div class="btn-group">
				<button type="button" class="btn btn-default" onclick="win_zip('fmember', 'mb_zip1', 'mb_zip2', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');"><i class="fa fa-home fa-fw"></i> 주소 검색</button>
				</div>
		        </div>

		        <div class="form-group col-md-6">
                <label for="mb_addr1">기본주소</label>
                <input type="text" name="mb_addr1" value="<?php echo $mb['mb_addr1'] ?>" id="mb_addr1" class="form-control readonly" size="60">
				</div>

		        <div class="form-group col-md-6">
                <label for="mb_addr2">상세주소</label>
                <input type="text" name="mb_addr2" value="<?php echo $mb['mb_addr2'] ?>" id="mb_addr2" class="form-control" size="60">
				</div>

		        <div class="form-group col-md-12">
                <label for="mb_addr3">참고항목</label>
                <input type="text" name="mb_addr3" value="<?php echo $mb['mb_addr3'] ?>" id="mb_addr3" class="form-control" size="60" readonly="readonly" placeholder="나머지 주소를 모를때 참고용">

                <input type="hidden" name="mb_addr_jibeon" value="<?php echo $mb['mb_addr_jibeon']; ?>">
				</div>
		</div>

		<hr>
		
        <div class="row">
		        <div class="form-group col-md-6">
                    <label for="mb_icon">회원아이콘</label>
					<br />
					<div class="btn-group btn-group">
				    <input type="file" name="mb_icon" id="mb_icon" class="form-control"> 
				    </div>
				</div>
		        <div class="form-group col-md-6">
                    <label for="mb_icon">현재아이콘</label>
					<br />
                    <?php
                    $mb_dir = substr($mb['mb_id'],0,2);
                    $icon_file = G5_DATA_PATH.'/member/'.$mb_dir.'/'.$mb['mb_id'].'.gif';
                    if (file_exists($icon_file)) {
                    $icon_url = G5_DATA_URL.'/member/'.$mb_dir.'/'.$mb['mb_id'].'.gif';
                    echo '<img src="'.$icon_url.'" alt="" class="img-thumbnail"> ';
                    echo '<label class="btn btn-default" for="del_mb_icon" style="margin-left:5px;"><input type="checkbox" id="del_mb_icon" name="del_mb_icon" value="1" data-toggle="checkbox"> 삭제 </label></button>';
                    }?>
                </div>
				
			<div class="col-md-12">
			<span class="glyphicon glyphicon-pushpin"></span> 회원아이콘
			<?php echo help('이미지 크기는 <strong>넓이 '.$config['cf_member_icon_width'].'픽셀 높이 '.$config['cf_member_icon_height'].'픽셀</strong>로 해주세요.') ?>
			</div>
		</div>
		<hr>
		
        <div class="row">
		        <div class="form-group col-md-6">
                    <label for="mb_1">회원사진</label>
					<br />
					<div class="btn-group btn-group">
					<input type="file" name="mb_1" class="form-control">
				    </div>
				</div>
		        <div class="form-group col-md-6">
                    <label for="mb_1">현재사진</label>
					<br />
                    <?php
                    $mb1_dir = substr($mb['mb_id'],0,2);
                    $mb_1 = G5_DATA_PATH.'/member_image/'.$mb1_dir.'/'.$mb['mb_id'].'.gif';
                    if (file_exists($mb_1)) {
                        $mb_1 = G5_DATA_URL.'/member_image/'.$mb1_dir.'/'.$mb['mb_id'].'.gif';
                        echo '<img src="'.$mb_1.'" alt="" class="img-thumbnail" style="width:'.$config['cf_1'].'px";>';
                        echo '<label class="btn btn-default" for="del_mb_1" style="margin-left:5px;"><input type="checkbox" id="del_mb_1" name="del_mb_1" value="1" data-toggle="checkbox"> 삭제 </label>';
                    }
                    ?>
                </div>
				
			<div class="col-md-12">
			<span class="glyphicon glyphicon-pushpin"></span> 회원사진
			<?php echo help('이미지 크기는 <strong>넓이 '.$config['cf_1'].'픽셀 높이 '.$config['cf_2'].'픽셀</strong>로 해주세요.') ?>
			</div>
		</div>
		<hr>

        <div class="row">
		        <div class="form-group col-md-4">
                    <label class="control-label">메일 수신</label>
					<br />
            		<input type="radio" name="mb_mailling" value="1" id="mb_mailling_yes" <?php echo $mb_mailling_yes; ?>>
            		<label for="mb_mailling_yes">예</label>
            		<input type="radio" name="mb_mailling" value="0" id="mb_mailling_no" <?php echo $mb_mailling_no; ?>>
           		    <label for="mb_mailling_no">아니오</label>
				</div>
		        <div class="form-group col-md-4">
                    <label for="mb_sms_yes">SMS 수신</label>
					<br />
            		<input type="radio" name="mb_sms" value="1" id="mb_sms_yes" <?php echo $mb_sms_yes; ?>>
            		<label for="mb_sms_yes">예</label>
            		<input type="radio" name="mb_sms" value="0" id="mb_sms_no" <?php echo $mb_sms_no; ?>>
            		<label for="mb_sms_no">아니오</label>
				</div>
		        <div class="form-group col-md-4">
                    <label for="mb_open">정보 공개</label>
					<br />
            		<input type="radio" name="mb_open" value="1" id="mb_open_yes" <?php echo $mb_open_yes; ?>>
            		<label for="mb_open_yes">예</label>
            		<input type="radio" name="mb_open" value="0" id="mb_open_no" <?php echo $mb_open_no; ?>>
            		<label for="mb_open_no">아니오</label>
                </div>
		</div>
		<hr>
        
		<div class="row">
		        <div class="form-group col-md-12">
                    <label for="mb_signature">서명</label>
                
                    <textarea class="form-control textarea" name="mb_signature" id="mb_signature"><?php echo $mb['mb_signature'] ?></textarea>
                </div>
		</div>
		<div class="row">
		        <div class="form-group col-md-12">
                    <label for="mb_profile">자기 소개</label>
                
                    <textarea class="form-control textarea" name="mb_profile" id="mb_profile"><?php echo $mb['mb_profile'] ?></textarea>
                </div>
		</div>
		<div class="row">
		        <div class="form-group col-md-12">
                    <label for="mb_memo">메모</label>
                
                    <textarea class="form-control textarea" name="mb_memo" id="mb_memo"><?php echo $mb['mb_memo'] ?></textarea>
                </div>
		</div>
		<hr>

    <?php if ($w == 'u') { ?>
        
		<div class="row">
		        <div class="form-group col-md-3">
                    <label>회원가입일</label>
					<br />
                    <?php echo $mb['mb_datetime'] ?>
                </div>
		        <div class="form-group col-md-3">
                    <label>최근접속일</label>
					<br />
                    <?php echo $mb['mb_today_login'] ?>
                </div>
		        <div class="form-group col-md-3">
                    <label>IP</label>
					<br />
                    <?php echo $mb['mb_ip'] ?>
                </div>
                
				<?php if ($config['cf_use_email_certify']) { ?>
		        <div class="form-group col-md-3">
                    <label>인증일시</label>
					<br />
                    <?php if ($mb['mb_email_certify'] == '0000-00-00 00:00:00') { ?>
                    <?php echo help('회원님이 메일을 수신할 수 없는 경우 등에 직접 인증처리를 하실 수 있습니다.') ?>
                    <input type="checkbox" name="passive_certify" id="passive_certify">
                    <label for="passive_certify">수동인증</label>
                    <?php } else { ?>
                    <?php echo $mb['mb_email_certify'] ?>
                    <?php } ?>
                    </div>
                    <?php } ?>
                <?php } ?>
				
            <?php if ($config['cf_use_recommend']) { // 추천인 사용 ?>
			<div class="col-md-6">
			<span class="glyphicon glyphicon-pushpin"></span> 추천인
			<?php echo ($mb['mb_recommend'] ? get_text($mb['mb_recommend']) : '없음'); // 081022 : CSRF 보안 결함으로 인한 코드 수정 ?>
			</div>
            <?php } ?>
		</div>
		<hr>
        <div class="row">
		        <div class="form-group col-md-6">
                    <label for="mb_leave_date">탈퇴일자</label>
					<div class="input-group">
                    <input type="text" name="mb_leave_date" value="<?php echo $mb['mb_leave_date'] ?>" id="mb_leave_date" class="form-control" maxlength="8">
                    <span class="input-group-addon">
			        <input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_leave_date_set_today" onclick="if (this.form.mb_leave_date.value==this.form.mb_leave_date.defaultValue) {this.form.mb_leave_date.value=this.value; } else { this.form.mb_leave_date.value=this.form.mb_leave_date.defaultValue; }">
                    <label for="mb_leave_date_set_today">탈퇴일을 오늘로 지정</label>
			        </span>
                    </div>
                </div>
		        <div class="form-group col-md-6">
				    <label>접근차단일자</label>
					<div class="input-group">
                    <input type="text" name="mb_intercept_date" value="<?php echo $mb['mb_intercept_date'] ?>" id="mb_intercept_date" class="form-control" maxlength="8">
                    <span class="input-group-addon">
			        <input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_intercept_date_set_today" onclick="if(this.form.mb_intercept_date.value==this.form.mb_intercept_date.defaultValue) { this.form.mb_intercept_date.value=this.value; } else {this.form.mb_intercept_date.value=this.form.mb_intercept_date.defaultValue; }">
                    <label for="mb_intercept_date_set_today">접근차단일을 오늘로 지정</label>
			        </span>
                    </div>
				</div>

                <?php for ($i=1; $i<=10; $i++) { ?>
		        <div class="col-md-12">
                    <label for="mb_<?php echo $i ?>">여분 필드 <?php echo $i ?></label>
                    <input type="text" name="mb_<?php echo $i ?>" value="<?php echo $mb['mb_'.$i] ?>" id="mb_<?php echo $i ?>" class="form-control" size="30" maxlength="255" placeholder="여분 필드 <?php echo $i ?>">
                </div>
                <?php } ?>
		</div>
		<hr>
    </tbody>
    </table>

<div class="center">
    <input type="submit" value="확인" class="btn btn-default" accesskey='s'>
    <a href="./member_list.php?<?php echo $qstr ?>" class="btn btn-default">목록</a>
</div>
</form>

<script>
function fmember_submit(f)
{
    if (!f.mb_icon.value.match(/\.gif$/i) && f.mb_icon.value) {
        alert('아이콘은 gif 파일만 가능합니다.');
        return false;
    }

    return true;
}
</script>

<?php
include_once('./admin.tail.php');
?>
