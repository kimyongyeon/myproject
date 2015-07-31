<?php
define('_INDEX_', true);
include_once('./_common.php');

// 초기화면 파일 경로 지정 : 이 코드는 가능한 삭제하지 마십시오.
if ($config['cf_include_index'] && is_file(G5_PATH.'/'.$config['cf_include_index'])) {
    include_once(G5_PATH.'/'.$config['cf_include_index']);
    return; // 이 코드의 아래는 실행을 하지 않습니다.
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/index.php');
    return;
}

include_once('./_head.php');
?>

<div class="row">
<div class="col-md-4">
<?php echo latest("basic", "board01", 4, 34); ?>
</div>
<div class="visible-xs"><hr><!--모바일에서 간격을 주기--></div>
<div class="col-md-4">
<?php echo latest("basic", "board02", 4, 34); ?>
</div>
<div class="visible-xs"><hr><!--모바일에서 간격을 주기--></div>
<div class="col-md-4">
<?php echo latest("basic", "board03", 4, 34); ?>
</div>
</div>


<?php
include_once('./_tail.php');
?>