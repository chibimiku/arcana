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
					<span># <?php echo $re_row['m_id'].'  ';?></span><span class="comment_author"><?php echo $re_row['m_author'] ? $re_row['m_author'] : '旗鼓相当的网友';?></span>
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
		<div id="face"><?php for($i=0;$i<20;++$i){echo '<img onclick="insert_emo('.($i+1).')" src="static/image/cmt/'.($i+1).'.gif" />';}?></div>
		<div id="cancel"></div>
		<form name="comment_form" id="comment_form" action="guest.php?action=comment_post" method="post">
			<input type="hidden" name="ajax" value="1" />
			<input id="i_videoid" type="hidden" name="videoid" value="<?php echo $playid;?>" />
			<input id="i_reply" type="hidden" name="reply" value="0" />
			<div class="tc">
				<textarea name="content" id="content" placeholder="说点儿什么吧…"></textarea></div>
			<div class="btn">
				<input type="submit" name="submit1" id="submit1" value=" 发表评论" style="*padding-top:2px;cursor:pointer;">
				<span id="re_message"></span>
			</div>
		</form>
		<script>
		//在光标当前位置插入文字
		function insertAtCursor(myField, myValue) {
			if (document.selection) {
				//IE support
				myField.focus();
				sel = document.selection.createRange();
				sel.text = myValue;
				sel.select();
			} else if (myField.selectionStart || myField.selectionStart == '0') {
				//MOZILLA/NETSCAPE support
				var startPos = myField.selectionStart;
				var endPos = myField.selectionEnd;
				var beforeValue = myField.value.substring(0, startPos);
				var afterValue = myField.value.substring(endPos, myField.value.length);

				myField.value = beforeValue + myValue + afterValue;

				myField.selectionStart = startPos + myValue.length;
				myField.selectionEnd = startPos + myValue.length;
				myField.focus();
			} else {
				myField.value += myValue;
				myField.focus();
			}
		}
		
		function insert_emo(emoid){
			var c_i = document.getElementById('content');
			insertAtCursor(c_i, '[em:'+emoid+':]');
		}
		
		</script>
		<script>
			//http://malsup.com/jquery/form/#ajaxForm
			var s_options = {
				success: showResponse
			}
			function showResponse(responseText, statusText, xhr, $form){
				console.log(responseText);
				$('#re_message').text(responseText.message);
			}
			$('#comment_form').submit(function() {
				$(this).ajaxSubmit(s_options);
				// return false to prevent normal browser submit and page navigation
				return false;
			});
		</script>
	</div>
	<div class="isad"></div>
	<div class="clean"></div>
</div>
<!-- 评论区 end -->