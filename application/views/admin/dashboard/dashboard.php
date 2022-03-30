<section id="content">
    <div class="container">
        <header class="page-header">
            <h3><?php echo lang('dashboard'); ?> </h3>
        </header>
        <div class="overview row">

            <?php if($this->user_type_id == 1 || $this->user_type_id == 2){ ?>

                <!-- Get Clients Count -->
                <div class="col-md-3 col-sm-4">
                    <div class="o-item bg-teal">
                        <div class="oi-title">
                            <span data-value="450382"></span>
                            <span><?php echo lang('total_clients'); ?></span>
                        </div>
                        <h1><?php echo addZero($statics['total_clients']); ?></h1>
                    </div>
                </div>
                
            <?php } ?>

            <?php if($this->user_type_id == 1){ ?>

                <!-- Get Sales Agents Count -->
                <div class="col-md-3 col-sm-4">
                    <div class="o-item bg-cyan">
                        <div class="oi-title">
                            <span data-value="450382"></span>
                            <span><?php echo lang('total_agents'); ?></span>
                        </div>
                        <h1><?php echo addZero($statics['total_agents']); ?></h1>
                    </div>
                </div>
                

                <!-- Get Categories Count -->
                <div class="col-md-3 col-sm-4">
                    <div class="o-item bg-orange">
                        <div class="oi-title">
                            <span data-value="450382"></span>
                            <span><?php echo lang('total_categories'); ?></span>
                        </div>
                        <h1><?php echo addZero($statics['total_categories']); ?></h1>
                    </div>
                </div>

                <!-- Get Sub Categories Count -->
                <div class="col-md-3 col-sm-4">
                    <div class="o-item bg-brand">
                        <div class="oi-title">
                            <span data-value="450382"></span>
                            <span><?php echo lang('total_subcategories'); ?></span>
                        </div>
                        <h1><?php echo addZero($statics['total_subcategories']); ?></h1>
                    </div>
                </div>

                <!-- Get Products Count -->
                <div class="col-md-3 col-sm-4">
                    <div class="o-item bg-deeporange">
                        <div class="oi-title">
                            <span data-value="450382"></span>
                            <span><?php echo lang('total_products'); ?></span>
                        </div>
                        <h1><?php echo addZero($statics['total_products']); ?></h1>
                    </div>
                </div>

                <!-- Get Price List Count -->
                <div class="col-md-3 col-sm-4">
                    <div class="o-item bg-indigo">
                        <div class="oi-title">
                            <span data-value="450382"></span>
                            <span><?php echo lang('total_pricelist'); ?></span>
                        </div>
                        <h1><?php echo addZero($statics['total_pricelist']); ?></h1>
                    </div>
                </div>

            <?php } ?>

            <!-- Get Orders Count -->
            <div class="col-md-3 col-sm-4">
                <div class="o-item bg-green">
                    <div class="oi-title">
                        <span data-value="450382"></span>
                        <span><?php echo lang('total_orders'); ?></span>
                    </div>
                    <h1><?php echo addZero($statics['total_orders']); ?></h1>
                </div>
            </div>

            <!-- Get Last Login -->
            <div class="col-md-3 col-sm-4">
                <div class="o-item bg-creat">
                    <div class="oi-title">
                        <span data-value="8737"></span>
                        <span><?php echo lang('last_login'); ?></span>
                    </div>
                    <h3 class="last_login"><?php echo convertDateTime($this->session->userdata("userdata")['last_login']); ?></h3>
                </div>
            </div>
        </div>
    </div>
</section>