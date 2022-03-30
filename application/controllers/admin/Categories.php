<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as admin categories management
 * @package   CodeIgniter
 * @category  Controller
 * @author    Sorav Garg (soravgarg123@gmail.com/+919074939905)
 */

class Categories extends Admin_Controller_Secure { 

	function __construct() {
        parent::__construct();    
        $this->load->model('Categories_model');
        if($this->user_type_id != 1){
        	$this->session->set_flashdata('error',lang('access_denied'));
        	redirect('admin/dashboard');
        }
    }

	/**
	 * Function Name: list
	 * Description:   To view categories list
	*/
	public function list()
	{
		$data['title'] = lang('categories');
		$data['module']= "categories";
		$data['css']   = array(
							'../../assets/admin/css/dataTables.bootstrap.min.css'
						);
		$data['js']    = array(
							'../../assets/admin/js/jquery.dataTables.min.js',
							'../../assets/admin/js/dataTables.bootstrap.min.js',
							'../../assets/admin/js/custom/categories.js'
						);	

		/* Get Categories */
		$data['categories'] = $this->Categories_model->get_categories('category_name,subcategory_count,created_date',array('order_by' => 'category_name', 'sequence' => 'ASC'),TRUE);
		$this->template->load('default', 'categories/list',$data);
	}

	/**
	 * Function Name: delete
	 * Description:   To delete category
	*/
	public function delete($category_guid)
	{
		if(!$this->Categories_model->delete_category($category_guid)){
			$this->session->set_flashdata('error',lang('error_occured'));
		}else{
			$this->session->set_flashdata('success',lang('category_deleted'));
		}
		redirect('admin/categories/list');
	}

	/**
	 * Function Name: subcategories_list
	 * Description:   To view subcategories list
	*/
	public function subcategories_list()
	{
		$data['title'] = lang('subcategories');
		$data['module']= "subcategories";
		$data['css']   = array(
							'../../assets/admin/css/dataTables.bootstrap.min.css',
							'../../assets/admin/vendors/chosen_v1.4.2/chosen.min.css'
						);
		$data['js']    = array(
							'../../assets/admin/js/jquery.dataTables.min.js',
							'../../assets/admin/js/dataTables.bootstrap.min.js',
							'../../assets/admin/vendors/chosen_v1.4.2/chosen.jquery.min.js',
							'../../assets/admin/js/custom/subcategories.js'
						);	

		/* Get Categories */
		$data['categories'] = $this->Categories_model->get_categories('category_name',array('order_by' => 'category_name', 'sequence' => 'ASC'),TRUE);

		/* Get Sub Categories */
		$data['subcategories'] = $this->Categories_model->get_categories('category_name,subcategory_name,subcategory_guid,subcategory_created_date',array('order_by' => 'category_name', 'sequence' => 'ASC'),TRUE);
		$this->template->load('default', 'categories/subcategories_list',$data);
	}

	/**
	 * Function Name: delete_subcategory
	 * Description:   To delete subcategory
	*/
	public function delete_subcategory($subcategory_guid)
	{
		if(!$this->Categories_model->delete_subcategory($subcategory_guid)){
			$this->session->set_flashdata('error',lang('error_occured'));
		}else{
			$this->session->set_flashdata('success',lang('subcategory_deleted'));
		}
		redirect('admin/subcategories/list');
	}
}

/* End of file Categories.php */
/* Location: ./application/controllers/admin/Categories.php */
