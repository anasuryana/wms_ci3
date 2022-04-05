<?php

class ITMLOC_mod extends CI_Model {
	private $TABLENAME = "ITMLOC_TBL";
	public function __construct()
    {
        $this->load->database();
    }	
	
	public function insert($data)
    {
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
	}
	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }
    public function deletebyID($parr){        
        $this->db->where($parr);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }
    public function selectbyitemcd($pcd){
        $this->db->select("ITMLOC_LOC,MSTLOCG_NM,MSTLOC_CD,MSTLOC_GRP,ITMLOC_BG");
        $this->db->from($this->TABLENAME." a");
        $this->db->join('MSTLOC_TBL b', 'a.ITMLOC_LOC=b.MSTLOC_CD AND a.ITMLOC_LOCG=b.MSTLOC_GRP');
        $this->db->join('MSTLOCG_TBL c', 'b.MSTLOC_GRP=c.MSTLOCG_ID');
        $this->db->where('ITMLOC_ITM', $pcd);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectbyitemcdin($pcd){
        $this->db->select("RTRIM(ITMLOC_ITM) ITMLOC_ITM,ITMLOC_LOC,MSTLOCG_NM,MSTLOC_CD,MSTLOC_GRP");
        $this->db->from($this->TABLENAME." a");
        $this->db->join('MSTLOC_TBL b', 'a.ITMLOC_LOC=b.MSTLOC_CD AND a.ITMLOC_LOCG=b.MSTLOC_GRP');
        $this->db->join('MSTLOCG_TBL c', 'b.MSTLOC_GRP=c.MSTLOCG_ID');
        $this->db->where_in('ITMLOC_ITM', $pcd);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_item_WithoutPSN($PWHERE){
        $this->db->select("RTRIM(MITM_ITMCD) MITM_ITMCD,RTRIM(MITM_ITMD1) ITMD1,ITMLOC_LOC,RTRIM(MITM_SPTNO) SPTNO");
        $this->db->from($this->TABLENAME." a");
        $this->db->join('MITM_TBL b', 'a.ITMLOC_ITM=b.MITM_ITMCD');
        $this->db->where($PWHERE);
        $query = $this->db->get();
        return $query->result_array();
    }

}