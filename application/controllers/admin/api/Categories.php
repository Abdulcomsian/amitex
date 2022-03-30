<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends API_Controller_Secure {

    function __construct() {
        parent::__construct();
        $this->load->model('Categories_model');
        if($this->user_type_id != 1){
            $this->Return['status']  = 500;
            $this->Return['message'] = lang('access_denied');
            exit;
        }
    }

    /*
      Description:  To add new category
      URL:          /admin/api/categories/add/
    */
    public function add_post() {

        /* Validation section */
        $this->form_validation->set_rules('category_name', 'Category', 'trim|required|is_unique[tbl_categories.category_name]');
        $this->form_validation->set_message('is_unique', '{field} '.lang('field_already_exist'));
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        if(!$this->Categories_model->add_category($this->Post)){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{

            $this->Return['status'] = 200;
            $this->Return['message'] = lang('category_added');   
        }
    }

     /*
      Description:  To edit category
      URL:          /admin/api/categories/edit/
    */
    public function edit_post() { 
        /* Validation section */
        $this->form_validation->set_rules('category_name', 'Category', 'trim|required');
        $this->form_validation->set_rules('category_guid', 'Category GUID', 'trim|required|callback_validate_guid[tbl_categories.category_guid.category_id]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
        
        if(!$this->Categories_model->update_category($this->category_id,$this->Post)){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{
            $this->Return['status']  = 200;
            $this->Return['message'] = lang('category_updated');   
        }
    }

    /*
      Description:  To view category details
      URL:          /admin/api/categories/details/
    */
    public function details_post() { 

        /* Validation section */
        $this->form_validation->set_rules('category_guid', 'Category GUID', 'trim|required|callback_validate_guid[tbl_categories.category_guid.category_id]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
       
        /* To Get Category Details */
         $this->Return['data'] = $this->Categories_model->get_categories('category_name',array('category_id' => $this->category_id));
    }

    /*
      Description:  To add new subcategory
      URL:          /admin/api/categories/subcategory_add/
    */
    public function subcategory_add_post() {

        /* Validation section */
        $this->form_validation->set_rules('category_guid', 'Category GUID', 'trim|required|callback_validate_guid[tbl_categories.category_guid.category_id]');
        $this->form_validation->set_rules('subcategory_name', 'Sub Category', 'trim|required|is_unique[tbl_subcategories.subcategory_name]');
        $this->form_validation->set_message('is_unique', '{field} '.lang('field_already_exist'));
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        if(!$this->Categories_model->add_subcategory(array_merge($this->Post,array('category_id' => $this->category_id)))){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{

            $this->Return['status'] = 200;
            $this->Return['message'] = lang('subcategory_added');   
        }
    }

     /*
      Description:  To edit subcategory details
      URL:          /admin/api/clients/subcategory_edit/
    */
    public function subcategory_edit_post() { 
        /* Validation section */
        $this->form_validation->set_rules('subcategory_name', 'Sub Category', 'trim|required');
        $this->form_validation->set_rules('subcategory_guid', 'Sub Category GUID', 'trim|required|callback_validate_guid[tbl_subcategories.subcategory_guid.subcategory_id]');
        $this->form_validation->set_rules('category_guid', 'Category GUID', 'trim|required|callback_validate_guid[tbl_categories.category_guid.category_id]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
        
        if(!$this->Categories_model->update_subcategory($this->subcategory_id,array_merge($this->Post,array('category_id' => $this->category_id)))){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{
            $this->Return['status']  = 200;
            $this->Return['message'] = lang('subcategory_updated');   
        }
    }

    /*
      Description:  To view sub category details
      URL:          /admin/api/categories/subcategory_details/
    */
    public function subcategory_details_post() { 

        /* Validation section */
        $this->form_validation->set_rules('subcategory_guid', 'Sub Category GUID', 'trim|required|callback_validate_guid[tbl_subcategories.subcategory_guid.subcategory_id]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
       
        /* To Get Sub Category Details */
        $this->Return['data'] = $this->Categories_model->get_categories('subcategory_name',array('subcategory_id' => $this->subcategory_id));
    }

    /*
      Description:  To get sub categories data
      URL:          /admin/api/categories/get_subcategories/
    */
    public function get_subcategories_post() { 

        /* Validation section */
        $this->form_validation->set_rules('category_guid', 'Category GUID', 'trim|callback_validate_guid[tbl_categories.category_guid.category_id]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
       
        /* To Get Sub Categories Data */
        $subcategories_data = $this->Categories_model->get_categories('subcategory_name,subcategory_guid',array('parent_category_id' => @$this->category_id, 'order_by' => 'SC.subcategory_name', 'sequence' => 'ASC'),TRUE);
        if (!empty($subcategories_data)) {
            $this->Return['data'] = $subcategories_data['data'];
        }
    }


  
}