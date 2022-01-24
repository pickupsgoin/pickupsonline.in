<?php
	require 'top.php';
	if (!isset($_SESSION['cart']) || count($_SESSION['cart'])==0) {
?>
	<script>
		window.location.href='index.php';
	</script>
<?php
	}

	$delivery_charge=49;
	$cart_total=0;
	$errMsg="";
	foreach ($_SESSION['cart'] as $key => $val) {
		$productArr=get_product($con,'','',$key);
		$price=$productArr[0]['price'];
		$qty=$val['qty'];
		$size=$val['size'];
		$cart_total=$cart_total+($price*$qty);
	}


	if (isset($_POST['submit'])) {
		$name=get_safe_value($con,$_POST['name']);
		$address=get_safe_value($con,$_POST['address']);
		$city=get_safe_value($con,$_POST['city']);
		$pincode=get_safe_value($con,$_POST['pincode']);
		$mobile_no=get_safe_value($con,$_POST['mobile_no']);

		$order_place_id='#'.rand(111111,999999);

		$payment_type=get_safe_value($con,$_POST['payment_type']);
		$user_id=$_SESSION['USER_ID'];
		$total_price=$cart_total;
		$payment_status='pending';
		if ($payment_type=='cash_on_delivery') {
			$payment_status='pending';			
		}
		$order_status='1';
		date_default_timezone_set('Asia/Kolkata');
		$added_on=date('y-m-d h:i:s');

		$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);

		if (isset($_SESSION['COUPON_ID'])) {
			$coupon_id=$_SESSION['COUPON_ID'];
			$coupon_code=$_SESSION['COUPON_CODE'];
			$coupon_value=$_SESSION['COUPON_VALUE'];
			$total_price=$total_price-$coupon_value;
			unset($_SESSION['COUPON_ID']);
			unset($_SESSION['COUPON_CODE']);
			unset($_SESSION['COUPON_VALUE']);
		}else{
			$coupon_id=0;
			$coupon_code='';
			$coupon_value='';
		}
		
		

		mysqli_query($con,"insert into `order`(user_id,name,address,city,pincode,mobile_no,payment_type,total_price,payment_status,order_status,added_on,txnid,coupon_id,coupon_code,coupon_value,order_place_id) values('$user_id','$name','$address','$city','$pincode','$mobile_no','$payment_type','$total_price','$payment_status','$order_status','$added_on','$txnid','$coupon_id','$coupon_code','$coupon_value','$order_place_id')");

		$order_id=mysqli_insert_id($con);

		foreach ($_SESSION['cart'] as $key => $val) {
			$productArr=get_product($con,'','',$key);
			$price=$productArr[0]['price'];
			$qty=$val['qty'];
			$size=$val['size'];

			mysqli_query($con,"insert into `order_detail`(order_id,product_id,size,qty,price,added_on) values('$order_id','$key','$size','$qty','$price','$added_on')");
		}

		

		if ($payment_type=='pay_online') {

			$userArr=mysqli_fetch_assoc(mysqli_query($con,"select * from users where id='$user_id'"));

			/*$posted['txnid']=$txnid;
			$posted['amount']=$total_price;*/
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://test.instamojo.com/api/1.1/payment-requests/');
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($ch, CURLOPT_HTTPHEADER,
			            array("X-Api-Key:test_d19bdac5cdf9b3e990cee33f433",
			                  "X-Auth-Token:test_ba95bc7db4bed414b0679383a36"));

			$payload = Array(
			    'purpose' => 'Buy Product',
			    'amount' => $total_price,
			    'phone' => $userArr['mobile'],
			    'buyer_name' => $userArr['name'],
			    'redirect_url' => SITE_PATH.'payment_complete.php',
			    'send_email' => false,
			    'send_sms' => false,
			    'email' => $userArr['email'],
			    'allow_repeated_payments' => false
			);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
			$response = curl_exec($ch);
			curl_close($ch); 
			$response=json_decode($response);

			if (isset($response->payment_request->id)) {
				unset($_SESSION['cart']);
				$_SESSION['TID']=$response->payment_request->id;
				mysqli_query($con,"update `order` set txnid='".$response->payment_request->id."' where id='".$order_id."'");	
				?>
				<script>
					window.location.href='<?php echo $response->payment_request->longurl ?>';
				</script>
				<?php
			}else{
				if (isset($response->message)) {
					$errMsg.="<div style='color:red'>";
					foreach ($response->message as $key => $val) {
						$errMsg.=strtoupper($key).':'.$val[0].'<br/>';
					}
					$errMsg.="</div>";
				}else{
					echo "Something went wrong";
				}
			}
			
		}else{

			setnInvoice($con,$order_id);
			unset($_SESSION['cart']);
			?>
				<script>
					window.location.href='thank_you.php';
				</script>
			<?php
		}

	}
