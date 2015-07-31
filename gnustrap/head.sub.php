<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$begin_time = get_microtime();

// 사용자가 지정한 head.sub.php 파일이 있다면 include
if(defined('G5_HEAD_SUB_FILE') && is_file(G5_PATH.'/'.G5_HEAD_SUB_FILE)) {
    include_once(G5_PATH.'/'.G5_HEAD_SUB_FILE);
    return;
}

if (!isset($g5['title'])) {
    $g5['title'] = $config['cf_title'];
    $g5_head_title = $g5['title'];
}
else {
    $g5_head_title = $g5['title']; // 상태바에 표시될 제목
    $g5_head_title .= " | ".$config['cf_title'];
}

// 현재 접속자
// 게시판 제목에 ' 포함되면 오류 발생
$g5['lo_location'] = addslashes($g5['title']);
if (!$g5['lo_location'])
    $g5['lo_location'] = addslashes($_SERVER['REQUEST_URI']);
$g5['lo_url'] = addslashes($_SERVER['REQUEST_URI']);
if (strstr($g5['lo_url'], '/'.G5_ADMIN_DIR.'/') || $is_admin == 'super') $g5['lo_url'] = '';

/*
// 만료된 페이지로 사용하시는 경우
header("Cache-Control: no-cache"); // HTTP/1.1
header("Expires: 0"); // rfc2616 - Section 14.21
header("Pragma: no-cache"); // HTTP/1.0
*/
?>
<!doctype html>
<html class="no-js" lang="ko">
<head>
<link rel="shortcut icon" href="fa.ico" />
<meta charset="utf-8">
<?php
if (G5_IS_MOBILE) {
    echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">'.PHP_EOL;
    echo '<meta name="HandheldFriendly" content="true">'.PHP_EOL;
    echo '<meta name="format-detection" content="telephone=no">'.PHP_EOL;
} else {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">'.PHP_EOL;
    echo '<meta name="description
	" content="">'.PHP_EOL;
    echo '<meta http-equiv="imagetoolbar" content="no">'.PHP_EOL;
    echo '<meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1">'.PHP_EOL;
}

if($config['cf_add_meta'])
    echo $config['cf_add_meta'].PHP_EOL;
?>
<title><?php echo $g5_head_title; ?></title>

<?php
if (defined('G5_IS_ADMIN')) {
    echo '<link rel="stylesheet" href="'.G5_ADMIN_URL.'/css/admin.css">'.PHP_EOL;
    echo '<link rel="stylesheet" href="'.G5_ADMIN_URL.'/css/bootstrap.min.css">'.PHP_EOL;
} else {
    $shop_css = '';
    if (defined('_SHOP_')) $shop_css = '_shop';
    echo '<link rel="stylesheet" href="'.G5_CSS_URL.'/gnustrap.css">'.PHP_EOL;
    echo '<link rel="stylesheet" href="'.G5_CSS_URL.'/bootstrap.min.css">'.PHP_EOL;
    echo '<link rel="stylesheet" href="'.G5_CSS_URL.'/'.(G5_IS_MOBILE?'mobile':'default').$shop_css.'.css">'.PHP_EOL;
}
?>

<script>
// 자바스크립트에서 사용하는 전역변수 선언
var g5_url       = "<?php echo G5_URL ?>";
var g5_bbs_url   = "<?php echo G5_BBS_URL ?>";
var g5_is_member = "<?php echo isset($is_member)?$is_member:''; ?>";
var g5_is_admin  = "<?php echo isset($is_admin)?$is_admin:''; ?>";
var g5_is_mobile = "<?php echo G5_IS_MOBILE ?>";
var g5_bo_table  = "<?php echo isset($bo_table)?$bo_table:''; ?>";
var g5_sca       = "<?php echo isset($sca)?$sca:''; ?>";
var g5_editor    = "<?php echo ($config['cf_editor'] && $board['bo_use_dhtml_editor'])?$config['cf_editor']:''; ?>";
var g5_cookie_domain = "<?php echo G5_COOKIE_DOMAIN ?>";
<?php
if ($is_admin) {
    echo 'var g5_admin_url = "'.G5_ADMIN_URL.'";'.PHP_EOL;
}
?>
</script>
<script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<?php
if (defined('_SHOP_')) {
    if(!G5_IS_MOBILE) {
?>
<script src="<?php echo G5_JS_URL ?>/jquery.shop.menu.js"></script>
<?php
    }
} else {
?>
<script src="<?php echo G5_JS_URL ?>/jquery.menu.js"></script>
<?php } ?>
<script src="<?php echo G5_JS_URL ?>/common.js"></script>
<script src="<?php echo G5_JS_URL ?>/wrest.js"></script>

<!--animate 플래쉬 효과-->
<link rel="stylesheet" href="<?php echo G5_CSS_URL ?>/animate.min.css" type="text/css" rel="stylesheet">

<!--animation 플래쉬 효과-->
<link rel="stylesheet" href="<?php echo G5_CSS_URL ?>/animation.css" type="text/css" rel="stylesheet">

<!--텍사스 자동 반응형-->
<script src="<?php echo G5_JS_URL ?>/jquery.expandable.js" type="text/javascript" charset="utf-8"></script>

<!--폰트어썸-->
<link rel="stylesheet" href="<?php echo G5_CSS_URL ?>/font-awesome.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo G5_CSS_URL ?>/font-awesome.min.css" type="text/css" rel="stylesheet">

<!--부트스트랩-->
<script src="<?php echo G5_JS_URL ?>/bootstrap.min.js"></script>
<!--그누스트랩-->
<script src="<?php echo G5_JS_URL ?>/gnustrap.js"></script>

<?php
if(G5_IS_MOBILE) {
    echo '<script src="'.G5_JS_URL.'/modernizr.custom.70111.js"></script>'.PHP_EOL; // overflow scroll 감지
}
if(!defined('G5_IS_ADMIN'))
    echo $config['cf_add_script'];
?>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="<?php echo G5_JS_URL ?>/html5.js"></script>
  <script src="<?php echo G5_JS_URL ?>/respond.min.js"></script>
<![endif]-->
</head>
<body id="top" class="index" data-twttr-rendered="true" <?php echo isset($g5['body_script']) ? $g5['body_script'] : ''; ?>>
<?php
if ($is_member) { // 회원이라면 로그인 중이라는 메세지를 출력해준다.
    $sr_admin_msg = '';
    if ($is_admin == 'super') $sr_admin_msg = "최고관리자 ";
    else if ($is_admin == 'group') $sr_admin_msg = "그룹관리자 ";
    else if ($is_admin == 'board') $sr_admin_msg = "게시판관리자 ";

    echo '<div id="hd_login_msg">'.$sr_admin_msg.$member['mb_nick'].'님 로그인 중 ';
    echo '<a href="'.G5_BBS_URL.'/logout.php">로그아웃</a></div>';
}
?>
<!--모달 팝업창 -->
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
</div>