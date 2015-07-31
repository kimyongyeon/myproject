<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$imgwidth = 310; //표시할 이미지의 가로사이즈
$imgheight = 160; //표시할 이미지의 세로사이즈

?>
<div class="panel panel-default">
<div class="panel-heading">
    <i class="fa fa-folder-o"></i> <?php echo $bo_subject; ?>
  	<a class="pull-right tooltip-top" href='<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $bo_table ?>' onfocus='this.blur()'  title=" 전체 보기 "><i class="fa fa-list"></i></a>
</div>
<div class="panel-body">
<div class="row">
<?php for ($i=0; $i<count($list); $i++) { ?>	
<div class="col-sm-6 col-md-4 col-lg-4">
      <div class="thumbnail">
        <a href="<?php echo $list[$i]['href'] ?>">
		<?php                
                        $thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], $imgwidth, $imgheight);    					            
                        if($thumb['src']) {
                            $img_content = '<img class="img_left" src="'.$thumb['src'].'" alt="'.$list[$i]['subject'].'" width="'.$imgwidth.'" height="'.$imgheight.'">';
                        } else {
                            $img_content = '<div class="text-center tooltip-top" title="이미지없음" style="font-size:50px;" >
							<span class="fa-stack fa-lg">
							<i class="fa fa-image fa-stack-1x"></i>
							<i class="fa fa-ban fa-stack-2x text-danger"></i>
							</span>
							</div>';
                        }                
                        echo $img_content;
		?>
		</a>
		<hr>
        <div class="caption">
          <strong><a href="<?php echo $list[$i]['href'] ?>"><?php echo cut_str($list[$i]['subject'], 20, "..") ?></a></strong>
        </div>
      </div>
</div>
<?php } ?>
</div>
</div>
</div>