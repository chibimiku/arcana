<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}


if(isset($_POST['steel']) && isset($_GET['data_id'])){
	$data_id = intval($_GET['data_id']);
	$data_cols = get_table_field('data'); //获取表格的titles
	$update_data = array(); //最终提交的data块
	foreach($data_cols as $d_key => $d_row){
		if(isset($_POST[$d_row['Field']])){
			$update_data[$d_row['Field']] = $_POST[$d_row['Field']];
		}
	}
	DB::update(table('data'), $update_data, "m_id=%i", $data_id);
	showmessage('更新完成', 'index.php');
}else{
	showmessage('error');
}

?>