<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

//公用头部导航
//导航栏内容
$navi_array = array(
	'首 页' => '/',
	'comic' =>  'http://mh.tsdm.tv',
);

?>

<!-- block header start -->

<div id="header">
	<!-- 顶部第一个广告 start-->
	<div class="box_960 box">
		<script type="text/javascript" language="javascript" src="static/js/ads/long_title1.js"></script>
	</div>
	<!-- 顶部第一个广告 end-->
	<div id="head">
		<div class="logo">
			<a href="http://www.tsdm.me"><?php echo $config['site_desc']?></a>
		</div>
		<div class="tInfo">欢迎来到天使动漫。如果你觉得本站不错，请推荐给你的朋友~<a href="http://www.tsdm.me">点击访问论坛</a></div>
		<div class="uInfo">
			<a onclick="var strHref=window.location.href;this.style.behavior='url(#default#homepage)';this.setHomePage('http://www.tsdm.me');" href="#">设为主页</a> | 
			<a href="javascript:void(0)" onclick="addFavorite('http://www.tsdm.me','S-DM 新番动漫在线 BD无修动漫在线 ,最新美剧在线-www.tsdm.me');">收藏本站</a> | 
			<a href="/help.html" target="_blank">网站帮助</a> |
			<script type="text/javascript" src="static/js/jianfan.js"></script> <a href="javascript:void(null)" name="StranLink">繁體中文</a>
		</div>
		<div class="search"> 
			<form name="formsearch" id="formsearch" action="search.php" method="post" target="_blank">     
				<ul class="search-form">
				<li><input type="text" name="query" id="query" class="text" value="在此输入动漫名称" onfocus="if(this.value=='在此输入动漫名称'){this.value='';}" onblur="if(this.value==''){this.value='在此输入动漫名称';}">
				</li>         
				<li><input type="submit" name="submit" class="button" value="搜索"></li>
				</ul>
			</form>
		</div>
		<div id="navigation" class="box">
			<ul id="naviul">
				<?php foreach($navi_array as $key => $row){?>
					<li><a href="<?php echo $row;?>"><span><?php echo $key;?></span></a></li>
				<?php }?>
			</ul>
		</div>
		<div id="submenu">
			<big>按字母检索：</big>
			<p>
			<?php 
				for($i=65;$i<91;$i++){  
					echo '<a href="search.php?letter='.$i.'" target="_blank">'.chr($i).'</a>'; //输出字母检索栏
				}
			?>
			</p>

		</div>	
	</div>
	<div class="cl"></div>
</div>

<!-- block header end -->