<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

$add_data = false;
$data_id = intval($_GET['data_id']);
$data = DB::queryFirstRow('SELECT * FROM '.table('data')." WHERE m_id=%i", $data_id);
if(!$data){
	$data = array();
	$add_data = true; //如果没有原来的data则认为是添加。
}

//编辑器介绍
//https://www.jianshu.com/p/23532c7424ce
//这个依靠的弱智jq需要woff2字体，记得加mime配置

$data_cols = get_table_field('data');

//根据需要赋予特殊属性
$data_cols['m_id']['Disabled'] = true;
$data_cols['m_des']['Big'] = true; //big表示用较大的textarea.

//hidden field 是需要隐藏的输入字段，主要是意义不明
$hidden_field = array('m_state', 'm_digg', 'm_topic', 'm_wrong', 'm_publisharea', 'm_isunion', 'm_tread', 'm_director', 'm_lang', 'm_recycle', 'm_score', 'm_week', 'm_show_week');

$field_dict = array(); //字段的中文名称词典。
layui_load_module('form');
?>

<form action="<?php echo $_SERVER['PHP_SELF'].'?action=edit_submit&data_id='.$data_id; ?>" method="post" class="layui-form">
	<div class="layui-form-item">
	<input name="steel" type="hidden" value="" />
	</div>
	<?php 
		foreach($data_cols as $key => $row){
	?>
		<div class="layui-form-item" <?php if(in_array($key, $hidden_field)){echo 'style="display:none"';}?>>
	<?php
			if(!isset($data[$key])){
				if($row['Field'] == 'm_addtime' || $row['Field'] == 'm_datetime'){
					$data[$key] = date('Y-m-d H:i:s', TIMESTAMP);
				}else if($row['Default'] != '(NULL)'){
					$data[$key] = $row['Default'];
				}else{
					$data[$key] = '';
				}
			}
			switch($row['Type']){
				case 'int':
				case 'tinyint':
				case 'varchar':
				case 'datetime':
	?>
					<label class="layui-form-label" for="<?php echo $key;?>"><?php echo get_fieldname_dict_show($key, 'data');?></label>
					<div class="layui-input-block">
						<input type="<?php echo in_array($key, $hidden_field) ? 'hidden' : 'text';?>" <?php if($key=='m_id'){echo 'disabled="disabled"';}?>class="layui-input" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo htmlspecialchars($data[$key]);?>" size="40" />
					</div>
				<?php
					break;
				case 'longtext':
				?>
					<div class="layui-form-item layui-form-text">
						<label class="layui-form-label"><?php echo get_fieldname_dict_show($key, 'data');?></label>
						<div class="layui-input-block">
							<?php 
								if(in_array($key, array('m_playdata', 'm_downdata'))){ //对播放和下载数据单独处理
									echo parse_playdata_box($data[$key], $key);
									echo '<div><a href="javascript:void(0)" onclick="clone_datanode(\''.$key.'\')">【添加一组数据】（不需要的话留空即可）</a></div>';
									echo '<hr /><div><h3>原始数据</h3><textarea name="'.$key.'" id="'.$key.'" placeholder="" class="layui-textarea">'.htmlspecialchars($data[$key]).'</textarea></div>';
								}else{
							?>
								<textarea name="<?php echo $key;?>" id="<?php echo $key;?>" placeholder="" class="layui-textarea"><?php echo htmlspecialchars($data[$key]);?></textarea>
							<?php }?>
						</div>
					</div>
				<?php
					
					break;
				default:
					echo '<p title="'.$row['Type'].'">'.$key.':'.$data[$key].'</p>';
				
			}
		?>
	</div><!-- form item end-->
	<?php }?>
	<div class="layui-form-item">
		<label class="layui-form-label" for="submit"> </label>
		<div class="layui-input-block">
			<button type="submit" class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
		</div>
	</div>
</form>

<!-- Initialize the editor. -->
<script>
	$(function() { 
		$('#m_des').froalaEditor() 
	}); 
