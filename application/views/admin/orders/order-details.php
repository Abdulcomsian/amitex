<div class="modal" id="noAnimation" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 800px;">
        <div class="modal-content">
            <div class="modal-header bg-cyan m-b-20"> 
                <button type="button" class="close white-clr" data-dismiss="modal">X</button>
                <h4 class="modal-title white-clr"><i class="zmdi zmdi-shopping-cart zmdi-hc-fw"></i> <?php echo lang('order_details'); ?> (<?php echo "OID-".$details['order_id']; ?>)</h4>
            </div>
            <div class="modal-body">               
              <div class="row table-responsive">
                  <table class="table table-hover">
                       <tbody>
                          <tr>
                               <td><?php echo lang('order_created_by'); ?></td>
                               <td><?php echo $details['order_by_first_name']." ".$details['order_by_last_name']; ?> (<?php echo $details['order_by_role']; ?>)</td>
                          </tr>
                          <tr>
                               <td><?php echo lang('total_products'); ?></td>
                               <td><?php echo addZero($details['total_products']); ?></td>
                           </tr>
                           <tr>
                               <td><?php echo lang('cart_total_amount'); ?> (<?php echo CURRENCY_SYMBOL; ?>)</td>
                               <td><?php echo CURRENCY_SYMBOL.$details['total_cart_amount']; ?></td>
                           </tr>
                           <tr>
                               <td><?php echo lang('order_date'); ?></td>
                               <td><?php echo convertDateTime($details['created_date']); ?></td>
                           </tr>
                           <tr>
                               <td><?php echo lang('order_note'); ?></td>
                               <td><?php echo $details['order_note']; ?></td>
                           </tr>
                       </tbody>
                  </table>
                    <?php foreach($details['order_details'] as $product) { ?>
                      <center><h4><?php echo $product['product_name']; ?> (<?php echo CURRENCY_SYMBOL.$product['product_total_amount']; ?>)</h4></center>
                      <table class="table table-hover">
                           <thead>
                                <tr>
                                    <th><?php echo lang('color'); ?></th>
                                    <th><?php echo lang('size'); ?></th>
                                    <th><?php echo lang('quantity'); ?></th>
                                    <th><?php echo lang('unit_price'); ?> (<?php echo CURRENCY_SYMBOL; ?>)</th>
                                    <th><?php echo lang('total_price'); ?> (<?php echo CURRENCY_SYMBOL; ?>)</th>
                                    <th><?php echo lang('note'); ?></th>
                                </tr>
                            </thead>
                           <tbody>
                              <?php foreach($product['product_variants'] as $variant) { ?>
                                <tr>
                                   <td><?php echo $variant['color_variant']; ?></td>
                                   <td><?php echo $variant['size_variant']; ?></td>
                                   <td><?php echo $variant['quantity']; ?></td>
                                   <td><?php echo CURRENCY_SYMBOL.$variant['unit_price']; ?></td>
                                   <td><?php echo CURRENCY_SYMBOL.$variant['total_price']; ?></td>
                                   <td><?php echo $variant['product_note']; ?></td>
                               </tr>
                              <?php } ?>
                           </tbody>
                      </table>
                    <?php } ?>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><?php echo lang('close'); ?></button>
        </div>
    </div>
</div>
</div>