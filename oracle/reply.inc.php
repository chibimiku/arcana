<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

if(isset($_POST['e_content'])){
	DB::insert(table('leaveword'), array(
		'm_replyid' => intval($_POST['reply_id']),
		'm_author' => 'Snake',
		'm_qq' => '',
		'm_mail' => '',
		'm_content' => $_POST['e_content'],
		'm_ip' => $_SERVER['REMOTE_ADDR']
	));
	showmessage('添加回复完成。', 'index.php?action=links&type=leaveword');
}else{

//添加回复
$r_id = intval($_GET['id']);
$info = DB::queryFirstRow('SELECT * FROM '.table('leaveword')." WHERE m_id=%i", $r_id);

?>

<form class="layui-form" action="index.php?action=reply" method="post">
	<input type="hidden" name="reply_id" value="<?php echo $r_id;?>" />
	<h3>原文（<?php echo $r_id?>）：</h3>
	<div><?php echo htmlspecialchars($info['m_content'])?></div>
	<hr />
	<h3>回复</h3>
	<div class="layui-form-item">
		<textarea name="e_content" class="layui-textarea"></textarea>
	</div>
	<div class="layui-form-item">
		<button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
	</div>
</form>

<?php }?>