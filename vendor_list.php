<?php
	require 'top.php';
?>

<section id="orignals">
	<div class="container-fluid">
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a class="active" href="vendor_list.php">+ Sellers</a>
			</div>
		</div>
		<div class="row cat">
			<div class="container">
				<?php
		        $seller_res=mysqli_query($con, "select id,username,admin_dp from admin_users where role=1 limit 0,5");
		        $seller_arr=array();
		        while ($seller_row=mysqli_fetch_assoc($seller_res)) {
		            $seller_arr[]=$seller_row;
		        }
		        foreach ($seller_arr as $vender) {
		        ?>
						<a class="cat1" href="vendor_item_list.php?vender_id=<?php echo $vender['id']?>">
							<div class="row">
								<div class="col-md-4">
									<h2><?php echo $vender['username']?></h2>
								</div>
				        <?php
				        if ($vender['admin_dp']!='') {
				        ?>
				          <div class="col-md-2 card _hide"><img class="card-img" src="<?php echo VENDER_SITE_PATH.$vender['admin_dp'] ?>"></div>
				      		</div>
				        </a>
				        <?php
				        }else{
				        ?>
				          <div class="col-md-2 card _hide"><strong style="color: #111;"><?php echo substr($vender['username'],0,1); ?></strong></div>
				      		</div>
				        </a>
				        <?php
				        }
		        }
		        ?>
				
			</div>
		</div>
	</div>

</section>


<?php
	require 'footer.php';
?>