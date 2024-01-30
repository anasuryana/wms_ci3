<?php

class LOGSER_mod extends CI_Model {
	private $TABLENAME = "LOGSER_TBL";
	public function __construct()
    {
        $this->load->database();
    }	

    public function insert_($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
	
	public function insert($data)
    {
        $qry = "INSERT INTO  LOGSER_TBL (LOGSER_KEYS,LOGSER_ITEM,LOGSER_QTY,LOGSER_LOT,LOGSER_JOB,LOGSER_DT,LOGSER_USRID) 
		VALUES(?,?,?,?,?,GETDATE(),?)";
		$this->db->query($qry , array($data['LOGSER_KEYS'],$data['LOGSER_ITEM'], $data['LOGSER_QTY'], $data['LOGSER_LOT'],$data['LOGSER_JOB'], $data['LOGSER_USRID']) );
        return $this->db->affected_rows();
	}
	public function insert_replace_log($data)
    {
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
	}
	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

    public function selectRelabelHistoryFromNewLabel($reffno){
        $this->db->select("LOGSER_KEYS,LOGSER_DT,LOGSER_USRID, CONCAT(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC, LOGSER_KEYS_RPLC");
		$this->db->from($this->TABLENAME." a");
		$this->db->join("MSTEMP_TBL", "LOGSER_USRID=MSTEMP_ID","LEFT");
        $this->db->like("LOGSER_KEYS", $reffno);
        $this->db->where("LOGSER_KEYS_RPLC is not null", NULL, true);
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_new_label($reffno){
        $this->db->select("LOGSER_KEYS,LOGSER_DT,LOGSER_USRID, CONCAT(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC");
		$this->db->from($this->TABLENAME." a");
		$this->db->join("MSTEMP_TBL", "LOGSER_USRID=MSTEMP_ID","LEFT");
        $this->db->where("LOGSER_KEYS_RPLC", $reffno);        
		$query = $this->db->get();
		return $query->result_array();
    }

}