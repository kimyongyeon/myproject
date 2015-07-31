<?php
$sub_menu = "900200";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$g5['title'] = "회원정보 업데이트";

include_once(G5_ADMIN_PATH.'/admin.head.php');
?>

<div id="sms5_mbup">
    <form name="mb_update_form" id="mb_update_form" action="./member_update_run.php" >
    <div class="alert alert-warning">
     새로운 회원정보로 업데이트 합니다.<br>
     <strong>주의!</strong> 실행 후 '완료' 메세지가 나오기 전에 프로그램의 실행을 <strong>중지</strong>하지 마십시오.
    </div>
    
    <div class="well well-sm">
            마지막 업데이트 일시 : <span id="datetime"><?php echo $sms5['cf_datetime']?></span> <br>
    </div>

    <div id="res_msg">
    </div>

    <div class="center">
        <input type="submit" value="실행" class="btn btn-default">
    </div>
    </form>
</div>

<script>
(function($){
    $( "#mb_update_form" ).submit(function( e ) {
        e.preventDefault();
        $("#res_msg").html('업데이트 중입니다. 잠시만 기다려 주십시오...');
        var params = { mtype : 'json' };
        $.ajax({
            url: $(this).attr("action"),
            cache:false,
            timeout : 30000,
            dataType:"json",
            data:params,
            success: function(data) {
                $("#datetime").html( data.datetime );
                $("#res_msg").html( data.res_msg );
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
        return false;
    });
})(jQuery);
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>