<?php
	require 'connection.inc.php';
	require 'functions.inc.php';
	require 'add_to_cart.inc.php';

	$cat_res=mysqli_query($con, "select * from categories where status=1 order by categories asc");
	$cat_arr=array();
	while ($row=mysqli_fetch_assoc($cat_res)) {
	    $cat_arr[]=$row;
	}

$obj=new add_to_cart();
$totalProduct=$obj->totalProduct();



if (isset($_SESSION['USER_LOGIN'])) {

	$uid=$_SESSION['USER_ID'];
	if(isset($_GET['wishlist_id'])){
		$wid=get_safe_value($con,$_GET['wishlist_id']);
		mysqli_query($con,"delete from wishlist where id='$wid' and user_id='$uid'");
	}

	$wishlist_count=mysqli_num_rows(mysqli_query($con,"select product.id,product.name,product.image,product.price,wishlist.id from product,wishlist where wishlist.product_id=product.id and wishlist.user_id='$uid'"));
}

//===================================meta deatil=============================

$script_name=$_SERVER['SCRIPT_NAME'];
$script_name_arr=explode('/',$script_name);
$mypage=$script_name_arr[count($script_name_arr)-1];

$meta_title="pickups";
$meta_desc="pickups";
$meta_keyword="pickups";
$meta_image="images/meta_image.png";
$meta_url=SITE_PATH;

if($mypage=='product.php'){
	$product_id=get_safe_value($con,$_GET['id']);
	$product_meta=mysqli_fetch_assoc(mysqli_query($con,"select * from product where id='$product_id'"));
	$meta_title=$product_meta['meta_title'];
	$meta_desc=$product_meta['meta_desc'];
	$meta_keyword=$product_meta['meta_keyword'];
	$meta_url=SITE_PATH."product.php?id=".$product_id;
	$meta_image=PRODUCT_IMAGE_SITE_PATH.$product_meta['image'];
}


if($mypage=='categories.php'){
	$cat_id=get_safe_value($con,$_GET['id']);
	$cat_res_name=mysqli_fetch_assoc(mysqli_query($con,"select * from categories where id='$cat_id'"));
	$meta_title=$cat_res_name['categories'];
}
if($mypage=='orignals.php'){
	$meta_title="Orignals";
}
if($mypage=='our_story.php'){
	$meta_title="Our Story";
}
if($mypage=='contact.php'){
	$meta_title="Contact Us";
}

//=========================================================================

?>



<!DOCTYPE html>
<html>
<head>
	<title><?php echo $meta_title; ?></title>
	<meta charset="utf-8">
	<meta name="description" content="<?php echo $meta_desc; ?>">
	<meta name="keywords" content="<?php echo $meta_keyword; ?>">

	<meta property="og:title" content="<?php echo $meta_title ?>"/>
	<meta property="og:image" content="<?php echo $meta_image ?>"/>
	<meta property="og:url" content="<?php echo $meta_url ?>"/>
	<meta property="og:site_name" content="<?php echo SITE_PATH ?>"/>

	<link rel="stylesheet" type="text/css" href="bootstrap-4.5.2-dist/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="fontawesome-free-5.15.1-web/css/all.css">
	<script type="text/javascript" src="js/jquery-3.5.1.js"></script>
	<script type="text/javascript" src="bootstrap-4.5.2-dist/js/bootstrap.js"></script>
	<link rel="stylesheet" type="text/css" href="styles.css">
	<link rel="stylesheet" type="text/css" href="responsive.css">


  <script type="text/javascript" src="js/jquery.validate.min.js"></script>
  <script type="text/javascript" src="js/additional-methods.min.js"></script>

	<!-- .....................mainattr....................... -->
	<script type="text/javascript" src="./mainatt/magic.js"></script>
	<link rel="stylesheet" type="text/css" href="mainatt/style.css">
	<!-- ..................................................... -->

	<!-- ===============================extra fonts======================= -->
	<!-- <link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet"> -->


</head>



<body onload="preload()">

<!-- <div id=loading>
  <div style="padding-top: 110px; font-weight: bold;">Loading..</div>
</div> -->




<!-- ////////////////////////////header////////////////////////////// -->
<div class="bg"></div>
<div class="bg bg2"></div>
<div class="bg bg3"></div>


<div class="scroll-up-btn">
	<i class="fas fa-angle-up"></i>
</div>


