<?php 
//simple player site on route.
include ('common_header.php');

//读取播放数据
$playid = intval($_GET['id']);
$data = DB::queryFirstRow('SELECT * FROM '.table('data')." WHERE m_id=%i", intval($playid));
if(!$data){
	showmessage('错误：无法找到数据。');
}
//解析数据
$play_show_data = parse_playdata_detail($data['m_playdata'], false);

//解析数据分类
$type_info = DB::queryFirstRow('SELECT * FROM '.table('type').' WHERE m_id=%i', $data['m_type']);

insert_playlist($data);

?>

<div class="page_content">
	<div class="here">
		<span></span>
		<h3>当前位置：<a href="/">首页</a> » <?php echo output_type_a($data['m_type']);?> » <big><?php echo $data['m_name'];?></big></h3>
	</div>
	
	<div class="box960-top"></div>
	<div class="box960-mid-box fix">
		<div class="box960-mid-minfo fix">
			<div class="m-info">
				<div class="mimg"><img src="<?php echo $data['m_pic'];?>" alt="<?php echo $data['m_name'];?>" /></div>
				<div class="mtext">
					<ul>
						<li><h1><?php echo $data['m_name']?></h1> <em><?php echo $data['m_note'];?></em></li>
						<li><span>分类：</span><?php echo output_type_a($data['m_type']);?></li>
						<li><span>更新：</span><?php echo $data['m_datetime']?></li>
						<li><span>分享：</span>
							<input size="43" value="<?php echo htmlspecialchars($data['m_name']);?>在线观看地址: <?php echo $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];?>" name="page_url" id="page_url" />
							<input type="button" onclick="copyToClipboard($('page_url').value)" value="复制地址" class="addresbt" name="Button" />
						</li>
					</ul>
					<div><?php echo draw_ad('detail_middle_02');?></div>
				</div>
				<div class="blank5"></div>
				<div id="content_box_60090">
					<?php echo draw_ad('detail_middle_01');?>
				</div>
				<div class="cl"></div>
			</div>
			<div class="m-ads">
				<div id="content_box_300250">
					<p align="center"><?php echo draw_ad('detail_middle_03');?></p>
				</div>
			</div>
		</div>
		<div class="cl"></div>
	</div>	
	<!-- 播放数据区 start -->
	<div class="box960-mid"></div>
	<div class="box960-mid-box">
		<div class="box960-mid-minfo">
			<!-- 一股邪恶力量 -->
			<div id="content_box_72890_out">
				<div id="content_box_72890">
					<p align="center"><?php echo draw_ad('detail_middle_04');?></p>
				</div>
			</div>
			<!-- 播放器数据展示 start -->
			<?php foreach($play_show_data as $play_row){?>
				<div class="playurl">
					<div class="title"><span class="player_desc <?php echo $play_row['source_name']?>"><?php echo get_player_desc($play_row['source_name']);?></span></div>
					<div class="bfdz">
						<ul>
							<?php $num_playdata = count($play_row['data']); $i_count = 0;?>
							<?php foreach($play_row['data'] as $p_row){?>
								<li><a href="play.php?id=<?php echo $playid;?>&source_id=<?php echo $play_row['source_id'];?>&row_id=<?php echo $p_row['row_id'];?>"><?php echo $p_row['name'];?>
								<?php if(++$i_count === $num_playdata) {echo '<em class="ts_new_em"></em>';}?>
								</a></li>
							<?php }?>
						</ul>
					</div>
				</div>
			<?php }?>
			<!-- 播放器数据展示 end -->
		</div>
	</div>
	<!-- 播放数据区 end -->
	
	<!-- 帮助区 start -->
	<div class="box960-mid"></div>
	<div class="box960-mid-box">
		<div class="box960-mid-minfo">
			<div id="forum_text">
				<a href="http://dm.tsdm.tv/gbook.asp" target="_blank"><font color="#FF0000">【求片、无法播放，点此留言】</font></a>
				<a href="http://www.tsdm.me/forum.php?mod=forumdisplay&amp;fid=4" target="_blank"><font color="#0972C0">【每日签到,送积分兵长徽章】</font></a>
				<a href="http://www.tsdm.me/forum.php?mod=viewthread&amp;tid=537303" target="_blank"><font color="#4BA7CC">【天使官方QQ群，来聊天吧】 </font></a>
				<a href="http://www.tsdm.me/forum.php?gid=21" target="_blank"><font color="#FF0000">【1月新番版主大量招募中】 </font></a>
			</div>
			<div id="forum_btn">
				<ul>
					<li><a target="_blank" href="http://www.tsdm.me/forum.php?mod=forumdisplay&amp;fid=8">下载</a></li>
					<li><a target="_blank" href="http://www.tsdm.me/forum.php?mod=forumdisplay&amp;fid=247">主题歌</a></li>
					<li><a target="_blank" href="http://www.tsdm.me/forum.php?mod=viewthread&amp;tid=852739">捐助</a></li>
				</ul>
			</div>
			<div id="forum_active">
				<a href="http://www.tsdm.me/forum.php?mod=viewthread&amp;tid=864229" style="color:#FF0000;margin-left:250px;margin-top:12px;" target="_blank">【何处是江湖】古风期刊第四期——《九州》正式发布</a>
				<a href="http://www.tsdm.me/forum.php?mod=forumdisplay&amp;fid=8" target="_blank"><font color="#0972C0">☆★2018年1月新番动漫连载百度云网盘临时下载点★☆</font></a>
			</div>
		</div>
	</div>
	<!-- 帮助区 end -->
	
	<!-- 下载区 start -->
	
	
	<!-- 下载区 end -->
	<div class="box960-mid"></div>
	
	<!-- 介绍块 start -->
	<div class="box960-mid-box">
		<div class="box960-mid-minfo">
			<div class="m-intro">
				<div class="ctext fix">
					<?php echo $data['m_des'];?>
				</div>
				<div class="cl"></div>
			</div>
		</div>
	</div>
	<!-- 介绍块 end -->
	
	<div class="box960-mid"></div>
	<div class="box960-mid-box">
		<div class="box960-mid-minfo">
		<?php include 'block/block_comment.inc.php'; ?>
		</div>
	</div>
</div>

<?php 
include ('common_footer.php');
?>