<?php
include_once('./_common.php');

$g5['title'] = '관리자메인';
include_once ('./admin.head.php');

$new_member_rows = 5;
$new_point_rows = 5;
$new_write_rows = 5;

$sql_common = " from {$g5['member_table']} ";

$sql_search = " where (1) ";

if ($is_admin != 'super')
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$new_member_rows} ";
$result = sql_query($sql);

$colspan = 12;
?>
<!-- left menu starts -->
<div class="col-sm-2 col-lg-2">
            <div class="sidebar-nav">
                <div class="nav-canvas">
                    <div class="nav-sm nav nav-stacked">

                    </div>
                    <ul class="nav nav-pills nav-stacked main-menu">
                        <li class="nav-header">메인메뉴</li>
                        <li><a class="ajax-link" href="<?php echo G5_ADMIN_URL ?>/config_form.php"><i class="glyphicon glyphicon-cog"></i><span> 환경설정</span></a>
                        </li>
                        <li><a class="ajax-link" href="<?php echo G5_ADMIN_URL ?>/member_list.php"><i class="glyphicon glyphicon-user"></i><span> 회원관리</span></a>
                        </li>
                        <li><a class="ajax-link" href="<?php echo G5_ADMIN_URL ?>/board_list.php"><i class="glyphicon glyphicon-list-alt"></i><span> 게시판관리</span></a>
                        </li>
                        <?php if(defined('G5_USE_SHOP')) { ?>
                        <li><a href="<?php echo G5_ADMIN_URL ?>/shop_admin"><i class="fa fa-cart-plus"></i><span> 쇼핑몰환경</span></a></li>
                        <li><a href="<?php echo G5_ADMIN_URL ?>/shop_admin/itemsellrank.php"><i class="fa fa-shopping-cart"></i><span> 쇼핑몰현황/기타</a></li>
                        <?php } ?>
                        <li><a class="ajax-link" href="<?php echo G5_ADMIN_URL ?>/sms_admin/config.php"><i class="glyphicon glyphicon-envelope"></i><span> SMS 관리</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
		<!-- left menu ends -->
        <noscript>
            &lt;div class="alert alert-block col-md-12"&gt;
                &lt;h4 class="alert-heading"&gt;Warning!&lt;/h4&gt;

                &lt;p&gt;You need to have &lt;a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank"&gt;JavaScript&lt;/a&gt;
                    enabled to use this site.&lt;/p&gt;
            &lt;/div&gt;
        </noscript>

		<div id="content" class="col-lg-10 col-sm-10">
        <ul class="breadcrumb">
        <li>
           <i class="glyphicon glyphicon-home"></i> HOME
        </li>
        </ul>


<!-- 신규가입회원 테이블-->

<div class="box col-md-12">
    <div class="box-inner">
    <div class="box-header well well-sm" data-original-title="">
        <h5><i class="glyphicon glyphicon-user"></i> 신규가입회원 <?php echo $new_member_rows ?>건 목록</h5>
    </div>
    <div class="box-content">
    <div class="well">
	총회원수 <?php echo number_format($total_count) ?>명 중 차단 <?php echo number_format($intercept_count) ?>명, 탈퇴 : <?php echo number_format($leave_count) ?>명
	</div>

