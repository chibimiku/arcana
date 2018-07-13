<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

//友情链接组
$flink_data = DB::query('SELECT * FROM '.table('link').' WHERE 1=1 ORDER BY m_sort');
echo '<div id="f_link">';
foreach($flink_data as $row){
	echo '<a href="'.$row['m_url'].'" target="_blank" title="'.htmlspecialchars($row['m_des']).'">'.$row['m_name'].'</a>';
}
echo '</div>';

?>