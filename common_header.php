<?php 
define ('IN_ARCANA', true);
//公用的头部数据，含模板和db配置
include 'conf/config.inc.php';
include 'lib/meekrodb.2.3.class.php'; //db lib需要在db conf前面加载
include 'conf/db.inc.php';
?>

<!DOCTYPE html><html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta charset="utf-8" />
<meta name="referrer" content="always" />
<title>
	<?php echo $config['site_name'].' '.$config['site_desc'];?>
</title>
<link type="text/css" rel="stylesheet" href="static/main.css" />
<link type="text/css" rel="stylesheet" href="static/layui/css/layui.css" />
</head>

<body>
<!-- 引入公用js区 -->
<script src="static/layui/layui.js"></script>
<script src="static/jquery/jquery-3.3.1.min.js"></script>
<?php 
	include 'block_header.inc.php';
?>

