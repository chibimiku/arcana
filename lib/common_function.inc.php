<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

 //拼接表名
function table($table_name){
	global $config;
	return $config['table_pre'].'_'.$table_name;
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