<!-- ===========================Login/Register================================== -->
<!-- ===========================Login/Register================================== -->
<section id="login">
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      	<div class="container-fluid">
      		<div class="row">
      			<div class="col-12 login">
			        <div class="modal-header">
			        	<h4 class="modal-title title">LOGIN</h4>
			        	<h6 style="font-size: 15px; color: #999; margin-left: 20px; align-items: baseline!important; cursor: pointer;" class="modal-title title"><a style="text-decoration: none;" data-dismiss="modal" data-toggle="modal" data-target="#register">Register</a></h6>
			          	<button type="button" class="close" data-dismiss="modal">&times;</button>
			        </div>
		          	<div class="modal-body">
			          	<form method="post">
							<input type="email" name="login_email" id="login_email" placeholder="Your Email*" required>
							<div class="field_error" id="login_email_error"></div>
							<input type="password" autocomplete="on" name="login_password" id="login_password" placeholder="Your Password" required>
							<div class="field_error" id="login_password_error"></div>
							<button class="go_on" type="button" onclick="user_login()" name="login">Login</button>
						</form>
						<div id="login_msg"><p></p></div>
					</div>
					<div><a class="login_other_options" data-dismiss="modal" data-toggle="modal" data-target="#forgot_pass_menu">Forgot password ?</a></div>
					<div><a class="login_other_options" data-dismiss="modal" data-toggle="modal" data-target="#register">Don't have account/New Regsitration</a></div>
        		</div>
        	</div>
        </div>
      </div>
      
    </div>
  </div>
</section>



<!-- /////////////////////////////// forgot password ///////////////////////// -->
<section id="login">
  <div class="modal fade" id="forgot_pass_menu" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      	<div class="container-fluid">
      		<div class="row">
      			<div class="col-12 login">
			        <div class="modal-header">
			        	<h4 class="modal-title title">Forgot Password</h4>
			          	<button type="button" class="close" data-dismiss="modal">&times;</button>
			        </div>
		          	<div class="modal-body">
			          	<form method="post">
							<input type="email" name="fp_email" id="fp_email" placeholder="Your Email*" required>
							<div class="field_error" id="fp_email_error"></div>
							
							<button class="go_on" type="button" onclick="forgot_password()"  id="btn_forgotpss_submit">Submit</button>
						</form>
						<div id="login_msg"><p></p></div>
					</div>
					<div><a class="login_other_options" data-dismiss="modal" data-toggle="modal" data-target="#myModal">Login</a></div>
					<div><a class="login_other_options" data-dismiss="modal" data-toggle="modal" data-target="#register">Regsitration</a></div>
        		</div>
        	</div>
        </div>
      </div>
      
    </div>
  </div>
</section>








<section id="login">
  <div class="modal fade" id="register" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      	<div class="container-fluid">
      		<div class="row">
      			<div class="col-12 register">
			        <div class="modal-header">
			        	<h6 style="font-size: 15px; color: #999; margin-right: 20px; align-items: baseline!important; cursor: pointer;" class="modal-title title"><a style="text-decoration: none;" data-dismiss="modal" data-toggle="modal" data-target="#myModal">Login</a></h6>
			        	<h4 class="modal-title title">REGISTER</h4>
			          	<button type="button" class="close" data-dismiss="modal">&times;</button>
			        </div>
		          	<div class="modal-body">
			          <form id="regis_form" method="post">
    							<input type="text" name="name" id="name" placeholder="Your Name*" required>
    							<div class="field_error" id="name_error"></div>

    							<input type="email" name="email" id="email" placeholder="Your Email*" required>
    							<!-- <button class="email_sent_otp" type="button" onclick="email_sent_otp()">Send OTP</button> -->

    							<!-- <input type="text" style="width: 55%" id="email_otp" placeholder="OTP" class="email_verify_otp" required>
    							<button class="go_on email_verify_otp" style="padding: 8px 18px" type="button" onclick="email_verify_otp()">Verify OTP</button> -->

    							<!-- <span id="email_otp_result"></span> -->

    							<div class="field_error" id="email_error"></div>


    							<input type="text" name="mobile_no" id="mobile_no" placeholder="Your Mobile No.*" required>
    							<!-- <button class="go_on mobile_sent_otp" style="padding: 8px 18px" type="button" onclick="mobile_sent_otp()">Send OTP</button>

    							<input type="text" style="width: 55%" id="mobile_otp" placeholder="OTP" class="mobile_verify_otp" required>
    							<button class="go_on mobile_verify_otp" style="padding: 8px 18px" type="button" onclick="mobile_verify_otp()">Verify OTP</button>

    							<span id="mobile_otp_result"></span> -->

    							<div class="field_error" id="mobile_no_error"></div>


    							<input type="password" autocomplete="on" name="password" id="password" placeholder="Your Password*" required>
    							<div class="field_error" id="password_error"></div>

                  <input type="text" id="email_otp" placeholder="OTP" class="email_verify_otp" required>

    							<button class="go_on enable" type="button" onclick="email_sent_otp()" name="register" id="btn_register">Register</button>
    						</form>
    						<div id="register_msg">
    							<p></p>
    							<a class="login_other_options" data-dismiss="modal" data-toggle="modal" data-target="#myModal">Login Here</a>
    						</div>
    					</div>
        		</div>
        	</div>
        </div>
      </div>
      
    </div>
  </div>

  <input type="hidden" id="is_email_verified"/>
  <input type="hidden" id="is_mobile_verified"/>


