<?php 

define ('IN_ARCANA', true);
define ('IN_ARCANA_ADMIN', true);
define ('EDITOR', true);
define ('TIMESTAMP', time());

//simple player site on route.
include 'header.inc.php';

//是否登录逻辑
if(isset($_COOKIE['uid']) && isset($_COOKIE['se_key'])){
	$userinfo = DB::queryFirstRow('SELECT * FROM '.table('manager').' WHERE m_id=%i', intval($_COOKIE['uid']));
	//check se_key ok.
}

if(!isset($_GET['action'])){
	$_GET['action'] = '';
}

switch($_GET['action']){
	case 'edit':
		include 'edit.inc.php';
		break;
	case 'login':
		$userinfo = DB::queryFirstRow('SELECT * FROM '.table('manager').' WHERE m_id=%i', intval($_COOKIE['uid']));
		break;
	default:
		//include 'login.inc.php';
		include 'main.inc.php';
}

include ('footer.inc.php');
?>