<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends API_Controller {

    function __construct() {
        parent::__construct();
    }

    /*
      Description: 	Verify login and activate session
      URL: 			/admin/api/login/
     */

    public function index_post() { 
        /* Validation section */
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        $user_data = $this->Users_model->get_users('user_id,is_admin,user_status,user_type_guid,user_type_id', array('login_keyword' => $this->Post['email'], 'password' => $this->Post['password']));
        if (!$user_data) {
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('invalid_credentials');
        } elseif ($user_data && $user_data['user_status'] == 'Pending') {
            $this->Return['status'] = 501;
            $this->Return['message'] = lang('account_pending');
        }elseif ($user_data && $user_data['user_status'] == 'Blocked') {
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('account_blocked');
        }elseif ($user_data && $user_data['is_admin'] == 'No') {
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('access_denied');
        } else {

            /* Update Data */
            $login_session_key = get_guid();

            $update_arr = array();
            $update_arr['login_session_key'] = $login_session_key;
            $update_arr['last_login']    = date('Y-m-d H:i:s');
            $update_arr['last_activity'] = date('Y-m-d H:i:s');
            $this->Users_model->update_user($user_data['user_id'],$update_arr);

            /* Manage Response Data */
            $response_data = array();
            $response_data['login_session_key'] = $login_session_key;
            $response_data['user_id']           = $user_data['user_id'];
            $response_data['user_guid']         = $user_data['user_guid'];
            $response_data['user_type_guid']    = $user_data['user_type_guid'];
            $response_data['user_type_id']      = $user_data['user_type_id'];
            $response_data['last_login']        = $update_arr['last_login'];

            /* Set PHP Session */
            $this->session->set_userdata('userdata',$response_data);

            if($user_data['user_type_id'] == 3){

                /* Get Random SubCategory For Order */
                $subcategory_guid = '';
                $query = $this->db->query('SELECT s.subcategory_guid FROM tbl_products p, tbl_subcategories s WHERE s.subcategory_id = p.product_subcategory_id ORDER BY RAND() LIMIT 1');
                if($query->num_rows() > 0){
                    $subcategory_guid = $query->row()->subcategory_guid;
                }
                $response_data['redirect_uri'] = BASE_URL.'admin/products/order/'.$response_data['user_guid'].'?subcategory_guid='.$subcategory_guid;
            }else if($user_data['user_type_id'] == 2){
                $response_data['redirect_uri'] = BASE_URL.'admin/clients/list';
            }else{ // 1 - Admin
                $response_data['redirect_uri'] = (!empty($this->Post['redirect_uri'])) ? $this->Post['redirect_uri'] : (BASE_URL.'admin/dashboard');
            }
            unset($response_data['user_id']);
            $this->Return['data'] = $response_data;
        }
    }

    /*
      Description:  To forgot password admin
      URL:          /admin/api/forgot_password/
    */
    public function forgot_password_post() { 
        /* Validation section */
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_validate_user_email');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        if(!$this->Users_model->forgot_password($this->Post['email'],$this->Post)){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{
            $this->Return['status']  = 200;
            $this->Return['message'] = "An email has been sent on your registered email. Please check your mail inbox.";   
        }
    }

    /*
      Description:  To reset password admin
      URL:          /admin/api/reset_password/
    */
    public function reset_password_post() { 
        /* Validation section */
        $this->form_validation->set_rules('user_token','User Token','trim|required|callback_verify_token');
        $this->form_validation->set_rules('new_password','New Password','trim|required|min_length[6]|max_length[14]');
        $this->form_validation->set_rules('confirm_password','Confirm Password','trim|required|min_length[6]|max_length[14]|matches[new_password]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        if(!$this->Users_model->reset_password($this->Post['user_id'],$this->Post)){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{
            $this->Return['status']  = 200;
            $this->Return['message'] = "Password changed successfully.";   
        }
    }

    /**
     * Function Name: validate_user_email
     * Description:   To validate user email
     */
    public function validate_user_email($email) {
        $Query = $this->db->query('SELECT user_id,user_type_id,user_status,first_name,last_name FROM `tbl_users` WHERE email = "'.$email.'" LIMIT 1');
        if ($Query->num_rows() == 0) {
            $this->form_validation->set_message('validate_user_email', 'Email Id not exists.');
            return FALSE;
        }
        if(!in_array($Query->row()->user_type_id, array(1,2))){
            $this->form_validation->set_message('validate_user_email', 'Access restricted.');
            return FALSE;
        }
        if($Query->row()->user_status == 'Pending'){
           $this->form_validation->set_message('validate_user_email', "Your account not activated yet. Please contact the Admin for more info.");
            return FALSE; 
        }
        if($Query->row()->user_status == 'Blocked'){
           $this->form_validation->set_message('validate_user_email', 'Your account has been blocked. Please contact the Admin for more info.');
            return FALSE; 
        }
        $this->Post['user_id'] = $Query->row()->user_id;
        $this->Post['user_type_id'] = $Query->row()->user_type_id;
        $this->Post['user_full_name'] = $Query->row()->first_name. " ".$Query->row()->last_name;
        return TRUE;
    }

    /**
     * Function Name: verify_token
     * Description:   To verify user token
     */
    public function verify_token($token){
        $Query = $this->db->query('SELECT user_id,user_type_id,first_name,last_name,email FROM `tbl_users` WHERE user_token = "'.$token.'" LIMIT 1');
        if ($Query->num_rows() == 0) {
            $this->form_validation->set_message('verify_token', 'Invalid Token.');
            return FALSE;
        }
        $this->Post['user_id'] = $Query->row()->user_id;
        $this->Post['user_type_id'] = $Query->row()->user_type_id;
        $this->Post['user_full_name'] = $Query->row()->first_name. " ".$Query->row()->last_name;
        $this->Post['user_email'] = $Query->row()->email;
        return TRUE;
    }

}
