<?php
	require 'connection.inc.php';
	require 'functions.inc.php';

	$type=get_safe_value($con,$_POST['type']);

	if ($type=='email') {
		$email=get_safe_value($con,$_POST['email']);
		$check_user=mysqli_num_rows(mysqli_query($con,"select * from users where email='$email'"));
		if ($check_user>0) {
			echo "email_present";
			die();
		}

		$otp=rand(111111,999999);
		$_SESSION['EMAIL_OTP']=$otp;
		$html="<strong>$otp</strong> is your One Time Password for your Registration with pickups";

		include('smtp/PHPMailerAutoload.php');
		$mail=new PHPMailer(true);
		$mail->isSMTP();
		$mail->Host="smtp.gmail.com";
		$mail->Port=587;
		$mail->SMTPSecure="tls";
		$mail->SMTPAuth=true;
		$mail->Username="pickupsgoin@gmail.com";
		$mail->Password="Pickups@11";
		$mail->SetFrom("pickupsgoin@gmail.com");
		$mail->AddAddress($email);
		$mail->IsHTML(true);
		$mail->Subject="New OTP for Registration";
		$mail->Body=$html;
		$mail->SMTPOptions=array('ssl'=>array(
			'verify_peer'=>false,
			'verify_peer_name'=>false,
			'allow_self_signed'=>false
		));
		if ($mail->send()) {
			echo "done";
		}else{
			//echo "failed";
		}
	}
	


	/*if ($type=='mobile') {
		$mobile=get_safe_value($con,$_POST['mobile']);
		$check_mobile=mysqli_num_rows(mysqli_query($con,"select * from users where mobile='$mobile'"));
		if ($check_mobile>0) {
			echo "mobile_present";
			die();
		}
		$otp=rand(111111,999999);
		$_SESSION['MOBILE_OTP']=$otp;
		$message="$otp is your One Time Password for your Registration with pickups";


		$mobile='91'.$mobile;
		$apiKey = urlencode('ZWQ3NjQyM2FhZTQ3ZjdhZTZiMmViY2IwNDcwMmMwZDk=');
		$numbers = array($mobile);
		$sender = urlencode('TXTLCL');
		$message = rawurlencode('$message');
		$numbers = implode(',', $numbers);
		$data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
	 	$ch = curl_init('https://api.textlocal.in/send/');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		echo "done";

	}*/
?>