<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

if(!$uid){
	showmessage("��û�е�¼���޷��ǳ���", 'index.php');
}else{
	DB::update(table('manager'), array('m_ssid' => ''), 'm_id=%i', $uid);
	setcookie('ssid', '');
	setcookie('uid', '');
	showmessage('���ѳɹ��ǳ���', 'index.php');
}

?>