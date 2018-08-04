<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

$type = '';
if(isset($_GET['type'])){
	$type = $_GET['type'];
}

//
$html_sp_key = array('m_html'); //需要htmlspecialchar加工的key集合

//直接一口气编辑所有的表格

switch($type){
	case 'banner':
		$table_name = 'ads';
		$table_head = array('ID', '中文名称', '英文ID', '描述', 'HTML正文', '修改时间', '编辑');
		break;
	case 'player':
		$table_name = 'player';
		$table_head = array('ID', '播放器英文标识', '播放器HTML', '编辑');
		break;
	case 'pic':
		$table_name = 'index_picblock';
		$table_head = array('ID', '图片地址', '图片链接', '中文名称', '编辑');
		break;
	case 'nav':
		$table_name = 'nav';
		$table_head = array('ID', '中文名称', '链接', '颜色(#CCCCCC)', '新窗口打开', '显示顺序(从大到小)', '编辑');
		break;
	case 'week':
		$table_name = 'week_board';
		$table_head = array('ID', '周期(0是周日)', 'HTML', '编辑');
		break;
	default:
		showmessage('错误：空的类型');
}

if(isset($_GET['editid'])){
	//get field
	layui_load_module('form');
	$data_cols = get_table_field($table_name); //获取表格的titles
	$editid = intval($_GET['editid']);
	$data = DB::queryFirstRow('SELECT * FROM '.table($table_name)." WHERE m_id=%i", $editid); //获取具体数据
	$data_r = array(); 
	foreach($data as $key => $value){
		$data_r[] = array('name' => $key, 'value' => $value, 'type' => $data_cols[$key]['Type']);
	}
	echo draw_form($data_r, 'index.php?action=links&type='.$type."&update_id=".$editid, $table_name, 'layui-form', array('m_id'), 'k_delete');
}else if(isset($_GET['update_id'])){
	$update_id = intval($_GET['update_id']);
	$update_array = array();
	$data_cols = get_table_field($table_name);
	foreach($data_cols as $key => $row){
		if($row['Key'] == 'PRI'){
			continue; //update里略过不应该修改的主键
		}
		$update_array[$key] = $_POST[$key];
	}
	$delete = false;
	if(isset($_POST['k_delete'])){
		if($_POST['k_delete'] == 1){
			$delete = true;
		}
	}
	if($delete){
		DB::delete(table($table_name), "m_id=%i", $update_id);
	}else{
		DB::update(table($table_name),$update_array ,"m_id=%i", $update_id);
	}
	showmessage('任务完成。', 'index.php?action=links&type='.$type);
}else if(isset($_GET['add'])){
	$data_cols = get_table_field($table_name);
	$data_r = array(); 
	foreach($data_cols as $row){
		$data_r[] = array('name' => $row['Field'], 'value' => '', 'type' => $row['Type']);
	}
	echo draw_form($data_r, 'index.php?action=links&type='.$type.'&add_submit=1', $table_name, '', array('m_id'));
}else if(isset($_GET['add_submit'])){
	$update_array = array();
	$data_cols = get_table_field($table_name);
	foreach($data_cols as $key => $row){
		if($row['Key'] == 'PRI'){
			continue;//跳过primary key
		}
		$update_array[$key] = $_POST[$key];
	}
	DB::insert(table($table_name),$update_array);
	showmessage('添加完成。', 'index.php?action=links&type='.$type);
}else{
	$data = DB::query('SELECT * FROM '.table($table_name));
	foreach($data as &$row){
		$row[] = '<a href="index.php?action=links&type='.$type.'&editid='.$row['m_id'].'">编辑</a>';
		foreach($html_sp_key as $sk){
			if(isset($row[$sk])){
				$row[$sk] = nl2br(htmlspecialchars($row[$sk])); //对特殊的text类数据字段进行处理
			}
		}
	}
	echo create_link('创建新数据', 'index.php?action=links&add=1&type='.$type, '', '#777777');
	echo draw_table($table_head, $data, 'layui-table');
	
}
?>


