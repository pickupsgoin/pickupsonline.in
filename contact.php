<?php
	require 'top.php';

?>

<section id="contactus">
	<div class="container-fluid">
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a class="active" href="contact.php">Contact Us</a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row top-row">
			<div class="col-md-12 left">
				<div class="testho">pickups</div>
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1481.0103453526212!2d73.30883872846363!3d28.010532960708556!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x393fe7882e35da43%3A0x8605a139bb81fc7d!2sOld%20Jail%20Rd%2C%20Old%20Bikaner%2C%20Bikaner%2C%20Rajasthan%20334001!5e0!3m2!1sen!2sin!4v1612244396771!5m2!1sen!2sin" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
			</div>
			<!-- <div class="col-md-6 right">
				<div class="animate"> <p>pickups</p></div>
			</div> -->
		</div>
	</div>
	<div class="container message">
		<div class="row">
			<div class="col">
				<form id="contact-form" method="post">
					<div class="formr1">
						<input type="text" name="name" id="contact_name" placeholder="Your Name" required>
						<input type="email" name="email" id="contact_email" placeholder="Email" required>
						<input type="number" name="mobile_no." id="contact_mobile_no" placeholder="Mobile No." required>	
					</div>
					<div id="contact_error" class="field_error"></div>
					<div class="formr2">
						<textarea name="message" id="message" placeholder="Write here" required></textarea>
					</div>
					<div class="submitbtn">
						<button type="button" onclick="send_message()" name="submit">Send Message</button>
					</div>
				</form>
			</div>
		</div>
	</div>

</section>

<script>
// just for the demos, avoids form submit
jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
});
$( "#contact-form" ).validate({
  rules: {
    contact_name: "required",
    contact_email: "required",
    contact_mobile_no: {
      phoneUS: true,
      required: true
    }
  },
  message:{},
  errorElement : 'div',
  errorLabelContainer: '#contact_error'/*,
  errorPlacement: function(label, element) {
      label.addClass('arrow');
      label.insertBefore(element);
  },
  wrapper: 'span'*/
});




</script>



<?php
	require 'footer.php';
?>