</script>
<hr class="layui-bg-gray" />
<form action="index.php?action=upload" method="post" class="layui-form">
	<div class="layui-form-item">
		<label for="fileToUpload" class="layui-form-label">图片</label>
		<div class="layui-input-block">
			<input id="new_file" type="file" name="fileToUpload" />
			<button class="layui-btn">上传图片</button><span id="upload_tip"></span>
		</div>
	</div>
</form>
<img id="preview_img" src="" />

<a href="index.php?action=links&type=review&videoid=<?php echo $data_id;?>">【编辑评论】</a>
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
	image_type_str = file.type;
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
			$("#m_pic").val(remote_url);
			if(j_res.upload_done == 1){
				$("#upload_tip").html('上传成功，上方 m_pic 路径已改变。');
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

<script>

//添加那两个输入节点
function clone_datanode(c_name){
	var seek_name = '#' + c_name + '_prototype';
	console.log("seek name is:" + seek_name);
	var c_el = $(seek_name).clone();
	c_el.appendTo('#block_' + c_name);
	c_el.show();
}

//获取所有播放/下载区列的数据，拼接在真实输入框里面

//狗屎JS不支持全部替换，简直是弱智
//https://stackoverflow.com/questions/1144783/how-to-replace-all-occurrences-of-a-string-in-javascript
String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};

function refresh_val(s_name){
	var downdata_source_slice = $('.' + s_name + ' option:selected');
	var downdata_content_slice = $('.' + s_name + ' textarea');
	var new_downdata = '';
	for(var i=0;i<downdata_source_slice.length;i++){
		//检查是否为空
		if(downdata_source_slice[i].text.length > 0){
			new_downdata = new_downdata + downdata_source_slice[i].text + '$$' + downdata_content_slice[i].value.replaceAll("\n", "#"); 
		}
	}
	//console.log(new_downdata);
	$('#' + s_name).val(new_downdata);
}

</script>

<?php 

//把播放数据和下载数据分解成多个输入框+选择框的组合.
//name选择play或download
function parse_playdata_box($playdata, $name = 'm_playdata'){
	$index_table_name = 'player';
	if($name == 'm_downdata'){
		$index_table_name = 'download';
	}
	$ret_string = '<div id="block_'.$name.'">';
	$de_playdata = parse_playdata_detail($playdata);
	foreach($de_playdata as $source_row){
		$ret_string = $ret_string.'<div class="'.$name.'" id="'.$name.'_'.$source_row['source_id'].'">';
		$ret_string = $ret_string.'<select name="'.$name.'_source_'.$source_row['source_id'].'">';
		$ret_string = $ret_string.get_source_option($source_row['source_name'], $index_table_name);
		$ret_string = $ret_string.'</select><textarea name="'.$name.'_content_'.$source_row['source_id'].'" class="layui-textarea">'.htmlspecialchars(str_replace('#',"\n", $source_row['ori_data'])).'</textarea>';
		$ret_string = $ret_string.'</div>';
	}
	$ret_string = $ret_string.'</div>';
	//追加prototype用于添加数据
	$ret_string = $ret_string.'<div class="'.$name.'" style="display:none;" id="'.$name.'_prototype">';
	$ret_string = $ret_string.'<select name="'.$name.'_source_prototype'.'">';
	$ret_string = $ret_string.get_source_option('', $index_table_name);
	$ret_string = $ret_string.'</select><textarea name="'.$name.'_content_prototype" class="layui-textarea"></textarea>';
	$ret_string = $ret_string.'</div>';
	return $ret_string;
}

function get_source_option($source_name, $index_table_name = 'player'){ //默认是选择player表
	$players = DB::query('SELECT * FROM '.table($index_table_name));
	$retstr = '';
	foreach($players as $row){
		if($row['m_name'] == $source_name){
			$retstr = $retstr.'<option selected="selected">'.$row['m_name'].'</option>';
		}else{
		$retstr = $retstr.'<option>'.$row['m_name'].'</option>';
		}
	}
	return $retstr;
}

?>
