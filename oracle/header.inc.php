<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Denied.');
}

//后台box//公用的头部数据，含模板和db配置
include '../conf/config.inc.php';
include '../lib/meekrodb.2.3.class.php'; //db lib需要在db conf前面加载
include '../conf/db.inc.php';
include '../lib/common_function.inc.php'; //一些常见的公用函数

?>
<!DOCTYPE html>
<html lang="cn">
<head>
	<title>神奇的后台</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="../static/layui/css/layui.css" />
</head>
<body>

<script src="../static/layui/layui.js"></script>

<!-- 导航 -->
<ul class="layui-nav" lay-filter="">
  <li class="layui-nav-item"><a href="./index.php">首页</a></li>
  <li class="layui-nav-item"><a href="./index.php?action=list&type=video">视频编辑</a></li>
</ul>
 
<script>
//注意：导航 依赖 element 模块，否则无法进行功能性操作
layui.use('element', function(){
  var element = layui.element;
  
  //…
});
</script>
