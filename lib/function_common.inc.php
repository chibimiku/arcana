<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

//读取留言板列表
function get_leaveword($page = 1, $tpp = 30){
	$where_cond = ' WHERE m_replyid=0';
	$limit_cond = ' LIMIT '.$tpp;
	if($page > 1){
		$limit_cond = ' LIMIT '.($page-1)*$tpp.', '.$tpp;
	}
	$rs = DB::query('SELECT * FROM '.table('leaveword').$where_cond.' ORDER BY m_addtime DESC'.$limit_cond);
	foreach($rs as &$row){
		$reply_content = DB::queryFirstRow('SELECT * FROM '.table('leaveword')." WHERE m_replyid=$row[m_id]");
		if($reply_content){
			$row['re'] = $reply_content['m_content'];
		}
	}
	return $rs;
}

//读取cookie中的播放列表
function get_playlist(){
	//保存数据结构：每条key是di， array('name' => 名称, 'note' => 进度, 'timestamp' => 当时看的时间)
	if(!isset($_COOKIE['playlist'])){
		return array();
	}else{
		return unserialize($_COOKIE['playlist']);
	}
}

function insert_playlist($g_data){
	$max_saving = 10;
	//$g_data = DB::queryFirstRow('SELECT m_id, m_name, m_note FROM '.table('data')." WHERE m_id=%i", intval($data_id));
	if(!$g_data){
		return false;
	}
	$my_data = get_playlist();
	if(!isset($my_data[$g_data['m_id']])){
		if(count($my_data) >= 10){
			$ages_time = TIMESTAMP; //记录最远古一条的时间
			$ages_id = 0;
			foreach($my_data as $key => $row){
				if($row['timestamp'] <= $ages_time){
					$ages_time = $row['timestamp']; //记录最远古一条的时间
					$ages_id = $key;
				}
			}
			if($ages_id > 0){
				$my_data[$ages_id] = array('name' => $g_data['m_name'], 'note' => $g_data['m_note'], 'timestamp' => TIMESTAMP); //替换最古老的数据
			}
		}
	}else{
		//最近看过的数据里有这条，只更新观看时间
		$my_data[$g_data['m_id']]['timestamp'] = TIMESTAMP;
	}
	setcookie('playlist', serialize($my_data));
}


