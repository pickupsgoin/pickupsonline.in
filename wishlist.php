<?php
	require 'top.php';

	if (!isset($_SESSION['USER_LOGIN'])) {
		?>
		<script>
			window.location.href="index.php";
		</script>
		<?php
	}

	$uid=$_SESSION['USER_ID'];

	if (isset($_GET['wishlist_id']) && $_GET['wishlist_id']!='') {
		$wishlist_id=get_safe_value($con,$_GET['wishlist_id']);
		$delete_sql="delete from wishlist where user_id='$uid',product_id='$wishlist_id'";
		mysqli_query($con, $delete_sql);

	}


	$res=mysqli_query($con,"select product.id,product.name,product.image,product.price from product,wishlist where wishlist.product_id=product.id and wishlist.user_id='$uid'");

?>

<section id="addtocart">
	<div class="container-fluid">
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a class="active" href="">Wishlist</a>
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
						<th>NAME OF PRODUCTS</th>
						<th>PRICE</th>
						<th>REMOVE</th>
					</tr>
					</thead>
					<tbody>
						<?php
							while ($row=mysqli_fetch_assoc($res)) {
						?>
						<tr>
						<td><div class="card"><a href="product.php?id=<?php echo $row['id'] ?>"><img class="card-img" src="<?php echo PRODUCT_IMAGE_SITE_PATH.$row['image'] ?>"></a></div></td>
						<td><div><a href="product.php?id=<?php echo $row['id'] ?>"><?php echo $row['name']; ?></a></div></td>
						<td><div><?php echo $row['price']; ?></div></td>
						<td><div><a href="wishlist.php?wishlist_id=<?php echo $row['id'] ?>"><i class="far fa-trash-alt"></i></a></div></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row action">
			<div class="col-12">
				<a class="conti_shop" href="<?php echo SITE_PATH ?>">Continue Shoppping</a>
			</div>
		</div>
	</div>
</section>


<?php  
	require 'footer.php'; 

?>