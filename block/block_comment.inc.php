<?php 

if(!defined('IN_ARCANA')){
	exit('Access Deined.');
}

?>

<!-- 评论区 start -->
<div id="comment_header">
	<h1><span id="tit"></span><a href="#cmt">我要发言</a></h1>
</div>
<?php 
//评论ID
	$comment_page = 1;
	if(isset($_GET['page'])){
		$comment_page = max(1, intval($_GET['page']));
	}
?>
<div id="comment_list">
	<div id="comment">
		<?php $comment_tpp = 20;?>
		<?php $review_data = get_comment_data($playid, $comment_page, $comment_tpp);?>
		<?php foreach($review_data['data'] as $re_row){ ?>
			<div class="row">
				<h3>
					<span><?php echo $re_row['m_author'] ? $re_row['m_author'] : '旗鼓相当的网友';?></span>
					<label><?php echo $re_row['m_addtime']?></label>
				</h3>
				<div class="con">
					<div class="mycon">
						<?php echo parse_comment_face(htmlspecialchars($re_row['m_content']));?>
					</div>
				</div>
				<div class="menu">
					<a href="#" onclick="return clk(this,<?php echo $re_row['m_id']?>,0,3);" class="item3">反对[-<?php echo $re_row['m_anti'];?>]</a>
					<a href="#" onclick="return clk(this,<?php echo $re_row['m_id']?>,1,2);" class="item2">同意[+<?php echo $re_row['m_agree']?>]</a>
					<a href="#cmt" onclick="reply(<?php echo $re_row['m_id']?>,'<?php echo htmlspecialchars($re_row['m_content']);?>');" class="item1">回复</a>
				</div>
			</div>
		<?php }?>
		<div class="pager">
			<?php echo multi($review_data['total_num'], $comment_tpp, $comment_page, "detail.php?id=$playid");?>
		</div>
	</div>
</div>
<div id="comment_footer">
	<h5><a name="cmt"></a>发表评论&nbsp;<span style="display:none">本站为防止低俗内容出现，用户发表的评论需本站审核后才能显示出来</span></h5>
	<div id="talk">
		<div id="uploadpic"></div>
		<div id="face"><?php for($i=0;$i<20;++$i){echo '<img src="static/image/cmt/'.($i+1).'.gif" />';}?></div>
		<div id="cancel"></div>
		<form name="form2" id="form2" action="api/send.asp?action=1" method="post" target="myiframe">
			<input type="hidden" name="ctype" id="ctype" value="1">
			<input type="hidden" name="cparent" id="cparent" value="0">
			<input type="hidden" name="gid" id="gid" value="35289">
			<input type="hidden" name="uid" id="uid" value="0">
			<input type="hidden" name="uname" id="uname" value="">
			<input type="hidden" name="unick" id="unick" value="">
			<input type="hidden" name="utmpname" id="utmpname" value="">
			<input type="hidden" name="ppath" id="ppath" value="">
			<input type="hidden" name="pvote" id="pvote" value="1">
			<input type="hidden" name="anony" id="anony" value="0">
			<input type="hidden" name="captcha" id="captcha" value="">
			<div class="tc"><textarea name="talkwhat" id="talkwhat" placeholder="说点儿什么吧…"></textarea></div>
		</form>
		<div class="captcha">验证码：<input type="text" name="gcaptcha" id="gcaptcha" onfocus="getcaptcha();">&nbsp;<div id="getcode"><a href="#cmt" onclick="return getcaptcha();"><span id="codeimg"></span> 看不清？</a></div>&nbsp;请点击后输入四位验证码，字母不区分大小写 [Ctrl+Enter]键快速回复</div>
		<div class="btn"><input type="button" name="submit1" id="submit1" value=" 发表评论 " onclick="submitform();" style="*padding-top:2px;cursor:pointer;">&nbsp;&nbsp;<input type="checkbox" name="anonymous" id="anonymous" value="1"><label for="anony" id="anonylabel">匿名</label></div>
	</div>
	<div class="isad"></div>
	<div class="clean"></div>
</div>
<!-- 评论区 end -->