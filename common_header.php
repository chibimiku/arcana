<?php 
define ('IN_ARCANA', true);
define ('TIMESTAMP', time());
//公用的头部数据，含模板和db配置
include 'conf/config.inc.php';
include 'lib/meekrodb.2.3.class.php'; //db lib需要在db conf前面加载
include 'conf/db.inc.php';
include 'lib/function_common.inc.php'; //一些常见的公用函数
include 'lib/function_cache.inc.php'; //一些常见的公用函数

$start_time = get_millisecond();
ob_start();
?>

<!DOCTYPE html><html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta charset="utf-8" />
<meta name="referrer" content="always" />
<link rel="shortcut icon" href="favicon.ico?version=1" type="image/x-icon">
<title>
	<?php echo $config['site_name'].' '.$config['site_desc'];?>
</title>
<link type="text/css" rel="stylesheet" href="static/main/base.css" />
<link type="text/css" rel="stylesheet" href="static/layui/css/layui.css" />

<?php if(defined('IN_EDITOR')){ ?>
<!-- editor -->
	<link href="static/froala/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="static/froala/codemirror.min.css">
 
	<!-- Include Editor style. -->
	<link href="static/froala/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
	<link href="static/froala/froala_style.min.css" rel="stylesheet" type="text/css" />
<!-- editor end -->
<?php }?>
</head>

<body>
<!-- 引入公用js区 -->
<script src="static/layui/layui.js"></script>
<script src="static/jquery/jquery-3.3.1.min.js"></script>
<script src="static/jquery/jquery.form.min.js"></script>

<?php if(!isset($_POST['ajax']) && !isset($_GET['ajax'])){ob_flush();} //输出头部?>

<?php init_type(); //读取配置里的分类中文名称 ?>
<?php include 'block/block_header.inc.php';?>
<?php include 'block/block_week.inc.php'; //每周新番的block显示，remastered. ?>

