<?php 
class Usrlog_mod extends CI_Model {
    private $TABLENAME = 'USRLOG_TBL';
    public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {        
        return $this->db->insert($this->TABLENAME,$data);
    }

    public function selectLastOne()
    {
        $query = $this->db->get('v_lastidlgusrplus');
        return $query->result_array();
    }

}
