<section id="content">
    <div class="container"> 
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                <div class="t-header">
                        <div class="th-title"><span class="zmdi zmdi-view-comfy zmdi-hc-fw" aria-hidden="true"></span> <?php echo lang('categories'); ?> (<?php echo addZero($categories['data']['total_records']); ?>)
                            <a class="btn btn-primary pull-right admin-right-btn add-category-btn" href="javascript:void(0);"><?php echo lang('add_new_category'); ?></a>
                    </div><br/>
                    <div class="current_games_section">
                        <table class="table table-striped table-bordered my-datatable">
                            <thead>
                                <tr>
                                    <th><?php echo lang('s_no'); ?></th>
                                    <th><?php echo lang('category_name'); ?></th>
                                    <th><?php echo lang('total_subcategories'); ?></th>
                                    <th><?php echo lang('created_date'); ?></th>
                                    <th><?php echo lang('action'); ?></th>  
                                </tr>
                            </thead>
                            <tbody> 
                            <?php if(!empty($categories['data']['records'])){ $i = 1; foreach ($categories['data']['records'] as $value) { ?>
                            <tr>
                                <td><?php echo addZero($i); ?> </td>
                                <td><?php echo $value['category_name']; ?></td>
                                <td><?php echo addZero($value['subcategory_count']); ?></td>
                                <td><?php echo convertDateTime($value['created_date']); ?> </td>
                                <td>
                                    <button class="btn bg-orange btn-icon edit-category-details" data-category-guid="<?php echo $value['category_guid']; ?>" title="<?php echo lang('edit_category'); ?>"><i class="zmdi zmdi-edit"></i></button>
                                    <button class="btn btn-danger btn-icon" onclick="showConfirmationBox('<?php echo lang('are_you_sure'); ?>','<?php echo lang('are_you_sure_delete'); ?> <b style=   &quot;color:red; &quot;><?php echo $value['category_name']; ?></b> <?php echo lang('category'); ?>?','<?php echo lang('yes'); ?>','<?php echo lang('no'); ?>','delete/<?php echo $value['category_guid']; ?>')" title="<?php echo lang('delete_category'); ?>"><i class="zmdi zmdi-delete"></i></button>
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

<!-- Add Category Modal -->
<div class="modal" id="noAnimation" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-blue m-b-20"> 
                <button type="button" class="close white-clr" data-dismiss="modal">X</button>
                <h4 class="modal-title white-clr category-popup-title"><?php echo lang('add_new_category'); ?></h4>
            </div>
            <form role="form" method="post" class="add-new-category-form category-form">
                <div class="modal-body">
                    <input type="hidden" name="category_guid" value="">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('category_name'); ?></label>
                                <input type="text" class="form-control" name="category_name" placeholder="<?php echo lang('category_name'); ?>" maxlength="150" autocomplete="off">
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