<script>
// just for the demos, avoids form submit
jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
});
$( "#regis_form" ).validate({
  rules: {
    name: "required",
    password: "required",
    mobile_no: {
      required: true,
      phoneUS: true
    }
  },
  errorPlacement: function(label, element) {
      label.addClass('arrow');
      label.insertAfter(element);
  },
  success: function(label,element) {
      label.parent().removeClass('error');
      label.remove(); 
  },
  wrapper: 'span'
    /*errorElement : 'div',
    errorLabelContainer: '#register_msg'*/
});
</script>


<script>
	function forgot_password(){
  		jQuery('#email_error').html('');
  		var fp_email=jQuery('#fp_email').val();
  		if (fp_email=='') {
  			jQuery('#fp_email_error').html('Please enter email id');
  		}else{
  			jQuery('#btn_forgotpss_submit').html('Please Wait..');
  			jQuery('#btn_forgotpss_submit').attr('disabled',true);
  			jQuery.ajax({
  				url:'forgot_password_submit.php',
  				type:'post',
  				data:'fp_email='+fp_email,
  				success:function(result){
  					var fp_email=jQuery('#fp_email').val('');
  					jQuery('#fp_email_error').html(result);
  					jQuery('#btn_forgotpss_submit').html('Submit');
  					jQuery('#btn_forgotpss_submit').attr('disabled',false);
  				}
  			});
  		}
  	}


  	function email_sent_otp(){
  		jQuery('#email_error').html('');
  		var email=jQuery('#email').val();
  		if (email=='') {
  			jQuery('#email_error').html('Please enter email id');
  		}else{
  			jQuery('#btn_register').html('Please Wait...');
        jQuery('#btn_register').attr('disabled',true);
  			$('#btn_register').removeClass("enable");
  			jQuery('.email_sent_otp');
  			jQuery.ajax({
  				url:'send_otp.php',
  				type:'post',
  				data:'email='+email+'&type=email',
  				success:function(result){
  					if (result=='done') {
  						jQuery('#email').attr('disabled',true);
              $("#btn_register").attr("onclick","email_verify_otp()");
              /*$('.email_sent_otp').addClass("disable");*/
	  					jQuery('.email_verify_otp').show();
              jQuery('#btn_register').attr('disabled',false);
              jQuery('#btn_register').html('Verify Email');
              $('#btn_register').addClass("enable");
              /*jQuery('.email_sent_otp').hide();*/
  					}else if(result=='email_present'){
  						jQuery('#btn_register').html('Register');
              $('#btn_register').addClass("enable");
  						jQuery('#btn_register').attr('disabled',false);
  						jQuery('#email_error').html('Email Already Exists');			
  					}else{
              jQuery('#btn_register').html('Register');
              jQuery('#btn_register').attr('disabled',false);
              $('#btn_register').addClass("enable");
  						/*jQuery('.email_sent_otp').html('Send OTP');
  						jQuery('.email_sent_otp').attr('disabled',false);*/
  						jQuery('#email_error').html('Please try after sometime');
  					}	
  				}
  			});
  		}
  	}
  	function email_verify_otp(){
  		jQuery('#email_error').html('');
  		var email_otp=jQuery('#email_otp').val();
  		if (email_otp=='') {
  			jQuery('#email_error').html('Please enter OTP');
  		}else{
  			jQuery.ajax({
  				url:'check_otp.php',
  				type:'post',
  				data:'otp='+email_otp+'&type=email',
  				success:function(result){
  					if (result=='done') {
              jQuery('.email_verify_otp').hide();
              jQuery('#btn_register').html('Register');
              user_register();
  						/*jQuery('#email_otp_result').html('Email id verified');*/
  						/*jQuery('#is_email_verified').val('1');
  						if (jQuery('#is_email_verified').val()==1) {*/
                                      							/*if (jQuery('#is_email_verified').val()==1) {
                                      								/////////iski jagah #is_mobile_verified hoga*/
  							/*jQuery('#btn_register').attr('disabled',false);
  							$('#login .row .register .go_on').addClass("enable");
  						}*/
  					}else{
  						jQuery('#register_msg p').html('Please Enter valid OTP');			
  					}	
  				}
  			});
  		}
  	}

    

  	/*function mobile_sent_otp(){
  		jQuery('#mobile_no_error').html('');
  		var mobile=jQuery('#mobile_no').val();
  		if (mobile=='') {
  			jQuery('#mobile_no_error').html('Please enter mobile number');
  		}else{
  			jQuery('.mobile_sent_otp').html('Please Wait...');
  			jQuery('.mobile_sent_otp').attr('disabled',true);
  			jQuery('.mobile_sent_otp');
  			jQuery.ajax({
  				url:'send_otp.php',
  				type:'post',
  				data:'mobile='+mobile+'&type=mobile',
  				success:function(result){
  					if (result=='done') {
  						jQuery('#mobile_no').attr('disabled',true);
	  					jQuery('.mobile_verify_otp').show();
	  					jQuery('.mobile_sent_otp').hide();
  					}else if(result=='mobile_present'){
  						jQuery('.mobile_sent_otp').html('Send OTP');
  						jQuery('.mobile_sent_otp').attr('disabled',false);
  						jQuery('#mobile_no_error').html('Mobile Number Already Exists');			
  					}else{
  						jQuery('.mobile_sent_otp').html('Send OTP');
  						jQuery('.mobile_sent_otp').attr('disabled',false);
  						jQuery('#mobile_no_error').html('Please try after sometime');			
  					}	
  				}
  			});
  		}
  	}
  	function mobile_verify_otp(){
  		jQuery('#mobile_no_error').html('');
  		var mobile_otp=jQuery('#mobile_otp').val();
  		if (mobile_otp=='') {
  			jQuery('#mobile_no_error').html('Please enter OTP');
  		}else{
  			jQuery.ajax({
  				url:'check_otp.php',
  				type:'post',
  				data:'otp='+mobile_otp+'&type=mobile',
  				success:function(result){
  					if (result=='done') {
  						jQuery('.mobile_verify_otp').hide();
  						jQuery('#mobile_otp_result').html('Mobile Number verified');
  						jQuery('#is_mobile_verified').val('1');
  						if (jQuery('#is_email_verified').val()==1) {
  							jQuery('#btn_register').attr('disabled',false);
  						}
  					}else{
  						jQuery('#mobile_no_error').html('Please Enter valid OTP');			
  					}	
  				}
  			});
  		}
  	}*/
