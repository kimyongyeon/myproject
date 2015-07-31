<?php
$sub_menu = '100400';
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '부가서비스';
include_once('./admin.head.php');
?>

<div class="well">
    아래의 서비스들은 그누보드에서 이미 지원하는 기능으로 별도의 개발이 필요 없으며 서비스 신청후 바로 사용 할수 있습니다.
</div>

<div class="row">
<div class="col-md-4">
<div class="sevice_1" style="margin-top: 15px;">
        <i class="fa fa-mobile-phone fa-5x" style="border: 1px solid #041198; color: #001B88; padding: 25px 45px; margin-top: 30px; border-radius: 50%;"></i>
        <h3>휴대폰 본인확인 서비스</h3>
        <p>정보통신망법 23조 2항(주민등록번호의 사용제한)에 따라 기존 주민등록번호 기반의 인증서비스 이용이 불가합니다. 주민등록번호 대체수단으로 최소한의 정보(생년월일, 휴대폰번호, 성별)를 입력받아 본인임을 확인하는 인증수단 입니다</p>
    <hr />
    <div class="text-center">
            <a href="http://sir.co.kr/main/service/p_cert.php" target="_blank" class="btn btn-default" style="margin-bottom:20px;">KCP 휴대폰 본인확인 신청</a>
            <a href="http://sir.co.kr/main/service/lg_cert.php" target="_blank" class="btn btn-default" style="margin-bottom:20px;">LG유플러스 휴대폰대체인증 신청</a>
            <a href="http://sir.co.kr/main/service/b_cert.php" target="_blank" class="btn btn-default" style="margin-bottom:20px;">OKname 휴대폰 본인확인 신청</a>
	</div>
    </div>
</div>
<div class="col-md-4">
<div class="sevice_1" style="margin-top: 15px;">
        <i class="fa fa-chain fa-4x" style="border: 1px solid #C70000; color: #6B0000; padding: 32px 34px; margin-top: 30px; border-radius: 50%;"></i>
        <h3>아이핀 본인확인 서비스</h3>
        <p>정부가 주관하는 주민등록번호 대체 수단으로 본인의 개인정보를 아이핀 사이트에 한번만 발급해 놓고, 이후부터는 아이디와 패스워드 만으로
        본인임을 확인하는 인증수단 입니다.</p>
    <hr />
	<div class="text-center"><a href="http://sir.co.kr/main/service/b_ipin.php" target="_blank" class="btn btn-default" style="margin-bottom:20px;">OKname 아이핀 본인확인 신청</a></div>
    </div>
</div>
<div class="col-md-4">
<div class="sevice_1" style="margin-top: 15px;">
        <i class="fa fa-envelope-o fa-4x" style="border: 1px solid #49C700; color: #1F6B00;padding: 32px 34px; margin-top: 30px; border-radius: 50%;"></i>
        <h3>SMS 문자 서비스</h3>
        <p>사이트 관리자 또는 회원이 다른 회원의 휴대폰으로 단문메세지(최대 한글 40자, 영문 80자)를 발송할 수 있습니다.</p>
    <hr />
	<div class="text-center"><a href="http://icodekorea.com/res/join_company_fix_a.php?sellid=sir2" target="_blank" class="btn btn-default" style="margin-bottom:20px;">ICODE 신청</a></div>
    </div>
</div>
<?php
include_once('./admin.tail.php');
?>