//借用discuz的分页函数
function multi($num, $perpage, $curpage, $mpurl, $maxpages = 0, $page = 10, $autogoto = TRUE, $simple = FALSE) {
	//参考 https://blog.csdn.net/zyyr365/article/details/4083053 不过这个人注释写的不是很好
	//$num 为总共的条数   比如说这个分类下共有15篇文章
	//$perpage为每页要显示的条数
	//$curpage为当前的页数
	//$mpurl为url的除去表示页数变量的一部分
	//$page为$multipage这个字符串中要显示的表示页数的变量个数
	//$maxpages为最大的页数值   此函数最后有一句$maxpage = $realpages;    
    global $maxpage;
   
    $multipage = '';    
    $mpurl .= strpos($mpurl, '?') ? '&' : '?';    
    $realpages = 1;    
     //判断总条数是否大于设置的每页要显示的条数
    if($num > $perpage) {    
		//设置在$multipage中当前页数之前还要输出几个页数    
        $offset = 2;    
        $realpages = @ceil($num / $perpage);    
		//$realpages 为实际总共的页数，$maxpages 为最大显示的页数，如果结果总数太多可以限制只能访问前 xx 页结果。
        $pages = $maxpages && $maxpages < $realpages ? $maxpages : $realpages;    
		//如果总页数小于multipage中要输出的页数$page，则只输出到实际页数为止      
        if($page > $pages){
            $from = 1;    
            $to = $pages;    
		//如果大于的话，就要输出$page个页数（我们假设的的15条数据就符合这个条件）    
        } else {
            $from = $curpage - $offset; //from 为当前的页号减去偏移量2（当前页数之前），如果$curpage 为1，这里 $from 是-1，为2则是0。这两种情况小于1。
            $to = $from + $page - 1;    
            //假设curpage为4，目前为止，from为2，to为11    
             //下面假设$curpage为1    
            if($from < 1) {
                $to = $curpage + 1 - $from; //处理当前选中第1页或第2页的情况。其中$curpage == 1的时候，$to 为3，若为2则还是3。搞这么复杂弄个毛。
				$from = 1;
                //目前为止from为1，to为3    
                if($to - $from < $page) {    
                    //因为这里的前提条件是总条数大于$page，所以，如果$to-$from小于$page的话显然达不到目的，应把$to设置为$page    
                    $to = $page;    
                 }//目前为止 from为1 ，to为10    
            } elseif($to > $pages) {//to是不可以大于总页数的    
                $from = $pages - $page + 1;    
                $to = $pages;    
             }    
         }    
   
        $multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.$mpurl.'page=1" mce_href="'.$mpurl.'page=1" class="first">1 ...</a>' : '').    
             ($curpage > 1 && !$simple ? '<a href="'.$mpurl.'page='.($curpage - 1).'" mce_href="'.$mpurl.'page='.($curpage - 1).'" class="prev">‹‹</a>' : '');    
         for($i = $from; $i <= $to; $i++) {    
            $multipage .= $i == $curpage ? '<strong>'.$i.'</strong>' :    
                '<a href="'.$mpurl.'page='.$i.'" mce_href="'.$mpurl.'page='.$i.'">'.$i.'</a>';    
         }    
   
        $multipage .= ($curpage < $pages && !$simple ? '<a href="'.$mpurl.'page='.($curpage + 1).'" mce_href="'.$mpurl.'page='.($curpage + 1).'" class="next">››</a>' : '').    
             ($to < $pages ? '<a href="'.$mpurl.'page='.$pages.'" mce_href="'.$mpurl.'page='.$pages.'" class="last">... '.$realpages.'</a>' : '').    
             (!$simple && $pages > $page ? '<kbd><input type="text" name="custompage" size="3" /></kbd>' : '');    
   
        $multipage = $multipage ? '<div class="pages">'.(!$simple ? '<em> '.$num.' </em>' : '').$multipage.'</div>' : '';    
    }    
	$maxpage = $realpages;    
	return $multipage;    
}

//解析表情符号
function parse_comment_face($in_str){
	$start_tag = '[em:';
	$end_tag = ':]';
	$find_start_pos = 0;
	$find_end_pos = 0;
	$find_last_pos = 0; //在 $find end_tag 之前做标志位。
	$return_str = '';
	$never_found = true;
	while($find_start_pos !== false){
		$find_start_pos = strpos($in_str, $start_tag, $find_start_pos); //找到开头
		if($find_start_pos !== false){
			$find_start_pos_tail = $find_start_pos + strlen($start_tag);
			$find_last_pos = $find_end_pos; //先寄存上次最后找到 end 的位置.
			$find_end_pos = strpos($in_str, $end_tag, $find_start_pos_tail); //从end开始查找
			if($find_end_pos !== false){
				//切割这一段字符
				$find_last_pos_tail = $find_last_pos > 0 ? $find_last_pos + strlen($end_tag) : 0;
				$return_str = $return_str.substr($in_str, $find_last_pos_tail, $find_start_pos - $find_last_pos_tail);
				$return_str = $return_str.'<img src="static/image/cmt/';
				$return_str = $return_str.intval(substr($in_str, $find_start_pos_tail , $find_end_pos - $find_start_pos_tail));
				$return_str = $return_str.'.gif" />';
				$find_start_pos = $find_end_pos + strlen($end_tag); //让 start 标记归位
				$never_found = false;
			}else{
				//只找到了开头，说明可以结束了
				$return_str = $return_str.substr($in_str, $find_start_pos);
			}
		}
	}
	if($never_found){ //如果从未找到进行替换则直接返回
		return $in_str;
	}
	return $return_str;
}

 //拼接表名
