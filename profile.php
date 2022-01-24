<?php
	require 'top.php';

	if (!isset($_SESSION['USER_LOGIN'])) {
		?>
		<script>
			window.location.href="index.php";
		</script>
		<?php
	}
?>

<section id="my_profile">
	<div class="container-fluid">
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a class="active" href="profile.php">My Profile</a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-6 left">
				<h3>My Profile</h3>
				<div class="lable">Your Name :)</div>
				<form method="post">
					<input type="text" name="update_name" id="update_name" placeholder="Your Name*" value="<?php echo $_SESSION['USER_NAME'] ?>" required>
					<div class="field_error" id="update_name_error"></div>
					
					<button class="btn_profile" type="button" onclick="update_profile()" name="btn_update_name" id="btn_update_name">Update</button>
				</form>


				<div class="lable">Change Password :)</div>
				<form method="post" id="formPassword">
					<label><small>Current Password</small></label>
					<input type="password" name="current_password" id="current_password" required>
					<div class="field_error" id="current_password_error"></div>

					<label><small>New Password</small></label>
					<input type="password" name="new_password" id="new_password" required>
					<div class="field_error" id="new_password_error"></div>

					<label><small>Confirm New Password</small></label>
					<input type="password" name="confirm_new_password" id="confirm_new_password" required>
					<div class="field_error" id="confirm_new_password_error"></div>
					
					<button class="btn_profile" type="button" onclick="update_password()" name="btn_update_password" id="btn_update_password">Update</button>
				</form>
				<div class="field_error" id="password_error"></div>
			</div>
			<div class="col-md-6"></div>
		</div>
	</div>
</section>



<?php
	require 'footer.php';
?>