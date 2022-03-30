<div class="modal" id="noAnimation" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-cyan m-b-20"> 
                <button type="button" class="close white-clr" data-dismiss="modal">X</button>
                <h4 class="modal-title white-clr"><i class="zmdi zmdi-shopping-cart zmdi-hc-fw"></i> <?php echo lang('shopping_cart_details'); ?> (<?php echo $details['client_name']; ?>)</h4>
            </div>
            <div class="modal-body">               
              <div class="row table-responsive">
                  <table class="table table-hover">
                       <tbody>
                          <tr>
                               <td><?php echo lang('total_products'); ?></td>
                               <td><?php echo addZero($details['total_products']); ?></td>
                           </tr>
                           <tr>
                               <td><?php echo lang('cart_total_amount'); ?> (<?php echo CURRENCY_SYMBOL; ?>)</td>
                               <td><?php echo CURRENCY_SYMBOL.$details['total_cart_amount']; ?></td>
                           </tr>
                       </tbody>
                  </table>
                    <?php foreach($details['product_data'] as $product_id => $product) { ?>
                      <center><h4><?php echo $product['product_name']; ?> (<?php echo CURRENCY_SYMBOL.$product['product_total_price']; ?>)</h4></center>
                      <table class="table table-hover">
                           <thead>
                                <tr>
                                    <th><?php echo lang('remove'); ?></th>
                                    <th><?php echo lang('color'); ?></th>
                                    <th><?php echo lang('size'); ?></th>
                                    <th><?php echo lang('quantity'); ?></th>
                                    <th><?php echo lang('unit_price'); ?> (<?php echo CURRENCY_SYMBOL; ?>)</th>
                                    <th><?php echo lang('total_price'); ?> (<?php echo CURRENCY_SYMBOL; ?>)</th>
                                    <th><?php echo lang('note'); ?></th>
                                </tr>
                            </thead>
                           <tbody>
                              <?php foreach($product['product_variants'] as $product_variant_id => $variant) { ?>
                                <tr>
                                   <td><a href="javascript:void(0);" class="btn btn-danger remove-product-order" data-product-id="<?php echo $product_id; ?>" data-product-variant-id="<?php echo $product_variant_id; ?>" title="<?php echo lang('remove'); ?>"><i class="zmdi zmdi-delete"></i></a></td>
                                   <td><?php echo $variant['color_variant']; ?></td>
                                   <td><?php echo $variant['size_variant']; ?></td>
                                   <td><input type="number" min="0" name="change-quantity" data-product-id="<?php echo $product_id; ?>" data-product-variant-id="<?php echo $product_variant_id; ?>" class="form-control validate-no" style="width:50%" value="<?php echo $variant['quantity']; ?>" id="change-quantity<?php echo $product_variant_id; ?>"></td>
                                   <td><?php echo CURRENCY_SYMBOL.$variant['unit_price']; ?></td>
                                   <td><?php echo CURRENCY_SYMBOL.$variant['total_price']; ?></td>
                                   <td><textarea name="product_note" data-product-id="<?php echo $product_id; ?>" data-product-variant-id="<?php echo $product_variant_id; ?>" class="productnote" id="productnote<?php echo $product_variant_id; ?>" placeholder="<?php echo lang('note'); ?>"><?php echo $variant['product_note']; ?></textarea></td>
                               </tr>
                              <?php } ?>
                           </tbody>
                      </table>
                    <?php } ?>
              </div>

              <div class="row">
                <h4><?php echo lang('order_note'); ?></h4>
                <textarea name="order_note" class="productnote" id="order_note" style="width: 100%;height:50px" placeholder="<?php echo lang('order_note'); ?>"></textarea>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><?php echo lang('close'); ?></button>
            <button type="button" class="btn btn-sm btn-success finish-order"><?php echo lang('finish_order'); ?></button>
        </div>
    </div>
</div>
</div>