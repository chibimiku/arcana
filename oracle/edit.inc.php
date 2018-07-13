<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

$data_id = intval($_GET['data_id']);
$data = DB::queryFirstRow('SELECT * FROM '.table('data')." WHERE m_id=%i", $data_id);

?>



<form action="<?php echo $_SERVER['PHP_SELF'].'?action=edit';?>" method="post">
	<textarea id=""></textarea>
	<input type="submit" value="提交" />
</form>