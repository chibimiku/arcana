<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

$type = '';
if(isset($_GET['type'])){
	$type = $_GET['type'];
}

//直接一口气编辑所有的表格

switch($type){
	case 'banner':
		$table_name = 'ads';
		$table_head = array('ID', '中文名称', '英文ID', '描述', 'HTML正文', '修改时间', '提交');
		break;
	case 'player':
		$table_name = 'player';
		$table_head = array('ID', '播放器英文标识', '播放器HTML', '提交');
		break;
	case 'pic':
		$table_name = 'index_picblock';
		$table_head = array('ID', '图片地址', '图片链接', '中文名称', '提交');
		break;
	case 'nav':
		$table_name = 'nav';
		$table_head = array('ID', '中文名称', '链接', '颜色(#CCCCCC)', '新窗口打开', '显示顺序(从大到小)', '提交');
		break;
	default:
		
}

$data = DB::query('SELECT * FROM '.table($table_name));
foreach($data as &$row){
	$row[] = '<a href="#">编辑</a>';
}

?>

<form action="" method="post">

<?php
echo draw_table($table_head, $data, 'layui-table');
?>

</form>