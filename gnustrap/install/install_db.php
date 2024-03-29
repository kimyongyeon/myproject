<?php
@set_time_limit(0);
$gmnow = gmdate('D, d M Y H:i:s') . ' GMT';
header('Expires: 0'); // rfc2616 - Section 14.21
header('Last-Modified: ' . $gmnow);
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0

include_once ('../config.php');
$title = G5_VERSION." 설치 완료 3/3";
include_once ('./install.inc.php');

//print_r($_POST); exit;

$mysql_host  = $_POST['mysql_host'];
$mysql_user  = $_POST['mysql_user'];
$mysql_pass  = $_POST['mysql_pass'];
$mysql_db    = $_POST['mysql_db'];
$table_prefix= $_POST['table_prefix'];
$admin_id    = $_POST['admin_id'];
$admin_pass  = $_POST['admin_pass'];
$admin_name  = $_POST['admin_name'];
$admin_email = $_POST['admin_email'];

$dblink = @mysql_connect($mysql_host, $mysql_user, $mysql_pass);
if (!$dblink) {
?>

<div class="text-center alert alert-danger">
    MySQL Host, User, Password 를 확인해 주십시오.
</div>
    <div class="text-center"><a href="./install_config.php" class="btn btn-danger" style="margin-bottom:15px;">뒤로가기</a></div>

<?php
    include_once ('./install.inc2.php');
    exit;
}

$select_db = @mysql_select_db($mysql_db, $dblink);
if (!$select_db) {
?>

<div class="text-center alert alert-danger">
    MySQL DB 를 확인해 주십시오.
</div>
    <div class="text-center"><a href="./install_config.php" class="btn btn-danger" style="margin-bottom:15px;">뒤로가기</a></div>

<?php
    include_once ('./install.inc2.php');
    exit;
}

$mysql_set_mode = 'false';
@mysql_query('set names utf8');
if(version_compare(mysql_get_server_info(), '5.6.6', '>=')  == 1) {
    @mysql_query("SET SESSION sql_mode = ''");
    $mysql_set_mode = 'true';
}
?>

<div class="alert alert-info">
    <?php echo G5_VERSION ?> 설치가 시작되었습니다.
</div>
<ul class="list-group">
<?php
// 테이블 생성 ------------------------------------
$file = implode('', file('./gnuboard5.sql'));
eval("\$file = \"$file\";");

$file = preg_replace('/^--.*$/m', '', $file);
$file = preg_replace('/`g5_([^`]+`)/', '`'.$table_prefix.'$1', $file);
$f = explode(';', $file);
for ($i=0; $i<count($f); $i++) {
    if (trim($f[$i]) == '') continue;
    mysql_query($f[$i]) or die(mysql_error());
}
// 테이블 생성 ------------------------------------
?>

        <li class="list-group-item">전체 테이블 생성 완료</li>

<?php
$read_point = 0;
$write_point = 0;
$comment_point = 0;
$download_point = 0;

