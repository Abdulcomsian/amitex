<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <title><?php echo SITE_NAME; ?> :: <?php echo (isset($title)) ? $title : 'Admin'; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <meta name="format-detection" content="telephone=no">
        <meta charset="UTF-8">
        <meta name="description" content="<?php echo SITE_NAME; ?> - You just bring it home">
        <meta name="keywords" content="<?php echo SITE_NAME; ?>">
        <link rel="icon" href="../assets/admin/img/favicon.ico">
        <link href="../assets/admin/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="../assets/admin/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="../assets/admin/css/app.min.css" rel="stylesheet">
        <link href="../assets/css/nprogress.css" rel="stylesheet">
        <link href="../assets/css/toastr.min.css" rel="stylesheet">
        <link href="../assets/admin/css/custom.css" rel="stylesheet">
    </head>
    <body class="login-content">

        <!-- Login -->
        <div class="lc-block toggled" id="l-login">
            <form id="reset-password-form">
                <input type="hidden" value="<?php echo $this->input->get('token'); ?>" name="user_token">
                <div class="lcb-float" style="margin-bottom:10px;"><img src="../assets/img/logo.png" alt="logo"></div>
                 <strong>Reset Password</strong><br/><br/>
	            <div class="form-group">
	                <input type="password" class="form-control input prevent-copy-paste" required id="new_password" name="new_password" placeholder="New Password">
	            </div>
	            <div class="form-group">
	                <input type="password" class="form-control input prevent-copy-paste" required id="confirm_password" name="confirm_password" placeholder="Confirm Password">
	            </div>
	            <div class="clearfix"></div>
	            <div class="p-relative ">
                    <a href="../admin/login">Login?</a>
	            </div>
                <button class="btn btn-block btn-primary btn-float m-t-25 reset-password-btn">Reset Password</button>
        	</form>
        </div>
        <script type="text/javascript">
            var api_url  = "<?php echo ADMIN_API_URL; ?>";
        </script>

        <!-- Javascript Libraries -->
        <script src="../assets/admin/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="../assets/admin/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="../assets/admin/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="../assets/admin/js/functions.js"></script>
        <script src="../assets/js/nprogress.js"></script>
        <script src="../assets/js/toastr.min.js"></script>
        <script src="../assets/admin/js/custom/login.js"></script>

        <!--  Error & Success Messages -->
        <script type="text/javascript">
        $(document).ready(function(){
          <?php if($this->session->flashdata('error')){ ?>
            let errorMsg = "<?php echo $this->session->flashdata('error') ?>";
            showToaster('error','Error !',errorMsg)
          <?php } ?>
          <?php if($this->session->flashdata('success')){ ?>
            let successMsg = "<?php echo $this->session->flashdata('success') ?>";
            showToaster('success','Success',successMsg)
          <?php } ?>
          <?php if($this->session->flashdata('logout')){ ?>
            localStorage.removeItem('login_session_key');
            localStorage.removeItem('user_guid');
          <?php } ?>
        });
        </script>
    </body>
</html>