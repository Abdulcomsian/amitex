<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends API_Controller_Secure {

    function __construct() {
        parent::__construct();
    }

    /*
      Description:  To get cities
      URL:          /admin/api/common/get_cities/
    */
    public function get_cities_post() {

        /* Validation section */
        $this->form_validation->set_rules('state_id', 'State ID', 'trim|required|callback_check_value_exist[set_states.id]');
        $this->form_validation->validation($this);  /* Run validation */
        /* Validation - ends */

        /* To Get Cities */
        $cities = $this->Common_model->get_cities('city_id,city_name',array('state_id' => $this->Post['state_id']),TRUE);
        if($cities){
            $this->Return['data'] = $cities['data'];
        }
    }



}