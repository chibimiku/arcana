<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

 //拼接表名
function table($table_name){
	global $config;
	return $config['table_pre'].'_'.$table_name;
}

//获取分类ID的名称
function get_cata_name_by_id($cata_id){
	$rs = DB::queryFirstRow('SELECT m_name FROM '.table('type')." WHERE m_id=%i", intval($cata_id));
	if(!$rs){
		return 'null';
	}else{
		return $rs['m_name'];
	}
}

//获取按分类ID的前x条儿结果
function get_data_by_cata_id($cata_id, $limit = 10, $by_type = -1){
	$order_cond = '';
	switch($by_type){
		case 0:
			$order_cond = 'ORDER BY m_hit DESC'; //按点击数降序
			break;
		case 1:
			$order_cond = 'ORDER BY m_hit ASC'; //按点击数升序
			break;
		case 2:
			$order_cond = 'ORDER BY m_datetime DESC'; //按更新时间降序
			break;
		case 3:
			$order_cond = 'ORDER BY m_datetime ASC'; //按更新时间升序
			break;
		default:
			$order_cond = '';
	}
	return DB::query('SELECT * FROM '.table('data')." WHERE m_type=%i $order_cond LIMIT $limit", intval($cata_id));
}

//根据data生成数据块儿
function generate_ul_block($title, $data, $class='', $id=''){
	$return_str = '<div id="'.$id.'" class="'.$class.'"><h3 class="block_title">'.htmlspecialchars($title).'</h3><ul>';
	foreach($data as $row){
		$return_str = $return_str.'<li>';
		$return_str = $return_str.'<a href="'.$row['link'].'" title="'.$row['name'].'"><img src="'.$row['image'].'" /><span>'.$row['name'].'</span></a>';
		$return_str = $return_str.'</li>';
	}
	$return_str = $return_str.'</ul></div>';
}


//对playdata里m_playdata那个别扭的字段进行解析
//注意$data_2分割符是否正确按db来的.
function parse_playdata_detail($in_str){
	$playdata = array();
	$play_sources = explode('$$$', $in_str);
	foreach($play_sources as $row1){
		$data_1 = explode("$$", $row2);
		$data_2 = explode("\r\n", $data_1[1]); //每个源里按行分割的数据
		//$playdata[$data_1[0]] = array();
		$input_array = array(); 
		foreach($data_2 as $row3){
			$data_3 = explode('$', $row3);
			$input_array[] = array(
				'name' => $row3[0],
				'playdata' => $row3[1],
				'playtype' => $row3[2],
			);
		}
		$playdata[] = array('source_name' => $data_1[0], 'data' => $input_array);
	}
	return $playdata;
}

//显示错误信息
function showmessage($message){
	ob_end_clean();
	echo $message;
	exit();
}


?>