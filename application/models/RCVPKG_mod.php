<?php

class RCVPKG_mod extends CI_Model {
	private $TABLENAME = "RCVPKG_TBL";
	public function __construct()
    {
        $this->load->database();
    }	

    public function selectAll()
	{		
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
    }  
    
    public function select_where($pcolumns,$pvar)
	{
        $this->db->select($pcolumns);
        $this->db->where($pvar);
        $this->db->join("ZREFERENSI_KEMASAN_TBL","RCVPKG_KODE_JENIS_KEMASAN=KODE_KEMASAN", "LEFT");
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

    public function deletebyID($parr){        
        $this->db->where($parr);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
	}

    public function select_maxline($pdoc, $paju){
        $this->db->select("MAX(RCVPKG_LINE) LLINE");
        $this->db->from($this->TABLENAME);
        $this->db->where("RCVPKG_DOC", $pdoc)->where("RCVPKG_AJU", $paju);
		return $this->db->get()->row()->LLINE;
    }
}