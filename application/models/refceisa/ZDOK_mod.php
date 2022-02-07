<?php

class ZDOK_imod extends CI_Model {
	private $TABLENAME = "ZDOK_TBL";
	public function __construct()
    {
        $this->load->database();
    }
	public function selectAll()
	{		       
        $this->db->select("KODE_JENIS_TPB, URAIAN_JENIS_TPB");
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
    }
    public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }
    public function insert($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }

    public function selectbyid_lk($pid)
	{		
		$this->db->like('KODE_JENIS_TPB', $pid);
		$query = $this->db->get($this->TABLENAME,10);
		return $query->result();
	}
	public function selectbynm_lk($pid)
	{		
		$this->db->like('URAIAN_JENIS_TPB', $pid);
		$query = $this->db->get($this->TABLENAME,10);
		return $query->result();
    }
    public function updatebyId($pdata, $pkey)
    {        
        $this->db->where('KODE_JENIS_TPB',$pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }
}