<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Custom Helper
* Author: Sorav Garg
* Author Email: soravgarg123@gmail.com
* Author Phone No: +919074939905
* version: 1.0
*/

/**
 * [To print array]
 * @param array $arr
*/
if ( ! function_exists('pr')) {
  function pr($arr)
  {
    echo '<pre>'; 
    print_r($arr);
    echo '</pre>';
    die;
  }
}

/**
 * [To get product discount price]
 * @param float $product_variant_price
 * @param string $is_premium
 * @param float $discount
*/
if ( ! function_exists('getProductDiscountPrice')) {
  function getProductDiscountPrice($product_variant_price,$is_premium,$discount)
  {
    if($is_premium == 'No'){
      $discount = (float) $discount;
      if($discount > 0){
        $product_variant_price = $product_variant_price - (($product_variant_price * $discount)/100);
      }
    }
    return round($product_variant_price,2);
  }
}

/**
 * [To check if site has SSL]
*/
if ( ! function_exists('isSSL')) {
  function isSSL()
  {
    if(isset($_SERVER['HTTPS'] ) ) {
      return 'https';
    }else{
      return 'http';
    }
  }
}

/**
 * [To all languages]
*/
if ( ! function_exists('languages')) {
  function languages()
  {
    return array('he' => 'Hebrew');
  }
}

/**
 * [To admin emails]
*/
if ( ! function_exists('get_admin_emails')) {
  function get_admin_emails()
  {
    $CI = & get_instance();
    $query = $CI->db->query('SELECT email_addresses FROM tbl_users WHERE user_type_id = 1 LIMIT 1');
    if($query->num_rows() == 0){
      return array();
    }
    return json_decode($query->row()->email_addresses,TRUE);
  }
}

/**
 * [To print last query]
*/
if ( ! function_exists('lq')) {
  function lq()
  {
    $CI = & get_instance();
    echo $CI->db->last_query();
    die;
  }
}

/**
 * [To load language]
*/
if ( ! function_exists('load_lang')) {
  function load_lang()
  {
    $CI = & get_instance();
    $lang = 'hebrew';
    $CI->config->set_item('language', $lang);
    $CI->lang->load(LANGUAGE_FILE_NAME, $lang);
  }
}

/**
 * [To get language data]
 * @param string $lang_key
*/
if ( ! function_exists('lang')) {
  function lang($lang_key)
  {
    $CI = & get_instance();
    return ($CI->lang->line($lang_key)) ? html_entity_decode($CI->lang->line($lang_key)) : '';
  }
}

/**
 * [To get job order no]
 * @param string $job_id
*/
if ( ! function_exists('getJobOrderNo')) {
  function getJobOrderNo($job_id)
  {
    return 'ACC'.addZero($job_id);
  }
}

/**
 * [To get job invoice no]
 * @param string $job_id
*/
if ( ! function_exists('getJobInvoiceNo')) {
  function getJobInvoiceNo($job_id)
  {
    return 'ACCINV'.addZero($job_id);
  }
}

/**
 * [To get user status color]
 * @param string $status
*/
if ( ! function_exists('getUserStatusColor')) {
  function getUserStatusColor($status)
  {
    switch ($status) {
      case 'Pending':
      case 'In Progress':
        return '#ff9800';
        break;
      case 'Verified':
      case 'Completed':
        return '#4caf50';
        break;
      case 'Blocked':
        return '#f44336';
        break;
      case 'Upcoming':
        return '#ff5722';
        break;
    }
  }
}

/**
 * [To get user status name]
 * @param string $status
*/
if ( ! function_exists('getUserStatusName')) {
  function getUserStatusName($status)
  {
    switch ($status) {
      case 'Pending':
        return lang('pending');
        break;
      case 'Verified':
        return lang('verified');
        break;
      case 'Blocked':
        return lang('blocked');
        break;
    }
  }
}

/**
 * [To get user gender]
 * @param string $gender
*/
if ( ! function_exists('getGenderName')) {
  function getGenderName($gender)
  {
    switch ($gender) {
      case 'Male':
        return lang('male');
        break;
      case 'Female':
        return lang('female');
        break;
      case 'Other':
        return lang('other');
        break;
    }
  }
}

/**
 * [To get database error message]
*/
if ( ! function_exists('db_err_msg')) {
  function db_err_msg()
  {
    $CI = & get_instance();
    $error = $CI->db->error();
    if(isset($error['message']) && !empty($error['message'])){
      return 'Database error - '.$error['message'];
    }else{
      return FALSE;
    }
  }
}

/**
 * [To insert email in mailchimp list]
 * @param string $email
 * @param string $listid
 * @param string $fname
 * @param string $lname
*/
if ( ! function_exists('mailchimp_manage_list'))
{
  function mailchimp_manage_list($email,$listid, $fname = '',$lname = '')
  {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://us13.api.mailchimp.com/3.0/lists/".$listid);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"members\": [{\"email_address\": \"".$email."\", \"status\": \"subscribed\"}], \"update_existing\": true}");
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_USERPWD, SITE_NAME . ":" . MAILCHIMP_API_KEY);
      $headers = array();
      $headers[] = "Content-Type: application/x-www-form-urlencoded";
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      $result = curl_exec($ch);
      if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
      }
      curl_close ($ch);
  }
}

/**
 * [To capitalize string]
 * @param string $str
*/
if ( ! function_exists('capitalize')) {
  function capitalize($str){
    if(!empty($str))
    {
      return ucwords(str_replace("_", " ", strtolower($str)));
    }
  }
}

