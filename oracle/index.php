<?php 


define ('IN_ARCANA', true);
define ('IN_ARCANA_ADMIN', true);
define ('EDITOR', true);
define ('TIMESTAMP', time());

ob_start();

header('X-XSS-Protection: 0'); //对提交iframe做容错，风险微增
//simple player site on route.
include 'header.inc.php';

if(!isset($_GET['action'])){
	$_GET['action'] = '';
}

switch($_GET['action']){
	case 'edit_submit':
		include 'edit_submit.inc.php';
		break;
	case 'edit':
		include 'edit.inc.php';
		break;
	case 'login':
		include 'login.inc.php';
		break;
	case 'logout':
		include 'logout.inc.php';
		break;
	case 'member':
		include 'member.inc.php';
		break;
	case 'links':
		include 'links.inc.php';
		break;
	case 'upload':
		include 'upload.inc.php';
		break;
	case 'reply':
		include 'reply.inc.php';
		break;
	default:
		include 'main.inc.php';
}

include ('footer.inc.php');
?>