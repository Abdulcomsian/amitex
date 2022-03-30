<section id="content">
    <div class="container"> 
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                <div class="t-header">
                        <div class="th-title"><span class="zmdi zmdi-shopping-cart zmdi-hc-fw" aria-hidden="true"></span> <?php echo $client_name; ?> <?php echo lang('order_history'); ?> (<?php echo addZero($orders['data']['total_records']); ?>)
                            <?php if($this->user_type_id == 1 || $this->user_type_id == 2) { ?>
                                <a href="<?php echo base_url(); ?>admin/clients/list" class="btn btn-primary pull-right admin-right-btn"><?php echo lang('back_to_client_list'); ?></a>
                            <?php } ?>
                    </div><br/>
                    <div class="current_games_section">
                        <table class="table table-striped table-bordered my-datatable">
                            <thead>
                                <tr>
                                    <th><?php echo lang('s_no'); ?></th>
                                    <th><?php echo lang('order_id'); ?></th>
                                    <th><?php echo lang('order_by'); ?></th>
                                    <th><?php echo lang('total_products'); ?></th>
                                    <th><?php echo lang('cart_total_amount'); ?> (<?php echo CURRENCY_SYMBOL; ?>)</th>
                                    <th><?php echo lang('created_date'); ?></th>
                                    <th><?php echo lang('action'); ?></th>  
                                </tr>
                            </thead>
                            <tbody> 
                            <?php if(!empty($orders['data']['records'])){ $i = 1; foreach ($orders['data']['records'] as $value) { ?>
                            <tr>
                                <td><?php echo addZero($i); ?> </td>
                                <td><?php echo "OID-".$value['order_id']; ?></td>
                                <td><?php echo $value['order_by_first_name']." ".$value['order_by_last_name']; ?> (<?php echo $value['order_by_role']; ?>)</td>
                                <td><?php echo addZero($value['total_products']); ?></td>
                                <td><?php echo CURRENCY_SYMBOL.$value['total_cart_amount']; ?></td>
                                <td><?php echo convertDateTime($value['created_date']); ?> </td>
                                <td>
                                    <button class="btn bg-cyan btn-icon view-order-details" data-order-guid="<?php echo $value['order_guid']; ?>" title="<?php echo lang('view_order_details'); ?>"><i class="zmdi zmdi-eye"></i></button>
                                </td>
                           </tr>
                            <?php $i++; } } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</section>

