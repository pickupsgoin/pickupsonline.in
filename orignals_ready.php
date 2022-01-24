<?php
	require 'top.php';

	
	$get_product=get_product($con,'',5,'','','');
	
?>

<section id="orignals_ready">
	<div class="container-fluid">
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a href="orignals.php">Orignals</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a class="active" href="orignals_custom.php">Wooden Decoratives</a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<?php if (count($get_product)>0) { 
				foreach($get_product as $list){
			?>
			<div class="col-md-4 prod">
				<a href="product.php?id=<?php echo $list['id']?>">
					<div>
						<h5><?php echo $list['name']?></h5>
						<small id="like">Show more detail about this</small>
						<span class="hide"><h4>Like This!!</h4>
						<i class="fas fa-heart"></i></span>
					</div>
					<div class="card">
						<img class="card-img" src="<?php echo PRODUCT_IMAGE_SITE_PATH.$list['image']?>">
					</div>
				</a>
			</div>
			<?php } ?>
			<?php }else{
            	echo "Coming Soon";
                } ?>
		</div>
	</div>
</section>

<?php
	require 'footer.php';
?>