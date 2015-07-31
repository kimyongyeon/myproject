<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
?>
<div class="row">
    <div id="contentdiv" class="contcustom">
        <span class="fa fa-spinner bigicon"></span>
        <h2>Sign-In</h2>
        <form action="<?php echo $login_action_url ?>" method="post">
        <input type="hidden" name="url" value="<?php echo $login_url ?>">
        <div>
            <input id="username" name="mb_id"  type="text" placeholder="username" onkeypress="check_values();">
            <input id="password" name="mb_password" type="password" placeholder="password" onkeypress="check_values();">
            <button id="button1" class="btn btn-default wide hidden"><span class="fa fa-check med"></span></button>
            <button class="btn btn-default wide"><span class="fa fa-lock med"></span></button>
        </div>
        </form>
        <div id="error_text"><?php echo $error_text ?></div>
        <h4>or social network</h4>
            <h5>Coming soon</h5>
    </div>
</div>

<script type="text/javascript">
jQuery(function ($) {
    function check_values() {
        if ($("#username").val().length != 0 && $("#password").val().length != 0) {
            $("#button1").removeClass("hidden").animate({ left: '250px' });
            $("#lock1").addClass("hidden").animate({ left: '250px' });
        }
    }
});
</script>

<style>
    .redborder {
        border: 2px solid #f96145;
        border-radius: 2px;
    }

    .hidden {
        display: none;
    }

    .visible {
        display: normal;
    }

    body{
        background-color: #F0EEEE;
    }

    .row {
        padding: 20px 0px;
    }

    .bigicon {
        font-size: 97px;
        color: #f96145;
    }

    .contcustom {
        text-align: center;
        width: 300px;
        border-radius: 0.5rem;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: 10px auto;
        background-color: white;
        padding: 20px;
    }

    input {
        width: 100%;
        margin-bottom: 17px;
        padding: 15px;
        background-color: #ECF4F4;
        border-radius: 2px;
        border: none;
    }

    h2 {
        margin-bottom: 20px;
        font-weight: bold;
        color: #ABABAB;
    }

    .btn {
        border-radius: 2px;
        padding: 10px;
    }

    .med {
        font-size: 27px;
        color: white;
    }

    .medhidden {
        font-size: 27px;
        color: #f96145;
        padding: 10px;
        width: 100%;
    }

    .wide {
        background-color: #8EB7E4;
        width: 100%;
        -webkit-border-top-right-radius: 0;
        -webkit-border-bottom-right-radius: 0;
        -moz-border-radius-topright: 0;
        -moz-border-radius-bottomright: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
</style>