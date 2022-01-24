<?php
	require 'top.php';
?>

<section id="addtocart">
	<div class="container-fluid">
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a class="active" href="cart.php">Your Cart</a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row top-row">
			<div class="col">
				<table>
					<thead>
					<tr>
						<th>PRODUCTS</th>
						<th class="user_main">NAME OF PRODUCTS</th>
						<th>PRICE</th>
						<th>QUANTITY</th>
						<th>TOTAL</th>
						<th>REMOVE</th>
					</tr>
					</thead>
					<tbody>
						<?php
							if (isset($_SESSION['cart'])) {
								foreach ($_SESSION['cart'] as $key => $val) {
									$productArr=get_product($con,'','',$key);
									$pid=$productArr[0]['id'];
									$pname=$productArr[0]['name'];
									$mrp=$productArr[0]['mrp'];
									$price=$productArr[0]['price'];
									$image=$productArr[0]['image'];
									$qty=$val['qty'];
									$size=$val['size'];
						?>
						<tr>
						<td><div class="card"><a href="product.php?id=<?php echo $pid ?>"><img class="card-img" src="<?php echo PRODUCT_IMAGE_SITE_PATH.$image ?>"><b class="cart_pname user_temp"><?php echo $pname; ?></b></a></div></td>
						<td class="user_main"><div><a href="product.php?id=<?php echo $pid ?>"><?php echo $pname; ?></a></div></td>
						<td><div><?php echo $price; ?></div></td>
						<td><div class="quantity">
			                	<input type="number" id="<?php echo $key?>qty" value="<?php echo $qty?>"/>
			                	<a href="javascript:void(0)" onclick="manage_cart('<?php echo $key?>','update')">update</a>
			                </div></td>
						<td><div><?php echo $qty*$price ?></div></td>
						<td><div><a href="javascript:void(0)" onclick="manage_cart('<?php echo $key?>','remove')"><i class="far fa-trash-alt"></i></a></div></td>
						</tr>
						<?php } } ?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row action">
			<div class="col-12">
				<a class="conti_shop" href="<?php echo SITE_PATH ?>">Continue Shoppping</a>
				<a class="checkout" href="<?php echo SITE_PATH ?>checkout.php">Checkout</a>
			</div>
		</div>
	</div>
</section>


<?php  
	require 'footer.php'; 

?>