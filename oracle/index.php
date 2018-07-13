<?php 

define ('IN_ARCANA_ADMIN', true);
define ('EDITOR', true);

//simple player site on route.
include '../common_header.php';
include 'block/block_header.inc.php'; //引入头部

//是否登录逻辑
if(isset($_COOKIE['uid']) && isset($_COOKIE['se_key'])){
	$userinfo = DB::queryFirstRow('SELECT * FROM '.table('manager').' WHERE m_id=%i', intval($_COOKIE['uid']));
	//check se_key ok.
}

switch($_GET['action']){
	case 'edit':
		include 'edit.inc.php';
		break;
	case 'login':
		$userinfo = DB::queryFirstRow('SELECT * FROM '.table('manager').' WHERE m_id=%i', intval($_COOKIE['uid']));
		break;
	default:
		include 'login.inc.php';
}

include ('../common_footer.php');
?>