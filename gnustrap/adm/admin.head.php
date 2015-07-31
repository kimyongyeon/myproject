<?php
if (!defined('_GNUBOARD_')) exit;

$begin_time = get_microtime();

include_once(G5_PATH.'/head.sub.php');

function print_menu1($key, $no)
{
    global $menu;

    $str = print_menu2($key, $no);

    return $str;
}

function print_menu2($key, $no)
{
    global $menu, $auth_menu, $is_admin, $auth, $g5;

    $str .= "<ul class=\"gnb_2dul\">";
    for($i=1; $i<count($menu[$key]); $i++)
    {
        if ($is_admin != 'super' && (!array_key_exists($menu[$key][$i][0],$auth) || !strstr($auth[$menu[$key][$i][0]], 'r')))
            continue;

        if (($menu[$key][$i][4] == 1 && $gnb_grp_style == false) || ($menu[$key][$i][4] != 1 && $gnb_grp_style == true)) $gnb_grp_div = 'gnb_grp_div';
        else $gnb_grp_div = '';

        if ($menu[$key][$i][4] == 1) $gnb_grp_style = 'gnb_grp_style';
        else $gnb_grp_style = '';

        $str .= '<li class="gnb_2dli"><a href="'.$menu[$key][$i][2].'" class="gnb_2da '.$gnb_grp_style.' '.$gnb_grp_div.'">'.$menu[$key][$i][1].'</a></li>';

        $auth_menu[$menu[$key][$i][0]] = $menu[$key][$i][1];
    }
    $str .= "</ul>";

    return $str;
}
?>

<script>
var tempX = 0;
var tempY = 0;

function imageview(id, w, h)
{

    menu(id);

    var el_id = document.getElementById(id);

    //submenu = eval(name+".style");
    submenu = el_id.style;
    submenu.left = tempX - ( w + 11 );
    submenu.top  = tempY - ( h / 2 );

    selectBoxVisible();

    if (el_id.style.display != 'none')
        selectBoxHidden(id);
}
</script>
<div id="to_content"><a href="#container">본문 바로가기</a></div>
<!--<?php
            $gnb_str = "<ul id=\"gnb_1dul\">";
            foreach($amenu as $key=>$value) {
                $href1 = $href2 = '';
                if ($menu['menu'.$key][0][2]) {
                    $href1 = '<a href="'.$menu['menu'.$key][0][2].'" class="gnb_1da">';
                    $href2 = '</a>';
                } else {
                    continue;
                }
                $current_class = "";
                if (isset($sub_menu) && (substr($sub_menu, 0, 3) == substr($menu['menu'.$key][0][0], 0, 3)))
                    $current_class = " gnb_1dli_air";
                $gnb_str .= '<li class="gnb_1dli'.$current_class.'">'.PHP_EOL;
                $gnb_str .=  $href1 . $menu['menu'.$key][0][1] . $href2;
                $gnb_str .=  print_menu1('menu'.$key, 1);
                $gnb_str .=  "</li>";
            }
            $gnb_str .= "</ul>";
            echo $gnb_str;
            ?>
			<?php if($sub_menu) { ?>
<ul id="lnb">
<?php
$menu_key = substr($sub_menu, 0, 3);
$nl = '';
foreach($menu['menu'.$menu_key] as $key=>$value) {
    if($key > 0) {
        if ($is_admin != 'super' && (!array_key_exists($value[0],$auth) || !strstr($auth[$value[0]], 'r')))
            continue;

        if($value[3] == 'cf_service')
            $svc_class = ' class="lnb_svc"';
        else
            $svc_class = '';

        echo $nl.'<li><a href="'.$value[2].'"'.$svc_class.'>'.$value[1].'</a></li>';
        $nl = PHP_EOL;
    }
}
?>
</ul>
<?php } ?>-->

<!--jasny 개발자의 부트스트랩 LESS 파일 호출하기-->
<link href="<?php echo G5_ADMIN_URL ?>/css/jasny-bootstrap.min.css" rel="stylesheet">
<script src="<?php echo G5_ADMIN_URL ?>/jasny-bootstrap.min.js"></script>