//-------------------------------------------------------------------------------------------------
// config 테이블 설정
$sql = " insert into `{$table_prefix}config`
            set cf_title = '".G5_VERSION."',
                cf_admin = '$admin_id',
                cf_admin_email = '$admin_email',
                cf_admin_email_name = '".G5_VERSION."',
                cf_use_point = '1',
                cf_use_copy_log = '1',
                cf_login_point = '100',
                cf_memo_send_point = '500',
                cf_cut_name = '15',
                cf_nick_modify = '60',
                cf_new_skin = 'basic',
                cf_new_rows = '15',
                cf_search_skin = 'basic',
                cf_connect_skin = 'basic',
                cf_read_point = '$read_point',
                cf_write_point = '$write_point',
                cf_comment_point = '$comment_point',
                cf_download_point = '$download_point',
                cf_write_pages = '10',
                cf_mobile_pages = '5',
                cf_link_target = '_blank',
                cf_delay_sec = '30',
                cf_filter = '18아,18놈,18새끼,18년,18뇬,18노,18것,18넘,개년,개놈,개뇬,개새,개색끼,개세끼,개세이,개쉐이,개쉑,개쉽,개시키,개자식,개좆,게색기,게색끼,광뇬,뇬,눈깔,뉘미럴,니귀미,니기미,니미,도촬,되질래,뒈져라,뒈진다,디져라,디진다,디질래,병쉰,병신,뻐큐,뻑큐,뽁큐,삐리넷,새꺄,쉬발,쉬밸,쉬팔,쉽알,스패킹,스팽,시벌,시부랄,시부럴,시부리,시불,시브랄,시팍,시팔,시펄,실밸,십8,십쌔,십창,싶알,쌉년,썅놈,쌔끼,쌩쑈,썅,써벌,썩을년,쎄꺄,쎄엑,쓰바,쓰발,쓰벌,쓰팔,씨8,씨댕,씨바,씨발,씨뱅,씨봉알,씨부랄,씨부럴,씨부렁,씨부리,씨불,씨브랄,씨빠,씨빨,씨뽀랄,씨팍,씨팔,씨펄,씹,아가리,아갈이,엄창,접년,잡놈,재랄,저주글,조까,조빠,조쟁이,조지냐,조진다,조질래,존나,존니,좀물,좁년,좃,좆,좇,쥐랄,쥐롤,쥬디,지랄,지럴,지롤,지미랄,쫍빱,凸,퍽큐,뻑큐,빠큐,ㅅㅂㄹㅁ',
                cf_possible_ip = '',
                cf_intercept_ip = '',
                cf_analytics = '',
                cf_member_skin = 'basic',
                cf_mobile_new_skin = 'basic',
                cf_mobile_search_skin = 'basic',
                cf_mobile_connect_skin = 'basic',
                cf_mobile_member_skin = 'basic',
                cf_faq_skin = 'basic',
                cf_mobile_faq_skin = 'basic',
                cf_editor = 'smarteditor2',
                cf_captcha_mp3 = 'basic',
                cf_register_level = '2',
                cf_register_point = '1000',
                cf_icon_level = '2',
                cf_leave_day = '30',
                cf_search_part = '10000',
                cf_email_use = '1',
                cf_prohibit_id = 'admin,administrator,관리자,운영자,어드민,주인장,webmaster,웹마스터,sysop,시삽,시샵,manager,매니저,메니저,root,루트,su,guest,방문객',
                cf_prohibit_email = '',
                cf_new_del = '30',
                cf_memo_del = '180',
                cf_visit_del = '180',
                cf_popular_del = '180',
                cf_use_member_icon = '2',
                cf_member_icon_size = '5000',
                cf_member_icon_width = '22',
                cf_member_icon_height = '22',
                cf_login_minutes = '10',
                cf_image_extension = 'gif|jpg|jpeg|png',
                cf_flash_extension = 'swf',
                cf_movie_extension = 'asx|asf|wmv|wma|mpg|mpeg|mov|avi|mp3',
                cf_formmail_is_member = '1',
                cf_page_rows = '15',
                cf_mobile_page_rows = '15',
                cf_cert_limit = '2',
                cf_stipulation = '해당 홈페이지에 맞는 회원가입약관을 입력합니다.',
                cf_privacy = '해당 홈페이지에 맞는 개인정보처리방침을 입력합니다.',
				cf_1 = '58',
				cf_1_subj = '회원사진 가로사이즈',
				cf_2 = '58',
				cf_2_subj = '회원사진 세로사이즈',
				cf_3 = '50000',
				cf_3_subj = '회원사진 용량'
                ";
mysql_query($sql) or die(mysql_error() . "<p>" . $sql);

// 1:1문의 설정
$sql = " insert into `{$table_prefix}qa_config`
            ( qa_title, qa_category, qa_skin, qa_mobile_skin, qa_use_email, qa_req_email, qa_use_hp, qa_req_hp, qa_use_editor, qa_subject_len, qa_mobile_subject_len, qa_page_rows, qa_mobile_page_rows, qa_image_width, qa_upload_size, qa_insert_content )
          values
            ( '1:1문의', '회원|포인트', 'basic', 'basic', '1', '0', '1', '0', '1', '60', '30', '15', '15', '600', '1048576', '' ) ";
