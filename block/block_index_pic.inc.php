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
<script>
layui.use('carousel', function(){
  var carousel = layui.carousel;
  //建造实例
  carousel.render({
    elem: '#pic_box',
    width: '220px', //设置容器宽度
	height: '318px',
    arrow: 'hover', //始终显示箭头
    //,anim: 'updown' //切换动画方式
  });
});
</script>
<!-- 条目中可以是任意内容，如：<img src=""> -->