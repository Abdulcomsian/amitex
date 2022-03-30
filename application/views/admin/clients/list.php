<section id="content">
    <div class="container"> 
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                <div class="t-header">
                        <div class="th-title"><span class="zmdi zmdi-account zmdi-hc-fw" aria-hidden="true"></span> <?php echo lang('clients'); ?> (<?php echo addZero($members['data']['total_records']); ?>)
                            <?php if($this->user_type_id == 1) { ?>
                                <div class="admin-right-btn">
                                    <a href="<?php echo base_url(); ?>admin/clients/add-new" class="btn btn-primary"><?php echo lang('add_new_client'); ?></a>
                                    <a href="javascript:void(0);" class="btn btn-primary upload-clients-btn"><?php echo lang('upload_clients_csv'); ?></a>
                                </div>
                            <?php } ?>
                    </div><br/>
                    <div class="current_games_section">
                        <table class="table table-striped table-bordered my-datatable">
                            <thead>
                                <tr>
                                    <th><?php echo lang('s_no'); ?></th>
                                    <th><?php echo lang('name'); ?></th>
                                    <th><?php echo lang('email'); ?></th>
                                    <th><?php echo lang('account_number'); ?></th>
                                    <th><?php echo lang('mobile_number'); ?></th>
                                    <th><?php echo lang('status'); ?></th>
                                    <th><?php echo lang('created_date'); ?></th>
                                    <th><?php echo lang('order_history'); ?></th>
                                    <th><?php echo lang('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody> 
                            <?php if(!empty($members['data']['records'])){ $i = 1; foreach ($members['data']['records'] as $value) { ?>
                            <tr>
                                <td><?php echo addZero($i); ?> </td>
                                <td><?php echo $value['first_name']." ".$value['last_name']; ?></td>
                                <td><?php echo $value['email']; ?></td>
                                <td><?php echo $value['account_number']; ?></td>
                                <td><?php echo $value['phone_number']; ?></td>
                                <td style="color:<?php echo getUserStatusColor($value['user_status']); ?>"><strong><?php echo $value['user_status']; ?></strong> </td>
                                <td><?php echo convertDateTime($value['created_date']); ?> </td>
                                <td><a href="../orders/history/<?php echo $value['user_guid']; ?>" class="btn btn-success"><?php echo lang('order_history'); ?></a></td>
                                <td>
                                    <button class="btn bg-cyan btn-icon view-user-details" data-user-guid="<?php echo $value['user_guid']; ?>" title="<?php echo lang('view_client_details'); ?>"><i class="zmdi zmdi-eye"></i></button>
                                    <button class="btn bg-green btn-icon" onclick="window.location.href='<?php echo base_url(); ?>admin/products/order/<?php echo $value['user_guid']; ?>?subcategory_guid=<?php echo @$subcategory_guid; ?>'" title="<?php echo lang('start_order'); ?>"><i class="zmdi zmdi-shopping-cart"></i></button>
                                    <?php if($this->user_type_id == 1) { ?>
                                        <button class="btn bg-orange btn-icon" onclick="window.location.href='edit/<?php echo $value['user_guid']; ?>'" title="<?php echo lang('edit_client'); ?>"><i class="zmdi zmdi-edit"></i></button>
                                        <button class="btn btn-danger btn-icon" onclick="showConfirmationBox('<?php echo lang('are_you_sure'); ?>','<?php echo lang('are_you_sure_delete'); ?> <b style=   &quot;color:red; &quot;><?php echo $value['email']; ?></b> <?php echo lang('client'); ?>?','<?php echo lang('yes'); ?>','<?php echo lang('no'); ?>','delete/<?php echo $value['user_guid']; ?>')" title="<?php echo lang('delete_client'); ?>"><i class="zmdi zmdi-delete"></i></button>
                                    <?php } ?>
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

<!-- Upload Clients Modal -->
<div class="modal" id="noAnimation1" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-blue m-b-20"> 
                <button type="button" class="close white-clr" data-dismiss="modal">X</button>
                <h4 class="modal-title white-clr"><?php echo lang('upload_clients_csv'); ?></h4>
            </div>
            <form role="form" method="post" class="upload-clients-form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('upload_clients_csv'); ?></label>
                                <a class="product-download-btn" download href="../../amitex-clients-sample.csv"><?php echo lang('download_clients_csv_sample'); ?></a>
                                <input type="file" class="form-control" name="clients_csv" onchange="validateFile(this,'csv')" accept=".csv">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary"><?php echo lang('upload'); ?></button>
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><?php echo lang('close'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Clients Modal Error -->
<div class="modal" id="clients_error" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-blue m-b-20"> 
                <button type="button" class="close white-clr" data-dismiss="modal">X</button>
                <h4 class="modal-title white-clr"><?php echo lang('error')." ".lang('upload_clients'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success success-area hidden" role="alert">
                    <strong></strong>
                </div>
                <div class="current_games_section">
                    <table class="table table-striped table-bordered error-datatable hidden">
                        <thead>
                            <tr>
                                <th><?php echo lang('s_no'); ?></th>
                                <th><?php echo lang('error_descprition'); ?></th>
                            </tr>
                        </thead>
                        <tbody> 

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-primary download-report"><?php echo lang('download_report'); ?></button>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><?php echo lang('close'); ?></button>
            </div>
        </div>
    </div>
</div>