<?php
	require 'connection.inc.php';
	require 'functions.inc.php';

	$name=get_safe_value($con,$_POST['name']);
	$email=get_safe_value($con,$_POST['email']);
	$mobile_no=get_safe_value($con,$_POST['mobile_no']);
	$password=get_safe_value($con,$_POST['password']);

	$check_user=mysqli_num_rows(mysqli_query($con,"select * from users where email='$email'"));
	$check_mobile=mysqli_num_rows(mysqli_query($con,"select * from users where mobile='$mobile_no'"));
	if ($check_user>0) {
		echo "email_present";
	}elseif ($check_mobile>0) {
		echo "mobile_present";
	}else{
		date_default_timezone_set('Asia/Kolkata');
		$added_on=date('y-m-d h:i:s');
		mysqli_query($con,"insert into users(name,email,mobile,password,added_on) values('$name','$email','$mobile_no','$password','$added_on')");


		$res=mysqli_query($con,"select * from users where BINARY email= BINARY '$email' and BINARY password= BINARY '$password'");
		$check_user_login=mysqli_num_rows($res);
		if ($check_user_login>0) {
			$row=mysqli_fetch_assoc($res);
			$_SESSION['USER_LOGIN']='yes';
			$_SESSION['USER_ID']=$row['id'];
			$_SESSION['USER_NAME']=$row['name'];

			if(isset($_SESSION['WISHLIST_ID']) && $_SESSION['WISHLIST_ID']!=''){
				wishlist_add($con,$_SESSION['USER_ID'],$_SESSION['WISHLIST_ID']);
				unset($_SESSION['WISHLIST_ID']);
			}
		}
		echo "insert";
	}
?>