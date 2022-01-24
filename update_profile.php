<?php
	require 'connection.inc.php';
	require 'functions.inc.php';

	if (!isset($_SESSION['USER_LOGIN'])) {
		?>
		<script>
			window.location.href="index.php";
		</script>
		<?php
	}


	$update_name=get_safe_value($con,$_POST['update_name']);
	$uid=$_SESSION['USER_ID'];
	mysqli_query($con,"update users set name='$update_name' where id='$uid'");
	$_SESSION['USER_NAME']=$update_name;
	echo "Your name updated";
	
?>