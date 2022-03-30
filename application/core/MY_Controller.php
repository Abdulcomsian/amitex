<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MAIN_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();		
	}
}

class MY_Controller_public extends MAIN_Controller
{
	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('SessionUserID')) {redirect(base_url().'dashboard');exit();}		
	}
}

class MY_Controller_Secure extends MAIN_Controller 
{
	public function __construct()
	{
		parent::__construct();
		/*ensure already signed in*/
		if (empty($this->session->userdata('SessionUserID'))){
			redirect(base_url()); exit;
		}
		else{
			$this->SessionUserID = $this->session->userdata('SessionUserID');//get User ID from session
		}
	}
}

/* Admin Control */

class Admin_Main_Controller extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$Input = file_get_contents("php://input");
			$this->Post =  json_decode($Input,1);
			if(empty($this->Post)){
				parse_str($Input,$this->Post);
			}
		}	
	}
}


class Admin_Controller extends Admin_Main_Controller {
	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('userdata')) {redirect(base_url().'admin/dashboard');exit();}	

		/* Load Language */
		load_lang();
	}
}

class Admin_Controller_Secure extends Admin_Main_Controller {
	public function __construct()
	{
		parent::__construct();

		/* Ensure already Signed in */
		if (empty($this->session->userdata('userdata'))) {
			redirect(base_url().'admin/login'); exit;
		}

		$this->session_data	     =	$this->session->userdata('userdata'); 
		$this->login_session_key =	$this->session_data['login_session_key'];	
		$this->session_user_id	 =	$this->session_data['user_id'];
		$this->session_user_guid =	$this->session_data['user_guid'];
		$this->user_type_guid	 =	$this->session_data['user_type_guid'];
		$this->user_type_id	     =	$this->session_data['user_type_id'];

		/* Load Language */
		load_lang();

		/* To Cross Check User Login Session */
		$IsActive = "Yes";
		$query = $this->db->query('SELECT user_status,last_activity FROM tbl_users WHERE login_session_key  = "'.$this->login_session_key.'" LIMIT 1');
		if($query->num_rows() == 0){
			$this->session->set_flashdata('error',lang('session_disconnected'));
			$IsActive = "No";
		}elseif ($query->num_rows() > 0 && $query->row()->user_status == 'Pending') {
            $this->session->set_flashdata('error',lang('account_pending'));
            $IsActive = "No";
        }elseif ($query->num_rows() > 0 && $query->row()->user_status == 'Blocked') {
            $this->session->set_flashdata('error',lang('account_blocked'));
            $IsActive = "No";
        }elseif($query->num_rows() > 0 && (strtotime(date('Y-m-d H:i:s')) - strtotime($query->row()->last_activity)) >= (SESSION_EXPIRE_HOURS * 3600)){
        	$this->session->set_flashdata('error',lang('session_disconnected'));
            $IsActive = "No";
        }

        /* Is User Active */
        if($IsActive == 'No'){

        	/* Delete Session Key */
			if(!empty($this->login_session_key)){
				$this->Users_model->delete_session($this->login_session_key);
			}
			
        	$this->session->unset_userdata('userdata');
        	$this->session->set_flashdata('logout','Yes');
        	redirect(base_url().'admin/login'); exit;
        }

        /* Update Last Activity */
        $this->Users_model->update_last_activity($this->session_user_id);
	}
	
}

/* Web Control */

class Web_Main_Controller extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$Input = file_get_contents("php://input");
			$this->Post =  json_decode($Input,1);
			if(empty($this->Post)){
				parse_str($Input,$this->Post);
			}
		}	
	}
}

class Web_Controller extends Web_Main_Controller {
	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('webuserdata') && $this->router->fetch_method() != 'logout') {
			if($this->session->userdata('webuserdata')['user_type_id'] == 4){
				redirect(base_url().'customer/dashboard');
				exit();
			}else if($this->session->userdata('webuserdata')['user_type_id'] == 3){
				redirect(base_url().'worker/dashboard');
				exit();
			}
		}	
	}
}

class Web_Controller_Secure extends Web_Main_Controller {
	public function __construct()
	{
		parent::__construct();

		/* Ensure already Signed in */
		if (empty($this->session->userdata('webuserdata'))) {
			redirect(base_url()); exit;
		}

		$this->session_data	     =	$this->session->userdata('webuserdata'); 
		$this->login_session_key =	$this->session_data['login_session_key'];	
		$this->session_user_id	 =	$this->session_data['user_id'];
		$this->session_user_guid =	$this->session_data['user_guid'];
		$this->user_type_guid	 =	$this->session_data['user_type_guid'];
		$this->user_type_id	     =	$this->session_data['user_type_id'];

		/* To Cross Check User Login Session */
		$IsActive = "Yes";
		$query = $this->db->query('SELECT user_status,last_activity FROM tbl_users WHERE login_session_key  = "'.$this->login_session_key.'" LIMIT 1');
		if($query->num_rows() == 0){
			$this->session->set_flashdata('error',lang('session_disconnected'));
			$IsActive = "No";
		}elseif ($query->num_rows() > 0 && $query->row()->user_status == 'Pending') {
            $this->session->set_flashdata('error',lang('account_pending'));
            $IsActive = "No";
        }elseif ($query->num_rows() > 0 && $query->row()->user_status == 'Blocked') {
            $this->session->set_flashdata('error',lang('account_blocked'));
            $IsActive = "No";
        }elseif($query->num_rows() > 0 && (strtotime(date('Y-m-d H:i:s')) - strtotime($query->row()->last_activity)) >= (SESSION_EXPIRE_HOURS * 3600)){
        	$this->session->set_flashdata('error',lang('session_disconnected'));
            $IsActive = "No";
        }

        /* Is User Active */
        if($IsActive == 'No'){

        	/* Delete Session Key */
			if(!empty($this->login_session_key)){
				$this->Users_model->delete_session($this->login_session_key);
			}
			
        	$this->session->unset_userdata('webuserdata');
        	$this->session->set_flashdata('logout','Yes');
        	redirect(base_url()); exit;
        }

        /* Update Last Activity */
        $this->Users_model->update_last_activity($this->session_user_id);
	}
	
}

include(APPPATH.'core/MY_API_Controller.php');

/* End of file MY_Controller.php */
/* Location: application/core/MY_Controller.php */