function table($table_name){
	global $config;
	return $config['table_pre'].'_'.$table_name;
}

//获取分类ID的名称
function get_cata_name_by_id($cata_id){
	$rs = DB::queryFirstRow('SELECT m_name FROM '.table('type')." WHERE m_id=%i", intval($cata_id));
	if(!$rs){
		return 'null';
	}else{
		return $rs['m_name'];
	}
}

//输出某个数据所属分类的内容和连接A标签，用在如面包屑导航的地方.
function output_type_a($type_id){
	global $config;
	return '<a href="search.php?typeid='.$type_id.'">'.$config['type'][$type_id]['m_name'].'</a>';
}

//从mysql原始数据里取需要的字段进行拼装
function make_data_table($in_data, $index_data, $primary_key = ''){
	$new_array = array();
	foreach($in_data as $row){ //遍历原始mysql的每一行
		$new_insert_array = array();
		foreach($index_data as $i_row){
			
		}
	}
}

function get_table_field($table_name){
	$data_cols_name_rs = DB::query('SHOW columns FROM '.table($table_name));
	$data_cols = array();
	foreach($data_cols_name_rs as $row){
		$s_pos = strpos($row['Type'], '(');
		if($s_pos !== false){
			$row['Type'] = substr($row['Type'] ,0, $s_pos);
		}
		$data_cols[$row['Field']] = $row;
	}
	return $data_cols;
}

//输出layui加载某个组的js指令
function layui_load_module($module_name){
	echo "<script>layui.use(['".$module_name."'],function(){var ".$module_name."=layui.".$module_name.";});</script>";
}

//绘制编辑某块DATA的form的html并返回
//注意这里上layui的block了
function draw_form($data, $action, $table_name = '', $form_class = 'layui-form', $disabled_key = array(), $delete_key = ''){
	$return_str = '<form class="'.$form_class.'" method="post" action="'.$action.'">';
	foreach($data as $row){
		if(!isset($row['name']) || empty($row['name'])){
			continue;
		}
		$disabled_str = in_array($row['name'], $disabled_key) ? 'disabled="disabled"' : '';
		$return_str = $return_str.'<div class="layui-form-item"><label class="layui-form-label" for="'.$row['name'].'">'.get_fieldname_dict_show($row['name'], $table_name).'</label><div class="layui-input-block" type="'.$row['type'].'">';  //get_fieldname_dict_show() 在data_fieldname.inc.php中，定义了数据标签的中文名称.
		switch($row['type']){
			case 'longtext':
			case 'text':
				$return_str = $return_str.'<textarea '.$disabled_str.' class="layui-textarea" name="'.$row['name'].'">'.htmlspecialchars($row['value']).'</textarea>';
				break;
			default:
				$return_str = $return_str.'<input '.$disabled_str.' class="layui-input" name="'.$row['name'].'" type="text" value="'.htmlspecialchars($row['value']).'" />';
		}
		$return_str = $return_str.'</div></div>';
	}
	if($delete_key){
		//删除按钮
		$return_str = $return_str."\n".'<div class="layui-form-item"><label class="layui-form-label" for="'.$delete_key.'">删除？</label><div class="layui-input-block"><input name="'.$delete_key.'" lay-skin="primary" type="checkbox" value="1" title="删除" /></div></div>'."\n";
	}
	//提交按钮
	$return_str = $return_str.'<div class="layui-form-item"><label class="layui-form-label" for=""></label><input class="layui-btn" type="submit" value="提交" /></div>';
	$return_str = $return_str.'</form>';
	return $return_str;
}

