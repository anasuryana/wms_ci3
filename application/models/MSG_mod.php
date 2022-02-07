<?php
class MSG_mod extends CI_Model {
	private $TABLENAME = "MSG_TBL";
	public function __construct()
    {
        $this->load->database();
    }

    public function insertb($data)
    {
        $this->db->insert_batch($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }

    public function updatebyId($pwhere, $pval)
    {        
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pval);
        return $this->db->affected_rows();
	}

    public function updateReadyId($pwhere, $pval)
    {        
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pval);
        return $this->db->affected_rows();
	}

    function select_total_unread_byuser($puserid){
        $this->db->select("COUNT(*) TTLMSG");
        $this->db->from($this->TABLENAME);
        $this->db->where('MSG_READAT is null', null, false)->where("MSG_TO", $puserid);
		$query = $this->db->get();
		return $query->result_array();
    }

    function select_byuser($puserid, $pyear, $pmonth){
        $this->db->select("MSG_TXT,MSG_REFFDATA,MSG_LINE,MSG_CREATEDAT,MSG_FR,MSG_READAT,MSG_TOPIC,MSG_DOC");
        $this->db->from($this->TABLENAME);
        $this->db->join("MSTEMP_TBL A", "MSG_FR=A.MSTEMP_ID", "LEFT");        
        $this->db->where("MSG_TO", $puserid)->where("MONTH(MSG_CREATEDAT)", $pmonth)->where("YEAR(MSG_CREATEDAT)", $pyear);
        $this->db->order_by("MSG_CREATEDAT DESC");
		$query = $this->db->get();
		return $query->result_array();
    }
}