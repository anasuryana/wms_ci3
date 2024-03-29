<?php

class MDEPT_mod extends CI_Model {
	private $TABLENAME = "MDEPT_TBL";	
	public function __construct()
    {
        $this->load->database();
    }
	public function insert($data){
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
    public function insertb($data)
    {        
        $this->db->insert_batch($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
    public function delete($pwhere){
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }
    public function select_all() {        
        $this->db->from($this->TABLENAME);        
		$query = $this->db->get();
		return $query->result_array();
    }
    public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }
  
    public function update_where($pdata, $pwhere)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();        
    }

}