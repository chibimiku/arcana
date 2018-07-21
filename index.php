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
		
		<!-- 第一行 TAB展示 5格切换 大家观看/观看记录 start -->
		<div class="page_content">
			<div class="box720 fl">
				<div id="index_ppt_box" style="float:left; width:230px; height:328px;">
					<div class="flash">
						<div id="focus">
							<div id="au">
								<?php $pic_data = DB::query('SELECT * FROM '.table('index_picblock')." WHERE 1=1");?>
								<?php $line_count = 0?>
								<?php foreach($pic_data as $row){?>
									<div <?php if($line_count == 0){echo 'style="display:block"';}?>>
										<a href="<?php echo htmlspecialchars($row['m_link']);?>" target="_blank">
											<img src="<?php echo $row['m_pic'];?>" alt="<?php echo htmlspecialchars($row['m_name']);?>" height="290" width="220" />
										</a>
									</div>
								<?php ++$line_count;?>
								<?php }?>
							</div>
						</div>
					</div>
				</div>
				<div class="update">
					<div class="serial">
						<div class="tabs">
						<?php $update_data_cata = array(35, 51, 52, 53, 54);?>
						<?php $line_count = 0;?>
							<h3>最新连载的</h3>
							<ul>
								<?php foreach($update_data_cata as $c_id){?>
									<li id="tabmenu_1<?php echo $line_count;?>" onmouseover="setTimeout('Show_TabMenu(1,<?php echo $line_count;?>)',100);">
										<?php echo $config['type'][$c_id]['m_name']?>
									</li>
								<?php ++$line_count;?>
								<?php }?>
							</ul>
						</div>
						<?php $line_count = 0;?>
						<?php foreach($update_data_cata as $c_id){?>
						<ul class="details fix" id="tabcontent_1<?php echo $line_count;?>" <?php if($line_count == 0){echo 'style="display:block;"';}?>>
							<?php $d_data = get_data_by_cata_id($c_id,22,2);?>
							<?php foreach($d_data as $d_row){?>
								<li>
									<span class="date"><?php echo $d_row['m_datetime'];?></span>
									<a href="detail.php?id=<?php echo $d_row['m_id'];?>"><?php echo $d_row['m_name'];?></a>
									<span>~</span>
									<span class="setnum"><?php echo $d_row['m_note'];?></a>
								</li>
							<?php }?>
							<?php ++$line_count;?>
						</ul>
						<?php }?>
					</div>
				</div>
			</div>
			
			<!-- 大家关注的/观看记录 -->
			<div class="box230 fr">
				<div class="tabs2 fix">
					<ul>
						<li id="one1" onclick="setTab('one',1,2)" class="hover"><a class="one1">大家关注的动漫⇔S-DM 新番动漫在线 BD无修动漫在线 ,最新美剧在线</a></li>
						<li id="one2" onclick="setTab('one',2,2)" class=""><a class="one2">观看历史记录</a></li>
					</ul>
				</div>
				<div class="tabs2cont fix">
					<div id="con_one_1" class="hover fix">
						<?php $mx_data = get_data_by_cata_id(0, 13, 2);?>
						<ul>
							<?php foreach($mx_data as $row){?>
								<li><?php echo output_row_a($row);?></li>
							<?php }?>
						</ul>
					</div>
					<div id="con_one_2" class="h-lsjl fix" style="display: none;"><a href="javascript:void(0)" onclick="$MH.showHistory(1);">我的观看历史</a></div>
				</div>
			</div>
		</div>
		<!-- 第一行 TAB展示 5格切换 大家观看/观看记录 end -->
		
		<!-- 第二行 动漫 / 热播榜 start -->
		<div class="page_content">
			<div class="box720 fl">
				<h3 class="titbar"><em class="dhp"><a href="#" target="_blank">天使动漫 - 最近更新</a></em></h3>
				<div class="home-plist fix">
					<?php $ht_data = get_data_by_cata_id(0, 6, 2);?>
					<ul class="fix">
						<?php foreach($ht_data as $row){?>
							<li><a href="detail.php?id=<?php echo $row['m_id'];?>" target="_blank">
								<img src="<?php echo htmlspecialchars($row['m_pic']);?>" title="<?php echo htmlspecialchars($row['m_name']);?>"/>
								<span><?php echo htmlspecialchars($row['m_name']);?></span>
								<em>#最近更新</em>
							</li></a>
						<?php }?>
					</ul>
					<div class="line"></div>
					<dl class="fix">
						<?php $lt_data = get_data_by_cata_id(0, 18, 2);?>
						<?php foreach($lt_data as $row){?>
							<dd>
								<small><?php echo($row['k_order'] + 1);?></small>
								<a href="detail.php?id=<?php echo $row['m_id'];?>"><?php echo htmlspecialchars($row['m_name']);?><em><?php echo $row['m_note']?></em></a>
							</dd>
						<?php }?>
					</dl>
				</div>
			</div>
			<div class="box230 fr">
				<h3 class="titbar"></h3>
				<?php $top_data = get_data_by_cata_id(0,13,0);?>
				<?php $line_count = 0;?>
				<div class="top_other">
					<ul>
						<?php foreach($top_data as $row){?>
						<?php 	++$line_count;?>
							<li>
								<span class="n<?php echo $line_count;?>"></span>
								<a target="_blank" href="detail.php?id=<?php echo $row['m_id'];?>"><?php echo $row['m_name'];?></a>
							</li>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>
		<!-- 第二行 动漫 / 热播榜 end -->
		
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

<script language="javascript" type="text/javascript">
		function Show_TabMenu(tabid_num,tabnum){
			for(var i=0;i<5;i++){document.getElementById("tabcontent_"+tabid_num+i).style.display="none";}
			for(var i=0;i<5;i++){document.getElementById("tabmenu_"+tabid_num+i).className="";}
			document.getElementById("tabmenu_"+tabid_num+tabnum).className="TabsOn";
			document.getElementById("tabcontent_"+tabid_num+tabnum).style.display="block";
		}
		</script>

<div class="page_content">
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
</div>

<?php 
include ('common_footer.php');
?>