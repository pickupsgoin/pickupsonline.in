<?php
	
	require 'top.php';

	if(!isset($_GET['id']) && $_GET['id']!=''){
		?>
		<script>
		window.location.href='index.php';
		</script>
		<?php
	}

	$cat_id=get_safe_value($con,$_GET['id']);
	$sort_order="";

	$price_high_selected="";
	$price_low_selected="";
	$new_selected="";
	$old_selected="";


	if (isset($_GET['sort'])) {
		$sort=get_safe_value($con,$_GET['sort']);
		if ($sort=="price_high") {
			$sort_order=" order by product.price desc ";
			$price_high_selected="selected";
		}
		if ($sort=="price_low") {
			$sort_order=" order by product.price asc ";
			$price_low_selected="selected";
		}
		if ($sort=="new") {
			$sort_order=" order by product.id desc ";
			$new_selected="selected";
		}
		if ($sort=="old") {
			$sort_order=" order by product.id asc ";
			$old_selected="selected";
		}
	}

	if ($cat_id>0) {
		$per_page=12;
		$start=0;
		$current_page=1;
		if (isset($_GET['start'])) {
			$start=$_GET['start'];
			if ($start<=0) {
				$start=0;
				$current_page=1;
			}else{
				$current_page=$start;
				$start--;
				$start=$start*$per_page;
			}
		}
		$record=count(get_product($con,'',$cat_id,'','',$sort_order));
		$pagi=ceil($record/$per_page);
		$limit="$start,$per_page";

		$get_product=get_product($con,$limit,$cat_id,'','',$sort_order);

		

	}else{
	?>
	    <script>
	        window.location.href='index.php';
	    </script>
	<?php
	}
?>

<section id="cat_home">
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<img src="images/offer_short4.png">
			</div>
		</div>
	</div>
</section>
<?php if (count($get_product)>0) {  ?>
<section id="products">
	<div class="container-fluid">
		<div class="row topui">
			<div class="col">
				<a href="index.php">HOME</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a href="categories_home.php">Categories</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<a class="active" href="categories.php?id=<?php echo $get_product['0']['categories_id']?>"><?php echo $get_product['0']['categories']?></a>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<select class="sorting" onchange="sort_product_drop('<?php echo $cat_id ?>','<?php echo SITE_PATH ?>')" id="sort_product_id">
					<option value="">Default sorting</option>
					<option value="price_low" <?php echo $price_low_selected; ?>>Sort by price low to high</option>
					<option value="price_high" <?php echo $price_high_selected; ?>>Sort by price high to low</option>
					<option value="new" <?php echo $new_selected; ?>>Sort by New First</option>
					<option value="old" <?php echo $old_selected; ?>>Sort by Old First</option>
				</select>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<?php  
				foreach($get_product as $list){
			?>
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

<section id="pagination_section">
	<div class="con_div">
		<ul class="pagination">
			<?php
			if ($start<=0) {
			?>
				<li class="page-ite previous_page"><a class="page-link" href="javascript:void(0)"><?php echo "pre" ?></a></li>
			<?php
			}else{
			?>
				<li class="page-item previous_page"><a class="page-link" href="?id=<?php echo $get_product['0']['categories_id']?>&start=<?php echo $current_page-1 ?>"><?php echo "pre" ?></a></li>
			<?php
			}
			?>


			<?php
			for ($i=1; $i<=$pagi ; $i++) { 
				$class='';
				if ($current_page==$i) {
					?>
					<li class="page-item active"><a class="page-link" href="javascript:void(0)"><?php echo $i ?></a></li>
					<?php
				}else{
			?>
			<li class="page-item"><a class="page-link" href="?id=<?php echo $get_product['0']['categories_id']?>&start=<?php echo $i?>"><?php echo $i ?></a></li>
			<?php
				}
			} 
			?>



			<?php
			if ($current_page==$pagi) {
			?>
				<li class="page-item next_page"><a class="page-link" href="javascript:void(0)"><?php echo "next" ?></a></li>
			<?php
			}else{
			?>			
			<li class="page-item next_page"><a class="page-link" href="?id=<?php echo $get_product['0']['categories_id']?>&start=<?php echo $current_page+1 ?>"><?php echo "next" ?></a></li>
			<?php
			}
			?>

		</ul>
  	</div>
</section>

		
	<?php }else{
	?>
    <!-- <script>
        window.location.href='index.php';
    </script> -->
    <h1>No Data Found</h1>
	<?php
    } ?>

<input type="hidden" id="qty" value="1"/>
<?php
	require 'footer.php';
?>