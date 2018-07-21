<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

$op = '';
if(isset($_GET['op'])){
	$op = $_GET['op'];
}

switch($op){
	case 'edit':
		$edit_uid = intval($_GET['uid']);
		$member_data = DB::queryFirstRow('SELECT * FROM '.table('manager')." WHERE m_id=%i", $edit_uid);
		?>
		
		<form class="layui-form" action="index.php?action=member&op=edit_submit" method="post">
			<h3>正在修改 <?php echo $member_data['m_username'];?> 的数据</h3>
			<div class="blank10"></div>
			<input type="hidden" name="modify_uid" value="<?php echo $edit_uid;?>" />
			<div class="layui-form-item">
				<label class="layui-form-label">密码框</label>
				<div class="layui-input-inline">
				<input type="password" name="password" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
				</div>
				<div class="layui-form-mid layui-word-aux"></div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">确认密码</label>
				<div class="layui-input-inline">
				<input type="password" name="password_confirm" required lay-verify="required" placeholder="请再次输入密码" autocomplete="off" class="layui-input">
				</div>
				<div class="layui-form-mid layui-word-aux"></div>
			</div>
			<div class="layui-form-item">
				<button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
			</div>
		</form>
		
		<?php
		break;
	case 'edit_submit':
		$new_pwd = $_POST['password'];
		if(!$new_pwd){
			showmessage('错误：提交的密码为空。');
		}
		$modify_uid = intval($_POST['modify_uid']);
		if(!$modify_uid){
			showmessage('错误：提交的UID为空。');
		}
		$new_pwd_hash = password_hash($new_pwd, PASSWORD_DEFAULT); //php5.5以后才有这个方法，没有的自己build个或者升级。
		DB::update(table('manager'), array('m_new_pwd' => $new_pwd_hash), "m_id=%i", $modify_uid);
		showmessage('修改成功', 'index.php');
		break;
	default:
		$member_data = DB::query('SELECT m_id, m_username, m_logintime, m_loginip FROM '.table('manager'));
		$table_head = array('ID', '用户名', '登录时间', '登录IP', '');
		foreach($member_data as &$row){
			$row['link'] = '<a href="index.php?action=member&op=edit&uid='.$row['m_id'].'">编辑</a>';
		}
		echo draw_table($table_head, $member_data, 'layui-table');
		?>

		

<?php }?>