<div class="table-responsive">
	<table class="table table-striped table-bordered">

    <thead>
    <tr role="row">
	<th class="sorting_asc" style="width: 100px;">회원ID</th>
	<th class="sorting_asc" style="width: 250px;">이름</th>
	<th class="sorting_asc" style="width: 250px;">닉네임</th>
	<th class="sorting_asc" style="width: 120px;">포인트</th>
	<th class="sorting_asc" style="width: 60px;">권한</th>
	<th class="sorting_asc" style="width: 50px;">수신</th>
	<th class="sorting_asc" style="width: 50px;">공개</th>
	<th class="sorting_asc" style="width: 50px;">인증</th>
	<th class="sorting_asc" style="width: 50px;">차단</th>
	<th class="sorting_asc" style="width: 50px;">그룹</th>
	</tr>
    </thead>
    
    <tbody role="alert" aria-live="polite" aria-relevant="all">
	<?php
        for ($i=0; $row=sql_fetch_array($result); $i++)
        {
            // 접근가능한 그룹수
            $sql2 = " select count(*) as cnt from {$g5['group_member_table']} where mb_id = '{$row['mb_id']}' ";
            $row2 = sql_fetch($sql2);
            $group = "";
            if ($row2['cnt'])
                $group = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'">'.$row2['cnt'].'</a>';

            if ($is_admin == 'group')
            {
                $s_mod = '';
                $s_del = '';
            }
            else
            {
                $s_mod = '<a href="./member_form.php?$qstr&amp;w=u&amp;mb_id='.$row['mb_id'].'">수정</a>';
                $s_del = '<a href="javascript:del(\'./member_delete.php?'.$qstr.'&amp;w=d&amp;mb_id='.$row['mb_id'].'&amp;url='.$_SERVER['PHP_SELF'].'\');">삭제</a>';
            }
            $s_grp = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'">그룹</a>';

            $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date("Ymd", G5_SERVER_TIME);
            $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date("Ymd", G5_SERVER_TIME);

            $mb_nick = get_sideview($row['mb_id'], $row['mb_nick'], $row['mb_email'], $row['mb_homepage']);

            $mb_id = $row['mb_id'];
            if ($row['mb_leave_date'])
                $mb_id = $mb_id;
            else if ($row['mb_intercept_date'])
                $mb_id = $mb_id;

        ?>
	<tr>
        <td class=" sorting_1"><?php echo $mb_id ?></td>
        <td class="center "><?php echo get_text($row['mb_name']); ?></td>
        <td class="center "><?php echo $mb_nick ?></td>
        <td class="center "><?php echo $row['mb_level'] ?></td>
        <td class="center"><a href="./point_list.php?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo number_format($row['mb_point']) ?></a></td>
        <td class="center"><?php echo $row['mb_mailling']?'예':'아니오'; ?></td>
        <td class="center"><?php echo $row['mb_open']?'예':'아니오'; ?></td>
        <td class="center"><?php echo preg_match('/[1-9]/', $row['mb_email_certify'])?'예':'아니오'; ?></td>
        <td class="center"><?php echo $row['mb_intercept_date']?'예':'아니오'; ?></td>
        <td class="center"><?php echo $group ?></td>
    </tr>
	<?php
            }
        if ($i == 0)
            echo '<tr><td colspan="'.$colspan.'" class="text-center">자료가 없습니다.</td></tr>';
        ?>
	</tbody></table>
	</div>
            <div class="center">
            <a href="./member_list.php" class="btn btn-primary">
		    <i class="glyphicon glyphicon-chevron-left glyphicon-white"></i> 회원 전체보기</a>
            </div>

	</div></div></div>

<!-- 최근게시물 테이블-->
<?php
$sql_common = " from {$g5['board_new_table']} a, {$g5['board_table']} b, {$g5['group_table']} c where a.bo_table = b.bo_table and b.gr_id = c.gr_id ";

if ($gr_id)
    $sql_common .= " and b.gr_id = '$gr_id' ";
if ($view) {
    if ($view == 'w')
        $sql_common .= " and a.wr_id = a.wr_parent ";
    else if ($view == 'c')
        $sql_common .= " and a.wr_id <> a.wr_parent ";
}
$sql_order = " order by a.bn_id desc ";

$sql = " select count(*) as cnt {$sql_common} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$colspan = 5;
?>
<div class="box col-md-12">
    <div class="box-inner">
    <div class="box-header well well-sm" data-original-title="">
        <h5><i class="glyphicon glyphicon-list-alt"></i> 최근게시물</h5>
    </div>

    <div class="box-content">
<div class="table-responsive">
	<table class="table table-striped table-bordered">

    <thead>
    <tr role="row">
	<th class="sorting_asc" style="width: 50px;">그룹</th>
	<th class="sorting_asc" style="width: 100px;">게시판</th>
	<th class="sorting_asc" style="width: 250px;">제목</th>
	<th class="sorting_asc" style="width: 150px;">이름</th>
	<th class="sorting_asc" style="width: 100px;">일시</th>
	</tr>
    </thead>
    
    <tbody role="alert" aria-live="polite" aria-relevant="all">
	<?php
        $sql = " select a.*, b.bo_subject, c.gr_subject, c.gr_id {$sql_common} {$sql_order} limit {$new_write_rows} ";
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++)
        {
            $tmp_write_table = $g5['write_prefix'] . $row['bo_table'];

            if ($row['wr_id'] == $row['wr_parent']) // 원글
            {
                $comment = "";
                $comment_link = "";
                $row2 = sql_fetch(" select * from $tmp_write_table where wr_id = '{$row['wr_id']}' ");

                $name = get_sideview($row2['mb_id'], get_text(cut_str($row2['wr_name'], $config['cf_cut_name'])), $row2['wr_email'], $row2['wr_homepage']);
                // 당일인 경우 시간으로 표시함
                $datetime = substr($row2['wr_datetime'],0,10);
                $datetime2 = $row2['wr_datetime'];
                if ($datetime == G5_TIME_YMD)
                    $datetime2 = substr($datetime2,11,5);
                else
                    $datetime2 = substr($datetime2,5,5);

            }
            else // 코멘트
            {
                $comment = '댓글. ';
                $comment_link = '#c_'.$row['wr_id'];
                $row2 = sql_fetch(" select * from {$tmp_write_table} where wr_id = '{$row['wr_parent']}' ");
                $row3 = sql_fetch(" select mb_id, wr_name, wr_email, wr_homepage, wr_datetime from {$tmp_write_table} where wr_id = '{$row['wr_id']}' ");

                $name = get_sideview($row3['mb_id'], get_text(cut_str($row3['wr_name'], $config['cf_cut_name'])), $row3['wr_email'], $row3['wr_homepage']);
                // 당일인 경우 시간으로 표시함
                $datetime = substr($row3['wr_datetime'],0,10);
                $datetime2 = $row3['wr_datetime'];
                if ($datetime == G5_TIME_YMD)
                    $datetime2 = substr($datetime2,11,5);
                else
                    $datetime2 = substr($datetime2,5,5);
            }
        ?>
		<tr>
        <td class="center"><a href="<?php echo G5_BBS_URL ?>/new.php?gr_id=<?php echo $row['gr_id'] ?>"><?php echo cut_str($row['gr_subject'],10) ?></a></td>
        <td class="center"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $row['bo_table'] ?>"><?php echo cut_str($row['bo_subject'],20) ?></a></td>
        <td class="center"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $row['bo_table'] ?>&amp;wr_id=<?php echo $row2['wr_id'] ?><?php echo $comment_link ?>"><?php echo $comment ?><?php echo conv_subject($row2['wr_subject'], 100) ?></a></td>
        <td class="center"><?php echo $name ?></td>
        <td class="center"><?php echo $datetime ?></td>
        </tr>

        <?php
        }
        if ($i == 0)
            echo '<tr><td colspan="'.$colspan.'" class="text-center">자료가 없습니다.</td></tr>';
        ?>
	</tbody>
	
	</table>
