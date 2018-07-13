<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

?>

<div id="login">
	<form action="<?php echo $_SERVER['PHP_SELF'].'?action=login';?>" method="post">
		<label for="u">username</label><input type="text" value="" name="u" />
		<label for="p">password</label><input type="text" value="" name="p" />
		<input type="submit" value="Submit"/>
	</form>
</div>