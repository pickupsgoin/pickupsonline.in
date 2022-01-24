<?php
	session_start();
	$con=mysqli_connect("localhost","root","","pickups");
	define('SERVER_PATH', $_SERVER['DOCUMENT_ROOT'].'/pickups/');
	define('SITE_PATH', 'http://localhost/pickups/');



	define('PRODUCT_IMAGE_SERVER_PATH', SERVER_PATH.'images/products/');
	define('PRODUCT_IMAGE_SITE_PATH', SITE_PATH.'images/products/');


	define('PRODUCT_MULTIPLE_IMAGE_SERVER_PATH', SERVER_PATH.'images/product_images/');
	define('PRODUCT_MULTIPLE_IMAGE_SITE_PATH', SITE_PATH.'images/product_images/');

	define('BANNER_SERVER_PATH', SERVER_PATH.'images/banner/');
	define('BANNER_SITE_PATH', SITE_PATH.'images/banner/');

	define('VENDER_SERVER_PATH', SERVER_PATH.'images/');
	define('VENDER_SITE_PATH', SITE_PATH.'images/');

	define('SMTP_EMAIL','');
	define('SMTP_PASSWORD','');
	define('SMS_KEY','');

	define('INSTAMOJO_KEY','');
	define('INSTAMOJO_TOKEN','');
	
?>