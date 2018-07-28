<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

global $config;
$config = array(
	'site_name' => 'S-DM',
	'site_desc' => '新番动漫在线 BD无修动漫在线 ,最新美剧在线',
	'table_pre' => 'm', 	//表名前缀
	'type' => array(), //存放播放数据的type
	'upload_temp_dir' => 'd:/tmp/upload/',
);

date_default_timezone_set('Asia/Shanghai'); //设置时区，让那个warning闭嘴


?>