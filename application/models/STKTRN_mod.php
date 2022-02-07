<?php
class STKTRN_mod extends CI_Model {	  
    private $TABLENAMERTN_D = "XVU_RTN_D";
	public function __construct()
    {
        $this->load->database();
    }			

    public function select_itemonly($pdoc){      
        $this->db->select("STKTRND2_ITMCD,MITM_ITMD1,MITM_STKUOM,SUM(STKTRND2_TRNQT) TRNQT");
        $this->db->from($this->TABLENAMERTN_D);
        $this->db->join("MITM_TBL", 'STKTRND2_ITMCD=MITM_ITMCD');
        $this->db->where("STKTRND1_DOCNO", $pdoc);
        $this->db->group_by("STKTRND2_ITMCD,MITM_ITMD1,MITM_STKUOM");
        $this->db->order_by("STKTRND2_ITMCD");
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_docno($pdocno){
        $this->db->select("STKTRND1_DOCNO,MAX(MBSG_BSGRP) BSGRP");
        $this->db->from("XVU_RTN");
        $this->db->where_in("STKTRND1_DOCNO", $pdocno);
        $this->db->group_by("STKTRND1_DOCNO");
		$query = $this->db->get();
		return $query->result_array();
    }
}