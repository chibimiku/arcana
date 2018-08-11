<?php 

//footer~
//固有底部境界
//计算整个页面的耗时
$page_cost = get_millisecond() - $start_time;
?>

<div class="footer">
	<div>
	免责声明:本站所有内容均来自互联网收集而来，版权归原公司所有，如果侵犯了您的权益，请给邮箱来信，我们会第一时间回复并删除侵权内容，谢谢！ 
	<a target="_blank" href="mailto:lolicd@126.com">【天使动漫联系邮箱】</a>
	</div>
	<div>Proceed in <?php echo $page_cost;?> ms.</div>
	© 2010-2018 <a href="http://www.tsdm.me">S-DM 新番动漫在线 BD无修动漫在线 ,最新美剧在线</a>
</div>

<div id="said_calculation">
	<!-- 度娘统计 -->
	<script>
	(function(){
		var bp = document.createElement('script');
		var curProtocol = window.location.protocol.split(':')[0];
		if (curProtocol === 'https') {
			bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';        
		}
		else {
			bp.src = 'http://push.zhanzhang.baidu.com/push.js';
		}
		var s = document.getElementsByTagName("script")[0];
		s.parentNode.insertBefore(bp, s);
	})();
	</script>
</div>

<div id="ts_block_tip" style="display:none">
	<font size="5">
		<br />
		<br />
		<a href="http://dm.tsdm.tv" target="_blank"> 您现在访问的是旧网址 DM.TSDM<font color="Blue">.NET</font>&nbsp;&nbsp;准备暂停使用<br>
		现在准备为您跳转到<font color="Red">新网址</font> DM.TSDM<font color="Red">.TV</font><br>
		请记好新网址后缀<font color="Red">.tv</font> 并修改好收藏夹网址</a>
	</font>
	<br />
	<br />
	关注 <a href="http://weibo.com/acgtsdm" target="_blank">新浪微博</a>&nbsp;&nbsp;<a href="http://ww2.sinaimg.cn/large/7044f931gw1ex4bpwtdkzj206y07074n.jpg" target="_blank">微信</a>&nbsp;&nbsp;<a href="https://tieba.baidu.com/f?ie=utf-8&amp;kw=%E5%A4%A9%E4%BD%BF%E5%8A%A8%E6%BC%AB" target="_blank">天使贴吧</a>&nbsp;&nbsp;<a href="http://www.tsdm.me/forum.php?mod=viewthread&amp;tid=537303" target="_blank">天使Q群</a>&nbsp;&nbsp;都可以了解最新动态<br />
	<br />
	<a href="http://dm.tsdm.tv"><img border="0" alt="" src="http://wx3.sinaimg.cn/large/7044f931gy1ffkqe4q4h4j20go0b4n13.jpg"></a>
</div>

<script>
	if(document.referrer.indexOf('tsdm.net') >= 0){
		document.getElementById('ts_block_tip').style.display='block';
	}
	if(window.location.href.indexOf('from=tsdmnet') >= 0){
		document.getElementById('ts_block_tip').style.display='block';
	}
</script>

</body>
</html>
<?php 
if(isset($save_index_content_cache)){
	cache_save(INDEX_CACHE_KEY, $cache_front.ob_get_contents(), 600); //缓存首页内容5分钟，头部用 $cache_front 追加，因为顶部那里有个额外的ob_flush();
}
ob_end_flush(); //最后完成输出
?>