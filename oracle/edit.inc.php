<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

$data_id = intval($_GET['data_id']);
$data = DB::queryFirstRow('SELECT * FROM '.table('data')." WHERE m_id=%i", $data_id);

//编辑器介绍
//https://www.jianshu.com/p/23532c7424ce
//这个依靠的弱智jq需要woff2字体，记得加mime配置

$data_cols = get_table_field('data');

//根据需要赋予特殊属性
$data_cols['m_id']['Disabled'] = true;
$data_cols['m_des']['Big'] = true; //big表示用较大的textarea.

$field_dict = array(); //字段的中文名称词典。


?>

<form action="<?php echo $_SERVER['PHP_SELF'].'?action=edit_submit&data_id='.$data_id; ?>" method="post" class="layui-form">
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
								<textarea name="<?php echo $key;?>" id="<?php echo $key;?>" placeholder="" class="layui-textarea"><?php echo htmlspecialchars($data[$key]);?></textarea>
							</div>
						</div>
					<?php
						break;
					default:
						echo '<p title="'.$row['Type'].'">'.$key.':'.$data[$key].'</p>';
					?>
		<?php 	}?>
		<?php }?>
		<button type="submit" class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
	</div>
</form>

<!-- Initialize the editor. -->
<script>
	$(function() { 
		$('#m_des').froalaEditor() 
	}); 
</script>
<form action="index.php?action=upload" method="post">
	<label for="pic">图片</label>
	<input id="new_file" type="file" name="fileToUpload" />
	<button class="layui-btn">上传图片</button><span id="upload_tip"></span>
</form>
<img id="preview_img" src="" />
<script>

var eleFile = document.querySelector('#new_file');
img = document.getElementById("preview_img");
var reader = new FileReader();
// 文件base64化，以便获知图片原始尺寸
reader.onload = function(e) {
	img.src = e.target.result;
};

eleFile.addEventListener('change', function (event) {
file = event.target.files[0];
// 选择的文件是图片
if (file.type.indexOf("image") == 0) {
reader.readAsDataURL(file);    
}
});

// base64地址图片加载完毕后
img.onload = function () {
	var image_type_str = 'image/png';
	if(file == null){
		
	}else{
		image_type_str = file.type;
	}
	console.log(image_type_str);
	// canvas转为blob并上传
	// 图片ajax上传
	var xhr = new XMLHttpRequest();
	// 文件上传成功
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			// xhr.responseText就是返回的数据
			var j_res = JSON.parse(xhr.responseText);
			console.log(j_res);
			var remote_url = j_res.dir;
			$("#image_url").val(remote_url);
			if(j_res.upload_done == 1){
				$("#upload_tip").html('上传成功…');
			}else{
				$("#upload_tip").html('上传失败…' + j_res.msg);
			}
		}else{
			$("#upload_tip").html('上传中');
		}
	};
	// 开始上传
	$("#upload_tip").html('少女祈祷中…');

	var send_url = 'index.php?action=upload';
	var send_content = img.src;
	xhr.open("POST", send_url, true);
	xhr.send(send_content);
};


</script>