/**
 * [Content Types]
*/
if ( ! function_exists('get_content_types')) {
  function get_content_types()
  {
    $types = array('aboutus','contact_us','privacy_policy','terms');
    return $types;
  }
}

/**
 * [To check url exist or not]
 * @param string $url
*/
if ( ! function_exists('is_url_exist')) {
  function is_url_exist($url)
  {
    $file_headers = @get_headers($url);
    if(!$file_headers || strpos($file_headers[0], '404') !== FALSE) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }
}

/**
 * [To convert base64 string to image]
 * @param string $imageData
*/
if ( ! function_exists('base64ToImage')) 
{
  function base64ToImage($imageData){
      $base64string = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
      $file_path = 'uploads/logo/'.get_file_name().'.png';
      $im = imagecreatefromstring($base64string);
      if ($im !== false) {
          header('Content-Type: image/png');
          imagepng($im,$file_path);
          imagedestroy($im);
          chmod($file_path, 0777);
          return $file_path;
      }
      else {
        return "";
      }
  }
}

/**
 * [To currency converter]
 * @param string $from_Currency
 * @param string $to_Currency
 * @param float $amount
*/
if (!function_exists('currencyConverter')) {
  function currencyConverter($from_Currency,$to_Currency,$amount) {
    $from_Currency = urlencode($from_Currency);
    $to_Currency = urlencode($to_Currency);
    $get = file_get_contents("https://finance.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency");
    $get = explode("<span class=bld>",$get);
    $get = explode("</span>",$get[1]);
    $converted_currency = preg_replace("/[^0-9\.]/", null, $get[0]);
    return round($converted_currency,2);
    }
}

/**
 * [To check url is valid or not]
 * @param string $weburl
*/
if ( ! function_exists('is_valid_url')) {
  function is_valid_url($weburl)
  {
    // Remove invalid characters from URL
    $weburl = filter_var($weburl, FILTER_SANITIZE_URL);

    // Validate web url
    if (!filter_var($weburl, FILTER_VALIDATE_URL) === false) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
}

/**
 * [Get Upload File Name]
*/
if ( ! function_exists('get_file_name')) {
  function get_file_name()
  {
    $file_name = time().'-'.get_guid();
    return @trim($file_name);
  }
}

/**
 * [Get content Type name,Title]
 * @param string $type_name
 * @param string $required_column
*/
if ( ! function_exists('get_content_detail')) {
  function get_content_detail($type_name,$required_column)
  {
    switch ($type_name) {
        case 'aboutus':
          $title = 'About US';
          $type  = 'ABOUT_US';
          break;
        case 'contact_us':
          $title = 'Contact US';
          $type  = 'CONTACT_US';
          break;
        case 'privacy_policy':
          $title = 'Privay Policy';
          $type  = 'PRIVACY_POLICY';
          break;
        case 'terms':
          $title = 'Terms & Conditions';
          $type  = 'TERMS_CONDITIONS';
          break;
        default:
          $title = '';
          $type = '';
          break;
      }
    return $$required_column;
  }
}

/**
 * [To parse html]
 * @param string $str
*/
if (!function_exists('parseHTML')) {
  function parseHTML($str) {
    $str = str_replace('src="//', 'src="https://', $str);
    return $str;
  }
}

/**
 * [To create directory]
 * @param string $folder_path
*/
if (!function_exists('make_directory')) {
  function make_directory($folder_path) {
    if (!file_exists($folder_path)) {
      mkdir($folder_path, 0777, true);
    }else{
      @chmod($folder_path, 0777);
    }
  }
}

/**
 * [To get previous dates]
 * @param int $no_of_days
*/
if ( ! function_exists('get_previous_dates')) {
  function get_previous_dates($no_of_days)
  {
    $dates_arr = array(); 
    $timestamp = time();
    for ($i = 0 ; $i < (int) $no_of_days ; $i++) {
        $dates_arr[] = date('Y-m-d', $timestamp);
        $timestamp -= 24 * 3600;
    }
    return $dates_arr;
  }
}


/**
 * [To get dates difference]
 * @param date $from
 * @param date $to
*/
if ( ! function_exists('diff_in_weeks_and_days')) {
  function diff_in_weeks_and_days($from, $to) 
  {
      $day   = 24 * 3600;
      $from  = strtotime($from);
      $to    = strtotime($to) + $day;
      $diff  = abs($to - $from);
      $weeks = floor($diff / $day / 7);
      $days  = $diff / $day - $weeks * 7;
      $out   = array();
      if ($weeks) $out[] = "$weeks Week" . ($weeks > 1 ? 's' : '');
      if ($days)  $out[] = "$days Day" . ($days > 1 ? 's' : '');
      return implode(', ', $out);
  }
}

/**
 * [To print number in standard format with 0 prefix]
 * @param int $no
*/
if ( ! function_exists('addZero')) {
  function addZero($no)
  {
    if($no >= 10){
      return $no;
    }else{
      return "0".$no;
    }
  }
}

/**
 * [To get current datetime]
*/
if ( ! function_exists('datetime')) {
  function datetime($default_format='Y-m-d H:i:s')
  {
    $datetime = date($default_format);
    return $datetime;
  }
}

/**
 * [To get age from birth date]
 * @param string $from_date
*/
if ( ! function_exists('get_age_from_birth_date')) {
  function get_age_from_birth_date($from_date)
  {
    $birthDate = date('m/d/Y',strtotime($from_date));
    $birthDate = explode("/", $birthDate);
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
            ? ((date("Y") - $birthDate[2]) - 1)
            : (date("Y") - $birthDate[2]));
    return $age;
  }
}

