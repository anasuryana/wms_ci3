<?php

class SWMP_model extends CI_Model
{
    private $TABLENAME = "WMS_SWMP_HIS";

    public function __construct()
    {
        $this->load->database();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }
}