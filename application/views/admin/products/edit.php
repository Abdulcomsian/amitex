<section id="content">
    <div class="container"> 
        <div class="tile">
            <div class="t-header">
                <div class="th-title"><?php echo lang('edit_product'); ?></div>
            </div>
            <div class="t-body tb-padding">
                <form role="form" method="post" class="edit-product-form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('product_item_number'); ?></label>
                                <input type="text" class="form-control" name="product_item_code" value="<?php echo $details['product_item_code']; ?>" placeholder="<?php echo lang('product_item_number'); ?>" maxlength="200" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('product_name'); ?></label>
                                <input type="text" class="form-control" value="<?php echo $details['product_name']; ?>" name="product_name" placeholder="<?php echo lang('product_name'); ?>" maxlength="200" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('select_category'); ?></label>
                                <select class="form-control chosen-select" name="product_category_id">
                                    <option value=""><?php echo lang('select_category'); ?></option>
                                    <?php if($categories['data']['total_records'] > 0) { foreach($categories['data']['records'] as $value) { ?>
                                        <option value="<?php echo $value['category_guid']; ?>" <?php if($value['category_guid'] == $details['category_guid']) {echo "selected";} ?>><?php echo $value['category_name']; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('select_subcategory'); ?></label>
                                <select class="form-control chosen-select" name="product_subcategory_id">
                                    <option value=""><?php echo lang('select_subcategory'); ?></option>
                                    <?php if($subcategories['data']['total_records'] > 0) { foreach($subcategories['data']['records'] as $value) { ?>
                                        <option value="<?php echo $value['subcategory_guid']; ?>" <?php if($value['subcategory_guid'] == $details['subcategory_guid']) {echo "selected";} ?>><?php echo $value['subcategory_name']; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $details['product_guid']; ?>" name="product_guid">
                        <input type="hidden" name="old_gallery_images" value='<?php echo json_encode($details['product_gallery_images'], JSON_UNESCAPED_UNICODE)?>'>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('product_descprition'); ?></label>
                                <textarea name="product_descprition" class="form-control" rows="6" placeholder="<?php echo lang('product_descprition'); ?>"><?php echo $details['product_descprition']; ?></textarea>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="m-b-20 control-label"><?php echo lang('product_brand'); ?></label>
                                <div class="radio cr-alt m-b-20">
                                    <label>
                                        <input type="radio" name="product_brand" value="Amitex" <?php if($details['product_brand'] == 'Amitex') {echo "checked";} ?>>
                                        <i class="input-helper"></i>
                                        Amitex
                                    </label>
                                </div>
                                <div class="radio cr-alt m-b-20">
                                    <label>
                                        <input type="radio" name="product_brand" value="Michal Negrin" <?php if($details['product_brand'] == 'Michal Negrin') {echo "checked";} ?>>
                                        <i class="input-helper"></i>
                                        Michal Negrin
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="m-b-20 control-label"><?php echo lang('is_premium'); ?></label>
                                <div class="radio cr-alt m-b-20">
                                    <label>
                                        <input type="radio" name="is_premium" value="Yes" <?php if($details['is_premium'] == 'Yes') {echo "checked";} ?>>
                                        <i class="input-helper"></i>
                                        <?php echo lang('yes'); ?>
                                    </label>
                                </div>
                                <div class="radio cr-alt m-b-20">
                                    <label>
                                        <input type="radio" name="is_premium" value="No" <?php if($details['is_premium'] == 'No') {echo "checked";} ?>>
                                        <i class="input-helper"></i>
                                        <?php echo lang('no'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label class="control-label"><?php echo lang('gallery_images'); ?></label><br/>
                            <div id="dZUpload" class="dropzone">
                                <div class="dz-default dz-message" data-dz-message><span><?php echo lang('drop_gallery_images'); ?></span></div>
                            </div>
                            <p style="color:red;"><?php echo lang('max_10_gallery_images'); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label"><?php echo lang('color_variants'); ?></label><br/>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="color_variants[]" placeholder="<?php echo lang('color_variants'); ?>" maxlength="40" autocomplete="off" value="<?php echo @$details['color_variants'][0]; ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <a href="javascript:void(0);" style="width:80px;" data-type="color" class="btn btn-primary add-more-variants"><?php echo lang('add_more'); ?></a>
                                    </div>
                                </div>
                            <div class="more-color-variants">
                                <?php 
                                    $color_length = (count($details['color_variants']) >= 4) ? count($details['color_variants']) : 4;
                                    for ($i=1; $i < $color_length; $i++) { ?>
                                        <div class="row m-t-10"><div class="col-sm-8"><input type="text" class="form-control" name="color_variants[]" placeholder="<?php echo lang('color_variants'); ?>" maxlength="40" autocomplete="off" value="<?php echo @$details['color_variants'][$i]; ?>"></div><div class="col-sm-4"><a href="javascript:void(0);" style="width:80px;" class="btn btn-danger remove-variant"><?php echo lang('remove'); ?></a></div></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label"><?php echo lang('size_variants'); ?></label><br/>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="size_variants[]" placeholder="<?php echo lang('size_varinats'); ?>" maxlength="40" autocomplete="off" value="<?php echo @$details['size_variants'][0]; ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <a href="javascript:void(0);" style="width:80px;" data-type="size" class="btn btn-primary add-more-variants"><?php echo lang('add_more'); ?></a>
                                    </div>
                                </div>
                            <div class="more-size-variants">
                                <?php 
                                    $size_length = (count($details['size_variants']) >= 4) ? count($details['size_variants']) : 4;
                                    for ($i=1; $i < $size_length; $i++) { ?>
                                        <div class="row m-t-10"><div class="col-sm-8"><input type="text" class="form-control" name="size_variants[]" placeholder="<?php echo lang('size_varinats'); ?>" maxlength="40" autocomplete="off" value="<?php echo @$details['size_variants'][$i]; ?>"></div><div class="col-sm-4"><a href="javascript:void(0);" style="width:80px;" class="btn btn-danger remove-variant"><?php echo lang('remove'); ?></a></div></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label"><?php echo lang('main_photo'); ?></label><br/>
                            <div class="fileinput fileinput-new" data-provides="fileinput">

                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="line-height: 150px;"><img src="<?php echo $details['product_main_photo']; ?>" class="img-responsive"></div>
                                <div>
                                    <span class="btn btn-info btn-file">
                                        <span class="fileinput-new"><?php echo lang('select_image'); ?></span>
                                        <span class="fileinput-exists"><?php echo lang('change'); ?></span>
                                        <input type="hidden" value=""><input type="file" name="product_main_photo">
                                    </span>
                                    <a href="javascript:void(0);" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><?php echo lang('remove'); ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 text-center m-t-20">
                            <button class="btn btn-primary" id="submit-product"><?php echo lang('submit'); ?></button>
                            <button type="button" class="btn btn-danger reset-btn"><?php echo lang('reset'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
