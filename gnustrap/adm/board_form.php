<?php
$sub_menu = "300100";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], 'w');

$sql = " select count(*) as cnt from {$g5['group_table']} ";
$row = sql_fetch($sql);
if (!$row['cnt'])
    alert('게시판그룹이 한개 이상 생성되어야 합니다.', './boardgroup_form.php');

$html_title = '게시판';

if (!isset($board['bo_device'])) {
    // 게시판 사용 필드 추가
    // both : pc, mobile 둘다 사용
    // pc : pc 전용 사용
    // mobile : mobile 전용 사용
    // none : 사용 안함
    sql_query(" ALTER TABLE  `{$g5['board_table']}` ADD  `bo_device` ENUM(  'both',  'pc',  'mobile' ) NOT NULL DEFAULT  'both' AFTER  `bo_subject` ", false);
}

if (!isset($board['bo_mobile_skin'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_mobile_skin` VARCHAR(255) NOT NULL DEFAULT '' AFTER `bo_skin` ", false);
}

if (!isset($board['bo_gallery_width'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_gallery_width` INT NOT NULL AFTER `bo_gallery_cols`,  ADD `bo_gallery_height` INT NOT NULL DEFAULT '0' AFTER `bo_gallery_width`,  ADD `bo_mobile_gallery_width` INT NOT NULL DEFAULT '0' AFTER `bo_gallery_height`,  ADD `bo_mobile_gallery_height` INT NOT NULL DEFAULT '0' AFTER `bo_mobile_gallery_width` ", false);
}

if (!isset($board['bo_mobile_subject_len'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_mobile_subject_len` INT(11) NOT NULL DEFAULT '0' AFTER `bo_subject_len` ", false);
}

if (!isset($board['bo_mobile_page_rows'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_mobile_page_rows` INT(11) NOT NULL DEFAULT '0' AFTER `bo_page_rows` ", false);
}

if (!isset($board['bo_mobile_content_head'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_mobile_content_head` TEXT NOT NULL AFTER `bo_content_head`, ADD `bo_mobile_content_tail` TEXT NOT NULL AFTER `bo_content_tail`", false);
}

if (!isset($board['bo_use_cert'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_use_cert` ENUM('','cert','adult') NOT NULL DEFAULT '' AFTER `bo_use_email` ", false);
}

if (!isset($board['bo_use_sns'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_use_sns` TINYINT NOT NULL DEFAULT '0' AFTER `bo_use_cert` ", false);

    $result = sql_query(" select bo_table from `{$g5['board_table']}` ");
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        sql_query(" ALTER TABLE `{$g5['write_prefix']}{$row['bo_table']}`
                    ADD `wr_facebook_user` VARCHAR(255) NOT NULL DEFAULT '' AFTER `wr_ip`,
                    ADD `wr_twitter_user` VARCHAR(255) NOT NULL DEFAULT '' AFTER `wr_facebook_user` ", false);
    }
}

$sql = " SHOW COLUMNS FROM `{$g5['board_table']}` LIKE 'bo_use_cert' ";
$row = sql_fetch($sql);
if(strpos($row['Type'], 'hp-') === false) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` CHANGE `bo_use_cert` `bo_use_cert` ENUM('','cert','adult','hp-cert','hp-adult') NOT NULL DEFAULT '' ", false);
}

if (!isset($board['bo_use_list_file'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_use_list_file` TINYINT NOT NULL DEFAULT '0' AFTER `bo_use_list_view` ", false);

    $result = sql_query(" select bo_table from `{$g5['board_table']}` ");
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        sql_query(" ALTER TABLE `{$g5['write_prefix']}{$row['bo_table']}`
                    ADD `wr_file` TINYINT NOT NULL DEFAULT '0' AFTER `wr_datetime` ", false);
    }
}

if (!isset($board['bo_mobile_subject'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_mobile_subject` VARCHAR(255) NOT NULL DEFAULT '' AFTER `bo_subject` ", false);
}

$required = "";
$readonly = "";
if ($w == '') {

    $html_title .= ' 생성';

    $required = 'required';
    $required_valid = 'alnum_';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $board['bo_count_delete'] = 1;
    $board['bo_count_modify'] = 1;
    $board['bo_read_point'] = $config['cf_read_point'];
    $board['bo_write_point'] = $config['cf_write_point'];
    $board['bo_comment_point'] = $config['cf_comment_point'];
    $board['bo_download_point'] = $config['cf_download_point'];

    $board['bo_gallery_cols'] = 4;
    $board['bo_gallery_width'] = 174;
    $board['bo_gallery_height'] = 124;
    $board['bo_mobile_gallery_width'] = 125;
    $board['bo_mobile_gallery_height'] = 100;
    $board['bo_table_width'] = 100;
    $board['bo_page_rows'] = $config['cf_page_rows'];
    $board['bo_mobile_page_rows'] = $config['cf_page_rows'];
    $board['bo_subject_len'] = 60;
    $board['bo_mobile_subject_len'] = 30;
    $board['bo_new'] = 24;
    $board['bo_hot'] = 100;
    $board['bo_image_width'] = 600;
    $board['bo_upload_count'] = 2;
    $board['bo_upload_size'] = 1048576;
    $board['bo_reply_order'] = 1;
    $board['bo_use_search'] = 1;
    $board['bo_skin'] = 'basic';
    $board['bo_mobile_skin'] = 'basic';
    $board['gr_id'] = $gr_id;
    $board['bo_use_secret'] = 0;
    $board['bo_include_head'] = '_head.php';
    $board['bo_include_tail'] = '_tail.php';

} else if ($w == 'u') {

    $html_title .= ' 수정';

    if (!$board['bo_table'])
        alert('존재하지 않은 게시판 입니다.');

    if ($is_admin == 'group') {
        if ($member['mb_id'] != $group['gr_admin'])
            alert('그룹이 틀립니다.');
    }

    $readonly = 'readonly';

}

if ($is_admin != 'super') {
    $group = get_group($board['gr_id']);
    $is_admin = is_admin($member['mb_id']);
}

$g5['title'] = $html_title;
include_once ('./admin.head.php');

//$pg_anchor = ''; //서브메뉴는 제일 상단만 위치

$frm_submit = '<div class="text-center">
    <input type="submit" value="확인" class="btn btn-primary" accesskey="s">
    <a href="./board_list.php?'.$qstr.'" class="btn btn-default">목록</a>'.PHP_EOL;
if ($w == 'u') $frm_submit .= '<span class="visible-xs"><br /></span><a href="./board_copy.php?bo_table='.$bo_table.'" id="board_copy" target="win_board_copy" class="btn btn-default">게시판복사</a>
    <a href="'.G5_BBS_URL.'/board.php?bo_table='.$board['bo_table'].'" class="btn btn-default">게시판이동</a>
    <a href="./board_thumbnail_delete.php?bo_table='.$board['bo_table'].'&amp;'.$qstr.'" onclick="return delete_confirm2(\'게시판 썸네일 파일을 삭제하시겠습니까?\');" class="btn btn-default">썸네일 삭제</a>
    '.PHP_EOL;
$frm_submit .= '</div><hr>';
?>
<!--서브메뉴-->
<nav class="navbar navbar-default navbar-static-top" role="navigation">
<div class="navbar-header">
			<span class="navbar-brand visible-xs">QUICK</span>
<button type="button" class="navbar-toggle2 collapsed visible-xs animated flip" data-toggle="collapse" data-target=".navbar-ex8-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
</button>
        </div>
<div class="subbar-collapse navbar-ex8-collapse collapse" style="height: 1px;">
<ul class="nav navbar-nav">
    <li><a href="#anc_bo_basic">기본 설정</a></li>
    <li><a href="#anc_bo_auth">권한 설정</a></li>
    <li><a href="#anc_bo_function">기능 설정</a></li>
    <li><a href="#anc_bo_design">디자인/양식</a></li>
    <li><a href="#anc_bo_point">포인트 설정</a></li>
    <li><a href="#anc_bo_extra">여분필드</a></li>
</ul>
</div></nav>
<!--서브메뉴 끝-->

<form name="fboardform" id="fboardform" action="./board_form_update.php" onsubmit="return fboardform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">

<style>
#board_form tr {margin-bottom:15px; height:1px solid #ddd;}
</style>
<section id="anc_bo_basic">
    <div class="well">
        <strong>게시판 기본 설정</strong>
    </div>
		<div class="row">
		        <div class="form-group col-md-4">
                    <label for="bo_table">TABLE<?php echo $sound_only ?></label><br />
                    <input type="text" name="bo_table" value="<?php echo $board['bo_table'] ?>" id="bo_table" <?php echo $required ?> <?php echo $readonly ?> class="form-control <?php echo $reaonly ?> <?php echo $required ?> <?php echo $required_valid ?>" maxlength="20" placeholder="board01">
                    <?php if ($w == '') { ?>
                    영문자, 숫자, _ 만 가능 (공백없이 20자 이내)
                    <?php } else { ?>
                    <a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $board['bo_table'] ?>" class="btn btn-default" style="margin-top:5px;"><i class="fa fa-reply"></i> 게시판 바로가기</a>
                    <a href="./board_list.php" class="btn btn-default" style="margin-top:5px;"><i class="fa fa-list-alt"></i> 목록으로</a>
                    <?php } ?>
                </div>
		        <div class="form-group col-md-4">
                    <label for="gr_id">그룹<strong class="sound_only">필수</strong></label><br />
                    <?php echo get_group_select('gr_id', $board['gr_id'], 'required'); ?>
                    <?php if ($w=='u') { ?><a href="javascript:document.location.href='./board_list.php?sfl=a.gr_id&stx='+document.fboardform.gr_id.value;" class="btn btn-default" style="margin-top:5px;">동일그룹 게시판목록</a><?php } ?>
				</div>
		        <div class="form-group col-md-4">
                    <label for="bo_subject">게시판 제목<strong class="sound_only">필수</strong></label><br />
					</label>
                    <input type="text" name="bo_subject" value="<?php echo get_text($board['bo_subject']) ?>" id="bo_subject" required class="required form-control" size="80" maxlength="120" placeholder="게시판01">
                </div>
		</div>
		<hr>
		<div class="row">
		        <div class="form-group col-md-12">
                    <label for="bo_category_list">분류</label><br />
                    <input type="text" name="bo_category_list" value="<?php echo get_text($board['bo_category_list']) ?>" id="bo_category_list" class="form-control" size="70" placeholder="일반|갤러리|기타">
			    <label class="btn btn-default <?php echo $board['bo_use_category']?'active':''; ?>" for="bo_use_category" style="margin-top:5px;">
		        <input type="checkbox" name="bo_use_category" value="1" id="bo_use_category" <?php echo $board['bo_use_category']?'checked':''; ?> data-toggle="checkbox"> 사용
				</label>
				</div>
			<div class="col-md-12">
			<span class="glyphicon glyphicon-pushpin"></span> 분류<br />
			<?php echo help('분류와 분류 사이는 | 로 구분하세요. (예: 질문|답변) 첫자로 #은 입력하지 마세요. (예: #질문|#답변 [X])') ?><br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_category_list">
				<input type="checkbox" name="chk_grp_category_list" value="1" id="chk_grp_category_list" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_category_list">
				<input type="checkbox" name="chk_all_category_list" value="1" id="chk_all_category_list" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
		</div>
		<hr>

        <?php if ($w == 'u') { ?>
		<div class="row">
		        <div class="form-group col-md-12">
                    <label for="proc_count">카운트 조정</label><br />
                    <label class="btn btn-default" for="proc_count" style="margin-top:5px;">
				    <input type="checkbox" name="proc_count" value="1" id="proc_count" data-toggle="checkbox">
					체크</label>
                </div>
			<div class="col-md-12">
			<span class="glyphicon glyphicon-pushpin"></span> 카운트 조정<br />
			<?php echo help('현재 원글수 : '.number_format($board['bo_count_write']).', 현재 댓글수 : '.number_format($board['bo_count_comment'])."\n".'게시판 목록에서 글의 번호가 맞지 않을 경우에 체크하십시오.') ?>
			</div>
		</div>
		<hr>
        <?php } ?>
</section>

<?php echo $frm_submit; ?>


<section id="anc_bo_auth">
      <div class="well">
        <strong>게시판 권한 설정</strong>
      </div>
		<div class="row">
		        <div class="form-group col-md-6">
                    <label for="bo_admin">게시판 관리자</label><br />
                    <input type="text" name="bo_admin" value="<?php echo $board['bo_admin'] ?>" id="bo_admin" class="form-control" maxlength="20" placeholder="admin">
                </div>
		        <div class="form-group col-md-6">
                    <label for="bo_list_level">목록보기 권한</label><br />
                    <?php echo get_member_level_select('bo_list_level', 1, 10, $board['bo_list_level']) ?>
                </div>
			<div class="col-md-6">
			<span class="glyphicon glyphicon-pushpin"></span> 게시판 관리자<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_admin">
				<input type="checkbox" name="chk_grp_admin" value="1" id="chk_grp_admin" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_admin">
				<input type="checkbox" name="chk_all_admin" value="1" id="chk_all_admin" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-6">
			<span class="glyphicon glyphicon-pushpin"></span> 목록보기 권한<br />
			<?php echo help('권한 1은 비회원, 2 이상 회원입니다. 권한은 10 이 가장 높습니다.') ?><br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_list_level">
				<input type="checkbox" name="chk_grp_list_level" value="1" id="chk_grp_list_level" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_list_level">
				<input type="checkbox" name="chk_all_list_level" value="1" id="chk_all_list_level" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
		</div>
		<hr>
		<div class="row">
		        <div class="form-group col-md-3">
                    <label for="bo_read_level">글읽기 권한</label><br />
                    <?php echo get_member_level_select('bo_read_level', 1, 10, $board['bo_read_level']) ?>
                </div>
		        <div class="form-group col-md-3">
                    <label for="bo_write_level">글쓰기 권한</label><br />
                    <?php echo get_member_level_select('bo_write_level', 1, 10, $board['bo_write_level']) ?>
                </div>
		        <div class="form-group col-md-3">
                    <label for="bo_reply_level">글답변 권한</label><br />
                    <?php echo get_member_level_select('bo_reply_level', 1, 10, $board['bo_reply_level']) ?>
                </div>
		        <div class="form-group col-md-3">
                    <label for="bo_comment_level">댓글쓰기 권한</label><br />
                    <?php echo get_member_level_select('bo_comment_level', 1, 10, $board['bo_comment_level']) ?>
                </div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 글읽기 권한<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_read_level">
				<input type="checkbox" name="chk_grp_read_level" value="1" id="chk_grp_read_level" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_read_level">
				<input type="checkbox" name="chk_all_read_level" value="1" id="chk_all_read_level" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 글쓰기 권한<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_write_level">
				<input type="checkbox" name="chk_grp_write_level" value="1" id="chk_grp_write_level" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_write_level">
				<input type="checkbox" name="chk_all_write_level" value="1" id="chk_all_write_level" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 글답변 권한<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_reply_level">
				<input type="checkbox" name="chk_grp_reply_level" value="1" id="chk_grp_reply_level" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_reply_level">
				<input type="checkbox" name="chk_all_reply_level" value="1" id="chk_all_reply_level" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 댓글쓰기 권한<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_comment_level">
				<input type="checkbox" name="chk_grp_comment_level" value="1" id="chk_grp_comment_level" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_comment_level">
				<input type="checkbox" name="chk_all_comment_level" value="1" id="chk_all_comment_level" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
		</div>
		<hr>
		<div class="row">
		        <div class="form-group col-md-3">
                    <label for="bo_link_level">링크 권한</label><br />
                    <?php echo get_member_level_select('bo_link_level', 1, 10, $board['bo_link_level']) ?>
                </div>
		        <div class="form-group col-md-3">
                    <label for="bo_upload_level">업로드 권한</label><br />
                    <?php echo get_member_level_select('bo_upload_level', 1, 10, $board['bo_upload_level']) ?>
                </div>
		        <div class="form-group col-md-3">
                    <label for="bo_download_level">다운로드 권한</label><br />
                    <?php echo get_member_level_select('bo_download_level', 1, 10, $board['bo_download_level']) ?>
                </div>
		        <div class="form-group col-md-3">
                    <label for="bo_html_level">HTML 쓰기 권한</label><br />
                    <?php echo get_member_level_select('bo_html_level', 1, 10, $board['bo_html_level']) ?>
                </div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 링크 권한<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_link_level">
				<input type="checkbox" name="chk_grp_link_level" value="1" id="chk_grp_link_level" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_link_level">
				<input type="checkbox" name="chk_all_link_level" value="1" id="chk_all_link_level" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 업로드 권한<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_upload_level">
				<input type="checkbox" name="chk_grp_upload_level" value="1" id="chk_grp_upload_level" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_upload_level">
				<input type="checkbox" name="chk_all_upload_level" value="1" id="chk_all_upload_level" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 다운로드 권한<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_download_level">
				<input type="checkbox" name="chk_grp_download_level" value="1" id="chk_grp_download_level" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_download_level">
				<input type="checkbox" name="chk_all_download_level" value="1" id="chk_all_download_level" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> HTML 쓰기 권한<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_html_level">
				<input type="checkbox" name="chk_grp_html_level" value="1" id="chk_grp_html_level" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_html_level">
				<input type="checkbox" name="chk_all_html_level" value="1" id="chk_all_html_level" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
		</div>
		<hr>
</section>

<?php echo $frm_submit; ?>


<section id="anc_bo_function">
      <div class="well">
        <strong>게시판 기능 설정</strong>
      </div>
		<div class="row">
			<div class="col-md-12">
                    <label for="bo_count_modify">원글 수정 불가<strong class="sound_only">필수</strong></label>
			</div>
		        <div class="form-group col-md-10">
					<div class="input-group">
                    <input type="text" name="bo_count_modify" value="<?php echo $board['bo_count_modify'] ?>" id="bo_count_modify" required class="required numeric form-control" size="3">
					<span class="input-group-addon">개 이상시 댓글수정불가</span>
					</div>
                </div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_count_modify">
				<input type="checkbox" name="chk_grp_count_modify" value="1" id="chk_grp_count_modify" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_count_modify">
				<input type="checkbox" name="chk_all_count_modify" value="1" id="chk_all_count_modify" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-12">
			<?php echo help('댓글의 수가 설정 수 이상이면 원글을 수정할 수 없습니다. 0으로 설정하시면 댓글 수에 관계없이 수정할 수있습니다.') ?>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
                    <label for="bo_count_delete">원글 삭제 불가<strong class="sound_only">필수</strong></label>
			</div>
		        <div class="form-group col-md-10">
					<div class="input-group">
                    <input type="text" name="bo_count_delete" value="<?php echo $board['bo_count_delete'] ?>" id="bo_count_delete" required class="required numeric form-control" size="3">
					<span class="input-group-addon">개 이상시 댓글삭제불가</span>
					</div>
                </div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_count_delete">
				<input type="checkbox" name="chk_grp_count_delete" value="1" id="chk_grp_count_delete" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_count_delete">
				<input type="checkbox" name="chk_all_count_delete" value="1" id="chk_all_count_delete" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-12">
			<?php echo help('댓글의 수가 설정 수 이상이면 원글을 수정할 수 없습니다. 0으로 설정하시면 댓글 수에 관계없이 수정할 수있습니다.') ?>
			</div>
		</div>
		<hr>

		<div class="row">
			<div class="col-md-12">
                    <label for="bo_use_sideview">글쓴이 사이드뷰</label> 
			</div>
		        <div class="form-group col-md-10">
					<label class="btn btn-default <?php echo $board['bo_use_sideview']?'active':''; ?>" for="bo_use_sideview">
				    <input type="checkbox" name="bo_use_sideview" value="1" id="bo_use_sideview" <?php echo $board['bo_use_sideview']?'checked':''; ?> data-toggle="checkbox"> 사용</label>
                </div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_sideview">
				<input type="checkbox" name="chk_grp_use_sideview" value="1" id="chk_grp_use_sideview" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_sideview">
				<input type="checkbox" name="chk_all_use_sideview" value="1" id="chk_all_use_sideview" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-12">
			<?php echo help('글쓴이 클릭시 나오는 레이어 메뉴') ?>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
                    <label for="bo_use_secret">비밀글 사용</label>
			</div>
		        <div class="form-group col-md-10">
                    <select id="bo_use_secret" name="bo_use_secret" class="form-control">
                    <?php echo option_selected(0, $board['bo_use_secret'], "사용하지 않음"); ?>
                    <?php echo option_selected(1, $board['bo_use_secret'], "체크박스"); ?>
                    <?php echo option_selected(2, $board['bo_use_secret'], "무조건"); ?>
                    </select>
                </div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_secret">
				<input type="checkbox" name="chk_grp_use_secret" value="1" id="chk_grp_use_secret" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_secret">
				<input type="checkbox" name="chk_all_use_secret" value="1" id="chk_all_use_secret" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-12">
            <?php echo help('"체크박스"는 글작성시 비밀글 체크가 가능합니다. "무조건"은 작성되는 모든글을 비밀글로 작성합니다. (관리자는 체크박스로 출력합니다.) 스킨에 따라 적용되지 않을 수 있습니다.') ?>
			</div>
		</div>
		<hr>

		<div class="row">
			<div class="col-md-12">
                    <label for="bo_use_dhtml_editor">DHTML 에디터 사용</label> 
			</div>
		        <div class="form-group col-md-10">
                <label class="btn btn-default <?php echo $board['bo_use_dhtml_editor']?'active':''; ?>" for="bo_use_dhtml_editor" style="margin-top:5px;">
				<input type="checkbox" name="bo_use_dhtml_editor" value="1" <?php echo $board['bo_use_dhtml_editor']?'checked':''; ?> id="bo_use_dhtml_editor" data-toggle="checkbox">
                사용</label>
                </div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_dhtml_editor">
				<input type="checkbox" name="chk_grp_use_dhtml_editor" value="1" id="chk_grp_use_dhtml_editor" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_dhtml_editor">
				<input type="checkbox" name="chk_all_use_dhtml_editor" value="1" id="chk_all_use_dhtml_editor" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-12">
			<?php echo help('글작성시 내용을 DHTML 에디터 기능으로 사용할 것인지 설정합니다. 스킨에 따라 적용되지 않을 수 있습니다.') ?>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
                    <label for="bo_use_rss_view">RSS 보이기 사용</label> 
			</div>
		        <div class="form-group col-md-10">
                <label class="btn btn-default <?php echo $board['bo_use_rss_view']?'active':''; ?>" for="bo_use_rss_view" style="margin-top:5px;">
				<input type="checkbox" name="bo_use_rss_view" value="1" <?php echo $board['bo_use_rss_view']?'checked':''; ?> id="bo_use_rss_view" data-toggle="checkbox">
                사용</label>
                </div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_rss_view">
				<input type="checkbox" name="chk_grp_use_rss_view" value="1" id="chk_grp_use_rss_view" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_rss_view">
				<input type="checkbox" name="chk_all_use_rss_view" value="1" id="chk_all_use_rss_view" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-12">
			<?php echo help('비회원 글읽기가 가능하고 RSS 보이기 사용에 체크가 되어야만 RSS 지원을 합니다.') ?>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
                    <label for="bo_use_good">추천 사용</label> 
			</div>
		        <div class="form-group col-md-10">
                <label class="btn btn-default <?php echo $board['bo_use_good']?'active':''; ?>" for="bo_use_good" style="margin-top:5px;">
				<input type="checkbox" name="bo_use_good" value="1" <?php echo $board['bo_use_good']?'checked':''; ?> id="bo_use_good" data-toggle="checkbox">
                사용</label>
                </div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_good">
				<input type="checkbox" name="chk_grp_use_good" value="1" id="chk_grp_use_good" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_good">
				<input type="checkbox" name="chk_all_use_good" value="1" id="chk_all_use_good" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
                    <label for="bo_use_nogood">비추천 사용</label>
			</div>
		        <div class="form-group col-md-10">
                <label class="btn btn-default <?php echo $board['bo_use_nogood']?'active':''; ?>" for="bo_use_nogood" style="margin-top:5px;">
				<input type="checkbox" name="bo_use_nogood" value="1" id="bo_use_nogood" <?php echo $board['bo_use_nogood']?'checked':''; ?> data-toggle="checkbox">
                사용</label>
                </div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_nogood" data-toggle="checkbox">
				<input type="checkbox" name="chk_grp_use_nogood" value="1" id="chk_grp_use_nogood">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_nogood" data-toggle="checkbox">
				<input type="checkbox" name="chk_all_use_nogood" value="1" id="chk_all_use_nogood">
                전체적용</label>
            </div>
			</div>
		</div>
		<hr>

		<div class="row">
			<div class="col-md-12">
                    <label for="bo_use_name">이름(실명) 사용</label>
			</div>
		        <div class="form-group col-md-10">
                <label class="btn btn-default <?php echo $board['bo_use_name']?'active':''; ?>" for="bo_use_name" style="margin-top:5px;">
				<input type="checkbox" name="bo_use_name" value="1" id="bo_use_name" <?php echo $board['bo_use_name']?'checked':''; ?> data-toggle="checkbox">
                사용</label>
                </div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_name">
				<input type="checkbox" name="chk_grp_use_name" value="1" id="chk_grp_use_name" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_name">
				<input type="checkbox" name="chk_all_use_name" value="1" id="chk_all_use_name" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
                    <label for="bo_use_signature">서명보이기 사용</label>
			</div>
		        <div class="form-group col-md-10">
				<label class="btn btn-default <?php echo $board['bo_use_signature']?'active':''; ?>" for="bo_use_signature" style="margin-top:5px;">
				<input type="checkbox" name="bo_use_signature" value="1" id="bo_use_signature" <?php echo $board['bo_use_signature']?'checked':''; ?> data-toggle="checkbox">
                사용</label>
                </div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_signature">
				<input type="checkbox" name="chk_grp_use_signature" value="1" id="chk_grp_use_signature" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_signature">
				<input type="checkbox" name="chk_all_use_signature" value="1" id="chk_all_use_signature" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
                    <label for="bo_use_ip_view">IP 보이기 사용</label>
			</div>
		        <div class="form-group col-md-10">
                <label class="btn btn-default <?php echo $board['bo_use_ip_view']?'active':''; ?>" for="bo_use_ip_view" style="margin-top:5px;">
				<input type="checkbox" name="bo_use_ip_view" value="1" id="bo_use_ip_view" <?php echo $board['bo_use_ip_view']?'checked':''; ?>>
                사용</label>
                </div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_ip_view">
				<input type="checkbox" name="chk_grp_use_ip_view" value="1" id="chk_grp_use_ip_view" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_ip_view">
				<input type="checkbox" name="chk_all_use_ip_view" value="1" id="chk_all_use_ip_view" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
		</div>
		<hr>

		<div class="row">
			<div class="col-md-12">
                    <label for="bo_use_list_content">목록에서 내용 사용</label>
			</div>
		        <div class="form-group col-md-10">
                <label class="btn btn-default <?php echo $board['bo_use_list_content']?'active':''; ?>" for="bo_use_list_content" style="margin-top:5px;">
				<input type="checkbox" name="bo_use_list_content" value="1" id="bo_use_list_content" <?php echo $board['bo_use_list_content']?'checked':''; ?> data-toggle="checkbox">
                사용</label>
                </div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_list_content">
				<input type="checkbox" name="chk_grp_use_list_content" value="1" id="chk_grp_use_list_content" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_list_content">
				<input type="checkbox" name="chk_all_use_list_content" value="1" id="chk_all_use_list_content" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-12">
			<?php echo help("목록에서 게시판 제목외에 내용도 읽어와야 할 경우에 설정하는 옵션입니다. 기본은 사용하지 않습니다."); ?><br /><br />
			<div class="alert alert-danger">(사용시 속도가 느려질 수 있습니다.)</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
                    <label for="bo_use_list_file">목록에서 파일 사용</label>
			</div>
		        <div class="form-group col-md-10">
				<label class="btn btn-default <?php echo $board['bo_use_list_file']?'active':''; ?>" for="bo_use_list_file" style="margin-top:5px;">
				<input type="checkbox" name="bo_use_list_file" value="1" id="bo_use_list_file" <?php echo $board['bo_use_list_file']?'checked':''; ?> data-toggle="checkbox">
                사용</label>
                </div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_list_file">
				<input type="checkbox" name="chk_grp_use_list_file" value="1" id="chk_grp_use_list_file" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_list_file">
				<input type="checkbox" name="chk_all_use_list_file" value="1" id="chk_all_use_list_file" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-12">
			<?php echo help("목록에서 게시판 첨부파일을 읽어와야 할 경우에 설정하는 옵션입니다. 기본은 사용하지 않습니다."); ?><br /><br />
			<div class="alert alert-danger">(사용시 속도가 느려질 수 있습니다.)</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
                    <label for="bo_use_list_view">전체목록보이기 사용</label>
			</div>
		        <div class="form-group col-md-10">
			    <label class="btn btn-default <?php echo $board['bo_use_list_view']?'active':''; ?>" for="bo_use_list_view" style="margin-top:5px;">
				<input type="checkbox" name="bo_use_list_view" value="1" id="bo_use_list_view" <?php echo $board['bo_use_list_view']?'checked':''; ?> data-toggle="checkbox">
                사용</label>
                </div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_list_view">
				<input type="checkbox" name="chk_grp_use_list_view" value="1" id="chk_grp_use_list_view" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_list_view">
				<input type="checkbox" name="chk_all_use_list_view" value="1" id="chk_all_use_list_view" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
                    <label for="bo_use_email">메일발송 사용</label>
			</div>
		        <div class="form-group col-md-10">
			    <label class="btn btn-default <?php echo $board['bo_use_email']?'active':''; ?>" for="bo_use_email" style="margin-top:5px;">
				<input type="checkbox" name="bo_use_email" value="1" id="bo_use_email" <?php echo $board['bo_use_email']?'checked':''; ?> data-toggle="checkbox">
                사용</label>
                </div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_email" data-toggle="checkbox">
				<input type="checkbox" name="chk_grp_use_email" value="1" id="chk_grp_use_email">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_email" data-toggle="checkbox">
				<input type="checkbox" name="chk_all_use_email" value="1" id="chk_all_use_email">
                전체적용</label>
            </div>
			</div>
		</div>
		<hr>

		<div class="row">
			<div class="col-md-12">
				<label for="bo_use_cert">본인확인 사용</label>
			</div>
		        <div class="form-group col-md-10">
                <select id="bo_use_cert" name="bo_use_cert" class="form-control">
                    <?php
                    echo option_selected("",  $board['bo_use_cert'], "사용안함");
                    if ($config['cf_cert_use']) {
                        echo option_selected("cert",  $board['bo_use_cert'], "본인확인된 회원전체");
                        echo option_selected("adult", $board['bo_use_cert'], "본인확인된 성인회원만");
                        echo option_selected("hp-cert",  $board['bo_use_cert'], "휴대폰 본인확인된 회원전체");
                        echo option_selected("hp-adult", $board['bo_use_cert'], "휴대폰 본인확인된 성인회원만");
                    }
                    ?>
                </select>
				</div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_cert">
				<input type="checkbox" name="chk_grp_use_cert" value="1" id="chk_grp_use_cert" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_cert">
				<input type="checkbox" name="chk_all_use_cert" value="1" id="chk_all_use_cert" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-12">
			<?php echo help("본인확인 여부에 따라 게시물을 조회 할 수 있도록 합니다."); ?>
			</div>
		</div>
		<hr>

		<div class="row">
			<div class="col-md-12">
				<label for="bo_upload_count">파일 업로드 개수<strong class="sound_only">필수</strong></label>
			</div>
		        <div class="form-group col-md-10">
                <input type="text" name="bo_upload_count" value="<?php echo $board['bo_upload_count'] ?>" id="bo_upload_count" required class="required numeric form-control" size="4">
				</div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_upload_count">
				<input type="checkbox" name="chk_grp_upload_count" value="1" id="chk_grp_upload_count" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_upload_count">
				<input type="checkbox" name="chk_all_upload_count" value="1" id="chk_all_upload_count" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-12">
			<?php echo help('게시물 한건당 업로드 할 수 있는 파일의 최대 개수 (0 은 파일첨부 사용하지 않음)') ?>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
				<label for="bo_upload_size">파일 업로드 용량<strong class="sound_only">필수</strong></label>
			</div>
		        <div class="form-group col-md-10">
				<div class="input-group">
                <input type="text" name="bo_upload_size" value="<?php echo $board['bo_upload_size'] ?>" id="bo_upload_size" required class="required numeric form-control" size="10">
				<span class="input-group-addon">bytes 이하</span>
				</div>
				</div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_upload_size">
				<input type="checkbox" name="chk_grp_upload_size" value="1" id="chk_grp_upload_size" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_upload_size">
				<input type="checkbox" name="chk_all_upload_size" value="1" id="chk_all_upload_size" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-12">
			<?php echo help('최대 '.ini_get("upload_max_filesize").' 이하 업로드 가능, 1 MB = 1,048,576 bytes') ?>
			</div>
		</div>
		<hr>
		<div class="row">
		        <div class="form-group col-md-10">
				<label for="bo_use_file_content">파일 설명 사용</label><br />
                <label class="btn btn-default <?php echo $board['bo_use_file_content']?'active':''; ?>" for="bo_use_file_content" style="margin-top:5px;">
				<input type="checkbox" name="bo_use_file_content" value="1" id="bo_use_file_content" <?php echo $board['bo_use_file_content']?'checked':''; ?> data-toggle="checkbox">
                사용</label>
				</div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_file_content">
				<input type="checkbox" name="chk_grp_use_file_content" value="1" id="chk_grp_use_file_content" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_file_content">
				<input type="checkbox" name="chk_all_use_file_content" value="1" id="chk_all_use_file_content" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
		</div>
		<hr>

		<div class="row">
		        <div class="form-group col-md-3">
				<label for="bo_write_min">최소 글수 제한</label><br />
				<input type="text" name="bo_write_min" value="<?php echo $board['bo_write_min'] ?>" id="bo_write_min" class="numeric form-control" size="4">
				</div>
		        <div class="form-group col-md-3">
				<label for="bo_write_max">최대 글수 제한</label><br />
				<input type="text" name="bo_write_max" value="<?php echo $board['bo_write_max'] ?>" id="bo_write_max" class="numeric form-control" size="4">
				</div>
		        <div class="form-group col-md-3">
				<label for="bo_comment_min">최소 댓글수 제한</label><br />
				<input type="text" name="bo_comment_min" value="<?php echo $board['bo_comment_min'] ?>" id="bo_comment_min" class="numeric form-control" size="4">
				</div>
		        <div class="form-group col-md-3">
				<label for="bo_comment_max">최대 댓글수 제한</label><br />
				<input type="text" name="bo_comment_max" value="<?php echo $board['bo_comment_max'] ?>" id="bo_comment_max" class="numeric form-control" size="4">
				</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 최소 글수 제한<br />
			<?php echo help('글 입력시 최소 글자수를 설정. 0을 입력하거나 최고관리자, DHTML 에디터 사용시에는 검사하지 않음') ?><br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_write_min">
				<input type="checkbox" name="chk_grp_write_min" value="1" id="chk_grp_write_min" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_write_min">
				<input type="checkbox" name="chk_all_write_min" value="1" id="chk_all_write_min" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 최대 글수 제한<br />
			<?php echo help('글 입력시 최대 글자수를 설정. 0을 입력하거나 최고관리자, DHTML 에디터 사용시에는 검사하지 않음') ?><br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_write_max">
				<input type="checkbox" name="chk_grp_write_max" value="1" id="chk_grp_write_max" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_write_max">
				<input type="checkbox" name="chk_all_write_max" value="1" id="chk_all_write_max" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 최소 댓글수 제한<br />
			<?php echo help('댓글 입력시 최소 글자수를 설정. 0을 입력하면 검사하지 않음') ?><br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_comment_min">
				<input type="checkbox" name="chk_grp_comment_min" value="1" id="chk_grp_comment_min" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_comment_min">
				<input type="checkbox" name="chk_all_comment_min" value="1" id="chk_all_comment_min" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 최대 댓글수 제한<br />
			<?php echo help('댓글 입력시 최대 글자수를 설정. 0을 입력하면 검사하지 않음') ?><br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_comment_max">
				<input type="checkbox" name="chk_grp_comment_max" value="1" id="chk_grp_comment_max" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_comment_max">
				<input type="checkbox" name="chk_all_comment_max" value="1" id="chk_all_comment_max" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
		</div>
		<hr>

		<div class="row">
			<div class="col-md-12">
				<label for="bo_use_sns">SNS 사용</label>
			</div>
		        <div class="form-group col-md-10">
				<input type="text" name="bo_write_min" value="<?php echo $board['bo_write_min'] ?>" id="bo_write_min" class="numeric form-control" size="4">
				</div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_sns">
				<input type="checkbox" name="chk_grp_use_sns" value="1" id="chk_grp_use_sns" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_sns">
				<input type="checkbox" name="chk_all_use_sns" value="1" id="chk_all_use_sns" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-12">
			<?php echo help("사용에 체크하시면 소셜네트워크서비스(SNS)에 글을 퍼가거나 댓글을 동시에 등록할수 있습니다.") ?><br /><br />
			<div class="alert alert-danger">기본환경설정의 SNS 설정을 하셔야 사용이 가능합니다.</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
				<label for="bo_use_search">전체 검색 사용</label> 
			</div>
		        <div class="form-group col-md-10">
			    <label class="btn btn-default <?php echo $board['bo_use_search']?'active':''; ?>" for="bo_use_search" style="margin-top:5px;">
				<input type="checkbox" name="bo_use_search" value="1" id="bo_use_search" <?php echo $board['bo_use_search']?'checked':''; ?> data-toggle="checkbox">
                사용</label>
				</div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_use_search">
				<input type="checkbox" name="chk_grp_use_search" value="1" id="chk_grp_use_search" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_use_search">
				<input type="checkbox" name="chk_all_use_search" value="1" id="chk_all_use_search" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-12">
			<?php echo help('글 입력시 최대 글자수를 설정. 0을 입력하거나 최고관리자, DHTML 에디터 사용시에는 검사하지 않음') ?>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
				<label for="bo_order">출력 순서</label>
			</div>
		        <div class="form-group col-md-10">
				<input type="text" name="bo_order" value="<?php echo $board['bo_order'] ?>" id="bo_order" class="form-control" size="4">
				</div>
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_order">
				<input type="checkbox" name="chk_grp_order" value="1" id="chk_grp_order" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_order">
				<input type="checkbox" name="chk_all_order" value="1" id="chk_all_order" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-12">
			<?php echo help('숫자가 낮은 게시판 부터 메뉴나 검색시 우선 출력합니다.') ?>
			</div>
		</div>
		<hr>
</section>

<?php echo $frm_submit; ?>


<section id="anc_bo_design">
      <div class="well">
        <strong>게시판 디자인/양식</strong>
      </div>
	  
	  <div class="row">
		        <div class="form-group col-md-4">
				<label for="bo_skin">스킨 디렉토리<strong class="sound_only">필수</strong></label><br />
				<?php echo get_skin_select('board', 'bo_skin', 'bo_skin', $board['bo_skin'], 'required'); ?>
				</div>
		        <div class="form-group col-md-4">
				<label for="bo_include_head">상단 파일 경로</label><br />
				<input type="text" name="bo_include_head" value="<?php echo $board['bo_include_head'] ?>" id="bo_include_head" class="form-control" size="50">
				</div>
		        <div class="form-group col-md-4">
				<label for="bo_include_tail">하단 파일 경로</label><br />
				<input type="text" name="bo_include_tail" value="<?php echo $board['bo_include_tail'] ?>" id="bo_include_tail" class="form-control" size="50">
				</div>

			<div class="col-md-4">
			<span class="glyphicon glyphicon-pushpin"></span> 스킨 디렉토리<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_skin">
				<input type="checkbox" name="chk_grp_skin" value="1" id="chk_grp_skin" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_skin">
				<input type="checkbox" name="chk_all_skin" value="1" id="chk_all_skin" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-4">
			<span class="glyphicon glyphicon-pushpin"></span> 상단 파일 경로<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_include_head">
				<input type="checkbox" name="chk_grp_include_head" value="1" id="chk_grp_include_head" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_include_head">
				<input type="checkbox" name="chk_all_include_head" value="1" id="chk_all_include_head" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-4">
			<span class="glyphicon glyphicon-pushpin"></span> 하단 파일 경로<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_include_tail">
				<input type="checkbox" name="chk_grp_include_tail" value="1" id="chk_grp_include_tail" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_include_tail">
				<input type="checkbox" name="chk_all_include_tail" value="1" id="chk_all_include_tail" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
	   </div>
	   <hr>

	  <div class="row">
		        <div class="form-group col-md-12">
			    <div class="alert alert-success"><label for="bo_content_head">상단 내용</label></div>
                <?php echo editor_html("bo_content_head", get_text($board['bo_content_head'], 0)); ?>
				</div>
			    <div class="col-md-12">
				<div class="btn-group btn-group">
                <h3>
				<label class="btn btn-default" for="chk_grp_content_head">
				<input type="checkbox" name="chk_grp_content_head" value="1" id="chk_grp_content_head" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_content_head">
				<input type="checkbox" name="chk_all_content_head" value="1" id="chk_all_content_head" data-toggle="checkbox">
                전체적용</label>
				</h3>
				</div>
				</div>
	  </div>
	  <hr />
	  <div class="row">
		        <div class="form-group col-md-12">
			    <div class="alert alert-success"><label for="bo_content_tail">하단 내용</label></div>
                <?php echo editor_html("bo_content_tail", get_text($board['bo_content_tail'], 0)); ?>
				</div>
			    <div class="col-md-12">
			    <div class="btn-group btn-group">
                <h3>
                <label class="btn btn-default" for="chk_grp_content_tail">
				<input type="checkbox" name="chk_grp_content_tail" value="1" id="chk_grp_content_tail" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_content_tail">
				<input type="checkbox" name="chk_all_content_tail" value="1" id="chk_all_content_tail" data-toggle="checkbox">
                전체적용</label>
				</h3>
				</div>
				</div>
	  </div>
	  <hr />
	  <div class="row">
		        <div class="form-group col-md-12">
			    <div class="alert alert-success"><label for="bo_insert_content">글쓰기 기본 내용</label></div>
                <textarea id="bo_insert_content" name="bo_insert_content" rows="5" class="form-control textarea" style="width:100%;"><?php echo $board['bo_insert_content'] ?></textarea>
				</div>
			    <div class="col-md-12">
				<div class="btn-group btn-group">
                <h3>
                <label class="btn btn-default" for="chk_grp_insert_content">
				<input type="checkbox" name="chk_grp_insert_content" value="1" id="chk_grp_insert_content" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_insert_content">
				<input type="checkbox" name="chk_all_insert_content" value="1" id="chk_all_insert_content" data-toggle="checkbox">
                전체적용</label>
				</h3>
				</div>
				</div>
	  </div>
	  <hr />
	  <div class="row">
		        <div class="form-group col-md-6">
				<label for="bo_subject_len">제목 길이<strong class="sound_only">필수</strong></label><br />
				<input type="text" name="bo_subject_len" value="<?php echo $board['bo_subject_len'] ?>" id="bo_subject_len" required class="required numeric form-control" size="4">
				</div>
		        <div class="form-group col-md-6">
				<label for="bo_page_rows">페이지당 목록 수<strong class="sound_only">필수</strong></label><br />
				<input type="text" name="bo_page_rows" value="<?php echo $board['bo_page_rows'] ?>" id="bo_page_rows" required class="required numeric form-control" size="4">
				</div>
			<div class="col-md-6">
			<span class="glyphicon glyphicon-pushpin"></span> 제목 길이<br />
			<?php echo help('목록에서의 제목 글자수. 잘리는 글은 … 로 표시') ?><br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_subject_len">
				<input type="checkbox" name="chk_grp_subject_len" value="1" id="chk_grp_subject_len" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_subject_len">
				<input type="checkbox" name="chk_all_subject_len" value="1" id="chk_all_subject_len" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-4">
			<span class="glyphicon glyphicon-pushpin"></span> 페이지당 목록 수<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_page_rows">
				<input type="checkbox" name="chk_grp_page_rows" value="1" id="chk_grp_page_rows" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_page_rows">
				<input type="checkbox" name="chk_all_page_rows" value="1" id="chk_all_page_rows" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
	  </div>
	  <hr>
	  
	  <div class="row">
		        <div class="form-group col-md-12">
				<label for="bo_gallery_cols">갤러리 이미지 수<strong class="sound_only">필수</strong></label><br />
				<input type="text" name="bo_gallery_cols" value="<?php echo $board['bo_gallery_cols'] ?>" id="bo_gallery_cols" required class="required numeric form-control" size="4">
				</div>
			<div class="col-md-12">
			<span class="glyphicon glyphicon-pushpin"></span> 갤러리 이미지 수<br />
			<?php echo help('갤러리 형식의 게시판 목록에서 이미지를 한줄에 몇장씩 보여 줄 것인지를 설정하는 값') ?><br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_gallery_cols">
				<input type="checkbox" name="chk_grp_gallery_cols" value="1" id="chk_grp_gallery_cols" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_gallery_cols">
				<input type="checkbox" name="chk_all_gallery_cols" value="1" id="chk_all_gallery_cols" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
	  </div>
	  <hr>
	  
	  <div class="row">
		        <div class="form-group col-md-6">
				<label for="bo_gallery_width">갤러리 이미지 폭<strong class="sound_only">필수</strong></label><br />
				<input type="text" name="bo_gallery_width" value="<?php echo $board['bo_gallery_width'] ?>" id="bo_gallery_width" required class="required numeric form-control" size="4">
				</div>
		        <div class="form-group col-md-6">
				<label for="bo_gallery_height">갤러리 이미지 높이<strong class="sound_only">필수</strong></label><br />
				<input type="text" name="bo_gallery_height" value="<?php echo $board['bo_gallery_height'] ?>" id="bo_gallery_height" required class="required numeric form-control" size="4">
				</div>
			<div class="col-md-6">
			<span class="glyphicon glyphicon-pushpin"></span> 갤러리 이미지 폭<br />
			<?php echo help('갤러리 형식의 게시판 목록에서 썸네일 이미지의 폭을 설정하는 값') ?><br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_gallery_width">
				<input type="checkbox" name="chk_grp_gallery_width" value="1" id="chk_grp_gallery_width" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_gallery_width">
				<input type="checkbox" name="chk_all_gallery_width" value="1" id="chk_all_gallery_width" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-6">
			<span class="glyphicon glyphicon-pushpin"></span> 갤러리 이미지 높이<br />
			<?php echo help('갤러리 형식의 게시판 목록에서 썸네일 이미지의 높이를 설정하는 값') ?><br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_gallery_height">
				<input type="checkbox" name="chk_grp_gallery_height" value="1" id="chk_grp_gallery_height" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_gallery_height">
				<input type="checkbox" name="chk_all_gallery_height" value="1" id="chk_all_gallery_height" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
	  </div>
	  <hr>

	  <div class="row">
		        <div class="form-group col-md-3">
				<label for="bo_table_width">게시판 폭<strong class="sound_only">필수</strong></label><br />
				<input type="text" name="bo_table_width" value="<?php echo $board['bo_table_width'] ?>" id="bo_table_width" required class="required numeric form-control" size="4">
				</div>
		        <div class="form-group col-md-3">
				<label for="bo_image_width">이미지 폭 크기<strong class="sound_only">필수</strong></label><br />
				<div class="input-group">
				<input type="text" name="bo_image_width" value="<?php echo $board['bo_image_width'] ?>" id="bo_image_width" required class="required numeric form-control" size="4">
				<span class="input-group-addon">픽셀</span>
				</div>
				</div>
		        <div class="form-group col-md-3">
				<label for="bo_new">새글 아이콘<strong class="sound_only">필수</strong></label><br />
				<input type="text" name="bo_new" value="<?php echo $board['bo_new'] ?>" id="bo_new" required class="required numeric form-control" size="4">
				</div>
		        <div class="form-group col-md-3">
				<label for="bo_hot">인기글 아이콘<strong class="sound_only">필수</strong></label><br />
				<input type="text" name="bo_hot" value="<?php echo $board['bo_hot'] ?>" id="bo_hot" required class="required numeric form-control" size="4">
				</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 게시판 폭<br />
			<?php echo help('100 이하는 %') ?><br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_table_width">
				<input type="checkbox" name="chk_grp_table_width" value="1" id="chk_grp_table_width" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_table_width">
				<input type="checkbox" name="chk_all_table_width" value="1" id="chk_all_table_width" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 이미지 폭 크기<br />
			<?php echo help('게시판에서 출력되는 이미지의 폭 크기') ?><br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_image_width">
				<input type="checkbox" name="chk_grp_image_width" value="1" id="chk_grp_image_width" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_table_width">
				<input type="checkbox" name="chk_all_table_width" value="1" id="chk_all_table_width" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 새글 아이콘<br />
			<?php echo help('글 입력후 new 이미지를 출력하는 시간. 0을 입력하시면 아이콘을 출력하지 않습니다.') ?><br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_new">
				<input type="checkbox" name="chk_grp_new" value="1" id="chk_grp_new" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_new">
				<input type="checkbox" name="chk_all_new" value="1" id="chk_all_new" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 인기글 아이콘<br />
			<?php echo help('조회수가 설정값 이상이면 hot 이미지 출력. 0을 입력하시면 아이콘을 출력하지 않습니다.') ?><br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_hot">
				<input type="checkbox" name="chk_grp_hot" value="1" id="chk_grp_hot" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_hot">
				<input type="checkbox" name="chk_all_hot" value="1" id="chk_all_hot" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
	  </div>
	  <hr>
	  
	  <div class="row">
		        <div class="form-group col-md-6">
				<label for="bo_reply_order">답변 달기</label><br />
                <select id="bo_reply_order" name="bo_reply_order" class="form-control">
                    <option value="1"<?php echo get_selected($board['bo_reply_order'], 1, true); ?>>나중에 쓴 답변 아래로 달기 (기본)
                    <option value="0"<?php echo get_selected($board['bo_reply_order'], 0); ?>>나중에 쓴 답변 위로 달기
                </select>
				</div>
		        <div class="form-group col-md-6">
				<label for="bo_sort_field">리스트 정렬 필드</label><br />
                <select id="bo_sort_field" name="bo_sort_field" class="form-control">
                    <option value="" <?php echo get_selected($board['bo_sort_field'], ""); ?>>wr_num, wr_reply : 기본</option>
                    <option value="wr_datetime asc" <?php echo get_selected($board['bo_sort_field'], "wr_datetime asc"); ?>>wr_datetime asc : 날짜 이전것 부터</option>
                    <option value="wr_datetime desc" <?php echo get_selected($board['bo_sort_field'], "wr_datetime desc"); ?>>wr_datetime desc : 날짜 최근것 부터</option>
                    <option value="wr_hit asc, wr_num, wr_reply" <?php echo get_selected($board['bo_sort_field'], "wr_hit asc, wr_num, wr_reply"); ?>>wr_hit asc : 조회수 낮은것 부터</option>
                    <option value="wr_hit desc, wr_num, wr_reply" <?php echo get_selected($board['bo_sort_field'], "wr_hit desc, wr_num, wr_reply"); ?>>wr_hit desc : 조회수 높은것 부터</option>
                    <option value="wr_last asc" <?php echo get_selected($board['bo_sort_field'], "wr_last asc"); ?>>wr_last asc : 최근글 이전것 부터</option>
                    <option value="wr_last desc" <?php echo get_selected($board['bo_sort_field'], "wr_last desc"); ?>>wr_last desc : 최근글 최근것 부터</option>
                    <option value="wr_comment asc, wr_num, wr_reply" <?php echo get_selected($board['bo_sort_field'], "wr_comment asc, wr_num, wr_reply"); ?>>wr_comment asc : 댓글수 낮은것 부터</option>
                    <option value="wr_comment desc, wr_num, wr_reply" <?php echo get_selected($board['bo_sort_field'], "wr_comment desc, wr_num, wr_reply"); ?>>wr_comment desc : 댓글수 높은것 부터</option>
                    <option value="wr_good asc, wr_num, wr_reply" <?php echo get_selected($board['bo_sort_field'], "wr_good asc, wr_num, wr_reply"); ?>>wr_good asc : 추천수 낮은것 부터</option>
                    <option value="wr_good desc, wr_num, wr_reply" <?php echo get_selected($board['bo_sort_field'], "wr_good desc, wr_num, wr_reply"); ?>>wr_good desc : 추천수 높은것 부터</option>
                    <option value="wr_nogood asc, wr_num, wr_reply" <?php echo get_selected($board['bo_sort_field'], "wr_nogood asc, wr_num, wr_reply"); ?>>wr_nogood asc : 비추천수 낮은것 부터</option>
                    <option value="wr_nogood desc, wr_num, wr_reply" <?php echo get_selected($board['bo_sort_field'], "wr_nogood desc, wr_num, wr_reply"); ?>>wr_nogood desc : 비추천수 높은것 부터</option>
                    <option value="wr_subject asc, wr_num, wr_reply" <?php echo get_selected($board['bo_sort_field'], "wr_subject asc, wr_num, wr_reply"); ?>>wr_subject asc : 제목 오름차순</option>
+                    <option value="wr_subject desc, wr_num, wr_reply" <?php echo get_selected($board['bo_sort_field'], "wr_subject desc, wr_num, wr_reply"); ?>>wr_subject desc : 제목 내림차순</option>
+                    <option value="wr_name asc, wr_num, wr_reply" <?php echo get_selected($board['bo_sort_field'], "wr_name asc, wr_num, wr_reply"); ?>>wr_name asc : 글쓴이 오름차순</option>
+                    <option value="wr_name desc, wr_num, wr_reply" <?php echo get_selected($board['bo_sort_field'], "wr_name desc, wr_num, wr_reply"); ?>>wr_name desc : 글쓴이 내림차순</option>
+                    <option value="ca_name asc, wr_num, wr_reply" <?php echo get_selected($board['bo_sort_field'], "ca_name asc, wr_num, wr_reply"); ?>>ca_name asc : 분류명 오름차순</option>
+                    <option value="ca_name desc, wr_num, wr_reply" <?php echo get_selected($board['bo_sort_field'], "ca_name desc, wr_num, wr_reply"); ?>>ca_name desc : 분류명 내림차순</option>
                </select>
				</div>
			<div class="col-md-6">
			<span class="glyphicon glyphicon-pushpin"></span> 답변 달기<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_reply_order">
				<input type="checkbox" id="chk_grp_reply_order" name="chk_grp_reply_order" value="1 data-toggle="checkbox"">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_reply_order">
				<input type="checkbox" id="chk_all_reply_order" name="chk_all_reply_order" value="1" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-6">
			<span class="glyphicon glyphicon-pushpin"></span> 리스트 정렬 필드<br />
			<?php echo help('리스트에서 기본으로 정렬에 사용할 필드를 선택합니다. "기본"으로 사용하지 않으시는 경우 속도가 느려질 수 있습니다.') ?><br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_sort_field">
				<input type="checkbox" name="chk_grp_sort_field" value="1" id="chk_grp_sort_field" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_sort_field">
				<input type="checkbox" name="chk_all_sort_field" value="1" id="chk_all_sort_field" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
		</div>
		<hr>
</section>

<?php echo $frm_submit; ?>

<section id="anc_bo_point">

      <div class="well">
        <strong>게시판 포인트 설정</strong>
      </div>
	  
	  <div class="row">
		        <div class="form-group col-md-12">
				<label for="chk_grp_point">기본값으로 설정</label><br />
				<label class="btn btn-default" style="margin-top:5px;">
				<input type="checkbox" name="chk_grp_point" id="chk_grp_point" onclick="set_point(this.form)" data-toggle="checkbox">
                환경설정에 입력된 포인트로 설정</label>
				</div>
	  </div>
	  <hr>

	  <div class="row">
		        <div class="form-group col-md-3">
				<label for="bo_read_point">글읽기 포인트<strong class="sound_only">필수</strong></label><br />
				<input type="text" name="bo_read_point" value="<?php echo $board['bo_read_point'] ?>" id="bo_read_point" required class="required form-control" size="5">
				</div>
		        <div class="form-group col-md-3">
				<label for="bo_write_point">글쓰기 포인트<strong class="sound_only">필수</strong></label><br />
				<input type="text" name="bo_write_point" value="<?php echo $board['bo_write_point'] ?>" id="bo_write_point" required class="required form-control" size="5">
				</div>
		        <div class="form-group col-md-3">
				<label for="bo_comment_point">댓글쓰기 포인트<strong class="sound_only">필수</strong></label><br />
				<input type="text" name="bo_comment_point" value="<?php echo $board['bo_comment_point'] ?>" id="bo_comment_point" required class="required form-control" size="5">
				</div>
		        <div class="form-group col-md-3">
				<label for="bo_download_point">다운로드 포인트<strong class="sound_only">필수</strong></label><br />
				<input type="text" name="bo_download_point" value="<?php echo $board['bo_download_point'] ?>" id="bo_download_point" required class="required form-control" size="5">
				</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 글읽기 포인트<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_read_point">
				<input type="checkbox" name="chk_grp_read_point" value="1" id="chk_grp_read_point" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_read_point">
				<input type="checkbox" name="chk_all_read_point" value="1" id="chk_all_read_point" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 글쓰기 포인트<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_write_point">
				<input type="checkbox" name="chk_grp_write_point" value="1" id="chk_grp_write_point" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_write_point">
				<input type="checkbox" name="chk_all_write_point" value="1" id="chk_all_write_point" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 댓글쓰기 포인트<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_comment_point">
				<input type="checkbox" name="chk_grp_comment_point" value="1" id="chk_grp_comment_point" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_comment_point">
				<input type="checkbox" name="chk_all_comment_point" value="1" id="chk_all_comment_point" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
			<div class="col-md-3">
			<span class="glyphicon glyphicon-pushpin"></span> 다운로드 포인트<br />
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_download_point">
				<input type="checkbox" name="chk_grp_download_point" value="1" id="chk_grp_download_point" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_download_point">
				<input type="checkbox" name="chk_all_download_point" value="1" id="chk_all_download_point" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
	  </div>
	  <hr>
</section>
<?php echo $frm_submit; ?>

<section id="anc_bo_extra">
      <div class="well">
        <strong>게시판 여분필드 설정</strong>
      </div>

	  <?php for ($i=1; $i<=10; $i++) { ?>
	  <div class="row">
		        <div class="form-group col-md-5">
                <label for="<?php echo $i ?>">여분필드 <?php echo $i ?> 제목</label>
				<input type="text" name="bo_<?php echo $i ?>_subj" id="bo_<?php echo $i ?>_subj" value="<?php echo get_text($board['bo_'.$i.'_subj']) ?>" class="form-control" placeholder="여분필드 <?php echo $i ?> 제목" style="margin-bottom: 5px;">
				</div>

		        <div class="form-group col-md-5">
                <label for="<?php echo $i ?>">여분필드 <?php echo $i ?> 값</label>
				<input type="text" name="bo_<?php echo $i ?>" value="<?php echo get_text($board['bo_'.$i]) ?>" id="bo_<?php echo $i ?>" class="form-control"placeholder="여분필드 <?php echo $i ?> 값">
				</div>
				
			<div class="col-md-2">
			<div class="btn-group btn-group">
                <label class="btn btn-default" for="chk_grp_<?php echo $i ?>">
				<input type="checkbox" name="chk_grp_<?php echo $i ?>" value="1" id="chk_grp_<?php echo $i ?>" data-toggle="checkbox">
                그룹적용</label>
                <label class="btn btn-default" for="chk_all_<?php echo $i ?>">
				<input type="checkbox" name="chk_all_<?php echo $i ?>" value="1" id="chk_all_<?php echo $i ?>" data-toggle="checkbox">
                전체적용</label>
            </div>
			</div>
        </div>
		<hr>
        <?php } ?>
</section>

<?php echo $frm_submit; ?>
</form>

<script>
$(function(){
    $("#board_copy").click(function(){
        window.open(this.href, "win_board_copy", "left=10,top=10,width=500,height=400");
        return false;
    });
});

function board_copy(bo_table) {
    window.open("./board_copy.php?bo_table="+bo_table, "BoardCopy", "left=10,top=10,width=500,height=200");
}

function set_point(f) {
    if (f.chk_grp_point.checked) {
        f.bo_read_point.value = "<?php echo $config['cf_read_point'] ?>";
        f.bo_write_point.value = "<?php echo $config['cf_write_point'] ?>";
        f.bo_comment_point.value = "<?php echo $config['cf_comment_point'] ?>";
        f.bo_download_point.value = "<?php echo $config['cf_download_point'] ?>";
    } else {
        f.bo_read_point.value     = f.bo_read_point.defaultValue;
        f.bo_write_point.value    = f.bo_write_point.defaultValue;
        f.bo_comment_point.value  = f.bo_comment_point.defaultValue;
        f.bo_download_point.value = f.bo_download_point.defaultValue;
    }
}

function fboardform_submit(f)
{
    <?php echo get_editor_js("bo_content_head"); ?>
    <?php echo get_editor_js("bo_content_tail"); ?>

    if (parseInt(f.bo_count_modify.value) < 0) {
        alert("원글 수정 불가 댓글수는 0 이상 입력하셔야 합니다.");
        f.bo_count_modify.focus();
        return false;
    }

    if (parseInt(f.bo_count_delete.value) < 1) {
        alert("원글 삭제 불가 댓글수는 1 이상 입력하셔야 합니다.");
        f.bo_count_delete.focus();
        return false;
    }

    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>