<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

//每周新番动画数据推荐的格子

$week_data = DB::query('SELECT m_id,m_name,m_week,m_color FROM '.table('data')." WHERE m_show_week>0");
$week_show_data = array();
for($i=0;$i<7;$i++){
	$week_show_data[$i] = array();
}
foreach($week_data as $row){
	$week_show_data[$row['m_week']][] = $row;
}

$index_array = array('周日', '周一', '周二', '周三', '周四', '周五', '周六', );

$today_order = date('w'); //今天是星期几，注意周日是0.
$before_order = $today_order - 1 < 0 ? 6 : $today_order - 1; //周日的前一天是周六
$after_order = $today_order + 1 > 6 ? 0 : $today_order + 1;

function show_day_link($data){
	$return_str = '';
	foreach($data as $row){
		$return_str = $return_str.create_link($row['m_name'], 'detail.php?id='.$row['m_id'], false, $row['m_color']);
	}
	return $return_str;
}

?>

<div class="page_content">
	<h3 class="titbar"><span></span><em>动漫新番更新</em></h3>
	<div class="anime_1">
		<span><?php echo $index_array[$before_order];?></span>
		<?php echo show_day_link($week_show_data[$before_order]);?>
	</div>
	<div class="anime_2">
		<span><?php echo $index_array[$today_order];?></span>
		<?php echo show_day_link($week_show_data[$today_order]);?>
	</div>
	<div class="anime_3">
		<span><?php echo $index_array[$after_order];?></span>
		<?php echo show_day_link($week_show_data[$after_order]);?>
	</div>
</div>