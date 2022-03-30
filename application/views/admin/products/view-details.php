<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                <div class="t-header">
                        <div class="th-title"><span class="zmdi zmdi-view-web zmdi-hc-fw" aria-hidden="true"></span> <?php echo lang('view_product_details'); ?>
                        <a href="<?php echo base_url(); ?>admin/products/list" class="btn btn-primary pull-right admin-right-btn"><?php echo lang('back_to_product_list'); ?></a>
                    </div>
                    <br/>
                    <div class="current_games_section">
                          <table id="example" class="table">
                            <tr>
                                <td><?php echo lang('product_item_number'); ?></td>
                                <td><?php echo $details['product_item_code']; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('product_name'); ?></td>
                                <td><?php echo $details['product_name']; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('category'); ?></td>
                                <td><?php echo $details['category_name']; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('subcategory'); ?></td>
                                <td><?php echo $details['subcategory_name']; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('is_premium'); ?></td>
                                <td><?php echo $details['is_premium']; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('product_descprition'); ?></td>
                                <td><?php echo $details['product_descprition']; ?></td>
                            </tr>
                            <!-- <tr>
                                <td><?php echo lang('color_variants'); ?></td>
                                <td>
                                    <?php if(!empty($details['color_variants'])) { 
                                        foreach($details['color_variants'] as $color){
                                            echo '<span class="badge badge-pill badge-success">'.$color.'</span>&nbsp;';
                                        }
                                    } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo lang('size_variants'); ?></td>
                                <td>
                                    <?php if(!empty($details['size_variants'])) { 
                                        foreach($details['size_variants'] as $size){
                                            echo '<span class="badge badge-pill badge-success">'.$size.'</span>&nbsp;';
                                        }
                                    } ?>
                                </td>
                            </tr> -->
                            <tr>
                                <td><?php echo lang('created_date'); ?></td>
                                <td style="width:1200px;"><?php echo convertDateTime($details['created_date']); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('main_photo'); ?></td>
                                <td><div class="lightbox row"><a href="<?php echo $details['product_main_photo']; ?>" class="col-sm-2 col-xs-6"><img src="<?php echo $details['product_main_photo']; ?>" alt="product-img"></a></div></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('gallery_images'); ?></td>
                                <td>
                                    <div class="lightbox row">
                                    <?php if(!empty($details['product_gallery_images'])){ foreach ($details['product_gallery_images'] as $value) { ?>
                                        <a class="col-sm-2 col-xs-6" href="<?php echo base_url().'uploads/products/'.$value; ?>">
                                            <img src="<?php echo base_url().'uploads/products/'.$value; ?>" alt="product-img">
                                        </a>
                                    <?php } } ?>
                                    </div>
                                </td>
                            </tr>
                          </table>

                            <div class="product_item">
                                <div class="table_main">
                                    <div class="table-responsive">
                                        <?php //echo '<pre>'; print_r($product_variants['data']['records']); echo '</pre>'; 
                                        $product_variants = $product_variants['data']['records'];
                                        ?>
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th><?php echo lang('size'); ?><br><?php echo lang('color'); ?></th>
                                                    <?php foreach($details['size_variants'] as $size) { ?>
                                                        <th><?php echo $size; ?></th>   
                                                    <?php } ?>
                                                    <!-- <th>Carton</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $varinat_ids = array(); 
                                                foreach($details['color_variants'] as $color) { ?>
                                                    <tr>
                                                        <th><?php echo $color; ?></th>
                                                        <?php 
                                                        foreach($product_variants as $varinat_price) {                 
                                                       ?>
                                                            
                                                                <?php if($varinat_price['color_variant']==$color && in_array($varinat_price['size_variant'], $details['size_variants'])){ 
                                                                    echo '<td>';
                                                                    if($varinat_price['in_stock']==1){
                                                                        echo '<strong style="width:30%;color:green">'.lang('in_stock').'</strong>';
                                                                    }
                                                                    else{
                                                                        echo '<strong style="width:30%;color:red">'.lang('out_of_stock').'</strong>';
                                                                    }
                                                                    echo '</td>';
                                                                    ?>
                                                            <?php } ?>
                                                            
                                                        <?php } ?>
                                                    </tr>
                                                <?php } ?>
                                               
                                            </tbody>
                                        </table>
                                        <div class="" tabindex="0"></div>
                                    </div>
                                </div>
                            </div>

                      </div>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</section>
