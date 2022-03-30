<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            	<div class="tile">
                    <div class="t-header">
                        <div class="th-title"><span class="zmdi zmdi-key zmdi-hc-fw" aria-hidden="true"></span> <?php echo lang('change_password'); ?></div>
                    </div>
                    
                    <div class="t-body tb-padding">
                        <div class="row">
                        	<form method="post" id="profile-form" class="profile-form" novalidate="novalidate">
                              <div class="form-group col-sm-12">
                                <div class="alert alert-warning col-sm-8" role="alert" style="font-size:16px;">
                                <strong><?php echo lang('important_info_password'); ?></strong>
                              </div>
                              </div>
                              <div class="form-group col-sm-12">
                              <div class="col-sm-3">
                                <label class="control-label"><?php echo lang('current_password'); ?></label>
                              </div>
                              <div class="col-sm-5">
                                <div class="input-group">
                                    <input type="password" placeholder="<?php echo lang('current_password'); ?>" class="form-control" id="current_password" name="current_password" required>
                                    <span class="input-group-addon last view-password"><i class="glyphicon glyphicon-eye-open"></i></span>
                                </div>
                              </div>
                              </div>
	                          <div class="form-group col-sm-12">
	                          <div class="col-sm-3">
	                          	<label class="control-label"><?php echo lang('new_password'); ?></label>
	                          </div>
	                          <div class="col-sm-5">
                              <div class="input-group">
                                    <input type="password" placeholder="<?php echo lang('new_password'); ?>" class="form-control" id="new_password" name="new_password" required>
                                    <span class="input-group-addon last view-password"><i class="glyphicon glyphicon-eye-open"></i></span>
                                </div>
	                          </div>
	                          </div>

	                          <div class="form-group col-sm-12">
	                          <div class="col-sm-3">
	                          	<label class="control-label"><?php echo lang('confirm_password'); ?></label>
	                          </div>
	                          <div class="col-sm-5">
                              <div class="input-group">
                                    <input type="password" placeholder="<?php echo lang('confirm_password'); ?>" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <span class="input-group-addon last view-password"><i class="glyphicon glyphicon-eye-open"></i></span>
                                </div>
	                          </div>
	                          </div>
                                <div class="form-group col-sm-12 text-center">
                                    <button class="btn btn-primary create_btn" ><?php echo lang('update'); ?></button>
                                    <button type="button" class="btn btn-danger reset-btn"><?php echo lang('reset'); ?></button>
	                            </div>
                            <form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

