<?php
$sub_menu = "100100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$token = get_token();

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

if (!isset($config['cf_include_index'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_include_index` VARCHAR(255) NOT NULL AFTER `cf_admin`,
                    ADD `cf_include_head` VARCHAR(255) NOT NULL AFTER `cf_include_index`,
                    ADD `cf_include_tail` VARCHAR(255) NOT NULL AFTER `cf_include_head`,
                    ADD `cf_add_script` TEXT NOT NULL AFTER `cf_include_tail` ", true);
}

if (!isset($config['cf_mobile_new_skin'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_mobile_new_skin` VARCHAR(255) NOT NULL AFTER `cf_memo_send_point`,
                    ADD `cf_mobile_search_skin` VARCHAR(255) NOT NULL AFTER `cf_mobile_new_skin`,
                    ADD `cf_mobile_connect_skin` VARCHAR(255) NOT NULL AFTER `cf_mobile_search_skin`,
                    ADD `cf_mobile_member_skin` VARCHAR(255) NOT NULL AFTER `cf_mobile_connect_skin` ", true);
}

if (isset($config['cf_gcaptcha_mp3'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    CHANGE `cf_gcaptcha_mp3` `cf_captcha_mp3` VARCHAR(255) NOT NULL DEFAULT '' ", true);
} else if (!isset($config['cf_captcha_mp3'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_captcha_mp3` VARCHAR(255) NOT NULL DEFAULT '' AFTER `cf_mobile_member_skin` ", true);
}

if(!isset($config['cf_editor'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_editor` VARCHAR(255) NOT NULL DEFAULT '' AFTER `cf_captcha_mp3` ", true);
}

if(!isset($config['cf_googl_shorturl_apikey'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_googl_shorturl_apikey` VARCHAR(255) NOT NULL DEFAULT '' AFTER `cf_captcha_mp3` ", true);
}

if(!isset($config['cf_mobile_pages'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_mobile_pages` INT(11) NOT NULL DEFAULT '0' AFTER `cf_write_pages` ", true);
    sql_query(" UPDATE `{$g5['config_table']}` SET cf_mobile_pages = '5' ", true);
}

if(!isset($config['cf_facebook_appid'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_facebook_appid` VARCHAR(255) NOT NULL AFTER `cf_googl_shorturl_apikey`,
                    ADD `cf_facebook_secret` VARCHAR(255) NOT NULL AFTER `cf_facebook_appid`,
                    ADD `cf_twitter_key` VARCHAR(255) NOT NULL AFTER `cf_facebook_secret`,
                    ADD `cf_twitter_secret` VARCHAR(255) NOT NULL AFTER `cf_twitter_key` ", true);
}

// uniqid 테이블이 없을 경우 생성
if(!sql_query(" DESC {$g5['uniqid_table']} ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['uniqid_table']}` (
                  `uq_id` bigint(20) unsigned NOT NULL,
                  `uq_ip` varchar(255) NOT NULL,
                  PRIMARY KEY (`uq_id`)
                ) ", false);
}

if(!sql_query(" SELECT uq_ip from {$g5['uniqid_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE {$g5['uniqid_table']} ADD `uq_ip` VARCHAR(255) NOT NULL ");
}

// 임시저장 테이블이 없을 경우 생성
if(!sql_query(" DESC {$g5['autosave_table']} ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['autosave_table']}` (
                  `as_id` int(11) NOT NULL AUTO_INCREMENT,
                  `mb_id` varchar(20) NOT NULL,
                  `as_uid` bigint(20) unsigned NOT NULL,
                  `as_subject` varchar(255) NOT NULL,
                  `as_content` text NOT NULL,
                  `as_datetime` datetime NOT NULL,
                  PRIMARY KEY (`as_id`),
                  UNIQUE KEY `as_uid` (`as_uid`),
                  KEY `mb_id` (`mb_id`)
                ) ", false);
}

if(!isset($config['cf_admin_email'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_admin_email` VARCHAR(255) NOT NULL AFTER `cf_admin` ", true);
}

if(!isset($config['cf_admin_email_name'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_admin_email_name` VARCHAR(255) NOT NULL AFTER `cf_admin_email` ", true);
}

if(!isset($config['cf_cert_use'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_cert_use` TINYINT(4) NOT NULL DEFAULT '0' AFTER `cf_editor`,
                    ADD `cf_cert_ipin` VARCHAR(255) NOT NULL DEFAULT '' AFTER `cf_cert_use`,
                    ADD `cf_cert_hp` VARCHAR(255) NOT NULL DEFAULT '' AFTER `cf_cert_ipin`,
                    ADD `cf_cert_kcb_cd` VARCHAR(255) NOT NULL DEFAULT '' AFTER `cf_cert_hp`,
                    ADD `cf_cert_kcp_cd` VARCHAR(255) NOT NULL DEFAULT '' AFTER `cf_cert_kcb_cd`,
                    ADD `cf_cert_limit` INT(11) NOT NULL DEFAULT '0' AFTER `cf_cert_kcp_cd` ", true);
    sql_query(" ALTER TABLE `{$g5['member_table']}`
                    CHANGE `mb_hp_certify` `mb_certify` VARCHAR(20) NOT NULL DEFAULT '' ", true);
    sql_query(" update {$g5['member_table']} set mb_certify = 'hp' where mb_certify = '1' ");
    sql_query(" update {$g5['member_table']} set mb_certify = '' where mb_certify = '0' ");
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['cert_history_table']}` (
                  `cr_id` int(11) NOT NULL auto_increment,
                  `mb_id` varchar(255) NOT NULL DEFAULT '',
                  `cr_company` varchar(255) NOT NULL DEFAULT '',
                  `cr_method` varchar(255) NOT NULL DEFAULT '',
                  `cr_ip` varchar(255) NOT NULL DEFAULT '',
                  `cr_date` date NOT NULL DEFAULT '0000-00-00',
                  `cr_time` time NOT NULL DEFAULT '00:00:00',
                  PRIMARY KEY (`cr_id`),
                  KEY `mb_id` (`mb_id`)
                )", true);
}

if(!isset($config['cf_analytics'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_analytics` TEXT NOT NULL AFTER `cf_intercept_ip` ", true);
}

if(!isset($config['cf_add_meta'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_add_meta` TEXT NOT NULL AFTER `cf_analytics` ", true);
}

if (!isset($config['cf_syndi_token'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_syndi_token` VARCHAR(255) NOT NULL AFTER `cf_add_meta` ", true);
}

if (!isset($config['cf_syndi_except'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_syndi_except` TEXT NOT NULL AFTER `cf_syndi_token` ", true);
}

if(!isset($config['cf_sms_use'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_sms_use` varchar(255) NOT NULL DEFAULT '' AFTER `cf_cert_limit`,
                    ADD `cf_icode_id` varchar(255) NOT NULL DEFAULT '' AFTER `cf_sms_use`,
                    ADD `cf_icode_pw` varchar(255) NOT NULL DEFAULT '' AFTER `cf_icode_id`,
                    ADD `cf_icode_server_ip` varchar(255) NOT NULL DEFAULT '' AFTER `cf_icode_pw`,
                    ADD `cf_icode_server_port` varchar(255) NOT NULL DEFAULT '' AFTER `cf_icode_server_ip` ", true);
}

if(!isset($config['cf_mobile_page_rows'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_mobile_page_rows` int(11) NOT NULL DEFAULT '0' AFTER `cf_page_rows` ", true);
}

if(!isset($config['cf_cert_req'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_cert_req` tinyint(4) NOT NULL DEFAULT '0' AFTER `cf_cert_limit` ", true);
}

if(!isset($config['cf_faq_skin'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_faq_skin` varchar(255) NOT NULL DEFAULT '' AFTER `cf_connect_skin`,
                    ADD `cf_mobile_faq_skin` varchar(255) NOT NULL DEFAULT '' AFTER `cf_mobile_connect_skin` ", true);
}

// LG유플러스 본인확인 필드 추가
if(!isset($config['cf_lg_mid'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_lg_mid` varchar(255) NOT NULL DEFAULT '' AFTER `cf_cert_kcp_cd`,
                    ADD `cf_lg_mert_key` varchar(255) NOT NULL DEFAULT '' AFTER `cf_lg_mid` ", true);
}

if(!isset($config['cf_optimize_date'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_optimize_date` date NOT NULL default '0000-00-00' AFTER `cf_popular_del` ", true);
}

// 카카오톡링크 api 키
if(!isset($config['cf_kakao_js_apikey'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_kakao_js_apikey` varchar(255) NOT NULL DEFAULT '' AFTER `cf_googl_shorturl_apikey` ", true);
}

if(!$config['cf_faq_skin']) $config['cf_faq_skin'] = "basic";
if(!$config['cf_mobile_faq_skin']) $config['cf_mobile_faq_skin'] = "basic";

$g5['title'] = '기본환경설정';
include_once ('./admin.head.php');

//$pg_anchor = ''; //서브메뉴는 제일 상단만 위치

$frm_submit = '<div class="center">
    <input type="submit" value="확인" class="btn btn-primary" accesskey="s">
    <a href="'.G5_URL.'/" class="btn btn-default">메인으로</a>
</div>';

if (!$config['cf_icode_server_ip'])   $config['cf_icode_server_ip'] = '211.172.232.124';
if (!$config['cf_icode_server_port']) $config['cf_icode_server_port'] = '7295';

if ($config['cf_icode_id'] && $config['cf_icode_pw']) {
    $res = get_sock('http://www.icodekorea.com/res/userinfo.php?userid='.$config['cf_icode_id'].'&userpw='.$config['cf_icode_pw']);
    $res = explode(';', $res);
    $userinfo = array(
        'code'      => $res[0], // 결과코드
        'coin'      => $res[1], // 고객 잔액 (충전제만 해당)
        'gpay'      => $res[2], // 고객의 건수 별 차감액 표시 (충전제만 해당)
        'payment'   => $res[3]  // 요금제 표시, A:충전제, C:정액제
    );
}
?>
<!--서브메뉴-->
<nav class="navbar navbar-default navbar-static-top" role="navigation">
<div class="navbar-header">
			<span class="navbar-brand visible-xs">QUICK</span>
<label class="navbar-toggle2 collapsed visible-xs animated flip" data-toggle="collapse" data-target=".navbar-ex8-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
</label>
        </div>
<div class="subbar-collapse navbar-ex8-collapse collapse" style="height: 1px;">
<ul class="nav navbar-nav">
    <li><a href="#anc_cf_basic">기본환경</a></li>
    <li><a href="#anc_cf_board">게시판기본</a></li>
    <li><a href="#anc_cf_join">회원가입</a></li>
    <li><a href="#anc_cf_cert">본인확인</a></li>
    <li><a href="#anc_cf_mail">기본메일환경</a></li>
    <li><a href="#anc_cf_article_mail">글작성메일</a></li>
    <li><a href="#anc_cf_join_mail">가입메일</a></li>
    <li><a href="#anc_cf_vote_mail">투표메일</a></li>
    <li><a href="#anc_cf_sns">SNS</a></li>
    <li><a href="#anc_cf_lay">레이아웃 추가설정</a></li>
    <li><a href="#anc_cf_sms">SMS</a></li>
    <li><a href="#anc_cf_extra">여분필드</a></li>
</ul>
</div></nav>
<!--서브메뉴 끝-->

<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);">
<input type="hidden" name="token" value="<?php echo $token ?>" id="token">

<section id="anc_cf_basic">
    <div class="well">
        <strong>홈페이지 기본환경 설정</strong>
      </div>

        <table>
        <caption>홈페이지 기본환경 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
           <div class="row">
		        <div class="form-group col-md-4">
                    <label class="control-label" for="cf_title">홈페이지 제목<strong class="sound_only">필수</strong></label>
                    <input type="text" name="cf_title" value="<?php echo $config['cf_title'] ?>" id="cf_title" required class="required form-control" size="40">
                </div>
		        <div class="form-group col-md-4">
                    <label class="control-label" for="cf_admin">최고관리자<strong class="sound_only">필수</strong></label>
					
                <?php echo get_member_id_select('cf_admin', 10, $config['cf_admin'], 'required') ?>
                </div>
		        <div class="form-group col-md-4">
                    <label class="control-label" for="cf_admin_email">관리자 메일 주소<strong class="sound_only">필수</strong>
					</label>
                <input type="text" name="cf_admin_email" value="<?php echo $config['cf_admin_email'] ?>" id="cf_admin_email" required class="required email form-control" size="40">
                </div>
				
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
			<span class="glyphicon glyphicon-pushpin"></span> 관리자 메일주소 <?php echo help('관리자가 보내고 받는 용도로 사용하는 메일 주소를 입력합니다. (회원가입, 인증메일, 테스트, 회원메일발송 등에서 사용)') ?>
			</div>
            </div>
		<hr>
           <div class="row">
		        <div class="form-group col-md-4">
                    <label for="cf_use_point">포인트 사용</label>
					<br />
                
				    <label class="btn btn-default <?php echo $config['cf_use_point']?'active':''; ?>" for="cf_use_point" >
		            <input type="checkbox" name="cf_use_point" value="1" id="cf_use_point" <?php echo $config['cf_use_point']?'checked':''; ?> data-toggle="checkbox"> 사이트에서 포인트제도 사용시 체크
				    </label>
                </div>
		        <div class="form-group col-md-4">
                    <label for="cf_login_point">로그인시 포인트<strong class="sound_only">필수</strong></label>
				    <div class="input-group">
                    <input type="text" name="cf_login_point" value="<?php echo $config['cf_login_point'] ?>" id="cf_login_point" required class="required  form-control">
                    <span class="input-group-addon">점</span>
                    </div>
                </div>

		        <div class="form-group col-md-4">
                    <label for="cf_memo_send_point">쪽지보낼시 차감 포인트<strong class="sound_only">필수</strong></label>
                
				    <div class="input-group">
                    <input type="text" name="cf_memo_send_point" value="<?php echo $config['cf_memo_send_point'] ?>" id="cf_memo_send_point" required class="required  form-control">
                    <span class="input-group-addon">점</span>
                    </div>
            </div>
			
			         <div class="col-md-4">
			         </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 로그인 포인트 <?php echo help('회원이 로그인시 하루에 한번만 적립') ?>
			         </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 쪽지차감 포인트 <?php echo help('양수로 입력하십시오. 0점은 쪽지 보낼시 포인트를 차감하지 않습니다.') ?>
			         </div>
			</div>
		<hr>
           <div class="row">
		        <div class="form-group col-md-4">
                    <label for="cf_cut_name">이름(닉네임) 표시</label>
                
				    <div class="input-group">
                    <input type="text" name="cf_cut_name" value="<?php echo $config['cf_cut_name'] ?>" id="cf_cut_name"  class=" form-control">
                    <span class="input-group-addon">자리만 표시</span>
                    </div>
                </div>
		        <div class="form-group col-md-4">
                    <label for="cf_nick_modify">닉네임 수정</label>
					

				    <div class="input-group">
                    <input type="text" name="cf_nick_modify" value="<?php echo $config['cf_nick_modify'] ?>" id="cf_nick_modify"  class=" form-control">
                    <span class="input-group-addon">일</span>
                    </div>
                </div>

		        <div class="form-group col-md-4">
                    <label for="cf_open_modify">정보공개 수정</label>
                
				    <div class="input-group">
                    <input type="text" name="cf_open_modify" value="<?php echo $config['cf_open_modify'] ?>" id="cf_open_modify" class=" form-control">
                    <span class="input-group-addon">일</span>
                    </div>
                </div>
			
			         <div class="col-md-4">
			         </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 닉네임 수정
                     <span class="frm_info">기입된 날짜동안 변경 불가능</span>
			         </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 정보공개 수정
                     <span class="frm_info">기입된 날짜동안 변경 불가능</span>
			         </div>
			</div>
		<hr>
        
           <div class="row">
		        <div class="form-group col-md-4">
                   <label for="cf_new_del">최근게시물 삭제</label>
                
				    <div class="input-group">
                    <input type="text" name="cf_new_del" value="<?php echo $config['cf_new_del'] ?>" id="cf_new_del" class=" form-control">
                    <span class="input-group-addon">일</span>
                    </div>
                </div>
		        <div class="form-group col-md-4">
                    <label for="cf_memo_del">쪽지 삭제</label>
					

				    <div class="input-group">
                    <input type="text" name="cf_memo_del" value="<?php echo $config['cf_memo_del'] ?>" id="cf_memo_del" class=" form-control">
                    <span class="input-group-addon">일</span>
                    </div>
                </div>

		        <div class="form-group col-md-4">
                    <label for="cf_visit_del">접속자로그 삭제</label>
                
				    <div class="input-group">
                    <input type="text" name="cf_visit_del" value="<?php echo $config['cf_visit_del'] ?>" id="cf_visit_del" class=" form-control">
                    <span class="input-group-addon">일</span>
                    </div>
                </div>
			
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 최근게시물 삭제
                     <?php echo help('설정일이 지난 최근게시물 자동 삭제') ?>
			         </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 쪽지 삭제
                    <?php echo help('설정일이 지난 쪽지 자동 삭제') ?>
			         </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 접속자로그 삭제
                     <?php echo help('설정일이 지난 접속자 로그 자동 삭제') ?>
			         </div>
			</div>
		<hr>
        
           <div class="row">
		        <div class="form-group col-md-4">
                    <label for="cf_popular_del">인기검색어 삭제</label>
                
				    <div class="input-group">
                    <input type="text" name="cf_popular_del" value="<?php echo $config['cf_popular_del'] ?>" id="cf_popular_del" class=" form-control">
                    <span class="input-group-addon">일</span>
                    </div>
                </div>
		        <div class="form-group col-md-4">
                    <label for="cf_login_minutes">현재 접속자</label>
					

				    <div class="input-group">
                    <input type="text" name="cf_login_minutes" value="<?php echo $config['cf_login_minutes'] ?>" id="cf_login_minutes" class=" form-control">
                    <span class="input-group-addon">분</span>
                    </div>
                </div>

		        <div class="form-group col-md-4">
                    <label for="cf_new_rows">최근게시물 라인수</label>
                
				    <div class="input-group">
                    <input type="text" name="cf_new_rows" value="<?php echo $config['cf_new_rows'] ?>" id="cf_new_rows" class=" form-control">
                    <span class="input-group-addon">라인</span>
                    </div>
                </div>
			
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 인기검색어 삭제
                     <?php echo help('설정일이 지난 인기검색어 자동 삭제') ?>
			         </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 현재 접속자
                     <?php echo help('설정값 이내의 접속자를 현재 접속자로 인정') ?>
			         </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 최근게시물 라인수
                     <?php echo help('목록 한페이지당 라인수') ?>
			         </div>
			</div>
		<hr>
        
           <div class="row">
		        <div class="form-group col-md-6">
                    <label for="cf_page_rows">한페이지당 라인수</label>
                
				    <div class="input-group">
                    <input type="text" name="cf_page_rows" value="<?php echo $config['cf_page_rows'] ?>" id="cf_page_rows" class=" form-control">
                    <span class="input-group-addon">라인</span>
                    </div>
                </div>
		        <div class="form-group col-md-6">
                    <label for="cf_write_pages">페이지 표시 수<strong class="sound_only">필수</strong></label>
                
				    <div class="input-group">
                    <input type="text" name="cf_write_pages" value="<?php echo $config['cf_write_pages'] ?>" id="cf_write_pages" required class="required  form-control">
                    <span class="input-group-addon">페이지</span>
                    </div>
                </div>

			         <div class="col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 한페이지당 라인수
                     <?php echo help('목록(리스트) 한페이지당 라인수') ?>
			         </div>
			         <div class="col-md-6">
			         </div>
			</div>
		<hr>
        
        
           <div class="row">
		        <div class="form-group col-md-3">
                    <label for="cf_new_skin">최근게시물 스킨<strong class="sound_only">필수</strong></label>
                

				<select name="cf_new_skin" id="cf_new_skin" required class="required form-control">
                <?php
                $arr = get_skin_dir('new');
                for ($i=0; $i<count($arr); $i++) {
                    if ($i == 0) echo "<option value=\"\">선택</option>";
                    echo "<option value=\"".$arr[$i]."\"".get_selected($config['cf_new_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
                }
                ?>
                </select>
                </div>
		        <div class="form-group col-md-3">
                    <label for="cf_search_skin">검색 스킨<strong class="sound_only">필수</strong></label>
				
                
				<select name="cf_search_skin" id="cf_search_skin" required class="required form-control">
                <?php
                $arr = get_skin_dir('search');
                for ($i=0; $i<count($arr); $i++) {
                    if ($i == 0) echo "<option value=\"\">선택</option>";
                    echo "<option value=\"".$arr[$i]."\"".get_selected($config['cf_search_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
                }
                ?>
                </select>
                </div>
		        <div class="form-group col-md-3">
                    <label for="cf_connect_skin">접속자 스킨<strong class="sound_only">필수</strong></label>
                
				<select name="cf_connect_skin" id="cf_connect_skin" required class="required form-control">
                <?php
                $arr = get_skin_dir('connect');
                for ($i=0; $i<count($arr); $i++) {
                    if ($i == 0) echo "<option value=\"\">선택</option>";
                    echo "<option value=\"".$arr[$i]."\"".get_selected($config['cf_connect_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
                }
                ?>
                </select>
                </div>
		        <div class="form-group col-md-3">
                    <label for="cf_faq_skin">FAQ 스킨<strong class="sound_only">필수</strong></label>
					
                
				<select name="cf_faq_skin" id="cf_faq_skin" required class="required form-control">
                <?php
                $arr = get_skin_dir('faq');
                for ($i=0; $i<count($arr); $i++) {
                    if ($i == 0) echo "<option value=\"\">선택</option>";
                    echo "<option value=\"".$arr[$i]."\"".get_selected($config['cf_faq_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
                }
                ?>
                </select>
                </div>
			</div>
		<hr>
        
           <div class="row">
		        <div class="form-group col-md-3">
                    <label for="cf_editor">에디터 선택</label>
                
                <select name="cf_editor" id="cf_editor" class="form-control">
                <?php
                $arr = get_skin_dir('', G5_EDITOR_PATH);
                for ($i=0; $i<count($arr); $i++) {
                    if ($i == 0) echo "<option value=\"\">사용안함</option>";
                    echo "<option value=\"".$arr[$i]."\"".get_selected($config['cf_editor'], $arr[$i]).">".$arr[$i]."</option>\n";
                }
                ?>
                </select>
                </div>
		        <div class="form-group col-md-3">
                    <label for="cf_captcha_mp3">음성캡챠 선택<strong class="sound_only">필수</strong></label>
				
                
				<select name="cf_captcha_mp3" id="cf_captcha_mp3" required class="required form-control">
                <?php
                $arr = get_skin_dir('mp3', G5_CAPTCHA_PATH);
                for ($i=0; $i<count($arr); $i++) {
                    if ($i == 0) echo "<option value=\"\">선택</option>";
                    echo "<option value=\"".$arr[$i]."\"".get_selected($config['cf_captcha_mp3'], $arr[$i]).">".$arr[$i]."</option>\n";
                }
                ?>
                </select>
                </div>
		        <div class="form-group col-md-3">
                    <label for="cf_use_copy_log">복사, 이동시 로그</label><br/>
                
				<label class="btn btn-default <?php echo $config['cf_use_copy_log']?'active':''; ?>" for="cf_use_copy_log" >
		        <input type="checkbox" name="cf_use_copy_log" value="1" id="cf_use_copy_log" <?php echo $config['cf_use_copy_log']?'checked':''; ?> data-toggle="checkbox"> 복사이동글 남길시 체크
				</label>

                </div>
		        <div class="form-group col-md-3">
                    <label for="cf_point_term">포인트 유효기간</label>
					
                <div class="input-group">
                    <input type="text" name="cf_point_term" value="<?php echo $config['cf_point_term']; ?>" id="cf_point_term" required  class="required  form-control">
                    <span class="input-group-addon">일</span>
                    </div>
				
                </div>
				
			         <div class="col-md-3">
			         <span class="glyphicon glyphicon-pushpin"></span> 에디터 선택
                     <?php echo help(G5_EDITOR_URL.' 밑의 DHTML 에디터 폴더를 선택합니다.') ?>
			         </div>
			         <div class="col-md-3">
			         <span class="glyphicon glyphicon-pushpin"></span> 음성캡챠 선택
                     <?php echo help(G5_CAPTCHA_URL.'/mp3 밑의 음성 폴더를 선택합니다.') ?>
			         </div>
			         <div class="col-md-3">
			         <span class="glyphicon glyphicon-pushpin"></span> 복사 이동시 로그
                     <?php echo help('게시물 아래에 누구로 부터 복사, 이동됨 표시') ?>
			         </div>
			         <div class="col-md-3">
			         <span class="glyphicon glyphicon-pushpin"></span> 포인트 유효기간
                     <?php echo help('기간을 0으로 설정시 포인트 유효기간이 적용되지 않습니다.') ?>
			         </div>
			</div>
		<hr>

           <div class="row">
		        <div class="form-group col-md-6">
                    <label for="cf_possible_ip">접근가능 IP</label>
                
                <textarea class="form-control textarea" name="cf_possible_ip" id="cf_possible_ip"><?php echo $config['cf_possible_ip'] ?></textarea>
                </div>
		        <div class="form-group col-md-6">
                    <label for="cf_intercept_ip">접근차단 IP</label>
				
                <textarea class="form-control textarea" name="cf_intercept_ip" id="cf_intercept_ip"><?php echo $config['cf_intercept_ip'] ?></textarea>
                </div>
				
			         <div class="col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 접근가능 IP
                     <?php echo help('입력된 IP의 컴퓨터만 접근할 수 있습니다.<br>123.123.+ 도 입력 가능. (엔터로 구분)') ?>
			         </div>
			         <div class="col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 접근차단 IP
                     <?php echo help('입력된 IP의 컴퓨터는 접근할 수 없음.<br>123.123.+ 도 입력 가능. (엔터로 구분)') ?>
			         </div>
			</div>
		<hr>

           <div class="row">
		        <div class="form-group col-md-6">
                    <label for="cf_analytics">방문자분석 스크립트</label>
                
                <textarea class="form-control textarea" name="cf_analytics" id="cf_analytics"><?php echo $config['cf_analytics']; ?></textarea>
                </div>
		        <div class="form-group col-md-6">
                    <label for="cf_add_meta">추가 메타태그</label>
				
                <textarea class="form-control textarea" name="cf_add_meta" id="cf_add_meta"><?php echo $config['cf_add_meta']; ?></textarea>
                </div>
				
			         <div class="col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 방문자분석 스크립트
                     <?php echo help('방문자분석 스크립트 코드를 입력합니다. 예) 구글 애널리틱스'); ?>
			         </div>
			         <div class="col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 추가 메타태그
                     <?php echo help('추가로 사용하실 meta 태그를 입력합니다.'); ?>
			         </div>
			</div>
		<hr>

           <div class="row">
		        <div class="form-group col-md-6">
                    <label for="cf_syndi_token">네이버 신디케이션 연동키</label>
                
                <input type="text" name="cf_syndi_token" value="<?php echo $config['cf_syndi_token'] ?>" id="cf_syndi_token" class="form-control " size="70">
                </div>
		        <div class="form-group col-md-6">
                    <label for="cf_syndi_except">네이버 신디케이션 제외게시판</label>
				    <input type="text" name="cf_syndi_except" value="<?php echo $config['cf_syndi_except'] ?>" id="cf_syndi_except" class="form-control " size="70">
                </div>
				
			         <div class="col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 네이버 신디케이션 연동키
                     <?php if (!function_exists('curl_init')) echo help('<b>경고) curl이 지원되지 않아 네이버 신디케이션을 사용할수 없습니다.</b>'); ?>
                     <?php echo help('네이버 신디케이션 연동키(token)을 입력하면 네이버 신디케이션을 사용할 수 있습니다.<br>연동키는 <a href="http://webmastertool.naver.com/" target="_blank"><u class="btn btn-info btn-xs"><i class="glyphicon glyphicon-link"></i> 네이버 웹마스터도구</u></a> -> 네이버 신디케이션에서 발급할 수 있습니다.') ?>
			         </div>
			         <div class="col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 네이버 신디케이션 제외게시판
                    <?php echo help('네이버 신디케이션 수집에서 제외할 게시판 아이디를 | 로 구분하여 입력하십시오. 예) notice|adult<br>참고로 그룹접근사용 게시판, 글읽기 권한 2 이상 게시판, 비밀글은 신디케이션 수집에서 제외됩니다.') ?>
			         </div>
			</div>
		<hr>
        </tbody>
        </table>
</section>

<?php echo $frm_submit; ?>
<hr>

<section id="anc_cf_board">
    <div class="well">
        <strong>게시판 기본 설정</strong>
      </div>

        <div class="well well-sm">
        각 게시판 관리에서 개별적으로 설정 가능합니다.
      </div>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>게시판 기본 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
		
           <div class="row">
		        <div class="form-group col-md-6">
                    <label for="cf_delay_sec">글쓰기 간격<strong class="sound_only">필수</strong></label>
                
				    <div class="input-group">
                    <input type="text" name="cf_delay_sec" value="<?php echo $config['cf_delay_sec'] ?>" id="cf_delay_sec" required class="required  form-control">
                    <span class="input-group-addon">초 지난후 가능</span>
                    </div>
                </div>
		        <div class="form-group col-md-6">
                    <label for="cf_link_target">새창 링크</label>
					

				    <select name="cf_link_target" id="cf_link_target" class="form-control">
                    <option value="_blank"<?php echo get_selected($config['cf_link_target'], '_blank') ?>>_blank</option>
                    <option value="_self"<?php echo get_selected($config['cf_link_target'], '_self') ?>>_self</option>
                    <option value="_top"<?php echo get_selected($config['cf_link_target'], '_top') ?>>_top</option>
                    <option value="_new"<?php echo get_selected($config['cf_link_target'], '_new') ?>>_new</option>
                    </select>
                </div>
			
			         <div class="col-md-6">
			         </div>
			         <div class="col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 새창 링크
                     <?php echo help('글내용중 자동 링크되는 타켓을 지정합니다.') ?>
			         </div>
			</div>
		<hr>
		
           <div class="row">

		        <div class="form-group col-md-3">
                    <label for="cf_read_point">글읽기 포인트<strong class="sound_only">필수</strong></label>
                
				    <div class="input-group">
                    <input type="text" name="cf_read_point" value="<?php echo $config['cf_read_point'] ?>" id="cf_read_point" required class="required  form-control">
                    <span class="input-group-addon">점</span>
                    </div>
                </div>

		        <div class="form-group col-md-3">
                    <label for="cf_write_point">글쓰기 포인트</label>
                
				    <div class="input-group">
                    <input type="text" name="cf_write_point" value="<?php echo $config['cf_write_point'] ?>" id="cf_write_point" required class="required  form-control">
                    <span class="input-group-addon">점</span>
                    </div>
                </div>
		        <div class="form-group col-md-3">
                    <label for="cf_comment_point">댓글쓰기 포인트</label>
					
				    <div class="input-group">
                    <input type="text" name="cf_comment_point" value="<?php echo $config['cf_comment_point'] ?>" id="cf_comment_point" required class="required  form-control">
                    <span class="input-group-addon">점</span>
					</div>
                </div>

		        <div class="form-group col-md-3">
                    <label for="cf_download_point">다운로드 포인트</label>
                
				    <div class="input-group">
                    <input <input type="text" name="cf_download_point" value="<?php echo $config['cf_download_point'] ?>" id="cf_download_point" required class="required  form-control">
                    <span class="input-group-addon">점</span>
                    </div>
                </div>
			
			         <div class="col-md-4">
			         </div>
			         <div class="col-md-4">
			         </div>
			         <div class="col-md-4">
			         </div>
			         <div class="col-md-4">
			         </div>
			</div>
		<hr>
		
           <div class="row">
		        <div class="form-group col-md-3">
                    <label for="cf_search_part">검색 단위</label>
                    <input type="text" name="cf_search_part" value="<?php echo $config['cf_search_part'] ?>" id="cf_search_part" class=" form-control">
                </div>
		        <div class="form-group col-md-3">
                    <label for="cf_image_extension">이미지 업로드 확장자</label>
                    <input type="text" name="cf_image_extension" value="<?php echo $config['cf_image_extension'] ?>" id="cf_image_extension" class=" form-control">
                </div>

		        <div class="form-group col-md-3">
                    <label for="cf_flash_extension">플래쉬 업로드 확장자</label>
                    <input type="text" name="cf_flash_extension" value="<?php echo $config['cf_flash_extension'] ?>" id="cf_flash_extension" class=" form-control">
                </div>

		        <div class="form-group col-md-3">
                    <label for="cf_movie_extension">동영상 업로드 확장자</label>
                    <input type="text" name="cf_movie_extension" value="<?php echo $config['cf_movie_extension'] ?>" id="cf_movie_extension" class=" form-control">
                </div>
			
			         <div class="col-md-3">
					 <span class="glyphicon glyphicon-pushpin"></span> 검색 단위
					 <span class="frm_info">[  ]건 단위로 검색</span>
			         </div>
			         <div class="col-md-3">
			         <span class="glyphicon glyphicon-pushpin"></span> 이미지 업로드 확장자
                     <?php echo help('게시판 글작성시 이미지 파일 업로드 가능 확장자. | 로 구분') ?>
			         </div>
			         <div class="col-md-3">
			         <span class="glyphicon glyphicon-pushpin"></span> 플래쉬 업로드 확장자
                     <?php echo help('게시판 글작성시 플래쉬 파일 업로드 가능 확장자. | 로 구분') ?>
			         </div>
			         <div class="col-md-3">
			         <span class="glyphicon glyphicon-pushpin"></span> 동영상 업로드 확장자
                     <?php echo help('게시판 글작성시 동영상 파일 업로드 가능 확장자. | 로 구분') ?>
			         </div>
			</div>
		<hr>

            <div class="row">
		         <div class="form-group col-md-12">
                    <label for="cf_filter">단어 필터링</label>
					<textarea class="form-control textarea" name="cf_filter" id="cf_filter" rows="7"><?php echo $config['cf_filter'] ?></textarea>
                </div>
			 </div>
			         <div class="col-md-12">
			         <span class="glyphicon glyphicon-pushpin"></span> 단어 필터링
                     <?php echo help('입력된 단어가 포함된 내용은 게시할 수 없습니다. 단어와 단어 사이는 ,로 구분합니다.') ?>
			         </div>
		<hr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>
<hr>

<section id="anc_cf_join">
    <div class="well">
        <strong>회원가입 설정</strong>
      </div>
	  <div class="well well-sm">
		회원가입 시 사용할 스킨과 입력 받을 정보 등을 설정할 수 있습니다.
      </div>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>회원가입 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
		
            <div class="row">
		         <div class="form-group col-md-3">
                    <label for="cf_member_skin">회원 스킨<strong class="sound_only">필수</strong></label>
					<select name="cf_member_skin" id="cf_member_skin" required class="required form-control">
                    <?php
                    $arr = get_skin_dir('member');
                    for ($i=0; $i<count($arr); $i++) {
                    if ($i == 0) echo "<option value=\"\">선택</option>";
                    echo '<option value="'.$arr[$i].'"'.get_selected($config['cf_member_skin'], $arr[$i]).'>'.$arr[$i].'</option>'."\n";
                    }
                    ?>
                </select>
                </div>
			
		        <div class="form-group col-md-3">
                    <label for="cf_register_level">회원가입시 권한</label>
					<?php echo get_member_level_select('cf_register_level', 1, 9, $config['cf_register_level']) ?>
                </div>
			
		        <div class="form-group col-md-3">
                    <label for="cf_register_point">회원가입시 포인트</label>
					<div class="input-group">
                    <input type="text" name="cf_register_point" value="<?php echo $config['cf_register_point'] ?>" id="cf_register_point" class=" form-control">
                    <span class="input-group-addon">점</span>
                    </div>
                </div>
			
		        <div class="form-group col-md-3">
                    <label for="cf_leave_day">회원탈퇴후 삭제일</label>
					<div class="input-group">
					<input type="text" name="cf_leave_day" value="<?php echo $config['cf_leave_day'] ?>" id="cf_leave_day" class=" form-control">
                    <span class="input-group-addon">일 후 자동 삭제</span>
                    </div>
                </div>
			 </div>
		<hr>
		
            <div class="row">
			
		        <div class="form-group col-md-6">
                    <label>홈페이지 입력</label><br/>
                
				    <label class="btn btn-default <?php echo $config['cf_use_homepage']?'active':''; ?>" for="cf_use_homepage" >
		            <input type="checkbox" name="cf_use_homepage" value="1" id="cf_use_homepage" <?php echo $config['cf_use_homepage']?'checked':''; ?> data-toggle="checkbox"> 보이기
				    </label>
                
				    <label class="btn btn-default <?php echo $config['cf_req_homepage']?'active':''; ?>" for="cf_req_homepage" >
		            <input type="checkbox" name="cf_req_homepage" value="1" id="cf_req_homepage" <?php echo $config['cf_req_homepage']?'checked':''; ?> data-toggle="checkbox"> 필수입력
				    </label>
                </div>
			
		        <div class="form-group col-md-6">
                    <label>주소 입력</label>
					<br />
                
				    <label class="btn btn-default <?php echo $config['cf_use_addr']?'active':''; ?>" for="cf_use_addr" >
		            <input type="checkbox" name="cf_use_addr" value="1" id="cf_use_addr" <?php echo $config['cf_use_addr']?'checked':''; ?> data-toggle="checkbox"> 보이기
				    </label>
                
				    <label class="btn btn-default <?php echo $config['cf_req_addr']?'active':''; ?>" for="cf_req_addr" >
		            <input type="checkbox" name="cf_req_addr" value="1" id="cf_req_addr" <?php echo $config['cf_req_addr']?'checked':''; ?> data-toggle="checkbox"> 필수입력
				    </label>
                </div>
			 </div>
		<hr>
		
            <div class="row">
			
		        <div class="form-group col-md-6">
                    <label>전화번호 입력</label>
					<br />
                
				    <label class="btn btn-default <?php echo $config['cf_use_tel']?'active':''; ?>" for="cf_use_tel" >
		            <input type="checkbox" name="cf_use_tel" value="1" id="cf_use_tel" <?php echo $config['cf_use_tel']?'checked':''; ?> data-toggle="checkbox"> 보이기
				    </label>
                
				    <label class="btn btn-default <?php echo $config['cf_req_tel']?'active':''; ?>" for="cf_req_tel" >
		            <input type="checkbox" name="cf_req_tel" value="1" id="cf_req_tel" <?php echo $config['cf_req_tel']?'checked':''; ?> data-toggle="checkbox"> 필수입력
				    </label>
                </div>
			
		        <div class="form-group col-md-6">
                    <label>휴대폰번호 입력</label>
					<br />
                
				    <label class="btn btn-default <?php echo $config['cf_use_hp']?'active':''; ?>" for="cf_use_hp" >
		            <input type="checkbox" name="cf_use_hp" value="1" id="cf_use_hp" <?php echo $config['cf_use_hp']?'checked':''; ?> data-toggle="checkbox"> 보이기
				    </label>
                
				    <label class="btn btn-default <?php echo $config['cf_req_hp']?'active':''; ?>" for="cf_req_hp" >
		            <input type="checkbox" name="cf_req_hp" value="1" id="cf_req_hp" <?php echo $config['cf_req_hp']?'checked':''; ?> data-toggle="checkbox"> 필수입력
				    </label>
                </div>
			 </div>
		<hr>
		
            <div class="row">
			
		        <div class="form-group col-md-6">
                    <label>서명 입력</label>
					<br />
                
				    <label class="btn btn-default <?php echo $config['cf_use_signature']?'active':''; ?>" for="cf_use_signature" >
		            <input type="checkbox" name="cf_use_signature" value="1" id="cf_use_signature" <?php echo $config['cf_use_signature']?'checked':''; ?> data-toggle="checkbox"> 보이기
				    </label>
                
				    <label class="btn btn-default <?php echo $config['cf_req_signature']?'active':''; ?>" for="cf_req_signature" >
		            <input type="checkbox" name="cf_req_signature" value="1" id="cf_req_signature" <?php echo $config['cf_req_signature']?'checked':''; ?> data-toggle="checkbox"> 필수입력
				    </label>

                </div>
			
		        <div class="form-group col-md-6">
                    <label>자기소개 입력</label>
					<br />
                
				    <label class="btn btn-default <?php echo $config['cf_use_profile']?'active':''; ?>" for="cf_use_profile" >
		            <input type="checkbox" name="cf_use_profile" value="1" id="cf_use_profile" <?php echo $config['cf_use_profile']?'checked':''; ?> data-toggle="checkbox"> 보이기
				    </label>
                
				    <label class="btn btn-default <?php echo $config['cf_req_profile']?'active':''; ?>" for="cf_req_profile" >
		            <input type="checkbox" name="cf_req_profile" value="1" id="cf_req_profile" <?php echo $config['cf_req_profile']?'checked':''; ?> data-toggle="checkbox"> 필수입력
				    </label>
                </div>
			 </div>
		<hr>
		
            <div class="row">
			
		        <div class="form-group col-md-3">
                    <label for="cf_use_member_icon">회원아이콘 사용</label>
					<select name="cf_use_member_icon" id="cf_use_member_icon" class="form-control">
                    <option value="0"<?php echo get_selected($config['cf_use_member_icon'], '0') ?>>미사용
                    <option value="1"<?php echo get_selected($config['cf_use_member_icon'], '1') ?>>아이콘만 표시
                    <option value="2"<?php echo get_selected($config['cf_use_member_icon'], '2') ?>>아이콘+이름 표시
                    </select>
                </div>
			
		        <div class="form-group col-md-3">
                    <label for="cf_icon_level">아이콘 업로드 권한</label>
                    <?php echo get_member_level_select('cf_icon_level', 1, 9, $config['cf_icon_level']) ?>
                </div>
			
		        <div class="form-group col-md-3">
                    <label for="cf_member_icon_size">회원아이콘 용량</label>
					<div class="input-group">
					<input type="text" name="cf_member_icon_size" value="<?php echo $config['cf_member_icon_size'] ?>" id="cf_member_icon_size"  class=" form-control">
                    <span class="input-group-addon">바이트 이하</span>
                    </div>
                </div>
			
		        <div class="form-group col-md-3">
                    <label>회원아이콘 사이즈</label>
					<div class="input-group">
                    <input type="text" name="cf_member_icon_width" value="<?php echo $config['cf_member_icon_width'] ?>" id="cf_member_icon_width" class="form-control " size="2">
                    <span class="input-group-addon">가로</span>
                    <input type="text" name="cf_member_icon_height" value="<?php echo $config['cf_member_icon_height'] ?>" id="cf_member_icon_height" class="form-control " size="2">
                    <span class="input-group-addon">세로</span>
                    </div>
                </div>
			
			         <div class="col-md-3">
			         </div>
			         <div class="col-md-3">
			         <span class="glyphicon glyphicon-pushpin"></span> 아이콘 업로드 권한
                     <?php echo help('지정된 레벨이상 적용') ?>
			         </div>
			         <div class="col-md-3">
			         </div>
			         <div class="col-md-3">
			         <span class="glyphicon glyphicon-pushpin"></span> 회원아이콘 사이즈
                     <?php echo help('예제 : 가로 22 기입시 21바이트 업로드 가능') ?>
			         </div>
				
			 </div>
		<hr>
		
            <div class="row">
			
		        <div class="form-group col-md-6">
                    <label for="cf_use_recommend">추천인제도 사용</label><br />
					
				    <label class="btn btn-default <?php echo $config['cf_use_recommend']?'active':''; ?>" for="cf_use_recommend" >
		            <input type="checkbox" name="cf_use_recommend" value="1" id="cf_use_recommend" <?php echo $config['cf_use_recommend']?'checked':''; ?> data-toggle="checkbox"> 추천인제도 사용시 체크
				    </label>
                </div>
			
		        <div class="form-group col-md-6">
                    <label for="cf_recommend_point">추천인 포인트</label>
                    <div class="input-group">
                    <input type="text" name="cf_recommend_point" value="<?php echo $config['cf_recommend_point'] ?>" id="cf_recommend_point" class=" form-control">
                    <span class="input-group-addon">점</span>
					</div>
                </div>
				
			 </div>
		<hr>
		
            <div class="row">
		        <div class="form-group col-md-6">
                    <label for="cf_prohibit_id">아이디,닉네임 금지단어</label>
                
                    <textarea class="form-control textarea" name="cf_prohibit_id" id="cf_prohibit_id" rows="5"><?php echo $config['cf_prohibit_id'] ?></textarea>
                </div>
		        <div class="form-group col-md-6">
                    <label for="cf_prohibit_email">입력 금지 메일</label>
				
                    <textarea class="form-control textarea" name="cf_prohibit_email" id="cf_prohibit_email" rows="5"><?php echo $config['cf_prohibit_email'] ?></textarea>
                </div>
				
			         <div class="col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 아이디,닉네임 금지단어
                     <?php echo help('회원아이디, 닉네임으로 사용할 수 없는 단어를 정합니다. 쉼표 (,) 로 구분') ?>
					 </div>
			         <div class="col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 입력 금지 메일
                     <?php echo help('입력 받지 않을 도메인을 지정합니다. 엔터로 구분 ex) hotmail.com') ?>
					 </div>
			</div>
		<hr>
		
            <div class="row">
		        <div class="form-group col-md-12">
                    <label for="cf_stipulation">회원가입약관</label>
                
                    <textarea class="form-control textarea" name="cf_stipulation" id="cf_stipulation" rows="10"><?php echo $config['cf_stipulation'] ?></textarea>
                </div>
			</div>
		<hr>

            <div class="row">
		        <div class="form-group col-md-12">
                    <label for="cf_privacy">개인정보처리방침</label>
                
                    <textarea class="form-control textarea" id="cf_privacy" name="cf_privacy" rows="10"><?php echo $config['cf_privacy'] ?></textarea>
                </div>
			</div>
		<hr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>
<hr>

<section id="anc_cf_cert">
    <div class="well">
        <strong>본인확인 설정</strong>
      </div>
        <div class="well well-sm">
            회원가입 시 본인확인 수단을 설정합니다.<br>
            실명과 휴대폰 번호 그리고 본인확인 당시에 성인인지의 여부를 저장합니다.<br>
            게시판의 경우 본인확인 또는 성인여부를 따져 게시물 조회 및 쓰기 권한을 줄 수 있습니다.
      </div>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>본인확인 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>

            <div class="row">
		        <div class="form-group col-md-12">
                    <label for="cf_cert_use">본인확인</label>
                
                    <select name="cf_cert_use" id="cf_cert_use" class="form-control">
                    <?php echo option_selected("0", $config['cf_cert_use'], "사용안함"); ?>
                    <?php echo option_selected("1", $config['cf_cert_use'], "테스트"); ?>
                    <?php echo option_selected("2", $config['cf_cert_use'], "실서비스"); ?>
                </select>
                </div>
			</div>
			<hr class="cf_cert_service">
			
            <div class="row">
		        <div class="form-group col-md-6">
                    <div scope="row" class="cf_cert_service">
					<label for="cf_cert_ipin">아이핀 본인확인</label>
                    <select name="cf_cert_ipin" id="cf_cert_ipin" class="form-control">
                    <?php echo option_selected("",    $config['cf_cert_ipin'], "사용안함"); ?>
                    <?php echo option_selected("kcb", $config['cf_cert_ipin'], "코리아크레딧뷰로(KCB) 아이핀"); ?>
                    </select>
			        </div>
		        </div>
		        <div class="form-group col-md-6">
                    <div scope="row" class="cf_cert_service"><label for="cf_cert_hp">휴대폰 본인확인</label>
                    <select name="cf_cert_hp" id="cf_cert_hp" class="form-control">
                    <?php echo option_selected("",    $config['cf_cert_hp'], "사용안함"); ?>
                    <?php echo option_selected("kcb", $config['cf_cert_hp'], "코리아크레딧뷰로(KCB) 휴대폰 본인확인"); ?>
                    <?php echo option_selected("kcp", $config['cf_cert_hp'], "한국사이버결제(KCP) 휴대폰 본인확인"); ?>
                    <?php echo option_selected("lg",  $config['cf_cert_hp'], "LG유플러스 휴대폰 본인확인"); ?>
                    </select>
			        </div>
		        </div>
		    </div>
			<hr class="cf_cert_service">
			
            <div class="row">
		        <div class="form-group col-md-6">
                    <div scope="row" class="cf_cert_service">
					<label for="cf_cert_kcb_cd">코리아크레딧뷰로 KCB 회원사ID</label>
                    <input type="text" name="cf_cert_kcb_cd" value="<?php echo $config['cf_cert_kcb_cd'] ?>" id="cf_cert_kcb_cd" class="form-control " size="20">
			        </div>
		        </div>
		        <div class="form-group col-md-6">
                    <div scope="row" class="cf_cert_service">
					<label for="cf_cert_kcp_cd">한국사이버결제 KCP 사이트코드</label>
                    <div class="input-group">
                    <span class="input-group-addon">SM</span>
                    <input type="text" name="cf_cert_kcp_cd" value="<?php echo $config['cf_cert_kcp_cd'] ?>" id="cf_cert_kcp_cd" class="form-control " size="3" placeholder="코드 뒤에 3자리">
                    </div>
			        </div>
		        </div>
			
			         <div class="cf_cert_service col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 코리아크레딧뷰로 KCB 회원사ID
                     <?php echo help('KCB 회원사ID를 입력해 주십시오.<br>서비스에 가입되어 있지 않다면, KCB와 계약체결 후 회원사ID를 발급 받으실 수 있습니다.<br>이용하시려는 서비스에 대한 계약을 아이핀, 휴대폰 본인확인 각각 체결해주셔야 합니다.<br>아이핀 본인확인 테스트의 경우에는 KCB 회원사ID가 필요 없으나,<br>휴대폰 본인확인 테스트의 경우 KCB 에서 따로 발급 받으셔야 합니다.') ?>
					 <a href="http://sir.co.kr/main/service/b_ipin.php" target="_blank" class="btn btn-info btn-xs">
					 <i class="glyphicon glyphicon-link"></i> KCB 아이핀 서비스 신청페이지</a>
                     <a href="http://sir.co.kr/main/service/b_cert.php" target="_blank" class="btn btn-info btn-xs">
					 <i class="glyphicon glyphicon-link"></i> KCB 휴대폰 본인확인 서비스 신청페이지</a>
			         </div>
			         <div class="cf_cert_service col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 한국사이버결제 KCP 사이트코드
                     <?php echo help('SM으로 시작하는 5자리 사이트 코드중 뒤의 3자리만 입력해 주십시오.<br>서비스에 가입되어 있지 않다면, 본인확인 서비스 신청페이지에서 서비스 신청 후 사이트코드를 발급 받으실 수 있습니다.') ?>
					 <a href="http://sir.co.kr/main/service/p_cert.php" target="_blank" class="btn btn-info btn-xs">
					 <i class="glyphicon glyphicon-link"></i> KCP 휴대폰 본인확인 서비스 신청페이지</a>
			         </div>
		    </div>
			<hr class="cf_cert_service">
			
            <div class="row">
		        <div class="form-group col-md-6">
                    <div scope="row" class="cf_cert_service">
					<label for="cf_lg_mid">LG유플러스 상점아이디</label>
                    <div class="input-group">
                    <span class="input-group-addon">si_</span>
					<input type="text" name="cf_lg_mid" value="<?php echo $config['cf_lg_mid'] ?>" id="cf_lg_mid" class="form-control" size="20">
                    </div>
			        </div>
		        </div>
		        <div class="form-group col-md-6">
                    <div scope="row" class="cf_cert_service">
					<label for="cf_lg_mert_key">LG유플러스 MERT KEY</label>
                    <input type="text" name="cf_lg_mert_key" value="<?php echo $config['cf_lg_mert_key'] ?>" id="cf_lg_mert_key" class="form-control " size="40" placeholder="">
			        </div>
		        </div>
			
			         <div class="cf_cert_service col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> LG유플러스 상점아이디
                     <?php echo help('LG유플러스 상점아이디 중 si_를 제외한 나머지 아이디만 입력해 주십시오.<br>서비스에 가입되어 있지 않다면, 본인확인 서비스 신청페이지에서 서비스 신청 후 상점아이디를 발급 받으실 수 있습니다.<br><strong class="text-danger">LG유플러스 휴대폰본인확인은 ActiveX 설치가 필요하므로 Internet Explorer 에서만 사용할 수 있습니다.</strong>') ?>
					 <a href="http://sir.co.kr/main/service/lg_cert.php" target="_blank" class="btn btn-info btn-xs" placeholder="si_를 제외한 나머지 아이디">LG유플러스 본인확인 서비스 신청페이지</a>
			         </div>
			         <div class="cf_cert_service col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> LG유플러스 MERT KEY 
                     <?php echo help('LG유플러스 상점MertKey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실 수 있습니다.') ?>
			         </div>
		    </div>
			<hr class="cf_cert_service">
			
            <div class="row">
		        <div class="form-group col-md-6">
                    <div scope="row" class="cf_cert_service">
					<label for="cf_cert_limit">본인확인 이용제한</label>
                    <input type="text" name="cf_cert_limit" value="<?php echo $config['cf_cert_limit']; ?>" id="cf_cert_limit" class="form-control " size="3" placeholder="">
                    </div>
		        </div>
		        <div class="form-group col-md-6">
                    <div scope="row" class="cf_cert_service">
					<label for="cf_cert_req">본인확인 필수</label><br />
					<label class="btn btn-default <?php echo $config['cf_cert_req']?'active':''; ?>" for="cf_email_use" >
		            <input type="checkbox" name="cf_cert_req" value="1" id="cf_cert_req"<?php echo get_checked($config['cf_cert_req'], 1); ?> data-toggle="checkbox"> 필수
				    </label>

			        </div>
		        </div>
			
			         <div class="cf_cert_service col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 본인확인 이용제한
                     <?php echo help('하루동안 아이핀과 휴대폰 본인확인 인증 이용회수를 제한할 수 있습니다.<br>회수제한은 실서비스에서 아이핀과 휴대폰 본인확인 인증에 개별 적용됩니다.<br>0 으로 설정하시면 회수제한이 적용되지 않습니다.'); ?>
			         </div>
			         <div class="cf_cert_service col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 본인확인 필수
                     <?php echo help('회원가입 때 본인확인을 필수로 할지 설정합니다. 필수로 설정하시면 본인확인을 하지 않은 경우 회원가입이 안됩니다.'); ?>
			         </div>
			<hr class="cf_cert_service">
		    </div>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>
<hr>

<section id="anc_cf_mail">
    
    <div class="well">
        <strong>기본 메일 환경 설정</strong>
      </div>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>기본 메일 환경 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
            <div class="row">
		        <div class="form-group col-md-4">
                    <label for="cf_email_use">메일발송 사용</label><br />
					
				    <label class="btn btn-default <?php echo $config['cf_email_use']?'active':''; ?>" for="cf_email_use" >
		            <input type="checkbox" name="cf_email_use" value="1" id="cf_email_use" <?php echo $config['cf_email_use']?'checked':''; ?> data-toggle="checkbox"> 메일발송 사용시 체크
				    </label>
                </div>
		        <div class="form-group col-md-4">
                    <label for="cf_use_email_certify">메일인증 사용</label><br />
					
				    <label class="btn btn-default <?php echo $config['cf_use_email_certify']?'active':''; ?>" for="cf_use_email_certify" >
		            <input type="checkbox" name="cf_use_email_certify" value="1" id="cf_use_email_certify" <?php echo $config['cf_use_email_certify']?'checked':''; ?> data-toggle="checkbox"> 메일인증 사용시 체크
				    </label>
                </div>
		        <div class="form-group col-md-4">
                    <label for="cf_formmail_is_member">폼메일 사용 여부</label><br />
					
				    <label class="btn btn-default <?php echo $config['cf_formmail_is_member']?'active':''; ?>" for="cf_use_email_certify" >
		            <input type="checkbox" name="cf_formmail_is_member" value="1" id="cf_formmail_is_member" <?php echo $config['cf_formmail_is_member']?'checked':''; ?> data-toggle="checkbox"> 회원만 사용을 원할시 체크
				    </label>
                </div>
			
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 메일발송 사용
					 <?php echo help('체크하지 않으면 메일발송을 아예 사용하지 않습니다. 메일 테스트도 불가합니다.') ?>
					 </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 메일인증 사용
					 <?php echo help('메일에 배달된 인증 주소를 클릭하여야 회원으로 인정합니다.'); ?>
					 </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 폼메일 사용 여부
					 <?php echo help('체크하지 않으면 비회원도 사용 할 수 있습니다.') ?>
			         </div>
			</div>
			<hr>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>
<hr>

<section id="anc_cf_article_mail">
    <div class="well">
        <strong>게시판 글 작성 시 설정</strong>
      </div>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>게시판 글 작성 시 메일 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>

            <div class="row">
		        <div class="form-group col-md-4">
                    <label for="cf_email_wr_super_admin">최고관리자</label><br/>
                
				    <label class="btn btn-default <?php echo $config['cf_email_wr_super_admin']?'active':''; ?>" for="cf_email_wr_super_admin" >
		            <input type="checkbox" name="cf_email_wr_super_admin" value="1" id="cf_email_wr_super_admin" <?php echo $config['cf_email_wr_super_admin']?'checked':''; ?> data-toggle="checkbox"> 최고관리자 메일받기 사용
			    	</label>
                </div>
		        <div class="form-group col-md-4">
                    <label for="cf_email_wr_group_admin">그룹관리자</label><br/>
                
				    <label class="btn btn-default <?php echo $config['cf_email_wr_group_admin']?'active':''; ?>" for="cf_email_wr_group_admin" >
		            <input type="checkbox" name="cf_email_wr_group_admin" value="1" id="cf_email_wr_group_admin" <?php echo $config['cf_email_wr_group_admin']?'checked':''; ?> data-toggle="checkbox"> 그룹관리자 메일받기 사용
			    	</label>
                </div>
		        <div class="form-group col-md-4">
                    <label for="cf_email_wr_board_admin">게시판관리자</label><br/>
                
				    <label class="btn btn-default <?php echo $config['cf_email_wr_board_admin']?'active':''; ?>" for="cf_email_wr_board_admin" >
		            <input type="checkbox" name="cf_email_wr_board_admin" value="1" id="cf_email_wr_board_admin" <?php echo $config['cf_email_wr_board_admin']?'checked':''; ?> data-toggle="checkbox"> 게시판관리자 메일받기 사용
			    	</label>
                </div>
			
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 최고관리자
					 <?php echo help('최고관리자에게 메일을 발송합니다.') ?>
					 </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 그룹관리자
					 <?php echo help('그룹관리자에게 메일을 발송합니다.') ?>
					 </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 게시판관리자
					 <?php echo help('게시판관리자에게 메일을 발송합니다.') ?>
			         </div>
			</div>
			<hr>

            <div class="row">
		        <div class="form-group col-md-6">
				<label for="cf_email_wr_write">원글작성자</label><br/>
                
				    <label class="btn btn-default <?php echo $config['cf_email_wr_write']?'active':''; ?>" for="cf_email_wr_write" >
		            <input type="checkbox" name="cf_email_wr_write" value="1" id="cf_email_wr_write" <?php echo $config['cf_email_wr_write']?'checked':''; ?> data-toggle="checkbox"> 원글작성자 메일받기 사용
			    	</label>
                </div>
		        <div class="form-group col-md-6>
                    <label for="cf_email_wr_comment_all">댓글작성자</label><br/>
                
				    <label class="btn btn-default <?php echo $config['cf_email_wr_comment_all']?'active':''; ?>" for="cf_email_wr_comment_all" >
		            <input type="checkbox" name="cf_email_wr_comment_all" value="1" id="cf_email_wr_comment_all" <?php echo $config['cf_email_wr_comment_all']?'checked':''; ?> data-toggle="checkbox"> 댓글작성자 메일받기 사용
			    	</label>
                </div>
			
			         <div class="col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 원글작성자
					 <?php echo help('게시자님께 메일을 발송합니다.') ?>
					 </div>
			         <div class="col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 댓글작성자
					 <?php echo help('원글에 댓글이 올라오는 경우 댓글 쓴 모든 분들께 메일을 발송합니다.') ?>
					 </div>
			</div>
			<hr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>
<hr>

<section id="anc_cf_join_mail">
    
    <div class="well">
        <strong>회원가입 시 메일 설정</strong>
      </div>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>회원가입 시 메일 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
		
            <div class="row">
		        <div class="form-group col-md-6">
				<label for="cf_email_mb_super_admin">최고관리자 메일발송</label><br/>
                
				    <label class="btn btn-default <?php echo $config['cf_email_mb_super_admin']?'active':''; ?>" for="cf_email_mb_super_admin" >
		            <input type="checkbox" name="cf_email_mb_super_admin" value="1" id="cf_email_mb_super_admin" <?php echo $config['cf_email_mb_super_admin']?'checked':''; ?> data-toggle="checkbox"> 회원이 가입시 최고관리자에게 메일발송
			    	</label>
                </div>
		        <div class="form-group col-md-6">
                    <label for="cf_email_mb_member">회원님께 메일발송</label><br/>
                
				    <label class="btn btn-default <?php echo $config['cf_email_mb_member']?'active':''; ?>" for="cf_email_mb_member" >
		            <input type="checkbox" name="cf_email_mb_member" value="1" id="cf_email_mb_member" <?php echo $config['cf_email_mb_member']?'checked':''; ?> data-toggle="checkbox"> 회원가입후 본인에게 가입축하 메일발송
			    	</label>
                </div>
			
			         <div class="col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 최고관리자 메일발송
					 <?php echo help('최고관리자에게 메일을 발송합니다.') ?>
					 </div>
			         <div class="col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 회원님께 메일발송
					 <?php echo help('회원가입한 회원님께 메일을 발송합니다.') ?>
					 </div>
			</div>
			<hr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>
<hr>

<section id="anc_cf_vote_mail">
    
    <div class="well">
        <strong>투표 기타의견 작성 시 메일 설정</strong>
      </div>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>투표 기타의견 작성 시 메일 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
		
            <div class="row">
		        <div class="form-group col-md-12">
				<label for="cf_email_po_super_admin">최고관리자 메일발송</label><br/>
                
				    <label class="btn btn-default <?php echo $config['cf_email_po_super_admin']?'active':''; ?>" for="cf_email_mb_member" >
		            <input type="checkbox" name="cf_email_po_super_admin" value="1" id="cf_email_po_super_admin" <?php echo $config['cf_email_po_super_admin']?'checked':''; ?> data-toggle="checkbox"> 회원이 투표참여시 최고관리자에게 메일발송
			    	</label>
                </div>
			         <div class="col-md-12">
			         <span class="glyphicon glyphicon-pushpin"></span> 최고관리자 메일발송
					 <?php echo help('최고관리자에게 메일을 발송합니다.') ?>
					 </div>
			</div>
			<hr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>
<hr>

<section id="anc_cf_sns">
    
    <div class="well">
        <strong>소셜네트워크서비스(SNS : Social Network Service)</strong>
      </div>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>소셜네트워크서비스 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>

           <div class="row">
		        <div class="form-group col-md-6">
                <label for="cf_facebook_appid">페이스북 앱 ID</label>
                
				<div class="input-group">
                <input type="text" name="cf_facebook_appid" value="<?php echo $config['cf_facebook_appid'] ?>" id="cf_facebook_appid" class="form-control ">
				<span class="input-group-addon"><a href="https://developers.facebook.com/apps" target="_blank"><i class="glyphicon glyphicon-link"></i> 앱 등록하기</a></span>
				</div>
                </div>
		        <div class="form-group col-md-6">
                <label for="cf_facebook_secret">페이스북 앱 Secret</label>

                <input type="text" name="cf_facebook_secret" value="<?php echo $config['cf_facebook_secret'] ?>" id="cf_facebook_secret" class="form-control " size="35">
                </div>
			</div>
		<hr>

           <div class="row">
		        <div class="form-group col-md-6">
                <label for="cf_twitter_key">트위터 컨슈머 Key</label>

				<div class="input-group">
                <input type="text" name="cf_twitter_key" value="<?php echo $config['cf_twitter_key'] ?>" id="cf_twitter_key" class="form-control ">
                <span class="input-group-addon"><a href="https://dev.twitter.com/apps" target="_blank"><i class="glyphicon glyphicon-link"></i> 앱 등록하기</a></span>
                </div>
				</div>

		        <div class="form-group col-md-6">
                <label for="cf_twitter_secret">트위터 컨슈머 Secret</label>
                
                <input type="text" name="cf_twitter_secret" value="<?php echo $config['cf_twitter_secret'] ?>" id="cf_twitter_secret" class="form-control " size="35">
                </div>
			</div>
		<hr>

           <div class="row">
		        <div class="form-group col-md-6">
                <label for="cf_googl_shorturl_apikey">구글 짧은주소 API Key</label>

				<div class="input-group">
                <input type="text" name="cf_googl_shorturl_apikey" value="<?php echo $config['cf_googl_shorturl_apikey'] ?>" id="cf_googl_shorturl_apikey"  class="form-control ">
			    <span class="input-group-addon"><a href="http://code.google.com/apis/console/" target="_blank">
				<i class="glyphicon glyphicon-link"></i> API Key 등록하기</a></span>
                </div>
				</div>

		        <div class="form-group col-md-6">
                <label for="cf_kakao_js_apikey">카카오 Javascript API Key</label>

				<div class="input-group">
                <input type="text" name="cf_kakao_js_apikey" value="<?php echo $config['cf_kakao_js_apikey'] ?>" id="cf_kakao_js_apikey" class="form-control ">
                <span class="input-group-addon"><a href="http://developers.kakao.com/" target="_blank"><i class="glyphicon glyphicon-link"></i> 앱 등록하기</a></span>
				</div>
                </div>
			</div>
		<hr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>
<hr>

<section id="anc_cf_lay">
    
    <div class="well">
        <strong>레이아웃 추가설정</strong>
      </div>
	  <div class="well well-sm">
		기본 설정된 파일 경로 및 script, css 를 추가하거나 변경할 수 있습니다.
      </div>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>레이아웃 추가설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>

		<div class="row">
		        <div class="form-group col-md-4">
                    <label for="cf_include_index">초기화면 파일 경로</label>
                    <input type="text" name="cf_include_index" value="<?php echo $config['cf_include_index'] ?>" id="cf_include_index" class=" form-control" size="50" placeholder="예제 ) index.php">
                </div>
		        <div class="form-group col-md-4">
                    <label for="cf_include_head">상단 파일 경로</label>
                    <input type="text" name="cf_include_head" value="<?php echo $config['cf_include_head'] ?>" id="cf_include_head" class="form-control " size="50" placeholder="예제 ) _head.php">
                </div>

		        <div class="form-group col-md-4">
                    <label for="cf_include_tail">하단 파일 경로</label>
                    <input type="text" name="cf_include_tail" value="<?php echo $config['cf_include_tail'] ?>" id="cf_include_tail" class="form-control " size="50" placeholder="예제 ) _tail.php">
                </div>

			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 초기화면 파일 경로
					 <?php echo help('입력이 없으면 index.php가 초기화면 파일로 설정됩니다.<br>초기화면 파일은 index.php 파일과 동일한 위치에 존재해야 합니다.') ?>
					 </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 상단 파일 경로
					 <?php echo help('입력이 없으면 head.php가 상단 파일로 설정됩니다.<br>상단 파일은 head.php 파일과 동일한 위치에 존재해야 합니다.') ?>
					 </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 하단 파일 경로
					 <?php echo help('입력이 없으면 tail.php가 하단 파일로 설정됩니다.<br>초기화면 파일은 tail.php 파일과 동일한 위치에 존재해야 합니다.') ?>
					 </div>
			</div>
		<hr>

		<div class="row">
		        <div class="form-group col-md-12">
                    <label for="cf_add_script">추가 script, css</label>
                    <textarea class="form-control textarea" name="cf_add_script" id="cf_add_script"><?php echo get_text($config['cf_add_script']); ?></textarea>
                </div>
		       
			         <div class="col-md-12">
			         <span class="glyphicon glyphicon-pushpin"></span> 추가 script, css
					 <?php echo help('HTML의 &lt;/HEAD&gt; 태그위로 추가될 JavaScript와 css 코드를 설정합니다.<br>관리자 페이지에서는 이 코드를 사용하지 않습니다.') ?>
					 </div>
			</div>
		<hr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>
<hr>

<section id="anc_cf_sms">
    
    <div class="well">
        <strong>SMS</strong>
      </div>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>SMS 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>

		<div class="row">
		        <div class="form-group col-md-4">
                    <label for="cf_sms_use">SMS 사용</label>
                    <select id="cf_sms_use" name="cf_sms_use" class="form-control">
                    <option value="" <?php echo get_selected($config['cf_sms_use'], ''); ?>>사용안함</option>
                    <option value="icode" <?php echo get_selected($config['cf_sms_use'], 'icode'); ?>>아이코드</option>
                    </select>
                </div>
		        <div class="form-group col-md-4">
                    <label for="cf_icode_id">아이코드 회원아이디</label>
                    <input type="text" name="cf_icode_id" value="<?php echo $config['cf_icode_id']; ?>" id="cf_icode_id" class="form-control " size="20" placeholder="예제) sir_gnu" >
                </div>

		        <div class="form-group col-md-4">
                    <label for="cf_icode_pw">아이코드 비밀번호</label>
                    <input type="password" name="cf_icode_pw" value="<?php echo $config['cf_icode_pw']; ?>" id="cf_icode_pw" class="form-control " size="50">
                </div>

			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 초기화면 파일 경로
					 <?php echo help('입력이 없으면 index.php가 초기화면 파일의 기본 경로로 설정됩니다.') ?>
					 </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 아이코드 회원아이디
					 <?php echo help("아이코드에서 사용하시는 회원아이디를 입력합니다."); ?>
					 </div>
			         <div class="col-md-4">
			         <span class="glyphicon glyphicon-pushpin"></span> 아이코드 비밀번호
					 <?php echo help("아이코드에서 사용하시는 비밀번호를 입력합니다."); ?>
					 </div>
			</div>
		<hr>

		<div class="row">
		        <div class="form-group col-md-6">
                    <label>요금제</label>
					<br />
                    <input type="hidden" name="cf_icode_server_ip" value="<?php echo $config['cf_icode_server_ip']; ?>" class="form-control">
					<?php
                    if ($userinfo['payment'] == 'A') {
                       echo '<a href="#" class="btn btn-info" data-toggle="popover" data-content="SMS 문자사용을 충전금액 만큼 소진하여 사용할수 있습니다" title="충전제란?"><i class="glyphicon glyphicon-flash"></i> 충전제</a>';
                        echo '<input type="hidden" name="cf_icode_server_port" value="7295">';
                    } else if ($userinfo['payment'] == 'C') {
                        echo '<a href="#" class="btn btn-info" data-toggle="popover" data-content="SMS 문자사용을 기간동안 사용할수 있습니다" title="정액제란?"><i class="glyphicon glyphicon-signal"></i> 정액제</a>';
                        echo '<input type="hidden" name="cf_icode_server_port" value="7296">';
                    } else {
                        echo '<a href="#" class="btn btn-info" data-toggle="popover" data-content="가입시 SMS문자 서비스를 연동하여 사용할수 있습니다." title="SMS 가입"><i class="glyphicon glyphicon-ban-circle"></i> 가입해주세요</a>';
                        echo '<input type="hidden" name="cf_icode_server_port" value="7295">';
                    }
                ?>

                </div>
		        <div class="form-group col-md-6">
                    <label>아이코드 SMS 신청 회원가입</label>
                </div>
				
				<?php if ($userinfo['payment'] == 'A') { ?>
		        <div class="form-group col-md-6">
                    <label>충전 잔액</label>
                    <?php echo number_format($userinfo['coin']); ?> 원.
                    <a href="http://www.icodekorea.com/smsbiz/credit_card_amt.php?icode_id=<?php echo $config['cf_icode_id']; ?>&amp;icode_passwd=<?php echo $config['cf_icode_pw']; ?>" target="_blank" class="btn btn-info btn-xs" onclick="window.open(this.href,'icode_payment', 'scrollbars=1,resizable=1'); return false;"> <i class="glyphicon glyphicon-flash"></i> 충전하기</a>
                </div>

		        <div class="form-group col-md-6">
                    <span class="glyphicon glyphicon-pushpin"></span> 건수별 금액
					<br />
                    <?php echo number_format($userinfo['gpay']); ?> 원
                </div>
                <?php } ?>

			         <div class="col-md-6">
					 </div>
			         <div class="col-md-6">
			         <span class="glyphicon glyphicon-pushpin"></span> 아이코드 SMS 신청 회원가입
					 <br />
					 <?php echo help("아래 링크에서 회원가입 하시면 문자 건당 16원에 제공 받을 수 있습니다."); ?>
					 <a href="http://icodekorea.com/res/join_company_fix_a.php?sellid=sir2" target="_blank" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-link"></i> 아이코드 회원가입</a>
					 </div>
			</div>
		<hr>

        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>
<hr>

<section id="anc_cf_extra">
    
    <div class="well">
        <strong>여분필드 기본 설정</strong>
      </div>
      <div class="well well-sm">
		각 게시판 관리에서 개별적으로 설정 가능합니다.
      </div>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>여분필드 기본 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <?php for ($i=1; $i<=10; $i++) { ?>

		<div class="row">
		        <div class="form-group col-md-6">
                <label for="cf_<?php echo $i ?>_subj">여분필드<?php echo $i ?> 제목</label>
				<input type="text" name="cf_<?php echo $i ?>_subj" value="<?php echo get_text($config['cf_'.$i.'_subj']) ?>" id="cf_<?php echo $i ?>_subj" class="form-control " size="30">
				</div>

		        <div class="form-group col-md-6">
                <label for="cf_<?php echo $i ?>">여분필드<?php echo $i ?> 값</label>
				<input type="text" name="cf_<?php echo $i ?>" value="<?php echo $config['cf_'.$i] ?>" id="cf_<?php echo $i ?>" class="form-control " size="30">
				</div>
        </div>
		<hr >
        <?php } ?>
		</div>
        </tbody>
        </table>
</section>

<?php echo $frm_submit; ?>

</form>
<!--오른쪽 팝오버-->
<script type="text/javascript">
    $(document).ready(function () {
        // popover demo
        $("a[data-toggle=popover]")
        .popover()
        .click(function(e) {
            e.preventDefault()
        })
    });
</script>
<script>
$(function(){
    <?php
    if(!$config['cf_cert_use'])
        echo '$(".cf_cert_service").addClass("cf_cert_hide");';
    ?>
    $("#cf_cert_use").change(function(){
        switch($(this).val()) {
            case "0":
                $(".cf_cert_service").addClass("cf_cert_hide");
                break;
            default:
                $(".cf_cert_service").removeClass("cf_cert_hide");
                break;
        }
    });
});

function fconfigform_submit(f)
{
    f.action = "./config_form_update.php";
    return true;
}
</script>

<?php
// 본인확인 모듈 실행권한 체크
if($config['cf_cert_use']) {
    // kcb일 때
    if($config['cf_cert_ipin'] == 'kcb' || $config['cf_cert_hp'] == 'kcb') {
        // 실행모듈
        if(strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            if(PHP_INT_MAX == 2147483647) // 32-bit
                $exe = G5_OKNAME_PATH.'/bin/okname';
            else
                $exe = G5_OKNAME_PATH.'/bin/okname_x64';
        } else {
            if(PHP_INT_MAX == 2147483647) // 32-bit
                $exe = G5_OKNAME_PATH.'/bin/okname.exe';
            else
                $exe = G5_OKNAME_PATH.'/bin/oknamex64.exe';
        }

        echo module_exec_check($exe, 'okname');
    }

    // kcp일 때
    if($config['cf_cert_hp'] == 'kcp') {
        if(PHP_INT_MAX == 2147483647) // 32-bit
           $exe = G5_KCPCERT_PATH . '/bin/ct_cli';
        else
           $exe = G5_KCPCERT_PATH . '/bin/ct_cli_x64';

        echo module_exec_check($exe, 'ct_cli');
    }

    // LG의 경우 log 디렉토리 체크
    if($config['cf_cert_hp'] == 'lg') {
        $log_path = G5_LGXPAY_PATH.'/lgdacom/log';

        if(!is_dir($log_path)) {
            echo '<script>'.PHP_EOL;
            echo 'alert("'.str_replace(G5_PATH.'/', '', G5_LGXPAY_PATH).'/lgdacom 폴더 안에 log 폴더를 생성하신 후 쓰기권한을 부여해 주십시오.\n> mkdir log\n> chmod 707 log");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        } else {
            if(!is_writable($log_path)) {
                echo '<script>'.PHP_EOL;
                echo 'alert("'.str_replace(G5_PATH.'/', '',$log_path).' 폴더에 쓰기권한을 부여해 주십시오.\n> chmod 707 log");'.PHP_EOL;
                echo '</script>'.PHP_EOL;
            }
        }
    }
}

include_once ('./admin.tail.php');
?>
