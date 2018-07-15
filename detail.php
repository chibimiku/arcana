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
$reviews = DB::query('SELECT * FROM '.table('review')." WHERE m_videoid=%i", $playid);

//解析数据分类
$type_info = DB::queryFirstRow('SELECT * FROM '.table('type').' WHERE m_id=%i', $data['m_type']);

?>

<style>
.here {
    color: #333;
    height: 30px;
    line-height: 30px;
    overflow: hidden;
    background: #EFF8FE url(bg_repeat.png) no-repeat 0 0;
}

.here span {
    display: block;
    float: right;
    padding: 8px 10px 0 0;
    background: url(bg_repeat.png) no-repeat right -30px;
}

.here h3 {
    padding-left: 20px;
    background: url(icon.png) no-repeat 10px 12px;
}

.here h3 a {
    text-decoration: underline;
}

.here big {
    color: #008000;
    font-size: 12px;
}

.box960-top {
    background-position: 0 -400px;
}

.box960-mid-box {
    width: 958px;
    border-left: 1px solid #97C3E5;
    border-right: 1px solid #97C3E5;
    overflow: hidden;
    background: #EFF8FE;
}

.box960-top, .box960-bot, .box960-mid {
    height: 10px;
    line-height: 0;
    font-size: 0;
    background: url(sprite.png) no-repeat 0 0;
}

.box960-mid {
    height: 12px;
    background-position: 0 -450px;
}

.box960-mid-minfo {
    width: 948px;
    margin: 0 auto;
    overflow: hidden;
    border-left: 1px solid #CFE3F4;
    border-right: 1px solid #CFE3F4;
    background: #fff;
}

.cl {
    clear: both;
}

.m-info {
    padding: 7px 0 0 10px;
    width: 600px;
    float: left;
}

.m-ads {
    width: 300px;
    float: right;
    padding: 10px 10px 0 0;
}



</style>

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
						<li><h1><?php echo $data['m_name']?></h1> <em>最新10集连载中 只是+一个播放</em></li>
						<li><span>分类：</span><?php echo output_type_a($data['m_type']);?></li>
						<li><span>更新：</span><?php echo $data['m_datetime']?></li>
						<li><span>分享：</span>
							<input size="43" value="<?php echo htmlspecialchars($data['m_name']);?>在线观看地址: <?php echo $_SERVER['REQUEST_URI'];?>" name="page_url" id="page_url" />
							<input type="button" onclick="copyToClipboard($('page_url').value)" value="复制地址" class="addresbt" name="Button" />
						</li>
					</ul>
					<div><script type="text/javascript" language="javascript" src="/js/ads/content_450-70.js"></script></div>
				</div>
			</div>
			<div class="m-ads">
				<div id="content_box_300250"><p align="center"><script type="text/javascript" language="javascript" src="/js/ads/300250.js"></script></p></div>
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
					<p align="center"><script type="text/javascript" language="javascript" src="/js/ads/index72890.js"></script></p>
				</div>
			</div>
			<!-- 播放器数据展示 start -->
			<?php foreach($play_show_data as $play_row){?>
				<div class="playurl">
					<div class="title"><span class="ckpdirect" name="1"></span><span class="ckpdirect">720P播放海外线路 如无法播放请F5刷新</span></div>
					<div class="bfdz">
						<ul>
							<?php foreach($play_row['data'] as $p_row){?>
								<li><a href="play.php?id=<?php echo $playid;?>&source_id=<?php echo $play_row['source_id'];?>&row_id=<?php echo $p_row['row_id'];?>"><?php echo $p_row['name'];?></a></li>
							<?php }?>
						</ul>
					</div>
				</div>
			<?php }?>
			<!-- 播放器数据展示 end -->
		</div>
	</div>
	<!-- 播放数据区 end -->
	
	<!-- 评论区 start -->
	<h3>comments</h3>
	<div id="comments">
		<?php foreach($reviews as $re_row){ ?>
			<div>
				<p><span class="re_author"><?php echo $re_row['m_author']?></span><span class="re_timestamp"><?php echo $re_row['m_reply']?></span></p>
				<p><?php echo htmlspecialchars($re_row['m_content'])?></p>
				<p><a href="">agree (<?php echo $re_row['m_agree']?>)</a>|<a href="">anti (<?php echo $re_row['m_anti']?>)</a></p>
			</div>
		<?php }?>
	</div>
	<!-- 评论区 end -->
</div>

<?php 
include ('common_footer.php');
?>