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

$base_fields = array('m_id', 'm_name', 'm_type', 'm_addtime', 'm_datetime');
$base_names = array('ID', '名称(点击进行编辑)', '类型', '添加时间', '修改时间', '删除');
if($query){
	$pg_data = DB::query('SELECT '.implode(',', $base_fields).' FROM '.table('data').$where_cond." ORDER BY m_addtime DESC ".$limit_cond, $query);
	//fix total_count
	$total_count = DB::queryFirstField('SELECT count(*) FROM '.table('data').$where_cond, $query);
}else{
	$pg_data = DB::query('SELECT '.implode(',', $base_fields).' FROM '.table('data')." ORDER BY m_addtime DESC ".$limit_cond);
}
foreach($pg_data as &$row){
	$row['del_btn'] = create_link('[删除]', 'index.php?action=delete&id='.$row['m_id']);
	$row['m_name'] = create_link($row['m_name'], 'index.php?action=edit&data_id='.$row['m_id']);
}

?>

<form class="layui-form" action="index.php?action=list&type=video" method="get">
	<div class="layui-form-item">
		<label class="layui-form-label">搜索数据</label>
		<div class="layui-input-inline">
			<input value="<?php echo $query;?>" class="layui-input" size="60" name="query" type="text" name="title" lay-verify="required" placeholder="搜索数据" autocomplete="off" class="layui-input" />
		</div>
		<button class="layui-btn" lay-submit lay-filter="formDemo">搜索</button>
	</div>
</form>

<?php

echo draw_table($base_names, $pg_data, 'layui-table');

?>

<div class="pager">
	<?php echo multi($total_count, $tpp, $page, "index.php?action=list");?>
</div>