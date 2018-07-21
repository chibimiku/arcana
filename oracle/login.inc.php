<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

//检查是否已处于登录状态
if($uid){
	echo "<script type=\"text/javascript\">setTimeout(\"window.location.href='index.php'\",3000);</script>";
}else{
	if(isset($_POST['p_login']) && isset($_POST['u'])){
		//检查登录
		$userinfo = DB::queryFirstRow('SELECT * FROM '.table('manager')." m_username=%s", $_POST['u']);
		if($userinfo){
			$check_result = password_verify($_POST['p'], $userinfo['m_new_pwd']);
			var_dump($check_result);
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