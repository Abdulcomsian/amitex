<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as admin products management
 * @package   CodeIgniter
 * @category  Controller
 * @author    Sorav Garg (soravgarg123@gmail.com/+919074939905)
 */

class Products extends Admin_Controller_Secure { 

	function __construct() {
        parent::__construct();    
        $this->load->model('Categories_model');
        $this->load->model('Products_model');
    }

	/**
	 * Function Name: list
	 * Description:   To view products list
	*/
	public function list()
	{
		if($this->user_type_id != 1){
        	$this->session->set_flashdata('error',lang('access_denied'));
        	redirect('admin/dashboard');
        }
		$data['title'] = lang('products');
		$data['module']= "products";
		$data['css']   = array(
							'../../assets/admin/css/dataTables.bootstrap.min.css'
						);
		$data['js']    = array(
							'../../assets/admin/js/jquery.dataTables.min.js',
							'../../assets/admin/js/dataTables.bootstrap.min.js',
							'../../assets/admin/js/custom/products.js'
						);

		/* Get Products */
		$data['products'] = $this->Products_model->get_products('product_name,category_name,subcategory_name,product_brand,product_main_photo,is_premium,created_date',array('order_by' => 'product_item_code', 'sequence' => 'ASC'),TRUE);
		$this->template->load('default', 'products/list',$data);
	}

	/**
	 * Function Name: add_new
	 * Description:   To add new product
	*/
	public function add_new()
	{
		if($this->user_type_id != 1){
        	$this->session->set_flashdata('error',lang('access_denied'));
        	redirect('admin/dashboard');
        }
		$data['title']  = lang('add_new_product');
		$data['module'] = "products";

		$data['css']   = array(
							'../../assets/admin/vendors/chosen_v1.4.2/chosen.min.css',
							'../../assets/admin/css/dropzone.css'
						);
		$data['js']     = array(
							'../../assets/admin/vendors/chosen_v1.4.2/chosen.jquery.min.js',
							'../../assets/admin/vendors/fileinput/fileinput.min.js',
							'../../assets/admin/js/dropzone.js',
							'../../assets/admin/js/custom/products.js'
						);

		/* Get Categories */
		$data['categories'] = $this->Categories_model->get_categories('category_name',array('order_by' => 'category_name', 'sequence' => 'ASC'),TRUE);
		$this->template->load('default', 'products/add-new',$data);
	}

	/**
	 * Function Name: edit
	 * Description:   To edit product
	*/
	public function edit($product_guid) 
	{
		if($this->user_type_id != 1){
        	$this->session->set_flashdata('error',lang('access_denied'));
        	redirect('admin/dashboard');
        }
		$data['title']  = lang('edit_product');
		$data['module'] = "products";
		$data['css']   = array(
							'../../../assets/admin/vendors/chosen_v1.4.2/chosen.min.css',
							'../../../assets/admin/css/dropzone.css'
						);
		$data['js']     = array(
							'../../../assets/admin/vendors/chosen_v1.4.2/chosen.jquery.min.js',
							'../../../assets/admin/vendors/fileinput/fileinput.min.js',
							'../../../assets/admin/js/dropzone.js',
							'../../../assets/admin/js/custom/products.js'
						);


		/*  To check product guid */	
		$query = $this->db->query('SELECT product_id FROM tbl_products WHERE product_guid = "'.$product_guid.'" LIMIT 1');
		if($query->num_rows() == 0){
			redirect('/admin/products/list');
		}
		$product_id = $query->row()->product_id;

		/* Get Categories */
		$data['categories'] = $this->Categories_model->get_categories('category_name',array('order_by' => 'category_name', 'sequence' => 'ASC'),TRUE);

		/* To Get Product Details */
        $data['details'] = $this->Products_model->get_products('product_name,product_category_id,product_descprition,category_guid,subcategory_guid,product_brand,product_main_photo,product_gallery_images,color_variants,size_variants,is_premium,product_item_code',array('product_id' => $product_id));

        /* To Get Sub Categories Data */
        $data['subcategories'] = $this->Categories_model->get_categories('subcategory_name,subcategory_guid',array('parent_category_id' => $data['details']['product_category_id'], 'order_by' => 'SC.subcategory_name', 'sequence' => 'ASC'),TRUE);

        /* Manage Gallery Images For Dropzone */
        $product_gallery_images = array();
        if(!empty($data['details']['product_gallery_images'])){
        	foreach($data['details']['product_gallery_images'] as $image){
        		$product_gallery_images[] = array(
        										'name' => $image,
        										'path' => '../../../uploads/products/'.$image,
        										'size' => filesize(FCPATH.'uploads/products/'.$image)
        									);
        	}
        }
        $data['details']['product_gallery_images'] = $product_gallery_images;
		$this->template->load('default', 'products/edit',$data);
	}

