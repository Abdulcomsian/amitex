<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as admin media library management
 * @package   CodeIgniter
 * @category  Controller
 * @author    Sorav Garg (soravgarg123@gmail.com/+919074939905)
 */

class Media extends Admin_Controller_Secure { 

	function __construct() {
        parent::__construct();    
    }

	/**
	 * Function Name: library
	 * Description:   To manage media library
	*/
	public function library()
	{
		$data['title'] = lang('media_library');
		$data['module']= "media_library";
		$data['css']   = array(
							'../../assets/admin/css/dropzone.css'
						);
		$data['js']    = array(
							'../../assets/admin/js/dropzone.js',
							'../../assets/admin/js/custom/media.js'
						);	

		$data['previous_media_images'] = array();

		/* Read files (From products directory) */
		$files = preg_grep('/^([^.])/', scandir(FCPATH.'uploads/products/'));
		unset($files[array_search('default-product.jpg',$files)]);
		$files = array_values($files);

		/* Manage Media Images For Dropzone */
        if(!empty($files)){
        	foreach($files as $image){
        		$data['previous_media_images'][] = array(
        										'name' => $image,
        										'path' => '../../uploads/products/'.$image,
        										'size' => filesize(FCPATH.'uploads/products/'.$image)
        									);
        	}
        }
		$this->template->load('default', 'media/library',$data);
	}

}

/* End of file Media.php */
/* Location: ./application/controllers/admin/Media.php */
