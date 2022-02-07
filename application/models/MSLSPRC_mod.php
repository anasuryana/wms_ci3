<?php

class MSLSPRC_mod extends CI_Model {
	private $TABLENAME = "MSLSPRICE_TBL";
	public function __construct()
    {
        $this->load->database();
    }	

    public function selectAll()
	{		
		$query = $this->db->get($this->TABLENAME);
		return $query->result();
	}
	
	public function insert($data)
    {
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
	}
	public function updatebyId($pdata, $pwhere)
    {        
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
	}
	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }
    public function selectbyVAR($pwhere)
    {   
        $this->db->from($this->TABLENAME.' a');
        $this->db->join('MCUS_TBL b', ' on a.MSLSPRICE_CUSCD=b.MCUS_CUSCD');
        $this->db->where($pwhere);
        $this->db->order_by('MCUS_CUSNM ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
}