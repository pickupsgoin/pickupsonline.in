<?php
	require 'top.php';
	$resBanner=mysqli_query($con,"select * from banner where status='1' order by order_no asc");

?>

<section id="contactus">
	<div class="container-fluid">
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a class="active" href="offer_banner.php">Offers</a>
			</div>
		</div>
	</div>
</section>
<section id="offer_banner">
	<?php
	if (mysqli_num_rows($resBanner)>0) {
	?>

	<?php while ($rowBanner=mysqli_fetch_assoc($resBanner)){
  	?>
  	<div class="container">
  		<div class="row">
  			<div class="col-xl-5 cols" style="display: flex; justify-content: center; align-items: center;">
				<div class="card">
			      <img class="card-img" src="<?php echo BANNER_SITE_PATH.$rowBanner['image'] ?>">
			   	</div>
			</div>
			<div class="col-xl-7 cols" style="border-left: 2px solid grey;">
				<h3><?php echo $rowBanner['heading1']; ?></h3>
				<h6><?php echo $rowBanner['heading2']; ?></h6>
			</div>
		</div>
	</div>
    <?php } ?>
    <?php
	}else{
		echo "Coming Sooon";
	}
	?>
</section>





<?php
	require 'footer.php';
?>