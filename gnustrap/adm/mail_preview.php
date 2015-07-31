<?php
$sub_menu = "200300";
include_once('./_common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

auth_check($auth[$sub_menu], 'r');

$se = sql_fetch("select ma_subject, ma_content from {$g5['mail_table']} where ma_id = '{$ma_id}' ");

$subject = $se['ma_subject'];
$content = conv_content($se['ma_content'], 1) . "<hr size=0><p><span style='font-size:9pt; font-family:굴림'>▶ 더 이상 정보 수신을 원치 않으시면 [<a href='".G5_BBS_URL."/email_stop.php?mb_id=***&amp;mb_md5=***' target='_blank'>수신거부</a>] 해 주십시오.</span></p>";?>

<link rel="stylesheet" href="<?php echo G5_CSS_URL ?>/bootstrap.min.css" type="text/css" rel="stylesheet">
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title><?php echo G5_VERSION ?> 메일발송 테스트</title>
</head>

<hr>
<div class="panel panel-default" style="min-widht:200px; max-width:900px; margin:0 auto;">
  <div class="panel-heading"><?php echo $subject; ?></div>
  <div class="panel-body">
    <?php echo $content; ?>
  </div>
<div class="alert alert-danger" style="margin:20px;">
    <strong>주의!</strong> 이 화면에 보여지는 디자인은 실제 내용이 발송되었을 때 디자인과 다를 수 있습니다.
</div>
</div>

</body>
</html>