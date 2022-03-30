<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends API_Controller_Secure {

    function __construct() {
        parent::__construct();
        $this->load->model('Products_model');
        if($this->user_type_id != 1){
            $this->Return['status']  = 500;
            $this->Return['message'] = lang('access_denied');
            exit;
        }
    }

    /*
      Description: 	To add new product
      URL: 			/admin/api/products/add/
    */
    public function add_post() {

        /* Validation section */
        $this->form_validation->set_rules('product_name', 'Product Name', 'trim|required');
        $this->form_validation->set_rules('product_item_code', 'Product Item Number', 'trim|required');
        $this->form_validation->set_rules('product_brand', 'Product Brand', 'trim|required|in_list[Amitex,Michal Negrin]');
        $this->form_validation->set_rules('is_premium', 'Is Premium', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('product_category_id', 'Product Category', 'trim|required|callback_validate_guid[tbl_categories.category_guid.category_id]');
        $this->form_validation->set_rules('product_subcategory_id', 'Product Sub Category', 'trim|required|callback_validate_guid[tbl_subcategories.subcategory_guid.subcategory_id]');
        $this->form_validation->set_rules('product_descprition', 'Product Descprition', 'trim|required');
        $this->form_validation->set_rules('color_variants[]', 'Color Variants', 'trim');
        $this->form_validation->set_rules('size_variants[]', 'Size Variants', 'trim');
        $this->form_validation->set_rules('product_main_photo', 'Product Main Photo', 'trim');
        $this->form_validation->set_rules('product_gallery_images[]', 'Product Gallery Images', 'trim|required');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Check Variants */
        // if(count($this->Post['color_variants']) != count($this->Post['size_variants'])){
        //     $this->Return['status']  = 500;
        //     $this->Return['message'] = lang('color_size_length_equal');
        //     exit;
        // }

        /* Upload main photo */
        if(!empty($_FILES['product_main_photo']['name'])){
            $image_data = fileUploading('product_main_photo','products','jpg|jpeg|png|gif');
            if(!empty($image_data['error'])){
                $this->Return['status'] = 500;
                $this->Return['message'] = lang('product_main_photo').' - '.$image_data['error'];
                exit;
            }
            $this->Post['product_main_photo'] = $image_data['upload_data']['file_name'];
        }else{
            $this->Return['status']  = 500;
            $this->Return['message'] = lang('require_product_main_photo');
            exit;
        }

        /* Check Variants */
        $this->Post['color_variants'] = (!empty($this->Post['color_variants'])) ? array_values(array_filter($this->Post['color_variants']))  : NULL;
        $this->Post['size_variants'] = (!empty($this->Post['size_variants'])) ? array_values(array_filter($this->Post['size_variants']))  : NULL;
        if(!$this->Products_model->add_product(array_merge($this->Post,array('product_category_id' => $this->category_id, 'product_subcategory_id' => $this->subcategory_id)))){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{

            $this->Return['status'] = 200;
            $this->Return['message'] = lang('product_added');   
        }
    }

    /*
      Description:  To edit product
      URL:          /admin/api/products/edit/
    */
    public function edit_post() {

        /* Validation section */
        $this->form_validation->set_rules('product_guid', 'Product GUID', 'trim|required|callback_validate_guid[tbl_products.product_guid.product_id]');
        $this->form_validation->set_rules('product_name', 'Product Name', 'trim|required');
        $this->form_validation->set_rules('product_item_code', 'Product Item Number', 'trim|required');
        $this->form_validation->set_rules('product_brand', 'Product Brand', 'trim|required|in_list[Amitex,Michal Negrin]');
        $this->form_validation->set_rules('is_premium', 'Is Premium', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('product_category_id', 'Product Category', 'trim|required|callback_validate_guid[tbl_categories.category_guid.category_id]');
        $this->form_validation->set_rules('product_subcategory_id', 'Product Sub Category', 'trim|required|callback_validate_guid[tbl_subcategories.subcategory_guid.subcategory_id]');
        $this->form_validation->set_rules('product_descprition', 'Product Descprition', 'trim|required');
        $this->form_validation->set_rules('color_variants[]', 'Color Variants', 'trim');
        $this->form_validation->set_rules('size_variants[]', 'Size Variants', 'trim');
        $this->form_validation->set_rules('product_main_photo', 'Product Main Photo', 'trim');
        $this->form_validation->set_rules('product_gallery_images[]', 'Product Gallery Images', 'trim');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Upload main photo */
        if(!empty($_FILES['product_main_photo']['name'])){
            $image_data = fileUploading('product_main_photo','products','jpg|jpeg|png|gif');
            if(!empty($image_data['error'])){
                $this->Return['status'] = 500;
                $this->Return['message'] = lang('product_main_photo').' - '.$image_data['error'];
                exit;
            }
            $this->Post['product_main_photo'] = $image_data['upload_data']['file_name'];
        }

        /* Check Variants */
        $this->Post['color_variants'] = (!empty($this->Post['color_variants'])) ? array_values(array_filter($this->Post['color_variants']))  : NULL;
        $this->Post['size_variants'] = (!empty($this->Post['size_variants'])) ? array_values(array_filter($this->Post['size_variants']))  : NULL;
        if(!$this->Products_model->edit_product($this->product_id,array_merge($this->Post,array('product_category_id' => $this->category_id, 'product_subcategory_id' => $this->subcategory_id)))){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{

            $this->Return['status'] = 200;
            $this->Return['message'] = lang('product_updated');   
        }
    }

    /*
      Description:  To upload products (using csv file)
      URL:          /admin/api/products/upload/
    */
    public function upload_post() {

        /* Validation section */
        $this->form_validation->set_rules('products_csv', 'Products CSV', 'trim|callback_validate_products_csv_file');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Insert Data */
        $this->Return['data'] = $this->Products_model->upload_products($this->Post,$this->session_user_id);
    }

    /**
     * Function Name: validate_products_csv_file  
     * Description:   To validate products csv file
     */
    public function validate_products_csv_file() {

        /* Validate Products CSV */
        if(!empty($_FILES['products_csv']['name'])){

            /* Read CSV file */
            $products_csv_data = array_map('str_getcsv', file($_FILES['products_csv']['tmp_name']));
            if(empty($products_csv_data)){
                $this->form_validation->set_message('validate_products_csv_file', lang('products_csv_empty'));
                return FALSE;
            }
            unset($products_csv_data[0]);
            $this->Post['products_data'] = array_values($products_csv_data);
        }else{
            $this->form_validation->set_message('validate_products_csv_file', "Require Products CSV file.");
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Function Name: delete_all
     * Description:   To delete all product
    */
    public function delete_multiple_product_post() {
        
        /*  To check product guid */  
        if(count($this->Post['product_guid'])>0){
            /* To Get Product Details */                
            $query = $this->db->query('SELECT product_main_photo,product_gallery_images FROM tbl_products WHERE product_guid IN ("'.implode('","',$this->Post['product_guid']).'") ');
            $details = $query->result_array();
            
            /* delete images also */
            if(!$this->Products_model->delete_multi_product($this->Post['product_guid'])){  
                $this->Return['status'] = 500;
                $this->Return['message'] = lang('error_occured'); 
                //return FALSE;
            }
            else{             
                foreach($details as $Rows){
                    /* Remove product main photo */
                    unlink(FCPATH.'uploads/products/'.$Rows['product_main_photo']);
                    /* Remove Gallery Images */
                    if(!empty($Rows['product_gallery_images'])){
                        foreach($Rows['product_gallery_images'] as $gallery_image){
                            unlink(FCPATH.'uploads/products/'.$gallery_image);
                        }
                    }  
                }  
                $this->Return['status'] = 200;
                $this->Return['message'] = lang('product_deleted'); 
            }            
        }
        else{
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('product_id_not_found');
        }  
    }

  
}