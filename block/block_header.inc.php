<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

//公用头部导航
//导航栏内容
$navi_array = array(
	array('m_name' => '首 页', 'm_link' => '/'),
);

$nav_tmp = DB::query('SELECT * FROM '.table('nav')," ORDER BY m_displayorder DESC");
foreach($nav_tmp as $row){
	$navi_array[] = $row;
}

?>

<!-- block header start -->

<div id="header">
	<!-- 顶部第一个广告 start-->
	<div class="box_960 box">
		<?php echo draw_ad('common_header_top_01');?>
	</div>
	<!-- 顶部第一个广告 end-->
	<div id="head">
		<div id="head_box" class="box">
			<div class="tInfo head_info">欢迎来到天使动漫。如果你觉得本站不错，请推荐给你的朋友~<a href="http://www.tsdm.me">点击访问论坛</a></div>
			<div>
				<div class="uInfo head_block head_info">
					<a onclick="var strHref=window.location.href;this.style.behavior='url(#default#homepage)';this.setHomePage('http://www.tsdm.me');" href="#">设为主页</a> | 
					<a href="javascript:void(0)" onclick="addFavorite('http://www.tsdm.me','S-DM 新番动漫在线 BD无修动漫在线 ,最新美剧在线-www.tsdm.me');">收藏本站</a> | 
					<a href="help.php" target="_blank">网站帮助</a> |
					<a href="javascript:void(0)" id="StranLink" name="StranLink">繁體中文</a>
					<script type="text/javascript" src="static/js/jianfan.js"></script>
				</div>
				<div class="search"> 
					<form name="formsearch" id="formsearch" action="search.php" method="get" target="_blank">     
						<ul class="search-form">
						<li><input type="text" name="query" id="query" class="text" value="在此输入动漫名称" onfocus="if(this.value=='在此输入动漫名称'){this.value='';}" onblur="if(this.value==''){this.value='在此输入动漫名称';}">
						</li>         
						<li><input type="submit" class="button" value="搜索"></li>
						</ul>
					</form>
				</div>
				<div class="cl"></div>
			</div>
		</div>
		<div id="navigation" class="box">
			<ul id="naviul">
				<?php foreach($navi_array as $key => $row){?>
					<li><a href="<?php echo $row['m_link'];?>"><span><?php echo $row['m_name'];?></span></a></li>
				<?php }?>
			</ul>
		</div>
		<div id="submenu">
			<big>按字母检索：</big>
			<p>
			<?php 
				for($i=65;$i<91;$i++){  
					echo '<a href="search.php?letter='.chr($i).'" target="_blank">'.chr($i).'</a>'; //输出字母检索栏
				}
			?>
			</p>
		</div>	
	</div>
	
	<div class="cl"></div>

</div>

<!-- block header end -->