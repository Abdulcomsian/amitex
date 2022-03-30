<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as admin clients management
 * @package   CodeIgniter
 * @category  Controller
 * @author    Sorav Garg (soravgarg123@gmail.com/+919074939905)
 */

class Clients extends Admin_Controller_Secure { 

	function __construct() {
        parent::__construct();    
        $this->load->model('Users_model');
        $this->load->model('Pricelist_model');
    }

	/**
	 * Function Name: list
	 * Description:   To view clients list
	*/
	public function list()
	{
		if(!in_array($this->user_type_id,array(1,2))){
        	$this->session->set_flashdata('error',lang('access_denied'));
        	redirect('admin/dashboard');
        }

		$data['title'] = lang('clients');
		$data['module']= "clients";
		$data['css']   = array(
							'../../assets/admin/css/dataTables.bootstrap.min.css'
						);
		$data['js']    = array(
							'../../assets/admin/js/jquery.dataTables.min.js',
							'../../assets/admin/js/dataTables.bootstrap.min.js',
							'../../assets/admin/js/custom/clients.js'
						);	

		/* Get Clients */
		$data['members'] = $this->Users_model->get_users('first_name,last_name,email,account_number,phone_number,gender,user_status,created_date',array('order_by' => 'first_name', 'sequence' => 'ASC', 'user_type_id' => 3),TRUE);

		/* Get Random SubCategory For Order */
		$subcategory_guid = $this->db->query('SELECT s.subcategory_guid FROM tbl_products p, tbl_subcategories s WHERE s.subcategory_id = p.product_subcategory_id ORDER BY RAND() LIMIT 1');
		if($subcategory_guid->num_rows() > 0){
			$data['subcategory_guid'] = $subcategory_guid->row()->subcategory_guid;
		}
		$this->template->load('default', 'clients/list',$data);
	}

	/**
	 * Function Name: add_new
	 * Description:   To add new client
	*/
	public function add_new()
	{
		if($this->user_type_id != 1){
        	$this->session->set_flashdata('error',lang('access_denied'));
        	redirect('admin/dashboard');
        }

		$data['title']  = lang('add_new_client');
		$data['module'] = "clients";

		$data['css']   = array(
							'../../assets/admin/vendors/chosen_v1.4.2/chosen.min.css'
						);
		$data['js']     = array(
							'../../assets/admin/vendors/chosen_v1.4.2/chosen.jquery.min.js',
							'../../assets/admin/js/custom/clients.js'
						);

		/* Get Amitex Price List */
		$data['amitex_pricelist'] = $this->Pricelist_model->get_pricelist('pricelist_name,pricelist_brand',array('pricelist_brand' => 'Amitex', 'order_by' => 'pricelist_name', 'sequence' => 'ASC'),TRUE);

		/* Get Michal Negrin Price List */
		$data['michal_pricelist'] = $this->Pricelist_model->get_pricelist('pricelist_name,pricelist_brand',array('pricelist_brand' => 'Michal Negrin', 'order_by' => 'pricelist_name', 'sequence' => 'ASC'),TRUE);
		$this->template->load('default', 'clients/add-new',$data);
	}

	/**
	 * Function Name: edit
	 * Description:   To edit client
	*/
	public function edit($user_guid) 
	{
		if($this->user_type_id != 1){
        	$this->session->set_flashdata('error',lang('access_denied'));
        	redirect('admin/dashboard');
        }

		$data['title']  = lang('edit_client');
		$data['module'] = "clients";
		$data['css']   = array(
							'../../../assets/admin/vendors/chosen_v1.4.2/chosen.min.css'
						);
		$data['js']     = array(
							'../../../assets/admin/vendors/chosen_v1.4.2/chosen.jquery.min.js',
							'../../../assets/admin/js/custom/clients.js'
						);


		/*  To check user guid */	
		$query = $this->db->query('SELECT user_id FROM tbl_users WHERE user_guid = "'.$user_guid.'" LIMIT 1');
		if($query->num_rows() == 0){
			redirect('/admin/clients/list');
		}
		$user_id = $query->row()->user_id;

		/* To Get Client Details */
        $data['details'] = $this->Users_model->get_users('first_name,last_name,email,phone_number,gender,user_status,pricelist_guid,michal_pricelist_guid,see_cart_total_amount,percent_reduction,percent_reduction_michal,account_number',array('user_id' => $user_id));

        /* Get Amitex Price List */
		$data['amitex_pricelist'] = $this->Pricelist_model->get_pricelist('pricelist_name,pricelist_brand',array('pricelist_brand' => 'Amitex', 'order_by' => 'pricelist_name', 'sequence' => 'ASC'),TRUE);

		/* Get Michal Negrin Price List */
		$data['michal_pricelist'] = $this->Pricelist_model->get_pricelist('pricelist_name,pricelist_brand',array('pricelist_brand' => 'Michal Negrin', 'order_by' => 'pricelist_name', 'sequence' => 'ASC'),TRUE);
		$this->template->load('default', 'clients/edit',$data);
	}

	/**
	 * Function Name: delete
	 * Description:   To delete client
	*/
	public function delete($user_guid)
	{
		if($this->user_type_id != 1){
        	$this->session->set_flashdata('error',lang('access_denied'));
        	redirect('admin/dashboard');
        }
        
		if(!$this->Users_model->delete_user($user_guid)){
			$this->session->set_flashdata('error',lang('error_occured'));
		}else{
			$this->session->set_flashdata('success',lang('client_deleted'));
		}
		redirect('admin/clients/list');
	}
}

/* End of file Clients.php */
/* Location: ./application/controllers/admin/Clients.php */
