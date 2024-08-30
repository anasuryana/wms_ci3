<?php

class WMS_PPSN2_LOG_mod extends CI_Model
{
    private $TABLENAME = "WMS_PPSN2_LOG";
    public function __construct()
    {
        $this->load->database();
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

    public function insertb($data)
    {
        $this->db->insert_batch($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }

    public function insert($data)
    {
        $this->db->insert($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }

}
