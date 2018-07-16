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

//输出某个数据所属分类的内容和连接A标签，用在如面包屑导航的地方.
function output_type_a($type_id){
	global $config;
	return '<a href="search.php?typeid='.$type_id.'">'.$config['type'][$type_id].'</a>';
}

//返回单条数据的a标签展示
function output_row_a($s_row, $new_window = true){
	if($new_window){
		return '<a href="detail.php?id='.$s_row['m_id'].'" target="_blank">'.htmlspecialchars($s_row['m_name']).'</a>';
	}else{
		return '<a href="detail.php?id='.$s_row['m_id'].'">'.htmlspecialchars($s_row['m_name']).'</a>';
	}
}

//获取评论列表
function get_comment_data($data_id, $page, $tpp = 20){
	$lc = DB::queryFirstField('SELECT count(*) FROM '.table('review')." WHERE m_id=%i", $data_id);
	$page = max(1, intval($page));
	$limit_cond = 'LIMIT '.$tpp;
	if($page > 1){
		$limit_start = ($page - 1) * $tpp;
		$limit_cond = 'LIMIT '.$limit_start.', '.$tpp;
	}
	$total_page_num = $lc / $tpp;
	$data = DB::query('SELECT * FROM '.table('review')." WHERE m_videoid=%i ".$limit_cond, $data_id);
	return array('total_page_num' => $total_page_num, 'data' => $data);
}

//获取按分类ID的前x条儿结果
function get_data_by_cata_id($cata_id, $limit = 10, $by_type = -1){
	$cata_id = intval($cata_id);
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
	if($cata_id){
		$dbrs = DB::query('SELECT * FROM '.table('data')." WHERE m_type=%i $order_cond LIMIT $limit", $cata_id);
	}else{
		$dbrs = DB::query('SELECT * FROM '.table('data')." $order_cond LIMIT $limit");
	}
	$in_line_count = 0;
	foreach($dbrs as &$row){
		$row['k_order'] = $in_line_count;
		++$in_line_count;
	}
	return $dbrs;
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
function parse_playdata_detail($in_str, $debug = false){
	$playdata = array();
	$play_sources = explode('$$$', $in_str);
	if($debug){
		var_dump($play_sources);
		$ss_tmp = explode("\n", $play_sources[0]);
		var_dump($ss_tmp);
		
	}
	$row1_count = 0; //这个是计数器，原始数据比较蠢没有做正确的索引
	foreach($play_sources as $row1){
		$data_1 = explode("$$", $row1);
		//用 $$ 分割之后，前面是整个source的名字，后面是播放数据.各行以#分割
		$data_2 = explode("#", $data_1[1]); //每个源里按行分割的数据
		$input_array = array(); 
		$row2_count = 0;
		foreach($data_2 as $row3){
			$data_3 = explode('$', $row3);
			$input_array[] = array(
				'name' => $data_3[0],
				'playurl' => $data_3[1],
				'playtype' => $data_3[2],
				'row_id' => $row2_count,
			);
			++$row2_count;
		}
		$playdata[] = array('source_name' => $data_1[0], 'data' => $input_array, 'source_id' => $row1_count);
		++$row1_count;
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