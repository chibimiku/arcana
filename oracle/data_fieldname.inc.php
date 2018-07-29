<?php 

if(!defined('IN_ARCANA_ADMIN')){
	exit('Access Deined.');
}

//同时显示中英名称
function get_fieldname_dict_show($i_key, $table){
	$tmp_rs = get_fieldname_dict($i_key, $table);
	if($tmp_rs !== $i_key){
		return $tmp_rs."\n".'('.$i_key.')';
	}
	return $tmp_rs;
}

function get_fieldname_dict($i_key, $table){
	$data_fields_dict = array(
		'data' => array(
			'm_id' => '唯一标识ID',
			'm_name' => '名称',
			'm_state' => '未知',
			'm_type' => '分类',
			'm_pic' => '图片URL',
			'm_hit' => '点击',
			'm_digg' => '未知',
			'm_actor' => '演员',
			'm_note' => '状态',
			'm_des' => '描述',
			'm_topic' => '话题',
			'm_color' => '标题颜色(0x)',
			'm_commend' => '推荐星数',
			'm_wrong' => '未知',
			'm_addtime' => '添加时间',
			'm_publishyear' => '发布时间',
			'm_publisharea' => '发布地区',
			'm_playdata' => '播放数据',
			'm_downdata' => '下载数据',
			'm_isunion' => '未知',
			'm_letter' => '字母',
			'm_keyword' => '关键词',
			'm_tread' => '未知',
			'm_director' => '导演',
			'm_lang' => '语言',
			'm_dayhit' => '日点击',
			'm_weekhit' => '周点击',
			'm_monthhit' => '月点击',
			'm_enname' => '英文名',
			'm_datetime' => '更新时间',
			'm_recycle' => '未知',
			'm_score' => '分数',
			'm_description' => '描述',
			'm_week' => '首页新番显示',
			'm_show_week' => '周几播出',
			'm_enabled' => '搜索可见',
		),
		'nav' => array(
			'm_id' => '唯一标识ID',
			'm_name' => '名称',
			'm_link' => '点出链接',
			'm_new_window' => '新窗口打开',
			'm_displayorder' => '排列顺序(大的在前面)',
		),
		'player' => array(
			'm_id' => '唯一标识ID',
			'm_name' => '播放器名称',
			'm_html' => '播放器HTML正文',
		),
		'index_picblock' => array(
			'm_id' => '唯一标识ID',
			'm_pic' => '图片URL',
			'm_link' => '点出链接',
			'm_name' => '名称,'
		),
		'ads' => array(
			'm_id' => '唯一标识ID',
			'm_name' => '名称',
			'm_enname' => '英文名称',
			'm_des' => '描述',
			'm_content' => '正文',
			'm_addtime' => '添加时间',
		)
	);
	
	if(isset($data_fields_dict[$table][$i_key])){
		return $data_fields_dict[$table][$i_key];
	}
	return $i_key;
}

?>