<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}	
	
	/*
	Description: Use to Save POST input to DB
	*/
	function addInputLog($Response){
		if(!API_SAVE_LOG){
			return TRUE;
		}
		@$this->db->insert('log_api', array(
			'URL' 		=> current_url(),
			'RawData'	=> @file_get_contents("php://input"),
			'DataJ'		=> json_encode(array_merge(array("API" => $this->classFirstSegment = $this->uri->segment(2)), $this->Post, $_FILES)),
			'Response'	=> json_encode($Response)
		));
	}

	/*
	Description: 	Use to get Countries & States
	*/
	function get_countries($Field='', $where=array(), $multiRecords=FALSE){
		$Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'country_id'        => 'id country_id',
                'country_code'      => 'code country_code',
                'country_name'      => 'name country_name'
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
		$this->db->select($Field,false);
		$this->db->from('set_countries');
		if(!empty($where['id'])){
			$this->db->where("id",$where['id']);
		}
		$this->db->order_by('name','ASC');
		$Query = $this->db->get();
		if($Query->num_rows() > 0){
			foreach($Query->result_array() as $key => $Record){
				if(!$multiRecords){
					return $Record;
				}
				$Records[] = $Record;
				if (in_array('states', $Params)) {
					$states = $this->get_states($Record['country_id']);
                    $Records[$key]['states'] = (!empty($states)) ? $states : array();
                }
			}
			$Return['data']['records'] = $Records;
			return $Return;
		}
		return FALSE;		
	}

	/*
    Description:   Use to get Get Permitted Modules.
    */
    function get_states($country_id){
        $this->db->select('id state_id, name state_name');
        $this->db->from('set_states');
        $this->db->where("country_id",$country_id); 
        $this->db->order_by('name','ASC');       
        $Query = $this->db->get();  
        if($Query->num_rows()>0){
            return $Query->result_array();
        }
        return FALSE;       
    }

	/*
      Description: 	Use to get cities.
     */
    function get_cities($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 100){
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'country_id'   => 'S.country_id',
                'country_code' => 'C.code country_code',
                'country_name' => 'C.name country_name',
                'state_id'     => 'CT.state_id',
                'state_name'   => 'S.name state_name',
                'city_id'      => 'CT.id city_id',
                'city_name'    => 'CT.name city_name',
                'created_date' => 'DATE_FORMAT(CT.created_date, "' . DATE_FORMAT . '") created_date'
            );
            if ($Params) {
                foreach ($Params as $Param) {
                    $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
                }
            }
        }
        $this->db->select('CT.id city_id');
        if (!empty($Field))
            $this->db->select($Field, FALSE);
        $this->db->from('set_cities CT');
        if (!empty($Where['Keyword']) || array_keys_exist($Params, array('state_name','country_id'))) {
            $this->db->from('set_states S');
            $this->db->where("S.id", "CT.state_id", FALSE);
        }
        if (!empty($Where['Keyword']) || array_keys_exist($Params, array('country_code','country_name'))) {
            $this->db->from('set_countries C');
            $this->db->where("C.id", "S.country_id", FALSE);
        }
        if (!empty($Where['Keyword'])) {
            $Where['keyword'] = trim($Where['keyword']);
            $this->db->group_start();
            $this->db->like("C.name", $Where['keyword']);
            $this->db->or_like("S.name", $Where['keyword']);
            $this->db->or_like("CT.name", $Where['keyword']);
            $this->db->group_end();
        }
        if (!empty($Where['city_id'])) {
            $this->db->where("CT.id", $Where['city_id']);
        }
        if (!empty($Where['state_id'])) {
            $this->db->where("CT.state_id", $Where['state_id']);
        }
        if (!empty($Where['country_id'])) {
            $this->db->where("S.country_id", $Where['country_id']);
        }
        if (!empty($Where['order_by']) && !empty($Where['sequence'])) {
            $this->db->order_by($Where['order_by'], $Where['sequence']);
        }else {
            $this->db->order_by('CT.name', 'ASC');
        }

        /* Total records count only if want to get multiple records */
        if ($multiRecords) {
            $TempOBJ = clone $this->db;
            $TempQ = $TempOBJ->get();
            $Return['data']['total_records'] = $TempQ->num_rows();
            if ($PageNo != 0) {
                $this->db->limit($PageSize, paginationOffset($PageNo, $PageSize)); /* for pagination */
            }
        } else {
            $this->db->limit(1);
        }
        $Query = $this->db->get();
      
        if ($Query->num_rows() > 0) {
            if ($multiRecords) {
                $Return['data']['records'] = $Query->result_array(); 
                return $Return;
            } else {
                return $Query->row_array();
            }
        }
        return FALSE;
    }

    /*
      Description:  Use to add city.
    */
    function add_city($Input) {
        $this->db->insert('set_cities',array('name' => $Input['city_name'],'state_id' => $Input['state_id'],'created_date' => date('Y-m-d H:i:s')));
        if($this->db->insert_id()){
            return TRUE;
        }
        return FALSE;
    }

    /*
      Description:  Use to edit city.
    */
    function edit_city($Input, $city_id) {
        $this->db->where('id', $city_id);
        $this->db->limit(1);
        $this->db->update('set_cities',array('name' => $Input['city_name'], 'state_id' => $Input['state_id']));
        return TRUE;
    }

    /*
      Description: 	Use to delete city.
    */
    function delete_city($city_id) {
        $this->db->where('id', $city_id);
        $this->db->limit(1);
        $this->db->delete('set_cities');
        if($this->db->affected_rows() > 0){
        	return TRUE;
        }
        return FALSE;
    }



}


