<?php
$sub_menu = '100920';
include_once('./_common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.', G5_URL);

$g5['title'] = '썸네일 일괄삭제';
include_once('./admin.head.php');
?>

<div class="alert alert-warning">
    <strong>주의!</strong> 완료 메세지가 나오기 전에 프로그램의 실행을 <strong>중지</strong>하지 마십시오.
</div>

                   <div class='progress progress-striped active'>
                        <div class='progress-bar'  role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width: 100%'>
                        <span class='sr-only'>100% Complete</span>
                        </div>
                   </div>

<?php
$directory = array();
$dl = array('file', 'editor');

foreach($dl as $val) {
    if($handle = opendir(G5_DATA_PATH.'/'.$val)) {
        while(false !== ($entry = readdir($handle))) {
            if($entry == '.' || $entry == '..')
                continue;

            $path = G5_DATA_PATH.'/'.$val.'/'.$entry;

            if(is_dir($path))
                $directory[] = $path;
        }
    }
}

flush();

if (empty($directory)) {
    echo '<p>썸네일디렉토리를 열지못했습니다.</p>';
}

$cnt=0;
echo '<div>'.PHP_EOL;

foreach($directory as $dir) {
    $files = glob($dir.'/thumb-*');
    if (is_array($files)) {
        foreach($files as $thumbnail) {
            $cnt++;
            @unlink($thumbnail);

            echo '<div class="well well-sm">'.$thumbnail.'</div>'.PHP_EOL;

            flush();

            if ($cnt%10==0)
                echo PHP_EOL;
        }
    }
}

echo '<div class="well well-sm">완료됨</div></div>'.PHP_EOL;
echo '<div class="alert alert-success">
    <strong>썸네일 '.$cnt.'건의 삭제 완료됐습니다.</strong>
	<br />프로그램의 실행을 끝마치셔도 좋습니다.</div>'.PHP_EOL;
?>

<?php
include_once('./admin.tail.php');
?>