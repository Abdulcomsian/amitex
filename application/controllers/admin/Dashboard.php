<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

/**
 * This Class used as admin management
 * @package   CodeIgniter
 * @category  Controller
 * @author    Sorav Garg (soravgarg123@gmail.com/+919074939905)
 */

class Dashboard extends Admin_Controller_Secure {

	function __construct() {
        parent::__construct();
    }

	/**
	 * Function Name: dashboard
	 * Description:   To admin dashboard
	 */
	public function index() 
	{
		$data['title']  = lang('dashboard');
		$data['module'] = "dashboard";
		$data['js']     =  array(
								'../assets/admin/js/custom/dashoard.js'
							);

		if($this->user_type_id == 1){
			$data['statics'] = $this->db->query('SELECT
                                            total_agents,
                                            total_clients,
                                            total_categories,
                                            total_subcategories,
                                            total_products,
                                            total_pricelist,
                                            total_orders
                                        FROM
                                            (SELECT
	                                            (
	                                                SELECT
	                                                    COUNT(user_id)
	                                                FROM
	                                                    tbl_users
	                                                WHERE user_type_id = 2
	                                            ) AS total_agents,
	                                            (
	                                                SELECT
	                                                    COUNT(user_id)
	                                                FROM
	                                                    tbl_users
	                                                WHERE user_type_id = 3
	                                            ) AS total_clients,
	                                            (
	                                                SELECT
	                                                    COUNT(*)
	                                                FROM
	                                                    tbl_categories
	                                            ) AS total_categories,
	                                            (
	                                                SELECT
	                                                    COUNT(*)
	                                                FROM
	                                                    tbl_subcategories
	                                            ) AS total_subcategories,
	                                            (
	                                                SELECT
	                                                    COUNT(*)
	                                                FROM
	                                                    tbl_products
	                                            ) AS total_products,
	                                            (
	                                                SELECT
	                                                    COUNT(*)
	                                                FROM
	                                                    tbl_pricelists
	                                            ) AS total_pricelist,
	                                            (
	                                                SELECT
	                                                    COUNT(*)
	                                                FROM
	                                                    tbl_orders
	                                            ) AS total_orders
                                        ) total'
                			)->row_array();
		}else if($this->user_type_id == 2){
			$data['statics'] = $this->db->query('SELECT
                                            total_clients,
                                            total_orders
                                        FROM
                                            (SELECT
	                                            (
	                                                SELECT
	                                                    COUNT(user_id)
	                                                FROM
	                                                    tbl_users
	                                                WHERE user_type_id = 3
	                                            ) AS total_clients,
	                                            (
	                                                SELECT
	                                                    COUNT(*)
	                                                FROM
	                                                    tbl_orders
	                                            ) AS total_orders
                                        ) total'
                			)->row_array();
		}else if($this->user_type_id == 3){
			$data['statics'] = $this->db->query('SELECT
                                            total_orders
                                        FROM
                                            (SELECT
	                                            (
	                                                SELECT
	                                                    COUNT(*)
	                                                FROM
	                                                    tbl_orders
	                                                WHERE user_id = '.$this->session_user_id.'
	                                            ) AS total_orders
                                        ) total'
                                )->row_array();
		}
		$this->template->load('default', 'dashboard/dashboard',$data);
	}

	/**
     * Function Name: logout
     * Description:   To admin logout
     */
	public function logout($login_session_key = NULL)
	{
		/* Delete Session Key */
		if(!empty($login_session_key)){
			$this->Users_model->delete_session($login_session_key);
		}

		/* Destroy Session */
		$this->session->unset_userdata('userdata');
    	$this->session->set_flashdata('logout','Yes');
    	redirect(base_url().'admin/login'); exit;
	}

	/**
	 * Function Name: changepassword
	 * Description:   To change admin password view
	 */
	public function changepassword()
	{

		$data['title'] = lang('change_password');
		$data['js']    = array(
							'../assets/admin/js/custom/dashoard.js'
						);	
		$this->template->load('default', 'dashboard/change-password',$data);
	}

	/**
	 * Function Name: edit_profile
	 * Description:   To edit profile view
	 */
	public function edit_profile()
	{
		$data['title'] = lang('edit_profile');
		$data['css']   = array(
							'../assets/admin/vendors/chosen_v1.4.2/chosen.min.css'
						);
		$data['js']    = array(
			                '../assets/admin/vendors/chosen_v1.4.2/chosen.jquery.min.js',
							'../assets/admin/js/custom/dashoard.js'
						);	

		/* To Get My Profile Details */
        $data['details'] = $this->Users_model->get_users('user_id,first_name,last_name,email,phone_number,address1,gender,state_id,city_id,email_addresses',array('user_id' => $this->session_user_id));
        $data['details']['email_addresses'] = (!empty($data['details']['email_addresses'])) ? json_decode($data['details']['email_addresses'], TRUE) : array();
		$this->template->load('default', 'dashboard/edit-profile',$data);
	}

	/**
	 * Function Name: languages
	 * Description:   To manage languages
	 */
	public function languages()
	{
		if($this->user_type_id != 1){
        	$this->session->set_flashdata('error',lang('access_denied'));
        	redirect('admin/dashboard');
        }

		$data['title'] = lang('manage_language_file');
		$data['module'] = "languages";
		$data['languages'] = languages();

		$data['css']   = array(
							'../assets/admin/css/dataTables.bootstrap.min.css'
						);
		$data['js']    = array(
							'../assets/admin/js/jquery.dataTables.min.js',
							'../assets/admin/js/dataTables.bootstrap.min.js',
							'../assets/admin/js/custom/dashoard.js'
						);	
		if($this->input->post()){

			/* Update Language Text */
			foreach ($this->input->post() as $key => $field) {
				if($key == 'example_length'){
					continue;
				}
	           $this->lang->change_line($key, $field,LANGUAGE_FILE_NAME,strtolower($data['languages'][$this->session->userdata('language')]));
	        }
	        $this->session->set_flashdata('success', lang('language_file_updated'));
			redirect('/admin/languages?lang='.$this->session->userdata('language'));
		}else{

			/* Load Language fields */
			$this->session->set_userdata('language',(!empty($this->input->get('lang')) ? $this->input->get('lang') : DEFAULT_LANGUAGE_CODE));
			$data['fields'] = $this->lang->language;
			if(count($data['fields']) % 2 != 0){
				$data['fields']['even_fld'] = 'test';
			}
			$this->template->load('default', 'dashboard/languages',$data);
		}
	}
}

/* End of file Login.php */
/* Location: ./application/controllers/admin/Dashboard.php */
