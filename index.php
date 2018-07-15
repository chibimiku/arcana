<?php 

//simple player site on route.

include ('common_header.php');
?>

<div class="page_content">
	
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
		
		<!-- 第一行 TAB展示 5格切换 大家观看/观看记录-->
		<div class="page_content">
			<div class="box720 fl">
				<div id="index_ppt_box" style="float:left; width:230px; height:328px;">
					此处应有图片轮播
				</div>
				<div class="update">
					<div class="serial">
						<div class="tabs">
						<?php $update_data_cata = array(35, 51, 52, 53, 54);?>
						<?php $line_count = 0;?>
							<h3>最新连载的</h3>
							<ul>
								<?php foreach($update_data_cata as $c_id){?>
									<li id="tabmenu_1<?php echo $line_count;?>" onmouseover="setTimeout('Show_TabMenu(1,<?php echo $line_count;?>)',100);"><?php echo $config['type'][$c_id]['m_name']?></li>
								<?php ++$line_count;?>
								<?php }?>
							</ul>
						</div>
						<?php $line_count = 0;?>
						<?php foreach($update_data_cata as $c_id){?>
						<ul class="details fix" id="tabcontent_1<?php echo $line_count;?>">
							<?php $d_data = get_data_by_cata_id($c_id,22,2);?>
							<?php foreach($d_data as $d_row){?>
								<li>
									<span class="date"><?php echo $d_row['m_datetime'];?></span>
									<a href="detail.php?id=<?php echo $d_row['m_id'];?>"><?php echo $d_row['m_name'];?></a>
									<span>~</span>
									<span class="setnum"> #最近更新 </a>
								</li>
							<?php }?>
							<?php ++$line_count;?>
						</ul>
						<?php }?>
					</div>
				</div>
			</div>
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
					<dl class="">
						<?php $q_info = get_data_by_cata_id($xx_key, 10, 0);$line_count = 0;?>
						<?php foreach($q_info as $row){?>
						<?php ++$line_count;?>
							<dd>
								<em><?php echo $line_count;?>.</em>
								<a href="detail.php?id=<?php echo $row['m_id']?>" target="_blank"><?php echo htmlspecialchars($row['m_name'])?></a>
							</dd>
						<?php }?>
					</dl>
				<?php }?>
			</div>
		</div>
		
		<!-- xx排行榜 ends-->
	</div>
	<!-- 新宝岛框架 ends -->
	
</div>

<div class="flink .box_960">
	<h3 class="titbar"><span>&nbsp;</span><em>欢迎PR3以上、收录正常的的网站友链</em></h3>
	<ul class="fix">
		<li><a href="http://www.tsdm.me" target="_blank">天使动漫论坛</a></li>
		<li><a href="tsdmtsdm@126.com" target="_blank">友链申请</a></li>
		<li><a href="http://dm.tsdm.net" target="_blank">在线动漫</a></li>
		<li><a href="http://dm.tsdm.net/list/?35.html" target="_blank">新番动漫</a></li>
		<li><a href="http://mh.tsdm.net/" target="_blank">天使漫画网</a></li>
		<li><a href="http://www.loli.cd/" target="_blank">最萌音</a></li>
		<li><a href="http://ysm.yesmiao.me" target="_blank">影视喵</a></li>
	</ul>
</div>

<?php 
include ('common_footer.php');
?>