<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Clients extends API_Controller_Secure {

    function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
    }

    /*
      Description:  To add new client
      URL:          /admin/api/clients/add/
    */
    public function add_post() {
        if($this->user_type_id != 1){
            $this->Return['status']  = 500;
            $this->Return['message'] = lang('access_denied');
            exit;
        }

        /* Validation section */
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('email', lang('email'), 'trim|valid_email');
        $this->form_validation->set_rules('account_number', lang('account_number'), 'trim|required|is_unique[tbl_users.account_number]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required');
        $this->form_validation->set_rules('amitex_pricelist_guid', 'Amitex Price List GUID', 'trim|required|callback_validate_guid[tbl_pricelists.pricelist_guid.pricelist_id]');
        $this->form_validation->set_rules('michal_pricelist_guid', 'Michal Price List GUID', 'trim|required|callback_validate_guid[tbl_pricelists.pricelist_guid.pricelist_id,michal_pricelist_id]');
        $this->form_validation->set_rules('percent_reduction', lang('percent_reduction'), 'trim|required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('percent_reduction_michal', lang('michal_percent_reduction'), 'trim|required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('see_cart_total_amount', lang('see_cart_total_amount'), 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_message('is_unique', '{field} '.lang('field_already_exist'));
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        if(!$this->Users_model->add_user(array_merge($this->Post,array('user_type_id' => 3, 'user_status' => 'Verified', 'parent_user_id' => $this->session_user_id, 'pricelist_id' => $this->pricelist_id, 'michal_pricelist_id' => @$this->michal_pricelist_id)))){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{

            $this->Return['status'] = 200;
            $this->Return['message'] = lang('client_added');   
        }
    }

     /*
      Description:  To edit client
      URL:          /admin/api/clients/edit/
    */
    public function edit_post() { 

        if($this->user_type_id != 1){
            $this->Return['status']  = 500;
            $this->Return['message'] = lang('access_denied');
            exit;
        }

        /* Validation section */
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('email', lang('email'), 'trim|valid_email');
        $this->form_validation->set_rules('user_guid', 'User GUID', 'trim|required|callback_validate_guid[tbl_users.user_guid.user_id]');
        $this->form_validation->set_rules('amitex_pricelist_guid', 'Amitex Price List GUID', 'trim|required|callback_validate_guid[tbl_pricelists.pricelist_guid.pricelist_id]');
        $this->form_validation->set_rules('michal_pricelist_guid', 'Michal Price List GUID', 'trim|required|callback_validate_guid[tbl_pricelists.pricelist_guid.pricelist_id,michal_pricelist_id]');
        $this->form_validation->set_rules('percent_reduction', lang('percent_reduction'), 'trim|required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('percent_reduction_michal', lang('michal_percent_reduction'), 'trim|required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('see_cart_total_amount', lang('see_cart_total_amount'), 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('user_status', 'Status', 'trim|required|in_list[Pending,Verified,Blocked]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
        
        if(!$this->Users_model->update_user($this->user_id,array_merge($this->Post,array('pricelist_id' => $this->pricelist_id, 'michal_pricelist_id' => @$this->michal_pricelist_id)))){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{
            $this->Return['status']  = 200;
            $this->Return['message'] = lang('client_updated');   
        }
    }

     /*
      Description:  To view client details
      URL:          /admin/api/clients/details/
    */
    public function details_post() { 

        if(!in_array($this->user_type_id,array(1,2))){
            $this->Return['status']  = 500;
            $this->Return['message'] = lang('access_denied');
            exit;
        }

        /* Validation section */
        $this->form_validation->set_rules('user_guid', 'User GUID', 'trim|required|callback_validate_guid[tbl_users.user_guid.user_id]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
       
        /* To Get User Details */
        $details = $this->Users_model->get_users('first_name,last_name,email,account_number,phone_number,gender,pricelist_name,michal_pricelist_name,see_cart_total_amount,percent_reduction,percent_reduction_michal,user_status,created_date',array('user_id' => $this->user_id));
        if(!empty($this->Post['data_type']) && $this->Post['data_type'] == 'html'){
            $this->load->view('admin/clients/view-details',array('details' => $details));
        }else{
            $this->Return['data'] = $details;
        }
    }

    /*
      Description:  To upload clients (using csv file)
      URL:          /admin/api/clients/upload/
    */
    public function upload_post() {

        /* Validation section */
        $this->form_validation->set_rules('clients_csv', 'Clients CSV', 'trim|callback_validate_clients_csv_file');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Insert Data */
        $this->Return['data'] = $this->Users_model->upload_clients($this->Post,$this->session_user_id);
    }

    /**
     * Function Name: validate_clients_csv_file  
     * Description:   To validate clients csv file
     */
    public function validate_clients_csv_file() {

        /* Validate Clients CSV */
        if(!empty($_FILES['clients_csv']['name'])){

            /* Read CSV file */
            $clients_csv_data = array_map('str_getcsv', file($_FILES['clients_csv']['tmp_name']));
            if(empty($clients_csv_data)){
                $this->form_validation->set_message('validate_clients_csv_file', lang('clients_csv_empty'));
                return FALSE;
            }
            unset($clients_csv_data[0]);
            $this->Post['clients_data'] = array_values($clients_csv_data);
        }else{
            $this->form_validation->set_message('validate_clients_csv_file', "Require Clients CSV file.");
            return FALSE;
        }
        return TRUE;
    }


  
}