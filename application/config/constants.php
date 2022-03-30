<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE); 

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code



/*---------Site Settings--------*/
/*------------------------------*/  

define('FROM_EMAIL', 'work@accfm.co.uk');
define('ADMIN_EMAIL', (ENVIRONMENT == 'local') ? 'soravgarg123@gmail.com' : 'arik@amitex.co.il');
define('SITE_NAME', 'Amitex APP');
define('DEFAULT_USER_IMG', 'default-148.png');
define('DEFAULT_USER_IMG_PATH', 'assets/img/default-148.png');
define('DEFAULT_NO_IMG', 'noimagefound.jpg');
define('DEFAULT_NO_IMG_PATH', 'assets/img/noimagefound.jpg');
define('DATE_FORMAT',"%Y-%m-%d %H:%i:%s"); /* dd-mm-yyyy */
define('SESSION_EXPIRE_HOURS', 2);
define('PAGESIZE_DEFAULT', 500);
define('CURRENCY', 'Shekel');
define('CURRENCY_SYMBOL', '₪');
define('DEFAULT_LANGUAGE_CODE', 'he');
define('LANGUAGE_FILE_NAME', 'amitex');

/* Messages constants */
define('GENERAL_ERROR', 'Some error occured, please try again.');
define('USER_VERIFICATION', 'Currently your profile is not verified, please verfiy your email id');
define('BLOCK_USER_MSG', 'Your profile has been blocked. Please contact to our support team');
define('DEACTIVATE_USER', 'Currently your profile is deactivated. Please contact to our support team');
define('SESSION_EXPIRED', 'Your session has been expired, please re-login to access our app');

switch (ENVIRONMENT)
{
  case 'local':
    /*Paths*/
    define('SITE_HOST', 'http://localhost/');
    define('ROOT_FOLDER', 'amitex-app/');

    /*Site Related Settings*/
    define('ADMIN_SAVE_LOG', false);
    define('API_SAVE_LOG', false);

    /* Google Recaptcha */
    define('Is_GOOGLE_RECAPTCHA', true);
    define('GOOGLE_RECAPTCHA_SITE_KEY', '6LejI7MZAAAAAF-j1Lsvu7qDsN01wdbWxYXo_UKZ');
    define('GOOGLE_RECAPTCHA_SECRET_KEY ', '6LejI7MZAAAAAHcHbwEtu2VGlGIhvlVrZ4Aafgmb');

  break;
  case 'testing':
    
    /*Paths*/
    define('SITE_HOST', '');
    define('ROOT_FOLDER', '');

    /*Site Related Settings*/
    define('ADMIN_SAVE_LOG', false);
    define('API_SAVE_LOG', false);

    /* Google Recaptcha */
    define('Is_GOOGLE_RECAPTCHA', true);
    define('GOOGLE_RECAPTCHA_SITE_KEY', '6LejI7MZAAAAAF-j1Lsvu7qDsN01wdbWxYXo_UKZ');
    define('GOOGLE_RECAPTCHA_SECRET_KEY ', '6LejI7MZAAAAAHcHbwEtu2VGlGIhvlVrZ4Aafgmb');

  break;
  case 'demo':
    /*Paths*/
    define('SITE_HOST', 'http://coderadda.in/');
    define('ROOT_FOLDER', 'amitex-app/');

    /*Site Related Settings*/
    define('ADMIN_SAVE_LOG', false);
    define('API_SAVE_LOG', false);

    /* Google Recaptcha */
    define('Is_GOOGLE_RECAPTCHA', true);
    define('GOOGLE_RECAPTCHA_SITE_KEY', '6LerdFsaAAAAAOJQZKtKHYB6JQVRjCUfK1f758tB');
    define('GOOGLE_RECAPTCHA_SECRET_KEY ', '6LerdFsaAAAAALWxWsFqi1mAQlqDtVWnD5e4b2TD');

  break;
  case 'production':
    /*Paths*/
    define('SITE_HOST', 'https://amitex.dtmedia.co.il/');
    define('ROOT_FOLDER', '');

    /*Site Related Settings*/
    define('ADMIN_SAVE_LOG', false);
    define('API_SAVE_LOG', false);

    /* Google Recaptcha */
    define('Is_GOOGLE_RECAPTCHA', false);
    define('GOOGLE_RECAPTCHA_SITE_KEY', '6LejI7MZAAAAAF-j1Lsvu7qDsN01wdbWxYXo_UKZ');
    define('GOOGLE_RECAPTCHA_SECRET_KEY ', '6LejI7MZAAAAAHcHbwEtu2VGlGIhvlVrZ4Aafgmb');

  break;
}

define('BASE_URL', SITE_HOST . ROOT_FOLDER);
define('ADMIN_API_URL', BASE_URL . 'admin/api/');
define('ASSET_BASE_URL', BASE_URL . 'assets/');
define('PROFILE_PICTURE_URL', BASE_URL . 'uploads/users/default-148.png');