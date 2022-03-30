<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pricelist_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

     /*
      Description:  Use to add pricelist.
     */
    function add_pricelist($Input = array()) {

        $this->db->trans_start();

        $insert_array = array_filter(array(
            "pricelist_guid" => get_guid(),
            "pricelist_name" => @ucfirst(strtolower($Input['pricelist_name'])),
            "pricelist_brand" => $Input['pricelist_brand'],
            "is_main_pricelist" => @$Input['is_main_pricelist'],
            "created_date" => date('Y-m-d H:i:s')
        ));
        $this->db->insert('tbl_pricelists', $insert_array);
        $pricelist_id = $this->db->insert_id();

        /* Add Product Variants Price */
        for ($i=0; $i < count($Input['products']); $i++) { 
         $variants_array[] = array('pricelist_id' => $pricelist_id, 'product_id' => $Input['products'][$i]['product_id'], 'product_variant_id' => $Input['products'][$i]['product_variant_id'], 'product_price' => $Input['products'][$i]['product_price']);
        }
        $this->db->insert_batch('tbl_pricelist_variants', $variants_array);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        return TRUE;
    }

    /*
      Description: 	Use to get pricelist
     */

    function get_pricelist($Field = '', $Where = array(), $multiRecords = FALSE, $PageNo = 1, $PageSize = 150) {
        
        /* Additional fields to select */
        $Params = array();
        if (!empty($Field)) {
            $Params = array_map('trim', explode(',', $Field));
            $Field = '';
            $FieldArray = array(
                'created_date'  => 'DATE_FORMAT(PL.created_date, "' . DATE_FORMAT . '") created_date',
                'pricelist_name'   => 'PL.pricelist_name',
                'pricelist_brand' => 'PL.pricelist_brand',
                'pricelist_id' => 'PL.pricelist_id',
                'is_main_pricelist' => 'PL.is_main_pricelist',
                'total_clients' => '(SELECT COUNT(*) FROM tbl_users WHERE PL.pricelist_id = tbl_users.pricelist_id) total_clients'
            );
            
            foreach ($Params as $Param) {
                $Field .= (!empty($FieldArray[$Param]) ? ',' . $FieldArray[$Param] : '');
            }
        }
        $this->db->select('PL.pricelist_guid');
        if (!empty($Field)) $this->db->select($Field, FALSE);
        $this->db->from('tbl_pricelists PL');
        if (!empty($Where['keyword'])) {
            $Where['keyword'] = trim($Where['keyword']);
            $this->db->group_start();
            $this->db->like("PL.pricelist_name", $Where['keyword']);
            $this->db->group_end();
        }
        if (!empty($Where['pricelist_id'])) {
            $this->db->where("PL.pricelist_id", $Where['pricelist_id']);
        }
        if (!empty($Where['is_main_pricelist'])) {
            $this->db->where("PL.is_main_pricelist", $Where['is_main_pricelist']);
        }
        if (!empty($Where['pricelist_brand'])) {
            $this->db->where("PL.pricelist_brand", $Where['pricelist_brand']);
        }
        if (!empty($Where['order_by']) && !empty($Where['sequence']) && in_array($Where['sequence'], array('ASC', 'DESC'))) {
            $this->db->order_by($Where['order_by'], $Where['sequence']);
        } else {
            $this->db->order_by('PL.pricelist_name', 'ASC');
        }

        /* Total records count only if want to get multiple records */
        if ($multiRecords) {
            $TempOBJ = clone $this->db;
            $TempQ = $TempOBJ->get();
            $Return['data']['total_records'] = $TempQ->num_rows();
            $this->db->limit($PageSize, paginationOffset($PageNo, $PageSize)); /* for pagination */
        } else {
            $this->db->limit(1);
        }

        $Query = $this->db->get();
        if ($Query->num_rows() > 0) {
            foreach ($Query->result_array() as $Record) {
                if (!$multiRecords) {
                    return $Record;
                }
                $Records[] = $Record;
            }

            $Return['data']['records'] = $Records;
            return $Return;
        }
        return FALSE;
    }

    /*
      Description:  Use to update pricelist
     */
    function update_pricelist($pricelist_id, $Input = array()) { 

        $this->db->trans_start();

        $update_array = array_filter(array(
            "pricelist_name" => @ucfirst(strtolower($Input['pricelist_name'])),
            "pricelist_brand" => $Input['pricelist_brand'],
            "is_main_pricelist" => @$Input['is_main_pricelist'],
            "modified_date" => date('Y-m-d H:i:s')
        ));
        $this->db->where('pricelist_id', $pricelist_id);
        $this->db->limit(1);
        $this->db->update('tbl_pricelists', $update_array);

        /* Delete All Old Varinats */
        $this->db->where('pricelist_id',$pricelist_id);
        $this->db->delete('tbl_pricelist_variants');

        /* Add Product Variants Price */
        for ($i=0; $i < count($Input['products']); $i++) { 
         $variants_array[] = array('pricelist_id' => $pricelist_id, 'product_id' => $Input['products'][$i]['product_id'], 'product_variant_id' => $Input['products'][$i]['product_variant_id'], 'product_price' => $Input['products'][$i]['product_price']);
        }
        $this->db->insert_batch('tbl_pricelist_variants', $variants_array);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        return TRUE;
    }

    /*
      Description:  Use to delete pricelist.
    */
    function delete_pricelist($pricelist_guid) {
        $this->db->where('pricelist_guid',$pricelist_guid);
        $this->db->limit(1);
        $this->db->delete('tbl_pricelists');
        if($this->db->affected_rows() > 0){
            return TRUE;
        }
        return FALSE;
    }


}
