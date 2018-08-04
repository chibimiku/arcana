<?php 
include ('common_header.php');

//用户留言板
//兼顾提交评论、修改评价的作用

?>

<?php 
if(isset($_GET['action'])){
	switch($_GET['action']){
		case 'comment_post':
			//检查输入
			$need_vars = array('videoid', 'content', 'reply');
			$get_vars = read_user_info($need_vars, 0); //0是POST
			$get_vars = set_intval($get_vars, array('videoid', 'reply'));
			if(!$get_vars['videoid']){
				showmessage_json('添加失败，没有videoID。');
			}
			DB::insert(table('review'), array(
				'm_author' => '',
				'm_type' => 1,
				'm_videoid' => $get_vars['videoid'],
				'm_content' => htmlspecialchars($get_vars['content']),
				'm_ip' => $_SERVER['REMOTE_ADDR'],
				'm_reply' => $get_vars['reply'],
				'm_pic' => '',
			));
			showmessage_json('评论添加成功！刷新可见。');
			break;
		case 'levaeword_post':
			//检查历史输入
			$mip = $_SERVER['REMOTE_ADDR'];
			$history_entry = DB::queryFirstRow('SELECT * FROM '.table('leaveword').' WHERE m_ip='.$mip);
			if($history_entry){
				if($history_entry['m_content'] == htmlspecialchars($_POST['content'])){
					showmessage('请您不要重复提交内容，谢谢。');
				}
			}
			DB::insert(table('leaveword'), array(
				'm_author' => htmlspecialchars($_POST['author']),
				'm_content' => htmlspecialchars($_POST['content']),
				'm_ip' => $mip,
			));
			showmessage('添加成功！');
			break;
		default:
			showmessage_json('没有正确的 action .', -1);
	}
}else{	 //没有action就给出默认模板
?>
<div class="page_content">
	<h3>游客留言板</h3>
	<hr />
	<form class="layui-form" action="guest.php?action=levaeword_post" method="post">
	<div class="layui-form-item">
		<label class="layui-form-label">昵称</label>
		<div class="layui-input-block">
			<input type="text" name="nickname" required placeholder="旗鼓相当的网友…" autocomplete="off" class="layui-input" value="" />
		</div>
	</div>
	<div class="layui-form-item layui-form-text">
		<label class="layui-form-label">内容<span style="color:#FF0000">(*)</span></label>
		<div class="layui-input-block">
			<textarea class="layui-textarea" required name="guest_word" placeholder="说点儿什么吧..."></textarea>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-input-block">
			<input class="layui-btn" lay-submit type="submit" value="提交"></button>
		</div>
	</div>
	</form>
</div>

<div class="page_content">
	<div id="leavewordlist">
	<?php
		$tpp = 30;
		$page = 1; //处理页数
		if(isset($_GET['page'])){
			$page = max(1, intval($_GET['page']));
		}
		$le = get_leaveword($page, $tpp);
		$total_count = DB::queryFirstField('SELECT count(*) FROM '.table('leaveword'));
		$total_count = intval($total_count);
		foreach($le as $row){
	?>
		<ul>
			<li class="topwords">
				<span><strong><?php echo $row['m_author'];?></strong> 发表于 <?php echo $row['m_addtime'];?></span>
				# <?php echo $row['m_id'];?>
			</li>
			<li class="words"><?php echo htmlspecialchars($row['m_content']);?></li>
			<?php if(isset($row['re'])){?>
			<li class="rewords">
				<em>回复内容</em>
				<?php echo htmlspecialchars($row['re']);?>
			</li>
			<?php }?>
		</ul>
		<?php }?>
	</div>
	<?php echo multi($total_count, $tpp, $page, 'guest.php');?>
</div>

<?php
	}
?>

<?php 
include ('common_footer.php');
?>