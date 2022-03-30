<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as admin agents management
 * @package   CodeIgniter
 * @category  Controller
 * @author    Sorav Garg (soravgarg123@gmail.com/+919074939905)
 */

class Agents extends Admin_Controller_Secure { 

	function __construct() {
        parent::__construct();    
        $this->load->model('Users_model');
        if($this->user_type_id != 1){
        	$this->session->set_flashdata('error',lang('access_denied'));
        	redirect('admin/dashboard');
        }
    }

	/**
	 * Function Name: list
	 * Description:   To view agents list
	*/
	public function list()
	{
		$data['title'] = lang('sales_agent');
		$data['module']= "agents";
		$data['css']   = array(
							'../../assets/admin/css/dataTables.bootstrap.min.css'
						);
		$data['js']    = array(
							'../../assets/admin/js/jquery.dataTables.min.js',
							'../../assets/admin/js/dataTables.bootstrap.min.js',
							'../../assets/admin/js/custom/agents.js'
						);	

		/* Get Agents */
		$data['members'] = $this->Users_model->get_users('first_name,last_name,email,phone_number,gender,user_status,created_date',array('order_by' => 'first_name', 'sequence' => 'ASC', 'user_type_id' => 2),TRUE);
		$this->template->load('default', 'agents/list',$data);
	}

	/**
	 * Function Name: add_new
	 * Description:   To add new agent
	*/
	public function add_new()
	{
		$data['title']  = lang('add_new_sales_agent');
		$data['module'] = "agents";

		$data['css']   = array(
							'../../assets/admin/vendors/chosen_v1.4.2/chosen.min.css'
						);
		$data['js']     = array(
							'../../assets/admin/vendors/chosen_v1.4.2/chosen.jquery.min.js',
							'../../assets/admin/js/custom/agents.js'
						);
		$this->template->load('default', 'agents/add-new',$data);
	}

	/**
	 * Function Name: edit
	 * Description:   To edit agent
	*/
	public function edit($user_guid) 
	{
		$data['title']  = lang('edit_sales_agent');
		$data['module'] = "agents";
		$data['css']   = array(
							'../../../assets/admin/vendors/chosen_v1.4.2/chosen.min.css'
						);
		$data['js']     = array(
							'../../../assets/admin/vendors/chosen_v1.4.2/chosen.jquery.min.js',
							'../../../assets/admin/js/custom/agents.js'
						);


		/*  To check user guid */	
		$query = $this->db->query('SELECT user_id FROM tbl_users WHERE user_guid = "'.$user_guid.'" LIMIT 1');
		if($query->num_rows() == 0){
			redirect('/admin/agents/list');
		}
		$user_id = $query->row()->user_id;

		/* To Get Agent Details */
        $data['details'] = $this->Users_model->get_users('first_name,last_name,email,phone_number,gender,user_status',array('user_id' => $user_id));

		$this->template->load('default', 'agents/edit',$data);
	}

	/**
	 * Function Name: delete
	 * Description:   To delete agent
	*/
	public function delete($user_guid)
	{
		if(!$this->Users_model->delete_user($user_guid)){
			$this->session->set_flashdata('error',lang('error_occured'));
		}else{
			$this->session->set_flashdata('success',lang('sales_agent_deleted'));
		}
		redirect('admin/agents/list');
	}
}

/* End of file Agents.php */
/* Location: ./application/controllers/admin/Agents.php */
