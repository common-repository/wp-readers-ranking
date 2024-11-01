<?php 
$readers='<style type="text/css">'.get_option('avatar_css').'</style>';
if (get_option('by_year')=="display"){
   $readers.='<h2>年度评论排行 TOP'.get_option('year_num').'</h2>';
   global $wpdb;
   $query1="SELECT COUNT(comment_ID) AS cnt, comment_author, comment_author_url, comment_author_email FROM (SELECT * FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->posts.ID=$wpdb->comments.comment_post_ID) WHERE comment_date between date_sub(now(),interval 1 year) and now() AND user_id='0' AND post_password='' AND comment_approved='1' AND comment_type='') AS tempcmt GROUP BY comment_author_email ORDER BY cnt DESC LIMIT ".get_option('year_num'); 
    $wall = $wpdb->get_results($query1); 
    if(empty($wall)) {echo '<p>暂无年度评论数据！</p>';}
    $maxNum = $wall[0]->cnt;   
    foreach ($wall as $comment)   
    {   
        $width = round(40 / ($maxNum / $comment->cnt),2);   
        //此处是对应的血条的宽度   
        if( $comment->comment_author_url )    
          $url = $comment->comment_author_url;    
        else $url="#"; 
  $avatar = get_avatar( $comment->comment_author_email, $size = '32');    
        $tmp = "<li><a rel=\"nofollow\" alt=\"avatar头像\" target=\"_blank\" href=\"".$comment->comment_author_url."\">".$avatar."<em>".$comment->comment_author."</em> <strong>+".$comment->cnt."</strong></br>".$comment->comment_author_url."</a></li>";   
        $output1 .= $tmp;   
     }    
    $output1 = "<ul class=\"readers-list\">".$output1."</ul>";
    $readers.=$output1;   
$readers.='<div class="clear"></div><br />';
}

if (get_option('by_mon')=="display"){
    $readers.='<h2>本月评论排行 TOP'.get_option('mon_num').'</h2>';
    global $wpdb;
    $query2="SELECT COUNT(comment_ID) AS cnt, comment_author, comment_author_url, comment_author_email FROM (SELECT * FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->posts.ID=$wpdb->comments.comment_post_ID) WHERE date_format(comment_date,'%Y-%m')=date_format(now(),'%Y-%m') AND user_id='0' AND post_password='' AND comment_approved='1' AND comment_type='') AS tempcmt GROUP BY comment_author_email ORDER BY cnt DESC LIMIT ".get_option('mon_num');   
    $wall = $wpdb->get_results($query2); 
    if(empty($wall)) {echo '<p>暂无本月评论数据！</p>';}
    $maxNum = $wall[0]->cnt;   
    foreach ($wall as $comment)   
    {   
        $width = round(40 / ($maxNum / $comment->cnt),2);   
        //此处是对应的血条的宽度   
        if( $comment->comment_author_url )    
          $url = $comment->comment_author_url;    
        else $url="#"; 
  $avatar = get_avatar( $comment->comment_author_email, $size = '32');    
        $tmp = "<li><a rel=\"nofollow\" alt=\"avatar头像\" target=\"_blank\" href=\"".$comment->comment_author_url."\">".$avatar."<em>".$comment->comment_author."</em> <strong>+".$comment->cnt."</strong></br>".$comment->comment_author_url."</a></li>";   
        $output2.= $tmp;   
     }    
    $output2 = "<ul class=\"readers-list\">".$output2."</ul>";    
    $readers.=$output2;   
    $readers.='<div class="clear"></div><br />';
}

if (get_option('by_week')=="display"){
    $readers.='<h2>本周评论排行 TOP'.get_option('week_num').'</h2>';
    global $wpdb;
    $query3="SELECT COUNT(comment_ID) AS cnt, comment_author, comment_author_url, comment_author_email FROM (SELECT * FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->posts.ID=$wpdb->comments.comment_post_ID) WHERE yearweek(date_format(comment_date,'%Y-%m-%d')) = yearweek(now()) AND user_id='0' AND post_password='' AND comment_approved='1' AND comment_type='') AS tempcmt GROUP BY comment_author_email ORDER BY cnt DESC LIMIT ".get_option('week_num');   
    $wall = $wpdb->get_results($query3);
    if(empty($wall)) {echo '<p>暂无本周评论数据！</p>';}
    $maxNum = $wall[0]->cnt;   
    foreach ($wall as $comment)   
    {   
        $width = round(40 / ($maxNum / $comment->cnt),2);   
        //此处是对应的血条的宽度   
        if( $comment->comment_author_url )    
          $url = $comment->comment_author_url;
        else $url="#"; 
  $avatar = get_avatar( $comment->comment_author_email, $size = '32');    
        $tmp = "<li><a rel=\"nofollow\" alt=\"avatar头像\" target=\"_blank\" href=\"".$comment->comment_author_url."\">".$avatar."<em>".$comment->comment_author."</em> <strong>+".$comment->cnt."</strong></br>".$comment->comment_author_url."</a></li>";   
        $output3 .= $tmp;   
     }    
    $output3 = "<ul class=\"readers-list\">".$output3."</ul>";    
    $readers.=$output3;
    }
if(get_option('readers_load') == 'm_load'){
    echo $readers;
    }
?>