</div>
            <div class="text-center">
            <a href="<?php echo G5_BBS_URL ?>/new.php" class="btn btn-primary">
		    <i class="glyphicon glyphicon-chevron-left glyphicon-white"></i> 최근게시물 더보기</a>
            </div>

	</div></div></div>

<!-- 포인트 발생내역 테이블-->
<?php
$sql_common = " from {$g5['point_table']} ";
$sql_search = " where (1) ";
$sql_order = " order by po_id desc ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$new_point_rows} ";
$result = sql_query($sql);

$colspan = 7;
?>
<div class="box col-md-12">
    <div class="box-inner">
    <div class="box-header well well-sm" data-original-title="">
        <h5><i class="glyphicon glyphicon-credit-card"></i> 최근 포인트 발생내역</h5>
    </div>

    <div class="box-content">
	<div class="well">
	전체 <?php echo number_format($total_count) ?> 건 중 <?php echo $new_point_rows ?>건 목록
	</div>

<div class="table-responsive">
	<table class="table table-striped table-bordered">

    <thead>
    <tr role="row">
    <th class="sorting_asc " style="width: 100px;"><span class="hidden-xs">회원</span>ID</th>
	<th class="sorting_asc " style="width: 250px;">이름</th>
	<th class="sorting_asc" style="width: 150px;">닉네임</th>
	<th class="sorting_asc" style="width: 120px;">일시</th>
	<th class="sorting_asc" style="width: 250px;">포인트내용</th>
	<th class="sorting_asc" style="width: 50px;">포인트</th>
	<th class="sorting_asc" style="width: 50px;">포인트합</th>
	</tr>
    </thead>
    
    <tbody role="alert" aria-live="polite" aria-relevant="all">
	<?php
        $row2['mb_id'] = '';
        for ($i=0; $row=sql_fetch_array($result); $i++)
        {
            if ($row2['mb_id'] != $row['mb_id'])
            {
                $sql2 = " select mb_id, mb_name, mb_nick, mb_email, mb_homepage, mb_point from {$g5['member_table']} where mb_id = '{$row['mb_id']}' ";
                $row2 = sql_fetch($sql2);
            }

            $mb_nick = get_sideview($row['mb_id'], $row2['mb_nick'], $row2['mb_email'], $row2['mb_homepage']);

            $link1 = $link2 = "";
            if (!preg_match("/^\@/", $row['po_rel_table']) && $row['po_rel_table'])
            {
                $link1 = '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$row['po_rel_table'].'&amp;wr_id='.$row['po_rel_id'].'" target="_blank">';
                $link2 = '</a>';
            }
        ?>

		<tr>
        <td class="sorting_1"><a href="./point_list.php?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo $row['mb_id'] ?></a></td>
        <td class="center"><?php echo get_text($row2['mb_name']); ?></td>
        <td class="center"><?php echo $mb_nick ?></td>
        <td class="center"><?php echo $row['po_datetime'] ?></td>
        <td class="center"><?php echo $link1.$row['po_content'].$link2 ?></td>
        <td class="center"><?php echo number_format($row['po_point']) ?></td>
        <td class="center"><?php echo number_format($row['po_mb_point']) ?></td>
        </tr>

        <?php
        }

        if ($i == 0)
            echo '<tr><td colspan="'.$colspan.'" class="text-center">자료가 없습니다.</td></tr>';
        ?>
	</tbody>
	
	</table>
</div>
            <div class="text-center">
            <a href="./point_list.php" class="btn btn-primary">
		    <i class="glyphicon glyphicon-chevron-left glyphicon-white"></i> 포인트내역 전체보기</a>
            </div>

	</div></div></div>


<?php
include_once ('./admin.tail.php');
?>