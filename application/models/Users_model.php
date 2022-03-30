<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

     /*
      Description:  Use to add user.
     */
    function add_user($Input = array()) {
        $insert_array = array_filter(array(
            "name" => @ucfirst(strtolower($Input['name'])),
            "first_name" => @ucfirst(strtolower($Input['first_name'])),
            "last_name" => @ucfirst(strtolower($Input['last_name'])),
            "email" => @$Input['email'],
            "user_guid" => get_guid(),
            "phone_number" => @$Input['phone_number'],
            "user_type_id" => @$Input['user_type_id'],
            "password" => (!empty($Input['password'])) ? md5($Input['password']) : '',
            "address" => @$Input['address'],
            "country_id" => @$Input['country_id'],
            "parent_user_id" => @$Input['parent_user_id'],
            "state_id" => @$Input['state_id'],
            "city_id" => @$Input['city_id'],
            "account_number" => @$Input['account_number'],
            "age" => @$Input['age'],
            "gender" => @$Input['gender'],
            "telephone_no" => @$Input['telephone_no'],
            "extension_no" => @$Input['extension_no'],
            "street_address1" => @$Input['street_address1'],
            "street_address2" => @$Input['street_address2'],
            "town" => @$Input['town'],
            "postcode" => @$Input['postcode'],
            "contact_name" => @$Input['contact_name'],
            "contact_number" => @$Input['contact_number'],
            "company_name" => @$Input['company_name'],
            "company_address" => @$Input['company_address'],
            "company_ltd_number" => @$Input['company_ltd_number'],
            "company_vat" => @$Input['company_vat'],
            "user_status" => @$Input['user_status'],
            "pricelist_id" => @$Input['pricelist_id'],
            "michal_pricelist_id" => @$Input['michal_pricelist_id'],
            "percent_reduction" => @$Input['percent_reduction'],
            "percent_reduction_michal" => @$Input['percent_reduction_michal'],
            "see_cart_total_amount" => @$Input['see_cart_total_amount'],
            "created_date" => date('Y-m-d H:i:s')
        ));

        $this->db->insert('tbl_users', $insert_array);
        if($this->db->insert_id()){
            return TRUE;
        }
        return FALSE;
    }

    /*
      Description: 	Use to get single user info or list of users.
      Note:			$Field should be comma seprated and as per selected tables alias.
     */

    function get_users($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 150) {
        
        /* Additional fields to select */
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'created_date'  => 'DATE_FORMAT(U.created_date, "' . DATE_FORMAT . '") created_date',
                'last_login'    => 'DATE_FORMAT(U.last_login, "' . DATE_FORMAT . '") last_login',
                'last_activity' => 'DATE_FORMAT(U.last_activity, "' . DATE_FORMAT . '") last_activity',
                'user_type_name' => 'UT.user_type_name',
                'is_admin' => 'UT.is_admin',
                'user_type_guid' => 'UT.user_type_guid',
                'user_id' => 'U.user_id',
                'user_type_id' => 'U.user_type_id',
                'name' => 'U.name',
                'first_name' => 'U.first_name',
                'last_name' => 'U.last_name',
                'phone_number' => 'U.phone_number',
                'account_number' => 'U.account_number',
                'user_image' => 'IF(U.user_image IS NULL,CONCAT("' . BASE_URL . '","uploads/users/","default-148.png"),CONCAT("' . BASE_URL . '","uploads/users/",U.user_image)) AS user_image',
                'email' => 'U.email',
                'address' => 'U.address',
                'country_id' => 'U.country_id',
                'state_id' => 'U.state_id',
                'city_id' => 'U.city_id',
                'age' => 'U.age',
                'gender' => 'U.gender',
                'login_session_key' => 'U.login_session_key',
                'user_token' => 'U.user_token',
                'user_status' => 'U.user_status',
                'telephone_no' => 'U.telephone_no',
                'extension_no' => 'U.extension_no',
                'street_address1' => 'U.street_address1',
                'street_address2' => 'U.street_address2',
                'town' => 'U.town',
                'postcode' => 'U.postcode',
                'contact_name' => 'U.contact_name',
                'email_addresses' => 'U.email_addresses',
                'contact_number' => 'U.contact_number',
                'company_name' => 'U.company_name',
                'company_address' => 'U.company_address',
                'company_ltd_number' => 'U.company_ltd_number',
                'see_cart_total_amount' => 'U.see_cart_total_amount',
                'percent_reduction' => 'U.percent_reduction',
                'percent_reduction_michal' => 'U.percent_reduction_michal',
                'company_vat' => 'U.company_vat',
                'country_code' => 'C.code country_code',
                'country_name' => 'C.name country_name',
                'state_name' => 'S.name state_name',
                'city_name' => 'CT.name city_name',
                'pricelist_guid' => 'PL.pricelist_guid',
                'pricelist_name' => 'PL.pricelist_name',
                'pricelist_brand' => 'PL.pricelist_brand',
                'michal_pricelist_guid' => 'MPL.pricelist_guid michal_pricelist_guid',
                'michal_pricelist_name' => 'MPL.pricelist_name michal_pricelist_name',
                'michal_pricelist_brand' => 'MPL.pricelist_brand michal_pricelist_brand'
            );
            
            foreach ($Params as $Param) {
                $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
            }
        }
        $this->db->select('U.user_guid');
        if (!empty($Field)) $this->db->select($Field, FALSE);
        $this->db->from('tbl_users U');
        if (array_keys_exist($Params, array('user_type_name', 'is_admin','user_type_guid'))) {
            $this->db->from('tbl_user_types UT');
            $this->db->where("UT.id", "U.user_type_id", FALSE);
        }
        if (array_keys_exist($Params, array('pricelist_guid', 'pricelist_name','pricelist_brand'))) {
            $this->db->join('tbl_pricelists PL', 'PL.pricelist_id = U.pricelist_id', 'left');
        }
        if (array_keys_exist($Params, array('michal_pricelist_guid', 'michal_pricelist_name','michal_pricelist_brand'))) {
            $this->db->join('tbl_pricelists MPL', 'MPL.pricelist_id = U.michal_pricelist_id', 'left');
        }
        if (array_keys_exist($Params, array('country_code','country_name'))) {
            $this->db->join('set_countries C', 'U.country_id = C.id', 'left');
        }
         if (array_keys_exist($Params, array('state_name'))) {
            $this->db->join('set_states S', 'S.id = U.state_id', 'left');
        }
         if (array_keys_exist($Params, array('city_name'))) {
            $this->db->join('set_cities CT', 'CT.id = U.city_id', 'left');
        }
        if (!empty($Where['keyword'])) {
            $Where['keyword'] = trim($Where['keyword']);
            $this->db->group_start();
            $this->db->like("U.name", $Where['keyword']);
            $this->db->or_like("U.email", $Where['keyword']);
            $this->db->or_like("U.user_status", $Where['keyword']);
            $this->db->group_end();
        }
        if (!empty($Where['user_type_id'])) {
            $this->db->where_in("U.user_type_id", $Where['user_type_id']);
        }
        if (!empty($Where['user_id'])) {
            $this->db->where("U.user_id", $Where['user_id']);
        }
        if (!empty($Where['user_guid'])) {
            $this->db->where("U.user_guid", $Where['user_guid']);
        }
        if (!empty($Where['email'])) {
            $this->db->where("U.email", $Where['email']);
        }
        if (!empty($Where['phone_number'])) {
            $this->db->where("U.phone_number", $Where['phone_number']);
        }
        if (!empty($Where['user_guid_not_in'])) {
            $this->db->where_not_in("U.user_guid", $Where['user_guid_not_in']); 
        }

        if (!empty($Where['login_keyword'])) {
            $this->db->group_start();
            $this->db->where("U.email", $Where['login_keyword']);
            // $this->db->or_where("U.phone_number", $Where['login_keyword']);
            $this->db->or_where("U.account_number", $Where['login_keyword']);
            $this->db->group_end();
        }
        if (!empty($Where['password'])) {
            $this->db->where("U.password", md5($Where['password']));
        }
        if (!empty($Where['is_admin'])) {
            $this->db->where("UT.is_admin", $Where['is_admin']);
        }
        if (!empty($Where['user_status'])) {
            $this->db->where("U.user_status", $Where['user_status']);
        }
        if (!empty($Where['pricelist_id'])) {
            $this->db->where("U.pricelist_id", $Where['pricelist_id']);
        }
        if (!empty($Where['order_by']) && !empty($Where['sequence']) && in_array($Where['sequence'], array('ASC', 'DESC'))) {
            $this->db->order_by($Where['order_by'], $Where['sequence']);
        } else {
            $this->db->order_by('U.user_id', 'DESC');
        }

        /* Total records count only if want to get multiple records */
        if ($multiRecords) {
            $TempOBJ = clone $this->db;
            $TempQ = $TempOBJ->get();
            $Return['data']['total_records'] = $TempQ->num_rows();
            $this->db->limit($PageSize, paginationOffset($PageNo, $PageSize)); /* for pagination */
        } else {
            $this->db->limit(1);
        }

        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            foreach ($Query->result_array() as $Record) {
                if (!$multiRecords) {
                    return $Record;
                }
                $Records[] = $Record;
            }

            $Return['data']['records'] = $Records;
            return $Return;
        }
        return FALSE;
    }

    /*
      Description:  Use to update user profile info.
     */
    function update_user($user_id, $Input = array()) { 

        if(!empty($Input['email_addresses'])){
            $email_addresses = array_values(array_unique(array_filter($Input['email_addresses'])));
            $email_addresses = (!empty($email_addresses)) ? json_encode($email_addresses) : NULL;
        }


        $update_array = array_filter(array(
            "first_name" => @ucfirst(strtolower($Input['first_name'])),
            "last_name" => @ucfirst(strtolower($Input['last_name'])),
            "email" => @$Input['email'],
            "phone_number" => @$Input['phone_number'],
            "user_type_id" => @$Input['user_type_id'],
            "password" => (!empty($Input['password'])) ? md5($Input['password']) : '',
            "address" => @$Input['address'],
            "country_id" => @$Input['country_id'],
            "state_id" => @$Input['state_id'],
            "city_id" => @$Input['city_id'],
            "age" => @$Input['age'],
            "gender" => @$Input['gender'],
            "login_session_key" => @$Input['login_session_key'],  
            "user_image" => @$Input['user_image'],  
            "user_token" => @$Input['user_token'],  
            "account_number" => @$Input['account_number'],  
            "user_status" => @$Input['user_status'],  
            "pricelist_id" => @$Input['pricelist_id'],  
            "michal_pricelist_id" => @$Input['michal_pricelist_id'],
            "percent_reduction" => @$Input['percent_reduction'],
            "percent_reduction_michal" => @$Input['percent_reduction_michal'],
            "see_cart_total_amount" => @$Input['see_cart_total_amount'],
            "telephone_no" => @$Input['telephone_no'],
            "extension_no" => @$Input['extension_no'],
            "street_address1" => @$Input['street_address1'],
            "street_address2" => @$Input['street_address2'],
            "town" => @$Input['town'],
            "postcode" => @$Input['postcode'],
            "contact_name" => @$Input['contact_name'],
            "contact_number" => @$Input['contact_number'],
            "company_name" => @$Input['company_name'],
            "company_address" => @$Input['company_address'],
            "company_ltd_number" => @$Input['company_ltd_number'],
            "company_vat" => @$Input['company_vat'],
            "last_login" => @$Input['last_login'],  
            "last_activity" => @$Input['last_activity'],
            "email_addresses" => @$email_addresses
        ));

        if (isset($Input['name']) && $Input['name'] == '') {
            $update_array['name'] = null;
        }
        if (isset($Input['email']) && $Input['email'] == '') {
            $update_array['email'] = null;
        }
        if (isset($Input['phone_number']) && $Input['phone_number'] == '') {
            $update_array['phone_number'] = null;
        }
        if (isset($Input['user_type_id']) && $Input['user_type_id'] == '') {
            $update_array['user_type_id'] = null;
        }
        if (isset($Input['address']) && $Input['address'] == '') {
            $update_array['address'] = null;
        }
        if (isset($Input['age']) && $Input['age'] == '') {
            $update_array['age'] = null;
        }
        if (isset($Input['gender']) && $Input['gender'] == '') {
            $update_array['gender'] = null;
        }
        if (isset($Input['user_image']) && $Input['user_image'] == '') {
            $update_array['user_image'] = null;
        }
        if (isset($Input['user_token']) && $Input['user_token'] == '') {
            $update_array['user_token'] = null;
        }
        if (isset($Input['account_number']) && $Input['account_number'] == '') {
            $update_array['account_number'] = null;
        }
        if (isset($Input['last_login']) && $Input['last_login'] == '') {
            $update_array['last_login'] = null;
        }
        if (isset($Input['last_activity']) && $Input['last_activity'] == '') {
            $update_array['last_activity'] = null;
        }
        
        /* Update User details to users table. */
        if (!empty($update_array)) {
            $this->db->where('user_id', $user_id);
            $this->db->limit(1);
            $this->db->update('tbl_users', $update_array);
        }
        return TRUE;
    }

    /*
      Description:  Use to delete user.
    */
    function delete_user($user_guid) {
        $this->db->where('user_guid',$user_guid);
        $this->db->where_not_in('user_type_id',1);
        $this->db->limit(1);
        $this->db->delete('tbl_users');
        if($this->db->affected_rows() > 0){
            return TRUE;
        }
        return FALSE;
    }

    /*
      Description:    Use to get User login Sources.
     */

    function get_user_role_type($user_id) {
        $this->db->select('user_type_id');
        $this->db->from('tbl_users');
        $this->db->where("user_id", $user_id);
        $Query = $this->db->get();
        if($Query->num_rows() == 0){
            return FALSE;
        }
        return $Query->row_array();

    }

    /*
      Description: 	Use to delete Session.
    */
    function delete_session($session_key) {
        $this->db->where("login_session_key", $session_key);
        $this->db->limit(1);
        $this->db->update('tbl_users', array('login_session_key' => NULL, 'last_activity' => NULL));
        return TRUE;
    }

    /*
      Description:  Use to change password.
    */
    function change_password($user_id,$password) {
        $this->db->where("user_id", $user_id);
        $this->db->limit(1);
        $this->db->update('tbl_users', array('password' => md5($password)));
        return TRUE;
    }

    /*
      Description:  Use to update last activity.
    */
    function update_last_activity($user_id) {
        $this->db->where("user_id", $user_id);
        $this->db->limit(1);
        $this->db->update('tbl_users', array('last_activity' => date('Y-m-d H:i:s')));
        return TRUE;
    }

    /*
    Description:    Use to get User Types
    */
    function get_user_types($Field='', $where=array(), $multiRecords=FALSE){
        $Params = array();
        if(!empty($Field)){
            $Params = array_map('trim',explode(',',$Field));
        }
        $this->db->select($Field,false);
        $this->db->from('tbl_user_types');
        if(!empty($where['is_admin'])){
            $this->db->where("is_admin",$where['is_admin']);
        }
        $this->db->where("is_permitted",'Yes');
        $this->db->order_by('user_type_name','ASC');
        $Query = $this->db->get();
        if($Query->num_rows() > 0){
            foreach($Query->result_array() as $key => $Record){
                if(!$multiRecords){
                    return $Record;
                }
                $Records[] = $Record;
            }
            $Return['data']['records'] = $Records;
            return $Return;
        }
        return FALSE;       
    }

    /*
      Description:  Use to send forgot password mail
    */
    function forgot_password($email,$input = array()) {

        $reset_password_link = base_url();
        $user_token = encoding($email."-".$input['user_id']."-".time());
        if(in_array($input['user_type_id'], array(1,2))){ // Admin
            $reset_password_link .= 'admin/reset-password?email='.$email.'&token='.$user_token;
        }else{ // Web
            $reset_password_link .= 'user/reset-password?email='.$email.'&token='.$user_token;
        }

        /* Update User Token */
        $this->db->where("user_id", $input['user_id']);
        $this->db->limit(1);
        $this->db->update('tbl_users', array('user_token' => $user_token));

        /* Send Email To User */
        $status = php_mailer($email,$input['user_full_name'],'['.SITE_NAME.'] Forgot Password Request !!',emailTemplate($this->load->view('emailer/forgotPassword', array('user_full_name' => $this->Post['user_full_name'],'reset_password_link' => $reset_password_link), true)));
        if($status){
            return TRUE;
        }
        return FALSE;
    }

    /*
      Description:  Use to reset password
    */
    function reset_password($user_id,$input = array()) {

        /* Update User Password */
        $this->db->where("user_id", $user_id);
        $this->db->limit(1);
        $this->db->update('tbl_users', array('user_token' => NULL, 'password' => md5($input['new_password'])));
        if($this->db->affected_rows() > 0){

            $login_link = base_url();
            if(in_array($input['user_type_id'], array(1,2))){ // Admin
                $login_link .= 'admin/login';
            }
            
            /* Send Email To User */
            php_mailer($input['user_email'],$input['user_full_name'],'['.SITE_NAME.'] Password Changed Successfully !!',emailTemplate($this->load->view('emailer/changePassword', array('user_full_name' => $this->Post['user_full_name'],'login_link' => $login_link), true)));

            return TRUE;
        }
        return FALSE;
    }

    /*
      Description:  Use to upload clients.
     */
    function upload_clients($Input = array(),$session_user_id) {

        /* Set max exceution time */
       ini_set('max_execution_time', 1800); // 30 minutes

       /* Set memory limit */
       ini_set('memory_limit', '1024M'); 

       $error_array = array();
       $total_success_records = 0;

       /* Clients */
       foreach($Input['clients_data'] as $key => $client){

        /* Check Client account number */
        if (empty(trim($client[0]))) {
          $error_array[] = 'Row no. '.($key+2).' Client account number can not empty.';
          continue;
        }

        /* Check Client name */
        if (empty(trim($client[1]))) {
          $error_array[] = 'Row no. '.($key+2).' Client name can not empty.';
          continue;
        }

        /* Check valid email */
        if (!empty(trim($client[6])) && !filter_var(trim($client[6]), FILTER_VALIDATE_EMAIL)) {
          $error_array[] = 'Row no. '.($key+2).' Invalid email address.';
          continue;
        }

        

        /* Check Unique account number */
        $user_id = "";
        $query = $this->db->query('SELECT user_id FROM tbl_users WHERE account_number = "'.trim($client[0]).'" LIMIT 1');
        if($query->num_rows() > 0){
            $user_id = $query->row()->user_id;
        }

        /* Check Client Price list */
        if (empty(trim($client[4]))) {
          $error_array[] = 'Row no. '.($key+2).' Price list can not empty.';
          continue;
        }

        /* Check Discount Code Price list */
        if (empty(trim($client[3]))) {
          $error_array[] = 'Row no. '.($key+2).' Discount code can not empty.';
          continue;
        }

        /* Check Client Price list */
        $pricelist_brand = (trim($client[3]) == 1) ? 'Amitex' : 'Michal Negrin';
        $query = $this->db->query('SELECT pricelist_id FROM tbl_pricelists WHERE pricelist_brand = "'.$pricelist_brand.'" AND pricelist_name LIKE "%'.trim($client[4]).'%" LIMIT 1');
        if($query->num_rows() == 0){
            $error_array[] = 'Row no. '.($key+2).' Price list not exist.';
            continue;
        }
        $pricelist_id = $query->row()->pricelist_id;

        if(empty($user_id)){

            /* Insert Client */
            $insert_array = array_filter(array(
                                'user_guid' => get_guid(),
                                'user_type_id' => 3,
                                'parent_user_id' => $session_user_id,
                                'first_name' => trim($client[1]),
                                'email' => trim($client[6]),
                                'account_number' => trim($client[0]),
                                'phone_number' => trim($client[2]),
                                //'address' => trim($client[4]),
                                'password' => md5(rand()),
                                'percent_reduction' => @$client[5],
                                ($pricelist_brand == 'Amitex') ? 'pricelist_id' : 'michal_pricelist_id' => $pricelist_id,
                                'user_status' => 'Verified',
                                'created_date' => date('Y-m-d H:i:s')
                            ));
            $this->db->insert('tbl_users', $insert_array);
            if($this->db->insert_id()){
              $total_success_records = $total_success_records + 1;
            }else{
                $error_array[] = 'Row no. '.($key+2).' client failed to insert into database.';
            }
        }else{

            /* Update User Price */
            $this->db->where("user_id", $user_id);
            $this->db->limit(1);
            $this->db->update('tbl_users', array(($pricelist_brand == 'Amitex') ? 'pricelist_id' : 'michal_pricelist_id' => $pricelist_id));
            if($this->db->affected_rows() > 0){
                $total_success_records = $total_success_records + 1;
            }else{
                $error_array[] = 'Row no. '.($key+2).' client price failed to update into database.';
            }
        }
       }
       return array('is_error' => ((!empty($error_array)) ? 1 : 0), 'is_success' => ((!empty($total_success_records)) ? 1 : 0), 'total_success_records' => $total_success_records ,'error_array' => $error_array);
    }


}
