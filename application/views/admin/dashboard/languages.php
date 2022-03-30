<section id="content">
    <div class="container"> 
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                <div class="t-header">
                        <div class="th-title"><span class="zmdi zmdi-file-text zmdi-hc-fw" aria-hidden="true"></span> <?php echo $languages[$this->input->get('lang')]; ?> <?php echo lang('language_fields'); ?> (<?php echo addZero(count($fields)); ?>)
                    </div>
                    <select onchange="window.location = 'languages?lang=' + this.options[this.selectedIndex].value" class="form-control" style="margin-top: 20px;width:20%;margin-left: 20px;" id="my-select">
                        <?php foreach($languages as $key => $lang) { ?>
                            <option value="<?php echo $key; ?>" <?php if($this->input->get('lang') == $key) { echo "selected"; } ?>><?php echo $lang; ?></option>
                        <?php } ?>
                    </select><br/>
                    <div class="current_games_section">
                        <form method="post" id="fiels-form">
                            <table id="example" class="table table-striped table-bordered my-datatable" style="width: 100%">
                                 <colgroup>
                                   <col span="1" style="width: 50%;">
                                   <col span="1" style="width: 50%;">
                                </colgroup>

                                <thead>
                                    <tr>
                                        <th><?php echo $languages[$this->input->get('lang')]; ?> <?php echo lang('text'); ?></th>
                                        <th><?php echo $languages[$this->input->get('lang')]; ?> <?php echo lang('text'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($fields)) { $i = 1; foreach($fields as $key => $field) {
                                        if(($i % 2 != 0)) { ?>
                                        <tr>
                                        <?php } ?>
                                            <td><textarea required="" name="<?php echo $key; ?>" class="form-control" style="width: 100% !important;"><?php echo $field; ?></textarea></td>
                                        <?php  if(($i % 2 == 0)) { ?>
                                            </tr>
                                    <?php } $i++; } } ?>
                                </tbody>
                              </table>
                              <center><button class="btn btn-primary" ><?php echo lang('update'); ?></button></center>
                        </form>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</section>

