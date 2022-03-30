<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Mailchimp configuration
*/

$config['mailchimp_api_key']  = 'cbac4473f2b3cc6caa19fb45f3b71d4e-us14';
$config['mailchimp_list_key'] = '70d57c3b77';

/** 
* Exchange currency converter configuration
*/

$config['exchange_app_id']  = 'fb3be8dfd01a447596ddd27c430e48ab-us14';

/** 
* Timezone converter
*/

$config['deafult_timezone']   = 'America/New_York';
$config['deafult_idetifire']  = '-05:00';

/** 
* Bitly short url generator
*/

$config['bitlyLogin']   = '123-456-789';
$config['bitlyAPIKey']  = '123456789';

/** 
* Stripe payment gateway details
*/

$config['stripe_secret_key']       = 'sk_test_htZuRrdkLDTjNGanHlkK8F1M';
$config['stripe_publishable_key']  = 'pk_test_KpEfqzfA4YdlqiGb4HPiOsTF';

/** 
* Braintree payment gateway details
*/

$config['briantree_environment'] = 'sandbox';
$config['briantree_merchantId']  = 'scfckv822kwbrqjf';
$config['briantree_publicKey']   = 'ty7d9st7srzxmz7b';
$config['briantree_privateKey']  = 'd5a395c82fe304d17957795f6fbd94bc';

/** 
* SMTP Details (Gmail account details)
*/

$config['smtpUsername']  = 'sorav@gmail.com';
$config['smtpPassword']  = '123456789';
$config['fromEmail']     = FROM_EMAIL;
$config['fromName']      = SITE_NAME;

/** 
* Worldpay payment gateway details
*/

$config['worldpay_merchant_id'] = 'e71f511d-40c9-436b-b5ae-4b139dbf9aa7';
$config['worldpay_service_key'] = 'T_S_7d509444-e4b5-4227-9741-a593ae31c5ec';
$config['worldpay_client_key']  = 'T_C_059d303f-e3b6-4921-bdcc-2d9f99f8beee';



/* End of file miscellaneous.php */
/* Location: ./system/application/config/miscellaneous.php */