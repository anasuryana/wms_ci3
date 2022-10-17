<?php 
class WOH_mod extends CI_Model {
    private $TABLENAME = 'WOH_TBL';
    public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {        
        return $this->db->insert($this->TABLENAME,$data);
    }

    public function updatebyId($pwhere, $pval)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pval);
        return $this->db->affected_rows();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

}