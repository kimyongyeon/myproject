<?php
include_once(G5_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// 상단 파일 경로 지정 : 이 코드는 가능한 삭제하지 마십시오.
if ($config['cf_include_head']) {
    if (!@include_once($config['cf_include_head'])) {
        die('기본환경 설정에서 상단 파일 경로가 잘못 설정되어 있습니다.');
    }
    return; // 이 코드의 아래는 실행을 하지 않습니다.
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/head.php');
    return;
}
    if (array_key_exists('mb_point', $member)) {
        $point = number_format($member['mb_point']);
    }
    // 읽지 않은 쪽지가 있다면
    if ($is_member) {
        $sql = " select count(*) as cnt from {$g5['memo_table']} where me_recv_mb_id = '{$member['mb_id']}' and me_read_datetime = '0000-00-00 00:00:00' ";
        $row = sql_fetch($sql);
        $memo_not_read = $row['cnt'];

        $is_auth = false;
        $sql = " select count(*) as cnt from {$g5['auth_table']} where mb_id = '{$member['mb_id']}' ";
        $row = sql_fetch($sql);
        if ($row['cnt'])
            $is_auth = true;
    }
?>
<script>
//* 메뉴 스크립트 *//
var manu = function () {
	"use strict";
	var chartColors = ['#eee'];
	return { init: init, chartColors: chartColors, debounce: debounce };

	function init () {
		initLayout ();	
	}

	function initLayout () {
		$('html').removeClass ('no-js');
		Nav.init ();	
	}

	function debounce (func, wait, immediate) {
		var timeout, args, context, timestamp, result;
		return function() {
			context = this;
			args = arguments;
			timestamp = new Date();

			var later = function() {
				var last = (new Date()) - timestamp;
				if (last < wait) {
					timeout = setTimeout(later, wait - last);
				} else {
					timeout = null;
					if (!immediate) result = func.manuly(context, args);
				}
			};
			var callNow = immediate && !timeout;
			if (!timeout) {
				timeout = setTimeout(later, wait);
			}
			if (callNow) result = func.manuly(context, args);
			return result;
		};
	}
}();
var Nav = function () {
	return { init: init };
	function init () {
		var mainnav = $('#left-menu'),
			openActive = mainnav.is ('.open-active'),
			navActive = mainnav.find ('> .active');

		mainnav.find ('> .dropdown > a').bind ('click', navClick);
		
		if (openActive && navActive.is ('.dropdown')) {			
			navActive.addClass ('opened').find ('.sub-menu').show ();
		}
	}
	
	function navClick (e) {
		e.preventDefault ();
		
		var li = $(this).parents ('li');		
		
		if (li.is ('.opened')) { 
			closeAll ();			
		} else { 
			closeAll ();
			li.addClass ('opened').find ('.sub-menu').slideDown ();			
		}
	}
	
	function closeAll () {	
		$('.sub-menu').slideUp ().parents ('li').removeClass ('opened');
	}
}();
$(function () {
	manu.init ();
});
</script>
<!-- 상단 시작 { -->
    <?php
    if(defined('_INDEX_')) { // index에서만 실행
        include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
    }
    ?>
<div id="wrapper">
	<header id="header">
	    <!--로고-->
	    <h2 id="site-logo">
			<a href="<?php echo G5_URL ?>">
				<img src="<?php echo G5_URL ?>/img/logo.png" alt="사이트 로고">
			</a>
		</h2>
		<!--로고 끝-->
		<a href="javascript:;" data-toggle="collapse" data-target=".top-bar-collapse" id="top-bar-toggle" class="navbar-toggle collapsed">
			<i class="fa fa-plus"></i>
		</a>
		<a href="javascript:;" data-toggle="collapse" data-target=".sidebar-collapse" id="sidebar-toggle" class="navbar-toggle collapsed">
			<i class="fa fa-reorder"></i>
		</a>
	</header>

	<nav id="top-bar" class="collapse top-bar-collapse">
		<ul class="nav navbar-nav pull-left">
		    <li><a href="<?php echo G5_BBS_URL.'/new.php'?>"><i class="fa fa-spinner fa-spin fa-fw"></i> 새글</a></li>
		    <li><a href="<?php echo G5_BBS_URL.'/search.php'?>"><i class="fa fa-search fa-fw"></i> 검색</a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/faq.php"><i class="fa fa-question-circle fa-fw"></i> FAQ</a></li><li id="header_inbox_bar" class="dropdown">
			<li><a href="<?php echo G5_BBS_URL ?>/current_connect.php"><i class="fa fa-users fa-fw"></i> 접속자 <span class="badge"><?php echo connect(); // 현재 접속자수  ?></span></a></li>
		</ul>
		<ul class="nav navbar-nav pull-right">
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
					<i class="fa fa-user fa-fw"></i> 멤버 페이지
		        	<span class="caret"></span>
		    	</a>

		    	<ul class="dropdown-menu" role="menu">
			      <?php if ($is_member) {  ?>
				  <li><a href="<?php echo G5_BBS_URL ?>/memo.php" onclick="win_memo(this.href); return false;"><i class="fa fa-envelope-o fa-fw"></i> 쪽지함 <span class="badge"><?php echo $memo_not_read ?></span></a></li>
				  <li><a href="<?php echo G5_BBS_URL ?>/point.php" onclick="win_point(this.href); return false;"><i class="fa fa-money fa-fw"></i> <?php echo $point ?> 점</a></li>
				  <li><a href="<?php echo G5_BBS_URL ?>/scrap.php" onclick="win_scrap(this.href); return false;"><i class="fa fa-archive fa-fw"></i> 스크랩</a></li>
                  <li><a href="<?php echo G5_BBS_URL ?>/qalist.php"><i class="fa fa-question fa-fw"></i> 1:1문의</a></li>
                  <li><a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=<?php echo G5_BBS_URL ?>/register_form.php"><i class="fa fa-user-secret fa-fw"></i> 정보수정</a></li>
                  <li><a href="<?php echo G5_BBS_URL ?>/logout.php"><i class="fa fa-sign-out fa-fw"></i> 로그아웃</a></li>
                  <?php } else {  ?>
                  <li><a href="<?php echo G5_BBS_URL ?>/register.php"><i class="fa fa-user fa-fw"></i> 회원가입</a></li>
                  <li><a href="<?php echo G5_BBS_URL ?>/login.php"><i class="fa fa-sign-in fa-fw"></i> 로그인</a></li>
                  <?php }  ?>
				  <?php if (defined('G5_USE_SHOP') && G5_USE_SHOP) { ?>
				  <li><a href="<?php echo G5_SHOP_URL ?>/"><i class="fa fa-shopping-cart fa-fw"></i> 쇼핑몰</a></li>
				  <?php } ?>
                  <?php if ($is_admin) {  ?>
                  <li><a href="<?php echo G5_ADMIN_URL ?>" style="color:#b30000;"><i class="fa fa-cog fa-fw"></i> 관리자</a></li>
                  <?php }  ?>
		    	</ul>
		    </li>
		</ul>
	</nav>

	<div id="sidebar-wrapper" class="collapse sidebar-collapse">
		<div id="layout-line"></div>
        <nav id="sidebar">
		   <ul id="left-menu" class="open-active">
				<h3><i class="fa fa-th-list"></i> CATEGORIES</h3>
				        <?php
                        $sql = " select *
                        from {$g5['menu_table']}
                        where me_use = '1'
                          and length(me_code) = '2'
                        order by me_order, me_id ";
                        $result = sql_query($sql, false);
                        $gnb_zindex = 999; // gnb_1dli z-index 값 설정용
                        for ($i=0; $row=sql_fetch_array($result); $i++) {
                        ?>
                <li class="dropdown">
				<a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>">
				<i class="fa fa-tasks"></i><?php echo $row['me_name'] ?><span class="caret"></span></a>
                        <?php
                        $sql2 = " select *
                        from {$g5['menu_table']}
                        where me_use = '1'
                        and length(me_code) = '4'
                        and substring(me_code, 1, 2) = '{$row['me_code']}'
                        order by me_order, me_id ";
                        $result2 = sql_query($sql2);
						for ($k=0; $row2=sql_fetch_array($result2); $k++) {
							if($k == 0)
								echo '<ul class="sub-menu" style="display: none;">'.PHP_EOL;
                        ?>
                    <li><a href="<?php echo $row2['me_link']; ?>" target="_<?php echo $row2['me_target']; ?>"><?php echo $row2['me_name'] ?></a></li>
                    <?php } if($k > 0) echo '</ul>'.PHP_EOL; ?>
                </li>
                <?php } if ($i == 0) {  ?>
                <li id="gnb_empty">메뉴 준비 중입니다.<?php if ($is_admin) { ?> <br><a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
                <?php } ?>
				<li><?php echo visit('basic'); // 접속자  ?></li>
				<li><?php echo poll('basic'); // 설문조사  ?></li>
		</nav>
	</div>

	<div id="content">
	<div id="content-main">
	
	<div class="content-box">
	    <? 
        $gr = sql_fetch("select * from g5_group where gr_id='".$gr_id."' "); //게시판 그룹을 불러오기 
        $bo = sql_fetch("select * from g5_board where bo_table ='".$bo_table."' "); // 게시판을 불러와 게시판 제목, 그림, 메뉴 표현등에 사용한다. 
        ?> 
        <i class="fa fa-home"></i> 메인 <? if($gr[gr_id]) { echo " > ".$gr['gr_subject']; } ?> <? if($bo[bo_subject]) { echo " > <strong>".$bo[bo_subject]."</strong>"; } ?>
	</div>