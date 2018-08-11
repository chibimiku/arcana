<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

define ('UPLOAD_TMP_DIR', $config['upload_temp_dir']);

$upload_done = 0;
$my_dir = '';
$my_mime = '';
$msg = 'data never set.';

$blob = false;
if(isset($_GET['blob'])){
	$blob = true;
}

if($_GET['action'] == 'upload'){
	$real_dir = '../data/'.date("Y-m-d").'/';
	
	//check file2tupload
	$raw_post_data = file_get_contents('php://input', 'r');
	if(!$raw_post_data){
		$msg = '错误：没有正确的上传文件。';
		$upload_done = -1;
	}
	
	//对上传的文件decode 
	$be6_pos = strpos($raw_post_data,'base64,');
	if($be6_pos === false){
		$upload_done = -2;
		$msg = '错误：上传的没有base64处理，请用chrome核心浏览器处理。';
	}else{
		$raw_post_data = substr($raw_post_data, $be6_pos + strlen('base64,'));
		$raw_post_data = base64_decode($raw_post_data);
	}
	
	if(!file_exists($real_dir)){
		mkdir($real_dir, 0777, true); //如果没有目录则创建
	}
	
	$upload_temp_file_path = tempnam(UPLOAD_TMP_DIR ,"TMP0");
	//写入临时文件
	$wrs = file_put_contents($upload_temp_file_path, $raw_post_data);
	if(!$wrs){
		$msg = '错误：写入临时文件失败。';
		$upload_done = -3;
	}
	
	if($upload_done === 0){ //如果写入失败就不进行下面的check了。
		$check = getimagesize($upload_temp_file_path);
		if($check !== false) {
			$my_mime = $check['mime'];
			$real_filename = $real_dir.md5($upload_temp_file_path.rand(0,999));
			//move to uploaded dir.
			$image_type  = $check['2'];
			switch($image_type){
				case 1:
					$image_ext = 'gif';
					break;
				case 2:
					$image_ext = 'jpg';
					break;
				case 3:
					$image_ext = 'png';
					break;
				default:
					$msg = '无法识别上传的图片类型。这个类型是：'.$image_type;
					$upload_done = -3;
			}
			if($upload_done === 0){
				rename ($upload_temp_file_path, $real_filename.'.'.$image_ext); //把临时文件挪到真正的上传目录下。
				$my_dir = $real_filename.'.'.$image_ext;
				//干掉 ../
				$my_dir = str_replace('../', '', $my_dir);
				$upload_done = 1;
				$msg = '上传成功、';
			}
		} else {
			$msg = "上传的不是图片。";
			$upload_done = -3;
		}
	}

}
$return_json_array = array('upload_done' => $upload_done, 'dir' => $my_dir, 'msg' => $msg, 'mime' => $my_mime, 'blob' => $blob);
show_data_json($return_json_array);

function binary_to_file($file, $need_base64 = false){  
	$content = '';
	if(empty($content)){  
		$content = file_get_contents('php://input');    // 不需要php.ini设置，内存压力小  
	}
	$content_ori = $content; //DEBUG backup to analy.
	if(empty($content)){
		exit('no content before decode');
		return false;
	}
	if($need_base64){
		$data_tmp = explode( ',', $content);
		if(!isset($data_tmp[1])){
			return false;
		}
		$content = base64_decode($data_tmp[1]);
	}
	if(empty($content)){
		return false;
	}
	$ret = file_put_contents($file, $content, true);  
	return $ret;  
}  
?>