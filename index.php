<?php
	require 'top.php';
	$active='';

	$resBanner=mysqli_query($con,"select * from banner where status='1' order by order_no asc");

	$bannercount=mysqli_query($con,"select * from banner where status='1' order by order_no asc");
?>



<!-- /////////////////////slider.........banner///////////////////////// -->

<?php
if (mysqli_num_rows($resBanner)>0) {
?>

<section id="slider_banner">
<div class="container-fluid">
	<div id="silder" class="carousel slide" data-ride="carousel" data-interval="2500">
	  <ol class="carousel-indicators">
	  	<?php while ($rowBanner2=mysqli_fetch_assoc($bannercount)){
	  		if ($rowBanner2['order_no']==1) {
				$active="active";
			}else{
				$active='';
			}
	  	?>
	  		 <li data-target="#silder" data-slide-to="1" class="<?php echo $active; ?>"></li>
	  	<?php }?>
		<!-- <li data-target="#silder" data-slide-to="0" class="active"></li>
	    <li data-target="#silder" data-slide-to="1"></li>
	    <li data-target="#silder" data-slide-to="2"></li> -->
	  </ol>
	  <div class="carousel-inner">
	    
	  	<?php while ($rowBanner=mysqli_fetch_assoc($resBanner)){
	  	if ($rowBanner['order_no']==1) {
			$active="active";
		}else{
			$active='';
		}
	  	?>
	  	<div class="carousel-item <?php echo $active; ?>">
	      <a href="<?php echo $rowBanner['btn_link'] ?>"><img class="d-block w-100" src="<?php echo BANNER_SITE_PATH.$rowBanner['image'] ?>"></a>
	    </div>
	    <?php } ?>


	  </div>
	  <a class="carousel-control-prev" href="#silder" role="button" data-slide="prev">
	    <i class="fas fa-angle-double-left"></i>
	    <span class="sr-only">Previous</span>
	  </a>
	  <a class="carousel-control-next" href="#silder" role="button" data-slide="next">
	    <i class="fas fa-angle-double-right"></i>
	    <span class="sr-only">Next</span>
	  </a>
	</div>
</div>

</section>

<?php
}
?>

<!--......................seller qoute//////...............----->
	<div class="container-fluid">
		<div class="row qoute">
			<div class="col">"Something SPECIAL for Someone SPECIAL"</div>
		</div>
	</div>


<!-- .......................... index attr......................... -->
<section id="indexattr">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-4 top_row">
				<a href="new_arrival.php">
					<div class="container newarrival">
						<h2>New Arrival</h2>
						<div class="row">
							<?php
							$get_product=get_product($con,4);
							foreach($get_product as $list){
							?>
							<div class="col set_one m-0"><div class="card"><img class="card-img" src="<?php echo PRODUCT_IMAGE_SITE_PATH.$list['image']?>"></div>
							<small><?php echo $list['categories'] ?></small></div>
							<?php } ?>
						</div>
					</div>
				</a>
			</div>
			<div class="col-xl-3 top_row">
				
					<div class="container newarrival">
						<h2>Memorable Gift Items</h2>
						<div class="row gift_row" style="padding: 10px 0px;">
							<a href="categories.php?id=1">
							<?php
								$get_product=get_product($con,1,1,'','','');
									foreach($get_product as $list){
							?>
								<div class="col"><div class="card gifts"><img class="card-img" src="<?php echo PRODUCT_IMAGE_SITE_PATH.$list['image']?>"></div>
								<?php echo $list['categories'] ?></div>
							
							<?php } ?>
							</a>
							<a href="categories.php?id=3">
							<?php
								$get_product=get_product($con,1,3,'','','');
									foreach($get_product as $list){
							?>
								<div class="col"><div class="card gifts"><img class="card-img" src="<?php echo PRODUCT_IMAGE_SITE_PATH.$list['image']?>"></div>
								<?php echo $list['categories'] ?></div>
							
							<?php } ?>
							</a>
						</div>
					</div>
				
			</div>
			<div class="col-xl-5 order-first order-xl-0 top_row">			
				<div class="container newarrival trending_items">
					<h2>Trendings</h2>
					<h5>People LOVED these, Hope you also like</h5>
					<div class="row" style="padding: 15px 0px;">
						<?php
						$get_product=get_product($con,6,'','','','','yes');
						foreach($get_product as $list){
						?>
							
							<div class="col-sm-4 trend_col mb-4">
								<a href="product.php?id=<?php echo $list['id']?>">
									<div class="card"><img class="card-img" src="<?php echo PRODUCT_IMAGE_SITE_PATH.$list['image']?>"></div>
								</a>
							<small><?php echo $list['name'] ?></small>
							</div>
							
						
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8">
				<div class="container newarrival">
					<h2 class="heading1">Categories</h2>
					<?php
                        foreach ($cat_arr as $list) {
                    ?>
					<section class="cats_index">
						<a href="categories.php?id=<?php echo $list['id']?>">
							<div class="row">
								<div class="col-md-4 cat_name"><h4><?php echo $list['categories']?></h4></div>
								<?php
									$get_product=get_product($con,3,$list['id']);
									foreach($get_product as $list){
								?>
								<div class="col-md-2 card"><img class="card-img" src="<?php echo PRODUCT_IMAGE_SITE_PATH.$list['image']?>"></div>
								<?php } ?>
							</div>
						</a>
					</section>
				<?php } ?>
				</div>
			</div>
			<div class="col-md-4 google_ads">
				<a href="categories_home.php">
					<div class="container newarrival">
						<h2 class="heading1">Google Ads</h2>
					</div>
				</a>
			</div>
		</div>
		<div class="row base">
			<div class="col-md-12">
				<a class="tag" href="categories_home.php">
					Happy Shopping Journey
				</a>
			</div>
		</div>
	</div>
</section>







<!-- ..............................key point.................. -->
<section id="feature">
<div class="container-fluid">
		<div class="row">
			<div class="col-md-4">
				<i class="fas fa-truck feature"></i><br><br>
				<h5><b>Delivery</b></h5>
				<p>
				Deliver within 7-8 working days.
				We are improving Delivery time day by day.
				*Cash on Delivery available on Above 499 in Outer Bikaner*</p>
			</div>
			<div class="col-md-4">
				<i class="fas fa-lock feature"></i><br><br>
				<h5><b>Secure Payments</b></h5>
				<p>
				Online payment mode are secured.
				So many types of online payment available.</p>
			</div>
			<div class="col-md-4">
				<i class="fas fa-undo-alt feature"></i><br><br>
				<h5><b>Return</b></h5>
				<p>
				Easy Return.
				5 Days Return policy</p>
			</div>
		</div>
	</div>
</section>



<?php 
 require 'footer.php';
?>