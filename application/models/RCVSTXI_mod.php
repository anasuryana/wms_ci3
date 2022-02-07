<?php

class RCVSTXI_mod extends CI_Model {
	private $TABLENAME = "XSSHP_TBL";
	public function __construct()
    {
        $this->load->database();
    }	
	public function selectbyid($pid)
	{
        $this->db->from($this->TABLENAME.' a');
        $this->db->select("SSHP_DONO,SSHP_CPONO,cast(SSHP_DELDT as date) SHPDATE");
        $this->db->like('SSHP_DONO', $pid);
        $this->db->group_by('SSHP_DONO,SSHP_CPONO,SSHP_DELDT');
		$query = $this->db->get();
		return $query->result();
    }
    public function selectbydo($pid)
	{
        $this->db->from($this->TABLENAME.' a');
        $this->db->select("MITM_ITMCD,MITM_SPTNO,SSHP_DONO,SSHP_CPONO,cast(SSHP_DELDT as date) SHPDATE,SSHP_SHPQT,coalesce(SSHP_SLPRC,0) SSHP_SLPRC");   
        $this->db->join('MITM_TBL b', "REPLACE(a.SSHP_ITMCD,CHAR(13)+CHAR(10),'')=b.MITM_ITMCD");     
        $this->db->WHERE('SSHP_DONO', $pid);
		$query = $this->db->get();
		return $query->result();
	}
}