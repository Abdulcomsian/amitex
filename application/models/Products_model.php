<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Products_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Description:  Use to add product.
     */
    function add_product($Input = array()) {

        $this->db->trans_start();

        $insert_array = array_filter(array(
            "product_guid" => get_guid(),
            "product_name" => @ucfirst(strtolower($Input['product_name'])),
            "product_brand" => $Input['product_brand'],
            "product_category_id" => $Input['product_category_id'],
            "product_subcategory_id" => $Input['product_subcategory_id'],
            "product_item_code" => @$Input['product_item_code'],
            "is_premium" => @$Input['is_premium'],
            "product_descprition" => $Input['product_descprition'],
            "product_main_photo" => $Input['product_main_photo'],
            "product_gallery_images" => (!empty($Input['product_gallery_images'])) ? json_encode($Input['product_gallery_images'], JSON_UNESCAPED_UNICODE) : NULL,
            "color_variants" => (!empty($Input['color_variants'])) ? json_encode($Input['color_variants'], JSON_UNESCAPED_UNICODE) : NULL,
            "size_variants" => (!empty($Input['size_variants'])) ? json_encode($Input['size_variants'], JSON_UNESCAPED_UNICODE) : NULL,
            "created_date" => date('Y-m-d H:i:s')
        ));
        $this->db->insert('tbl_products', $insert_array);
        $product_id = $this->db->insert_id();

        /* Add Product Variants */
        if(!empty($Input['color_variants']) && !empty($Input['size_variants'])){
            $variants_array = array();
            for ($i=0; $i < count($Input['color_variants']); $i++) { 
                for ($j=0; $j < count($Input['size_variants']); $j++) { 
                    $variants_array[] = array('product_id' => $product_id, 'color_variant' => $Input['color_variants'][$i], 'size_variant' => $Input['size_variants'][$j]);
                }
            }
            if(!empty($variants_array)){
                $this->db->insert_batch('tbl_products_variants', $variants_array);
            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        return TRUE;
    }

    /*
      Description:  Use to edit product.
     */
    function edit_product($product_id, $Input = array()) {

        $this->db->trans_start();

        $update_array = array_filter(array(
            "product_name" => @ucfirst(strtolower($Input['product_name'])),
            "product_brand" => $Input['product_brand'],
            "product_category_id" => $Input['product_category_id'],
            "product_subcategory_id" => $Input['product_subcategory_id'],
            "is_premium" => @$Input['is_premium'],
            "product_item_code" => @$Input['product_item_code'],
            "product_descprition" => $Input['product_descprition'],
            "product_main_photo" => @$Input['product_main_photo'],
            "color_variants" => (!empty($Input['color_variants'])) ? json_encode($Input['color_variants'], JSON_UNESCAPED_UNICODE) : NULL,
            "size_variants" => (!empty($Input['size_variants'])) ? json_encode($Input['size_variants'], JSON_UNESCAPED_UNICODE) : NULL,
            "modified_date" => date('Y-m-d H:i:s')
        ));

        /* Update Gallery Images */
        if(!empty($Input['product_gallery_images']) || !empty($Input['removed_product_gallery_images'])){
        
            /* Fetch Old Gallery Images */
            $old_gallery_images = json_decode($this->db->query('SELECT product_gallery_images FROM tbl_products WHERE product_id = '.$product_id.' LIMIT 1')->row()->product_gallery_images,TRUE);
            if(!empty($Input['removed_product_gallery_images'])){
                $old_gallery_images = array_values(array_diff($old_gallery_images,$Input['removed_product_gallery_images']));
            }
            $old_gallery_images = (!empty($old_gallery_images)) ? $old_gallery_images : array();
            $update_array['product_gallery_images']  = json_encode(array_merge($old_gallery_images,(!empty($Input['product_gallery_images']) ? $Input['product_gallery_images'] : array())), JSON_UNESCAPED_UNICODE);
        }

        $this->db->where('product_id', $product_id);
        $this->db->limit(1);
        $this->db->update('tbl_products', $update_array);

        /* Add Product Variants */
        // if(!empty($Input['color_variants']) && !empty($Input['size_variants'])){
        //     $variants_array = array();
        //     for ($i=0; $i < count($Input['color_variants']); $i++) { 
        //         for ($j=0; $j < count($Input['size_variants']); $j++) { 
        //             $variants_array[] = array('product_id' => $product_id, 'color_variant' => $Input['color_variants'][$i], 'size_variant' => $Input['size_variants'][$j]);
        //         }
        //     }
        //     if(!empty($variants_array)){
        //         $this->db->insert_batch('tbl_products_variants', $variants_array);
        //     }
        // }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }

        /* Delete Images From Directory */
        if(!empty($Input['removed_product_gallery_images'])){
            foreach($Input['removed_product_gallery_images'] as $image){
                unlink(FCPATH.'uploads/products/'.$image);
            }
        }
        return TRUE;
    }

    /*
      Description: 	Use to get products
     */

    function get_products($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 150) {
        /* Additional fields to select */
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'created_date'  => 'DATE_FORMAT(P.created_date, "' . DATE_FORMAT . '") created_date',
                'product_id'   => 'P.product_id',
                'product_category_id'   => 'P.product_category_id',
                'product_brand'   => 'P.product_brand',
                'product_subcategory_id'   => 'P.product_subcategory_id',
                'product_name'   => 'P.product_name',
                'is_premium'   => 'P.is_premium',
                'product_item_code'   => 'P.product_item_code',
                'product_descprition'   => 'P.product_descprition',
                'color_variants'   => 'P.color_variants',
                'size_variants'    => 'P.size_variants',
                'product_gallery_images'   => 'P.product_gallery_images',
                'product_main_photo_file'   => 'P.product_main_photo product_main_photo_file',
                'product_main_photo' => 'IF(P.product_main_photo IS NULL,CONCAT("' . BASE_URL . '","uploads/products/","default-product.jpg"),CONCAT("' . BASE_URL . '","uploads/products/",P.product_main_photo)) AS product_main_photo',
                'category_name' => 'C.category_name',
                'category_guid' => 'C.category_guid',
                'subcategory_name' => 'SC.subcategory_name',
                'subcategory_guid' => 'SC.subcategory_guid',
                'pricelist_variants_count' => '(SELECT COUNT(*) FROM tbl_pricelist_variants WHERE product_id = P.product_id) pricelist_variants_count'
            );
            
            foreach ($Params as $Param) {
                $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
            }
        }
        $this->db->select('P.product_guid');
        if (!empty($Field)) $this->db->select($Field, FALSE);
        $this->db->from('tbl_products P');
        if (array_keys_exist($Params, array('category_name', 'category_guid'))) {
            $this->db->from('tbl_categories C');
            $this->db->where("C.category_id", "P.product_category_id", FALSE);
        }
        if (array_keys_exist($Params, array('subcategory_name', 'subcategory_guid'))) {
            // $this->db->from('tbl_subcategories SC');
            // $this->db->where("SC.subcategory_id", "P.product_subcategory_id", FALSE);
            $this->db->join('tbl_subcategories SC', 'P.product_subcategory_id = SC.subcategory_id', 'left');
        }
        if (!empty($Where['keyword'])) {
            $Where['keyword'] = trim($Where['keyword']);
            $this->db->group_start();
            $this->db->like("C.category_name", $Where['keyword']);
            $this->db->or_like("SC.subcategory_name", $Where['keyword']);
            $this->db->or_like("P.product_name", $Where['keyword']);
            $this->db->group_end();
        }
        if (!empty($Where['product_guid'])) {
            $this->db->where("P.product_guid", $Where['product_guid']);
        }
        if (!empty($Where['product_brand'])) {
            $this->db->where("P.product_brand", $Where['product_brand']);
        }
        if (!empty($Where['is_premium'])) {
            $this->db->where("P.is_premium", $Where['is_premium']);
        }
        if (!empty($Where['product_category_id'])) {
            $this->db->where("P.product_category_id", $Where['product_category_id']);
        }
        if (!empty($Where['product_subcategory_id'])) {
            $this->db->where("P.product_subcategory_id", $Where['product_subcategory_id']);
        }
        if (!empty($Where['product_id'])) {
            $this->db->where("P.product_id", $Where['product_id']);
        }
        if (!empty($Where['product_ids'])) {
            $this->db->where_in("P.product_id", $Where['product_ids']);
        }
        if (!empty($Where['order_by']) && !empty($Where['sequence']) && in_array($Where['sequence'], array('ASC', 'DESC'))) {
            $this->db->order_by($Where['order_by'], $Where['sequence']);
        } else {
            $this->db->order_by('P.product_id', 'DESC');
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
            if ($multiRecords) {
                $Records = array();
                foreach ($Query->result_array() as $key => $Record) {
                    $Records[] = $Record;
                    if (in_array('color_variants', $Params)) {
                        $Records[$key]['color_variants'] = (!empty($Record['color_variants'])) ? json_decode($Record['color_variants'], TRUE) : array();
                    }
                    if (in_array('size_variants', $Params)) {
                        $Records[$key]['size_variants'] = (!empty($Record['size_variants'])) ? json_decode($Record['size_variants'], TRUE) : array();
                    }
                    if (in_array('product_gallery_images', $Params)) {
                        $Records[$key]['product_gallery_images'] = (!empty($Record['product_gallery_images'])) ? json_decode($Record['product_gallery_images'], TRUE) : array();
                    }
                    if (in_array('product_varinats_prices', $Params)) {
                        $product_variants = $this->get_product_variants_price('product_variant_id,color_variant,size_variant,in_stock,product_price',array('pricelist_ids' => $Where['pricelist_ids'], 'product_id' => $Record['product_id'], 'pricelist_variants_count' => $Record['pricelist_variants_count'] , 'order_by' => 'product_name', 'sequence' => 'ASC'),TRUE);
                        $Records[$key]['product_varinats_prices'] = (!empty($product_variants['data']['records'])) ? $product_variants['data']['records'] : array();
                    }
                }
                $Return['data']['records'] = $Records;
                return $Return;
            } else {
                $Record = $Query->row_array();
                if (in_array('color_variants', $Params)) {
                    $Record['color_variants'] = (!empty($Record['color_variants'])) ? json_decode($Record['color_variants'], TRUE) : array();
                }
                if (in_array('size_variants', $Params)) {
                    $Record['size_variants'] = (!empty($Record['size_variants'])) ? json_decode($Record['size_variants'], TRUE) : array();
                }
                if (in_array('product_gallery_images', $Params)) {
                    $Record['product_gallery_images'] = (!empty($Record['product_gallery_images'])) ? json_decode($Record['product_gallery_images'], TRUE) : array();
                }
                if (in_array('product_varinats_prices', $Params)) {
                        $product_variants = $this->get_product_variants_price('product_variant_id,color_variant,size_variant,in_stock,product_price',array('pricelist_ids' => $Where['pricelist_ids'], 'product_id' => $Record['product_id'] , 'order_by' => 'product_name', 'sequence' => 'ASC'),TRUE);
                        $Record['product_varinats_prices'] = (!empty($product_variants['data']['records'])) ? $product_variants['data']['records'] : array();
                    }
                return $Record;
            }
        }
        return FALSE;
    }

    /*
      Description:  Use to get product variants price
     */

    function get_product_variants_price($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 150) {
        /* Additional fields to select */
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'product_name'   => 'P.product_name',
                'product_main_photo' => 'IF(P.product_main_photo IS NULL,CONCAT("' . BASE_URL . '","uploads/products/","default-product.jpg"),CONCAT("' . BASE_URL . '","uploads/products/",P.product_main_photo)) AS product_main_photo',
                'product_id' => 'PV.product_id',
                'product_variant_id' => 'PV.product_variant_id',
                'color_variant' => 'PV.color_variant',
                'size_variant' => 'PV.size_variant',
                'in_stock' => 'PV.in_stock',
                'product_price' => 'PLV.product_price'
            );
            
            foreach ($Params as $Param) {
                $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
            }
        }
        $this->db->select('P.product_guid');
        if (!empty($Field)) $this->db->select($Field, FALSE);
        $this->db->from('tbl_products P');
        if (array_keys_exist($Params, array('product_id', 'product_variant_id','color_variant','size_variant'))) {
            $this->db->from('tbl_products_variants PV');
            $this->db->where("PV.product_id", "P.product_id", FALSE);
        }
        if (array_keys_exist($Params, array('product_price'))) {
            $this->db->from('tbl_pricelist_variants PLV');
            $this->db->where("PLV.product_variant_id", "PV.product_variant_id", FALSE);
        }
        if (!empty($Where['keyword'])) {
            $Where['keyword'] = trim($Where['keyword']);
            $this->db->group_start();
            $this->db->like("P.product_name", $Where['keyword']);
            $this->db->group_end();
        }
        if (!empty($Where['product_id'])) {
            $this->db->where("P.product_id", $Where['product_id']);
        }
        if (!empty($Where['product_variant_id'])) {
            $this->db->where("PLV.product_variant_id", $Where['product_variant_id']);
        }
        if (!empty($Where['pricelist_ids'])) {
            $this->db->where_in("PLV.pricelist_id", $Where['pricelist_ids']);
        }
        if (!empty($Where['order_by']) && !empty($Where['sequence']) && in_array($Where['sequence'], array('ASC', 'DESC'))) {
            $this->db->order_by($Where['order_by'], $Where['sequence']);
        } else {
            $this->db->order_by('P.product_id', 'DESC');
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
            if ($multiRecords) {
                $Return['data']['records'] = $Query->result_array();
                return $Return;
            } else {
                return $Query->row_array();
            }
        }
        return FALSE;
    }

    /*
      Description:  Use to update product
     */
    function update_product($product_id, $Input = array()) {
        $update_array = array_filter(array(
            "product_name" => @ucfirst(strtolower($Input['product_name'])),
            "product_brand" => $Input['product_brand'],
            "product_category_id" => $Input['product_category_id'],
            "product_subcategory_id" => $Input['product_subcategory_id'],
            "product_descprition" => $Input['product_descprition'],
            "is_premium" => @$Input['is_premium'],
            "product_main_photo" => $Input['product_main_photo'],
            "product_gallery_images" => (!empty($Input['product_gallery_images'])) ? json_encode($Input['product_gallery_images'], JSON_UNESCAPED_UNICODE) : NULL,
            "color_variants" => (!empty($Input['color_variants'])) ? json_encode($Input['color_variants'], JSON_UNESCAPED_UNICODE) : NULL,
            "size_variants" => (!empty($Input['size_variants'])) ? json_encode($Input['size_variants'], JSON_UNESCAPED_UNICODE) : NULL,
            "modified_date" => date('Y-m-d H:i:s')
        ));

        $this->db->where('product_id', $product_id);
        $this->db->limit(1);
        $this->db->update('tbl_products', $update_array);
        return TRUE;
    }

    /*
      Description:  Use to delete product.
    */
    function delete_product($product_guid) {
        $this->db->where('product_guid',$product_guid);
        $this->db->limit(1);
        $this->db->delete('tbl_products');
        if($this->db->affected_rows() > 0){
            return TRUE;
        }
        return FALSE;
    }

    /*
      Description:  Use to upload products.
     */
    function upload_products($Input = array(),$session_user_id) {

        /* Set max exceution time */
       ini_set('max_execution_time', 1800); // 30 minutes

       /* Set memory limit */
       ini_set('memory_limit', '1024M'); 

       $pricelist_ids_array = $product_ids = array();
       $error_array = array();
       $total_success_records = 0;

       /* Products */
       foreach($Input['products_data'] as $key => $product){

            /* Check Item Code */
            if (empty(trim($product[0]))) {
              $error_array[] = 'Row no. '.($key+2).' Item code can not empty.';
              continue;
            }

            /* Check Item Name */
            if (empty(trim($product[1]))) {
              $error_array[] = 'Row no. '.($key+2).' Item name can not empty.';
              continue;
            }

            /* Check Product Size */
            if (empty(trim($product[2]))) {
              $error_array[] = 'Row no. '.($key+2).' Size can not empty.';
              continue;
            }

            /* Check Product Color */
            if (empty(trim($product[3]))) {
              $error_array[] = 'Row no. '.($key+2).' Color can not empty.';
              continue;
            }

            /* Check Product Category */
            if (empty(trim($product[4]))) {
              $error_array[] = 'Row no. '.($key+2).' Category can not empty.';
              continue;
            }

            /* Check Discount Code */
            if (empty(trim($product[14]))) {
              $error_array[] = 'Row no. '.($key+2).' Discount code can not empty.';
              continue;
            }else if(!in_array(trim($product[14]),array(1,2))){
                $error_array[] = 'Row no. '.($key+2).' Discount code value should be 1 Or 2.';
                continue;
            }

            /* Check Premium Product */
            if (trim($product[16]) == "") {
              $error_array[] = 'Row no. '.($key+2).' premium can not empty.';
              continue;
            }else if(!in_array(trim($product[16]),array(0,1))){
                $error_array[] = 'Row no. '.($key+2).' premium value should be 0 Or 1.';
                continue;
            }

            /* Check In Stock Product */
            if (trim($product[17]) == "") {
              $error_array[] = 'Row no. '.($key+2).' in stock can not empty.';
              continue;
            }else if(!in_array(trim($product[17]),array(0,1))){
                $error_array[] = 'Row no. '.($key+2).' in stock value should be 0 Or 1.';
                continue;
            }

            /* To Check If Item Name already exists */
            $product_brand = (trim($product[14]) == 1) ? 'Amitex' : 'Michal Negrin';
            $this->db->select('product_id,product_gallery_images');
            $this->db->from('tbl_products');
            $this->db->where('product_name', trim($product[1]));
            $this->db->limit(1);
            $query = $this->db->get();
            if($query->num_rows() == 0){

                /* Check If Category already present in database */
                $this->db->select('category_id');
                $this->db->from('tbl_categories');
                $this->db->where('category_name', trim($product[4]));
                $this->db->limit(1);
                $query = $this->db->get();
                if($query->num_rows() > 0){
                    $category_id = $query->row()->category_id;
                }else{
                    $this->db->insert('tbl_categories', array('category_guid' => get_guid(), 'category_name' => trim($product[4]), 'created_date' => date('Y-m-d H:i:s')));
                    $category_id = $this->db->insert_id();
                    if(!$category_id){
                        $error_array[] = 'Row no. '.($key+2).' failed to insert category into database.';
                        continue;
                    }
                }

                /* Check If Sub Category already present*/
                $subcategory_id = "";
                if (!empty(trim($product[5]))) {
                    $this->db->select('subcategory_id');
                    $this->db->from('tbl_subcategories');
                    $this->db->where('subcategory_name', trim($product[5]));
                    $this->db->limit(1);
                    $query = $this->db->get();
                    if($query->num_rows() > 0){
                        $subcategory_id = $query->row()->subcategory_id;
                    }else{
                        $this->db->insert('tbl_subcategories', array('subcategory_guid' => get_guid(), 'category_id' => $category_id, 'subcategory_name' => trim($product[5]), 'created_date' => date('Y-m-d H:i:s')));
                        $subcategory_id = $this->db->insert_id();
                        if(!$subcategory_id){
                            $error_array[] = 'Row no. '.($key+2).' failed to insert sub category into database.';
                            continue;
                        }
                    }
                }

                /* Insert Product */
                $insert_array = array_filter(array(
                                    'product_guid' => get_guid(),
                                    'product_item_code' => trim($product[0]),
                                    'product_category_id' => $category_id,
                                    'product_subcategory_id' => $subcategory_id,
                                    'product_name' => trim($product[1]),
                                    'product_descprition' => (!empty($product[15])) ? trim($product[15]) : "",
                                    'is_premium' => (trim($product[16]) == 1) ? 'Yes' : "No",
                                    'product_main_photo' => trim($product[6]),
                                    'product_brand' => $product_brand,
                                    'created_date' => date('Y-m-d H:i:s')
                                ));
                $this->db->insert('tbl_products', $insert_array);
                $product_id = $this->db->insert_id();
            }else{

                $product_id = $query->row()->product_id;
                $product_gallery_images = (!empty($query->row()->product_gallery_images)) ? json_decode($query->row()->product_gallery_images, TRUE) : array();

                /* Check If Category already present in database */
                $this->db->select('category_id');
                $this->db->from('tbl_categories');
                $this->db->where('category_name', trim($product[4]));
                $this->db->limit(1);
                $query = $this->db->get();
                if($query->num_rows() > 0){
                    $category_id = $query->row()->category_id;
                }else{
                    $this->db->insert('tbl_categories', array('category_guid' => get_guid(), 'category_name' => trim($product[4]), 'created_date' => date('Y-m-d H:i:s')));
                    $category_id = $this->db->insert_id();
                    if(!$category_id){
                        $error_array[] = 'Row no. '.($key+2).' failed to insert category into database.';
                        continue;
                    }
                }

                /* Check If Sub Category already present*/
                $subcategory_id = "";
                if (!empty(trim($product[5]))) {
                    $this->db->select('subcategory_id');
                    $this->db->from('tbl_subcategories');
                    $this->db->where('subcategory_name', trim($product[5]));
                    $this->db->limit(1);
                    $query = $this->db->get();
                    if($query->num_rows() > 0){
                        $subcategory_id = $query->row()->subcategory_id;
                    }else{
                        $this->db->insert('tbl_subcategories', array('subcategory_guid' => get_guid(), 'category_id' => $category_id, 'subcategory_name' => trim($product[5]), 'created_date' => date('Y-m-d H:i:s')));
                        $subcategory_id = $this->db->insert_id();
                        if(!$subcategory_id){
                            $error_array[] = 'Row no. '.($key+2).' failed to insert sub category into database.';
                            continue;
                        }
                    }
                }

                /* Update Product */
                $update_array = array_filter(array(
                                    'product_item_code' => trim($product[0]),
                                    'product_category_id' => $category_id,
                                    'product_subcategory_id' => $subcategory_id,
                                    'product_descprition' => (!empty($product[15])) ? trim($product[15]) : "",
                                    'is_premium' => (trim($product[16]) == 1) ? 'Yes' : "No",
                                    // 'product_main_photo' => trim($product[6]),
                                    'product_brand' => $product_brand
                                ));
                $this->db->where('product_id', $product_id);
                $this->db->limit(1);
                $this->db->update('tbl_products', $update_array);

                /* Update Gallery Images */
                if(!empty(trim($product[6]))){
                    $product_gallery_images[] = trim($product[6]);
                    $product_gallery_images = array_values(array_unique($product_gallery_images));

                    /* Update Into Database */
                    $this->db->where('product_id', $product_id);
                    $this->db->limit(1);
                    $this->db->update('tbl_products', array("product_gallery_images" => json_encode($product_gallery_images, JSON_UNESCAPED_UNICODE)));
                }
            }
            $product_ids[] = $product_id;

            /* To Check If Product Variant Already Exist */
            $this->db->select('product_variant_id');
            $this->db->from('tbl_products_variants');
            $this->db->where(array('product_id' => $product_id, 'color_variant' => trim($product[3]), 'size_variant' => trim($product[2])));
            $this->db->limit(1);
            $query = $this->db->get();
            if($query->num_rows() == 0){

                /* Insert Product Variant */
                $this->db->insert('tbl_products_variants', array('product_id' => $product_id, 'color_variant' => trim($product[3]), 'size_variant' => trim($product[2]),'in_stock' => trim($product[17])));
                $product_variant_id = $this->db->insert_id();
                if(!$product_variant_id){
                    $error_array[] = 'Row no. '.($key+2).' failed to insert product variant into database.';
                    continue;
                }
            }else{
               $product_variant_id = $query->row()->product_variant_id;

               /* Update Into Database */
                $this->db->where('product_variant_id', $product_variant_id);
                $this->db->limit(1);
                $this->db->update('tbl_products_variants', array("in_stock" => trim($product[17])));
            }

            /* Manage Price List */
            if(empty($pricelist_ids_array[trim($product[14])])){
                for ($i=0; $i < 5; $i++) { 

                    /* To Check if Brand is already exists */
                    $this->db->select('pricelist_id');
                    $this->db->from('tbl_pricelists');
                    $this->db->where(array('pricelist_brand' => $product_brand, 'is_main_pricelist' => 'No', 'pricelist_name' => 'Price list '.($i+1)));
                    $this->db->limit(1);
                    $query = $this->db->get();
                    if($query->num_rows() == 0){
                        $this->db->insert('tbl_pricelists', array('pricelist_guid' => get_guid(), 'pricelist_name' => 'Price list '.($i+1), 'pricelist_brand' => $product_brand, 'created_date' => date('Y-m-d H:i:s')));
                        $pricelist_ids_array[trim($product[14])][$i+1] = $this->db->insert_id();
                    }else{
                        $pricelist_ids_array[trim($product[14])][$i+1] = $query->row()->pricelist_id;
                    }
                }

                /* Manage Consumer Price list */
                $this->db->select('pricelist_id');
                $this->db->from('tbl_pricelists');
                $this->db->where(array('pricelist_brand' => $product_brand, 'is_main_pricelist' => 'Yes'));
                $this->db->limit(1);
                $query = $this->db->get();
                if($query->num_rows() == 0){
                    $this->db->insert('tbl_pricelists', array('pricelist_guid' => get_guid(), 'pricelist_name' => 'Consumer price', 'pricelist_brand' => $product_brand, 'is_main_pricelist' => 'Yes', 'created_date' => date('Y-m-d H:i:s')));
                    $pricelist_ids_array[trim($product[14])][6] = $this->db->insert_id();
                }else{
                    $pricelist_ids_array[trim($product[14])][6] = $query->row()->pricelist_id;
                }
            }

            /* Delete Old Product Varaints */
            $this->db->where(array('product_variant_id' => $product_variant_id, 'product_id' => $product_id));
            $this->db->delete('tbl_pricelist_variants');

            /* Manage Price list product variants */
            $pricelist_variants_arr = array();
            for ($i=0; $i < 5; $i++) { 
                $pricelist_variants_arr[] = array(
                                    'pricelist_id' => $pricelist_ids_array[trim($product[14])][$i+1],
                                    'product_id' => $product_id,
                                    'product_variant_id' => $product_variant_id,
                                    'product_price' => (empty(trim($product[7+$i]))) ? trim($product[12]) : trim($product[7+$i])
                                );
            }
            if(!empty($pricelist_variants_arr)){
                $this->db->insert_batch('tbl_pricelist_variants', $pricelist_variants_arr);
            }

            /* Insert Main/Default pricelist */
            $this->db->insert('tbl_pricelist_variants', array('pricelist_id' => $pricelist_ids_array[trim($product[14])][6], 'product_id' => $product_id, 'product_variant_id' => $product_variant_id, 'product_price' => trim($product[12])));
            $total_success_records = $total_success_records + 1;
       }

       /* Update Product Variants */
       if(!empty($product_ids)){
        foreach(array_values(array_unique($product_ids)) as $product_id){
            $color_variants = $this->db->query('SELECT color_variant FROM tbl_products_variants WHERE product_id = '.$product_id.' Group By color_variant order by product_variant_id');
            $size_variants = $this->db->query('SELECT size_variant FROM tbl_products_variants WHERE product_id = '.$product_id.' Group By size_variant order by product_variant_id');
            // $instock_variants = $this->db->query('SELECT in_stock FROM tbl_products_variants WHERE product_id = '.$product_id.' Group By in_stock order by product_variant_id');
            $update_array = array(
                            'color_variants' => ($color_variants->num_rows() > 0) ? json_encode(array_column($color_variants->result_array(),'color_variant'), JSON_UNESCAPED_UNICODE) : null,
                            'size_variants' => ($size_variants->num_rows() > 0) ? json_encode(array_column($size_variants->result_array(),'size_variant'), JSON_UNESCAPED_UNICODE) : null
                            // 'instock_variants' => ($instock_variants->num_rows() > 0) ? json_encode(array_column($instock_variants->result_array(),'size_variant'), JSON_UNESCAPED_UNICODE) : null
                        );
            $this->db->where('product_id', $product_id);
            $this->db->limit(1);
            $this->db->update('tbl_products', $update_array);
        }
       }
       return array('is_error' => ((!empty($error_array)) ? 1 : 0), 'is_success' => ((!empty($total_success_records)) ? 1 : 0), 'total_success_records' => $total_success_records ,'error_array' => $error_array);
    }

    /*
      Description:  Use to delete multiple product.
    */
    function delete_multi_product($product_guid) {
        $this->db->where_in('product_guid',$product_guid);
        $this->db->delete('tbl_products');
        if($this->db->affected_rows() > 0){
            return TRUE;
        }
        return FALSE;
    }

    /*
      Description:  Product Export in CSV.
    */
    function export_products($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 15000) {

        /* Additional fields to select */
        $query = $this->db->select('(SELECT product_gallery_images FROM tbl_products WHERE product_id = tp.product_id LIMIT 1) product_gallery_images, tp.product_id,tp.product_item_code,tp.product_name,tp.is_premium,tp.product_brand,tp.product_main_photo,tp.product_gallery_images,tpv.color_variant,tpv.size_variant,tpv.in_stock,tp.product_descprition,tpv.product_variant_id,tpv.in_stock,tc.category_name,tsc.subcategory_name')->from('tbl_products as tp')->join('tbl_products_variants as tpv', 'tpv.product_id = tp.product_id')
        ->join('tbl_categories as tc', 'tc.category_id = tp.product_category_id')
        ->join('tbl_subcategories as tsc', 'tsc.subcategory_id = tp.product_subcategory_id')->get()->result();
        if($query){
            $product_ids = array();
            $index = 0;
            foreach($query as $Row){
                $products = array(
                            'item_code' => $Row->product_item_code,
                            'product_name' => $Row->product_name,
                            'size' => $Row->size_variant,
                            'color' => $Row->color_variant,
                            'category' => $Row->category_name,
                            'subcategory' => $Row->subcategory_name,
                            'picture_name'=> ''
                        );

                /* Append picture name */
                if(!in_array($Row->product_id, $product_ids)){
                    $products['picture_name'] = $Row->product_main_photo;
                    $product_ids[] = $Row->product_id;
                    $index = 0;
                }else if(in_array($Row->product_id, $product_ids) && !empty($Row->product_gallery_images)){
                    $product_gallery_images   = json_decode($Row->product_gallery_images, TRUE);
                    $products['picture_name'] = (isset($product_gallery_images[$index])) ? $product_gallery_images[$index] : '';
                    $index++;
                }
                $priceQuery = $this->db->select('tpl.pricelist_name,tplv.product_price')->from('tbl_pricelists as tpl')->join('tbl_pricelist_variants as tplv', 'tplv.pricelist_id = tpl.pricelist_id')->where(array('tplv.product_id'=>$Row->product_id,'tplv.product_variant_id'=>$Row->product_variant_id))->get()->result();
                foreach($priceQuery as $price){
                    $products[$price->pricelist_name] = $price->product_price;
                }
                $products['Barcode'] = '12365478';
                $products['discound_code'] = ($Row->product_brand == 'Amitex') ? 1 : 2;
                $products['description'] = $Row->product_descprition;
                $products['premium'] = ($Row->is_premium=='Yes') ? 1 : 0;
                $products['in_stock'] = $Row->in_stock;
                $product[] = $products;
            }
            return $product;
        }
        else{
            return array();
        }
    }


}
