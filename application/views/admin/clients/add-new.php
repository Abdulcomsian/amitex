<section id="content">
    <div class="container"> 
        <div class="tile">
            <div class="t-header">
                <div class="th-title"><?php echo lang('add_new_client'); ?></div>
            </div>
            <div class="t-body tb-padding">
                <form role="form" method="post" class="add-new-client-form">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('name'); ?></label>
                                <input type="text" class="form-control" name="first_name" placeholder="<?php echo lang('name'); ?>" maxlength="30" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('email_address'); ?></label>
                                <input type="email" class="form-control" name="email" placeholder="<?php echo lang('email_address'); ?>" maxlength="250" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('account_number'); ?></label>
                                <input type="text" class="form-control" name="account_number" placeholder="<?php echo lang('account_number'); ?>" maxlength="250" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('password'); ?></label>
                                <input type="text" class="form-control" name="password" placeholder="<?php echo lang('password'); ?>" autocomplete="off" value="18654">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('mobile_number'); ?></label>
                                <input type="text" class="form-control" name="phone_number" placeholder="<?php echo lang('mobile_number'); ?>" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('amitex_pricelist'); ?></label>
                                <select class="form-control chosen-select" name="amitex_pricelist_guid">
                                    <option value=""><?php echo lang('amitex_pricelist'); ?></option>
                                    <?php if($amitex_pricelist['data']['total_records'] > 0) { foreach($amitex_pricelist['data']['records'] as $value) { ?>
                                        <option value="<?php echo $value['pricelist_guid']; ?>"><?php echo $value['pricelist_name']; ?> (<?php echo $value['pricelist_brand']; ?>)</option>
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
                                        <option value="<?php echo $value['pricelist_guid']; ?>"><?php echo $value['pricelist_name']; ?> (<?php echo $value['pricelist_brand']; ?>)</option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('amitex_percent_reduction'); ?></label>
                                <input type="number" min="0" class="form-control" name="percent_reduction" placeholder="<?php echo lang('amitex_percent_reduction'); ?>" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('michal_percent_reduction'); ?></label>
                                <input type="number" min="0" class="form-control" name="percent_reduction_michal" placeholder="<?php echo lang('michal_percent_reduction'); ?>" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('see_cart_total_amount'); ?></label>
                                <select class="form-control chosen-select" name="see_cart_total_amount">
                                    <option value=""><?php echo lang('see_cart_total_amount'); ?></option>
                                    <option value="Yes"><?php echo lang('yes'); ?></option>
                                    <option value="No"><?php echo lang('no'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 text-center m-t-20">
                            <button class="btn btn-primary" ><?php echo lang('submit'); ?></button>
                            <button type="button" class="btn btn-danger reset-btn"><?php echo lang('reset'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>