mysql_query($sql);

// 게시판그룹 생성 그룹1
$sql = " insert into `{$table_prefix}group`
            ( gr_id, gr_subject, gr_device, gr_admin, gr_use_access, gr_order, gr_1_subj, gr_2_subj, gr_3_subj, gr_4_subj, gr_5_subj, gr_6_subj, gr_7_subj, gr_8_subj, gr_9_subj, gr_10_subj, gr_1, gr_2, gr_3, gr_4, gr_5, gr_6, gr_7, gr_8, gr_9, gr_10 )
          values
            ( 'group1', '그룹1', 'both', '', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) ";
mysql_query($sql);

// 게시판그룹 생성 그룹2
$sql = " insert into `{$table_prefix}group`
            ( gr_id, gr_subject, gr_device, gr_admin, gr_use_access, gr_order, gr_1_subj, gr_2_subj, gr_3_subj, gr_4_subj, gr_5_subj, gr_6_subj, gr_7_subj, gr_8_subj, gr_9_subj, gr_10_subj, gr_1, gr_2, gr_3, gr_4, gr_5, gr_6, gr_7, gr_8, gr_9, gr_10 )
          values
            ( 'group2', '그룹2', 'both', '', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) ";
mysql_query($sql);

// 게시판 생성
$sql = " insert into `{$table_prefix}board`
            ( bo_table, gr_id, bo_subject, bo_mobile_subject, bo_device, bo_admin, bo_list_level, bo_read_level, bo_write_level, bo_reply_level, bo_comment_level, bo_upload_level, bo_download_level, bo_html_level, bo_link_level, bo_count_delete, bo_count_modify, bo_read_point, bo_write_point, bo_comment_point, bo_download_point, bo_use_category, bo_category_list, bo_use_sideview, bo_use_file_content, bo_use_secret, bo_use_dhtml_editor, bo_use_rss_view, bo_use_good, bo_use_nogood, bo_use_name, bo_use_signature, bo_use_ip_view, bo_use_list_view, bo_use_list_file, bo_use_list_content, bo_table_width, bo_subject_len, bo_mobile_subject_len, bo_page_rows, bo_mobile_page_rows, bo_new, bo_hot, bo_image_width, bo_skin, bo_mobile_skin, bo_include_head, bo_include_tail, bo_content_head, bo_mobile_content_head, bo_content_tail, bo_mobile_content_tail, bo_insert_content, bo_gallery_cols, bo_gallery_width, bo_gallery_height, bo_mobile_gallery_width, bo_mobile_gallery_height, bo_upload_size, bo_reply_order, bo_use_search, bo_order, bo_count_write, bo_count_comment, bo_write_min, bo_write_max, bo_comment_min, bo_comment_max, bo_notice, bo_upload_count, bo_use_email, bo_use_cert, bo_use_sns, bo_sort_field, bo_1_subj, bo_2_subj, bo_3_subj, bo_4_subj, bo_5_subj, bo_6_subj, bo_7_subj, bo_8_subj, bo_9_subj, bo_10_subj, bo_1, bo_2, bo_3, bo_4, bo_5, bo_6, bo_7, bo_8, bo_9, bo_10 )
          values
            ( 'board01', 'group1', '게시판01', '', 'both', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 100, 40, 20, 3, 15, 24, 100, 600, 'basic', 'basic', '_head.php', '_tail.php', '', '', '', '', '', 4, 174, 124, 125, 100, 1048576, 1, 1, 0, 13, 7, 0, 0, 0, 0, '15', 2, 1, '', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) ";
mysql_query($sql);

