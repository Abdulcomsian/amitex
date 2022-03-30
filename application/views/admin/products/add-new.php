<section id="content">
    <div class="container"> 
        <div class="tile">
            <div class="t-header">
                <div class="th-title"><?php echo lang('add_new_product'); ?></div>
            </div>
            <div class="t-body tb-padding">
                <form role="form" method="post" class="add-new-product-form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('product_item_number'); ?></label>
                                <input type="text" class="form-control" name="product_item_code" placeholder="<?php echo lang('product_item_number'); ?>" maxlength="200" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('product_name'); ?></label>
                                <input type="text" class="form-control" name="product_name" placeholder="<?php echo lang('product_name'); ?>" maxlength="200" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('select_category'); ?></label>
                                <select class="form-control chosen-select" name="product_category_id">
                                    <option value=""><?php echo lang('select_category'); ?></option>
                                    <?php if($categories['data']['total_records'] > 0) { foreach($categories['data']['records'] as $value) { ?>
                                        <option value="<?php echo $value['category_guid']; ?>"><?php echo $value['category_name']; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('select_subcategory'); ?></label>
                                <select class="form-control chosen-select" name="product_subcategory_id" disabled="">
                                    <option value=""><?php echo lang('first_select_category'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('product_descprition'); ?></label>
                                <textarea name="product_descprition" class="form-control" rows="6" placeholder="<?php echo lang('product_descprition'); ?>"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="m-b-20 control-label"><?php echo lang('product_brand'); ?></label>
                                <div class="radio cr-alt m-b-20">
                                    <label>
                                        <input type="radio" name="product_brand" value="Amitex" checked="">
                                        <i class="input-helper"></i>
                                        Amitex
                                    </label>
                                </div>
                                <div class="radio cr-alt m-b-20">
                                    <label>
                                        <input type="radio" name="product_brand" value="Michal Negrin">
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
                                        <input type="radio" name="is_premium" value="Yes">
                                        <i class="input-helper"></i>
                                        <?php echo lang('yes'); ?>
                                    </label>
                                </div>
                                <div class="radio cr-alt m-b-20">
                                    <label>
                                        <input type="radio" name="is_premium" value="No" checked="">
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
                            <label class="control-label"><?php echo lang('color_varinats'); ?></label><br/>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="color_variants[]" placeholder="<?php echo lang('color_varinats'); ?>" maxlength="40" autocomplete="off">
                                    </div>
                                    <div class="col-sm-4">
                                        <a href="javascript:void(0);" style="width:80px;" data-type="color" class="btn btn-primary add-more-variants"><?php echo lang('add_more'); ?></a>
                                    </div>
                                </div>
                            <div class="more-color-variants">
                                <div class="row m-t-10"><div class="col-sm-8"><input type="text" class="form-control" name="color_variants[]" placeholder="<?php echo lang('color_varinats'); ?>" maxlength="40" autocomplete="off"></div><div class="col-sm-4"><a href="javascript:void(0);" style="width:80px;" class="btn btn-danger remove-variant"><?php echo lang('remove'); ?></a></div></div>
                                <div class="row m-t-10"><div class="col-sm-8"><input type="text" class="form-control" name="color_variants[]" placeholder="<?php echo lang('color_varinats'); ?>" maxlength="40" autocomplete="off"></div><div class="col-sm-4"><a href="javascript:void(0);" style="width:80px;" class="btn btn-danger remove-variant"><?php echo lang('remove'); ?></a></div></div>
                                <div class="row m-t-10"><div class="col-sm-8"><input type="text" class="form-control" name="color_variants[]" placeholder="<?php echo lang('color_varinats'); ?>" maxlength="40" autocomplete="off"></div><div class="col-sm-4"><a href="javascript:void(0);" style="width:80px;" class="btn btn-danger remove-variant"><?php echo lang('remove'); ?></a></div></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label"><?php echo lang('size_varinats'); ?></label><br/>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="size_variants[]" placeholder="<?php echo lang('size_varinats'); ?>" maxlength="40" autocomplete="off">
                                    </div>
                                    <div class="col-sm-4">
                                        <a href="javascript:void(0);" style="width:80px;" data-type="size" class="btn btn-primary add-more-variants"><?php echo lang('add_more'); ?></a>
                                    </div>
                                </div>
                            <div class="more-size-variants">
                                <div class="row m-t-10"><div class="col-sm-8"><input type="text" class="form-control" name="size_variants[]" placeholder="<?php echo lang('size_varinats'); ?>" maxlength="40" autocomplete="off"></div><div class="col-sm-4"><a href="javascript:void(0);" style="width:80px;" class="btn btn-danger remove-variant"><?php echo lang('remove'); ?></a></div></div>
                                <div class="row m-t-10"><div class="col-sm-8"><input type="text" class="form-control" name="size_variants[]" placeholder="<?php echo lang('size_varinats'); ?>" maxlength="40" autocomplete="off"></div><div class="col-sm-4"><a href="javascript:void(0);" style="width:80px;" class="btn btn-danger remove-variant"><?php echo lang('remove'); ?></a></div></div>
                                <div class="row m-t-10"><div class="col-sm-8"><input type="text" class="form-control" name="size_variants[]" placeholder="<?php echo lang('size_varinats'); ?>" maxlength="40" autocomplete="off"></div><div class="col-sm-4"><a href="javascript:void(0);" style="width:80px;" class="btn btn-danger remove-variant"><?php echo lang('remove'); ?></a></div></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label"><?php echo lang('main_photo'); ?></label><br/>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="line-height: 150px;"></div>
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
