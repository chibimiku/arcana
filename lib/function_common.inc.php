<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

//获取毫秒级时间戳
function get_millisecond() {
	list($t1, $t2) = explode(' ', microtime());
	return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
}

function init_type(){
	global $config;
	//往config里放入缓存
	//同时支持正排查询和倒排查询
	$config['type_reverse'] = array();
	$c_type = DB::query('SELECT * FROM '.table('type').' WHERE 1=1');
	foreach($c_type as $row){
		$config['type'][$row['m_id']] = $row;
		$config['type_reverse'][$row['m_name']] = $row['m_id'];
	}
	return true;
}

//获取播放器的详情
function get_player_desc($name){
	$rs = DB::queryFirstRow('SELECT * FROM '.table('player')." WHERE m_name=%s", $name);
	if(!$rs){
		return '';
	}else{
		return $rs['m_desc'];
	}
}

//读取留言板列表
function get_leaveword($page = 1, $tpp = 30){
	$where_cond = ' WHERE m_replyid=0';
	$limit_cond = ' LIMIT '.$tpp;
	if($page > 1){
		$limit_cond = ' LIMIT '.($page-1)*$tpp.', '.$tpp;
	}
	$rs = DB::query('SELECT * FROM '.table('leaveword').$where_cond.' ORDER BY m_addtime DESC'.$limit_cond);
	foreach($rs as &$row){
		$reply_content = DB::queryFirstRow('SELECT * FROM '.table('leaveword')." WHERE m_replyid=$row[m_id]");
		if($reply_content){
			$row['re'] = $reply_content['m_content'];
		}
	}
	return $rs;
}

//读取cookie中的播放列表
function get_playlist(){
	//保存数据结构：每条key是di， array('name' => 名称, 'note' => 进度, 'timestamp' => 当时看的时间)
	if(!isset($_COOKIE['playlist'])){
		return array();
	}else{
		return unserialize($_COOKIE['playlist']);
	}
}

function insert_playlist($g_data){
	$max_saving = 10;
	//$g_data = DB::queryFirstRow('SELECT m_id, m_name, m_note FROM '.table('data')." WHERE m_id=%i", intval($data_id));
	if(!$g_data){
		return false;
	}
	$my_data = get_playlist();
	if(!isset($my_data[$g_data['m_id']])){
		if(count($my_data) >= 10){
			$ages_time = TIMESTAMP; //记录最远古一条的时间
			$ages_id = 0;
			foreach($my_data as $key => $row){
				if($row['timestamp'] <= $ages_time){
					$ages_time = $row['timestamp']; //记录最远古一条的时间
					$ages_id = $key;
				}
			}
			if($ages_id > 0){
				$my_data[$ages_id] = array('name' => $g_data['m_name'], 'note' => $g_data['m_note'], 'timestamp' => TIMESTAMP); //替换最古老的数据
			}
		}
	}else{
		//最近看过的数据里有这条，只更新观看时间
		$my_data[$g_data['m_id']]['timestamp'] = TIMESTAMP;
	}
	setcookie('playlist', serialize($my_data));
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

function get_table_field($table_name){
	$data_cols_name_rs = DB::query('SHOW columns FROM '.table($table_name));
	$data_cols = array();
	foreach($data_cols_name_rs as $row){
		$s_pos = strpos($row['Type'], '(');
		if($s_pos !== false){
			$row['Type'] = substr($row['Type'] ,0, $s_pos);
		}
		$data_cols[$row['Field']] = $row;
	}
	return $data_cols;
}


//带limit的替换
//来自https://stackoverflow.com/questions/8510223
function str_replace_limit($find, $replacement, $subject, $limit = 0){
  if($limit == 0){
	return str_replace($find, $replacement, $subject);
  }
  $ptn = '/' . preg_quote($find,'/') . '/';
  return preg_replace($ptn, $replacement, $subject, $limit);
}

//获取评论列表
function get_comment_data($data_id, $page, $tpp = 20){
	$lc = DB::queryFirstField('SELECT count(*) FROM '.table('review')." WHERE m_videoid=%i", $data_id);
	$page = max(1, intval($page));
	$limit_cond = 'LIMIT '.$tpp;
	if($page > 1){
		$limit_start = ($page - 1) * $tpp;
		$limit_cond = 'LIMIT '.$limit_start.', '.$tpp;
	}
	$total_page_num = $lc / $tpp;
	$data = DB::query('SELECT * FROM '.table('review')." WHERE m_videoid=%i ORDER BY m_addtime DESC ".$limit_cond, $data_id);
	return array('total_num' => $lc, 'total_page_num' => $total_page_num, 'data' => $data);
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
	//m_enabled 标志是否隐藏
	if($cata_id){
		$dbrs = DB::query('SELECT * FROM '.table('data')." WHERE m_enabled>0 AND m_type=%i $order_cond LIMIT $limit", $cata_id);
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
		if(!isset($data_1[1])){ //分割之后没有东西就返回空了
			continue;
		}
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
		$playdata[] = array('source_name' => $data_1[0], 'data' => $input_array, 'source_id' => $row1_count, 'ori_data' => $data_1[1]);
		++$row1_count;
	}
	return $playdata;
}

//以array批量读入post变量
//0是post，1是get
function read_user_info($var_names, $var_type = 0){
	$ret_array = array();
	foreach($var_names as $row){
		if($var_type == 0){
			if(isset($_POST[$row])){
				$ret_array[$row] = $_POST[$row];
			}else{
				$ret_array[$row] = '';
			}
		}else{
			if(isset($_GET[$row])){
				$ret_array[$row] = $_GET[$row];
			}else{
				$ret_array[$row] = '';
			}
		}
	}
	return $ret_array;
}

//把部分数据转化为intval，提高安全性
function set_intval($in_array, $intval_keys){
	foreach($intval_keys as $row){
		$in_array[$row] = intval($in_array[$row]);
	}
	return $in_array;
}

//显示错误信息
function showmessage($message, $jump_url = '', $title = '提示'){
	ob_end_clean();
	echo '<div style="margin: 0 auto;">';
	echo "<div id=\"error\" class=\"layui-bg-gray\"><h3 class=\"layui-bg-black\">$title</h3><div id=\"error_content\">";
	echo '<span>'.htmlspecialchars($message).'</span>';
	echo '</div>';
	if($jump_url){
		echo '<div><a href="'.$jump_url.'">等待3秒页面自动跳转，或者点击此处立即跳转。</a></div>';
	}
	echo '<button onclick="goBack()">点击返回</button><script>function goBack(){window.location.href="'.$jump_url.'";}</script>';
	if($jump_url){
		echo '<script>setTimeout(function(){window.location.href = "'.$jump_url.'"}, 3000);</script>';
	}
	echo '</div>';
	echo '</body></html>';
	exit();
}

function showmessage_json($message, $status = 0){
	show_data_json(array('status' => $status, 'message' => $message));
}

//向客户端发送json
function show_data_json($j_data){
	ob_end_clean();
	header('Content-type: application/json'); //返回json结果
	$json_result = json_encode($j_data, JSON_UNESCAPED_SLASHES);
	if(!$json_result){
		echo '{"message" : "输入的json不正确。"}';
	}else{
		echo $json_result;
	}
	exit();
}


?>