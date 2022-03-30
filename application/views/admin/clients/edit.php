<section id="content">
    <div class="container">
        <div class="tile">
            <div class="t-header">
                <div class="th-title"><?php echo lang('edit_client'); ?></div>
            </div>
            <div class="t-body tb-padding">
                <form role="form" method="post" class="edit-client-form">
                    <input type="hidden" name="user_guid" value="<?php echo $details['user_guid']; ?>">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('name'); ?></label>
                                <input type="text" value="<?php echo $details['first_name'];?>" class="form-control" name="first_name" placeholder="<?php echo lang('name'); ?>" maxlength="30" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('email_address'); ?></label>
                                <input type="email" class="form-control" name="email" value="<?php echo $details['email'];?>" maxlength="200" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('account_number'); ?></label>
                                <input type="text" class="form-control" readonly="" value="<?php echo $details['account_number'];?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('mobile_number'); ?></label>
                                <input type="text" readonly="" value="<?php echo $details['phone_number'];?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('password'); ?></label>
                                <input type="text" class="form-control" name="password" placeholder="<?php echo lang('password'); ?>" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('amitex_pricelist'); ?></label>
                                <select class="form-control chosen-select" name="amitex_pricelist_guid">
                                    <option value=""><?php echo lang('amitex_pricelist'); ?></option>
                                    <?php if($amitex_pricelist['data']['total_records'] > 0) { foreach($amitex_pricelist['data']['records'] as $value) { ?>
                                        <option value="<?php echo $value['pricelist_guid']; ?>" <?php if($details['pricelist_guid'] == $value['pricelist_guid']) echo "selected"; ?>><?php echo $value['pricelist_name']; ?> (<?php echo $value['pricelist_brand']; ?>)</option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('michal_pricelist'); ?></label>
                                <select class="form-control chosen-select" name="michal_pricelist_guid">
                                    <option value=""><?php echo lang('michal_pricelist'); ?></option>
                                    <?php if($michal_pricelist['data']['total_records'] > 0) { foreach($michal_pricelist['data']['records'] as $value) { ?>
                                        <option value="<?php echo $value['pricelist_guid']; ?>" <?php if($details['michal_pricelist_guid'] == $value['pricelist_guid']) echo "selected"; ?>><?php echo $value['pricelist_name']; ?> (<?php echo $value['pricelist_brand']; ?>)</option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('amitex_percent_reduction'); ?></label>
                                <input type="number" min="0" class="form-control" name="percent_reduction" placeholder="<?php echo lang('amitex_percent_reduction'); ?>" autocomplete="off" value="<?php echo $details['percent_reduction'];?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('michal_percent_reduction'); ?></label>
                                <input type="number" min="0" class="form-control" name="percent_reduction_michal" placeholder="<?php echo lang('michal_percent_reduction'); ?>" autocomplete="off" value="<?php echo $details['percent_reduction_michal'];?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('see_cart_total_amount'); ?></label>
                                <select class="form-control chosen-select" name="see_cart_total_amount">
                                    <option value=""><?php echo lang('see_cart_total_amount'); ?></option>
                                    <option value="Yes" <?php if($details['see_cart_total_amount'] == "Yes") echo "selected"; ?>><?php echo lang('yes'); ?></option>
                                    <option value="No" <?php if($details['see_cart_total_amount'] == "No") echo "selected"; ?>><?php echo lang('no'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('select_status'); ?></label>
                                <select class="form-control chosen-select" name="user_status">
                                    <option  value=""><?php echo lang('select_status'); ?></option>
                                    <option <?php if($details['user_status'] == "Pending") echo "selected"; ?> value="Pending"><?php echo lang('pending'); ?></option>
                                    <option <?php if($details['user_status'] == "Verified") echo "selected"; ?> value="Verified"><?php echo lang('verified'); ?></option>
                                    <option <?php if($details['user_status'] == "Blocked") echo "selected"; ?> value="Blocked"><?php echo lang('blocked'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 text-center m-t-20">
                            <button class="btn btn-primary" ><?php echo lang('submit'); ?></button>
                             <button type="button" class="btn btn-danger" onclick="window.location.href='list'"><?php echo lang('cancel'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>