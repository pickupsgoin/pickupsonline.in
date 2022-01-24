<?php
	require 'top.php';
?>

<section id="newarrival">
	<div class="container-fluid"><h1>New Arrival</h1></div>
</section>
<section id="products" class="arrival_back">
	<div class="container">
		<div class="row">
			<?php
			$get_product=get_product($con,20);
			foreach($get_product as $list){
			?>
			<div class="col-md-3 arrival_items">
				<div class="inside_col">
					<div class="action_product">
						<a href="javascript:void(0)" onclick="wishlist_manage('<?php echo $list['id'] ?>','add')" class="wishlist"><i class="far fa-heart"></i></a>
						<a href="javascript:void(0)" onclick="manage_cart('<?php echo $list['id'] ?>','add')" class="wishlist"><i class="far fa-plus-square"></i></a>
					</div>
					<a href="product.php?id=<?php echo $list['id']?>">
						<div class="card">
							<img class="card-img" src="<?php echo PRODUCT_IMAGE_SITE_PATH.$list['image']?>" alt="product images">
						</div>						
						<div class="describe">
							<h5><?php echo $list['name']?></h5>
							<p class="describe_more"><i class="fas fa-rupee-sign"></i>&nbsp;<span class="mrp"><?php echo $list['mrp']?></span>&nbsp;<span class="price"><?php echo $list['price']?></span></p>
						</div>
					</a>
				</div>				
			</div>
			<?php } ?>
		</div>
	</div>
</section>
<input type="hidden" id="qty" value="1"/>

<?php
	require 'footer.php';
?>
