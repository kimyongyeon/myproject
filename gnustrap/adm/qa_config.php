<?php
$sub_menu = "300500";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], 'r');

$token = get_token();

$g5['title'] = '1:1문의 설정';
include_once ('./admin.head.php');

// DB 테이블 생성
if(!sql_query(" DESCRIBE `{$g5['qa_config_table']}` ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['qa_config_table']}` (
                  `qa_title` varchar(255) NOT NULL DEFAULT'',
                  `qa_category` varchar(255) NOT NULL DEFAULT'',
                  `qa_skin` varchar(255) NOT NULL DEFAULT '',
                  `qa_mobile_skin` varchar(255) NOT NULL DEFAULT '',
                  `qa_use_email` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_req_email` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_use_hp` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_req_hp` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_use_sms` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_send_number` varchar(255) NOT NULL DEFAULT '',
                  `qa_admin_hp` varchar(255) NOT NULL DEFAULT '',
                  `qa_use_editor` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_subject_len` int(11) NOT NULL DEFAULT '0',
                  `qa_mobile_subject_len` int(11) NOT NULL DEFAULT '0',
                  `qa_page_rows` int(11) NOT NULL DEFAULT '0',
                  `qa_mobile_page_rows` int(11) NOT NULL DEFAULT '0',
                  `qa_image_width` int(11) NOT NULL DEFAULT '0',
                  `qa_upload_size` int(11) NOT NULL DEFAULT '0',
                  `qa_insert_content` text NOT NULL,
                  `qa_include_head` varchar(255) NOT NULL DEFAULT '',
                  `qa_include_tail` varchar(255) NOT NULL DEFAULT '',
                  `qa_content_head` text NOT NULL,
                  `qa_content_tail` text NOT NULL,
                  `qa_mobile_content_head` text NOT NULL,
                  `qa_mobile_content_tail` text NOT NULL,
                  `qa_1_subj` varchar(255) NOT NULL DEFAULT '',
                  `qa_2_subj` varchar(255) NOT NULL DEFAULT '',
                  `qa_3_subj` varchar(255) NOT NULL DEFAULT '',
                  `qa_4_subj` varchar(255) NOT NULL DEFAULT '',
                  `qa_5_subj` varchar(255) NOT NULL DEFAULT '',
                  `qa_1` varchar(255) NOT NULL DEFAULT '',
                  `qa_2` varchar(255) NOT NULL DEFAULT '',
                  `qa_3` varchar(255) NOT NULL DEFAULT '',
                  `qa_4` varchar(255) NOT NULL DEFAULT '',
                  `qa_5` varchar(255) NOT NULL DEFAULT ''
                )", true);
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['qa_content_table']}` (
                  `qa_id` int(11) NOT NULL AUTO_INCREMENT,
                  `qa_num` int(11) NOT NULL DEFAULT '0',
                  `qa_parent` int(11) NOT NULL DEFAULT '0',
                  `qa_related` int(11) NOT NULL DEFAULT '0',
                  `mb_id` varchar(20) NOT NULL DEFAULT '',
                  `qa_name` varchar(255) NOT NULL DEFAULT '',
                  `qa_email` varchar(255) NOT NULL DEFAULT '',
                  `qa_hp` varchar(255) NOT NULL DEFAULT '',
                  `qa_type` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_category` varchar(255) NOT NULL DEFAULT '',
                  `qa_email_recv` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_sms_recv` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_html` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_subject` varchar(255) NOT NULL DEFAULT '',
                  `qa_content` text NOT NULL,
                  `qa_status` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_file1` varchar(255) NOT NULL DEFAULT '',
                  `qa_source1` varchar(255) NOT NULL DEFAULT '',
                  `qa_file2` varchar(255) NOT NULL DEFAULT '',
                  `qa_source2` varchar(255) NOT NULL DEFAULT '',
                  `qa_ip` varchar(255) NOT NULL DEFAULT '',
                  `qa_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                  `qa_1` varchar(255) NOT NULL DEFAULT '',
                  `qa_2` varchar(255) NOT NULL DEFAULT '',
                  `qa_3` varchar(255) NOT NULL DEFAULT '',
                  `qa_4` varchar(255) NOT NULL DEFAULT '',
                  `qa_5` varchar(255) NOT NULL DEFAULT '',
                  PRIMARY KEY (`qa_id`),
                  KEY `qa_num_parent` (`qa_num`,`qa_parent`)
                )", true);
}

$sql = " SHOW COLUMNS FROM `{$g5['qa_content_table']}` LIKE 'qa_content' ";
$row = sql_fetch($sql);
if(strpos($row['Type'], 'text') === false) {
    sql_query(" ALTER TABLE `{$g5['qa_content_table']}` CHANGE `qa_content` `qa_content` text NOT NULL ", true);
}

$qaconfig = get_qa_config();

if(empty($qaconfig)) {
    $sql = " insert into `{$g5['qa_config_table']}`
                ( qa_title, qa_category, qa_skin, qa_mobile_skin, qa_use_email, qa_req_email, qa_use_hp, qa_req_hp, qa_use_editor, qa_subject_len, qa_mobile_subject_len, qa_page_rows, qa_mobile_page_rows, qa_image_width, qa_upload_size, qa_insert_content )
              values
                ( '1:1문의', '회원|포인트', 'basic', 'basic', '1', '0', '1', '0', '1', '60', '30', '15', '15', '600', '1048576', '' ) ";
    sql_query($sql);

    $qaconfig = get_qa_config();
}

// 관리자 이메일필드 추가
if(!isset($qaconfig['qa_admin_email'])) {
    sql_query(" ALTER TABLE `{$g5['qa_config_table']}`
                    ADD `qa_admin_email` varchar(255) NOT NULL DEFAULT '' AFTER `qa_admin_hp` ", true);
}

// 상단 하단 설정 필드 추가
if(!isset($qaconfig['qa_include_head'])) {
    sql_query(" ALTER TABLE `{$g5['qa_config_table']}`
                    ADD `qa_include_head` varchar(255) NOT NULL DEFAULT '' AFTER `qa_insert_content`,
                    ADD `qa_include_tail` varchar(255) NOT NULL DEFAULT '' AFTER `qa_include_head`,
                    ADD `qa_content_head` text NOT NULL AFTER `qa_include_tail`,
                    ADD `qa_content_tail` text NOT NULL AFTER `qa_content_head`,
                    ADD `qa_mobile_content_head` text NOT NULL AFTER `qa_content_tail`,
                    ADD `qa_mobile_content_tail` text NOT NULL AFTER `qa_mobile_content_head` ", true);
}
?>

<form name="fqaconfigform" id="fqaconfigform" method="post" onsubmit="return fqaconfigform_submit(this);" autocomplete="off">
<input type="hidden" name="token" value="<?php echo $token ?>" id="token">

<section id="anc_cf_qa_config">
    <div class="well">
        <strong>1:1문의 설정</strong>
    </div>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>1:1문의 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <td><label for="qa_title">타이틀<strong class="sound_only">필수</strong></label>
                <input type="text" name="qa_title" value="<?php echo $qaconfig['qa_title'] ?>" id="qa_title" required class="required form-control" size="40">
                <a href="<?php echo G5_BBS_URL; ?>/qalist.php" class="btn btn-default" style="margin-top:5px;"><i class="fa fa-reply"></i> 1:1문의 바로가기</a>
            </td>
        </tr>
        <tr>
            <td><label for="qa_category">분류<strong class="sound_only">필수</strong></label>
                <?php echo help('분류와 분류 사이는 | 로 구분하세요. (예: 질문|답변) 첫자로 #은 입력하지 마세요. (예: #질문|#답변 [X])') ?>
                <input type="text" name="qa_category" value="<?php echo $qaconfig['qa_category'] ?>" id="qa_category" required class="required form-control" size="70">
            </td>
        </tr>
        <tr>
            <td><label for="qa_skin">스킨 디렉토리<strong class="sound_only">필수</strong></label>
                <?php echo get_skin_select('qa', 'qa_skin', 'qa_skin', $qaconfig['qa_skin'], 'required'); ?>
            </td>
        </tr>
        <tr>
            <td><label>이메일 입력</label><br />
			<label class="btn btn-default <?php echo $qaconfig['qa_use_email']?'active':''; ?>" for="qa_use_email">
                <input type="checkbox" name="qa_use_email" value="1" id="qa_use_email" <?php echo $qaconfig['qa_use_email']?'checked':''; ?>> <label for="qa_use_email">보이기</label>
			</label>
			<label class="btn btn-default <?php echo $qaconfig['qa_req_email']?'active':''; ?>" for="qa_req_email">
                <input type="checkbox" name="qa_req_email" value="1" id="qa_req_email" <?php echo $qaconfig['qa_req_email']?'checked':''; ?>> <label for="qa_req_email">필수입력</label>
			</label>
            </td>
        </tr>
        <tr>
            <td><label>휴대폰 입력</label><br />

			<label class="btn btn-default <?php echo $qaconfig['qa_use_hp']?'active':''; ?>" for="qa_use_hp">
                <input type="checkbox" name="qa_use_hp" value="1" id="qa_use_hp" <?php echo $qaconfig['qa_use_hp']?'checked':''; ?>> <label for="qa_use_hp">보이기</label>
			</label>
			<label class="btn btn-default <?php echo $qaconfig['qa_req_hp']?'active':''; ?>" for="qa_req_hp">
                <input type="checkbox" name="qa_req_hp" value="1" id="qa_req_hp" <?php echo $qaconfig['qa_req_hp']?'checked':''; ?>> <label for="qa_req_hp">필수입력</label>
			</label>
            </td>
        </tr>
        <tr>
            <td><label for="qa_use_sms">SMS 알림</label>
                <?php echo help('휴대폰 입력을 사용하실 경우 문의글 등록시 등록자가 답변등록시 SMS 알림 수신을 선택할 수 있도록 합니다.<br>SMS 알림을 사용하기 위해서는 기본환경설정 > <a href="'.G5_ADMIN_URL.'/config_form.php#anc_cf_sms" class="label label-info">SMS 설정</a>을 하셔야 합니다.') ?>
                <select name="qa_use_sms" id="qa_use_sms" class="form-control" style="margin-top:5px;">
                    <?php echo option_selected(0, $qaconfig['qa_use_sms'], '사용안함'); ?>
                    <?php echo option_selected(1, $qaconfig['qa_use_sms'], '사용함'); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="qa_send_number">SMS 발신번호</label>
                <?php echo help('SMS 알림 전송시 발신번호로 사용됩니다.'); ?>
                <input type="text" name="qa_send_number" value="<?php echo $qaconfig['qa_send_number'] ?>" id="qa_send_number" class="form-control"  size="30">
            </td>
        </tr>
        <tr>
            <td><label for="qa_admin_hp">관리자 휴대폰번호</label>
                <?php echo help('관리자 휴대폰번호를 입력하시면 문의글 등록시 등록하신 번호로 SMS 알림이 전송됩니다.<br>SMS 알림을 사용하지 않으시면 알림이 전송되지 않습니다.'); ?>
                <input type="text" name="qa_admin_hp" value="<?php echo $qaconfig['qa_admin_hp'] ?>" id="qa_admin_hp" class="form-control"  size="30">
            </td>
        </tr>
        <tr>
            <td><label for="qa_admin_email">관리자 이메일</label>
                <?php echo help('관리자 이메일을 입력하시면 문의글 등록시 등록하신 이메일로 알림이 전송됩니다.'); ?>
                <input type="text" name="qa_admin_email" value="<?php echo $qaconfig['qa_admin_email'] ?>" id="qa_admin_email" class="form-control"  size="50">
            </td>
        </tr>
        <tr>
            <td><label for="qa_use_editor">DHTML 에디터 사용</label>
                <?php echo help('글작성시 내용을 DHTML 에디터 기능으로 사용할 것인지 설정합니다. 스킨에 따라 적용되지 않을 수 있습니다.'); ?>
                <select name="qa_use_editor" id="qa_use_editor">
                    <?php echo option_selected(0, $qaconfig['qa_use_editor'], '사용안함'); ?>
                    <?php echo option_selected(1, $qaconfig['qa_use_editor'], '사용함'); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="qa_subject_len">제목 길이<strong class="sound_only"> 필수</strong></label>
                <?php echo help('목록에서의 제목 글자수') ?>
                <input type="text" name="qa_subject_len" value="<?php echo $qaconfig['qa_subject_len'] ?>" id="qa_subject_len" required class="required numeric form-control"  size="4">
            </td>
        </tr>
        <tr>
            <td><label for="qa_page_rows">페이지당 목록 수<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="qa_page_rows" value="<?php echo $qaconfig['qa_page_rows'] ?>" id="qa_page_rows" required class="required numeric form-control"  size="4">
            </td>
        </tr>
        <tr>
            <td><label for="qa_image_width">이미지 폭 크기<strong class="sound_only"> 필수</strong></label>
                <?php echo help('게시판에서 출력되는 이미지의 폭 크기') ?>
                <input type="text" name="qa_image_width" value="<?php echo $qaconfig['qa_image_width'] ?>" id="qa_image_width" required class="required numeric form-control"  size="4"> 픽셀
            </td>
        </tr>
        <tr>
            <td><label for="qa_upload_size">파일 업로드 용량<strong class="sound_only"> 필수</strong></label>
                <?php echo help('최대 '.ini_get("upload_max_filesize").' 이하 업로드 가능, 1 MB = 1,048,576 bytes') ?>
                업로드 파일 한개당 <input type="text" name="qa_upload_size" value="<?php echo $qaconfig['qa_upload_size'] ?>" id="qa_upload_size" required class="required numeric form-control"  size="10"> bytes 이하
            </td>
        </tr>
        <tr>
            <td><label for="qa_include_head">상단 파일 경로</label>
                <input type="text" name="qa_include_head" value="<?php echo $qaconfig['qa_include_head'] ?>" id="qa_include_head" class="form-control" size="50">
            </td>
        </tr>
        <tr>
            <td><label for="qa_include_tail">하단 파일 경로</label>
                <input type="text" name="qa_include_tail" value="<?php echo $qaconfig['qa_include_tail'] ?>" id="qa_include_tail" class="form-control" size="50">
            </td>
        </tr>
        <tr>
            <td><label for="qa_content_head">상단 내용</label>
                <?php echo editor_html("qa_content_head", get_text($qaconfig['qa_content_head'], 0)); ?>
			</td>
		</tr>
        <tr>
            <td><label for="qa_content_tail">하단 내용</label>
                <?php echo editor_html("qa_content_tail", get_text($qaconfig['qa_content_tail'], 0)); ?>
			</td>
		</tr>
        <tr>
            <td><label for="qa_insert_content">글쓰기 기본 내용</label>
                <textarea id="qa_insert_content" name="qa_insert_content" rows="5" class="form-control textarea"><?php echo $qaconfig['qa_insert_content'] ?></textarea>
			</td>
		</tr>
        <?php for ($i=1; $i<=5; $i++) { ?>
        <tr>
            <td>여분필드<?php echo $i ?>
                <input type="text" name="qa_<?php echo $i ?>_subj" id="qa_<?php echo $i ?>_subj" value="<?php echo get_text($qaconfig['qa_'.$i.'_subj']) ?>" class="form-control" placeholder="여분필드 <?php echo $i ?> 제목" style="margin-bottom: 5px;">
                <input type="text" name="qa_<?php echo $i ?>" value="<?php echo get_text($qaconfig['qa_'.$i]) ?>" id="qa_<?php echo $i ?>" class="form-control"placeholder="여분필드 <?php echo $i ?> 값">
            </td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
    </div>
</section>

<div class="center">
    <button type="submit" class="btn btn-primary" accesskey="s"><i class="fa fa-check-square-o"></i> 확인</button>
</div>

</form>

<script>
function fqaconfigform_submit(f)
{
    <?php echo get_editor_js("qa_content_head"); ?>
    <?php echo get_editor_js("qa_content_tail"); ?>

    f.action = "./qa_config_update.php";
    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>