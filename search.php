<?php 

//simple player site on route.
include ('common_header.php');

define('SEARCH_INTERVAL_MAX_COUNT', 3);
define('SEARCH_INTERVAL_TIME', 2);
define('SEARCH_TOO_MANY_MESSAGE', '您搜索太过频繁，请过几秒再来吧。');

//检查该IP以前的查询记录
$last_timestamp = TIMESTAMP - SEARCH_INTERVAL_TIME;
$search_times = DB::query('SELECT * FROM '.table('search_log')." WHERE from_ip=%s AND timestamp > ".$last_timestamp.' ORDER BY timestamp DESC', $info['userip']);
if(count($search_times) >= SEARCH_INTERVAL_MAX_COUNT){
	showmessage(SEARCH_TOO_MANY_MESSAGE);
}

$show_hit = true; //是否显示点击数人气

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
	$mode = $_GET['mode']; //需要限制GET来的mode在正常的范围内
}

if($mode == 'type'){
	$show_hit = false; //当类型检索时不显示人气
}

if(!isset($_GET['page'])){
	$page = 1;
}else{
	$page = max(1, intval($_GET['page']));
}

$tpp = 20;
$result_array = array(); //公用存放结果的集合

$query_field = array('m_id','m_name','m_pic','m_note','m_type','m_hit','m_datetime');
$search_url = init_search_url($mode, array('letter' => $letter, 'type' => $type, 'query' => $query));

$cache_key = 'SEARCH_'.md5($query.'_'.$letter.'_'.$type);

$need_save_cache = false;
//尝试读取缓存
$cache_result = cache_load($cache_key);
if(!$cache_result){ //没有读取到缓存，开始从db里面读取数据
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
			break;
		case 'type':
			$result_tmp = DB::query('SELECT '.implode(',', $query_field).' FROM '.table('data')." WHERE m_type=%i", $type); //同时包含大小写数据
			foreach($result_tmp as $row){
				if(!isset($result_array[$row['m_id']])){
					$result_array[$row['m_id']] = $row;
				}
			}
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
			//提取出全部结果
	}
	$need_save_cache = true;
}else{
	$result_array = unserialize($cache_result);
}

//记录总结果数
$total_item = count($result_array);

//保存缓存
if($need_save_cache){
	cache_save($cache_key,serialize($result_array),3600);
}

//进行分页
$offset = ($page - 1) * $tpp;
$result_array = array_slice($result_array, $offset, $tpp, true);

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
						<h6><a href="detail.php?id=<?php echo $row['m_id'];?>"><?php echo htmlspecialchars($row['m_name']);?></a></h6>
						<em>状态：<span><?php echo $row['m_note'];?></span></em>
						<em>类型：<a href="search.php?type=<?php echo $row['m_type'];?>"><?php echo get_cata_name_by_id($row['m_type']);?></a></em>
						<?php if($show_hit){?><em>人气：<?php echo $row['m_hit'];?></em><?php }?>
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
			<?php echo draw_ad('search_right_01');?>
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
		<div class="blank10"></div>
		<h3 class="titbar"><em>推荐</em></h3>
		<div class="list-rec fix">
			<?php echo draw_recommend_list(20, 'fix', '');?>
		</div>
		<div class="blank10"></div>
		<h3 class="titbar"><em>点击排行</em></h3>
		<div class="list-rec fix">
			<?php echo draw_hit_list(20, 'fix', '');?>
		</div>
	</div>
	<!-- 右侧推荐 end -->
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

//设置 search_url 翻页url参数
function init_search_url($mode, $s_var){
	switch ($mode){
		case 'letter':
			$search_url = '&letter='.$s_var['letter'];
			break;
		case 'type':
			$search_url = '&type='.$s_var['type'];
			break;
		default:
			$search_url = '&query='.$s_var['query'];
	}
	return $search_url;
}

include ('common_footer.php');
?>