<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class API_Controller extends REST_Controller {
    public $Return = array('status' => 200, 'message' => 'success', 'data' => array());
    public function __construct() {
        parent::__construct();
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->Post = $_POST = $this->post();
            // $this->form_validation->validation($this);            
        }

        /* Load Language */
        load_lang();
    }
    
    
    function __destruct() {
        parent::__destruct();
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if(empty($this->Return['data'])){
                exit;
            }
        }
        if(empty($this->Return['data'])){
            $this->Return['data'] = new stdClass();
        }
        if($this->router->fetch_method() != 'upload_product_gallery_image'){
            $this->Common_model->addInputLog($this->Return);
            if(empty($this->Post['data_type']) && $this->Post['data_type'] != 'html'){
                $this->response($this->Return, (($this->Return['status'] == 502) ? 502 : ($this->Return['status'] == 403 ? 403 : 200)));
            }
        }
    }
    
    
    function validate_session($session_key) {
        if (empty($session_key)) {
            return TRUE;
        }

        /* To Cross Check User Login Session */
        $query = $this->db->query('SELECT user_id,user_guid,user_status,last_activity FROM tbl_users WHERE login_session_key  = "'.$session_key.'" LIMIT 1');
        if ($query->num_rows() == 0) {
            $this->Return['status'] = 502;
            $this->form_validation->set_message('validate_session', lang('session_disconnected'));
            return FALSE;
        }elseif ($query->num_rows() > 0 && $query->row()->user_status == 'Pending') {
            $this->Return['status'] = 502;
            $this->form_validation->set_message('validate_session', lang('account_pending'));
            return FALSE;
        }elseif ($query->num_rows() > 0 && $query->row()->user_status == 'Blocked') { 
            $this->Return['status'] = 502;
            $this->form_validation->set_message('validate_session', lang('account_blocked'));
            return FALSE;
        }elseif($query->num_rows() > 0 && (strtotime(date('Y-m-d H:i:s')) - strtotime($query->row()->last_activity)) >= (SESSION_EXPIRE_HOURS * 3600)){
            $this->Return['status'] = 502;
            $this->form_validation->set_message('validate_session', lang('session_disconnected'));
            return FALSE;
        }
        $this->session_user_guid = $query->row()->user_guid;
        $this->session_user_id   = $query->row()->user_id;

        /* Update Last Activity */
        $this->Users_model->update_last_activity($this->session_user_id);
        return TRUE;
    }

    /**
     * Function Name: validate_email  
     * Description:   To validate email
     */
    public function validate_email($email) {
        $Query = $this->db->query('SELECT user_id FROM `tbl_users` WHERE email = "'.$this->Post['email'].'" LIMIT 1');
        if ($Query->num_rows() > 0 && $Query->row()->user_id != $this->user_id) {
            $this->form_validation->set_message('validate_email', '{field} '.lang('field_already_exist'));
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Function Name: validate_phone  
     * Description:   To validate phone
     */
    public function validate_phone($phone_number) {
        $Query = $this->db->query('SELECT user_id FROM `tbl_users` WHERE phone_number = "'.$this->Post['phone_number'].'" LIMIT 1');
        if ($Query->num_rows() > 0 && $Query->row()->user_id != $this->user_id) {
            $this->form_validation->set_message('validate_phone', '{field} '.lang('field_already_exist'));
            return FALSE;
        }
        return TRUE;
    }
    
    function validateDate($Date) {
        if ($Date == '') {
            return TRUE;
        }
        if (!strtotime($Date)) {
            $this->form_validation->set_message('validateDate', 'Invalid {field}.');
            return FALSE;
        }
        return TRUE;
    }
    
    
    function validateIP($IPAddress) {
        if ($this->input->valid_ip($IPAddress) || empty($IPAddress)) {
            return TRUE;
        }
        $this->form_validation->set_message('validateIP', 'Invalid {field}.');
        return FALSE;
    }

    
    
    function validateArray($Array) {
        if ($Array == '') {
            return TRUE;
        }
        if (!is_array($Array)) {
            $this->form_validation->set_message('validateArray', 'Invalid format {field}.');
            return FALSE;
        }
        return TRUE;
    }
    
    function validateImage() {
        if (isset($_FILES['File']['name']) && $_FILES['File']['name'] != "") {
            if (in_array(get_mime_by_extension($_FILES['File']['name']), array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png'))) {
                return TRUE;
            }
            $this->form_validation->set_message('validateImage', 'Please select only jpg/png File.');
            return TRUE;
        } else {
            $this->form_validation->set_message('validateImage', 'Please choose a File to upload.');
            return FALSE;
        }
    }
    
    
    function validatePageNo($PageNo) {
        $this->PageNo = (empty($PageNo)? '1':$PageNo);
        return TRUE;
    }
    
    
    function validatePageSize($PageSize) {
        $this->PageSize = (empty($PageSize)? PAGESIZE_DEFAULT:$PageSize);
        return TRUE;
    }

    /**
     * Function Name: check_value_exist
     * Description:   To check values exist or not into database
     */
    function check_value_exist($str,$field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        $query = $this->db->limit(1)->get_where($table, array($field => $str));
        if($query->num_rows() == 0){
            $this->form_validation->set_message('check_value_exist', 'Invalid {field}');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Function Name: validate_guid
     * Description:   To validate guid
     */
    function validate_guid($str,$field)
    {
        sscanf($field, '%[^.].%[^.].%[^.]', $table, $field,$return_field);
        $return_field_arr = explode(",", $return_field);
        $query = $this->db->limit(1)->select($return_field_arr[0])->get_where($table, array($field => $str));
        if($query->num_rows() == 0){
            $this->form_validation->set_message('validate_guid', 'Invalid {field}');
            return FALSE;
        }
        if(!empty($return_field_arr[1])){
          $this->{$return_field_arr[1]} = $query->row()->{$return_field_arr[0]};
        }else{
          $this->$return_field = $query->row()->{$return_field_arr[0]};
        }
        return TRUE;
    }
}


class API_Controller_Secure extends API_Controller {
    public function __construct() {
        parent::__construct();
        $Headers = $this->input->request_headers();
        if(!empty($Headers['Authorization'])){
            $_POST['Authorization'] = $Headers['Authorization'];
        }
        $this->form_validation->set_rules('Authorization', 'Auth Token', 'trim|required|callback_validate_session');
        $this->form_validation->validation($this);
        $this->session_data =  $this->session->userdata('userdata'); 
        $this->user_type_id =  $this->session_data['user_type_id'];

        /* Load Language */
        load_lang();
    }
}