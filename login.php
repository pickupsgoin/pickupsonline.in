<?php
	require 'top.php';
	if (isset($_SESSION['USER_LOGIN']) && $_SESSION['USER_LOGIN']=='yes') {
?>

<script>
	window.location.href='index.php';
</script>

<?php
	}
?>

<section id="login">
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="login">
				<div class="title">LOGIN</div>
				<form method="post">
					<input type="text" name="login_email" id="login_email" placeholder="Your Email*" required>
					<div class="field_error" id="login_email_error"></div>
					<input type="password" name="login_password" id="login_password" placeholder="Your Password" required>
					<div class="field_error" id="login_password_error"></div>
					<button type="button" onclick="user_login()" name="login">Login</button>
				</form>
				<div id="login_msg"><p></p></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="register">
				<div class="title">REGISTER</div>
				<form method="post">
					<input type="text" name="name" id="name" placeholder="Your Name*" required>
					<div class="field_error" id="name_error"></div>
					<input type="email" name="email" id="email" placeholder="Your Email*" required>
					<div class="field_error" id="email_error"></div>
					<input type="text" name="mobile_no" id="mobile_no" placeholder="Your Mobile No.*" required>
					<div class="field_error" id="mobile_no_error"></div>
					<input type="password" name="password" id="password" placeholder="Your Password*" required>
					<div class="field_error" id="password_error"></div>
					<button type="button" onclick="user_register()" name="register">Register</button>
				</form>
				<div id="register_msg"><p></p></div>
			</div>
		</div>
	</div>
</div>
</section>



<?php
	require 'footer.php';
?>