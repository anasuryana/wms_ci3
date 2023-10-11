<?php

class ZRPSCRAP_HIST_mod extends CI_Model
{
    private $TABLENAME = "ZRPSCRAP_HIST";
    public function __construct()
    {
        $this->load->database();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }

    public function select_all_where($pwhere)
    {
        $this->db->from($this->TABLENAME);
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_header($plike)
    {
        $this->db->select("ID_TRANS");
        $this->db->from($this->TABLENAME);
        $this->db->like($plike);
        $this->db->group_by("ID_TRANS");
        $this->db->order_by("ID_TRANS");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectByRefNo($strRefNo)
    {
        $qry = "SELECT ID_TRANS,REF_NO,DOC_NO,ITEMNUM FROM ZRPSCRAP_HIST WHERE REF_NO IN ($strRefNo)
        GROUP BY ID_TRANS,REF_NO,DOC_NO,ITEMNUM";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
}
