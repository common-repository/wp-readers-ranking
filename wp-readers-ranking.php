<?php 
/*
Plugin Name: WP Readers Ranking
Plugin URI: http://zhangge.net/4756.html
Description: <strong>Wordpress读者排行榜插件</strong>，通过这个插件可以快速在WordPress博客的任意页面部署一个读者排行榜 ！并可以给排行榜限制不同的时间段，比如年度、月份或星期，不同时间段亦可灵活组合，插件已自带Gravatar评论头像加速功能，更多功能请进入插件设置。
Version: 1.0.0
Author: 张戈
Author URI: http://zhangge.net/
Copyright: 中国博客联盟原创插件，任何个人或团体不可擅自更改版权。
*/
register_activation_hook(__FILE__, 'wp_readers_ranking_install');
//register_deactivation_hook(__FILE__, 'wp_readers_ranking_remove');
function wp_readers_ranking_install() {
    add_option("by_year", "display", '', 'yes');
    add_option("year_num", "9", '', 'yes');
    add_option("by_mon", "display", '', 'yes');
    add_option("mon_num", "9", '', 'yes');
    add_option("by_week", "display", '', 'yes');
    add_option("week_num", "9", '', 'yes');
    add_option("readers_position", "before", '', 'yes');
    add_option("readers_load", "m_load", '', 'yes');
    add_option("get_gravatar", "enabled", '', 'yes');
    add_option("s_gravatar", "cn", '', 'yes');
    add_option("avatar_css", ".readers-list li{/*修改width百分比可以调整头像列数，默认33.33%，即3列*/
	width:33.33%;
	float:left;
	*margin-right:-1px;
	list-style:none !important;
	line-height:18px !important;
}
.readers-list a:hover strong {
	color:#0196e3;
/*修改right数值可修复评论数错位的问题*/
	right:183px;
	top:0;
	text-align:center;
	border-right:#ccc 1px solid;
	height:44px;
	line-height:40px
}
/* 若只是修复错位问题，以下样式不建议改动 */
.readers-list {
	line-height:18px;
	text-align:left;
	overflow:hidden;
	_zoom:1
}
.readers-list a,.readers-list a:hover strong {
	background-color:#f2f2f2;
	background-image:-webkit-linear-gradient(#f8f8f8,#f2f2f2);
	background-image:-moz-linear-gradient(#f8f8f8,#f2f2f2);
	background-image:linear-gradient(#f8f8f8,#f2f2f2)
}
.readers-list a {
	border-radius:4px;
	position:relative;
	display:block;
	height:36px;
	margin:4px;
	padding:4px 4px 4px 44px;
	color:#999;
	overflow:hidden;
	border:#ccc 1px solid;
	border-radius:4px;
	box-shadow:#eee 0 0 2px
	white-space: nowrap;
	text-overflow: ellipsis;
}
.readers-list img,.readers-list em,.readers-list strong {
	-webkit-transition:all .2s ease-out;
	-moz-transition:all .2s ease-out;
	transition:all .2s ease-out
}
.readers-list img {
	width:36px;
	height:36px;
	float:left;
	margin:0 8px 0 -40px;
	box-shadow:inset 0 -1px 0 #3333sf;
	-webkit-box-shadow:inset 0 -1px 0 #3333sf;
	-webkit-transition:1s;
	-webkit-transition:-webkit-transform 1s ease-out;
	transition:transform 1s ease-out;
	-moz-transition:-moz-transform 1s ease-out
}
.readers-list em {
	color:#666;
	font-style:normal;
	margin-right:10px
}
.readers-list strong {
	color:#ddd;
	width:40px;
	text-align:right;
	position:absolute;
	right:6px;
	top:4px;
	font:bold 14px/16px microsoft yahei
}
.readers-list a:hover {
	border-color:#bbb;
	box-shadow:#ccc 0 0 2px;
	background-color:#fff;
	background-image:none
}
.readers-list a:hover img {
	opacity:.6;
	margin-left:0
}
.readers-list a:hover img {
	-webkit-transform:rotate(1080deg);
	-moz-transform:rotate(1080deg);
	-o-transform:rotate(1080deg);
	-ms-transform:rotate(1080deg);
	transform:rotate(1080deg);
	border-radius:30px !important
}
.readers-list a:hover em {
	color:#0196e3;
	font:bold 12px/36px microsoft yahei
}", '', 'yes');
}
function wp_readers_ranking_remove() {
    //delete_option('by_year');
    //delete_option('year_num');
    //delete_option('by_mon');
    //delete_option('mon_num');
    //delete_option('by_week');
    //delete_option('week_num');
    delete_option('avatar_css');
}

//短代码函数
class readers_ranking{
  function __construct(){
    add_shortcode( 'readers_ranking', array( $this, 'readers_ranking_page_sc' ) );
  }
  function readers_ranking_page_sc( $atts, $content){
     if(is_page()){  
        include('wp-readers-ranking-page.php');
     }
     return $content;
  }
}
new readers_ranking();

add_filter('plugin_action_links', 'readers_rank_plugin_action_links', 10, 3);
function readers_rank_plugin_action_links($action_links, $plugin_file, $plugin_info) {
    $this_file = basename(__FILE__);
    if(substr($plugin_file, -strlen($this_file))==$this_file) {
        $new_action_links = array(
        "<a href='options-general.php?page=readers_rank'>设置</a>"
        );
        foreach($action_links as $action_link) {
        if (stripos($action_link, '>Edit<')===false) {
            if (stripos($action_link, '>Deactivate<')!==false) {
                $new_action_links[] = $action_link;
            } else {
                $new_action_links[] = $action_link;
                    }
                }
            }
    return $new_action_links;
        }
  return $action_links;
        }
?>
<?php   
if(is_admin()) {   
    add_action('admin_menu', 'display_readers_rank_menu');   
}   
function display_readers_rank_menu() {   
    add_options_page('读者排行榜', '读者排行榜','administrator','readers_rank', 'display_readers_rank_page');
}   
function display_readers_rank_page($avatar_css) {   
?>
<style type="text/css">
#setting_page {position:relative;}
h3{margin-top: 30px;}
#num{ margin-left:20px;}
#year_num,#mon_num,#week_num{width: 30px;height: 20px;text-align: center;margin: 10px 0 0 0;}
#options,#m_load_info,#pageid {margin-left: 30px;}
#readers_page_Id{width: 60px;height: 25px;text-align: center;font-weight: normal;}
#m_load{margin-left: 45px;}
#m_load_info{height: 95px;}
#readers_after{margin-left: 32px;}
#page_list{padding: 0px 0px 0 17px;
margin: 0 0 0 324px;
top: 100px;
border: 1px solid #ccc;
width: 175px;
height: auto;
position: fixed;
}
.button-primary{margin: 0 0 0 30PX !important;}
</style>
<div id="setting_page"> 
    <div style="width: 469px;"><h2>读者排行榜插件设置</h2>
    <form accept-charset="GBK" action="https://shenghuo.alipay.com/send/payment/fill.htm" method="POST" target="_blank"><input name="optEmail" type="hidden" value="ge@zhangge.net" />
    <input name="payAmount" type="hidden" value="0" />
    <input id="title" name="title" type="hidden" value="赞助张戈博客o(∩_∩)o" />
    <input name="memo" type="hidden" value="请填写您的联系方式，以便张戈答谢。" />
    <input title="如果好用，您可以赞助张戈博客" name="pay" src="<?php echo plugins_url('payment.png',__FILE__);?>" type="image" value="捐赠共勉" style="float: right;margin-top: -43px;"/>
    </form>
</div>
<form method="post" action="options.php" onsubmit="return checkTab('submit');">   
<?php 
    wp_nonce_field('update-options');
    if (get_option('by_year')=="display"){
        $by_year_display='checked="checked"';
    } 
    
    if (get_option('by_mon')=="display"){
        $by_mon_display='checked="checked"';
    }
    
    if (get_option('by_week')=="display"){
        $by_week_display='checked="checked"';
    }
    
    if (get_option('readers_load')=="m_load"){
        $m_load='checked="checked"';
    } else {
        $auto_load='checked="checked"';
    }
    
    if (get_option('get_gravatar')=="enabled"){
        $optimized='checked="checked"';
    } else {
        $unoptimized='checked="checked"';
    }
    
    if (get_option('s_gravatar')=="ds"){
        $ds_gravatar='checked="checked"';
    } else if(get_option('s_gravatar')=="en"){
        $en_gravatar='checked="checked"';
    } else {
       $cn_gravatar='checked="checked"';
    }
    
    if (get_option('readers_position')=="before"){
        $readers_before='checked="checked"';
    } else {
        $readers_after='checked="checked"';
    }
?> 
<!-- 时间段设置 -->
<h3>一、排行时间段</h3>
<div id="options">
    <input type="checkbox" name="by_year" id="by_year" value="display" <?php echo $by_year_display;?>/>
    <span for="by_year">年度排行</span>
    <span id="num">数量限制：</span><input type="text" name="year_num" id="year_num" value="<?php echo get_option('year_num');?>"/>个
    <br />
    <input type="checkbox" name="by_mon" id="by_mon" value="display" <?php echo $by_mon_display;?>/>
    <span for="by_mon">月份排行</span>
    <span id="num">数量限制：</span><input type="text" name="mon_num" id="mon_num" value="<?php echo get_option('mon_num');?>"/>个
    <br />
    <input type="checkbox" name="by_week" id="by_week" value="display" <?php echo $by_week_display;?>/>
    <span for="by_week">一周排行</span>
    <span id="num">数量限制：</span><input type="text" name="week_num" id="week_num" value="<?php echo get_option('week_num');?>"/>个 
</div>
<!-- 头像加速 -->
<h3>二、头像加速</h3>
<div id="options">
    <input type="radio" name="get_gravatar" id="optimized" onclick="checkTab('optimized')" value="enabled" <?php echo $optimized;?>/>
    <label for="optimized" style="cursor: pointer;">开启</label>
    <input type="radio" name="get_gravatar" id="unoptimized" onclick="checkTab('unoptimized')" value="disabled" <?php echo $unoptimized;?>/>
    <label for="unoptimized" style="cursor: pointer;">关闭</label><br />
<?php if(get_option('get_gravatar')=="enabled"){ ?>
 <div id="s_gravatar" style="display:block;">
 <?php } else { ?>
 <div id="s_gravatar" style="display:none;">
 <?php } ?>     
    <p id="s_list"><input type="radio" name="s_gravatar" id="cn_gravatar" value="cn" <?php echo $cn_gravatar;?>/>
    <span for="cn_gravatar">CN服务器（cn.gravatar.com）[推荐]</span></p>
    <p id="s_list"><input type="radio" name="s_gravatar" id="ds_gravatar" value="ds" <?php echo $ds_gravatar;?>/>
    <span for="unoptimized">多说服务器（gravatar.duoshuo.com）</span></p>
    <p id="s_list"><input type="radio" name="s_gravatar" id="en_gravatar" value="en" <?php echo $en_gravatar;?>/>
    <span for="optimized">EN服务器（en.gravatar.com）</span></p>
</div>    
</div>
<!-- 加载模式 -->
<h3>三、加载模式</h3>
<div id="options">
    <input type="radio" name="readers_load" id="auto_load" onclick="checkTab('auto_load')" value="auto_load" <?php echo $auto_load;?>/>
    <label for="auto_load" style="cursor: pointer;">自动</label>
    <input type="radio" name="readers_load" id="m_load" onclick="checkTab('m_load')" value="m_load" <?php echo $m_load;?>/>
    <label for="m_load" style="cursor: pointer;">手动</label>
</div>
<!-- 加载位置提醒 -->
<?php if(get_option('readers_load')=="auto_load"){ ?>
    <div id="pageid" style="display:block;">
<?php }else { ?>
    <div id="pageid" style="display:none;">
<?php } ?>

<h4>①、加载位置</h4>
    <input type="radio" name="readers_position" id="readers_before" value="before" <?php echo $readers_before;?>/>
    <label for="readers_before" style="cursor: pointer;">文章前</label>
    <input type="radio" name="readers_position" id="readers_after" value="after" <?php echo $readers_after;?>/>
    <label for="readers_after" style="cursor: pointer;">文章后</label>
<!-- 自动模式填写ID -->
<h4>②、目标页面ID：<input type="text" name="readers_page_Id" id="readers_page_Id" value="<?php echo  get_option('readers_page_Id');?>" /></h4>
<!-- 页面列表 -->
<div id="page_list">
<h3>页面清单(名称：ID)</h3>
<span style="color: #080;">
<ul>
<?php
$mypages = get_pages();
if(count($mypages) > 0) {
    foreach($mypages as $page) { ?>
      <li><?php echo get_the_title($page->ID); ?> : <?php echo $page->ID; ?></li>
<?php }} ?> 
</ul>
</span>
</div>
</div>
<!-- 手动加载时的提醒信息 -->
<?php if(get_option('readers_load')=="m_load"){ ?>
 <div id="m_load_info" style="display:block;">
 <?php } else { ?>
 <div id="m_load_info" style="display:none;">
 <?php } ?>
 <h4>手动部署方法：</h4>
    <p>方法①、后台编辑页面内容，在任意位置插入短代码“[readers_ranking]”,保存更新即可（短代码无法自定义位置）；</p>
    <p>方法②、若需要自定义导航出现的位置，请编辑主题页面模板(比如：gueskgook.php)，在合适的位置插入：&lt;?php readers_page();?&gt;并保存。</p>
</div>
<p style="color:red;">&nbsp;</p>
<h3>四、自定义样式</h3>
<div id="options">
<textarea name="avatar_css" id="avatar_css" cols="40" rows="6"><?php echo get_option('avatar_css');?></textarea>
</div>
    <input type="hidden" name="action" value="update" />   
    <input type="hidden" name="page_options" value="by_year,by_mon,by_week,year_num,mon_num,week_num,get_gravatar,s_gravatar,readers_load,readers_position,readers_page_Id,avatar_css" />
    <input type="submit" value="保存设置" class="button-primary" />
</p>   
</form>
<script src="http://lib.sinaapp.com/js/jquery/1.9.1/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
function checkTab(bool){
    if(bool == 'auto_load'){
        $('#pageid').show();
        $("#m_load_info").css("display","none");
        $("#pageid").css("display","block");
    }else if(bool == 'm_load'){
        $('#pageid').show();
        $("#m_load_info").css("display","block");
        $("#pageid").css("display","none");
    }
    if(bool == 'optimized'){
        $("#s_gravatar").css("display","block");
    }else if(bool == 'unoptimized'){
        $("#s_gravatar").css("display","none");
    }
  var readers_page_Id = document.getElementsByName("readers_page_Id")[0].value;
  if(readers_page_Id == '' && bool=='submit'){
            alert('请正确输入需要自动加载导航的页面ID!');
            return false;
    } 
}
</script>
</div>
<?php }?>
<?php
//gravatar头像加速函数
function fixed_get_avatar($avatar) {
    if (get_option('s_gravatar')=="ds"){
        $s_gravatar='gravatar.duoshuo.com';
    } else if(get_option('s_gravatar')=="en"){
        $s_gravatar='en.gravatar.com';
    } else {
       $s_gravatar='cn.gravatar.com';
    }    
$avatar = str_replace(array("www.gravatar.com","0.gravatar.com","1.gravatar.com","2.gravatar.com"),$s_gravatar,$avatar);
return $avatar;
    }
if (get_option('get_gravatar')=="enabled"){
    add_filter( 'get_avatar', 'fixed_get_avatar', 10, 3 );  
}

//加载页面函数
if(get_option('readers_load') == 'auto_load') {
    add_filter('the_content','readers_page');
}    
function readers_page($content){
if (is_page()){
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
}
if(get_option('readers_load') == 'auto_load' && get_option('readers_page_Id') == get_the_id()) {
   if (get_option('readers_position')=="before"){
        $content=$readers.$content;
    } else {
        $content.=$readers;  
    }
    return $content;
} else if(get_option('readers_load') == 'm_load'){
    echo $readers;
    } else {
       return $content;   
    }
}
?>