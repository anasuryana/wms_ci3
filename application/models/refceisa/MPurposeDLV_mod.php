<?php

class MPurposeDLV_mod extends CI_Model {
	private $TABLENAME = "ZTJNKIR_TBL";
	public function __construct()
    {
        $this->load->database();
    }	

    public function selectAll()
	{		
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
    }

    public function selectlastid()
	{		
        $this->db->select("max(ID) LTSID");
		$query = $this->db->get($this->TABLENAME);
		return $query->row()->LTSID;
	}
    
    public function selectbyvar($pvar)
	{		
        $this->db->where($pvar);
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
    public function selectbyid_lk($pid)
	{		
		$this->db->like('KODE_DOKUMEN', $pid);
		$query = $this->db->get($this->TABLENAME,10);
		return $query->result();
	}
	public function selectbynm_lk($pid)
	{		
		$this->db->like('URAIAN_TUJUAN_PENGIRIMAN', $pid);
		$query = $this->db->get($this->TABLENAME,10);
		return $query->result();
    }
}