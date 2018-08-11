<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

//播放界面的顶部界面

$top_box = array(
	array('name' => '首页', 'link' => '/'),
	array('name' => '新番动漫', 'link' => 'search.php?type=35'),
	array('name' => '动漫下载', 'link' => 'https://www.tsdm.me/forum.php?mod=forumdisplay&fid=8'),
	array('name' => '9000部漫画在线观看', 'link' => 'https://mh.tsdm.tvt/'),
	array('name' => '动漫音乐', 'link' => 'http://www.tsdm.tv/forum.php?mod=forumdisplay&amp;fid=247'),
	array('name' => '<font color="#FF0000">下载格子</font>', 'link' => '/index/'),
	array('name' => '访问论坛', 'link' => 'https://www.tsdm.me/'),
);

?>

<div id="player_head">
	<div id="headbox">
		<div class="logo"><a href="/" title="<?php echo htmlspecialchars($config['site_desc']);?>"><?php echo $config['site_desc'];?></a></div>
		<div id="site-nav">
			<ul class="quick-menu">
				<?php foreach($top_box as $row){?>
					<li><a href="<?php echo $row['link'];?>" target="_blank"><?php echo $row['name'];?></a></li>
				<?php }?>
			</ul>
		</div>
		<div class="search">
			<form name="formsearch" id="formsearch" action="search.php" method="get" target="_blank">
			<div class="search-form">
				<ul class="search-form">
				<li><input type="text" name="query" id="query" class="text" value="在此输入动漫名称" onfocus="if(this.value=='在此输入动漫名称'){this.value='';}" onblur="if(this.value==''){this.value='在此输入动漫名称';}">
				</li>         
				<li><input type="submit" class="button" value="搜索"></li>
				</ul>
			</div>
			</form>
		</div>
		<div class="cl"></div>
	</div>
</div>