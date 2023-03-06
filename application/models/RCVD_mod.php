<?php

class RCVD_mod extends CI_Model
{
    private $TABLENAME = "RCVD_TBL";
    public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {
        $this->db->insert($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }

    public function insertb($data)
    {
        $this->db->insert_batch($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }

    public function deletebyID($parr)
    {
        $this->db->where($parr);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }

    public function updatebyId($pwhere, $pval)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pval);
        return $this->db->affected_rows();
    }

    public function selectColumnWhere($column, $where)
    {
        $this->db->select($column);
        $this->db->from($this->TABLENAME);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }
}
