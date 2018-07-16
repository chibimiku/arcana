<?php 
define ('IN_ARCANA', true);
//公用的头部数据，含模板和db配置
include 'conf/config.inc.php';
include 'lib/meekrodb.2.3.class.php'; //db lib需要在db conf前面加载
include 'conf/db.inc.php';
include 'lib/common_function.inc.php'; //一些常见的公用函数
?>

<!DOCTYPE html><html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta charset="utf-8" />
<meta name="referrer" content="always" />
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

<style>
.update {
    width: 480px;
    float: left;
    margin-left: 10px;
}

.serial {
    margin-bottom: 10px;
    width: 480px;
    clear: both;
    float: left;
}

.serial .tabs {
    font-size: 13px;
    color: #016A9F;
    height: 30px;
    line-height: 30px;
    background: url(sprite.png) 0 -40px no-repeat;
}

.serial .tabs h3 {
    width: 76px;
    text-indent: -9999px;
    float: left;
    padding: 0 10px;
    background: url(sprite.png) no-repeat 10px -143px;
}

.serial .tabs ul {
    float: left;
    margin-top: 6px;
}

.serial .tabs li {
    width: 70px;
    text-align: center;
    font-size: 12px;
    height: 24px;
    line-height: 24px;
    float: left;
    margin: 0 2px;
    cursor: pointer;
    background: url(sprite.png) no-repeat 0 -90px;
}

.serial .tabs a {
    color: #0071C7;
    padding: 0 10px;
}

.serial ul.details {
    width: 473px;
    padding: 3px 0 0 5px;
    border: 1px solid #97C3E5;
    border-top: none;
    display: none;
}
.serial ul.details li {
    float: left;
    width: 234px;
    border-bottom: 1px #CAD9E3 dotted;
    height: 27px;
    line-height: 27px;
    overflow: hidden;
}

.serial li span.date {
    margin-right: 3px;
    font-size: 11px;
    font-family: Arial;
}

.serial li span {
    padding: 1px;
    color: #aaa;
}

.serial li span.setnum {
    color: #080;
}
</style>

<style>
.page_content:after {
	content: ".";
    display: block;
    height: 0;
    clear: both;
    visibility: hidden;
}

.box_960 {
	width: 960px;
	margin: 0 auto;
}

.box {
	border:1px #BFE6F4 solid;
}

.titbar {
    font-size: 13px;
    color: rgb(0, 113, 199);
    height: 31px;
    line-height: 31px;
    background: url(static/image/sprite.png) left top no-repeat;
}

.home-plist {
    width: 718px;
    height: auto;
    padding-bottom: 10px;
    border-width: 1px 1px 1px;
    border-style: none solid solid;
    border-color: rgb(151, 195, 229) rgb(151, 195, 229) rgb(151, 195, 229);
    border-image: initial;
    border-top: none;
    overflow: hidden;
}

.home-plist ul:after {
    clear: both;
}

.home-plist li {
    padding: 10px 3px 0 9px;
    float: left;
    width: 106px;
	list-style: none;
}

.home-plist li a {
    cursor: pointer;
}

.home-plist li img {
    width: 100px;
    height: 130px;
    padding: 2px;
    border: 1px solid #dedede;
}

.home-plist li a span {
    display: block;
    width: 106px;
    padding-top: 6px;
    font-size: 12px;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    color: #0071C7;
}

.home-top-new {
    width: 958px;
    height: auto;
    padding-bottom: 10px;
    border-width: 1px 1px 1px;
    border-style: none solid solid;
    border-color: rgb(151, 195, 229) rgb(151, 195, 229) rgb(151, 195, 229);
    border-image: initial;
    border-top: none;
    overflow: hidden;
}

.home-top-new dl {
    float: left;
    margin-top: 5px;
    padding: 0 0 0 15px;
    width: 144px;
    color: #666;
    background: url(static/image/home_hotline.gif) repeat-y right;
}

.home-top-new dd {
    height: 24px;
    line-height: 24px;
    overflow: hidden;
}

.home-top-new dd em {
    padding-right: 5px;
    color: #999;
    font-size: 11px;
    font-family: Arial;
}

.home-top-new dd a {
    color: #016A9F;
}

.flink ul {
    width: 952px;
    height: auto;
    padding: 3px;
    border: 1px solid #97C3E5;
    border-top: none;
}
.flink li {
    float: left;
    margin: 0 6px;
    line-height: 24px;
    white-space: nowrap;
}

.footer {
    clear: both;
    width: 960px;
    text-align: center;
    margin: 0 auto;
    padding: 10px 0;
    line-height: 25px;
    color: #666;
    font-family: Arial;
    background: url(static/image/footer_bg.png) repeat-x top;
}

.footer div a {
    margin: 0 6px;
}

</style>

<?php 
	$c_type = DB::query('SELECT * FROM '.table('type').' WHERE 1=1');
	foreach($c_type as $row){
		$config['type'][$row['m_id']] = $row;
	}
?>
<?php include 'block/block_header.inc.php';?>
<?php include 'block/block_week.inc.php'; //每周新番的block显示，remastered. ?>

