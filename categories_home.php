<?php

	require 'top.php';
?>

<section id="categories_home">
<section id="cat_home">
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<img src="images/offer_short4.png">
			</div>
		</div>
	</div>
</section>

<section id="products">
	<div class="container-fluid">
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a class="active" href="categories_home.php">Categories</a>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="container">
					<div class="row">
						
						<?php
	                        foreach ($cat_arr as $list) {
	                    ?>
						<div class="col-md-6">
							<a href="categories.php?id=<?php echo $list['id']?>">
								<div class="inside">
									<?php echo $list['categories']?>
								</div>
							</a>
						</div>
						<?php
	                        }
	                    ?>

					</div>
				</div>
			</div>
		</div>
	</div>
</section>

</section>

<?php
	require 'footer.php';
?>