<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
?>
<?php for ($i=0; $i<count($list); $i++) {  ?>
            <div class="post-preview">
                <a href="<?php echo $list[$i][href]; ?>">
                    <h2 class="post-title">
                        <?php echo $list[$i][subject]; ?>
                    </h2>
                    <h3 class="post-subtitle">
                        <?php echo $list[$i][wr_content]; ?>
                    </h3>
                </a>
                <p class="post-meta">Posted by <?php echo $list[$i][name]; ?> on <?php echo $list[$i][wr_datetime]?> <b>Comment</b><?php echo $list[$i][comment_cnt]; ?></p>
            </div>
<?php }  ?>
            <hr>
            <ul class="pager">
                <li class="next">
                    <a href="<?php echo G5_URL ?>/<?php echo $bo_table ?>"><?php echo $bo_subject; ?> &rarr;</a>
                </li>
            </ul>