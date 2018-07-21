<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

if(!$uid){
	showmessage("您没有登录，无法登出。", 'index.php');
}else{
	DB::update(table('manager'), array('m_ssid' => ''), 'm_id=%i', $uid);
	setcookie('ssid', '');
	setcookie('uid', '');
	showmessage('您已成功登出。', 'index.php');
}

?>