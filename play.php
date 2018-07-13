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

include ('common_footer.php');

?>