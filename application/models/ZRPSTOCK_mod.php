<?php

class ZRPSTOCK_mod extends CI_Model {
	private $TABLENAME = "ZRPSAL_BCSTOCK";
	public function __construct()
    {
        $this->load->database();
    }
    public function select_byTXID($pTXID) {
        $this->db->where("RPSTOCK_REMARK", $pTXID)->where("deleted_at", NULL);
        $this->db->select("RPSTOCK_BCTYPE,RPSTOCK_BCNUM,RPSTOCK_BCDATE");
        $this->db->group_by("RPSTOCK_BCTYPE,RPSTOCK_BCNUM,RPSTOCK_BCDATE");
        $this->db->order_by("RPSTOCK_BCDATE,RPSTOCK_BCNUM");
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

    public function select_all_where($pwhere){
		$this->db->from("ZRPSAL_BCSTOCK");
		$this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_all_where_imod($pwhere){
        $DBUse = $this->load->database('rpcust',TRUE);        
        $DBUse->from("RPSAL_BCSTOCK");
		$DBUse->where($pwhere);
        $query = $DBUse->get();
        return $query->result_array();
    }

    public function updatebyId($pwhere, $pval)
    {        
        $DBUse = $this->load->database('rpcust',TRUE);
        $DBUse->where($pwhere);
        $DBUse->update("RPSAL_BCSTOCK", $pval);
        return $DBUse->affected_rows();
	}

    public function query($qry){
        $DBUse = $this->load->database('rpcust',TRUE);
        $query = $DBUse->query($qry);
		return $query->result_array();
    }
}