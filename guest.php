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
			DB::insert(table('review'), array(
				'm_author' => '',
				'm_type' => 1,
				'm_videoid' => $get_vars['videoid'],
				'm_content' => htmlspecialchars($get_vars['content']),
				'm_ip' => $_SERVER['REMOTE_ADDR'],
				'm_reply' => $get_vars['reply'],
				'm_pic' => '',
			));
			showmessage_json('添加成功！');
			break;
		case 'levaeword_post':
			DB::insert(table('leaveword'), array(
				'm_author' => htmlspecialchars($_POST['author']),
				'm_content' => htmlspecialchars($_POST['content']),
				'm_ip' => $_SERVER['REMOTE_ADDR'],
			));
			showmessage_json('添加成功！');
			break;
		default:
			showmessage_json('没有正确的 action .', -1);
	}
}else{	 //没有action就给出默认模板
?>
<div>
	<form action="guest.php?action=submit" method="post">
		<textarea placeholder="说点儿什么吧..."></textarea>
	</form>
</div>
<?php
	}
?>

<?php 
include ('common_footer.php');
?>