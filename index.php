<?php 

//simple player site on route.

include ('common_header.php');
?>

<div class="page_content">
	<?php include 'block/block_week.inc.php'; //每周新番的block显示，remastered. ?>
	
	<!-- ad area. 好汉饶命系列 -->
	<div class="adbox">
		<div class="page_content">
			<div class="a960" style="padding:5px 0;">
				<center>
					<script type="text/javascript" language="javascript" src="/js/ads/top2_96090.js"></script>
				</center>
			</div>
		</div>
		<div class="page_content">
			<div class="a960" style="padding:5px 0;">
				<center>
					<script type="text/javascript" language="javascript" src="/js/ads/top96090.js"></script>
				</center>
			</div>
		</div>
	</div>
	<!-- ad area end -->
	
	<!-- 新宝岛框架 -->
	<div class="page_content">
		
		<!-- TAB型数据显示块儿的原型畸体 -->
		<div id="lastest_update">
			<?php 
				$show_data = array(
					array(
						'type_id' => 1,
						'type_name' => 'type1',
						'last_update_title' => '123',
						'last_update_time' => '456',
						'link' => '/test/',
					)
				);
			?>
			<h3>最新连载的</h3>
			<div class="tabs">
				<ul>
					<li id="tabmenu_10">type1</li>
					<li id="tabmenu_12">type2</li>
				</ul>
			</div>
			<ul class="details fix" id="tabcontent_10" style="display:block;">
				<li><span class="date">07-11</span><a href="/Angolmoisyuankouhezhanji/" target="_blank">Angolmois元..</a><span>~</span><span class="setnum">第1集</span></li>
			</ul>
		</div>
		<!-- TAB型数据显示块儿的原型畸体 end -->
		
		<!-- history -->
				<div class="tabs2 fix">
			<ul>
				<li id="one1" onclick="setTab('one',1,2)"  class="hover"><a class="one1">大家关注的动漫&#8660;S-DM 新番动漫在线 BD无修动漫在线 ,最新美剧在线</a></li>
				<li id="one2" onclick="setTab('one',2,2)" ><a class="one2">观看历史记录</a></li>
			</ul>
		</div>
		<div class="tabs2cont fix">
			<div id="con_one_1" class="hover fix">
				<ul style="padding:5px 0;">
					<li>sth ddddddd</li>
				</ul>
			</div>
			<div id="con_one_2" class="h-lsjl fix"><a href="javascript:void(0)" onclick="$MH.showHistory(1);">我的观看历史</a></div>
		</div>
		
		<!-- 横向的方格类型展示，图片block -->
		<?php $pic_block_cata_ids = array(35,58,69);?>
		<?php foreach($pic_block_cata_ids as $cata_id){
				$p_data = get_data_by_cata_id($cata_id, 16, 0);
		?>
				<!-- cata block start -->
				<div class="page_content">
					<div class="box_960">
						<h3 class="title-bar"><?php echo get_cata_name_by_id($cata_id);?></h3>
						<div class="home-plist">
							<ul class="fix">
								<?php foreach($p_data as $p_row){ ?>
								<li><a href="detail.php?id=<?php echo $p_row['m_id']?>"><p><img lazy-load="<?php echo $p_row['m_pic']?>" src="<?php echo $p_row['m_pic'];?>" /></p><p><span><?php echo htmlspecialchars($p_row['m_name'])?></span></a></li>
								<?php }?>
							</ul>
						</div>
					</div>
				</div>
				<!-- cata block end -->
			<?php }?>
		<!-- 横向的方格类型展示，图片block END -->
		<!-- xx排行榜 -->
		<?php $xx_rank_cata = array(69 => '无修', 51 => '后宫', 54 => '热血', 53 => '恋爱', 56 => '奇幻', 61 => '冒险');?>
		<div class="page_content">
			<h3 class="titbar">
				<?php foreach($xx_rank_cata as $xx_key => $xx_row){?>
					<em class="bottomrank"><?php echo $xx_row;?>排行榜</em>
				<?php }?>
			</h3>
			<div class="home-top-new">
				<?php foreach($xx_rank_cata as $xx_key => $xx_row){?>
				
				<?php }?>
			</div>
		</div>
		
		<!-- xx排行榜 ends-->
	</div>
	<!-- 新宝岛框架 ends -->
	
</div>

<?php 
include ('common_footer.php');
?>