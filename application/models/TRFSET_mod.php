<?php

class TRFSET_mod extends CI_Model
{
    private $TABLENAME = "TRFSET_TBL";
    public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {
        $this->db->insert($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }

    public function selectDetailWhere($where)
    {
        $this->db->from($this->TABLENAME);
        $this->db->join("MSTEMP_TBL A", "TRFSET_APPROVER=MSTEMP_ID", "LEFT");
        $this->db->where($where)->where("TRFSET_DELETED_DT is null", null, false);
        $this->db->select("MSTEMP_ID,CONCAT(MSTEMP_FNM, ' ', MSTEMP_LNM) APPROVER,TRFSET_LINE");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectColumnWhere($columns, $where)
    {
        $this->db->from($this->TABLENAME);
        $this->db->join("MSTLOCG_TBL", "TRFSET_WH=MSTLOCG_ID", "LEFT");
        $this->db->where($where)->where("TRFSET_DELETED_DT is null", null, false);
        $this->db->select($columns);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function updatebyId($pdata, $where)
    {
        $this->db->where($where);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function selectApproverByNIK($nik){
        $qry = "wms_sp_select_approver_by_nik ?";
        $query = $this->db->query($qry, [$nik]);
        return $query->result_array();
    }
}
