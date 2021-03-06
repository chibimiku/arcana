<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Denied.');
}

//主界面，显示所有播放数据/文档数据结果，提供编辑入口。

$page = 1;
$tpp = 30;
if(isset($_GET['page'])){
	$page = max(1, intval($_GET['page']));
}
$total_count = DB::queryFirstField('SELECT count(*) FROM '.table('data'));

$limit_cond = ' LIMIT '.$tpp;
if($page > 1){
	$limit_cond = ' LIMIT '.($page-1)*$tpp.', '.$tpp;
}

//处理 WHERE 条件
$where_cond = '';
$query = '';
if(isset($_GET['query'])){
	$query = $_GET['query'];
	$where_cond = ' WHERE m_name LIKE %ss';
}

$base_fields = array('m_id', 'm_name', 'm_type', 'm_hit', 'm_addtime', 'm_datetime', 'm_enabled');
$base_names = array('ID', '名称(点击进行编辑)', '类型', '人气(点开)', '添加时间', '修改时间', '生效', '操作');
if($query){
	$pg_data = DB::query('SELECT '.implode(',', $base_fields).' FROM '.table('data').$where_cond." ORDER BY m_addtime DESC ".$limit_cond, $query);
	//fix total_count
	$total_count = DB::queryFirstField('SELECT count(*) FROM '.table('data').$where_cond, $query);
}else{
	$pg_data = DB::query('SELECT '.implode(',', $base_fields).' FROM '.table('data')." ORDER BY m_addtime DESC ".$limit_cond);
}
foreach($pg_data as &$row){
	$row['del_btn'] = create_link($row['m_enabled'] ? '[屏蔽]' : '[恢复]', 'index.php?action=edit_submit&delete_id='.$row['m_id']);
	$row['m_name'] = create_link($row['m_name'], 'index.php?action=edit&data_id='.$row['m_id']);
	if(isset($config['type'][$row['m_type']])){
		$row['m_type'] = $config['type'][$row['m_type']]['m_name'];
	}
	$row['m_hit'] = create_link($row['m_hit'], '../detail.php?id='.$row['m_id'], true);
}

?>

<form class="layui-form" action="index.php?action=list&type=video" method="get">
	<div class="layui-form-item">
		<label class="layui-form-label">搜索数据</label>
		<div class="layui-input-inline">
			<input value="<?php echo $query;?>" class="layui-input" size="60" name="query" type="text" name="title" lay-verify="required" placeholder="搜索数据" autocomplete="off" class="layui-input" />
		</div>
		<button class="layui-btn" lay-submit lay-filter="formDemo">搜索</button>
		<a href="index.php?action=edit&data_id=0">【添加数据】</a>
	</div>
</form>

<?php

echo draw_table($base_names, $pg_data, 'layui-table');

?>

<div class="pager">
	<?php echo multi($total_count, $tpp, $page, "index.php?action=list");?>
</div>