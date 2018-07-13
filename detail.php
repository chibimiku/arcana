<?php 
//simple player site on route.
include ('common_header.php');
include 'block/block_header.inc.php'; //引入头部

//读取播放数据
$playid = intval($_GET['play_id']);
$data = DB::queryFirstRow('SELECT * FROM '.table('data')." WHERE m_id=%i", intval($playid));
//解析数据
$play_show_data = parse_playdata_detail($data['m_playdata']);

$reviews = DB::query('SELECT * FROM '.table('content')." WHERE m_videoid=%i", $playid);
?>

<div class="page_content">
	<h3><?php echo $data['m_name']?> 介绍</h3>
	<div><?php echo $data['m_des']?></div>
	<!-- play data area.-->
	<?php foreach($play_show_data as $p_row){?>
		<h3><?php echo $p_row['source_name'];?></h3>
		<ul>
			<?php foreach($p_row['data'] as $sp_row){?>
				<li><a href="<?php echo $sp_row['playdata']?>"><?php echo $sp_row['name']?></a></li>
			<?php }?>
		</ul>
	<?php }?>
	
	<!-- comment area -->
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
</div>

<?php 
include ('common_footer.php');
?>