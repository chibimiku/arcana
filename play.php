<?php 

//simple player site on route.

include ('common_header.php');

//设置一下两个输入参数
$source_id = intval($_GET['source_id']);
$row_id = intval($_GET['row_id']);

//读取播放数据
$playid = intval($_GET['id']);
$data = DB::queryFirstRow('SELECT * FROM '.table('data')." WHERE m_id=%i", intval($playid));
//解析数据
$play_show_data = parse_playdata_detail($data['m_playdata']);
//获取parse之后的playdata里面的数据
$rs = $play_show_data[$source_id]['data'][$row_id];
echo '<br /><br />';
?>

<div class="phere">
	<div id="crumblink" class="channel">
		<a href="/">首页</a>»
		<?php echo output_type_a($data['m_type']);?>» 
		<h1 id="playtitle"><?php echo $data['m_name'];?></h1>
	</div>
</div>
<div class="play-box">
	<div class="play">
		<div id="ccplay" class="ccplay_norm">
			<?php echo make_player_by_name($rs['playtype'], array($rs['playurl']));?>
		</div>
		<div class="playable_box">
			<div>
				<div>
					<script type="text/javascript" language="javascript" src="/js/ads/play_640-40-2.js"></script>
					<a class="topban_1" id="topban" href="http://www.acmoba.com/download.html?from=tssp" target="_blank">
						<img id="topbanimg" src="http://wx3.sinaimg.cn/large/7044f931gy1fqi9hcwr4hj20go02iq4i.jpg" alt="天使动漫" width="640" height="90" />
					</a>
				</div>
				<div id="play_control">
					<a href="javascript:pgup()">
						<img src="static/image/shang.gif">
					</a> 
					<a href="detail.php?id=<?php echo $playid;?>">
						<img src="static/image/mulu.gif">
					</a>
					<a href="javascript:pgdn()">
						<img src="static/image/xia.gif">
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="a300 something_to_be_fixed">
		<div><script type="text/javascript" language="javascript" src="/js/ads/play_300-250.js"></script><a class="topban_1" id="topban" href="https://show.bilibili.com/platform/detail.html?id=12967&amp;from=pc" target="_blank"><img id="topbanimg" src="http://wx1.sinaimg.cn/large/7044f931gy1ft5pl8wfkbj208c06yace.jpg" alt="天使动漫" width="300" height="250"></a>
		</div><table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tbody><tr>

		</tr>
		<tr>
		<td>
		<div><script type="text/javascript" language="javascript" src="/js/ads/play_300-250-2.js"></script><script src="http://js.wo-x.cn/29604"></script>
		</div>
		</td>
		</tr></tbody></table>
	</div>
	<div class="cl"></div>
</div>
<!-- 播放box end -->
<!-- 公告区 start -->
<div class="page_content">
	
</div>
<!-- 公告区 end -->
<!-- 评论区 start -->
<div class="page_content">
	<?php include 'block/block_comment.inc.php'; ?>
</div>
<!-- 评论区 end -->


<?php 

//播放器函数
//player_id为数据库里播放器name，因为历史问题这里用的name方式来检索.
function make_player_by_name($player_name, $player_vars, $insert_key = '__P_VAR__'){
	global $config;
	$player_info = DB::queryFirstRow('SELECT * FROM '.table('player')." WHERE m_name=%s", $player_name);
	if(!$player_info){
		return "<span>无法找到 ".$player_name."</span>";
	}
	//依次用 $player_var 里的数据对模板进行替换.
	foreach($player_vars as $player_var){
		$player_info['m_html'] = str_replace_limit($insert_key, $player_var, $player_info['m_html'], 1);
	}
	return $player_info['m_html'];
}

include ('common_footer.php');

?>