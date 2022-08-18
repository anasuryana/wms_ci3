<?php

class MPROCD_mod extends CI_Model {
	private $TABLENAME = "MPROCD_TBL";	
	public function __construct()
    {
        $this->load->database();
    }

    public function deleteby_filter($pwhere)
    {
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
	}

    public function update_where($pdata, $pwhere)
    { 
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
	}

    function select_header($plike){
        $this->db->select("RTRIM(MPROCD_MDLCD) MITM_ITMCD
        ,RTRIM(MITM_ITMD1) ITMD1
        ,MAX(MPROCD_MDLTYP) MPROCD_MDLTYP");
        $this->db->join("MITM_TBL", "MITM_ITMCD=MPROCD_MDLCD");
		$this->db->where('MITM_MODEL', '1')->like($plike);
        $this->db->group_by("MPROCD_MDLCD,MITM_ITMD1");
		$this->db->order_by("MITM_ITMCD");
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
    }
}