/**
 * [To sort multi-dimensional array]
 * @param array $response
 * @param string $column
 * @param string $type
*/
if ( ! function_exists('sortarr')) {
  function sortarr($response,$column,$type)
  {
    $arr =array();
    foreach ($response as $r) {
      $arr[] = $r->$column; // In Object
    }
    if($type == 'ASC'){
      array_multisort($arr,SORT_ASC,$response);
    }else{
      array_multisort($arr,SORT_DESC,$response);
    }
    return $response;
  }
}

/**
 * [To convert date time format]
 * @param datetime $datetime
 * @param string $format
*/
if ( ! function_exists('convertDateTime')) {
  function convertDateTime($datetime,$format='dS F Y, h:i A')
  {
    if(empty($datetime)) return "N/A";
    $convertedDateTime = date($format,strtotime($datetime));
    return $convertedDateTime;
  }
}


/**
 * [To encode string]
 * @param string $str
*/
if ( ! function_exists('encoding')) {
  function encoding($str){
      $one = serialize($str);
      $two = @gzcompress($one,9);
      $three = addslashes($two);
      $four = base64_encode($three);
      $five = strtr($four, '+/=', '-_.');
      return $five;
  }
}

/**
 * [To decode string]
 * @param string $str
*/
if ( ! function_exists('decoding')) {
  function decoding($str){
    $one = strtr($str, '-_.', '+/=');
      $two = base64_decode($one);
      $three = stripslashes($two);
      $four = @gzuncompress($three);
      if ($four == '') {
          return "z1"; 
      } else {
          $five = unserialize($four);
          return $five;
      }
  }
}

/**
 * [To export csv file from array]
 * @param string $fileName
 * @param array $assocDataArray
 * @param array $headingArr
*/
if ( ! function_exists('exportCSV')) {
  function exportCSV($fileName,$assocDataArray,$headingArr)
  {

    // header('Content-Type: text/csv; charset=utf-8');
    // header('Content-Disposition: attachment; filename='.$fileName);
    ob_clean();
    header('Content-type: application/csv');
    header('Content-Disposition: attachment; filename=' . $fileName);
    header("Content-Transfer-Encoding: UTF-8");
    $output = fopen('php://output', 'w');
    fputcsv($output, $headingArr);
    foreach ($assocDataArray as $key => $value) { 
        fputcsv($output, $value);
    }
    fclose($output);
     exit;
  }
}

/**
 * [To check number is digit or not]
 * @param int $element
*/
if ( ! function_exists('is_digits')) {
  function is_digits($element){ // for check numeric no without decimal
      return !preg_match ("/[^0-9]/", $element);
  }
}

/**
 * [To get all months list]
*/
if ( ! function_exists('getMonths')) {
  function getMonths(){
    $monthArr = array('January','February','March','April','May','June','July','August','September','October','November','December');
    return $monthArr ;
  }
}

/**
 * [To upload all files]
 * @param string $subfolder
 * @param string $ext
 * @param int $size
 * @param int $width
 * @param int $height
 * @param string $filename
 * @param string $filename_type
*/
if ( ! function_exists('fileUploading')) {
  function fileUploading($filename,$subfolder,$ext,$size="",$width="",$height="",$filename_type="custom")
  {
      $CI = & get_instance();

      /* To create directory */
      $directory_path = 'uploads/'.$subfolder; 
      make_directory($directory_path);
      
      $config['upload_path']   = $directory_path.'/';
      $config['allowed_types'] = $ext; 
      if($size){
        $config['max_size']   = 100; 
      }
      if($width){
        $config['max_width']  = 1024; 
      }
      if($height){
        $config['max_height'] = 768;  
      }
      if($filename_type == "custom"){
        $config['file_name'] = get_file_name();
      }
      $CI->load->library('upload', $config);
      $CI->upload->initialize($config);
      if (!$CI->upload->do_upload($filename)) {
        $error = array('error' => strip_tags($CI->upload->display_errors())); 
        return $error;
      }
     else { 
        $data = array('upload_data' => $CI->upload->data()); 
        return $data;
     } 
  }
}

/**
 * [To check autorized user]
 * @param string $return_uri
*/
if ( ! function_exists('is_logged_in')) {
  function is_logged_in($return_uri = '') {
      $ci =&get_instance();
    $user_login = $ci->session->userdata('user_id');
    if(!isset($user_login) || $user_login != true) {
      if($return_uri) {
        $ci->session->set_flashdata('blog_token',time());
        redirect('?return_uri='.urlencode(base_url().$return_uri));  
      } else {
        $ci->session->set_flashdata('blog_token',time());
        redirect("/");  
      }   
    }   
  }
}

