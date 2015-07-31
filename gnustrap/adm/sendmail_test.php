<?php
$sub_menu = '100300';
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

if (!$config['cf_email_use'])
    alert('환경설정에서 \'메일발송 사용\'에 체크하셔야 메일을 발송할 수 있습니다.');

include_once(G5_LIB_PATH.'/mailer.lib.php');

$g5['title'] = '메일 테스트';
include_once('./admin.head.php');

if (isset($_POST['email'])) {
    $email = explode(',', $_POST['email']);
    for ($i=0; $i<count($email); $i++)
        mailer($config['cf_admin_email_name'], $config['cf_admin_email'], trim($email[$i]), '[메일검사] 제목', '<span style="font-size:9pt;">[메일검사] 내용<p>이 내용이 제대로 보인다면 보내는 메일 서버에는 이상이 없는것입니다.<p>'.G5_TIME_YMDHIS.'<p>이 메일 주소로는 회신되지 않습니다.</span>', 1);

    echo '<div class="panel panel-success">';
	echo '<div class="panel-heading">';
	echo '<h3 class="panel-title"><strong>결과메세지</strong></h3>';
	echo '</div>';
    echo '<div class="panel-body">';
    echo '다음 '.count($email).'개의 메일 주소로 테스트 메일 발송이 완료되었습니다.';
    echo '</div></div>';
    echo '<ul>';
    for ($i=0;$i<count($email);$i++) {
        echo '<li>'.$email[$i].'</li>';
    }
    echo '</ul>';
    echo '<div class="local_desc02 local_desc"><p>';
    echo '해당 주소로 테스트 메일이 도착했는지 확인해 주십시오.<br>';
    echo '만약, 테스트 메일이 오지 않는다면 더 다양한 계정의 메일 주소로 메일을 보내 보십시오.<br>';
    echo '그래도 메일이 하나도 도착하지 않는다면 메일 서버(sendmail server)의 오류일 가능성이 높으니, 웹 서버관리자에게 문의하여 주십시오.<br>';
    echo '</p></div>';
    echo '</section>';
}
?>

<div class="panel panel-default">
  <div class="panel-heading"><strong>테스트 메일 발송</strong></div>
  <div class="panel-body">
    메일서버가 정상적으로 동작 중인지 확인할 수 있습니다.<br>
            아래 입력칸에 테스트 메일을 발송하실 메일 주소를 입력하시면, [메일검사] 라는 제목으로 테스트 메일을 발송합니다.<br>
  </div>
</div>

    <form name="fsendmailtest" method="post">
    <fieldset id="fsendmailtest">
		<div class="row">
  <div class="col-lg-6">
    <div class="input-group">
      <input type="text"  name="email" value="<?php echo $member['mb_email'] ?>" id="email" required class="required email form-control" size="80" placeholder="받는 메일주소">
      <span class="input-group-btn">
        <button type="submit" class="btn btn-default tooltip-top" title="전송" type="button"><i class="glyphicon glyphicon-envelope"></i></button>
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->
    </fieldset>
    </form>
	<div class="well">
            만약 [메일검사] 라는 내용으로 테스트 메일이 도착하지 않는다면 보내는 메일서버 혹은 받는 메일서버 중 문제가 발생했을 가능성이 있습니다.<br>
            따라서 보다 정확한 테스트를 원하신다면 여러 곳으로 테스트 메일을 발송하시기 바랍니다.
      </div>

<?php
include_once('./admin.tail.php');
?>