// 게시판 생성
$sql = " insert into `{$table_prefix}board`
            ( bo_table, gr_id, bo_subject, bo_mobile_subject, bo_device, bo_admin, bo_list_level, bo_read_level, bo_write_level, bo_reply_level, bo_comment_level, bo_upload_level, bo_download_level, bo_html_level, bo_link_level, bo_count_delete, bo_count_modify, bo_read_point, bo_write_point, bo_comment_point, bo_download_point, bo_use_category, bo_category_list, bo_use_sideview, bo_use_file_content, bo_use_secret, bo_use_dhtml_editor, bo_use_rss_view, bo_use_good, bo_use_nogood, bo_use_name, bo_use_signature, bo_use_ip_view, bo_use_list_view, bo_use_list_file, bo_use_list_content, bo_table_width, bo_subject_len, bo_mobile_subject_len, bo_page_rows, bo_mobile_page_rows, bo_new, bo_hot, bo_image_width, bo_skin, bo_mobile_skin, bo_include_head, bo_include_tail, bo_content_head, bo_mobile_content_head, bo_content_tail, bo_mobile_content_tail, bo_insert_content, bo_gallery_cols, bo_gallery_width, bo_gallery_height, bo_mobile_gallery_width, bo_mobile_gallery_height, bo_upload_size, bo_reply_order, bo_use_search, bo_order, bo_count_write, bo_count_comment, bo_write_min, bo_write_max, bo_comment_min, bo_comment_max, bo_notice, bo_upload_count, bo_use_email, bo_use_cert, bo_use_sns, bo_sort_field, bo_1_subj, bo_2_subj, bo_3_subj, bo_4_subj, bo_5_subj, bo_6_subj, bo_7_subj, bo_8_subj, bo_9_subj, bo_10_subj, bo_1, bo_2, bo_3, bo_4, bo_5, bo_6, bo_7, bo_8, bo_9, bo_10 )
          values
            ( 'board02', 'group1', '게시판02', '', 'both', '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 100, 40, 20, 3, 15, 24, 100, 600, 'basic', 'basic', '_head.php', '_tail.php', '', '', '', '', '', 4, 174, 124, 125, 100, 1048576, 1, 1, 0, 13, 7, 0, 0, 0, 0, '15', 2, 1, '', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) ";
mysql_query($sql);

// 게시판 생성
$sql = " insert into `{$table_prefix}board`
            ( bo_table, gr_id, bo_subject, bo_mobile_subject, bo_device, bo_admin, bo_list_level, bo_read_level, bo_write_level, bo_reply_level, bo_comment_level, bo_upload_level, bo_download_level, bo_html_level, bo_link_level, bo_count_delete, bo_count_modify, bo_read_point, bo_write_point, bo_comment_point, bo_download_point, bo_use_category, bo_category_list, bo_use_sideview, bo_use_file_content, bo_use_secret, bo_use_dhtml_editor, bo_use_rss_view, bo_use_good, bo_use_nogood, bo_use_name, bo_use_signature, bo_use_ip_view, bo_use_list_view, bo_use_list_file, bo_use_list_content, bo_table_width, bo_subject_len, bo_mobile_subject_len, bo_page_rows, bo_mobile_page_rows, bo_new, bo_hot, bo_image_width, bo_skin, bo_mobile_skin, bo_include_head, bo_include_tail, bo_content_head, bo_mobile_content_head, bo_content_tail, bo_mobile_content_tail, bo_insert_content, bo_gallery_cols, bo_gallery_width, bo_gallery_height, bo_mobile_gallery_width, bo_mobile_gallery_height, bo_upload_size, bo_reply_order, bo_use_search, bo_order, bo_count_write, bo_count_comment, bo_write_min, bo_write_max, bo_comment_min, bo_comment_max, bo_notice, bo_upload_count, bo_use_email, bo_use_cert, bo_use_sns, bo_sort_field, bo_1_subj, bo_2_subj, bo_3_subj, bo_4_subj, bo_5_subj, bo_6_subj, bo_7_subj, bo_8_subj, bo_9_subj, bo_10_subj, bo_1, bo_2, bo_3, bo_4, bo_5, bo_6, bo_7, bo_8, bo_9, bo_10 )
          values
            ( 'board03', 'group2', '게시판03', '', 'both', '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 100, 40, 20, 3, 15, 24, 100, 600, 'basic', 'basic', '_head.php', '_tail.php', '', '', '', '', '', 4, 174, 124, 125, 100, 1048576, 1, 1, 0, 13, 7, 0, 0, 0, 0, '15', 2, 1, '', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) ";
mysql_query($sql);

// 게시판 생성
$sql = " insert into `{$table_prefix}board`
            ( bo_table, gr_id, bo_subject, bo_mobile_subject, bo_device, bo_admin, bo_list_level, bo_read_level, bo_write_level, bo_reply_level, bo_comment_level, bo_upload_level, bo_download_level, bo_html_level, bo_link_level, bo_count_delete, bo_count_modify, bo_read_point, bo_write_point, bo_comment_point, bo_download_point, bo_use_category, bo_category_list, bo_use_sideview, bo_use_file_content, bo_use_secret, bo_use_dhtml_editor, bo_use_rss_view, bo_use_good, bo_use_nogood, bo_use_name, bo_use_signature, bo_use_ip_view, bo_use_list_view, bo_use_list_file, bo_use_list_content, bo_table_width, bo_subject_len, bo_mobile_subject_len, bo_page_rows, bo_mobile_page_rows, bo_new, bo_hot, bo_image_width, bo_skin, bo_mobile_skin, bo_include_head, bo_include_tail, bo_content_head, bo_mobile_content_head, bo_content_tail, bo_mobile_content_tail, bo_insert_content, bo_gallery_cols, bo_gallery_width, bo_gallery_height, bo_mobile_gallery_width, bo_mobile_gallery_height, bo_upload_size, bo_reply_order, bo_use_search, bo_order, bo_count_write, bo_count_comment, bo_write_min, bo_write_max, bo_comment_min, bo_comment_max, bo_notice, bo_upload_count, bo_use_email, bo_use_cert, bo_use_sns, bo_sort_field, bo_1_subj, bo_2_subj, bo_3_subj, bo_4_subj, bo_5_subj, bo_6_subj, bo_7_subj, bo_8_subj, bo_9_subj, bo_10_subj, bo_1, bo_2, bo_3, bo_4, bo_5, bo_6, bo_7, bo_8, bo_9, bo_10 )
          values
            ( 'board04', 'group2', '게시판04', '', 'both', '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 100, 40, 20, 3, 15, 24, 100, 600, 'basic', 'basic', '_head.php', '_tail.php', '', '', '', '', '', 4, 174, 124, 125, 100, 1048576, 1, 1, 0, 13, 7, 0, 0, 0, 0, '15', 2, 1, '', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) ";
mysql_query($sql);

// 관리자 회원가입
$sql = " insert into `{$table_prefix}member`
            set mb_id = '$admin_id',
                 mb_password = PASSWORD('$admin_pass'),
                 mb_name = '$admin_name',
                 mb_nick = '$admin_name',
                 mb_email = '$admin_email',
                 mb_level = '10',
                 mb_mailling = '1',
                 mb_open = '1',
                 mb_email_certify = '".G5_TIME_YMDHIS."',
                 mb_datetime = '".G5_TIME_YMDHIS."',
                 mb_ip = '{$_SERVER['REMOTE_ADDR']}'
                 ";
@mysql_query($sql);

// 내용관리 생성
@mysql_query(" insert into `{$table_prefix}content` set co_id = 'company', co_html = '1', co_subject = '회사소개', co_content= '<p align=center><b>회사소개에 대한 내용을 입력하십시오.</b></p>' ") or die(mysql_error() . "<p>" . $sql);
@mysql_query(" insert into `{$table_prefix}content` set co_id = 'privacy', co_html = '1', co_subject = '개인정보 처리방침', co_content= '<p align=center><b>개인정보 처리방침에 대한 내용을 입력하십시오.</b></p>' ") or die(mysql_error() . "<p>" . $sql);
@mysql_query(" insert into `{$table_prefix}content` set co_id = 'provision', co_html = '1', co_subject = '서비스 이용약관', co_content= '<p align=center><b>서비스 이용약관에 대한 내용을 입력하십시오.</b></p>' ") or die(mysql_error() . "<p>" . $sql);
// FAQ Master
@mysql_query(" insert into `{$table_prefix}faq_master` set fm_id = '1', fm_subject = '자주하시는 질문' ") or die(mysql_error() . "<p>" . $sql);
?>

        <li class="list-group-item">DB설정 완료</li>

<?php
//-------------------------------------------------------------------------------------------------

// 디렉토리 생성
$dir_arr = array (
    $data_path.'/cache',
    $data_path.'/editor',
    $data_path.'/file',
    $data_path.'/log',
    $data_path.'/member',
    $data_path.'/session',
    $data_path.'/content',
    $data_path.'/faq',
    $data_path.'/tmp'
);

for ($i=0; $i<count($dir_arr); $i++) {
    @mkdir($dir_arr[$i], G5_DIR_PERMISSION);
    @chmod($dir_arr[$i], G5_DIR_PERMISSION);
}
?>

        <li class="list-group-item">데이터 디렉토리 생성 완료</li>

<?php
//-------------------------------------------------------------------------------------------------

// DB 설정 파일 생성
$file = '../'.G5_DATA_DIR.'/'.G5_DBCONFIG_FILE;
$f = @fopen($file, 'a');

fwrite($f, "<?php\n");
fwrite($f, "if (!defined('_GNUBOARD_')) exit;\n");
fwrite($f, "define('G5_MYSQL_HOST', '{$mysql_host}');\n");
fwrite($f, "define('G5_MYSQL_USER', '{$mysql_user}');\n");
fwrite($f, "define('G5_MYSQL_PASSWORD', '{$mysql_pass}');\n");
fwrite($f, "define('G5_MYSQL_DB', '{$mysql_db}');\n");
fwrite($f, "define('G5_MYSQL_SET_MODE', {$mysql_set_mode});\n\n");
fwrite($f, "define('G5_TABLE_PREFIX', '{$table_prefix}');\n\n");
fwrite($f, "\$g5['write_prefix'] = G5_TABLE_PREFIX.'write_'; // 게시판 테이블명 접두사\n\n");
fwrite($f, "\$g5['auth_table'] = G5_TABLE_PREFIX.'auth'; // 관리권한 설정 테이블\n");
fwrite($f, "\$g5['config_table'] = G5_TABLE_PREFIX.'config'; // 기본환경 설정 테이블\n");
fwrite($f, "\$g5['group_table'] = G5_TABLE_PREFIX.'group'; // 게시판 그룹 테이블\n");
fwrite($f, "\$g5['group_member_table'] = G5_TABLE_PREFIX.'group_member'; // 게시판 그룹+회원 테이블\n");
fwrite($f, "\$g5['board_table'] = G5_TABLE_PREFIX.'board'; // 게시판 설정 테이블\n");
fwrite($f, "\$g5['board_file_table'] = G5_TABLE_PREFIX.'board_file'; // 게시판 첨부파일 테이블\n");
fwrite($f, "\$g5['board_good_table'] = G5_TABLE_PREFIX.'board_good'; // 게시물 추천,비추천 테이블\n");
fwrite($f, "\$g5['board_new_table'] = G5_TABLE_PREFIX.'board_new'; // 게시판 새글 테이블\n");
fwrite($f, "\$g5['login_table'] = G5_TABLE_PREFIX.'login'; // 로그인 테이블 (접속자수)\n");
fwrite($f, "\$g5['mail_table'] = G5_TABLE_PREFIX.'mail'; // 회원메일 테이블\n");
fwrite($f, "\$g5['member_table'] = G5_TABLE_PREFIX.'member'; // 회원 테이블\n");
fwrite($f, "\$g5['memo_table'] = G5_TABLE_PREFIX.'memo'; // 메모 테이블\n");
fwrite($f, "\$g5['poll_table'] = G5_TABLE_PREFIX.'poll'; // 투표 테이블\n");
fwrite($f, "\$g5['poll_etc_table'] = G5_TABLE_PREFIX.'poll_etc'; // 투표 기타의견 테이블\n");
fwrite($f, "\$g5['point_table'] = G5_TABLE_PREFIX.'point'; // 포인트 테이블\n");
fwrite($f, "\$g5['popular_table'] = G5_TABLE_PREFIX.'popular'; // 인기검색어 테이블\n");
fwrite($f, "\$g5['scrap_table'] = G5_TABLE_PREFIX.'scrap'; // 게시글 스크랩 테이블\n");
fwrite($f, "\$g5['visit_table'] = G5_TABLE_PREFIX.'visit'; // 방문자 테이블\n");
fwrite($f, "\$g5['visit_sum_table'] = G5_TABLE_PREFIX.'visit_sum'; // 방문자 합계 테이블\n");
fwrite($f, "\$g5['uniqid_table'] = G5_TABLE_PREFIX.'uniqid'; // 유니크한 값을 만드는 테이블\n");
fwrite($f, "\$g5['autosave_table'] = G5_TABLE_PREFIX.'autosave'; // 게시글 작성시 일정시간마다 글을 임시 저장하는 테이블\n");
fwrite($f, "\$g5['cert_history_table'] = G5_TABLE_PREFIX.'cert_history'; // 인증내역 테이블\n");
fwrite($f, "\$g5['qa_config_table'] = G5_TABLE_PREFIX.'qa_config'; // 1:1문의 설정테이블\n");
fwrite($f, "\$g5['qa_content_table'] = G5_TABLE_PREFIX.'qa_content'; // 1:1문의 테이블\n");
fwrite($f, "\$g5['content_table'] = G5_TABLE_PREFIX.'content'; // 내용(컨텐츠)정보 테이블\n");
fwrite($f, "\$g5['faq_table'] = G5_TABLE_PREFIX.'faq'; // 자주하시는 질문 테이블\n");
fwrite($f, "\$g5['faq_master_table'] = G5_TABLE_PREFIX.'faq_master'; // 자주하시는 질문 마스터 테이블\n");
fwrite($f, "\$g5['new_win_table'] = G5_TABLE_PREFIX.'new_win'; // 새창 테이블\n");
fwrite($f, "\$g5['menu_table'] = G5_TABLE_PREFIX.'menu'; // 메뉴관리 테이블\n");
fwrite($f, "?>");

fclose($f);
@chmod($file, G5_FILE_PERMISSION);
?>

        <li class="list-group-item">DB설정 파일 생성 완료 (<?php echo $file ?>)</li>

<?php
// data 디렉토리 및 하위 디렉토리에서는 .htaccess .htpasswd .php .phtml .html .htm .inc .cgi .pl 파일을 실행할수 없게함.
$f = fopen($data_path.'/.htaccess', 'w');
$str = <<<EOD
<FilesMatch "\.(htaccess|htpasswd|[Pp][Hh][Pp]|[Pp]?[Hh][Tt][Mm][Ll]?|[Ii][Nn][Cc]|[Cc][Gg][Ii]|[Pp][Ll])">
Order allow,deny
Deny from all
</FilesMatch>
EOD;
fwrite($f, $str);
fclose($f);
//-------------------------------------------------------------------------------------------------
?>
</ul>

    
<div class="alert alert-success">축하합니다. <?php echo G5_VERSION ?> 설치가 완료되었습니다.</div>


<div class="alert alert-info">
환경설정 변경은 다음의 과정을 따르십시오.
</div>
<ul class="list-group">
  <li class="list-group-item">메인화면으로 이동</li>
  <li class="list-group-item">관리자 로그인</li>
  <li class="list-group-item">관리자 모드 접속</li>
  <li class="list-group-item">환경설정 메뉴의 기본환경설정 페이지로 이동</li>
</ul>

    <div class="text-center">
        <a href="../index.php" class="btn btn-success" style="margin-bottom:15px;">새로운 그누스트랩5로 이동</a>
    </div>


<?php
include_once ('./install.inc2.php');
?>