<?php
$gmnow = gmdate('D, d M Y H:i:s').' GMT';
header('Expires: 0'); // rfc2616 - Section 14.21
header('Last-Modified: ' . $gmnow);
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0

include_once ('../config.php');
$title = G5_VERSION." 초기환경설정 2/3";
include_once ('./install.inc.php');

if (!isset($_POST['agree']) || $_POST['agree'] != '동의함') {
    echo "<div class=\"text-center alert alert-danger\">라이센스(License) 내용에 동의하셔야 설치를 계속하실 수 있습니다.</div>".PHP_EOL;
    echo "<div class=\"text-center\"><a href=\"./\" class=\"btn btn-danger\" style=\"margin-bottom:15px;\">뒤로가기</a></div>".PHP_EOL;
    exit;
}
?>

<form id="frm_install" method="post" action="./install_db.php" autocomplete="off" onsubmit="return frm_install_submit(this)" role="form">

  <div class="col-sm-6">
  <div class="well well-sm"><span class="text-info">호스팅 정보</span></div>
  <div class="form-group">
    <label for="mysql_host">Host</label>
    <input name="mysql_host" type="text" value="localhost" id="mysql_host" class="form-control" placeholder="호스트">
  </div>
  <div class="form-group">
    <label for="mysql_user">User</label>
    <input name="mysql_user" type="text" id="mysql_user" class="form-control" placeholder="DB 아이디">
  </div>
  <div class="form-group">
    <label for="mysql_pass">Password</label>
    <input name="mysql_pass" type="text" id="mysql_pass" class="form-control" placeholder="DB 비밀번호">
  </div>
  <div class="form-group">
    <label for="mysql_db">DB</label>
    <input name="mysql_db" type="text" id="mysql_db" class="form-control" placeholder="DB 명">
  </div>
  <div class="form-group">
    <label for="table_prefix">TABLE명 접두사</label>
    <input name="table_prefix" type="text" value="g5_" id="table_prefix" class="form-control" placeholder="DB 접두사">
    <p class="help-block" style="font-size:14px;">가능한 변경하지마십시오.</p>
  </div>
  </div>

  <div class="col-sm-6">
  <div class="well well-sm"><span class="text-info">관리자 정보</span></div>
  <div class="form-group">
    <label for="admin_id">회원 ID</label>
    <input name="admin_id" type="text" value="admin" id="admin_id" class="form-control" placeholder="관리자 아이디">
  </div>
  <div class="form-group">
    <label for="admin_pass">비밀번호</label>
    <input name="admin_pass" type="text" id="admin_pass" class="form-control" placeholder="관리자 비밀번호">
  </div>
  <div class="form-group">
    <label for="admin_name">이름</label>
    <input name="admin_name" type="text" value="최고관리자" id="admin_name" class="form-control" placeholder="관리자 이름">
  </div>
  <div class="form-group">
    <label for="admin_email">E-mail</label>
    <input name="admin_email" type="text" value="admin@domain.com" id="admin_email" class="form-control" placeholder="관리자 메일">
  </div>
  </div>
<div class="clearfix"></div>
<hr>

    <div class="alert alert-danger">
        <strong class="st_strong">주의! 이미 <?php echo G5_VERSION ?>가 존재한다면 DB 자료가 망실되므로 주의하십시오.</strong><br>
        주의사항을 이해했으며, 그누보드 설치를 계속 진행하시려면 다음을 누르십시오.
    </div>

    <div class="text-center">
        <input type="submit" value="다음" class="btn btn-default" style="margin-bottom:15px;">
    </div>
</form>

<script>
function frm_install_submit(f)
{
    if (f.mysql_host.value == '')
    {
        alert('MySQL Host 를 입력하십시오.'); f.mysql_host.focus(); return false;
    }
    else if (f.mysql_user.value == '')
    {
        alert('MySQL User 를 입력하십시오.'); f.mysql_user.focus(); return false;
    }
    else if (f.mysql_db.value == '')
    {
        alert('MySQL DB 를 입력하십시오.'); f.mysql_db.focus(); return false;
    }
    else if (f.admin_id.value == '')
    {
        alert('최고관리자 ID 를 입력하십시오.'); f.admin_id.focus(); return false;
    }
    else if (f.admin_pass.value == '')
    {
        alert('최고관리자 비밀번호를 입력하십시오.'); f.admin_pass.focus(); return false;
    }
    else if (f.admin_name.value == '')
    {
        alert('최고관리자 이름을 입력하십시오.'); f.admin_name.focus(); return false;
    }
    else if (f.admin_email.value == '')
    {
        alert('최고관리자 E-mail 을 입력하십시오.'); f.admin_email.focus(); return false;
    }


    if(/^[a-z][a-z0-9]/i.test(f.admin_id.value) == false) {
        alert('최고관리자 회원 ID는 첫자는 반드시 영문자 그리고 영문자와 숫자로만 만드셔야 합니다.');
        f.admin_id.focus();
        return false;
    }

    return true;
}
</script>

<?php
include_once ('./install.inc2.php');
?>