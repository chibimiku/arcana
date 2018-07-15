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
<link type="text/css" rel="stylesheet" href="static/main.css" />
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
#head {
    width: 960px;
    height: 170px;
    position: relative;
    margin: 0 auto;
    background: url(head_bg.jpg) no-repeat 0 0px;
    margin-bottom: 10px;
}

#head .logo {
    position: absolute;
    top: 12px;
    left: 0;
    width: 185px;
    height: 49px;
    background: url(static/image/logo.gif) no-repeat;
}

#head .logo a {
    text-indent: -9999px;
    width: 172px;
    height: 49px;
    display: block;
}

.tInfo {
    position: absolute;
    top: 10px;
    right: 0;
    color: #666;
}

.tInfo a {
    margin: 0 3px;
}

.uInfo {
    position: absolute;
    top: 40px;
    left: 190px;
    color: #999;
}

.search {
    position: absolute;
    top: 35px;
    right: 0px;
}

.search-form {
    float: left;
    height: 24px;
    line-height: 24px;
    background: url(layout.css) no-repeat 0 -160px;
}

.search-form li {
    float: left;
    margin: 0 0 0 3px;
    padding: 0;
    position: relative;
}

#head #navigation {
    width: 960px;
    height: 74px;
    overflow: hidden;
    position: absolute;
    top: 100px;
    background: url(soo.gif) no-repeat 0 0;
}

#head #navigation ul {
    width: 960px;
    line-height: 33px;
    font-size: 14px;
    position: absolute;
    top: 1px;
    left: 4px;
}

#head #navigation li {
    display: block;
    height: 33px;
    overflow: hidden;
    float: left;
    padding-left: 2px;
    background: url(head.gif) no-repeat 0 -60px;
    position: relative;
    left: -2px;
}

#head #navigation li a {
    font-weight: bold;
    float: left;
    padding: 0 14px;
    color: #fff;
}

#head #submenu {
    position: absolute;
    top: 170px;
    width: 960px;
    font-size: 13px;
    height: 35px;
    line-height: 35px;
    color: #999;
    overflow: hidden;
    background: url(cmenu.gif) no-repeat 0 0;
}

#head #submenu big {
    display: block;
    float: left;
    font-size: 12px;
    height: 35px;
    font-weight: bold;
    padding-left: 35px;
    color: #333;
    background: url(layout.css) no-repeat 10px center;
}

#head #submenu a {
    margin: 0 7px;
    color: #06c;
}

#head #navigation li a:hover, #head #navigation li.selected a {
    text-decoration: none;
    background: url(head.gif) no-repeat 50% -60px;
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

