<?php 

//simple player site on route.
include ('common_header.php');

//对传入query做加工
if(isset($_GET['query'])){
	$query = trim($_GET['query']);
}else{
	$query = '';
}
if(isset($_GET['letter'])){
	$letter = trim($_GET['letter']);
}else{
	$letter = '';
}
//参数化查询

if(!isset($_GET['mode'])){
	if($letter){
		$mode = 'letter';
	}else{
		$mode = '';
	}
}else{
	$mode = $_GET['mode'];
}

$tpp = 20;
$result_array = array(); //公用存放结果的集合
$cache_key = md5($query.'_'.$letter);

switch ($mode){
	case 'letter':
		if(empty($letter)){
			showmessage('错误，没有字母。');
		}
		$allow_letter = array();
		for($i=65;$i<91;$i++){  
			$allow_letter[] = chr($i);
		}
		
		if(!in_array($letter, $allow_letter)){
			showmessage('错误的查询字母。');
		}
		$result_tmp = DB::query('SELECT * FROM '.table('data')." WHERE m_letter=%s", $letter);
		foreach($result_tmp as $row){
			if(!isset($result_array[$row['m_id']])){
				$result_array['m_id'] = $row;
			}
		}
		break;
	default:
		if(empty($query)){
			showmessage('错误，没有查询词。');
		}
		$query_part = explode(' ', $query, 5);
		foreach($query_part as $query_single){
			$result_tmp = DB::query('SELECT * FROM '.table('m_data')." WHERE m_name LIKE %ss", $query_single); //依次query出来所有的数据
			foreach($result_tmp as $result_row){
				if(!isset($result_array[$result_row['m_id']])){
					$result_array['m_id'] = $result_row;
				}
			}
		}
		//提取出全部结果
		//TODO：缓存结果
}

?>

<div class="page_content">
	
</div>

<?php 
include ('common_footer.php');
?>