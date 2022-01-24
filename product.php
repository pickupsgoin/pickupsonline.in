<?php
ob_start();
require('top.php');
if(isset($_GET['id'])){
	$product_id=get_safe_value($con,$_GET['id']);
	if($product_id>0){
		$get_product=get_product($con,'','',$product_id);
	}else{
		?>
		<script>
		window.location.href='index.php';
		</script>
		<?php
	}

	//multiple images///////////////
	$resMultipleImages=mysqli_query($con,"select * from product_images where product_id='$product_id'");
	$multipleImages=[];
	if (mysqli_num_rows($resMultipleImages)>0) {
		while ($rowMultipleImages=mysqli_fetch_assoc($resMultipleImages)) {
			$multipleImages[]=$rowMultipleImages['product_images'];
		}
	}
	/////////////////////////////////////
	if (isset($_POST['review_submit'])) {
		$rating=get_safe_value($con,$_POST['rating']);
		$review=get_safe_value($con,$_POST['review']);
		$added_on=date('Y-m-d h:i:s');
		mysqli_query($con,"insert into product_review(product_id,user_id,rating,review,status,added_on) values('$product_id','".$_SESSION['USER_ID']."','$rating','$review','1','$added_on')");

		header('location:product.php?id='.$product_id);
		die();
	}

	$product_review_res=mysqli_query($con,"select users.name,product_review.id,product_review.rating,product_review.review,product_review.added_on from users,product_review where product_review.status=1 and product_review.user_id=users.id and product_review.product_id='$product_id' order by product_review.added_on desc");

}else{
	?>
	<script>
	window.location.href='index.php';
	</script>
	<?php
}
?>


<!-- ......................    product detail.................... -->
<div class="modal fade" id="share">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Share to_<i class="fas fa-share" style="transform: rotate(360deg); font-size: 20px; margin-left: 20px; color: #999;"></i></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <a class="facebook" target="_blank" href="https://facebook.com/share.php?u=<?php echo $meta_url?>"><i class="fab fa-facebook-square circle"></i></a>
          <a class="twitter" target="_blank" href="https://twitter.com/share?text=<?php echo $get_product['0']['name']?>&url=<?php echo $meta_url?>"><i class="fab fa-twitter"></i></a>
          <a class="whatsapp" target="_blank" href="https://api.whatsapp.com/send?text=<?php echo urlencode($get_product['0']['name'])?> <?php echo $meta_url?>"><i class="fab fa-whatsapp"></i></a>
          <a class="link" onclick="copyToClipboard('<?php echo $meta_url ?>')"><i class="fas fa-link"></i></a>
          <a class="link er"></a>
        </div>
      </div>
    </div>
</div>

<script>
	function copyToClipboard(text) {
	  var input = document.body.appendChild(document.createElement("input"));
	  input.value = text;
	  input.focus();
	  input.select();
	  document.execCommand('copy');
	  input.parentNode.removeChild(input);
	  jQuery('.er').html('Copied to Clipboard');
	}
</script>




