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
		
		<form class="layui-form" action="index.php?action=member&op=edit" method="post">
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
	default:
		$member_data = DB::query('SELECT * FROM '.table('manager'));

		?>

		<table class="layui-table">
			<colgroup>
				<col width="150">
				<col width="200">
				<col>
			</colgroup>
			<thead><tr><td>ID</td><td>用户名</td><td>登录时间</td><td>登录IP</td><td></td></tr></thead>
			<tbody>
				<?php foreach($member_data as $row){?>
					<tr>
					<td><?php echo $row['m_id']?></td>
					<td><?php echo $row['m_username']?></td>
					<td><?php echo $row['m_logintime']?></td>
					<td><?php echo $row['m_loginip']?></td>
					<td><a href="index.php?action=member&op=edit&uid=<?php echo $row['m_id']?>">编辑</a></td>
					</tr>
				<?php }?>
			</tbody>
		</table>

<?php }?>