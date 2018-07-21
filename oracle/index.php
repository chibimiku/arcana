<?php 

define ('IN_ARCANA', true);
define ('IN_ARCANA_ADMIN', true);
define ('EDITOR', true);
define ('TIMESTAMP', time());

//simple player site on route.
include 'header.inc.php';

if(!isset($_GET['action'])){
	$_GET['action'] = '';
}

switch($_GET['action']){
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
	default:
		include 'main.inc.php';
}

include ('footer.inc.php');
?>