//绘制table的html并返回
function draw_table($table_head, $table_data, $table_class = ''){
	if(!is_array($table_data)){
		return '';
	}
	$num_diff = count($table_head) - count($table_data);
	$return_str = '<table class="'.$table_class.'"><thead><tr>';
	foreach($table_head as $row){
		$return_str = $return_str.'<td>'.htmlspecialchars($row).'</td>';
	}
	$return_str = $return_str.'</tr></thead><tbody>';
	foreach($table_data as $key => $row){

		$return_str = $return_str.'<tr>';
		if(is_array($row)){
			foreach($row as $col){
				$return_str = $return_str.'<td>'.$col.'</td>';
			}
		}else{
			$return_str = $return_str.'<td>'.$row.'</td>';
		}
		$return_str = $return_str.'</tr>';
		
	}
	$return_str = $return_str.'</tbody></table>';
	return $return_str;
}

//制作a标签的html
function create_link($content, $url, $new_window = false, $color = ''){
	$addtional_blank = $new_window ? ' target="_blank "' : '';
	return '<a style="color:'.$color.'" href="'.$url.'"'.$addtional_blank.'>'.htmlspecialchars($content).'</a>';
}

//返回单条数据的a标签展示
function output_row_a($s_row, $new_window = true){
	if($new_window){
		return '<a href="detail.php?id='.$s_row['m_id'].'" target="_blank">'.htmlspecialchars($s_row['m_name']).'</a>';
	}else{
		return '<a href="detail.php?id='.$s_row['m_id'].'">'.htmlspecialchars($s_row['m_name']).'</a>';
	}
}

//带limit的替换
//来自https://stackoverflow.com/questions/8510223
function str_replace_limit($find, $replacement, $subject, $limit = 0){
  if ($limit == 0)
    return str_replace($find, $replacement, $subject);
  $ptn = '/' . preg_quote($find,'/') . '/';
  return preg_replace($ptn, $replacement, $subject, $limit);
}

//获取评论列表
function get_comment_data($data_id, $page, $tpp = 20){
	$lc = DB::queryFirstField('SELECT count(*) FROM '.table('review')." WHERE m_videoid=%i", $data_id);
	$page = max(1, intval($page));
	$limit_cond = 'LIMIT '.$tpp;
	if($page > 1){
		$limit_start = ($page - 1) * $tpp;
		$limit_cond = 'LIMIT '.$limit_start.', '.$tpp;
	}
	$total_page_num = $lc / $tpp;
	$data = DB::query('SELECT * FROM '.table('review')." WHERE m_videoid=%i ORDER BY m_addtime DESC ".$limit_cond, $data_id);
	return array('total_num' => $lc, 'total_page_num' => $total_page_num, 'data' => $data);
}

//获取那一大堆评论数据的回复叠加框
//其实我觉得这个设计挺傻的
function get_comment_block_data($data_id, $max_depth = 10){
	$final_ret = '';
	$get_box = true;
	$block_datas = array();
	$depth = 0;
	while($get_box){
		$single_data = DB::queryFirstRow('SELECT * FROM '.table('review')." WHERE m_id=%i", $data_id);
		if(!$single_data){
			$get_box = false;
			break;
		}
		$block_datas[] = $single_data;
		if(!$single_data['m_reply']){
			$get_box = false;
			break;
		}
		if($depth >= $max_depth){
			$get_box = false; //最大深度10层，免得有弱智加太多回复
		}
		$data_id = $single_data['m_reply'];
		++$depth;
	}
	foreach($block_datas as $row){
		$final_ret = $final_ret.'<div class="reply">';
	}
	foreach($block_datas as $row){
		$final_ret = $final_ret.'<h4><span>'.htmlspecialchars($row['m_author'] ? $row['m_author'] : '无名氏').'</span></h4><p>'.htmlspecialchars($row['m_content']).'</p></div>';
	}
	return $final_ret;
}

