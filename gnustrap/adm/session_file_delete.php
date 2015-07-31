<?php
$sub_menu = "100800";
include_once("./_common.php");

if ($is_admin != "super")
    alert("최고관리자만 접근 가능합니다.", G5_URL);

$g5['title'] = "세션파일 일괄삭제";
include_once("./admin.head.php");
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
    flush();

    $list_tag_st = "";
    $list_tag_end = "";
    if (!$dir=@opendir(G5_DATA_PATH.'/session')) {
      echo "<p>세션 디렉토리를 열지못했습니다.</p>";
    } else {
        $list_tag_st = "<div class='well well-sm'>완료됨";
        $list_tag_end = "</div>\n";
    }

    $cnt=0;
    echo $list_tag_st;
    while($file=readdir($dir)) {

        if (!strstr($file,'sess_')) continue;
        if (strpos($file,'sess_')!=0) continue;

        $session_file = G5_DATA_PATH.'/session/'.$file;

        if (!$atime=@fileatime($session_file)) {
            continue;
        }
        if (time() > $atime + (3600 * 6)) {  // 지난시간을 초로 계산해서 적어주시면 됩니다. default : 6시간전
            $cnt++;
            $return = unlink($session_file);
            //echo "<script>document.getElementById('ct').innerHTML += '{$session_file}<br/>';</script>\n";
            echo "<div class='well well-sm'>{$session_file}</div>\n";

            flush();

            if ($cnt%10==0)
                //echo "<script>document.getElementById('ct').innerHTML = '';</script>\n";
                echo "\n";
        }
    }
    echo $list_tag_end;
	echo '<div class="alert alert-success">
    <strong>세션데이터 '.$cnt.'건 삭제 완료됐습니다.</strong>
	<br />프로그램의 실행을 끝마치셔도 좋습니다.</div>'.PHP_EOL;
?>

<?php
include_once("./admin.tail.php");
?>
