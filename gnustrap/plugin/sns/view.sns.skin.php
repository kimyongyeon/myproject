<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (!$board['bo_use_sns']) return;

$sns_msg = urlencode(str_replace('\"', '"', $view['subject']));
//$sns_url = googl_short_url('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
//$msg_url = $sns_msg.' : '.$sns_url;

/*
$facebook_url  = 'http://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$sns_url.'&p[title]='.$sns_msg;
$twitter_url   = 'http://twitter.com/home?status='.$msg_url;
$gplus_url     = 'https://plus.google.com/share?url='.$sns_url;
*/

$sns_send  = G5_BBS_URL.'/sns_send.php?longurl='.urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
//$sns_send .= '&amp;title='.urlencode(utf8_strcut(get_text($view['subject']),140));
$sns_send .= '&amp;title='.$sns_msg;

$facebook_url = $sns_send.'&amp;sns=facebook';
$twitter_url  = $sns_send.'&amp;sns=twitter';
$gplus_url    = $sns_send.'&amp;sns=gplus';
?>

<?php if(G5_IS_MOBILE && $config['cf_kakao_js_apikey']) { ?>
<script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
<script src="<?php echo G5_JS_URL; ?>/kakaolink.js"></script>
<script>
    // 사용할 앱의 Javascript 키를 설정해 주세요.
    Kakao.init("<?php echo $config['cf_kakao_js_apikey']; ?>");
</script>
<?php } ?>
<div class="btn-group">
		<a href="<?php echo $facebook_url; ?>" target="_blank" class="btn btn-default popover-top" data-container="body" data-toggle="popover-top" data-content="페이스북으로 보내기를 요청합니다." data-original-title="페이스북"><i class="fa fa-facebook"></i></a>
		<a href="<?php echo $twitter_url; ?>" target="_blank" class="btn btn-default popover-top" data-container="body" data-toggle="popover-top" data-content="트위터로 보내기를 요청합니다." data-original-title="트위터"><i class="fa fa-twitter"></i></a>
		<a href="<?php echo $gplus_url; ?>" target="_blank" class="btn btn-default popover-top" data-container="body" data-toggle="popover-top" data-content="구글플러스로 보내기를 요청합니다." data-original-title="구글플러스"><i class="fa fa-google-plus"></i></a>
		<?php if(G5_IS_MOBILE && $config['cf_kakao_js_apikey']) { ?>
        <a href="javascript:kakaolink_send('<?php echo $sns_msg; ?>', '<?php echo urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>');" class="btn btn-default popover-top" data-container="body" data-toggle="popover-top" data-content="카카오톡으로 보내기를 요청합니다." data-original-title="카카오톡"><img src="<?php echo G5_SNS_URL; ?>/icon/kakaotalk.png" alt="카카오톡으로 보내기"></a>
        <?php } ?>
</div>