/**
 * [To excecute CURL]
 * @param string $Url
 * @param array $jsondata
 * @param array $post
 * @param array $headerData
*/
if (!function_exists('ExecuteCurl'))
{

    function ExecuteCurl($url, $jsondata = '', $post = '', $headerData = [])
    {
        $ch = curl_init();
        $headers = array('Accept: application/json', 'Content-Type: application/json');
        if (!empty($headerData) && is_array($headerData))
        {
            foreach ($headerData as $key => $value)
            {
                $headers[$key] = $value;
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($jsondata != '')
        {
            curl_setopt($ch, CURLOPT_POST, count($jsondata));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
        }

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 50);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        if ($post != '')
        {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $post);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

/**
 * [To send mail]
 * @param string $from
 * @param string $to
 * @param string $subject
 * @param string $message
*/
if ( ! function_exists('send_mail')) {
  function send_mail($message,$subject,$to_email,$from_email="",$attach="")
  {
      $ci = &get_instance();
      $config['mailtype'] = 'html';
      $ci->email->initialize($config);
      if(!empty($from_email)){
        $ci->email->from($from_email,SITE_NAME);
      }else{
        $ci->email->from(FROM_EMAIL,SITE_NAME);
      }
      $ci->email->to($to_email);
      $ci->email->subject($subject);
      $ci->email->message($message);
      if(!empty($attach))
      {
        $ci->email->attach($attach);
      }
      if($ci->email->send()) {  
        return true;
      } else {
        return false;
      }
  }
}

/**
 * [To send smtp mail] // AWS SMTP (SES)
 * @param string $to_email
 * @param string $subject
 * @param string $message
*/
if ( ! function_exists('send_smtp_mail')) {
  function send_smtp_mail($to_email,$subject,$message)
  {
    require '/var/www/html/vendor/autoload.php';
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->setFrom('support@camtestonline.com', SITE_NAME);
    $mail->addAddress($to_email, '');
    $mail->Username = '*************';
    $mail->Password = '*****************';
    $mail->Host     = 'email-smtp.us-east-1.amazonaws.com';
    $mail->Subject  = $subject;
    $mail->Body     = $message;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->isHTML(true);
    if(!$mail->send()) {
      return FALSE;
    } else {
      return TRUE;
    }
  }
}

/**
 * [To send smtp mail] // PHP Mailer
 * @param string $to_email
 * @param string $to_name
 * @param string $subject
 * @param string $message
*/
if ( ! function_exists('php_mailer')) {
  function php_mailer($emails,$to_name = NULL,$subject,$message,$attachment = NULL)
  {
    if($_SERVER['HTTP_HOST'] == 'coderadda.in'){
      $ci = &get_instance();
      $config['mailtype'] = 'html';
      $ci->email->initialize($config);
      $ci->email->from('amitex@coderadda.in',SITE_NAME);
      $ci->email->to((!empty($emails)) ? $emails : ADMIN_EMAIL);
      $ci->email->subject($subject);
      $ci->email->message($message);
      if(!empty($attachment)){
        $ci->email->attach($attachment,'',basename($attachment));
      }
      if($ci->email->send()) {  
        return TRUE;
      } else {
        return FALSE;
      }
    }else{
      require_once APPPATH . 'libraries/php-mailer/class.phpmailer.php';
      $mail = new PHPMailer();
      $mail->IsSMTP();                                      
      $mail->Host = "mailgw.netvision.net.il"; 
      $mail->SMTPAuth = true;     
      $mail->Port = 25;       
      $mail->CharSet = 'UTF-8';                          
      $mail->Username = "amitex2@netvision.net.il"; 
      $mail->Password = 'amitex06'; 
      $mail->From     = 'amitex2@netvision.net.il';
      $mail->FromName = SITE_NAME;
      if(!empty($emails)){
        foreach($emails as $email){
          $mail->AddAddress($email, $to_name);
        }
      }else{
        $mail->AddAddress(ADMIN_EMAIL, $to_name);
      }
      $mail->MsgHTML($message);
      $mail->IsHTML(true); // send as HTML
      $mail->Subject = $subject;
      if(!empty($attachment)){
        $mail->AddAttachment($attachment, basename($attachment));
      }
      if(!$mail->Send()){
        return FALSE;
      }else{
        return TRUE;
      }
    }
  }
}

/**
 * extract_value
 * @return string
 */
if (!function_exists('extract_value'))
{
    function extract_value($array, $key, $default = "")
    {
        $CI = & get_instance();
        $final_value = $default;
        if(isset($array[$key]) && $array[$key] != ''){
          $final_value = @trim(strip_tags($array[$key]));
        }
        return $final_value;
    }
}

if ( ! function_exists('crypto_rand_secure')) {
  function crypto_rand_secure($min, $max) 
  {
      $range = $max - $min;
      if ($range < 1) return $min;
      $log = ceil(log($range, 2));
      $bytes = (int) ($log / 8) + 1;
      $bits = (int) $log + 1;
      $filter = (int) (1 << $bits) - 1;
      do {
          $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
          $rnd = $rnd & $filter;
      } while ($rnd >= $range);
      return $min + $rnd;
  }
}

/**
 * [To generate random token]
 * @param string $length
*/
if ( ! function_exists('generateToken')) {
  function generateToken($length) 
  {
      $token = "";
      $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
      $codeAlphabet.= "0123456789";
      $max = strlen($codeAlphabet) - 1;
      for ($i=0; $i < $length; $i++) {
          $token .= $codeAlphabet[crypto_rand_secure(0, $max)];
      }
      return $token;
  }
}

/**
 * [To get live videos thumb Youtube,Vimeo]
 * @param string $videoString
*/
if ( ! function_exists('getVideoThumb')) {
  function getVideoThumb($videoString = null){
      // return data
      $videos = array();
      if (!empty($videoString)) {
          // split on line breaks
          $videoString = stripslashes(trim($videoString));
          $videoString = explode("\n", $videoString);
          $videoString = array_filter($videoString, 'trim');
          // check each video for proper formatting
          foreach ($videoString as $video) {
              // check for iframe to get the video url
              if (strpos($video, 'iframe') !== FALSE) {
                  // retrieve the video url
                  $anchorRegex = '/src="(.*)?"/isU';
                  $results = array();
                  if (preg_match($anchorRegex, $video, $results)) {
                      $link = trim($results[1]);
                  }
              } else {
                  // we already have a url
                  $link = $video;
              }
              // if we have a URL, parse it down
              if (!empty($link)) {
                  // initial values
                  $video_id = NULL;
                  $videoIdRegex = NULL;
                  $results = array();
                  // check for type of youtube link
                  if (strpos($link, 'youtu') !== FALSE) {
                      if (strpos($link, 'youtube.com') !== FALSE) {
                          // works on:
                          // http://www.youtube.com/embed/VIDEOID
                          // http://www.youtube.com/embed/VIDEOID?modestbranding=1&amp;rel=0
                          // http://www.youtube.com/v/VIDEO-ID?fs=1&amp;hl=en_US
                          $videoIdRegex = '/youtube.com\/(?:embed|v){1}\/([a-zA-Z0-9_]+)\??/i';
                      } else if (strpos($link, 'youtu.be') !== FALSE) {
                          // works on:
                          // http://youtu.be/daro6K6mym8
                          $videoIdRegex = '/youtu.be\/([a-zA-Z0-9_]+)\??/i';
                      }
                      if ($videoIdRegex !== NULL) {
                          if (preg_match($videoIdRegex, $link, $results)) {
                              $video_str = 'http://www.youtube.com/v/%s?fs=1&amp;autoplay=1';
                              $thumbnail_str = 'http://img.youtube.com/vi/%s/2.jpg';
                              $fullsize_str = 'http://img.youtube.com/vi/%s/0.jpg';
                              $video_id = $results[1];
                          }
                      }
                  }
                  // handle vimeo videos
                  else if (strpos($video, 'vimeo') !== FALSE) {
                      if (strpos($video, 'player.vimeo.com') !== FALSE) {
                          // works on:
                          // http://player.vimeo.com/video/37985580?title=0&amp;byline=0&amp;portrait=0
                          $videoIdRegex = '/player.vimeo.com\/video\/([0-9]+)\??/i';
                      } else {
                          // works on:
                          // http://vimeo.com/37985580
                          $videoIdRegex = '/vimeo.com\/([0-9]+)\??/i';
                      }
                      if ($videoIdRegex !== NULL) {
                          if (preg_match($videoIdRegex, $link, $results)) {
                              $video_id = $results[1];
                              // get the thumbnail
                              try {
                                  $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$video_id.php"));
                                  if (!empty($hash) && is_array($hash)) {
                                      $video_str = 'http://vimeo.com/moogaloop.swf?clip_id=%s';
                                      $thumbnail_str = $hash[0]['thumbnail_small'];
                                      $fullsize_str = $hash[0]['thumbnail_large'];
                                  } else {
                                      // don't use, couldn't find what we need
                                      unset($video_id);
                                  }
                              } catch (Exception $e) {
                                  unset($video_id);
                              }
                          }
                      }
                  }
                  // check if we have a video id, if so, add the video metadata
                  if (!empty($video_id)) {
                      // add to return
                      $videos[] = array(
                          'url' => sprintf($video_str, $video_id),
                          'thumbnail' => sprintf($thumbnail_str, $video_id),
                          'fullsize' => sprintf($fullsize_str, $video_id)
                      );
                  }
              }
          }
      }
      // return array of parsed videos
      return $videos;
  }
}

if ( ! function_exists('getVimeoVideoIdFromUrl')) {
  function getVimeoVideoIdFromUrl($url = '') {
      $regs = array();
      $id = '';
      if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $regs)) {
          $id = $regs[3];
      }
      return $id;
  }
}

/**
 * [To get embedded live video url]
 * @param string $url
*/
if ( ! function_exists('convertYoutube')) {
  function convertYoutube($url) {
      $str_url =  preg_replace(
          "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
          "<iframe src=\"//www.youtube.com/embed/$2\" allowfullscreen></iframe>",
          $url
      );
      $str_url = str_replace(array('m.youtube','m.youtu.be'), array('youtube','youtube.com'), $str_url);
      return $str_url;
  }
}

/**
 * [To get embedded live video url]
 * @param string $url
 * @param string $type
*/
if ( ! function_exists('parseLiveVideo')) {
  function parseLiveVideo($url,$type = 'youtube') {
    $parsedURL = '';
    switch ($type) {
      case 'youtube':
        $parsedURL = convertYoutube($url);
        break;
      case 'vimeo':
        $vid  = getVimeoVideoIdFromUrl($url);
        $parsedURL = 'https://player.vimeo.com/video/'.$vid;
        break;
      default:
        $parsedURL = '';
        break;
    }
    return $parsedURL;
  }
}

/**
 * [To export DOC file]
 * @param string $html
 * @param string $filename
*/
if ( ! function_exists('exportDOCFile')) {
  function exportDOCFile($html,$filename = ''){
    $$filename = (!empty($filename)) ? $filename : 'document';
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename=".$filename.".doc");
    echo $html;
  }
}

/**
 * [To get user ip address]
*/
if (!function_exists('getRealIpAddr'))
{
    function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR']; //'103.15.66.178';//
        }
        return $ip;
    }
}

