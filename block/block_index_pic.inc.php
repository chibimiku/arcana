<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

//首页的四格

?>

<div class="layui-carousel flash" id="pic_box" style="float:left;">
	<div carousel-item>
		<?php $pic_data = DB::query('SELECT * FROM '.table('index_picblock')." WHERE 1=1");?>
		<?php foreach($pic_data as $row){?>
		<div>
			<a href="<?php echo htmlspecialchars($row['m_link']);?>" target="_blank">
				<img style="max-width:220px;" src="<?php echo $row['m_pic'];?>" alt="<?php echo htmlspecialchars($row['m_name']);?>"/>
			</a>
		</div>
		<?php }?>
	</div>
</div>
<!-- 条目中可以是任意内容，如：<img src=""> -->
<!-- load的问题放在页面最后加载 -->