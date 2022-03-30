<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as admin orders management
 * @package   CodeIgniter
 * @category  Controller
 * @author    Sorav Garg (soravgarg123@gmail.com/+919074939905)
 */

class Orders extends Admin_Controller_Secure { 

	function __construct() {
        parent::__construct();    
        $this->load->model('Orders_model');
    }

	/**
	 * Function Name: history
	 * Description:   To view order history
	*/
	public function history($user_guid)
	{
		$data['title'] = lang('order_history');
		$data['module']= "orders";
		$data['css']   = array(
							'../../../assets/admin/css/dataTables.bootstrap.min.css',
							'../../../assets/admin/css/slick.css'
						);
		$data['js']    = array(
							'../../../assets/admin/js/jquery.dataTables.min.js',
							'../../../assets/admin/js/dataTables.bootstrap.min.js',
							'../../../assets/js/slick.min.js',
							'../../../assets/admin/js/custom/order.js'
						);	

		/*  To check user guid */	
		$query = $this->db->query('SELECT user_id,first_name,last_name FROM tbl_users WHERE user_guid = "'.$user_guid.'" LIMIT 1');
		if($query->num_rows() == 0){
			redirect('/admin/dashboard');
		}
		$data['client_name'] = $query->row()->first_name." ".$query->row()->last_name;

		/* Get Orders */
		$data['orders'] = $this->Orders_model->get_orders('order_id,total_products,total_cart_amount,order_by_first_name,order_by_last_name,order_by_role,created_date',array('user_id' => $query->row()->user_id),TRUE);
		$this->template->load('default', 'orders/history',$data);
	}

}

/* End of file Orders.php */
/* Location: ./application/controllers/admin/Orders.php */
