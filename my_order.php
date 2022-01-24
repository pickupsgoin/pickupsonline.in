<?php
	require 'top.php';
?>


<section id="my_orders">
	<div class="container-fluid">
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a class="active" href="my_order.php">My Orders</a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col">
				<table>
					<thead>
						<tr>
							<th>Order ID</th>
							<th>Order Date</th>
							<th>Address</th>
							<th class="payment_size">Payment Type</th>
							<th>Payment Status</th>
							<th>Order Status</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$uid=$_SESSION['USER_ID'];
						$res=mysqli_query($con,"select `order`.*,order_status.name as order_status_str from `order`,order_status where `order`.user_id='$uid' and order_status.id=`order`.order_status order by id desc");
						while ($row=mysqli_fetch_assoc($res)) {
						?>
						<tr>
							<td><a href="my_order_detail.php?id=<?php echo $row['id']?>&order_place_id=<?php echo $row['order_place_id']?>"><?php echo $row['order_place_id'] ?></a>
								<br/><a href="order_pdf.php?id=<?php echo $row['id']?>">PDF</a>
							</td>
							<td><?php echo $row['added_on'] ?></td>
							<td class="address_font"><?php echo $row['address'] ?><br>
								<?php echo $row['city'] ?><br>
								<?php echo $row['pincode'] ?>
							</td>
							<td class="payment_size"><?php echo $row['payment_type'] ?></td>
							<td><?php echo $row['payment_status'] ?></td>
							<td><?php echo $row['order_status_str'] ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>


<?php
	require 'footer.php';
?>