<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
?>

<nav class="navbar navbar-default navbar-custom navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo G5_URL ?>/">boan.pw</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
<?php
$sql = " select * from {$g5['menu_table']} where me_use = '1' and length(me_code) = '2' order by me_order, me_id ";
$result = sql_query($sql, false);

for ($i=0; $row=sql_fetch_array($result); $i++) {
?>
                <li>
                    <a href="<?php echo $row['me_link']; ?>"><?php echo $row['me_name'] ?></a>
                </li>
<?php
}
?>
<?php if ($is_member) {  ?>
<?php if ($is_admin) {  ?>
                <li>
                    <a href="<?php echo G5_ADMIN_URL ?>">ADMIN</a>
                </li>
<?php }  ?>
                <li>
                    <a href="<?php echo G5_BBS_URL ?>/logout.php">Logout</a>
                </li>
<?php } else {  ?>
                <li>
                    <a href="<?php echo G5_BBS_URL ?>/login.php">Sign In</a>
                </li>
<?php }  ?>
            </ul>
        </div>
    </div>
</nav>