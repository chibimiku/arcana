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
	$s_pos = strpos($row['Type'], '(');
	if($s_pos !== false){
		$row['Type'] = substr($row['Type'] ,0, $s_pos);
	}
	$data_cols[$row['Field']] = $row;
}

//根据需要赋予特殊属性
$data_cols['m_id']['Disabled'] = true;
$data_cols['m_des']['Big'] = true; //big表示用较大的textarea.

?>

<form action="<?php echo $_SERVER['PHP_SELF'].'?action=edit';?>" method="post" class="layui-form">
	<div class="layui-form-item">
	<input name="steel" type="hidden" value="" />
		<?php foreach($data_cols as $key => $row){
				switch($row['Type']){
					case 'int':
					case 'tinyint':
					case 'varchar':
					case 'datetime':
		?>
						<label class="layui-form-label" for="<?php echo $key;?>"><?php echo $key?></label>
						<div class="layui-input-block">
							<input <?php if($key=='m_id'){echo 'disabled="disabled"';}?>class="layui-input" name="<?php echo $key;?>" value="<?php echo htmlspecialchars($data[$key]);?>" size="40" />
						</div>
					<?php
						break;
					case 'longtext':
					?>
						<div class="layui-form-item layui-form-text">
							<label class="layui-form-label"><?php echo $key;?></label>
							<div class="layui-input-block">
								<textarea name="<?php echo $key;?>" placeholder="" class="layui-textarea"><?php echo htmlspecialchars($data[$key]);?></textarea>
							</div>
						</div>
					<?php
						break;
					default:
						echo '<p title="'.$row['Type'].'">'.$key.':'.$data[$key].'</p>';
					?>
		<?php 	}?>
		<?php }?>
		<button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
		<button type="reset" class="layui-btn layui-btn-primary">重置</button>
	</div>
</form>
<script>
//Demo
layui.use('form', function(){
  var form = layui.form;
  
  //监听提交
  form.on('submit(formDemo)', function(data){
    layer.msg(JSON.stringify(data.field));
    return false;
  });
});
</script>
<!-- Initialize the editor. -->
<script>
	$(function() { 
		$('#desc').froalaEditor() 
	}); 
</script>
<form action="./upload.php" method="post">
	<label for="pic">图片</label>
	<input type="file" name="pic" />
	<button class="layui-btn">上传图片</button>
</form>