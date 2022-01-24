<?php
	require 'top.php';
?>

<section id="orignals">
	<div class="container-fluid">
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a class="active" href="orignals.php">Orignals</a>
			</div>
		</div>
		<div class="row cat">
			<div class="container">
				<a href="orignals_ready.php" class="cat1">
					<div class="row">
						<div class="col-md-4"><h2>Wooden Decoratives</h2></div>
						<?php
							$get_product=get_product($con,3,5);
							foreach($get_product as $list){
						?>
						<div class="col-md-2 card _hide"><img class="card-img" src="<?php echo PRODUCT_IMAGE_SITE_PATH.$list['image']?>"></div>
						<?php } ?>
					</div>
				</a>
				<a href="orignals_custom.php" class="cat2">
					<div class="row">
						<div class="col-md-4"><h2>Wooden NameTags</h2></div>
						<?php
							$get_product=get_product($con,3,6);
							foreach($get_product as $list){
						?>
						<div class="col-md-2 card _hide"><img class="card-img" src="<?php echo PRODUCT_IMAGE_SITE_PATH.$list['image']?>"></div>
						<?php } ?>
					</div>
				</a>
			</div>
		</div>
	</div>

</section>


<?php
	require 'footer.php';
?>