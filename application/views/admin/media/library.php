<section id="content">
    <div class="container"> 
        <div class="tile">
            <div class="t-header">
                <div class="th-title"><?php echo lang('media_library'); ?></div>
            </div>
            <div class="t-body tb-padding">
                <input type="hidden" name="previous_media_images" value='<?php echo json_encode($previous_media_images, JSON_UNESCAPED_UNICODE)?>'>
                <form role="form" method="post" class="add-media-form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="control-label"><?php echo lang('media_library_images'); ?></label><br/>
                            <div id="dZUpload" class="dropzone">
                                <div class="dz-default dz-message" data-dz-message><span><?php echo lang('media_library_images'); ?></span></div>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 text-center m-t-20"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
