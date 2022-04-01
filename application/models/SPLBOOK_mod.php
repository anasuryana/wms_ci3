<?php

class SPLBOOK_mod extends CI_Model {
	private $TABLENAME = "SPLBOOK_TBL";
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

    public function lastserialid($pdate){
        $qry = "select ISNULL(MAX(CONVERT(INT,SUBSTRING(SPLBOOK_DOC,7,4))),0) lser 
        from ".$this->TABLENAME." where SPLBOOK_DATE=?";
        $query =  $this->db->query($qry, [$pdate]);
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return 0;
        }
    }

    public function updatebyVars($pdata, $pwhere)
    {        
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
	}

    public function select_maxline($pdoc){
        $this->db->select("MAX(SPLBOOK_LINE) LLINE");
        $this->db->from($this->TABLENAME);
        $this->db->where("SPLBOOK_DOC", $pdoc);
		return $this->db->get()->row()->LLINE;
    }

    public function select_book_like($plike)
	{			
		$this->db->select("SPLBOOK_DOC,SPLBOOK_SPLDOC,SPLBOOK_CAT,SPLBOOK_DATE");
		$this->db->from($this->TABLENAME);
        $this->db->group_by("SPLBOOK_DOC,SPLBOOK_SPLDOC,SPLBOOK_CAT,SPLBOOK_DATE");
		$this->db->like($plike);
		$query = $this->db->get();
		return $query->result_array();
	}
    public function select_book_where($pwhere)
	{			
		$this->db->select("a.*,RTRIM(MITM_ITMD1) ITMD1");
		$this->db->from($this->TABLENAME." a");
        $this->db->join("MITM_TBL","SPLBOOK_ITMCD=MITM_ITMCD","LEFT");
		$this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
	}

    public function deleteby_filter($pwhere)
    {          
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function select_book_ost()
	{
		$this->db->from("wms_v_book_ost");
		$query = $this->db->get();
		return $query->result_array();
	}
}