<div class="navbar navbar-default" role="navigation">
        <div class="navbar-inner">
		
            <?php if($sub_menu) { //서브에서만 버튼출력 ?>
                <button type="button" class="navbar-toggle animated flip visible-xs" data-toggle="offcanvas" data-target="#myNavmenu" data-canvas="body" style="float: left;">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                </button>
			<?}?>

            <a class="navbar-brand hidden-xs" href="<?php echo G5_ADMIN_URL ?>">
            <span>ADMINISTRATOR</span>
            </a>

            <a class="navbar-brand visible-xs" href="<?php echo G5_ADMIN_URL ?>" style="margin-right:-20px;">
			<span>ADMIN</span>
			</a>

            <!-- user dropdown starts -->
			<div class="btn-sm pull-right theme-container animated tada dropdown" style="z-index: 9999;">
          <a id="drop4" class="btn btn-default" role="button" data-toggle="dropdown" href="#">
		  <i class="glyphicon glyphicon-user"></i> <span class="hidden-sm hidden-xs"> ADMIN</span>
		  <b class="caret"></b>
		  </a>
          <ul id="menu1" class="dropdown-menu" role="menu" aria-labelledby="drop4">
                    <li><a href="<?php echo G5_ADMIN_URL ?>/member_form.php?w=u&amp;mb_id=<?php echo $member['mb_id'] ?>">관리자정보</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo G5_ADMIN_URL ?>/config_form.php">기본환경</a></li>
                    <li class="divider"></li>
					<li><a href="<?php echo G5_ADMIN_URL ?>/service.php">부가서비스</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo G5_URL ?>/">커뮤니티</a></li>
                    <li class="divider"></li>
                    <?php if(defined('G5_USE_SHOP')) { ?>
                    <li><a href="<?php echo G5_SHOP_URL ?>/">쇼핑몰</a></li>
                    <li class="divider"></li>
                    <?php } ?>
                    <li id="tnb_logout"><a href="<?php echo G5_BBS_URL ?>/logout.php">로그아웃</a></li>
          </ul>
            </div>
            <!-- user dropdown ends -->

            <!-- theme selector starts -->
			<div class="btn-sm pull-right theme-container animated tada dropdown" style="z-index:9999;">
          <a id="drop4" class="btn btn-default" role="button" data-toggle="dropdown" href="#">
		  <i class="glyphicon glyphicon-tint"></i> <span class="hidden-sm hidden-xs">MENU</span>
		  <b class="caret"></b>
		  </a>
          <ul id="menu1" class="dropdown-menu" role="menu" aria-labelledby="drop4">
                    <li><a href="<?php echo G5_ADMIN_URL ?>/config_form.php">환경설정</a></li>
                    <li class="divider"></li>
				    <li><a href="<?php echo G5_ADMIN_URL ?>/member_list.php">회원관리</a></li>
                    <li class="divider"></li>
					<li><a href="<?php echo G5_ADMIN_URL ?>/board_list.php">게시판관리</a></li>
                    <li class="divider"></li>
                    <?php if(defined('G5_USE_SHOP')) { ?>
                    <li><a href="<?php echo G5_ADMIN_URL ?>/shop_admin/">쇼핑몰관리</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo G5_ADMIN_URL ?>/shop_admin/itemsellrank.php">쇼핑몰현황/기타</a></li>
                    <li class="divider"></li>
                    <?php } ?>
					<li><a href="<?php echo G5_ADMIN_URL ?>/sms_admin/config.php">SMS 관리</a></li>
          </ul>
            </div>
            <!-- theme selector ends -->

        </div>
    </div>
<!-- topbar ends -->

<div class="ch-container">
    <div class="row">

<!-- left menu starts -->
<?php if($sub_menu) { ?>
<div class="col-sm-2 col-lg-2">
            <div class="sidebar-nav">
                <div class="nav-canvas">

                    <ul class="nav nav-pills nav-stacked main-menu">
					<li class="nav-header">서브메뉴</li>
                    <?php
                    $menu_key = substr($sub_menu, 0, 3);
                    $nl = '';
                    foreach($menu['menu'.$menu_key] as $key=>$value) {
                        if($key > 0) {
                        if ($is_admin != 'super' && (!array_key_exists($value[0],$auth) || !strstr($auth[$value[0]], 'r')))
                        continue;

                        echo $nl.'<li><a href="'.$value[2].'"><i class="glyphicon glyphicon-plus"></i> <span> '.$value[1].'</span></a></li>';
                        $nl = PHP_EOL;
                      }
                     }?>
				<!-- 모바일에서 jasny 개발자 메뉴 펼치기 사용 -->
                   <nav id="myNavmenu" class="navmenu navbar navbar-default navmenu-fixed-left offcanvas" role="navigation">
				   
                    <ul class="nav nav-pills nav-stacked">
					<li class="navbar-brand">Sub Menu</li>
                    <?php
                    $menu_key = substr($sub_menu, 0, 3);
                    $nl = '';
                    foreach($menu['menu'.$menu_key] as $key=>$value) {
                        if($key > 0) {
                        if ($is_admin != 'super' && (!array_key_exists($value[0],$auth) || !strstr($auth[$value[0]], 'r')))
                        continue;

                        echo $nl.'<li><a href="'.$value[2].'"><i class="glyphicon glyphicon-plus"></i> <span> '.$value[1].'</span></a></li>';
                        $nl = PHP_EOL;
                      }
                     }?>
					 </ul>
					 </nav>
			    <!-- jasny 개발자 메뉴 펼치기 끝 -->
                </div>
            </div>
        </div>
<!-- left menu end -->

		<div id="content" class="col-lg-10 col-sm-10">
        <ul class="breadcrumb">
        <li>
           <i class="glyphicon glyphicon-home"></i> HOME
        </li>
        <li>
           <i class="glyphicon glyphicon-eye-open"></i> <?php echo $g5['title'] ?>
        </li>
        </ul>

<?php } ?>