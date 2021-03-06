<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

$is_update = false;
if(isset($_GET['data_id'])){
	if(intval($_GET['data_id']) > 0){
		$is_update = true;
	}
}

if($is_update){
	$data_id = intval($_GET['data_id']);
	$update_data = fill_update_data('data');
	DB::update(table('data'), $update_data, "m_id=%i", $data_id);
	showmessage('更新数据完成', 'index.php?action=list&type=video');
}else if(isset($_POST['steel'])){
	//添加数据逻辑，此时data_id为0
	$insert_data = $update_data = fill_update_data('data');	
	DB::insert(table('data'), $insert_data);
	$new_id = DB::insertId(); 
	showmessage('添加数据完成，新的ID是：'.$new_id, 'index.php?action=list&type=video');
}else if(isset($_GET['delete_id'])){
	$delete_id = intval($_GET['delete_id']);
	$rs_del = DB::queryFirstRow('SELECT * FROM '.table('data')." WHERE m_id=%i", $delete_id);
	if(!$rs_del){
		showmessage('找不到'.$delete_id, 'index.php');
	}
	if(!$rs_del['m_enabled']){
		DB::update(table('data'), array('m_enabled' => 1), "m_id=%i", $delete_id);
	}else{
		DB::update(table('data'), array('m_enabled' => 0), "m_id=%i", $delete_id);
	}
	showmessage('屏蔽/恢复数据'.$delete_id.'完成，', 'index.php?action=list&type=video');
}else{
	showmessage('错误，没有正确的输入', 'index.php');
}

//根据表格内容，找post里面的数据进行填充
function fill_update_data($table_name){
	$data_cols = get_table_field($table_name); //获取表格的titles
	$update_data = array(); //最终提交的data块
	foreach($data_cols as $d_key => $d_row){
		if($d_row['Key'] == 'PRI'){ //不处理主键数据，这个不需要update，insert的时候
			continue;
		}
		if(isset($_POST[$d_row['Field']])){
			$update_data[$d_row['Field']] = $_POST[$d_row['Field']];
		}
	}
	return $update_data;
}

?>