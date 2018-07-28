<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

//������صķ���

function cache_save($key, $content, $expires = 0){
	$rs = DB::queryFirstRow('SELECT * FROM '.table('cache')." WHERE m_key=%s", $key);
	if(!$rs){
		DB::insert(table('cache'), array(
			'm_key' => $key,
			'm_content' => $content,
			'm_timestamp' => TIMESTAMP,
			'm_expires' => $expires
		));
	}else{
		DB::update(table('cache'), array(
			'm_content' => $content,
		),"m_key=%s", $key);
	}
	return true;
}

function cache_load($key){
	$rs = DB::queryFirstRow('SELECT * FROM '.table('cache')." WHERE m_key=%s", $key);
	if(!$rs){
		return false;
	}
	//��黺���Ƿ����
	if($rs['m_timestamp'] + $rs['m_expires'] < TIMESTAMP){
		return false;
	}
	return $rs['m_content'];
}

?>