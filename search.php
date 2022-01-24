<?php
	require 'top.php';

	$str=mysqli_real_escape_string($con,$_GET['str']);
	if ($str!=='') {

		$sort_order="";

		$price_high_selected="";
		$price_low_selected="";
		$new_selected="";
		$old_selected="";


		if (isset($_GET['sort'])) {
			$sort=get_safe_value($con,$_GET['sort']);
			if ($sort=="price_high") {
				$sort_order=" order by product.price desc ";
				$price_high_selected="selected";
			}
			if ($sort=="price_low") {
				$sort_order=" order by product.price asc ";
				$price_low_selected="selected";
			}
			if ($sort=="new") {
				$sort_order=" order by product.id desc ";
				$new_selected="selected";
			}
			if ($sort=="old") {
				$sort_order=" order by product.id asc ";
				$old_selected="selected";
			}
		}

	    $get_product=get_product($con,'','','',$str,$sort_order);
	}else{
	?>
	    <script>
	        window.location.href='index.php';
	    </script>
	<?php
	}
?>


<section id="products">
	<div class="container-fluid">
		
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a>Search</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<span class="active"><?php echo $str; ?></span>
			</div>
		</div>
		<?php if (count($get_product)>0) { ?>
		<div class="row">
			<div class="col">
				<select class="sorting" onchange="sort_product_drop_search('<?php echo $str ?>','<?php echo SITE_PATH ?>')" id="sort_product_id">
					<option value="">Default sorting</option>
					<option value="price_low" <?php echo $price_low_selected; ?>>Sort by price low to high</option>
					<option value="price_high" <?php echo $price_high_selected; ?>>Sort by price high to low</option>
					<option value="new" <?php echo $new_selected; ?>>Sort by New First</option>
					<option value="old" <?php echo $old_selected; ?>>Sort by Old First</option>
				</select>
			</div>
		</div>

	</div>
	<div class="container">
		<div class="row">
			<?php 
				foreach($get_product as $list){
			?>
			<div class="col-md-3">
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
			<?php }else{
            	?>
            	<div class="container">
            		<div class="row">
            			<div class="col bg-light p-5">
            				<img style="width: 200px; height: auto;" src="images/e27813e577548baadaa53ad737b6a5cd.gif">
            				<h4>Sorry No result found</h4>
            			</div>
            		</div>
            	</div>
            	<?php
                } ?>
		</div>
	</div>
</section>


<?php
	require 'footer.php';
?>