/**
 * [Create GUID]
 * @return string
 */
if (!function_exists('get_guid'))
{
    function get_guid()
    {
        if (function_exists('com_create_guid'))
        {
            return strtolower(com_create_guid());
        }
        else
        {
            mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            $uuid = substr($charid, 0, 8) . $hyphen
                    . substr($charid, 8, 4) . $hyphen
                    . substr($charid, 12, 4) . $hyphen
                    . substr($charid, 16, 4) . $hyphen
                    . substr($charid, 20, 12);
            return strtolower($uuid);
        }
    }
}

/**
 * [get_domain Get domin based on given url]
 * @param  string $url
 */
if ( ! function_exists('get_domain')) 
{ 
    function get_domain($url)
    {
      $pieces = parse_url($url);
      $domain = isset($pieces['host']) ? $pieces['host'] : '';
      if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
      }
      return false;
    }
}

/**
 * [to check url is 404 or not]
 * @param  string $url
 */
if ( ! function_exists('is_404')) 
{ 
  function is_404($url) {
      $handle = curl_init($url);
      curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

      /* Get the HTML or whatever is linked in $url. */
      $response = curl_exec($handle);

      /* Check for 404 (file not found). */
      $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
      curl_close($handle);

      /* If the document has loaded successfully without any redirection or error */
      if ($httpCode >= 200 && $httpCode < 300) {
          return false;
      } else {
          return true;
      }
  }
}

