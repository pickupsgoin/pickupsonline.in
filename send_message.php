<?php
require('connection.inc.php');
require('functions.inc.php');
$name=get_safe_value($con,$_POST['name']);
$email=get_safe_value($con,$_POST['email']);
$mobile=get_safe_value($con,$_POST['mobile_no']);
$comment=get_safe_value($con,$_POST['message']);
date_default_timezone_set('Asia/Kolkata');
$added_on=date('y-m-d h:i:s');

mysqli_query($con,"insert into contact_us(name,email,mobile,comment,added_on) values('$name','$email','$mobile','$comment','$added_on')");
echo "Thankyou..";
?>