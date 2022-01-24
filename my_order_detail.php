<?php
	require 'top.php';
	if (!isset($_SESSION['USER_LOGIN'])) {
		?>
		<script>
			window.location.href='index.php';
		</script>
		<?php
	}
	$order_id=get_safe_value($con,$_GET['id']);
	$total_amount=0;
	$coupon_value=0;


	$remain_order_details=mysqli_fetch_assoc(mysqli_query($con,"select coupon_value,order_place_id from `order` where id='$order_id'"));
	$order_place_id=$remain_order_details['order_place_id'];
	$coupon_value=$remain_order_details['coupon_value'];
?>

<section id="my_orders">
	<div class="container-fluid">
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a href="my_order.php">My Orders</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a href="my_order_detail.php" class="active"><?php echo $order_place_id; ?></a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col">
				<table>
					<thead>
						<tr>
							<th>Product Name</th>
							<th>Product Image</th>
							<th>Qty</th>
							<th>Price</th>
							<th>Total Price</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$uid=$_SESSION['USER_ID'];
						$res=mysqli_query($con,"select distinct(order_detail.id),order_detail.*,product.name,product.image from order_detail,product,`order` where order_detail.order_id='$order_id' and `order`.user_id='$uid' and product.id=order_detail.product_id");
						while ($row=mysqli_fetch_assoc($res)) {
						$total_amount=$total_amount+($row['qty']*$row['price']);
						?>
						<tr>
							<td><?php echo $row['name'] ?></td>
							<td><img class="my_order_d" src="<?php echo PRODUCT_IMAGE_SITE_PATH.$row['image'] ?>"></td>
							<td><?php echo $row['qty'] ?></td>
							<td><?php echo $row['price'] ?></td>
							<td><?php echo $row['qty']*$row['price'] ?></td>
						</tr>
						<?php } 
						if ($coupon_value!=0) {
						?>
						<tr>
							<td colspan="4">Coupon Value</td>
							<td><?php echo $coupon_value; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td colspan="4">Total Amount</td>
							<td><?php echo $total_amount-$coupon_value; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>


<?php
	require 'footer.php';
?>