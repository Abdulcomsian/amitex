<section id="content">
    <div class="container"> 
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                <div class="t-header">
                        <div class="th-title"><span class="zmdi zmdi-money-box zmdi-hc-fw" aria-hidden="true"></span> <?php echo lang('price_lists'); ?> (<?php echo addZero($pricelist['data']['total_records']); ?>)
                            <a class="btn btn-primary pull-right admin-right-btn add-pricelist-btn" href="javascript:void(0);"><?php echo lang('add_new_price_list'); ?></a>
                    </div><br/>
                    <div class="current_games_section">
                        <table class="table table-striped table-bordered my-datatable">
                            <thead>
                                <tr>
                                    <th><?php echo lang('s_no'); ?></th>
                                    <th><?php echo lang('price_list_name'); ?></th>
                                    <th><?php echo lang('price_list_brand'); ?></th>
                                    <th><?php echo lang('is_main_price_list'); ?></th>
                                    <th><?php echo lang('no_of_clients'); ?></th>
                                    <th><?php echo lang('created_date'); ?></th>
                                    <th><?php echo lang('action'); ?></th>  
                                </tr>
                            </thead>
                            <tbody> 
                            <?php if(!empty($pricelist['data']['records'])){ $i = 1; foreach ($pricelist['data']['records'] as $value) { ?>
                            <tr>
                                <td><?php echo addZero($i); ?> </td>
                                <td><?php echo $value['pricelist_name']; ?></td>
                                <td><?php echo $value['pricelist_brand']; ?></td>
                                <td><?php echo $value['is_main_pricelist']; ?></td>
                                <td><?php echo addZero($value['total_clients']); ?></td>
                                <td><?php echo convertDateTime($value['created_date']); ?> </td>
                                <td>
                                    <button class="btn bg-cyan btn-icon" onclick="window.location.href='details/<?php echo $value['pricelist_guid']; ?>'" title="<?php echo lang('view_price_list_details'); ?>"><i class="zmdi zmdi-eye"></i></button>
                                    <button class="btn bg-orange btn-icon edit-pricelist-details" data-pricelist-guid="<?php echo $value['pricelist_guid']; ?>" title="<?php echo lang('edit_price_list'); ?>"><i class="zmdi zmdi-edit"></i></button>
                                    <button class="btn btn-danger btn-icon" onclick="showConfirmationBox('<?php echo lang('are_you_sure'); ?>','<?php echo lang('are_you_sure_delete'); ?> <b style=   &quot;color:red; &quot;><?php echo $value['pricelist_name']; ?></b> <?php echo lang('price_list'); ?>?','<?php echo lang('yes'); ?>','<?php echo lang('no'); ?>','delete/<?php echo $value['pricelist_guid']; ?>')" title="<?php echo lang('delete_price_list'); ?>"><i class="zmdi zmdi-delete"></i></button>
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

<!-- Add Pricelist Modal -->
<div class="modal" id="noAnimation" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-blue m-b-20"> 
                <button type="button" class="close white-clr" data-dismiss="modal">X</button>
                <h4 class="modal-title white-clr pricelist-popup-title"><?php echo lang('add_new_price_list'); ?></h4>
            </div>
            <form role="form" method="post" class="add-new-pricelist-form pricelist-form">
                <div class="modal-body">
                    <input type="hidden" name="pricelist_guid" value="">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('price_list_name'); ?></label>
                                <input type="text" class="form-control" name="pricelist_name" placeholder="<?php echo lang('price_list_name'); ?>" maxlength="150" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="m-b-10 control-label"><?php echo lang('price_list_brand'); ?></label>
                                <div class="radio cr-alt m-b-20">
                                    <label>
                                        <input type="radio" name="pricelist_brand" value="Amitex" checked="">
                                        <i class="input-helper"></i>
                                        Amitex
                                    </label>
                                    &nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="pricelist_brand" value="Michal Negrin">
                                        <i class="input-helper"></i>
                                        Michal Negrin
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="main-pricelist-section col-sm-12 <?php if($is_main_pricelist_added) { echo "hidden"; }?>">
                            <div class="form-group">
                                <label class="m-b-10 control-label"><?php echo lang('is_main_price_list'); ?></label>
                                <div class="radio cr-alt m-b-20">
                                    <label>
                                        <input type="radio" name="is_main_pricelist" value="Yes">
                                        <i class="input-helper"></i>
                                        Yes
                                    </label>
                                    &nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="is_main_pricelist" value="No" checked="">
                                        <i class="input-helper"></i>
                                        No 
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('uploads_products_csv'); ?></label>
                                <a class="product-download-btn" href="javascript:void(0);"><?php echo lang('download_products_csv'); ?></a>
                                <input type="file" class="form-control" name="products_csv" onchange="validateFile(this,'csv')" accept=".csv">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary"><?php echo lang('submit'); ?></button>
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><?php echo lang('close'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>