<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                <div class="t-header">
                        <div class="th-title"><span class="zmdi zmdi-view-web zmdi-hc-fw" aria-hidden="true"></span> <?php echo lang('view_price_list_details'); ?>
                        <a href="<?php echo base_url(); ?>admin/pricelist/list" class="btn btn-primary pull-right admin-right-btn"><?php echo lang('back_to_price_list'); ?></a>
                    </div>
                    <br/>
                    <div class="current_games_section">
                          <table id="example" class="table">
                            <tr>
                                <td><?php echo lang('price_list_name'); ?></td>
                                <td><?php echo $details['pricelist_name']; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('price_list_brand'); ?></td>
                                <td><?php echo $details['pricelist_brand']; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('is_main_price_list'); ?></td>
                                <td><?php echo $details['is_main_pricelist']; ?></td>
                            </tr>
                             <tr>
                                <td><?php echo lang('created_date'); ?></td>
                                <td><?php echo convertDateTime($details['created_date']); ?></td>
                            </tr>
                          </table>
                      </div>
                    </div>
                </div>
            </div> 

            <div class="col-md-12">
                <div class="tile">
                    <div class="t-header">
                        <div class="th-title"><span class="zmdi zmdi-view-web zmdi-hc-fw" aria-hidden="true"></span> <?php echo lang('product_varinats'); ?> (<?php echo addZero($products['data']['total_records']); ?>)
                    </div><br/>
                        <div class="current_games_section">
                            <table class="table table-striped table-bordered my-datatable">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('s_no'); ?></th>
                                        <th><?php echo lang('product_name'); ?></th>
                                        <th><?php echo lang('color_varinats'); ?></th>
                                        <th><?php echo lang('size_varinats'); ?></th>
                                        <th><?php echo lang('product_price'); ?> (<?php echo CURRENCY_SYMBOL; ?>)</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                <?php if(!empty($products['data']['records'])){ $i = 1; foreach ($products['data']['records'] as $value) { ?>
                                <tr>
                                    <td><?php echo addZero($i); ?> </td>
                                    <td><?php echo $value['product_name']; ?></td>
                                    <td><?php echo $value['color_variant']; ?></td>
                                    <td><?php echo $value['size_variant']; ?></td>
                                    <td title="<?php echo CURRENCY." ".$value['product_price']; ?>"><?php echo CURRENCY_SYMBOL." ".$value['product_price']; ?></td>
                               </tr>
                                <?php $i++; } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>       
        </div>
    </div>
</section>