/**
 * [get_ip_location_details Get location details based on given IP Address]
 * @param  [string] $ip_address [IP Adress]
 * @return [array]           [location details]
 */
if ( ! function_exists('get_ip_location_details')) 
{    
    function get_ip_location_details($ip_address) 
    {
        $url = "http://api.ipinfodb.com/v3/ip-city/?key=" . IPINFODBKEY . "&ip=" . $ip_address . "&timezone=true&format=json";
        $location_data = json_decode(ExecuteCurl($url), true);
        return $location_data;
    }
}

/**
* [geocoding_location_details Get location details based on given geo coordinate]
* @param  [string] $latitude  [latitude]
* @param  [string] $longitude [longitude]
* @return [array]            [location details]
*/
if(!function_exists('geocoding_location_details'))
{    
    function geocoding_location_details($latitude, $longitude)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude;
        $details = json_decode(file_get_contents($url));
        return $details;
    }
}

/**
* [To Format Bytes]
* @param  [integer] $bytes
*/
if (!function_exists('formatSizeUnits'))
{
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = NumberFormat($bytes / 1073741824) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = NumberFormat($bytes / 1048576) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = NumberFormat($bytes / 1024) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = NumberFormat($bytes) . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = NumberFormat($bytes) . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}

/**
* [To formatted number]
* @param  [integer] $number
*/
if (!function_exists('NumberFormat'))
{
    function NumberFormat($number)
    {
        $CI = & get_instance();
        $decimal_places = 2;
        $newnumber = number_format($number, $decimal_places);
        return $newnumber;
    }
}

/**
* [To Get date difference]
* @param  [string] $start
* @param  [string] $end
*/
if (!function_exists('dateDiff'))
{
    function dateDiff($start, $end)
    {
      $start_ts = strtotime($start);
      $end_ts = strtotime($end);
      $diff = $end_ts - $start_ts;
      return round($diff / 86400) + 1;
    }
}

/**
* [To Get offset using page no, limit]
* @param  [integer] $PageNo
* @param  [integer] $Limit
*/
if (!function_exists('getOffset'))
{
    function getOffset($PageNo, $Limit = 10)
    {
        if (empty($PageNo))
        {
            $PageNo = 1;
        }
        $offset = ($PageNo - 1) * $Limit;
        return $offset;
    }
}

/**
* [To create seo friendly string]
* @param  [string] $str
*/
if (!function_exists('get_seo_url'))
{
  function get_seo_url($str){
    if($str !== mb_convert_encoding( mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
    $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
    $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
    $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $str);
    $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
    $str = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $str);
    $str = strtolower( trim($str, '-') );
    return $str;
  }
}

/**
* [To upload files using core php]
* @param  [string] $name
* @param  [string] $subfolder
*/
function corefileUploading($name,$subfolder){    
  $f_name1 = $_FILES[$name]['name'];    
  $f_tmp1  = $_FILES[$name]['tmp_name'];    
  $f_size1 = $_FILES[$name]['size'];    
  $f_extension1 = explode('.',$f_name1);     
  $f_extension1 = strtolower(end($f_extension1));    
  $f_newfile1="";    
  if($f_name1){      
    $f_newfile1 = get_file_name().'.'.$f_extension1;      
    $store1 = "uploads/".$subfolder."/". urlencode($f_newfile1);     
    if(move_uploaded_file($f_tmp1,$store1)){        
      chmod($store1, 0777);       
      return $store1;     
    }else{       
      return "";      
    }
  }else{
    return "";    
  }    
}

/**
 * [To check null value]
 * @param string $value
*/
if ( ! function_exists('null_checker')) {
  function null_checker($value,$custom="")
  {
    $return = "";
    if($value != "" && $value != NULL){
      $return = ($value == "" || $value == NULL) ? $custom : $value;
      return $return;
    }else{
      return $return;
    }
  }
}