	/**
	 * Function Name: details
	 * Description:   To view product details
	*/
	public function details($product_guid) 
	{
		if($this->user_type_id != 1){
        	$this->session->set_flashdata('error',lang('access_denied'));
        	redirect('admin/dashboard');
        }
		$data['title']  = lang('view_product_details');
		$data['module'] = "products";
		$data['css']   = array(
							'../../../assets/admin/vendors/bower_components/lightgallery/src/css/lightgallery.css'
						);
		$data['js']     = array(
							'../../../assets/admin/vendors/bower_components/lightgallery/src/js/lightgallery.js',
							'../../../assets/admin/js/custom/products.js'
						);

		/*  To check product guid */	
		$query = $this->db->query('SELECT product_id FROM tbl_products WHERE product_guid = "'.$product_guid.'" LIMIT 1');
		if($query->num_rows() == 0){
			redirect('/admin/products/list');
		}
		$product_id = $query->row()->product_id;

		/* To Get Product Details */
        $data['details'] = $this->Products_model->get_products('product_name,product_descprition,category_name,subcategory_name,product_main_photo,product_gallery_images,color_variants,size_variants,is_premium,product_item_code,created_date,pricelist_variants_count',array('product_id' => $product_id));
        $data['product_variants'] = $this->Products_model->get_product_variants_price('product_variant_id,color_variant,size_variant,in_stock', array('product_id' => $product_id , 'order_by' => 'product_name', 'sequence' => 'ASC'),TRUE);
		$this->template->load('default', 'products/view-details',$data);
	}

	/**
	 * Function Name: delete
	 * Description:   To delete product
	*/
	public function delete($product_guid)
	{
		if($this->user_type_id != 1){
        	$this->session->set_flashdata('error',lang('access_denied'));
        	redirect('admin/dashboard');
        }
        
		/*  To check product guid */	
		$query = $this->db->query('SELECT product_id FROM tbl_products WHERE product_guid = "'.$product_guid.'" LIMIT 1');
		if($query->num_rows() == 0){
			redirect('/admin/products/list');
		}
		$product_id = $query->row()->product_id;

		/* To Get Product Details */
        $details = $this->Products_model->get_products('product_main_photo_file,product_gallery_images',array('product_id' => $product_id));

		/* delete images also */
		if(!$this->Products_model->delete_product($product_guid)){
			$this->session->set_flashdata('error',lang('error_occured'));
		}else{

			/* Remove product main photo */
        	unlink(FCPATH.'uploads/products/'.$details['product_main_photo_file']);

        	/* Remove Gallery Images */
        	if(!empty($details['product_gallery_images'])){
        		foreach($details['product_gallery_images'] as $gallery_image){
        			unlink(FCPATH.'uploads/products/'.$gallery_image);
        		}
        	}
			$this->session->set_flashdata('success',lang('product_deleted'));
		}
		redirect('admin/products/list');
	}

