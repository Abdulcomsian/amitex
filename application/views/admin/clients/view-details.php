<div class="modal" id="noAnimation" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-cyan m-b-20"> 
                <button type="button" class="close white-clr" data-dismiss="modal">X</button>
                <h4 class="modal-title white-clr"><?php echo lang('view_client_details'); ?></h4>
            </div>
            <div class="modal-body">               
              <div class="row table-responsive">
                  <table class="table table-hover">
                       <tbody>
                          <tr>
                               <td><?php echo lang('name'); ?></td>
                               <td><?php echo $details['first_name']." ".$details['last_name']; ?></td>
                           </tr>
                           <tr>
                               <td><?php echo lang('email_address'); ?></td>
                               <td><?php echo $details['email']; ?></td>
                           </tr>
                           <tr>
                               <td><?php echo lang('account_number'); ?></td>
                               <td><?php echo $details['account_number']; ?></td>
                           </tr>
                            <tr>
                               <td><?php echo lang('mobile_number'); ?></td>
                               <td><?php echo $details['phone_number']; ?></td>
                           </tr>
                           <tr>
                               <td><?php echo lang('gender'); ?></td>
                               <td><?php echo getGenderName($details['gender']); ?></td>  
                           </tr>
                           <tr>
                               <td><?php echo lang('amitex_pricelist'); ?></td>
                               <td><?php echo $details['pricelist_name']; ?></td> 
                           </tr>
                           <tr>
                               <td><?php echo lang('michal_pricelist'); ?></td>
                               <td><?php echo $details['michal_pricelist_name']; ?></td> 
                           </tr>
                           <tr>
                               <td><?php echo lang('amitex_percent_reduction'); ?></td>
                               <td><?php echo $details['percent_reduction']; ?></td> 
                           </tr>
                           <tr>
                               <td><?php echo lang('michal_percent_reduction'); ?></td>
                               <td><?php echo $details['percent_reduction_michal']; ?></td> 
                           </tr>
                           <tr>
                               <td><?php echo lang('see_cart_total_amount'); ?></td>
                               <td><?php echo $details['see_cart_total_amount']; ?></td> 
                           </tr>
                           <tr>
                               <td><?php echo lang('status'); ?></td>
                               <td style="color:<?php echo getUserStatusColor($details['user_status']); ?>"><strong><?php echo getUserStatusName($details['user_status']); ?></strong> </td> 
                           </tr>
                           <tr>
                               <td><?php echo lang('created_date'); ?></td>
                               <td><?php echo convertDateTime($details['created_date']); ?></td>
                           </tr>
                       </tbody>
                  </table>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><?php echo lang('close'); ?></button>
        </div>
    </div>
</div>
</div>