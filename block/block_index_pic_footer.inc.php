<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

//首页的四格 END部分

?>

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