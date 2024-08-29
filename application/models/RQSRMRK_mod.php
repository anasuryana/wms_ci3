<?php

class RQSRMRK_mod extends CI_Model
{
    private $TABLENAME = "RQSRMRK_TBL";
    public function __construct()
    {
        $this->load->database();
    }

    public function selectAll()
    {
        $this->db->from($this->TABLENAME);
        $this->db->order_by('1');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectAllActive()
    {
        $this->db->from($this->TABLENAME);
        $this->db->where("deleted_at is null", null, false);
        $this->db->order_by('1');
        $query = $this->db->get();
        return $query->result_array();
    }
}
