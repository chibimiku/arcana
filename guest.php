<?php 
include ('common_header.php');

//用户留言板

?>

<?php 
	if(isset($_GET['action'])){
		
		
	}else{	 //没有action就给出默认模板
?>
		<div>
			<form action="guest.php?action=submit" method="post">
				<textarea placeholder="说点儿什么吧..."></textarea>
			</form>
		</div>
<?php
	}
?>

<?php 
include ('common_footer.php');
?>