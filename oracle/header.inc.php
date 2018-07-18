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
	 <meta charset="utf-8">
</head>
<body>
