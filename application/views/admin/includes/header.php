<html lang="<?php echo DEFAULT_LANGUAGE_CODE; ?>">
    <head>
        <title><?php echo SITE_NAME; ?> :: <?php echo (isset($title)) ? $title : lang('admin'); ?></title>  

        <!-- Meta Tags -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" currency=<?php echo CURRENCY_SYMBOL; ?> />
        <meta name="format-detection" content="telephone=no">
        <meta charset="UTF-8">
        <meta name="description" content="<?php echo SITE_NAME; ?>">
        <meta name="keywords" content="<?php echo SITE_NAME; ?>">
        <link rel="icon" href="<?php echo base_url(); ?>assets/admin/img/favicon.ico">

        <!-- CSS Files -->
        <link href="<?php echo isSSL(); ?>://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/admin/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/admin/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/admin/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/sweetalert2.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/toastr.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/nprogress.css" rel="stylesheet">

        <!-- Other CSS -->
        <?php if(!empty($css)){foreach($css as $value){ ?>
                <link href="<?php echo $value; ?>" rel="stylesheet">
        <?php }} ?> 

        <link href="<?php echo base_url(); ?>assets/admin/css/app.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/admin/css/custom.css" rel="stylesheet">

        <!-- Javascript Libraries -->
        <script src="<?php echo base_url(); ?>assets/admin/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/admin/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/admin/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/admin/js/functions.js"></script>
        <script src="<?php echo base_url(); ?>assets/admin/js/jquery.validate.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/admin/js/messages_he.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/toastr.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/nprogress.js"></script>
        <script src="<?php echo base_url(); ?>assets/admin/js/custom.js"></script>

        <!-- Other JS -->
<?php if(!empty($js)){foreach($js as $value){ ?>
        <script src="<?php echo $value; ?>"></script>
<?php }} ?>

        <!-- Get Base URL --> 
        <script type="text/javascript">
            var base_url = "<?php echo base_url(); ?>";
            var api_url  = "<?php echo ADMIN_API_URL; ?>";
        </script>    
    </head>
    <body>
        <header id="header" class="clearfix" data-spy="affix" data-offset-top="65">
            <ul class="header-inner">
                
                <li class="logo">
                    <a href="<?php echo base_url(); ?>admin/dashboard" class="admin-heading"><?php echo SITE_NAME; ?></a>
                    <div id="menu-trigger"><i class="zmdi zmdi-menu"></i></div>
                </li>
                
                <!-- Settings -->
                <li class="pull-right dropdown hidden-xs">
                    <a href="" data-toggle="dropdown">
                        <i class="zmdi zmdi-more-vert"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a data-toggle="fullscreen" href="javascript:void(0);"><span class="zmdi zmdi-fullscreen zmdi-hc-fw"></span> <?php echo lang('toggle_fullscreen'); ?></a></li>
                        <li><a href="<?php echo base_url(); ?>admin/change-password"><span class="zmdi zmdi-key zmdi-hc-fw" aria-hidden="true"></span> <?php echo lang('change_password'); ?></a></li>
                        <li><a href="<?php echo base_url(); ?>admin/edit-profile"><span class="zmdi zmdi-account zmdi-hc-fw" aria-hidden="true"></span>  <?php echo lang('edit_profile'); ?></a></li>
                        <li><a href="javascript:void(0);" onclick="showConfirmationBox('<?php echo lang('are_you_sure'); ?>','<?php echo lang('are_you_sure_logout'); ?>','<?php echo lang('yes'); ?>','<?php echo lang('no'); ?>','<?php echo base_url(); ?>admin/dashboard/logout/<?php echo $this->login_session_key; ?>')"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> <?php echo lang('logout'); ?></a></li>
                    </ul>
                </li>
                
                <!-- Time -->
                <li class="pull-right hidden-xs" style="direction: ltr;">
                    <div id="time">
                        <span id="t-hours"></span>
                        <span id="t-min"></span>
                        <span id="t-sec"></span>
                    </div>
                </li>
            </ul>
        </header>