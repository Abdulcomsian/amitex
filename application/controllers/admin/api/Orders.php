<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends API_Controller_Secure {

    function __construct() {
        parent::__construct();
        $this->load->model('Products_model');
        $this->load->model('Orders_model');
    }

    /*
      Description:  To add product into cart
      URL:          /admin/api/orders/product_add_to_cart/
    */
    public function product_add_to_cart_post() {

        /* Validation section */
        $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|numeric');
        $this->form_validation->set_rules('product_guid', 'Product GUID', 'trim|required');
        $this->form_validation->set_rules('product_variant_id', 'Product Variant ID', 'trim|required');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */
        
        /* To Check Product */
        if(strlen($this->Post['product_guid']) == 36){ // product guid
            $product_data = $this->Products_model->get_products('product_name,product_id,product_brand,is_premium',array('product_guid' => $this->Post['product_guid']));
        }else{
            $product_data = $this->Products_model->get_products('product_name,product_id,product_brand,is_premium',array('product_id' => $this->Post['product_guid']));
        }
        if(empty($product_data)){
            $this->Return['status'] = 500;
            $this->Return['message'] = "Invalid Product GUID.";
            exit;
        }

        /* To check product variant */
        $product_variant_data = $this->Products_model->get_product_variants_price('color_variant,size_variant,product_price',array('product_id' => $product_data['product_id'], 'product_variant_id' => $this->Post['product_variant_id'], 'pricelist_ids' => array($this->session->userdata('order_user_pricelist_id'),$this->session->userdata('order_user_michal_pricelist_id'))));
        if(empty($product_variant_data)){
            $this->Return['status'] = 500;
            $this->Return['message'] = "Invalid Product Variant ID.";
            exit;
        }

        /* To Get Client Details */
        $client_details = $this->db->query('SELECT percent_reduction,percent_reduction_michal FROM tbl_users WHERE user_id = '.$this->session->userdata('order_user_id').' LIMIT 1')->row_array();
        $product_variant_data['product_price'] = getProductDiscountPrice($product_variant_data['product_price'],$product_data['is_premium'],(($product_data['product_brand'] == 'Amitex') ? $client_details['percent_reduction'] :  $client_details['percent_reduction_michal']));

        /* To Get Client Order Session */
        $client_order_data = $this->session->userdata('client_order_data');
        if(!empty($client_order_data)){
            if($this->session->userdata('order_user_id') != $client_order_data['client_id']){
                $this->Return['status'] = 500;
                $this->Return['message'] = "It seems shopping cart data already filled for another client, first please clear the cart & then add products into cart.";
                exit;
            }
            $order_product_data = $client_order_data['product_data'];
        }else{
            $client_order_data = array();
            $client_order_data['client_id'] = $this->session->userdata('order_user_id');
            $order_product_data = array();
        }
        $order_product_data[$product_data['product_id']] = array(
                                            'product_name' => $product_data['product_name'],
                                            'product_id' => $product_data['product_id'],
                                            'product_variants' => (!isset($order_product_data[$product_data['product_id']])) ? array() : $order_product_data[$product_data['product_id']]['product_variants']
                                        );

        if($this->Post['quantity'] == 0){
            if(isset($order_product_data[$product_data['product_id']]['product_variants'][$this->Post['product_variant_id']])){
                unset($order_product_data[$product_data['product_id']]['product_variants'][$this->Post['product_variant_id']]);
            }
            if(empty($order_product_data[$product_data['product_id']]['product_variants'])){
               unset($order_product_data[$product_data['product_id']]);
            }
        }else{
            $order_product_data[$product_data['product_id']]['product_variants'][$this->Post['product_variant_id']] = array(
                            'product_variant_id' => $this->Post['product_variant_id'],
                            'color_variant'      => $product_variant_data['color_variant'],
                            'size_variant'       => $product_variant_data['size_variant'],
                            'unit_price'         => $product_variant_data['product_price'],
                            'quantity'           => $this->Post['quantity'],
                            'total_price'        => $this->Post['quantity'] * $product_variant_data['product_price'],
                            'product_note'        => $this->Post['note']
                        );
        }
        if(!empty($order_product_data[$product_data['product_id']]['product_variants'])){
          $order_product_data[$product_data['product_id']]['product_total_price'] = array_sum(array_column(array_values($order_product_data[$product_data['product_id']]['product_variants']),'total_price'));
        }
        $client_order_data['product_data'] = $order_product_data;
        $client_order_data['total_products'] = count($client_order_data['product_data']);
        $client_order_data['total_cart_amount'] = array_sum(array_column(array_values($client_order_data['product_data']),'product_total_price'));

        /* Set Order Session Data */
        $this->session->set_userdata('client_order_data',$client_order_data);

        /* Return Success */
        $this->Return['status'] = ($this->Post['quantity'] > 0) ? 200 : 500;
        $this->Return['data']   = array('total_products' => $client_order_data['total_products'],'total_cart_amount' => $client_order_data['total_cart_amount']);
        $this->Return['message'] = ($this->Post['quantity'] > 0) ? lang('product_variant_added') : lang('product_variant_removed');
    }

    /*
      Description:  To view cart details
      URL:          /admin/api/orders/get_cart_details/
    */
    public function get_cart_details_post() { 

        /* Get Cart Data */
        $cart_data = $this->session->userdata('client_order_data');
        $cart_data['client_name'] = $this->db->query('SELECT first_name client_name FROM tbl_users where user_id ='.$cart_data['client_id'].' LIMIT 1')->row()->client_name;
        $this->load->view('admin/orders/cart-details',array('details' => $cart_data));
    }

    /*
      Description:  To finish order
      URL:          /admin/api/orders/finish/
    */
    public function finish_post() { 

        /* Create Order */
        if(!$this->Orders_model->finish_order($this->session_user_id,$_POST['note'])){
            $this->Return['status'] = 500;
            $this->Return['message'] = lang('error_occured');
        }else{
            $this->Return['status'] = 200;
            $this->Return['message'] = lang('order_finished');  
        }
    }

    /*
      Description:  To view order details
      URL:          /admin/api/orders/get_order_details/
    */
    public function get_order_details_post() { 

        /* Validation section */
        $this->form_validation->set_rules('order_guid', 'Order GUID', 'trim|required|callback_validate_guid[tbl_orders.order_guid.order_id]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* Get Order Data */
        $details = $this->Orders_model->get_orders('order_id,total_products,total_cart_amount,order_by_first_name,order_by_last_name,order_by_role,order_details,created_date,order_note',array('order_id' => $this->order_id));
        $this->load->view('admin/orders/order-details',array('details' => $details));
    }

  
}