	/**
	 * Function Name: order
	 * Description:   To view order page
	*/
	public function order($user_guid) 
	{
		if(empty($this->input->get('subcategory_guid'))){
			redirect('/admin/dashboard');
		}

		$data['css']    = array(
							'../../../assets/admin/css/slick.css',
							'../../../assets/admin/vendors/bower_components/lightgallery/src/css/lightgallery.css'
						);
		$data['js']     = array(
							'../../../assets/js/slick.min.js',
							'../../../assets/admin/vendors/bower_components/lightgallery/src/js/lightgallery.js',
							'../../../assets/admin/js/custom/order.js'
						);

		/*  To check user guid */	
		$query = $this->db->query('SELECT user_id,first_name,last_name,pricelist_id,michal_pricelist_id,percent_reduction,percent_reduction_michal FROM tbl_users WHERE user_guid = "'.$user_guid.'" LIMIT 1');
		if($query->num_rows() == 0){
			redirect('/admin/dashboard');
		}
		$data['client_name'] = $query->row()->first_name." ".$query->row()->last_name;
		$data['percent_reduction_amitex'] = $query->row()->percent_reduction;
		$data['percent_reduction_michal'] = $query->row()->percent_reduction_michal;
		$data['title']  = lang('order')." (".$data['client_name'].")";
		$data['module'] = "clients";

		/*  To check subcategory guid */	
		$subcat_query = $this->db->query('SELECT subcategory_id FROM tbl_subcategories WHERE subcategory_guid = "'.$this->input->get('subcategory_guid').'" LIMIT 1');
		if($subcat_query->num_rows() == 0){
			redirect('/admin/dashboard');
		}

		$client_order_data = $this->session->userdata('client_order_data');
		if(!empty($client_order_data) && $client_order_data['client_id'] != $query->row()->user_id){
			$this->session->unset_userdata('client_order_data'); // Reset order session data
		}
		$data['order_data'] = $this->session->userdata('client_order_data');
		$this->session->set_userdata('order_user_id',$query->row()->user_id);
		$this->session->set_userdata('order_user_pricelist_id',$query->row()->pricelist_id);
		$this->session->set_userdata('order_user_michal_pricelist_id',$query->row()->michal_pricelist_id);

		/* Get Categories & Subcategories */
		$data['categories'] = $this->Categories_model->get_categories('category_name,subcategories',array('order_by' => 'category_name', 'sequence' => 'ASC'),TRUE);

		/* Get Products */
		$data['products'] = $this->Products_model->get_products('product_id,product_name,product_descprition,product_brand,is_premium,color_variants,size_variants,in_stock,product_main_photo,product_gallery_images,category_name,subcategory_name,pricelist_variants_count,product_varinats_prices',array('product_subcategory_id' => $subcat_query->row()->subcategory_id, 'pricelist_ids' => array($query->row()->pricelist_id,$query->row()->michal_pricelist_id), 'order_by' => 'product_item_code', 'sequence' => 'ASC'),TRUE);
		$this->template->load('default', 'products/order',$data);
	}

	/**
	 * Function Name: clear_cart
	 * Description:   To clear user cart
	*/
	public function clear_cart() {
		$this->session->unset_userdata('client_order_data'); // Reset order session data
		$this->session->set_flashdata('success',lang('shopping_cart_cleared'));
		redirect($_SERVER['HTTP_REFERER']);
	}

	/**
	 * Function Name: make_order
	 * Description:   To make order link
	*/
	public function make_order() {

		/* Get Random SubCategory For Order */
        $query = $this->db->query('SELECT s.subcategory_guid FROM tbl_products p, tbl_subcategories s WHERE s.subcategory_id = p.product_subcategory_id ORDER BY RAND() LIMIT 1');
        if($query->num_rows() > 0){
            redirect(BASE_URL.'admin/products/order/'.$this->session_user_guid.'?subcategory_guid='.$query->row()->subcategory_guid);
        }else{
        	$this->session->set_flashdata('error',lang('products_not_found'));
			redirect($_SERVER['HTTP_REFERER']);
        }
	}

	/**
	 * Function Name: download_product_csv
	 * Description:   Download Product data in csv
	*/
	public function download_product_csv() {

		/* Get Products */
		$products = $this->Products_model->export_products();
		exportCSV('amitex-products.csv',$products,array('Item_Item','item name','size','color','category','sub category','Picture name','Price 1','Price 2','Price 3','Price 4','Price 5','Consumer Price','Barcode','Discount Code','description','premium','in stock'));
		
	}

}

/* End of file Products.php */
/* Location: ./application/controllers/admin/Products.php */
