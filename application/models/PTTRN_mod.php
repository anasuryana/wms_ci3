<?php

class PTTRN_mod extends CI_Model {
	private $TABLENAME = "PTTRN_TBL";
	public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }

    public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

    public function select_total_rows()
    {        
        return $this->db->get($this->TABLENAME)->num_rows();
    }

    public function select_all_where($pwhere){        
		$this->db->from($this->TABLENAME." a");        
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }


}