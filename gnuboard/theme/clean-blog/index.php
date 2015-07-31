<?php
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH.'/head.php');
?>
<header class="intro-header" style="background-image: url('<?php echo G5_THEME_IMG_URL ?>/home-bg.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <div class="site-heading">
                    <h1>익명닉네임 블로그</h1>
                    <hr class="small">
                    <span class="subheading">그누보드5 테마 베타버전 으로 구축된 블로그 입니다.</span>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
<?php echo latest('theme/basic', board, 5, 25); ?>
        </div>
    </div>
</div>

<?php
include_once(G5_THEME_PATH.'/tail.php');
?>