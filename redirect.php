<?php 

if(!isset($_GET['url'])){
	exit('Access Deined.');
}

define ('IN_ARCANA', true);
define ('TIMESTAMP', time());
//公用的头部数据，含模板和db配置
include 'conf/config.inc.php';
include 'lib/meekrodb.2.3.class.php'; //db lib需要在db conf前面加载
include 'conf/db.inc.php';
include 'lib/function_common.inc.php'; //一些常见的公用函数
include 'lib/function_cache.inc.php'; //一些常见的公用函数

$s_input = get_last_i($_GET['url']);

$found = false;
//依次查找分类和vod
if(!$s_input || $s_input == '/'){
	header('Location: index.php');
}else{
	$rs_type = DB::queryFirstRow('SELECT m_id FROM '.table('type')." WHERE m_enname=%s", $s_input);
	if($rs_type){
		header('Location: search.php?type='.$rs_type['m_id']);
		$found = true;
	}else{ //注意分支，不应该逻辑上同时决定发多个header。
		$rs_vod = DB::queryFirstRow('SELECT m_id FROM '.table('data')." WHERE m_enname=%s", $s_input);
		if($rs_vod){
			header('Location: detail.php?id='.$rs_vod['m_id']);
			$found = true;
		}
	}
}

if(!$found){
	exit('Cannot found.');
}

function get_last_i($in_url){
	if(!$in_url){
		return '/';
	}
	$tmp_data = explode('/', $in_url);
	$tmp_ret = '';
	foreach($tmp_data as $row){
		if(!empty($row)){
			$tmp_ret = $row;
		}
	}
	return $tmp_ret;
}

?>