<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Denied.');
}

//后台box//公用的头部数据，含模板和db配置
include '../conf/config.inc.php';
include '../lib/meekrodb.2.3.class.php'; //db lib需要在db conf前面加载
include '../conf/db.inc.php';
include '../lib/function_common.inc.php'; //一些常见的公用函数
include '../lib/function_template.inc.php'; //一些常见的公用函数
include 'data_fieldname.inc.php'; //加载一下表格字段名称的中文

init_type(); //载入type中文名称

$uid = 0;
$username = '';
//检查是否登录
if(isset($_COOKIE['ssid']) && isset($_COOKIE['uid'])){
	$rs = DB::queryFirstRow('SELECT * FROM '.table('manager')." WHERE m_ssid=%s AND m_id=%i", $_COOKIE['ssid'], intval($_COOKIE['uid']));
	if($rs){
		$uid = $rs['m_id'];
		$username = $rs['m_username'];
	}
	unset($rs);
}

?>
<!DOCTYPE html>
<html lang="cn">
<head>
	<title>Misty sky 先知后台系统</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="../static/layui/css/layui.css" />
	<link rel="stylesheet" href="mcv.css" />
	<!-- Include external CSS. -->
    <link href="../static/froala/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../static/froala/codemirror.min.css">
 
    <!-- Include Editor style. -->
    <link href="../static/froala/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
    <link href="../static/froala/froala_style.min.css" rel="stylesheet" type="text/css" />
	
</head>
<body>

<script src="../static/layui/layui.js"></script>

<!-- Include external JS libs. -->
<script type="text/javascript" src="../static/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../static/froala/codemirror.min.js"></script>
<script type="text/javascript" src="../static/froala/xml.min.js"></script>

<!-- Include Editor JS files. -->
<script type="text/javascript" src="../static/froala/froala_editor.pkgd.min.js"></script>

<!-- 导航 -->
<ul class="layui-nav" lay-filter="">
  <li class="layui-nav-item"><a href="./index.php">首页</a></li>
  <li class="layui-nav-item"><a href="./index.php?action=list&type=video">视频编辑</a></li>
  <li class="layui-nav-item"><a href="./index.php?action=member">成员编辑</a></li>
  <li class="layui-nav-item"><a href="./index.php?action=links&type=banner">布告栏编辑</a></li>
  <li class="layui-nav-item"><a href="./index.php?action=links&type=player">播放器编辑</a></li>
  <li class="layui-nav-item"><a href="./index.php?action=links&type=pic">首页图编辑</a></li>
  <li class="layui-nav-item"><a href="./index.php?action=links&type=nav">导航编辑</a></li>
  <li class="layui-nav-item"><a href="./index.php?action=links&type=week">周番编辑</a></li>
  <li class="layui-nav-item"><a href="./index.php?action=links&type=leaveword">留言板编辑</a></li>
  <li class="layui-nav-item"><a href="./index.php?action=links&type=type">分类编辑</a></li>
  <?php if($uid){?>
	<li class="layui-nav-item login_btn">
		 <a href="javascript:;"><?php echo $username?></a>
		<dl class="layui-nav-child">
		<dd><a href="./index.php?action=logout">登出</a></dd>
		</dl>
	</li>
  <?php }else{?>
	<li class="layui-nav-item login_btn"><a href="./index.php?action=login">登录</a></li>
  <?php }?>
</ul>
 
<script>
//注意：导航 依赖 element 模块，否则无法进行功能性操作
layui.use('element', function(){
  var element = layui.element;
  
  //…
});
</script>
<div class="blank10"></div>