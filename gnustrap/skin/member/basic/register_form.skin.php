<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 회원정보 입력/수정 시작 { -->

    <script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>
    <?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
    <script src="<?php echo G5_JS_URL ?>/certify.js"></script>
    <?php } ?>

    <form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="url" value="<?php echo $urlencode ?>">
    <input type="hidden" name="agree" value="<?php echo $agree ?>">
    <input type="hidden" name="agree2" value="<?php echo $agree2 ?>">
    <input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
    <input type="hidden" name="cert_no" value="">
    <?php if (isset($member['mb_sex'])) {  ?><input type="hidden" name="mb_sex" value="<?php echo $member['mb_sex'] ?>"><?php }  ?>
    <?php if (isset($member['mb_nick_date']) && $member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) { // 닉네임수정일이 지나지 않았다면  ?>
    <input type="hidden" name="mb_nick_default" value="<?php echo $member['mb_nick'] ?>">
    <input type="hidden" name="mb_nick" value="<?php echo $member['mb_nick'] ?>">
    <?php }  ?>

<div class="panel panel-default">
    <div class="panel-heading"><strong>회원정보</strong></div>

    <div class="panel-body">

        <div class="alert alert-info">
        <strong>사이트 이용정보 입력</strong>
        </div>

		<div class="row">
		        <div class="form-group col-md-4">
                    <label for="reg_mb_id">아이디<strong class="sound_only">필수</strong></label>
                    <input type="text" name="mb_id" value="<?php echo $member['mb_id'] ?>" id="reg_mb_id" <?php echo $required ?> <?php echo $readonly ?> class="form-control <?php echo $required ?> <?php echo $readonly ?>" minlength="3" maxlength="20" placeholder="아이디">
                </div>
		        <div class="form-group col-md-4">
                    <label for="reg_mb_password">비밀번호<strong class="sound_only">필수</strong></label>
					<input type="password" name="mb_password" id="reg_mb_password" <?php echo $required ?> class="form-control <?php echo $required ?>" minlength="3" maxlength="20" placeholder="비밀번호">
                </div>
		        <div class="form-group col-md-4">
                    <label for="reg_mb_password_re">비밀번호 확인<strong class="sound_only">필수</strong></label>
					<input type="password" name="mb_password_re" id="reg_mb_password_re" <?php echo $required ?> class="form-control <?php echo $required ?>" minlength="3" maxlength="20" placeholder="비밀번호 확인">
                </div>
				
			<div class="col-md-4">
			<span class="glyphicon glyphicon-pushpin"></span> 아이디<br />
			영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.
			</div>
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
            </div>
		</div>
		<hr>

        <div class="alert alert-info">
        <strong>개인정보 입력</strong>
        </div>

		<div class="row">
		        <div class="form-group col-md-4">
                    <label for="reg_mb_name">이름<strong class="sound_only">필수</strong></label>
                    <input type="text" id="reg_mb_name" name="mb_name" value="<?php echo $member['mb_name'] ?>" <?php echo $required ?> <?php echo $readonly; ?> class="form-control <?php echo $required ?> <?php echo $readonly ?>" size="10" placeholder="이름">
                    
                </div>

                <?php if ($req_nick) {  ?>
		        <div class="form-group col-md-4">
                    <label for="reg_mb_nick">닉네임<strong class="sound_only">필수</strong></label>
					<input type="hidden" name="mb_nick_default" value="<?php echo isset($member['mb_nick'])?$member['mb_nick']:''; ?>">
                    <input type="text" name="mb_nick" value="<?php echo isset($member['mb_nick'])?$member['mb_nick']:''; ?>" id="reg_mb_nick" required class="form-control required nospace" size="10" maxlength="20" placeholder="닉네임">
                    <span id="msg_mb_nick"></span>
                </div>
                <?php }  ?>

		        <div class="form-group col-md-4">
                    <label for="reg_mb_email">E-mail<strong class="sound_only">필수</strong></label>
					<input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">
                    <input type="text" name="mb_email" value="<?php echo isset($member['mb_email'])?$member['mb_email']:''; ?>" id="reg_mb_email" required class="form-control email required" size="70" maxlength="100" placeholder="gnustrap@******.com">
                </div>
				
			<div class="col-md-4">
			        <?php if ($config['cf_cert_use']) { ?>
                    <span class="frm_info">아이핀 본인확인 후에는 이름이 자동 입력되고 휴대폰 본인확인 후에는 이름과 휴대폰번호가 자동 입력되어 수동으로 입력할수 없게 됩니다.</span>
                    <?php } ?>
			        <?php
                    if($config['cf_cert_use']) {
                    if($config['cf_cert_ipin'])
                        echo '<button type="button" id="win_ipin_cert" class="btn btn-default">아이핀 본인확인</button>'.PHP_EOL;
                    if($config['cf_cert_hp'])
                        echo '<button type="button" id="win_hp_cert" class="btn btn-default">휴대폰 본인확인</button>'.PHP_EOL;

                    echo '<noscript>본인확인을 위해서는 자바스크립트 사용이 가능해야합니다.</noscript>'.PHP_EOL;
                    }
                    ?>
                    <?php
                    if ($config['cf_cert_use'] && $member['mb_certify']) {
                    if($member['mb_certify'] == 'ipin')
                        $mb_cert = '아이핀';
                    else
                        $mb_cert = '휴대폰';
                    ?>
			        <span class="glyphicon glyphicon-pushpin"></span> 이름<br />
					<div id="msg_certify">
                    <strong><?php echo $mb_cert; ?> 본인확인</strong><?php if ($member['mb_adult']) { ?> 및 <strong>성인인증</strong><?php } ?> 완료
                    </div>
                    <?php } ?>
			</div>
			<div class="col-md-4">
			        <span class="glyphicon glyphicon-pushpin"></span> 닉네임<br />
					공백없이 한글,영문,숫자만 입력 가능 (한글2자, 영문4자 이상)<br>
					닉네임을 바꾸시면 앞으로 <?php echo (int)$config['cf_nick_modify'] ?>일 이내에는 변경 할 수 없습니다.
			</div>
			<div class="col-md-4">
                <?php if ($config['cf_use_email_certify']) {  ?>
			        <span class="glyphicon glyphicon-pushpin"></span> E-mail<br />
			        <?php if ($w=='') { echo "E-mail 로 발송된 내용을 확인한 후 인증하셔야 회원가입이 완료됩니다."; }  ?>
                    <?php if ($w=='u') { echo "E-mail 주소를 변경하시면 다시 인증하셔야 합니다."; }  ?>
                <?php }  ?>
            </div>
		</div>
		<hr>

		<div class="row">
        <?php if ($config['cf_use_homepage']) {  ?>
		        <div class="form-group col-md-4">
                    <label for="reg_mb_homepage">홈페이지<?php if ($config['cf_req_homepage']){ ?><strong class="sound_only">필수</strong><?php } ?></label>
                    <input type="text" name="mb_homepage" value="<?php echo $member['mb_homepage'] ?>" id="reg_mb_homepage" <?php echo $config['cf_req_homepage']?"required":""; ?> class="form-control <?php echo $config['cf_req_homepage']?"required":""; ?>" size="70" maxlength="255" placeholder="http://gnustrap.com">
                </div>
        <?php }  ?>
        <?php if ($config['cf_use_tel']) {  ?>
		        <div class="form-group col-md-4">
                    <label for="reg_mb_tel">전화번호<?php if ($config['cf_req_tel']) { ?><strong class="sound_only">필수</strong><?php } ?></label>
					<input type="text" name="mb_tel" value="<?php echo $member['mb_tel'] ?>" id="reg_mb_tel" <?php echo $config['cf_req_tel']?"required":""; ?> class="form-control <?php echo $config['cf_req_tel']?"required":""; ?>" maxlength="20" placeholder="02-000-0000">
                </div>
        <?php }  ?>
        <?php if ($config['cf_use_tel']) {  ?>
		        <div class="form-group col-md-4">
                    <label for="reg_mb_hp">휴대폰번호<?php if ($config['cf_req_hp']) { ?><strong class="sound_only">필수</strong><?php } ?></label>
					<input type="text" name="mb_hp" value="<?php echo $member['mb_hp'] ?>" id="reg_mb_hp" <?php echo ($config['cf_req_hp'])?"required":""; ?> class="form-control <?php echo ($config['cf_req_hp'])?"required":""; ?>" maxlength="20" placeholder="010-0000-0000">
                    <?php if ($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
                    <input type="hidden" name="old_mb_hp" value="<?php echo $member['mb_hp'] ?>">
                    <?php } ?>
                </div>
        <?php }  ?>
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
            </div>
		</div>
		<hr>

        <?php if ($config['cf_use_addr']) { ?>
        <div class="row">
		        <div class="form-group col-md-12">
		        <label>주소<?php if ($config['cf_req_addr']) { ?><strong class="sound_only">필수</strong><?php }  ?></label>
				<br>

                <div class="btn-group">
				<label for="reg_mb_zip1" class="sound_only">우편번호 앞자리<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></label>
                <input type="text" name="mb_zip1" value="<?php echo $member['mb_zip1'] ?>" id="reg_mb_zip1" <?php echo $config['cf_req_addr']?"required":""; ?> class="form-control <?php echo $config['cf_req_addr']?"required":""; ?>" size="3" maxlength="3">
				</div>
                -
				<div class="btn-group">
				<label for="reg_mb_zip2" class="sound_only">우편번호 뒷자리<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></label>
                <input type="text" name="mb_zip2" value="<?php echo $member['mb_zip2'] ?>" id="reg_mb_zip2" <?php echo $config['cf_req_addr']?"required":""; ?> class="form-control <?php echo $config['cf_req_addr']?"required":""; ?>" size="3" maxlength="3">
				</div>

				<div class="btn-group">
				<button type="button" class="btn btn-default" onclick="win_zip('fregisterform', 'mb_zip1', 'mb_zip2', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');"><i class="fa fa-home fa-fw"></i> 주소 검색</button><br>
				</div>
		        </div>

		        <div class="form-group col-md-6">
                <label for="reg_mb_addr1">기본주소<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></label>
                <input type="text" name="mb_addr1" value="<?php echo $member['mb_addr1'] ?>" id="reg_mb_addr1" <?php echo $config['cf_req_addr']?"required":""; ?> class="form-control frm_address <?php echo $config['cf_req_addr']?"required":""; ?>" size="50" placeholder="시작 주소">
				</div>

		        <div class="form-group col-md-6">
                <label for="reg_mb_addr2">상세주소</label>
                <input type="text" name="mb_addr2" value="<?php echo $member['mb_addr2'] ?>" id="reg_mb_addr2" class="form-control frm_address" size="50" placeholder="나머지 주소">
				</div>

		        <div class="form-group col-md-12">
                <label for="reg_mb_addr3">참고항목</label>
                <input type="text" name="mb_addr3" value="<?php echo $member['mb_addr3'] ?>" id="reg_mb_addr3" class="form-control frm_address" size="50" readonly="readonly" placeholder="나머지 주소를 모를때 참고용">

                <input type="hidden" name="mb_addr_jibeon" value="<?php echo $member['mb_addr_jibeon']; ?>">
				</div>
		  </div>
		  <hr >
        <?php }  ?>
		
        <div class="alert alert-info">
        <strong>기타 개인설정</strong>
        </div>

		<div class="row">
		<?php if ($config['cf_use_signature']) {  ?>
		        <div class="form-group col-md-6">
                    <label for="reg_mb_signature">서명<?php if ($config['cf_req_signature']){ ?><strong class="sound_only">필수</strong><?php } ?></label>
                    <textarea name="mb_signature" id="reg_mb_signature" <?php echo $config['cf_req_signature']?"required":""; ?> class="form-control textarea <?php echo $config['cf_req_signature']?"required":""; ?>" placeholder="서명을 입력합니다."><?php echo $member['mb_signature'] ?></textarea>
                </div>
        <?php }  ?>
        <?php if ($config['cf_use_profile']) {  ?>
		        <div class="form-group col-md-6">
                    <label for="reg_mb_profile">자기소개</label>
					<textarea name="mb_profile" id="reg_mb_profile" <?php echo $config['cf_req_profile']?"required":""; ?> class="form-control textarea <?php echo $config['cf_req_profile']?"required":""; ?>" placeholder="자신을 소개해주세요."><?php echo $member['mb_profile'] ?></textarea>
                </div>
        <?php }  ?>
		</div>
		<hr>
		
		<div class="row">
        <?php if ($config['cf_use_member_icon'] && $member['mb_level'] >= $config['cf_icon_level']) {  ?>
		        <div class="form-group col-md-6">
                    <label for="reg_mb_icon">회원아이콘</label>
					<input type="file" name="mb_icon" id="reg_mb_icon" class="form-control">
                    <?php if ($w == 'u' && file_exists($mb_icon_path)) {  ?>
                    <img src="<?php echo $mb_icon_url ?>" alt="회원아이콘">
                    <input type="checkbox" name="del_mb_icon" value="1" id="del_mb_icon">
                    <label for="del_mb_icon">삭제</label>
                    <?php }  ?>
                </div>
        <?php }  ?>
		        <div class="form-group col-md-6">
                    <label for="reg_mb_1">회원사진</label>
					<input type="file" name="mb_1" id="reg_mb_1" class="form-control">
                    <?php if ($w == 'u' && file_exists($mb_1_path)) {  ?>
                    <img src="<?php echo $mb_1_url ?>" alt="회원사진">
                    <input type="checkbox" name="del_mb_1" value="1" id="del_mb_1">
                    <label for="del_mb_1">삭제</label>
                    <?php }  ?>
                </div>
			<div class="col-md-6">
			<span class="glyphicon glyphicon-pushpin"></span> 회원아이콘<br />
			이미지 크기는 가로 <?php echo $config['cf_member_icon_width'] ?>픽셀, 세로 <?php echo $config['cf_member_icon_height'] ?>픽셀 이하로 해주세요.<br>
			gif만 가능하며 용량 <?php echo number_format($config['cf_member_icon_size']) ?>바이트 이하만 등록됩니다.
			</div>
			<div class="col-md-6">
			<span class="glyphicon glyphicon-pushpin"></span> 회원사진<br />
			이미지 크기는 가로 <?php echo $config[cf_1] ?>픽셀, 세로 <?php echo $config[cf_2] ?>픽셀 이하로 해주세요.<br>
			gif만 가능하며 용량 <?php echo number_format($config[cf_3]) ?>바이트 이하만 등록됩니다.
            </div>
		</div>
		<hr>

		<div class="row">
		        <div class="form-group col-md-4">
                    <label for="reg_mb_mailling">메일링서비스</label>
					<label class="btn btn-primary <?php echo $member['mb_mailling']?'active':''; ?>" for="reg_mb_mailling" style="width:100%; margin:15px 0;">
		            <input type="checkbox" name="mb_mailling" value="1" <?php echo ($w=='' || $member['mb_mailling'])?'checked':''; ?> data-toggle="checkbox" id="reg_mb_mailling">
					<i class="fa fa-mail-reply-all"></i> 정보 메일을 받겠습니다.</label>
                </div>
        <?php if ($config['cf_use_hp']) {  ?>
		        <div class="form-group col-md-4">
				    <label for="reg_mb_sms">SMS 수신여부</label>
					<label class="btn btn-primary <?php echo $member['mb_sms']?'active':''; ?>" for="reg_mb_sms" style="width:100%; margin:15px 0;">
		            <input type="checkbox" name="mb_sms" value="1" <?php echo ($w=='' || $member['mb_sms'])?'checked':''; ?> data-toggle="checkbox" id="reg_mb_sms">
					<i class="fa fa-envelope-o"></i> 휴대폰 문자메세지를 받겠습니다.</label>
                </div>
        <?php }  ?>
        <?php if (isset($member['mb_open_date']) && $member['mb_open_date'] <= date("Y-m-d", G5_SERVER_TIME - ($config['cf_open_modify'] * 86400)) || empty($member['mb_open_date'])) { // 정보공개 수정일이 지났다면 수정가능  ?>
		        <div class="form-group col-md-4">
				    <label>정보공개</label>
					<label class="btn btn-primary <?php echo $member['mb_open']?'active':''; ?>" for="reg_mb_open" style="width:100%; margin:15px 0;">
		            <input type="hidden" name="mb_open_default" value="<?php echo $member['mb_open'] ?>">
                    <input type="checkbox" name="mb_open" value="1" <?php echo ($w=='' || $member['mb_open'])?'checked':''; ?> id="reg_mb_open" data-toggle="checkbox">
					<i class="fa fa-share-alt-square"></i> 다른분들이 나의 정보를 볼 수 있도록 합니다.</label>
                </div>
				
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
			<span class="glyphicon glyphicon-pushpin"></span> 정보공개<br />
			정보공개를 바꾸시면 앞으로 <?php echo (int)$config['cf_open_modify'] ?>일 이내에는 변경이 안됩니다.
            </div>
        <?php } else {  ?>
		        <div class="form-group col-md-4">
                    <label>정보공개</label>
                    <input type="hidden" name="mb_open" value="<?php echo $member['mb_open'] ?>">
                </div>
				
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
			<span class="glyphicon glyphicon-pushpin"></span> 정보공개<br />
			정보공개는 수정후 <?php echo (int)$config['cf_open_modify'] ?>일 이내, <?php echo date("Y년 m월 j일", isset($member['mb_open_date']) ? strtotime("{$member['mb_open_date']} 00:00:00")+$config['cf_open_modify']*86400:G5_SERVER_TIME+$config['cf_open_modify']*86400); ?> 까지는 변경이 안됩니다.<br>
			이렇게 하는 이유는 잦은 정보공개 수정으로 인하여 쪽지를 보낸 후 받지 않는 경우를 막기 위해서 입니다.
            </div>
        <?php }  ?>
		</div>
		<hr>

        <?php if ($w == "" && $config['cf_use_recommend']) {  ?>
		<div class="row">
		        <div class="form-group col-md-6">
                    <label for="reg_mb_recommend">추천인아이디</label>
                    <input type="text" name="mb_recommend" id="reg_mb_recommend" class="form-control" placeholder="자신을 소개해준 회원 ID">
                </div>
        <?php }  ?>
		        <div class="form-group col-md-6">
                <?php echo captcha_html(); ?>
		        </div>
		</div>
    </div>

    <div class="panel-footer text-center">
        <input type="submit" value="<?php echo $w==''?'회원가입':'정보수정'; ?>" id="btn_submit" class="btn btn-primary" accesskey="s">
        <a href="<?php echo G5_URL ?>" class="btn btn-default">취소</a>
    </div>
    </form>

    <script>
    $(function() {
        $("#reg_zip_find").css("display", "inline-block");

        <?php if($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
        // 아이핀인증
        $("#win_ipin_cert").click(function() {
            if(!cert_confirm())
                return false;

            var url = "<?php echo G5_OKNAME_URL; ?>/ipin1.php";
            certify_win_open('kcb-ipin', url);
            return;
        });

        <?php } ?>
        <?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
        // 휴대폰인증
        $("#win_hp_cert").click(function() {
            if(!cert_confirm())
                return false;

            <?php
            switch($config['cf_cert_hp']) {
                case 'kcb':
                    $cert_url = G5_OKNAME_URL.'/hpcert1.php';
                    $cert_type = 'kcb-hp';
                    break;
                case 'kcp':
                    $cert_url = G5_KCPCERT_URL.'/kcpcert_form.php';
                    $cert_type = 'kcp-hp';
                    break;
                case 'lg':
                    $cert_url = G5_LGXPAY_URL.'/AuthOnlyReq.php';
                    $cert_type = 'lg-hp';
                    break;
                default:
                    echo 'alert("기본환경설정에서 휴대폰 본인확인 설정을 해주십시오");';
                    echo 'return false;';
                    break;
            }
            ?>

            certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
            return;
        });
        <?php } ?>
    });

    // submit 최종 폼체크
    function fregisterform_submit(f)
    {
        // 회원아이디 검사
        if (f.w.value == "") {
            var msg = reg_mb_id_check();
            if (msg) {
                alert(msg);
                f.mb_id.select();
                return false;
            }
        }

        if (f.w.value == "") {
            if (f.mb_password.value.length < 3) {
                alert("비밀번호를 3글자 이상 입력하십시오.");
                f.mb_password.focus();
                return false;
            }
        }

        if (f.mb_password.value != f.mb_password_re.value) {
            alert("비밀번호가 같지 않습니다.");
            f.mb_password_re.focus();
            return false;
        }

        if (f.mb_password.value.length > 0) {
            if (f.mb_password_re.value.length < 3) {
                alert("비밀번호를 3글자 이상 입력하십시오.");
                f.mb_password_re.focus();
                return false;
            }
        }

        // 이름 검사
        if (f.w.value=="") {
            if (f.mb_name.value.length < 1) {
                alert("이름을 입력하십시오.");
                f.mb_name.focus();
                return false;
            }

            /*
            var pattern = /([^가-힣\x20])/i;
            if (pattern.test(f.mb_name.value)) {
                alert("이름은 한글로 입력하십시오.");
                f.mb_name.select();
                return false;
            }
            */
        }

        <?php if($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
        // 본인확인 체크
        if(f.cert_no.value=="") {
            alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
            return false;
        }
        <?php } ?>

        // 닉네임 검사
        if ((f.w.value == "") || (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {
            var msg = reg_mb_nick_check();
            if (msg) {
                alert(msg);
                f.reg_mb_nick.select();
                return false;
            }
        }

        // E-mail 검사
        if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
            var msg = reg_mb_email_check();
            if (msg) {
                alert(msg);
                f.reg_mb_email.select();
                return false;
            }
        }

        <?php if (($config['cf_use_hp'] || $config['cf_cert_hp']) && $config['cf_req_hp']) {  ?>
        // 휴대폰번호 체크
        var msg = reg_mb_hp_check();
        if (msg) {
            alert(msg);
            f.reg_mb_hp.select();
            return false;
        }
        <?php } ?>

        if (typeof f.mb_icon != "undefined") {
            if (f.mb_icon.value) {
                if (!f.mb_icon.value.toLowerCase().match(/.(gif)$/i)) {
                    alert("회원아이콘이 gif 파일이 아닙니다.");
                    f.mb_icon.focus();
                    return false;
                }
            }
        }

        if (typeof(f.mb_recommend) != "undefined" && f.mb_recommend.value) {
            if (f.mb_id.value == f.mb_recommend.value) {
                alert("본인을 추천할 수 없습니다.");
                f.mb_recommend.focus();
                return false;
            }

            var msg = reg_mb_recommend_check();
            if (msg) {
                alert(msg);
                f.mb_recommend.select();
                return false;
            }
        }

        <?php echo chk_captcha_js();  ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>

</div>
<!-- } 회원정보 입력/수정 끝 -->