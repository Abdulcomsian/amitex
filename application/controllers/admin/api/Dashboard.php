<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends API_Controller_Secure { 

    function __construct() {
        parent::__construct();
    }

    /*
      Description: 	To admin change password
      URL: 			/admin/api/change_password/
    */
    public function change_password_post() {

        /* Validation section */
        $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required|callback_validate_current_password');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]|alpha_numeric|differs[current_password]');
        $this->form_validation->set_message('differs', lang('password_field_different'));
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

		$this->Users_model->change_password($this->session_user_id,$this->Post['new_password']);   
		$this->Return['status'] = 200;
        $this->Return['message'] = lang('password_changed');     
    }

    /*
      Description:  To edit my profile
      URL:          /admin/api/edit_profile/
    */
    public function edit_profile_post() {
         /* Validation section */
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('user_guid', 'User GUID', 'trim|required|callback_validate_guid[tbl_users.user_guid.user_id]');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|in_list[Male,Female,Other]');
        $this->form_validation->set_rules('email', lang('email'), 'trim|required|valid_email');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Validate Unique Email ID */
        $query = $this->db->query('SELECT 1 FROM tbl_users WHERE email = "'.$this->Post['email'].'" AND user_id != '.$this->user_id.' LIMIT 1');
        if($query->num_rows() > 0){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('email_id_already_exist');
            exit;
        }
        
        if(!$this->Users_model->update_user($this->user_id,$this->Post)){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{
            $this->Return['status']  = 200;
            $this->Return['message'] = lang('profile_updated');   
        }     
    }


    /**
     * Function Name: validate_current_password
     * Description:   To validate current password
     */
    public function validate_current_password($current_password) {
        $Query = $this->db->query('SELECT 1 FROM `tbl_users` WHERE user_id = '.$this->session_user_id.' AND password = "'.md5($this->Post['current_password']).'" LIMIT 1');
        if ($Query->num_rows() == 0) {
            $this->form_validation->set_message('validate_current_password', lang('invalid_current_password'));
            return FALSE;
        }
        return TRUE;
    }

}