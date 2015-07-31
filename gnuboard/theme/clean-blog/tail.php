<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<hr>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <ul class="list-inline text-center">
                    <li>
                        <a href="#">
                            <span class="fa-stack fa-lg">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa-stack fa-lg">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa-stack fa-lg">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-github fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                    </li>
                </ul>
                <p class="copyright text-muted">Copyright &copy; boan.pw 2015</p>
            </div>
        </div>
    </div>
</footer>
<script src="<?php echo G5_THEME_JS_URL; ?>/jquery.js"></script>
<script src="<?php echo G5_THEME_JS_URL; ?>/bootstrap.min.js"></script>
<?php
if(!defined('_THEME_WRITE_')){
?>
<script src="<?php echo G5_THEME_JS_URL; ?>/clean-blog.min.js"></script>
<?php
}
include_once(G5_THEME_PATH."/tail.sub.php");
?>