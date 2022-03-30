<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pricelist extends API_Controller_Secure {

    function __construct() {
        parent::__construct();
        $this->load->model('Pricelist_model');
        if($this->user_type_id != 1){
            $this->Return['status']  = 500;
            $this->Return['message'] = lang('access_denied');
            exit;
        }
    }

    /*
      Description:  To add new pricelist
      URL:          /admin/api/pricelist/add/
    */
    public function add_post() {

        /* Validation section */
        $this->form_validation->set_rules('pricelist_name', 'Price List Name', 'trim|required|is_unique[tbl_pricelists.pricelist_name]');
        $this->form_validation->set_rules('pricelist_brand', 'Price List Brand', 'trim|required|in_list[Amitex,Michal Negrin]');
        $this->form_validation->set_rules('products_csv', 'Products CSV', 'trim|callback_validate_product_csv_file');
        $this->form_validation->set_rules('is_main_pricelist', 'Main Price List', 'trim|in_list[Yes,No]|callback_validate_single_main_pricelist');
        $this->form_validation->set_message('is_unique', '{field} '.lang('field_already_exist'));
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Insert Data */
        if(!$this->Pricelist_model->add_pricelist($this->Post)){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{

            $this->Return['status'] = 200;
            $this->Return['message'] = lang('price_list_added');   
        }
    }

     /*
      Description:  To edit pricelist
      URL:          /admin/api/pricelist/edit/
    */
    public function edit_post() { 
        /* Validation section */
        $this->form_validation->set_rules('pricelist_guid', 'Price List GUID', 'trim|required|callback_validate_guid[tbl_pricelists.pricelist_guid.pricelist_id]');
        $this->form_validation->set_rules('pricelist_name', 'Price List Name', 'trim|required|callback_validate_pricelist_name');
        $this->form_validation->set_rules('pricelist_brand', 'Price List Brand', 'trim|required|in_list[Amitex,Michal Negrin]');
        $this->form_validation->set_rules('is_main_pricelist', 'Main Price List', 'trim|in_list[Yes,No]|callback_validate_single_main_pricelist');
        $this->form_validation->set_rules('products_csv', 'Products CSV', 'trim|callback_validate_product_csv_file');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
        
        if(!$this->Pricelist_model->update_pricelist($this->pricelist_id,$this->Post)){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{
            $this->Return['status']  = 200;
            $this->Return['message'] = lang('price_list_updated');   
        }
    }

    /*
      Description:  To view pricelist details
      URL:          /admin/api/pricelist/details/
    */
    public function details_post() { 

        /* Validation section */
        $this->form_validation->set_rules('pricelist_guid', 'Price List GUID', 'trim|required|callback_validate_guid[tbl_pricelists.pricelist_guid.pricelist_id]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
       
        /* To Get Price List Details */
        $this->Return['data'] = $this->Pricelist_model->get_pricelist('pricelist_name,pricelist_brand,is_main_pricelist',array('pricelist_id' => $this->pricelist_id));
    }

    /**
     * Function Name: validate_product_csv_file  
     * Description:   To validate product csv file
     */
    public function validate_product_csv_file() {

        /* Validate Product CSV */
        if(!empty($_FILES['products_csv']['name'])){

            /* Read CSV file */
            $products_csv_data = array_map('str_getcsv', file($_FILES['products_csv']['tmp_name']));
            if(empty($products_csv_data)){
                $this->form_validation->set_message('validate_product_csv_file', lang('product_csv_empty'));
                return FALSE;
            }

            $error = '';
            $p = 0;
            $this->Post['products'] = array();
            for ($i=0; $i < count($products_csv_data); $i++) { 
                if(empty($products_csv_data[$i][0]) && empty($products_csv_data[$i][1]) && empty($products_csv_data[$i][2])){
                    // $error .= '<hr> Row no '.($i+1).' - empty row. <br/>';
                }else if(empty($products_csv_data[$i][0]) || empty($products_csv_data[$i][1]) || empty($products_csv_data[$i][2])){
                    $is_row = FALSE;
                    if(empty($products_csv_data[$i][0])){
                        $is_row = TRUE;
                        $error .= '<hr> Row no '.($i+1).' - Product ID is missing <br/>';
                    }
                    if(empty($products_csv_data[$i][1])){
                        if(!$is_row){
                            $is_row = TRUE;
                            $error .= 'Row no '.($i+1).' - Product Name is missing. <br/>';
                        }else{
                            $error .= 'Product Name is missing. <br/>';
                        }
                    }
                    if(empty($products_csv_data[$i][2])){
                        if(!$is_row){
                            $is_row = TRUE;
                            $error .= 'Row no '.($i+1).' - Product Price is missing. <br/>';
                        }else{
                            $error .= 'Product Price is missing. <br/>';
                        }
                    }
                }else if(!empty($products_csv_data[$i][0]) && !empty($products_csv_data[$i][1]) && !empty($products_csv_data[$i][2])){
                    if($products_csv_data[$i][0] != 'Product ID'){
                        
                        /* First Check Product ID */
                        list($product_id,$product_variant_id) = explode("-",$products_csv_data[$i][0]);

                        /* Check product variant */
                        $query = $this->db->query('SELECT 1 FROM tbl_products_variants WHERE product_id = '.$product_id.' AND product_variant_id = '.$product_variant_id.' LIMIT 1');
                        $is_row = FALSE;
                        if($query->num_rows() == 0){
                            $is_row = TRUE;
                            $error .= '<hr> Row no '.($i+1).' - Product ID is not valid <br/>';
                        }else{

                            /* Check Product Brand */
                            $product_brand = $this->db->query('SELECT product_brand FROM tbl_products WHERE product_id = '.$product_id.' LIMIT 1')->row()->product_brand;
                            if($product_brand != $this->Post['pricelist_brand']){
                                if(!$is_row){
                                    $is_row = TRUE;
                                    $error .= '<hr> Row no '.($i+1).' - Product brand is mismatch. <br/>';
                                }else{
                                    $error .= 'Product brand is mismatch. <br/>';
                                }
                            }
                        }

                        /* Check price */
                        if (!preg_match('/^\d+(\.\d{2})?$/', $products_csv_data[$i][2])) {
                           if(!$is_row){
                                $is_row = TRUE;
                                $error .= '<hr> Row no '.($i+1).' - Product price format is wrong. <br/>';
                            }else{
                                $error .= 'Product price format is wrong. <br/>';
                            }
                        }
                        $this->Post['products'][$p++] = array('product_variant_id' => $product_variant_id, 'product_id' => $product_id, 'product_price' => $products_csv_data[$i][2]);
                    }
                }
            }
            if(!empty($error)){
                $this->form_validation->set_message('validate_product_csv_file', $error);
                return FALSE;
            }else if(empty($error) && empty($this->Post['products'])){
                $this->form_validation->set_message('validate_product_csv_file', "Require valid products.");
                return FALSE;
            }
        }else{
            $this->form_validation->set_message('validate_product_csv_file', "Require Products CSV file.");
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Function Name: validate_pricelist_name  
     * Description:   To validate price list name
     */
    public function validate_pricelist_name() {
        $Query = $this->db->query('SELECT pricelist_id FROM `tbl_pricelists` WHERE pricelist_name = "'.$this->Post['pricelist_name'].'" LIMIT 1');
        if ($Query->num_rows() > 0 && $Query->row()->pricelist_id != $this->pricelist_id) {
            $this->form_validation->set_message('validate_pricelist_name', '{field} '.lang('field_already_exist'));
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Function Name: validate_single_main_pricelist  
     * Description:   To validate single main pricelist
     */
    public function validate_single_main_pricelist() {
        if(!empty($this->Post['is_main_pricelist']) && $this->Post['is_main_pricelist'] == 'Yes'){
            $Query = $this->db->query('SELECT is_main_pricelist,pricelist_guid FROM `tbl_pricelists` WHERE pricelist_brand = "'.$this->Post['pricelist_brand'].'" AND is_main_pricelist = "Yes" LIMIT 1');
            if ($Query->num_rows() > 0) {
                if(empty($this->Post['pricelist_guid']) || (!empty($this->Post['pricelist_guid']) && $this->Post['pricelist_guid'] != $Query->row()->pricelist_guid)){
                    $this->form_validation->set_message('validate_single_main_pricelist', lang('main_price_list_already_created'));
                    return FALSE;
                }
            }
        }
        return TRUE;
    }


  
}