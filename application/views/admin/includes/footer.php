		<footer id="footer">
		    <?php echo lang('copyright'); ?> © <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>
		</footer>

    <div id="modal-section"></div>

    <!--  Error & Success Messages -->
    <script type="text/javascript">
    $(document).ready(function(){
      <?php if($this->session->flashdata('error')){ ?>
        showToaster('error',"<?php echo lang('error'); ?>","<?php echo $this->session->flashdata('error') ?>")
      <?php } ?>
      <?php if($this->session->flashdata('success')){ ?>
        showToaster('success',"<?php echo lang('success'); ?>","<?php echo $this->session->flashdata('success') ?>")
      <?php } ?>
    });

    /* Manage JS Language Fields */
    var success = "<?php echo lang('success'); ?>";
    var error = "<?php echo lang('error'); ?>";
    var info = "<?php echo lang('info'); ?>";
    var category = "<?php echo lang('category'); ?>";
    var email_address = "<?php echo lang('email_address'); ?>";
    var add_new_category = "<?php echo lang('add_new_category'); ?>";
    var edit_category = "<?php echo lang('edit_category'); ?>";
    var product_added_into_cart = "<?php echo lang('product_added_into_cart'); ?>";
    var cart_total_amount = "<?php echo lang('cart_total_amount'); ?>";
    var are_you_sure = "<?php echo lang('are_you_sure'); ?>";
    var are_you_sure_finish_order = "<?php echo lang('are_you_sure_finish_order'); ?>";
    var yes = "<?php echo lang('yes'); ?>";
    var no = "<?php echo lang('no'); ?>";
    var add_new_price_list = "<?php echo lang('add_new_price_list'); ?>";
    var edit_price_list = "<?php echo lang('edit_price_list'); ?>";
    var select_gallery_images = "<?php echo lang('select_gallery_images'); ?>";
    var first_select_category = "<?php echo lang('first_select_category'); ?>";
    var select_subcategory = "<?php echo lang('select_subcategory'); ?>";
    var subcategory_not_found = "<?php echo lang('subcategory_not_found'); ?>";
    var subcategory_not_found_for = "<?php echo lang('subcategory_not_found_for'); ?>";
    var max_10_gallery_images = "<?php echo lang('max_10_gallery_images'); ?>";
    var add_new_subcategory = "<?php echo lang('add_new_subcategory'); ?>";
    var edit_subcategory = "<?php echo lang('edit_subcategory'); ?>";
    </script>
   </body>
</html>

 
