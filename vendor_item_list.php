<?php
	require 'top.php';

	
	$vender_id=$_GET['vender_id'];
	$product_query="select product.*,categories.categories from product,categories where product.categories_id=categories.id and product.status='1' and categories.status='1' and product.added_by='$vender_id' order by product.id desc";

	$product_res=mysqli_query($con,$product_query);
	$product_arr=array();
	while($product_row=mysqli_fetch_assoc($product_res)){
		$product_arr[]=$product_row;
	}


	$seller_res=mysqli_query($con, "select username from admin_users where id='$vender_id' and role=1");
	$seller_row=mysqli_fetch_assoc($seller_res);

?>

<section id="orignals_custom">
	<div class="container-fluid">
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a href="vendor_list.php">+ Sellers</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a class="active"><?php
				if (is_null($seller_row)) {
					echo "pickups more";
				}else{
					echo $seller_row['username'];
				}
				?></a>
			</div>
		</div>
	</div>
<section id="products">
	<div class="container">
		<div class="row">
			<?php if (count($product_arr)>0) { 
				foreach($product_arr as $product_list){
			?>
			<div class="col-md-3">
				<div class="inside_col">
					<div class="action_product">
						<a href="javascript:void(0)" onclick="wishlist_manage('<?php echo $product_list['id'] ?>','add')" class="wishlist"><i class="far fa-heart"></i></a>
						<a href="javascript:void(0)" onclick="manage_cart('<?php echo $product_list['id'] ?>','add')" class="wishlist"><i class="far fa-plus-square"></i></a>
					</div>
					<a href="product.php?id=<?php echo $product_list['id']?>">
						<div class="card">
							<img class="card-img" src="<?php echo PRODUCT_IMAGE_SITE_PATH.$product_list['image']?>" alt="product images">
						</div>						
						<div class="describe">
							<h5><?php echo $product_list['name']?></h5>
							<p class="describe_more"><i class="fas fa-rupee-sign"></i>&nbsp;<span class="mrp"><?php echo $product_list['mrp']?></span>&nbsp;<span class="price"><?php echo $product_list['price']?></span></p>
						</div>
					</a>
				</div>				
			</div>
			<?php } ?>
			<?php }else{
            	echo "Coming Soon";
                } ?>
		</div>
	</div>
</section>
</section>

<?php
	require 'footer.php';
?>