</script>



</section>

<?php
	/*echo '<pre>';
	print_r($_SESSION);
	die();*/
?>






<!-- ========================================================================= -->
<!-- ========================================================================= -->


<section id="top">
	<nav class="headtop navbar navbar-expand-md"><!-- <div class="container"> -->
		<a class="navbar-brand" href="index.php"><img class="disabledownload" src="images/logo.png"></a>

		<div id="slogan_slide">
			<div class="triangle"></div> We pickup for You!!
		</div>
			


		<button class="navbar-toggler bar" data-toggle="collapse" data-target=#menu_target >
			<i class="fas fa-bars"></i>
		</button>




		<div class="collapse navbar-collapse menu_check" id="menu_target">
			<ul class="navbar-nav  ml-md-auto">

				<li class="nav-item user_temp">
					<?php if (isset($_SESSION['USER_LOGIN'])) {
						$uname=$_SESSION['USER_NAME'];
					?>
					<a href="profile.php" class="nav-link login" style="font-weight: bold">Hey,
					<?php
						$uname=strtok($uname,' ');
						echo $uname;
					?>
					</a>
					<?php
					}
					?>
				</li>

				<?php if (!isset($_SESSION['USER_LOGIN']) && isset($_SESSION['USER_LOGIN'])=='') {
				echo '<a class="nav-link login user_temp" data-toggle="modal" data-target="#myModal">Login / Register</a>';
				}
				?>
				<li class="nav-item">
					<a class="nav-link" href="index.php">Home</a>
				</li>
				
				<li class="nav-item user_temp">
					<?php if (isset($_SESSION['USER_LOGIN'])) {
						echo '<a class="nav-link logout" href="my_order.php">My Order</a>';
					}
					?>
				</li>

				<li class="nav-item dropdown">
					<a class="nav-link" id="cate"  aria-haspopup="true" aria-expanded="false" href="categories_home.php">All Categories</a>
					<div class="dropdown-menu" aria-labelledby="cate">
						<div class="container-fluid p-0">
							<div class="row p-0">
								<div class="col-6">
									<?php
				                        foreach ($cat_arr as $list) {
				                    ?>
			                        	<a class="dropdown-item testing" href="categories.php?id=<?php echo $list['id']?>"><?php echo $list['categories']?></a>

			                        	<!-- ======= sub categories ======= -->

				                    <?php
				                        }
				                    ?>
	                			</div>
	                			<div class="col-6">
	                				<div class="temp_div">
	                					<div class="sub_dropdown" style="float:left;">
        										  <div class="sub_dropdown-content">
        										    <!-- <strong>Hope<br>you<br>like<br>these!!</strong> -->
                                <img src="images/giphy.gif" class="disabledownload" onmousedown="return false" style="width: 100px; height: auto; margin-left: 25px;">
        										  </div>
        										</div>
	                				</div>
	                			</div>
	                		</div>
	                	</div>
	                </div>

	            </li>
	            <li class="nav-item dropdown extra_option">
					<a class="nav-link" id="raam"  aria-haspopup="true" aria-expanded="false" href="vendor_list.php">+ Sellers</a>
					<div class="dropdown-menu" aria-labelledby="raam">
            <?php
            $seller_res=mysqli_query($con, "select id,username,admin_dp from admin_users where role=1 limit 0,5");
            $seller_arr=array();
            while ($seller_row=mysqli_fetch_assoc($seller_res)) {
                $seller_arr[]=$seller_row;
            }
            foreach ($seller_arr as $vender) {
            ?>
						<a class="dropdown-item vender_list" href="vendor_item_list.php?vender_id=<?php echo $vender['id']?>"><?php echo $vender['username']?>
            <?php
            if ($vender['admin_dp']!='') {
            ?>
              <div class="card seller_logo"><img class="card-img" src="<?php echo VENDER_SITE_PATH.$vender['admin_dp'] ?>"></div>
              </a>
            <?php
            }else{
            ?>
              <div class="card seller_logo"><strong style="color: #111;"><?php echo substr($vender['username'],0,1); ?></strong></div>
              </a>
            <?php
            }
            }
            ?>
            <small style="float: right;padding: 5px;"><a style="color: #999;" href="vendor_list.php">show more</a></small>
					</div>
				</li>

				<li class="nav-item">
					<a class="nav-link" href="contact.php">Contact Us</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="our_story.php">Our Story</a>
				</li>
				<li class="nav-item">
					<?php if (isset($_SESSION['USER_LOGIN'])) {
						echo '<a class="nav-link logout" href="logout.php">Logout</a>';
					}else{
						echo '<a class="nav-link login user_main" data-toggle="modal" data-target="#myModal">Login / Register</a>';
					}
					?>

				</li>
				<li class="nav-item user_main">
					<?php if (isset($_SESSION['USER_LOGIN'])) {
						echo '<a class="nav-link logout" href="my_order.php">My Order</a>';
					}
					?>
				</li>
				<li class="nav-item user_main">
					<?php if (isset($_SESSION['USER_LOGIN'])) {
						$uname=$_SESSION['USER_NAME'];
					?>
					<a href="profile.php" class="nav-link login" style="font-weight: bold">Hey,
					<?php
						$uname=strtok($uname,' ');
						echo $uname;
					?>
					</a>
					<?php
					}
					?>
				</li>
			</ul>			
		</div>

			<?php if (!isset($_SESSION['USER_LOGIN']) && isset($_SESSION['USER_LOGIN'])=='') {
				echo '<div class="dropdown cart-btn sear_ch2" style="margin-left: 3px;">';
			}else{
				echo '<div class="dropdown cart-btn sear_ch" style="margin-left: 3px;">';	
			}
			?>
			
			  <button class="search_btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="fas fa-search"></i>
			  </button>
			  <div class="search_bar dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
			    <form action="search.php" method="get" autocomplete="off">
			    	<input type="text" name="str" placeholder="What you want?">
			    	<button type="submit" name="submit"><i class="fas fa-search"></i></button>
			    </form>
			  </div>
			</div>


			<?php
			if (isset($_SESSION['USER_ID'])) {
			?>
			<div class="cart">
				<a href="wishlist.php" class="cart-btn wish_list"><i class="far fa-heart"></i>
					<span class="wishlist_num"><?php echo $wishlist_count; ?></span>
				</a>
			</div>
			<?php }?>


			<div class="cart">
				<a href="cart.php" class="cart-btn"><i class="fas fa-truck-loading"></i>
					<span class="cart_num"><?php echo $totalProduct; ?></span>
				</a>
			</div>
			
	<!-- </div> -->
	</nav>
</section>

<script>
(function($){
  $(document).on('contextmenu', '.disabledownload', function() {
      return false;
  })
})(jQuery);
</script>

