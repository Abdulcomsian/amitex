<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Agents extends API_Controller_Secure {

    function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
        if($this->user_type_id != 1){
            $this->Return['status']  = 500;
            $this->Return['message'] = lang('access_denied');
            exit;
        }
    }

    /*
      Description: 	To add new agent
      URL: 			/admin/api/agents/add/
    */
    public function add_post() {

        /* Validation section */
        $this->form_validation->set_rules('first_name', lang('first_name'), 'trim|required');
        $this->form_validation->set_rules('last_name', lang('last_name'), 'trim|required');
        $this->form_validation->set_rules('email', lang('email'), 'trim|required|valid_email|is_unique[tbl_users.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|in_list[Male,Female]');
        $this->form_validation->set_message('is_unique', '{field} '.lang('field_already_exist'));
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        if(!$this->Users_model->add_user(array_merge($this->Post,array('user_type_id' => 2, 'user_status' => 'Verified', 'parent_user_id' => $this->session_user_id)))){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{

            $this->Return['status'] = 200;
            $this->Return['message'] = lang('sales_agent_added');   
        }
    }

     /*
      Description:  To edit agent
      URL:          /admin/api/agents/edit/
    */
    public function edit_post() { 
        /* Validation section */
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('user_guid', 'User GUID', 'trim|required|callback_validate_guid[tbl_users.user_guid.user_id]');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|in_list[Male,Female]');
        $this->form_validation->set_rules('user_status', 'Status', 'trim|required|in_list[Pending,Verified,Blocked]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
        
        if(!$this->Users_model->update_user($this->user_id,$this->Post)){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{
            $this->Return['status']  = 200;
            $this->Return['message'] = lang('sales_agent_updated');   
        }
    }

     /*
      Description:  To view agent details
      URL:          /admin/api/agents/details/
    */
    public function details_post() { 

        /* Validation section */
        $this->form_validation->set_rules('user_guid', 'User GUID', 'trim|required|callback_validate_guid[tbl_users.user_guid.user_id]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
       
        /* To Get User Details */
        $details = $this->Users_model->get_users('first_name,last_name,email,phone_number,gender,user_status,created_date',array('user_id' => $this->user_id));
        if(!empty($this->Post['data_type']) && $this->Post['data_type'] == 'html'){
            $this->load->view('admin/agents/view-details',array('details' => $details));
        }else{
            $this->Return['data'] = $details;
        }
    }


  
}