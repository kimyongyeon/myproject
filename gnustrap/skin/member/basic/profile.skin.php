<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// 회원사진 경로
$mb_1_url = G5_DATA_URL.'/member_image/'.substr($member['mb_id'],0,2).'/'.$member['mb_id'].'.gif';
$mb_2_url = G5_DATA_URL.'/member_image/'.substr($mb['mb_id'],0,2).'/'.$mb['mb_id'].'.gif';
$mb_3_url = G5_DATA_URL.'/member_image/member_photo.gif';
$mb_2_path = G5_DATA_PATH.'/member_image/'.substr($mb['mb_id'],0,2).'/'.$mb['mb_id'].'.gif';
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 자기소개 시작 { -->
<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="window.close();">×</button>
		<?php echo $mb_nick ?>
      </div>
    <div class="modal-body">

							  <div class="text-center">
							  <!--회원사진-->
<? 
//회원사진출력 
if (file_exists($mb_2_path)) { 
echo "<img src='$mb_2_url' alt='회원사진' style='border-radius: 50%;border: 3px solid #ddd;'>"; 
}else{ 
echo "<img src='$mb_3_url' alt='사진없음' style='border-radius: 50%;border: 3px solid #ddd;'>"; 
} 
?>
							  <p style="margin-top:5px;">
							  <i class="fa fa-calendar tooltip-top fa-fw" title="회원 가입일" ></i> <?php echo ($member['mb_level'] >= $mb['mb_level']) ?  substr($mb['mb_datetime'],0,10) ." (".number_format($mb_reg_after)." 일)" : "알 수 없음";  ?></p>
								   <?php if ($mb_homepage) {  ?>
  								 	   <a href="<?php echo $mb_homepage ?>" target="_blank" class="btn btn-default tooltip-top" title="홈페이지"><i class="fa fa-external-link fa-fw"></i><?php echo $mb_homepage ?></a>
   								   <?php }  ?>
								<hr>
							  </div>
								<span class="tooltip-top" title="인사말"><?php echo $mb_profile ?></span>
							</div><!-- /.panel-body -->

						<div class="clearfix"></div>

						<div class="row well" style="margin:0 15px;">
									<div class="col-xs-4 text-center">
										<span class="tooltip-top" title="회원권한"><?php echo $mb['mb_level'] ?></span><br />
										<small class="text-muted">Level</small>
									</div><!-- /.col -->
									<div class="col-xs-4 text-center">
										<span class="tooltip-top" title="소유포인트"><?php echo number_format($mb['mb_point']) ?></span><br />
										<small class="text-muted">Point</small>
									</div><!-- /.col -->
									<div class="col-xs-4 text-center">
										<span class="tooltip-top" title="마지막 접속일"><?php echo ($member['mb_level'] >= $mb['mb_level']) ? $mb['mb_today_login'] : "알 수 없음"; ?></span><br />
										<small class="text-muted">Last access</small>
									</div><!-- /.col -->
					    </div>
  	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.close();"><i class="fa fa-times-circle"></i> Close</button>
    </div>
      </div>
</div>

         
<!-- } 자기소개 끝 -->