<?php 
define ('IN_PLAYER', true);
//simple player site on route.

include ('common_header.php');
include ('block/block_header_player.inc.php');

//设置一下两个输入参数
$source_id = intval($_GET['source_id']);
$row_id = intval($_GET['row_id']);

//读取播放数据
$playid = intval($_GET['id']);
$data = DB::queryFirstRow('SELECT * FROM '.table('data')." WHERE m_id=%i", intval($playid));
if(!$data){
	showmessage("无法找到数据，请在留言板回馈错误ID。", 'guest.php');
}

if(!$data['m_enabled']){
	showmessage("无法找到正常数据，请在留言板回馈错误ID。", 'guest.php');
}
//解析数据
$play_show_data = parse_playdata_detail($data['m_playdata']);
//获取parse之后的playdata里面的数据
$rs = $play_show_data[$source_id]['data'][$row_id];
$has_next = isset($play_show_data[$source_id]['data'][$row_id + 1]) ? true : false;
$has_prev = isset($play_show_data[$source_id]['data'][$row_id -1 ]) ? true : false;
update_hitnum($playid); //更新点击数
?>

<br />
<br />
<div class="phere">
	<div id="crumblink" class="channel">
		<a href="/">首页</a>»
		<?php echo output_type_a($data['m_type']);?>» 
		<h1 id="playtitle"><?php echo $data['m_name'];?></h1>
	</div>
</div>
<div id="play_outer_box" class="play-box">
	<div class="play">
		<div id="ccplay" class="ccplay_norm" style="width:<?php echo $config['vod_width']?>px;height:<?php echo $config['vod_height'];?>px;">
			<?php echo make_player_by_name($rs['playtype'], array($rs['playurl']));?>
		</div>
		<div class="playable_box">
			<div>
				<div>
					<div><?php echo draw_ad('play_box_bottom_01');?></div>
					<div><?php echo draw_ad('play_box_bottom_02');?></div>
				</div>
				<div id="play_control">
					<a href="<?php echo $has_prev ? 'play.php?id='.$playid.'&source_id='.$source_id.'&row_id='.($row_id-1) : 'javascript:void(0)'?>">
						<img src="static/image/shang.gif">
					</a> 
					<a href="detail.php?id=<?php echo $playid;?>">
						<img src="static/image/mulu.gif">
					</a>
					<a href="<?php echo $has_next ? 'play.php?id='.$playid.'&source_id='.$source_id.'&row_id='.($row_id+1) : 'javascript:void(0)'?>">
						<img src="static/image/xia.gif">
					</a>
					<a id="w_switcher" href="javascript:void(0);" onclick="switch_wide();">【宽屏模式】</a>
				</div>
				<div>
					<?php echo draw_ad('play_box_bottom_sp');?>
				</div>
			</div>
		</div>
	</div>
	<div class="a300 play_right">
		<div>
			<div><?php echo draw_ad('play_right_01');?></div>
			<div style="margin-top:5px;"><?php echo draw_ad('play_right_02');?></div>
		</div>
	</div>
	<div class="cl"></div>
</div>
<!-- 播放box end -->
<!-- 公告区 start -->
<div class="page_content">
	
</div>
<!-- 公告区 end -->
<script>
var is_wide = false;
var default_width = <?php echo $config['vod_width'];?>

var default_height = <?php echo $config['vod_height'];?>

var wide_width = <?php echo $config['vod_width_wide'];?>

var wide_height = <?php echo $config['vod_height_wide'];?>

function switch_wide(){
	if(is_wide){
		$("#play_outer_box").css("width", (default_width + 320) + "px");
		$("#ccplay").css("width", default_width + "px");
		$("#ccplay").css("height", default_height + "px");
		$("#w_switcher").text("【宽屏模式】");
		is_wide = false;
	}else{
		$("#play_outer_box").css("width", (wide_width + 320) + "px");
		$("#ccplay").css("width", wide_width + "px");
		$("#ccplay").css("height", wide_height + "px");
		$("#w_switcher").text("【普通模式】");
		is_wide = true;
	}
}
</script>
<!-- 评论区 start -->
<div class="page_content">
	<?php include 'block/block_comment.inc.php'; ?>
</div>
<!-- 评论区 end -->


<?php 

//播放器函数
//player_id为数据库里播放器name，因为历史问题这里用的name方式来检索.
function make_player_by_name($player_name, $player_vars, $insert_key = '__P_VAR__', $is_wide = false){
	global $config;
	$player_info = DB::queryFirstRow('SELECT * FROM '.table('player')." WHERE m_name=%s", $player_name);
	if(!$player_info){
		return "<span>无法找到 ".$player_name."</span><a href=\"guest.php\">点击报错，注意反馈遇到问题的具体地址。</a>";
	}
	//依次用 $player_var 里的数据对模板进行替换.
	foreach($player_vars as $player_var){
		$player_info['m_html'] = str_replace_limit($insert_key, $player_var, $player_info['m_html'], 1);
	}
	$re_width = $config['vod_width'];
	$re_height = $config['vod_height'];
	//处理宽和高
	if($is_wide){ //宽屏版的赋值，暂未启用
		$re_width = $config['vod_width_wide'];
		$re_height = $config['vod_height_wide'];
	}
	$player_info['m_html'] = str_replace_limit('__P_WIDTH__', $re_width, $player_info['m_html'], 1);
	$player_info['m_html'] = str_replace_limit('__P_HEIGHT__', $re_height, $player_info['m_html'], 1);
	return $player_info['m_html'];
}

//更新播放数
function update_hitnum($p_id){
	DB::query('UPDATE '.table('data')." SET m_hit=m_hit+1 WHERE m_id=%i", $p_id);
	DB::query('UPDATE '.table('data')." SET m_dayhit=m_dayhit+1 WHERE m_id=%i", $p_id);
	DB::query('UPDATE '.table('data')." SET m_weekhit=m_weekhit+1 WHERE m_id=%i", $p_id);
	DB::query('UPDATE '.table('data')." SET m_monthhit=m_monthhit+1 WHERE m_id=%i", $p_id);
}

include ('common_footer.php');

?>