/**
* [To get user image thumb]
* @param  [string] $filepath
* @param  [string] $subfolder
* @param  [int] $width
* @param  [int] $height
* @param  [int] $min_width
* @param  [int] $min_height
*/
if (!function_exists('get_image_thumb'))
{
  function get_image_thumb($filepath,$subfolder,$width,$height,$min_width="",$min_height="")
  {

    if(empty($min_width))
    {
      $min_width = $width;
    }
    if(empty($min_height))
    {
      $min_height = $height;
    }
    /* To get image sizes */
    $image_sizes = getimagesize($filepath);
    if(!empty($image_sizes))
    {
      $img_width  = $image_sizes[0];
      $img_height = $image_sizes[1];
      if($img_width <= $min_width && $img_height <= $min_height)
      {
        return $filepath;
      }
    }

    $ci   = &get_instance();
    /* Get file info using file path */
    $file_info = pathinfo($filepath);
    if(!empty($file_info)){
      $filename = $file_info['basename'];
      $ext      = $file_info['extension'];
      $dirname  = $file_info['dirname'].'/';
      $path     = $dirname.$filename;
      $file_status = @file_exists($path);
      if($file_status){
          $config['image_library']  = 'gd2';
          $config['source_image']   = $path;
          $config['create_thumb']   = TRUE;
          $config['maintain_ratio'] = TRUE;
          $config['width']          = $width;
          $config['height']         = $height;
          $config['file_permissions'] = 0777;
          $ci->load->library('image_lib', $config);
          $ci->image_lib->initialize($config);
          if(!$ci->image_lib->resize()) {
              return $path;
          } else {
            @chmod($path, 0777);
            $thumbnail = preg_replace('/(\.\w+)$/im', '', urlencode($filename)) . '_thumb.' . $ext;
            return 'uploads/'.$subfolder.'/'.urlencode($thumbnail);
          }
      }else{
        return $filepath;
      }
    }else{
      return $filepath;
    }
  }
}

/**
* [To get default image if file not exist]
* @param  [string] $file_path
* @param  [string] $filepath
*/
if (!function_exists('display_image'))
{
  function display_image($file_path,$default_file_path = "")
  {
    $final_file_path = DEFAULT_NO_IMG_PATH;
    if(!empty($file_path) && file_exists($file_path))
    {
      $final_file_path = $file_path;
    }
    else if(!empty($default_file_path) && @file_exists($default_file_path)){
      $final_file_path = $default_file_path;
    }
    $file_path_arr   = explode("/", $final_file_path);
    $final_file_name = end($file_path_arr);
    $key = array_search($final_file_name, $file_path_arr);
    if($key >= 0){
      unset($file_path_arr[$key]);
      return base_url().implode("/", array_values($file_path_arr))."/".urlencode($final_file_name);
    }else{
      return base_url().DEFAULT_NO_IMG_PATH;
    }
  }
}

/**
* [To delete file from directory]
* @param  [string] $filename
* @param  [string] $filepath
*/
if (!function_exists('delete_file'))
{
  function delete_file($filename,$filepath)
  {
    /* Send file path last slash */
    $file_path_name = $filepath.$filename;
    if(!empty($filename) && @file_exists($file_path_name) && @unlink($file_path_name)){
      return TRUE;
    }else{
      return FALSE;
    }
  }
}

/**
* [To get all dates betweeb start date & end date]
* @param  [string] $strDateFrom
* @param  [string] $strDateTo
*/
if (!function_exists('get_date_range'))
{
  function get_date_range($strDateFrom,$strDateTo)
  {
      $aryRange=array();

      $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
      $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

      if ($iDateTo>=$iDateFrom)
      {
          array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
          while ($iDateFrom<$iDateTo)
          {
              $iDateFrom+=86400; // add 24 hours
              array_push($aryRange,date('Y-m-d',$iDateFrom));
          }
      }
      return $aryRange;
  }
}

/**
* [To get ordinal of particular number]
* @param  [int] $number
*/
if(!function_exists('ordinal'))
{
  function ordinal($number) {
      $ends = array('th','st','nd','rd','th','th','th','th','th','th');
      if ((($number % 100) >= 11) && (($number%100) <= 13))
          return $number. 'th';
      else
          return $number. $ends[$number % 10];
  }
}

/**
* [To get time ago string]
* @param  [datetime] $datetime
*/
if(!function_exists('time_ago'))
{
  function time_ago($datetime, $full = false)
  {
      $now     = new DateTime;
      $ago     = new DateTime($datetime);
      $diff    = $now->diff($ago);
      $diff->w = floor($diff->d / 7);
      $diff->d -= $diff->w * 7;
      
      $string = array(
          'y' => 'year',
          'm' => 'month',
          'w' => 'week',
          'd' => 'day',
          'h' => 'hour',
          'i' => 'minute',
          's' => 'second'
      );
      foreach ($string as $k => &$v) {
          if ($diff->$k) {
              $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
          } else {
              unset($string[$k]);
          }
      }
      
      if (!$full)
          $string = array_slice($string, 0, 1);
      return $string ? implode(', ', $string) . ' ago' : 'just now';
  }
}

/**
* [To save file from another server to own server]
* @param  [string] $file
* @param  [string] $subfolder
*/
if(!function_exists('save_file_from_server'))
{
  function save_file_from_server($file,$subfolder)
  {
    $explode_file = explode(".", $file);
    if(!empty($explode_file) && !is_404($file)){
      $ext      = end($explode_file);
      $pic      = file_get_contents($file);
      $filename = get_file_name().'.'.$ext;
      $path     = 'uploads/'.$subfolder."/".$filename;
      file_put_contents($path, $pic);
      chmod($path, 0777);
      return $filename;
    }else{
      return DEFAULT_NO_IMG_PATH;
    }
  }
}

