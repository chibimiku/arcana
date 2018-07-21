<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Denied.');
}

//后台box//公用的头部数据，含模板和db配置
include '../conf/config.inc.php';
include '../lib/meekrodb.2.3.class.php'; //db lib需要在db conf前面加载
include '../conf/db.inc.php';
include '../lib/common_function.inc.php'; //一些常见的公用函数

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
</head>
<body>

<script src="../static/layui/layui.js"></script>

<!-- 导航 -->
<ul class="layui-nav" lay-filter="">
  <li class="layui-nav-item"><a href="./index.php">首页</a></li>
  <li class="layui-nav-item"><a href="./index.php?action=list&type=video">视频编辑</a></li>
  <li class="layui-nav-item"><a href="./index.php?action=member">成员编辑</a></li>
  <?php if($uid){?>
	<li class="layui-nav-item login_btn"><a href="./index.php?action=logout"><?php echo $username?></a></li>
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