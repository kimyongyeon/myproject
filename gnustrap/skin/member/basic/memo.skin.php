<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 쪽지 목록 시작 { -->
<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <nav class="navbar navbar-default" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex5-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo G5_BBS_URL?>/memo.php?kind=send"><?php echo  ($kind == "recv") ? "보낸사람" : "받는사람";  ?></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex5-collapse">
          <ul class="nav navbar-nav">
            <li><a href="<?php echo G5_BBS_URL?>/memo.php?kind=recv"><?php echo  ($kind == "recv") ? "보낸사람" : "보낸사람";  ?></a></li>
            <li><a href="<?php echo G5_BBS_URL?>/memo.php?kind=send"><?php echo  ($kind == "send") ? "받는사람" : "받는사람";  ?></a></li>
            <li><a href="<?php echo G5_BBS_URL?>/memo_form.php">쪽지쓰기</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </nav>
      </div>
    <div class="modal-body">
        <div class="well well-sm" style="margin:5px;">
            전체 <?php echo $kind_title ?>쪽지 <?php echo $total_count ?>통<br>
        </div>
	<div class="table-responsive">
	<table class="table table-striped table-bordered">
        <thead>
        <tr role="row">
            <th scope="col"><?php echo  ($kind == "recv") ? "보낸사람" : "받는사람";  ?></th>
            <th scope="col">보낸시간</th>
            <th scope="col">읽은시간</th>
            <th scope="col">관리</th>
        </tr>
        </thead>
        <tbody role="alert" aria-live="polite" aria-relevant="all">
        <?php for ($i=0; $i<count($list); $i++) {  ?>
        <tr>
            <td><?php echo $list[$i]['name'] ?></td>
            <td><a href="<?php echo $list[$i]['view_href'] ?>" class="btn btn-default"><?php echo $list[$i]['send_datetime'] ?></a></td>
            <td><a href="<?php echo $list[$i]['view_href'] ?>" class="btn btn-default"><?php echo $list[$i]['read_datetime'] ?></a></td>
            <td><a href="<?php echo $list[$i]['del_href'] ?>" onclick="del(this.href); return false;" class="btn btn-default"><i class="fa fa-minus-square"></i> 삭제</a></td>
        </tr>
        <?php }  ?>
        <?php if ($i==0) { echo '<tr><td colspan="4" class="text-center">자료가 없습니다.</td></tr>'; }  ?>
        </tbody>
        </table>
    </div>
	<div class="alert alert-info" style="margin:5px;">
        쪽지 보관일수는 최장 <strong><?php echo $config['cf_memo_del'] ?></strong>일 입니다.
    </div>
	</div>

	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.close();"><i class="fa fa-times-circle"></i> Close</button>
    </div>
</form>

      </div>
</div>