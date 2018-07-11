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
	<div id="navibox" class="box">
		<ul id="naviul">
			<?php foreach($navi_array as $key => $row){?>
				<li><a href="<?php echo $row;?>"><span><?php echo $key;?></span></a></li>
			<?php }?>
		</ul>
	</div>
	<div id="letterbox" class="submenu box">
		<big>按字母检索：</big>
		<p>
		<?php 
			for($i=97;$i<122;$i++){  
				echo '<a href="search.php?letter='.$i.'" target="_blank">'.chr($i).'</a>'; //输出字母检索栏
			}
		?>
		</p>
	</div>	
	<div class="tInfo">欢迎来到天使动漫。如果你觉得本站不错，请推荐给你的朋友~<a href="http://www.tsdm.me">点击访问论坛</a></div>
	<div class="uInfo"><a onClick="var strHref=window.location.href;this.style.behavior='url(#default#homepage)';this.setHomePage('http://www.tsdm.me');" href="#">设为主页</a> | 
	<a href="javascript:void(0)" onClick="addFavorite('http://www.tsdm.me','S-DM 新番动漫在线 BD无修动漫在线 ,最新美剧在线-www.tsdm.me');">收藏本站</a> | 
	<a href="/help.html" target="_blank">网站帮助</a>
	<!-- 简繁转换 -->
	<script type="text/javascript" src="static/jianfan.js"></script>
	<div id="searchbox" class="box">
		<form action="search.php" method="post">
			<input type="hidden" value="" name="u_key" />
			<input type="text" name="query" />
			<input type="submit" name="submit" class="button" value="搜索"/>
		</form>
	</div>
</div>

<!-- block header end -->