?>

<section id="checkoutpage">
	<div class="container-fluid">
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a href="cart.php">Cart</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a class="active" href="">Checkout</a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<?php echo $errMsg; ?>
			<div class="col-md-8 left">

				<?php 
					$address='address';
					$payment_info='payment_info';
					if (!isset($_SESSION['USER_LOGIN'])) {
						$address='address_disable';
						$payment_info='payment_info_disable';

				?>
				<script>
					$('#myModal').modal('show');
				</script>
				<div><a style="cursor: pointer; color: red; font-weight: bold;" data-toggle="modal" data-target="#myModal">Login First >>></a></div>
				<!-- <div class="checkout_method">
					<span><i class="far fa-plus-square"></i>&nbsp;FIRST :) Login/Register</span>
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-6 login">
								LOGIN
								<form method="post">
									<label>Email Address</label>
									<br>
									<input type="text" name="login_email" id="login_email" placeholder="Your Email*" required>
									<div class="field_error" id="login_email_error"></div>
									<br>
									<br>
									<label>Password</label>
									<br>
									<input type="password" name="login_password" id="login_password" placeholder="Your Password" required>
									<div class="field_error" id="login_password_error"></div>
									<button type="button" onclick="user_login()" name="login">Login</button>
								</form>
								<div id="login_msg"><p></p></div>
							</div>
							<div class="col-md-6 regis">
								REGISTER
								<form method="post">
									<label>Name</label>
									<br>
									<input type="text" name="name" id="name" placeholder="Your Name*" required>
									<div class="field_error" id="name_error"></div>
									<br><br>
									<label>Email Address</label>
									<br>
									<input type="email" name="email" id="email" placeholder="Your Email*" required>
									<div class="field_error" id="email_error"></div>
									<br><br>
									<input type="text" name="mobile_no" id="mobile_no" placeholder="Your Mobile No.*" required>
									<div class="field_error" id="mobile_no_error"></div>
									<br><br>
									<label>Password</label>
									<br>
									<input type="password" name="password" id="password" placeholder="Your Password*" required>
									<div class="field_error" id="password_error"></div>
									<button type="button" onclick="user_register()" name="register">Register</button>
								</form>
								<div id="register_msg"><p></p></div>
							</div>
						</div>
					</div>
				</div> -->

			<?php } ?>

				<div class="<?php echo $address ?> add">
					<form method="post">
						<span><i class="far fa-plus-square"></i>&nbsp;ADDRESS INFORMATION</span>
						<div class="container-fluid">
							<div class="row"><div class="col">
								<input type="text" name="name" id="testb" placeholder="Name" required>
								<input type="text" name="address" placeholder="Address" required>
								<section class="bottom_input">
									<input type="text" name="city" placeholder="City" required>
									<input type="text" name="pincode" placeholder="Post code/ZIP" required>
									<input type="text" name="mobile_no" placeholder="Mobile Number" required>
								</section>
							</div></div>
						</div>
					</div>
					<div class="<?php echo $payment_info ?> payment">
						<span><i class="far fa-plus-square"></i>&nbsp;PAYMENT METHOD</span>
						<div class="container-fluid">
							<div class="row">
								<div class="col">
								<p><input type="radio" name="payment_type" class="payment_type" value="pay_online" required>Pay Online</p>
								<p><input type="radio" name="payment_type" class="payment_type" value="cash_on_delivery" required>Cash on Delivery</p>
								</div>
							</div>
						</div>
					</div>
					<input class="checkout_submit" type="submit" name="submit" value="Place Order">
					<div class="field_error" id="submit_error"></div>
				</form>
			</div>

			<div class="col-md-4 right">
				<p class="heading">You want to order</p>

				<?php
				$cart_total=0;
				foreach ($_SESSION['cart'] as $key => $val) {
				$productArr=get_product($con,'','',$key);
				$pname=$productArr[0]['name'];
				$mrp=$productArr[0]['mrp'];
				$price=$productArr[0]['price'];
				$image=$productArr[0]['image'];
				$qty=$val['qty'];
				$size=$val['size'];
				$cart_total=$cart_total+($price*$qty);
				?>

				<div class="detail">
					<div class="container-fluid"><div class="row">
						<div class="cover col-3"><img src="<?php echo PRODUCT_IMAGE_SITE_PATH.$image ?>"></div>
						<div class="col-4 checkout_pname"><?php echo $pname ;?>
							<br><small>(<?php echo $price." x ".$qty;?>)</small>
							<br><small style="color: red;"><?php
							if ($size=="undefined") {
								echo "Size : Not Apply";
							}else{
								echo "Size : ".$size;
							}?></small>
						</div>
						<div class="col-3 center align-items-end"><strong><i class="fas fa-rupee-sign"></i>&nbsp;<?php echo $price*$qty ?></strong></div>
						<div class="col-2 bin center align-items-end"><a href="javascript:void(0)" onclick="manage_cart('<?php echo $key?>','remove')"><i class="fas fa-trash"></i></a></div>
					</div></div>
				</div>
				
				<?php } ?>

				<!-- <div class="detail2">
					<b>Sub Total</b>
					<span><i class="fas fa-rupee-sign"></i>&nbsp;599</span>
				</div>
				<div class="detail">
					<b>Shipping Charges</b>
					<span><i class="fas fa-rupee-sign"></i>&nbsp;599</span>
				</div> -->
				<div class="detail" id="coupon_box">
					<b>Coupon Value</b>
					<div><i class="fas fa-rupee-sign"></i><span id="coupon_price"></span></div>
				</div>

				<?php if ($cart_total<=249) {
				?>
					<div class="detail" style="display: flex; justify-content: space-between;align-items: center;">
						<b>Delivery Charges</b>
						<div><i class="fas fa-rupee-sign"></i><span>&nbsp; 49</span></div>
					</div>
					<div style=" background: black; padding: 0px 6px;"><small style="color: orange;">Make order above 249* for free delivery</small></div>
				<?php
				$cart_total=$cart_total+$delivery_charge;
				} ?>
				

				<div class="detail total_final">
					<b>Order Total</b>
					<div><i class="fas fa-rupee-sign"></i><span id="order_total_price">&nbsp;<?php echo $cart_total ?></span></div>
				</div>

				<div class="detail total_final">
					<input type="textbox" name="coupon_str" id="coupon_str"><input class="coupon_btn checkout_submit" type="button" name="submit_coupon" value="Apply Coupon" onclick="set_coupon()" />
				</div>
				<div class="detail" id="coupon_result" style="color: orange; font-weight: bold; font-size: 15px;"></div>

			</div>
		</div>
	</div>
</section>

<script>
	function set_coupon(){
		var coupon_str=jQuery('#coupon_str').val();
		if (coupon_str!='') {
			jQuery('#coupon_result').html('');
			jQuery.ajax({
				url:'set_coupon.php',
				type:'post',
				data:'coupon_str='+coupon_str,
				success:function(result){
					var data=jQuery.parseJSON(result);
					if (data.is_error=='yes') {
						jQuery('#coupon_box').hide();
						jQuery('#coupon_result').html(data.dd);
						jQuery('#order_total_price').html(data.result);
					}
					if (data.is_error=='no') {
						jQuery('#coupon_box').show();
						jQuery('#coupon_price').html(data.dd);
						jQuery('#order_total_price').html(data.result);
					}
				}
			});
		}
	}
</script>


<?php  
	if (isset($_SESSION['COUPON_ID'])) {
		unset($_SESSION['COUPON_ID']);
		unset($_SESSION['COUPON_CODE']);
		unset($_SESSION['COUPON_VALUE']);
	}
	require 'footer.php'; 

?>