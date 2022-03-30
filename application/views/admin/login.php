<html lang="<?php echo DEFAULT_LANGUAGE_CODE; ?>">
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <title><?php echo SITE_NAME; ?> :: <?php echo (isset($title)) ? $title : lang('admin'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <meta name="format-detection" content="telephone=no">
        <meta charset="UTF-8">
        <meta name="description" content="<?php echo SITE_NAME; ?>">
        <meta name="keywords" content="<?php echo SITE_NAME; ?>">
        <link rel="icon" href="<?php echo base_url(); ?>assets/admin/img/favicon.ico">
        <link href="<?php echo base_url(); ?>assets/admin/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/admin/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/admin/css/app.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/nprogress.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/toastr.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/admin/css/custom.css" rel="stylesheet">
    </head>
    <body class="login-content">

        <!-- Login -->
        <div class="lc-block toggled" id="l-login">
            <form id="login-form">
                <div class="lcb-float" style="margin-bottom:10px;">
                    <img src="<?php echo base_url(); ?>assets/img/logo.png" alt="logo">
                </div>
                 <strong><?php echo lang('signin'); ?></strong><br/><br/>
                <div class="form-group">
                    <input type="text" class="form-control input" required id="email" name="email" placeholder="<?php echo lang('email_address'); ?>/<?php echo lang('account_number'); ?>">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control input" required id="password" name="password" placeholder="<?php echo lang('password'); ?>">
                </div>
                <div class="clearfix"></div>
                <div class="p-relative ">
                    <div class="checkbox cr-alt">
                        <label class="c-gray">
                            <input type="checkbox" checked name="remember_me" value="1">
                            <i class="input-helper"></i>
                           <?php echo lang('keep_me_signed_in'); ?>
                        </label>
                    </div>
                    <br/>
                    <!-- <a href="javascript:void(0);" onclick="$('form#login-form').addClass('hidden');$('form#forgot-form').removeClass('hidden');">Forgot password?</a> -->
                </div>
                <?php if(Is_GOOGLE_RECAPTCHA) { ?>
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                <?php } ?>
                <button class="btn btn-block btn-primary btn-float m-t-25 login-btn"><?php echo lang('signin'); ?></button>
            </form>
            <form id="forgot-form" class="hidden">
                <div class="lcb-float" style="margin-bottom:10px;"><img src="<?php echo base_url(); ?>assets/img/logo.png" alt="logo"></div>
                 <!-- <strong>Forgot Password</strong><br/><br/> -->
                <div class="form-group">
                    <input type="text" class="form-control input" required="" id="email" name="email" placeholder="Email Address">
                </div>
                <div class="clearfix"></div>
                <div class="p-relative ">
                    <a href="javascript:void(0);" onclick="$('form#login-form').removeClass('hidden');$('form#forgot-form').addClass('hidden');">Login?</a>
                </div>
                <!-- <button class="btn btn-block btn-primary btn-float m-t-25 forgot-btn">Forgot passwrod</button> -->
            </form>
        </div>
        
        <?php if(Is_GOOGLE_RECAPTCHA) { ?>
            <script src="<?php echo isSSL(); ?>://www.google.com/recaptcha/api.js?render=<?php echo GOOGLE_RECAPTCHA_SITE_KEY; ?>&hl=iw"></script>
        <?php } ?>
        <script type="text/javascript">
            var api_url  = "<?php echo ADMIN_API_URL; ?>";
            var is_google_recaptcha  = parseInt("<?php echo Is_GOOGLE_RECAPTCHA; ?>");
            if(is_google_recaptcha){
                grecaptcha.ready(function () {
                    grecaptcha.execute('<?php echo GOOGLE_RECAPTCHA_SITE_KEY; ?>', { action: 'contact' }).then(function (token) {
                        var recaptchaResponse = document.getElementById('recaptchaResponse');
                        recaptchaResponse.value = token;
                    });
                });
            }
        </script>

        <!-- Javascript Libraries -->
        <script src="<?php echo base_url(); ?>assets/admin/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/admin/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/admin/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/admin/js/functions.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/nprogress.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/toastr.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/admin/js/custom/login.js"></script>

        <!--  Error & Success Messages -->
        <script type="text/javascript">
        $(document).ready(function(){
          <?php if($this->session->flashdata('error')){ ?>
            let errorMsg = "<?php echo $this->session->flashdata('error') ?>";
            showToaster('error',lang('error'),errorMsg)
          <?php } ?>
          <?php if($this->session->flashdata('success')){ ?>
            let successMsg = "<?php echo $this->session->flashdata('success') ?>";
            showToaster('success',lang('success'),successMsg)
          <?php } ?>
          <?php if($this->session->flashdata('logout')){ ?>
            localStorage.removeItem('login_session_key');
            localStorage.removeItem('user_guid');
          <?php } ?>
        });
        var loading = "<?php echo lang('loading'); ?>";
        var success = "<?php echo lang('success'); ?>";
        var error = "<?php echo lang('error'); ?>";
        var login = "<?php echo lang('login'); ?>";
        </script>
    </body>
</html>