/*------------------------------*/
/*------------------------------*/  
function ValidateUserAccess($PermittedModules, $Path) {
  if(!empty($PermittedModules)){
  foreach($PermittedModules as $Value){
    if($Value['ModuleName'] == $Path){
      return $Value;
    }
  }}
  $Obj =& get_instance();
  $Obj->session->sess_destroy();
  exit("You do not have permission to access this module.");
  return false;
}

/*------------------------------*/
/*------------------------------*/  
function APICall($URL, $JSON='') {
  $CH = curl_init();
  $Headers = array('Accept: application/json', 'Content-Type: application/json');

  curl_setopt($CH, CURLOPT_URL, $URL);
  if ($JSON != '') {
    //curl_setopt($CH, CURLOPT_POST, count($JSON));
    curl_setopt($CH, CURLOPT_POSTFIELDS, $JSON);
  }

  curl_setopt($CH, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($CH, CURLOPT_CONNECTTIMEOUT, 50);
  curl_setopt($CH, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($CH, CURLOPT_HTTPHEADER, $Headers);
  curl_setopt($CH, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

  $Response = curl_exec($CH);

  $Response = json_decode($Response,true);
  curl_close($CH);
  return $Response;
}


/*------------------------------*/
/*------------------------------*/  
if (!function_exists('response')) {
    function response($data) {
      header('Content-type: application/json');
    echo json_encode($data/*,JSON_NUMERIC_CHECK*/);
    exit;
  }
}

/**
* [To convert number into words]
* @param  [number] $number
*/
if(!function_exists('convert_number_to_words'))
{
  function convert_number_to_words($number) {

      $hyphen      = '-';
      $conjunction = ' and ';
      $separator   = ', ';
      $negative    = 'negative ';
      $decimal     = ' point ';
      $dictionary  = array(
          0                   => 'zero',
          1                   => 'one',
          2                   => 'two',
          3                   => 'three',
          4                   => 'four',
          5                   => 'five',
          6                   => 'six',
          7                   => 'seven',
          8                   => 'eight',
          9                   => 'nine',
          10                  => 'ten',
          11                  => 'eleven',
          12                  => 'twelve',
          13                  => 'thirteen',
          14                  => 'fourteen',
          15                  => 'fifteen',
          16                  => 'sixteen',
          17                  => 'seventeen',
          18                  => 'eighteen',
          19                  => 'nineteen',
          20                  => 'twenty',
          30                  => 'thirty',
          40                  => 'fourty',
          50                  => 'fifty',
          60                  => 'sixty',
          70                  => 'seventy',
          80                  => 'eighty',
          90                  => 'ninety',
          100                 => 'hundred',
          1000                => 'thousand',
          1000000             => 'million',
          1000000000          => 'billion',
          1000000000000       => 'trillion',
          1000000000000000    => 'quadrillion',
          1000000000000000000 => 'quintillion'
      );

      if (!is_numeric($number)) {
          return false;
      }

      if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
          // overflow
          trigger_error(
              'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
              E_USER_WARNING
          );
          return false;
      }

      if ($number < 0) {
          return $negative . convert_number_to_words(abs($number));
      }

      $string = $fraction = null;

      if (strpos($number, '.') !== false) {
          list($number, $fraction) = explode('.', $number);
      }

      switch (true) {
          case $number < 21:
              $string = $dictionary[$number];
              break;
          case $number < 100:
              $tens   = ((int) ($number / 10)) * 10;
              $units  = $number % 10;
              $string = $dictionary[$tens];
              if ($units) {
                  $string .= $hyphen . $dictionary[$units];
              }
              break;
          case $number < 1000:
              $hundreds  = $number / 100;
              $remainder = $number % 100;
              $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
              if ($remainder) {
                  $string .= $conjunction . convert_number_to_words($remainder);
              }
              break;
          default:
              $baseUnit = pow(1000, floor(log($number, 1000)));
              $numBaseUnits = (int) ($number / $baseUnit);
              $remainder = $number % $baseUnit;
              $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
              if ($remainder) {
                  $string .= $remainder < 100 ? $conjunction : $separator;
                  $string .= convert_number_to_words($remainder);
              }
              break;
      }

      if (null !== $fraction && is_numeric($fraction)) {
          $string .= $decimal;
          $words = array();
          foreach (str_split((string) $fraction) as $number) {
              $words[] = $dictionary[$number];
          }
          $string .= implode(' ', $words);
      }

      return $string;
  }
}

/* ------------------------------ */
/* ------------------------------ */

function array_keys_exist(array $needles, array $StrArray) {
    foreach ($needles as $needle) {
        if (in_array($needle, $StrArray)) return true;
    } return false;
}

/* ------------------------------ */
/* ------------------------------ */

function validateEmail($Str) {
    return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $Str)) ? FALSE : TRUE;
}

/* ------------------------------ */
/* ------------------------------ */

function validateDate($Date) {
    if (strtotime($Date)) {
        return true;
    } else {
        return false;
    }
}

/* ------------------------------ */
/* ------------------------------ */

function paginationOffset($PageNo, $PageSize) {
    if (empty($PageNo)) {
        $PageNo = 1;
    }
    $offset = ($PageNo - 1) * $PageSize;
    return $offset;
}

/**
* [To manage email templates]
* @param  [string] $HTML
*/
if (!function_exists('emailTemplate'))
{
  function emailTemplate($HTML) {
      $CI = & get_instance();
      return $CI->load->view("emailer/layout", array("HTML" => $HTML), TRUE);
  }
}

/* End of file custom_helper.php */
/* Location: ./system/application/helpers/custom_helper.php */

?>

