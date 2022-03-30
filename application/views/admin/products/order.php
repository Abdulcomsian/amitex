<section id="content" class="product-page-main">
	<div class="container">
		<div class="cat_navigation">
			<button class="cat-navbar-toggler">
	           <span class="cat-toggler-icon"></span>
	        </button>
	        <div class="car_navigation">
	        	<button class="cat_nav_cloce glyphicon glyphicon-remove"></button>
	        	<ul class="cat_list">
	        		<?php if(!empty($categories['data']['records'])) { foreach($categories['data']['records'] as $value) { 
	        			$is_subcategory_exist = FALSE;
	        			if(!empty($this->input->get('subcategory_guid')) && in_array($this->input->get('subcategory_guid'), array_column($value['subcategories'],'subcategory_guid'))){
	        				$is_subcategory_exist = TRUE;
	        			}
	        		?>
	        			<li>
		        			<a href="javascript:void(0);"><?php echo $value['category_name']; ?> (<?php echo addZero(array_sum(array_column($value['subcategories'],'products_count'))); ?>)</a>
		        			<?php if(!empty($value['subcategories'])) { ?>
			        			<ul class="sub_cat_list">
			        				<?php foreach($value['subcategories'] as $subcategory) { ?>
			        					<li class="<?php if(!empty($this->input->get('subcategory_guid')) && $this->input->get('subcategory_guid') == $subcategory['subcategory_guid']) {echo "active";} ?>"><a href="?subcategory_guid=<?php echo $subcategory['subcategory_guid']; ?>"><?php echo $subcategory['subcategory_name']; ?> (<?php echo addZero($subcategory['products_count']); ?>)</a></li>
			        				<?php } ?>
			        			</ul>
		        			<?php } ?>
		        		</li>
	        		<?php } } ?>
	        	</ul>
	        </div>
		</div>

        <div class="makeNewOrder">
			<div class="t-header">
	            <div class="th-title"><?php echo lang('start_order'); ?> (<?php echo $client_name; ?>)</div>
	        </div>

			<div class="pro_slider_main">
				
				<?php if($products['data']['total_records'] > 0) { foreach($products['data']['records'] as $product) { ?>
				<div class="product_item" data-product-guid="<?php echo $product['product_guid']; ?>">
					<div class="row">
						<div class="col-md-6">
							<div class="product-info forMobile">
								<h2 class="product_name"><?php echo $product['product_name']; ?></h2>
								<p><?php echo $product['category_name']; ?> -> <?php echo $product['subcategory_name']; ?></p>
							</div>
							<div class="pro_slider_singleimg lightbox" id="image-wrapper">
								<a class="main-img" href="<?php echo $product['product_main_photo']; ?>">
                                  <img class="main-img" src="<?php echo $product['product_main_photo']; ?>" alt="product-img">
                                </a>
                                <?php foreach($product['product_gallery_images'] as $image) { ?>
                                	<a class="col-sm-2 col-xs-6 hidden" href="<?php echo base_url(); ?>uploads/products/<?php echo $image; ?>">
	                                  <img src="<?php echo base_url(); ?>uploads/products/<?php echo $image; ?>" alt="product-img">
	                                </a>
	                            <?php } ?>
								<!-- <img class="main-img" src="<?php echo $product['product_main_photo']; ?>" alt=""> -->
							</div>
							<div class="pro_image_slider">
								<!-- <a class="col-sm-2 col-xs-6" href="<?php echo $product['product_main_photo']; ?>">
                                  <img src="<?php echo $product['product_main_photo']; ?>" alt="product-img">
                                </a> -->
								<figure class="slide_img" data-product-name="<?php echo $product['product_name']; ?>" data-product-gallery-images='<?php echo json_encode($product['product_gallery_images']); ?>'>
									<img src="<?php echo $product['product_main_photo']; ?>" alt="product main image">
								</figure>
								<?php foreach($product['product_gallery_images'] as $image) { ?>
									<!-- <a class="col-sm-2 col-xs-6" href="javascript:void(0);">
	                                  <img src="<?php echo base_url(); ?>uploads/products/<?php echo $image; ?>" alt="product-img">
	                                </a> -->
									<figure class="slide_img" data-product-name="<?php echo $product['product_name']; ?>" data-product-gallery-images='<?php echo json_encode($product['product_gallery_images']); ?>'>
										<img src="<?php echo base_url(); ?>uploads/products/<?php echo $image; ?>" alt="product image">
									</figure>
								<?php } ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="product-info forDesktop">
								<h2 class="product_name"><?php echo $product['product_name']; ?></h2>
								<p><?php echo $product['category_name']; ?> -> <?php echo $product['subcategory_name']; ?></p>
							</div>
							<div class="content_main">
								<div class="content_block">
			                        <h4 ><?php echo lang('product_descprition'); ?></h4>
			                        <p><?php echo $product['product_descprition']; ?></p>
		                        </div>
		                        <!-- <button class="btn_expand readmore">Read more...</button> -->

		                        <div class="table_main">
		                        	<div class="table-responsive">
			                        	<table class="table table-bordered table-striped">
			                                <thead>
			                                    <tr>
			                                        <th><?php echo lang('size'); ?><br><?php echo lang('color'); ?></th>
			                                        <?php foreach($product['size_variants'] as $size) { ?>
			                                        	<th><?php echo $size; ?></th>	
			                                        <?php } ?>
			                                        <!-- <th>Carton</th> -->
			                                    </tr>
			                                </thead>
			                                <tbody>
			                                	<?php $varinat_ids = array(); foreach($product['color_variants'] as $color) {?>
			                                		<tr>
				                                        <th><?php echo $color; ?></th>
				                                        <?php $i = 0; foreach($product['product_varinats_prices'] as $varinat_price) { if($varinat_price['color_variant'] != $color) {continue;} 
				                                        	if($varinat_price['in_stock'] == 1){
				                                        		$varinat_ids[$i][] = $varinat_price['product_variant_id'];
				                                        	}
				                                       ?>
				                                        	<td>
				                                        		<?php if($varinat_price['in_stock']==1){ ?>
				                                        		<strong style="width:30%"><?php echo CURRENCY_SYMBOL.getProductDiscountPrice($varinat_price['product_price'],$product['is_premium'],(($product['product_brand'] == 'Amitex') ? $percent_reduction_amitex :  $percent_reduction_michal)); ?></strong>&nbsp;&nbsp;<input style="width:70px" type="number" data-product-guid="<?php echo $product['product_guid']; ?>" data-product-variant-id="<?php echo $varinat_price['product_variant_id']; ?>" class="product-variant" name="product_variant_id[<?php echo $varinat_price['product_variant_id']; ?>][]" value="0" min="0">
				                                        	<?php }else{ echo '<strong style="width:30%;color:red">'.lang('out_of_stock').'</strong>'; } ?>
				                                        	</td>
				                                        <?php $i++; } ?>
				                                    </tr>
			                                	<?php } ?>
			                                	<tr>
			                                    	<th>All Colors</th>
			                                    	<?php $j = 0 ;foreach($product['product_varinats_prices'] as $varinat_price) { if($varinat_price['color_variant'] != $color) {continue;} ?>
			                                        	<td></strong>&nbsp;&nbsp;<input style="width:70px" type="number" class="product-variant-all-colors" data-product-guid="<?php echo $product['product_guid']; ?>" data-product-variant-ids='<?php echo json_encode($varinat_ids[$j++], JSON_UNESCAPED_UNICODE); ?>' value="0" min="0"></td>
			                                        <?php } ?>
			                                    </tr>
			                                </tbody>
			                            </table>
			                            <div class="" tabindex="0"></div>
			                        </div>
		                        </div>
		                    </div>
						</div>
					</div>	
				</div>
				<?php } } else{ ?>
					<div class="alert alert-danger" role="alert">
	                    <?php echo lang('products_not_found'); ?>
	                </div>
				<?php } ?>
			</div>

			<div class="addedProduct">
				<div class="alert alert-info cart-message animate__bounceInDown <?php if(empty($order_data['product_data'])) { echo "hidden"; }?>" role="alert" style="cursor:pointer;">
					<?php if(!empty($order_data['product_data'])) { ?>
		            	<i class="zmdi zmdi-shopping-cart zmdi-hc-fw"></i> <?php echo $order_data['total_products']; ?> <?php echo lang('product_added_into_cart'); ?> (<?php echo lang('cart_total_amount'); ?> <?php echo CURRENCY_SYMBOL.$order_data['total_cart_amount']; ?>)
		            <?php } ?>
		        </div>
		        <button class="btn btn-danger <?php if(empty($order_data['product_data'])) { echo "hidden"; }?>" onclick="showConfirmationBox('<?php echo lang('are_you_sure'); ?>','<?php echo lang('are_you_sure_clear_shopping_cart'); ?>','<?php echo lang('yes'); ?>','<?php echo lang('no'); ?>','../../products/clear_cart')" title="<?php echo lang('clear_cart'); ?>"><?php echo lang('clear_cart'); ?></button>
		    </div>
		</div>
	</div>

	<script>
		$('.pro_slider_main').slick({
		    rtl: true
		});
	</script>
</section>
