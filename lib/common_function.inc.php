<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

//根据data生成数据块儿
function generate_ul_block($title, $data, $class='', $id=''){
	$return_str = '<div id="'.$id.'" class="'.$class.'"><h3 class="block_title">'.htmlspecialchars($title).'</h3><ul>';
	foreach($data as $row){
		$return_str = $return_str.'<li>';
		$return_str = $return_str.'<a href="'.$row['link'].'" title="'.$row['name'].'"><img src="'.$row['image'].'" /><span>'.$row['name'].'</span></a>';
		$return_str = $return_str.'</li>';
	}
	$return_str = $return_str.'</ul></div>';
}

function showmessage($message){
	ob_end_clean();
	echo $message;
	exit();
}

?>