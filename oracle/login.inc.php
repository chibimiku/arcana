<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

//检查是否已处于登录状态
if($uid){
	showmessage('你已经处于登录状态。');
}else{
	if(isset($_POST['p_login']) && isset($_POST['u'])){
		//检查登录
		$userinfo = DB::queryFirstRow('SELECT * FROM '.table('manager')." WHERE m_username=%s", $_POST['u']);
		if($userinfo){
			$check_result = password_verify($_POST['p'], $userinfo['m_new_pwd']);
			if($check_result){
				$new_ssid = rand(100000,999999).'_'.rand(100000,999999).md5(rand(100,999));
				//生成ssid并写入cookie和DB
				DB::update(table('manager'), array('m_ssid' => $new_ssid), "m_id=%i", $userinfo['m_id']);
				setcookie('ssid', $new_ssid);
				setcookie('uid', $userinfo['m_id']);
				showmessage('登录成功。');
			}else{
				showmessage('登录失败，用户名或者密码错误。');
			}
		}else{
			showmessage('登录失败，用户名或者密码错误。');
		}
	}
?>

<div id="login">
	<h3>登录还是登陆</h3>
	<div class="blank10"></div>
	<form class="layui-form" action="<?php echo $_SERVER['PHP_SELF'].'?action=login';?>" method="post">
		<input type="hidden" value="1" name="p_login" />
		<div class="layui-form-item">
			<label class="layui-form-label" for="u">用户名</label>
			<div class="layui-input-inline">
				<input class="layui-input" required type="text" value="" name="u" />
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label" for="p">密码</label>
			<div class="layui-input-inline">
				<input class="layui-input" autocomplete="off" required type="password" value="" name="p" />
			</div>
		</div>
		<div class="layui-form-item">
			<button class="layui-btn right" lay-submit lay-filter="formDemo">登录</button>
		</div>
	</form>
</div>

<?php }?>