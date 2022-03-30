<section id="content">
    <div class="container"> 
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                <div class="t-header">
                        <div class="th-title"><span class="zmdi zmdi-account zmdi-hc-fw" aria-hidden="true"></span> <?php echo lang('sales_agent'); ?> (<?php echo addZero($members['data']['total_records']); ?>)
                            <a href="<?php echo base_url(); ?>admin/agents/add-new" class="btn btn-primary pull-right admin-right-btn"><?php echo lang('add_new_sales_agent'); ?></a>
                    </div><br/>
                    <div class="current_games_section">
                        <table class="table table-striped table-bordered my-datatable">
                            <thead>
                                <tr>
                                    <th><?php echo lang('s_no'); ?></th>
                                    <th><?php echo lang('first_name'); ?></th>
                                    <th><?php echo lang('last_name'); ?></th>
                                    <th><?php echo lang('email'); ?></th>
                                    <th><?php echo lang('mobile_number'); ?></th>
                                    <th><?php echo lang('gender'); ?></th>
                                    <th><?php echo lang('status'); ?></th>
                                    <th><?php echo lang('created_date'); ?></th>
                                    <th><?php echo lang('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody> 
                            <?php if(!empty($members['data']['records'])){ $i = 1; foreach ($members['data']['records'] as $value) { ?>
                            <tr>
                                <td><?php echo addZero($i); ?> </td>
                                <td><?php echo $value['first_name']; ?></td>
                                <td><?php echo $value['last_name']; ?></td>
                                <td><?php echo $value['email']; ?></td>
                                <td><?php echo $value['phone_number']; ?></td>
                                <td><?php echo getGenderName($value['gender']); ?> </td>
                                <td style="color:<?php echo getUserStatusColor($value['user_status']); ?>"><strong><?php echo getUserStatusName($value['user_status']); ?></strong> </td>
                                <td><?php echo convertDateTime($value['created_date']); ?> </td>
                                <td>
                                    <button class="btn bg-cyan btn-icon view-user-details" data-user-guid="<?php echo $value['user_guid']; ?>" title="<?php echo lang('view_sales_agent_details'); ?>"><i class="zmdi zmdi-eye"></i></button>
                                    <button class="btn bg-orange btn-icon" onclick="window.location.href='edit/<?php echo $value['user_guid']; ?>'" title="<?php echo lang('edit_sales_agent'); ?>"><i class="zmdi zmdi-edit"></i></button>
                                    <button class="btn btn-danger btn-icon" onclick="showConfirmationBox('<?php echo lang('are_you_sure'); ?>','<?php echo lang('are_you_sure_delete'); ?> <b style=   &quot;color:red; &quot;><?php echo $value['email']; ?></b> <?php echo lang('sales_agents'); ?>?','<?php echo lang('yes'); ?>','<?php echo lang('no'); ?>','delete/<?php echo $value['user_guid']; ?>')" title="<?php echo lang('delete_sales_agent'); ?>"><i class="zmdi zmdi-delete"></i></button>
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

