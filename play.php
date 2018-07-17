<?php 

//simple player site on route.

include ('common_header.php');

//设置一下两个输入参数
$source_id = intval($_GET['source_id']);
$col_id = intval($_GET['col_id']);

//读取播放数据
$playid = intval($_GET['play_id']);
$data = DB::queryFirstRow('SELECT * FROM '.table('data')." WHERE m_id=%i", intval($playid));
//解析数据
$play_show_data = parse_playdata_detail($data['m_playdata']);
//获取parse之后的playdata里面的数据
$rs = $play_show_data[intval($source_id)][$col_id];

?>

<div class="page_content">
	
	<div id="play_box">
		<?php echo $rs['playdata'];?>
	</div>
	
	<div class=""></div>
	<div class=""></div>
</div>

<?php 

//播放器函数
//player_id为数据库里播放器name，因为历史问题这里用的name方式来检索.
function make_player_by_name($player_name, $player_vars, $insert_key = '__P_VAR__'){
	global $config;
	$player_info = DB::queryFirstRow('SELECT * FROM '.table('player')." WHERE player_id=%s", $player_name);
	if(!$player_info){
		return "<span>无法找到 ".$player_name."</span>";
	}
	//依次用 $player_var 里的数据对模板进行替换.
	foreach($player_vars as $player_var){
		$player_info['html'] = str_replace($insert_key, $row, $player_info['html'], 1);
	}
	return $player_info['html'];
}

include ('common_footer.php');

?>