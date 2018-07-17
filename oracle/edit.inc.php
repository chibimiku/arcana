<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

$data_id = intval($_GET['data_id']);
$data = DB::queryFirstRow('SELECT * FROM '.table('data')." WHERE m_id=%i", $data_id);

//编辑器介绍
//https://www.jianshu.com/p/23532c7424ce

$data_cols_name_rs = DB::query('SHOW columns FROM '.table('data'));
$data_cols = array();
foreach($data_cols_name_rs as $row){
	if($s_pos = strpos($row['Type'], '(') !== false){
		$row['Type'] = substr(0, $s_pos);
	}
	$data_cols[$row['Field']] = $row;
}

//根据需要赋予特殊属性
$data_cols['m_id']['Disabled'] = true;
$data_cols['m_des']['Big'] = true; //big表示用较大的textarea.

?>

<form action="<?php echo $_SERVER['PHP_SELF'].'?action=edit';?>" method="post">
	<?php foreach($data_cols as $key => $row){
			switch($row['Type']):
				case 'int':
				case 'tinyint'
				case 'varchar':
				case 'datetime':
				?>
					<label for="<?php echo $key;?>"><?php echo $key?></label><input name="<?php echo $key;?>" value="<?php echo htmlspecialchars($data[$key]);?>" size="40" />
				<?php
					break;
				default:
					echo '<p>'.$key.':'.$data[$key].'</p>';
	?>
	<?php }?>
	<input type="submit" value="提交" />
	
</form>
<!-- Initialize the editor. -->
<script>
	$(function() { 
		$('#desc').froalaEditor() 
	}); 
</script>