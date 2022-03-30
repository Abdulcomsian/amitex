<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as admin price list management
 * @package   CodeIgniter
 * @category  Controller
 * @author    Sorav Garg (soravgarg123@gmail.com/+919074939905)
 */

class Pricelist extends Admin_Controller_Secure { 

	function __construct() {
        parent::__construct();    
        $this->load->model('Pricelist_model');
        $this->load->model('Products_model');
        if($this->user_type_id != 1){
        	$this->session->set_flashdata('error',lang('access_denied'));
        	redirect('admin/dashboard');
        }
    }

	/**
	 * Function Name: list
	 * Description:   To view pricelist
	*/
	public function list()
	{
		$data['title'] = lang('price_list');
		$data['module']= "pricelist";
		$data['css']   = array(
							'../../assets/admin/css/dataTables.bootstrap.min.css'
						);
		$data['js']    = array(
							'../../assets/admin/js/jquery.dataTables.min.js',
							'../../assets/admin/js/dataTables.bootstrap.min.js',
							'../../assets/admin/js/custom/pricelist.js'
						);	

		/* Get Price List */
		$data['pricelist'] = $this->Pricelist_model->get_pricelist('pricelist_name,pricelist_brand,is_main_pricelist,total_clients,created_date',array('order_by' => 'pricelist_name', 'sequence' => 'ASC'),TRUE);

		/* Check If Main Price list added */
		$data['is_main_pricelist_added'] = (!empty($data['pricelist']['data']['records'])) ? (in_array('Yes',array_column($data['pricelist']['data']['records'], 'is_main_pricelist')))  : FALSE;
		$this->template->load('default', 'pricelist/list',$data);
	}

	/**
	 * Function Name: delete
	 * Description:   To delete pricelist
	*/
	public function delete($pricelist_guid)
	{
		if(!$this->Pricelist_model->delete_pricelist($pricelist_guid)){
			$this->session->set_flashdata('error',lang('error_occured'));
		}else{

			/* Update Clients Data */
			$this->db->where('pricelist_id', $pricelist_id);
        	$this->db->update('tbl_users', array('pricelist_id' => NULL));

			$this->session->set_flashdata('success',lang('price_list_deleted'));
		}
		redirect('admin/pricelist/list');
	}

	/**
	 * Function Name: details
	 * Description:   To view pricelist details
	*/
	public function details($pricelist_guid) 
	{
		$data['title']  = lang('view_price_list_details');
		$data['module'] = "pricelist";

		$data['css']   = array(
							'../../../assets/admin/css/dataTables.bootstrap.min.css'
						);
		$data['js']    = array(
							'../../../assets/admin/js/jquery.dataTables.min.js',
							'../../../assets/admin/js/dataTables.bootstrap.min.js',
							'../../../assets/admin/js/custom/pricelist.js'
						);	

		/*  To check pricelist guid */	
		$query = $this->db->query('SELECT pricelist_id FROM tbl_pricelists WHERE pricelist_guid = "'.$pricelist_guid.'" LIMIT 1');
		if($query->num_rows() == 0){
			redirect('/admin/pricelist/list');
		}
		$pricelist_id = $query->row()->pricelist_id;

		/* To Get Pricelist Details */
        $data['details'] = $this->Pricelist_model->get_pricelist('pricelist_name,pricelist_brand,is_main_pricelist,created_date',array('pricelist_id' => $pricelist_id));

        /* Get Products */
        $data['products'] = $this->Products_model->get_product_variants_price('product_name,color_variant,size_variant,product_price',array('pricelist_id' => $pricelist_id, 'order_by' => 'product_name', 'sequence' => 'ASC'),TRUE);
		$this->template->load('default', 'pricelist/view-details',$data);
	}

	/**
	 * Function Name: download_product_csv
	 * Description:   To download product csv
	*/
    public function download_product_csv() { 
    	if(!empty($this->input->get('pricelist_brand'))){
    		$pricelist_brand = $this->input->get('pricelist_brand');

    		/* Get products */
    		if(empty($_GET['pricelist_guid'])){
    			$products = $this->db->query('SELECT P.product_name, PV.product_id, PV.product_variant_id, PV.color_variant,PV.size_variant FROM tbl_products P, tbl_products_variants PV WHERE P.product_id = PV.product_id AND P.product_brand = "'.$pricelist_brand.'"');
    		}else{
    			$query = $this->db->query('SELECT pricelist_id FROM tbl_pricelists WHERE pricelist_guid = "'.$_GET['pricelist_guid'].'" LIMIT 1');
				if($query->num_rows() == 0){
					redirect('/admin/pricelist/list');
				}
    			$products = $this->db->query('SELECT P.product_name, PV.product_id,PV.product_variant_id,PV.color_variant,PV.size_variant, (SELECT product_price FROM tbl_pricelist_variants PLV WHERE PV.product_variant_id = PLV.product_variant_id AND P.product_id = PLV.product_id AND PLV.pricelist_id = '.$query->row()->pricelist_id.') product_price FROM tbl_products_variants PV LEFT JOIN tbl_products P ON PV.product_id = P.product_id WHERE P.product_brand = "'.$pricelist_brand.'"');
    		}
    		if($products->num_rows() > 0){
    			$products_arr = array();
    			foreach($products->result_array() as $product){
					$products_arr[] = array('product_id' => $product['product_id']."-".$product['product_variant_id'],'product_name' => $product['product_name']."-".$product['color_variant']."-".$product['size_variant'], 'product_price' => (!empty($product['product_price'])) ? $product['product_price'] : '');
				}
    			$filename = "products--".date('d-F-Y-h-i-A').".csv";
		        $fp = fopen('php://output', 'w');
		        header('Content-type: application/csv');
		        header('Content-Disposition: attachment; filename=' . $filename);
		        fputcsv($fp, array(lang('product_id'),lang('product_name'),lang('product_price')));
		        foreach ($products_arr as $row) {
		            fputcsv($fp, $row);
		        }
    		}else{
    			$this->session->set_flashdata('error',lang('products_not_found'));
    			redirect('admin/pricelist/list');
    		}
    	}
    }
}

/* End of file Pricelist.php */
/* Location: ./application/controllers/admin/Pricelist.php */
