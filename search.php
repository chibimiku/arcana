<?php 

//simple player site on route.
include ('common_header.php');

define('SEARCH_INTERVAL_MAX_COUNT', 3);
define('SEARCH_INTERVAL_TIME', 2);

//检查该IP以前的查询记录
$last_timestamp = TIMESTAMP - SEARCH_INTERVAL_TIME;
$search_times = DB::query('SELECT * FROM '.table('search_log')." WHERE from_ip=%s AND timestamp > ".$last_timestamp.' ORDER BY timestamp DESC', $info['userip']);
if(count($search_times) >= SEARCH_INTERVAL_MAX_COUNT){
	showmessage('您搜索太过频繁，请过几秒再来吧。');
}

//对传入query做加工
$query = init_var('query');
$letter = init_var('letter');
$type = intval(init_var('type'));

if(!isset($_GET['mode'])){
	if($letter){
		$mode = 'letter';
	}elseif($type){
		$mode = 'type';
	}else{
		$mode = '';
	}
}else{
	$mode = $_GET['mode'];
}

if(!isset($_GET['page'])){
	$page = 1;
}else{
	$page = max(1, intval($_GET['page']));
}

$tpp = 20;
$result_array = array(); //公用存放结果的集合
$cache_key = md5($query.'_'.$letter.'_'.$type);

$query_field = array('m_id','m_name','m_pic','m_note','m_type','m_hit','m_datetime');
$search_url = '';
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
		$result_tmp = DB::query('SELECT '.implode(',', $query_field).' FROM '.table('data')." WHERE m_letter=%s", $letter);
		foreach($result_tmp as $row){
			if(!isset($result_array[$row['m_id']])){
				$result_array[$row['m_id']] = $row;
			}
		}
		unset($result_tmp);
		$result_tmp = DB::query('SELECT '.implode(',', $query_field).' FROM '.table('data')." WHERE m_letter=%s", strtolower($letter)); //同时包含大小写数据
		foreach($result_tmp as $row){
			if(!isset($result_array[$row['m_id']])){
				$result_array[$row['m_id']] = $row;
			}
		}
		unset($result_tmp);
		$search_url = '&letter='.$letter;
		break;
	case 'type':
		$result_tmp = DB::query('SELECT '.implode(',', $query_field).' FROM '.table('data')." WHERE m_type=%i", $type); //同时包含大小写数据
		foreach($result_tmp as $row){
			if(!isset($result_array[$row['m_id']])){
				$result_array[$row['m_id']] = $row;
			}
		}
		$search_url = '&type='.$type;
		break;
	default:
		if(empty($query)){
			showmessage('错误，没有查询词。');
		}
		$query_part = explode(' ', $query, 5);
		foreach($query_part as $query_single){
			$result_tmp = DB::query('SELECT '.implode(',', $query_field).' FROM '.table('data')." WHERE m_name LIKE %ss", $query_single); //依次query出来所有的数据
			foreach($result_tmp as $result_row){
				if(!isset($result_array[$result_row['m_id']])){
					$result_array['m_id'] = $result_row;
				}
			}
		}
		$search_url = '&query='.$query;
		//提取出全部结果
		//TODO：缓存结果
}

//记录总结果数
$total_item = count($result_array);

//进行分页
$offset = ($page - 1) * $tpp;
$result_array= array_slice($result_array, $offset, $tpp, true);

//记录日志
DB::insert(table('search_log'), array('query' => $query, 'from_ip' => $info['userip'], 'timestamp' => TIMESTAMP));
?>

<div class="page_content">
	<div class="box700 fl">
		<h3 class="titbar">&nbsp;&nbsp;搜索视频 <?php echo $letter ? $letter : $query;?> 共 <?php echo $total_item;?> 条</h3>
		<div class="movie-chrList"><ul>
			<?php foreach($result_array as $row){?>
				<li>
					<div class="cover">
						<a href="detail.php?id=<?php echo $row['m_id'];?>"><img src="<?php echo $row['m_pic'];?>" /></a>
					</div>
					<div class="intro">
						<h6><a href="<?php echo $row['m_id'];?>"><?php echo htmlspecialchars($row['m_name']);?></a></h6>
						<em>状态：<span><?php echo $row['m_note'];?></span></em>
						<em>类型：<?php echo get_cata_name_by_id($row['m_type']);?></em>
						<em>人气：<?php echo $row['m_hit'];?></em>
						<em>更新：<?php echo $row['m_datetime'];?></em>
					</div>
				</li>
			<?php }?>
			</ul><div class="blank10 cl"></div>
		</div>
	</div>
	<!-- 右侧推荐 start -->
	<div class="box250 fr">
		<div class="search_right_box">
			<script type="text/javascript" language="javascript" src="static/js/ads/250200.js"></script>
		</div>
		<div class="blank10"></div>
		<h3 class="titbar"><span>&nbsp;</span><em class="hkdsj">推荐</em></h3>
		<div class="list-rec fix">
			<?php $recommend_data_1 = get_data_by_cata_id(0);?>
			<ul class="fix">
				<?php foreach($recommend_data_1 as $row){?>
					<li><a href="detail.php?id=<?php echo $row['m_id'];?>"><?php echo $row['m_name'];?></a> <span><?php echo $row['m_note']?></span></li>
				<?php }?>
			</ul>
		</div>
	</div>
	<!-- 右侧推荐 emd -->
	<div class="cl"></div>
</div>
<div class="page_content">
	<div class="pager">
		<?php echo multi($total_item, $tpp, $page, 'search.php?'.$search_url);?>
	</div>
</div>

<?php 

function init_var($var_s_name){
	if(isset($_GET[$var_s_name])){
		$in_var = trim($_GET[$var_s_name]);
		$in_var = str_ireplace('\'', '', $in_var); //SQL处理掉引号
		$in_var = str_ireplace('"', '', $in_var);
		$in_var = str_ireplace('?', '', $in_var);
		$in_var = str_ireplace('=', '', $in_var);
	}else{
		$in_var = '';
	}
	return $in_var;
}


include ('common_footer.php');
?>