<section id=productdetail>
	<div class="container-fluid">
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a href="categories_home.php">Categories</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a href="categories.php?id=<?php echo $get_product['0']['categories_id']?>"><?php echo $get_product['0']['categories']?></a>&nbsp;&nbsp;>>&nbsp;&nbsp;<span class="active"> <?php echo $get_product['0']['name']?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-2 more_img">
				
				<?php
				if (isset($multipleImages[0])) {
				?>
				
					<?php
						foreach ($multipleImages as $list => $val) {
							echo "<div id='more_product_img'><img src='".PRODUCT_MULTIPLE_IMAGE_SITE_PATH.$val."' onclick=showMultipleImage('".PRODUCT_MULTIPLE_IMAGE_SITE_PATH.$val."')></div>";
						}
					?>
				
				<?php
				}
				?>

				<script>
					function showMultipleImage(im){
						jQuery('#main_img').html("<img src='"+im+"' data-origin='"+im+"'>");
						/*jQuery('.imageZoom').imgZoom();*/
					}
				</script>

			</div>

			<div class="col-lg-6 itemimg imageZoom" id="main_img"><img data-origin="<?php echo PRODUCT_IMAGE_SITE_PATH.$get_product['0']['image'] ?>" src="<?php echo PRODUCT_IMAGE_SITE_PATH.$get_product['0']['image'] ?>" alt="full-image"></div>
			<div class="col-lg-4 p-4" style="background-color: white;">
				<h4><b><?php echo $get_product['0']['name']?></b></h4>
				<i><?php echo $get_product['0']['short_desc']?></i>
				<h4 style="margin: 20px auto; background: #dff9fb; padding: 10px;"><i style="color: crimson;" class="fas fa-rupee-sign"></i>&nbsp; <b style="color: crimson;"><?php echo $get_product['0']['price']?></b>&nbsp; <span class="mrp"><?php echo $get_product['0']['mrp']?></span></h4>
				<!-- <ul class="list-inline small">
                  <li class="list-inline-item m-0"><i class="fa fa-star text-success"></i></li>
                  <li class="list-inline-item m-0"><i class="fa fa-star text-success"></i></li>
                  <li class="list-inline-item m-0"><i class="fa fa-star text-success"></i></li>
                  <li class="list-inline-item m-0"><i class="fa fa-star text-success"></i></li>
                  <li class="list-inline-item m-0"><i class="fa fa-star text-success"></i></li>
                </ul>
 -->
                <div class="social_share_btn"><a data-dismiss="modal" data-toggle="modal" data-target="#share"><i class="fas fa-share-alt"></i><small>Share this</small></a></div>
                <div style="margin-bottom: 15px;"><a style="text-decoration: none;" href="javascript:void(0)" onclick="wishlist_manage('<?php echo $get_product['0']['id'] ?>','add')" class="wishlist"><i class="far fa-heart"></i> <small>Add to Wishlist</small></a></div>

                <?php 
                	$productSoldQtyByProductId=productSoldQtyByProductId($con,$get_product['0']['id']);

                	$pending_qty=$get_product['0']['qty']-$productSoldQtyByProductId;

                	$cart_show='yes';
                	if ($get_product['0']['qty']>$productSoldQtyByProductId) {
                		$stock='<i style="color: green; font-weight: bold;">In Stock</i>';
                	}else{
                		$stock='<i style="color: red; font-weight: bold;">Out of Stock</i>';
                		$cart_show='';
                	}
                ?>
                <b>Availailty : </b> <?php echo $stock; ?><br><br>


                <span style="font-weight: bold">Category :</span><a class="cate" href="categories.php?id=<?php echo $get_product['0']['categories_id']?>"><?php echo $get_product['0']['categories']?></a>

                <?php
                if ($cart_show!='') {
                ?>
                <div class="quantity">
                	Qty :
                	<select id="qty">
                		<?php
                		for ($i=1;$i<=$pending_qty;$i++) { 
                			echo "<option>$i</option>";
                		}
                		?>
                	</select>
                </div>


                <?php 
                $id_cat=$get_product['0']['categories_id'];
                $size_query=mysqli_query($con,"select * from categories where id='$id_cat'");
                $size_result=mysqli_fetch_assoc($size_query);
                if ($size_result['var_size']==1) {
                ?>
                <div class="quantity">
                	Size :
                	<select id="size" required>
                		<option value="M">M</option>
                		<option value="L" selected>L</option>
                		<option value="XL">XL</option>
                		<option value="XXL">XXL</option>
                	</select>
                </div>
            	<?php } ?>


            	<?php } ?>


                <p>**This product is available in all cities of India.**</p>
                <p><b>Seller : </b>&nbsp;&nbsp;&nbsp;<strong><a href="vendor_item_list.php?vender_id=<?php echo $get_product['0']['added_by']?>">
                <?php
                $added_by=$get_product['0']['added_by'];
                $seller_res=mysqli_query($con,"select username,role from admin_users where id='$added_by'");
                $seller_row=mysqli_fetch_assoc($seller_res);
                if($seller_row['role']==0){
                	echo "pickups";
                }else{
                	echo $seller_row['username'];
                }
                ?></a></strong>
                </p><br>
                
                <?php
                if ($cart_show!='') {
                ?>
                <a href="javascript:void(0)" onclick="manage_cart('<?php echo $get_product['0']['id'] ?>','add')" class="addtocart">Add to Cart</a>
                <a href="javascript:void(0)" onclick="manage_cart('<?php echo $get_product['0']['id'] ?>','add','yes')" class="buynow">Buy Now</a>

                <br>
                <?php if ($get_product['0']['categories_id']==1 || $get_product['0']['categories_id']==3) {
                ?>
                <a href="https://wa.me/+917425878384/?text=<?php echo urlencode($get_product['0']['name'])?> <?php echo $meta_url?>" class="modify"><i class="fas fa-wrench" style="color: #273c75"></i>&nbsp;Modify</a>
            	<?php } ?>


                <?php 
            	}else{
            		echo '<i style="color:red;">Available Soon !!</i>';
            	}
            	?>

			</div>
		</div>
		<div class="row rowdescrip">
			<div class="col pl-5 pr-5 descrip">
				<h6><b>Description</b></h6>
				<p><?php echo $get_product['0']['description']?></p>
			</div>
		</div>
		<div class="row rowreview">
			<div class="col">
				<div class="row">
					<div class="col colname"><h5 style="margin-bottom: 0px;">REVIEWS</h5></div>
				</div>
				<div class="row">
					<div class="col">
						<?php
						if (mysqli_num_rows($product_review_res)>0) {
							while ($product_review_row=mysqli_fetch_assoc($product_review_res)) {
						?>
						<div class="reviews">
							<h6><?php echo $product_review_row['rating']; ?> <span>[ <?php echo $product_review_row['name']; ?> ]</span></h6>
							<small style="color: grey">
								<?php
								$added_on=strtotime($product_review_row['added_on']);
								echo date('d M Y',$added_on);
								?>
							</small>
							<p><?php echo $product_review_row['review']; ?></p>
						</div>
						<?php
							}
						}else{
							echo "<div class='reviews'><h6>No Review Found</h6></div>";
						}
						?>
						
						<div class="writereview">
							<h4>Enter Your Review</h4>
							<?php
							if (isset($_SESSION['USER_LOGIN'])) {
							?>
							<form method="post">

								  <div class="feedback">
								    <div class="rating">
								      <input type="radio" name="rating" value="excellent" id="rating-5">
								      <label for="rating-5"></label>
								      <input type="radio" name="rating" value="very good" id="rating-4">
								      <label for="rating-4"></label>
								      <input type="radio" name="rating" value="good" id="rating-3">
								      <label for="rating-3"></label>
								      <input type="radio" name="rating" value="not bad" id="rating-2">
								      <label for="rating-2"></label>
								      <input type="radio" name="rating" value="worst" id="rating-1">
								      <label for="rating-1"></label>
								      <div class="emoji-wrapper">
								        <div class="emoji">
								          <svg class="rating-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								            <circle cx="256" cy="256" r="256" fill="#ffd93b" />
								            <path d="M512 256c0 141.44-114.64 256-256 256-80.48 0-152.32-37.12-199.28-95.28 43.92 35.52 99.84 56.72 160.72 56.72 141.36 0 256-114.56 256-256 0-60.88-21.2-116.8-56.72-160.72C474.8 103.68 512 175.52 512 256z" fill="#f4c534" />
								            <ellipse transform="scale(-1) rotate(31.21 715.433 -595.455)" cx="166.318" cy="199.829" rx="56.146" ry="56.13" fill="#fff" />
								            <ellipse transform="rotate(-148.804 180.87 175.82)" cx="180.871" cy="175.822" rx="28.048" ry="28.08" fill="#3e4347" />
								            <ellipse transform="rotate(-113.778 194.434 165.995)" cx="194.433" cy="165.993" rx="8.016" ry="5.296" fill="#5a5f63" />
								            <ellipse transform="scale(-1) rotate(31.21 715.397 -1237.664)" cx="345.695" cy="199.819" rx="56.146" ry="56.13" fill="#fff" />
								            <ellipse transform="rotate(-148.804 360.25 175.837)" cx="360.252" cy="175.84" rx="28.048" ry="28.08" fill="#3e4347" />
								            <ellipse transform="scale(-1) rotate(66.227 254.508 -573.138)" cx="373.794" cy="165.987" rx="8.016" ry="5.296" fill="#5a5f63" />
								            <path d="M370.56 344.4c0 7.696-6.224 13.92-13.92 13.92H155.36c-7.616 0-13.92-6.224-13.92-13.92s6.304-13.92 13.92-13.92h201.296c7.696.016 13.904 6.224 13.904 13.92z" fill="#3e4347" />
								          </svg>
								          <svg class="rating-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								            <circle cx="256" cy="256" r="256" fill="#ffd93b" />
								            <path d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z" fill="#f4c534" />
								            <path d="M328.4 428a92.8 92.8 0 0 0-145-.1 6.8 6.8 0 0 1-12-5.8 86.6 86.6 0 0 1 84.5-69 86.6 86.6 0 0 1 84.7 69.8c1.3 6.9-7.7 10.6-12.2 5.1z" fill="#3e4347" />
								            <path d="M269.2 222.3c5.3 62.8 52 113.9 104.8 113.9 52.3 0 90.8-51.1 85.6-113.9-2-25-10.8-47.9-23.7-66.7-4.1-6.1-12.2-8-18.5-4.2a111.8 111.8 0 0 1-60.1 16.2c-22.8 0-42.1-5.6-57.8-14.8-6.8-4-15.4-1.5-18.9 5.4-9 18.2-13.2 40.3-11.4 64.1z" fill="#f4c534" />
								            <path d="M357 189.5c25.8 0 47-7.1 63.7-18.7 10 14.6 17 32.1 18.7 51.6 4 49.6-26.1 89.7-67.5 89.7-41.6 0-78.4-40.1-82.5-89.7A95 95 0 0 1 298 174c16 9.7 35.6 15.5 59 15.5z" fill="#fff" />
								            <path d="M396.2 246.1a38.5 38.5 0 0 1-38.7 38.6 38.5 38.5 0 0 1-38.6-38.6 38.6 38.6 0 1 1 77.3 0z" fill="#3e4347" />
								            <path d="M380.4 241.1c-3.2 3.2-9.9 1.7-14.9-3.2-4.8-4.8-6.2-11.5-3-14.7 3.3-3.4 10-2 14.9 2.9 4.9 5 6.4 11.7 3 15z" fill="#fff" />
								            <path d="M242.8 222.3c-5.3 62.8-52 113.9-104.8 113.9-52.3 0-90.8-51.1-85.6-113.9 2-25 10.8-47.9 23.7-66.7 4.1-6.1 12.2-8 18.5-4.2 16.2 10.1 36.2 16.2 60.1 16.2 22.8 0 42.1-5.6 57.8-14.8 6.8-4 15.4-1.5 18.9 5.4 9 18.2 13.2 40.3 11.4 64.1z" fill="#f4c534" />
								            <path d="M155 189.5c-25.8 0-47-7.1-63.7-18.7-10 14.6-17 32.1-18.7 51.6-4 49.6 26.1 89.7 67.5 89.7 41.6 0 78.4-40.1 82.5-89.7A95 95 0 0 0 214 174c-16 9.7-35.6 15.5-59 15.5z" fill="#fff" />
								            <path d="M115.8 246.1a38.5 38.5 0 0 0 38.7 38.6 38.5 38.5 0 0 0 38.6-38.6 38.6 38.6 0 1 0-77.3 0z" fill="#3e4347" />
								            <path d="M131.6 241.1c3.2 3.2 9.9 1.7 14.9-3.2 4.8-4.8 6.2-11.5 3-14.7-3.3-3.4-10-2-14.9 2.9-4.9 5-6.4 11.7-3 15z" fill="#fff" />
								          </svg>
								          <svg class="rating-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								            <circle cx="256" cy="256" r="256" fill="#ffd93b" />
								            <path d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z" fill="#f4c534" />
								            <path d="M336.6 403.2c-6.5 8-16 10-25.5 5.2a117.6 117.6 0 0 0-110.2 0c-9.4 4.9-19 3.3-25.6-4.6-6.5-7.7-4.7-21.1 8.4-28 45.1-24 99.5-24 144.6 0 13 7 14.8 19.7 8.3 27.4z" fill="#3e4347" />
								            <path d="M276.6 244.3a79.3 79.3 0 1 1 158.8 0 79.5 79.5 0 1 1-158.8 0z" fill="#fff" />
								            <circle cx="340" cy="260.4" r="36.2" fill="#3e4347" />
								            <g fill="#fff">
								              <ellipse transform="rotate(-135 326.4 246.6)" cx="326.4" cy="246.6" rx="6.5" ry="10" />
								              <path d="M231.9 244.3a79.3 79.3 0 1 0-158.8 0 79.5 79.5 0 1 0 158.8 0z" />
								            </g>
								            <circle cx="168.5" cy="260.4" r="36.2" fill="#3e4347" />
								            <ellipse transform="rotate(-135 182.1 246.7)" cx="182.1" cy="246.7" rx="10" ry="6.5" fill="#fff" />
								          </svg>
								          <svg class="rating-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								            <circle cx="256" cy="256" r="256" fill="#ffd93b" />
								            <path d="M407.7 352.8a163.9 163.9 0 0 1-303.5 0c-2.3-5.5 1.5-12 7.5-13.2a780.8 780.8 0 0 1 288.4 0c6 1.2 9.9 7.7 7.6 13.2z" fill="#3e4347" />
								            <path d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z" fill="#f4c534" />
								            <g fill="#fff">
								              <path d="M115.3 339c18.2 29.6 75.1 32.8 143.1 32.8 67.1 0 124.2-3.2 143.2-31.6l-1.5-.6a780.6 780.6 0 0 0-284.8-.6z" />
								              <ellipse cx="356.4" cy="205.3" rx="81.1" ry="81" />
								            </g>
								            <ellipse cx="356.4" cy="205.3" rx="44.2" ry="44.2" fill="#3e4347" />
								            <g fill="#fff">
								              <ellipse transform="scale(-1) rotate(45 454 -906)" cx="375.3" cy="188.1" rx="12" ry="8.1" />
								              <ellipse cx="155.6" cy="205.3" rx="81.1" ry="81" />
								            </g>
								            <ellipse cx="155.6" cy="205.3" rx="44.2" ry="44.2" fill="#3e4347" />
								            <ellipse transform="scale(-1) rotate(45 454 -421.3)" cx="174.5" cy="188" rx="12" ry="8.1" fill="#fff" />
								          </svg>
								          <svg class="rating-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								            <circle cx="256" cy="256" r="256" fill="#ffd93b" />
								            <path d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z" fill="#f4c534" />
								            <path d="M232.3 201.3c0 49.2-74.3 94.2-74.3 94.2s-74.4-45-74.4-94.2a38 38 0 0 1 74.4-11.1 38 38 0 0 1 74.3 11.1z" fill="#e24b4b" />
								            <path d="M96.1 173.3a37.7 37.7 0 0 0-12.4 28c0 49.2 74.3 94.2 74.3 94.2C80.2 229.8 95.6 175.2 96 173.3z" fill="#d03f3f" />
								            <path d="M215.2 200c-3.6 3-9.8 1-13.8-4.1-4.2-5.2-4.6-11.5-1.2-14.1 3.6-2.8 9.7-.7 13.9 4.4 4 5.2 4.6 11.4 1.1 13.8z" fill="#fff" />
								            <path d="M428.4 201.3c0 49.2-74.4 94.2-74.4 94.2s-74.3-45-74.3-94.2a38 38 0 0 1 74.4-11.1 38 38 0 0 1 74.3 11.1z" fill="#e24b4b" />
								            <path d="M292.2 173.3a37.7 37.7 0 0 0-12.4 28c0 49.2 74.3 94.2 74.3 94.2-77.8-65.7-62.4-120.3-61.9-122.2z" fill="#d03f3f" />
								            <path d="M411.3 200c-3.6 3-9.8 1-13.8-4.1-4.2-5.2-4.6-11.5-1.2-14.1 3.6-2.8 9.7-.7 13.9 4.4 4 5.2 4.6 11.4 1.1 13.8z" fill="#fff" />
								            <path d="M381.7 374.1c-30.2 35.9-75.3 64.4-125.7 64.4s-95.4-28.5-125.8-64.2a17.6 17.6 0 0 1 16.5-28.7 627.7 627.7 0 0 0 218.7-.1c16.2-2.7 27 16.1 16.3 28.6z" fill="#3e4347" />
								            <path d="M256 438.5c25.7 0 50-7.5 71.7-19.5-9-33.7-40.7-43.3-62.6-31.7-29.7 15.8-62.8-4.7-75.6 34.3 20.3 10.4 42.8 17 66.5 17z" fill="#e24b4b" />
								          </svg>
								          <svg class="rating-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								            <g fill="#ffd93b">
								              <circle cx="256" cy="256" r="256" />
								              <path d="M512 256A256 256 0 0 1 56.8 416.7a256 256 0 0 0 360-360c58 47 95.2 118.8 95.2 199.3z" />
								            </g>
								            <path d="M512 99.4v165.1c0 11-8.9 19.9-19.7 19.9h-187c-13 0-23.5-10.5-23.5-23.5v-21.3c0-12.9-8.9-24.8-21.6-26.7-16.2-2.5-30 10-30 25.5V261c0 13-10.5 23.5-23.5 23.5h-187A19.7 19.7 0 0 1 0 264.7V99.4c0-10.9 8.8-19.7 19.7-19.7h472.6c10.8 0 19.7 8.7 19.7 19.7z" fill="#e9eff4" />
								            <path d="M204.6 138v88.2a23 23 0 0 1-23 23H58.2a23 23 0 0 1-23-23v-88.3a23 23 0 0 1 23-23h123.4a23 23 0 0 1 23 23z" fill="#45cbea" />
								            <path d="M476.9 138v88.2a23 23 0 0 1-23 23H330.3a23 23 0 0 1-23-23v-88.3a23 23 0 0 1 23-23h123.4a23 23 0 0 1 23 23z" fill="#e84d88" />
								            <g fill="#38c0dc">
								              <path d="M95.2 114.9l-60 60v15.2l75.2-75.2zM123.3 114.9L35.1 203v23.2c0 1.8.3 3.7.7 5.4l116.8-116.7h-29.3z" />
								            </g>
								            <g fill="#d23f77">
								              <path d="M373.3 114.9l-66 66V196l81.3-81.2zM401.5 114.9l-94.1 94v17.3c0 3.5.8 6.8 2.2 9.8l121.1-121.1h-29.2z" />
								            </g>
								            <path d="M329.5 395.2c0 44.7-33 81-73.4 81-40.7 0-73.5-36.3-73.5-81s32.8-81 73.5-81c40.5 0 73.4 36.3 73.4 81z" fill="#3e4347" />
								            <path d="M256 476.2a70 70 0 0 0 53.3-25.5 34.6 34.6 0 0 0-58-25 34.4 34.4 0 0 0-47.8 26 69.9 69.9 0 0 0 52.6 24.5z" fill="#e24b4b" />
								            <path d="M290.3 434.8c-1 3.4-5.8 5.2-11 3.9s-8.4-5.1-7.4-8.7c.8-3.3 5.7-5 10.7-3.8 5.1 1.4 8.5 5.3 7.7 8.6z" fill="#fff" opacity=".2" />
								          </svg>
								        </div>
								      </div>
								    </div>
								  </div>
								

<!-- 
								<select name="rating" required>
									<option></option>
									<option>Select Rating</option>
									<option>Select Rating</option>
								</select><br>
 -->								<textarea name="review" required></textarea>
								<button type="submit" name="review_submit">Submit</button>
							</form>
							<?php
							}else{
								echo "Please <a style='color:red; cursor:pointer;' data-dismiss='modal' data-toggle='modal' data-target='#myModal'>Login</a> to submit your review";
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php 
		//unset($_COOKIE['recently_viewed']);
		/*$_COOKIE['recently_viewed']=$product_id;*/
		if (isset($_COOKIE['recently_viewed'])) {
			$arrRecentView=unserialize($_COOKIE['recently_viewed']);
			$countRecentView=count($arrRecentView);
			$countStartRecentView=$countRecentView-4;
			
			if ($countRecentView>4) {
				$arrRecentView=array_slice($arrRecentView,$countStartRecentView,4);	
			}
			$recentViewId=implode(",",$arrRecentView);
			
			$res=mysqli_query($con,"select product.*,categories.categories from product,categories where product.id IN ($recentViewId) and product.status=1 and categories.status=1 and product.categories_id=categories.id order by $recentViewId desc");
		?>
		<div class="row rowdescrip" style="background: #fff;">
			<div class="col pl-5 descrip">
				<h4 style="text-align: center; border-bottom: 1px solid #111;padding: 8px;"><b>Recent Viewed</b></h4>
				<section id="products">
				<div class="container">
				<div class="row">
				<?php while ($list=mysqli_fetch_assoc($res)) { ?>
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
				</div>
				</div>
				</section>
			</div>
		</div>
		<?php 
			$arrRec=unserialize($_COOKIE['recently_viewed']);
			if (($key=array_search($product_id, $arrRec))!==false) {
				unset($arrRec[$key]);
			}
			$arrRec[]=$product_id;
			setcookie('recently_viewed',serialize($arrRec),time()+60*60*24);

		}else{
			$arrRec[]=$product_id;
			setcookie('recently_viewed',serialize($arrRec),time()+60*60*24);
		}
		?>
	</div>
</section>
	



<?php
	require 'footer.php';
ob_flush();
?>