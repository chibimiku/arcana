<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
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
	$data = DB::query('SELECT * FROM '.table('review')." WHERE m_videoid=%i ".$limit_cond, $data_id);
	return array('total_num' => $lc, 'total_page_num' => $total_page_num, 'data' => $data);
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
	if($cata_id){
		$dbrs = DB::query('SELECT * FROM '.table('data')." WHERE m_type=%i $order_cond LIMIT $limit", $cata_id);
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
		$playdata[] = array('source_name' => $data_1[0], 'data' => $input_array, 'source_id' => $row1_count);
		++$row1_count;
	}
	return $playdata;
}

//显示错误信息
function showmessage($message){
	ob_end_clean();
	echo $message;
	exit();
}


?>