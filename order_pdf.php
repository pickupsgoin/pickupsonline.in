<?php
	include 'vendor/autoload.php';
	require 'connection.inc.php';
	require 'functions.inc.php';

	if (!$_SESSION['ADMIN_LOGIN']) {
		if (!isset($_SESSION['USER_ID'])) {
			die();
		}
	}


	$order_id=get_safe_value($con,$_GET['id']);
	$coupon_details=mysqli_fetch_assoc(mysqli_query($con,"select coupon_value from `order` where id='$order_id'"));
	$coupon_value=$coupon_details['coupon_value'];
	$total_amount=0;

	/*$css=file_get_contents('bootstrap-4.5.2-dist/css/bootstrap.css');*/
	$css=file_get_contents('styles.css');



	$html='<section id="order_pdf">
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
					<tbody>';
					if (isset($_SESSION['ADMIN_LOGIN'])) {
						$res=mysqli_query($con,"select distinct(order_detail.id),order_detail.*,product.name,product.image from order_detail,product,`order` where order_detail.order_id='$order_id' and order_detail.product_id=product.id");
					}else{
						$uid=$_SESSION['USER_ID'];
						$res=mysqli_query($con,"select distinct(order_detail.id),order_detail.*,product.name,product.image from order_detail,product,`order` where order_detail.order_id='$order_id' and `order`.user_id='$uid' and order_detail.product_id=product.id");
					}
					if (mysqli_num_rows($res)==0) {
						die();
					}
					while ($row=mysqli_fetch_assoc($res)) {
					$total_amount=$total_amount+($row['qty']*$row['price']);
					$html.='<tr>
							<td>'.$row['name'].'</td>
							<td><img style="width: 100px; height: auto;" src="'.PRODUCT_IMAGE_SITE_PATH.$row['image'].'"></td>
							<td>'.$row['qty'].'</td>
							<td>'.$row['price'].'</td>
							<td>'.$row['qty']*$row['price'].'</td>
						</tr>';
					}

					if ($coupon_value!='') {
						$html.='<tr>
							<td colspan="4">Coupon Value</td>
							<td>'.$coupon_value.'</td>
						</tr>';
					}
					$total_amount=$total_amount-$coupon_value;

					$html.='<tr>
							<td colspan="4">Total Amount</td>
							<td>'.$total_amount.'</td>
						</tr>';
					$html.='</tbody>
				</table>
			</div>
		</div>
	</div>
</section>';
	$mpdf=new \Mpdf\Mpdf();
	$mpdf->WriteHTML($css,1);
	$mpdf->WriteHTML($html,2);
	$file=time().'.pdf';
	$mpdf->Output($file,'D');
?>