//获取按分类ID的前x条儿结果
function get_data_by_cata_id($cata_id, $limit = 10, $by_type = -1){
	$cata_id = intval($cata_id);
	$order_cond = '';
	switch($by_type){
		case 0:
			$order_cond = 'ORDER BY m_hit DESC'; //按点击数降序
			break;
		case 1:
			$order_cond = 'ORDER BY m_hit ASC'; //按点击数升序
			break;
		case 2:
			$order_cond = 'ORDER BY m_datetime DESC'; //按更新时间降序
			break;
		case 3:
			$order_cond = 'ORDER BY m_datetime ASC'; //按更新时间升序
			break;
		default:
			$order_cond = '';
	}
	//m_enabled 标志是否隐藏
	if($cata_id){
		$dbrs = DB::query('SELECT * FROM '.table('data')." WHERE m_enabled>0 AND m_type=%i $order_cond LIMIT $limit", $cata_id);
	}else{
		$dbrs = DB::query('SELECT * FROM '.table('data')." $order_cond LIMIT $limit");
	}
	$in_line_count = 0;
	foreach($dbrs as &$row){
		$row['k_order'] = $in_line_count;
		++$in_line_count;
	}
	return $dbrs;
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


//对playdata里m_playdata那个别扭的字段进行解析
//注意$data_2分割符是否正确按db来的.
function parse_playdata_detail($in_str, $debug = false){
	$playdata = array();
	$play_sources = explode('$$$', $in_str);
	if($debug){
		var_dump($play_sources);
		$ss_tmp = explode("\n", $play_sources[0]);
		var_dump($ss_tmp);
		
	}
	$row1_count = 0; //这个是计数器，原始数据比较蠢没有做正确的索引
	foreach($play_sources as $row1){
		$data_1 = explode("$$", $row1);
		//用 $$ 分割之后，前面是整个source的名字，后面是播放数据.各行以#分割
		$data_2 = explode("#", $data_1[1]); //每个源里按行分割的数据
		$input_array = array(); 
		$row2_count = 0;
		foreach($data_2 as $row3){
			$data_3 = explode('$', $row3);
			$input_array[] = array(
				'name' => $data_3[0],
				'playurl' => $data_3[1],
				'playtype' => $data_3[2],
				'row_id' => $row2_count,
			);
			++$row2_count;
		}
		$playdata[] = array('source_name' => $data_1[0], 'data' => $input_array, 'source_id' => $row1_count, 'ori_data' => $data_1[1]);
		++$row1_count;
	}
	return $playdata;
}

//读入post变量
//0是post，1是get
function read_user_info($var_names, $var_type = 0){
	$ret_array = array();
	foreach($var_names as $row){
		if($var_type == 0){
			if(isset($_POST[$row])){
				$ret_array[$row] = $_POST[$row];
			}else{
				$ret_array[$row] = '';
			}
		}else{
			if(isset($_GET[$row])){
				$ret_array[$row] = $_GET[$row];
			}else{
				$ret_array[$row] = '';
			}
		}
	}
	return $ret_array;
}

//把部分数据转化为intval，提高安全性
function set_intval($in_array, $intval_keys){
	foreach($intval_keys as $row){
		$in_array[$row] = intval($in_array[$row]);
	}
	return $in_array;
}

//显示错误信息
function showmessage($message, $jump_url = '', $title = '提示'){
	ob_end_clean();
	echo "<div id=\"error\"><h3>$title</h3><div id=\"error_content\">";
	echo '<span>'.htmlspecialchars($message).'</span>';
	echo '</div>';
	if($jump_url){
		echo '<div><a href="'.$jump_url.'">等待3秒页面自动跳转，或者点击此处立即跳转。</a></div>';
	}
	echo '<button onclick="goBack()">点击返回</button><script>function goBack(){window.location.href="'.$jump_url.'";}</script>';
	if($jump_url){
		echo '<script>setTimeout(function(){window.location.href = "'.$jump_url.'"}, 3000);</script>';
	}
	echo '</body></html>';
	exit();
}

function showmessage_json($message, $status = 0){
	show_data_json(array('status' => $status, 'message' => $message));
}

//向客户端发送json
function show_data_json($j_data){
	ob_end_clean();
	header('Content-type: application/json'); //返回json结果
	$json_result = json_encode($j_data, JSON_UNESCAPED_SLASHES);
	if(!$json_result){
		echo '{"message" : "输入的json不正确。"}';
	}else{
		echo $json_result;
	}
	exit();
}


?>