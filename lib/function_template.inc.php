<?php 

//公用函数中，负责输出html部分的放到这里来.

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

function draw_ad($en_name){
	$info = DB::queryFirstRow('SELECT * FROM '.table('ads')." WHERE m_enname=%s", $en_name);
	if(!$info){
		return '';
	}
	return '<div class="ad_'.$info['m_id'].'">'.$info['m_content'].'</div>';
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

//输出某个数据所属分类的内容和连接A标签，用在如面包屑导航的地方.
function output_type_a($type_id){
	global $config;
	return '<a href="search.php?type='.$type_id.'">'.$config['type'][$type_id]['m_name'].'</a>';
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

//制作options
//options_list为一系列 key=>value 对
//$my_option 为我当前选中的value
function draw_options($options_list, $my_option, $in_name, $class=''){
	$ret_str = '<select name="'.$in_name.'" class="'.$class.'">';
	foreach($options_list as $key => $value){
		if($my_option == $value){
			$ret_str = $ret_str.'<option selected="selected" value="'.$value.'">'.$key.'</option>';
		}else{
			$ret_str = $ret_str.'<option value="'.$value.'">'.$key.'</option>';
		}
	}
	$ret_str = $ret_str.'</select>';
	return $ret_str;
}

//返回单条数据的a标签展示
function output_row_a($s_row, $new_window = true){
	if($new_window){
		return '<a href="detail.php?id='.$s_row['m_id'].'" target="_blank">'.htmlspecialchars($s_row['m_name']).'</a>';
	}else{
		return '<a href="detail.php?id='.$s_row['m_id'].'">'.htmlspecialchars($s_row['m_name']).'</a>';
	}
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

?>