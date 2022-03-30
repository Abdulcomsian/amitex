<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Orders_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Description:  Use to create/finish order
     */
    function finish_order($session_user_id,$note=NULL) {

        $this->db->trans_start();

        /* Get Cart Data */
        $cart_data = $this->session->userdata('client_order_data');

        $insert_array = array_filter(array(
            "order_guid" => get_guid(),
            "user_id" => $cart_data['client_id'],
            'ordered_by_user_id' => $session_user_id,
            "total_products" => $cart_data['total_products'],
            "total_cart_amount" => $cart_data['total_cart_amount'],
            "order_note" => $note,
            "created_date" => date('Y-m-d H:i:s')
        ));
        $this->db->insert('tbl_orders', $insert_array);
        $order_id = $this->db->insert_id();

        /* Add Order Details */
        $order_details = array();
        foreach($cart_data['product_data'] as $product){
            $order_details[] = array('order_id' => $order_id, 'product_id' => $product['product_id'], 'product_name' => $product['product_name'], 'product_total_amount' => $product['product_total_price'], 'product_variants' => json_encode(array_values($product['product_variants']),JSON_UNESCAPED_UNICODE));
        }
        if(!empty($order_details)){
            $this->db->insert_batch('tbl_order_details', $order_details);
        }
       
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) { 
            return FALSE;
        }
        
        /* Get Client Details */
        $client_data = $this->db->query('SELECT first_name,last_name,email,pricelist_id,michal_pricelist_id,percent_reduction,percent_reduction_michal FROM tbl_users WHERE user_id = '.$cart_data['client_id'].' LIMIT 1')->row_array();

        /* Get Product Details */
        $products_data = $this->Products_model->get_products('product_name,category_name,subcategory_name,product_brand,is_premium,color_variants,size_variants,product_varinats_prices,product_id',array('product_ids'=> array_values(array_unique(array_column($order_details,'product_id'))), 'pricelist_ids' => array($client_data['pricelist_id'],$client_data['michal_pricelist_id']) , 'order_by' => 'product_name', 'sequence' => 'ASC'),TRUE);

        /* Create PDF Using MPDF */
        $pdf_data = array('order_id' => 'OID-'.$order_id, 'client_name' => $client_data['first_name']." ".$client_data['last_name']);
        $pdf_data['order_date'] = date('Y-m-d');
        $pdf_data['percent_reduction_amitex'] = $client_data['percent_reduction'];
        $pdf_data['percent_reduction_michal'] = $client_data['percent_reduction_michal'];
        $pdf_data['total_cart_amount'] = $cart_data['total_cart_amount'];
        $pdf_data['order_details'] = $order_details;
        $pdf_data['products_data'] = $products_data['data']['records'];

        /* Load MPDF Library */
        require_once FCPATH . 'vendor/autoload.php';
        $pdf_file_path = FCPATH.'uploads/'.lang('order').'-'.$pdf_data['order_id'].'.pdf';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($this->load->view('admin/orders/invoice',$pdf_data,TRUE));
        $mpdf->Output($pdf_file_path,'F');

        $order_heading = lang('new_order').' ('.$pdf_data['order_id'].') '.lang('generated').' !!';
        $subject = 'New order #'.addZero($pdf_data['order_id']).' from client '.$pdf_data['client_name'];

        /* Send Email To Admin */
        php_mailer(get_admin_emails(),SITE_NAME,$subject,emailTemplate($this->load->view('emailer/new-order', array('full_name' => 'Admin', 'order_heading' => $order_heading), true)),$pdf_file_path);

        /* Send Email To Client */
        php_mailer(array($client_data['email']),$pdf_data['client_name'],'['.SITE_NAME.'] '.$order_heading,emailTemplate($this->load->view('emailer/new-order', array('full_name' => $pdf_data['client_name'], 'order_heading' => $order_heading), true)),$pdf_file_path);

        /* Delete/Unlink PDF File Attachement */
        unlink($pdf_file_path);

        /* Destroy Cart Session */
        $this->session->unset_userdata('client_order_data');
        return TRUE;
    }

    /*
      Description:  Use to get orders
     */

    function get_orders($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 150) {
        /* Additional fields to select */
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'created_date'  => 'DATE_FORMAT(O.created_date, "' . DATE_FORMAT . '") created_date',
                'order_id'      => 'O.order_id',
                'user_id'       => 'O.user_id',
                'order_note'       => 'O.order_note',
                'ordered_by_user_id'   => 'O.ordered_by_user_id',
                'total_products'       => 'O.total_products',
                'total_cart_amount'    => 'O.total_cart_amount',
                'order_by_first_name'  => 'U.first_name order_by_first_name',
                'order_by_last_name'   => 'U.last_name order_by_last_name',
                'order_by_role'        => 'CASE U.user_type_id
                                                when "1" then "Admin"
                                                when "2" then "Agent"
                                                when "3" then "You"
                                            END as order_by_role',
            );
            
            foreach ($Params as $Param) {
                $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
            }
        }
        $this->db->select('O.order_guid');
        if (!empty($Field)) $this->db->select($Field, FALSE);
        $this->db->from('tbl_orders O');
        if (array_keys_exist($Params, array('order_by_first_name', 'order_by_last_name','order_by_role'))) {
            $this->db->from('tbl_users U');
            $this->db->where("U.user_id", "O.ordered_by_user_id", FALSE);
        }
        if (!empty($Where['order_guid'])) {
            $this->db->where("O.order_guid", $Where['order_guid']);
        }
        if (!empty($Where['order_id'])) {
            $this->db->where("O.order_id", $Where['order_id']);
        }
        if (!empty($Where['user_id'])) {
            $this->db->where("O.user_id", $Where['user_id']);
        }
        if (!empty($Where['ordered_by_user_id'])) {
            $this->db->where("O.ordered_by_user_id", $Where['ordered_by_user_id']);
        }
        if (!empty($Where['order_by']) && !empty($Where['sequence']) && in_array($Where['sequence'], array('ASC', 'DESC'))) {
            $this->db->order_by($Where['order_by'], $Where['sequence']);
        } else {
            $this->db->order_by('O.order_id', 'DESC');
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
                    if (in_array('order_details', $Params)) {
                        $order_details = $this->db->query('SELECT product_name,product_total_amount,product_variants FROM tbl_order_details WHERE order_id = '.$Record['order_id'].' ORDER BY order_detail_id DESC');
                        $Records[$key]['order_details'] = array();
                        if($order_details->num_rows() > 0){
                            foreach($order_details->result_array() as $subkey => $value){
                                $Records[$key]['order_details'][$subkey] = $value;
                                $Records[$key]['order_details'][$subkey]['product_variants'] = json_decode($value['product_variants'],TRUE);
                            }
                        }
                    }
                }
                $Return['data']['records'] = $Records;
                return $Return;
            } else {
                $Record = $Query->row_array();
                if (in_array('order_details', $Params)) {
                    $order_details = $this->db->query('SELECT product_name,product_total_amount,product_variants FROM tbl_order_details WHERE order_id = '.$Record['order_id'].' ORDER BY order_detail_id DESC');
                    $Record['order_details'] = array();
                    if($order_details->num_rows() > 0){
                        foreach($order_details->result_array() as $key => $value){
                            $Record['order_details'][$key] = $value;
                            $Record['order_details'][$key]['product_variants'] = json_decode($value['product_variants'],TRUE);
                        }
                    }
                }
                return $Record;
            }
        }
        return FALSE;
    }

}