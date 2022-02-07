<?php

class SCRSCN_mod extends CI_Model {
	private $TABLENAME = "SCRSCN_TBL";
	public function __construct()
    {
        $this->load->database();
    }

	public function insert($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
    public function insertb($data)
    {
        $this->db->insert_batch($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
	}
	
	public function updatebyId($pdata, $pkeys)
    {        
        $this->db->where($pkeys);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }
    
    public function lastserialid(){       
        $qry = "select TOP 1 substring(SCRSCN_ID, 9, 20) lser from SCRSCN_TBL 
        WHERE convert(date, SCRSCN_LUPDT) = convert(date,GETDATE())
        ORDER BY convert(bigint,SUBSTRING(SCRSCN_ID,9,20)) desc";
        $query =  $this->db->query($qry);
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function selectby_filter($pwhere){
        $this->db->from($this->TABLENAME);        
        $this->db->where($pwhere);
        $this->db->order_by('SCRSCN_LUPDT DESC');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function deleteby_filter($pwhere)
    {          
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }
}